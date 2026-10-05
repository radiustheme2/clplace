<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace RT\Clplace\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Traits\SingletonTraits;
use Jetpack;

class ThemeJetpack {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		if ( ! class_exists( 'Jetpack' ) ) {
			return;
		}
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_filter( 'jetpack_photon_pre_args', [ $this, 'photon_compression' ] );
	}

	public function setup() {
		// Add theme support for Infinite Scroll.
		add_theme_support( 'infinite-scroll', [
			'container' => 'main',
			'render'    => [ $this, 'infinite_scroll_render' ],
			'footer'    => 'page',
		] );
		// Add theme support for Responsive Videos.
		add_theme_support( 'jetpack-responsive-videos' );
	}

	public function infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			if ( is_search() ) :
				get_template_part( 'views/content', 'search' );
			else :
				get_template_part( 'views/content', get_post_format() );
			endif;
		}
	}

	public function photon_compression( $args ) {
		$args['quality'] = 100;
		$args['strip']   = 'all';

		return $args;
	}
}
