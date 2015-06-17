<?php
/*
 * Admin Options Page And Save Function.
 */

class WPSS_OPTIONS {

    /*
     * Function to run on Init.
     */
    public static function Init(){

        register_setting( 'wpss_options_group', 'wpss_options' );

        add_settings_section( 'wpss_general_options', 'General Options', array('WPSS_OPTIONS', 'General_Overview'), 'wpss_options_page' );

        /*
         * General Option Fields
         */
        $use_css_data_arr = array(
            'id'          => 'wpss_use_css',
            'description' => __( 'Uncheck This Box To Disable WP Slick Slider From Using CSS', 'wp_slick_slider' )
        );
        add_settings_field( 'wpss_use_css', __( 'Disable CSS?', 'wp_slick_slider' ) , array('WPSS_OPTIONS', 'Checkbox'), 'wpss_options_page', 'wpss_general_options', $use_css_data_arr );

        $use_js_data_arr = array(
            'id' => 'wpss_use_js',
            'description' => __( 'Uncheck This Box To Disable WP Slick Slider From Using Javascript.<br /> ( Not recommended unless you are going to add it somewhere else, it is necessary to animate the slides! )', 'wp_slick_slider' )
        );
        add_settings_field( 'wpss_use_js', __( 'Disable Javascript?', 'wp_slick_slider' ), array('WPSS_OPTIONS', 'Checkbox'), 'wpss_options_page', 'wpss_general_options', $use_js_data_arr );

        $footer_creds_data_arr = array(
            'id' => 'wpss_footer_credits',
            'description' => __( 'If you like this plugin, you can enable the addition of a small link in the footer of your website to the author of the plugin', 'wp_slick_slider' )
        );
        add_settings_field( 'wpss_footer_credits', __( 'Enable Footer Credits?', 'wp_slick_slider' ), array('WPSS_OPTIONS', 'Checkbox'), 'wpss_options_page', 'wpss_general_options', $footer_creds_data_arr );


        /*
         * Hook Up The Form Fields For the Add / Edit Slider Forms.
         */
        add_action( 'wpss_sliders_edit_form_fields' , array( 'WPSS_OPTIONS' , 'Edit_Sliders_Fields' ), 10, 2 );
        add_action( 'wpss_sliders_add_form_fields' , array( 'WPSS_OPTIONS' , 'Add_Sliders_Fields' ), 10, 1 );

        add_action( 'created_wpss_sliders' , array( 'WPSS_OPTIONS' , 'Wpss_Save_Slider_Data' ) , 10, 2 );
        add_action( 'edited_wpss_sliders' , array( 'WPSS_OPTIONS' , 'Wpss_Save_Slider_Data' ) , 10, 2 );

    }

    /*
     * Add the admin menus
     */
    public static function Add_Admin_Menus(){

        add_submenu_page( 'edit.php?post_type=wpss_slides' , __( 'Slick Slider Options', 'wp_slick_slider' ) , 'Options', 'edit_posts', 'wpss-options' , array('WPSS_OPTIONS', 'Options_Page') );

    }

    /*
     * Default Options For The Sliders
     */
    public static function Get_Default_Sliders_Options(){
        $opts = array(
            'wpss_timeout'           => 2000,
            'wpss_speed'             => 500,
            'wpss_pauseonhover'      => 'off',
            'wpss_pauseonpagerhover' => 'off',
            'wpss_effects'           => array( 'fade' ),
            'wpss_easing'            => 'swing',
            'wpss_height'            => 250,
            'wpss_outerwidth'        => 620,
            'wpss_fullwidth'         => 620,
            'wpss_halfwidth'         => 310,
            'wpss_useprevnext'       => 'on',
            'wpss_usepauseresume'    => false,
            'wpss_usepager'          => 'on',
            'wpss_usepagerimages'    => 'off',
            'wpss_order'             => 'slide_number'
            );
        return $opts;
    }

    /*
     * Create the options page
     */
    public static function Options_Page(){ global $wpss_options; ?>

        <div class="wrap">

            <h2 style="clear:both;"><?php _e( 'WP Slick Slider Options' ); ?></h2>

            <form method="post" action="options.php">

                <?php settings_fields( 'wpss_options_group' ); ?>

                <?php do_settings_sections( 'wpss_options_page' ); ?>

                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>

            </form>

        </div>

    <?php }

    /*
     * Create the HTML for the extension of the edit sliders form.
     */
    public static function Edit_Sliders_Fields( $tag, $taxonomy ){

        $data = get_option( 'wpss_' . $tag->slug . '_options' );

        if( ! $data ){ //Something is very wrong...
            $data = WPSS_OPTIONS::Get_Default_Sliders_Options();
        }

        ?>
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_timeout"><?php _e( 'Timeout' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <input name="wpss_timeout" id="wpss_timeout" type="text" value="<?php echo $data['wpss_timeout']; ?>" size="40" /><br />
                    <span class="description"><?php _e( 'This Controls The Timeout Value Of The Slider', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_speed"><?php _e( 'Speed' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <input name="wpss_speed" id="wpss_speed" type="text" value="<?php echo $data['wpss_speed']; ?>" size="40" /><br />
                    <span class="description"><?php _e( 'This Controls The Speed Of The Actual Transition', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_pauseonhover"><?php _e( 'Pause On Hover' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpss_pauseonhover'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpss_pauseonhover" id="wpss_pauseonhover" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e( 'Check This Box To Pause The Slideshow Advance When The User Hovers Over It', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_pauseonpagerhover"><?php _e( 'Pause On Pager Hover' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpss_pauseonpagerhover'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpss_pauseonpagerhover" id="wpss_pauseonpagerhover" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Pause The Slideshow Advance When The User Hovers Over The Pager', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_useprevnext"><?php _e( 'Use Previous Next Buttons' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpss_useprevnext'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpss_useprevnext" id="wpss_useprevnext" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Enable The Previous And Next Buttons', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>


        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_usepauseresume"><?php _e( 'Use Pause And Resume Links' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpss_usepauseresume'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpss_usepauseresume" id="wpss_usepauseresume" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Enable The Pause And Resume Links', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_usepager"><?php _e( 'Use Pager Links?' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpss_usepager'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpss_usepager" id="wpss_usepager" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Use A Pager For Your Slider', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_usepagerimages"><?php _e( 'Use Featured Images as Pager Links?' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpss_usepagerimages'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpss_usepagerimages" id="wpss_usepagerimages" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Use Featured Images Of Each Slide As Pager Links If Using A Pager', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>


        <?php //Effects ?>
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_effects"><?php _e( 'Effects' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <p class="description"><?php _e('These are the effects used during the transition, you can select as many as you like.', 'wp_slick_slider' ); ?></p>
                    <?php
                    $effects = array(
                        'all', 'blindX', 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'fade', 'fadeZoom',
                        'growX', 'growY', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert',
                        'shuffle', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover',
                        'wipe', 'zoom', 'none'
                    );

                    foreach( $effects as $effect ){
                        $checked = ( in_array( $effect , $data['wpss_effects'] ) ) ? 'checked="checked"' : '' ;
                        echo '<p style="width:80px; float:left; margin-top:0; margin-bottom:0"><input style="width:15px; height:15px;" ' . $checked . ' type="checkbox" name="wpss_effects[]" value="' . $effect . '" /><br /><span class="description"><b>' . $effect . '</b></span></p>';
                    }
                    ?>
                    <div style="clear:both"></div>
                </td>
        </tr>

        <?php //Easing ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_easing"><?php _e( 'Easing' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <?php
                    $easings = array(
                        'linear', 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic',
                        'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint',
                        'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo',
                        'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic',
                        'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce', 'none'
                    );
                    ?>
                    <select name="wpss_easing" id="wpss_easing">
                        <?php
                        foreach( $easings as $easing ){
                            $selected = ( $easing == $data['wpss_easing'] ) ? 'selected="selected"' : '' ;
                            echo '<option ' . $selected . ' value="' . $easing . '">' . $easing . '</option>';
                        }
                        ?>
                    </select>
                    <br /><span class="description"><?php _e('This is the easing used in the transition.', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <?php //Height ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_height"><?php _e( 'Height' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <input name="wpss_height" id="wpss_speed" type="text" value="<?php echo $data['wpss_height']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Height Of The Slider', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <?php //Outerwidth ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_outerwidth"><?php _e( 'Outer Width' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <input name="wpss_outerwidth" id="wpss_outerwidth" type="text" value="<?php echo $data['wpss_outerwidth']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Outer Width Of The Slider Container', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <?php //Fullwidth ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_fullwidth"><?php _e( 'Full Width' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <input name="wpss_fullwidth" id="wpss_speed" type="text" value="<?php echo $data['wpss_fullwidth']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Full Width Image Width', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <?php //Halfwidth ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpss_halfwidth"><?php _e( 'Half Width' , 'wp_slick_slider'); ?></label></th>
                <td>
                    <input name="wpss_halfwidth" id="wpss_speed" type="text" value="<?php echo $data['wpss_halfwidth']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Half Width Image Width', 'wp_slick_slider' ); ?></span>
                </td>
        </tr>

        <?php //Slide Order ?>

        <tr class="form-field">
            <?php $opts = array(
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'slide_number' => 'Slide Number',
                'random' => 'Random'
            );
            ?>
            <th scope="row" valign="top"><label for="wpss_order"><?php _e( 'Slide Order' , 'wp_slick_slider'); ?></label></th>
            <td>
                <select name="wpss_order" id="wpss_order">
                    <?php foreach( $opts as $key => $opt ){
                        $selected = ( $key == $data['wpss_order'] ) ? 'selected="selected"' : '' ;
                        echo '<option ' . $selected . ' value="' . $key . '">' . $opt . '</option>';
                    } ?>
                </select>
                <p><?php _e('Set The Order To Use When Selecting The Slides.', 'wp_slick_slider' ); ?></p>
            </td>
        </tr>

        <?php
    }

    /*
     * Create the HTML for the extension of the add slider form.
     */
    public static function Add_Sliders_Fields( $taxonomy ){
        $data = WPSS_OPTIONS::Get_Default_Sliders_Options();
        ?>

        <div class="form-field">
                <label for="wpss_timeout"><?php _e( 'Timeout' , 'wp_slick_slider'); ?></label>
                <input name="wpss_timeout" id="wpss_timeout" type="text" value="<?php echo $data['wpss_timeout']; ?>" size="40" />
                <p><?php _e('This Controls The Timeout Value Of The Slider', 'wp_slick_slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpss_speed"><?php _e( 'Speed' , 'wp_slick_slider'); ?></label>
                <input name="wpss_speed" id="wpss_speed" type="text" value="<?php echo $data['wpss_speed']; ?>" size="40" /><br />
                <p><?php _e('This Controls The Speed Of The Actual Transition', 'wp_slick_slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpss_pauseonhover"><?php _e( 'Pause On Hover' , 'wp_slick_slider'); ?></label>
                <input style="width:15px;" name="wpss_pauseonhover" id="wpss_pauseonhover" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Pause The Slideshow Advance When The User Hovers Over It', 'wp_slick_slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpss_pauseonpagerhover"><?php _e( 'Pause On Pager Hover' , 'wp_slick_slider'); ?></label>
                <input style="width:15px;" name="wpss_pauseonpagerhover" id="wpss_pauseonpagerhover" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Pause The Slideshow Advance When The User Hovers Over The Pager', 'wp_slick_slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpss_useprevnext"><?php _e( 'Use Previous Next Buttons' , 'wp_slick_slider'); ?></label>
                <input style="width:15px;" name="wpss_useprevnext" id="wpss_useprevnext" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Enable The Previous Next Buttons', 'wp_slick_slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpss_usepauseresume"><?php _e( 'Use Pause And Resume Links' , 'wp_slick_slider'); ?></label>
                <input style="width:15px;" name="wpss_usepauseresume" id="wpss_usepauseresume" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Enable The Pause And Resume Links', 'wp_slick_slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpss_usepager"><?php _e( 'Use Pager Links?' , 'wp_slick_slider'); ?></label>
                <input style="width:15px;" name="wpss_usepager" id="wpss_usepager" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Use A Pager For Your Slider', 'wp_slick_slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpss_usepagerimages"><?php _e( 'Use Featured Images As Pager Links?' , 'wp_slick_slider'); ?></label>
                <input style="width:15px;" name="wpss_usepagerimages" id="wpss_usepagerimages" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Use Featured Images Of Each Slide As Pager Links If Using A Pager', 'wp_slick_slider' ); ?></p>
        </div>

        <?php //Effects ?>
        <div class="form-field">
                <label for="wpss_effects"><?php _e( 'Effects' , 'wp_slick_slider'); ?></label>
                <p><?php _e('These are the effects used during the transition, you can select as many as you like.', 'wp_slick_slider' ); ?></p>
                <?php
                    $effects = array(
                        'all', 'blindX', 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'fade', 'fadeZoom',
                        'growX', 'growY', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert',
                        'shuffle', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover',
                        'wipe', 'zoom', 'none'
                    );

                    foreach( $effects as $effect ){
                        $checked = ( in_array( $effect , $data['wpss_effects'] ) ) ? 'checked="checked"' : '' ;
                        echo '<p style="width:80px; float:left; margin-top:0; margin-bottom:0"><input style="width:15px; height:15px;" ' . $checked . ' type="checkbox" name="wpss_effects[]" value="' . $effect . '" /><br /><span class="description"><b>' . $effect . '</b></span></p>';
                    }
                ?>
                <div style="clear:both;"></div>
        </div>

        <?php //Easing ?>

        <div class="form-field">
                <label for="wpss_easing"><?php _e( 'Easing' , 'wp_slick_slider'); ?></label>
                <?php
                    $easings = array(
                        'linear', 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic',
                        'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint',
                        'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo',
                        'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic',
                        'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce', 'none'
                    );
                ?>
                <select name="wpss_easing" id="wpss_easing">
                    <?php
                    foreach( $easings as $easing ){
                        //$selected = ( $easing == $data['wpss_easing'] ) ? 'selected="selected"' : '' ;
                        echo '<option value="' . $easing . '">' . $easing . '</option>';
                    }
                    ?>
                </select>
                <p><?php _e('This is the easing used in the transition.', 'wp_slick_slider' ); ?></p>
        </div>

        <?php //Height ?>

        <div class="form-field">
                <label for="wpss_height"><?php _e( 'Height' , 'wp_slick_slider'); ?></label>
                <input name="wpss_height" id="wpss_height" type="text" value="<?php echo $data['wpss_height']; ?>" size="40" />
                <p><?php _e('The Height Of The Slider', 'wp_slick_slider' ); ?></p>
        </div>

        <?php //Outerwidth ?>

        <div class="form-field">
                <label for="wpss_outerwidth"><?php _e( 'Outer Width' , 'wp_slick_slider'); ?></label>
                <input name="wpss_outerwidth" id="wpss_outerwidth" type="text" value="<?php echo $data['wpss_outerwidth']; ?>" size="40" />
                <p><?php _e('The Outer Width Of The Slider Container', 'wp_slick_slider' ); ?></p>
        </div>

        <?php //Fullwidth ?>

        <div class="form-field">
                <label for="wpss_fullwidth"><?php _e( 'Full Width' , 'wp_slick_slider'); ?></label>
                <input name="wpss_fullwidth" id="wpss_fullwidth" type="text" value="<?php echo $data['wpss_fullwidth']; ?>" size="40" />
                <p><?php _e('The Full Width Image Width', 'wp_slick_slider' ); ?></p>
        </div>

        <?php //Halfwidth ?>

        <div class="form-field">
                <label for="wpss_halfwidth"><?php _e( 'Half Width' , 'wp_slick_slider'); ?></label>
                <input name="wpss_halfwidth" id="wpss_halfwidth" type="text" value="<?php echo $data['wpss_halfwidth']; ?>" size="40" />
                <p><?php _e('The Half Width Image Width', 'wp_slick_slider' ); ?></p>
        </div>

        <?php //Order ?>

        <div class="form-field">
            <?php $opts = array(
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'slide_number' => 'Slide Number',
                'random' => 'Random'
            );
            ?>
            <label for="wpss_order"><?php _e( 'Slide Order' , 'wp_slick_slider'); ?></label>
            <select name="wpss_order" id="wpss_order">
                <?php foreach( $opts as $key => $opt ){
                    //$selected = ( $key == $data['wpss_order'] ) ? 'selected="selected"' : '' ;
                    echo '<option value="' . $key . '">' . $opt . '</option>';
                } ?>
            </select>
            <p><?php _e('Set The Order To Use When Selecting The Slides.', 'wp_slick_slider' ); ?></p>
        </div>

        <?php
    }

    /*
     * Save the data
     */
    public static function Wpss_Save_Slider_Data( $term_id, $tt_id ){
        $fields = array(
            'wpss_timeout',
            'wpss_speed',
            'wpss_pauseonhover',
            'wpss_pauseonpagerhover',
            'wpss_effects',
            'wpss_easing',
            'wpss_height',
            'wpss_fullwidth',
            'wpss_halfwidth',
            'wpss_useprevnext',
            'wpss_usepauseresume',
            'wpss_usepager',
            'wpss_usepagerimages',
            'wpss_outerwidth',
            'wpss_order'
        );

        //Get the original data
        $data = WPSS_OPTIONS::Get_Default_Sliders_Options();

        $from_posteditor = false;

        if( isset( $_POST['tag-name'] ) ){
            $slider_name = $_POST['tag-name'];
        } elseif( isset( $_POST['name'] ) ) {
            $slider_name = $_POST['name'];
        } else {
            $slider_name = $_POST['newwpss_sliders'];
            $from_posteditor = true;
        }

        $slug = ( empty( $_POST['slug'] ) ) ? strtolower( str_replace( ' ' , '-' , $slider_name ) ) :  $_POST['slug'] ;

        //Overwrite Defaults If Has Been Posted
        foreach( $fields as $field ){

            if( isset( $_POST[$field] ) ){
                $data[$field] = $_POST[$field];
            } elseif( ! $from_posteditor ) {
                $data[$field] = false;
            }

        }
        update_option( 'wpss_' . $slug . '_options' , $data );

    }

    /*
     * Output General Info
     */
    public static function General_Overview(){
        _e( '<p>These are the general options for controlling the WP Slick Slider. You can control the effects of each slider individually, This is done in the slider edit page.</p>', 'wp_slick_slider' );
    }

    /**
     *
     * Output A Checkbox, $data should be in the following format:
     * $data = array(
     *  'id' => 'id_of_the_element',
     *  'description' => 'Option Description.'
     * )
     */
    public static function Checkbox( $data ){
        global $WP_Slick_Slider;

        $checked = ( isset( $WP_Slick_Slider->options[ $data['id'] ] ) ) ? 'checked="checked"' : '' ;

        echo '<input ' . $checked . ' id="' . $data['id'] . '" name="wpss_options[' . $data['id'] . ']" type="checkbox" /> <span class="description">' . $data['description'] . '</span>';

    }

    /*
     * Output a Text input field
     */
    public static function TextInput( $data ){
        global $WP_Slick_Slider;

        echo '<input id="' . $data['id'] . '" name="wpss_options[' . $data['id'] . ']" size="40" type="text" value="' . $WP_Slick_Slider->options[ $data['id'] ] . '" /> <span class="description">' . $data['description'] . '</span>';

    }

    /*
     * Output A Select Field
     */
    public static function Select( $data ){
        global $WP_Slick_Slider;

        echo '<select name="wpss_options[' . $data['id'] . ']">';
        foreach( $data['options'] as $option ){

            $selected = ( $option == $WP_Slick_Slider->options[ $data['id'] ] ) ? 'selected="selected"' : '' ;
            echo '<option ' . $selected . ' value="' . $option . '">' . $option . '</option>';

        }
        echo '</select> <span class="description">' . $data['description'] . '</span>';

    }

    /*
     * Output A Multiselect Field
     */
    public static function MultiSelect( $data ){
        global $WP_Slick_Slider;

        echo '<select style="height:auto;" name="wpss_options[' . $data['id'] . '][]" multiple="multiple">';
        foreach( $data['options'] as $option ){

            $selected = ( in_array( $option, $WP_Slick_Slider->options[ $data['id'] ] ) ) ? 'selected="selected"' : '' ;
            echo '<option ' . $selected . ' value="' . $option . '">' . $option . '</option>';

        }
        echo '</select> <span class="description">' . $data['description'] . '</span>';

    }


}
?>