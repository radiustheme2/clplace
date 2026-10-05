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
class Labels extends Customizer {
	protected string $section_labels = 'clplace_labels_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_labels,
			'title'       => __( 'Modify Static Text', 'clplace' ),
			'description' => __( 'You can change all static text of the theme.', 'clplace' ),
			'priority'    => 999
		] );
		Customize::add_controls( $this->section_labels, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clplace_labels_controls', [

			'rt_header_labels' => [
				'type'  => 'heading',
				'label' => __( 'Header Labels', 'clplace' ),
			],

			'rt_login_signup_label' => [
				'type'        => 'text',
				'label'       => __( 'Login / Sign Up', 'clplace' ),
				'default'     => __( 'Login / Sign Up', 'clplace' ),
				'description' => __( 'Context: Login Button (logged out)', 'clplace' ),
			],

			'rt_my_account_label' => [
				'type'        => 'text',
				'label'       => __( 'My Account', 'clplace' ),
				'default'     => __( 'My Account', 'clplace' ),
				'description' => __( 'Context: Login Button (logged in)', 'clplace' ),
			],

			'rt_get_started_label' => [
				'type'        => 'text',
				'label'       => __( 'Get Started', 'clplace' ),
				'default'     => __( 'Get Started', 'clplace' ),
				'description' => __( 'Context: Menu Button', 'clplace' ),
			],

			'rt_follow_us_label' => [
				'type'        => 'text',
				'label'       => __( 'Follow Us On:', 'clplace' ),
				'default'     => __( 'Follow Us On:', 'clplace' ),
				'description' => __( 'Context: Topbar icon label', 'clplace' ),
			],

			'rt_blog_labels'          => [
				'type'  => 'heading',
				'label' => __( 'Blog Labels', 'clplace' ),
			],
			'rt_author_prefix' => [
				'type'        => 'text',
				'label'       => __( 'By', 'clplace' ),
				'default'     => 'by',
				'description' => __( 'Context: Meta Author Prefix', 'clplace' ),
			],
			'rt_tags'                 => [
				'type'        => 'text',
				'label'       => __( 'Tags:', 'clplace' ),
				'default'     => __( 'Tags:', 'clplace' ),
				'description' => __( 'Context: Single blog footer tags label', 'clplace' ),
			],
			'rt_share'                 => [
				'type'        => 'text',
				'label'       => __( 'Share:', 'clplace' ),
				'default'     => __( 'Share:', 'clplace' ),
				'description' => __( 'Context: Single blog footer share label', 'clplace' ),
			],

		] );
	}

}
