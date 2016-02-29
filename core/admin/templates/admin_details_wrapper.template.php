<?php
global $post_type, $post;
//action for registering metaboxes
do_action( 'add_meta_boxes', $post_type, $post );
?>
<div id="admin-page-header">
	<?php echo $admin_page_header; ?>
</div>
<div id="poststuff">
		<!-- admin-page-header -->
	<div id="post-body" class="metabox-holder columns-2">

	<?php if ( ! empty( $post_body_content )) : ?>
		<div id="post-body-content">
			<?php echo $post_body_content; ?>
		</div>
		<!-- post-body-content -->
	<?php endif; ?>

		<div id="postbox-container-1" class="postbox-container">
			<?php do_meta_boxes( $current_page, 'side', NULL ); ?>
		</div>
		<!-- postbox-container-1 -->

		<div id="postbox-container-2" class="postbox-container">
			<?php do_meta_boxes( $current_page, 'normal', NULL ); ?>
			<?php do_meta_boxes( $current_page, 'advanced', NULL ); ?>
		</div>
		<!-- postbox-container-2 -->
	</div>
	<br class="clear"/>
	<!-- post-body -->
</div>
<!-- poststuff -->