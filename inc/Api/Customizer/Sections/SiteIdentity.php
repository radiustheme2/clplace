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
class SiteIdentity extends Customizer {

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_controls( 'title_tagline', $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_title_tagline_controls', [

			'rt_logo' => [
				'type'         => 'image',
				'label'        => __( 'Main Logo', 'clplace' ),
				'description'  => __( 'Upload main logo for your site.', 'clplace' ),
				'button_label' => __( 'Logo', 'clplace' ),
			],

			'rt_logo_light' => [
				'type'         => 'image',
				'label'        => __( 'Light Logo', 'clplace' ),
				'description'  => __( 'Upload light logo for transparent header. It should a white logo', 'clplace' ),
				'button_label' => __( 'Light Logo', 'clplace' ),
			],

			'rt_logo_mobile' => [
				'type'         => 'image',
				'label'        => __( 'Mobile Logo', 'clplace' ),
				'description'  => __( 'Upload, if you need a different logo for mobile device..', 'clplace' ),
				'button_label' => __( 'Mobile Logo', 'clplace' ),
			],

			'rt_logo_offcanvas' => [
				'type'         => 'image',
				'label'        => __( 'Offcanvas Logo', 'clplace' ),
				'description'  => __( 'Upload, if you need a different logo for offcanvas slide menu.', 'clplace' ),
				'button_label' => __( 'Offcanvas Logo', 'clplace' ),
			],

			'rt_logo_width_height' => [
				'type'      => 'text',
				'label'     => __( 'Logo Dimension', 'clplace' ),
				'description'     => __( 'Enter the width and height value separate by comma (,). Eg. 180px,45px', 'clplace' ),
				'transport' => '',
			],

		] );

	}

}
