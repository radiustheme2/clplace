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
class Contact extends Customizer {
	protected string $section_contact = 'clplace_contact_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_contact,
			'panel'       => 'rt_contact_social_panel',
			'title'       => __( 'Contact Information', 'clplace' ),
			'description' => __( 'Clplace Contact Address Section', 'clplace' ),
			'priority'    => 1
		] );
		Customize::add_controls( $this->section_contact, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_contact_controls', [

			'rt_phone' => [
				'type'  => 'text',
				'label' => __( 'Phone', 'clplace' ),
			],

			'rt_email' => [
				'type'  => 'text',
				'label' => __( 'Email', 'clplace' ),
			],

			'rt_website' => [
				'type'  => 'text',
				'label' => __( 'Website', 'clplace' ),
			],

			'rt_contact_address' => [
				'type'        => 'textarea',
				'label'       => __( 'Address', 'clplace' ),
				'description' => __( 'Enter company address here.', 'clplace' ),
			],

		] );
	}
}
