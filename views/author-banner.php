<?php
/**
 * Template part for displaying banner content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RT\Clplace\Plugins\Listing_Functions;

// Get the author information
$author_id = get_the_author_meta('ID');
$author_name = get_the_author_meta('display_name');
$author_description = get_the_author_meta('description');
$author_website = get_the_author_meta('user_url');
$author_avatar = get_avatar_url($author_id, array('size' => 140));
$authorAddress = get_user_meta( $author_id, '_rtcl_address', true );
$authorPhone = get_user_meta( $author_id, '_rtcl_phone', true );
$authorWhatsapp = get_user_meta( $author_id, '_rtcl_whatsapp_number', true );
$pp_id = absint( get_user_meta( $author_id, '_rtcl_pp_id', true ) );
$status = apply_filters( 'rtcl_user_offline_text', esc_html__( 'User is offline Now', 'clplace' ) );
if ( Fns::is_online( $author_id ) ) {
	$status = apply_filters( 'rtcl_user_online_text', esc_html__( 'User is online now!', 'clplace' ) );
}
$social_list = Functions::get_user_social_profile( $author_id );

?>

<div class="clplace-breadcrumb-wrapper author-banner">
	<div class="container">
		<div class="breadcrumb-content">
			<div class="listing-author listing-owner">
				<div class="author-logo-wrapper <?php echo esc_attr( strtolower( $status ) ); ?>">
					<?php
						$pp_id = absint( get_user_meta( $author_id, '_rtcl_pp_id', true ) );
						echo wp_kses_post( $pp_id ? wp_get_attachment_image( $pp_id, [140, 140]) : get_avatar( $author_id, 140 ) );
					?>
					<p class="rtcl-user-status <?php echo esc_attr( strtolower( $status ) ); ?>"></p>
				</div>
				<div class="author-info-wrapper">
					<div class="member-since">
						<?php
							$since = date( "F, Y", strtotime(get_userdata($author_id)->user_registered ));
							echo esc_html( sprintf( __( "Member Since : %s", "clplace" ), $since ) );
						?>
					</div>
					<h2 class="author-name">
						<?php echo esc_html( $author_name ); ?>
                        <div class="rtin-user-item">
							<?php do_action('rtcl_after_author_meta', $author_id ); ?>
                        </div>
					</h2>
					<p class="author-address">
						<?php echo esc_html( $authorAddress ); ?>
					</p>
					<div class="phone-whatsapp">
						<?php
							Listing_Functions::the_phone($authorPhone, '', '');
							Listing_Functions::the_phone('', $authorWhatsapp, '');
						?>
					</div>
				</div>
			</div>
			<?php if ( ! empty( $social_list ) ) { ?>
				<div class="rtcl-user-social post-socials-button rtcl">
					<?php
						foreach ( $social_list as $item => $value ) {
							?>
							<a class="<?php echo esc_attr( $item ); ?>" href="<?php echo esc_url( $value ) ?>" target="_blank">
								<?php if ( 'twitter' === $item ) { ?>
                                    <i class="rtcl-icon fa-brands fa-x-twitter"></i>
								<?php } elseif ( 'tiktok' === $item ) { ?>
                                    <i class="rtcl-icon fa-brands fa-tiktok"></i>
								<?php } else { ?>
                                    <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
								<?php } ?>
							</a>
							<?php
						}
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
