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

$meta_list = clplace_option( 'rt_blog_meta', '', true );
if ( clplace_option( 'rt_blog_above_cat_visibility' ) ) {
	$category_index = array_search( 'category', $meta_list );
	unset( $meta_list[ $category_index ] );
}
?>
<article data-post-id="<?php the_ID(); ?>" <?php post_class( clplace_post_class() ); ?>>
	<div class="article-inner-wrapper">
		<?php clplace_post_thumbnail( 'full' ); ?>
		<div class="entry-wrapper">
			<header class="entry-header">
				<?php
                    clplace_separate_meta( 'title-above-meta' );
                    if ( ! is_single() ) {
                        the_title( sprintf( '<h2 class="entry-title default-max-width"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' );
                    } else {
                        the_title( '<h2 class="entry-title default-max-width">', '</h2>' );
                    }
                    if ( ! empty( $meta_list ) && clplace_option( 'rt_meta_visibility' ) ) {
                        echo wp_kses_post( clplace_post_meta( [
                            'with_list'     => true,
                            'with_icon'     => true,
                            'include'       => $meta_list,
                            'author_prefix' => clplace_option( 'rt_author_prefix' ),
                        ] ) );
				    }
				?>
			</header>

			<?php if ( clplace_option( 'rt_blog_content_visibility' ) ) : ?>
				<div class="entry-content">
					<?php clplace_entry_content() ?>
				</div>
			<?php endif; ?>

			<?php clplace_entry_footer(); ?>
		</div>
	</div>
</article>
