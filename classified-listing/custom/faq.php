<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\ClProperty\Listing_Functions;
use Rtcl\Helpers\Functions;

global $listing;

$listing_form = $listing->getForm();
$sections = $listing_form->getSectionById('faq_section');

$faq_items = get_post_meta( $listing->get_id(), "faq_items", true );

?>

<?php if (! empty( $faq_items )) { ?>

    <div class="rtcl-single-listing-details faq-box">
	    <?php if (!empty( $sections['title'] )) { ?>
            <h3 class="desc-title"><?php echo esc_attr( $sections['title'] ); ?></h3>
	    <?php } ?>
        <div class="accordion-container">
		    <?php foreach ( $faq_items as $faq ):
			    $title   = $faq['title'] ?? '';
			    $desc    = $faq['description'] ?? '';
			    ?>
                <div class="accordion">
				    <?php if (!empty( $title )) { ?>
                        <button class="menu-button">
                            <span class="icon"></span>
						    <?php echo esc_html( $title ); ?>
                        </button>
				    <?php } if (!empty( $title )){ ?>
                        <div class="content">
                            <p><?php echo esc_html( $desc ); ?></p>
                        </div>
				    <?php } ?>
                </div>
		    <?php endforeach; ?>
        </div>
	</div>
	
<?php } ?>