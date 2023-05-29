<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php

// variables from theme options
$top_header     = get_theme_mod( 'regina_top_header', true );
$header_type    = get_theme_mod( 'regina_header_type', 'v1' );
$page_for_posts = get_option( 'page_for_posts' );
$preloader      = get_theme_mod( 'regina_preloader', true );
$extra_header   = false;
$breadcrumbs    = false;

if ( is_home() ) {
	$extra_header = mt_get_page_option( $page_for_posts, 'page-header' );
	$breadcrumbs  = mt_get_page_option( $page_for_posts, 'page-breadcrumbs' );
} elseif ( is_page() ) {
	$extra_header = mt_get_page_option( get_the_ID(), 'page-header' );
	$breadcrumbs  = mt_get_page_option( get_the_ID(), 'page-breadcrumbs' );
} elseif ( is_singular( 'member' ) ) {
	$extra_header = mt_get_page_option( get_the_ID(), 'member-page-header' );
	$breadcrumbs  = mt_get_page_option( get_the_ID(), 'member-page-breadcrumbs' );
} elseif ( is_single() ) {
	$extra_header = mt_get_page_option( get_the_ID(), 'post-header' );
	if ( 0 === $extra_header ) {
		$extra_header = get_theme_mod( 'regina_enable_post_header', true );
	}
	$breadcrumbs = mt_get_page_option( get_the_ID(), 'post-breadcrumbs' );
	if ( 0 === $breadcrumbs ) {
		$breadcrumbs = get_theme_mod( 'regina_enable_post_breadcrumbs', true );
	}
}

if ( $preloader ) {
	get_template_part( 'template-parts/header/preloader' );
}

if ( $top_header ) {
	get_template_part( 'template-parts/header/top-header' );
}

get_template_part( 'template-parts/header/header', $header_type );

if ( $extra_header && ! is_front_page() ) {
	get_template_part( 'template-parts/header/subheader' );
}

if ( $breadcrumbs && ! is_front_page() ) {
	get_template_part( 'template-parts/header/breadcrumbs' );
}

if ( is_archive() || is_search() ) {

	$archive_extra_header = true;
	$archive_breadcrumbs  = true;

	if ( is_category() ) {
		$archive_extra_header = get_theme_mod( 'regina_blog_category_header', true );
		$archive_breadcrumbs  = get_theme_mod( 'regina_blog_category_breadcrumbs', true );
	} elseif ( is_tag() ) {
		$archive_extra_header = get_theme_mod( 'regina_blog_tag_header', true );
		$archive_breadcrumbs  = get_theme_mod( 'regina_blog_tag_breadcrumbs', true );
	} elseif ( is_author() ) {
		$archive_extra_header = get_theme_mod( 'regina_blog_author_header', true );
		$archive_breadcrumbs  = get_theme_mod( 'regina_blog_author_breadcrumbs', true );
	} elseif ( is_search() ) {
		$archive_extra_header = get_theme_mod( 'regina_blog_search_header', true );
		$archive_breadcrumbs  = get_theme_mod( 'regina_blog_search_breadcrumbs', true );
	} else {
		$archive_extra_header = get_theme_mod( 'regina_blog_archive_header', true );
		$archive_breadcrumbs  = get_theme_mod( 'regina_blog_archive_breadcrumbs', true );
	}
	if ( $archive_extra_header ) {
		get_template_part( 'template-parts/header/subheader', 'archive' );
	}
	if ( $archive_breadcrumbs ) {
		get_template_part( 'template-parts/header/breadcrumbs' );
	}
}

?>
