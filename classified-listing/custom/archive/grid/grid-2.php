<?php
/**
 * Listing Archive Layout
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Controllers\Hooks\TemplateHooks;
use RT\Clplace\Plugins\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;

global $listing;

$phone = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url = str_replace(' ', '', $phone );

?>

<?php
/**
 * Hook: rtcl_before_listing_loop_item.
 *
 * @hooked rtcl_template_loop_product_link_open - 10
 */
do_action( 'rtcl_before_listing_loop_item' );

/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked listing_thumbnail - 10
 */
do_action( 'rtcl_listing_loop_item_start' );

/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked loop_item_wrap_start - 10
 * @hooked loop_item_listing_title - 20
 * @hooked loop_item_labels - 30
 * @hooked loop_item_listable_fields - 40
 * @hooked loop_item_meta - 50
 * @hooked loop_item_excerpt - 60
 * @hooked loop_item_wrap_end - 100
 */

?>

<?php TemplateHooks::loop_item_wrapper_start(); ?>

<div class="all-meta-info-box">
	<?php
	if ( $listing->has_category() && $listing->can_show_category() ) :
		$category = $listing->get_categories();
		$category = end( $category );
		?>
        <div class="rt-categories">
            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
        </div>
	<?php endif; ?>
	<?php TemplateHooks::loop_item_listing_title(); ?>

	<?php Listing_Functions::clplace_listing_rating( $listing ); ?>

	<?php ProTemplateHooks::loop_item_listable_fields(); ?>
	<?php TemplateHooks::loop_item_meta(); ?>
</div>
<div class="listing-footer">
	<?php TemplateHooks::listing_price(); ?>
	<?php if ( !empty( $phone ) ){ ?>
        <a href="tel:<?php echo esc_attr( $phone_url ); ?>" class="phone-no">
            <i class="icon-phone-call"></i>
			<?php echo esc_html( $phone ); ?>
        </a>
	<?php } ?>
</div>

<?php TemplateHooks::loop_item_wrapper_end(); ?>

<?php
/**
 * Hook: rtcl_after_listing_loop_item.
 *
 * @hooked listing_loop_map_data - 50
 */
do_action( 'rtcl_after_listing_loop_item' );

?>
