<?php

namespace Minimog_Elementor\Modules\Woocommerce\Tags;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || exit;

abstract class Base_Tag extends Tag {
	use Tag_Product_Id;

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
}
