<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Options\Opt;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
	if ( clplace_option( 'rt_preloader' ) == 1 ) {
		do_action('site_prealoader');
	}
?>

<div id="page" class="site">
	<header id="masthead" class="site-header" role="banner">
		<?php get_template_part( 'views/header/topbar', Opt::$topbar_style ); ?>
		<?php get_template_part( 'views/header/header', Opt::$header_style ); ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<?php
			if (is_author()) {
				get_template_part( 'views/author-banner' );
			} else {
				get_template_part( 'views/content-banner', Opt::$banner_style );
			}
		?>
