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
define( 'DB_NAME', 'db_blogldr' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'X>@bL? ?V:9^^$8xr$P^V% QUK&yhS SgY<^P]j20jn`r0^Lio/-u$,m6XYmu.{a' );
define( 'SECURE_AUTH_KEY',  '<E];l_XJ0tjvioW2/rfi%eY ~Yr0xkL=-peIg&r$JQ4 1z3i&qC1b=#2U}{lws+a' );
define( 'LOGGED_IN_KEY',    '3chG2{ur#e(:w0>F#<r CuFtcSKaC*C1048@_CAlx K-&8aWnS_IvCAf:#j7[sDq' );
define( 'NONCE_KEY',        '_]Xm.%=`-2>XfZ9(4*^D%XgeAtJ;/a|0NPbuq-]e7W36g]4j7-LY;DSe_IB3OZ77' );
define( 'AUTH_SALT',        'Z7<6:xlyba~v{tN6 k$EMo!Z1ep7>j~.irV|uL&KT<[Vv :M!9SJN `t[(`=xts/' );
define( 'SECURE_AUTH_SALT', 'NQa,ZHuZlY6=#1sWR PaG2^A2e}[+m|b3fsdJ_1yf2S!llwsU^<:4oq5`KTNlt+1' );
define( 'LOGGED_IN_SALT',   '0/F*;h ocIGvFM_V<oPoILn)}H?^SJ(DNt-9T?DuFS7lw,$GyT1[p?DulKm%4Awg' );
define( 'NONCE_SALT',       'e`27+derVtFY?Q#qaDYLVk%(R&s&+#p%0=SEo9;K<I~4h:VDhDh,5@%B^`5)7U96' );

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
