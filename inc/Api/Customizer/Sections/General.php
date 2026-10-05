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
class General extends Customizer {
	protected string $section_general = 'clplace_general_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_general,
			'title'       => __( 'General', 'clplace' ),
			'description' => __( 'Clplace General Section', 'clplace' ),
			'priority'    => 20
		] );
		Customize::add_controls( $this->section_general, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_general_controls', [

			'rt_svg_enable' => [
				'type'  => 'switch',
				'label' => __( 'Enable SVG Upload', 'clplace' ),
				'default' => 1,
			],

			'rt_preloader' => [
				'type'  => 'switch',
				'label' => __( 'Preloader', 'clplace' ),
			],

			'rt_preloader_image' => [
				'type'         => 'image',
				'label'        => __( 'Preloader Image', 'clplace' ),
				'description'  => __( 'Upload preloader animate image for your site.', 'clplace' ),
				'button_label' => __( 'Upload', 'clplace' ),
				'condition'    => [ 'rt_preloader' ]
			],

			'rt_back_to_top' => [
				'type'  => 'switch',
				'label' => __( 'Back to Top', 'clplace' ),
			],

			'rt_remove_admin_bar' => [
				'type'        => 'switch',
				'label'       => __( 'Remove Admin Bar', 'clplace' ),
				'description' => __( 'This option not work for administrator role.', 'clplace' ),
			],

			'rt_social_icon_style' => [
				'type'    => 'select',
				'label'   => __( 'Social Icon Style', 'clplace' ),
				'default' => '',
				'choices' => [
					''        => __( 'Default Icon', 'clplace' ),
					'-square' => __( 'Square Icon', 'clplace' ),
				]
			],

		] );

	}

}
