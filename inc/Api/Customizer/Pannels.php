<?php
/**
 * Theme Customizer Pannels
 *
 * @package clplace
 */

namespace RT\Clplace\Api\Customizer;

use RT\Clplace\Traits\SingletonTraits;
use RTFramework\Customize;

/**
 * Customizer class
 */
class Pannels {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		$this->add_panels();
	}

	/**
	 * Add Panels
	 * @return void
	 */
	public function add_panels() {
		Customize::add_panels(
			[
				[
					'id'          => 'rt_header_panel',
					'title'       => esc_html__( 'Header - Topbar - Menu', 'clplace' ),
					'description' => esc_html__( 'Clplace Header', 'clplace' ),
					'priority'    => 22,
				],
				[
					'id'          => 'rt_typography_panel',
					'title'       => esc_html__( 'Typography', 'clplace' ),
					'description' => esc_html__( 'Clplace Typography', 'clplace' ),
					'priority'    => 24,
				],
				[
					'id'          => 'rt_color_panel',
					'title'       => esc_html__( 'Colors', 'clplace' ),
					'description' => esc_html__( 'Clplace Color Settings', 'clplace' ),
					'priority'    => 28,
				],
				[
					'id'          => 'rt_layouts_panel',
					'title'       => esc_html__( 'Layout Settings', 'clplace' ),
					'description' => esc_html__( 'Clplace Layout Settings', 'clplace' ),
					'priority'    => 34,
				],
				[
					'id'          => 'rt_listing_panel',
					'title'       => esc_html__( 'Listing Settings', 'clplace' ),
					'description' => esc_html__( 'Clplace Listing Settings', 'clplace' ),
					'priority'    => 35,
				],
				[
					'id'          => 'rt_contact_social_panel',
					'title'       => esc_html__( 'Contact & Socials', 'clplace' ),
					'description' => esc_html__( 'Clplace Contact & Socials', 'clplace' ),
					'priority'    => 24,
				],

			]
		);
	}

}
