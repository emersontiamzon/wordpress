<?php

//Remove Standard WooCommerce Sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );


//Open shop wrapper
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_before_main_content', 'antreas_woocommerce_before_main_content', 10 );
function antreas_woocommerce_before_main_content() {
	//Begin main area
	echo '<div id="main" class="main">';
	echo '<div class="container">';
	echo '<section id="content" class="content">';
	do_action( 'antreas_before_content' );
}


//Close shop wrapper
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_after_main_content', 'antreas_woocommerce_after_main_content', 10 );
function antreas_woocommerce_after_main_content() {
	do_action( 'antreas_after_content' );
	echo '</section>';
	get_sidebar();
	do_action( 'antreas_woocommerce_after_sidebar' );
	echo '</div>';
	echo '</div>';
}


//Do not display the product title on listings-- show the shop's title.
add_filter( 'woocommerce_show_page_title', 'antreas_woocommerce_show_page_title' );
function antreas_woocommerce_show_page_title( $data ) {
	if ( ! is_singular() ) {
		return false;
	}
	return $data;
}


//Customize the breadcrumb separator and remove standard breadcrumb location
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
add_filter( 'woocommerce_breadcrumb_defaults', 'antreas_woocommerce_breadcrumbs' );
function antreas_woocommerce_breadcrumbs( $data ) {
	$data['delimiter']   = '<span class="breadcrumb-separator"></span>';
	$data['wrap_before'] = '<nav id="breadcrumb" class="breadcrumb" itemprop="breadcrumb">';
	return $data;
}
