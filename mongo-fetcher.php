<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Mongo_Fetcher
 *
 * @wordpress-plugin
 * Plugin Name:       Mongo Fetcher
 * Plugin URI:        http://example.com/mongo-fetcher-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Yaidier Perez
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mongo-fetcher
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
define( 'MONGO_FETCHER_VERSION', '1.0.0' );

/**
 * Currently plugin path.
 */
define( 'MONGO_FETCHER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mongo-fetcher-activator.php
 */
function activate_mongo_fetcher() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mongo-fetcher-activator.php';
	Mongo_Fetcher_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mongo-fetcher-deactivator.php
 */
function deactivate_mongo_fetcher() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mongo-fetcher-deactivator.php';
	Mongo_Fetcher_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mongo_fetcher' );
register_deactivation_hook( __FILE__, 'deactivate_mongo_fetcher' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mongo-fetcher.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
$mongo_fetcher_plugin = new Mongo_Fetcher();
$mongo_fetcher_plugin->run();


