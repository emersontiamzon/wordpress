<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME','test');

/** MySQL database username */
define( 'DB_USER','root');

/** MySQL database password */
define( 'DB_PASSWORD','Nescafe3in1');

/** MySQL hostname */
define( 'DB_HOST', "127.0.0.1");

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', 'latin1_general_ci' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY','kmXckiZz+uICEkhym97K39hrX2/StnEuWScpAerFgf24fv9lbhiE4gZbiBZdKeoV');
define('SECURE_AUTH_KEY','h2qCCkabK320H3sLkrdJQ3oFr9yv2duTmk9pdT/VqBzyPkPSq+EI7Rtap0fBv7ui');
define('LOGGED_IN_KEY','hR5zvVCYAlucyjoJMgyiEPVBus3DkVUycL7IF/SSyIUtX0HgkmNkCe3GVPGQk39J');
define('NONCE_KEY','65l9RCS4iWcra/PTvBz3cYvFHKk+2LizmDmOGKrby9iHqCN7YS1UY2ZQuqyLFwHN');
define('AUTH_SALT','7ilXs7qbQAkmvn6nirvBIinGbUkGrBlseLCw6LUGcds5BGVCuv1b4ShEzhFlCytM');
define('SECURE_AUTH_SALT','SRpxJekRyTL71qa2GH7Jy01FWpMIjFasfrIQvDlrMSKbx6qlZwzLNSaUIzaLW06c');
define('LOGGED_IN_SALT','gDHLiDVeOgfDR9KIsoEkgBX77dGirNDpso3/XYsd+r/G1fg5ol5U8Fp8rQbKpF4c');
define('NONCE_SALT','ASqrhBT57AOEP9F/ySXqYrZVNNwRGbi6s6JWACItWiMBUx2seiXmxDwghlYfZL0n');


define('FTP_PUBKEY','/home/wp-user/wp_rsa.pub');
define('FTP_PRIKEY','/home/wp-user/wp_rsa');
define('FTP_USER','wp-user');
define('FTP_PASS','');
define('FTP_HOST','http://192.168.2.138:5000/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 */

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */
$pageURL = 'http';
if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80" and $_SERVER["SERVER_PORT"] != "443") {
    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
} else {
    $pageURL .= $_SERVER["SERVER_NAME"];
}

if ($_SERVER["HOST"] != "") {
    define('WP_SITEURL', $pageURL);
} else {
        define('WP_SITEURL', $pageURL.'/wordpress');
}

if (!defined('SYNOWORDPRESS'))
    define('SYNOWORDPRESS', 'Synology Inc.');

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
require_once( ABSPATH . 'syno-misc.php' );

define( 'AUTOMATIC_UPDATER_DISABLED', true );
add_filter('pre_site_transient_update_core','__return_null');
