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
define( 'DB_NAME', 'db' );

/** Database username */
define( 'DB_USER', 'db' );

/** Database password */
define( 'DB_PASSWORD', 'db' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define( 'AUTH_KEY',         'm#:62`;X&Jil*Z=vF2yu*ihBxpQ.)Z:5#8pXaef96tKFqQFg%l{f/X%%$UKW2sgY' );
define( 'SECURE_AUTH_KEY',  'u;xB>Zg*=P$*tKO|Zsrgjd3J`*%=>(A9F*-Gz.]WSeWWL.zp<Ncd|?aR^{eX:B9#' );
define( 'LOGGED_IN_KEY',    '(sxsX## _Mat^NsE=[!#{,PNQoqYdsgErq&*rc4*L]9:1T0oY0G$d.4?U%h-_k{m' );
define( 'NONCE_KEY',        '%jPAs-+1^U72v4n~Zjq{0y-`qKr.j`X]wa xTdIy`bY%s.LFWnL.k~|o]7d+rJ q' );
define( 'AUTH_SALT',        'W5NK}8}I5w=H[8|Kn]t7uR^<f%fg@wnN4yhz-ob,GYeG_A<>9,3{9Qd1)<6Ja!2.' );
define( 'SECURE_AUTH_SALT', '%F^i^(.!a:uoK7CCoad#5O_B0{LkMOTc43hDNjV@26P|Az/!3$7!cfZa{2X2gT^}' );
define( 'LOGGED_IN_SALT',   'a{y >_d= SMTSm(H@t,eag}]l{<gFJjLL_%^;>/Ij?7gj E9tZJ~,,S#[;nvGgpr' );
define( 'NONCE_SALT',       't%.%Si$k~T0PTC1<*z!k@A9Q|mN+:1Xls6N=o5I&c&i51saWe&#_z91korDj;eLA' );

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
