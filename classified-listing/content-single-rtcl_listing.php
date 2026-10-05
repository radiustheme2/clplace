<?php
/**
 * The template for displaying product content in the single-rtcl_listing.php template
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/content-single-rtcl_listing.php.
 *
 * @package ClassifiedListing/Templates
 * @version 2.2.25
 */

use RT\Clplace\Plugins\Listing_Functions;

defined( 'ABSPATH' ) || exit;

global $listing;

if ( post_password_required() ) {
	echo wp_kses_post( get_the_password_form() ); // WPCS: XSS ok.
	return;
}

$clplace_style = clplace_option('rt_listing_single_style');

/**
 * Hook: rtcl_before_single_product.
 *
 * @hooked rtcl_print_notices - 10
 */
do_action( 'rtcl_before_single_listing' );

?>

<?php Listing_Functions::get_custom_listing_template( 'single/content-single-'.$clplace_style ); ?>

<?php if (!empty(clplace_option('rt_related_listing_visibility'))){ ?>
<!-- Related Listing -->
	<div class="related-listing-wrapper">
		<div class="container">
			<?php $listing->the_related_listings(); ?>
		</div>
	</div>
<?php } ?>
<?php do_action( 'rtcl_after_single_listing' ); ?>
