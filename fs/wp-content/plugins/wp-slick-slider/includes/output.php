<?php
/*
 * The Outputting function, Template tag and Shortcode for WP Slick Slider.
 */

/*
 * Template Tag
 */
function wp_slick_slider( $slider , $echo = true ){

    global $post , $wpss_options, $wp_query;

    //Preserve The Original query
    //$original_query = $wp_query->query;
    
    $timthumbsrc = WPSS_HTTP_PATH . 'includes/timthumb/timthumb.php';

    $data = get_option( 'wpss_' . $slider . '_options' );
    
    $prev_next_buttons = ( isset( $data['wpss_useprevnext'] ) && $data['wpss_useprevnext'] == 'on' ) ? true : false ;
    $pause_resume_links = ( isset( $data['wpss_usepauseresume'] ) && $data['wpss_usepauseresume'] == 'on' ) ? true : false ;
    $pager = ( isset( $data['wpss_usepager'] ) && $data['wpss_usepager'] == 'on' ) ? true : false ;
    
    //Image Measurement Filters
    $full_width = $data['wpss_fullwidth'] ;
    $half_width = $data['wpss_halfwidth'];
    $image_height = $data['wpss_height'];

    /*
     * Set Some args and run a WP Query
     */
    $slide_args = array(
        'post_type' => 'wpss_slides',
        'posts_per_page' => -1,
	    'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy'  => 'wpss_sliders',
                'field'     => 'slug',
                'terms'     => $slider
            )	
        )
    );
    
    //Handle The ordering.
    if( isset( $data['wpss_order'] ) ){
        
        switch( $data['wpss_order'] ):
            
            case 'asc':
                $slide_args['order'] = 'ASC';
                break;
            
            case 'desc':
                $slide_args['order'] = 'DESC';
                break;
            
            case 'random':
                $slide_args['orderby'] = 'rand';
                break;
            
            case 'slide_number':
            default:
                $slide_args['order'] = 'ASC';
                $slide_args['orderby'] = 'meta_value_num';
                $slide_args['meta_key'] = '_slide_order';
                break;
            
        endswitch;
        
    } else {
        $slide_args['order'] = 'ASC';
        $slide_args['orderby'] = 'meta_value_num';
        $slide_args['meta_key'] = '_slide_order';
    }

    $slides = new WP_Query( $slide_args );
    
    /*
     * Let The Party Begin!
     */
    if( $slides -> have_posts() ):

    $out = '<div class="wpss_slideshow" id="wpss_slideshow_' . $slider . '">';
        
        if( $prev_next_buttons ){ $out .= '<div id="wpss_prev_' . $slider . '"></div>'; }
        
        $out .= '<div class="wpss_slideshow_">';

            while( $slides->have_posts() ): $slides->the_post();
            
            $featured_url = ( has_post_thumbnail( $post->ID ) ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) ) : false ;

            if( $featured_url ){
                $out .= '<div data-featured_image="' . esc_attr( $featured_url[0] ) . '" id="slide-' . $post->ID . '" class="wpss_slide">';
            }

            $type = get_post_meta($post->ID , '_slide_type' , true);
            
            //No Images = NO Images!
            if( ! has_post_thumbnail( $post->ID) )
                $type = 'Full Width Text';
            
            $wpss_default_path = WPSS_ABSPATH . 'templates';
            
            if( file_exists( trailingslashit( TEMPLATEPATH ) . 'wpss' ) ){
                $wpss_templatepath = trailingslashit( TEMPLATEPATH ) . 'wpss';
            } else {
                $wpss_templatepath = $wpss_default_path;
            }

            switch ( $type ){

                case 'Full Width Image':
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpss/{$slug}_full-width-image.php
                     * theme/wpss/full-width-image.php
                     * wp-slick-slider/templates/full-width-image.php
                     */
                    if( file_exists( $wpss_templatepath . '/' . $slider . '_full-width-image.php' ) ){
                        $include = $wpss_templatepath . '/' . $slider . '_full-width-image.php';
                    } elseif( file_exists( $wpss_templatepath . '/full-width-image.php' ) ) {
                        $include = $wpss_templatepath . '/full-width-image.php';
                    } else {
                        $include = $wpss_default_path . '/full-width-image.php';
                    }
                    
                    break;

                case 'Left Half Image':
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpss/{$slug}_left-half-image.php
                     * theme/wpss/left-half-image.php
                     * wp-slick-slider/templates/left-half-image.php
                     */
                    if( file_exists( $wpss_templatepath . '/' . $slider . '_left-half-image.php' ) ){
                        $include = $wpss_templatepath . '/' . $slider . '_left-half-image.php';
                    } elseif( file_exists( $wpss_templatepath . '/left-half-image.php' ) ) {
                        $include = $wpss_templatepath . '/left-half-image.php';
                    } else {
                        $include = $wpss_default_path . '/left-half-image.php';
                    }
                    
                    break;

                case 'Right Half Image':
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpss/{$slug}_right-half-image.php
                     * theme/wpss/right-half-image.php
                     * wp-slick-slider/templates/right-half-image.php
                     */
                    if( file_exists( $wpss_templatepath . '/' . $slider . '_right-half-image.php' ) ){
                        $include = $wpss_templatepath . '/' . $slider . '_right-half-image.php';
                    } elseif( file_exists( $wpss_templatepath . '/right-half-image.php' ) ) {
                        $include = $wpss_templatepath . '/right-half-image.php';
                    } else {
                        $include = $wpss_default_path . '/right-half-image.php';
                    }

                    break;

                case 'Full Width Text':
                default:
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpss/{$slug}_full-width-text.php
                     * theme/wpss/full-width-text.php
                     * wp-slick-slider/templates/full-width-text.php
                     */
                    if( file_exists( $wpss_templatepath . '/' . $slider . '_full-width-text.php' ) ){
                        $include = $wpss_templatepath . '/' . $slider . '_full-width-text.php';
                    } elseif( file_exists( $wpss_templatepath . '/full-width-text.php' ) ) {
                        $include = $wpss_templatepath . '/full-width-text.php';
                    } else {
                        $include = $wpss_default_path . '/full-width-text.php';
                    }

                    break;

            }
            
            ob_start();
            
            include $include;
            
            $out .= ob_get_contents();
            
            ob_end_clean();

            if( $featured_url ){
                $out .= '</div>';
            }

            endwhile;



        $out .= '</div>';

        if( $pager ){ $out .= '<div id="wpss_slideshow_pager_' . $slider . '"></div>'; }
        
        if( $prev_next_buttons ){ $out .= '<div id="wpss_next_' . $slider . '"></div>'; }
        
        
        
    $out .= '</div><!--end WP Slick Slider slideshow-->';
    
    if( $pause_resume_links ):
        $out .= '<a href="javascript:void(0)" class="wpss_pause_' . $slider . '">' . __( 'Pause' , 'wp_slick_slider' ) . '</a>';
        $out .= '<a href="javascript:void(0)" class="wpss_resume_' . $slider . '">' . __( 'Resume' , 'wp_slick_slider' ) . '</a>';
    endif;

    else: //No Slides Found

    $out = '<!-- ' . __( 'No Slides Are In The Selected Slider Or The Slider Selected Has Been Incorrectly Named.' , 'wp_slick_slider' ) . ' -->';

    endif; 
    
    //wp_reset_query(); //This should have done it.
    
    wp_reset_postdata();
    
    //query_posts( $original_query ); //Force Reset.
    
    if( ! $echo )
        return $out;

    echo $out;
    
}


/*
 * Shortcode
 */
function sc_wpss( $atts ){
    extract(shortcode_atts(array(
        'name' => false
    ), $atts));

    if( ! $name )
        return false;
    
    return wp_slick_slider( $name,  false );
    
}
add_shortcode( 'SlickSlider' , 'sc_wpss' );

?>