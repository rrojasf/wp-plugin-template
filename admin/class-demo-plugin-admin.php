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
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->add_hooks();
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
     * Add hooks for spell checking and other functionalities.
     *
     * @since    1.0.0
     */
    private function add_hooks() {
        add_action('pre_save_post', array($this, 'check_spelling'));
    }
    
    /**
     * Check spelling for posts using the OpenAI API.
     *
     * @since    1.1.0
     *
     * @param    int $post_id
     */
    public function check_spelling($post_id) {
        if (get_post_status($post_id) !== 'publish') {
            return;
        }
        
        $content = get_post_field('post_content', $post_id);
        
        // Placeholder for API call
        $corrected_content = $this->spell_check_content($content);
        
        if ($corrected_content !== $content) {
            wp_update_post(array(
                'ID'           => $post_id,
                'post_content' => $corrected_content,
            ));
            add_action('admin_notices', array($this, 'display_admin_notice'));
        }
    }
    
    /**
     * Spell check content with OpenAI.
     *
     * @since    1.1.0
     *
     * @param    string $content
     * @return   string
     */
    private function spell_check_content($content) {
        // Implement OpenAI API interaction here
        // For now, return content as-is
        return $content;
    }
    
    /**
     * Display admin notice if post content was corrected.
     *
     * @since    1.1.0
     */
    public function display_admin_notice() {
        echo '<div class="notice notice-success is-dismissible"><p>Post content was spell-checked and updated.</p></div>';
    }
}