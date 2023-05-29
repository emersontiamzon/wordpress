<?php get_header(); ?>

<?php

$page_sidebar          = mt_get_page_option( get_the_ID(), 'page-sidebar' );
$page_sidebar_position = mt_get_page_option( get_the_ID(), 'page-sidebar-position' );
$page_sidebar_id       = mt_get_page_option( get_the_ID(), 'page-sidebar-id' );
if ( ! $page_sidebar_id ) {
	$page_sidebar_id = 'sidebar-page';
}

$content_classes = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
$sidebar_classes = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';

if ( $page_sidebar ) {
	$content_classes = 'col-lg-8 col-md-8 col-sm-8 col-xs-12';

	if ( 'left-sidebar' == $page_sidebar_position ) {
		$content_classes .= ' pull-right';
	}
}


?>
	<div class="container">
		<div class="row">
			<div class="entry-content <?php echo esc_attr( $content_classes ); ?>">
				<?php
				while ( have_posts() ) :
					the_post();
?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div>
			<?php if ( $page_sidebar ) : ?>
				<div class="<?php echo esc_attr( $sidebar_classes ); ?>">
					<aside id="sidebar" class="hidden-xs hidden-sm"><?php dynamic_sidebar( $page_sidebar_id ); ?></aside>
				</div>
			<?php endif ?>
		</div>
	</div>

<?php get_footer(); ?>
