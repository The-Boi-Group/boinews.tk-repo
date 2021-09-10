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
define( 'DB_NAME', 'example_db' );

/** MySQL database username */
define( 'DB_USER', 'admin' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Hellosystem35!' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'QFM`jMjv $:%O,Xp(lfYpp.OM+*3{Q2-lG#))JBz3NfRB##~]#F[4:-4RF%RYERc' );
define( 'SECURE_AUTH_KEY',  'Aq//GOC-%#[~,(cE1dUab$Ag<h+:$#,KK@lV3t9S!,_1b~,:AH>ukcbu-P>tz^Sm' );
define( 'LOGGED_IN_KEY',    ')>FuyjFETR41q^ B.Q9DG6Z[x1d=yKrBYhPEi81=PnC]PB*8<f]^81]#fLfzB^BH' );
define( 'NONCE_KEY',        '2Hk/Hn=MdgU`=Le{{zd<)zE:8-r|xyyG&ucOnsGAT4OOz%Fg#?pPIg/)T2hlHk9=' );
define( 'AUTH_SALT',        'K]`[Zn/!*g5Z~H!_pF*k<(e:V)eNlfwA7IQ9.Ss)G4jj_[SSGp(p&2-op.6 ,HdF' );
define( 'SECURE_AUTH_SALT', ',>),C#v|oi%o1RZ@9d=oQI7c9ykoHyOn:^=BCmkr6tN5^&OZfr?G 2tnI;~tpl,/' );
define( 'LOGGED_IN_SALT',   'li>wJq{Y1rl,WI2b]y<oP2b97&0iiCk K43#)wY Bvz9|-b3g0K-E&s`]R$>-I$a' );
define( 'NONCE_SALT',       'n=Ucu2I)RKDY@Q/(ZB%@~oBVR#=$x,(pZUZm*8lrf,*Asz_ :$Hw1o1_o[60@DWh' );

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

