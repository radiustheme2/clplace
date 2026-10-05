<?php
/**
 * Build Gutenberg Blocks
 *
 * @package clplace
 */

namespace RT\Clplace\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Traits\SingletonTraits;

/**
 * Customizer class
 */
class Gutenberg {
	use SingletonTraits;

	/**
	 * Register default hooks and actions for WordPress
	 *
	 * @return WordPress add_action()
	 */
	public function __construct() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'gutenberg_init' ] );

	}

	/**
	 * Custom Gutenberg settings
	 * @return
	 */
	public function gutenberg_init() {
		add_theme_support( 'gutenberg', [
			// Theme supports responsive video embeds
			'responsive-embeds' => true,
			// Theme supports wide images, galleries and videos.
			'wide-images'       => true,
		] );

		add_theme_support( 'editor-color-palette', [
			[
				'name'  => __( 'Primary', 'clplace' ),
				'slug'  => 'clplace-primary',
				'color' => '#2d68ff',
			],
			[
				'name'  => __( 'White', 'clplace' ),
				'slug'  => 'clplace-white',
				'color' => '#ffffff',
			],
			[
				'name'  => __( 'Black', 'clplace' ),
				'slug'  => 'clplace-black',
				'color' => '#333333',
			],
			[
				'name'  => __( 'Gold', 'clplace' ),
				'slug'  => 'clplace-gold',
				'color' => '#FCBB6D',
			],
			[
				'name'  => __( 'Pink (Primary)', 'clplace' ),
				'slug'  => 'clplace-pink',
				'color' => '#f80a0a',
			],
			[
				'name'  => __( 'clplace-grey', 'clplace' ),
				'slug'  => 'grey',
				'color' => '#b8c2cc',
			],
		] );
	}
}
