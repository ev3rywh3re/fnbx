<?php
/**
* Search Template
*
* Template for search results.
*
* @package FNBX Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

		<!-- START: template_search -->
		<?php do_action( 'fnbx_template_search_start', 'template_search' ) ?>

<?php if ( have_posts() ) : ?>

		<!-- START: template_search_results -->
		<?php do_action( 'fnbx_template_search_results_start', 'template_search_results' ) ?>

			<?php
			/* Run The Loop
			 *
			 * If you want to overload this in a child theme then include a file
			 * called fnbx-loop-search.php and that will be used instead.
			 * We also put the template part name 'search' into the global
			 * $fnbx->template_part_name so you can use it.
			 */

			 // Filter to catch this loop template part name into gloabal $fnbx
			 global $fnbx;
			 add_filter( 'get_template_part_fnbx-loop', array(&$fnbx, 'get_template_part_filter'), 1, 2 );
			 get_template_part( 'fnbx-loop', 'search' );
			 
			?>

		<?php do_action( 'fnbx_template_search_results_end', 'template_search_results' ) ?>
		<!-- START: template_search_results -->

<?php else : ?>

			<div id="post-0" class="post search-no-results">
				<h2 class="entry-title"><?php _e( 'Nothing Found', 'fnbx_lang' ) ?></h2>
				<?php fnbx_layout_element_open_class_only( 'entry-content' ) ?>
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'fnbx_lang' ) ?></p>
				</div>
				<form id="searchform-no-results" class="site-search" method="get" action="<?php echo site_url() ?>">
					<div>
						<input id="s-no-results" name="s" class="text" type="text" value="<?php the_search_query() ?>" size="40" />
						<input class="button" type="submit" value="<?php _e( 'Find', 'fnbx_lang' ) ?>" />
					</div>
				</form>
			<?php fnbx_layout_post_close() ?><!-- .post -->

<?php endif; ?>

		<?php do_action( 'fnbx_template_search_end', 'template_search' ) ?>
		<!-- END: template_search -->	

<?php get_sidebar() ?>

<?php get_footer() ?>
