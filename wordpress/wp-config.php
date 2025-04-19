<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'qgamecltxfun_wp_5ems4' );

/** Database username */
define( 'DB_USER', 'qgamecltxfun_wp_r4972' );

/** Database password */
define( 'DB_PASSWORD', '~Ru6JN48OKVz@k_9' );

/** Database hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'T43;t3~9*;+8awFDme]0zNY!1;r0+86(Wpfj[ZO3kwYvhDjv91m/003iA56JZ82_');
define('SECURE_AUTH_KEY', 'PDRD)18[fZa#WB7~J4Q&7RI(&3/048JC9U|466%e0fHsoXU1*cK6yo6Ydj)946b-');
define('LOGGED_IN_KEY', '[#VX0|w2xL3:(nmG171:s/o6@)D2H7+B7bnt:&q9W2-]favkT%6WyTw[IM);Y)vi');
define('NONCE_KEY', '++~!1w/C!2zY%2v)/8H!3wV(6|e)W68(+JV1!m[p2h1F#UY*LuE)s+3b-%7rk;2J');
define('AUTH_SALT', '+W|@lEM|x~|ZYLzZ5*N93;:u6I83Os)8B:gft05&4J]E65gDEU*08k1]i2YtU[r!');
define('SECURE_AUTH_SALT', 'G1dV8q~(X|2%a(g6J_J|]22qMk|Ip24Xu94@(wyY6*3O[f35#O77Al3CJ084HM&B');
define('LOGGED_IN_SALT', '275T2BB1@LO|gHPqUg63*Y9vP7t|zM6%J9Mc6Z3EV82#zG68is_4y8Z3gE%X!Jb@');
define('NONCE_SALT', 'YK8n!s2n0BR_f2+6]vyzxnXHDE&)7N3M~JyL#arNa]B(H06XUYzuv8~VENc2_97~');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '1heg7_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', true);
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'DISALLOW_FILE_EDIT', true );
define( 'CONCATENATE_SCRIPTS', false );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
