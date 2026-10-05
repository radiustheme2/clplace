<?php
/**
 * Theme Customizer - Header
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RT\Clplace\Api\Customizer;
use RT\Clplace\Helpers\Fns;
use RTFramework\Customize;

/**
 * Customizer class
 */
class Banner extends Customizer {

	protected string $section_breadcrumb = 'clplace_breadcrumb_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_breadcrumb,
			'title'       => __( 'Banner - Breadcrumb', 'clplace' ),
			'description' => __( 'Clplace Banner Section', 'clplace' ),
			'priority'    => 23
		] );

		Customize::add_controls( $this->section_breadcrumb, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_topbar_controls', [

			'rt_banner' => [
				'type'    => 'switch',
				'label'   => __( 'Banner Visibility', 'clplace' ),
				'default'   => '1',
			],

			'rt_banner_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Breadcrumb Style', 'clplace' ),
				'default'   => '1',
				'choices'   => Fns::image_placeholder( 'banner', 1 ),
				'condition' => [ 'rt_banner' ]
			],

			'rt_banner_image' => [
				'type'         => 'image',
				'label'        => __( 'Banner Image', 'clplace' ),
				'description'  => __( 'Upload Banner Image', 'clplace' ),
				'button_label' => __( 'Banner', 'clplace' ),
				'condition'    => [ 'rt_banner' ]
			],

			'rt_banner_image_attr' => [
				'type'      => 'bg_attribute',
				'condition' => [ 'rt_banner' ],
				'default'   => json_encode(
					[
						'position'   => 'center center',
						'attachment' => 'scroll',
						'repeat'     => 'no-repeat',
						'size'       => 'cover',
					]
				)
			],

			'rt_banner_height' => [
				'type'        => 'number',
				'label'       => __( 'Banner Height (px)', 'clplace' ),
				'description' => __( 'Height can be differ for transparent header.', 'clplace' ),
				'default'     => '',
				'condition'   => [ 'rt_banner' ]
			],

			'rt_banner_content_alignment' => [
				'type'      => 'image_select',
				'label'     => __( 'Content Alignment', 'clplace' ),
				'default'   => '1',
				'choices'     => [
					'align-items-start'  => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/align-left.svg',
						'name'  => esc_html__( 'Left', 'clplace' ),
					],
					'align-items-center' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/align-center.svg',
						'name'  => esc_html__( 'Center', 'clplace' ),
					],
					'align-items-end' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/align-right.svg',
						'name'  => esc_html__( 'Right', 'clplace' ),
					],
				],
				'condition' => [ 'rt_banner' ]
			],

			'rt_banner_shape_image' => [
				'type'         => 'image',
				'label'        => __( 'Banner Shape Image', 'clplace' ),
				'description'  => __( 'Upload Shape Image', 'clplace' ),
				'button_label' => __( 'Banner Shape Image', 'clplace' ),
				'condition'    => [ 'rt_banner' ]
			],

			'rt_banner1' => [
				'type'      => 'heading',
				'label'     => __( 'Breadcrumb Settings', 'clplace' ),
				'condition' => [ 'rt_banner' ]
			],

			'rt_breadcrumb' => [
				'type'      => 'switch',
				'label'     => __( 'Banner Content (Breadcrumb) Visibility', 'clplace' ),
				'default'   => 1,
				'condition' => [ 'rt_banner' ]
			],

			'rt_breadcrumb_border' => [
				'type'      => 'switch',
				'label'     => __( 'Breadcrumb Border', 'clplace' ),
				'default'   => 1,
				'condition' => [ 'rt_banner' ]
			],

		] );
	}
}
