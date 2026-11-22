<?php

/**
 * Plugin Name: WP Social Login
 * Plugin URI: https://github.com/MahimZaman/wp-social-login
 * Description: Google login for WordPress & WooCommerce (custom OAuth)
 * Version: 1.0.0
 * Author: Mahim Zaman (Condixia)
 * Author URI: https://condixia.com
 * Text Domain: wpsl
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

define('WPSL_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('WPSL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WPSL_VER', '1.0.0');
define('WPSL_TEXTDOMAIN', 'wpsl');

// import all the necessary classes

require_once WPSL_PATH . "includes/classes/class-admin.php";
require_once WPSL_PATH . "includes/classes/class-public.php";
require_once WPSL_PATH . "includes/classes/class-auth.php";
require_once WPSL_PATH . "includes/classes/class-google.php";
require_once WPSL_PATH . "includes/classes/class-user.php";

(new WPSL\Admin)->register();
(new WPSL\Frontend)->register();
new WPSL\Auth();
