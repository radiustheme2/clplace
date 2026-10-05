<?php
/**
 * Theme Customizer - Listing Single
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RTFramework\Customize;
use RT\Clplace\Api\Customizer;

/**
 * Customizer class
 */
class ListingSingle extends Customizer {

	protected string $section_listing_single_archive = 'clplace_listing_single_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_listing_single_archive,
			'title'       => __( 'Single', 'clplace' ),
			'description' => __( 'Clplace Listing Single Section', 'clplace' ),
			'priority'    => 2,
			'panel' => 'rt_listing_panel',
		] );

		Customize::add_controls( $this->section_listing_single_archive, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clplace_listing_single_controls', [

			'rt_listing_single_style' => [
				'type'        => 'select',
				'label'       => __( 'Select Layout', 'clplace' ),
				'placeholder' => __( 'Choose Layout', 'clplace' ),
				'multiselect' => true,
				'default'     => '1',
				'choices'     => [
					'1' => __( 'Layout 1', 'clplace' ),
					'2' => __( 'Layout 2', 'clplace' ),
				],
			],
			'single_sidebar_listing_info' => [
				'type'        => 'select',
				'label'       => __( 'Select Info Type', 'clplace' ),
				'placeholder' => __( 'Choose Info Type', 'clplace' ),
				'multiselect' => true,
				'default'     => '1',
				'choices'     => [
					'listing_info' => esc_html__( 'Listing Information', 'clplace' ),
					'listing_owner_info' => esc_html__( 'Listing Owner Information', 'clplace' ),
				],
			],

			'rt_visibility' => [
				'type'  => 'heading',
				'label' => __( 'Visibility Section', 'clplace' ),
			],

			'rt_listing_video_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Video Visibility', 'clplace' ),
				'default' => 1,
				'desc' => esc_html__('If enable video display in content and videe hide form gallery.', 'clplace'),
			],

			'rt_listing_video_title' => [
				'type'    => 'switch',
				'label'   => __( 'Video Title', 'clplace' ),
				'default' => 'Video',
			],

			'rt_related' => [
				'type'  => 'heading',
				'label' => __( 'Related Listing', 'clplace' ),
			],

			'rt_related_listing_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Related Listing Visibility', 'clplace' ),
				'default' => 1
			],

			'rt_related_listing_cat_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Category Visibility', 'clplace' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_author_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Author Visibility', 'clplace' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_location_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Location Visibility', 'clplace' ),
				'default' => 1,
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_time_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Time Visibility', 'clplace' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_views_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Views Visibility', 'clplace' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],

		] );
	}

}
