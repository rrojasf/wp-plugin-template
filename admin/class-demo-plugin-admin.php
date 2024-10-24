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
 * enqueue the admin-specific stylesheet and JavaScript. Also includes
 * functionality to register spell-check filter for published posts.
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

        // Add filter hook to register spell-checking process.
        add_filter( 'save_post', array( $this, 'spell_check_post_content' ) );

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
     * Spell check post content before saving.
     *
     * @param  int  $post_id  The ID of the post being saved.
     * @since  1.0.1
     */
    public function spell_check_post_content( $post_id ) {

        // Check if this is an autosave or a revision to avoid unnecessary processing.
        if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
            return;
        }

        // Only run on published posts.
        if ( get_post_status( $post_id ) !== 'publish' ) {
            return;
        }
        
        // Check user capabilities.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Retrieve post content.
        $post_content = get_post_field( 'post_content', $post_id );

        // Call OpenAI API (pseudo-code, implementation in actual API integration).
        $corrected_content = $this->check_spelling_via_openai( $post_content );

        // Log if API returns an error or content remains unchanged.
        if ( is_wp_error( $corrected_content ) || $corrected_content === $post_content ) {
            // Log error or unchanged status.
            error_log( 'Spell check failed or returned identical content for Post ID: ' . $post_id );
            return;
        }

        // Update post with corrected content.
        wp_update_post( array(
            'ID'           => $post_id,
            'post_content' => $corrected_content
        ) );

    }

    /**
     * Stub function for integrating OpenAI spell check.
     *
     * @param string $text The text to be checked.
     * @return string|WP_Error Corrected text or error.
     * @since 1.0.1
     */
    private function check_spelling_via_openai( $text ) {
        // This should contain the integration logic with the OpenAI API.
        // Returning the text as-is for now since actual API call is not implemented.
        return $text; // Placeholder
    }

}
