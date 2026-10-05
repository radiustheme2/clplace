<?php
/**
 * Result Count
 *
 * @var Listing $listing
 */

use Rtcl\Helpers\Functions;
use Rtcl\Controllers\Hooks\TemplateHooks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $listing;

if ( ! $listing ) {
	return;
}

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

?>

<div class="listing-thumb">
    <div class="clplace-listing-actions-buttons">
		<?php if ( $listing->can_show_ad_type() && ! empty( $listing_type ) ): ?>
            <span class="listing-type-badge">
                <?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', esc_html__( 'For', 'clplace' ) ), esc_html( $listing_type['label'] ) ) ); ?>
            </span>
		<?php endif; ?>
		<?php TemplateHooks::loop_item_badges(); ?>
    </div>

    <div class="listing-thumb-inner">
		<a href="<?php $listing->the_permalink(); ?>" class="rtcl-media grid-thumbnail"><?php $listing->the_thumbnail('rtcl-thumbnail'); ?></a>
		<a href="<?php $listing->the_permalink(); ?>" class="rtcl-media list-thumbnail"><?php $listing->the_thumbnail('rtcl-thumbnail'); ?></a>
		<?php
		/**
		 * Hook: rtcl_after_listing_thumbnail.
		 *
		 * @hooked loop_item_meta_buttons - 10
		 */
		do_action( 'rtcl_after_listing_thumbnail' );
		?>
	</div>
</div>
