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
 * Defines the plugin name, version, and examples hooks for enqueuing
 * admin-specific stylesheet and JavaScript.
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
        add_filter('pre_save_post', array($this, 'spell_check_post_content'), 10, 2);
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
     * Spell check the content of a post.
     *
     * @since    1.1.0
     *
     * @param    string $content    The content of the post.
     * @param    int $post_id       The post ID.
     */
    public function spell_check_post_content($post_id) {
        // Only check for published posts
        if (get_post_status($post_id) !== 'publish') {
            return;
        }

        $content = get_post_field('post_content', $post_id);

        // Call OpenAI API (Mock example)
        $response = $this->call_openai_api($content);

        if ($response) {
            // Update post content
            // handle API Response
            // Cache the result if needed
            // Use wp_update_post() to update
        }

        // Add admin notice if content was changed
        add_action('admin_notices', array($this, 'display_admin_notice'));
    }

    /**
     * Call the OpenAI API to check for spelling.
     *
     * @since    1.1.0
     *
     * @param    string $content    The content to check.
     * @return   array  $response   The response from OpenAI API.
     */
    private function call_openai_api($content) {
        // Mock OpenAI API request and response
        return [];
    }

    /**
     * Display admin notice if post content was changed.
     *
     * @since    1.1.0
     */
    public function display_admin_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Post content was modified for spelling errors.', 'demo-plugin'); ?></p>
        </div>
        <?php
    }
}
