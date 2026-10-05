<?php
/**
 * @author  RadiusTheme
 *
 * Locationbox style.
 *
 * @package  Classifid-listing
 * @since   2.0.10
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$count_text = $settings['count_text'] ? $settings['count_text'] : 'Listings';

$count_html = sprintf( _nx( '%s ', '%s ', $count, 'Number of Listing Services', 'clplace' ), number_format_i18n( $count ) );

$link_start   = $settings['enable_link'] ? '<a href="' . $permalink . '">' : '';
$link_end     = $settings['enable_link'] ? '</a>' : '';
$location_box = $settings['rtcl_location_style'] ? $settings['rtcl_location_style'] : ' style-1';
$class        = $settings['display_count'] ? ' rtin-has-count ' : '';
$class       .= 'wow '. $settings['custom_animation'] .' animate__animated location-box-' . $location_box;

?>

<div class="rtcl-el-listing-location-box location-box-pro <?php echo esc_attr( $class ); ?>"  data-wow-duration="1200ms" data-wow-delay="<?php echo esc_attr( $settings['custom_animation_delay'] ); ?>">
	<div class="rtcl-image-wrapper">
		<?php echo wp_kses_post( $link_start ); ?>
		<div class="rtin-img"></div>
		<?php echo wp_kses_post( $link_end ); ?>
	</div>

	<div class="rtin-content">
		<h3 class="rtin-title">
			<?php
                if ( $settings['enable_link'] ) {
                    ?>
                        <a href="<?php echo esc_url( $permalink ); ?>">
                            <?php echo esc_html( $title ); ?>
                        </a>
                        <?php
                } else {
                    echo esc_html( $title );
                }
			?>
		</h3>
		<?php if ( $settings['enable_link'] && !empty( $icon ) ) { ?>
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php echo wp_kses_post( wp_kses_stripslashes( $icon ) ); ?>
			</a>
		<?php } ?>
	</div>

	<?php if ( $settings['display_count'] ) : ?>
        <div class="rtin-counter">
            <?php echo esc_html( $count_html ); ?><?php echo esc_html( $count_text ); ?>
        </div>
	<?php endif; ?>
</div>
