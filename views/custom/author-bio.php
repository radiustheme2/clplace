<?php
/**
 * Template part for single post author bio
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php if (get_the_author_meta('description')) : ?>
	<div class="blog-author-bio">
		<div class="info-item avatar">
			<?php echo get_avatar(get_the_author_meta('user_email'), '95'); ?>
		</div>
		<div class="info-item avatar-text">
			<h4 class="author-title"><?php esc_html(the_author_meta('display_name')); ?></h4>
			<?php echo wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) ); ?>
		</div>
	</div>
<?php endif; ?>
