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

use Rtcl\Helpers\Functions;
use RtclMarketplace\Hooks\ActionHooks;
use Rtcl\Controllers\Hooks\TemplateHooks;
use RT\Clplace\Plugins\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;

global $listing;

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
$show_rating = ! empty( $archive_settings['display_options'] ) && in_array( 'rating', $archive_settings['display_options'] );
$show_status = ! empty( $archive_settings['display_options'] ) && in_array( 'status', $archive_settings['display_options'] );
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
        <?php TemplateHooks::loop_item_listing_title(); ?>
        <?php Listing_Functions::clplace_listing_excerpt( clplace_option('rt_listing_excerpt') ); ?>
        <div class="meta-rating">
            <?php TemplateHooks::loop_item_meta(); ?>
            <?php if ( !empty( $show_rating )) { ?>
                <div class="listing-review"><?php Listing_Functions::clplace_listing_rating_counting( $listing ); ?></div>
            <?php } ?>
        </div>
        <?php ProTemplateHooks::loop_item_listable_fields(); ?>
    </div>
    <?php
        if ( class_exists('RtclMarketplace') ) {
            ActionHooks::add_buy_button();
        }
    ?>

    <?php if ( $listing->can_show_price() || $show_status || ( $listing->has_category() && $listing->can_show_category() ) ) { ?>
        <div class="listing-footer">
            <div class="price-status">
                <?php TemplateHooks::listing_price(); ?>
                <?php if ( ! empty( $show_status ) ) {
                    Listing_Functions::listing_bhs_status( $listing );
                } ?>
            </div>
            <?php
                if ( $listing->has_category() && $listing->can_show_category() ):
                    Listing_Functions::clplace_listing_categories( 'icon' );
                endif;
            ?>
        </div>
    <?php } ?>

<?php TemplateHooks::loop_item_wrapper_end(); ?>

<?php
    /**
     * Hook: rtcl_after_listing_loop_item.
     *
     * @hooked listing_loop_map_data - 50
     */
    do_action( 'rtcl_after_listing_loop_item' );
?>
