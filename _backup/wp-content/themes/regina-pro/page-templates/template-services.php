<?php
/*
* Template Name: Services
*/
?>
<?php get_header(); ?>
<?php

// values

$contact_enable   = get_theme_mod( 'regina_services_contact_enable', '1' );
$contact_title    = get_theme_mod( 'regina_services_contact_title', __( 'Speak with our doctors', 'regina' ) );
$contact_subtitle = get_theme_mod( 'regina_services_contact_subtitle', __( 'We offer various services lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.', 'regina' ) );


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
			<div id="services-block" class="entry-content <?php echo esc_attr( $content_classes ); ?>">
				<?php

				$args = array(
					'post_type'      => 'service',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				);

				$services = new WP_Query( $args );

				?>
				<?php
				while ( $services->have_posts() ) :
					$services->the_post();
?>
					<?php get_template_part( 'template-parts/content', 'service' ); ?>
				<?php
				endwhile;
				wp_reset_query(); // end of the loop.
				?>
				<?php the_content(); ?>
			</div>
			<?php if ( $page_sidebar ) : ?>
				<div class="<?php echo esc_attr( $sidebar_classes ); ?>">
					<aside id="sidebar" class="hidden-xs hidden-sm"><?php dynamic_sidebar( $page_sidebar_id ); ?></aside>
				</div>
			<?php endif ?>
		</div>
	</div>

<?php if ( '1' == $contact_enable ) { ?>
	<div class="page-services-contact">
		<div class="container">
			<div class="row">
				<div class="section-info">
					<h2><?php echo esc_html( $contact_title ); ?></h2>
					<hr />
					<p><?php echo esc_html( $contact_subtitle ); ?></p>
				</div><!--/.section-info-->
			</div><!--/.row-->
		</div><!--/.container-->

		<div class="container">
			<div class="row">
				<?php echo do_shortcode( '[appointment-button button_text="Book appointment" button_style="solid" button_align="center"][/appointment-button]' ); ?>
			</div><!--/.row-->
		</div><!--/.container-->
	</div><!--/.page-services-contact-->
<?php } ?>

<?php get_footer(); ?>
