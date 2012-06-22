<?php 
// Make sure FNBX is loaded
global $fnbx, $wp_query;

/*
// Debug $fnbx object
echo $fnbx->template_file;
echo $fnbx->template_part_name;
print_r( $wp_query );
*/

?>

<!-- START: Loop Template Part -->
<?php do_action( "fnbx_template_loop_start", $fnbx->template_part_name  ) ?>
<?php do_action( "fnbx_template_loop_{$fnbx->template_part_name}_start", $fnbx->template_part_name ) ?>

<?php 

/* 
Show next & previous navigation when applicable 

Need to study the behavor of

<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<?php fnbx_post_navigation_box_auto( 'above' ); ?>
<?php endif; ?>

*/ 
?>
<?php fnbx_post_navigation_box_auto( 'above' ); ?>
			
<?php while ( have_posts() ) : the_post() ?>

	<!-- START: post -->
	<?php do_action( 'fnbx_template_loop_post_start', fnbx_get_the_id() ) ?>
	<?php do_action( "fnbx_template_loop_{$fnbx->template_part_name}_post_start", fnbx_get_the_id() ) ?>

		<?php do_action( 'fnbx_template_loop_entry_title', fnbx_get_the_id() ) ?>

		<?php if( !is_page() ) fnbx_entry_date(); // Only display date for non-pages ?>
	
		<?php if ( is_archive() || is_search() ) : // Only display Excerpts for archives & search ?>
			
			<!-- START: entry-summary -->
			<?php do_action( 'fnbx_template_loop_content_start', 'entry-summary' ) ?>
			<?php do_action( "fnbx_template_loop_content_{$fnbx->template_part_name}_start", 'entry-summary'  ) ?>
				
				<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'fnbx_lang' ) ); ?>
			
			<?php do_action( "fnbx_template_loop_content_{$fnbx->template_part_name}_end", 'entry-summary'  ) ?>
			<?php do_action( 'fnbx_template_loop_content_end', 'entry-summary' ) ?>
			<!-- END: entry-summary -->
			
		<?php else : ?>
			
			<!-- START: entry-content -->
			<?php do_action( 'fnbx_template_loop_content_start', 'entry-content' ) ?>
			<?php do_action( "fnbx_template_loop_content_{$fnbx->template_part_name}_start", 'entry-content'  ) ?>
				
				<?php the_content( __( 'Continue&nbsp;reading&nbsp;<span class="meta-nav">&rarr;</span>', 'fnbx_lang' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="link-pages">' . __( 'Pages:', 'fnbx_lang' ), 'after' => '</div>' ) ); ?>
				
			<?php do_action( "fnbx_template_loop_content_{$fnbx->template_part_name}_end", 'entry-content'  ) ?>
			<?php do_action( 'fnbx_template_loop_content_end', 'entry-content' ) ?>
			<!-- END: entry-content -->
			
		<?php endif; ?>
	
	<?php do_action( "fnbx_template_loop_{$fnbx->template_part_name}_post_end", fnbx_get_the_id() ) ?>		
	<?php do_action( 'fnbx_template_loop_post_end', fnbx_get_the_id() ) ?>
	<!-- END: post -->

<?php endwhile; ?>

<?php /* We trap the 404 errors on the loop */ ?>
<?php if ( is_404() ) : // Only display Excerpts for archives & search ?>

	<!-- START: post -->
	<?php do_action( 'fnbx_template_loop_post_start', fnbx_get_the_id() ) ?>
	<?php do_action( "fnbx_template_loop_{$fnbx->template_part_name}_post_start", fnbx_get_the_id() ) ?>
		
		<?php fnbx_entry_title(); // Title wrapper function with some logic ?>
	
		<!-- START: entry-summary -->
		<?php do_action( 'fnbx_template_loop_content_start', 'entry-summary' ) ?>
		<?php do_action( "fnbx_template_loop_content_{$fnbx->template_part_name}_start", 'entry-summary'  ) ?>
			
			<p>
				<?php _e( 'Sorry, but the information you requested could not be found. You should try searching or visitng other pages on this website.', 'fnbx_lang' ); ?>
			</p>
		
		<?php do_action( "fnbx_template_loop_content_{$fnbx->template_part_name}_end", 'entry-summary'  ) ?>
		<?php do_action( 'fnbx_template_loop_content_end', 'entry-summary' ) ?>
		<!-- END: entry-summary -->

	<?php do_action( "fnbx_template_loop_{$fnbx->template_part_name}_post_end", fnbx_get_the_id() ) ?>		
	<?php do_action( 'fnbx_template_loop_post_end', fnbx_get_the_id() ) ?>
	<!-- END: post -->
	
<?php endif; ?>

<?php /* Show next & previous navigation when applicable */ ?>
<?php fnbx_post_navigation_box_auto( 'below' ); ?>

<?php do_action( "fnbx_template_loop_{$fnbx->template_part_name}_end", $fnbx->template_part_name ) ?>
<?php do_action( "fnbx_template_loop_end", $fnbx->template_part_name  ) ?>
<!-- END: Loop Template Part -->
