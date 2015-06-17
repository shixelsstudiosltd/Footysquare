<?php global $px_node,$px_counter_node,$px_theme_option ; 
px_enqueue_validation_script();

?>
	<script type="text/javascript">
        jQuery().ready(function($) {
            var container = $('');
            var validator = jQuery("#frm<?php echo $px_counter_node?>").validate({
                messages:{
                	contact_name: '',
                	contact_email:{
                		required: '',
                    	email:'',
                	},
                    subject: {
                        required:'',
                    },
                	contact_msg: '',
       	        },
                errorContainer: container,
                errorLabelContainer: jQuery(container),
                errorElement:'div',
                errorClass:'frm_error',
                meta: "validate"
            });
        });
        function frm_submit<?php echo $px_counter_node?>(){
            var $ = jQuery;
            $("#submit_btn<?php echo $px_counter_node?>").hide();
            $("#loading_div<?php echo $px_counter_node?>").html('<img src="<?php echo get_template_directory_uri()?>/images/ajax_loading.gif" alt="" />');
            $.ajax({
                type:'POST', 
                url: '<?php echo get_template_directory_uri()?>/page_contact_submit.php',
                data:$('#frm<?php echo $px_counter_node?>').serialize(), 
                success: function(response) {
                    //$('#frm').get(0).reset();
                    $("#loading_div<?php echo $px_counter_node?>").html('');
                    $("#form_hide<?php echo $px_counter_node?>").hide();
                    $("#succ_mess<?php echo $px_counter_node?>").show('');
                    $("#succ_mess<?php echo $px_counter_node?>").html(response);
                    //$('#frm_slide').find('.form_result').html(response);
                }
            });
        }
    </script>
    
    
    <div class="element_size_<?php echo $px_node->contact_element_size; ?>">
        <div class="inputforms respond">
        	<?php if($px_node->px_contact_form_title <> ''){?>
                    <!-- Comments Heading Start -->                            
                    <header class="pix-heading-title">
                        <h2 class="pix-section-title"><?php echo $px_node->px_contact_form_title;?></h2>
                    </header>
                    <!-- Comments Heading End --> 
            <?php }?> 
            <div class="textsection">
               <div class="succ_mess" id="succ_mess<?php echo $px_counter_node?>"  style="display:none;"></div>
            </div>
            <div id="form_hide<?php echo $px_counter_node;?>">
           		<div class="inquiry" id="respond">
                <form id="frm<?php echo $px_counter_node ?>" name="frm<?php echo $px_counter_node ?>" method="post" action="javascript:<?php echo "frm_submit".$px_counter_node. "()";
                ?>" novalidate>   
                
                	<p class="comment-form-author">
                        <label class="form-icons">
                            <small class="fa fa-ellipsis-v"></small>
                            <i class="fa fa-user"></i>
                            
                        </label>
                        <input type="text" name="contact_name" id="contact_name" class="nameinput {validate:{required:true}}"   value="<?php _e('Name', 'Kings Club'); ?>" />                        
                        <span>(<?php _e('required', 'TheSoccerClub');?>)</span>
                    </p>
                    
                    <p class="comment-form-email">
                    	<label class="form-icons">
                            <small class="fa fa-ellipsis-v"></small>
                            <i class="fa fa-envelope"></i>
                        </label>
                        <input type="text" name="contact_email" id="contact_email" class="emailinput {validate:{required:true ,email:true}}"   value="<?php _e('Email', 'Kings Club'); ?>" />
                        <span>(<?php _e('required', 'TheSoccerClub');?>)</span>
                    </p>
                    <p class="comment-form-contact">
                    	<label class="form-icons">
                            <small class="fa fa-ellipsis-v"></small>
                            <i class="fa fa-phone"></i>
                        </label>
                        <input type="text" name="subject" id="subject" class="subjectinput {validate:{required:true}}"   value="<?php if($px_theme_option['trans_switcher']== "on"){ _e('Subject','Kings Club');}else{ echo $px_theme_option['trans_subject']; } ?>" />
                    </p>
                    <p class="comment-form-comment">
                      <textarea name="contact_msg"   id="contact_msg" class="{validate:{required:true}}" ></textarea>    
                    </p>
                    <p class="form-submit">
                        <input type="hidden" value="<?php echo $px_node->px_contact_email ?>" name="px_contact_email">
                        <input type="hidden" value="<?php echo $px_node->px_contact_succ_msg ?>" name="px_contact_succ_msg">
                        <input type="hidden" name="bloginfo" value="<?php echo get_bloginfo() ?>" />
                        <input type="hidden" name="counter_node" value="<?php echo $px_counter_node ?>" />
                        <input type="submit" value="<?php _e('Submit', 'Kings Club'); ?>" id="submit_btn<?php echo $px_counter_node ?>" class="backcolr">
						<span><?php if($px_theme_option['trans_switcher'] == "on"){ _e('*Your Email will never published.','Kings Club');}else{ echo $px_theme_option['trans_email_published']; } ?></span>
                      </p>
                      <div id="loading_div<?php echo $px_counter_node ?>"></div>
                </form>
            </div>
            </div>
         </div>
  </div>
 