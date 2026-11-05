<?php
/**
 * WooCommerce compatibility. Only loaded when WooCommerce is active.
 *
 * @link https://woocommerce.com/
 *
 * @package loose
 */

/**
 * Declares WooCommerce support and the product gallery features.
 */
function loose_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'loose_woocommerce_setup' );

/**
 * Registers the Shop Sidebar widget area.
 */
function loose_woocommerce_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'loose' ),
			'id'            => 'loose-shop-sidebar',
			'description'   => esc_html__( 'Appears left of the products on the shop page and product category and tag pages, and above them on small screens. Leave empty for a full width shop.', 'loose' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title"><span>',
			'after_title'   => '</span></h2>',
		)
	);
}
add_action( 'widgets_init', 'loose_woocommerce_widgets_init' );

/**
 * Whether the current page shows the Shop Sidebar.
 *
 * @return bool
 */
function loose_woocommerce_has_sidebar() {
	return ( is_shop() || is_product_taxonomy() ) && get_theme_mod( 'loose_woocommerce_sidebar', 1 ) && is_active_sidebar( 'loose-shop-sidebar' );
}

/**
 * Drops the blog's masonry and infinite scroll scripts from shop pages and loads the sticky sidebar for the Shop Sidebar.
 */
function loose_woocommerce_scripts() {
	if ( ! is_woocommerce() ) {
		return;
	}

	// Masonry and imagesLoaded are only loaded as its dependencies.
	wp_dequeue_script( 'infinite-scroll' );

	if ( get_theme_mod( 'sticky_sidebar', 1 ) && loose_woocommerce_has_sidebar() ) {
		wp_enqueue_script( 'theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js', array( 'jquery' ), '1.2.2', true );
	}
}
add_action( 'wp_enqueue_scripts', 'loose_woocommerce_scripts', 20 );

/**
 * Gives text links in product descriptions the Customizer content link colour.
 */
function loose_woocommerce_inline_css() {
	if ( ! is_woocommerce() ) {
		return;
	}

	$links = '.woocommerce-product-details__short-description a,.woocommerce-Tabs-panel a,.term-description a,.page-description a';
	$css   = $links . '{color:' . esc_attr( get_theme_mod( 'content_link_color', '#000' ) ) . ';}';
	$css  .= str_replace( ',', ':hover,', $links ) . ':hover,' . str_replace( ',', ':focus,', $links ) . ':focus{color:#b55f3a;}';

	wp_add_inline_style( 'loose-style', $css );
}
add_action( 'wp_enqueue_scripts', 'loose_woocommerce_inline_css', 20 );

if ( ! function_exists( 'loose_woocommerce_cart_link' ) ) :

	/**
	 * Displays the header cart link with the number of items in the cart.
	 */
	function loose_woocommerce_cart_link() {
		if ( ! WC()->cart ) {
			return;
		}

		$count = WC()->cart->get_cart_contents_count();
		?>
		<a class="loose-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M17.016 18q0.797 0 1.383 0.609t0.586 1.406-0.586 1.383-1.383 0.586-1.406-0.586-0.609-1.383 0.609-1.406 1.406-0.609zM0.984 2.016h3.281l0.938 1.969h14.813q0.422 0 0.703 0.305t0.281 0.727q0 0.047-0.141 0.469l-3.563 6.469q-0.563 1.031-1.734 1.031h-7.453l-0.891 1.641-0.047 0.141q0 0.234 0.234 0.234h11.578v2.016h-12q-0.797 0-1.383-0.609t-0.586-1.406q0-0.469 0.234-0.938l1.359-2.484-3.609-7.594h-2.016v-1.969zM6.984 18q0.797 0 1.406 0.609t0.609 1.406-0.609 1.383-1.406 0.586-1.383-0.586-0.586-1.383 0.586-1.406 1.383-0.609z"></path></svg>
		<?php if ( $count > 0 ) : ?>
			<span class="loose-cart-count" aria-hidden="true"><?php echo esc_html( number_format_i18n( $count ) ); ?></span>
		<?php endif; ?>
		<span class="screen-reader-text">
		<?php
		/* translators: %s: number of items in the cart. */
		echo esc_html( sprintf( _n( 'Cart, %s item', 'Cart, %s items', $count, 'loose' ), number_format_i18n( $count ) ) );
		?>
		</span></a>
		<?php
	}

endif;

/**
 * Refreshes the header cart link after products are added to the cart with AJAX.
 *
 * @param array $fragments HTML fragments keyed by selector.
 * @return array
 */
function loose_woocommerce_cart_link_fragment( $fragments ) {
	if ( ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	loose_woocommerce_cart_link();
	$fragments['a.loose-cart-link'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'loose_woocommerce_cart_link_fragment' );

/**
 * Hides the shop page title when turned off in the Customizer.
 *
 * @param bool $show Whether to show the title.
 * @return bool
 */
function loose_woocommerce_show_page_title( $show ) {
	return $show && get_theme_mod( 'loose_woocommerce_show_page_title', 1 );
}
add_filter( 'woocommerce_show_page_title', 'loose_woocommerce_show_page_title' );

/**
 * Adds the WooCommerce (Loose) Customizer section.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function loose_woocommerce_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'loose_woocommerce',
		array(
			'title'       => esc_html__( 'WooCommerce (Loose)', 'loose' ),
			'priority'    => 1030,
			'description' => esc_html__( 'Shop layout options from the Loose theme.', 'loose' ),
		)
	);

	$wp_customize->add_setting(
		'loose_woocommerce_sidebar',
		array(
			'default'           => 1,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'loose_woocommerce_sidebar',
		array(
			'label'       => esc_html__( 'Show the Shop Sidebar', 'loose' ),
			'description' => esc_html__( 'Shows the Shop Sidebar widget area on the shop page and product category and tag pages, when it has widgets.', 'loose' ),
			'section'     => 'loose_woocommerce',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'loose_woocommerce_show_page_title',
		array(
			'default'           => 1,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'loose_woocommerce_show_page_title',
		array(
			'label'       => esc_html__( 'Show the shop page title', 'loose' ),
			'description' => esc_html__( 'Shows the title above the products on the shop page and product category and tag pages.', 'loose' ),
			'section'     => 'loose_woocommerce',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'loose_woocommerce_customize_register' );
