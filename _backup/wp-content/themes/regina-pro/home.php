<?php get_header(); ?>
<?php

$page_for_posts  = get_option( 'page_for_posts' );
$content_classes = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
$sidebar_classes = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';

if ( $page_for_posts ) {
	$page_sidebar          = mt_get_page_option( $page_for_posts, 'page-sidebar' );
	$page_sidebar_position = mt_get_page_option( $page_for_posts, 'page-sidebar-position' );

	if ( $page_sidebar ) {
		$content_classes = 'col-lg-8 col-md-8 col-sm-8 col-xs-12';

		if ( 'left-sidebar' == $page_sidebar_position ) {
			$content_classes .= ' pull-right';
		}
	}
} else {
	$page_sidebar          = get_theme_mod( 'regina_blog_archive_sidebar', true );
	$page_sidebar_position = get_theme_mod( 'regina_blog_archive_sidebar_position', 'right-sidebar' );

	if ( $page_sidebar ) {

		if ( $page_sidebar_position ) {
			$content_classes = 'col-lg-8 col-md-8 col-sm-8 col-xs-12';

			if ( 'left-sidebar' == $page_sidebar_position ) {
				$content_classes .= ' pull-right';
			}
		}
	}
}


?>
	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr( $content_classes ); ?>">
				<div id="blog">
					<?php if ( have_posts() ) : ?>

						<?php
						while ( have_posts() ) :
							the_post();
?>
							<?php get_template_part( 'template-parts/content' ); ?>
						<?php endwhile; ?>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content', 'none' ); ?>

					<?php endif ?>
				</div>
			</div>
			<?php if ( $page_sidebar ) : ?>
				<div class="<?php echo esc_attr( $sidebar_classes ); ?>">
					<aside id="sidebar" class="hidden-xs hidden-sm"><?php dynamic_sidebar( 'sidebar-blog' ); ?></aside>
				</div>
			<?php endif ?>
		</div>
		<nav id="blog-navigation">
			<?php mt_pagination(); ?>
		</nav>
	</div>
<?php get_footer(); ?>
