<?php

namespace RT\Clplace\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Api\Customizer;
use RT\Clplace\Traits\SingletonTraits;

class Opt {

	use SingletonTraits;

	// Sitewide static variables
	public static $options = null;
	public static $layout = null;
	public static $sidebar = null;
	public static $header_style = null;
	public static $topbar_style = null;
	public static $footer_style = null;
	public static $footer_schema = null;
	public static $has_banner = null;
	public static $banner_style = null;
	public static $has_breadcrumb = null;
	public static $banner_image = '';
	public static $banner_height = '';
	public static $header_width = null;
	public static $menu_alignment = null;
	public static $padding_top = null;
	public static $padding_bottom = null;
	public static $has_tr_header;
	public static $header_tr_color = null;
	public static $has_top_bar;
	public static $single_style;


	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'set_options' ], 99 );
		add_action( 'customize_preview_init', [ $this, 'set_options' ] );
	}

	public function set_options() {
		$newData  = [];
		$defaults = Customizer::$default_value;
		foreach ( $defaults as $key => $dValue ) {
			if ( isset( $_GET['reset_theme_mod'] ) && $_GET['reset_theme_mod'] == 1 ) {
				remove_theme_mod( $key );
				wp_redirect('customize.php');
			}
			$value           = $_GET[ $key ] ?? get_theme_mod( $key, $dValue );
			$newData[ $key ] = $value;
		}
		self::$options = $newData;
	}

}

