<?php
// Header File
 get_header();
 global  $px_theme_option;
	$px_layout = '';
	if(isset($px_theme_option['px_layout'])){ $px_layout = $px_theme_option['px_layout']; }elseif(!isset($px_theme_option['px_layout'])){ $px_layout = 'right';}
 
 if ( $px_layout <> '' and $px_layout  <> "none" and $px_layout  == 'left') :  ?>
        <aside class="left-content col-md-3">
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_theme_option['px_sidebar_left']) ) : endif; ?>
        </aside>
<?php endif;?>
        <div class="<?php px_default_pages_meta_content_class( $px_layout ); ?>">
        <?php px_page_title();?>
	       	<div class="pix-blog blog-medium">
			<?php 
				 if (empty($_GET['page_id_all']))
                        $_GET['page_id_all'] = 1;
                    if (!isset($_GET["s"])) {
                        $_GET["s"] = '';
                    }
		if ( have_posts() ) : 
			while ( have_posts() ) : the_post(); 
				px_defautlt_artilce();
            endwhile; 
			$qrystr = '';
			// pagination start
				if ( $px_theme_option['pagination'] == "Show Pagination" and $wp_query->found_posts > get_option('posts_per_page')) {
					echo "<nav class='pagination'><ul>";
						 if ( isset($_GET['page_id']) ) $qrystr .= "&page_id=".$_GET['page_id'];
						 if ( isset($_GET['author']) ) $qrystr .= "&author=".$_GET['author'];
						 if ( isset($_GET['tag']) ) $qrystr .= "&tag=".$_GET['tag'];
						 if ( isset($_GET['cat']) ) $qrystr .= "&cat=".$_GET['cat'];
						 if ( isset($_GET['sermon-tag']) ) $qrystr .= "&sermon-tag=".$_GET['sermon-tag'];
						 if ( isset($_GET['season-category']) ) $qrystr .= "&season-category=".$_GET['season-category'];
						 if ( isset($_GET['event-category']) ) $qrystr .= "&event-category=".$_GET['event-category'];
						 if ( isset($_GET['m']) ) $qrystr .= "&m=".$_GET['m'];
						 
					echo px_pagination(wp_count_posts()->publish,get_option('posts_per_page'), $qrystr);
					echo "</ul></nav>";
				}
			// pagination end
 			 endif; ?>
                     
 		</div>
     </div>
  <?php
	if ( $px_layout <> '' and $px_layout  <> "none" and $px_layout  == 'right') :  ?>
		<aside class="left-content col-md-3">
			<?php 
            if(isset($px_theme_option['px_sidebar_right'])){
                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_theme_option['px_sidebar_right']) ) : endif;
            }else{
                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-1') ) : endif;
            }
              ?>
		</aside>
<?php endif; ?>	
 <?php
 //Footer FIle
 get_footer();
?>