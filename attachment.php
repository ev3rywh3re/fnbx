<?php
/**
* Attachment Template
*
* Template generic attachments.
*
* @package Funbox Base Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

		<!-- START: template_attachment -->
		<?php do_action( 'fnbx_template_attachment_start', 'template_attachment' ) ?>

			<?php
			/* Run The Loop
			 *
			 * If you want to overload this in a child theme then include a file
			 * called funbox-loop-attachment.php and that will be used instead.
			 * We also put the template part name 'attachment' into the global
			 * $fnbx->template_part_name so you can use it.
			 */

			 // Filter to catch this loop template part name into gloabal $fnbx
			 global $fnbx;
			 add_filter( 'get_template_part_funbox-loop', array(&$fnbx, 'get_template_part_filter'), 1, 2 );
			 get_template_part( 'funbox-loop', 'attachment' );
			 
			?>

		<?php do_action( 'fnbx_template_attachment_end', 'template_attachment' ) ?>
		<!-- END: template_attachment -->

<?php get_sidebar() ?>

<?php get_footer() ?>