<?php
/**
 * Template part for displaying footer
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer_container = 'container' . clplace_option( 'rt_footer_width' );

?>

<?php if ( clplace_option('rt_footer_menu') && has_nav_menu( 'footer' ) ) : ?>
	<div class="footer-menu-wrapper">
		<div class="footer-container <?php echo esc_attr( $footer_container ) ?>">
			<div class="row">

				<?php clplace_scroll_top(); ?>

				<nav id="footer-menu" class="clplace-navigation col-md-12 <?php echo esc_attr( clplace_option( 'rt_footer_menu_alignment' ) ) ?>" role="navigation">
					<?php
					wp_nav_menu( [
						'theme_location' => 'footer',
						'menu_class'     => 'clplace-navbar',
						'items_wrap'     => '<ul id="%1$s" class="%2$s clplace-footer-menu">%3$s</ul>',
						'fallback_cb'    => 'clplace_custom_menu_cb',
						'walker'         => has_nav_menu( 'footer' ) ? new RT\Clplace\Core\WalkerNav() : '',
					] );
					?>
				</nav><!-- .footer-navigation -->
			</div>
		</div>
	</div><!-- .footer-fop -->
<?php endif; ?>

<?php if ( ! empty( clplace_option( 'rt_footer_copyright' ) ) ) : ?>
	<div class="footer-copyright-wrapper">
		<div class="footer-container <?php echo esc_attr( $footer_container ) ?>">
			<div class="row align-items-center">
				<div class="col-md-6">
					<div class="footer-copyright-logo text-left">
						<?php echo wp_kses_post( clplace_footer_logo() ); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="copyright-text text-right">
						<?php echo wp_kses_post( clplace_html( str_replace( '[y]', date( 'Y' ), clplace_option( 'rt_footer_copyright' ) ) ) ); ?>
					</div>
				</div>
			</div>
		</div>

	</div>
<?php endif; ?>
