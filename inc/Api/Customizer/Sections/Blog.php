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
class Blog extends Customizer {

	protected string $section_blog = 'clplace_blog_section';


	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_blog,
			'title'       => __( 'Blog Archive', 'clplace' ),
			'description' => __( 'Clplace Blog Section', 'clplace' ),
			'priority'    => 25
		] );

		Customize::add_controls( $this->section_blog, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clplace_blog_controls', [

			'rt_blog_style' => [
				'type'        => 'select',
				'label'       => __( 'Blog Style', 'clplace' ),
				'description' => __( 'This option works only for large device', 'clplace' ),
				'default'     => 'default',
				'choices'     => [
					'default' => __( 'Default From Theme', 'clplace' ),
					'list-1'    => __( 'List', 'clplace' ),
					'grid-1'    => __( 'Grid', 'clplace' ),
				]
			],

			'rt_blog_column' => [
				'type'        => 'select',
				'label'       => __( 'Grid Column', 'clplace' ),
				'description' => __( 'This option works only for large device', 'clplace' ),
				'default'     => 'default',
				'choices'     => [
					'default'   => __( 'Default From Theme', 'clplace' ),
					'col-lg-12' => __( '1 Column', 'clplace' ),
					'col-lg-6'  => __( '2 Column', 'clplace' ),
					'col-lg-4'  => __( '3 Column', 'clplace' ),
					'col-lg-3'  => __( '4 Column', 'clplace' ),
				]
			],


			'rt_excerpt_limit' => [
				'type'    => 'text',
				'label'   => __( 'Content Limit', 'clplace' ),
				'default' => '30',
			],

			'rt_meta_heading' => [
				'type'  => 'heading',
				'label' => __( 'Post Meta Settings', 'clplace' ),
			],

			'rt_blog_meta_style' => [
				'type'    => 'select',
				'label'   => __( 'Meta Style', 'clplace' ),
				'default' => 'meta-style-default',
				'choices' => Fns::meta_style()
			],

			'rt_single_above_meta_style' => [
				'type'    => 'select',
				'label'   => __( 'Title Above Meta Style', 'clplace' ),
				'default' => 'meta-style-dash',
				'choices' => Fns::meta_style( [ 'meta-style-dash-bg', 'meta-style-pipe' ] )
			],

			'rt_blog_meta' => [
				'type'        => 'select2',
				'label'       => __( 'Choose Meta', 'clplace' ),
				'description' => __( 'You can sort meta by drag and drop', 'clplace' ),
				'placeholder' => __( 'Choose Meta', 'clplace' ),
				'multiselect' => true,
				'default'     => 'author,date',
				'choices'     => Fns::blog_meta_list(),
			],

			'rt_visibility' => [
				'type'  => 'heading',
				'label' => __( 'Visibility Section', 'clplace' ),
			],

			'rt_meta_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Meta Visibility', 'clplace' ),
				'default' => 1
			],

			'rt_blog_above_cat_visibility' => [
				'type'  => 'switch',
				'label' => __( 'Title Above Category Visibility', 'clplace' ),
			],

			'rt_blog_content_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Entry Content Visibility', 'clplace' ),
				'default' => ''
			],

			'rt_blog_footer_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Entry Footer Visibility', 'clplace' ),
				'default' => ''
			],

			'rt_blog_pagination' => [
				'type'  => 'heading',
				'label' => __( 'Pagination Style', 'clplace' ),
			],
			'rt_blog_pagination_type' => [
				'type'        => 'select',
				'label'       => __( 'Choose Pagination', 'clplace' ),
				'placeholder' => __( 'Choose Pagination', 'clplace' ),
				'multiselect' => true,
				'default'     => 'custom',
				'choices'     => [
					'custom' => __( 'Pagination Number', 'clplace' ),
					'default' => __( 'Pagination Prev/Next', 'clplace' ),
				],
			],

		] );
	}
}
