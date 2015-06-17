<?php
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_login_form($atts) {

	// Shortcode parameters
	extract( shortcode_atts( array(
		'url' => get_site_url(),
		'label_login' => __( 'Log In', WPS2_TEXT_DOMAIN ),
		'label_username' => __( 'Username', WPS2_TEXT_DOMAIN ),
		'label_email' => __( 'Email address', WPS2_TEXT_DOMAIN ),
		'label_password' => __( 'Password', WPS2_TEXT_DOMAIN ),
		'label_password_confirm' => __( 'Re-enter password', WPS2_TEXT_DOMAIN ),
		'label_register' => __( 'Register', WPS2_TEXT_DOMAIN ),
		'label_lostpassword' => __( 'Forgotten password?', WPS2_TEXT_DOMAIN ),
		'label_back_to_login' =>__('Back to login', WPS2_TEXT_DOMAIN),
		'label_nickname' => __( 'Nickname', WPS2_TEXT_DOMAIN ),
		'label_display_name' => __( 'Display name as shown on this site', WPS2_TEXT_DOMAIN ),
		'label_email_confirm' => __( 'Re-enter email address', WPS2_TEXT_DOMAIN ),
		'label_name' => __( 'first name and last name', WPS2_TEXT_DOMAIN ),
		'text_register_prompt' => __( 'An Email for verification will be sent to the email address you enter.', WPS2_TEXT_DOMAIN ),
		'text_lostpassword_prompt' => __( 'Enter your username or email address, and a new password will be sent to you.<br />You can then login and change it on the Edit Profile page.', WPS2_TEXT_DOMAIN ),
		'text_username_not_found' => __( 'Username or email address (%s) not found.', WPS2_TEXT_DOMAIN),
		'text_new_password' => __('Your new password is: %s', WPS2_TEXT_DOMAIN),
		'text_login_url' => site_url( $_SERVER['REQUEST_URI'] ),
		'text_password_reset' => __( 'Your password has been reset and sent to the email address of the account.<br />Please check your email in a few minutes, including the spam folder.<br />You can then login and change it on the Edit Profile page.', WPS2_TEXT_DOMAIN ),
        'text_password_closed' => __( 'This account was closed on %s at %s.', WPS2_TEXT_DOMAIN),
		'label_lostpassword_send' => __( 'Send new password', WPS2_TEXT_DOMAIN ),
		'mode' => 'login',
		'register' => 1,
		'registration_url' => '',
        'register_auto' => 0,
		'captcha' => 1,
		'lostpassword' => 1,
        'nickname' => 0,
        'name' => 1,
        'display_name' => 0,
		'town' => __('Town/City', WPS2_TEXT_DOMAIN),
		'country' => __('Country', WPS2_TEXT_DOMAIN),
        'password' => 0,
        'mandatory' => '<span style="color:red;"></span>',
		'before' => '',
		'after' => '',
	), $atts, 'wps_login_form' ) );
    
	$html = '';
    
    /* Multi-site? */
    if (is_multisite()) $url = get_site_url().$url;
    if (is_multisite()) $registration_url = get_site_url().$registration_url;
    
	global $current_user;

	if (!is_user_logged_in()):

		/* Follow-on/forced actions */
		if (isset($_POST['action']) || $mode == 'register'):

			$action = isset($_POST['action']) ? $_POST['action'] : 'register';
			
			//social login
			/*
			$html.='
				
			<div class="col-lg-12 col-md-12 no-padding">
				<p class="section-second-title">sign in to your account :</p>
				<div class="social_connect_ui ">
					<p class="comment-form-social-connect">
						<div class="social_connect_form">
							<label>Sign in using</label>
							<a href="javascript:void(0);" title="Facebook" class="social_connect_login_facebook">
								<img alt="Facebook" src="'.get_site_url().'/wp-content/plugins/social-connect/media/img/fb-cust.png" />
							</a>
							<label>or</label>
							<a href="javascript:void(0);" title="Twitter" class="social_connect_login_twitter">
								<img alt="Twitter" src="'.get_site_url().'/wp-content/plugins/social-connect/media/img/tw-cust.png" />
							</a>
						</div>
					</p>
		
				<div id="social_connect_facebook_auth">
					<input type="hidden" name="client_id" value="377783302381480" />
					<input type="hidden" name="redirect_uri" value="'.get_site_url().'/index.php?social-connect=facebook-callback" />
				</div>
				<div id="social_connect_twitter_auth"><input type="hidden" name="redirect_uri" value="'.get_site_url().'/index.php?social-connect=twitter" /></div>
			</div>';
			*/
			//add twitter etc as above
				//.social logi
			
			
			
			/* Register */
			
            if ($action == 'register'):
				//$html .= '<div id="wps_text_register_prompt">'.$text_register_prompt.'</div>';
				
				//registration header
				//$html .= '<div class="col-md-12 no-padding"><div class="div-section login-section"><div class="section-title"><i class="fa fa-user"></i><p>or signup in with your username / email :</p></div>';
				$html .= '<div class="login-sec-inner">';
				$html .= '<div class="notification-alert-brown">sign up :</div>';
				//$html .= '<p>Fields marked with an asterisk(*) are required!</p>';
				
                // Store redirection URL for JS use
                $html .= '<input type="hidden" id="wps_registration_url" value="'.$registration_url.'" />';
                $html .= '<input type="hidden" id="register_auto" value="'.$register_auto.'" />';

				$html .= '<input type="hidden" name="action" value="register2">';
				
				if ($name):
                    $html .= '<div id="wps_registration_name" class="wps_registration_row">';
                        //$html .= '<div class="wps_registration_label">'.$label_name.'</div>';
                        $html .= '<div class="wps_registration_field"><input type="text" class="wps_register_mandatory" id="wps_register_firstname" value="" placeholder="FIRST NAME"> ';
                        $html .= '<input type="text" class="wps_register_mandatory" id="wps_register_familyname" value="" placeholder="LAST NAME">'.$mandatory.'</div>';
                    $html .= '</div>';
                endif;
				
				$html .= '<div id="wps_registration_username" class="wps_registration_row">';
					//$html .= '<div class="wps_registration_label">'.$label_username.'</div>';
					$html .= '<div class="wps_registration_field"><input type="text" class="wps_register_mandatory" id="wps_register_username" value="" placeholder="USERNAME">'.$mandatory.'</div>';
				$html .= '</div>';
                if ($nickname):
                    $html .= '<div id="wps_registration_nickname" class="wps_registration_row">';
                        $html .= '<div class="wps_registration_label">'.$label_nickname.'</div>';
                        $html .= '<div class="wps_registration_field"><input type="text" class="wps_register_mandatory" id="wps_register_nickname" value="">'.$mandatory.'</div>';
                    $html .= '</div>';
                endif;
                if ($display_name):
                    $html .= '<div id="wps_registration_nickname" class="wps_registration_row">';
                        $html .= '<div class="wps_registration_label">'.$label_display_name.'</div>';
                        $html .= '<div class="wps_registration_field"><input type="text" class="wps_register_mandatory" id="wps_register_display_name" value="">'.$mandatory.'</div>';
                    $html .= '</div>';
                endif;
				$html .= '<div id="wps_registration_email" class="wps_registration_row">';
					//$html .= '<div class="wps_registration_label">'.$label_email.'</div>';
					$html .= '<div class="wps_registration_field"><input type="text" class="wps_register_mandatory" id="wps_register_email" value="" placeholder="EMAIL ADDRESS">'.$mandatory.'</div>';
				$html .= '</div>';
				$html .= '<div id="wps_registration_email_confirm" class="wps_registration_row">';
					//$html .= '<div class="wps_registration_label">'.$label_email_confirm.'</div>';
					$html .= '<div class="wps_registration_field"><input type="text" class="wps_register_mandatory" id="wps_register_email2" value="" placeholder="CONFIRM EMAIL ADDRESS">'.$mandatory.'</div>';
				$html .= '</div>';
                
				// Allow users to set password?
                if ($password):
                    $html .= '<div id="wps_registration_name" class="wps_registration_row">';
                        //$html .= '<div class="wps_registration_label">'.$label_password.'</div>';
                        $html .= '<input type="password" class="wps_register_mandatory" id="wps_register_password" value="" placeholder="PASSWORD">'.$mandatory;
                    //$html .= '</div>';
                    //$html .= '<div id="wps_registration_name" class="wps_registration_row">';
                        //$html .= '<div class="wps_registration_label">'.$label_password_confirm.'</div>';
                        $html .= '<input type="password" class="wps_register_mandatory" id="wps_register_password_confirm" value="" placeholder="CONFORM PASSWORD">'.$mandatory;
                    $html .= '</div>';
                endif;
				
				$html .= '<div id="wps_registration_wpspro_home"  class="wps_registration_row">';
                // Town and Country?
                if ($town):
                    $mand = (substr($town, 0,1) == '*') ? 'class="wps_register_mandatory"' : '';
                   // $html .= str_replace('*', '', $town).'<br />';
                    $html .= '<input type="text" '.$mand.' id="wps_register_wpspro_home" value="" placeholder="CITY">';
					/*
					$html .= '<select id="wps_register_wpspro_country" value="">
								<option value="">City</option>
								<option value="">Lagos</option>
								<option value="">Islamabad</option>
								<option value="">City</option>
							</select>';
					*/
                    if ($mand) $html .= $mandatory;
					//$html .= '</div>';
                    //$html .= '</div></div>';
                endif;
                if ($country):
                    //$html .= '<div id="wps_registration_wpspro_country" class="wps_registration_row">';
                    $mand = (substr($town,0,1) == '*') ? 'class="wps_register_mandatory"' : '';
                    //$html .= str_replace('*', '', $country).'<br />';
                    //$html .= '<input type="text" '.$mand.' id="wps_register_wpspro_country" value="" placeholder="COUNTRY">';
					$html .= '<select id="wps_register_wpspro_country" value="">
								<option value="">Country</option>
									<option value="Afganistan">Afghanistan</option>
									<option value="Albania">Albania</option>
									<option value="Algeria">Algeria</option>
									<option value="American Samoa">American Samoa</option>
									<option value="Andorra">Andorra</option>
									<option value="Angola">Angola</option>
									<option value="Anguilla">Anguilla</option>
									<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
									<option value="Argentina">Argentina</option>
									<option value="Armenia">Armenia</option>
									<option value="Aruba">Aruba</option>
									<option value="Australia">Australia</option>
									<option value="Austria">Austria</option>
									<option value="Azerbaijan">Azerbaijan</option>
									<option value="Bahamas">Bahamas</option>
									<option value="Bahrain">Bahrain</option>
									<option value="Bangladesh">Bangladesh</option>
									<option value="Barbados">Barbados</option>
									<option value="Belarus">Belarus</option>
									<option value="Belgium">Belgium</option>
									<option value="Belize">Belize</option>
									<option value="Benin">Benin</option>
									<option value="Bermuda">Bermuda</option>
									<option value="Bhutan">Bhutan</option>
									<option value="Bolivia">Bolivia</option>
									<option value="Bonaire">Bonaire</option>
									<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
									<option value="Botswana">Botswana</option>
									<option value="Brazil">Brazil</option>
									<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
									<option value="Brunei">Brunei</option>
									<option value="Bulgaria">Bulgaria</option>
									<option value="Burkina Faso">Burkina Faso</option>
									<option value="Burundi">Burundi</option>
									<option value="Cambodia">Cambodia</option>
									<option value="Cameroon">Cameroon</option>
									<option value="Canada">Canada</option>
									<option value="Canary Islands">Canary Islands</option>
									<option value="Cape Verde">Cape Verde</option>
									<option value="Cayman Islands">Cayman Islands</option>
									<option value="Central African Republic">Central African Republic</option>
									<option value="Chad">Chad</option>
									<option value="Channel Islands">Channel Islands</option>
									<option value="Chile">Chile</option>
									<option value="China">China</option>
									<option value="Christmas Island">Christmas Island</option>
									<option value="Cocos Island">Cocos Island</option>
									<option value="Colombia">Colombia</option>
									<option value="Comoros">Comoros</option>
									<option value="Congo">Congo</option>
									<option value="Cook Islands">Cook Islands</option>
									<option value="Costa Rica">Costa Rica</option>
									<option value="Cote DIvoire">Cote D\'Ivoire</option>
									<option value="Croatia">Croatia</option>
									<option value="Cuba">Cuba</option>
									<option value="Curaco">Curacao</option>
									<option value="Cyprus">Cyprus</option>
									<option value="Czech Republic">Czech Republic</option>
									<option value="Denmark">Denmark</option>
									<option value="Djibouti">Djibouti</option>
									<option value="Dominica">Dominica</option>
									<option value="Dominican Republic">Dominican Republic</option>
									<option value="East Timor">East Timor</option>
									<option value="Ecuador">Ecuador</option>
									<option value="Egypt">Egypt</option>
									<option value="El Salvador">El Salvador</option>
									<option value="Equatorial Guinea">Equatorial Guinea</option>
									<option value="Eritrea">Eritrea</option>
									<option value="Estonia">Estonia</option>
									<option value="Ethiopia">Ethiopia</option>
									<option value="Falkland Islands">Falkland Islands</option>
									<option value="Faroe Islands">Faroe Islands</option>
									<option value="Fiji">Fiji</option>
									<option value="Finland">Finland</option>
									<option value="France">France</option>
									<option value="French Guiana">French Guiana</option>
									<option value="French Polynesia">French Polynesia</option>
									<option value="French Southern Ter">French Southern Ter</option>
									<option value="Gabon">Gabon</option>
									<option value="Gambia">Gambia</option>
									<option value="Georgia">Georgia</option>
									<option value="Germany">Germany</option>
									<option value="Ghana">Ghana</option>
									<option value="Gibraltar">Gibraltar</option>
									<option value="Great Britain">Great Britain</option>
									<option value="Greece">Greece</option>
									<option value="Greenland">Greenland</option>
									<option value="Grenada">Grenada</option>
									<option value="Guadeloupe">Guadeloupe</option>
									<option value="Guam">Guam</option>
									<option value="Guatemala">Guatemala</option>
									<option value="Guinea">Guinea</option>
									<option value="Guyana">Guyana</option>
									<option value="Haiti">Haiti</option>
									<option value="Hawaii">Hawaii</option>
									<option value="Honduras">Honduras</option>
									<option value="Hong Kong">Hong Kong</option>
									<option value="Hungary">Hungary</option>
									<option value="Iceland">Iceland</option>
									<option value="India">India</option>
									<option value="Indonesia">Indonesia</option>
									<option value="Iran">Iran</option>
									<option value="Iraq">Iraq</option>
									<option value="Ireland">Ireland</option>
									<option value="Isle of Man">Isle of Man</option>
									<option value="Israel">Israel</option>
									<option value="Italy">Italy</option>
									<option value="Jamaica">Jamaica</option>
									<option value="Japan">Japan</option>
									<option value="Jordan">Jordan</option>
									<option value="Kazakhstan">Kazakhstan</option>
									<option value="Kenya">Kenya</option>
									<option value="Kiribati">Kiribati</option>
									<option value="Korea North">Korea North</option>
									<option value="Korea Sout">Korea South</option>
									<option value="Kuwait">Kuwait</option>
									<option value="Kyrgyzstan">Kyrgyzstan</option>
									<option value="Laos">Laos</option>
									<option value="Latvia">Latvia</option>
									<option value="Lebanon">Lebanon</option>
									<option value="Lesotho">Lesotho</option>
									<option value="Liberia">Liberia</option>
									<option value="Libya">Libya</option>
									<option value="Liechtenstein">Liechtenstein</option>
									<option value="Lithuania">Lithuania</option>
									<option value="Luxembourg">Luxembourg</option>
									<option value="Macau">Macau</option>
									<option value="Macedonia">Macedonia</option>
									<option value="Madagascar">Madagascar</option>
									<option value="Malaysia">Malaysia</option>
									<option value="Malawi">Malawi</option>
									<option value="Maldives">Maldives</option>
									<option value="Mali">Mali</option>
									<option value="Malta">Malta</option>
									<option value="Marshall Islands">Marshall Islands</option>
									<option value="Martinique">Martinique</option>
									<option value="Mauritania">Mauritania</option>
									<option value="Mauritius">Mauritius</option>
									<option value="Mayotte">Mayotte</option>
									<option value="Mexico">Mexico</option>
									<option value="Midway Islands">Midway Islands</option>
									<option value="Moldova">Moldova</option>
									<option value="Monaco">Monaco</option>
									<option value="Mongolia">Mongolia</option>
									<option value="Montserrat">Montserrat</option>
									<option value="Morocco">Morocco</option>
									<option value="Mozambique">Mozambique</option>
									<option value="Myanmar">Myanmar</option>
									<option value="Nambia">Nambia</option>
									<option value="Nauru">Nauru</option>
									<option value="Nepal">Nepal</option>
									<option value="Netherland Antilles">Netherland Antilles</option>
									<option value="Netherlands">Netherlands (Holland, Europe)</option>
									<option value="Nevis">Nevis</option>
									<option value="New Caledonia">New Caledonia</option>
									<option value="New Zealand">New Zealand</option>
									<option value="Nicaragua">Nicaragua</option>
									<option value="Niger">Niger</option>
									<option value="Nigeria">Nigeria</option>
									<option value="Niue">Niue</option>
									<option value="Norfolk Island">Norfolk Island</option>
									<option value="Norway">Norway</option>
									<option value="Oman">Oman</option>
									<option value="Pakistan">Pakistan</option>
									<option value="Palau Island">Palau Island</option>
									<option value="Palestine">Palestine</option>
									<option value="Panama">Panama</option>
									<option value="Papua New Guinea">Papua New Guinea</option>
									<option value="Paraguay">Paraguay</option>
									<option value="Peru">Peru</option>
									<option value="Phillipines">Philippines</option>
									<option value="Pitcairn Island">Pitcairn Island</option>
									<option value="Poland">Poland</option>
									<option value="Portugal">Portugal</option>
									<option value="Puerto Rico">Puerto Rico</option>
									<option value="Qatar">Qatar</option>
									<option value="Republic of Montenegro">Republic of Montenegro</option>
									<option value="Republic of Serbia">Republic of Serbia</option>
									<option value="Reunion">Reunion</option>
									<option value="Romania">Romania</option>
									<option value="Russia">Russia</option>
									<option value="Rwanda">Rwanda</option>
									<option value="St Barthelemy">St Barthelemy</option>
									<option value="St Eustatius">St Eustatius</option>
									<option value="St Helena">St Helena</option>
									<option value="St Kitts-Nevis">St Kitts-Nevis</option>
									<option value="St Lucia">St Lucia</option>
									<option value="St Maarten">St Maarten</option>
									<option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
									<option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
									<option value="Saipan">Saipan</option>
									<option value="Samoa">Samoa</option>
									<option value="Samoa American">Samoa American</option>
									<option value="San Marino">San Marino</option>
									<option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
									<option value="Saudi Arabia">Saudi Arabia</option>
									<option value="Senegal">Senegal</option>
									<option value="Serbia">Serbia</option>
									<option value="Seychelles">Seychelles</option>
									<option value="Sierra Leone">Sierra Leone</option>
									<option value="Singapore">Singapore</option>
									<option value="Slovakia">Slovakia</option>
									<option value="Slovenia">Slovenia</option>
									<option value="Solomon Islands">Solomon Islands</option>
									<option value="Somalia">Somalia</option>
									<option value="South Africa">South Africa</option>
									<option value="Spain">Spain</option>
									<option value="Sri Lanka">Sri Lanka</option>
									<option value="Sudan">Sudan</option>
									<option value="Suriname">Suriname</option>
									<option value="Swaziland">Swaziland</option>
									<option value="Sweden">Sweden</option>
									<option value="Switzerland">Switzerland</option>
									<option value="Syria">Syria</option>
									<option value="Tahiti">Tahiti</option>
									<option value="Taiwan">Taiwan</option>
									<option value="Tajikistan">Tajikistan</option>
									<option value="Tanzania">Tanzania</option>
									<option value="Thailand">Thailand</option>
									<option value="Togo">Togo</option>
									<option value="Tokelau">Tokelau</option>
									<option value="Tonga">Tonga</option>
									<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
									<option value="Tunisia">Tunisia</option>
									<option value="Turkey">Turkey</option>
									<option value="Turkmenistan">Turkmenistan</option>
									<option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
									<option value="Tuvalu">Tuvalu</option>
									<option value="Uganda">Uganda</option>
									<option value="Ukraine">Ukraine</option>
									<option value="United Arab Erimates">United Arab Emirates</option>
									<option value="United Kingdom">United Kingdom</option>
									<option value="United States of America">United States of America</option>
									<option value="Uraguay">Uruguay</option>
									<option value="Uzbekistan">Uzbekistan</option>
									<option value="Vanuatu">Vanuatu</option>
									<option value="Vatican City State">Vatican City State</option>
									<option value="Venezuela">Venezuela</option>
									<option value="Vietnam">Vietnam</option>
									<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
									<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
									<option value="Wake Island">Wake Island</option>
									<option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
									<option value="Yemen">Yemen</option>
									<option value="Zaire">Zaire</option>
									<option value="Zambia">Zambia</option>
									<option value="Zimbabwe">Zimbabwe</option>
								</select>';
                    if ($mand) $html .= $mandatory;
                    
					//$html .= '</div></div>';
                endif;
				$html .= '</div>';
                
    
                // Loop through extensions and check if set for registration form
                $args = array (
                    'post_type'              => 'wps_extension',
                    'posts_per_page'         => -1,
                    'meta_key'				 => 'wps_extension_order',
                    'orderby'				 => 'meta_value_num',
                    'order'					 => 'ASC',
                    'suppress_filters'		 => true
                );

                $extensions = query_posts( $args );
                if ($extensions):

                    foreach ($extensions as $extension):
    
                        if ($extension->wps_extension_type == 'text' || $extension->wps_extension_type == 'textarea' || $extension->wps_extension_type == 'list' || $extension->wps_extension_type == 'divider'):    

                            if (get_post_meta($extension->ID, 'wps_extension_register', true)):
    
                                $item_html = '';

                                if ( wps_using_permalinks() ):    	
                                    $slug = explode('/', get_post_permalink($extension->ID));
                                    $key = $slug[count($slug)-2];
                                else:
                                    $permalink = get_post_permalink($extension->ID).'<br>';
                                    if (strpos($permalink, '=')):
                                        $slug = explode('=', get_post_permalink($extension->ID));
                                        $key = $slug[1];
                                    else:
                                        if (strpos($permalink, 'blog/')) $permalink = str_replace('blog/', '', $permalink);
                                        $slug = explode('/', $permalink);
                                        $key = $slug[count($slug)-2];
                                    endif;
                                endif;

                                $key = 'wps_'.$key;

                                $item_html .= '<div class="wps_registration_item">';

                                    $label = $extension->post_title;
                                    $item_html .= '<div class="wps_registration_label">'.$label.'</div>';

                                    $mand = get_post_meta($extension->ID, 'wps_extension_register_mandatory', true) ? ' wps_register_mandatory' : '';

                                    if ($extension->post_content)
                                        $item_html .= '<div class="wps_registration_description">'.$extension->post_content.'</div>';
                                    if ($extension->wps_extension_type == 'text'):
                                        $item_html .= '<div class="wps_registration_field"><input rel="'.$extension->ID.'" class="wps_registration_value wps_registration_text'.$mand.'" data-key="'.$key.'" type="text" value="" /></div>';
                                        if ($mand) $item_html .= $mandatory;
                                    endif;
                                    if ($extension->wps_extension_type == 'textarea'):
                                        $item_html .= '<div class="wps_registration_field"><textarea rel="'.$extension->ID.'" class="wps_registration_value wps_registration_textarea'.$mand.'" data-key="'.$key.'"></textarea></div>';
                                        if ($mand) $item_html .= $mandatory;
                                    endif;
                                    if ($extension->wps_extension_type == 'list'):
                                        $values = explode(',', get_post_meta($extension->ID, 'wps_extension_default', true));
                                        $selected = $mand ? $values[0] : '';
                                        $item_html .= '<input id="wps_registration_meta_'.$extension->ID.'_default" type="hidden" value="'.$selected.'" />';
                                        $mandatory_text = $mand ? $mandatory : '';
                                        $item_html .= '<div class="wps_registration_field"><input type="text" id="wps_registration_meta_'.$extension->ID.'" class="wps_registration_value wps_registration_meta_list" rel="'.$extension->ID.'" data-key="'.$key.'" value="'.$values[0].'" />'.$mandatory_text.'</div>';
                                    endif;


                                $item_html .= '</div>';
								
								$item_html .= '</div></div>'; //end extrernal body

                                $html .= $item_html;
    
                            endif;
    
                        endif;
    
                    endforeach;

                endif;
                wp_reset_query();


				$html .= '<div id="wps_registration_dummy_field" style="position:absolute;top:-1000px;left:-5000px;">';
					$html .= __('This is a dummy field, only filled in by bots by mistake', WPS2_TEXT_DOMAIN).'<br />';
					$html .= '<input type="text" id="wps_registration_dummy" value=""> ';
				$html .= '</div>';
				if ($captcha):
					$html .= '<div id="wps_registration_captcha" class="wps_registration_row">';
						$html .= '<br /><img src="'.plugins_url( '', __FILE__ ).'/captcha.php" id="wps_captcha_image" /><br/>';
						$html .= '<input type="text" class="wps_register_mandatory" name="wps_captcha" id="wps_captcha_form" autocomplete="off" /><br/>';
						$html .= '<a href="javascript:void(0);" onclick="
						    document.getElementById(\'wps_captcha_image\').src=\''.plugins_url( '', __FILE__ ).'/captcha.php?\'+Math.random();
						    document.getElementById(\'wps_captcha_form\').focus();"
						    id="change-image">'.__('Not readable? Change text...', WPS2_TEXT_DOMAIN).'</a><br/>';
					$html .= '</div>';
				else:
					$html .= '<input type="hidden" id="wps_captcha_form" />';
				endif;

                $html .= '<div id="wps_register_error" class="wps_error" style="display:none"></div>';
				$html .= '<input id="wps_register_submit" type="submit" value="'.$label_register.'" />';
				if ($mode != 'register') $html .= '<br /><br /><a href="">'.$label_back_to_login.'</a>';
			endif;

			/* Request new password */
			if ($action == 'lost'):
				$html .= '<form action="#" method="post" class="lost-pass-sec">';
				$html .= $text_lostpassword_prompt.'<br /><br />';
				$html .= '<input type="hidden" name="action" value="lost2">';
				$html .= '<input type="text" name="wps_username_lost" value=""><br /><br />';
				$html .= '<input type="submit" value="'.$label_lostpassword_send.'" />';
				$html .= '</form>';
				$html .= '<br /><a class="lost-pass-sec" href="">'.$label_back_to_login.'</a>';
			endif;

			/* Send new password */
			if ($action == 'lost2'):
				if ($_POST['wps_username_lost'] != ''):

                    global $wpdb;

					$pw = wp_generate_password (12, false);
					$username = $_POST['wps_username_lost'];
					$sql = "SELECT ID, user_email FROM ".$wpdb->base_prefix."users WHERE user_login = %s or user_email = %s";
					$user = $wpdb->get_row($wpdb->prepare($sql, $username, $username));
					if ($user):
    
                        $closed_info = wps_is_account_closed($user->ID);
                        if (!$closed_info):
                            wp_set_password($pw, $user->ID);
                            add_filter( 'wp_mail_content_type', 'wps_set_html_content_type' );
                            $headers = 'From: '.get_bloginfo('admin_email').' <'.get_bloginfo('admin_email').'>' . "\r\n";
                            $content = sprintf($text_new_password, $pw).'<br /><br />';
                            $content .= $text_login_url;
                            $content = stripslashes(get_option('wps_alerts_customise_before')) . $content . stripslashes(get_option('wps_alerts_customise_after'));						
                            wp_mail($user->user_email, home_url(), $content, $headers);
                            remove_filter( 'wp_mail_content_type', 'wps_set_html_content_type' );
                            $html .= $text_password_reset;
                        else:
                            $html .= sprintf($text_password_closed, date('F j, Y', strtotime($closed_info['date'])), date('H:i', strtotime($closed_info['date'])));
                        endif;
					else:
						$html .= sprintf($text_username_not_found, $username);
					endif;
				endif;
			endif;

		endif;
		//echo '</div>';
		//end of registration
		
		/* End of follow-on actions */		

		if (isset($_GET['redirect'])) $url = $_GET['redirect'];

		/* Login mode */
		if ($mode == 'login' && !isset($_POST['action'])):
    
            if (isset($_GET['msg'])) $html .= '<div class="wps_success">'.$_GET['msg'].'</div>';
			
			
		
			//add twitter etc as above
			//social login
			
			//$html .= '<div class="col-md-12 no-padding"><div class="div-section login-section"><div class="section-title"><i class="fa fa-user"></i><p>or sign in with your username / email :</p></div>';
			$html .= '<div class="login-sec-inner">';
			$html .= '<div class="notification-alert-brown">sign in :</div>';
			
			$html .= '<div id="wps_login_username_label" class="wps_login_row">';
			
			$html .= '<div id="wps_login_username_label" class="wps_login_row">';
				//$html .= '<div class="wps_login_label">'.$label_username.'</div>';
				$html .= '<div class="wps_login_field"><input type="text" id="wps_login_username" value="" placeholder="USERNAME OR EMAIL ADDRESS"></div>';
			$html .= '</div>';
			$html .= '<div id="wps_login_password_label" class="wps_login_row">';
				//$html .= '<div class="wps_login_label">'.$label_password.'</div>';
				$html .= '<div class="wps_login_field"><input type="password" id="wps_login_password" value="" placeholder="PASSWORD"></div>';
			$html .= '</div>';
			$html .= '<div id="wps_login_error" class="wps_error" style="display:none"></div>';
			$html .= '<div id="wps_submit_buttons">';
			$html .= '<input id="wps_login_submit" rel="'.$url.'" class="wps_submit" type="submit" value="'.$label_login.'" />';
			
			$html .= '<br/><br/><br/>';
			
			/* Lost password button */
		    if ($lostpassword):
				$html .= '<form action="#" method="post">';
				$html .= '<input type="hidden" name="action" value="lost">';
				$html .= '<input id="wps_lost_password_button" class="wps_submit no-btn" type="submit" value="'.$label_lostpassword.'" />';
				$html .= '</form>';
		    endif;
			
		    /* Register button */
		    if ($register):
				$html .= '<form action="#" method="post">';
				$html .= '<input type="hidden" name="action" value="register">';
				//$html .= '<span>if you don\'t have an account, you can </span>';
				//$html .= '<input id="wps_register_button" class="wps_submit no-btn" type="submit" value="sign up here!" />';
				$html .= '</form>';
				$html .= '</div></div>'; //end extrernal body
		    endif;
			$html .= '<div id="login-bottom">a new <strong>community</strong> for enthusiasts of the <strong>beautiful game</strong></div>';
		    $html .= '</div>';
		endif;

	else:

        if (is_multisite()) {
            $url = get_site_url();
        } else {
            $url = '/';
        }
		wp_redirect( get_site_url().'?page_id=1770' ); exit;
		$html .= '<div class="wps_button wps_submit" id="wps_logout"><a href="'.wp_logout_url($url).'" title="Logout">Logout</a></div>';

	endif;
	
	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}



if (!is_admin()) add_shortcode('wps-login-form', 'wps_login_form');




?>
