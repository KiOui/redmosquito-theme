<?php

class RedmosquitoCustomizer
{

    public function __construct()
    {
        add_action('customize_register', array($this, 'register_customize_sections'));
    }

    public function register_customize_sections($wp_customize)
    {
        /*
        * Add settings to sections.
        */
        $this->contact_section($wp_customize);
    }

    /* Sanitize Inputs */
    public function sanitize_custom_text($input)
    {
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

    public function sanitize_custom_url($input)
    {
        return filter_var($input, FILTER_SANITIZE_URL);
    }

    public function sanitize_custom_email($input)
    {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    public function sanitize_hex_color($color)
    {
        if ('' === $color) {
            return '';
        }

        // 3 or 6 hex digits, or the empty string.
        if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
            return $color;
        }
    }

    /* Footer Section */
    private function contact_section($wp_customize)
    {
        // New panel for "Layout".
        $wp_customize->add_section('contact-section', array(
            'title' => 'Contact',
            'priority' => 10,
            'description' => __('Settings for the contact section.', 'boutique'),
        ));

        $wp_customize->add_setting('contact-phone', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_custom_text')
        ));

        $wp_customize->add_control( 'contact-phone',
            array(
                'label' => __( 'Phone number' ),
                'description' => esc_html__( 'Enter a phone number to display in the header' ),
                'section' => 'contact-section',
                'settings' => 'contact-phone',
                'type' => 'text', // Can be either text, email, url, number, hidden, or date
            )
        );

        $wp_customize->add_setting('contact-email', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_custom_email')
        ));

        $wp_customize->add_control( 'contact-email',
            array(
                'label' => __( 'Email address' ),
                'description' => esc_html__( 'Enter an email address to display in the header' ),
                'section' => 'contact-section',
                'settings' => 'contact-email',
                'type' => 'email', // Can be either text, email, url, number, hidden, or date
            )
        );
    }
}