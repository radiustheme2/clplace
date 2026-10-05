<?php
/**
 * LayoutControls
 */

namespace RT\Clplace\Traits;

// Do not allow directly accessing this file.
use RT\Clplace\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

trait LayoutControlsTraits {
	public function get_layout_controls( $prefix = '' ) {

		$_left_text  = __( 'Left Sidebar', 'clplace' );
		$_right_text = __( 'Right Sidebar', 'clplace' );
		$left_text   = $_left_text;
		$right_text  = $_right_text;
		$image_left  = 'sidebar-left.png';
		$image_right = 'sidebar-right.png';

		if ( is_rtl() ) {
			$left_text   = $_right_text;
			$right_text  = $_left_text;
			$image_left  = 'sidebar-right.png';
			$image_right = 'sidebar-left.png';
		}

		return apply_filters( "clplace_{$prefix}_layout_controls", [

			$prefix . '_layout' => [
				'type'    => 'image_select',
				'label'   => __( 'Choose Layout', 'clplace' ),
				'default' => 'right-sidebar',
				'choices' => [
					'left-sidebar'  => [
						'image' => clplace_get_img( $image_left ),
						'name'  => $left_text,
					],
					'full-width'    => [
						'image' => clplace_get_img( 'sidebar-full.png' ),
						'name'  => __( 'Full Width', 'clplace' ),
					],
					'right-sidebar' => [
						'image' => clplace_get_img( $image_right ),
						'name'  => $right_text,
					],
				]
			],

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
