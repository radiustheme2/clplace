<?php
/**
 * Template part for displaying header offcanvas
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Options\Opt;

?>

<div class="clplace-offcanvas-drawer">
	<div class="offcanvas-header">
		<div class="site-branding pr-15">
			<?php echo wp_kses_post( clplace_offcanvas_logo() ); ?>
		</div><!-- .site-branding -->
		<a class="menu-bar trigger-off-canvas" href="#"><i class="icon-close-1"></i></a>
	</div>
	<nav class="offcanvas-navigation" role="navigation">
		<?php
		if ( has_nav_menu( 'primary' ) ) :
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'walker'         => new RT\Clplace\Core\WalkerNav(),
				)
			);
		endif;
		?>
	</nav><!-- .clplace-navigation -->
	<?php if(! Opt::$has_top_bar) { ?>
	<div class="header-top-info">
		<ul class="topbar-left d-flex gap-15 align-items-start">
			<?php if( !empty(clplace_option( 'rt_contact_address' )) && clplace_option( 'rt_topbar_address' ) ) { ?>
				<li class="site-address">
					<i class="icon-location-1"></i>
					<?php echo wp_kses( clplace_option( 'rt_contact_address' ) , 'allowed_html' );?>
				</li>
			<?php } if( !empty(clplace_option( 'rt_phone' )) &&  clplace_option( 'rt_topbar_phone' ) ) { ?>
				<li class="site-phone">
					<i class="icon-phone-call"></i>
					<a href="tel:<?php echo esc_attr( clplace_option( 'rt_phone' ) );?>"><?php echo wp_kses( clplace_option( 'rt_phone' ) , 'allowed_html' );?></a>
				</li>
			<?php } if( !empty(clplace_option( 'rt_email' )) &&  clplace_option( 'rt_topbar_email' ) ) { ?>
				<li class="site-email">
					<i class="icon-mi_email"></i>
					<a href="mailto:<?php echo esc_attr( clplace_option( 'rt_email' ) );?>"><?php echo wp_kses( clplace_option( 'rt_email' ) , 'allowed_html' );?></a>
				</li>
			<?php } ?>
		</ul>
		<ul class="topbar-right">
			<li class="social-label"><?php echo esc_html( clplace_option( 'rt_follow_us_label' ) ) ?></li>
			<li class="social-icon">
				<?php clplace_get_social_html( '#555' ); ?>
			</li>
		</ul>
	</div>
	<?php } ?>
</div><!-- .container -->

<div class="clplace-body-overlay"></div>
