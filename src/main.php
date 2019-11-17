<?php


if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( ! class_exists( 'Change_Password_Main' ) ) {


    class Change_Password_Main{

        const VERSION = '1.0.0';

        /**
         * Static Singleton Holder
         * @var self
         */
        protected static $instance;

        /**
         * Get (and instantiate, if necessary) the instance of the class
         *
         * @return self
         */
        public static function instance() {
            if ( ! self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        function __construct() {
            // add_action( 'admin_enqueue_scripts', array($this, 'load_styles') );
            add_action( 'admin_menu', array($this, 'create_menu') );
            //add_filter( 'init', array( $this, 'my_custom_sizes') );
            add_action( 'wp_dashboard_setup', array($this, 'add_dashboard_widgets') );


            if ( !get_option("makerspace_ldap_server") ) {
                update_option("makerspace_ldap_server", "ldap.example.com", true);
                update_option("makerspace_ldap_port", 389, true);
                update_option("makerspace_ldap_admin", "cn=admin,dc=example,dc=com", true);
                update_option("makerspace_ldap_admin_pass", "verysecure", true);
                update_option("makerspace_ldap_user_ou", "ou=users,dc=example,dc=com", true);
            }

        }


        public static function activate() {

        }

        public static function deactivate( $network_deactivating ) {

        }

        function add_dashboard_widgets() {

            $user = wp_get_current_user();
            $should_change_password = !get_user_meta( $user->ID, 'ms-should-change-password', true );

            if ($should_change_password == true) {
                wp_add_dashboard_widget(
                    'ms-user-change-password',         // Widget slug.
                    'Passwort ändern',         // Title.
                    array( $this, 'render_dashboard_widget_change_password') // Display function.
                );
            }
        }

        public function create_menu () {
            add_submenu_page(
                'users.php',
                'Passwort ändern',
                'Passwort ändern',
                'read',
                'makerspace_change_user_password',
                array( $this, 'render_makerspace_change_password')
            );

            add_options_page(
                'LDAP Einstellungen',
                'LDAP Einstellungen',
                'activate_plugins',
                'makerspace_ldap_settings',
                array( $this, 'render_makerspace_ldap_settings')
            );
        }

        public function render_dashboard_widget_change_password () {
            include 'partials/change-password-dashboard-widget.php';
        }

        public function render_makerspace_change_password () {
            include 'partials/change-password.php';
        }

        public function render_makerspace_ldap_settings () {
            include 'partials/ldap-settings.php';
        }
    }
}