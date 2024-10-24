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
 * Defines the plugin name, version, and includes hooks for enqueueing 
 * admin-specific stylesheets and JavaScript, as well as a filter for spell checking.
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

		// Hook into the post data before saving.
		add_filter('wp_insert_post_data', array($this, 'check_and_correct_spelling'), 10, 2);
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
	 * Check and correct spelling errors in post content.
	 *
	 * This function hooks into 'wp_insert_post_data' and processes the post 
	 * content for spell checking when saving a post.
	 *
	 * @since    1.0.1
	 * @param    array $data An array of post data.
	 * @param    array $postarr An array of post input data.
	 * @return   array Modified post data with corrected spelling.
	 */
	public function check_and_correct_spelling( $data, $postarr ) {
		// Only process when the post is published.
		if ( $data['post_status'] === 'publish' ) {
			$data['post_content'] = $this->correct_misspelled_words( $data['post_content'] );
		}
		return $data;
	}

	/**
	 * Correct misspelled words in the provided content.
	 *
	 * This function identifies and corrects misspelled words using a predefined
	 * dictionary or spell check API.
	 *
	 * @since    1.0.1
	 * @param    string $content The content to be spell-checked.
	 * @return   string The spell-checked content.
	 */
	private function correct_misspelled_words( $content ) {
		// Placeholder for actual spell check logic, using simple replacements as an example.
		$misspellings = array(
			'wrod' => 'word',
			'wrld' => 'world',
		);

		foreach ( $misspellings as $wrong => $correct ) {
			$content = str_replace( $wrong, $correct, $content );
		}

		return $content;
	}
}
