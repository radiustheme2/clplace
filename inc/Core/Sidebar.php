<?php

namespace RT\Clplace\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Helpers\Fns;
use RT\Clplace\Traits\SingletonTraits;

/**
 * Sidebar.
 */
class Sidebar {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
	}

	public function widgets_init() {

		foreach ( Fns::default_sidebar() as $sidebar ) {

			$classes = $sidebar['class'] ?? '';

			register_sidebar( [
				'id'            => $sidebar['id'],
				'name'          => sprintf( esc_html_x( '%s', 'Widget Name', 'clplace' ), $sidebar['name'] ),
				'description'   => $sidebar['description'] ?? '',
				'before_widget' => '<div id="%1$s" class="widget ' . $classes . ' %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );

		}
	}
}
