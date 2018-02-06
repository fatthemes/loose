<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package loose
 */

?>
<div class="col-md-12 masonry">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'loose-list' ); ?>>
	<header class="entry-header row">
		<?php
		if ( has_post_format( 'image' ) ) :
			get_the_image(
				 array(
					 'scan' => true,
					 'scan_raw' => true,
					 'size' => 'medium',
				 )
				);
		elseif ( has_post_thumbnail() ) :
		?>

			<div class="featured-image col-xs-12 col-sm-6">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_post_thumbnail( 'medium' ); ?>   
			</a>  
			<?php echo loose_post_format_icon( get_the_ID() ); // WPCS: XSS OK. ?>
			<?php
			if ( ! has_post_format( 'aside' ) && ! has_post_format( 'link' ) && ! has_post_format( 'quote' ) && ! has_post_format( 'image' ) ) :
				?>
				<div class="featured-image-cat">
					<?php the_category( __( '<span> &#124; </span>', 'loose' ) ); ?>
				</div>
			<?php endif; ?>
			</div>
			<div class="title-meta-wrapper col-xs-12 col-sm-6">
			<?php
				loose_the_title();
				loose_the_content();
				loose_entry_meta();
			?>
			</div><!-- .title-meta-wrapper -->

		<?php else : ?>

			<div class="title-meta-wrapper col-xs-12">
			<?php echo loose_post_format_icon( get_the_ID() ); // WPCS: XSS OK. ?>
			<?php
			if ( ! has_post_format( 'aside' ) && ! has_post_format( 'link' ) && ! has_post_format( 'quote' ) && ! has_post_format( 'image' ) ) :
				?>
				<div class="featured-image-cat">
					<?php the_category( __( '<span> &#124; </span>', 'loose' ) ); ?>
				</div>
			<?php
			endif;

				loose_home_the_title();
				loose_home_the_content()
			?>
			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php loose_posted_on(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
			</div><!-- .title-meta-wrapper -->

		<?php endif; ?>
	</header><!-- .entry-header -->
	</article><!-- #post-## -->
</div>
