<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $can_search_by_category
 * @var $can_search_by_keyword
 * @var $can_search_by_listing_types
 * @var $can_search_by_location
 * @var $can_search_by_radius_search
 * @var $can_search_by_custom_field
 * @var $can_search_by_price
 * @var $can_search_by_radius_distance
 * @var $category_is_parent
 * @var $layout
 * @var $min_price
 * @var $max_price
 * @var $params
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Rtcl\Helpers\Functions' ) ) {
	return;
}

use Rtcl\Helpers\Link;
use Rtcl\Helpers\Functions;
use RT\Clplace\Plugins\Listing_Functions;
use Rtcl\Resources\Options as RtclOptions;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

$currency = Functions::get_currency_symbol();

$loc_text = esc_attr__( 'Select Location', 'clplace' );
$typ_text = esc_attr__( 'Select Type', 'clplace' );
$cat_text = esc_attr__( 'Select Category', 'clplace' );

$is_form_visible = ( $can_search_by_category || $can_search_by_keyword || $can_search_by_listing_types || $can_search_by_location || $can_search_by_radius_search );
if ( $is_form_visible === false ) {
	printf( '<div class="alert alert-danger text-center" role="alert"><i class="fas fa-info-circle mr-2"></i>%s</div>',
		esc_html__( 'Please choose at least one or more fields for showing the widget', 'clplace' ) );
	return;
}

$orderby = strtolower( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_orderby', 'name' ) );
$order   = strtoupper( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_order', 'DESC' ) );

$listing_price_range = Listing_Functions::listing_price_range();
$_min_price          = $min_price ?? $listing_price_range['min_price'];
$_max_price          = $max_price ?? $listing_price_range['max_price'];

$_min_price = apply_filters( 'rtcl_raw_price', $_min_price );
$_max_price = apply_filters( 'rtcl_raw_price', $_max_price );
?>
<div class="banner-box banner-layout-<?php echo esc_attr( $layout ); ?>">
    <form action="<?php echo esc_url( Link::get_listings_page_link() ); ?>"
          class="advance-search-form rtcl-widget-search-form"
          data-min-price="<?php echo esc_attr( $_min_price ) ?>"
          data-max-price="<?php echo esc_attr( $_max_price ) ?>">
		<?php $permalink_structure = get_option( 'permalink_structure' ); ?>
		<?php if ( ! $permalink_structure ) : ?>
            <input type="hidden" name="post_type" value="rtcl_listing">
		<?php endif; ?>

		<?php if ( $can_search_by_listing_types ): ?>
            <div class="ad-type-wrapper search-radio-check">
                <ul class="list-inline">
					<?php
					$listing_types = Functions::get_listing_types();
					$listing_types = empty( $listing_types ) ? [] : $listing_types;
					?>
					<?php foreach ( $listing_types as $key => $listing_type ): ?>
                        <li>
                            <label for="<?php echo esc_attr( $key ); ?>"
                                   class="<?php //echo esc_attr( $is_active ); ?>">

                                <span><?php echo esc_html( $listing_type ); ?></span>
                                <input
									<?php //echo esc_attr( $is_checked ); ?>
                                        class="sr-only"
                                        type="radio"
                                        name="<?php echo esc_attr( 'filters[ad_type]' ) ?>"
                                        id="<?php echo esc_attr( $key ); ?>"
                                        value="<?php echo esc_attr( $key ); ?>"
                                >
                            </label>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>
		<?php endif; ?>

        <div class="search-box">

			<?php if ( $can_search_by_keyword ): ?>
                <div class="search-item search-keyword search-select">
                    <div class="input-group">
                        <input type="text" data-type="listing" name="s" class="rtcl-autocomplete form-control"
                               placeholder="<?php esc_attr_e( 'Enter Keyword here ...', 'clplace' ); ?>"
                               value="<?php if ( isset( $_GET['s'] ) ) {
							       echo esc_html( sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
						       } ?>"/>
                    </div>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_category ): ?>
                <div class="search-item search-select rtin-category">
					<?php

					$cat_args = [
						'show_option_none'  => $cat_text,
						'option_none_value' => '',
						'taxonomy'          => rtcl()->category,
						'name'              => 'filter_category',
						'id'                => 'rtcl-category-cat' . wp_rand(),
						'class'             => 'select2 rtcl-category-search rtcl-category-search-ajax',
						'selected'          => get_query_var( 'rtcl_category' ),
						'hierarchical'      => true,
						'value_field'       => 'id',
						'depth'             => Functions::get_category_depth_limit(),
						'show_count'        => false,
						'hide_empty'        => false,
						'orderby'           => $orderby,
						'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
					];
					if ( '_rtcl_order' === $orderby ) {
						$cat_args['orderby']  = 'meta_value_num';
						$cat_args['meta_key'] = '_rtcl_order';
					}

					if ( $category_is_parent ) {
						$cat_args['parent'] = 0;
					}
					wp_dropdown_categories( $cat_args );
					?>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_listing_types ): ?>
                <div class="search-item search-select">
                    <select class="select2" name="filters[ad_type]"
                            data-placeholder="<?php echo esc_attr( $typ_text ); ?>">
                        <option value=""><?php echo esc_html( $typ_text ); ?></option>
						<?php
						$listing_types = Functions::get_listing_types();
						$listing_types = empty( $listing_types ) ? [] : $listing_types;
						?>
						<?php foreach ( $listing_types as $key => $listing_type ): ?>
                            <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $listing_type ); ?></option>
						<?php endforeach; ?>
                    </select>
                </div>
			<?php endif; ?>

			<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && $can_search_by_location && 'local' === Functions::location_type() ): ?>
                <div class="search-item search-select rtin-location">
					<?php
                        $location_args = [
                            'show_option_none'  => $loc_text,
                            'option_none_value' => '',
                            'taxonomy'          => rtcl()->location,
                            'name'              => 'filter_location',
                            'id'                => 'rtcl-location-search-' . wp_rand(),
                            'class'             => 'select2 rtcl-location-search',
                            'selected'          => get_query_var( 'rtcl_location' ),
                            'hierarchical'      => true,
                            'value_field'       => 'id',
                            'depth'             => Functions::get_location_depth_limit(),
                            'show_count'        => false,
                            'hide_empty'        => false,
                            'orderby'           => $orderby,
                            'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
                        ];
                        if ( '_rtcl_order' === $orderby ) {
                            $location_args['orderby']  = 'meta_value_num';
                            $location_args['meta_key'] = '_rtcl_order';
                        }
                        wp_dropdown_categories( $location_args );
					?>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_radius_search ): ?>
                <div class="search-item search-keyword rtcl-radius-group">
                    <div class="input-group rtcl-geo-address-field">
                        <input id="rtcl-geo-address-search" type="text" name="geo_address" autocomplete="off"
                               value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( $_GET['geo_address'] ) : '' ?>"
                               placeholder="<?php esc_attr_e( "Enter location", "clplace" ) ?>"
                               class="form-control rtcl-geo-address-input"/>
                        <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                        <input type="hidden" class="latitude" name="center_lat"
                               value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( $_GET['center_lat'] ) : '' ?>">
                        <input type="hidden" class="longitude" name="center_lng"
                               value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( $_GET['center_lng'] ) : '' ?>">
                    </div>
                </div>
				<?php if ( $can_search_by_radius_distance ) :
					$default_radius = apply_filters( 'clplace_widget_default_radius', '' );
					$rs_data = RtclOptions::radius_search_options();
					?>
                    <div class="search-item search-radius">
                        <div class="input-group">
                            <div class="rtcl-search-input-button">
                                <input type="number" class="form-control" name="distance"
                                       value="<?php echo esc_attr( $_GET['distance'] ?? $default_radius ) ?>"
                                       placeholder="<?php esc_attr_e( "Radius", "clplace" ); ?>">
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
			<?php endif ?>

            <div class="search-item search-btn">
				<?php if ( $can_search_by_custom_field ): ?>
                    <button class="advanced-btn collapsed" type="button"><i class="icon-satting"></i></button>
				<?php endif; ?>
                <button type="submit" class="submit-btn">
                    <i class="icon-search"></i>
					<?php esc_html_e( 'Search', 'clplace' ); ?>
                </button>
            </div>
        </div>
		<?php
		if ( $can_search_by_custom_field ): ?>
            <div class="advanced-search-box" id="advanced-search">
                <div class="advanced-box advanced-banner-box rtcl_cf_by_category_html" data-price-search="<?php echo esc_attr( $can_search_by_price ? 'yes' : 'no' ) ?>">

					<?php
					$directoryData = FBHelper::getDirectoryData('all');
					$hideAbleCount = 40;
					$filterTypes = [
						'text',
						'textarea',
						'number',
						'checkbox',
						'select',
						'radio',
						'date',
					];
					$field_html = null;
					if ( !empty( $directoryData['custom'] ) ) {
						foreach ( $directoryData['custom'] as $custom_field ) {
							if ( empty( $custom_field['filterable'] )
							     || ! in_array( $custom_field['element'],
									$filterTypes )
							) {
								continue;
							}
							$field = new FBField( $custom_field );

							$isActive = false;
							$metaKey = $field->getMetaKey();
							$filterName = 'cf_' . $metaKey;

							$values = !empty( $params[$filterName] ) ? ( is_array( $params[$filterName] ) ? array_filter( array_map( function ( $param ) {
								return trim( sanitize_text_field( wp_unslash( $param ) ) );
							}, $params[$filterName] ) ) : trim( sanitize_text_field( wp_unslash( $params[$filterName] ) ) ) ) : '';

							if ( 'number' == $field->getElement() ) {
								$filterInput = !empty( $values ) ? ( is_array( $values ) ? $values : explode( ',', $values ) ) : [
									null,
									null
								];
								$filterInput = array_map( 'intval', $filterInput );
								$fMinValue = !empty( $filterInput[0] ) ? esc_attr( $filterInput[0] ) : null;
								$fMaxValue = !empty( $filterInput[1] ) ? esc_attr( $filterInput[1] ) : null;
								$isActive = $fMinValue || $fMaxValue;
								$field_html .= sprintf( '<div class="rtcl-filter-number-field-wrap min-max">
                                                                    <input id="filter-cf-number-%1$s-min" name="filter-cf-number-%1$s-min" type="number" value="%2$s" class="rtcl-filter-number-field min form-control" placeholder="%3$s">									
                                                                    <input id="filter-cf-number-%1$s-max" name="filter-cf-number-%1$s-max" type="number" value="%4$s" class="rtcl-filter-number-field max form-control" placeholder="%5$s">
                                                                </div>',
									$metaKey,
									$fMinValue,
									esc_html__( 'Min.', 'clplace' ),
									$fMaxValue,
									esc_html__( 'Max.', 'clplace' )
								);
							} elseif ( 'date' == $field->getElement() ) {

								$field_html .= sprintf( '<div class="rtcl-filter-date-field-wrap">
														<input id="filter-cf-date-%1$s" autocomplete="off" name="filter-cf-date-%1$s" type="text" value="%2$s" data-options="%4$s" class="form-control rtcl-filter-date-field" placeholder="%3$s">									
													</div>',
									esc_attr( $filterName ),
									esc_attr( $values ),
									esc_html__( 'Date', 'clplace' ),
									htmlspecialchars(
										wp_json_encode(
											$field->getDateFieldOptions(
												[
													'singleDatePicker' => $field->getData( 'filterable_date_type' ) === 'single',
													'autoUpdateInput'  => false,
												]
											)
										)
									)
								);
								$isActive = !empty( $values );
							} elseif ( in_array( $field->getElement(), [ 'text', 'textarea' ], true ) ) {
								$isActive = !empty( $values );
								$placeholder_text = sprintf( esc_html__( 'Search by %s', 'clplace' ), $field->getLabel() );
								$field_html .= sprintf( '<div class="rtcl-ajax-filter-text">
																	<label class="screen-reader-text" for="rtcl-ajax-filter-%1$s">%3$s ...</label>
                                                                    <input id="rtcl-ajax-filter-%1$s" name="%1$s" type="text" autocomplete="off" value="%2$s" class="form-control rtcl-filter-text-field" placeholder="%3$s">
                                                                    <i class="rtcl-clear-text rtcl-icon-trash"></i>
                                                                </div>',
									$filterName,
									$values,
									apply_filters( 'rtcl_ajax_filter_cf_text_field_placeholder', $placeholder_text, $field )
								);
							} else {
								$values = is_string( $values ) ? explode( ',', $values ) : $values;
								$options = $field->getOptions();
								if ( !empty( $options ) ) {
									$field_html .= '<div class="search-item checkbox-wrapper">';
									$count = 0;
									foreach ( $options as $option ) {
										$count++;
										$option = wp_parse_args( $option, [ 'value' => '', 'label' => '' ] );
										$_value = $option['value'];
										$_label = $option['label'];

										$field_html .= sprintf( '<div class="rtcl-ajax-filter-data-item rtcl-filter-checkbox-item rtcl-filter-cf-%1$s%6$s">
															<div class="rtcl-ajax-filter-diiWrap">
																<input id="cf-%1$s" name="%2$s" value="%3$s" type="checkbox" class="rtcl-filter-checkbox"%4$s />
																<label for="cf-%1$s" class="rtcl-filter-checkbox-label">
																	<span class="rtcl-filter-checkbox-text">%5$s</span>
																</label>
															</div>
												</div>',
											esc_attr( $filterName . '-' . $_value ),
											$filterName,
											esc_attr( $_value ),
											in_array( $_value, $values ) ? ' checked' : '',
											esc_html( $_label ),
											$count >= $hideAbleCount ? ' hideAble' : '',
										);
									}
									if ( $count >= $hideAbleCount ) {
										$field_html .= '<div class="rtcl-more-less-btn">
													<div class="text more-text"><i class="rtcl-icon rtcl-icon-plus"></i>' . __( 'More', 'clplace' ) . '</div>
													<div class="text less-text"><i class="rtcl-icon rtcl-icon-minus"></i>' . __( 'Less', 'clplace' ) . '</div>
												</div>';
									}
									$field_html .= '</div>';
								}
							}
							$options = [ 'name' => $filterName, 'field_type' => $field->getElement() ];
							$html = apply_filters( 'rtcl_ajax_filter_cf_html',
								sprintf( '<div class="rtcl-ajax-filter-item rtcl-ajax-filter-cf-item is-open rtcl-filter_%1$s%2$s" data-cf-id="%1$s">
									                <div class="rtcl-filter-title-wrap">
									                    <div class="rtcl-filter-title">%3$s<span class="rtcl-reset rtcl-icon rtcl-icon-cw mm"></span></div>
									                    <i class="rtcl-icon rtcl-icon-angle-down"></i>
									                </div>
									                <div class="rtcl-filter-content" data-options="%4$s">%5$s</div>
									            </div>',
									$filterName,
									$isActive ? ' is-active' : '',
									$field->getLabel(),
									htmlspecialchars( wp_json_encode( $options ) ),
									$field_html
								),
								$field_html,
								$field
							);
						}
					}

					Functions::print_html( $field_html, true );

					if ( $can_search_by_price ): ?>
                        <div class="search-item price-wrapper">
                            <div class="price-range">
                                <label><?php esc_html_e( 'Price', 'clplace' ); ?></label>
								<?php
								$data_form = '';
								$data_to   = '';
								if ( isset( $_GET['filters']['price']['min'] ) ) {
									$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
								}
								if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
									$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
								}

								global $rtclmcData;
								if ( ! is_array( $rtclmcData ) && class_exists( 'RtclMc_Frontend_Price_Filters' ) ) {
									$RtclMc_Frontend_Price_Filters = \RtclMc_Frontend_Price_Filters::instance();
									$rtclmcData                    = $RtclMc_Frontend_Price_Filters->getCurrencyData();
								}

								$currency_symbol = Functions::get_currency_symbol();
								if ( isset( $rtclmcData['currency'] ) && ! empty( $rtclmcData['currency'] ) ) {
									$currency_symbol = Functions::get_currency_symbol( $rtclmcData['currency'] );
								}

								$currency_pos           = Functions::get_option_item( 'rtcl_general_currency_settings', 'currency_position', 'left' );
								$data_currency_position = ! empty( $currency_symbol ) ? sprintf( "data-prefix=%s", $currency_symbol ) : '';

								if ( in_array( $currency_pos, [ 'right', 'right_space' ] ) ) {
									$data_currency_position = sprintf( "data-postfix=%s", $currency_symbol );
								}
								?>
                                <input type="number" class="ion-rangeslider"
									<?php echo esc_attr( $data_form ); ?>
									<?php echo esc_attr( $data_to ); ?>
									<?php echo esc_attr( $data_currency_position ); ?>
                                       data-min="<?php echo esc_attr( $_min_price ) ?>"
                                       data-max="<?php echo esc_attr( $_max_price ) ?>"
                                />
                                <input type="hidden" class="min-volumn" name="filters[price][min]"
                                       value="<?php if ( isset( $_GET['filters']['price']['min'] ) ) {
									       echo absint( $_GET['filters']['price']['min'] );
								       } ?>">
                                <input type="hidden" class="max-volumn" name="filters[price][max]"
                                       value="<?php if ( isset( $_GET['filters']['price']['max'] ) ) {
									       echo absint( $_GET['filters']['price']['max'] );
								       } ?>">
                            </div>
                        </div>
					<?php endif; ?>
                </div>
            </div>
		<?php endif; ?>
    </form>
</div>