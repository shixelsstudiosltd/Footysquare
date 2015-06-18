<?php
/**
 * General Functions File For WP Slick Slider
 */

/*
 * Echo Out The Option Driven JS.
 */
function wpss_js_init(){

global $WP_Slick_Slider;

$wpss_options = $WP_Slick_Slider->options;

    /*
     * get all the slides.
     */
    $slides = get_terms( 'wpss_sliders' );

    if( isset( $WP_Slick_Slider->options['wpss_use_js'] ) ){

        echo "<script type='text/javascript'>jQuery(document).ready(function($){ \n";
        //Cycle through them
        foreach( $slides as $slide ){

            $slider_opts = array( 'timeout' , 'pause', 'pauseOnPagerHover' , 'fx', 'easing', 'speed' );

            $data = get_option( 'wpss_' . $slide->slug . '_options' );

            //If A Slider Timeout Has Been Specified:
            $timeout = ( isset( $data['wpss_timeout'] ) ) ?  $data['wpss_timeout'] : 0 ;

            //Pause On Hover
            $pause = ( isset( $data['wpss_pauseonhover'] ) && $data['wpss_pauseonhover'] == 'on' ) ? 1 : false ;

            //Pause on Pager Hover?
            $pauseOnPagerHover = ( isset( $data['wpss_pauseonpagerhover'] ) && $data['wpss_pauseonpagerhover'] == 'on' ) ? 1 : false ;

            //wpss_slider_speed
            $speed = ( isset( $data['wpss_speed'] ) ) ? $data['wpss_speed']  : false ;

            //Effects
            $fx = false;
            if(  isset( $data['wpss_effects'] ) ){
                $fx = implode( ',', $data['wpss_effects'] );
            }

            //Easing
            $easing = ( isset( $data['wpss_easing'] ) ) ?  $data['wpss_easing'] : false ;

            echo "$( '#wpss_slideshow_" . $slide->slug . " .wpss_slideshow_' ).cycle({\n";
                foreach( $slider_opts as $opt ){
                    if( $$opt !== false ){
                        echo "'" . $opt . "' : '" . $$opt . "',\n";
                    }
                }
            //Use Previous Next Buttons?
            if( isset( $data['wpss_useprevnext'] ) && $data['wpss_useprevnext'] == 'on' ){
                echo "'prev' : '#wpss_prev_" . $slide->slug . "', 'next' : '#wpss_next_" . $slide->slug . "',";
            }

            //Pager
            echo "'pager' : '#wpss_slideshow_pager_" . $slide->slug . "'";
            //Use Images In Pager?
            if ( isset( $data['wpss_usepagerimages'] ) && $data['wpss_usepagerimages'] == 'on' ){
                echo ",\n";
                echo "'pagerAnchorBuilder' : function(i,el){ var imgsrc = $(el).data( 'featured_image' ), linkcontent = '<img src=\"' + imgsrc + '\" />'; if ( typeof imgsrc != 'undefined' && imgsrc.indexOf('src=&') > -1) { linkcontent = i + 1; } return '<a href=\"#\">' + linkcontent + '</a>'; }";
            }

            echo " \n}); \n\n";

            //Use Pause Resume Buttons?
            if( isset( $data['wpss_usepauseresume'] ) ){
                echo "$( '.wpss_pause_" . $slide->slug . "' ).click( function(){ $( '#wpss_slideshow_" . $slide->slug . " .wpss_slideshow_' ).cycle( 'pause' ) }) \n";
                echo "$( '.wpss_resume_" . $slide->slug . "' ).click( function(){ $( '#wpss_slideshow_" . $slide->slug . " .wpss_slideshow_' ).cycle( 'resume' ) }) \n";
            }

        }


        echo "})</script>";
    }//End if use JS

    if( isset( $WP_Slick_Slider->options['wpss_use_css'] ) ){

        echo '<style type="text/css" media="all">';

        foreach( $slides as $slide ){

            $data = get_option( 'wpss_' . $slide->slug . '_options' );

            //echo '/*' . var_dump( $data ) . /'*/';//wpss_outerwidth

            $outerwidth = $data['wpss_outerwidth'];
            $halfwidth = $data['wpss_halfwidth'];
            $fullwidth = $data['wpss_fullwidth'];
            $height = $data['wpss_height'];
            $halfHeight = $height / 2;
            $prevNextHeight = $halfHeight;
            $textHalfWidth = ( $fullwidth - $halfwidth ) - 20;
            $pagerTop = $height - 20 ;
            $prevBgPath = WPSS_HTTP_PATH . 'images/wpss_prev_arrow.png';
            $nextBgPath = WPSS_HTTP_PATH . 'images/wpss_next_arrow.png';

            echo '
            #wpss_slideshow_' . $slide->slug . '{ padding:10px; background-color:#F1F1F1; height:' . $height . 'px; overflow:hidden; position:relative; margin-bottom:5px; clear:both; width: ' . $outerwidth . 'px }
            #wpss_slideshow_' . $slide->slug . ' .wpss_img.half{ float:left; width:' . $halfwidth . 'px; }
            #wpss_slideshow_' . $slide->slug . ' .wpss_content_half{ padding: 10px; float:left; width:' . $textHalfWidth . 'px; }
            #wpss_slideshow_pager_' . $slide->slug . '{ position:absolute; z-index:1000; right:17px; top:' . $pagerTop . 'px; }
            #wpss_slideshow_pager_' . $slide->slug . ' a{ float:left; height:20px; width:20px; text-align:center; line-height:20px; color:#FFF; background-color:#000; margin-right:5px; }
            #wpss_slideshow_pager_' . $slide->slug . ' a:hover, #wpss_slideshow_pager_' . $slide->slug . ' a.activeSlide{ background-color:#1f1e1e; }
            #wpss_next_' . $slide->slug . '{ position:absolute; right: 0 ; top: ' . $prevNextHeight . 'px ; height:30px; width: 30px; cursor:pointer; z-index:1000; background: url(' . $nextBgPath . ') no-repeat; }
            #wpss_prev_' . $slide->slug . '{ position:absolute; left: 0 ; top: ' . $prevNextHeight . 'px ; height:30px; width: 30px; cursor:pointer; z-index:1000; background: url(' . $prevBgPath . ') no-repeat; }
            ';

        }

        echo '</style>';
    }

}
add_action( 'wp_head' , 'wpss_js_init' );

/**
 * Display the post content.
 *
 * Usage of the_content() in the WPSS Templates is not recommended as it may conflict with the
 *
 * @since 1.3
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool $stripteaser Optional. Strip teaser content before the more text. Default is false.
 */
function wpss_the_content($more_link_text = null, $stripteaser = false) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = apply_filters('wpss_the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	echo $content;
}