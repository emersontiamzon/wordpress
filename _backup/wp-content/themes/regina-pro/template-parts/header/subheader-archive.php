<?php

//Header Options
$page_header_title    = get_the_archive_title();
$page_header_subtitle = get_the_archive_description();
$page_header_image    = '';

if ( ! is_tag() && ! is_category() && ! is_author() ) {
	$page_header_subtitle = get_theme_mod( 'regina_blog_archive_header_description', '' );
}
if ( is_search() ) {
	$page_header_title    = __( 'Search', 'regina' ) . ' : ' . get_search_query();
	$page_header_subtitle = get_theme_mod( 'regina_blog_search_header_description', '' );
}
if ( is_category() ) {
	$page_header_image = get_theme_mod( 'regina_blog_category_header_image' );
} elseif ( is_tag() ) {
	$page_header_image = get_theme_mod( 'regina_blog_tag_header_image' );
} elseif ( is_author() ) {
	$page_header_image = get_theme_mod( 'regina_blog_author_header_image' );
} elseif ( is_search() ) {
	$page_header_image = get_theme_mod( 'regina_blog_search_header_image' );
} else {
	$page_header_image = get_theme_mod( 'regina_blog_archive_header_image' );
}

$page_style = '';
if ( $page_header_image ) {
	$page_style = 'style="background-image:url(' . esc_url( $page_header_image ) . ');"';
}


?>
<header id="page-header" <?php echo $page_style; ?>>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php if ( '' != $page_header_title ) : ?>
					<h2 class="title"><?php echo wp_kses_post( $page_header_title ); ?></h2>
				<?php endif ?>

				<?php if ( '' != $page_header_subtitle ) : ?>
					<p class="description"><?php echo wp_kses_post( $page_header_subtitle ); ?></p>
				<?php endif ?>
			</div><!--.col-xs-12-->
		</div><!--.row-->
	</div><!--.container-->
</header>
