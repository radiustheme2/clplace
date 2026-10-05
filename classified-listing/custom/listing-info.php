<?php
/**
 * @var string  $address
 * @var string  $phone
 * @var string  $whatsapp_number
 * @var string  $email
 * @var string  $website
 * @var array   $phone_options
 * @var bool    $has_contact_form
 * @var string  $email_to_seller_form
 * @var Listing $listing
 * @var array   $locations
 * @var int     $listing_id Listing id
 * @author      RadiusTheme
 * @package     clplace/templates
 * @version     1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RT\Clplace\Plugins\Listing_Functions;

global $listing;
$owner_id          = $listing->get_owner_id();
$ownerUrl          = get_author_posts_url($owner_id);
$listingAddress    = get_post_meta( $listing->get_id(), 'address', true );
$listingPhone      = get_post_meta( $listing->get_id(), 'phone', true );
$listingWhatsapp   = get_post_meta( $listing->get_id(), '_rtcl_whatsapp_number', true );
$listingEmail      = get_post_meta( $listing->get_id(), 'email', true );
$website           = get_post_meta( $listing->get_id(), 'website', true );
$listingWebsite    = str_replace(['https://', 'http://'], '', $website );
$has_contact_form  = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_contact_form', false, 'checkbox' );
$status = apply_filters( 'rtcl_user_offline_text', esc_html__( 'Offline Now', 'clplace' ) );
if ( Fns::is_online( $listing->get_owner_id() ) ) {
	$status = apply_filters( 'rtcl_user_online_text', esc_html__( 'Online now!', 'clplace' ) );
}
?>

<div class="listing-author listing-owner">
    <h3 class="title"><?php esc_html_e('Posted By', 'clplace'); ?></h3>
    <div class="author-logo-wrapper <?php echo esc_attr( strtolower( $status ) ); ?>">
        <div class="author-avatar-wrapper">
            <?php
            $pp_id = absint( get_user_meta( $listing->get_owner_id(), '_rtcl_pp_id', true ) );
            if ( $listing->can_add_user_link() ): ?>
                <a href="<?php echo esc_url($listing->get_the_author_url()); ?>"><?php echo wp_kses_post( $pp_id ? wp_get_attachment_image( $pp_id, [70, 70]) : get_avatar( $listing->get_author_id(), 70 ) ); ?></a>
            <?php else:
                echo wp_kses_post( $pp_id ? wp_get_attachment_image( $pp_id, [70, 70]) : get_avatar( $listing->get_author_id(), 70 ) );
            endif;
            ?>
        </div>
        <div class="author-name-wrapper">
            <h4 class="author-name">
                <?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                    <a class="author-link" href="<?php echo esc_url( $ownerUrl ); ?>">
                        <?php echo esc_html( $listing->get_owner_name() ); ?>
                    </a>
                <?php else: ?>
                    <?php echo wp_kses_post( $listing->get_owner_name() ); ?>
                <?php endif; ?>
                <div class="rtin-user-item">
                    <?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                </div>
            </h4>
            <div class="member-since">
                <?php
                $since = date( "Y", strtotime(get_userdata($owner_id)->user_registered ));
                echo esc_html( sprintf( esc_html__( "Member Since : %s", "clplace" ), $since ) );
                ?>
            </div>
            <span class="rtcl-user-status <?php echo esc_attr( strtolower( $status ) ); ?>"><span class="user-staus-text"><?php echo wp_kses_post( $status ); ?></span></span>
        </div>
    </div>
    <div class="author-info-wrapper">
        <ul class="info-list">
            <?php if ( $listingAddress ){ ?>
                <li>
                    <div class="icon d-flex justify-content-center align-items-center">
                        <i class="icon-location"></i>
                    </div>
                    <?php echo esc_html( $listingAddress ); ?>
                </li>
            <?php } if ( $listingEmail && Functions::check_visibility( $listing->get_author_id(), 'email' ) ){ ?>
                <li>
                    <div class="icon d-flex justify-content-center align-items-center">
                        <i class="icon-massage-1"></i>
                    </div>
                    <a class="rtcl-phone-link" href="mailto:<?php echo esc_attr( $listingEmail ); ?>" target="_blank">
                        <?php echo esc_html( $listingEmail ); ?>
                    </a>
                </li>
            <?php } if ( $website ){ ?>
                <li>
                    <div class="icon d-flex justify-content-center align-items-center">
                        <i class="icon-web"></i>
                    </div>
                    <a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank"
                        <?php echo Functions::is_external( $website ) ? ' rel="nofollow"' : ''; ?>>
                        <?php echo esc_html( $listingWebsite ); ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<?php
    if ( Functions::check_visibility( $owner_id, 'phone' ) ) {
        Listing_Functions::the_phone( $listingPhone, '', '' );
    }
    if ( Functions::check_visibility( $owner_id, 'whatsapp' ) ) {
        Listing_Functions::the_phone( '', $listingWhatsapp, '' );
    }
?>
