<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'footysq_db');

/** MySQL database username */
define('DB_USER', 'footysq_db');

/** MySQL database password */
define('DB_PASSWORD', 'CE5hefd5OFdMJlzO1');

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
define('AUTH_KEY',         'Q[Y1K-9Cfp.XpnS4J|a@r-+@ 2sIc*EtL^;U:AoH9vLbe#?`k[mY7OlB+>E~w4u:');
define('SECURE_AUTH_KEY',  'z8p(z){-j{$iFI+j^OgOik/Rv]-!.aZ|+ZVBv<F9z9<d|o|#ACQ-?o.-DTNe(V^K');
define('LOGGED_IN_KEY',    '26KMY+vK$,gL`*Pp0k@O.xTX3ivDHR:Ne6QOsr?bc7TiESpL4Ey?t-ai2VM# -nt');
define('NONCE_KEY',        'x-U9|w)ANtEF_Z4x<9AZf ,HEL69PlH}16*xBS*;[zZ5A6QGTS/vY<}ou3lU)g$=');
define('AUTH_SALT',        'yAXiP`ANs=.k]sIxvXzA-(BRY7IF?;!+<,+)@C`+p32oP@-Fnp!@MD-)Zo>KHPc<');
define('SECURE_AUTH_SALT', '5EL+AQIZ%{^|nudR1)WhrS/+}#55Qr-yk9xy4IyALsy2_PLj{s$<Fe9)DSD>!mn~');
define('LOGGED_IN_SALT',   '-W=W0%wonM;YF|o-vOo*U>|U~za*hCec`fQhVaP+VNOTdmbmP3IB.RdIX)j]MBPe');
define('NONCE_SALT',       '-YeRf.tbqXxfF:+K@y68qk&]X9AOj tJbWO0)7}k-w`i2!Qu:`Tbo@E@J&:M3 $.');

/**#@-*/

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
