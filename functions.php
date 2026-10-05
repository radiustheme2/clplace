<?php
/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `inc/Custom/Custom.php` to write your custom functions
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) :
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
endif;

if ( class_exists( 'RT\\Clplace\\Init' ) ) :
	/**
	 * Initialize theme after setup.
	 */
	add_action( 'after_setup_theme', function() {
		RT\Clplace\Init::instance();
		do_action( 'clplace_theme_init' );
	}, 1 );
endif;

add_editor_style( 'style-editor.css' );