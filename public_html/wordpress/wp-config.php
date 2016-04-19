<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home4/grayson/public_html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
/** The name of the database for WordPress */
define('DB_NAME', 'grayson_wrdp1');

/** MySQL database username */
define('DB_USER', 'grayson_wrdp1');

/** MySQL database password */
define('DB_PASSWORD', 'lJ0xTaXxPpQ9q0K');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@r4PW!A-Ap;SY$fJ-ZH@Im@6xdSbosFGS)zYNmOBekk*o<p-c:-BQ5d)C^e9G<qO8Cy5MwzW');
define('SECURE_AUTH_KEY',  'U$5\`n2;LLSvZeZcvoD;)*)mj$qMdW7~7n9fRP=Sm>HiDECMf?A:OB~sqa=N?J:R#gEq/aOI');
define('LOGGED_IN_KEY',    'bIJ@O-HuZ-Pl~aS7~Ms7s<ixw^lFACjw8mvn|0kwzeh0N*^>N;^=FP#q|IbbWn7N3/U9R^!Y\`X1Y5');
define('NONCE_KEY',        '!cM-)_;>uwDaVfulzHeMlVx~veQg$:l3K<ykO6k_:4RrXLbwPW>29HW2;Y#ogeEJtA3Nn#4do2(^q_S4mYsE:wRw7');
define('AUTH_SALT',        'dgL>u/AX!?zzErAzKcFb-;PiyCE(rzOgmQv3y9d7HdKgZy1WZ6fwL#dPh6BZBqEty!4UU7fIlUlLrnts9T-3^V');
define('SECURE_AUTH_SALT', 'WFDr2h/@K==fwri<x#cqRla)YR6>r?Vo0hwG:A:jw3G!qP2KB3_B^*4YC!qF8)mv;66\`0nW7');
define('LOGGED_IN_SALT',   'NnBUlK(1~;>sgS9_Tam0$|c#x*_?yggQawRWW9R8_Y#3@y~C7;XTr@hr7Ray_sB<eUr/F6');
define('NONCE_SALT',       'ohTURt_9N0vQT@pS8e?-hzz>B:0Wo\`2/8Hn;;wDj-7jw<S6QR*5)_k:fcji>)hhw|iINMWQoBQu:M8:Xf');


/**#@-*/
define('AUTOSAVE_INTERVAL', 600 );
define('WP_POST_REVISIONS', 1);
define( 'WP_CRON_LOCK_TIMEOUT', 120 );
define( 'WP_AUTO_UPDATE_CORE', true );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );
