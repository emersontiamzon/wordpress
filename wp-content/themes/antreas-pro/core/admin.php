<?php

//Define custom columns for each custom post type page
if ( ! function_exists( 'antreas_admin_columns' ) ) {
	add_action( 'manage_posts_custom_column', 'antreas_admin_columns', 2 );
	function antreas_admin_columns( $column ) {
		global $post;
		switch ( $column ) {
			case 'cpo-image':
				echo get_the_post_thumbnail( $post->ID, array( 60, 60 ) );
				break;
			//Portfolio
			case 'cpo-portfolio-cats':
				echo get_the_term_list( $post->ID, 'cpo_portfolio_category', '', ', ', '' );
				break;
			case 'cpo-portfolio-tags':
				echo get_the_term_list( $post->ID, 'cpo_portfolio_tag', '', ', ', '' );
				break;
			//Services
			case 'cpo-service-cats':
				echo get_the_term_list( $post->ID, 'cpo_service_category', '', ', ', '' );
				break;
			case 'cpo-service-tags':
				echo get_the_term_list( $post->ID, 'cpo_service_tag', '', ', ', '' );
				break;
			//Team
			case 'cpo-team-cats':
				echo get_the_term_list( $post->ID, 'cpo_team_category', '', ', ', '' );
				break;
			//Products
			case 'cpo-product-cats':
				echo get_the_term_list( $post->ID, 'cpo_product_category', '', ', ', '' );
				break;
			case 'cpo-product-tags':
				echo get_the_term_list( $post->ID, 'cpo_product_tag', '', ', ', '' );
				break;

			default:
				break;
		}
	}
}
