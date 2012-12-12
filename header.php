<?php
/**
* Header Template
*
* Template for standard Header.
*
* @package FNBX Theme
* @subpackage Template
*/

do_action( 'fnbx_init'); // Default action and filter initialization
do_action( 'fnbx_child_init'); // Child init override or enhance defaults
do_action( 'fnbx_header_init'); // Typically used for doctype

?>
<!--[if lt IE 7]>      <html <?php language_attributes('html'); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html <?php language_attributes('html'); ?> class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html <?php language_attributes('html'); ?> class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes('html'); ?> class="no-js"> <!--<![endif]-->
<head>

	<title><?php fnbx_document_title() ?></title>

<?php do_action( 'fnbx_wp_head_before', 'wp_head' ) ?>
<?php wp_head() // For plugins ?>
<?php do_action( 'fnbx_wp_head_after', 'wp_head' ) ?>

</head>

<body <?php body_class() ?>>
<!-- START: body -->
<?php do_action( 'fnbx_body_start', 'body' ) ?>

<!-- START: wrapper -->
<?php do_action( 'fnbx_wrapper_start', 'wrapper' ) ?>

	<!-- START: header -->
	<?php do_action( 'fnbx_header_start', 'header' ) ?>

		<?php do_action( 'fnbx_header', 'header' ) ?>

	<?php do_action( 'fnbx_header_end', 'header' ) ?>
	<!-- END: header -->

	<!-- START: container -->
	<?php do_action( 'fnbx_container_start', 'container' ) ?>

		<!-- START: content -->
		<?php do_action( 'fnbx_content_start', 'content' ) ?>