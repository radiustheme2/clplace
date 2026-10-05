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
class Footer extends Customizer {
	protected string $section_footer = 'clplace_footer_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_footer,
			'title'       => __( 'Footer', 'clplace' ),
			'description' => __( 'Clplace Footer Section', 'clplace' ),
			'priority'    => 38
		] );

		Customize::add_controls( $this->section_footer, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_footer_controls', [

			'rt_footer_style' => [
				'type'    => 'image_select',
				'label'   => __( 'Choose Layout', 'clplace' ),
				'default' => '1',
				'choices' => Fns::image_placeholder( 'footer', 2, 'jpg' )
			],

			'rt_footer_width' => [
				'type'    => 'select',
				'label'   => __( 'Footer Width', 'clplace' ),
				'default' => '',
				'choices' => [
					''       => __( 'Box Width', 'clplace' ),
					'-fluid' => __( 'Full Width', 'clplace' ),
				]
			],

			'rt_footer_max_width' => [
				'type'        => 'number',
				'label'       => __( 'Footer Max Width (PX)', 'clplace' ),
				'description' => __( 'Enter a number greater than 992.', 'clplace' ),
				'condition'   => [ 'rt_footer_width', '==', '-fluid' ]
			],

			'rt_sticy_footer' => [
				'type'        => 'switch',
				'label'       => __( 'Sticky Footer', 'clplace' ),
				'description' => __( 'Show footer at the top when scrolling down', 'clplace' ),
			],

			'rt_footer_heading1' => [
				'type'  => 'heading',
				'label' => __( 'Footer Copyright Section', 'clplace' ),
			],

			'rt_footer_copyright' => [
				'type'        => 'tinymce',
				'label'       => __( 'Footer Copyright Text', 'clplace' ),
				'default'     => __( 'Copyright© [y] Clplace by <a href="https://radiustheme.com/">RadiusTheme</a>', 'clplace' ),
				'description' => __( 'Add [y] flag anywhere for dynamic year.', 'clplace' ),
			],
			'rt_footer_social' => [
				'type'        => 'switch',
				'label'       => __( 'Footer Social', 'clplace' ),
				'description' => __( 'Show footer social beside at copyright text', 'clplace' ),
			],
			'rt_footer_copyright_alignment' => [
				'type'    => 'select',
				'label'   => __( 'Copyright & Socials Alignment', 'clplace' ),
				'default' => 'align-default',
				'choices' => [
					'align-default'          => __( 'Default from style', 'clplace' ),
					'justify-content-start'  => __( 'Left', 'clplace' ),
					'justify-content-center' => __( 'Center', 'clplace' ),
					'justify-content-end'    => __( 'Right', 'clplace' ),
					'justify-content-between'  => __( 'Between', 'clplace' ),
					'justify-content-around'  => __( 'Around', 'clplace' ),
				],
			],
			'rt_footer_menu' => [
				'type'        => 'switch',
				'label'       => __( 'Footer menu', 'clplace' ),
				'description' => __( 'Show footer menu above at copyright', 'clplace' ),
				'condition'   => [ 'rt_footer_style', '==', '2' ]
			],
			'rt_footer_menu_alignment' => [
				'type'    => 'select',
				'label'   => __( 'Footer Menu Alignment', 'clplace' ),
				'default' => 'align-default',
				'choices' => [
					'align-default'          => __( 'Default from style', 'clplace' ),
					'justify-content-start'  => __( 'Left', 'clplace' ),
					'justify-content-center' => __( 'Center', 'clplace' ),
					'justify-content-end'    => __( 'Right', 'clplace' ),
				],
				'condition'   => [ 'rt_footer_style', '==', '2' ]
			],

		] );

	}


}
