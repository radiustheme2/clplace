<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use RT\Clplace\Helpers\Fns;
use RT\Clplace\Plugins\Listing_Functions;

defined('ABSPATH') || exit;

global $listing;

$content_columns = Fns::content_columns();

?>

<?php Listing_Functions::get_custom_listing_template( 'single/listing-single-header-2' ); ?>

<div class="container">
	<div class="row align-stretch">
        <div class="col-xl-12 col-lg-12">
			<?php Listing_Functions::get_custom_listing_template( 'single/single-content-area'); ?>
		</div>
	</div>
</div>
