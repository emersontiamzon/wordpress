<?php

//Header Options
$page_header_title    = '';
$page_header_subtitle = '';
$page_header_image    = '';
$page_for_posts       = get_option( 'page_for_posts' );

if ( is_home() ) {
	$page_header_title    = mt_get_page_option( $page_for_posts, 'page-title' );
	$page_header_subtitle = mt_get_page_option( $page_for_posts, 'page-subtitle' );
	$page_header_image    = mt_get_page_option( $page_for_posts, 'page-header-image' );
} elseif ( is_page() ) {
	$page_header_title    = mt_get_page_option( get_the_ID(), 'page-title' );
	$page_header_subtitle = mt_get_page_option( get_the_ID(), 'page-subtitle' );
	$page_header_image    = mt_get_page_option( get_the_ID(), 'page-header-image' );
} elseif ( is_singular( 'member' ) ) {
	$page_header_title    = mt_get_page_option( get_the_ID(), 'member-page-title' );
	$page_header_subtitle = mt_get_page_option( get_the_ID(), 'member-page-subtitle' );
	$page_header_image    = mt_get_page_option( get_the_ID(), 'member-page-header-image' );
} elseif ( is_single() ) {
	$page_header_title    = mt_get_page_option( get_the_ID(), 'post-title' );
	$page_header_subtitle = mt_get_page_option( get_the_ID(), 'post-subtitle' );
	$page_header_image    = mt_get_page_option( get_the_ID(), 'post-header-image' );
}

$page_style = '';
if ( $page_header_image ) {
	$page_header_image_url = wp_get_attachment_image_src( $page_header_image, 'full' );
	if ( isset( $page_header_image_url[0] ) ) {
		$page_style = 'style=background-image:url(' . esc_url( $page_header_image_url[0] ) . ');';
	}
}


?>
<header id="page-header" <?php echo esc_attr( $page_style ); ?>>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php if ( '' != $page_header_title ) { ?>
					<h2 class="title"><?php echo wp_kses_post( $page_header_title ); ?></h2>
				<?php } else { ?>
					<h2 class="title"><?php echo wp_kses_post( get_the_title() ); ?></h2>
				<?php } ?>

				<?php if ( '' != $page_header_subtitle ) : ?>
					<p class="description"><?php echo wp_kses_post( $page_header_subtitle ); ?></p>
				<?php endif ?>

			</div><!--.col-xs-12-->
		</div><!--.row-->
	</div><!--.container-->
</header>
