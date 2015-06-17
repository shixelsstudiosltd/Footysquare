<?php
//Get The ID of the post thumbnail
$img_id = get_post_thumbnail_id( $post->ID );

//Then pull the source of the fullsize image
$imgsrc = wp_get_attachment_image_src( $img_id , 'full' );
$imgsrc = $imgsrc[0];



$image_src = $timthumbsrc . '?src=' . $imgsrc . '&w=' . $full_width . '&h=' . $image_height;
$img = '<img src="' . $image_src . '" alt="" />';
?>
<div class="wpss_img full">
    <?php echo $img; ?>
</div>
