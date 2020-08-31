<?php
/**
 * Boutique engine room
 *
 * @package boutique
 */

/**
 * Set the theme version number as a global variable
 */
$theme				= wp_get_theme( 'boutique' );
$boutique_version	= $theme['Version'];

$theme				= wp_get_theme( 'storefront' );
$storefront_version	= $theme['Version'];

/**
 * Load the individual classes required by this theme
 */
require_once( 'inc/class-boutique.php' );
require_once( 'inc/class-boutique-customizer.php' );
require_once( 'inc/class-boutique-template.php' );
require_once( 'inc/class-boutique-integrations.php' );
require_once( 'inc/customizer.php' );

/**
 * Do not add custom code / snippets here.
 * While Child Themes are generally recommended for customisations, in this case it is not
 * wise. Modifying this file means that your changes will be lost when an automatic update
 * of this theme is performed. Instead, add your customisations to a plugin such as
 * https://github.com/woothemes/theme-customisations
 */

if ( ! function_exists( 'storefront_site_title_or_logo' ) ) {
    /**
     * Display the site title or logo
     *
     * @since 2.1.0
     * @param bool $echo Echo the string or return it.
     * @return string
     */
    function storefront_site_title_or_logo( $echo = true ) {
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            $logo = get_custom_logo();
            $html = $logo;
        }

        $tag = is_home() ? 'h1' : 'div';
        $html .= '<div class="site-branding-container"><div class="site-branding-align-bottom">';
        $html .= '<' . esc_attr( $tag ) . ' class="beta site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></' . esc_attr( $tag ) . '>';

        if ( '' !== get_bloginfo( 'description' ) ) {
            $html .= '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
        }

        if (!empty(get_theme_mod('contact-phone'))) {
            $html .= '<p class="site-contact">' . get_theme_mod( 'contact-phone' ) . '</p>';
        }

        if (!empty(get_theme_mod('contact-email'))) {
            $html .= '<p class="site-contact">' . get_theme_mod( 'contact-email' ) . '</p>';
        }

        $html .= "</div></div>";

        if ( ! $echo ) {
            return $html;
        }

        echo $html; // WPCS: XSS ok.
    }
}

if ( ! function_exists( 'storefront_header_container' ) ) {
    /**
     * The header container
     */
    function storefront_header_container() {
        echo '<div class="storefront-header-container">';
    }
}

if ( ! function_exists( 'storefront_primary_navigation_wrapper' ) ) {
    /**
     * The primary navigation wrapper
     */
    function storefront_primary_navigation_wrapper() {
        echo '<div class="storefront-primary-navigation">';
    }
}

if ( ! function_exists( 'storefront_primary_navigation_wrapper_close' ) ) {
    /**
     * The primary navigation wrapper close
     */
    function storefront_primary_navigation_wrapper_close() {
        echo '</div>';
    }
}

function remove_storefront_header_actions() {
    remove_all_actions("storefront_header");
    add_action( 'storefront_header', 'storefront_header_container', 0 );
    add_action( 'storefront_header', 'storefront_skip_links', 5 );
    add_action( 'storefront_header', 'storefront_site_branding', 20 );
    add_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
    add_action( 'storefront_header', 'storefront_header_container_close', 41 );
    add_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
    add_action( 'storefront_header', 'storefront_primary_navigation', 50 );
    add_action( 'storefront_header', 'storefront_product_search', 51);
    add_action( 'storefront_header', 'storefront_header_cart', 60);
    add_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );
}

add_action("storefront_before_header", "remove_storefront_header_actions", 100);

new RedmosquitoCustomizer();