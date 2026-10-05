<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( is_singular() && is_active_sidebar( 'rt-single-sidebar' ) ) {
	clplace_sidebar( 'rt-single-sidebar' );
}

if ( ! is_singular() && is_active_sidebar( 'rt-sidebar' ) ) {
	clplace_sidebar( 'rt-sidebar' );
}
