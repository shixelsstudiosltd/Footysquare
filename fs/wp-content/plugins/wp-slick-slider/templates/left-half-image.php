<?php
//Get The ID of the post thumbnail
$img_id = get_post_thumbnail_id( $post->ID );

//Then pull the source of the fullsize image
$imgsrc = wp_get_attachment_image_src( $img_id , 'full' );
$imgsrc = $imgsrc[0];

//Get the image from timthumb.
$img = '<img src="' . $timthumbsrc . '?src=' . $imgsrc . '&w=' . $half_width . '&h=' . $image_height . '" alt="" />';
?>
<div class="wpss_img half">
    <?php echo $img; ?>
</div>
<div class="wpss_content_half">
    <h4><?php the_title(); ?></h4>
    <?php wpss_the_content(); ?>
</div>
