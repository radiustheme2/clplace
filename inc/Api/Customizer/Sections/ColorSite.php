<?php
/**
 * Theme Customizer - Header
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RT\Clplace\Api\Customizer;
use RTFramework\Customize;

/**
 * Customizer class
 */
class ColorSite extends Customizer {
	protected string $section_site_color = 'clplace_site_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_site_color,
			'panel'       => 'rt_color_panel',
			'title'       => __( 'Site Colors', 'clplace' ),
			'description' => __( 'Clplace Site Color Section', 'clplace' ),
			'priority'    => 2
		] );
		Customize::add_controls( $this->section_site_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_site_color_controls', [

			'rt_site_color1'   => [
				'type'  => 'heading',
				'label' => __( 'Site Ascent Color', 'clplace' ),
			],
			'rt_primary_color' => [
				'type'    => 'color',
				'label'   => __( 'Primary Color', 'clplace' ),
			],

			'rt_primary_dark_color' => [
				'type'    => 'color',
				'label'   => __( 'Primary Dark Color', 'clplace' ),
			],

			'rt_primary_light_color' => [
				'type'    => 'color',
				'label'   => __( 'Primary Light Color', 'clplace' ),
			],

			'rt_color_separator2' => [
				'type' => 'separator',
			],

			'rt_secondary_color' => [
				'type'    => 'color',
				'label'   => __( 'Secondary Color', 'clplace' ),
			],

			'rt_site_color2' => [
				'type'  => 'heading',
				'label' => __( 'Others Color', 'clplace' ),
			],

			'rt_body_color' => [
				'type'    => 'color',
				'label'   => __( 'Body Color', 'clplace' ),
			],

			'rt_title_color' => [
				'type'    => 'color',
				'label'   => __( 'Title Color', 'clplace' ),
			],

			'rt_meta_color' => [
				'type'    => 'color',
				'label'   => __( 'Meta Color', 'clplace' ),
			],

			'rt_meta_light' => [
				'type'    => 'color',
				'label'   => __( 'Meta Light', 'clplace' ),
			],

			'rt_gray20_color' => [
				'type'    => 'color',
				'label'   => __( 'Gray # 1', 'clplace' ),
			],

			'rt_gray40_color' => [
				'type'    => 'color',
				'label'   => __( 'Gray # 2', 'clplace' ),
			],

		] );
	}
}
