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

		// Hook into WordPress post save
		add_filter('wp_insert_post_data', array($this, 'correct_misspelled_words'), 10, 2);
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
	 * Corrects misspelled words in the post content.
	 *
	 * This function hooks into the 'wp_insert_post_data' filter and corrects
	 * spelling in the post content of published posts before saving.
	 *
	 * @param array $data An array of slashed post data.
	 * @param array $postarr An array of unslashed post data.
	 * @return array Filtered post data.
	 */
	public function correct_misspelled_words( $data, $postarr ) {
		if ( 'publish' !== $data['post_status'] ) {
			return $data; // Only act on published posts
		}

		// Dummy implementation of a spell-checking logic
		$content = $data['post_content'];
		$content = $this->perform_spell_check( $content );
		$data['post_content'] = $content;

		return $data;
	}

	/**
	 * Perform a dummy spell check on the content.
	 *
	 * This function simulates a spell-checking process by replacing
	 * certain 'misspelled' with corrections.
	 *
	 * @param string $content The content to be checked.
	 * @return string The corrected content.
	 */
	private function perform_spell_check( $content ) {
		// A simple demo dictionary of corrections
		$corrections = array(
			'misspelled' => 'corrected', // Example correction
			'wrd' => 'word',
		);

		foreach ( $corrections as $incorrect => $correct ) {
			$content = str_replace( $incorrect, $correct, $content );
		}

		return $content;
	}
}
