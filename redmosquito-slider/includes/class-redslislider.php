<?php
/**
 * Redmosquito Slider
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RedSliSlider' ) ) {
	/**
	 * Redmosquito Slider
	 *
	 * @class RedSliSlider
	 */
	class RedSliSlider {

		/**
		 * Identifier of slider.
		 *
		 * @var string
		 */
		private string $id;

		/**
		 * Theme color for slider.
		 *
		 * @var string|null
		 */
		private ?string $theme_color = null;

		/**
		 * Secondary theme color for slider.
		 *
		 * @var string|null
		 */
		private ?string $secondary_theme_color = null;

		/**
		 * Whether arrows are enabled on slider.
		 *
		 * @var bool
		 */
		private bool $arrow_enabled = true;

		/**
		 * Wheter pagination is enabled on slider.
		 *
		 * @var bool
		 */
		private bool $pagination_enabled = true;

		/**
		 * Categories to include in slider.
		 *
		 * @var array
		 */
		private array $category = array();

		/**
		 * WidgetsCollectionTestimonialsShortcode constructor.
		 *
		 * @param array $atts {
		 *      Optional. Array of Widget parameters.
		 *
		 *      @type string    $id                     CSS ID of the widget, if empty a random ID will be assigned.
		 *      @type string    $theme_color            Primary theme color, if empty the color will not be included in
		 *                                              CSS.
		 *      @type string    $secondary_theme_color  Secondary theme color, if empty the color will not be included
		 *                                              in CSS.
		 *      @type string    $arrow_enabled          Whether or not to enable the arrows on the slider, defaults to
		 *                                              true.
		 *      @type string    $pagination_enabled     Whether or not to enable the pagination on the slider, defaults
		 *                                              to true.
		 *      @type string    $slides_per_view        How many slides per view to show. Defaults to 1.
		 *      @type string    $enable_star_rating     Whether or not to enable the star rating on the slider, defaults
		 *                                              to true.
		 *      @type string    $category               List of comma-separated categories of testimonials to include,
		 *                                              defaults to all categories (array(0)).
		 * }
		 */
		public function __construct( array $atts = array() ) {
			if ( key_exists( 'id', $atts ) && 'string' === gettype( $atts['id'] ) ) {
				$this->id = $atts['id'];
			} else {
				$this->id = uniqid();
			}
			if ( key_exists( 'theme_color', $atts ) && gettype( $atts['theme_color'] ) == 'string' ) {
				$this->theme_color = sanitize_hex_color( $atts['theme_color'] );
			}
			if ( key_exists( 'secondary_theme_color', $atts ) && gettype( $atts['secondary_theme_color'] ) == 'string' ) {
				$this->secondary_theme_color = sanitize_hex_color( $atts['secondary_theme_color'] );
			}
			if ( key_exists( 'arrow_enabled', $atts ) && gettype( $atts['arrow_enabled'] ) == 'string' ) {
				$arrow_enabled = filter_var( $atts['arrow_enabled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
				$this->arrow_enabled = $arrow_enabled ?? false;
			}
			if ( key_exists( 'pagination_enabled', $atts ) && gettype( $atts['pagination_enabled'] ) == 'string' ) {
				$pagination_enabled = filter_var( $atts['pagination_enabled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
				$this->pagination_enabled = $pagination_enabled ?? false;
			}
			if ( key_exists( 'category', $atts ) & 'string' === gettype( $atts['category'] ) ) {
				include_once REDSLI_ABSPATH . 'includes/redsli-functions.php';
				$this->category = redsli_string_to_array_ints( $atts['category'] );
			}
			$this->include_styles_and_scripts();
		}

		/**
		 * Get Testimonials corresponding to this slider.
		 *
		 * @return WP_POST[]
		 */
		public function get_posts(): array {
			$atts = array(
				'post_type' => 'redsli_slider',
				'post_status' => 'publish',
				'numberposts' => '-1',
			);
			if ( count( $this->category ) > 0 ) {
				$atts['tax_query'] = array(
					array(
						'taxonomy' => 'redsli_slider_category',
						'field' => 'term_id',
						'terms' => $this->category,
					),
				);
			}
			return get_posts( $atts );
		}

		/**
		 * Get the ID of this slider.
		 *
		 * @return string
		 */
		public function get_id(): string {
			return $this->id;
		}

		/**
		 * Include all styles and scripts required for this slider to work.
		 */
		public function include_styles_and_scripts() {
			wp_enqueue_style( 'swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '1.0' );
			wp_enqueue_script( 'swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '1.0' );
			wp_enqueue_script( 'swiper-activation', REDSLI_PLUGIN_URI . 'assets/js/swiper-activation.js', array( 'swiper-js' ), '1.0', true );
			wp_enqueue_style( 'swiper-overrides', REDSLI_PLUGIN_URI . 'assets/css/swiper-overrides.css', array(), '1.0' );
			if ( $this->theme_color ) {
				wp_add_inline_style(
					'swiper-overrides',
					'
                    #swiper-container-' . esc_attr( $this->get_id() ) . ' .swiper-pagination .swiper-pagination-bullet-active {
                        ' . ( $this->theme_color ? 'background: ' . esc_attr( $this->theme_color ) : '' ) . '
                    }
                    
                    #swiper-container-' . esc_attr( $this->get_id() ) . ' .swiper-slide .text-content {
                        ' . ( $this->theme_color ? 'color: ' . esc_attr( $this->theme_color ) : '' ) . '
                    }
                    
                    #swiper-container-' . esc_attr( $this->get_id() ) . ' .swiper-slide .text-content h3 {
                        ' . ( $this->theme_color ? 'color: ' . esc_attr( $this->theme_color ) : '' ) . '
                    }
                    
                    #swiper-container-' . esc_attr( $this->get_id() ) . ' .dashicons-star-filled {
                        ' . ( $this->secondary_theme_color ? 'color: ' . esc_attr( $this->secondary_theme_color ) : '' ) . '
                    }
                '
				);
			}
		}

		/**
		 * Localize the slider activation javascript file to activate all activated sliders.
		 *
		 * @param RedSliSlider[] $sliders sliders to localize the activation script for.
		 */
		public static function localize_swiper_activation( array $sliders ) {
			$configs = array();
			foreach ( $sliders as $slider ) {
				$configs[] = array(
					'id' => 'swiper-container-' . $slider->get_id(),
				);
			}
			wp_localize_script( 'swiper-activation', 'swiper_configs', $configs );
		}

		/**
		 * Get the contents of the shortcode.
		 *
		 * @return false|string
		 */
		public function do_shortcode(): bool|string {
			ob_start();
			$posts = $this->get_posts(); ?>
			<img class="custom-slider-img" src="<?php echo esc_attr( REDSLI_PLUGIN_URI . '/assets/img/inner-shadow--top.png' ); ?>">
			<div id="swiper-container-<?php echo esc_attr( $this->id ); ?>" class="swiper-container redsli-swiper-container">
					<div class="swiper-wrapper">
						<?php
						foreach ( $posts as $post ) {
							?>
							<div class="swiper-slide">
								<div class="text-content">
									<h3><?php echo esc_html( $post->post_title ); ?></h3>
									<p><?php echo wp_kses( get_post_meta( $post->ID, 'redsli_slider_content', true ), array( 'br' => array() ) ); ?></p>
								</div>
								<?php echo get_the_post_thumbnail( $post->ID, 'full' ); ?>
							</div>
							<?php
						}
						?>
					</div>
					<?php if ( $this->pagination_enabled ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $this->arrow_enabled ) : ?>
						<div class="swiper-button-prev" style="
						<?php
						if ( $this->theme_color ) :
							echo 'color: ' . esc_attr( $this->theme_color );
			endif
						?>
			"></div>
						<div class="swiper-button-next" style="
						<?php
						if ( $this->theme_color ) :
							echo 'color: ' . esc_attr( $this->theme_color );
			endif
						?>
			"></div>
					<?php endif; ?>
				</div>
				<img class="custom-slider-img" src="<?php echo esc_attr( REDSLI_PLUGIN_URI . '/assets/img/inner-shadow--bottom.png' ); ?>">
			<?php
			$ob_content = ob_get_contents();
			ob_end_clean();
			return $ob_content;
		}
	}
}
