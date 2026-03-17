<?php

namespace JP\WhyIsItHere;

class Dashboard {

    private $dashboard_page_slug;


    public function __construct()
    {

        $this->dashboard_page_slug = "wiith";

        add_action( 'admin_enqueue_scripts', array( $this, 'adminStyles' ) );
        add_action( 'admin_menu', array( $this, 'registerMenu' ) );
    }

    public function adminStyles()
    {
        wp_enqueue_style( 'wiih_style', JP_WIIH_URL . '/assets/admin.css', false, JP_WIIH_V );
    }

    public function registerMenu()
    {
        add_submenu_page(
            'tools.php',                        // The parent menu slug (Tools)
            'Why is it here?',                  // Page title (shows in the browser tab)
            'Why is it here?',                        // Menu title (shows in the sidebar)
            'manage_options',                   // Capability required
            $this->dashboard_page_slug,         // Menu slug
            array( $this, 'renderAbout' )   // Callback function to render the page
        );
    }

    public function getPluginStats()
    {

        if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();
        
        $stats = array(
            'total_plugins'          => count( $installed_plugins ),
            'total_active_plugins'   => 0,
            'total_inactive_plugins' => 0,
            'active_with_notes'      => 0,
            'active_without_notes'   => 0,
            'inactive_with_notes'    => 0,
            'inactive_without_notes' => 0,
            'orphaned_notes'         => 0, 
        );

        global $wpdb;
        $db_notes = $wpdb->get_results( 
            "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'wiih_note_%' AND option_value != ''", 
            OBJECT_K
        );

        foreach ( $installed_plugins as $plugin_file => $plugin_data ) {
            
            $is_active = is_plugin_active( $plugin_file );
            $has_note  = isset( $db_notes[ 'wiih_note_' . $plugin_file ] );

            if ( $is_active && $has_note ) {
                $stats['active_with_notes']++;
                $stats['total_active_plugins']++;
            } elseif ( $is_active && ! $has_note ) {
                $stats['active_without_notes']++;
                $stats['total_active_plugins']++;
            } elseif ( ! $is_active && $has_note ) {
                $stats['inactive_with_notes']++;
                $stats['total_inactive_plugins']++;
            } else {
                $stats['inactive_without_notes']++;
                $stats['total_inactive_plugins']++;
            }
            
            if ( $has_note ) {
                unset( $db_notes[ 'wiih_note_' . $plugin_file ] );
            }
        }
        
        $stats['orphaned_notes'] = count( $db_notes );
        $stats['total_notes']    = $stats['active_with_notes'] + $stats['inactive_with_notes'] + $stats['orphaned_notes'];

        return $stats;
    }

    public function renderAbout()
    {
        $stats = $this->getPluginStats();
        require JP_WIIH_DIR . '/templates/about.php';
    }
}

new Dashboard();