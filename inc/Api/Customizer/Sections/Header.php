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
class Header extends Customizer {
	protected string $section_header = 'clplace_header_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_header,
			'panel'       => 'rt_header_panel',
			'title'       => __( 'Header Menu', 'clplace' ),
			'description' => __( 'Clplace Header Section', 'clplace' ),
			'priority'    => 2,
			'edit-point'  => ''
		] );
		Customize::add_controls( $this->section_header, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_header_controls', [

			'rt_header_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Choose Layout', 'clplace' ),
				'default'   => '1',
				'edit-link' => '.site-branding',
				'choices'   => Fns::image_placeholder( 'header', 1)
			],

			'rt_menu_alignment' => [
				'type'    => 'select',
				'label'   => __( 'Menu Alignment', 'clplace' ),
				'default' => '',
				'choices' => [
					''                       => __( 'Menu Alignment', 'clplace' ),
					'justify-content-start'  => __( 'Left Alignment', 'clplace' ),
					'justify-content-center' => __( 'Center Alignment', 'clplace' ),
					'justify-content-end'    => __( 'Right Alignment', 'clplace' ),
				]
			],

			'rt_header_width' => [
				'type'    => 'select',
				'label'   => __( 'Header Width', 'clplace' ),
				'default' => '',
				'choices' => [
					''       => __( 'Box Width', 'clplace' ),
					'-fluid' => __( 'Full Width', 'clplace' ),
				]
			],

			'rt_header_max_width' => [
				'type'        => 'number',
				'label'       => __( 'Header Max Width (PX)', 'clplace' ),
				'description' => __( 'Enter a number greater than 1440. Remove value for 100%', 'clplace' ),
				'condition'   => [ 'rt_header_width', '==', '-fluid' ]
			],

			'rt_sticy_header' => [
				'type'        => 'switch',
				'label'       => __( 'Sticky Header', 'clplace' ),
				'description' => __( 'Show header at the top when scrolling down', 'clplace' ),
			],

			'rt_tr_header' => [
				'type'  => 'switch',
				'label' => __( 'Transparent Header', 'clplace' ),
			],

			'rt_tr_header_color' => [
				'type'    => 'select',
				'label'   => __( 'Transparent color', 'clplace' ),
				'default' => 'tr_header_light',
				'choices' => [
					'tr-header-light'       => __( 'Light Color', 'clplace' ),
					'tr-header-dark' => __( 'Dark Color', 'clplace' ),
				],
				'condition' => [ 'rt_tr_header' ]
			],

			'rt_tr_header_shadow' => [
				'type'  => 'switch',
				'label' => __( 'Header Dark Shadow', 'clplace' ),
			],

			'rt_header_border' => [
				'type'    => 'switch',
				'label'   => __( 'Header Border', 'clplace' ),
				'default' => 1
			],
			'rt_header_sep1'   => [
				'type' => 'separator',
				'edit-link' => '.menu-icon-wrapper',
			],

			'rt_header_login_button' => [
				'type'    => 'switch',
				'label'   => __( 'User Login ?', 'clplace' ),
				'default' => '',
			],

			'rt_header_login_link' => [
				'type'    => 'text',
				'label'   => __( 'Login/Register Link', 'clplace' ),
				'condition' => [ 'rt_header_login_button' ]
			],

			'rt_header_search' => [
				'type'    => 'switch',
				'label'   => __( 'Search Icon ?', 'clplace' ),
				'default' => '',
			],

			'rt_header_bar' => [
				'type'        => 'switch',
				'label'       => __( 'Hamburger Menu', 'clplace' ),
				'description' => __( 'It will be hide only for desktop.', 'clplace' ),
				'default'     => '',
			],

			'rt_header_separator' => [
				'type'    => 'switch',
				'label'   => __( 'Icon Separator', 'clplace' ),
				'default' => 1,
			],

			'rt_header_sep2' => [
				'type' => 'separator',
			],

			'rt_get_started_button' => [
				'type'    => 'switch',
				'label'   => __( 'Get Started Button ?', 'clplace' ),
				'default' => ''
			],

			'rt_get_started_button_url' => [
				'type'    => 'text',
				'label'   => __( 'Button Link', 'clplace' ),
				'condition' => [ 'rt_get_started_button' ]
			],

		] );

	}

}
