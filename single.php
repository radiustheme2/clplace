<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Helpers\Fns;
use RT\Clplace\Options\Opt;

get_header();
$classes = Fns::class_list( [
	'single-post-container',
	Fns::is_single_fullwidth() ? 'should-full-width' : ''
] );

?>

	<div class="<?php echo esc_attr( $classes ) ?>">

		<?php while ( have_posts() ) :
			the_post(); ?>

			<?php do_action( 'clplace_before_single_content', get_the_ID() ); ?>

			<div class="container">
				<div class="row content-row">

					<div class="content-col <?php echo esc_attr( Fns::single_content_colums() ); ?>">

						<div id="primary" class="content-area single-content">
							<main id="main" class="site-main" role="main">
								<?php
									get_template_part( 'views/content-single', Opt::$single_style );
									//Author Bio
									get_template_part('views/custom/author', 'bio');

									// If comments are open or we have at least one comment, load up the comment template.
									if ( comments_open() || get_comments_number() ) :
										comments_template();
									endif;
								?>
							</main><!-- #main -->
						</div><!-- #primary -->

					</div><!-- .col- -->

					<?php get_sidebar(); ?>

				</div><!-- .row -->
			</div><!-- .container -->

			<?php do_action( 'clplace_after_single_content' ); ?>

		<?php endwhile; ?>

	</div>

<?php
get_footer();
