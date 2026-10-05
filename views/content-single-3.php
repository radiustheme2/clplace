<?php
/**
 * Template part for displaying content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$meta_list = clplace_option( 'rt_single_meta', '', true );
$meta      = clplace_option( 'rt_blog_above_cat_visibility' );
$meta      = clplace_option( 'rt_single_above_meta_style' );
if ( clplace_option( 'rt_single_above_cat_visibility' ) ) {
	$category_index = array_search( 'category', $meta_list );
	unset( $meta_list[ $category_index ] );
}

?>

<article data-post-id="<?php the_ID(); ?>" <?php post_class( clplace_post_class() ); ?>>
	<div class="article-inner-wrapper">

		<div class="entry-wrapper">
			<?php if ( clplace_option( 'rt_blog_content_visibility' ) ) : ?>
				<div class="entry-content">
					<?php clplace_entry_content() ?>
				</div>
			<?php endif; ?>

			<?php clplace_entry_footer(); ?>
		</div>
	</div>
</article>
