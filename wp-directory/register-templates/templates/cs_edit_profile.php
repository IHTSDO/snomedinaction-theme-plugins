<?php ?>
<div class="cs-section-title"><h2><?php echo __('Profiles Setting','directory'); ?></h2></div>
<div class="main-content-in has-border">
  <?php 
 	 global $current_user;
      if ( !is_user_logged_in() ) {
          echo '<p class="warning">'.__('You must be logged in to edit your profile', 'directory').'</p>';
      }else { 
          if (!empty($error)){
               echo '<p class="error form-title">' . $error . '</p>';
          }else{
            if($flag == 'true'){
                  echo '<p class="error form-title">'.__('user profile update successfully','directory').'</p>';
            }
          }
          ?>
          <ul class="cs-form-element">
              <li>
                  <form method="POST" id="form_user_avatar_submit" enctype="multipart/form-data" action="">
                  <?php 
                      $display_photo = trim(cs_get_user_avatar(0, $uid)); 
                      $display = 'style="display:none"';
                      if($display_photo <> ''){
                        $display = 'style="display:block"';
                      }
					  if($display_photo <> ''){
					  	$user_avatar	= cs_get_user_avatar(0, $current_user->ID);
					  } else {
					  		$user_avatar	= wp_directory::plugin_url().'/assets/images/dummy.jpg';
					  }
                  ?>
                  <div class="cs-user-avatar-loading"></div>
                  <div class="page-wrap" id="user_avatar_display_box">
                      
                      <div class="thumb-secs">
                      	   <span class="profile-loading"></span>
                           <span id="cs-user-avatar-ajax-display"><img src="<?php echo esc_url($user_avatar); ?>" width="150" alt=""/></span>                     
                           <div class="gal-edit-opts" <?php echo cs_allow_special_char($display);?>>
                              <a onclick="cs_user_profile_picture_del('user_avatar_display', '<?php echo absint($uid); ?>', '<?php echo admin_url('admin-ajax.php');?>')" class="delete">
                                  <i class="icon-times"></i>
                              </a> 
                           </div>
                      </div>
                  </div>
                  <div class="profile-thumb">
                     <div class="browse-sec">
                        <span class="upload-file-icon">
                            <i class="icon-image"></i>
                            <input id="uploadFile" placeholder="Choose File" class="file-upload" disabled="disabled">
                        </span>
                        <div class="fileUpload">
                            <span><i class="icon-camera11"></i><?php _e('Upload Photo','directory')?></span>
                            <input type="file" id="form_user_avatar" class="upload" name="user_avatar" value="">
                            <input type="hidden" name="action" value="cs_user_avatar_upload">
                        </div>
                     </div>
                     <ul class="cs-hint-text">
                        <li> <?php _e('Update your avatar manually,If the not set the default Gravatar will be the same as your login email/user account.','directory'); ?></li>
                        <li> <?php _e('Max Upload Size: 1MB, Dimensions: 60x60 , Extensions: JPEG,PNG','directory'); ?></li>
                     </ul>
                  </div> 
                  <script>
                    document.getElementById("form_user_avatar").onchange = function () {
                        cs_user_avatar_upload( "<?php echo admin_url('admin-ajax.php');?>" );
                    };
                   </script>
                  </form>
              </li>
            </ul>
            <form method="post" id="edituser" class="user-forms" enctype="multipart/form-data" action="<?php the_permalink($cs_page_id); ?>?action=profile-setting&uid=<?php echo absint($uid);?>">
               <div class="cs-holder up">
                  <div class="form-title">
                    <h4><?php _e('About My Organization','directory')?></h4><a class="profile-toggle"><i class="icon-arrow-down9"></i></a>
                  </div>
                  <div class="has-border" id="cs-toggle-area">
                      
                        <ul class="cs-form-element tw-input">
                            <li class="first_name">
                                <label for="first_name"><?php _e('Full Name', 'directory'); ?></label>
                                <div class="inner-sec">
                                    <input class="text-input" name="first_name" type="text" id="first_name" value="<?php the_author_meta( 'first_name', $uid ); ?>" />
                                </div>
                            </li><!-- .first_name -->
                            
                            <li class="first_name">
                                <label for="tagline"><?php _e('TagLine', 'directory'); ?></label>
                                <div class="inner-sec">
                                    <input class="text-input" name="tagline" type="text" id="tagline" value="<?php the_author_meta( 'tagline', $uid ); ?>" />
                                </div>
                            </li>
                        </ul>
                        <ul class="cs-form-element">
                        <!-- .first_name -->
                            <li class="form-description">
                                <label for="description"><?php _e('Biography', 'directory'); ?></label>
                                <div class="inner-sec">
                                    <textarea class="text-input" name="description" id="description" rows="25" cols="30"><?php echo the_author_meta( 'description', $uid ); ?></textarea>
                                </div>
                            </li>
                        </ul>
                        <ul class="cs-form-element half-input">
                             <li>
                                  <label><?php _e('Public Profile', 'directory'); ?></label>
                                  <ul class="radio-box">
                                      <li>
                                        <input name="user_profile_public" id="show_profile" type="radio" value="1" <?php checked(1, get_the_author_meta('user_profile_public', $uid), true); ?>>
                                        <label for="show_profile"><?php _e('Show', 'directory'); ?></label>
                                      </li>
                                      <li>
                                          <input name="user_profile_public" id="hide_profile" type="radio" value="0" <?php checked(0, get_the_author_meta('user_profile_public', $uid), true); ?>>
                                        <label for="hide_profile"><?php _e('hide', 'directory'); ?></label>
                                      </li>
                                  </ul>
                              </li>
                              
                              <li>
                                    <label><?php _e('Contact Form', 'directory'); ?></label>
                                      <ul class="radio-box">
                                          <li>
                                            <input name="user_contact_form" id="show_form" type="radio" value="1" <?php checked(1, get_the_author_meta('user_contact_form', $uid),true ); ?>>
                                            <label for="show_form"><?php _e('Show', 'directory'); ?></label>
                                          </li>
                                          <li>
                                              <input name="user_contact_form" id="hide_form" type="radio" value="0" <?php checked(0, get_the_author_meta('user_contact_form', $uid),true ); ?>>
                                            <label for="hide_form"><?php _e('hide', 'directory'); ?></label>
                                          </li>
                                      </ul>
                                    
                              </li>
                            
                       </ul>
                  </div>
               </div>
               <div class="cs-holder">   
                    <div class="form-title">
                        <h4><?php _e('Contact Detail','directory')?></h4><a class="profile-toggle"><i class="icon-arrow-down9"></i></a>
                    </div>
                    <ul class="cs-form-element half-input has-border" style="display:none">
                         <li>
                              <label for="website"><?php _e('Landline', 'directory'); ?></label>
                              <input class="text-input" name="landline" type="text" id="landline" value="<?php the_author_meta( 'landline', $uid ); ?>" />
                         </li>
                         <li>       
                                 <label for="website"><?php _e('Mobile', 'directory'); ?></label>
                                 <input class="text-input" name="mobile" type="text" id="mobile" value="<?php the_author_meta( 'mobile', $uid ); ?>" />
                         </li>
                         <li> 
                                  <label for="email"><?php _e('Email Address', 'directory'); ?></label>
                                  <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $uid ); ?>" />
                         </li>
                          <li>
                                  <label for="skype"><?php _e('Skype', 'directory'); ?></label>
                                  <input class="text-input" name="skype" type="text" id="skype" value="<?php the_author_meta( 'skype', $uid ); ?>" />
                          </li>
                          <li>
                                  <label for="website"><?php _e('website', 'directory'); ?></label>
                                  <input class="text-input" name="website" type="text" id="website" value="<?php the_author_meta( 'user_url', $uid ); ?>" />
                          </li>
                     </ul>
               </div>
              <!--
               <div class="cs-holder">   
                                 <div class="form-title">
                                       <h4><?php _e('Social Media','directory'); ?></h4><a class="profile-toggle"><i class="icon-arrow-down9"></i></a>
                                  </div>
                                <ul class="cs-form-element half-input has-border pcs-social-media" style="display:none">
                                      <li>
                                          <label for="facebook"><?php _e('Facebook', 'directory'); ?></label>
                                          <div class="inner-sec">
                                              <input class="text-input" name="facebook" type="text" id="facebook" value="<?php the_author_meta( 'facebook', $uid ); ?>" />
                                              <i class="icon-facebook9"></i>
                                          </div>
                                      </li>
                                      <li>
                                          <label for="twitter"><?php _e('Twitter', 'directory'); ?></label>
                                          <div class="inner-sec">
                                              <input class="text-input" name="twitter" type="text" id="twitter" value="<?php the_author_meta( 'twitter', $uid ); ?>" />
                                              <i class="icon-twitter2"></i>
                                          </div>
                                      </li>
                                      <li>
                                          <label for="lastfm"><?php _e('Linkedin', 'directory'); ?></label>
                                          <div class="inner-sec">
                                              <input class="text-input" name="linkedin" type="text" id="linkedin" value="<?php the_author_meta( 'linkedin', $uid ); ?>" />
                                              <i class="icon-linkedin4"></i>
                                          </div>
                                      </li>
                                      <li>
                                          <label for="pinterest"><?php _e('Pinterest', 'directory'); ?></label>
                                          <div class="inner-sec">
                                              <input class="text-input" name="pinterest" type="text" id="pinterest" value="<?php the_author_meta( 'pinterest', $uid ); ?>" />
                                              <i class="icon-pinterest4"></i>
                                          </div>
                                      </li>
                                      <li>
                                          <label for="lastfm"><?php _e('Google Plus', 'directory'); ?></label>
                                          <div class="inner-sec">
                                              <input class="text-input" name="google_plus" type="text" id="google_plus" value="<?php the_author_meta( 'google_plus', $uid ); ?>" />
                                              <i class="icon-google-plus"></i>
                                          </div>
                                      </li>
                                      <li>
                                          <label for="lastfm"><?php _e('Instagram', 'directory'); ?></label>
                                          <div class="inner-sec">
                                              <input class="text-input" name="instagram" type="text" id="instagram" value="<?php the_author_meta( 'instagram', $uid ); ?>" />
                                              <i class="icon-instagram"></i>
                                          </div>
                                    </li>
                                </ul>
                             </div>-->
              
               <div class="cs-holder">   
                    <div class="form-title">
                        <h4><?php _e('Password Update','directory');?></h4><a class="profile-toggle"><i class="icon-arrow-down9"></i></a>
                    </div>
                    <ul class="cs-form-element title-left has-border" style="display:none">
                      <li class="form-password">
                          <label for="pass1"><?php _e('New Password', 'directory'); ?> </label>
                          <div class="inner-sec">
                            <input class="text-input" name="pass1" type="password" id="pass1" />
                          </div>
                      </li><!-- .form-password -->
                      <li class="form-password">
                          <label for="pass2"><?php _e('Repeat Password', 'directory'); ?></label>
                          <div class="inner-sec">
                            <input class="text-input" name="pass2" type="password" id="pass2" />
                            <p><?php _e('Enter same password in both fields. Use an uppercase letter and a number for stronger password.','directory');?></p> 
                          </div>
                      </li><!-- .form-password -->
                    </ul>
               </div>
              <!--
               <div class="cs-holder">   
                                  <div class="form-title">
                                      <h4><?php _e('Paypal','directory')?></h4><a class="profile-toggle"><i class="icon-arrow-down9"></i></a>
                                  </div>
                                  <ul class="cs-form-element title-left has-border" style="display:none">
                                   <li class="form-password">
                                        <label for="pass2"><?php _e('Paypal Email', 'directory'); ?></label>
                                        <div class="inner-sec">
                                          <input class="text-input" name="paypal_email" type="text" id="paypal_email" value="<?php the_author_meta( 'paypal_email', $uid ); ?>" />
                                        </div>
                                    </li>
                                  </ul>
                             </div>-->
              
             <!--
               <div class="cs-holder">   
                                 <div class="form-title">
                                     <h4><?php _e('Opening Hours','directory')?></h4><a class="profile-toggle"><i class="icon-arrow-down9"></i></a>
                                 </div>
                                 <ul class="cs-form-element column-input has-border" style="display:none">
                                     <table class="user-openings">
                                      <thead>
                                       <tr>
                                        <th><?php _e('Day Name', 'directory'); ?></th>
                                        <th><?php _e('Start Time', 'directory'); ?></th>
                                        <th><?php _e('End Time', 'directory'); ?></th>
                                       </tr>
                                      </thead>
                                     <?php if(function_exists('cs_openinghours_fields'))
                                         cs_openinghours_fields();
                                     ?>
                                     </table>
                                 </ul>
                            </div>-->
             
               <div class="cs-holder">   
                    <ul class="cs-form-element cs-submit-form">
                     <li>
                          <div class="inner-sec">
                           <p><?php _e('Carefully Check entered information and than click button to submit them.', 'directory'); ?></p>
                           <span><?php _e('By Clicking \'Submit\' you agree to our Terms of Use & Posting Rules.', 'directory'); ?></span>
                            <?php 
								if($current_user->user_login =='directory-demo'){
									$cs_disable = 'disabled';
								}else{
									$cs_disable = '';
								}
							?>
                            <input name="updateuser" type="submit" <?php echo $cs_disable; ?> id="updateuser" class="submit-button cs-bg-color" value="<?php _e('Submit Changes', 'directory'); ?>" />
                          <?php wp_nonce_field( 'update-user' ) ?>
                          <input name="action" type="hidden" id="action" value="update-user" />
                          </div>
                     </li><!-- .form-password -->
                    </ul>
               </div>
          </form>
<?php } ?>
</div>
