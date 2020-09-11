<?php
/**
 * @author    Lars van Rhijn
 * @license   https://opensource.org/licenses/MIT MIT License
 */

defined( 'ABSPATH' ) or exit;

/**
 * Shortcodes handler.
 */
class Shortcodes {


    /**
     * Initializes shortcodes.
     */
    public function __construct() {
        add_shortcode( 'boutique-custom-slider', array( $this, 'boutique_custom_slider' ) );
    }


    public function boutique_custom_slider( $atts ) {
        require_once("shortcodes/custom-slider.php");
        $custom_slider = new CustomSlider();
        return $custom_slider->render_shortcode( $atts );
    }
}
