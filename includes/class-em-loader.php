<?php
/**
 * Main Loader.
 *
 * @package employee-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EM_Loader' ) ) {

	/**
	 * Class WCBAB_Loader.
	 */
	class EM_Loader {
		/**
		 *  Constructor.
		 */
		public function __construct() {
			$this->includes();
		}

		private function includes() {
            include_once 'class-em-init.php';
            include_once 'class-em-fields.php';
			include_once 'gutenberg/class-em-block.php';
		}
	}
}

new EM_Loader();
