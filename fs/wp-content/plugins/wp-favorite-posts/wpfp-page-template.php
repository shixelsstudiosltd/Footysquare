<?php
    $wpfp_before = "";
    echo "<div class='wpfp-span'>";
    if (!empty($user)) {
        if (wpfp_is_user_favlist_public($user)) {
            $wpfp_before = "$user's Favorite Posts.";
        } else {
            $wpfp_before = "$user's list is not public.";
        }
    }

    if ($wpfp_before):
        echo '<div class="wpfp-page-before">'.$wpfp_before.'</div>';
    endif;

    echo "<ul>";
    if ($favorite_post_ids) {
		$favorite_post_ids = array_reverse($favorite_post_ids);
		
		// add all fav post ids to a global variable to use in function.
		
		//foreach($favorite_post_ids as $fv)
		//	echo $fv.',';
		
        $post_per_page = wpfp_get_option("post_per_page");
        $page = intval(get_query_var('paged'));
        $qry = array('post__in' => $favorite_post_ids, 'posts_per_page'=> $post_per_page, 'orderby' => 'post__in', 'paged' => $page);
		$qry['post_type'] = array('post','page');
		$query = new WP_Query( $qry );
        // custom post type support can easily be added with a line of code like below.
        // $qry['post_type'] = array('post','page');
        //query_posts($qry);
        while ( $query->have_posts() ) : $query->the_post();
			if(in_array(get_the_ID(),$favorite_post_ids)){
				echo "<li><a href='".get_permalink()."' title='". get_the_title() ."'>" . get_the_title() . "</a> ";
					wpfp_remove_favorite_link(get_the_ID());
				echo "</li>";
			}
        endwhile;
		
		
        echo '<div class="navigation">';
            if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
            <div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
            <div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>
            <?php }
        echo '</div>';

        wp_reset_query();
		
		/*show post from custom posts*/
		$paged = 1;  
		if ( get_query_var('paged') ) $paged = get_query_var('paged');  
		if ( get_query_var('page') ) $paged = get_query_var('page');
		
		  //query_posts( array( 'post_type' => array( 'country', 'player', 'wps_forum_post', 'club' ), 'post__in' => $favorite_post_ids,'posts_per_page' => 10,'paged' => $paged ) );
		  query_posts( array( 'post_type' => array( 'country', 'player','club' ), 'post__in' => $favorite_post_ids,'posts_per_page' => 10,'paged' => $paged ) );
		  if ( have_posts() ) : while ( have_posts() ) : the_post();

				echo "<li><a href='".get_permalink()."' title='". get_the_title() ."'>" . get_the_title() . "</a> ";
					wpfp_remove_favorite_link(get_the_ID());
				echo "</li>";
				endwhile; 
			endif; 

			posts_nav_link(' — ', __('&laquo; Newer Posts'), __('Older Posts &raquo;'));

		wp_reset_query();
		
    } else {
        $wpfp_options = wpfp_get_options();
        echo "<li>";
        echo $wpfp_options['favorites_empty'];
        echo "</li>";
    }
    echo "</ul>";

    echo '<p>'.wpfp_clear_list_link().'</p>';
    echo "</div>";
    wpfp_cookie_warning();
	echo '<style>
		.wpfp-span a:last-child{color:#ccc;text-transform: capitalize;font-weight:bold;margin-left:10px;} 
		.footer-widget{top:100px;} 
		.wpfp-span li {
		border-bottom: 1px solid #ccc;
		margin-bottom: 5px;
		width: 80%;
		padding-bottom: 5px;
		}
	</style>';