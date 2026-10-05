<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

use Rtcl\Helpers\Functions;
use RT\Clplace\Helpers\Fns;

defined( 'ABSPATH' ) || exit;

$content_columns = Fns::content_columns();

get_header( 'listing' );

?>

<div class="listing-archive-template-content-wrapper">
	<div class="container">
		<div class="row align-stretch">
			<?php
				$listing_page_id = Functions::get_page_id( 'listings' );

				if ( post_password_required( $listing_page_id ) ) { ?>
					<div class="rtcl-wrapper">
						<?php echo wp_kses_post( get_the_password_form( $listing_page_id ) ); ?>
					</div>
				<?php } else { ?>
					<div class="<?php echo esc_attr( $content_columns ); ?>">

						<?php
						/**
						 * Hook: rtcl_before_main_content.
						 *
						 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
						 */
						do_action( 'rtcl_before_main_content' );

						?>
						<?php

						if ( rtcl()->wp_query()->have_posts() ) {

							/**
							 * Hook: rtcl_before_listing_loop.
							 *
							 * @hooked TemplateHooks::output_all_notices() - 10
							 * @hooked TemplateHooks::listings_actions - 20
							 *
							 */
							do_action( 'rtcl_before_listing_loop' );


							Functions::listing_loop_start();

							/**
							 * Prepend listings
							 */
							do_action( 'rtcl_listing_loop_prepend_data' );

							while ( rtcl()->wp_query()->have_posts() ) : rtcl()->wp_query()->the_post();

								/**
								 * Hook: rtcl_listing_loop.
								 */
								do_action( 'rtcl_listing_loop' );

								Functions::get_template_part( 'content', 'listing' );

							endwhile;

							Functions::listing_loop_end();

							/**
							 * Hook: rtcl_after_listing_loop.
							 *
							 * @hooked TemplateHook::pagination() - 10
							 */
							do_action( 'rtcl_after_listing_loop' );
						} else {

							/**
							 * Prepend listings
							 */
							ob_start();
							do_action( 'rtcl_listing_loop_prepend_data' );
							$listing_loop_prepend_data = ob_get_clean();
							if ( $listing_loop_prepend_data ) {
								Functions::listing_loop_start();
								echo wp_kses_post( $listing_loop_prepend_data );
								Functions::listing_loop_end();
							}

							/**
							 * Hook: rtl_no_listings_found.
							 *
							 * @hooked no_listings_found - 10
							 */
							do_action( 'rtcl_no_listings_found' );
						}

						/**
						 * Hook: rtcl_after_main_content.
						 *
						 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
						 */
						do_action( 'rtcl_after_main_content' );
					?>
					</div>
					<?php
					/**
					 * Hook: rtcl_sidebar.
					 *
					 * @hooked rtcl_get_sidebar - 10
					 */
					do_action( 'rtcl_sidebar' );
				}
			?>
		</div>
	</div>
</div>
<?php
get_footer( 'listing' );
