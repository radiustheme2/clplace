<?php
/**
 * @package Saervlisting/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Plugins\Listing_Functions;

$style = clplace_option('rt_listing_archive_style');

Listing_Functions::get_custom_listing_template( 'archive/list/list-'.$style );

?>
