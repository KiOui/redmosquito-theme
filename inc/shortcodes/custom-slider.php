<?php
/**
 * @author    Lars van Rhijn
 * @license   https://opensource.org/licenses/MIT MIT License
 */

defined( 'ABSPATH' ) or exit;

/**
 * Measurement calculator.css shortcode.
 */
class CustomSlider {

    /**
     * Includes scripts utilised by the search bar
     */
    private function include_scripts() {
        wp_enqueue_script("boutique-custom-slider-scripts", "https://unpkg.com/swiper/swiper-bundle.min.js");
        wp_enqueue_script("boutique-custom-slider-scripts-start", get_theme_file_uri('/assets/js/slider.js'), array("boutique-custom-slider-scripts"));
    }

    private function include_styles() {
        wp_enqueue_style("boutique-custom-slider-styles", "https://unpkg.com/swiper/swiper-bundle.min.css");
        wp_enqueue_style("boutique-custom-slider-custom-style", get_theme_file_uri('/assets/css/slider.css'));
    }

    /**
     * Render the shortcode.
     */
	public function render_shortcode( $atts ) {
	    $this->include_scripts();
	    $this->include_styles();
	    ob_start();
	    ?>
            <!-- Slider main container -->
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/inner-shadow--top.png">
            <div class="swiper-container">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <?php
                        for ($i = 0; $i < 5; $i++) {
                            if (!empty(get_theme_mod('slider-slide-title-' . strval($i))) &&
                                !empty(get_theme_mod('slider-slide-text-' . strval($i))) &&
                                !empty(get_theme_mod('slider-slide-image-' . strval($i)))) {
                                ?>
                                    <div class="swiper-slide">
                                        <div class="text-content">
                                            <h3><?php echo get_theme_mod('slider-slide-title-' . strval($i)); ?></h3>
                                            <p><?php echo get_theme_mod('slider-slide-text-' . strval($i)); ?></p>
                                        </div>
                                        <img src="<?php echo get_theme_mod('slider-slide-image-' . strval($i)); ?>"/>
                                    </div>
                                <?php
                            }
                        }
                    ?>
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/inner-shadow--bottom.png">
        <?php
        $output_string = ob_get_contents();
        ob_end_clean();
        return $output_string;
    }
}
