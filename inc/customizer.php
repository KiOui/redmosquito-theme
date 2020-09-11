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
        $this->slider_customizer($wp_customize);
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

    private function slider_customizer($wp_customize) {
        $wp_customize->add_section('slider-section', array(
            'title' => 'Homepage slider',
            'priority' => 9,
            'description' => __('Settings for the homepage slider.', 'boutique'),
        ));

        for ($i = 0; $i < 5; $i++) {
            $wp_customize->add_setting('slider-slide-title-' . strval($i), array(
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => array($this, 'sanitize_custom_text')
            ));

            $wp_customize->add_control('slider-slide-title-' . strval($i),
                array(
                    'label' => __('Slide ' . strval($i) . ' title'),
                    'description' => esc_html__('Enter the title for slide ' . strval($i)),
                    'section' => 'slider-section',
                    'settings' => 'slider-slide-title-' . strval($i),
                    'type' => 'text', // Can be either text, email, url, number, hidden, or date
                )
            );

            $wp_customize->add_setting('slider-slide-text-' . strval($i), array(
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options'
            ));

            $wp_customize->add_control('slider-slide-text-' . strval($i),
                array(
                    'label' => __('Slide ' . strval($i) . ' text'),
                    'description' => esc_html__('Enter the text for slide ' . strval($i)),
                    'section' => 'slider-section',
                    'settings' => 'slider-slide-text-' . strval($i),
                    'type' => 'textarea', // Can be either text, email, url, number, hidden, or date
                )
            );

            $wp_customize->add_setting('slider-slide-image-' . strval($i), array(
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => array($this, 'sanitize_custom_url')
            ));

            $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'slider-slide-image-' . strval($i), array(
                'label' => 'Slider ' . strval($i) . " image",
                'section' => 'slider-section',
                'settings' => 'slider-slide-image-' . strval($i)
            )));
        }
    }

    /* Footer Section */
    private function contact_section($wp_customize)
    {
        // New panel for "Layout".
        $wp_customize->add_section('contact-section', array(
            'title' => 'Header and Footer sections',
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

        $wp_customize->add_setting('footer-image', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_custom_url')
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer-image-control', array(
            'label' => 'Image',
            'section' => 'contact-section',
            'settings' => 'footer-image'
        )));

        $wp_customize->add_setting('footer-text', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_custom_text')
        ));

        $wp_customize->add_control( 'footer-text',
            array(
                'label' => __( 'Footer text' ),
                'description' => esc_html__( 'Enter footer text to display in the footer' ),
                'section' => 'contact-section',
                'settings' => 'footer-text',
                'type' => 'text', // Can be either text, email, url, number, hidden, or date
            )
        );
    }
}