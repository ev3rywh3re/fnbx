<?php
/**
* Comments Template
*
* Template for comment lists, forms, and controls.
*
* @package Funbox Base Theme
* @subpackage Template
*/
?>
<!-- START: template_comments -->
<?php
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
?>

<?php do_action( 'fnbx_template_comments_start', 'comments' ) ?>

	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is protected. Enter the password to view any comments.', 'fnbx_lang' ) ?></p>
	<?php fnbx_layout_element_close( 'comments' ) ?><!-- #comments -->
		<?php return ?>
	<?php endif; ?>

	<?php if ( have_comments() ) : ?>
	
	<?php 
	
	$comment_header_defaults = array(
		'tag' => 'h3',
		'id' => 'comment-header',
		'class' => 'comment-header-',
		'tag_content' => fnbx_comments_number('No Responses', 'One Response', '% Responses' ) . ' to &#8220;' . get_the_title() . '&#8221;'
	);
	
	$comment_header_defaults = apply_filters( 'fnbx_comment_header', $comment_header_defaults );
	
	fnbx_html_tag( $comment_header_defaults );
	
	?>

		<?php do_action( 'fnbx_template_comments_comments_list_start', 'comments' ) ?>

		<?php do_action( 'fnbx_template_comments_comments_list', 'comments' ) ?>

		<?php do_action( 'fnbx_template_comments_comments_list_end', 'comments' ) ?>

	<?php else : // this is displayed if there are no comments so far ?>

		<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
			<?php do_action( 'fnbx_template_comments_comments_open_empty', 'comments' ) ?>
		 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
			<?php do_action( 'fnbx_template_comments_comments_closed', 'comments' ) ?>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
	
		<?php comment_form() ?>

	<?php endif; // if you delete this the sky will fall on your head ?>

<?php do_action( 'fnbx_template_comments_end', 'comments' ) ?>

<!-- END: template_comments -->