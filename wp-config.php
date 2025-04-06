<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '[R gX01te% 2|z5lDbZk1Qx[w|Xomy;]jrY:<H+5qLe&g%Nezt-iewBU&ogF&j5c' );
define( 'SECURE_AUTH_KEY',  '98{jl@0z)/5x4x&a &)6jM&(wbR{IkT3|gyC7M9WfY,# 0/95584OWn&ZEX:dV_Z' );
define( 'LOGGED_IN_KEY',    'L}gp30d}^IDpOv7}[wgQ72Bascn$IN*DRJ#B_2tcqhbq^9E~5{X;)N+.Dbg<b#L{' );
define( 'NONCE_KEY',        'YPCQ7hp,^o0#ak!OEA@_fxwU@bQoJ6h8OE|HtVE>c&*0yN2>9Xe3?<acppgsIoX+' );
define( 'AUTH_SALT',        'JTj34EEp3$9DhDMG{XXsY-qN&t9]F;%d=hq4h<+?5kH5Ds5ZkK94$<=)A5W&I{T:' );
define( 'SECURE_AUTH_SALT', 'Fj^R4PDN4x:I~qZq~B<XJ.F .mSee_N]gQc!r?A5)-o;#^H2WCN($}^W[b{dxI2_' );
define( 'LOGGED_IN_SALT',   '@>% N:yx4]C]v4o+th|V:)nWVk_{17r4`*7N{Lb/LN/XG!gZ(0HF2(5ABqxEmU;X' );
define( 'NONCE_SALT',       '*D/gEt!u}D8gW*|rimfc@kE.Ao])FL5SE*a3m9%F>]I%`.h!w =grGN~NASL:pT3' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
