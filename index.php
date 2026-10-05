<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Helpers\Fns;
use RT\Clplace\Modules\Pagination;

get_header();
$content_columns = Fns::content_columns();

?>
<div class="archive-template-content-wrapper">
	<div class="container">

		<div class="row align-stretch">

			<div class="<?php echo esc_attr( $content_columns ); ?>">

				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<div class="row archive-post-wrapper rt-masonry-grid">
							<?php
							if ( have_posts() ) :
								/* Start the Loop */
								while ( have_posts() ) :
									the_post();
									get_template_part( 'views/content', clplace_option('rt_blog_style') );
								endwhile;
							else :
								get_template_part( 'views/content', 'none' );
							endif;
							?>
						</div>
						<?php if(clplace_option('rt_blog_pagination_type') == 'default'){ ?>
                            <div class="post-pagination pagination-type-<?php echo esc_attr( clplace_option('rt_blog_pagination_type') ); ?>">
                                <?php the_posts_navigation(); ?>
                            </div>
                        <?php } else {
                            Pagination::pagination();
                        } ?>
					</main><!-- #main -->
				</div><!-- #primary -->

			</div><!-- .col- -->

			<?php get_sidebar(); ?>

		</div><!-- .row -->

	</div><!-- .container -->
</div>
<?php
get_footer();
