<?php
/**
 * Employee Custom Fields.
 *
 * @package employee-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EM_Fields' ) ) {

	/**
	 * Class EM_Fields.
	 */
	class EM_Fields {
        /**
         *  string  $prefix  The prefix for storing custom fields in the postmeta table
        */
        private $prefix = '_em_';
        /**
        *  array  $post_types  An array of public custom post types, plus the standard "post" and "page" - add the custom types you want to include here
        */
        private $post_types = array( "employee" );

        function __construct() {
            add_action( 'admin_menu', array( $this, 'create_custom_fields' ) );
            add_action( 'save_post', array( $this, 'save_custom_fields' ), 1, 2 );
            add_action( 'init', array( $this, 'display_fields_in_rest' ) );
        }

        /**
        * Initializing all custom fields
        */
        function custom_fields(){
            $custom_fields = array(
                array(
                    "name"          => "name",
                    "title"         => "Name",
                    "description"   => "",
                    "type"          => "text",
                    "scope"         => $this->post_types,
                    "capability"    => "edit_posts"
                ),
                array(
                    "name"          => "email",
                    "title"         => "Email",
                    "description"   => "",
                    "type"          => "email",
                    "scope"         => $this->post_types,
                    "capability"    => "manage_options"
                ),
                array(
                    "name"          => "date_of_hiring",
                    "title"         => "Date of hiring",
                    "description"   => "",
                    "type"          => "date",
                    "scope"         =>   $this->post_types,
                    "capability"    => "manage_options"														
                ),
                array(
                    "name"          => "age",
                    "title"         => "Age",
                    "description"   => "",
                    "type"          => "number",
                    "scope"         =>   $this->post_types,
                    "capability"    => "manage_options"
                ),
            );

            return $custom_fields;
        }
        /**
        * Create the new Custom Fields meta box
        */
        function create_custom_fields() {
            if ( function_exists( 'add_meta_box' ) ) {
                foreach ( $this->post_types as $post_type ) {
                    add_meta_box( 'my-custom-fields', 'Custom Fields', array( $this, 'display_custom_fields' ), $post_type, 'normal', 'high' );
                }
            }
        }
        /**
        * Display the new Custom Fields meta box
        */
        public function display_custom_fields() {
            global $post;
            ?>
            <div class="form-wrap">
                <?php
                wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
                foreach ( $this->custom_fields() as $custom_field ) {
                    // Check scope
                    $scope = $custom_field[ 'scope' ];
                    $output = false;
                    foreach ( $scope as $scopeItem ) {
                        switch ( $scopeItem ) {
                            default: {
                                if ( $post->post_type == $scopeItem )
                                    $output = true;
                                break;
                            }
                        }
                        if ( $output ) break;
                    }
                    // Check capability
                    if ( !current_user_can( $custom_field['capability'], $post->ID ) )
                        $output = false;
                    // Output if allowed
                    if ( $output ) { ?>
                        <div class="form-field form-required">
                            <?php
                            switch ( $custom_field[ 'type' ] ) {
                                case "text": {
                                    // Plain text field
                                    echo '<label for="' . $this->prefix . $custom_field[ 'name' ] .'"><b>' . $custom_field[ 'title' ] . '</b></label>';
                                    echo '<input type="text" name="' . $this->prefix . $custom_field[ 'name' ] . '" id="' . $this->prefix . $custom_field[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $custom_field[ 'name' ], true ) ) . '" />';
                                    break;
                                }
                                default: {
                                    // Plain text field
                                    echo '<label for="' . $this->prefix . $custom_field[ 'name' ] .'"><b>' . $custom_field[ 'title' ] . '</b></label>';
                                    echo '<input type="'. $custom_field[ 'type' ] .'" name="' . $this->prefix . $custom_field[ 'name' ] . '" id="' . $this->prefix . $custom_field[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $custom_field[ 'name' ], true ) ) . '" style="width:95%" />';
                                    break;
                                }
                            }
                            ?>
                            <?php if ( $custom_field[ 'description' ] ) echo '<p>' . $custom_field[ 'description' ] . '</p>'; ?>
                        </div>
                    <?php
                    }
                } ?>
            </div>
            <?php
        }
        /**
        * Save the new Custom Fields values
        */
        public function save_custom_fields( $post_id, $post ) {
            if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
                return;
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
            if ( ! in_array( $post->post_type, $this->post_types ) )
                return;
            foreach ( $this->custom_fields() as $custom_field ) {
                if ( current_user_can( $custom_field['capability'], $post_id ) ) {
                    if ( isset( $_POST[ $this->prefix . $custom_field['name'] ] ) && trim( $_POST[ $this->prefix . $custom_field['name'] ] ) ) {
                        $value = $_POST[ $this->prefix . $custom_field['name'] ];
                        
                        update_post_meta( $post_id, $this->prefix . $custom_field[ 'name' ], $value );
                    } else {
                        delete_post_meta( $post_id, $this->prefix . $custom_field[ 'name' ] );
                    }
                }
            }
        }

        public function display_fields_in_rest(){
            foreach ( $this->custom_fields() as $custom_field ) {
                $field_name = $this->prefix. $custom_field['name'];

                register_meta('post', $field_name, [
                    'object_subtype' => 'employee',
                    'show_in_rest'   => true,
                    'single'         => true,
                    'auth_callback'  => function($meta_key, $object_id, $user_id) {
                                        // Check if the current user has the necessary capabilities or is in the right role.
                                        if (current_user_can('administrator')) {
                                            return true; // User is allowed to edit the custom field.
                                        } 
                                    }
                ]);
            }
        }
 
    } 
}
 
new EM_Fields();