<?php

//Post Settings
$general_settings = array(
	'single-post-show-post-featured-image' => get_theme_mod( 'regina_enable_post_featured_image', true ),
	'single-post-show-post-meta' => get_theme_mod( 'regina_enable_post_posted_on_blog_posts', true ),
	'single-post-show-post-social-box' => get_theme_mod( 'regina_enable_post_social_box', true ),
	'single-post-show-post-prev-next' => get_theme_mod( 'regina_enable_post_navigation', true ),
	'single-post-show-post-author-box' => get_theme_mod( 'regina_enable_author_box_blog_posts', true ),
);

$options_needded = array(
	'single-post-show-post-featured-image',
	'single-post-show-post-meta',
	'single-post-show-post-social-box',
	'single-post-show-post-author-box',
	'single-post-show-post-prev-next',
);

$settings = mt_get_page_options( get_the_ID(), $options_needded );
$settings = wp_parse_args( $settings, $general_settings );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() && $settings['single-post-show-post-featured-image'] ) { ?>
		<div class="image">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php } ?>

	<h2 class="title">
		<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
	</h2>

	<?php if ( $settings['single-post-show-post-meta'] ) { ?>
		<p class="meta"><?php the_date( 'F d, Y', '', '' ); ?> - <?php echo __( 'by', 'regina' ); ?>
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
			<?php
			$categories = get_the_category( get_the_ID() );
			if ( $categories ) {
				_e( '  -  in', 'regina' );
			}

			foreach ( $categories as $key => $category ) {
				$category_url = get_category_link( $category->term_id );
				echo ' <a href="' . esc_attr( $category_url ) . '">' . esc_html( $category->name ) . '</a>';
				if ( $key + 1 < count( $categories ) ) {
					echo ',';
				}
			}
			?>
		</p>

		<p class="comments">
			<a href="#"><span class="nc-icon-outline ui-2_chat-round"></span><?php comments_number( __( 'No Comments', 'regina' ), __( '1 Comment', 'regina' ), __( '% Comment', 'regina' ) ); ?>
			</a>
		</p>
	<?php } ?>

	<div class="body">
		<?php the_content(); ?>
	</div>

	<?php
	$display_tags_post_meta     = get_theme_mod( 'regina_enable_post_tags_blog_posts', 1 );
	if ( 1 == $display_tags_post_meta ) {
		$tags = get_tags();
		if ( $tags ) {
			echo '<ul class="post-tags">';
				echo '<li><p>' . esc_html( 'Tags:', 'regina' ) . '</p></li>';
			foreach ( $tags as $tag ) {
				$tag_link = get_tag_link( $tag->term_id );
				echo '<li><a href="' . esc_url( $tag_link ) . '" title="' . esc_attr( $tag->name ) . '">' . esc_html( $tag->name ) . '</a></li>';
			}
			echo '</ul><!--/.post-tags-->';
		}
	}

	?>

	<?php if ( $settings['single-post-show-post-prev-next'] ) { ?>
		<div id="post-navigation">
			<?php previous_post_link( ' %link', '<span class="nc-icon-glyph arrows-1_bold-left"></span> %title' ); ?>

			<?php next_post_link( '%link ', '%title <span class="nc-icon-glyph arrows-1_bold-right"></span>' ); ?>
		</div><!--#post-navigation-->
	<?php } ?>

	<?php if ( $settings['single-post-show-post-social-box'] ) { ?>
		<div id="share-post">
			<p class="left"><strong><?php _e( 'Share this article', 'regina' ); ?></strong></p>
			<?php

			$social_sharing_links = mt_display_sharingbox_social_links_array();

			?>
			<ul class="social">
				<li class="facebook">
					<a href="<?php echo esc_attr( $social_sharing_links['facebook'] ); ?>"><span class="nc-icon-glyph socials-1_logo-facebook"></span></a>
				</li>
				<li class="twitter">
					<a href="<?php echo esc_attr( $social_sharing_links['twitter'] ); ?>"><span class="nc-icon-glyph socials-1_logo-twitter"></span></a>
				</li>
				<li class="linkedin">
					<a href="<?php echo esc_attr( $social_sharing_links['linkedin'] ); ?>"><span class="nc-icon-glyph socials-1_logo-linkedin"></span></a>
				</li>
				<li class="email">
					<a href="<?php echo esc_attr( $social_sharing_links['mail'] ); ?>"><span class="nc-icon-glyph ui-1_email-83"></span></a>
				</li>
			</ul>
		</div><!--#share-post-->
	<?php } ?>

	<?php
	if ( $settings['single-post-show-post-author-box'] ) {
		get_template_part( 'template-parts/single/single', 'author' );
	}// End if().
	?>
</article><!--.post-->
