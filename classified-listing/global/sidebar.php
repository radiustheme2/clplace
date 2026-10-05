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

use Rtcl\Helpers\Functions;

if ( ( Functions::is_listings() || Functions::is_listing_taxonomy() ) && is_active_sidebar( 'rtcl-archive-sidebar' ) ) {
	clplace_sidebar( 'rtcl-archive-sidebar' );
} else if ( Functions::is_listing() && is_active_sidebar( 'rtcl-single-sidebar' ) ) {
	clplace_sidebar( 'rtcl-single-sidebar' );
} else {
//	sidebar is not set
}
