<?php
/**
* Author Template
*
* Template for author based archives.
*
* @package Funbox Base Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

<?php the_post() ?>

		<!-- START: template_author -->
		<?php do_action( 'fnbx_template_author_start', 'template_author' ) ?>

<?php rewind_posts() ?>

			<?php
			/* Run The Loop
			 *
			 * If you want to overload this in a child theme then include a file
			 * called funbox-loop-index.php and that will be used instead.
			 * We also put the template part name 'index' into the global
			 * $fnbx->template_part_name so you can use it.
			 */

			 // Filter to catch this loop template part name into gloabal $fnbx
			 global $fnbx;
			 add_filter( 'get_template_part_funbox-loop', array(&$fnbx, 'get_template_part_filter'), 1, 2 );
			 get_template_part( 'funbox-loop', 'author' );
			 
			?>

		<?php do_action( 'fnbx_template_author_end', 'template_author' ) ?>
		<!-- END: template_author -->

<?php get_sidebar() ?>

<?php get_footer() ?>