<?php
class sbr_optionsPageClass
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'SBR Settings', 
            'manage_options', 
            'sbr-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'sbr_settings' );
        ?>
        <div class="wrap">
            <h1>Simple Browser Redirect Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'sbr_option_group' );
                do_settings_sections( 'sbr-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'sbr_option_group', // Option group
            'sbr_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'SBR Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'sbr-setting-admin' // Page
        );  

        add_settings_field(
            'redirect_url', // ID
            'Redirect URL', // Title 
            array( $this, 'redirect_url_callback' ), // Callback
            'sbr-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        /*add_settings_field(
            'title', 
            'Title', 
            array( $this, 'title_callback' ), 
            'sbr-setting-admin', 
            'setting_section_id'
        ); */     
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['redirect_url'] ) )
            $new_input['redirect_url'] = esc_url_raw( $input['redirect_url'] );

        //if( isset( $input['title'] ) )
        //    $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function redirect_url_callback()
    {
        printf(
            '<input type="text" id="redirect_url" name="sbr_settings[redirect_url]" value="%s" />',
            isset( $this->options['redirect_url'] ) ? esc_attr( $this->options['redirect_url']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="sbr_settings[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
    */
}

if( is_admin() )
    $sbr_settings_page = new sbr_optionsPageClass();