<?php
/**
 * jetpack.
 *
 * @link https://wordpress.org/plugins/classified-listing/
 */

namespace RT\Clplace\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Models\Listing;
use Rtcl\Resources\Options;
use Rtcl\Helpers\Link;
use Rtcl\Helpers\Functions;
use RT\Clplace\Helpers\Fns;
use RtclPro\Helpers\Fns as FnsPro;
use RT\Clplace\Traits\SingletonTraits;
use Rtcl\Services\FormBuilder\FBField;
use RtclMarketplace\Hooks\ActionHooks;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Controllers\BusinessHoursController as BHS;
use RtclStore\Controllers\Hooks\TemplateHooks as StoreHooks;
use RtclPro\Controllers\Hooks\TemplateHooks as TemplateHooksPro;

class Listing_Functions {
	use SingletonTraits;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'rtcl_action_hook' ] );
		add_action( 'init', [ $this, 'rtcl_filter_hook' ] );
		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_filter( 'rtcl_get_icon_class_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_action('admin_menu', [$this, 'remove_menus'], 99 );
		add_action('init', [$this, 'remove_custom_post_type'] );

        /* = Remove Action */
		remove_action( 'rtcl_single_listing_inner_sidebar', [ ActionHooks::class, 'add_buy_button' ], 5 );
	}

	public function remove_menus(){
		remove_submenu_page( 'edit.php?post_type=rtcl_listing', 'rtcl-listing-type' );
	}

	function remove_custom_post_type() {
		unregister_post_type('rtcl_cfg');
	}

	/**
	 *
	 *  Classified listing plugin support.
	 *
	 * @return void
	 */
	public function theme_support() {
		add_theme_support( 'rtcl' );
	}

	public function rtcl_action_hook() {

		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = esc_attr( $_GET['view'] );
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}

		/* = Listing Archive Hooks
		=====================================================================================================*/
		//Remove Hooks
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );

		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 30 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );

		if ( FnsPro::is_enable_compare() ) {
			remove_action( 'rtcl_listing_meta_buttons', [ TemplateHooksPro::class, 'add_compare_button' ], 30 );
			add_action( 'rtcl_listing_meta_buttons', [__CLASS__, 'listing_compare_button' ], 30 );
		}

		if ( FnsPro::is_enable_quick_view() ) {
			remove_action( 'rtcl_listing_meta_buttons', [ TemplateHooksPro::class, 'add_quick_view_button' ], 20 );
			add_action( 'rtcl_listing_meta_buttons', [__CLASS__, 'listing_quick_view_button' ], 20 );
		}

		//Add Hooks
		add_filter( 'rtcl_bootstrap_dequeue', '__return_false' );

		/* = Listing Single Hooks
		=====================================================================================================*/
		// remove action
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_gallery' ], 30 );
		add_action( 'rt_pets_list_galley', [ __CLASS__, 'listing_details_gallery' ], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_custom_field'
		], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_action'
		], 20 );
		if ( class_exists( 'RtclStore' ) ) {
			remove_action( 'rtcl_single_store_information', [ StoreHooks::class, 'store_social_media' ], 40 );
			add_action( 'rtcl_single_store_information', [ StoreHooks::class, 'store_social_media' ], 60 );
		}
		// Seller Verification
		if ( class_exists('RtclSellerVerification' ) ) {
			remove_action( 'rtcl_listing_seller_information', [ \RtclSellerActionHooks::class, 'listing_sidebar_verified_author' ], 5 );
		}

		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_title' ], 5 );

		add_action( 'rtcl_shortcode_before_listings_loop_start', function () {
			if (is_active_sidebar('rt-listing-map-archive-sidebar')) { ?>
				<div class="listing-map-search">
					<?php dynamic_sidebar( 'rt-listing-map-archive-sidebar' ); ?>
				</div>
			<?php }
		});
	}

	public function rtcl_filter_hook() {
		// Change Grid Column for listing
		add_filter( 'rtcl_listing_the_excerpt', function ( $excerpt ) {
			return wp_trim_words( $excerpt, 25 );
		} );
		// Override Related Listing Item Number
		add_filter( 'rtcl_related_slider_options', function ( $slider_options ) {
			$slider_options = [
				"loop"         => false,
				"autoplay"     => [
					"delay"                => 3000,
					"disableOnInteraction" => false,
					"pauseOnMouseEnter"    => true
				],
				"speed"        => 1000,
				"spaceBetween" => 20,
				"breakpoints"  => [
					0    => [
						"slidesPerView" => 1
					],
					500  => [
						"slidesPerView" => 2
					],
					1200 => [
						"slidesPerView" => 3
					]
//					1400 => [
//						"slidesPerView" => 4
//					]
				]
			];

			return $slider_options;
		} );

		add_filter( 'rtcl_single_listing_email_button_text', function ( $excerpt ) {
			return __("Message to User", "clplace");
		} );

		add_filter( 'rtcl_get_listing_display_options', function( $options ) {
			$options['address'] = esc_html__( 'Address', 'clplace' );
			$options['rating'] = esc_html__( 'Review Rating', 'clplace' );
			$options['status'] = esc_html__( 'Open/Close Status', 'clplace' );
			return $options;
 		});

        add_filter( 'rtcl_get_listing_detail_page_display_options', function( $options ) {
	        $options['rating'] = esc_html__( 'Review Rating', 'clplace' );
	        $options['status'] = esc_html__( 'Open/Close Status', 'clplace' );
	        $options['print']  = esc_html__( 'Print Button', 'clplace' );

	        return $options;
 		});
	}

	public static function get_template_part( $template, $args = [] ) {
		extract( $args );

		$template = '/' . $template . '.php';

		if ( file_exists( get_stylesheet_directory() . $template ) ) {
			$file = get_stylesheet_directory() . $template;
		} else {
			$file = get_template_directory() . $template;
		}
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			return false;
		}
	}

	/**
	 * Custom templates path
	 * @return $template
	 */
	public static function get_custom_listing_template( $template, $echo = true, $args = [], $path = 'custom/' ) {
		$template = 'classified-listing/' . $path . $template;
		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';

			return $template;
		}
	}

	/**
	 * @param $icon
	 *
	 * @return void
	 */
	public static function clplace_listing_categories($icon) {
		global $listing;
		if ( $listing->has_category() ){
			$category = $listing->get_categories();
			$category = end( $category );
			$term_id = $category->term_id;
			?>
			<a href="<?php echo esc_url( Link::get_category_page_link( $category ) ); ?>" class="category-list">
				<?php
					if ( $icon != ''  ) {
						echo wp_kses_post( self::listing_cat_icon( $term_id, $icon ) );
					}
				?>
				<?php echo esc_html( $category->name ); ?>
			</a>
		<?php }
	}

	/**
	 * @param $excerpt_length
	 */
	public static function clplace_listing_excerpt( $excerpt_length ) { ?>
		<p><?php echo wp_kses_post( Fns::clplace_excerpt( $excerpt_length ) ); ?></p>
	<?php }

	/**
	 * @param $listing
	 *
	 * @return $rating_count
	 */
	public static function clplace_listing_rating( $listing ) {
		$average_rating = $listing->get_average_rating();
		$rating_count   = $listing->get_rating_count();

		if ( ! empty( $rating_count ) ): ?>
		<div class="listing-ratings">
			<div class="item-icon">
				<?php echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) ); ?>
			</div>
			<div class="item-text"><?php echo wp_kses_post( apply_filters( 'clplace_rating_count_format', sprintf( __( '(<span>%s</span>)', 'clplace' ), esc_html( $rating_count ) ) ) ); ?></div>
		</div>
		<?php endif;
	}

	/**
	 * @param $listing
	 *
	 * @return $rating_count
	 */
	public static function clplace_listing_rating_counting( $listing ) {
		$average_rating = $listing->get_average_rating();
		$average_number = number_format($average_rating, 1);
		$rating_count   = $listing->get_rating_count();

		if ( ! empty( $rating_count ) ): ?>
            <div class="listing-ratings">
                <div class="average-rating">
					<?php echo esc_html( $average_number ); ?>
                </div>
                <div class="item-text"><?php echo wp_kses_post( apply_filters( 'clplace_rating_count_format', sprintf( __( '(<span>%s</span>)', 'clplace' ), esc_html( $rating_count ) ) ) ); ?></div>
            </div>
		<?php endif;
	}

	/**
	 * @param $listing
	 *
	 * @return $rating_count
	 */
	public static function clplace_listing_avarage_rating( $listing ) {
		$average_rating = $listing->get_average_rating();
		$average_rating 	= number_format( $average_rating, 1 );
		$rating_count   = $listing->get_rating_count();
		if ( ! empty( $rating_count ) ): ?>
			<div class="listing-ratings">
				<div class="item-icon">
					<span class="rtcl-icon rtcl-icon-star"></span>
				</div>
				<div class="item-text"><?php echo wp_kses_post( apply_filters( 'clplace_rating_count_format', sprintf( __( '%s <span>(%s)</span>', 'clplace' ), esc_html( $average_rating ), esc_html( $rating_count ) ) ) ); ?></div>
			</div>
		<?php endif;
	}

	/**
	 * @param $listing
	 *
	 * @return array|string[]
	 */
	public static function get_listing_type( $listing ) {
		$listing_types = Functions::get_listing_types();
		$listing_types = empty( $listing_types ) ? [] : $listing_types;

		$type = $listing->get_ad_type();

		if ( $type && ! empty( $listing_types[ $type ] ) ) {
			$result = [
				'label' => $listing_types[ $type ],
				'icon'  => 'fa-tags',
			];
		} else {
			$result = [
				'label' => '',
				'icon'  => 'fa-tags',
			];
		}

		return $result;
	}

	/**
	 * @return listing_details_slider
	 */
	public static function listing_details_gallery() {
		global $listing;
		$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

		$video_urls = [];
		if ( empty( clplace_option('rt_listing_video_visibility') ) ) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
		}
		// Image Gallery
		$images              = $listing->get_images();
		$total_gallery_image = count( $images );
		$number = $total_gallery_image - 5;

		if ($total_gallery_image < 2) {
			$item_count = 'items-one';
		} elseif ($total_gallery_image < 3 ) {
			$item_count = 'items-two';
		} elseif ($total_gallery_image < 4) {
			$item_count = 'items-three';
		} elseif ($total_gallery_image < 5) {
			$item_count = 'items-four';
		} else {
			$item_count = 'items-five';
		}
		if ( $total_gallery_image ) {
			?>
			<div class="page-header-gallery">
				<div class="photo-swip-gallery-wrap <?php echo esc_attr($item_count); ?>">
                    <?php
                    if ( empty( clplace_option('rt_listing_video_visibility') ) ) {
                        if ( ! empty( $video_urls ) ) { ?>
                            <div class="listing-gallery-item">
                                <div class="video-info rtcl-slider-video-item ratio-16x9">
                                    <iframe class="rtcl-lightbox-iframe"
                                            src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ) ?>"
                                            style="height: 404px"
                                            allowFullScreen></iframe>
                                </div>
                            </div>
                        <?php }
                    }
                    $counter = 0;
                    foreach ( $images as $image ) {
                        ++$counter;
                        if ($total_gallery_image < 2) {
                            $img_size = 'full';
                        } elseif ($total_gallery_image < 3) {
                            $img_size = 'clplace-1200-650';
                        } elseif ($total_gallery_image < 4) {
                            $img_size = 'clplace-500-290';
                        } else {
                            $img_size = 'rtcl-thumbnail';
                        }
                        ?>
                        <div class="listing-gallery-item photoswip-item image-size-<?php echo esc_attr($img_size.' item-'.$counter); ?>">
                            <?php
                                $img_url = wp_get_attachment_image_url( $image->ID, 'full' );
                                $getimagesize = getimagesize($img_url);
                                $width = $getimagesize[0];
                                $height = $getimagesize[1];
                            ?>
                            <a class="listing-popup-btn" href="<?php echo esc_url( $img_url ); ?>" data-width="<?php echo esc_attr($width); ?>" data-height="<?php echo esc_attr($height); ?>">
                                <?php
                                    echo wp_get_attachment_image( $image->ID, $img_size );
                                    if( ! empty( $number ) && $counter === 5 ) { ?>
                                        <span> <?php esc_html_e('Show All Photos', 'clplace'); ?></span>
                                   <?php }
                                ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
			</div>
		<?php }
	}

	/**
	 * @return listing_details_slider
	 */
	public static function listing_details_gallery_2() {
		global $listing;
		$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

		$video_urls = [];
		if ( ! Functions::is_video_urls_disabled() ) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
		}
		// Image Gallery
		$images              = $listing->get_images();
		$total_gallery_image = count( $images );
		$number = $total_gallery_image - 5;

		if ($total_gallery_image < 2) {
			$item_count = 'items-one';
		} elseif ($total_gallery_image < 3 ) {
			$item_count = 'items-two';
		} elseif ($total_gallery_image < 4) {
			$item_count = 'items-three';
		} elseif ($total_gallery_image < 5) {
			$item_count = 'items-four';
		} else {
			$item_count = 'items-five';
		}
		if ( $total_gallery_image ) {
			?>
			<div class="page-header-gallery">
				<div class="photo-swip-gallery-wrap <?php echo esc_attr($item_count); ?>">
					<?php
					if ( !in_array('video_url', $detailOption) ){
						if ( ! empty( $video_urls ) ) { ?>
							<div class="listing-gallery-item">
								<div class="video-info rtcl-slider-video-item ratio-16x9">
									<iframe class="rtcl-lightbox-iframe"
											src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ) ?>"
											style="height: 404px"
											allowFullScreen></iframe>
								</div>
							</div>
						<?php }
					}
					$counter = 0;
					foreach ( $images as $image ) {
						++$counter;
						if ($total_gallery_image < 2) {
							$img_size = 'full';
						} elseif ($total_gallery_image < 3) {
							$img_size = 'clplace-1200-650';
						} else {
							$img_size = 'clplace-500-290';
						}
						?>
						<div class="listing-gallery-item photoswip-item image-size-<?php echo esc_attr($img_size.' item-'.$counter); ?>">
							<?php
							$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
							$getimagesize = getimagesize($img_url);
							$width = $getimagesize[0];
							$height = $getimagesize[1];
							?>
							<a class="listing-popup-btn" href="<?php echo esc_url( $img_url ); ?>" data-width="<?php echo esc_attr($width); ?>" data-height="<?php echo esc_attr($height); ?>">
								<?php
								echo wp_get_attachment_image( $image->ID, $img_size );
								if( ! empty( $number ) && $counter === 5 ) {
									echo '<span>+'.esc_html($number).'</span>';
								}
								?>
							</a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		<?php }
	}

	/**
	 * @return listing_details_slider
	 */
	public static function listing_details_slider() {
		global $listing;
		$imgNone = '';
		$total_gallery_image = '';
		$total_gallery_videos = '';
		$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

		$images = $listing->get_images();
		$videos = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
		$rand   = substr( md5( mt_rand() ), 0, 7 );

		$slider_data = [
			'allowSlideNext' => true,
			'allowSlidePrev' => true,
			'centeredSlides' => true,
			'roundLengths' => true,
			"navigation"     => [
				"nextEl" => ".swiper-button-next",
				"prevEl" => ".swiper-button-prev",
			],
			"loop"           => true,
			"speed"          => 1000,
			"spaceBetween"   => 10,
			"breakpoints"    => [
				0    => [
					"slidesPerView" => 1
				],
				576  => [
					"slidesPerView" => 1
				],
				800  => [
					"slidesPerView" => 1
				],
				1200 => [
					"slidesPerView" => 1
				]
			]
		];
		if ( is_rtl() ) {
			$slider_data['rtl'] = true;
		}
		$data['slider_data'] = json_encode( $slider_data );

		if (!empty($images)) {
			$total_gallery_image = count( $images );
		}
		if (!empty($videos)) {
			$total_gallery_videos = count($videos);
		}

		if (!empty($videos)) {
			$total_gallery_item  = $total_gallery_image + $total_gallery_videos;
		} else {
			$total_gallery_item  = $total_gallery_image;
		}
		if ( $total_gallery_item ) :
			$owl_class = $total_gallery_item > 3 && Functions::is_gallery_slider_enabled() ? " slick-navigation-layout2" : 'not-slider';
			if ($total_gallery_image === 0) {
				$imgNone = 'image-not-set';
			}
			?>
			<!-- Listing Banner Area Start Here -->
			<section class="single-listing-carousel-wrap photo-swip-gallery-wrap <?php echo esc_attr( $imgNone ); ?>">
				<?php if ( $total_gallery_item > 1 && Functions::is_gallery_slider_enabled() ){ ?>
					<div class="<?php echo esc_attr( $owl_class ); ?>"
						 data-carousel-options='<?php echo esc_attr( $data['slider_data'] ); ?>'>
						<div class="rtcl-related-slider rtcl-carousel-slider" id="rtcl-related-slider-banner" data-options="<?php echo esc_attr( $data['slider_data'] ); ?>">
							<div class="swiper-wrapper">
								<?php
								if ( !in_array('video_url', $detailOption) ){
									if ($total_gallery_videos) {
										foreach ($videos as $index => $video_url) { ?>
											<div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
												<iframe class="rtcl-lightbox-iframe"
														src="<?php echo esc_url( Functions::get_sanitized_embed_url($video_url) ) ?>"
														allowFullScreen></iframe>
											</div>
											<?php
										}
									}
								}
								if ( $total_gallery_image ) {
									foreach ( $images as $index => $image ) :
										$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
										$getimagesize = getimagesize($img_url);
										$width = $getimagesize[0];
										$height = $getimagesize[1];
										?>
										<div class="swiper-slide nav-item photoswip-item">
											<a href="<?php echo esc_url( $img_url ); ?>" data-width="<?php echo esc_attr($width); ?>" data-height="<?php echo esc_attr($height); ?>">
												<?php echo wp_get_attachment_image( $image->ID, 'full' ); ?>
											</a>
										</div>
									<?php endforeach;
								}
								?>
							</div>
							<div class="swiper-button-next"></div>
							<div class="swiper-button-prev"></div>
						</div>
					</div>
				<?php } elseif ( $total_gallery_item > 1 && Functions::is_gallery_slider_enabled() ) {
					if ( $total_gallery_item >= 3 ) {
						$cols = '4';
					} else {
						$cols = '6';
					}
					?>
					<div class="row no-gutters justify-content-center">
						<?php if ( !in_array('video_url', $detailOption) ){
							if ($total_gallery_videos) {
								foreach ($videos as $index => $video_url) { ?>
									<div class="col-md-<?php echo esc_attr( $cols ); ?> image-fit">
										<div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
											<iframe
												class="rtcl-lightbox-iframe"
												src="<?php echo esc_url( Functions::get_sanitized_embed_url($video_url) ) ?>"
												allowFullScreen></iframe>
										</div>
									</div>
									<?php
								}
							}
						}
						?>
						<?php foreach ( $images as $index => $image ) {
							$img_url = wp_get_attachment_image_url( $image->ID, $size = 'full' );
							?>
							<div class="col-md-<?php echo esc_attr( $cols ); ?> image-fit">
								<div class="swiper-slide nav-item photoswip-item">
									<a href="<?php echo esc_url( $img_url ); ?>">
										<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery' ); ?>
									</a>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } else { ?>
					<div class="row">
						<?php if ( !in_array('video_url', $detailOption) ){
							if ($total_gallery_videos) {
								foreach ($videos as $index => $video_url) { ?>
									<div class="col-md-12 image-fit-full">
										<div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
											<iframe class="rtcl-lightbox-iframe"
													src="<?php echo esc_url( Functions::get_sanitized_embed_url($video_url) ) ?>"
													allowFullScreen></iframe>
										</div>
									</div>
									<?php
								}
							}
						}
						?>
						<?php foreach ( $images as $index => $image ) { ?>
							<div class="col-md-12 image-fit-full text-center">
								<?php echo wp_get_attachment_image( $image->ID, 'full' ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</section>
			<!-- Listing Banner Area End Here -->
		<?php endif;
	}

	/**
	 * @param $post_id
	 *
	 * @return string|void
	 */
	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_general_settings' );
		if ( isset( $has_favourites['has_favourites'] ) && 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( $post_id == 0 ) {
				global $post;
				$post_id = $post->ID;
			}
			$favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );

			if ( in_array( $post_id, $favourites, true ) ) {
				return '<a href="javascript:void(0)" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '"><span class="icon-heart-2"></span></a>';
			} else {
				return '<a href="javascript:void(0)" class="rtcl-favourites" data-id="' . $post_id . '"><i class="icon-heart-2"></i></a>';
			}
		} else {
			return '<a href="#" class="rtcl-favourites" data-toggle="modal" data-target="#logoutModalCenter" title="' . esc_html__( "Favourites", 'clplace' )
			       . '"><i class="icon-heart-2"></i></a>';
		}
	}


	/**
	 * @return void
	 */
	public static function logout_user_favourite() { ?>
		<!-- Modal -->
		<div class="modal fade" id="logoutModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog vertical-align-center" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="logoutModalTitle"><?php esc_html_e( 'Login', 'clplace' ); ?></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="icon-close-1"></i></span>
						</button>
					</div>
					<div class="modal-body">
						<div class="share-icon">
							<form id="rtcl-login-form" class="form-horizontal" method="post">
								<?php do_action( 'rtcl_login_form_start' ); ?>
								<div class="form-group">
									<label for="rtcl-user-login" class="control-label">
										<?php esc_html_e( 'Username or E-mail', 'clplace' ); ?>
										<strong class="rtcl-required">*</strong>
									</label>
									<input type="text" name="username" autocomplete="username"
										   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
										   id="rtcl-user-login" class="form-control" required/>
								</div>

								<div class="form-group">
									<label for="rtcl-user-pass" class="control-label">
										<?php esc_html_e( 'Password', 'clplace' ); ?>
										<strong class="rtcl-required">*</strong>
									</label>
									<input type="password" name="password" id="rtcl-user-pass"
										   autocomplete="current-password"
										   class="form-control" required/>
								</div>

								<?php do_action( 'rtcl_login_form' ); ?>

								<div class="form-group">
									<div id="rtcl-login-g-recaptcha" class="mb-2"></div>
									<div id="rtcl-login-g-recaptcha-message"></div>
								</div>

								<div class="form-group d-flex align-items-center">
									<button type="submit" name="rtcl-login" class="btn btn-primary" value="login">
										<?php esc_html_e( 'Login', 'clplace' ); ?>
									</button>
									<div class="form-check">
										<input type="checkbox" name="rememberme" id="rtcl-rememberme" value="forever">
										<label class="form-check-label" for="rtcl-rememberme">
											<?php esc_html_e( 'Remember Me', 'clplace' ); ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<p class="rtcl-forgot-password">
										<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'clplace' ); ?></a>
									</p>
								</div>
								<?php do_action( 'rtcl_login_form_end' ); ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public static function clplace_single_listing_meta() {
		$details_settings = Functions::get_option( 'rtcl_single_listing_settings' );
		$show_rating = ! empty( $details_settings['display_options_detail'] ) && in_array( 'rating', $details_settings['display_options_detail'] );
		$show_status = ! empty( $details_settings['display_options_detail'] ) && in_array( 'status', $details_settings['display_options_detail'] );
		global $listing; ?>
		<!-- Meta data -->
		<div class="rtcl-listing-meta">

			<?php $listing->the_meta(); ?>

			<?php if ( !empty( $show_rating )) { ?>
                <div class="listing-review"><?php Listing_Functions::clplace_listing_rating_counting( $listing ); ?></div>
			<?php } ?>

			<?php if ( ! empty( $show_status ) ) {
				Listing_Functions::listing_bhs_status( $listing );
			} ?>

		    <?php
                if ( $listing->has_category() && $listing->can_show_category() ) :
                $category = $listing->get_categories();
                $category = end( $category );
			?>

            <div class="rt-categories">
                <i class="icon-buliding"></i>
                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
            </div>
            <?php endif; ?>

			<?php TemplateHooks::listing_price(); ?>
		</div>
		<?php
	}

	/**
	 * @return bool|void
	 */
	public static function form_builder_custom_group_field_check() {
		global $listing;
		$form = $listing->getForm();
        if (!empty($form)) {
	        $fields = $form->getFieldAsGroup( FBField::CUSTOM );
	        $fields_available = false;
	        if ( count( $fields ) ) {
		        foreach ( $fields as $fieldName => $field ) {
			        $field = new FBField( $field );
			        $value
			               = $field->getFormattedCustomFieldValue( $listing->get_id() );
			        if ( ! empty( $value ) ) {
				        return true;
			        }
		        }

		        return $fields_available;
	        }
        }
	}

	/**
	 * @return get_share_link
	 */
	public static function get_share_link() {
		global $listing;
		?>
		<!-- Modal -->
		<div class="modal fade social-share" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="socialShareModalTitle"><?php esc_html_e( 'Share This Link Via', 'clplace' ); ?></h5>
					</div>
					<div class="modal-body">
						<div class="share-icon">
							<?php $listing->the_social_share(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @return get_repost_abuse
	 */
	public static function get_repost_abuse() { ?>
        <div class="rtcl-popup-wrapper" id="rtcl-report-abuse-modal">
            <div class="rtcl-popup">
                <div class="rtcl-popup-content">
                    <div class="rtcl-popup-header">
                        <h5 class="rtcl-popup-title" id="rtcl-report-abuse-modal-label"><?php esc_html_e( 'Report Abuse', 'classified-listing' ); ?></h5>
                        <a href="#" class="rtcl-popup-close">×</a>
                    </div>
                    <div class="rtcl-popup-body">
                        <form id="rtcl-report-abuse-form">
                            <div class="rtcl-form-group">
                                <label class="rtcl-field-label" for="rtcl-report-abuse-message">
									<?php esc_html_e( 'Your Complaint', 'classified-listing' ); ?>
                                    <span class="rtcl-star">*</span>
                                </label>
                                <textarea name="message" class="rtcl-form-control" id="rtcl-report-abuse-message" rows="3"
                                          placeholder="<?php esc_attr_e( 'Message... ', 'classified-listing' ); ?>"
                                          required></textarea>
                            </div>
                            <div id="rtcl-report-abuse-g-recaptcha"></div>
                            <div id="rtcl-report-abuse-message-display"></div>
                            <button type="submit"
                                    class="rtcl-btn rtcl-btn-primary"><?php esc_html_e( 'Submit', 'classified-listing' ); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * @param $phone
	 * @param $whatsapp_number
	 * @param $telegram
	 *
	 * @return void
	 */
	public static function the_phone( $phone = '', $whatsapp_number = '', $telegram = '' ){
		$mobileClass = wp_is_mobile() ? " rtcl-mobile" : null;
		$phone_options = [];
		if ( $phone ) {
			$phone_options = [
				'safe_phone'   => mb_substr( $phone, 0, mb_strlen( $phone ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' ),
				'phone_hidden' => mb_substr( $phone, - 3 )
			];
		}
		if ( $whatsapp_number && ! Functions::is_field_disabled( 'whatsapp_number' ) ) {
			$phone_options['safe_whatsapp_number'] = mb_substr( $whatsapp_number, 0, mb_strlen( $whatsapp_number ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' );
			$phone_options['whatsapp_hidden']      = mb_substr( $whatsapp_number, - 3 );
		}
		if ( $telegram ) {
			$phone_options['safe_telegram'] = mb_substr( $telegram, 0, mb_strlen( $telegram ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' );
			$phone_options['telegram_hidden'] = mb_substr( $telegram, - 3 );
		}

		$phone_options = apply_filters( 'rtcl_phone_number_options', $phone_options, [
			'phone'             => $phone,
			'whatsapp_number'   => $whatsapp_number,
			'telegram'          => $telegram
		] );

		if ( $phone ) { ?>
			<div class='item-number phone rtcl-contact-reveal-wrapper reveal-phone<?php echo esc_attr( $mobileClass ); ?>' data-options="<?php echo esc_attr( wp_json_encode( $phone_options ) ); ?>">
				<div class="number-icon">
					<i class="icon-phone-call"></i>
					<div class='numbers'>
						<?php echo esc_html( $phone_options['safe_phone'] ); ?>
					</div>
				</div>
				<small class='text-muted'><i class="icon-eye"></i></small>
			</div>
		<?php } elseif ( $whatsapp_number ) { ?>
			<div class='item-number whatsapp rtcl-contact-reveal-wrapper reveal-phone<?php echo esc_attr( $mobileClass ); ?>' data-options="<?php echo esc_attr( wp_json_encode( $phone_options ) ); ?>">
				<div class="number-icon">
					<i class="fab fa-whatsapp"></i>
					<div class='numbers'><?php echo esc_html( $phone_options['safe_whatsapp_number'] ); ?></div>
				</div>
				<small class='text-muted'><i class="icon-eye"></i></small>
			</div>
		<?php } elseif ( $telegram ) { ?>
			<div class='item-number telegram rtcl-contact-reveal-wrapper reveal-phone<?php echo esc_attr( $mobileClass ); ?>' data-options="<?php echo esc_attr( wp_json_encode( $phone_options ) ); ?>">
				<div class="number-icon">
					<i class="fa-brands fa-telegram"></i>
					<div class='numbers'> <?php echo esc_html( $phone_options['safe_telegram'] ); ?></div>
				</div>
				<small class='text-muted'><i class="icon-eye"></i></small>
			</div>
		<?php }
	}

	/**
	 * @param $term_id
	 * @param $icon_type
	 *
	 * @return string|null
	 */
	public static function listing_cat_icon( $term_id, $icon_type = NULL ) {
		$cat_img  = $cat_icon = $icon = null;
		$image_id = get_term_meta( $term_id, '_rtcl_image', true );
		if ( $image_id ) {
			$image_attributes = wp_get_attachment_image_src( (int) $image_id, 'medium' );
			$image            = $image_attributes[0];
			if ( '' !== $image ) {
				$cat_img = sprintf( '<img src="%s" class="rtcl-cat-img" alt="%s"/>', $image, esc_attr__( 'Category Image', 'clplace' ) );
			}
		}
		$icon_id = get_term_meta( $term_id, '_rtcl_icon', true );
		if ( $icon_id ) {
			$cat_icon = sprintf( '<span class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></span>', $icon_id );
		}

		$icon = $icon_type == 'icon' ? $cat_icon : $cat_img;

		return $icon;
	}

	/**
	 * @param $term_id
	 *
	 * @return int
	 */
	public static function rt_term_post_count( $term_id, $term_name ){
		$args = array(
			'nopaging'            => true,
			'fields'              => 'ids',
			'post_type'           => 'rtcl_listing',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'suppress_filters'    => false,
			'tax_query' => array(
				array(
					'taxonomy' => $term_name,
					'field'    => 'term_id',
					'terms'    => $term_id,
				)
			)
		);
		$posts = get_posts( $args );
		return count( $posts );
	}

	/**
	 * @param Listing $listing
	 */
	public static function listing_compare_button( $listing ) {
		if ( empty( rtcl()->session ) ) {
			rtcl()->initialize_session();
		}
		$compare_ids = rtcl()->session->get( 'rtcl_compare_ids', [] );
		$selected_class = '';
		if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
			$selected_class = ' selected';
		}
		if ( FnsPro::is_enable_compare() ) {
		?>
		<div class="rtcl-compare rtcl-btn<?php echo esc_attr( $selected_class ); ?>"
			 data-tooltip="<?php esc_attr_e( "Add to compare list", "clplace" ) ?>"
			 data-listing_id="<?php echo absint( $listing->get_id() ) ?>"><i
				class="icon-center-arow"></i></div>
		<?php
		}
	}

	/**
	 * @param $listing
	 *
	 * @return void
	 */
	public static function listing_quick_view_button( $listing ) {
		?>
		<div class="rtcl-quick-view rtcl-btn"
			data-tooltip="<?php esc_attr_e( "Quick view", "clplace" ) ?>"
			data-listing_id="<?php echo absint( $listing->get_id() ) ?>"><i class="icon-eye"></i></div>
		<?php
	}

	/**
	 * @param $listing
	 *
	 * @return void
	 */
	public static function listing_bhs_status( $listing ) {

		/** @var Listing $listing */
		$form = $listing->getForm();
		if ( $form ) {
			$allBhs = BHS::get_business_hours( $listing->get_id() );
			if ( empty( $allBhs['bhs'] ) ) {
				return;
			}
			$business_hours = $allBhs['bhs'];
		} else {
			$business_hours = BHS::get_old_business_hours( $listing->get_id() );
			$allBhs = $business_hours;
		}

		if ( !BHS::hasOpenHours( $business_hours ) ) {
			return;
		}

		$current_week_day = absint( gmdate( 'w', current_time( 'timestamp' ) ) );
		$defaults = [
			'header'                => true,
			'footer'                => false,
			'day_name'              => 'full',
			'show_closed_day'       => true,
			'show_closed_period'    => true,
			'show_open_status'      => true,
			'open_text'             => esc_html__( 'Open Now', 'clplace' ),
			'close_text'            => esc_html__( 'Close Now', 'clplace' ),
		];

		$options = wp_parse_args( apply_filters( 'rtcl_business_hours_display_options', [] ), $defaults );

		if ( !empty( $options['show_open_status'] ) ) {
			if ( BHS::openStatus( $business_hours ) ) {
				printf( '<div class="rtclbh-status rtclbh-status-open"><i class="icon-cloock"></i> %s</div>', !empty( $options['open_text'] ) ? esc_html( $options['open_text'] ) : esc_html__( 'Open Now', 'clplace' ) );
			} else {
				printf( '<div class="rtclbh-status rtclbh-status-closed"><i class="icon-cloock"></i> %s</div>', !empty( $options['close_text'] ) ? esc_html( $options['close_text'] ) : esc_html__( 'Closed Now', 'clplace' ) );
			}
		}
	}

	/**
	 * Get min and max meta value
	 *
	 * @param $key
	 * @param $type
	 *
	 * @return string|null
	 */
	public static function get_min_max_meta_value( $key, $type = 'max' ) {
		global $wpdb;
		$sql   = "SELECT " . $type . "( cast( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key='%s'";
		$query = $wpdb->prepare( $sql, $key );
		$value = $wpdb->get_var( $query );

		return $value;
	}

	/**
	 * Get Global Price Range (min and max price)
	 * @return array
	 */
	public static function listing_price_range() {
		$global_price_min     = self::get_min_max_meta_value( 'price', 'min' );
		$global_price_max     = absint( self::get_min_max_meta_value( 'price' ) );
		$global_max_price_max = absint( self::get_min_max_meta_value( '_rtcl_max_price' ) );
		$max_price            = max( $global_price_max, $global_max_price_max );
		$price_range          = [];

		$price_range['min_price'] = $global_price_min ?? 0;
		$price_range['max_price'] = $max_price;

		return $price_range;
	}

	public static function get_advanced_search_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == $choice ) {
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						absint( $field->getFieldId() ),
						$id . wp_rand(),
						$field->getLabel(),
						$options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : [];
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : ''
					);

					$field_html = sprintf(
                '<div class="search-item price-wrapper">
                            <div class="price-range">
                                <label>%s</label>
                                <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                %s
                            </div>
                         </div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] )
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] )
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
			}
		}

		return $field_html;
	}


	/**
	 * Build CSS class string for checkbox/radio field column layout.
	 *
	 * Reads the field's direction and columns settings from FBField
	 * and returns classes matching the form builder's grid system.
	 *
	 * @param FBField $field The form builder field instance.
	 *
	 * @return string Space-separated CSS classes (e.g. 'rtcl-checkbox-group vertical cols-3').
	 */
	public static function clplace_get_field_columns_class( $field ) {
		$classes = [];
		$element = $field->getElement();

		$group_type = 'checkbox' === $element ? 'rtcl-checkbox-group' : 'rtcl-radio-group';
		$classes[]  = $group_type;

		$direction = $field->getData( 'direction', 'vertical' );
		if ( empty( $direction ) ) {
			$direction = 'vertical';
		}
		$classes[] = sanitize_html_class( $direction );

		$cols = (int) $field->getData( 'vertical_cols', 1 );
		if ( $cols > 1 ) {
			$classes[] = 'cols-' . $cols;
		}

		return implode( ' ', $classes );
	}

	/**
	 * @param $icons_lists
	 *
	 * @return array
	 */
	public function rtcl_get_icon_list_modify( $icons_lists ) {
		$new_icons = [
			" icon-right-open",
			" icon-left-open-big",
			" icon-right-open-big",
			" icon-author",
			" icon-twiter",
			" icon-location-1",
			" icon-heart-1",
			" icon-arrow-right-1",
			" icon-reply",
			" icon-star",
			" icon-eye-light",
			" icon-tag-2",
			" icon-tag",
			" icon-attention",
			" icon-location",
			" icon-plus",
			" icon-ok",
			" icon-calander",
			" icon-down-open",
			" icon-ok-1",
			" icon-arrow-3",
			" icon-massage",
			" icon-facebook",
			" icon-search-1",
			" icon-linkdin",
			" icon-insta",
			" icon-right-arow",
			" icon-location-2",
			" icon-heart-3",
			" icon-arrow-2",
			" icon-cancel",
			" icon-star-4",
			" icon-location-3",
			" icon-heart-empty",
			" icon-talk-bubbles-line",
			" icon-massage-1",
			" icon-heart-2",
			" icon-center-arow",
			" icon-printer",
			" icon-go-to-top",
			" icon-info-outline",
			" icon-star-2",
			" icon-minus",
			" icon-phone-call",
			" icon-heart-empty-1",
			" icon-go-right",
			" icon-data-analysis",
			" icon-location-4",
			" icon-massage-3",
			" icon-star-1",
			" icon-play-btn",
			" icon-phon-call-2",
			" icon-eye",
			" icon-gps",
			" icon-satting",
			" icon-search",
			" icon-buliding",
			" icon-cap",
			" icon-location-5",
			" icon-buliding-2",
			" icon-routing",
			" icon-courthouse",
			" icon-railway-station",
			" icon-airport",
			" icon-play",
			" icon-check",
			" icon-quotation",
			" icon-cloock",
			" icon-check-1",
			" icon-close-1",
			" icon-quotion-2",
			" icon-web",
			" icon-arrow-bottom",
			" icon-flug",
			" icon-plus-squared",
			" icon-plus-2",
			" icon-angle-up",
			" icon-angle-down",
		];

		return array_merge( $icons_lists, $new_icons );
	}

}
