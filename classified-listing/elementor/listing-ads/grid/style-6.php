<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 *
 * @var $the_loops WP_Query;
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Models\Listing;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use RT\Clplace\Plugins\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;
global $listing;

?>

<div class="rtcl rtcl-listings-sc-wrapper rtcl-elementor-widget">
	<div class="rtcl-listings-wrapper">
		<?php
		$class  = '';
		$class .= ! empty( $view ) ? 'rtcl-' . $view . '-view ' : 'rtcl-list-view ';
		$class .= ! empty( $style ) ? 'rtcl-' . $style . '-view ' : 'rtcl-style-1-view ';

		$class .= ! empty( $instance['rtcl_listings_column'] ) ? 'columns-' . $instance['rtcl_listings_column'] . ' ' : ' columns-4 ';
		$class .= ! empty( $instance['rtcl_listings_column_laptop'] ) ? 'laptop-columns-' . $instance['rtcl_listings_column_laptop'] . ' ' : ' laptop-columns-4 ';
		$class .= ! empty( $instance['rtcl_listings_column_tablet_extra'] ) ? 'tab-extra-columns-' . $instance['rtcl_listings_column_tablet_extra'] . ' ' : ' tab-extra-columns-3 ';
		$class .= ! empty( $instance['rtcl_listings_column_tablet'] ) ? ' tab-columns-' . $instance['rtcl_listings_column_tablet'] . ' ' : ' tab-columns-3';
		$class .= ! empty( $instance['rtcl_listings_column_mobile_extra'] ) ? 'mobile-extra-columns-' . $instance['rtcl_listings_column_mobile_extra'] . ' ' : ' mobile-extra-columns-2 ';
		$class .= ! empty( $instance['rtcl_listings_column_mobile'] ) ? 'mobile-columns-' . $instance['rtcl_listings_column_mobile'] . ' ' : ' mobile-columns-1';

		?>
		<div class="rtcl-listings <?php echo esc_attr( $class ); ?> ">
			<?php

			while ( $the_loops->have_posts() ) :
				$the_loops->the_post();
				$_id                 = get_the_ID();
				$post_meta           = get_post_meta( $_id );
				$listing             = new Listing( $_id );
				$listing_title       = null;
				$listing_meta        = null;
				$listing_description = null;
				$img                 = null;
				$labels              = null;
				$time                = null;
				$location            = null;
				$category            = null;
				$price               = null;
				$img_position_class  = '';
				$types               = null;
				$phone               = get_post_meta( $_id, 'phone', true );
				$custom_field 		 = null;
				$address 			 = get_post_meta( $_id, 'address', true );

				global $listing;
				$phone_url = str_replace(' ', '', $phone);

				?>

				<div <?php Functions::listing_class( [ 'rtcl-widget-listing-item', 'listing-item', $img_position_class ] ); ?>>
					<!-- Thumbnail Image Box -->
					<div class="item-img bg--gradient-50">

						<?php if ( $instance['rtcl_show_types'] || $instance['rtcl_show_labels'] ) { ?>
							<div class="listing-actions-buttons">
								<?php
									if ( $instance['rtcl_show_types'] || $listing->get_ad_type() ) {
									$listing_type = Listing_Functions::get_listing_type( $listing );

									if (!empty($listing_type['label'])) {
									?>
										<span class="listing-type-badge">
											<?php
												esc_html_e( 'For', 'clplace' );
												echo esc_html($listing_type['label']);
											?>
										</span>
								<?php }
								} ?>

								<?php
									if ( $instance['rtcl_show_labels'] ) {
										$labels = $listing->badges() ? $listing->badges() : '';
										echo wp_kses_post($labels);
									}
								?>
							</div>
						<?php } ?>

						<?php
						if ( $instance['rtcl_show_image'] ) {
							$image_size    = $instance['rtcl_thumb_image_size'];
							$the_thumbnail = $listing->get_the_thumbnail( $image_size );
							if ( $the_thumbnail ) { ?>
							<div class='listing-thumb'>
								<?php
									if ( rtcl()->has_pro() ) {
										ProTemplateHooks::sold_out_banner();
									}
								?>
								<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php the_title() ?>"><?php echo wp_kses_post($the_thumbnail); ?></a>
							</div>
						<?php }
						} ?>
						<ul class="meta-tags">
							<?php if ( $instance['rtcl_show_favourites'] ) { ?>
								<?php if ( is_user_logged_in() ) { ?>
									<li class="meta-item meta-favourite">
										<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
									</li>
								<?php } else { ?>
									<li class="meta-item meta-favourite">
										<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
									</li>
								<?php } ?>
							<?php } if ( rtcl()->has_pro() ) {
								if ( ! empty( $instance['rtcl_show_compare'] ) ){
								?>
								<li class="meta-compare">
									<?php
										$compare_ids = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
										$selected_class = '';
										if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
											$selected_class = ' selected';
										}
									?>
									<a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
										<i class="icon-center-arow"></i>
									</a>
								</li>
								<?php }
								}
								if ( rtcl()->has_pro() ) {
									if ( ! empty( $instance['rtcl_show_quick_view'] ) ) :
										?>
										<li class="rtin-el-button">
											<a class="rtcl-quick-view" href="#" title="<?php esc_attr_e( 'Quick View', 'clplace' ); ?>"  data-listing_id="<?php echo absint( $_id ); ?>">
												<i class="icon-eye"></i>
											</a>
										</li>
										<?php
									endif;
								}
							?>
						</ul>
					</div>

					<!-- All Meta Info Box -->
					<div class="item-content">
						<?php if ( $instance['rtcl_show_category'] ): ?>
							<div class="rt-categories">
								<?php echo wp_kses_post( $listing->the_categories( false, true )); ?>
							</div>
						<?php endif; ?>
						<div class="title-excerpt-box">
							<?php if ( $instance['rtcl_show_title'] ) { ?>
								<h3 class="listing-title rtcl-listing-title"><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title() ?></a></h3>
							<?php } ?>

							<?php
								if ( $instance['rtcl_show_review'] ) :
									Listing_Functions::clplace_listing_rating( $listing );
								endif;
							?>

							<?php
								if ( $instance['rtcl_show_description'] ) {
									$excerpt = get_the_excerpt( $_id );
								?>
									<div class="rtcl-short-description"><?php echo wp_kses_post( wpautop( $excerpt ) ); ?></div>
								<?php }
							?>
						</div>

						<?php if (!empty($instance['rtcl_show_custom_fields'])) { ?>
							<div class="custom-flelds-box">
								<?php ProTemplateHooks::loop_item_listable_fields(); ?>
							</div>
						<?php } ?>
						<?php if ( !empty ( $instance['rtcl_show_user'] || $instance['rtcl_show_location'] || $instance['rtcl_show_views'] || $instance['rtcl_show_category'] )): ?>
						<div class="all-meta-info-box">
							<ul class="rtcl-listing-meta-data">
								<?php if ( $instance['rtcl_show_user'] ) : ?>
									<li class="author">
										<i class="icon-author"></i>
										<?php esc_html_e( 'by ', 'clplace' ); ?>
										<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
											<a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
										<?php else : ?>
											<?php $listing->the_author(); ?>
										<?php endif; ?>
										<?php do_action( 'rtcl_after_author_meta', $listing->get_owner_id() ); ?>
									</li>
								<?php endif; ?>

								<?php if ( $instance['rtcl_show_location'] ) : ?>
									<li class="rt-location">
										<i class="icon-location"></i>
										<?php $listing->the_locations( true, true ); ?>
									</li>
								<?php endif; ?>

								<?php if ( $instance['rtcl_show_address'] ) : ?>
									<li class="rt-location">
										<i class="icon-home"></i>
										<?php  echo esc_html($address ); ?>
									</li>
								<?php endif; ?>

								<?php if ( $instance['rtcl_show_views'] ) : ?>
									<li class="rt-views">
										<i class="icon-eye"></i>
										<?php echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'clplace' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
									</li>
								<?php endif; ?>
							</ul>
						</div>
						<?php endif; ?>

						<div class="listing-footer">
							<?php
								if ( $instance['rtcl_show_price'] ) {
									$price_html = $listing->get_price_html();
									$price      = sprintf( '<div class="item-price">%s</div>', $price_html );
									echo wp_kses_post( wp_kses_stripslashes($price) );
								}
							?>
							<?php if ( !empty( $phone ) ){ ?>
								<a href="tel:<?php echo esc_url( $phone_url ); ?>" class="phone-no">
									<i class="icon-phone-call"></i>
									<?php echo esc_html( $phone ); ?>
								</a>
							<?php } ?>
						</div>
					</div>
				</div>

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>

		</div>
		<?php if ( ! empty( $instance['rtcl_listing_pagination'] ) ) { ?>
			<?php Pagination::pagination( $the_loops, true ); ?>
		<?php } ?>
	</div>
</div>
