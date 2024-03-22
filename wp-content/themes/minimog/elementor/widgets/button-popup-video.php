<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Widget_Button_Popup_Video extends Widget_Button {

	private $hosted_video_id = '';

	public function get_name() {
		return 'tm-button-popup-video';
	}

	public function get_title() {
		return esc_html__( 'Button: Popup Video', 'minimog' );
	}

	public function get_script_depends() {
		return [ 'lightgallery' ];
	}

	public function get_style_depends() {
		return [ 'lightgallery' ];
	}

	public function register_controls() {
		$this->add_video_section();

		parent::register_controls();

		$this->update_control( 'text', [
			'default'     => esc_html__( 'Play video', 'minimog' ),
			'placeholder' => esc_html__( 'Play video', 'minimog' ),
		] );

		$this->remove_control( 'link' );
	}

	public function add_video_section() {
		$this->start_controls_section( 'video_section', [
			'label' => esc_html__( 'Video', 'minimog' ),
		] );

		$this->add_control( 'video_source', [
			'label'   => esc_html__( 'Source', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'external',
			'options' => [
				'external' => esc_html__( 'External', 'minimog' ),
				'hosted'   => esc_html__( 'Self Hosted', 'minimog' ),
			],
		] );

		$this->add_control( 'hosted_url', [
			'label'      => esc_html__( 'Choose File', 'minimog' ),
			'type'       => Controls_Manager::MEDIA,
			'dynamic'    => [
				'active'     => true,
				'categories' => [
					TagsModule::MEDIA_CATEGORY,
				],
			],
			'media_type' => 'video',
			'condition'  => [
				'video_source' => 'hosted',
			],
		] );

		$this->add_control( 'video_url', [
			'label'       => esc_html__( 'Video Url', 'minimog' ),
			'description' => esc_html__( 'Input Youtube video url or Vimeo video url. For e.g: "https://www.youtube.com/watch?v=XHOmBV4js_E"', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'default'     => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
			'condition'   => [
				'video_source' => 'external',
			],
		] );

		$this->end_controls_section();
	}

	public function before_render_button() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-popup-video' );

		if ( 'hosted' === $settings['video_source'] ) {
			$this->hosted_video_id = uniqid( 'hosted-video-' );
			$video_url             = $settings['hosted_url']['url'];
			$this->add_render_attribute( 'button', 'data-html', '#' . $this->hosted_video_id );

			$this->add_render_attribute( 'video-inline', [
				'id'    => $this->hosted_video_id,
				'style' => 'display:none;',
			] );
		} else {
			$video_url = $settings['video_url'];
			$this->add_render_attribute( 'button', 'href', esc_url( $video_url ) );
		}
	}

	public function after_render_button() {
		$settings = $this->get_settings_for_display();

		if ( 'hosted' !== $settings['video_source'] ) {
			return;
		}

		$video_url = $settings['hosted_url']['url'];
		?>
		<div <?php $this->print_attributes_string( 'video-inline' ); ?>>
			<video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none"
			       src="<?php echo esc_url( $video_url ); ?>"></video>
		</div>
		<?php
	}
}
