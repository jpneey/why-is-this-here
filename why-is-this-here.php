<?php
/**
 * Plugin Name: Why is this here
 * Plugin URI: https://jpburato.now.sh
 * Description: Site owners often inherit websites with 40 active plugins and have no idea what half of them do. This utility injects a tiny "Add Note" link beneath each plugin on the Plugins page.
 * Version: 1.0.0
 * Author: JP Burato
 * Author URI: https://jpburato.now.sh
 * Text Domain: why-is-it-here
 * Domain Path: /i18n/languages/
 * Requires at least: 6.8
 * Requires PHP: 7.4
 *
 * @package WhyIsItHere
 */

defined( 'ABSPATH' ) || exit;

define( 'JP_WIIH_V', '1.0.0' );
define( 'JP_WIIH_DIR', plugin_dir_path(__FILE__) );
define( 'JP_WIIH_URL', plugin_dir_url(__FILE__) );

include JP_WIIH_DIR . '/inc/about.php';
include JP_WIIH_DIR . '/inc/notes.php';