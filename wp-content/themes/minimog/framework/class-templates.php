<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom template tags for this theme.
 */
class Minimog_Templates {

	public static function pre_loader() {
		if ( Minimog::setting( 'pre_loader_enable' ) !== '1' ) {
			return;
		}

		// Don't render template in Elementor editor mode.
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return;
		}

		$style = Minimog::setting( 'pre_loader_style' );
		?>
		<div id="page-preloader" class="page-loading clearfix">
			<?php minimog_load_template( 'preloader/style', $style ); ?>
		</div>
		<?php
	}

	public static function slider( $template_position ) {
		$slider          = Minimog_Global::instance()->get_slider_alias();
		$slider_position = Minimog_Global::instance()->get_slider_position();

		if ( ! function_exists( 'rev_slider_shortcode' ) || $slider === '' || $slider_position !== $template_position ) {
			return;
		}

		?>
		<div id="page-slider" class="page-slider">
			<?php echo do_shortcode( '[rev_slider ' . $slider . ']' ); ?>
		</div>
		<?php
	}

	public static function paging_nav( $query = false ) {
		global $wp_query, $wp_rewrite;
		if ( $query === false ) {
			$query = $wp_query;
		}

		// Don't print empty markup if there's only one page.
		if ( $query->max_num_pages < 2 ) {
			return;
		}

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		$page_num_link = html_entity_decode( get_pagenum_link() );
		$query_args    = array();
		$url_parts     = explode( '?', $page_num_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$page_num_link = esc_url( remove_query_arg( array_keys( $query_args ), $page_num_link ) );
		$page_num_link = trailingslashit( $page_num_link ) . '%_%';

		$format = '';
		if ( $wp_rewrite->using_index_permalinks() && ! strpos( $page_num_link, 'index.php' ) ) {
			$format = 'index.php/';
		}
		if ( $wp_rewrite->using_permalinks() ) {
			$format .= user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' );
		} else {
			$format .= '?paged=%#%';
		}

		// Set up paginated links.

		$args  = array(
			'base'      => $page_num_link,
			'format'    => $format,
			'total'     => $query->max_num_pages,
			'current'   => max( 1, $paged ),
			'mid_size'  => 1,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => self::get_pagination_prev_text(),
			'next_text' => self::get_pagination_next_text(),
			'type'      => 'array',
		);
		$pages = paginate_links( $args );

		if ( is_array( $pages ) ) {
			echo '<ul class="page-pagination">';
			foreach ( $pages as $page ) {
				printf( '<li>%s</li>', $page );
			}
			echo '</ul>';
		}
	}

	public static function render_paginate_links( $args = array() ) {
		$defaults = array(
			'prev_text' => self::get_pagination_prev_text(),
			'next_text' => self::get_pagination_next_text(),
			'type'      => 'array',
			'end_size'  => 2,
			'mid_size'  => 2,
		);

		$args = wp_parse_args( $args, $defaults );

		$pages = paginate_links( $args );

		if ( is_array( $pages ) ) {
			echo '<ul class="page-pagination">';
			foreach ( $pages as $page ) {
				printf( '<li>%s</li>', $page );
			}
			echo '</ul>';
		}
	}

	public static function get_pagination_prev_text() {
		return '<span class="far fa-angle-double-left"></span>';
	}

	public static function get_pagination_next_text() {
		return '<span class="far fa-angle-double-right"></span>';
	}

	public static function page_links() {
		wp_link_pages( array(
			'before'           => '<div class="page-links">',
			'after'            => '</div>',
			'link_before'      => '<span>',
			'link_after'       => '</span>',
			'nextpagelink'     => esc_html__( 'Next', 'minimog' ),
			'previouspagelink' => esc_html__( 'Prev', 'minimog' ),
		) );
	}

	public static function comment_navigation( $args = array() ) {
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
			$defaults = array(
				'container_id'    => '',
				'container_class' => 'navigation comment-navigation',
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<nav id="<?php echo esc_attr( $args['container_id'] ); ?>"
			     class="<?php echo esc_attr( $args['container_class'] ); ?>">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'minimog' ); ?></h2>

				<div class="comment-nav-links">
					<?php paginate_comments_links( array(
						'prev_text' => esc_html__( 'Prev', 'minimog' ),
						'next_text' => esc_html__( 'Next', 'minimog' ),
						'type'      => 'list',
					) ); ?>
				</div>
			</nav>
			<?php
		}
		?>
		<?php
	}

	public static function comment_template( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-wrap">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
			</div>
			<div class="comment-content">
				<div class="meta">
					<?php
					printf( '<h6 class="fn">%s</h6>', get_comment_author_link() );
					printf( '<cite>' . esc_html__( '%s at %s', 'minimog' ) . '</cite>', get_comment_date(), get_comment_time() );
					?>
				</div>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-messages"><?php esc_html_e( 'Your comment is awaiting moderation.', 'minimog' ) ?></em>
					<br/>
				<?php endif; ?>
				<div class="comment-text"><?php comment_text(); ?></div>

				<div class="comment-footer">
					<div class="comment-actions">
						<?php comment_reply_link( array_merge( $args, array(
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => esc_html__( 'Reply', 'minimog' ),
						) ) ); ?>
						<?php edit_comment_link( '' . esc_html__( 'Edit', 'minimog' ) ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function comment_form() {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = '';
		if ( $req ) {
			$aria_req = " aria-required='true'";
		}

		$fields = array(
			'author' => '<div class="row"><div class="col-sm-4 comment-form-author"><input id="author" placeholder="' . esc_attr__( 'Name *', 'minimog' ) . '" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" ' . $aria_req . '/></div>',
			'email'  => '<div class="col-sm-4 comment-form-email"><input id="email" placeholder="' . esc_attr__( 'Email *', 'minimog' ) . '" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" ' . $aria_req . '/></div>',
			'url'    => '<div class="col-sm-4 comment-form-url"><input id="url" placeholder="' . esc_attr__( 'Website', 'minimog' ) . '" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" ' . $aria_req . '/></div></div>',
		);

		$comment_field = '<div class="row"><div class="col-md-12 comment-form-comment"><textarea id="comment" placeholder="' . esc_attr__( 'Comment', 'minimog' ) . '" name="comment" aria-required="true"></textarea></div></div>';

		$comments_args = array(
			'label_submit'        => esc_html__( 'Submit', 'minimog' ),
			'title_reply'         => esc_html__( 'Leave a Comment', 'minimog' ),
			'comment_notes_after' => '',
			'fields'              => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field'       => $comment_field,
		);
		comment_form( $comments_args );
	}

	public static function post_author() {
		?>
		<div class="entry-author">
			<div class="author-info">
				<div class="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'email' ), '100' ); ?>

					<?php self::get_author_socials(); ?>
				</div>
				<div class="author-description">
					<h5 class="author-name"><?php the_author(); ?></h5>

					<div class="author-biographical-info">
						<?php the_author_meta( 'description' ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function get_author_socials( $user_id = false ) {
		$email_address = get_the_author_meta( 'email_address', $user_id );
		$facebook      = get_the_author_meta( 'facebook', $user_id );
		$twitter       = get_the_author_meta( 'twitter', $user_id );
		$instagram     = get_the_author_meta( 'instagram', $user_id );
		$linkedin      = get_the_author_meta( 'linkedin', $user_id );
		$pinterest     = get_the_author_meta( 'pinterest', $user_id );
		$youtube       = get_the_author_meta( 'youtube', $user_id );

		$link_classes = 'hint--bounce hint--top';
		?>
		<?php if ( $facebook || $twitter || $instagram || $linkedin || $email_address ) : ?>
			<div class="author-social-networks">
				<div class="inner">
					<?php if ( $twitter ) : ?>
						<a class="<?php echo esc_attr( $link_classes ); ?>"
						   aria-label="<?php esc_attr_e( 'Twitter', 'minimog' ); ?>"
						   href="<?php echo esc_url( $twitter ); ?>" target="_blank">
							<?php echo Minimog_FontAwesome_Manager::instance()->get( 'twitter' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $facebook ) : ?>
						<a class="<?php echo esc_attr( $link_classes ); ?>"
						   aria-label="<?php esc_attr_e( 'Facebook', 'minimog' ); ?>"
						   href="<?php echo esc_url( $facebook ); ?>" target="_blank">
							<?php echo Minimog_FontAwesome_Manager::instance()->get( 'facebook' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $instagram ) : ?>
						<a class="<?php echo esc_attr( $link_classes ); ?>"
						   aria-label="<?php esc_attr_e( 'Instagram', 'minimog' ); ?>"
						   href="<?php echo esc_url( $instagram ); ?>" target="_blank">
							<?php echo Minimog_FontAwesome_Manager::instance()->get( 'instagram' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $linkedin ) : ?>
						<a class="<?php echo esc_attr( $link_classes ); ?>"
						   aria-label="<?php esc_attr_e( 'Linkedin', 'minimog' ) ?>"
						   href="<?php echo esc_url( $linkedin ); ?>" target="_blank">
							<?php echo Minimog_FontAwesome_Manager::instance()->get( 'linkedin' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $pinterest ) : ?>
						<a class="<?php echo esc_attr( $link_classes ); ?>"
						   aria-label="<?php esc_attr_e( 'Pinterest', 'minimog' ); ?>"
						   href="<?php echo esc_url( $pinterest ); ?>" target="_blank">
							<?php echo Minimog_FontAwesome_Manager::instance()->get( 'pinterest' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $youtube ) : ?>
						<a class="<?php echo esc_attr( $link_classes ); ?>"
						   aria-label="<?php esc_attr_e( 'Youtube', 'minimog' ); ?>"
						   href="<?php echo esc_url( $youtube ); ?>" target="_blank">
							<?php echo Minimog_FontAwesome_Manager::instance()->get( 'youtube' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $email_address ) : ?>
						<a class="<?php echo esc_attr( $link_classes ); ?>"
						   aria-label="<?php esc_attr_e( 'Email', 'minimog' ); ?>"
						   href="mailto:<?php echo esc_url( $email_address ); ?>" target="_blank">
							<?php echo Minimog_FontAwesome_Manager::instance()->get( 'email' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif;
	}

	public static function get_author_meta_phone_number_template( $user_id = false ) {
		$phone_number = self::get_author_meta_phone_number( $user_id );

		if ( empty( $phone_number ) ) {
			return;
		}
		?>
		<div class="author-phone-number">
			<?php echo esc_html( $phone_number ); ?>
		</div>
		<?php
	}

	public static function get_author_meta_phone_number( $user_id = false ) {
		$phone_number = get_the_author_meta( 'phone_number', $user_id );

		return $phone_number;
	}

	public static function get_author_meta_email_template( $user_id = false ) {
		$email = self::get_author_meta_email( $user_id );

		if ( empty( $email ) ) {
			return;
		}
		?>
		<div class="author-email">
			<?php echo esc_html( $email ); ?>
		</div>
		<?php
	}

	public static function get_author_meta_email( $user_id = false ) {
		$email = get_the_author_meta( 'email', $user_id );

		return $email;
	}

	public static function get_sharing_list( $args = array() ) {
		global $post;

		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$defaults       = array(
			'style'            => 'icons',
			'target'           => '_blank',
			'tooltip_enable'   => true,
			'tooltip_skin'     => '',
			'tooltip_position' => 'top',
			'brand_color'      => false,
		);
		$args           = wp_parse_args( $args, $defaults );
		$social_sharing = Minimog::setting( 'social_sharing_item_enable' );

		$share_links = [
			'twitter'     => [
				'url'  => 'https://twitter.com/share?text={text}&amp;url={url}',
				'text' => __( 'Twitter', 'minimog' ),
			],
			'pinterest'   => [
				'url'  => 'https://www.pinterest.com/pin/create/button/?url={url}&amp;media={image}',
				'text' => __( 'Pinterest', 'minimog' ),
			],
			'facebook'    => [
				'url'  => 'https://www.facebook.com/sharer.php?u={url}',
				'text' => __( 'Facebook', 'minimog' ),
			],
			'email'       => [
				'url'  => 'mailto:?subject={title}&amp;body={url}',
				'text' => __( 'Email', 'minimog' ),
			],
			'linkedin'    => [
				'url'  => 'https://www.linkedin.com/shareArticle?mini=true&amp;url={url}&amp;title={title}&amp;summary={text}&amp;source={url}',
				'text' => __( 'Linkedin', 'minimog' ),
			],
			'vk'          => [
				'url'  => 'https://vkontakte.ru/share.php?url={url}&amp;title={title}&amp;description={text}&amp;image={image}',
				'text' => __( 'VK', 'minimog' ),
			],
			'tumblr'      => [
				'url'  => 'https://tumblr.com/share/link?url={url}',
				'text' => __( 'Tumblr', 'minimog' ),
			],
			'digg'        => [
				'url'  => 'https://digg.com/submit?url={url}',
				'text' => __( 'Digg', 'minimog' ),
			],
			'reddit'      => [
				'url'  => 'https://reddit.com/submit?url={url}&amp;title={title}',
				'text' => __( 'Reddit', 'minimog' ),
			],
			'stumbleupon' => [
				'url'  => 'https://www.stumbleupon.com/submit?url={url}',
				'text' => __( 'StumbleUpon', 'minimog' ),
			],
			'whatsapp'    => [
				'url'  => 'https://api.whatsapp.com/send?text=*{title}*%0A{text}%0A{url}',
				'text' => __( 'WhatsApp', 'minimog' ),
			],
			'telegram'    => [
				'url'  => 'https://telegram.me/share/url?url={url}&amp;text={text}',
				'text' => __( 'Telegram', 'minimog' ),
			],
			'skype'       => [
				'url'  => 'https://web.skype.com/share?url={url}',
				'text' => __( 'Skype', 'minimog' ),
			],
			'xing'        => [
				'url'  => 'https://www.xing.com/app/user?op=share&amp;url={url}',
				'text' => __( 'Xing', 'minimog' ),
			],
		];

		if ( ! empty( $social_sharing ) ) {
			$link_classes = '';

			if ( $args['tooltip_enable'] === true ) {
				$link_classes .= " hint--bounce hint--{$args['tooltip_position']} hint--{$args['tooltip_skin']}";
			}

			if ( $args['brand_color'] === true ) {
				$link_classes .= " brand-color";
			}

			$post_permalink = rawurlencode( get_permalink() );
			$post_title     = rawurlencode( get_the_title() );
			$post_title_raw = rawurlencode( $post->post_title ); // Use raw post_title to prevent special char convert to html entity. For eg chars: '
			$post_desc      = rawurlencode( get_the_excerpt() );
			$post_thumbnail = rawurlencode( get_the_post_thumbnail_url() );

			foreach ( $social_sharing as $social => $is_active ) {
				if ( empty( $is_active ) || empty( $share_links[ $social ] ) ) {
					continue;
				}

				$setting = $share_links[ $social ];

				$url = str_replace( '{url}', $post_permalink, $setting['url'] );
				$url = str_replace( '{title}', 'email' === $social ? $post_title_raw : $post_title, $url );
				$url = str_replace( '{text}', $post_desc, $url );
				$url = str_replace( '{image}', $post_thumbnail, $url );

				$icon = Minimog_FontAwesome_Manager::instance()->get( $social );
				?>
				<a class="<?php echo esc_attr( $link_classes . ' ' . $social ); ?>"
				   target="<?php echo esc_attr( $args['target'] ); ?>"
				   aria-label="<?php echo esc_attr( $setting['text'] ); ?>"
				   href="<?php echo '' . $url; ?>">
					<?php if ( 'text' === $args['style'] ) : ?>
						<span><?php echo esc_html( $setting['text'] ); ?></span>
					<?php else: ?>
						<?php if ( ! empty ( $icon ) ) : ?>
							<?php echo '<span class="icon">' . $icon . '</span>'; ?>
						<?php endif; ?>
					<?php endif; ?>
				</a>
				<?php
			}
		}
	}

	public static function social_icons( $args = array() ) {
		$defaults    = array(
			'link_classes'     => '',
			'display'          => 'icon',
			'tooltip_enable'   => true,
			'tooltip_position' => 'top',
			'tooltip_skin'     => '',
		);
		$args        = wp_parse_args( $args, $defaults );
		$social_link = Minimog_Helper::parse_redux_repeater_field_values( Minimog::setting( 'social_link' ) );

		if ( ! empty( $social_link ) ) {
			$social_link_target = Minimog::setting( 'social_link_target' );

			$args['link_classes'] .= ' social-link';
			if ( $args['tooltip_enable'] ) {
				$args['link_classes'] .= ' hint--bounce';
				$args['link_classes'] .= " hint--{$args['tooltip_position']}";

				if ( $args['tooltip_skin'] !== '' ) {
					$args['link_classes'] .= " hint--{$args['tooltip_skin']}";
				}
			}

			foreach ( $social_link as $key => $row_values ) {
				?>
				<a class="<?php echo esc_attr( $args['link_classes'] ); ?>"
					<?php if ( $args['tooltip_enable'] ) : ?>
						aria-label="<?php echo esc_attr( $row_values['tooltip'] ); ?>"
					<?php endif; ?>
                   href="<?php echo esc_url( $row_values['link_url'] ); ?>"
                   data-hover="<?php echo esc_attr( $row_values['tooltip'] ); ?>"
					<?php if ( $social_link_target === '1' ) : ?>
						target="_blank"
					<?php endif; ?>
                   rel="nofollow"
				>
					<?php if ( in_array( $args['display'], array( 'icon', 'icon_text' ), true ) ) : ?>
						<i class="social-icon <?php echo esc_attr( $row_values['icon_class'] ); ?>"></i>
					<?php endif; ?>
					<?php if ( in_array( $args['display'], array( 'text', 'icon_text' ), true ) ) : ?>
						<span class="social-text"><?php echo esc_html( $row_values['tooltip'] ); ?></span>
					<?php endif; ?>
				</a>
				<?php
			}
		}
	}

	public static function excerpt( $args = array() ) {
		echo self::get_excerpt( $args );
	}

	public static function get_excerpt( $args = array() ) {
		$defaults = array(
			'post'  => null,
			'limit' => 55,
			'after' => '&hellip;',
			'type'  => 'word',
		);
		$args     = wp_parse_args( $args, $defaults );

		$excerpt = strip_tags( get_the_excerpt( $args['post'] ) );

		switch ( $args['type'] ) {
			case 'word':
				$excerpt = self::string_limit_words( $excerpt, $args['limit'] );
				break;
			case 'character':
				$excerpt = self::string_limit_characters( $excerpt, $args['limit'] );
				break;
		}

		if ( $excerpt !== '' && $excerpt !== '&nbsp;' ) {
			$excerpt .= $args['after'];
		}

		return $excerpt;
	}

	public static function string_limit_words( $string, $word_limit ) {
		$words = explode( ' ', $string, $word_limit + 1 );
		if ( count( $words ) > $word_limit ) {
			array_pop( $words );
		}

		return implode( ' ', $words );
	}

	public static function string_limit_characters( $string, $limit ) {
		$string = substr( $string, 0, $limit );
		$string = substr( $string, 0, strripos( $string, " " ) );

		return $string;
	}

	public static function image_placeholder( $width, $height ) {
		echo '<img src="https://via.placeholder.com/' . $width . 'x' . $height . '?text=' . esc_attr__( 'No+Image', 'minimog' ) . '" alt="' . esc_attr__( 'Thumbnail', 'minimog' ) . '"/>';
	}

	public static function get_image_placeholder_url( $width, $height ) {
		$url = 'https://via.placeholder.com/' . $width . 'x' . $height . '?text=' . esc_attr__( 'No+Image', 'minimog' );

		return $url;
	}

	public static function render_button( $args ) {
		$defaults = [
			'wrapper'            => true,
			'wrapper_class'      => '',
			'wrapper_attributes' => [],
			'text'               => '',
			'link'               => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
			'style'              => 'flat',
			'size'               => 'nm',
			'full_wide'          => false,
			'icon'               => '',
			'icon_align'         => 'left',
			'extra_class'        => '',
			'class'              => 'tm-button',
			'id'                 => '',
			'attributes'         => [],
			'echo'               => true,
		];

		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		$button_attrs = wp_parse_args( [], $attributes );

		$button_classes   = [ $class ];
		$button_classes[] = 'style-' . $style;
		$button_classes[] = 'tm-button-' . $size;

		if ( $full_wide ) {
			$button_classes[] = 'tm-button-full-wide';
		}

		if ( ! empty( $extra_class ) ) {
			$button_classes[] = $extra_class;
		}

		if ( ! empty( $icon ) ) {
			$button_classes[] = 'icon-' . $icon_align;
		}

		$button_attrs['class'] = implode( ' ', $button_classes );

		if ( ! empty( $id ) ) {
			$button_attrs['id'] = $id;
		}

		$button_tag = 'div';

		if ( ! empty( $link['url'] ) ) {
			$button_tag = 'a';

			$button_attrs['href'] = $link['url'];

			if ( ! empty( $link['is_external'] ) ) {
				$button_attrs['target'] = '_blank';
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$button_attrs['rel'] = $link['nofollow'];
			}
		}

		$attributes_str = '';

		if ( ! empty( $button_attrs ) ) {
			foreach ( $button_attrs as $attribute => $value ) {
				$attributes_str .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
			}
		}

		$wrapper_classes = 'tm-button-wrapper';
		if ( ! empty( $wrapper_class ) ) {
			$wrapper_classes .= " $wrapper_class";
		}

		$wrapper_attributes_str = '';
		if ( ! empty( $wrapper_attributes ) ) {
			foreach ( $wrapper_attributes as $attribute => $value ) {
				$wrapper_attributes_str .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
			}
		}

		ob_start();
		?>
		<?php printf( '<%1$s %2$s>', $button_tag, $attributes_str ); ?>
		<div class="button-content-wrapper">

			<?php if ( ! empty( $icon ) && 'left' === $icon_align ): ?>
				<span class="button-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
			<?php endif; ?>

			<?php if ( ! empty( $text ) ): ?>
				<span class="button-text"><?php echo esc_html( $text ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $icon ) && 'right' === $icon_align ): ?>
				<span class="button-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
			<?php endif; ?>

			<?php if ( $style === 'bottom-line-winding' ): ?>
				<span class="line-winding">
						<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
						     xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd"
							      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
							      fill="#E8C8B3"/>
						</svg>
					</span>
			<?php endif; ?>
		</div>
		<?php printf( '</%1$s>', $button_tag ); ?>
		<?php
		$output = ob_get_clean();

		if ( $wrapper ) {
			$output = sprintf( '<div class="%1$s" %2$s>%3$s</div>', esc_attr( $wrapper_classes ), $wrapper_attributes_str, $output );
		}

		if ( $echo ) {
			echo '' . $output;
		}

		return $output;
	}

	/**
	 * @param int|float $rating Rating average.
	 * @param array     $args
	 *
	 * @return string HTML
	 */
	public static function render_rating( $rating = 5, $args = array() ) {
		$default = [
			'style'         => '01',
			'wrapper_class' => '',
			'echo'          => true,
		];

		$args = wp_parse_args( $args, $default );

		$el_classes = 'tm-star-rating style-' . $args['style'];
		if ( ! empty( $args['wrapper_class'] ) ) {
			$el_classes .= " {$args['wrapper_class']}";
		}

		$full_stars = intval( $rating );

		$star_full_icon = Minimog_SVG_Manager::instance()->get( 'star-full' );

		$star_empty_icon = Minimog_SVG_Manager::instance()->get( 'star-empty' );

		$template = '';
		$template .= str_repeat( $star_full_icon, $full_stars );

		$half_star = floatval( $rating ) - $full_stars;

		if ( $half_star != 0 ) {
			$star_half_icon = Minimog_SVG_Manager::instance()->get( 'star-half' );
			$template       .= $star_half_icon;
		}

		$empty_stars = intval( 5 - $rating );
		$template    .= str_repeat( $star_empty_icon, $empty_stars );

		$template = '<div class="' . esc_attr( $el_classes ) . '">' . $template . '</div>';

		if ( $args['echo'] ) {
			echo '' . $template;
		}

		return $template;
	}
}
