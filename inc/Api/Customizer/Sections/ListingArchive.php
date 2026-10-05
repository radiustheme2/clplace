<?php
/**
 * Theme Customizer - Listing Archive
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer\Sections;

use RTFramework\Customize;
use RT\Clplace\Helpers\Fns;
use RT\Clplace\Api\Customizer;

/**
 * Customizer class
 */
class ListingArchive extends Customizer {

	protected string $section_listing_archive = 'clplace_listing_archive_section';


	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_listing_archive,
			'title'       => __( 'Archive', 'clplace' ),
			'description' => __( 'Clplace Listing Section', 'clplace' ),
			'priority'    => 1,
			'panel' => 'rt_listing_panel',
		] );

		Customize::add_controls( $this->section_listing_archive, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clplace_listing_archive_controls', [

			'rt_listing_archive_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Choose Layout', 'clplace' ),
				'default'   => '1',
				'choices'   => Fns::image_placeholder( 'listing-archive', 1)
			],

			'rt_listing_excerpt' => [
				'type'        => 'number',
				'label'       => __( 'Listing Excerpt', 'clplace' ),
				'default'     => 16,
				'description' => __( 'This excerpt work only in listing list layout.', 'clplace' ),
			],

			'rt_listing_archive_column' => [
				'type'        => 'select',
				'label'       => __( 'Grid Column', 'clplace' ),
				'description' => __( 'This option works only for large device', 'clplace' ),
				'default'     => '2',
				'choices'     => [
					'default'   => __( 'Default From Theme', 'clplace' ),
					'1' => __( '1 Column', 'clplace' ),
					'2'  => __( '2 Column', 'clplace' ),
					'3'  => __( '3 Column', 'clplace' ),
					'4'  => __( '4 Column', 'clplace' ),
				]
			],

		] );
	}


}
