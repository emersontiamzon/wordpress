<?php
function regina_check_top_header() {

	$top_header = get_theme_mod( 'regina_top_header', true );
	if ( $top_header ) {
		return true;
	}

	return false;

}

function regina_check_hero_slider() {

	$hero_slider = get_theme_mod( 'regina_homepage_type', 'slider' );
	if ( 'slider' == $hero_slider ) {
		return true;
	}

	return false;

}

function regina_check_hero_slider_autoplay() {

	if ( ! regina_check_hero_slider() ) {
		return false;
	}

	$slider_autoplay = get_theme_mod( 'regina_slider_autoplay', false );
	if ( $slider_autoplay ) {
		return true;
	}

	return false;

}

function regina_check_hero_image() {

	$hero_slider = get_theme_mod( 'regina_homepage_type', 'slider' );
	if ( 'image' == $hero_slider ) {
		return true;
	}

	return false;

}

function regina_check_categories_header() {

	$check = get_theme_mod( 'regina_blog_category_header', true );
	if ( $check ) {
		return true;
	}

	return false;

}

function regina_check_categories_sidebar() {

	$check = get_theme_mod( 'regina_blog_category_sidebar', true );
	if ( $check ) {
		return true;
	}

	return false;

}

function regina_check_tags_header() {

	$check = get_theme_mod( 'regina_blog_tag_header', true );
	if ( $check ) {
		return true;
	}

	return false;

}

function regina_check_tags_sidebar() {

	$check = get_theme_mod( 'regina_blog_tag_sidebar', true );
	if ( $check ) {
		return true;
	}

	return false;

}

function regina_check_author_header() {

	$check = get_theme_mod( 'regina_blog_tag_header', true );
	if ( $check ) {
		return true;
	}

	return false;

}

function regina_check_author_sidebar() {

	$check = get_theme_mod( 'regina_blog_tag_sidebar', true );
	if ( $check ) {
		return true;
	}

	return false;

}

function regina_check_breadcrumbs() {

	$breadcrumbs = get_theme_mod( 'regina_enable_post_breadcrumbs', true );
	if ( $breadcrumbs ) {
		return true;
	}

	return false;

}

function regina_check_related_posts() {

	$related_posts = get_theme_mod( 'regina_enable_related_blog_posts', true );
	if ( $related_posts ) {
		return true;
	}

	return false;

}

function check_if_is_cf7() {
	$form = get_theme_mod( 'regina_booking_form_opions', 'custom-form' );
	if ( 'contact-form-7' == $form ) {
		return true;
	}

	return false;
}

function check_if_is_wpforms() {
	$form = get_theme_mod( 'regina_booking_form_opions', 'custom-form' );
	if ( 'wpforms' == $form ) {
		return true;
	}

	return false;
}

function check_if_is_kaliforms() {
	$form = get_theme_mod( 'regina_booking_form_opions', 'custom-form' );
	if ( 'kali-forms' == $form ) {
		return true;
	}

	return false;
}
