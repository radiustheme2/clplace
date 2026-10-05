<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Helpers\Fns;

get_header();

?>
<div class="search-template-content-wrapper">
	<div class="container">

		<div class="row align-stretch">

			<div class="<?php echo esc_attr( Fns::content_columns() ); ?>">

				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<div class="row">
							<?php
							if ( have_posts() ) :
								/* Start the Loop */
								while ( have_posts() ) :
									the_post();
									get_template_part( 'views/content', get_post_format() );
								endwhile;
							else :
								get_template_part( 'views/content', 'none' );
							endif;
							?>
						</div>

						<div class="post-pagination">
							<?php the_posts_navigation(); ?>
						</div>

					</main><!-- #main -->
				</div><!-- #primary -->

			</div><!-- .col- -->

			<?php get_sidebar(); ?>

		</div><!-- .row -->

	</div><!-- .container -->
</div>
<?php
get_footer();
