<?php

use WP_MockToolsTestCase;

/**
 * Class Test_Demo_Plugin
 *
 * Unit tests for Demo Plugin.
 *
 * @group                       demo-plugin
 * @coversDefaultClass          Demo_Plugin_Admin
 */
class Test_Demo_Plugin extends TestCase {

    /**
     * Setup test environment.
     */
    public function setUp(): void {
        WP_Mock::setUp();
    }

    /**
     * Teardown test environment.
     */
    public function tearDown(): void {
        WP_Mock::tearDown();
    }

    /**
     * Test checking spelling in post content.
     *
     * @covers ::check_spelling
     */
    public function test_check_spelling() {
        $plugin_admin = new Demo_Plugin_Admin('demo_plugin', '1.0.0');

        $post_id = 123;
        
        WP_Mock::userFunction('get_post_status', [
            'args'   => $post_id,
            'return' => 'publish',
        ]);

        WP_Mock::userFunction('get_post_field', [
            'times'  => 1,
            'return' => 'This is a smaple content.',
            'args'   => ['post_content', $post_id],
        ]);

        WP_Mock::userFunction('wp_update_post', [
            'times' => 1,
            'args'  => [
                [
                    'ID'           => $post_id,
                    'post_content' => 'This is a sample content.'
                ]
            ]
        ]);

        $plugin_admin->check_spelling($post_id);
    }
}