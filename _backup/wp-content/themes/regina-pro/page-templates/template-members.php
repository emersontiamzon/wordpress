<?php
/*
* Template Name: Members
*/
?>
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
			<div id="team-block" class="entry-content <?php echo esc_attr( $content_classes ); ?>">
				<?php

				$args = array(
					'post_type'      => 'member',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				);

				$members = new WP_Query( $args );

				?>
				<?php
				while ( $members->have_posts() ) :
					$members->the_post();
?>
					<?php get_template_part( 'template-parts/content', 'member' ); ?>
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
