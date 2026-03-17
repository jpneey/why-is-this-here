<?php

namespace JP\WhyIsItHere;

class Notes {

    public function __construct()
    {
        add_filter( 'manage_plugins_columns', array( $this, 'add_notes_column' ) );

        add_action( 'manage_plugins_custom_column', array( $this, 'render_notes_column' ), 10, 3 );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

        add_action( 'wp_ajax_save_wiih_note', array( $this, 'save_note_ajax' ) );
    }

    public function add_notes_column( $columns ) {
        $columns['wiih_note'] = 'Why Is This Here?';
        return $columns;
    }

    public function render_notes_column( $column_name, $plugin_file, $plugin_data ) {
        if ( 'wiih_note' === $column_name ) {
            
            $saved_note = get_option( 'wiih_note_' . $plugin_file, '' );

            echo '<textarea 
                    class="wiih-note-field" 
                    data-plugin-id="' . esc_attr( $plugin_file ) . '" 
                    rows="2" 
                    style="width: 100%; max-width: 300px; resize: vertical;" 
                    placeholder="Why is this plugin here?">' . esc_textarea( $saved_note ) . '</textarea>';
            
            echo '<span class="wiih-save-status-' . esc_attr( md5($plugin_file) ) . '" style="display:block; font-size: 11px; color: #00a32a; min-height: 16px;"></span>';
        }
    }

    public function enqueue_admin_scripts( $hook ) {

        if ( 'plugins.php' !== $hook ) {
            return;
        }
        
        wp_enqueue_script( 'wiih-admin-js', JP_WIIH_URL . 'assets/admin.js', array( 'jquery' ), JP_WIIH_V, true );
        
        wp_localize_script( 'wiih-admin-js', 'wiihData', array(
            'ajaxurl'  => admin_url( 'admin-ajax.php' ),
            'security' => wp_create_nonce( 'wiih_save_note_nonce' )
        ));
    }

    public function save_note_ajax() {

        check_ajax_referer( 'wiih_save_note_nonce', 'security' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Permission denied.' );
        }

        $plugin_id = isset( $_POST['plugin_id'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_id'] ) ) : '';
        $note      = isset( $_POST['note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['note'] ) ) : '';

        if ( empty( $plugin_id ) ) {
            wp_send_json_error( 'Missing plugin ID.' );
        }

        update_option( 'wiih_note_' . $plugin_id, $note );

        wp_send_json_success();
    }

}

new Notes();