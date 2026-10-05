<?php

namespace RT\Clplace\Setup;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Traits\SingletonTraits;

class Setup {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return void
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'site_prealoader', [ $this, 'preloader' ] );

		remove_filter('the_content', 'wpautop');
		remove_filter('the_excerpt', 'wpautop');
		remove_filter('the_author_description', 'wpautop');
	}

	/**
	 * Setup Theme
	 * @return void
	 */
	public function setup() {
		load_theme_textdomain( 'clplace', get_template_directory() . '/languages' );

		$this->add_theme_support();
		$this->add_image_size();
	}

	/**
	 * Add Image Size
	 * @return void
	 */
	private function add_image_size() {
		$sizes = [
			'clplace-430-250' => [ 430, 250, true ],
			'clplace-450-420' => [ 450, 420, true ],
		];

		$sizes = apply_filters( 'clplace_image_size', $sizes );

		foreach ( $sizes as $size => $value ) {
			add_image_size( $size, $value[0], $value[1], $value[2] );
		}
	}

	/**
	 * Add Theme Support
	 * @return void
	 */
	private function add_theme_support() {
		/*
		 * Default Theme Support options better have
		 */
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'custom-logo' );
		add_theme_support( "custom-header" );
		add_theme_support( "custom-background" );

		/*
		 * Activate Post formats if you need
		 */
		add_theme_support( 'post-formats', [
			'aside',
			'gallery',
			'link',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat',
		] );
	}

	/*
		Define a max content width to allow WordPress to properly resize your images
	*/
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'content_width', 1440 );
	}

	public function preloader() {
		$loading = wp_get_attachment_image( clplace_option( 'rt_preloader_image' ), 'full' );
		echo '<div id="pageoverlay" class="pageoverlay"><span class="pageLoader">' . wp_kses_post( $loading ) . '</span></div>';
	}
}
