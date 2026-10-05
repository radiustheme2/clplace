<?php
/**
 * Template part for displaying header
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$logo_h1      = ! is_singular( [ 'post' ] );
$menu_classes = clplace_option( 'rt_menu_alignment' );

?>

	<div class="main-header-section header-layout-1">
		<div class="header-container rt-container<?php echo esc_attr( clplace_option( 'rt_header_width' ) ) ?>">

			<div class="row align-middle m-0 menu-items-row">

				<div class="site-branding pr-15">
					<?php echo wp_kses_post( clplace_site_logo() ); ?>
				</div><!-- .site-branding -->

				<nav class="clplace-navigation pl-15 pr-15 <?php echo esc_attr( $menu_classes ) ?>" role="navigation">
					<?php
					wp_nav_menu( [
						'theme_location' => 'primary',
						'menu_class'     => 'clplace-navbar',
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'fallback_cb'    => 'clplace_custom_menu_cb',
						'walker'         => has_nav_menu( 'primary' ) ? new RT\Clplace\Core\WalkerNav() : '',
					] );
					?>
				</nav><!-- .clplace-navigation -->

				<?php clplace_menu_icons_group(); ?>

			</div><!-- .row -->

		</div><!-- .container -->
	</div>
<?php
