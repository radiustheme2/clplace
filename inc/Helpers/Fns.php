<?php

namespace RT\Clplace\Helpers;

use RT\Clplace\Options\Opt;

/**
 * Extras.
 */
class Fns {

	/**
	 * Filters whether post thumbnail can be displayed.
	 *
	 * @param bool $show_post_thumbnail Whether to show post thumbnail.
	 *
	 */
	public static function can_show_post_thumbnail() {
		return apply_filters(
			'clplace_can_show_post_thumbnail',
			! post_password_required() && ! is_attachment() && has_post_thumbnail()
		);
	}

	/**
	 * Allowed HTML for wp_kses.
	 *
	 * @param $html
	 * @param $echo
	 *
	 * @return string
	 */
	public static function print_html( $html, $echo = true, $context = 'basic' ) {
	}


	/**
	 * Prints HTMl.
	 *
	 * @param $html
	 * @param $allHtml
	 *
	 * @return void
	 */
	public static function print_html_all( $html, $allHtml = false ) {
	}


	/**
	 * Sanitize Text Field
	 *
	 * @param $input
	 * @param $default
	 * @param $mode
	 *
	 * @return mixed|string
	 */
	public static function sanitize( $input, $default = '', $mode = '' ) {

		$data = $input ?? $default;

		if ( 'html' === $mode ) {
			return self::print_html( $data, false );
		}

		return sanitize_text_field( $data );
	}


	/**
	 * Social icon for the site
	 * @return mixed|null
	 */
	public static function get_socials() {
		return apply_filters( 'clplace_socials_icon', [
			'facebook'  => [
				'title' => __( 'Facebook', 'clplace' ),
				'url'   => clplace_option( 'facebook' ),
			],
			'twitter'   => [
				'title' => __( 'Twitter', 'clplace' ),
				'url'   => clplace_option( 'twitter' ),
			],
			'linkedin'  => [
				'title' => __( 'Linkedin', 'clplace' ),
				'url'   => clplace_option( 'linkedin' ),
			],
			'youtube'   => [
				'title' => __( 'Youtube', 'clplace' ),
				'url'   => clplace_option( 'youtube' ),
			],
			'pinterest' => [
				'title' => __( 'Pinterest', 'clplace' ),
				'url'   => clplace_option( 'pinterest' ),
			],
			'instagram' => [
				'title' => __( 'Instagram', 'clplace' ),
				'url'   => clplace_option( 'instagram' ),
			],
			'skype'     => [
				'title' => __( 'Skype', 'clplace' ),
				'url'   => clplace_option( 'skype' ),
			],
		] );

	}

	/**
	 * Get Sidebar lists
	 *
	 * @return array
	 */
	public static function sidebar_lists( $default_title = '' ) {
		$sidebar_fields            = [];
		$sidebar_fields['default'] = $default_title ?? esc_html__( 'Choose Sidebar', 'clplace' );

		foreach ( self::default_sidebar() as $id => $sidebar ) {
			$sidebar_fields[ $id ] = $sidebar['name'];
		}

		return $sidebar_fields;
	}

	/**
	 * Get image presets
	 *
	 * @param $name
	 * @param int $total
	 * @param string $type
	 *
	 * @return array
	 */
	public static function image_placeholder( $name, $total = 1, $type = 'jpg' ) {
		$presets = [];
		for ( $i = 1; $i <= $total; $i ++ ) {
			$image_name    = "$name-$i.$type";
			$presets[ $i ] = [
				'image' => clplace_get_img( $image_name ),
				'name'  => __( 'Style', 'clplace' ) . ' ' . $i,
			];
		}

		return apply_filters( 'clplace_image_placeholder', $presets );
	}


	/**
	 * Convert HEX to RGB color
	 *
	 * @param $hex
	 *
	 * @return string
	 */
	public static function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = "$r, $g, $b";

		return $rgb;
	}

	/**
	 * Modify Color
	 * Add positive or negative $steps. Ex: 30, -50 etc
	 *
	 * @param $hex
	 * @param $steps
	 *
	 * @return string
	 */
	public static function modify_color( $hex, $steps ) {
		$steps = max( - 255, min( 255, $steps ) );
		// Format the hex color string
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}
		// Get decimal values
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		// Adjust number of steps and keep it inside 0 to 255
		$r     = max( 0, min( 255, $r + $steps ) );
		$g     = max( 0, min( 255, $g + $steps ) );
		$b     = max( 0, min( 255, $b + $steps ) );
		$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}


	/**
	 * Return Sidebar Column
	 * @return string
	 */
	public static function sidebar_columns() {
		if (is_archive('rtcl-listing')){
			$columns = "col-lg-3";
		} else {
			$columns = "col-lg-3";
		}
		return $columns;
	}

	/**
	 * Return content columns
	 * @return string
	 */
	public static function content_columns( $full_width_col = 'col-xl-12 col-lg-12' ) {
		$sidebar = Opt::$sidebar === 'default' ? 'rt-sidebar' : Opt::$sidebar;
		if (is_archive('rtcl-listing')){
			$columns = ! is_active_sidebar( $sidebar ) ? $full_width_col : 'col-lg-9';
		} else {
			$columns = ! is_active_sidebar( $sidebar ) ? $full_width_col : 'col-lg-9';
		}
		if ( Opt::$layout === 'full-width' ) {
			$columns = $full_width_col;
		}
		return $columns;
	}

	/**
	 * @return string
	 */
	public static function single_content_colums() {
		$sidebar = Opt::$sidebar === 'default' ? 'rt-single-sidebar' : Opt::$sidebar;
		$columns = is_active_sidebar( $sidebar ) ? "col-lg-8" : "col-lg-10 col-lg-offset-1";

		if ( Opt::$layout === 'full-width' ) {
			$columns = "col-lg-10 col-lg-offset-1";
		}

		return $columns;
	}

	/**
	 * Get blog colum
	 * @return mixed|string
	 */
	public static function blog_column() {
		if ( ! empty( $_REQUEST['column'] ) ) {
			return sanitize_text_field( $_REQUEST['column'] );
		}
		$blog_colum_opt = clplace_option( 'rt_blog_column' ) !== 'default' ? clplace_option( 'rt_blog_column' ) : '';
		$blog_sidebar   = Opt::$sidebar === 'default' ? 'rt-sidebar' : Opt::$sidebar;
		$blog_layout    = Opt::$layout ?? 'right-sidebar';

		$output = 'col-lg-12';
		if ( $blog_colum_opt ) {
			$output = $blog_colum_opt;
		} elseif ( in_array( $blog_layout, [ 'left-sidebar', 'right-sidebar' ] ) && is_active_sidebar( $blog_sidebar ) ) {
			$output = 'col-lg-12';
		}

		return $output;
	}

	/**
	 * Get all post type
	 * @return array
	 */
	public static function get_post_types() {
		$post_types = get_post_types(
			[
				'public' => true,
			],
			'objects'
		);
		$post_types = wp_list_pluck( $post_types, 'label', 'name' );

		$exclude = apply_filters( 'clplace_exclude_post_type', [
			'attachment',
			'revision',
			'nav_menu_item',
			'elementor_library',
			'tpg_builder',
			'e-landing-page',
			'elementor-rt-hfb' ] );

		foreach ( $exclude as $ex ) {
			unset( $post_types[ $ex ] );
		}

		return $post_types;
	}

	/**
	 * Meta Style
	 * @return array
	 */
	public static function meta_style( $exclude = [] ) {
		$meta_style = [
			'meta-style-default' => __( 'Default From Theme', 'clplace' ),
			'meta-style-border'  => __( 'Border Style', 'clplace' ),
			'meta-style-dash'    => __( 'Before Dash ( — )', 'clplace' ),
			'meta-style-dash-bg' => __( 'Before Dash with BG ( — )', 'clplace' ),
			'meta-style-pipe'    => __( 'After Pipe ( | )', 'clplace' ),
		];

		if ( ! empty( $exclude ) && is_array( $exclude ) ) {
			foreach ( $exclude as $item ) {
				unset( $meta_style[ $item ] );
			}
		}

		return $meta_style;
	}

	/**
	 * Single Style
	 * @return array
	 */
	public static function single_post_style( $exclude = [] ) {
		$meta_style = [
			'1' => __( 'Style 1 (Default From Theme)', 'clplace' ),
			'2' => __( 'Style 2 (Full-width Thumbnail)', 'clplace' ),
			'3' => __( 'Style 3 (Transparent Menu)', 'clplace' ),
			'4' => __( 'Style 4 (Content over on Thumb)', 'clplace' ),
		];

		if ( ! empty( $exclude ) && is_array( $exclude ) ) {
			foreach ( $exclude as $item ) {
				unset( $meta_style[ $item ] );
			}
		}

		return $meta_style;
	}

	/**
	 * Blog Meta Style
	 * @return array
	 */
	public static function blog_meta_list() {
		return [
			'author'   => __( 'Author', 'clplace' ),
			'date'     => __( 'Date', 'clplace' ),
			'category' => __( 'Category', 'clplace' ),
			'tag'      => __( 'Tag', 'clplace' ),
			'comment'  => __( 'Comment', 'clplace' ),
		];
	}

	/**
	 * Blog Meta Style
	 * @return array
	 */
	public static function post_share_list() {
		return [
			'facebook' => __( 'Facebook', 'clplace' ),
			'twitter'  => __( 'Twitter X', 'clplace' ),
			'linkedin' => __( 'Linkedin', 'clplace' ),
			'pinterest'  => __( 'Pinterest', 'clplace' ),
		];
	}

	public static function is_single_fullwidth() {
		if ( in_array( Opt::$single_style, [ 'rt-single-top-thumb', 'rt-single-transparent', 'rt-single-content-on-thumb' ] ) ) {
			return true;
		}

		return false;
	}


	public static function single_meta_lists() {
		$meta_list = clplace_option( 'rt_single_meta', '', true );
		if ( clplace_option( 'rt_single_above_cat_visibility' ) ) {
			$category_index = array_search( 'category', $meta_list );
			unset( $meta_list[ $meta_list ] );
		}
		return $meta_list;
	}

	/**
	 * Class list
	 *
	 * @param $clsses
	 *
	 * @return string
	 */
	public static function class_list( $clsses ): string {
		return implode( ' ', $clsses );
	}

	/**
	 * Get all default sidebar args for theme
	 *
	 * @param $id
	 *
	 * @return array|mixed|null
	 */
	public static function default_sidebar( $id = '' ) {
		if ( class_exists( 'Rtcl' ) ) {
			$sidebar_lists['listing-map']  = [
				'id'    => 'rt-listing-map-archive-sidebar',
				'name'  => __( 'Listing Map Sidebar', 'clplace' ),
				'class' => 'listing-map-archive-sidebar',
			];
		}
		$sidebar_lists['main']  = [
				'id'    => 'rt-sidebar',
				'name'  => __( 'Main Sidebar', 'clplace' ),
				'class' => 'rt-sidebar',
		];
		$sidebar_lists['single']  = [
				'id'    => 'rt-single-sidebar',
				'name'  => __( 'Single Sidebar', 'clplace' ),
				'class' => 'rt-single-sidebar',
		];
		$sidebar_lists['footer']  = [
				'id'    => 'rt-footer-sidebar',
				'name'  => 'Footer Sidebar',
				'class' => 'footer-sidebar col-lg-3 col-md-6',
		];
		if ( class_exists( 'WooCommerce' ) ) {
			$sidebar_lists['woo-archive'] = [
				'id'    => 'rt-woo-archive-sidebar',
				'name'  => __( 'WooCommerce Archive Sidebar', 'clplace' ),
				'class' => 'woo-archive-sidebar',
			];
			$sidebar_lists['woo-single']  = [
				'id'    => 'rt-woo-single-sidebar',
				'name'  => __( 'WooCommerce Single Sidebar', 'clplace' ),
				'class' => 'woo-single-sidebar',
			];
		}
		$sidebar_lists = apply_filters( 'clplace_sidebar_lists', $sidebar_lists );
		if ( ! $id ) {
			return $sidebar_lists;
		}
		if ( isset( $sidebar_lists[ $id ] ) ) {
			return $sidebar_lists[ $id ]['id'];
		}

		return [];
	}

	public static function clplace_excerpt( $limit ) {
		if (!empty($limit)) {
			$limit = $limit;
		} else {
			$limit = 0;
		}
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'';
		} else {
			$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);

		return $excerpt;
	}

}
