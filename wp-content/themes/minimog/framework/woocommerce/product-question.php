<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Question {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		// Support avatars for `question` comment type.
		add_filter( 'get_avatar_comment_types', [ $this, 'add_avatar_for_question_comment_type' ] );

		add_action( 'wp_ajax_nopriv_minimog_add_comment', [ $this, 'add_comment' ] );
		add_action( 'wp_ajax_minimog_add_comment', [ $this, 'add_comment' ] );

		add_action( 'wp_ajax_nopriv_minimog_get_questions', [ $this, 'get_product_questions_via_ajax' ] );
		add_action( 'wp_ajax_minimog_get_questions', [ $this, 'get_product_questions_via_ajax' ] );

		add_filter( 'comments_template_top_level_query_args', [ $this, 'exclude_questions_from_reviews' ] );
		add_filter( 'comments_template_query_args', [ $this, 'exclude_questions_from_reviews' ] );

		add_filter( 'woocommerce_product_reviews_list_table_item_types', [ $this, 'add_question_type_filter' ] );
		add_filter( 'woocommerce_product_reviews_table_column_type_content', [
			$this,
			'update_output_column_type_content',
		], 99, 2 );
		add_filter( 'woocommerce_product_reviews_pending_count', [ $this, 'update_reviews_pending_count' ] );
	}

	/**
	 * @see \Automattic\WooCommerce\Internal\Admin\ProductReviews\ReviewsListTable::review_type_dropdown()
	 *
	 * @param $types
	 *
	 * @return mixed
	 */
	public function add_question_type_filter( $types ) {
		$types['question'] = __( 'Questions', 'minimog' );

		return $types;
	}

	/**
	 * @see \Automattic\WooCommerce\Internal\Admin\ProductReviews\ReviewsListTable::column_type()
	 *
	 * @param $output
	 * @param $item
	 *
	 * @return string
	 */
	public function update_output_column_type_content( $output, $item ) {
		return 'question' === $item->comment_type ? __( 'Question', 'minimog' ) : $output;
	}

	public function update_reviews_pending_count( $count ) {
		$count = (int) get_comments( [
			'type__in'  => [ 'review', 'comment', 'question' ],
			'status'    => '0',
			'post_type' => 'product',
			'count'     => true,
		] );

		return $count;
	}

	public function exclude_questions_from_reviews( $args ) {
		if ( empty( $args['post_id'] ) ) {
			return $args;
		}

		$post_id = intval( $args['post_id'] );

		$post_type = get_post_type( $post_id );

		// Remove question from review list.
		if ( 'product' === $post_type ) {
			$args['type__not_in'] = 'question';
		}

		return $args;
	}

	/**
	 * Make sure WP displays avatars for comments with the `question` type.
	 *
	 * @since  2.3
	 *
	 * @param  array $comment_types Comment types.
	 *
	 * @return array
	 */
	public function add_avatar_for_question_comment_type( $comment_types ) {
		return array_merge( $comment_types, array( 'question' ) );
	}

	public function get_comment_per_page() {
		$per_page = 0;
		if ( get_option( 'page_comments' ) ) {
			$per_page = (int) get_query_var( 'comments_per_page' );
			if ( 0 === $per_page ) {
				$per_page = (int) get_option( 'comments_per_page' );
			}
		}

		return apply_filters( 'minimog/product_questions/per_page', $per_page, $this );
	}

	public function get_user_current_display_name( $current_user ) {
		$displayName = trim( $current_user->display_name );
		if ( ! $displayName ) {
			$user_nicename = trim( $current_user->user_nicename );
			$displayName   = $user_nicename ? $user_nicename : trim( $current_user->user_login );
		}

		return $displayName;
	}

	public function get_comment_list_args( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'style'            => 'ol',
			'callback'         => array( $this, 'question_template' ),
			'avatar_size'      => \Minimog::COMMENT_AVATAR_SIZE,
			'echo'             => false,
			'reverse_children' => '1',
		) );

		return $args;
	}

	public function add_comment() {
		if ( ! check_ajax_referer( 'product_question', 'product_question_nonce' ) ) {
			wp_die();
		}

		$post_id            = ! empty( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$question_parent_id = ! empty( $_POST['question_parent_id'] ) ? absint( $_POST['question_parent_id'] ) : 0;
		$question           = sanitize_textarea_field( $_POST['question'] );

		$who_can_post  = \Minimog::setting( 'product_ask_question_role' );
		$who_can_reply = \Minimog::setting( 'product_reply_question_role' );
		$current_user  = wp_get_current_user();

		$can_post_qna = true;

		if ( 0 === $question_parent_id ) { // Ask a question.
			if ( 'logged_in_users' === $who_can_post && ! $current_user instanceof \WP_User ) {
				$can_post_qna = false;
			}
		} else { // Reply to question.
			switch ( $who_can_reply ) {
				case 'logged_in_users':
					if ( ! $current_user instanceof \WP_User ) {
						$can_post_qna = false;
					}
					break;
				case 'administrators':
					if ( $current_user instanceof \WP_User ) {
						$has_role = false;
						foreach ( $current_user->roles as $role ) {
							if ( 'administrator' === $role ) {
								$has_role = true;
								break;
							}
						}
						$can_post_qna = $has_role ? true : false;
					} else {
						$can_post_qna = false;
					}
					break;
			}
		}

		if ( ! $can_post_qna ) {
			wp_die( 'You are not allowed to do this' );
		}

		if ( $current_user && $current_user->ID ) {
			$user_id = $current_user->ID;
			$name    = $this->get_user_current_display_name( $current_user );
			$email   = $current_user->user_email;
		} else {
			$user_id = 0;
			$name    = sanitize_text_field( $_POST['author'] );
			$email   = sanitize_email( $_POST['email'] );
		}

		if ( empty( $question ) || empty( $name ) || empty( $email ) ) {
			wp_send_json_error( [
				'message' => esc_html__( 'Please fill out required fields.', 'minimog' ),
			] );
		}

		if ( ! is_email( $email ) ) {
			wp_send_json_error( [
				'message' => esc_html__( 'Please fill valid email address.', 'minimog' ),
			] );
		}

		$website_url = ! empty( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';
		$user_agent  = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';

		$data = array(
			'user_id'              => $user_id,
			'comment_post_ID'      => $post_id,
			'comment_author'       => $name,
			'comment_author_email' => $email,
			'comment_content'      => $question,
			'comment_author_url'   => $website_url,
			'comment_agent'        => $user_agent,
			'comment_type'         => 'question',
		);

		// Set type of reply comment ( children comments ) is comment instead of question.
		if ( $question_parent_id ) {
			$parent_comment         = get_comment( $question_parent_id );
			$parent_comment_id      = isset( $parent_comment->comment_ID ) ? $parent_comment->comment_ID : 0;
			$data['comment_parent'] = $parent_comment_id;
			$data['comment_type']   = 'comment';
		}

		$new_comment_id = wp_new_comment( wp_slash( $data ) );
		$new_comment    = get_comment( $new_comment_id );

		$list_comment_args = $this->get_comment_list_args();

		$response = [
			'single_text' => esc_html__( 'Question', 'minimog' ),
			'plural_text' => esc_html__( 'Questions', 'minimog' ),
			'message'     => esc_html__( 'Thanks for asking. We will get back to you as soon as possible.', 'minimog' ),
			'response'    => wp_list_comments( $list_comment_args, array( $new_comment ) ),
		];

		wp_send_json_success( $response );
	}

	public function get_top_questions( $args = array() ) {
		$comment_order = get_option( 'comment_order' );
		$comment_page  = get_option( 'default_comments_page' );

		$order = 'ASC';

		if ( 'newest' === $comment_page ) {
			$order = 'DESC';

			if ( 'desc' === $comment_order ) {
				$order = 'ASC';
			}
		}

		$defaults = [
			'type'         => 'question',
			'post_id'      => '',
			'status'       => 'approve',
			'hierarchical' => false,
			'search'       => '', // Filter keyword by author, content, email, url..
			'order'        => $order,
			'orderby'      => 'comment_date_gmt',
		];

		$args = wp_parse_args( $args, $defaults );

		return get_comments( $args );
	}

	private function get_questions( $args = array() ) {
		$defaults = [
			'post_id'        => '',
			'comment_parent' => 0,
			'offset'         => 0,
			'limit'          => get_option( 'comments_per_page' ),
			'search_term'    => '',
			'order'          => 'newest',
		];

		$args = wp_parse_args( $args, $defaults );

		$offset = intval( $args['offset'] );
		$limit  = intval( $args['limit'] );

		global $wpdb;
		$query = array(
			'select'   => "SELECT comment.*",
			'from'     => "FROM {$wpdb->comments} AS comment",
			'join'     => "INNER JOIN {$wpdb->posts} AS post ON comment.comment_post_ID = post.ID",
			'where'    => "
				WHERE comment.comment_type = 'question' 
				AND comment.comment_approved = '1'
				AND comment.comment_post_ID = %d
				AND comment.comment_parent = %d
			",
			'order'    => '',
			'group_by' => '',
			'limit'    => "LIMIT {$offset},{$limit};",
		);

		$query_where_like = array();
		$query_variables  = array(
			$args['post_id'],
			$args['comment_parent'],
		);

		switch ( $args['order'] ) {
			case 'newest':
				$query['order'] = "ORDER BY comment.comment_date_gmt DESC";
				break;
			case 'oldest';
				$query['order'] = "ORDER BY comment.comment_date_gmt ASC";
				break;
		}

		if ( ! empty( $args['search_term'] ) ) {
			$query_where_like[] = "comment.comment_content LIKE %s";
			$query_variables[]  = '%' . $wpdb->esc_like( $args['search_term'] ) . '%';
		}

		$query_sql = implode( ' ', $query );
		$_comments = $wpdb->get_results( $wpdb->prepare( $query_sql, $query_variables ) );

		// Convert to WP_Comment instances.
		$comments = array_map( 'get_comment', $_comments );

		return $comments;
	}

	private function get_questions_html() {
		global $product;

		$comments = $this->get_questions( [
			'post_id' => $product->get_id(),
		] );

		$list_comment_args = $this->get_comment_list_args( [
			'echo' => true,
		] );

		ob_start();
		wp_list_comments( $list_comment_args, $comments );
		$comment_html = ob_get_clean();

		echo $comment_html;
	}

	public function get_product_questions_via_ajax() {
		$keyword      = ! empty( $_GET['keyword'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
		$current_page = ! empty( $_GET['current_page'] ) ? absint( $_GET['current_page'] ) : 1;
		$post_id      = ! empty( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;

		$top_questions = $this->get_top_questions( [
			'post_id' => $post_id,
			'search'  => $keyword,
		] );

		$child_questions = array();

		foreach ( $top_questions as $comment ) {
			$_children_comments = get_comments( array(
				'parent'       => $comment->comment_ID,
				'hierarchical' => true,
				'status'       => 'approve',
			) );

			foreach ( $_children_comments as $_comment ) {
				$child_questions[] = $_comment;
			}
		}

		$all_questions = array_merge( $top_questions, $child_questions );

		$comment_count = count( $top_questions );

		$list_comment_args = $this->get_comment_list_args();

		// Comment Pagination.
		$per_page    = $this->get_comment_per_page();
		$total_pages = ceil( $comment_count / $per_page );

		if ( $current_page > $total_pages ) {
			$current_page = $total_pages;
		}

		$list_comment_args['page']     = $current_page;
		$list_comment_args['per_page'] = $per_page;

		$fragments = [];

		ob_start();
		if ( get_comment_pages_count( $top_questions ) > 1 && get_option( 'page_comments' ) ) {
			?>
			<nav class="navigation question-navigation comment-navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Question navigation', 'minimog' ); ?></h2>

				<div class="comment-nav-links">
					<?php
					\Minimog_Templates::render_paginate_links( [
						'format'  => '?current_page=%#%',
						'current' => max( 1, $current_page ),
						'total'   => $total_pages,
					] );
					?>
				</div>
			</nav>
			<?php
		}
		$fragments['.question-navigation'] = ob_get_clean();

		ob_start();
		wc_get_template( 'single-product/product-question/questions.php', [
			'questions'          => $all_questions,
			'list_comments_args' => $list_comment_args,
		] );
		$fragments['#question-list'] = ob_get_clean();

		wp_send_json_success( [
			'message'   => esc_html__( 'Product question list retrieved successfully.', 'minimog' ),
			'fragments' => $fragments,
		] );
	}

	/**
	 * Question Callback
	 *
	 * @param $comment
	 * @param $args
	 * @param $depth
	 */
	public function question_template( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		wc_get_template(
			'single-product/question.php',
			array(
				'comment' => $comment,
				'args'    => $args,
				'depth'   => $depth,
			)
		);
	}

	function question_reply_and_cancel_link( $args, $comment ) {
		$defaults = array(
			'max_depth' => 0,
			'depth'     => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		if ( 0 == $args['depth'] || $args['max_depth'] <= $args['depth'] ) {
			return;
		}

		$enable = apply_filters( 'minimog/enable_question_reply_link', true );
		if ( ! $enable ) {
			return;
		}

		$comment = get_comment( $comment );

		if ( empty( $comment ) ) {
			return;
		}

		if ( empty( $post ) ) {
			$post = $comment->comment_post_ID;
		}

		$post = get_post( $post );

		if ( ! comments_open( $post->ID ) ) {
			return;
		}

		echo '<div class="reply-action">';

		echo sprintf(
			'<a href="%s" class="question-reply question-reply--%s" data-post-id="%s" data-question-id="%s">%s</a>',
			add_query_arg( array(
				'reply_to_question' => $comment->comment_ID,
			), get_permalink( $comment->comment_post_ID ) ),
			esc_attr( $comment->comment_ID ),
			esc_attr( $comment->comment_post_ID ),
			esc_attr( $comment->comment_ID ),
			esc_html__( 'Reply', 'minimog' )
		);

		echo sprintf(
			'<a href="#" class="cancel-reply cancel-reply--%s" data-question-id="%s">%s</a>',
			esc_attr( $comment->comment_ID ),
			esc_attr( $comment->comment_ID ),
			esc_html__( 'Cancel Reply', 'minimog' )
		);

		echo '</div>';
	}

	/**
	 * @return bool Check whether current user can reply to a question.
	 */
	public function current_user_can_reply_question() {
		$can_reply     = true;
		$who_can_reply = \Minimog::setting( 'product_reply_question_role' );
		$current_user  = wp_get_current_user();

		if ( in_array( $who_can_reply, [ 'logged_in_users', 'administrators' ], true ) ) {
			if ( ! $current_user instanceof \WP_User || empty( $current_user->ID ) ) {
				return false;
			}

			switch ( $who_can_reply ) {
				case 'logged_in_users' :
					return true;
				case 'administrators':
					foreach ( $current_user->roles as $role ) {
						if ( 'administrator' === $role ) {
							return true;
						}
					}

					return false;
			}
		}

		return $can_reply;
	}

	public function current_user_can_post_question() {
		$can_post     = true;
		$who_can_post = \Minimog::setting( 'product_ask_question_role' );
		$current_user = wp_get_current_user();

		if ( in_array( $who_can_post, [ 'logged_in_users' ], true ) ) {
			if ( ! $current_user instanceof \WP_User || empty( $current_user->ID ) ) {
				return false;
			}

			return true;
		}

		return $can_post;
	}


	/**
	 * Question Form
	 */
	public function question_form() {
		wc_get_template( 'product-question-form.php' );
	}
}

Product_Question::instance()->initialize();
