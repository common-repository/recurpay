<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://recurpay.com/
 * @since             1.0.0
 * @package           Recurpay
 *
 * @wordpress-plugin
 * Plugin Name:       Recurpay
 * Plugin URI:        https://recurpay.com/
 * Description:       Subscriptions for Woocommerce. Powered by Recurpay. 
 * Version:           2.1.1
 * Author:            Recurpay 
 * Author URI:        https://www.recurpay.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       recurpay
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RECURPAY_VERSION', '2.1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-recurpay-activator.php
 */
function activate_recurpay() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-recurpay-activator.php';
	Recurpay_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-recurpay-deactivator.php
 */
function deactivate_recurpay() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-recurpay-deactivator.php';
	Recurpay_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_recurpay' );
register_deactivation_hook( __FILE__, 'deactivate_recurpay' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-recurpay.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_recurpay() {

	$plugin = new Recurpay();
	$plugin->run();

}
run_recurpay();
