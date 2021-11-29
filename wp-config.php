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
define('DB_NAME', 'conextuval');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'ogJIH58ga8GzKbSl');

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
define('AUTH_KEY',         's^&w{8/( }}p7F3zz)rlU}Dfdoja_sh(,|f*+oM>oAKvhM:NCkA?DzvDn>L7-B]E');
define('SECURE_AUTH_KEY',  '=(%;stH!8pI!ir6ks}hrG%,WBio?cQZ$jmvtMejrThpn!e8?a{y|^yHM)<?T,sLg');
define('LOGGED_IN_KEY',    'S[:{VuCXz4I;yXh2w*_lVR2}AV-I:>Xq87-EpF}{I.U/9{R8gw)#e_rrP:h;vahx');
define('NONCE_KEY',        '~#qF$fyW9u?/b]XXucLf~xV]y?{Hl)!}QJv=BAu=;`Ur)&5Z4o&D18q&0%837%}-');
define('AUTH_SALT',        '[Gh$Q8WYBm>s.7q{cee* Z$rA$PJbXBBI3I(Du&T+QahS0./@!fI$fnH}(Z$)Qbl');
define('SECURE_AUTH_SALT', 'q#F58/kJ?H|jyin&}(f=v:m1n@bKlHD{*cMw4N*j}i(bdrP)]Gs-8t4ct&Z{0)m?');
define('LOGGED_IN_SALT',   '))> )J}y<)<<W%EFeo-[K_!EsfDLfD~0==Nz4*JPP[=3>4GT?&qtCntv-RJa7pDw');
define('NONCE_SALT',       '8, 1W}2(m.La3uSmRs.{4EpkXbNt.1ppe<4^i_g7TZtV 1Mr.eYo],7e.&&SfbrS');
define('WP_MEMORY_LIMIT', '128M');
define( 'WP_MAX_MEMORY_LIMIT', '256M' );

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
