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
class ColorTopbar extends Customizer {
	protected string $section_topbar_color = 'clplace_topbar_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_topbar_color,
			'panel'       => 'rt_color_panel',
			'title'       => __( 'Topbar Colors', 'clplace' ),
			'description' => __( 'Clplace Topbar Color Section', 'clplace' ),
			'priority'    => 3
		] );

		Customize::add_controls( $this->section_topbar_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_header_color_controls', [

			'rt_topbar_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Topbar Color', 'clplace' ),
			],

			'rt_topbar_active_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Hover & Active Color', 'clplace' ),
			],

			'rt_topbar_bg_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Topbar Background', 'clplace' ),
			],

		] );


	}

}
