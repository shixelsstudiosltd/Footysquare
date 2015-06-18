<?php
function theme_option() {
	global $post;
	$px_theme_option = get_option('px_theme_option');
?>
<link href="<?php echo get_template_directory_uri()?>/css/admin/datePicker.css" rel="stylesheet" type="text/css" />
<form id="frm" method="post" action="javascript:theme_option_save('<?php echo admin_url('admin-ajax.php');?>', '<?php echo get_template_directory_uri()?>');">
  <div class="theme-wrap fullwidth">
    <div class="loading_div"></div>
    <div class="form-msg"></div>
    <div class="inner">
      <div class="row"> 
        <!-- Left Column Start -->
        <div class="col1">
            <div class="main-nav">
                 <ul class="sub-menu categoryitems" style="display:block">
                    <li class="logo"><a href="#"><img src="<?php echo get_template_directory_uri()?>/images/admin/logo.png" /></a></li>
 	                <li><i class="fa fa-cogs"></i><a href="#tab-color" onClick="toggleDiv(this.hash);return false;">Gernal Settings</a></li>
                    <li><i class="fa fa-picture-o"></i><a href="#tab-logo" onClick="toggleDiv(this.hash);return false;">Logo / Fav Icon</a></li>
                    <li><i class="fa fa-tasks"></i><a href="#tab-head-scripts" onClick="toggleDiv(this.hash);return false;">Header Settings</a></li>
                    <li><i class="fa fa-tasks"></i><a href="#tab-footer-setting" onClick="toggleDiv(this.hash);return false;">Footer Settings</a></li>
                    <li><i class="fa fa-chain-broken"></i><a href="#tab-api-key" onClick="toggleDiv(this.hash);return false;">API Settings</a></li>
                    <li><i class="fa fa-tasks"></i><a href="#tab-other" onClick="toggleDiv(this.hash);return false;">Other Settings</a></li>
                    <li><i class="fa fa-table"></i><a href="#tab-pointstable-settings" onClick="toggleDiv(this.hash);return false;">Points Table Settings</a></li>
                    <li><i class="fa fa-bullhorn"></i><a href="#tab-advertisement-banner" onClick="toggleDiv(this.hash);return false;">Banner Settings</a></li>
                    <li><i class="fa fa-users"></i><a href="#tab-social-sharing" onClick="toggleDiv(this.hash);return false;">Social Links</a></li>
                    <li><i class="fa fa-globe"></i><a href="#tab-upload-languages" onClick="toggleDiv(this.hash);return false;">Translations</a></li>
                    <li><i class="fa fa-columns"></i><a href="#tab-manage-sidebars" onClick="toggleDiv(this.hash);return false;">Manage Sidebars</a></li>
                    <li><i class="fa fa-columns"></i><a href="#tab-default-pages" onClick="toggleDiv(this.hash);return false;">Default Pages</a></li>
                    <li><i class="fa fa-floppy-o"></i> <a href="#tab-import-export" onClick="toggleDiv(this.hash);return false;">Theme Options Backup</a></li> 
                     <li class="to-field">
                     <i class="fa fa-paste"></i>
                        <input type="button" value="Restore Default" onclick="px_to_restore_default('<?php echo admin_url('admin-ajax.php');?>', '<?php echo get_template_directory_uri()?>')" />
                     </li>      
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <!-- Left Column End -->
        <div class="col2">
          <input type="submit" id="submit_btn" name="submit_btn" class="topbtn" value="Save All Settings" />
          <!-- Color And Style Start -->
          <div id="tab-color">
            <div class="theme-header">
              <h1>General Settings</h1>
            </div>
            <div class="opt-head">
              <h4>Theme color Settings</h4>
              <div class="clear"></div>
            </div>
            <ul class="form-elements">
              
              <li class="to-field">
              <label class="to-label">Custom Color Scheme</label>
                <input type="text" name="custom_color_scheme" value="<?php echo $px_theme_option['custom_color_scheme']?>" class="bg_color" />
              </li>
            </ul>
             
            <ul class="form-elements noborder">
             
              <li class="to-field">
              	<label class="to-label">Header Background Color</label>
                <input type="text" name="header_bg_color" value="<?php echo $px_theme_option['header_bg_color']?>" class="bg_color" data-default-color="" />
              </li>
            </ul>
            <ul class="form-elements noborder">
             
              <li class="to-field">
              	<label class="to-label">Navigation Background Color</label>
                <input type="text" name="nav_bg_color" value="<?php echo $px_theme_option['nav_bg_color']?>" class="bg_color" data-default-color="" />
              </li>
            </ul>
            <ul class="form-elements noborder">
             
              <li class="to-field">
              	<label class="to-label">Navigation Font Color</label>
                <input type="text" name="nav_color" value="<?php echo $px_theme_option['nav_color']?>" class="bg_color" data-default-color="" />
              </li>
            </ul>
            
            <div class="clear"></div>
            <div class="theme-help">
              <h4 style="padding-bottom:0px;">Layout Options</h4>
              <div class="clear"></div>
            </div>
             <ul class="form-elements">
              <li class="to-label">
                <label>Layout Option</label>
              </li>
              <li class="to-field">
               <span id="wrapper_boxed_layoutoptions1"> <input type="radio" name="layout_option"  value="wrapper_boxed" <?php if($px_theme_option['layout_option']=="wrapper_boxed")echo "checked"?> class="styled" /></span>
                <label>Boxed</label>
                <span id="wrapper_boxed_layoutoptions2"><input type="radio" name="layout_option"  value="wrapper" <?php if($px_theme_option['layout_option']=="wrapper")echo "checked"?> class="styled" /></span>
                <label>Wide</label>
              </li>
            </ul>
            <div id="layout-background-theme-options" <?php if($px_theme_option['layout_option']=="wrapper"){echo 'style="display: none;"';}?>	>
            <div class="clear"></div>
            <div class="theme-help">
              <h4 style="padding-bottom:0px;">Background Options</h4>
              <div class="clear"></div>
            </div>
            <ul class="form-elements">
              <li class="to-label">
                <label>Background Image</label>
              </li>
              <li class="to-field">
                <div class="meta-input pattern">
                  <?php
				  
					for ( $i = 0; $i < 8; $i++ ) {
					?>
                  <div class='radio-image-wrapper'>
                    <input <?php if($px_theme_option['bg_img']==$i)echo "checked"?> onclick="select_bg()" type="radio" name="bg_img" class="radio" value="<?php echo $i?>" />
                    <label for="radio_<?php echo $i?>"> <span class="ss"><img src="<?php echo get_template_directory_uri()?>/images/background/bg<?php echo $i?>.png" height="50" width="50" /></span> <span <?php if($px_theme_option['bg_img']==$i)echo "class='check-list'"?> id="check-list">&nbsp;</span> </label>
                  </div>
                  <?php }?>
                </div>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Background Image</label>
              </li>
              <li class="to-field">
                <input id="bg_img_custom" name="bg_img_custom" value="<?php echo $px_theme_option['bg_img_custom'] ?>" type="text" class="small" />
                <input id="bg_img_custom" name="bg_img_custom" type="button" class="uploadfile left" value="Browse"/>
                <?php if ( $px_theme_option['bg_img_custom'] <> "" ) { ?>
                <div class="thumb-preview" id="bg_img_custom_img_div"> <img src="<?php echo $px_theme_option['bg_img_custom']?>"   /> <a href="javascript:remove_image('bg_img_custom')" class="del">&nbsp;</a> </div>
                <?php } ?>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Position</label>
              </li>
              <li class="to-field">
                <input type="radio" name="bg_position" value="left" <?php if($px_theme_option['bg_position']=="left")echo "checked"?> class="styled" />
                <label>Left</label>
                <input type="radio" name="bg_position" value="center" <?php if($px_theme_option['bg_position']=="center")echo "checked"?> class="styled" />
                <label>Center</label>
                <input type="radio" name="bg_position" value="right" <?php if($px_theme_option['bg_position']=="right")echo "checked"?> class="styled" />
                <label>Right</label>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Repeat</label>
              </li>
              <li class="to-field">
                <input type="radio" name="bg_repeat" value="no-repeat" <?php if($px_theme_option['bg_repeat']=="no-repeat")echo "checked"?> class="styled" />
                <label>No Repeat</label>
                <input type="radio" name="bg_repeat" value="repeat" <?php if($px_theme_option['bg_repeat']=="repeat")echo "checked"?> class="styled" />
                <label>Tile</label>
                <input type="radio" name="bg_repeat" value="repeat-x" <?php if($px_theme_option['bg_repeat']=="repeat-x")echo "checked"?> class="styled" />
                <label>Tile Horizontally</label>
                <input type="radio" name="bg_repeat" value="repeat-y" <?php if($px_theme_option['bg_repeat']=="repeat-y")echo "checked"?> class="styled" />
                <label>Tile Vertically</label>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Attachment</label>
              </li>
              <li class="to-field">
                <input type="radio" name="bg_attach" value="scroll" <?php if($px_theme_option['bg_attach']=="scroll")echo "checked"?> class="styled" />
                <label>Scroll</label>
                <input type="radio" name="bg_attach" value="fixed" <?php if($px_theme_option['bg_attach']=="fixed")echo "checked"?> class="styled" />
                <label>Fixed</label>
              </li>
            </ul>
            <ul class="form-elements">
              <li class="to-label">
                <label>Background Pattern</label>
              </li>
              <li class="to-field">
                <div class="meta-input pattern">
                	<?php
					for ( $i = 0; $i < 16; $i++ ) {
					?>
                  <div class='radio-image-wrapper'>
                    <input <?php if($px_theme_option['pattern_img']==$i)echo "checked"?> onclick="select_pattern()" type="radio" name="pattern_img" class="radio" value="<?php echo $i?>" />
                    <label for="radio_<?php echo $i?>"> <span class="ss"><img src="<?php echo get_template_directory_uri()?>/images/pattern/pattern<?php echo $i?>.jpg" height="50" width="50"  /></span> <span <?php if($px_theme_option['pattern_img']==$i)echo "class='check-list'"?> id="check-list">&nbsp;</span> </label>
                  </div>
                  <?php }?>
                </div>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Background Pattern</label>
              </li>
              <li class="to-field">
                <input id="custome_pattern" name="custome_pattern" value="<?php echo $px_theme_option['custome_pattern']; ?>" type="text" class="small" />
                <input id="custome_pattern" name="custome_pattern" type="button" class="uploadfile left" value="Browse"/>
                <?php if ( $px_theme_option['custome_pattern'] <> "" ) { ?>
                <div class="thumb-preview" id="custome_pattern_img_div"> <img height="50" width="50" src="<?php echo $px_theme_option['custome_pattern'];?>" /> <a href="javascript:remove_image('custome_pattern')" class="del">&nbsp;</a> </div>
                <?php }?>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Background Color</label>
              </li>
              <li class="to-field">
                <input type="text" name="bg_color" value="<?php echo $px_theme_option['bg_color']?>" class="bg_color" data-default-color="" />
              </li>
            </ul>
          </div>      
            
            <ul class="form-elements">
              <li class="to-label">
                <label>Analytics Code</label>
              </li>
              <li class="to-field">
                <textarea rows="" cols="" name="analytics"><?php echo $px_theme_option['analytics']?></textarea>
                <p>Paste your Google Analytics (or other) tracking code here.<br />
                  This will be added into the footer template of your theme.</p>
              </li>
            </ul>
          </div>
          <!-- Color And Style End --> 
          <!-- Logo Tabs -->
          <div id="tab-logo" style="display:none;">
            <div class="theme-header">
              <h1>Logo / Fav Icon Settings</h1>
            </div>
            <div class="opt-head">
              <h4>Logo Settings</h4>
              <div class="clear"></div>
            </div>
            <ul class="form-elements">
              <li class="to-label">
                <label>Upload Logo</label>
              </li>
              <li class="to-field">
                <input id="logo" name="logo" value="<?php echo $px_theme_option['logo']?>" type="text" 
                class="small {validate:{accept:'jpg|jpeg|gif|png|bmp'}}"  />
                <input id="log" name="logo"   type="button" class="uploadfile left" value="Browse"/>
                <?php if ( $px_theme_option['logo'] <> "" ) { ?>
                    <div class="thumb-preview" id="logo-preview"  style="min-height:<?php echo $px_theme_option['logo_height']?>px; min-width:<?php echo $px_theme_option['logo_width']?>px;"> 
                    	<img width="<?php echo $px_theme_option['logo_width']?>" height="<?php echo $px_theme_option['logo_height']?>" 
                        src="<?php echo $px_theme_option['logo']?>" /> 
                    	<a href="javascript:remove_image('logo')" class="del">&nbsp;</a> 
                    </div>
                <?php } ?>
               </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Width</label>
              </li>
              <li class="to-field">
                <input type="text" name="logo_width" id="width-value" value="<?php echo $px_theme_option['logo_width']?>" class="vsmall" />
                <span class="short">px</span>
                
              </li>
            </ul>
            <ul class="form-elements  noborder">
              <li class="to-label">
                <label>Height</label>
              </li>
              <li class="to-field">
                <input type="text" name="logo_height" id="height-value" value="<?php echo $px_theme_option['logo_height']?>" class="vsmall" />
                <span class="short">px</span>
                
              </li>
            </ul>
            <div class="opt-head">
              <h4>FAV Icon Settings</h4>
              <div class="clear"></div>
            </div>
            
            <ul class="form-elements">
               <li class="to-label">
                <label>FAV Icon</label>
              </li>
              <li class="to-field">
                <input id="favicon" name="fav_icon" value="<?php echo $px_theme_option['fav_icon']?>" type="text" class="small {validate:{accept:'ico|png'}}" />
                <input id="fav_icon" name="favicon" type="button" class="uploadfile left" value="Browse" />
                <?php if ( $px_theme_option['fav_icon'] <> "" ) { ?>
                    <div class="thumb-preview" id="favicon-preview"  style="min-height:50px; min-width:50px;"> 
                    	<img width="32" height="32" src="<?php echo $px_theme_option['fav_icon']?>" /> 
                    	<a href="javascript:remove_image('favicon')" class="del">&nbsp;</a> 
                    </div>
                <?php } ?>                
                <p>Browse a small fav icon, only .ICO or .PNG format allowed.</p>
              </li>
            </ul>
          </div>
          
          <!-- Logo Tabs End --> 
          
          <!-- Header Styles --> 
          
          <!-- Header Script -->
          <div id="tab-head-scripts" style="display:none;">
            <div class="theme-header">
              <h1>Header Settings</h1>
            </div>
            <div class="header-section" id="header_banner1">
             <div class="opt-head">
              <h4>Fixtures</h4>
              <div class="clear"></div>
            </div>
            
            <ul class="form-elements noborder">
            
            <li class="to-label">
                <label>Select event type</label>
              </li>
              <li class="to-field">
                <select name="fixture_type">
                  <option value="0">---- Select event ----</option>
                  <?php
                  ?>
                  <option <?php if(isset($px_theme_option['fixture_type']) and $px_theme_option['fixture_type']=='All')echo "selected"?>>All</option>
                  <option <?php if(isset($px_theme_option['fixture_type']) and $px_theme_option['fixture_type']=='Fixtures')echo "selected"?>>Fixtures</option>
                  <option <?php if(isset($px_theme_option['fixture_type']) and $px_theme_option['fixture_type']=='Results')echo "selected"?>>Results</option>
                </select>
               </li>
               </ul>
               
             <ul class="form-elements noborder">
            
              <li class="to-label">
                <label>Select Order</label>
              </li>
              <li class="to-field">
                <select name="fixture_order">
                  <option value="0">---- Order ----</option>
                  <?php
                  ?>
                  <option value="ASC" <?php if(isset($px_theme_option['fixture_order']) and $px_theme_option['fixture_order']=='ASC')echo "selected"?>>Ascending</option>
                  <option value="DESC" <?php if(isset($px_theme_option['fixture_order']) and $px_theme_option['fixture_order']=='DESC')echo "selected"?>>Descending</option>
                </select>
               </li>
               </ul>
               
              <ul class="form-elements noborder">
              <li class="to-label">
                <label>Choose Fixtures Category</label>
              </li>
              <li class="to-field">
                <select name="announcement_fixtures_category" class="dropdown">
                  <option value="">-- Select Category --</option>
				  <?php show_all_cats('', '', $px_theme_option['announcement_fixtures_category'], "event-category");?>
                </select>
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Show no of posts</label>
              </li>
              <li class="to-field">
                <input type="text" name="announcement_no_posts" size="5" value="<?php echo $px_theme_option['announcement_no_posts']?>" />
               
              </li>
            </ul>
           
	        <?php
			   $wpmlsettings=get_option('icl_sitepress_settings');
  			   if ( function_exists('icl_object_id') ) {
   			   ?>
              <ul class="form-elements noborder">
                <li class="to-field">
                <input type="hidden" name="header_languages" value="" />
                <label class="to-label">Header Languages</label>
                  <label class="cs-on-off">
                      <input type="checkbox" class="myClass" name="header_languages" <?php if ($px_theme_option['header_languages'] == "on") echo "checked" ?> />
                      <span></span>
                  </label>
                </li>
              </ul>
		  <?php } else { ?>
          		<input type="hidden" name="header_languages" value="" />
          <?php }?>

              
              <?php if ( function_exists( 'is_woocommerce' ) ){ ?>  
             <ul class="form-elements noborder">
              	
                <li class="to-field">
                <input type="hidden" name="header_cart" value=""/>
                <label class="to-label">Cart Count</label>
                  <label class="cs-on-off">
                      <input type="checkbox" name="header_cart" <?php if ($px_theme_option['header_cart'] == "on") echo "checked" ?>/>
                      <span></span>
                  </label>
                </li>
              </ul>
              <?php } else { ?>
              	<input type="hidden" name="header_cart" value=""/>
              <?php 
				}
				?>
		<ul class="form-elements noborder">
            <li class="to-field">
            <label class="to-label">Search</label>
              <label class="cs-on-off">
                  <input type="checkbox" name="header_search" <?php if (isset($px_theme_option['header_search']) && $px_theme_option['header_search'] == "on") echo "checked" ?>/>
                  <span></span>
              </label>
            </li>
          </ul>
          
          <ul class="form-elements noborder">
            <li class="to-field">
            <label class="to-label">BreadsCrumb</label>
              <label class="cs-on-off">
                  <input type="checkbox" name="header_breadcrumbs" <?php if (isset($px_theme_option['header_breadcrumbs']) && $px_theme_option['header_breadcrumbs'] == "on") echo "checked" ?>/>
                  <span></span>
              </label>
            </li>
          </ul>
          
          <ul class="form-elements noborder">
              <li class="to-label">
                <label>Header Code</label>
              </li>
              <li class="to-field">
                <textarea rows="" cols="" name="header_code"><?php echo $px_theme_option['header_code']?></textarea>
                <p>Paste your Html or Css Code here.</p>
              </li>
            </ul>
            </div>
             
          </div>
          <!-- Header Script End --> 
          <!-- Footer Settings -->
          <div id="tab-footer-setting" style="display:none;">
            <div class="theme-header">
              <h1>Footer Settings</h1>
            </div>
            
            <ul class="form-elements">
            <li class="to-field">
            <label class="to-label">Social Icon</label>
              <label class="cs-on-off">
                  <input type="checkbox" name="footer_social_icons" <?php if (isset($px_theme_option['footer_social_icons']) && $px_theme_option['footer_social_icons'] == "on") echo "checked" ?>/>
                  <span></span>
              </label>
            </li>
          </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Partners Title</label>
              </li>
              <li class="to-label">
                <input type="text" name="partners_title" value="<?php echo $px_theme_option['partners_title']?>" />
              </li>
              <li class="to-label">
                <label>Select Partner</label>
              </li>
              <li class="to-field">
                <select name="partners_gallery">
                  <option value="0">---- Select Partner ----</option>
                  <?php
                  	query_posts( array('posts_per_page' => "-1", 'post_status' => 'publish', 'post_type' => 'px_gallery') );
                    while ( have_posts()) : the_post();
                  ?>
                  <option <?php if($px_theme_option['partners_gallery']==$post->post_name)echo "selected"?> value="<?php echo $post->post_name;?>">
                  <?php the_title()?>
                  </option>
                  <?php endwhile; ?>
                </select>
               </li>
            </ul>
            
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Custom Copyright</label>
              </li>
              <li class="to-field">
                <textarea rows="2" cols="4" name="copyright"><?php echo $px_theme_option['copyright']?></textarea>
                <p>Write Custom Copyright text.</p>
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Powered By Text</label>
              </li>
              <li class="to-field">
                <textarea rows="2" cols="4" name="powered_by"><?php echo $px_theme_option['powered_by']?></textarea>
                <p>Please enter powered by text.</p>
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Analytics Code</label>
              </li>
              <li class="to-field">
                <textarea rows="" cols="" name="analytics"><?php echo $px_theme_option['analytics']?></textarea>
                <p>Paste your Google Analytics (or other) tracking code here.<br />
                  This will be added into the footer template of your theme.</p>
              </li>
            </ul>
          </div>
          <!-- Footer Settings End --> 
		  <!-- API Settings Start -->
          <div id="tab-api-key" style="display:none;">
              <div class="theme-header">
                <h1>API Setting</h1>
              </div>
              <div class="opt-head">
                <h4>MailChimp Setting</h4>
                <div class="clear"></div>
              </div>
              <ul class="form-elements">
              <li class="to-label">
                <label>MailChimp Key</label>
              </li>
              <li class="to-field">
                <input type="text" id="mailchimp_key" name="mailchimp_key" value="<?php if(isset($px_theme_option['mailchimp_key'])){ echo $px_theme_option['mailchimp_key'];}else{ $px_theme_option['mailchimp_key'] = '';}   ?>" />
                <p><?php echo __('Enter a valid MailChimp API key here to get started. Once you\'ve done that, you can use the MailChimp Widget from the Widgets menu. You will need to have at least MailChimp list set up before the using the widget.', 'mailchimp-widget'). __(' You can get your mailchimp activation key', 'Resources') . ' <u><a href="' . get_admin_url() . 'https://login.mailchimp.com/">' . __('here', 'KingsClub') . '</a></u>' ?> 				
			</p>
              </li>
            </ul>
               <div class="opt-head">
                <h4>Twitter API Setting</h4>
                <div class="clear"></div>
              </div>
               <ul class="form-elements noborder">
                <li class="to-label">
                  <label>Consumer Key</label>
                </li>
                <li class="to-field">
                  <input type="hidden" name="consumer_key" value="" />
                  <input type="text" id="consumer_key" name="consumer_key" value="<?php  echo $px_theme_option['consumer_key'];  ?>"  class="{validate:{required:true}}"/>
                </li>
              </ul>
              <ul class="form-elements noborder">
                <li class="to-label">
                  <label>Consumer Secret</label>
                </li>
                <li class="to-field">
                  <input type="hidden" name="consumer_secret" value="" />
                  <input type="text" id="consumer_secret" name="consumer_secret" value="<?php echo $px_theme_option['consumer_secret']; ?>" class="{validate:{required:true}}"/>
                </li>
              </ul>
              <ul class="form-elements noborder">
                <li class="to-label">
                  <label>Access Token</label>
                </li>
                <li class="to-field">
                  <input type="hidden" name="access_token" value="" />
                  <input type="text" id="access_token" name="access_token" value="<?php echo $px_theme_option['access_token']; ?>" class="{validate:{required:true}}"/>
                </li>
              </ul>
              <ul class="form-elements noborder">
                <li class="to-label">
                  <label>Access Token Secret</label>
                </li>
                <li class="to-field">
                  <input type="hidden" name="access_token_secret" value="" />
                  <input type="text" id="access_token_secret" name="access_token_secret" value="<?php echo $px_theme_option['access_token_secret']; ?>" class="{validate:{required:true}}"/>
                </li>
              </ul>
               <input type="hidden" id="submit_btn" name="twitter_setting" class="botbtn" value="Generate Bearer Token"  />
                 
                
          </div>
          <!-- API Settings end -->
          <!-- Other Settings Start -->
          <div id="tab-other" style="display:none;">
            <div class="theme-header">
              <h1>Other Setting</h1>
            </div>
            <ul class="form-elements">
              
              <li class="to-field">
              	<label class="to-label">Responsive</label>
                <input type="hidden" name="responsive" value="" />
                <label class="cs-on-off">
                    <input type="checkbox" name="responsive" <?php if(isset($px_theme_option['responsive']) && $px_theme_option['responsive']=="on") echo "checked" ?> />
                    <span></span>
                </label>
               
              </li>
            </ul>
            <ul class="form-elements noborder">
            
              <li class="to-field">
               <label class="to-label">Translation Switcher</label>
                <input type="hidden" name="trans_switcher" value="" />
                <label class="cs-on-off">
                    <input type="checkbox" name="trans_switcher" <?php if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher']=="on") echo "checked" ?> />
                    <span></span>
                </label>
               
              </li>
            </ul>
            <ul class="form-elements noborder">
            
              <li class="to-field">
               <label class="to-label">Color Switcher</label>
                <input type="hidden" name="color_switcher" value="" />
                <label class="cs-on-off">
                    <input type="checkbox" name="color_switcher" <?php if(isset($px_theme_option['color_switcher']) && $px_theme_option['color_switcher']=="on") echo "checked" ?> />
                    <span></span>
                </label>
               
              </li>
            </ul>
            <ul class="form-elements noborder">
            
              <li class="to-field">
              <label class="to-label">RTL Switcher</label>
                <input type="hidden" name="rtl_switcher" value="" />
                <label class="cs-on-off">
                    <input type="checkbox" name="rtl_switcher" <?php if(isset($px_theme_option['rtl_switcher']) && $px_theme_option['rtl_switcher']=="on") echo "checked" ?> />
                    <span></span>
                </label>
               
              </li>
            </ul>
          </div>
          <!-- Other Settings End --> 
          
          <div id="tab-pointstable-settings" style="display:none;">
          		<div class="theme-header">
            	<h1>Points Table Settings</h1>
            </div>
            <div class="opt-head">
                <h4>Points Table</h4>
                <div class="clear"></div>
            </div>     
            
            <ul class="form-elements  poped-up" id="add_pointtbale_link">
            	<li class="to-label">
                <label>Points Table Title</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_title" id="points_table_title"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 1</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_1" id="points_table_coloumn_field_1"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 2</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_2" id="points_table_coloumn_field_2"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 3</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_3" id="points_table_coloumn_field_3"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 4</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_4" id="points_table_coloumn_field_4"/>
              </li>
              <li class="full">&nbsp;</li>
              
              
              <li class="to-label">
                <label>Table Coloumn Title 5</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_5" id="points_table_coloumn_field_5"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 6</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_6" id="points_table_coloumn_field_6"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 7</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_7" id="points_table_coloumn_field_7"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 8</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_8" id="points_table_coloumn_field_8"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Table Coloumn Title 9</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="points_table_coloumn_field_9" id="points_table_coloumn_field_9"/>
              </li>
              <li class="full">&nbsp;</li>
              
              <li class="to-label"></li>
              <li class="to-field">
                <input type="button" value="Add" onclick="javascript:px_add_pointstable_coloumns('<?php echo admin_url()?>')" />
              </li>
            </ul>
            
            
            <div class="opt-head">
              <h4>Already Added Items</h4>
              <a href="javascript:openpopedup_social('add_pointtbale_link')" class="button add_pointtbale_link">Add Point Table Coloumns</a>
              <a href="javascript:closepopedup_social('add_pointtbale_link')" style="display:none;" class="button close_pointtbale_link">Close</a>
              <div class="clear"></div>
            </div>
             <div class="boxes">
             
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th class="centr">Actions</th>
                  </tr>
                </thead>
                <tbody id="pointtable_area">
                  <?php
						
					if ( isset($px_theme_option['points_table_title']) and is_array($px_theme_option['points_table_title']) and count($px_theme_option['points_table_title']) > 0 ) {
						$px_counter_pointstable = rand(10000,20000);
						$i = 0;
						$record_count=0;
						foreach ( $px_theme_option['points_table_title'] as $val ){
							
							$px_counter_pointstable++;
							echo '<tr id="del_'.$px_counter_pointstable.'">';
								echo '<td>'.$px_theme_option['points_table_title'][$i].'</td>';
								echo '<td class="centr"> 
											<a onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:social_icon_del('.$px_counter_pointstable.')"><i class="fa fa fa-times"></i></a>
											| <a href="javascript:px_toggle('.$px_counter_pointstable.')"><i class="fa fa-edit"></i></a>
										</td>';
							echo '</tr>';
							?>
                  <tr id="<?php echo $px_counter_pointstable;?>" style="display:none">
                    <td colspan="3">
                    <span class="theme-wrap"><a onclick="px_toggle('<?php echo $px_counter_pointstable?>')" ><img src="<?php echo get_template_directory_uri()?>/images/admin/close-red.png" /></a></span>
                    <ul class="form-elements">
                    	
                        <li class="to-label">
                          <label>Title</label>
                        </li>
                        <li class="to-field">
                          <input class="small" type="text"  name="points_table_title[]" id="points_table_title<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_title'][$i]?>" />
                          
                        </li>
                         <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 1</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_1[]" id="points_table_coloumn_field_1<?php echo $px_counter_pointstable?>"  value="<?php echo $px_theme_option['points_table_coloumn_field_1'][$i]?>"/>
                          </li> 
                          <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 2</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_2[]" id="points_table_coloumn_field_2<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_coloumn_field_2'][$i]?>"/>
                          </li> 
                          <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 3</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_3[]" id="points_table_coloumn_field_3<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_coloumn_field_3'][$i]?>"/>
                          </li> 
                          <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 4</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_4[]" id="points_table_coloumn_field_4<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_coloumn_field_4'][$i]?>"/>
                          </li> 
                          <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 5</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_5[]" id="points_table_coloumn_field_5<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_coloumn_field_5'][$i]?>"/>
                          </li> 
                         <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 6</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_6[]"  id="points_table_coloumn_field_6<?php echo $px_counter_pointstable?>"  value="<?php echo $px_theme_option['points_table_coloumn_field_6'][$i]?>"/>
                          </li> 
                          <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 7</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_7[]"  id="points_table_coloumn_field_7<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_coloumn_field_7'][$i]?>"/>
                          </li> 
                          <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 8</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_8[]"  id="points_table_coloumn_field_8<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_coloumn_field_8'][$i]?>"/>
                          </li> 
                          <li class="full">&nbsp;</li>
                          <li class="to-label">
                            <label>Table Coloumn Title 9</label>
                          </li>
                          <li class="to-field">
                            <input class="small" type="text" name="points_table_coloumn_field_9[]"  id="points_table_coloumn_field_9<?php echo $px_counter_pointstable?>" value="<?php echo $px_theme_option['points_table_coloumn_field_9'][$i]?>"/>
                          </li> 
                          
                       
                      </ul></td>
                  </tr>
                  <?php
						
					$i++;
					}
				}
				?>
                </tbody>
              </table>
            </div>
            
          </div>
          <div id="tab-advertisement-banner" style="display:none;">
            <div class="theme-header">
            	<h1>Banners Settings</h1>
            </div>
            <div class="opt-head">
                <h4>Advertisement Banners</h4>
                <div class="clear"></div>
            </div>     
            
             
            <div class="share_message"></div>
            <ul class="form-elements poped-up" id="add_banner_link">
            <li class="to-label">
                <label>Title</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="banner_title_input" id="banner_title_input"/>
                
              </li>
              <li class="full">&nbsp;</li>
             <li class="to-label">
                <label>Banner Type</label>
              </li>
              <li class="to-field">
              	<select name="banner_type_input" id="banner_type_input">
                	<option value="top_banner">Top Banner</option>
                    <option value="bottom_banner">Bottom Banner</option>
                    <option value="sidebar_banner">Sidebar Banner</option>
                    <option value="vertical_banner">Vertical Banner</option>
                </select>
              </li>
              <li class="to-label">
                <label>Banner Image Path</label>
              </li>
              <li class="to-field">
                <input id="banner_image_url" type="text" class="small" onblur="javascript:update_image('social_net_icon_path_input_img_div')" />
                <input id="banner_image_url" name="banner_image_url" type="button" class="uploadfile left" value="Browse"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Banner URL</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="banner_url_input" id="banner_url_input" />
                
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Adsense Code</label>
              </li>
              <li class="to-field">
                <textarea rows="10" cols="20"  name="adsense_input" id="adsense_input" /></textarea>
                
              </li>
              <li class="full">&nbsp;</li>
              
              <li class="to-label"></li>
              <li class="to-field">
                <input type="button" value="Add" onclick="javascript:px_add_banner_add('<?php echo admin_url('admin-ajax.php');?>')" />
              </li>
            </ul>
            
            <div class="opt-head">
              <h4>Already Added Items</h4>
              <a href="javascript:openpopedup_social('add_banner_link')" class="button add_banner_link">Add Banner</a>
              <a href="javascript:closepopedup_social('add_banner_link')" style="display:none;" class="button close_banner_link">Close</a>
              <div class="clear"></div>
            </div>
            <div class="boxes">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Banner Image Path</th>
                    <th>Shortcode</th>
                    <th class="centr">Actions</th>
                  </tr>
                </thead>
                <tbody id="banner_ads_area">
                  <?php

					if ( isset($px_theme_option['banner_image_url']) and count($px_theme_option['banner_image_url']) > 0 ) {
		
						$px_counter_advertisement_banner = rand(10000,20000);
						$i = 0;
						foreach ( $px_theme_option['banner_image_url'] as $val ){
							$px_counter_advertisement_banner++;
							echo '<tr id="del_'.$px_counter_advertisement_banner.'">';
								echo '<td>'.$px_theme_option['banner_title_input'][$i].'</td>';
								if(isset($px_theme_option['banner_image_url'][$i]) && $px_theme_option['banner_image_url'][$i] <> ''){
									
									echo '<td><a href="'.$px_theme_option['banner_url_input'][$i].'" target="_blank"><img width="200" src="'.$px_theme_option['banner_image_url'][$i].'"></a></td>';
								} else {
									echo '<td></td>';
								}
								echo '<td>[ads no="'.$i.'"]</td>';
								
								echo '<td class="centr"> 
											<a onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:social_icon_del('.$px_counter_advertisement_banner.')"><i class="fa fa fa-times"></i></a>
											| <a href="javascript:px_toggle('.$px_counter_advertisement_banner.')"><i class="fa fa-edit"></i></a>
										</td>';
							echo '</tr>';
							?>
                  <tr id="<?php echo $px_counter_advertisement_banner;?>" style="display:none">
                    <td colspan="3">
                    <span class="theme-wrap"><a onclick="px_toggle('<?php echo $px_counter_advertisement_banner?>')" ><img src="<?php echo get_template_directory_uri()?>/images/admin/close-red.png" /></a></span>
                    <ul class="form-elements">
                    	
                        <li class="to-label">
                          <label>Title</label>
                        </li>
                        <li class="to-field">
                          <input class="small" type="text" id="banner_title_input" name="banner_title_input[]" value="<?php echo $px_theme_option['banner_title_input'][$i]?>" />
                          
                        </li>
                         <li class="full">&nbsp;</li>
                         <li class="to-label">
                            <label>Banner Type</label>
                          </li>
                          <li class="to-field">
                            <select name="banner_type_input[]" id="banner_type_input">
                                <option value="top_banner" <?php if($px_theme_option['banner_type_input'][$i] == 'top_banner') echo 'selected="selected"';?>)>Top Banner</option>
                                <option value="bottom_banner" <?php if($px_theme_option['banner_type_input'][$i] == 'bottom_banner') echo 'selected="selected"';?>>Bottom Banner</option>
                                <option value="sidebar_banner" <?php if($px_theme_option['banner_type_input'][$i] == 'sidebar_banner') echo 'selected="selected"';?>>Sidebar Banner</option>
                                <option value="vertical_banner" <?php if($px_theme_option['banner_type_input'][$i] == 'vertical_banner') echo 'selected="selected"';?>>Vertical Banner</option>
                            </select>
                          </li>
                         <li class="full">&nbsp;</li>
                        <li class="to-label">
                          <label>Banner Image Path</label>
                        </li>
                        <li class="to-field">
                          <input id="social_net_icon_path<?php echo $px_counter_advertisement_banner?>" name="banner_image_url[]" value="<?php echo $px_theme_option['banner_image_url'][$i]?>" type="text" class="small" />
                        </li>
                        <li class="full">&nbsp;</li>
                        <li class="to-label">
                          <label>Banner URL</label>
                        </li>
                        <li class="to-field">
                          <input class="small" type="text" id="banner_url_input" name="banner_url_input[]" value="<?php echo $px_theme_option['banner_url_input'][$i]?>" />
                        </li>
                        <li class="full">&nbsp;</li>
                        <li class="to-label">
                          <label>Adsense Code</label>
                        </li>
                        <li class="to-field">
                        	<textarea rows="10" cols="20"  name="adsense_input[]" id="adsense_input" /><?php echo $px_theme_option['adsense_input'][$i]?></textarea>
                        </li>
                       
                      </ul></td>
                  </tr>
                  <?php
							$i++;
						}
					}
				?>
                </tbody>
              </table>
            </div>
           </div>
          
          <div id="tab-social-sharing" style="display:none;">
            <div class="theme-header">
            	<h1>Social Links</h1>
            </div>
            <div class="opt-head">
                <h4>Social Network and Share</h4>
                <div class="clear"></div>
            </div>     
            
             <ul class="form-elements">
             <li class="to-field">
               <label class="to-label">Social share</label>
                <input type="hidden" name="social_share" value="" />
                <label class="cs-on-off">
                    <input type="checkbox" name="social_share" <?php if($px_theme_option['social_share']=="on") echo "checked" ?> />
                    <span></span>
                </label>
              </li>
            </ul>
            <div class="share_message"></div>
            <ul class="form-elements poped-up" id="add_social_link">
              <li class="to-label">
                <label>Icon Path</label>
              </li>
              <li class="to-field">
                <input id="social_net_icon_path_input" type="text" class="small" onblur="javascript:update_image('social_net_icon_path_input_img_div')" />
                <input id="social_net_icon_path_input" name="social_net_icon_path_input" type="button" class="uploadfile left" value="Browse"/>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Awesome Font</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" id="social_net_awesome_input" />
                
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>URL</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" id="social_net_url_input" />
                
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Title</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" id="social_net_tooltip_input"/>
                
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label"></li>
              <li class="to-field">
                <input type="button" value="Add" onclick="javascript:px_add_social_icon('<?php echo admin_url('admin-ajax.php');?>')" />
              </li>
            </ul>
            
            <div class="opt-head">
              <h4>Already Added Items</h4>
              <a href="javascript:openpopedup_social('add_social_link')" class="button add_social_link">Add Social link</a>
              <a href="javascript:closepopedup_social('add_social_link')" style="display:none;" class="button close_social_link">Close</a>
              <div class="clear"></div>
            </div>
            <div class="boxes">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th>Icon Path</th>
                    <th>URL</th>
                    <th class="centr">Actions</th>
                  </tr>
                </thead>
                <tbody id="social_network_area">
                  <?php
					if ( isset($px_theme_option['social_net_url']) and count($px_theme_option['social_net_url']) > 0 ) {
						wp_enqueue_style('font-awesome_css', get_template_directory_uri() . '/css/font-awesome.css');
						// Register stylesheet
						wp_register_style( 'font-awesome-ie7_css', get_template_directory_uri() . '/css/font-awesome-ie7.css' );
						// Apply IE conditionals
						$GLOBALS['wp_styles']->add_data( 'font-awesome-ie7_css', 'conditional', 'lte IE 9' );
						// Enqueue stylesheet
						wp_enqueue_style( 'font-awesome-ie7_css' );
						$px_counter_social_network = rand(10000,20000);
						$i = 0;
						foreach ( $px_theme_option['social_net_url'] as $val ){
							$px_counter_social_network++;
							echo '<tr id="del_'.$px_counter_social_network.'">';
								if(isset($px_theme_option['social_net_awesome'][$i]) && $px_theme_option['social_net_awesome'][$i] <> ''){
									echo '<td><i style="color: green;" class="fa '.$px_theme_option['social_net_awesome'][$i].' 2x"></td>';
								} else {
									echo '<td><img width="50" src="'.$px_theme_option['social_net_icon_path'][$i].'"></td>';
								}
								echo '<td>'.$val.'</td>';
								echo '<td class="centr"> 
											<a onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:social_icon_del('.$px_counter_social_network.')"><i class="fa fa fa-times"></i></a>
											| <a href="javascript:px_toggle('.$px_counter_social_network.')"><i class="fa fa-edit"></i></a>
										</td>';
							echo '</tr>';
							?>
                  <tr id="<?php echo $px_counter_social_network;?>" style="display:none">
                    <td colspan="3">
                    <span class="theme-wrap"><a onclick="px_toggle('<?php echo $px_counter_social_network?>')" ><img src="<?php echo get_template_directory_uri()?>/images/admin/close-red.png" /></a></span>
                    <ul class="form-elements">
                        <li class="to-label">
                          <label>Icon Path</label>
                        </li>
                        <li class="to-field">
                          <input id="social_net_icon_path<?php echo $px_counter_social_network?>" name="social_net_icon_path[]" value="<?php echo $px_theme_option['social_net_icon_path'][$i]?>" type="text" class="small" />
                        </li>
                        <li class="full">&nbsp;</li>
                        <li class="to-label">
                          <label>Awesome Font</label>
                        </li>
                        <li class="to-field">
                          <input class="small" type="text" id="social_net_awesome" name="social_net_awesome[]" value="<?php echo $px_theme_option['social_net_awesome'][$i]?>" />
                          
                        </li>
                        <li class="full">&nbsp;</li>
                        <li class="to-label">
                          <label>URL</label>
                        </li>
                        <li class="to-field">
                          <input class="small" type="text" id="social_net_url" name="social_net_url[]" value="<?php echo $val?>" />
                         
                        </li>
                        <li class="full">&nbsp;</li>
                        <li class="to-label">
                          <label>Title</label>
                        </li>
                        <li class="to-field">
                          <input class="small" type="text" id="social_net_tooltip" name="social_net_tooltip[]" value="<?php echo $px_theme_option['social_net_tooltip'][$i]?>" />
                          
                        </li>
                      </ul></td>
                  </tr>
                  <?php
							$i++;
						}
					}
				?>
                </tbody>
              </table>
            </div>
           </div>
          
          <div id="tab-upload-languages" style="display:none;">
            <div class="theme-header">
              <h1>Translations</h1>
             </div>
             
             
            <div class="opt-head">
              <h4>Upload New Language</h4>
              <div class="clear"></div>
            </div>
            <div class="message-box">
                <div class="messagebox alert alert-info">
                    <button type="button" class="close" data-dismiss="alert"><em class="fa fa-times"></em></button>
                    <div class="masg-text">
                        <p>Please upload new language file (MO format only). It will be uploaded in your theme's languages folder. </p>
                        <p>Download MO files from <a target="_blank" href="http://translate.wordpress.org/projects/wp/">http://translate.wordpress.org/projects/wp/</a> </p>
                    </div>
                </div>
            </div>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Upload Language (MO File)</label>
              </li>
              <li class="to-field">
                <div class="fileinputs">
                  <input type="file" class="file" size="78" name="mofile" id="mofile" />
                  <div class="fakefile">
                    <input type="text" />
                    <button>Browse</button>
                  </div>
                </div>
                
               
                <p>
                  <button type="button" id="upload_btn">Upload Files!</button>
                </p>
              </li>
            </ul>
            <ul id="image-list">
            </ul>
            <ul class="form-elements  noborder">
              <li class="to-label">
                <label>Already Uploaded Languages</label>
              </li>
              <li class="to-field"> <strong>
                <?php
					$px_counter = 0;
					foreach (glob(get_template_directory()."/languages/*.mo") as $filename) {
						$px_counter++;
						$val = str_replace(get_template_directory()."/languages/","",$filename);
						echo "<p>".$px_counter . ". " . str_replace(".mo","",$val)."</p>";
					}
				?>
                </strong>
                <p>Please copy the language name, open config.php file, find WPLANG constant and set its value by replacing the language name. </p>
              </li>
            </ul>
            <div class="opt-head">
              <h4>Translation</h4>
              <div class="clear"></div>
            </div>
            
             
            <ul class="form-elements noborder">
            <li class="to-label">
              <label>Start (Kick-of)</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_event_start" value="<?php echo $px_theme_option['trans_event_start']?>" />
            </li>
          </ul>
          <ul class="form-elements noborder">
            <li class="to-label">
              <label>VS</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_event_vs" value="<?php echo $px_theme_option['trans_event_vs']?>" />
            </li>
          </ul>
            <ul class="form-elements noborder">
            <li class="to-label">
              <label>Match Goals</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_event_goals" value="<?php echo $px_theme_option['trans_event_goals']?>" />
            </li>
          </ul>
          <ul class="form-elements noborder">
            <li class="to-label">
              <label>Born (age)</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_player_born" value="<?php echo $px_theme_option['trans_player_born']?>" />
            </li>
          </ul>
            <ul class="form-elements noborder">
            <li class="to-label">
              <label>Location</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_player_location" value="<?php echo $px_theme_option['trans_player_location']?>" />
            </li>
          </ul>
          <ul class="form-elements noborder">
            <li class="to-label">
              <label>Position</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_player_postion" value="<?php echo $px_theme_option['trans_player_postion']?>" />
            </li>
          </ul>
          <ul class="form-elements noborder">
            <li class="to-label">
              <label>Squad Number</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_player_squad" value="<?php echo $px_theme_option['trans_player_squad']?>" />
            </li>
          </ul>
          <ul class="form-elements noborder">
            <li class="to-label">
              <label>Debut date</label>
            </li>
            <li class="to-field">
              <input type="text" name="trans_player_debut_date" value="<?php echo $px_theme_option['trans_player_debut_date']?>" />
            </li>
          </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>First Name</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_firstname" value="<?php if(isset($px_theme_option['trans_firstname'])) echo $px_theme_option['trans_firstname']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Last Name</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_lastname" value="<?php if(isset($px_theme_option['trans_lastname'])) echo $px_theme_option['trans_lastname']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Subject</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_subject" value="<?php echo $px_theme_option['trans_subject']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Message</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_message" value="<?php echo $px_theme_option['trans_message']?>" />
              </li>
            </ul>
               <ul class="form-elements noborder">
              <li class="to-label">
                <label>Phone</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_other_phone" value="<?php echo $px_theme_option['trans_other_phone']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Fax</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_other_fax" value="<?php echo $px_theme_option['trans_other_fax']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Email Spam</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_email_published" value="<?php echo $px_theme_option['trans_email_published']?>" />
              </li>
            </ul>
              
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Share Now</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_share_this_post" value="<?php echo $px_theme_option['trans_share_this_post']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Posted on</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_posted_on" value="<?php echo $px_theme_option['trans_posted_on']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Listed in</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_listed_in" value="<?php echo $px_theme_option['trans_listed_in']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Featured</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_featured" value="<?php echo $px_theme_option['trans_featured']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Read More</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_read_more" value="<?php echo $px_theme_option['trans_read_more']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>View All</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_viewall" value="<?php echo $px_theme_option['trans_viewall']?>" />
              </li>
            </ul>
             <ul class="form-elements noborder">
              <li class="to-label">
                <label>Position</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_pos" value="<?php echo $px_theme_option['trans_pos']?>" />
              </li>
            </ul>
             <ul class="form-elements noborder">
              <li class="to-label">
                <label>Team</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_team" value="<?php echo $px_theme_option['trans_team']?>" />
              </li>
            </ul>
             <ul class="form-elements noborder">
              <li class="to-label">
                <label>Play</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_play" value="<?php echo $px_theme_option['trans_play']?>" />
              </li>
            </ul>
             <ul class="form-elements noborder">
              <li class="to-label">
                <label>+/- Points</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_plusminus" value="<?php echo $px_theme_option['trans_plusminus']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Total Points</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_totalpoints" value="<?php echo $px_theme_option['trans_totalpoints']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>From</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_from" value="<?php if(isset($px_theme_option['trans_from']))echo $px_theme_option['trans_from']?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Current Page</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_currentpage" value="<?php if(isset($px_theme_option['trans_currentpage']))echo $px_theme_option['trans_currentpage']?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Photos</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_photo" value="<?php if(isset($px_theme_option['trans_photo']))echo $px_theme_option['trans_photo']; ?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Add to Calendar</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_add_calendar" value="<?php if(isset($px_theme_option['trans_add_calendar']))echo $px_theme_option['trans_add_calendar']; ?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Previous</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_previous" value="<?php if(isset($px_theme_option['trans_previous']))echo $px_theme_option['trans_previous']; ?>" />
              </li>
            </ul>
            
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Headlines</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_headlines" value="<?php if(isset($px_theme_option['trans_headlines']))echo $px_theme_option['trans_headlines']; ?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Recent</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_recent" value="<?php if(isset($px_theme_option['trans_recent']))echo $px_theme_option['trans_recent']; ?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Popular</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_popular" value="<?php if(isset($px_theme_option['trans_popular']))echo $px_theme_option['trans_popular']; ?>" />
              </li>
            </ul>
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Out of</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_out_of" value="<?php if(isset($px_theme_option['trans_out_of']))echo $px_theme_option['trans_out_of']; ?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Days</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_days" value="<?php if(isset($px_theme_option['trans_days']))echo $px_theme_option['trans_days']; ?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Hours</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_hours" value="<?php if(isset($px_theme_option['trans_hours']))echo $px_theme_option['trans_hours']; ?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Minutes</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_minutes" value="<?php if(isset($px_theme_option['trans_minutes']))echo $px_theme_option['trans_minutes']; ?>" />
              </li>
            </ul>
            
            <ul class="form-elements noborder">
              <li class="to-label">
                <label>Seconds</label>
              </li>
              <li class="to-field">
                <input type="text" name="trans_seconds" value="<?php if(isset($px_theme_option['trans_seconds']))echo $px_theme_option['trans_seconds']; ?>" />
              </li>
            </ul>
            
            
            
            </div>
          <div id="tab-manage-sidebars" style="display:none;">
            <div class="theme-header">
              <h1>Manage Sidebars</h1>
            </div>
            <ul class="form-elements">
              <li class="to-label">
                <label>Sidebar Name</label>
              </li>
              <li class="to-field">
                <input class="small" type="text" name="sidebar_input" id="sidebar_input" />
                <input type="button" value="Add Sidebar" onclick="javascript:add_sidebar()" />
              </li>
            </ul>
            <div class="opt-head">
              <h4>Already Added Sidebars</h4>
              <div class="clear"></div>
            </div>
            <div class="boxes">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th>Sider Bar Name</th>
                    <th class="centr">Actions</th>
                  </tr>
                </thead>
                <tbody id="sidebar_area">
                  <?php
					if ( isset($px_theme_option['sidebar']) and count($px_theme_option['sidebar']) > 0 ) {
						$px_counter_sidebar = rand(10000,20000);
						foreach ( $px_theme_option['sidebar'] as $sidebar ){
							$px_counter_sidebar++;
							echo '<tr id="'.$px_counter_sidebar.'">';
								echo '<td><input type="hidden" name="sidebar[]" value="'.$sidebar.'" />'.$sidebar.'</td>';
								echo '<td class="centr"> <a onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:px_div_remove('.$px_counter_sidebar.')">Del</a> </td>';
							echo '</tr>';
						}
					}
					?>
                </tbody>
              </table>
            </div>
          </div>  
          <div id="tab-default-pages" style="display:none;">
            <div class="theme-header">
              <h1>Default Pages Settings</h1>
            </div>
            <ul class="form-elements">
              <li class="to-label">
                <label>Pagination</label>
              </li>
              <li class="to-field">
                <select name="pagination" class="dropdown" onchange="px_toggle('record_per_page')">
                  <option <?php if($px_theme_option['pagination']=="Show Pagination")echo "selected";?> >Show Pagination</option>
                  <option <?php if($px_theme_option['pagination']=="Single Page")echo "selected";?> >Single Page</option>
                </select>
              </li>
            </ul>
            <?php
				global $px_xmlObject;
				$px_xmlObject = new stdClass();
				$px_xmlObject->sidebar_layout = new stdClass();
				$px_xmlObject->sidebar_layout->px_layout = $px_theme_option['px_layout'];
				$px_xmlObject->sidebar_layout->px_sidebar_left = $px_theme_option['px_sidebar_left'];
				$px_xmlObject->sidebar_layout->px_sidebar_right = $px_theme_option['px_sidebar_right'];
				if ( $px_theme_option['px_layout'] == "none" ) {
					$px_xmlObject->sidebar_layout->px_sidebar_left = '';
					$px_xmlObject->sidebar_layout->px_sidebar_right = '';
				}
				else if ( $px_theme_option['px_layout'] == "left" ) {
					$px_xmlObject->sidebar_layout->px_sidebar_right = '';
				}
				else if ( $px_theme_option['px_layout'] == "right" ) {
					$px_xmlObject->sidebar_layout->px_sidebar_left = '';
				}
				meta_layout('default');
			?>
          </div>
           <!-- import export Start -->
          <div id="tab-import-export" style="display:none;">
            <div class="theme-header">
              <h1>Backup Options</h1>
            </div>
             <?php /*?><ul class="form-elements">
              <li class="to-label">
                <label>Last Backup Taken on</label>
              </li>
              <li class="to-field"> <strong><span id="last_backup_taken">
                <?php 
						if ( get_option('px_theme_option_backup_time') ) {
							echo get_option('px_theme_option_backup_time');
						}
						else { echo "Not Taken Yet"; }
					?>
                </span></strong> </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Take Backup</label>
              </li>
              <li class="to-field">
                <input type="button" value="Take Backup" onclick="px_to_backup('<?php echo home_url()?>', '<?php echo get_template_directory_uri()?>')" />
                <p>Take the Backup of your current theme options, it will replace the old backup if you have already taken.</p>
              </li>
              <li class="full">&nbsp;</li>
              <li class="to-label">
                <label>Restore Backup</label>
              </li>
              <li class="to-field">
                <input type="button" value="Restore Backup" onclick="px_to_backup_restore('<?php echo home_url()?>', '<?php echo get_template_directory_uri()?>')" />
                <p>Restore your last backup taken (It will be replaced on your curernt theme options).</p>
              </li>
            </ul><?php */?>
            <ul class="form-elements">
            <li class="to-label">
              <label>Current Theme Option Data</label>
            </li>
            <li class="to-field">
                <textarea id="theme_option_data"  readonly="readonly" onclick="this.select()"><?php echo base64_encode(serialize($px_theme_option)); ?></textarea>
              <p>You can copy your current theme data in a text file and import it later by replacing the above text.</p>
            </li>
          </ul>
          <ul class="form-elements noborder">
            <li class="to-label">
              <label>Import Theme Option Data</label>
            </li>
            <li class="to-field">
                <textarea id="theme_option_data_import" name="theme_option_data_import"></textarea>
              <p>You can paste theme option backup data.</p>
              <p><input type="button" value="Import This Data" onclick="px_to_import_export('<?php echo admin_url('admin-ajax.php');?>', '<?php echo get_template_directory_uri()?>')" /></p>
            </li>
          </ul>
          </div>
          <!-- import / export end --> 
          
        </div>
        <div class="clear"></div>
        <!-- Right Column End --> 
      </div>
      <div class="footer">
        <input type="submit" id="submit_btn" name="submit_btn" class="botbtn" value="Save All Settings" />
        <input type="hidden" name="action" value="theme_option_save" />
      </div>
    </div>
  </div>
</form>
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap.min.css">
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-3.0.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/select.js"></script> 
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/functions.js"></script> 
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.validate.metadata.js"></script> 
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.validate.js"></script> 
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/ddaccordion.js"></script> 
<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.timepicker.js"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/jquery.ui.datepicker.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/jquery.ui.datepicker.theme.css">
<script type="text/javascript">
        jQuery(document).ready(function($){
            $('.bg_color').wpColorPicker(); 
        });
		 function load_default_settings(id) {
           jQuery("#" + id + " input.button.wp-picker-default").trigger('click');
           jQuery("#" + id + " input[type='checkbox'].myClass").each(function(index) {
             var da = jQuery(this).data('default-header');
             var ch = jQuery(this).next().hasClass("checked")
             if ((da == 'on') && (!ch)) {
               jQuery(this).next().trigger('click');
             }
             if ((da == 'off') && (ch)) {
               jQuery(this).next().trigger('click');
             }
           });
           jQuery("#" + id + " input[type='text'].vsmall").each(function(index) {
             var da = jQuery(this).data('default-header');
             jQuery(this).val(da);

           });
           jQuery("#" + id + " .to-field input.small").each(function(index) {
             var da = jQuery(this).data('default-header');
             jQuery(this).val(da);
             jQuery(this).parent().find(".thumb-preview").find('img').attr("src", da)
           });
           jQuery("#" + id + " textarea").each(function(index) {
             var da = jQuery(this).data('default-header');
             jQuery(this).val(da);

           });
           jQuery("#" + id + " select").each(function(index) {

             var da = jQuery(this).data('default-header');
             jQuery(this).find("option[value='" + da + "']").attr("selected", "selected");

           });

         }
    </script> 
<script type="text/javascript">
		jQuery(function($) {
			$( "#launch_date" ).datepicker({
            	defaultDate: "+1w",
				dateFormat: "yy-mm-dd",
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                	$( "#launch_date" ).datepicker( "option", "minDate", selectedDate );
                }
            });
		});
		  function toggleDiv(id)
  {
   jQuery('.col2').children().hide();
   jQuery(id).show();
            location.hash = id+"-show";
            var link = id.replace('#', '');
            jQuery('.categoryitems li').removeClass('active');
            jQuery(".menuheader.expandable") .removeClass('openheader');
            //jQuery(".categoryitems").hide();
            jQuery("."+link).addClass('active');
            jQuery("."+link) .parent("ul").show().prev().addClass("openheader");
      
  }
        jQuery(document).ready(function() {
                jQuery(".menuheader:first").addClass("openheader");
                jQuery(".menuheader").live('click', function(event) {
                    if (jQuery(this).hasClass('openheader')){
                        jQuery(".menuheader").removeClass("openheader");
                        jQuery(this).next().slideUp(200);
                        return false;
                    }
                    jQuery(".menuheader").removeClass("openheader");
                    jQuery(this).addClass("openheader");
                    jQuery(".categoryitems").slideUp(200);
                    jQuery(this).next().slideDown(200); 
                    return false;
             });
            var hash = window.location.hash.substring(1);
            var id = hash.split("-show")[0];
            if (id){
                jQuery('.col2').children().hide();
                jQuery("#"+id).show();
                jQuery('.categoryitems li').removeClass('active');
               // jQuery(".menuheader.expandable") .removeClass('openheader');
                //jQuery(".categoryitems").hide();
                jQuery("."+id).addClass('active');
                jQuery("."+id) .parent("ul").slideDown(300).prev().addClass("openheader");

           } 
        });

        var counter_sidebar = 0;
        function add_sidebar(){
            counter_sidebar++;
            var sidebar_input = jQuery("#sidebar_input").val();
            if ( sidebar_input != "" ) {
                jQuery("#sidebar_area").append('<tr id="'+counter_sidebar+'"> \
                            <td><input type="hidden" name="sidebar[]" value="'+sidebar_input+'" />'+sidebar_input+'</td> \
                            <td class="centr"> <a onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:px_div_remove('+counter_sidebar+')">Del</a> </td> \
                        </tr>');
                jQuery("#sidebar_input").val("");
            }
        }
		var counter_newsticker = 0;
		 function px_add_newsticker(){
            counter_newsticker++;
            var newsticker_input = jQuery("#newsticker_input").val();
            if ( newsticker_input != "" ) {
                jQuery("#newsticker_area").append('<tr id="'+counter_sidebar+'"> \
                            <td><input type="hidden" name="newsticker[]" value="'+newsticker_input+'" />'+newsticker_input+'</td> \
                            <td class="centr"> <a onclick="javascript:return confirm(\'Are you sure! You want to delete\')" href="javascript:px_div_remove('+counter_newsticker+')">Del</a> </td> \
                        </tr>');
                jQuery("#newsticker_input").val("");
            }
        }
		jQuery().ready(function($) {
			var container = $('div.container');
			// validate the form when it is submitted
			var validator = $("#frm").validate({
				errorContainer: container,
				errorLabelContainer: $(container),
				errorElement:'span',
				errorClass:'ele-error',				
				meta: "validate"
			});
		});
        jQuery(document).ready( function($) {
            var consoleTimeout;
            $('.minicolors').each( function() {
                $(this).minicolors({
                    change: function(hex, opacity) {
                        // Generate text to show in console
                        text = hex ? hex : 'transparent';
                        if( opacity ) text += ', ' + opacity;
                        text += ' / ' + $(this).minicolors('rgbaString');
                    }
                });
            });
        });
		(function () {
			var input = document.getElementById("mofile")
			var upload_btn = document.getElementById("upload_btn"), 
			formdata = false;
			if (window.FormData) {
				formdata = new FormData();
			}
			upload_btn.addEventListener("click", function (evt) {
				var i = 0, len = input.files.length, txt, reader, file;
			
				for ( ; i < len; i++ ) {
					file = input.files[i];
						if (formdata) {
							formdata.append("mofile[]", file);
						}
				}
				if (formdata) {
					jQuery.ajax({
						url: '<?php echo get_template_directory_uri()?>/include/lang_upload.php',
						type: "POST",
						data: formdata,
						processData: false,
						contentType: false,
						success: function (res) {
							jQuery("#mofile").val("");
		                    jQuery(".form-msg").show();
							jQuery(".form-msg").html(res);
						}
					});
				}
			}, false);
		}());
    </script>
<?php }?>
