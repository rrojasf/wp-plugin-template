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

	// Private properties
	private $plugin_name;
	private $version;

	// Constructor method
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Register hook for spell check
		add_action('pre_save_post', array($this, 'check_post_spelling'));
	}

	// Method to enqueue styles
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/demo-plugin-admin.css', array(), $this->version, 'all' );
	}

	// Method to enqueue scripts
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demo-plugin-admin.js', array( 'jquery' ), $this->version, false );
	}

	// Method to check and correct spelling errors in posts
	public function check_post_spelling($post_id) {
		$post = get_post($post_id);

		// Check if post is published
		if ('publish' !== $post->post_status) {
			return;
		}

		$content = $post->post_content;
		$corrected_content = $this->get_corrected_content($content);

		// If corrections are made, update the post content
		if ($corrected_content !== $content) {
			remove_action('pre_save_post', array($this, 'check_post_spelling'));
			wp_update_post(array(
				'ID' => $post_id,
				'post_content' => $corrected_content,
			));
			add_action('pre_save_post', array($this, 'check_post_spelling'));

			// Add admin notice for correction
			add_action('admin_notices', function() {
				echo '<div class="notice notice-success is-dismissible"><p>Post content has been spell-checked and corrected.</p></div>';
			});
		}
	}

	// Method to handle OpenAI API interaction
	private function get_corrected_content($content) {
		// Check if cached response exists
		$cache_key = 'spellcheck_' . md5($content);
		$cached_content = get_transient($cache_key);

		if ($cached_content) {
			return $cached_content;
		}

		// Prepare the API request
		$api_key = 'your-api-key-here'; // This should be securely stored
		$response = wp_remote_post('https://api.openai.com/v1/spellcheck', array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $api_key,
			),
			'body' => json_encode(array('text' => $content))
		));

		// Error handling
		if (is_wp_error($response)) {
			error_log('Error accessing OpenAI API: ' . $response->get_error_message());
			return $content;
		}

		$body = json_decode($response['body']);
		$corrected_content = $body->corrected_text ?? $content;

		// Cache corrected content
		set_transient($cache_key, $corrected_content, HOUR_IN_SECONDS);

		return $corrected_content;
	}
}
