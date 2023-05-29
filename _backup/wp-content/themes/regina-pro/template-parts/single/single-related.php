<?php

$category_ids = get_categories(
	array(
		'fields' => 'tt_ids',
		'object_ids' => get_the_ID(),
	)
);

$posts_per_page = get_option( 'posts_per_page' );

$args = array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => $posts_per_page,
	'post__not_in'   => array( get_the_ID() ),
);

if ( ! empty( $category_ids ) ) {
	$args['category__in'] = $category_ids;
}

// Number of posts to show / view
$limit = get_theme_mod( 'regina_howmany_blog_posts', 3 );

// Auto play
$auto_play = get_theme_mod( 'regina_autoplay_blog_posts', 1 );

// Pagination
$pagination = get_theme_mod( 'regina_pagination_blog_posts', 0 );

$enable_related_title_blog_posts = get_theme_mod( 'regina_enable_related_title_blog_posts', 1 );
$enable_related_date_blog_posts = get_theme_mod( 'regina_enable_related_date_blog_posts', 1 );

$related_posts = new WP_Query( $args );

if ( $related_posts->have_posts() ) {
	?>
	<div id="related-posts">
		<h3><?php _e( 'Related Posts', 'regina' ); ?></h3>
		<?php
		echo sprintf( '<div class="owlCarousel" data-slider-id="%s" id="owlCarousel-%s" data-slider-items="%s" data-slider-speed="400" data-slider-auto-play="%s" data-slider-navigation="0" data-slider-pagination="%s">', get_the_ID(), get_the_ID(), $limit, $auto_play, $pagination );
		?>
			<?php
			while ( $related_posts->have_posts() ) :
				$related_posts->the_post();
?>
				<div class="col-sm-12">
					<div class="post">
						<a href="<?php echo get_permalink(); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								$url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
								echo '<img data-original="' . esc_attr( $url ) . '" alt="' . get_the_title() . '" class="lazy">';
							}
							?>
							<?php if ( $enable_related_title_blog_posts || $enable_related_date_blog_posts ) : ?>
								<div class="inner">
									<?php if ( $enable_related_date_blog_posts ) : ?>
										<h6 class="date"><?php echo get_the_date( 'F d, Y', '', '' ); ?></h6>
									<?php endif ?>
									<?php if ( $enable_related_title_blog_posts ) : ?>
										<p class="title"><?php esc_html( the_title() ); ?></p>
									<?php endif ?>
								</div>
							<?php endif ?>
						</a>
					</div><!--.post-->
				</div><!--.col-sm-4-->
			<?php endwhile; ?>
		</div>
	</div><!--#related-posts-->
<?php } ?>
