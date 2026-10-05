<?php
/**
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
 * @package     classified-listing/templates
 * @version     1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Link;
use Rtcl\Helpers\Text;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RT\Clplace\Plugins\Listing_Functions;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;

$can_report_abuse = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;

?>

<div class="rtcl-listing-user-info">
    <div class="list-group">
        <?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { ?>
            <p class="login-message">
                <?php echo wp_kses(sprintf(__("Please <a href='%s'>login</a> to view the seller information.", "clplace"), esc_url(Link::get_my_account_page_link())), ['a' => ['href' => []]]); ?>
            </p>
			<?php } else {
				if ( clplace_option('single_sidebar_listing_info') == 'listing_owner_info' ){
					Listing_Functions::get_custom_listing_template( 'listing-owner-info');
				} else {
					Listing_Functions::get_custom_listing_template( 'listing-info');
				}
			}
        ?>

		<?php
			if (Fns::is_enable_chat() && ((is_user_logged_in() && $listing->get_author_id() !== get_current_user_id()) || !is_user_logged_in())):
				$chat_btn_class = [ 'rtcl-chat-link' ];
				$chat_url = Link::get_my_account_page_link();
				$chat_label = esc_html__( "Quick Chat", 'clplace');
				$chant_enable_class = "rtcl-contact-seller";
				if ( is_user_logged_in() ) {
					$chat_url = '#';
					array_push( $chat_btn_class, 'rtcl-contact-seller' );
				} else {
					array_push( $chat_btn_class, 'rtcl-no-contact-seller' );
					$chat_label = esc_html__('Login for chat', 'clplace');
					$chant_enable_class = 'need-to-logdin';
				}
			?>
			<div class="chat-form">
				<div class=<?php echo esc_attr( $chant_enable_class ); ?>>
					<a class="<?php echo esc_attr( implode( ' ', $chat_btn_class ) ) ?>" href="<?php echo esc_url( $chat_url ) ?>" data-listing_id="<?php the_ID() ?>">
						<i class="icon-talk-bubbles-line"></i>
						<?php echo esc_html( $chat_label ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( $has_contact_form && $email ) : ?>
			<div class="contact-form">
				<div class='rtcl-do-email list-group-item'>
					<div class='media'>
						<span class='icon-massage-1'></span>
						<div class='media-body'>
							<a class="rtcl-do-email-link" href='#'>
								<?php echo wp_kses_post( Text::get_single_listing_email_button_text() ); ?>
							</a>
						</div>
					</div>
					<?php Functions::print_html( $email_to_seller_form, true ); ?>
				</div>
			</div>
		<?php endif; ?>
    </div>
</div>

<?php
$detail_option    = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );
$show_favourite   = Functions::is_enable_favourite();
$show_compare     = Fns::is_enable_compare();
$show_report      = $can_report_abuse;
$show_claim       = function_exists( 'rtclClaimListing' ) && ClaimFunctions::claim_listing_enable();
$show_print       = in_array( 'print', $detail_option );

if ( $show_favourite || $show_compare || $show_report || $show_claim || $show_print ) : ?>
<div class="rtcl-single-actions">
	<H3 class="title"><?php esc_html_e( 'Like By', 'clplace' ); ?></H3>
	<ul class="meta-tags">
		<?php if ( $show_favourite ) { ?>
			<li class="meta-favourite">
				<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
			</li>
		<?php } if ( $show_compare ) { ?>
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
		<?php } if ( $show_report ) { ?>
			<li class="single-listing-custom-fields-action report-abuse-li">
				<?php if ( is_user_logged_in() ): ?>
                    <a href="javascript:void(0)" data-toggle="modal"<a href="javascript:void(0)" data-toggle="modal" id="rtcl-report-abuse-modal-link">
						<i class="icon-info-outline"></i>
					</a>
				<?php else: ?>
					<a href="javascript:void(0)" class="rtcl-require-login">
						<i class="icon-info-outline"></i>
					</a>
				<?php endif; ?>

                <?php Listing_Functions::get_repost_abuse(); ?>
			</li>
		<?php } ?>
        <?php if ( $show_claim ) { ?>
        <li class='claim-listing-li' data-toggle="tooltip" data-placement="top" data-trigger="hover" title="<?php esc_attr_e( "Claim", "clplace" ) ?>">
			<?php if ( is_user_logged_in() ): ?>
                <span data-toggle="tooltip" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#rtcl-claim-listing-modal">
                        <i class="icon-attention"></i>
                    </a>
                </span>
			<?php else: ?>
                <a href="javascript:void(0)" data-toggle="tooltip" class="rtcl-require-login" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                    <i class="icon-attention"></i>
                </a>
			<?php endif; ?>
        </li>
		<?php } ?>
		<?php if ( $show_print ) : ?>
		<li><a href="#" onclick="window.print();" title="<?php esc_attr_e( "Print", "clplace" ) ?>"><i class="icon-printer"></i></a></li>
		<?php endif; ?>
	</ul>
</div>
<?php endif; ?>

<?php
    if ( $can_report_abuse ){
        //Listing_Functions::get_repost_abuse();
    }

    if (!is_user_logged_in() && Functions::is_enable_favourite()) {
        Listing_Functions::logout_user_favourite();
    }

    do_action( 'rtcl_single_listing_after_action', $listing->get_id() );
?>
