<?php

/**
 * The admin-specific functionality of the plugin.
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
		add_filter('wp_insert_post_data', array($this, 'check_and_correct_spellings'), 10, 2);
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
	 * Checks and corrects spelling of post content using OpenAI model.
	 *
	 * @since    1.0.1
	 * @param    array     $data     An array of sanitized attachment post data.
	 * @param    array     $postarr  An array of unsanitized attachment post data.
	 * @return   array     The filtered and corrected post data.
	 */
	public function check_and_correct_spellings($data, $postarr) {
		if ($data['post_status'] == 'publish') {
			$data['post_content'] = $this->correct_spelling_errors($data['post_content']);
		}
		return $data;
	}

	/**
	 * Correct spelling errors in text using OpenAI.
	 *
	 * @since    1.0.1
	 * @param    string    $content    The content with potential spelling errors.
	 * @return   string    The content with corrected spelling.
	 */
	private function correct_spelling_errors($content) {
		// Integrate with OpenAI API for spell-checking
		// This is a placeholder for actual API integration
		// Assume $corrected_content is received after processing
		$corrected_content = $content; // Replace this with actual API call result
		return $corrected_content;
	}
}

