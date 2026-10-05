<?php
/**
 * Theme Customizer - Menu Typography
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RT\Clplace\Api\Customizer;
use RTFramework\Customize;

/**
 * Customizer class
 */
class TypographyMenu extends Customizer {

	protected string $section_id = 'clplace_menu_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Menu Typography', 'clplace' ),
			'description' => __( 'Clplace Menu Typography Section', 'clplace' ),
			'panel'       => 'rt_typography_panel',
			'priority'    => 3
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_menu_typo_section', [

			'rt_menu_typo' => [
				'type'    => 'typography',
				'label'   => __( 'Menu Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '500',
						'size'          => '16',
						'lineheight'    => '22',
					]
				)
			],

		] );

	}

}
