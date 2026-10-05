<?php
/**
 * Theme Customizer - Heading Typography
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RT\Clplace\Api\Customizer;
use RTFramework\Customize;

/**
 * Customizer class
 */
class TypographyHeading extends Customizer {

	protected string $section_id = 'clplace_heading_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Heading Typography', 'clplace' ),
			'description' => __( 'Clplace Heading Typography Section', 'clplace' ),
			'panel'       => 'rt_typography_panel',
			'priority'    => 2
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_heading_typo_section', [

			'rt_all_heading_typo' => [
				'type'    => 'typography',
				'label'   => __( 'All Headings Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '700',
						'size'          => '48',
						'lineheight'    => '58',
					]
				)
			],

			'rt_heading_h1_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H1 Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'rt_heading_h2_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H2 Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'rt_heading_h3_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H3 Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '600',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'rt_heading_h4_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H4 Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'rt_heading_h5_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H5 Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'rt_heading_h6_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H6 Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

		] );

	}

}
