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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "kinggems_protest" );

/** MySQL database username */
define( 'DB_USER', "kinggems_protest" );

/** MySQL database password */
define( 'DB_PASSWORD', "gJ7(7dD?%2!M" );

/** MySQL hostname */
define( 'DB_HOST', "localhost" );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '?W+#q<I)V!3(n1m4,`e{W%G!qsnEr_sfi%MZ9YqYo~.CA%;TSh]J*&8SkR^}?e2m' );
define( 'SECURE_AUTH_KEY',  'RZMWAJ/x`aR*G2Q+zb;897OKcra#QSQB;0t%Kj3U|XXwoHMK=l*qzFt{@Crk@?Qo' );
define( 'LOGGED_IN_KEY',    'o2{EJV*<zYAhRe+FHe[FUva)y4tTJj[hW7d)6qe1G-_(|i>bnCfJkt`_0:tTi/xf' );
define( 'NONCE_KEY',        ')8(qbA,P&.eG -0`SxzKI`c59[Y6!B1oc0)m8u eTR93{Cja1(Cqops>M$jVHW12' );
define( 'AUTH_SALT',        '4JeUHLED;9C;BJ)r[SsbXly<BgMcDwK0!({9y6&mV[DA),~]~bTaogMe!8M(?57T' );
define( 'SECURE_AUTH_SALT', 'Zu>?,X]XgfYmsFDen!u1Ag-V]Yub!tu]9SX6#)g.c>Qot9 I$}t9.<Qfx/8kekn,' );
define( 'LOGGED_IN_SALT',   'OMcS.2|0C$ot{=rg{G.4>rur,kg,U8Eq7WqDJ9&C4(Q,qQX9jU(Y+w|}Zg~:c;xV' );
define( 'NONCE_SALT',       'b<s$(ZUWie~b~ K@AD@hm) [h;@5Lv1kjg{w.H~)xxGg`{_643dm&EZXA^Q$doO6' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
