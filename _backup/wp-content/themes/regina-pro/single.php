<?php get_header(); ?>

<?php

$general_settings = array(
	'single-post-show-sidebar'          => 1,
	'single-post-sidebar-position'      => 'left-sidebar',
	'single-post-sidebar'               => 'sidebar-blog',
	'single-post-show-post-related-box' => get_theme_mod( 'regina_enable_related_blog_posts', true ),
);

$options_needded = array(
	'single-post-show-sidebar',
	'single-post-sidebar-position',
	'single-post-sidebar',
	'single-post-show-post-related-box',
);

$settings = mt_get_page_options( get_the_ID(), $options_needded );
$settings = wp_parse_args( $settings, $general_settings );

$content_classes = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
$sidebar_classes = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';

if ( $settings['single-post-show-sidebar'] ) {
	$content_classes = 'col-lg-8 col-md-8 col-sm-8 col-xs-12';

	if ( 'left-sidebar' == $settings['single-post-sidebar-position'] ) {
		$content_classes .= ' pull-right';
	}
}


?>
	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr( $content_classes ); ?>">
				<div id="blog" class="single">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'single' );

						if ( $settings['single-post-show-post-related-box'] ) {
							get_template_part( 'template-parts/single/single', 'related' );
						}

						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>

					<?php endwhile; ?>
				</div>
			</div>
			<?php if ( $settings['single-post-show-sidebar'] ) : ?>
				<div class="<?php echo esc_attr( $sidebar_classes ); ?>">
					<aside id="sidebar" class="hidden-xs hidden-sm"><?php dynamic_sidebar( $settings['single-post-sidebar'] ); ?></aside>
				</div>
			<?php endif ?>
		</div>
	</div>

<?php get_footer(); ?>
