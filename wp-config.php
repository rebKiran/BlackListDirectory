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
define('DB_NAME', 'blacklistdir');

/** MySQL database username */
define('DB_USER', 'crmuser_new');

/** MySQL database password */
define('DB_PASSWORD', 'Reb@123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '@(m)U)jP9Z!3eFh~mE*&c1}/As.ej/%f 0I0$g&7VU8;lrC*AmAM^!|X+*w!/$Z/');
define('SECURE_AUTH_KEY',  'APe:Yvleae6ITJfJ1;clshfit~dzQL#ouJIOQ/?zMI<f1(hR:HOAuV`11z17<[&Z');
define('LOGGED_IN_KEY',    '/G>(!*Po|(L_P6xEhlpV$z~L,FOA+1zk}%bm)/h4Lpko0/E28^,lw}I2sB@<R?{w');
define('NONCE_KEY',        '%ME~X&y}Yz>U+8o*,}fvQOL+%;En_q}z3n_M}jZQA_/t{Che2]Vxj]U39AJVJ`f ');
define('AUTH_SALT',        'I@+fT!|T^nWNH!E9$,KoM2+skVoEsoo:sUZ3&Aj?j!mize^Gjx`ef1~P}jT|8>/s');
define('SECURE_AUTH_SALT', ')XdaEew`D#fIty=AZ{ $k]o/2}*Bhv?YgO1k2=kxj2X^9h%zDG7U,CKM.vZI4g5c');
define('LOGGED_IN_SALT',   '<+Zt4>icc+l@AxrR2{W9lrjk{O |/bnr%mIJJ33;D%i8S!v~[eT`6@9B<Wxu0~UU');
define('NONCE_SALT',       '0r-HCT:s`%VV1%lL3ufjM 3:~~?8[I}aWXb9>-8~dxodi%`f6qwOep^WsQwXH3n1');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);
define('WP_CACHE', false);
define( 'SAVEQUERIES', false);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
