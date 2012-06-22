<?php
/**
* Category Template
*
* Template for category archives.
*
* @package FNBX Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

		<!-- START: template_category -->
		<?php do_action( 'fnbx_template_category_start', 'template_category' ) ?>

			<?php
			/* Run The Loop
			 *
			 * If you want to overload this in a child theme then include a file
			 * called fnbx-loop-category.php and that will be used instead.
			 * We also put the template part name 'category' into the global
			 * $fnbx->template_part_name so you can use it.
			 */

			 // Filter to catch this loop template part name into gloabal $fnbx
			 global $fnbx;
			 add_filter( 'get_template_part_fnbx-loop', array(&$fnbx, 'get_template_part_filter'), 1, 2 );
			 get_template_part( 'fnbx-loop', 'category' );
			 
			?>

		<?php do_action( 'fnbx_template_category_end', 'template_category' ) ?>
		<!-- END: template_category -->

<?php get_sidebar() ?>

<?php get_footer() ?>