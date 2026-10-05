<?php
/**
 * Theme Customizer - Body Typography
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RT\Clplace\Api\Customizer;
use RTFramework\Customize;

/**
 * Customizer class
 */
class TypographyBody extends Customizer {

	protected string $section_id = 'clplace_body_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Body Typography', 'clplace' ),
			'description' => __( 'Clplace Body Typography Section', 'clplace' ),
			'panel'       => 'rt_typography_panel',
			'priority'    => 1
		] );
		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clplace_body_typo_section', [
			'rt_body_typo' => [
				'type'    => 'typography',
				'label'   => __( 'Body Typography', 'clplace' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => 'normal',
						'size'          => '16',
						'lineheight'    => '26',
					]
				)
			],
		] );
	}

}
