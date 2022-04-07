<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/gblessylva
 * @since      1.0.0
 *
 * @package    Trife
 * @subpackage Trife/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Trife
 * @subpackage Trife/public
 * @author     gblessylva <gblessylva@gmail.com>
 */
class Trife_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		require plugin_dir_path( __FILE__ ) . 'partials/class-register-custom-product.php';
		require plugin_dir_path( __FILE__ ) . 'partials/class-send-order-details-api.php';
		require plugin_dir_path( __FILE__ ) . 'partials/class-add-prodgy-settings.php';
		require plugin_dir_path( __FILE__ ) . 'partials/class-duplicate-admin-post.php';
		require plugin_dir_path( __FILE__ ) . 'partials/class-compare-admin-price.php';
		require plugin_dir_path( __FILE__ ) .'partials/class-create-cpt.php';
		require plugin_dir_path( __FILE__ ) . 'partials/class-cpt-custom-fields.php';	
		require  plugin_dir_path( __FILE__ ) . 'prodigi-cpt/prodigi-cpt-core.php';
		require  plugin_dir_path( __FILE__ ) . 'partials/includes/class-import-product-templates-csv.php';	
		require  plugin_dir_path( __FILE__ ) . 'partials/class-create-new-product-menu.php';
		require_once plugin_dir_path( __FILE__ ) . 'partials/class-add-variation-fields.php';
		require plugin_dir_path( __FILE__ ) . 'partials/includes/class-sync-order-status.php';
		require_once plugin_dir_path( __FILE__ ) . 'partials/includes/class-get-prodigi-quote.php';


		$this->plugin_name = $plugin_name;
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
		 * defined in Trife_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Trife_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/trife-public.css', array(), $this->version, 'all' );
		
		// enqueue Bootstrap
		// wp_enqueue_style( $this->plugin_name, 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), $this->version, 'all' );

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
		 * defined in Trife_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Trife_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script($this->plugin_name, 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/trife-public.js', array( 'jquery' ), $this->version, false );
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/trife-control.js', array( 'jquery' ), $this->version, false );
	}

}
