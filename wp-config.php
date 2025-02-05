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
define( 'DB_NAME', 'traffixworld' );

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
define( 'AUTH_KEY',         'uM(k:DhAS)(Z(4Ov|dWa%,*[6`<@`sB5W0 geH)KlnU&,0[fDhAw$lBxnAQc@PD9' );
define( 'SECURE_AUTH_KEY',  'u9p- zOdl4d-7R-G|9*>#/1f>3Ypirw8Wm!]A]Rji`/35mOZ9~wmx-/b^/.XjFl$' );
define( 'LOGGED_IN_KEY',    'H^*QUa<Ews/G%[,0[A8gJ[V%p=Juon}RW4m?M7wFi^i9#&_NK3SlR[rtG<j_,69U' );
define( 'NONCE_KEY',        'j;PtBP$6V4;f*jF$Ox)DwDhoT?]yk{d|h~Rd}Q2#)Hrh|Ax)]~ij]b-bXD20^MbY' );
define( 'AUTH_SALT',        'kKE?bp-wy!V %]i)Kk*463V@*A.qQ[>DtP^1#G. TLLXwMxcz@]fRpqKI[h-nEo9' );
define( 'SECURE_AUTH_SALT', '&&-:wzcgS(fk6/?*mz8-~{8@8lG32SDLnId%4e>@E`AHI|k] [m^t<_p/Am |WBv' );
define( 'LOGGED_IN_SALT',   'z>ke&/m0ntAXJFw-mHsAsCQd_qQ@h;%VOLlk%wP&>~=I.n%Lbe(iqE&KU,]DkH<&' );
define( 'NONCE_SALT',       'P)}h6Zq|nm3;HYkXP7`l#JjDHY=AsP3IvZxcuJ91ja<y./S,[mhoM(X=.^>%Ua,5' );

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
