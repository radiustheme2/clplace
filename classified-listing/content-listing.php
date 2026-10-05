<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RT\Clplace\Plugins\Listing_Functions;

global $listing;

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

$style = clplace_option('rt_listing_archive_style');

?>
<div <?php Functions::listing_class('listing-layout-'.$style, $listing ) ?> <?php Functions::listing_data_attr_options() ?>>
    <?php
		if ( $view == 'grid' ) {
			Listing_Functions::get_custom_listing_template( 'archive/grid' );
		} else {
			Listing_Functions::get_custom_listing_template( 'archive/list' );
		}
	?>
</div>
