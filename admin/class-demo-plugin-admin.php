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
 * enqueue the admin-specific stylesheet and JavaScript. Additionally, includes a filter
 * that checks for spelling errors on published posts.
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
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Register the spell check filter for published posts.
		add_filter( 'content_save_pre', array( $this, 'spell_check_published_content' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        // Enqueue admin styles.
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/demo-plugin-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        // Enqueue admin scripts.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demo-plugin-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Perform spell checking on content for published posts using OpenAI API.
	 *
	 * @since 1.0.0
	 * @param string $content The post content.
	 * @return string The possibly corrected content.
	 */
	public function spell_check_published_content( $content ) {
		// Check if spell checking is enabled in admin settings.
		if ( $this->is_spell_check_enabled() && 'publish' === get_post_status() ) {
			$content = $this->perform_spell_check( $content );
		}
		return $content;
	}

	/**
	 * Check if spell check feature is enabled in admin settings.
	 *
	 * @return bool True if enabled, false otherwise.
	 */
	private function is_spell_check_enabled() {
		// Option for enabling/disabling spell check.
		return get_option( 'demo_plugin_sp_check_enabled', true );
	}

	/**
	 * Interact with OpenAI API to perform spell checking on content.
	 *
	 * @param string $content The content text to be checked.
	 * @return string The content after spell check.
	 */
	private function perform_spell_check( $content ) {
		$openai_client = new Demo_Plugin_OpenAI_Client();
		try {
			$corrected_content = $openai_client->spell_check_text( $content );
			return $corrected_content;
		} catch ( Exception $e ) {
			// Log the error message.
			error_log( 'Spell checking failed: ' . $e->getMessage() );
			return $content; // Return the original content in case of failure.
		}
	}
}