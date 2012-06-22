<?php
/**
* Tag Template
*
* Template for tag archives.
*
* @package FNBX Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

		<!-- START: template_tag -->
		<?php do_action( 'fnbx_template_tag_start', 'template_tag' ) ?>

			<?php
			/* Run The Loop
			 *
			 * If you want to overload this in a child theme then include a file
			 * called fnbx-loop-tag.php and that will be used instead.
			 * We also put the template part name 'tag' into the global
			 * $fnbx->template_part_name so you can use it.
			 */

			 // Filter to catch this loop template part name into gloabal $fnbx
			 global $fnbx;
			 add_filter( 'get_template_part_fnbx-loop', array(&$fnbx, 'get_template_part_filter'), 1, 2 );
			 get_template_part( 'fnbx-loop', 'tag' );
			 
			?>

		<?php do_action( 'fnbx_template_tag_end', 'template_tag' ) ?>
		<!-- END: template_tag -->

<?php get_sidebar() ?>

<?php get_footer() ?>