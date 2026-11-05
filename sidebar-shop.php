<?php
/**
 * The sidebar containing the shop widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package loose
 */

if ( ! is_active_sidebar( 'loose-shop-sidebar' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area loose-shop-sidebar col-lg-4" role="complementary">
	<div class="theiaStickySidebar">
	<?php dynamic_sidebar( 'loose-shop-sidebar' ); ?>
	</div>
</div><!-- #secondary -->
