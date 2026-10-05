<?php

namespace RT\Clplace\Setup;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Helpers\Constants;
use RT\Clplace\Traits\SingletonTraits;

/**
 * Enqueue.
 */
class Enqueue {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 12);
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 21 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 15 );
	}

	function register_scripts(){
		wp_register_style( 'clplace-gfonts', $this->fonts_url(), [], Constants::get_version() );
		wp_register_script( 'animated-line', clplace_get_js( 'animated-line' ), [ 'jquery' ], Constants::get_version(), true );
	}

	/**
	 * Enqueue all necessary scripts and styles for the theme
	 * @return void
	 */
	public function enqueue_scripts() {
		// CSS
		wp_enqueue_style( 'clplace-gfonts' );
		wp_enqueue_style( 'fontello', clplace_get_css( 'fontello', true ), [], Constants::get_version() );
		wp_enqueue_style( 'animate.min', clplace_get_css( 'animate.min', true ), [], Constants::get_version() );
		wp_enqueue_style( 'magnific-popup', clplace_get_css( 'magnific-popup', true ), [], Constants::get_version() );
		wp_enqueue_style( 'rangeSlider', clplace_get_css( 'ion.rangeSlider.min', true ), [], Constants::get_version() );
		wp_enqueue_style( 'clplace-main', clplace_get_rtl_css( 'style', true ), [], Constants::get_version() );

		// JS
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'headroom', clplace_get_js( 'headroom' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'wow', clplace_get_js( 'wow.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'isotope', clplace_get_js( 'isotope.pkgd.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'parallax.min', clplace_get_js( 'parallax.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'ele-parallax', clplace_get_js( 'ele-parallax' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'magnific-popup', clplace_get_js( 'jquery.magnific-popup.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'rangeSlider', clplace_get_js( 'ion.rangeSlider.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'clplace-main', clplace_get_js( 'scripts' ), [ 'jquery' ], Constants::get_version(), true );

		// Extra
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue all necessary scripts and styles for the admin
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'clplace-gfonts' );
		wp_enqueue_style( 'admin', clplace_get_css( 'admin', true ), [], Constants::get_version() );
		wp_enqueue_style( 'fontello', clplace_get_css( 'fontello', true ), [], Constants::get_version() );
	}

	public function fonts_url() {

		if ( 'off' === _x( 'on', 'Google font: on or off', 'clplace' ) ) {
			return '';
		}
		$body_font = json_decode( clplace_option( 'rt_body_typo' ), true );
		$menu_font = json_decode( clplace_option( 'rt_menu_typo' ), true );
		$h_font    = json_decode( clplace_option( 'rt_all_heading_typo' ), true );

		$bodyFont = $body_font['font'] ?? 'IBM Plex Sans'; // Body Font
		$menuFont = $menu_font['font'] ?? $bodyFont; // Menu Font
		$hFont    = $h_font['font'] ?? $body_font; // Heading Font
		$hFontW   = $h_font['regularweight'];

		$heading_fonts = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];

		foreach ( $heading_fonts as $heading ) {
			$heading_font         = json_decode( clplace_option( "rt_heading_{$heading}_typo" ), true );
			${$heading . '_font'} = $heading_font;
			${$heading . 'Font'}  = ''; //Assign default value if not exist the value
			if ( ! empty( $heading_font['font'] ) ) {
				${$heading . 'Font'}  = $heading_font['font'] == 'Inherit' ? $hFont : $heading_font['font'];
				${$heading . 'FontW'} = $heading_font['font'] == 'Inherit' ? $hFontW : $heading_font['regularweight'];
			}
		}

		$check_families = [];
		$font_families  = [];

		// Body Font
		$font_families[]  = $bodyFont . ':300,400,500,600,700';
		$check_families[] = $bodyFont;

		// Menu Font
		if ( ! in_array( $menuFont, $check_families ) ) {
			$font_families[]  = $menuFont . ':300,400,500,600,700';
			$check_families[] = $menuFont;
		}

		// Heading Font
		if ( ! in_array( $hFont, $check_families ) ) {
			$font_families[]  = $hFont . ':300,400,500,600,700';
			$check_families[] = $hFont;
		}

		//Check all heading fonts
		foreach ( $heading_fonts as $heading ) {
			$hDynamic = ${$heading . '_font'};
			if ( ! empty( $hDynamic['font'] ) ) {
				if ( ! in_array( ${$heading . 'Font'}, $check_families ) ) {
					$font_families[]  = ${$heading . 'Font'} . ':' . ${$heading . 'FontW'};
					$check_families[] = ${$heading . 'Font'};
				}
			}
		}

		$final_fonts = array_unique( $font_families );
		$query_args  = [
			'family'  => urlencode( implode( '|', $final_fonts ) ),
			'display' => urlencode( 'fallback' ),
		];

		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );

		return esc_url_raw( $fonts_url );
	}
}