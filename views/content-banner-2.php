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

use RT\Clplace\Options\Opt;
use RT\Clplace\Helpers\Fns;

if ( ! Opt::$has_banner ) {
	return;
}

$banner_height_css = '';
$banner_image_css = '';

if ( ! empty( Opt::$banner_image ) ) {
	$image_url = wp_get_attachment_image_src( Opt::$banner_image, 'full' );

	$banner_image_css .= isset( $image_url[0] ) ? "background-image:url({$image_url[0]});" : '';

	if ( ! empty( Opt::$banner_height ) ) {
		$banner_height_css .= "min-height:" . rtrim( Opt::$banner_height, 'px' ) . "px;";
	}

	if ( ! empty( clplace_option( 'rt_banner_image_attr' ) ) ) {
		$bg_attr = json_decode( clplace_option( 'rt_banner_image_attr' ), ARRAY_A );

		if ( ! empty( $bg_attr['position'] ) ) {
			$banner_image_css .= "background-position: {$bg_attr['position']};";
		}
		if ( ! empty( $bg_attr['attachment'] ) ) {
			$banner_image_css .= "background-attachment: {$bg_attr['attachment']};";
		}
		if ( ! empty( $bg_attr['repeat'] ) ) {
			$banner_image_css .= "background-repeat: {$bg_attr['repeat']};";
		}
		if ( ! empty( $bg_attr['size'] ) ) {
			$banner_image_css .= "background-size: {$bg_attr['size']};";
		}
	}
}
$has_image = isset( $image_url[0] );
if ( in_array( Opt::$single_style, [ '3', '4' ] ) ) {
	$has_image        = false;
	$banner_image_css = '';
}

$classes = Fns::class_list( [
	'clplace-breadcrumb-wrapper banner-layout-2',
	$has_image ? 'has-bg' : 'no-bg'
] );
?>

<div class="<?php echo esc_attr( $classes ) ?>" style="<?php echo esc_attr( $banner_image_css ) ?>">
	<?php if ( Opt::$has_breadcrumb )  : ?>
		<div class="container">
			<div class="breadcrumb-content <?php echo esc_attr( clplace_option('rt_banner_content_alignment') ); ?>" style="<?php echo esc_attr( $banner_height_css ) ?>">
				<h1 class="breadcrumb-title">
					<?php breadcrumb_title() ?>
				</h1>
				<nav class="breadcrumb-nav">
					<?php
						if ( ( class_exists( 'Rtcl' ) ) && ( is_archive( 'rtcl_listing' ) || is_singular( 'rtcl_listing' ) || is_tax( 'rtcl_category' ) ) ) {
							listing_breadcrumb();
						} else {
							clplace_breadcrumb();
						}
					?>
				</nav>
				<?php if (!empty(clplace_option('rt_banner_shape_image'))): ?>
					<div class="shapes">
						<?php
							echo wp_get_attachment_image( clplace_option('rt_banner_shape_image'), 'full' );
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
