<?php
/**
* Archive Template
*
* Template generic archives.
*
* @package Funbox Base Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

		<!-- START: template_archive -->
		<?php do_action( 'fnbx_template_archive_start', 'template_archive' ) ?>

			<?php
			/* Run The Loop
			 *
			 * If you want to overload this in a child theme then include a file
			 * called funbox-loop-archive.php and that will be used instead.
			 * We also put the template part name 'archive' into the global
			 * $fnbx->template_part_name so you can use it.
			 */

			 // Filter to catch this loop template part name into gloabal $fnbx
			 global $fnbx;
			 add_filter( 'get_template_part_funbox-loop', array(&$fnbx, 'get_template_part_filter'), 1, 2 );
			 get_template_part( 'funbox-loop', 'archive' );
			 
			?>

		<?php do_action( 'fnbx_template_archive_end', 'template_archive' ) ?>
		<!-- END: template_archive -->

<?php get_sidebar() ?>

<?php get_footer() ?>