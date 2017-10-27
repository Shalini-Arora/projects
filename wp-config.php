<?php

define('FS_METHOD', 'direct');
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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db675014604' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'R3v3nge@321' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ')pO;pI Tk1-XRa,{M{*iRffj-qE|PVi8CKXYS+K_4RzYXtR5Xdji(!q0mWL-Kyla');
define('SECURE_AUTH_KEY',  'M-qN8rb*2?uV$JO-j*t8rFOWvAuJ5jc@CLqztQ-1-8{u{~=~Xq.T00gU.ArJZR:M');
define('LOGGED_IN_KEY',    '$OH^vJa+m,}!fgh)o~MyL`~#1*x,xjGj)1fz1GOZCt>#TtNM`UZgD|pm5R{VrP?Y');
define('NONCE_KEY',        '$nL3h{{]B$15!}`0N5h2x$xjro$Swg|<eE{cQOY?FTQVoz8&<Iy^iIr~`do )nJ+');
define('AUTH_SALT',        'Cc+:1##e:cRk_k^*RBc>_4J0RW^SCRy9O^2}|6[ibncO-VvX7M95-.=b/w`P`OcU');
define('SECURE_AUTH_SALT', 'neD?< snGCQNc=>D&v!}5c=3)k |}FhM+>}k--`{9T&*hj(e./t.nneO@ymh@-hw');
define('LOGGED_IN_SALT',   '8}uy@Im?NiC4=QP<a-=}A|J]M/QS=ZLb.&pT[zW(xJCfm]>Ijx{1ab_]#g|3 PAd');
define('NONCE_SALT',       's$hqV5Z|xg%HY|]&^0<X(|QrM.Pwl.~P(QSCk<qk&sw2V?Xh{r^ >`j?RRCSpKQo');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'LMTccWlC';

ini_set('log_errors','On');
ini_set('display_errors','Off');
//ini_set('error_reporting', E_ALL );
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);



/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

