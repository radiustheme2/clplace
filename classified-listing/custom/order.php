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
$sections = $listing_form->getSectionById('order_section');

$order_items = get_post_meta( $listing->get_id(), "order_items", true );

//&plus;

?>

<?php if (! empty( $order_items )) { ?>

    <div class="rtcl-single-listing-details order-box wow animate__fadeInUp animate__animated animate__animated" data-wow-duration="1200ms" data-wow-delay="600ms">
        <div class="listing-order">
	        <?php if (!empty( $sections['title'] )) { ?>
                <h3 class="desc-title"><?php echo esc_attr( $sections['title'] ); ?></h3>
	        <?php } ?>
            <div class="order-card-wrapper d-flex">
                <?php foreach ( $order_items as $order ):
                    $number  = $order['number'] ?? '';
                    $title   = $order['title'] ?? '';
                    $desc    = $order['description'] ?? '';
                    ?>
                <div class="order-card">
                    <?php if (!empty( $number )){ ?>
                        <div class="d-flex">
                            <div class="number-count">
                                <?php echo esc_html( $number ); ?>
                            </div>
                        </div>
                    <?php } if (!empty( $title || $desc )){ ?>
                        <div class="order-card-content">
                            <?php if (!empty( $number )){ ?>
                                <h4 class="title">
                                    <?php echo esc_html( $title ); ?>
                                </h4>
                            <?php } if (!empty( $number )){ ?>
                                <p class="para-text"><?php echo esc_html( $desc ); ?></p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
	</div>
	
<?php } ?>