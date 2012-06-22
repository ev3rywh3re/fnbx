<?php
/**
* Single Template
*
* Template for single post entries.
*
* @package Funbox Base Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

		<!-- START: template_single -->
		<?php do_action( 'fnbx_template_single_start', 'template_single' ) ?>

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
			 get_template_part( 'funbox-loop', 'single' );
			 
			?>

		<?php do_action( 'fnbx_template_single_end', 'template_single' ) ?>
		<!-- END: template_single -->
		
<?php get_sidebar() ?>

<?php get_footer() ?>