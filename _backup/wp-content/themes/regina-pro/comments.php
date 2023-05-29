<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$aria_req = '';

?>

<div id="comments-list" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( _x( 'One thought to &ldquo;%s&rdquo;', 'comments title', 'regina' ), get_the_title() );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s thought to &ldquo;%2$s&rdquo;',
						'%1$s thoughts to &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'regina'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
		</h3>

		<ul class="comments">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ul',
					'short_ping'  => true,
					'avatar_size' => 56,
					'callback'    => 'regina_custom_comments',
				)
			);
			?>
		</ul><!-- .comment-list -->

	<?php endif; ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'regina' ); ?></p>
	<?php endif; ?>

	<?php

	$fields = array(

		'author' => '<p class="comment-form-author col-md-4"><label for="author">' . __( 'Name', 'regina' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . esc_attr( $aria_req ) . ' /></p>',

		'email'  => '<p class="comment-form-email col-md-4"><label for="email">' . __( 'Email', 'regina' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . esc_attr( $aria_req ) . ' /></p>',

		'url'    => '<p class="comment-form-url col-md-4"><label for="url">' . __( 'Website', 'regina' ) . '</label>' . '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$args = array(

		// 'comment_notes_before' => '<div class="col-xs-12">',
		// 'comment_notes_after'  => '</div>',
		'class_submit'  => 'button',
		'fields'        => $fields,
		'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',

	);


	echo '<div class="row">';
	comment_form( $args );
	echo '</div>';

	?>

</div><!-- .comments-area -->
