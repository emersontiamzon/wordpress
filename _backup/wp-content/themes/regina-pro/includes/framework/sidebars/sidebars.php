<?php


if ( function_exists( 'register_sidebar' ) ) {

	if ( ! function_exists( 'mt_register_sidebars' ) ) {
		function mt_register_sidebars() {
			#
			#    Register sidebars
			#

			register_sidebar( array(
				'name'          => __( 'Blog Sidebar', 'regina' ),
				'id'            => 'sidebar-blog',
				'description'   => __( 'Widgets in this area will be shown on Blog pages.', 'regina' ),
				'before_widget' => '<div id="%1$s" class="widget block %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Page Sidebar', 'regina' ),
				'id'            => 'sidebar-page',
				'description'   => __( 'Widgets in this area will be shown on Pages.', 'regina' ),
				'before_widget' => '<div id="%1$s" class="widget block %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			$theme_sidebars = get_theme_mod( 'regina_multi_sidebars', array( array() ) );

			if ( ! empty( $theme_sidebars )  ) {
				foreach ( $theme_sidebars as $theme_sidebar ) {

					if ( isset($theme_sidebar['sidebar_name']) ) {
						$sidebarID = 'sidebar-' . str_replace( ' ', '-', strtolower( $theme_sidebar['sidebar_name'] ) );

						register_sidebar( array(
							'name'          => $theme_sidebar['sidebar_name'],
							'id'            => $sidebarID,
							'description'   => __( 'Custom Sidebar.', 'regina' ),
							'before_widget' => '<div id="%1$s" class="widget block %2$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<h3>',
							'after_title'   => '</h3>',
						) );
					}
				}
			}

			$sidebar_columns = get_theme_mod( 'regina_footer_columns_v2' );
			if ( $sidebar_columns ) {
				$sidebar_columns = json_decode( $sidebar_columns, true );
				$no_sidebar = $sidebar_columns['columnsCount'];
			}else{
				$no_sidebar = 4;
			}
			
			$sidebars_args = array(
				'name'          => __( 'Sidebar Footer %d', 'regina' ),
				'id'            => 'sidebar-footer',
				'description'   => __( 'Widgets in this area will be shown on footer.', 'regina' ),
				'before_widget' => '<div id="%1$s" class="widget block %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h6><small>',
				'after_title'   => '</small></h6>',
			);

			register_sidebars( $no_sidebar, $sidebars_args );

		} // function mt_register_sidebars end

		add_action( 'widgets_init', 'mt_register_sidebars' );

	} // function exists (mt_register_sidebars) check


} // function exists (register_sidebar) check


?>