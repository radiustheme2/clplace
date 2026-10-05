<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RT\Clplace\Plugins\Listing_Functions;
use Rtrs\Modules\Review\Helpers\ReviewFns;
use Rtcl\Controllers\BusinessHoursController as BHS;

global $listing;
global $wp_locale;

$images = $listing->get_images();

$business_hours = BHS::get_business_hours($listing->get_id());
if (BHS::openStatus($business_hours)) {
    $onoff = '<span class="onoff-status open"><i class="fas fa-check-circle"></i>'. esc_html__( 'Open', 'clplace' ).'</span>';
} else {
    $onoff = '<span class="onoff-status close"><i class="fas fa-times-circle"></i>'. esc_html__( 'Closed', 'clplace' ).'</span>';
}

$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

if( class_exists( ReviewFns::class ) ){
    $rating_count   = ReviewFns::getTotalRatings( get_the_ID() );
} else {
    $rating_count   = $listing->get_rating_count();
}

$show_phone = ! empty( $mod_settings['display_options'] ) && in_array( 'phone', $mod_settings['display_options'] );

$address = get_post_meta( $listing->get_id(), 'address', true );
$phone = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url = str_replace( ' ', '', $phone );

$social_page = Functions::get_option_item('rtcl_general_social_share_settings', 'social_pages', array('listing'));

?>
<div class="listing-details-header header-v2">
	<div class="header-slider">
		<div class="container">
			<?php Listing_Functions::listing_details_slider(); ?>
		</div>
	</div>
	<div class="container">
		<div class="listing-details-head">
			<div class="meta-info-box">
				<div class="listing-details-head-top">
					<div class="listing-badge">
						<?php if ( $listing->has_category() && $listing->can_show_category() ) :
							$category = $listing->get_categories();
							$category = end( $category );
							?>
							<div class="rt-categories">
								<a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
							</div>
						<?php endif; if ( in_array('ad_type', $detailOption) && ! empty( $listing_type ) ) : ?>
							<span class="listing-type-badge">
							<?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', esc_html__( 'For', 'clplace' ) ), esc_html( $listing_type['label'] ) ) ); ?>
						 </span>
						<?php endif; ?>
						<?php $listing->the_badges(); ?>
					</div>
				</div>
				<div class="title-price">
					<h2 class="rtcl-listing-title">
						<?php the_title(); ?>
					</h2>
					<!-- Price -->
					<?php if ( $listing->can_show_price() ): ?>
						<div class="rtcl-price-wrap price-in-mobile">
							<?php echo wp_kses_post( wp_kses_stripslashes( $listing->get_price_html() ) ); ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="meta-list">
					<?php Listing_Functions::clplace_single_listing_meta(); ?>
				</div>
			</div>
			<?php
				if ( in_array('listing', $social_page) ) { ?>
					<div class="post-socials-button">
						<?php $listing->the_social_share(); ?>
					</div>
				<?php }
			?>
		</div>
	</div>
</div>

<?php
    if ( in_array('social_share', $detailOption)){
        Listing_Functions::get_share_link();
    }
?>
