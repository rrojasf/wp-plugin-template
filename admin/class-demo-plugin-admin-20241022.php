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
 * Defines the plugin name, version, and hooks to enqueue the admin-specific
 * stylesheet and JavaScript. Also adds a spell-checking filter for posts.
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
		add_filter('wp_insert_post_data', [$this, 'correct_misspelled_words'], 10, 2);
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
	 * Correct misspelled words in post content.
	 *
	 * This function is a filter hooked to 'wp_insert_post_data'. It processes the
	 * content of published posts and corrects any misspelled words.
	 *
	 * @since    1.0.0
	 * @param    array  $data    An array of slashed, sanitized, and processed post data.
	 * @param    array  $postarr Raw post data.
	 * @return   array  Modified post data with corrected content.
	 */
	public function correct_misspelled_words($data, $postarr) {
		if ($data['post_status'] === 'publish') {
			$data['post_content'] = $this->perform_spell_check($data['post_content']);
		}
		return $data;
	}

	/**
	 * Perform spell check on the given content.
	 *
	 * This is a simple implementation for demonstration purposes.
	 *
	 * @since    1.0.0
	 * @param    string  $content  The content to be checked.
	 * @return   string  The corrected content.
	 */
	private function perform_spell_check($content) {
		// Placeholder for real spell checking logic.
		$correct_words = array('WordPress', 'plugin', 'function');
		$misspelled_words = explode(' ', $content);
		$corrected_content = '';

		foreach ($misspelled_words as $word) {
			if (!in_array($word, $correct_words)) {
				// Simulate correction
				$word = '[corrected]';
			}
			$corrected_content .= $word . ' ';
		}

		return trim($corrected_content);
	}
}
