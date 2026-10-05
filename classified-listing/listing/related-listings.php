<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var WP_Query $rtcl_related_query
 * @var array $slider_options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Plugins\Listing_Functions;

if ( ! $rtcl_related_query->have_posts() ) {
	return;
}
$listing_related = clplace_option('rt_related_listing_visibility');
$style = clplace_option('rt_listing_archive_style');

$category = clplace_option('rt_related_listing_cat_visibility') ? 'has-cat' : 'none-cat';
$author = clplace_option('rt_related_listing_author_visibility') ? 'has-author' : 'none-author';
$location = clplace_option('rt_related_listing_location_visibility') ? 'has-location' : 'none-location';
$time = clplace_option('rt_related_listing_time_visibility') ? 'has-time' : 'none-time';
$views = clplace_option('rt_related_listing_views_visibility') ? 'has-views' : 'none-views';

$class = $category.' '.$author.' '.$location.' '.$time.' '.$views;

if ( $listing_related ) {

?>

<div class="listing-archive-wrapper related-listing-main <?php echo esc_attr( $class ); ?>">
	<div class="rtcl mb-3 rtcl-related-listing rtcl-listings">
		<div class="rtcl-related-title"><h2><?php esc_html_e( "See More Related Listing", "clplace" ); ?></h2></div>
		<div class="rtcl-related-listings">
			<div class="rtcl-related-slider-wrap">
				<div class="rtcl-related-slider rtcl-carousel-slider" id="rtcl-related-slider" data-options="<?php echo esc_attr( wp_json_encode( $slider_options ) ); // WPCS: XSS ok. ?>">
					<div class="swiper-wrapper">
						<?php
						global $post;
						while ( $rtcl_related_query->have_posts() ):
							$rtcl_related_query->the_post();
							$listing = rtcl()->factory->get_listing( get_the_ID() );
							?>
							<div class="swiper-slide rtcl-related-slider-item listing-item rtcl-listing-item">
								<?php Listing_Functions::get_custom_listing_template( 'archive/grid/grid-'.$style ); ?>
							</div>
						<?php endwhile;
						wp_reset_postdata();
						?>
					</div>
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php } ?>
