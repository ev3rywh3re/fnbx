<?php
/**
* Footer Template
*
* Template for standard footer.
*
* @package FNBX Theme
* @subpackage Template
*/
?>

		<?php do_action( 'fnbx_content_end', 'content' ) ?>
		<!-- END: content -->

	<?php do_action( 'fnbx_container_end', 'container' ) ?>
	<!-- END: container -->

	<!-- START: footer -->
	<?php do_action( 'fnbx_footer_start', 'footer' ) ?>
		<?php do_action( 'fnbx_footer', 'footer' ) ?>
	<?php do_action( 'fnbx_footer_end', 'footer' ) ?>
	<!-- END: footer -->

<?php do_action( 'fnbx_wrapper_end', 'wrapper' ) ?>
<!-- END: wrapper -->

<?php do_action( 'fnbx_wp_footer_before', 'wp_footer' ) ?>
<?php wp_footer() ?>
<?php do_action( 'fnbx_wp_footer_after', 'wp_footer' ) ?>

<?php do_action( 'fnbx_body_end', 'body' ) ?>
<!-- END: body -->
</body>
</html>