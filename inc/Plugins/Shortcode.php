<?php
/**
 * Shortcode.
 *
 */

namespace RT\Clplace\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Controllers\BusinessHoursController;

/**
 * ThemeJetpack Class
 */
class Shortcode {

	protected static $instance = null;

	public static $shortcode_list = [
		'header'    	=> [
			'tag'      	=> 'clplace_listing_header',
			'hint'      => 'clplace_listing_header style="1"',
			'callback' 	=> 'render_listing_header',
		],
		'sidebar'    	=> [
			'tag'      	=> 'clplace_listing_sidebar',
			'callback' 	=> 'render_listing_sidebar',
		],
		'description'   => [
			'tag'      	=> 'clplace_listing_description',
			'callback' 	=> 'render_listing_description',
		],
        'custom_fields' => [
			'tag'       => 'clplace_listing_custom_fields',
			'callback'  => 'render_listing_custom_fields',
		],
		'faq' => [
			'tag'       => 'clplace_listing_faq',
			'callback'  => 'render_listing_faq',
		],
        'order' => [
			'tag'       => 'clplace_listing_order',
			'callback'  => 'render_listing_order',
		],
		'video'    		=> [
			'tag'       => 'clplace_listing_video',
			'callback'  => 'render_listing_video',
		],
        'map'    		=> [
			'tag'      	=> 'clplace_listing_map',
			'callback' 	=> 'render_listing_map',
		],
        'review'    	=> [
			'tag'      	=> 'clplace_listing_review',
			'callback' 	=> 'render_listing_review',
		],
	];

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_filter(
			'rtcl/fb/single_layout/fields',
			function ( $fields ) {
				$shortcode_hints = '';
				foreach ( self::$shortcode_list as $label => $shortcode ) {
					$display = isset( $shortcode['hint'] ) ? $shortcode['hint'] : $shortcode['tag'];
				    $shortcode_hints .= '<br><b>' . ucfirst( $label ) . ":</b> [{$display}]";
				}

				$fields['shortcode']['editor']['hints']['value'] = 'Available Shortcodes: ' . $shortcode_hints;

				return $fields;
			}
		);

		foreach ( self::$shortcode_list as $shortcode ) {
			add_shortcode( $shortcode['tag'], [ $this, $shortcode['callback'] ] );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * @return false|string
	 */
	public function render_listing_header( $atts ) {
		$atts = shortcode_atts( [ 'style' => '1' ], $atts, 'clplace_listing_header' );

		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			Listing_Functions::get_custom_listing_template( 'single/listing-single-header-'.$atts['style'] );

			return ob_get_clean();
		}

		return '';
	}

    /**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_sidebar() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();
			?>

			<div class="listing-sidebar">
                <?php $listing->the_user_info(); ?>
				<?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>

				<?php if ( Functions::is_enable_business_hours() && ! empty( BusinessHoursController::get_business_hours( $listing->get_id() ) ) ): ?>
                    <div class="business-hour-box widget">
                        <h3 class="title"><?php esc_html_e( 'Business Hours', 'clplace' ); ?></h3>
                        <div class="single-business-hour">
                            <div class="main-content">
								<?php do_action( 'rtcl_single_listing_business_hours' ); ?>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>

				<?php
				/**
				 * Hook: rtcl_sidebar.
				 *
				 * @hooked rtcl_get_sidebar - 10
				 */
				dynamic_sidebar( 'rtcl-single-sidebar' );
				?>
			</div>

            <?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
    public function render_listing_description() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();
			?>

            <div class="rtcl-main-content-wrapper">
                <h3 class="desc-title"><?php esc_html_e('Service Overview', 'clplace'); ?></h3>
                <!-- Description -->
                <div class="rtcl-listing-description"><?php $listing->the_content(); ?></div>
            </div>

            <?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_custom_fields() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();
        ?>
            <div class="rtcl-single-listing-details cfg-box">
				<?php $listing->custom_fields(); ?>
            </div>
        <?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing faq
	 *
	 * @return false|string
	 */
	public function render_listing_faq() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			Listing_Functions::get_custom_listing_template( 'faq' );

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing order steps
	 *
	 * @return false|string
	 */
	public function render_listing_order() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			Listing_Functions::get_custom_listing_template( 'order' );

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_video() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$video_urls = [];
			if ( ! Functions::is_video_urls_disabled() ) {
				$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
				$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
			}
			?>

            <!-- Map -->
			<?php if ( ! empty( $video_urls ) ){ ?>
                <div class="rtcl-single-listing-details video-box">
                    <div class="rtcl-main-content-wrapper">
						<?php if (!empty(clplace_option('rt_listing_video_title'))){ ?>
                            <h3 class="desc-title">
								<?php echo esc_html( clplace_option('rt_listing_video_title') ); ?>
                            </h3>
						<?php } ?>
                        <div class="video-info ratio-16x9 mt-3">
                            <iframe class="rtcl-lightbox-iframe" src="<?php echo wp_kses_post( Functions::get_sanitized_embed_url( $video_urls[0] ) ) ?>"></iframe>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_map() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );
			?>

            <!-- Map -->
			<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){ ?>
                <div class="rtcl-single-listing-details map-box">
                    <div class="rtcl-main-content-wrapper">
                        <h3 class="desc-title">
							<?php esc_html_e( 'Location', 'clplace' ); ?>
                        </h3>
						<?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
                    </div>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_review() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
        ?>
            <div class="rtcl-single-listing-details review-box">
                <div class="rtcl-main-content-wrapper">
                    <h3 class="desc-title mb-3">
						<?php esc_html_e( 'Reviews of Place', 'clplace' ); ?>
                    </h3>
					<?php do_action( 'rtcl_single_listing_review' ) ?>
                </div>
            </div>
        <?php
			return ob_get_clean();
		}

		return '';
	}

}

Shortcode::instance();
