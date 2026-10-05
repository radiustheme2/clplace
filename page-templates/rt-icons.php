<?php
/**
 * Template Name: RT Icons
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>
	<div class="container">
		<div class="row pt-50 pb-50 d-flex gap-15">
			<?php
				echo clplace_get_svg( 'search' );
				echo clplace_get_svg( 'facebook' );
				echo clplace_get_svg( 'twitter' );
				echo clplace_get_svg( 'linkedin' );
				echo clplace_get_svg( 'instagram' );
				echo clplace_get_svg( 'pinterest' );
				echo clplace_get_svg( 'tiktok' );
				echo clplace_get_svg( 'youtube' );
				echo clplace_get_svg( 'snapchat' );
				echo clplace_get_svg( 'whatsapp' );
				echo clplace_get_svg( 'reddit' );
			?>
		</div>
	</div>
<?php
get_footer();
