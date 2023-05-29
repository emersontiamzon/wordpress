<?php

// Framework Constants
require_once dirname( __FILE__ ) . '/framework-constants.php';

// Main Framework files
require_once dirname( __FILE__ ) . '/framework-bootstrap.php';

// Custom post types
require_once dirname( __FILE__ ) . '/panels/custom-post-types.php';

// Load framework extra features
include_once dirname( __FILE__ ) . '/framework-extra.php';
// Metaboxes
require_once dirname( __FILE__ ) . '/panels/metaboxes.php';

// User Meta Fields
require_once dirname( __FILE__ ) . '/panels/user-meta.php';

// Admin Screens
// require_once dirname( __FILE__ ) . '/panels/admin/loader.php';

// Sidebars
require_once dirname( __FILE__ ) . '/sidebars/sidebars.php';

// BreadCrumbs
require_once dirname( __FILE__ ) . '/breadcrumbs/class.macho-breadcrumbs.php';

// Widgets
require_once dirname( __FILE__ ) . '/widgets/widget-contact.php';

// Post Order
require_once dirname( __FILE__ ) . '/custom-post-order/simple-custom-post-order.php';