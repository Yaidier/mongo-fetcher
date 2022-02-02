<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mongo_Fetcher
 * @subpackage Mongo_Fetcher/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mongo_Fetcher
 * @subpackage Mongo_Fetcher/public
 * @author     Yaidier Perez <yaidier.perez@gmail.com>
 */
class Mongo_Fetcher_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $mongo_fetcher    The ID of this plugin.
	 */
	private $mongo_fetcher;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $mongo_fetcher       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $mongo_fetcher, $version ) {

		$this->mongo_fetcher = $mongo_fetcher;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mongo_Fetcher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mongo_Fetcher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

         /**
          * No Public Scripts needed for this plugin so far
          *
          * wp_enqueue_style( $this->mongo_fetcher, plugin_dir_url( __FILE__ ) . 'css/mongo-fetcher-public.css', array(), $this->version, 'all' );
        */


	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mongo_Fetcher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mongo_Fetcher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        /**
          * No Public Scripts needed for this plugin so far
          *
		  * wp_enqueue_script( $this->mongo_fetcher, plugin_dir_url( __FILE__ ) . 'js/mongo-fetcher-public.js', array( 'jquery' ), $this->version, false );
        */

	}

}
