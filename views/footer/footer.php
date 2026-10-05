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

$footer_width = 'container'.clplace_option('rt_footer_width');

?>

<?php if ( is_active_sidebar( 'rt-footer-sidebar' ) ) : ?>
	<div class="footer-widgets-wrapper">
		<div class="footer-container <?php echo esc_attr($footer_width) ?>">
			<div class="footer-widgets row">
				<?php dynamic_sidebar( 'rt-footer-sidebar' ); ?>
			</div>
		</div>
	</div><!-- .site-info -->
<?php endif; ?>

<?php if ( ! empty( clplace_option( 'rt_footer_copyright' ) ) ) : ?>
	<div class="footer-copyright-wrapper">
		<div class="footer-container <?php echo esc_attr( $footer_width ); ?>">
			<div class="copyright-content <?php echo esc_attr( clplace_option('rt_footer_copyright_alignment') ); ?>">
				<div class="copyright-text">
					<?php echo wp_kses_post( clplace_html( str_replace( '[y]', date( 'Y' ), clplace_option( 'rt_footer_copyright' ) ) ) ); ?>
				</div>
				<?php if ( clplace_option('rt_footer_social') ) : ?>
					<div class="social-media">
						<ul class="social-media-list">
							<li class="social-icon">
								<label><?php echo esc_html( clplace_option( 'rt_follow_us_label' ) ) ?></label>
								<?php clplace_get_social_html( '#555' ); ?>
							</li>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php clplace_scroll_top(); ?>
