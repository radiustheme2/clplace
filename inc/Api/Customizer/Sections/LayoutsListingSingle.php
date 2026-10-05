<?php
/**
 * Theme Customizer - Header
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RTFramework\Customize;
use RT\Clplace\Helpers\Fns;
use RT\Clplace\Api\Customizer;
use RT\Clplace\Traits\LayoutControlsTraits;

/**
 * Customizer class
 */
class LayoutsListingSingle extends Customizer {

	use LayoutControlsTraits;

	protected string $section_page_layout = 'clplace_listing_single_layout_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'    => $this->section_page_layout,
			'title' => __( 'Listing Single Layout', 'clplace' ),
			'panel' => 'rt_layouts_panel',
		] );

		Customize::add_controls( $this->section_page_layout, $this->get_controls() );
	}

	public function get_controls() {
		$prefix = 'listing_single';
		return apply_filters( "clplace_{$prefix}_layout_controls", [

			$prefix . '_sidebar' => [
				'type'    => 'select',
				'label'   => __( 'Choose a Sidebar', 'clplace' ),
				'default' => 'default',
				'choices' => Fns::sidebar_lists()
			],

			$prefix . '_header_heading' => [
				'type'  => 'heading',
				'label' => __( 'Header Settings', 'clplace' ),
			],

			$prefix . '_header_style' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Header Layout', 'clplace' ),
				'choices' => [
					'default' => __( '--Default--', 'clplace' ),
					'1'       => __( 'Layout 1', 'clplace' ),
					'2'       => __( 'Layout 2', 'clplace' ),
					'3'       => __( 'Layout 3', 'clplace' ),
				],
			],

			$prefix . '_top_bar' => [
				'type'    => 'select',
				'label'   => __( 'Top Bar', 'clplace' ),
				'default' => 'default',
				'choices' => [
					'default' => __( '--Default--', 'clplace' ),
					'on'      => __( 'On', 'clplace' ),
					'off'     => __( 'Off', 'clplace' ),
				]
			],

			$prefix . '_banner_heading' => [
				'type'  => 'heading',
				'label' => __( 'Banner Settings', 'clplace' ),
			],

			$prefix . '_banner' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Visibility', 'clplace' ),
				'choices' => [
					'default' => __( '--Default--', 'clplace' ),
					'on'      => __( 'On', 'clplace' ),
					'off'     => __( 'Off', 'clplace' ),
				],
			],

			$prefix . '_banner_style' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Layout', 'clplace' ),
				'choices' => [
					'default' => __( '--Default--', 'clplace' ),
					'1'       => __( 'Layout 1', 'clplace' ),
					'2'       => __( 'Layout 2', 'clplace' ),
				],
			],

			$prefix . '_breadcrumb' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Content (Breadcrumb) Visibility', 'clplace' ),
				'choices' => [
					'default' => __( '--Default--', 'clplace' ),
					'on'      => __( 'On', 'clplace' ),
					'off'     => __( 'Off', 'clplace' ),
				],
			],

			$prefix . '_banner_image' => [
				'type'         => 'image',
				'label'        => __( 'Banner Image', 'clplace' ),
				'description'  => __( 'Upload Banner Image', 'clplace' ),
				'button_label' => __( 'Banner Image', 'clplace' ),
			],

			$prefix . '_footer_heading' => [
				'type'  => 'heading',
				'label' => __( 'Footer Settings', 'clplace' ),
			],

			$prefix . '_footer_style'  => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Footer Layout', 'clplace' ),
				'choices' => [
					'default' => __( '--Default--', 'clplace' ),
					'1'       => __( 'Layout 1', 'clplace' ),
					'2'       => __( 'Layout 2', 'clplace' ),
				],
			],
		] );
	}
}
