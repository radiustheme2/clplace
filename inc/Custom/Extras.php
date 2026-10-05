<?php

namespace RT\Clplace\Custom;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clplace\Traits\SingletonTraits;
use RT\Clplace\Options\Opt;

/**
 * Extras.
 */
class Extras {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_action( 'wp_nav_menu_item_custom_fields', [ $this, 'menu_customize' ], 10, 2 );
		add_action( 'wp_update_nav_menu_item', [ $this, 'menu_update' ], 10, 2 );
		add_filter( 'wp_get_nav_menu_items', [ $this, 'menu_modify' ], 11, 3 );
		add_filter( 'get_search_form', [ $this, 'search_form' ] );
		add_action( 'after_switch_theme', [ $this, 'rewrite_flush' ] );
	}

	/*
	 * Body Class added
	 */
	public function body_class( $classes ) {

		// Adds a class of group-blog to blogs with more than 1 published author.

		$classes[] = 'clplace-header-' . Opt::$header_style;
		$classes[] = 'clplace-footer-' . Opt::$footer_style;

		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		if ( Opt::$has_tr_header ) {
			$classes[] = 'has-trheader';
		} else {
			$classes[] = 'no-trheader';
		}

		if ( Opt::$has_tr_header && !empty( Opt::$header_tr_color ) ) {
			$classes[] = Opt::$header_tr_color;
		}

		if ( clplace_option( 'rt_tr_header_shadow' ) ) {
			$classes[] = 'has-menu-shadow';
		}

		if ( Opt::$has_banner ) {
			$classes[] = 'has-banner';
		} else {
			$classes[] = 'no-banner';
		}

		if ( Opt::$layout ) {
			$classes[] = 'layout-' . Opt::$layout;
		}

		if ( clplace_option( 'rt_sticy_header' ) ) {
			$classes[] = 'has-sticky-header';
		}

		if ( is_single() && Opt::$single_style ) {
			$classes[] = 'clplace-single-' . Opt::$single_style;
		}

		if ( Opt::$header_width == 'fullwidth' || Opt::$header_width == '-fluid' ) {
			$classes[] = 'header-' . Opt::$header_width;
		}

		return $classes;
	}

	/*
	 * Menu Customize
	 */
	function menu_customize( $item_id, $item ) {
		// Mega menu
		$_mega_menu = get_post_meta( $item_id, 'clplace_mega_menu', true );
		// Query string
		$menu_query_string = get_post_meta( $item_id, 'clplace_menu_qs', true );
		?>

		<?php if ( $item->menu_item_parent < 1 ) : ?>
			<p class="description mega-menu-wrapper widefat">
				<label for="clplace_mega_menu-<?php echo esc_attr( $item_id ); ?>" class="widefat">
					<?php esc_html_e( 'Make as Mega Menu', 'clplace' ); ?><br>
					<select class="widefat" id="clplace_mega_menu-<?php echo esc_attr( $item_id ); ?>" name="clplace_mega_menu[<?php echo absint( $item_id ); ?>]">
						<option value=""><?php esc_html_e( 'Choose Mega Menu', 'clplace' ); ?></option>
						<?php
						for ( $item = 2; $item < 12; $item++ ) {
							$menu_item  = $item;
							$class_hide = null;
							$label_hide = '';
							if ( $item > 6 ) {
								$menu_item -= 5;
								$class_hide = ' hide-header';
								$label_hide = ' — Hide Col Title';
							}
							$class    = "mega-menu mega-menu-col-{$menu_item}" . $class_hide ?? '';
							$selected = ( $_mega_menu == $class ) ? ' selected="selected" ' : null;
							?>
							<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $class ); ?>">
								<?php echo wp_kses_post( sprintf( esc_html__( 'Mega menu - %1$s Col %2$s', 'clplace' ), absint( $menu_item ), esc_html( $label_hide ) ) ); ?>
							</option>
							<?php
						}
						?>
					</select>
				</label>
			</p>
		<?php endif; ?>

		<p class="description widefat">
			<label class="widefat" for="clplace-menu-qs-<?php echo esc_attr( $item_id ); ?>">
				<?php echo esc_html__( 'Query String', 'clplace' ); ?><br>
				<input type="text"
					   class="widefat"
					   id="clplace-menu-qs-<?php echo esc_attr( $item_id ); ?>"
					   name="clplace-menu-qs[<?php echo esc_attr( $item_id ); ?>]"
					   value="<?php echo esc_html( $menu_query_string ); ?>"
				/>
			</label>
		</p>


		<?php
	}

	/**
	 * Menu Update
	 *
	 * @param $menu_id
	 * @param $menu_item_db_id
	 *
	 * @return void
	 */
	function menu_update( $menu_id, $menu_item_db_id ) {
		$_mega_menu         = $_POST['clplace_mega_menu'][ $menu_item_db_id ] ?? '';
		$query_string_value = $_POST['clplace-menu-qs'][ $menu_item_db_id ] ?? '';

		update_post_meta( $menu_item_db_id, 'clplace_mega_menu', $_mega_menu );
		update_post_meta( $menu_item_db_id, 'clplace_menu_qs', $query_string_value );
	}

	/**
	 * Modify Menu item
	 *
	 * @param $items
	 * @param $menu
	 * @param $args
	 *
	 * @return mixed
	 */
	function menu_modify( $items, $menu, $args ) {
		foreach ( $items as $item ) {
			$menu_query_string = get_post_meta( $item->ID, 'clplace_menu_qs', true );
			if ( $menu_query_string ) {
				$item->url = add_query_arg( $menu_query_string, '', $item->url );
			}
		}

		return $items;
	}

	/**
	 * Search form modify
	 *
	 * @return string
	 */
	public function search_form() {
		$output = '
		<form method="get" class="clplace-search-form" action="' . esc_url( home_url( '/' ) ) . '">
            <div class="search-box">
				<input type="text" class="form-control" placeholder="' . esc_attr__( 'Search here...', 'clplace' ) . '" value="' . get_search_query() . '" name="s" />
				<button class="item-btn" type="submit">
					' . clplace_get_svg( 'search', false ) . '
					<span class="btn-label">' . esc_html__( 'Search', 'clplace' ) . '</span>
				</button>
            </div>
		</form>
		';

		return $output;
	}

	/**
	 * Flush Rewrite on CPT activation
	 *
	 * @return empty
	 */
	public function rewrite_flush() {
		// Flush the rewrite rules only on theme activation
		flush_rewrite_rules();
	}
}
