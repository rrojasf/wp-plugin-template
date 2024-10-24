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
	 * Hook into wp_insert_post_data to check and correct spelling.
	 *
	 * @param array $data The post data.
	 * @param string $postarr The original post array.
	 * @return array The modified post data.
	 */
	public function filter_post_content( $data, $postarr ) {
		// Only process published posts
		if ( isset($data['post_status']) && $data['post_status'] === 'publish' ) {
			$data['post_content'] = $this->correct_spelling($data['post_content']);
		}
		return $data;
	}

	/**
	 * Correct misspelled words in the content using OpenAI API.
	 *
	 * @param string $content The post content.
	 * @return string The corrected content.
	 */
	private function correct_spelling( $content ) {
		// Call to OpenAI API to check and correct spelling
		$api_url = 'https://api.openai.com/v1/engines/gpt-4o-mini/completions';
		$response = wp_remote_post( $api_url, [
			'headers' => [
				'Authorization' => 'Bearer YOUR_API_KEY', // Replace with your actual API key
				'Content-Type' => 'application/json',
			],
			'body' => json_encode([
				'prompt' => $content,
				'max_tokens' => 400,
			]),
		]);
		if ( is_wp_error( $response ) ) {
			return $content; // Return original content on error
		}
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		return $data['choices'][0]['text'] ?? $content; // Return corrected content
	}

}