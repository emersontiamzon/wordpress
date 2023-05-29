<?php


define( 'MT_ORDER_URL', get_template_directory_uri() . '/includes/framework/custom-post-order' );
define( 'MT_ORDER_DIR', get_template_directory() . '/includes/framework/custom-post-order' );

$mt_order = new MT_Sort_Engine();

class MT_Sort_Engine {

	function __construct() {

		if ( ! get_option( 'mt_order_install' ) ) {
			$this->mt_order_install();
		}

		add_action( 'admin_init', array( $this, 'refresh' ) );
		add_action( 'admin_init', array( $this, 'update_options' ) );
		add_action( 'admin_init', array( $this, 'load_script_css' ) );

		add_action( 'wp_ajax_update-menu-order', array( $this, 'update_menu_order' ) );
		add_action( 'wp_ajax_update-menu-order-tags', array( $this, 'update_menu_order_tags' ) );

		add_action( 'pre_get_posts', array( $this, 'mt_order_pre_get_posts' ) );

		add_filter( 'get_previous_post_where', array( $this, 'mt_order_previous_post_where' ) );
		add_filter( 'get_previous_post_sort', array( $this, 'mt_order_previous_post_sort' ) );
		add_filter( 'get_next_post_where', array( $this, 'mt_order_next_post_where' ) );
		add_filter( 'get_next_post_sort', array( $this, 'mt_order_next_post_sort' ) );

	}

	function mt_order_install() {

		global $wpdb;
		$result = $wpdb->query( "DESCRIBE $wpdb->terms `term_order`" );

		if ( ! $result ) {
			$query  = "ALTER TABLE $wpdb->terms ADD `term_order` INT( 4 ) NULL DEFAULT '0'";
			$result = $wpdb->query( $query );
		}

		update_option( 'mt_order_install', 1 );
	}


	function _check_load_script_css() {
		$active = false;

		$objects = $this->get_mt_order_options_objects();


		if ( empty( $objects ) ) {
			return false;
		}

		if ( isset( $_GET['orderby'] ) || strstr( $_SERVER['REQUEST_URI'], 'action=edit' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) ) {
			return false;
		}

		if ( ! empty( $objects ) ) {
			if ( isset( $_GET['post_type'] ) && ! isset( $_GET['taxonomy'] ) && in_array( $_GET['post_type'], $objects ) ) { // if page or custom post types
				$active = true;
			}
			if ( ! isset( $_GET['post_type'] ) && strstr( $_SERVER['REQUEST_URI'], 'wp-admin/edit.php' ) && in_array( 'post', $objects ) ) { // if post
				$active = true;
			}
		}

		return $active;
	}

	function load_script_css() {

		if ( $this->_check_load_script_css() ) {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'mt_orderjs', MT_ORDER_URL . '/assets/scporder.js', array( 'jquery' ), null, true );
			wp_enqueue_style( 'mt_order', MT_ORDER_URL . '/assets/scporder.css', array(), null );
		}
	}

	function refresh() {
		global $wpdb;
		$objects = $this->get_mt_order_options_objects();

		if ( ! empty( $objects ) ) {
			foreach ( $objects as $object ) {
				$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min 
					FROM $wpdb->posts 
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
				" );
				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}

				$results = $wpdb->get_results( "
					SELECT ID 
					FROM $wpdb->posts 
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') 
					ORDER BY menu_order ASC
				" );
				foreach ( $results as $key => $result ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
				}
			}
		}

	}

	function update_menu_order() {
		global $wpdb;

		parse_str( $_POST['order'], $data );

		if ( ! is_array( $data ) ) {
			return false;
		}

		$id_arr = array();
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = $id;
			}
		}

		$menu_order_arr = array();
		foreach ( $id_arr as $key => $id ) {
			$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval( $id ) );
			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->menu_order;
			}
		}

		sort( $menu_order_arr );

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[ $position ] ), array( 'ID' => intval( $id ) ) );
			}
		}
	}


	function update_options() {
		global $wpdb;

		if ( ! isset( $_POST['mt_order_submit'] ) ) {
			return false;
		}

		check_admin_referer( 'nonce_mt_order' );

		$input_options            = array();
		$input_options['objects'] = isset( $_POST['objects'] ) ? $_POST['objects'] : '';


		update_option( 'mt_order_options', $input_options );

		$objects = $this->get_mt_order_options_objects();


		if ( ! empty( $objects ) ) {
			foreach ( $objects as $object ) {
				$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min 
					FROM $wpdb->posts 
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
				" );
				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}

				if ( $object == 'page' ) {
					$results = $wpdb->get_results( "
						SELECT ID 
						FROM $wpdb->posts 
						WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') 
						ORDER BY post_title ASC
					" );
				} else {
					$results = $wpdb->get_results( "
						SELECT ID 
						FROM $wpdb->posts 
						WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') 
						ORDER BY post_date DESC
					" );
				}
				foreach ( $results as $key => $result ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
				}
			}
		}

		wp_redirect( 'admin.php?page=mt_order-settings&msg=update' );
	}

	function mt_order_previous_post_where( $where ) {
		global $post;

		$objects = $this->get_mt_order_options_objects();
		if ( empty( $objects ) ) {
			return $where;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where              = "WHERE p.menu_order > '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}

		return $where;
	}

	function mt_order_previous_post_sort( $orderby ) {
		global $post;

		$objects = $this->get_mt_order_options_objects();
		if ( empty( $objects ) ) {
			return $orderby;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
		}

		return $orderby;
	}

	function mt_order_next_post_where( $where ) {
		global $post;

		$objects = $this->get_mt_order_options_objects();
		if ( empty( $objects ) ) {
			return $where;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where              = "WHERE p.menu_order < '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}

		return $where;
	}

	function mt_order_next_post_sort( $orderby ) {
		global $post;

		$objects = $this->get_mt_order_options_objects();
		if ( empty( $objects ) ) {
			return $orderby;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order DESC LIMIT 1';
		}

		return $orderby;
	}

	function mt_order_pre_get_posts( $wp_query ) {
		$objects = $this->get_mt_order_options_objects();
		if ( empty( $objects ) ) {
			return false;
		}
		if ( is_admin() ) {

			if ( isset( $wp_query->query['post_type'] ) && ! isset( $_GET['orderby'] ) ) {
				if ( in_array( $wp_query->query['post_type'], $objects ) ) {
					$wp_query->set( 'orderby', 'menu_order' );
					$wp_query->set( 'order', 'ASC' );
				}
			}
		} else {

			$active = false;

			if ( isset( $wp_query->query['post_type'] ) ) {
				if ( ! is_array( $wp_query->query['post_type'] ) ) {
					if ( in_array( $wp_query->query['post_type'], $objects ) ) {
						$active = true;
					}
				}
			} else {
				if ( in_array( 'post', $objects ) ) {
					$active = true;
				}
			}

			if ( ! $active ) {
				return false;
			}

			if ( isset( $wp_query->query['suppress_filters'] ) ) {
				if ( $wp_query->get( 'orderby' ) == 'date' ) {
					$wp_query->set( 'orderby', 'menu_order' );
				}
				if ( $wp_query->get( 'order' ) == 'DESC' ) {
					$wp_query->set( 'order', 'ASC' );
				}
			} else {
				if ( ! $wp_query->get( 'orderby' ) ) {
					$wp_query->set( 'orderby', 'menu_order' );
				}
				if ( ! $wp_query->get( 'order' ) ) {
					$wp_query->set( 'order', 'ASC' );
				}
			}
		}
	}


	function get_mt_order_options_objects() {
		/**
		 * Custom Post Types this is supposed to be working for.
		 */
		$objects = array(
			'section',
			'testimonial',
			'member',
			'service',
		);

		return $objects;
	}

}

/**
 * mt_ Order Uninstall hook
 */
register_uninstall_hook( __FILE__, 'mt_order_uninstall' );

function mt_order_uninstall() {
	global $wpdb;
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$curr_blog = $wpdb->blogid;
		$blogids   = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blogids as $blog_id ) {
			switch_to_blog( $blog_id );
			mt_order_uninstall_db();
		}
		switch_to_blog( $curr_blog );
	} else {
		mt_order_uninstall_db();
	}
}

function mt_order_uninstall_db() {
	global $wpdb;
	$result = $wpdb->query( "DESCRIBE $wpdb->terms `term_order`" );
	if ( $result ) {
		$query  = "ALTER TABLE $wpdb->terms DROP `term_order`";
		$result = $wpdb->query( $query );
	}
	delete_option( 'mt_order_install' );
}