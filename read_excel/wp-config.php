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
define( 'DB_NAME', 'cargo' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '0e KBX/0tQt9mccu0sNY%=>LwS;2(Q!KR/j3vzU8q}uYJg9}9@N+2>=:8:Mqj:{R' );
define( 'SECURE_AUTH_KEY',  'T+|.$;xqbwv?8Hub h@ZB_ohX2]hzN~+Kj;T+`wLO-.`-qG__qkz6N72)~n>$bD ' );
define( 'LOGGED_IN_KEY',    'g^Jl9OK-3D^Dp;}({JC7q4rzU{O]=Bh9et=SNflq3ZpT@qS}3r$TKowO>zJ#=&)s' );
define( 'NONCE_KEY',        'o`Htr}@{^z`TJ&,*}qgW42y$TVTn,={CURYm1vs(}KNlW*5n,,x~0/[*ssm!O=6]' );
define( 'AUTH_SALT',        'RVVY9|_=FGr%? Kv{EPM(t%nDXZZPRYw24r%@Z0Q.yHd@sLA~vES|d.T!$@G+q-N' );
define( 'SECURE_AUTH_SALT', 'ki$F>=.n|a%3[7|7ddNK2VCE6{=/ldR,T*x-xmPX?wZMYhoS`jL}UNx%6<7raX#3' );
define( 'LOGGED_IN_SALT',   'Qil&X;`(^oAk! >SI@M%r082My^x(w>{Q%0Hk@8!)~j1~2wk_?3,MM3eU{k@>9cq' );
define( 'NONCE_SALT',       'q>%DIuhby13hQsuC8>|Ggo%e!!KqfdeWYN,T`b5&M(@HK!3hqnu!5^sEy)!V/mq?' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ca_';

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
