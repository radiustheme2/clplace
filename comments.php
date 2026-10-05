<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) :
	return;
endif;

if ( 'open' !== get_option( 'default_comment_status' ) ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<div class="comment-list-wrapper">
			<h2 class="comments-title">
				<?php
				printf(
				/* translators: 1: Comments count. */
					esc_html( _n( '%d Comment', '%d Comments', get_comments_number(), 'clplace' ) ),
					absint( get_comments_number() )
				);
				?>
			</h2><!-- .comments-title -->

			<ol class="comment-list">
				<?php
				wp_list_comments(
					[
						'style'       => 'ol',
						'avatar_size' => 60,
						'short_ping'  => true,
						'callback'    => 'clplace_comments_cbf',
					]
				);
				?>
			</ol><!-- .comment-list -->

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
				<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
					<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'clplace' ); ?></h2>
					<div class="nav-links">
						<?php
						$arrow_next = clplace_get_svg( 'arrow-right' );
						$arrow_prev = clplace_get_svg( 'arrow-right', '180' );
						?>
						<div class="nav-previous"><?php previous_comments_link( $arrow_prev . esc_html__( 'Older Comments', 'clplace' ) ); ?></div>
						<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'clplace' ) . $arrow_next ); ?></div>

					</div><!-- .nav-links -->
				</nav><!-- #comment-nav-below -->
			<?php
			endif; // Check for comment navigation.?>
		</div>
	<?php

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'clplace' ); ?></p>
	<?php endif; ?>


	<?php
	/**
	 * Comment Form
	 */
	$rtheme_commenter = wp_get_current_commenter();
	$rtheme_req       = get_option( 'require_name_email' );
	$rtheme_aria_req  = ( $rtheme_req ? " required" : '' );

	$comment_form_fields = [
		'author' =>
			'<div class="row gutters-20"><div class="col-lg-6 form-group"><input type="text" id="author" name="author" value="' . esc_attr( $rtheme_commenter['comment_author'] )
			. '" placeholder="' . esc_attr__( 'Name', 'clplace' ) . ( $rtheme_req ? ' *' : '' ) . '" class="form-control"' . $rtheme_aria_req . '></div>',

		'email' =>
			'<div class="col-lg-6 form-group"><input id="email" name="email" type="email" value="' . esc_attr( $rtheme_commenter['comment_author_email'] )
			. '" class="form-control" placeholder="' . esc_attr__( 'Email', 'clplace' ) . ( $rtheme_req ? ' *' : '' ) . '"' . $rtheme_aria_req . '></div></div>',
	];

	$comment_form_args = [
		'title_reply' => esc_html__('Add Comment', 'clplace' ),
		'class_submit'  => 'submit btn-send',
		'submit_field'  => '<div class="form-group submit-button">%1$s %2$s</div>',
		'comment_field' => '<div class="form-group"><textarea id="comment" name="comment" required placeholder="' . esc_attr__( 'Comment *', 'clplace' )
						. '" class="form-control textarea" rows="5" cols="40"></textarea></div>',
		'fields'        => apply_filters( 'comment_form_default_fields', $comment_form_fields ),
	];
	?>

	<?php if ( comments_open() ): ?>
		<div class="comment-reply-block">
			<div class="main-content">
				<?php comment_form( $comment_form_args ); ?>
			</div>
		</div>
	<?php endif; ?>

</div><!-- #comments -->
