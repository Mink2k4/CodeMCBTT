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
define( 'DB_NAME', 'qgamecltxfun_wp_okmhg' );

/** Database username */
define( 'DB_USER', 'qgamecltxfun_wp_11ts8' );

/** Database password */
define( 'DB_PASSWORD', '?EO80X4b_ZbuUW0x' );

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
define('AUTH_KEY', '1Tk5O3Uh2X8nO[5w[[ha1I4z!cZ8hzq28|/5eMK[1ZS_f|4aMb6Sifr%(R*t2N04');
define('SECURE_AUTH_KEY', 'eq6P4*570OAPU56vy|t/1c55SZh2A5XS|d&;|J&68@-5&5lugP((3slT0[x3(P05');
define('LOGGED_IN_KEY', '/h!9[H|L!hD%5g2&)*V]BQ4YE@1(994Q)KY)4R#s82/h(3Ld8Z#m7P38r&Ay[(BY');
define('NONCE_KEY', '3~l#K!18[6DBbl52e9**G2O~/gLL:y71i#5dGe]d7&~+M6D-I2)s)i;i*88|t]!8');
define('AUTH_SALT', '2&965FB;A[38V45FRw290*2X7888#(ha5-lq)qQBbg6c(!Q[R0/mA5~6rd@4(9)0');
define('SECURE_AUTH_SALT', '(SFOk3xe9dPu3E]wuSC:B&2y/@%jJz!1&XY53_Y3)K8wN73x%C:n2omckf9l&U00');
define('LOGGED_IN_SALT', 'J)7*W5!(TmPav7y~@TJ]m_B61zN!w5_Ys(6u#l-Oa9D~5_c%aU_HLt9g&Fj32qsh');
define('NONCE_SALT', 'gBhI0ud5zCE49]A*tT1!Ur_83;[AyW5ZIi&2MwF1a+&g2i0t[K_D9i-4;ZwIqGwk');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'hgNHo_';


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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
