<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Adds spell-checking capabilities to publish posts using OpenAI API.
 *
 * @link       https://wizeline.com
 * @since      1.0.0
 *
 * @package    Demo_Plugin
 * @subpackage Demo_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Demo_Plugin
 * @subpackage Demo_Plugin/admin
 * @author     Wizeline <dev@wizeline.com>
 */
class Demo_Plugin_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_filter( 'wp_insert_post_data', array( $this, 'spell_check_published_post' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/demo-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demo-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under settings menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_options_page(
			__('Demo Plugin Settings', 'demo-plugin'),
			__('Demo Plugin', 'demo-plugin'),
			'manage_options',
			'demo-plugin-admin',
			array($this, 'display_plugin_admin_page')
		);
	}

	/**
	 * Display admin options page.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once('partials/demo-plugin-admin-display.php');
	}

	/**
	 * Register settings for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function register_plugin_settings() {
		register_setting('demo-plugin-settings-group', 'enable_spell_check');
	}

	/**
	 * Perform spell check on published posts.
	 *
	 * @since    1.0.0
	 * @param    array    $data    An array of unsanitized post data.
	 * @param    array    $postarr    An array of the post data.
	 * @return   array    $data    Updated post data with spell-checked content.
	 */
	public function spell_check_published_post($data, $postarr) {
		
		// Check if spell checking is enabled in settings
		$is_enabled = get_option('enable_spell_check');
		if (!$is_enabled) {
			return $data;
		}

		// Check if post status is published
		if ($data['post_status'] !== 'publish') {
			return $data;
		}

		// Get the content to be checked
		$content = isset($data['post_content']) ? $data['post_content'] : '';

		// Assuming there is a class / function demo_plugin_spell_check
		// to handle API call and return corrected content
		$corrected_content = demo_plugin_spell_check($content);

		// Update post data
		$data['post_content'] = $corrected_content;
		
		return $data;
	}

}

