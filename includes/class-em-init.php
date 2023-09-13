<?php
/**
 * Initializer.
 *
 * @package employee-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EM_Init' ) ) {

    class EM_Init {

        public function __construct() {
            add_action('init', array($this, 'create_employee_cpt'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_em_script'));

        }

        // Register Custom Post Type
        public function create_employee_cpt() {
            $labels = array(
                'name' => 'Employees',
                'singular_name' => 'Employee',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Employee',
                'edit_item' => 'Edit Employee',
                'view_item' => 'View Employee',
                'search_items' => 'Search Employees',
                'not_found' => 'No employees found',
                'not_found_in_trash' => 'No employees found in Trash',
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'employees'),
                'supports' => array( 'title', 'editor', 'custom-fields'),
                'show_in_rest' => true, 
            );

            register_post_type('employee', $args);
        }


        public function enqueue_em_script(){
            wp_enqueue_script(
                'em-script',
                EM_PLUGIN_DIR_URL . '/assets/js/script.js', 
                array('jquery'), // Dependencies
                '', 
                true // Load script in the footer
            );
        }

      
    }
}

new EM_Init();
