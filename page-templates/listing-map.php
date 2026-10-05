<?php
/**
 * Template Name: Listing Map
 *
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

?>
<div id="primary" class="product-grid listing-map-wrapper bg--accent">
  	<div class="container-fluid full-width p0">
		<div class="custom-row">
			<div class="custom-column">
				<div class="listing-map-wrapper">
					<?php
						if ( get_the_content() ) {
							the_content();
						} else {
							echo do_shortcode( '[rtcl_listings map="1" columns="2" paginate="true" limit="6"]' );
						}
					?>
				</div>
			</div>
		</div>
  	</div>
</div>

<?php get_footer(); ?>

