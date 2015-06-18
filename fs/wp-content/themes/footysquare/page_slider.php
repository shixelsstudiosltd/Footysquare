<?php 
	global $px_node,$px_counter_node,$px_theme_option;
	$width = '768';
	$height = '403';
?>
	<div class="element_size_<?php echo $px_node->slider_element_size; ?>"> 
		<?php if ($px_node->slider_header_title <> '') { ?>
                    <header class="pix-heading-title"><h2 class="pix-section-title"><?php echo $px_node->slider_header_title; ?></h2></header>
        <?php }
            if(!empty($px_node->slider)){
                $args=array(
                  'name' => (string)$px_node->slider,
                  'post_type' => 'px_gallery',
                  'post_status' => 'publish',
                  'showposts' => 1,
                );
                $get_posts = get_posts($args);
                if($get_posts){
                    $slider_id = $get_posts[0]->ID;
                    px_flex_slider($width,$height,(int)$slider_id);
                }
            }else{
                echo "Please Select Slider";
            }
        ?>
	</div>