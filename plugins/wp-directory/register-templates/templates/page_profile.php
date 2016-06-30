<?php 
/**
 *  Template Name: User Profile
 
 */
	global $current_user, $wp_roles,$userdata,$cs_theme_options,$myTotalAdds;
 	$uid= $current_user->ID;
  	$cs_directory_per_page = get_option('posts_per_page');
	$user_role = get_the_author_meta('roles',$uid );
	$cs_counter_node  = 1;
	$cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
	$cs_directory_ads_allow = $cs_theme_options['cs_directory_ads_allow'];
 	if(isset($_GET['uid']) && $_GET['uid'] <> ''){ $uid = $_GET['uid']; }
	$action = (isset($_GET['action']) && $_GET['action'] <> '') ? $_GET['action'] : $action	= '';
	$error = '';
	$flag = 'false';
	cs_user_avatar();
	if (empty($_GET['page_id_all'])) $_GET['page_id_all'] = 1;
	
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' && $uid == $current_user->ID) {
 		if($current_user->user_login =='directory-demo'){
				$error = __('You are not able to update profile setting from demo account.', 'directory');
 		}else{
		if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
			
			if ( $_POST['pass1'] == $_POST['pass2'] )
				wp_update_user( array( 'ID' => $uid, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
			else
				$error = __('The passwords you entered do not match. Your password was not updated', 'directory');
		}
		if ( !empty( $_POST['email'] ) ){
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
 				//update_user_meta( $uid, 'user_email', esc_attr( $_POST['email'] ) );
				wp_update_user( array( 'ID' => $uid, 'user_email' => esc_attr( $_POST['email'] ) ) );
			} else {
				$error = __('Please enter a valid email address.','directory');
			}
		}

  		 if(isset($_POST['mobile'])) {
			update_user_meta( $uid, 'mobile', esc_attr( $_POST['mobile'] ) );
		 }
		 if(isset($_POST['landline'])) {
			update_user_meta( $uid, 'landline', esc_attr( $_POST['landline'] ) );
		 }
 		/* Update user information. */
 		if(isset($_POST['first_name'])){
			update_user_meta( $uid,'first_name', esc_attr( $_POST['first_name'] ) );
		}
		if(isset($_POST['paypal_email'])){
			update_user_meta( $uid,'paypal_email', esc_attr( $_POST['paypal_email'] ) );
		}
		if(isset($_POST['tagline'])){
			update_user_meta( $uid,'tagline', esc_attr( $_POST['tagline'] ) );
		}
		if(isset($_POST['user_profile_public'])){
				update_user_meta( $uid, 'user_profile_public', absint($_POST['user_profile_public']) );
		}
		if(isset($_POST['user_contact_form'])){
			update_user_meta( $uid, 'user_contact_form', absint($_POST['user_contact_form']) );
		}
		if(isset($_POST['description'])){
			update_user_meta( $uid, 'description', html_entity_decode( $_POST['description'] ) );
		}
		$opening_hours = array();
		if(isset($_POST['opening_hours'])){
			foreach($_POST['opening_hours'] as $key=>$value){
				$opening_hours[$key] = esc_attr($value);
			}
		}
		update_user_meta( $uid, 'opening_hours', $opening_hours );
		/* Extra Profile Information */
		$user_id = wp_update_user( array( 'ID' => $uid, 'user_url' => esc_attr($_POST['website']) ) );
		update_user_meta( $uid, 'facebook', esc_attr( $_POST['facebook'] ) );
		update_user_meta( $uid, 'twitter', esc_attr( $_POST['twitter'] ) );
		update_user_meta( $uid, 'google_plus',esc_attr( $_POST['google_plus'] ));
		update_user_meta( $uid, 'linkedin', esc_attr($_POST['linkedin']) );
		update_user_meta( $uid, 'pinterest', esc_attr($_POST['pinterest']) );
		update_user_meta( $uid, 'skype', esc_attr($_POST['skype']) );
		update_user_meta( $uid, 'instagram', esc_attr($_POST['instagram']) );
		}
		/* Redirect so the page will show updated info. */
		if (!$error) {
			$flag = 'true';
		}
	}
 	get_header();
	$user_profile_public = get_the_author_meta('user_profile_public',$uid );
	$add_directory_link  = add_query_arg( array('action'=>'add-directory','uid'=>  urlencode( $uid )),  esc_url( get_permalink( $cs_page_id ) ) );
?>
	<!-- PageSection -->
    <section class="page-section">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
            	<?php
				if( !(is_user_logged_in()) ){
					echo '<div class="col-md-12"><p>'.__('You must have to login first to access this page.', 'directory').'</p></div>';
					echo do_shortcode('[cs_register register_title="Register" register_role="subscriber"][/cs_register]');
				}
				else{
					if(isset($user_profile_public) and $user_profile_public=='1' or $current_user->ID ==$uid){ 
				?>
					   <aside class="page-sidebar profile-bar">
						 <article class="st-userinfo">
						   <div class="cs-auther">
							  <figure>
								  <?php 
									  $cs_dummy_image = 'dummy.jpg';
									  $plugin_url		= plugins_url();
									  $cs_dummy_image = $plugin_url.'/wp-directory/assets/images/dummy.jpg';
									  $cs_display_image = '';
									  $cs_display_image = cs_get_user_avatar(1 ,$uid);
									  if( $cs_display_image <> ''){?>
										  <a class="info-thumb"><img height="60" width="60" src="<?php echo esc_url( $cs_display_image );?>"  /></a>
									  <?php }else{?>
										  <a class="info-thumb"><?php echo '<img height="60" width="60" src="'.esc_url($cs_dummy_image).'" alt="" />'; ?></a>
									  <?php }?>
							  </figure>
							  <div class="text">
								  <h6><?php echo get_the_author_meta('display_name',$uid );?></h6>
								  <span>
								  	<?php 
								  		$role = get_the_author_meta('roles',$uid );
 										$role = (isset($role[0]) and $role[0]) ? $role[0] : __('Subscriber','directory');
										echo esc_attr( $role );
									?>
                                    </span>
							  </div>
						  </div>
						  <!-- Nav Assigment -->
						   <nav class="cs_profile_tabs">
							  <?php 
								  if ( get_current_user_id()== $uid ){
										 cs_profile_menu( $action ,$uid );
								  }
							  ?>
						  </nav>
						  <!-- Nav Assigment -->
						  </article>
						  <div class="post-new-add cs-bgcolor">
							  <i class="icon-plus3"></i> 
							  <a href="<?php echo esc_url($add_directory_link);?>"><?php _e('Post New Project', 'directory'); ?></a>
							  <span><?php _e('Create your new Project.', 'directory'); ?></span>
						  </div>
					   </aside>
					   <div class="page-content">
						 <div class="container">
						  <div class="row">
							<div class="section-fullwidth">
							 <div class="element-size-100">
								  <div id="post-<?php the_ID(); ?>" >
									<div class="entry-content">
										<?php
											if (have_posts()):
												while (have_posts()) : the_post();
													the_content();
												endwhile;
											endif;
											wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages:', 'directory'), "</div>\n", 'number');
										?>
									</div>
								  </div>
								  <?php 
									if((isset($_GET['action']) && $_GET['action'] == 'dashboard') || !isset($_GET['action'])){
										?>
										<div class="cs-section-title"><h2><a><?php echo __('About','directory').' '.get_the_author_meta('display_name',$uid );  ?></a></h2></div>
											
										   <!-- .post -->
											<?php  
												$the_author_description = apply_filters("the_content",get_the_author_meta('description',$uid));
												if (isset ( $the_author_description ) && $the_author_description !='' ) {?>
												<div class="col-md-12 detail_text rich_editor_text  has-border">
													<p><?php echo balanceTags($the_author_description, true);?></p>
													<div class="col-md-12">
													  <div class="assigment-form">
													   <?php if( get_the_author_meta('user_contact_form',$uid) =="1" and  $current_user->ID != $uid ){ ?>
														<h5><?php echo __('Contact Me','directory');?></h5>
													    <div class="cs_form_styling">
															<div class="inputforms respond">
															<div class="textsection">
																<div class="succ_mess" id="succ_mess<?php echo esc_attr($cs_counter_node)?>"  style="display:none;"></div>
															</div>
															<div class="form-style" id="contact_formZSd">
															<div class="respond fullwidth" id="respond">
																<form id="frm<?php echo esc_attr($cs_counter_node) ?>" name="frm<?php echo esc_attr($cs_counter_node) ?>" method="post" action="javascript:<?php echo "frm_submit".$cs_counter_node."()";?>" novalidate>   
																	<p><label><?php echo __('Name','directory');?></label><input type="text" name="contact_name" id="contact_name" class="required"  value="" /></p>
																	<p><label><?php echo __('Email Address*','directory');?></label><input type="text" name="contact_email" id="contact_email" class="required"    value="" /></p>
																	<p><label><?php echo __('Phone Number','directory');?></label><input type="text" name="phone" id="phone" class="required" value="" /></p>
																	<p><label><?php echo __('Type Message*','directory');?></label><textarea name="contact_msg"   id="contact_msg" class="required"></textarea></p>
																	<p><label></label><input type="submit" value="submit" name="submit" class="cs-bg-color" id="submit_btn<?php echo esc_attr($cs_counter_node) ?>"> </p>
																	 <input type="hidden" name="counter_node" value="<?php echo esc_attr($cs_counter_node) ?>">
																	
																</form>
																<span class="form-submit">
																	<div id="loading_div<?php echo esc_attr($cs_counter_node) ?>"></div>
																	<div id="message<?php echo esc_attr($cs_counter_node) ?>" style="display:none;"></div>
																</span>
															</div>
														   </div>
													</div>
														<?php 
													   $cs_contact_email		= get_the_author_meta( 'user_email', $uid );
													   $cs_contact_succ_msg		= 'Email has been sent Successfully.';
													   $cs_contact_error_msg	= 'An error Occured, please try again later.';
													   cs_enqueue_validation_script(); ?>
														<script type="text/javascript">
															jQuery().ready(function($) {
																var container = $('');
																var validator = jQuery("#frm<?php echo esc_js($cs_counter_node);?>").validate({
																	rules: {
																		 contact_name: "required",
																		 phone: "required",
																		 contact_msg: "required",
																		 contact_email: {
																		   required: true,
																		   email: true
																		 }
																	},
																	messages:{
																		contact_name: '<?php _e('Please enter a username.','directory');?>',
																		phone: '<?php _e('Please enter a phone number.','directory');?>',
																		contact_msg: '<?php _e('Please enter a message.','directory');?>',
																		contact_email:{
																			required:'<?php _e('Please enter a email address.','directory');?>',
																			email:'<?php _e('lease enter a valid email address.','directory');?>',
																		},
																	},
																	errorContainer: container,
																	errorLabelContainer: jQuery(container),
																	errorElement:'div',
																	errorClass:'frm_error',
																	meta: "validate"
																});
															});
															function frm_submit<?php echo esc_js($cs_counter_node);?>(){
																var $ = jQuery;
																$("#loading_div<?php echo esc_js($cs_counter_node);?>").html('<img src="<?php echo get_template_directory_uri()?>/assets/images/ajax-loader.gif" alt="" />');
																$.ajax({
																	type:'POST', 
																	url: '<?php echo get_template_directory_uri()?>/page_contact_submit.php',
																	data:$('#frm<?php echo esc_js($cs_counter_node);?>').serialize() + "&cs_contact_email=<?php echo esc_js($cs_contact_email);;?>&cs_contact_succ_msg=<?php echo esc_js($cs_contact_succ_msg);?>&cs_contact_error_msg=<?php echo esc_js($cs_contact_error_msg);?>", 
																	dataType: "json",
																	success: function(response) {
																		if (response.type == 'error'){
																			$("#loading_div<?php echo esc_js($cs_counter_node);?>").html('');
																			$("#loading_div<?php echo esc_js($cs_counter_node);?>").hide();
																			$("#message<?php echo esc_js($cs_counter_node);?>").addClass('error_mess');
																			$("#message<?php echo esc_js($cs_counter_node);?>").show();
																			$("#message<?php echo esc_js($cs_counter_node);?>").html('<p>'+response.message+'</p>');
																		} else if (response.type == 'success'){
																			$("#frm<?php echo esc_js($cs_counter_node);?>").slideUp();
																			$("#loading_div<?php echo esc_js($cs_counter_node);?>").html('');
																			$("#loading_div<?php echo esc_js($cs_counter_node);?>").hide();
																			$("#message<?php echo esc_js($cs_counter_node);?>").addClass('succ_mess');
																			$("#message<?php echo esc_js($cs_counter_node);?>").show();
																			$("#message<?php echo esc_js($cs_counter_node);?>").html('<p>'+response.message+'</p>');
																		}
																	}
																});
															}
														</script>
													   </div>
													   <?php }?>
													 </div>
													</div>
												</div>
											<?php }
										}elseif(isset($_GET['action']) && $_GET['action'] == 'add-directory' and $current_user->ID == $uid){
											
											$maximumDirectoryAllow	 = isset($cs_theme_options['directory_submition_per_user']) ? $cs_theme_options['directory_submition_per_user'] : 0;
											$csDirectoryEditing	 = isset($cs_theme_options['cs_directory_editing']) ? $cs_theme_options['cs_directory_editing'] : 'off';
			
											if(isset( $cs_directory_ads_allow ) && $cs_directory_ads_allow == 'on' ){
												if ( isset( $myTotalAdds ) && $myTotalAdds < $maximumDirectoryAllow ) {
													include "page_add_directory.php";
												} else {
													if( !isset($_GET['directory_id']) && empty($_GET['directory_id'])) {
														$message = '<div class="main-content-in"><div style="background:#dd3e3b;" class="messagebox messagebox-v1 has-radius alert alert-info align-left no_border" >';
														$message .= '<div class="error" style="color:#ffffff;"><i class="icon-info-circle"></i>Oops! You reached to maximum submission directory.</div>';
														$message .= '</div></div>';
														echo balanceTags($message, false);
													}
													else {
														if ( isset( $csDirectoryEditing ) && $csDirectoryEditing == 'on' ) {
															include "page_add_directory.php";
														}
														else{
															$message = '<div class="main-content-in"><div style="background:#dd3e3b;" class="messagebox messagebox-v1 has-radius alert alert-info align-left no_border" >';
															$message .= '<div class="error" style="color:#ffffff;"><i class="icon-info-circle"></i>Oops! You are not allowed to edit directory.</div>';
															$message .= '</div></div>';
															echo balanceTags($message, false);
														}
														
													}
												}
											} else {
												
												if(isset($_GET['directory_id']) && !empty($_GET['directory_id']))
													include "page_add_directory.php";
												else 
													$message = '<div class="main-content-in"><div style="background:#dd3e3b;" class="messagebox messagebox-v1 has-radius alert alert-info align-left no_border" >';
													$message .= '<div class="error" style="color:#ffffff;"><i class="icon-info-circle"></i>Oops! You are not allowed to create Directory.</div>';
													$message .= '</div></div>';
													echo balanceTags($message, false);
												}
											}
											elseif( isset( $_GET['action']) && $_GET['action'] == 'my_ads' ){
												include "directory_ads.php";
											}
											elseif(isset($_GET['action']) && $_GET['action'] == 'user-reviews' && $current_user->ID == $uid){
												cs_profile_reviews_html($uid, $cs_directory_per_page);
											}
											elseif(isset($_GET['action']) && $_GET['action'] == 'payments' and $current_user->ID == $uid){
												include "user_directory_payments.php";
											}
											elseif(isset($_GET['action']) && $_GET['action'] == 'profile-setting' && $current_user->ID == $uid){
												include 'cs_edit_profile.php';	
											} elseif(isset($_GET['action']) && $_GET['action'] == 'saved_ads' && $current_user->ID == $uid){
												cs_profile_save_ads();
											} 
											?>  
										</div>  
									</div>
								</div>
							</div>
						</div>
					<?php 
					} 
				}
				?>
            </div>
        </div>
    </section>            
<?php get_footer(); ?>
