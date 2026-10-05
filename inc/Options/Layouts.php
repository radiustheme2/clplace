<?php

namespace RT\Clplace\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RT\Clplace\Traits\SingletonTraits;

class Layouts {

	use SingletonTraits;

	public $base;
	public $type;
	public $meta_value;

	public function __construct() {
		add_action( 'template_redirect', [ $this, 'set_options_value' ] );
	}

	/**
	 * Set Options value
	 * @return void
	 */
	public function set_options_value() {
		$is_listing_archive = false;

		if ( class_exists( 'Rtcl' ) ) {
			$is_listing_archive = Functions::is_listings() || Functions::is_listing_taxonomy();
		}

		// Single Pages
		if ( ( is_single() || is_page() ) && ! $is_listing_archive ) {
			$post_type        = get_post_type();
			$post_id          = get_the_id();
			$this->meta_value = get_post_meta( $post_id, "rt_layout_meta_data", true );

			switch ( $post_type ) {
				case 'post':
					$this->type = 'single_post';
					break;
				case 'product':
					$this->type = 'woocommerce_single';
					break;
				case 'rtcl_listing':
					$this->type = 'listing_single';
					break;
				default:
					$this->type = 'page';
			}

			Opt::$layout          = $this->check_meta_and_layout_value( 'layout', false, true );
			Opt::$header_style    = $this->check_meta_and_layout_value( 'header_style', false, true );
			Opt::$sidebar         = $this->check_meta_and_layout_value( 'sidebar', false, true );
			Opt::$header_width    = $this->check_meta_and_layout_value( 'header_width' );
			Opt::$menu_alignment  = $this->check_meta_and_layout_value( 'menu_alignment' );
			Opt::$padding_top     = $this->check_meta_and_layout_value( 'padding_top' );
			Opt::$padding_bottom  = $this->check_meta_and_layout_value( 'padding_bottom' );
			Opt::$banner_style    = $this->check_meta_and_layout_value( 'banner_style', false, true );
			Opt::$banner_image    = $this->check_meta_and_layout_value( 'banner_image', false, true );
			Opt::$banner_height   = $this->check_meta_and_layout_value( 'banner_height', false, true );
			Opt::$footer_style    = $this->check_meta_and_layout_value( 'footer_style', false, true );
			Opt::$footer_schema   = $this->check_meta_and_layout_value( 'footer_schema', false, true );
			Opt::$has_top_bar     = $this->check_meta_and_layout_value( 'top_bar', false, false );
			Opt::$has_tr_header   = $this->check_meta_and_layout_value( 'tr_header', false, false );
			Opt::$header_tr_color = $this->check_meta_and_layout_value( 'tr_header_color' );
			Opt::$has_breadcrumb  = $this->check_meta_and_layout_value( 'breadcrumb', true, true );
			Opt::$has_banner      = $this->check_meta_and_layout_value( 'banner', true, true );
			Opt::$single_style    = $this->check_meta_option_value( 'single_post_style' );

		} // Blog and Archive
		elseif ( is_home() || is_archive() || is_search() || is_404() || $is_listing_archive ) {
			if ( class_exists( 'WooCommerce' ) && is_shop() ) {
				$this->type = 'woocommerce_archive';
			} elseif ( $is_listing_archive ) {
				$this->type = 'listing_archive';
			} elseif ( is_404() ) {
				$this->type = 'error';
			} else {
				$this->type = 'blog';
			}

			Opt::$layout          = $this->check_option_value( 'layout', false, true );
			Opt::$header_style    = $this->check_option_value( 'header_style', false, true );
			Opt::$sidebar         = $this->check_option_value( 'sidebar', false, true );
			Opt::$header_width    = $this->check_option_value( 'header_width' );
			Opt::$menu_alignment  = $this->check_option_value( 'menu_alignment' );
			Opt::$padding_top     = $this->check_option_value( 'padding_top' );
			Opt::$padding_bottom  = $this->check_option_value( 'padding_bottom' );
			Opt::$banner_style    = $this->check_option_value( 'banner_style', false, true );
			Opt::$banner_image    = $this->check_option_value( 'banner_image', false, true );
			Opt::$banner_height   = $this->check_option_value( 'banner_height', false, true );
			Opt::$footer_style    = $this->check_option_value( 'footer_style', false, true );
			Opt::$footer_schema   = $this->check_option_value( 'footer_schema', false, true );
			Opt::$has_top_bar     = $this->check_option_value( 'top_bar', false, false );
			Opt::$has_tr_header   = $this->check_option_value( 'tr_header', false, false );
			Opt::$header_tr_color = $this->check_option_value( 'tr_header_color' );
			Opt::$has_breadcrumb  = $this->check_option_value( 'breadcrumb', true, true );
			Opt::$has_banner      = $this->check_option_value( 'banner', true, true );
		}
	}

	/**
	 * Get Meta and Options value conditionally
	 *
	 * @param $key
	 * @param $is_bool
	 *
	 * @return bool|mixed|string
	 */
	private function check_meta_and_layout_value( $key, $is_bool = false, $check_layout = false ) {
		$option_key      = $this->type . '_' . $key;
		$meta_value      = $this->meta_value[ $key ] ?? 'default';
		$opt_from_layout = Opt::$options[ $option_key ] ?? 'default';
		$opt_from_global = Opt::$options[ 'rt_' . $key ] ?? 'default';

		if ( ! empty( $meta_value ) && $meta_value != 'default' ) { //Check from Meta
			$result = $meta_value;
		} elseif ( $check_layout && ! empty( $opt_from_layout ) && $opt_from_layout != 'default' ) { //Check from Layout
			$result = $opt_from_layout;
		} else {
			$result = $opt_from_global;
		}

		if ( $is_bool ) {
			return $result == 1 || $result == 'on';
		}

		return $result;
	}

	/**
	 * Get Options value only
	 *
	 * @param $key
	 * @param bool $is_bool
	 *
	 * @return bool|mixed|string
	 */
	private function check_option_value( $key, $is_bool = false, $check_layout = false ) {
		$option_key = $this->type . '_' . $key;

		$opt_from_layout = Opt::$options[ $option_key ] ?? 'default';
		$opt_from_global = Opt::$options[ 'rt_' . $key ] ?? 'default';

		if ( $check_layout && ! empty( $opt_from_layout ) && $opt_from_layout != 'default' ) {
			$result = $opt_from_layout;
		} else {
			$result = $opt_from_global;
		}

		if ( $is_bool ) {
			return $result == 1 || $result == 'on';
		}

		return $result;
	}

	private function check_meta_option_value( $key ) {
		$meta_value      = $this->meta_value[ $key ] ?? 'default';
		$opt_from_global = Opt::$options[ 'rt_' . $key ] ?? 'default';

		if ( ! empty( $meta_value ) && $meta_value != 'default' ) { //Check from Meta
			$result = $meta_value;
		} else {
			$result = $opt_from_global;
		}

		return $result;
	}

	public function overwrite_options_value() {
		if ( Opt::$single_style == '3' ) {
			Opt::$has_tr_header = '1';
		}
	}
}
