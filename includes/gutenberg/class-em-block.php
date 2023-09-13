<?php
/**
 * PLugin Guttenberg configuration file for set blocks
 *
 * @package employee-manager
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'EM_Block' ) ) {

	/**
	 * Guttenberg Class.
	 */
	class EM_Block {

		/**
		 * Construct.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_employee_table_block' ) );
            add_action('enqueue_block_editor_assets', array($this, 'enqueue_employee_table_block_editor_assets'));
		}

		/**
		 * Register BLock function.
		 */
		public function register_employee_table_block() {
            register_block_type('employee-manager/employee-list', array(
                'render_callback' => array($this, 'get_employees_table')
            ));
		}

		/**
		 * Shortcode for Box Button.
		 *
		 * @param array $atts it gives the attribues of shortcode.
		 */
		public function get_employees_table( $atts ) {
			ob_start();
                include EM_PLUGIN_DIR.'/blocks/employee-table.php';
                $output = ob_get_contents();
                ob_end_clean();
			return $output;
		}
        
        /**
		 * Enqueue Block Assets.
		 *
		 */
        public function enqueue_employee_table_block_editor_assets() {
            wp_enqueue_script(
                'employee-table-block',
                EM_PLUGIN_DIR_URL.'dist/employee-block.build.js',
                array('wp-blocks', 'wp-element', 'wp-components', 'wp-data')
            );
        }

	}
}
new EM_Block();
