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

use RT\Clplace\Options\Opt;

?>
<article data-post-id="<?php the_ID(); ?>" <?php post_class( clplace_post_class() ); ?>>
	<div class="article-inner-wrapper">

		<?php if ( ! in_array( Opt::$single_style, [ '2', '3', '4' ] ) ) : ?>
			<?php clplace_post_single_thumbnail(); ?>
		<?php endif; ?>

		<div class="entry-wrapper">
			<?php clplace_single_entry_header(); ?>

			<div class="entry-content">
				<?php clplace_entry_content() ?>
			</div>

			<?php clplace_entry_footer(); ?>
		</div>
	</div>
</article>
