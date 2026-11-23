<?php
/**
 * The template for displaying WooCommerce shop, product archive and product pages.
 *
 * @link https://woocommerce.com/document/woocommerce-theme-developer-handbook/
 *
 * @package loose
 */

get_header();

$loose_shop_sidebar = loose_woocommerce_has_sidebar();
?>
<div class="row">
	<?php
	// The Shop Sidebar comes first: left of the products on desktop, above them on small screens.
	if ( $loose_shop_sidebar ) {
		get_sidebar( 'shop' );
	}
	?>
	<div id="primary" class="content-area <?php echo esc_attr( $loose_shop_sidebar ? 'col-lg-8' : 'col-lg-12' ); ?>">
	<main id="main" class="site-main" role="main">

		<?php woocommerce_content(); ?>

	</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .row -->
<?php
get_footer();
