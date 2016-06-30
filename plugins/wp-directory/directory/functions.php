<?php

//======================================================================
// Function Add To Favourute
//======================================================================
if ( ! function_exists( 'cs_addto_usermeta' ) ) :
	function cs_addto_usermeta() {
		$user = cs_get_user_id();
		if(isset($user) && $user <> ''){
			if(isset($_POST['post_id']) && $_POST['post_id'] <> ''){
				
				$cs_wishlist = cs_get_user_meta();
				$cs_wishlist = (isset($cs_wishlist) and is_array($cs_wishlist)) ? $cs_wishlist : array();
				if ( isset ( $cs_wishlist ) && in_array( $_POST['post_id'] , $cs_wishlist )) {
					$post_id = array();
					$post_id[] = $_POST['post_id'];
					$cs_wishlist = array_diff( $post_id , $cs_wishlist );
					cs_update_user_meta( $cs_wishlist );
					echo '<i class="icon-star-o"></i>'; 
					die();
				}
				
				$cs_wishlist = array();
				$cs_wishlist =  get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
					$cs_wishlist[] = $_POST['post_id'];
					$cs_wishlist = array_unique($cs_wishlist);
					update_user_meta(cs_get_user_id(),'cs-directory-wishlist',$cs_wishlist);
					$user_watchlist = get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
					?>
			 	    <i class="icon-star2"></i>
                    <div class="outerwrapp-layer">
					<?php _e('Added to Favourite','directory'); ?>
					</div>
                    <?php
				}
		} else {
			_e('You have to login first.','directory');
		}
		die();	
	}
endif;

add_action("wp_ajax_cs_addto_usermeta", "cs_addto_usermeta");
add_action("wp_ajax_nopriv_cs_addto_usermeta", "cs_addto_usermeta");

//======================================================================
// Function Add To Favourute Carousel
//======================================================================
if ( ! function_exists( 'cs_addto_usermeta_carosel' ) ) :
	function cs_addto_usermeta_carosel() {
		$user = cs_get_user_id();
		if(isset($user) && $user <> ''){
			if(isset($_POST['post_id']) && $_POST['post_id'] <> ''){
				
				$cs_wishlist = cs_get_user_meta();
				$cs_wishlist = (isset($cs_wishlist) and is_array($cs_wishlist)) ? $cs_wishlist : array();
				if ( isset ( $cs_wishlist ) && in_array( $_POST['post_id'] , $cs_wishlist )) {
					$post_id = array();
					$post_id[] = $_POST['post_id'];
					$cs_wishlist = array_diff( $post_id , $cs_wishlist );
					cs_update_user_meta( $cs_wishlist );
					echo '<i class="icon-star-o"></i>'; 
					die();
				}
				
				$cs_wishlist = array();
				$cs_wishlist =  get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
					$cs_wishlist[] = $_POST['post_id'];
					$cs_wishlist = array_unique($cs_wishlist);
					update_user_meta(cs_get_user_id(),'cs-directory-wishlist',$cs_wishlist);
					$user_watchlist = get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
					
					$cs_icon = '<i class="icon-star2"></i>';
					$cs_pop_msg = '<div class="outerwrapp-layer">'.__('Added to Favourite','directory').'</div>';
					
					$cs_html = array(
									'icon' => $cs_icon,
									'msg' => $cs_pop_msg,
							   );
					$cs_json = json_encode($cs_html);
					echo $cs_json;
				}
		} else {
			_e('You have to login first.','directory');
		}
		die();	
	}
endif;

add_action("wp_ajax_cs_addto_usermeta_carosel", "cs_addto_usermeta_carosel");
add_action("wp_ajax_nopriv_cs_addto_usermeta_carosel", "cs_addto_usermeta_carosel");


//======================================================================
// Function Directory Likes
//=====================================================================
if ( ! function_exists( 'cs_get_directory_likes' ) ) :
	function cs_get_directory_likes(){
		global $post;
		$counter = 0;
		$blogusers = get_users('orderby=nicename');
		 foreach ($blogusers as $user) {
			 $cs_wishlist =  get_user_meta($user->ID,'cs-directory-wishlist', true);
			 if(isset($cs_wishlist) && isset($post->ID) && is_array($cs_wishlist) && in_array($post->ID, $cs_wishlist)){
				 $counter++;
			 }
		 }
		 return $counter;
	}
endif;

//======================================================================
// Directory Price 
//=====================================================================
if ( ! function_exists( 'cs_get_directory_price' ) ) :
	function cs_get_directory_price( $cs_post_id,$cs_phone_view ='false' ){
		global $cs_theme_options;
		$cs_currency_sign	= isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';		
		$cs_html = '';
		
		if( $cs_post_id <> '' ) {
			$cs_oldPrice		= get_post_meta($cs_post_id, "dynamic_post_sale_oldprice", true);
			$cs_newPrice		= get_post_meta($cs_post_id, "dynamic_post_sale_newprice", true);
			$cs_phone_no		= get_post_meta($cs_post_id, 'dynamic_post_sale_price_call', true);
			$cs_organizer_id	= cs_get_organizer_id($cs_post_id);
			$cs_user_profile_public	= get_the_author_meta('user_profile_public', $cs_organizer_id);
			
			if ( $cs_oldPrice != '' || $cs_newPrice != '' ) {
				if( $cs_oldPrice != '' ) {
					$cs_oldPrice = '<small itemprop="price">'.esc_attr( $cs_currency_sign.number_format(absint($cs_oldPrice))).'</small>&nbsp;';
				}
				if( $cs_newPrice != '' ) {
					$cs_newPrice = esc_attr( $cs_currency_sign.number_format(absint($cs_newPrice)));
				}
				
				$cs_html .= '<span itemprop="price">' . $cs_oldPrice.$cs_newPrice . '</span>';
			}
			else if( $cs_phone_no <> '' && $cs_user_profile_public != '1' && $cs_phone_view == 'true') {
				$cs_html .= '<span itemprop="price">' . __('Price On Call:', 'directory') . $cs_phone_no . '</span>';
			}
			else {
				$cs_html .= '<span itemprop="price">' . __('Price On Call', 'directory') . '</span>';
			}
		}
		
		return $cs_html;
	}
endif;

//======================================================================
// Function Delete From Favourite
//======================================================================

function cs_delete_from_favourite(){
	if(isset($_POST['post_id']) && $_POST['post_id'] <> ''){
		$cs_wishlist = cs_get_user_meta();
		$cs_wishlist = (isset($cs_wishlist) and is_array($cs_wishlist)) ? $cs_wishlist : array();
		$post_id = array();
		$post_id[] = $_POST['post_id'];
		$cs_wishlist = array_diff( $cs_wishlist,$post_id);
		cs_update_user_meta($cs_wishlist);
		echo '<i class="icon-star"></i><div class="outerwrapp-layer">';
		_e('Removed From Favourite','directory'); 
		echo '</div>';
	} else {
		_e('You are not authorised','directory'); 
	}
	die();
}
add_action("wp_ajax_cs_delete_from_favourite", "cs_delete_from_favourite");
add_action("wp_ajax_nopriv_cs_delete_from_favourite", "cs_delete_from_favourite");

//======================================================================
// Function Delete From Favourite carousel
//======================================================================

function cs_delete_from_favourite_carosel(){
	if(isset($_POST['post_id']) && $_POST['post_id'] <> ''){
		$cs_wishlist = cs_get_user_meta();
		$cs_wishlist = (isset($cs_wishlist) and is_array($cs_wishlist)) ? $cs_wishlist : array();
		$post_id = array();
		$post_id[] = $_POST['post_id'];
		$cs_wishlist = array_diff( $cs_wishlist,$post_id);
		cs_update_user_meta($cs_wishlist);
		$cs_icon = '<i class="icon-star"></i>';
		$cs_pop_msg = '<div class="outerwrapp-layer">'.__('Removed From Favourite','directory').'</div>';
		
		$cs_html = array(
						'icon' => $cs_icon,
						'msg' => $cs_pop_msg,
				   );
		$cs_json = json_encode($cs_html);
		echo $cs_json;
	} else {
		_e('You are not authorised','directory'); 
	}
	die();
}
add_action("wp_ajax_cs_delete_from_favourite_carosel", "cs_delete_from_favourite_carosel");
add_action("wp_ajax_nopriv_cs_delete_from_favourite_carosel", "cs_delete_from_favourite_carosel");

//======================================================================
// Function Delete From Wishlist
//======================================================================
function cs_delete_wishlist(){
	if(isset($_POST['post_id']) && $_POST['post_id'] <> ''){
			$cs_wishlist = cs_get_user_meta();
			$post_id = array();
			$post_id[] = $_POST['post_id'];
			$cs_wishlist = array_diff( $cs_wishlist,$post_id);
			cs_update_user_meta($cs_wishlist);
			  _e('Removed From Favourite','directory'); 
	} else {
		  _e('You are not authorised','directory'); 
	}
	
	die();
	
}
add_action("wp_ajax_cs_delete_wishlist", "cs_delete_wishlist");
add_action("wp_ajax_nopriv_cs_delete_wishlist", "cs_delete_wishlist");

//======================================================================
// Function Delete From Wishlist
//======================================================================
function cs_delete_all_wishlist(){
	if(isset($_POST['user_id']) && $_POST['user_id'] <> ''){
		update_user_meta($_POST['user_id'],'cs-directory-wishlist', '');
		_e('Removed From Wishlist','directory');
	} else {
		_e('You are not authorised','directory'); 
	}
	die();
}
add_action("wp_ajax_cs_delete_all_wishlist", "cs_delete_all_wishlist");
add_action("wp_ajax_nopriv_cs_delete_all_wishlist", "cs_delete_all_wishlist");

//======================================================================
// Function get user meta
//======================================================================
if ( ! function_exists( 'cs_get_user_meta' ) ) :
	function cs_get_user_meta($user = "") {
		if (!empty($user)){
			$userdata = get_user_by( 'login', $user );
			$user_id = $userdata->ID;
			return get_user_meta($user_id,'cs-directory-wishlist', true);
		}else{  
			 return get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
		}
	}
endif;

//======================================================================
// Function update user meta
//======================================================================
if ( ! function_exists( 'cs_update_user_meta' ) ) :
function cs_update_user_meta($arr) {
    return update_user_meta(cs_get_user_id(),'cs-directory-wishlist',$arr);
}
endif;

//======================================================================
// Function Validate
//======================================================================
if(!function_exists('cs_validate')){
	function cs_validate($value){
		return $value;
	}
}


//======================================================================
// Function Delete Thumb
//======================================================================
if ( ! function_exists( 'cs_delete_directory_thumbnail' ) ) {
	function cs_delete_directory_thumbnail(){
		$post_id = $_POST['post_id'];
		$thumb_id = $_POST['thumb_id'];
		if ( current_user_can('edit_posts',$post_id ) ) {
			update_post_meta($post_id,'_thumbnail_id','');
		}
		if ( current_user_can('delete_posts') ) {
			$delte = wp_delete_attachment( $thumb_id );
			if($delte)
				echo 'Attachment Removed';
		}
		die();
	}
	add_action('wp_ajax_cs_delete_directory_thumbnail', 'cs_delete_directory_thumbnail');
}


//======================================================================
// Function Review Rating
//======================================================================
if ( ! function_exists( 'cs_directory_reviews_rating' ) ) { 
	function cs_directory_reviews_rating(){
		global $post;
		$reviews_args = array(
			'posts_per_page'	=> "-1",
			'post_type'			=> 'cs-reviews',
			'post_status'		=> 'publish',
			'meta_key'			=> 'cs_reviews_directory',
			'meta_value'		=> $post->ID,
			'meta_compare'		=> "=",
			'orderby'			=> 'meta_value',
			'order'				=> 'ASC',
		);
		$reviews_query = new WP_Query($reviews_args);
		$reviews_count = $reviews_query->post_count;
		$var_cp_rating = 0;
		if ( $reviews_query->have_posts() <> "" ) {
			while ( $reviews_query->have_posts() ): $reviews_query->the_post();	
				$var_cp_rating = $var_cp_rating+get_post_meta($post->ID, "cs_reviews_rating", true);
			endwhile;
		}
		if($var_cp_rating){
			$var_cp_rating = $var_cp_rating/$reviews_count;
		}
		return $var_cp_rating;
		
	}
}

//======================================================================
// Email Setting hooks
//======================================================================
add_filter('wp_mail_from', 'lms_mail_from');
add_filter('wp_mail_from_name', 'lms_mail_from_name');
function lms_mail_from($old) {
	$email = get_option( 'admin_email' );
	return $email;
}
function lms_mail_from_name($old) {
	$site_name = get_option( 'blogname');
	return $site_name;
}

//======================================================================
// Function Claim And Reports
//======================================================================
if ( ! function_exists( 'cs_add_request' ) ) { 
	function cs_add_request(){
		global $post,$cs_theme_options;
		$user_id = cs_get_user_id();
 		if ( $_SERVER["REQUEST_METHOD"] == "POST"){
				
			$request_name  		= $_POST['request_name'];
			$request_email  	= $_POST['request_email'];
			$request_number 	= $_POST['request_number'];
			$request_message    = $_POST['request_message'];
			$emailTo		    = get_the_author_meta('email', $_POST['user_id'] );
			if ( $request_email == '' || $request_message == '' ) {
				$json['type']    = "error";
				$json['message'] = 'Please fill the required fields.';
				echo json_encode( $json );
				exit;
			}
			
			if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $request_email)) { 
			
				$json['type']		=  "error";
				$json['message']	=  __("Please enter a valid email.", "directory");
				echo json_encode( $json );
				die;
			}
		
			if( !isset( $_POST['term_condtions_check'] ) ) {
				$json['type']    = "error";
				$json['message'] = 'Please select Term &amp; Conditions.';
				echo json_encode( $json );
				exit;
			}
			
			define('WP_USE_THEMES', false);
			$subject = '';
			
			$bloginfo 		= get_bloginfo();
			$subjecteEmail  = "(" . $bloginfo . ") Request Detail";
			$message = '
				<table width="100%" border="1">
				  <tr>
					<td width="100"><strong>"'.__('Name','directory').'":</strong></td>
					<td>'.$request_name.'</td>
				  </tr>
				  <tr>
					<td width="100"><strong>"'.__('Email','directory').'":</strong></td>
					<td>'.$request_email.'</td>
				  </tr>
				  <tr>
					<td width="100"><strong>"'.__('Phone Number','directory').'":</strong></td>
					<td>'.$request_number.'</td>
				  </tr>
				  <tr>
					<td><strong>Message:</strong></td>
					<td>'.$request_message.'</td>
				  </tr>
				  <tr>
					<td><strong>"'.__('IP Address','directory').'":</strong></td>
					<td>'.$_SERVER["REMOTE_ADDR"].'</td>
				  </tr>
				</table>';
	
			$from_email	= 'noreply@directory.com';
			$headers  = "From: " . $request_name . "\r\n";
			$headers .= "Reply-To: " . $from_email . "\r\n";
			$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
			$headers .= "MIME-Version: 1.0" . "\r\n";
			$attachments = '';
			if(	wp_mail( $emailTo, $subjecteEmail, $message, $headers, $attachments ) ) {
				$json	= array();
				$json['type']    = "success";
				$json['message'] = 'Your message has been Sent.';
			} else {
				$json['type']    = "error";
				$json['message'] = 'An error occur, please try again later.';
			};
			echo json_encode( $json );
			exit;
		}
		exit;
		
	}
	add_action('wp_ajax_cs_add_request', 'cs_add_request');
	add_action("wp_ajax_nopriv_cs_add_request", "cs_add_request");
}

//======================================================================
// User Claim Report Mail
//======================================================================
if ( ! function_exists( 'cs_submit_report_mail' ) ) {
	function cs_submit_report_mail( $cs_post_id, $cs_report_counter, $cs_decription ) {
		
		global $current_user;
		
		$cs_user = 'User';
		if ( !is_user_logged_in() ) {
			if( isset($_POST['report_from_email_'.$cs_report_counter]) ) { $cs_user = $_POST['report_from_email_'.$cs_report_counter]; }
		}
		else{
			$cs_user = $current_user->user_nicename;
		}
		
		$cs_package_name = get_the_title($cs_post_id);
		$cs_subject = "{$cs_user} claim Report on {$cs_package_name}, (" . get_bloginfo() . ")";	
		
		$cs_reply_to = 'no-reply@user.com';
		if ( !is_user_logged_in() ) {
			if( isset($_POST['report_from_email_'.$cs_report_counter]) ) { $cs_reply_to = $_POST['report_from_email_'.$cs_report_counter]; }
		}
		else{
			$cs_reply_to = $current_user->user_email;
		}
		
		$cs_mail_headers = "From: " . esc_attr( $cs_user ) . "\r\n";
		$cs_mail_headers .= "Reply-To: " . $cs_reply_to . "\r\n";
		$cs_mail_headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
		$cs_mail_headers .= "MIME-Version: 1.0" . "\r\n";
		
		$cs_attachments = '';
		
		$cs_message = $cs_decription;
		
		$cs_organizer = cs_get_organizer_id($cs_post_id);
		$cs_organizer_email = get_the_author_meta('email', $cs_organizer);
		
		wp_mail( sanitize_email($cs_organizer_email), $cs_subject, $cs_message, $cs_mail_headers, $cs_attachments );
	}
}

//======================================================================
// Function icon picker
//======================================================================
if ( ! function_exists( 'cs_iconpicker_directory') ) {
	function cs_iconpicker_directory($icon_value='',$id='',$name=''){
		ob_start();
		?>
		<script>
            jQuery(document).ready(function($) {
				var e9_element = $('#e9_element_<?php echo esc_js($id);?>').fontIconPicker({
					theme: 'fip-bootstrap'
				});
					// Add the event on the button
				$('#e9_buttons_<?php echo esc_js($id);?> button').on('click', function(e) {
						e.preventDefault();
						// Show processing message
						$(this).prop('disabled', true).html('<i class="icon-cog demo-animate-spin"></i> Please wait...');
						$.ajax({
							url: '<?php echo esc_js(get_template_directory_uri());?>/include/assets/icon/js/selection.json',
							type: 'GET',
							dataType: 'json'
						})
						.done(function(response) {
							// Get the class prefix
							var classPrefix = response.preferences.fontPref.prefix,
								icomoon_json_icons = [],
								icomoon_json_search = [];
							$.each(response.icons, function(i, v) {
								icomoon_json_icons.push( classPrefix + v.properties.name );
								if ( v.icon && v.icon.tags && v.icon.tags.length ) {
									icomoon_json_search.push( v.properties.name + ' ' + v.icon.tags.join(' ') );
								} else {
									icomoon_json_search.push( v.properties.name );
								}
							});
							// Set new fonts on fontIconPicker
							e9_element.setIcons(icomoon_json_icons, icomoon_json_search);
							// Show success message and disable
							$('#e9_buttons_<?php echo esc_js($id);?> button').removeClass('btn-primary').addClass('btn-success').text('Successfully loaded icons').prop('disabled', true);
						})
						.fail(function() {
							// Show error message and enable
							$('#e9_buttons_<?php echo esc_js($id);?> button').removeClass('btn-primary').addClass('btn-danger').text('Error: Try Again?').prop('disabled', false);
						});
						e.stopPropagation();
					});
				
				jQuery("#e9_buttons_<?php echo esc_js($id);?> button").click();
			});	
		</script>
		<input type="text" id="e9_element_<?php echo esc_attr($id);?>" name="<?php echo esc_attr($name);?>" value="<?php echo esc_attr($icon_value);?>"/>
		<span id="e9_buttons_<?php echo esc_attr($id);?>" style="display:none">
			<button autocomplete="off" type="button" class="btn btn-primary">Load from IcoMoon selection.json</button>
		</span>
	<?php 
		$fontawesome = ob_get_clean();
		echo  cs_allow_special_char($fontawesome);
	}
}

//add extra fields to cousres categories
add_action ( 'directory-category_edit_form_fields', 'cs_edit_extra_category_fields');
add_action ( 'directory-category_add_form_fields', 'cs_extra_category_fields');

//======================================================================
// Function Add Category Fields
//======================================================================
if ( ! function_exists( 'cs_extra_category_fields' ) ) :
function cs_extra_category_fields( $tag ) {    //check for existing featured ID
	if ( isset($tag->term_id) ) {$t_id = $tag->term_id; }
	else { $t_id = rand(23434,4345434); }
	$cat_image = '';
	$cs_counter = $t_id;
	?>

    <div class="form-field">        
        <ul class="form-elements directory_cat_image" id="directory_cat_image<?php echo esc_attr($cs_counter);?>">
          <li class="to-label">
            <label>Image</label>
          </li>
          <li class="to-field">
            <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_attr($cat_image) && trim($cat_image) != '' ? 'inline' : 'none';?>" id="directory_cat_image_box" >
              <div class="gal-active">
                <div class="dragareamain" style="padding-bottom:0px;">
                  <ul id="gal-sortable">
                    <li class="ui-state-default">
                      <div class="thumb-secs cs-custom-image"> 
                      	<img src="<?php echo esc_url($cat_image);?>" id="directory_cat_image_img" width="200" />
                        <div class="gal-edit-opts"> <a href="javascript:del_media('directory_cat_image')" class="delete"></a> </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <input id="directory_cat_image" name="directory_cat_image" type="hidden" class="" value="<?php echo esc_url($cat_image);?>"/>
            <label class="browse-icon">
                <input name="directory_cat_image" type="button" class="uploadMedia left" value="<?php _e('Browse','directory'); ?>"/>
            </label>
          </li>
        </ul>
        <p><?php _e('Image for Directory Category', 'directory');?></p>
	</div>
    <input type="hidden" name="directory_cat_meta" value="1" />
	<?php
}
endif;

//======================================================================
// Function Edit Category Fields
//======================================================================
if ( ! function_exists( 'cs_edit_extra_category_fields' ) ) :
function cs_edit_extra_category_fields( $tag ) {    //check for existing featured ID
	if ( isset($tag->term_id) ) {$t_id = $tag->term_id; }
	else { $t_id = ""; }
	$cs_counter = $tag->term_id;
	$cat_meta = get_option( "directory_cat_$t_id");
	$cat_icon = isset($cat_meta['icon']) ? $cat_meta['icon'] : '';
	$cat_image = isset($cat_meta['image']) ? $cat_meta['image'] : '';
	?>
    <tr>
        <th><label for="cat_f_icon_url"><?php _e('Choose Image', 'directory');?></label></th>
        <td>
        <ul class="form-elements directory_cat_image" id="directory_cat_image<?php echo esc_attr($cs_counter);?>">
          <li class="to-label">
            <label><?php _e('Image', 'directory');?></label>
          </li>
          <li class="to-field">
            <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_attr($cat_image) && trim($cat_image) != '' ? 'inline' : 'none';?>" id="directory_cat_image_box" >
              <div class="gal-active">
                <div class="dragareamain" style="padding-bottom:0px;">
                  <ul id="gal-sortable">
                    <li class="ui-state-default">
                      <div class="thumb-secs cs-custom-image"> 
                      	<img src="<?php echo esc_url($cat_image);?>"  id="directory_cat_image_img" width="200" />
                        <div class="gal-edit-opts"> <a href="javascript:del_media('directory_cat_image')" class="delete"></a> </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <input id="directory_cat_image" name="directory_cat_image" type="hidden" class="" value="<?php echo esc_url($cat_image);?>"/>
            <label class="browse-icon"><input name="directory_cat_image"  type="button" class="uploadMedia left" value="<?php _e('Browse','directory'); ?>"/></label>
          </li>
        </ul>
        
        <p><?php _e('Image for Directory Category', 'directory');?></p>
        </td>
	</tr>
    <input type="hidden" name="directory_cat_meta" value="1" />
	<?php
}
endif;

// save cousres categories extra fields hook
add_action ( 'create_directory-category', 'cs_save_extra_category_fileds');
add_action ( 'edited_directory-category', 'cs_save_extra_category_fileds');


//======================================================================
// Function Save extra Category Fields
//======================================================================
if ( ! function_exists( 'cs_save_extra_category_fileds' ) ) :
function cs_save_extra_category_fileds( $term_id ) {
	if ( isset( $_POST['directory_cat_meta'] ) and $_POST['directory_cat_meta'] == '1' ) {
		$t_id = $term_id;
		get_option( "directory_cat_$t_id");
		$directory_cat_image = '';
		if (isset($_POST['directory_cat_image'])){
			$directory_cat_image = $_POST['directory_cat_image'];
		}
		$cat_meta = array(
			'image' => $directory_cat_image,
		);
		//save the option array
		update_option( "directory_cat_$t_id", $cat_meta );
	}
}
endif;

//======================================================================
// Function Set Buffer
//======================================================================
add_action('init', 'cs_do_output_buffer');
function cs_do_output_buffer() {
        ob_start();
}

//======================================================================
// Function Directory Listing Counter
//======================================================================
if ( ! function_exists( 'cs_dir_listing_count' ) ) {
	function cs_dir_listing_count($id, $echo = true){
		$argss = array(
			'posts_per_page'			=> "-1",
			'post_type'					=> 'directory',
			'post_status'				=> array('publish'),
			'meta_query' => array(
				'relation'  => 'AND',
			 		array('key' => 'directory_organizer','value' => $id,'compare' => '='),
			  		array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>=', 'type' => 'NUMERIC'),
			  	),
		);
		$custom_query = new WP_Query($argss);
		$count = $custom_query->found_posts;
		$count = ($count > 0) ? $count:'0';
		if($echo == true){
			echo '<span class="category-list">'.$count.__("Listings","directory").'</span>';
		}
		else{
			return '<span class="category-list">'.$count.__("Listings","directory").'</span>';
		}
		
	}
}

//======================================================================
// Function Directory Type Total Post count
//======================================================================
if ( !function_exists( 'cs_directory_type_post_count' ) ) {
	function cs_directory_type_post_count($cs_type = '', $cs_selected_cat = '') {
		
		$total_post_count = 0;
		if(!empty($cs_selected_cat)) {
			if( ! is_numeric($cs_selected_cat) ) {
				$cs_selected_cat = get_term_by( 'slug', $cs_selected_cat, 'directory-category' );
				$cs_selected_cat_id = $cs_selected_cat->term_id;
			}
			else{
				$cs_selected_cat_id = $cs_selected_cat;
			}
			$cs_term_slug = get_term_by( 'slug', $cs_selected_cat, 'directory-category' );
			if(is_object($cs_term_slug)){
				$total_post_count = $cs_term_slug->count;
			}
		}
		else {
			$directory_categories_array = get_post_meta($cs_type, "directory_types_categories", true);
			$directory_categories_array = explode(",", $directory_categories_array);
			
			if(!empty($directory_categories_array) and is_array($directory_categories_array)){
				foreach($directory_categories_array as $cats) :
					if(!empty($cats)){
						$cs_term_slug = get_term_by( 'slug', $cats, 'directory-category' );
						if(is_object($cs_term_slug)){
							$total_post_count += $cs_term_slug->count;
						}
					}
				endforeach;
			}
		}
		return $total_post_count;
	}
}

//======================================================================
// Function get Countries
//======================================================================
if ( !function_exists( 'cs_get_countries' ) ) {
	function cs_get_countries() {
		$get_countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan",
			"Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "British Virgin Islands",
			"Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China",
			"Colombia", "Comoros", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Democratic People's Republic of Korea", "Democratic Republic of the Congo", "Denmark", "Djibouti",
			"Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "England", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "French Polynesia",
			"Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong",
			"Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan",
			"Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia",
			"Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
			"Myanmar(Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Northern Ireland",
			"Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico",
			"Qatar", "Republic of the Congo", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa",
			"San Marino", "Saudi Arabia", "Scotland", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa",
			"South Korea", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
			"Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "US Virgin Islands", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay",
			"Uzbekistan", "Vanuatu", "Vatican", "Venezuela", "Vietnam", "Wales", "Yemen", "Zambia", "Zimbabwe");
		return $get_countries;
	}
}

//======================================================================
// Function Return Seleced
//====================================================================== 
if(!function_exists('cs_directory_selected')){
	function cs_directory_selected($current,$orignal){
		if($current == $orignal){
			return 'selected=selected';
		}
	}
}

//======================================================================
// User Contact Form
//====================================================================== 
if(!function_exists('cs_user_conatct_form')){
	function cs_user_conatct_form( $organizerID = ''){
		$user_contact_form	= get_the_author_meta( 'user_contact_form', $organizerID );
		if( $user_contact_form == '1') {
		?>
        <div class="csuser_info contactform">
            <div id="cs_requestdetail">
              <div class="rq-form">
               <h5><?php esc_html_e('enquire now','directory');?></h5>
    
                <div id="request-loading"></div>
                <div class="request-message-type succ_mess" style="display:none"><p></p></div>  
                <form id="frm_request" name="frm_request" method="post" novalidate>
                <ul>
                    <li><div class="rqform-text"><input type="text" placeholder="<?php _e('Name','directory');?>" name="request_name" id="request_name" /></div></li>
                    <li><div class="rqform-text"><input type="text" placeholder="<?php _e('Email','directory');?>" name="request_email" id="request_email"></div></li>
                    <li><div class="rqform-text"><input type="text" placeholder="<?php _e('Phone No','directory');?>" name="request_number" id="request_number"></div></li>
                    <li><div class="rqform-text"><textarea placeholder="<?php _e('Message','directory');?>" name="request_message" id="request_message" ></textarea>
                         <ul class="check-box" style="display:none">
                            <li>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkbox2" checked="checked" name="term_condtions_check">
                                </div>
                            </li>
                         </ul>
                            <input type="hidden" name="user_id" value="<?php echo intval( $organizerID );?>" />
                            <input type="hidden" name="action" value="cs_add_request" />
                            <input type="button" value="<?php _e('Submit','directory');?>" class="cs-bgcolor" onclick="cs_request_submission('<?php echo admin_url('admin-ajax.php')?>', '<?php echo get_template_directory_uri()?>');">
                        </div>
                    </li>
                </ul>
               </form>
              </div>
          </div>
        </div>
        <?php
		}
	}
}

//======================================================================
// Review Graph Lines
//======================================================================
if ( ! function_exists( 'cs_reviews_graph_lines' ) ) {
	function cs_reviews_graph_lines( $cs_num = 5, $cs_score = 0, $cs_reviews_count = 0 ){
		
		global $cs_stars_text, $cs_ratings_text;
		$cs_html = '<li>
				  <span class="cs-shorttitle">'.$cs_num.' '.$cs_stars_text.'</span>';
				  $cs_rating_width = 0;
				  if($cs_score > 0){
					  $cs_rating_width = ($cs_score/$cs_reviews_count)*100;
				  }
				  $cs_html .= '<div class="cs-progressbar"><span style=" width: '.$cs_rating_width.'%; "> <small>'.round($cs_rating_width).'%</small> </span></div>';
				  $cs_html .= '<span class="cs-point">'.$cs_score.' '.$cs_ratings_text.'</span>
			  </li>';
		return $cs_html;
	}
}

//======================================================================
// Reviews Graph
//======================================================================
if ( ! function_exists( 'cs_reviews_graph' ) ) {
	function cs_reviews_graph( $cs_post_id ){
		
		global $cs_theme_options, $cs_stars_text, $cs_ratings_text;
		$cs_reviews_post_per_page = isset($cs_theme_options['reviews_per_page']) ? $cs_theme_options['reviews_per_page'] : 10;
		$cs_html = '';
		$cs_directory_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
		$cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);
		
		$reviews_args = array(
			'posts_per_page'		=> "-1",
			'post_type'				=> 'cs-reviews',
			'post_status'			=> 'publish',
			'meta_key'				=> 'cs_reviews_directory',
			'meta_value'			=> $cs_post_id,
			'meta_compare'			=> "=",
			'orderby'				=> 'meta_value',
			'order'					=> 'ASC',
		);
		
		$reviews_query = new WP_Query($reviews_args);
		$cs_reviews_count = $reviews_query->post_count;
		if ( $reviews_query->have_posts() <> "" ){
			
			// Rating Scores Variables
			$cs_rating_score_one	= 0;
			$cs_rating_score_two	= 0;
			$cs_rating_score_three	= 0;
			$cs_rating_score_four	= 0;
			$cs_rating_score_five	= 0;
			// Rating Scores Variables
				
			$cs_html .= '<div class="cs-rating-progress"><ul>';
			while ( $reviews_query->have_posts() ): $reviews_query->the_post();    
				$var_cp_rating = get_post_meta(get_the_id(), "cs_reviews_rating", true);
				$var_cp_reviews_members = get_post_meta(get_the_id(), "cs_reviews_user", true);
				$cs_rating_score = 0;
				$rating_array = array();
				if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
					foreach($cs_rating_options as $rating_key=>$rating){
						if(isset($rating_key) && $rating_key <> ''){
							$rating_id = $rating['rating_id'];
							$rating_title = $rating['rating_title'];
							$rating_slug  = $rating['rating_slug'];
							$rating_point = get_post_meta(get_the_id(), $rating_slug, true);
							if($rating_point)
								$rating_array[] = $rating_point;
						}
					}
					$cs_rating_score = array_sum($rating_array)/count($cs_rating_options);

					switch($cs_rating_score){
						case ( $cs_rating_score == 5 ):
							$cs_rating_score_five++;
							break;
						case ( ($cs_rating_score > 4 && $cs_rating_score < 5) || $cs_rating_score == 4 ):
							$cs_rating_score_four++;
							break;
						case ( ($cs_rating_score > 3 && $cs_rating_score < 4) || $cs_rating_score == 3 ):
							$cs_rating_score_three++;
							break;
						case ( ($cs_rating_score > 2 && $cs_rating_score < 3) || $cs_rating_score == 2 ):
							$cs_rating_score_two++;
							break;
						case ( $cs_rating_score == 1 ):
							$cs_rating_score_one++;
							break;
					}
					
				}
			endwhile;
			wp_reset_query();
			
			$cs_stars_text		= __('Strars','directory');
			$cs_ratings_text	= __('Ratings','directory');
			
			$cs_html .= cs_reviews_graph_lines( '5', $cs_rating_score_five,		$cs_reviews_count );
			$cs_html .= cs_reviews_graph_lines( '4', $cs_rating_score_four,		$cs_reviews_count );
			$cs_html .= cs_reviews_graph_lines( '3', $cs_rating_score_three,	$cs_reviews_count );
			$cs_html .= cs_reviews_graph_lines( '2', $cs_rating_score_two,		$cs_reviews_count );
			$cs_html .= cs_reviews_graph_lines( '1', $cs_rating_score_one,		$cs_reviews_count );			
			
			$cs_html .= '</ul></div>';
		}
		
		return $cs_html;
	}
}

//======================================================================
// Total Reviews
//======================================================================
if ( ! function_exists( 'cs_total_reviews_score' ) ) {
	function cs_total_reviews_score( $cs_post_id, $cs_echo = true ){
		
		global $cs_theme_options;
		$cs_reviews_post_per_page = isset($cs_theme_options['reviews_per_page']) ? $cs_theme_options['reviews_per_page'] : 10;
		
		$cs_directory_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
		$cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);
		
		$cs_reviews_args = array(
			'posts_per_page'		=> "-1",
			'post_type'				=> 'cs-reviews',
			'post_status'			=> 'publish',
			'meta_key'				=> 'cs_reviews_directory',
			'meta_value'			=> $cs_post_id,
			'meta_compare'			=> "=",
			'orderby'				=> 'meta_value',
			'order'					=> 'ASC',
		);
		
		$cs_reviews_query = new WP_Query($cs_reviews_args);
		$cs_reviews_count = $cs_reviews_query->post_count;
		if ( $cs_reviews_query->have_posts() <> "" ){
			$cs_total_rating = 0;
			while ( $cs_reviews_query->have_posts() ): $cs_reviews_query->the_post();    
				$var_cp_rating = get_post_meta(get_the_id(), "cs_reviews_rating", true);
				$var_cp_reviews_members = get_post_meta(get_the_id(), "cs_reviews_user", true);
				
				$cs_rating = 0;
				$cs_rating_array = array();
				if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
					foreach($cs_rating_options as $cs_rating_key=>$cs_rating){
						if(isset($cs_rating_key) && $cs_rating_key <> ''){
							$cs_rating_id = $cs_rating['rating_id'];
							$cs_rating_title = $cs_rating['rating_title'];
							$cs_rating_slug  = $cs_rating['rating_slug'];
							$cs_rating_point = get_post_meta(get_the_id(), $cs_rating_slug, true);
							if($cs_rating_point)
								$cs_rating_array[] = $cs_rating_point;
						}
					}
					$cs_rating = round(array_sum($cs_rating_array)/count($cs_rating_options), 2);
					$cs_total_rating = $cs_total_rating+$cs_rating;
					
				}
			endwhile;
			wp_reset_query();
			$cs_total_rating = round($cs_total_rating/$cs_reviews_count, 2);
			if($cs_echo == true)
				echo cs_allow_special_char($cs_total_rating);
			else
				return $cs_total_rating;
		}
	}
}

//======================================================================
// Total Reviews Stars
//======================================================================
if ( ! function_exists( 'cs_total_reviews_stars' ) ) {
	function cs_total_reviews_stars( $cs_post_id ){
		$cs_directory_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
		$cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);

		$reviews_args = array(
			'posts_per_page'		=> "-1",
			'post_type'				=> 'cs-reviews',
			'post_status'			=> 'publish',
			'meta_key'				=> 'cs_reviews_directory',
			'meta_value'			=> $cs_post_id,
			'meta_compare'			=> "=",
			'orderby'				=> 'meta_value',
			'order'					=> 'ASC',
		);
		
		$reviews_query = new WP_Query($reviews_args);
		$cs_reviews_count = $reviews_query->post_count;
		if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0):
			
			if ( $reviews_query->have_posts() <> "" ){

				$cs_total_rating = cs_total_reviews_score($cs_post_id, false);
				$cs_rating_width = 0;
				if($cs_total_rating > 0){
					$cs_rating_width = ($cs_total_rating/5)*100;
				}
				?>
				<div class="cs-ratingstar-wrap">
					<div class="cs-ratingstar cs-custom-rate">
						<span style="width: <?php echo cs_allow_special_char($cs_rating_width); ?>%;"></span>
					</div>
				</div>
				<?php
			}
			
		endif;
	}
}

//======================================================================
// Review Partial Score
//======================================================================
if ( ! function_exists( 'cs_partial_reviews_score' ) ) {
	function cs_partial_reviews_score( $cs_post_id ){
		
		global $cs_theme_options;
		$cs_reviews_post_per_page = isset($cs_theme_options['reviews_per_page']) ? $cs_theme_options['reviews_per_page'] : 10;
		$cs_html = '';
		$cs_directory_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
		$cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);

		$reviews_args = array(
			'posts_per_page'		=> "-1",
			'post_type'				=> 'cs-reviews',
			'post_status'			=> 'publish',
			'meta_key'				=> 'cs_reviews_directory',
			'meta_value'			=> $cs_post_id,
			'meta_compare'			=> "=",
			'orderby'				=> 'meta_value',
			'order'					=> 'ASC',
		);
		
		$reviews_query = new WP_Query($reviews_args);
		$cs_reviews_count = $reviews_query->post_count;
		if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
			
			if ( $reviews_query->have_posts() <> "" ){
				
				$cs_rating_id = array();
				foreach($cs_rating_options as $cs_rating_key=>$cs_rate){
					if(isset($cs_rating_key) && $cs_rating_key <> ''){
						
						if( isset($cs_rate['rating_id']) ){
							$cs_rating_id[$cs_rate['rating_id']] = array();
						}
					}
				}
				
				$cs_html .= '<div class="cs-rating-services"><ul>';
				while ( $reviews_query->have_posts() ): $reviews_query->the_post();    
					$var_cp_rating = get_post_meta(get_the_id(), "cs_reviews_rating", true);
					$var_cp_reviews_members = get_post_meta(get_the_id(), "cs_reviews_user", true);
					
					$rating = 0;
					
					foreach($cs_rating_options as $rating_key=>$rating){
						if(isset($rating_key) && $rating_key <> ''){
							$rating_id = $rating['rating_id'];
							$rating_title = $rating['rating_title'];
							$rating_slug  = $rating['rating_slug'];
							$rating_point = get_post_meta(get_the_id(), $rating_slug, true);
							if($rating_point) {
								if( isset($cs_rating_id[$rating_id]) ){
									$cs_rating_id[$rating_id][] = $rating_point;
								}
							}
						}
					}
					
				endwhile;
				wp_reset_query();

				foreach($cs_rating_options as $cs_rating_key=>$cs_rate){
					if(isset($cs_rating_key) && $cs_rating_key <> ''){
						
						if( isset($cs_rate['rating_id']) ){
							$rating_id    = $cs_rate['rating_id'];
							$rating_title = $cs_rate['rating_title'];
							
							
							if( isset($cs_rating_id[$rating_id]) && is_array($cs_rating_id[$rating_id]) ) {
								$cs_total_ratings = round(array_sum($cs_rating_id[$rating_id])/count($cs_rating_options), 2);
								$cs_rating_width = 0;
								if(array_sum($cs_rating_id[$rating_id]) > 0){
									$cs_rating_width = (array_sum($cs_rating_id[$rating_id])/(5*$cs_reviews_count))*100;
								}
								$cs_html .= '<li><span class="cs-shorttitle">'.$rating_title.'</span>';
								$cs_html .=	'<div class="cs-ratingstar-wrap"><div class="cs-ratingstar"><span style="width:'.$cs_rating_width.'%"></span></div></div>';
								$cs_html .= '</li>';
							}
						}
						//
					}
				}
				
				$cs_html .= '</ul></div>';
			}
		}
		
		return $cs_html;
	}
}

//======================================================================
// Review Total Score Text
//======================================================================
if ( ! function_exists( 'cs_total_score_text' ) ) {
	function cs_total_score_text( $cs_total_rating, $cs_brackets = true ){
		
		if( is_numeric($cs_total_rating) && $cs_total_rating > 0 ) { 
			switch($cs_total_rating){
				case ( $cs_total_rating > 4.5 ):
					$cs_score_text = __('Excellent','directory');
					break;
				case ( $cs_total_rating > 3.5 && $cs_total_rating < 4.5 ):
					$cs_score_text = __('Good','directory');
					break;
				case ( $cs_total_rating > 2 && $cs_total_rating < 3.5 ):
					$cs_score_text = __('Average','directory');
					break;
				default:
					$cs_score_text = __('Bad','directory');
			}
			
			if( $cs_brackets == true )
				return '('.$cs_score_text.')';
			else
				return $cs_score_text;
		} 
	}
}

//======================================================================
// Review Total Scor Section
//======================================================================
if ( ! function_exists( 'cs_total_score_section' ) ) {
	function cs_total_score_section( $cs_post_id ){
		
		$cs_directory_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
		$cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);

		$reviews_args = array(
			'posts_per_page'		=> "-1",
			'post_type'				=> 'cs-reviews',
			'post_status'			=> 'publish',
			'meta_key'				=> 'cs_reviews_directory',
			'meta_value'			=> $cs_post_id,
			'meta_compare'			=> "=",
			'orderby'				=> 'meta_value',
			'order'					=> 'ASC',
		);
		
		$reviews_query = new WP_Query($reviews_args);
		$cs_reviews_count = $reviews_query->post_count;
		if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
			
			if ( $reviews_query->have_posts() <> "" ){
			?>
			<div class="cs-rating-wrap">
				<div class="cs-stars-wrap">
					<div class="cs-table">
						<div class="cs-table-row">
							<?php 
							echo cs_partial_reviews_score( $cs_post_id ); 
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
		}
	}
}


/*-------------------------------------------------------
 * Directory Get Locations
 *------------------------------------------------------*/
if ( ! function_exists( 'cs_get_custom_locations' ) ) {
	function cs_get_custom_locations( $rand_id = '', $geo_location = "no"){
		global $cs_theme_options;
		
		wp_directory::cs_load_choosen_scripts();
		$goe_location_enable    = isset($cs_theme_options['goe_location_enable']) ? $cs_theme_options['goe_location_enable'] : 'No';
		$cs_location_type		= isset( $cs_theme_options['cs_search_by_location'] ) ?  $cs_theme_options['cs_search_by_location'] : '';
		$cs_location_countries	= get_option('cs_location_countries');
		$states_list			= get_option('cs_location_states');
		$cities_list			= get_option('cs_location_cities');

		
		ob_start();
		
		$location_list	= '';
		$selectedkey	= '';
		if( isset( $_GET['location'] ) && $_GET['location'] !='' ){
			$selectedkey	=  $_GET['location'];
		}
		
		if ( $cs_location_type == 'countries_only' ) {
			
			if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) ) {
				foreach( $cs_location_countries as $key => $country ) {
					 $selected	= '';
					 if( $selectedkey == $country['name'] ) {
						$selected	= 'selected'; 
					 }
					 
					 $location_list	.=  "<option class=\"item\" ".$selected." value='".$country['name']."'>".$country['name']."</option>";
				}
			} 
			
		} else if ( $cs_location_type == 'countries_and_cities' ) {
			
			if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) ) {
				foreach( $cs_location_countries as $key => $country ) {
					 $selected_Country	= '';
					 
					 if( $selectedkey == $country['name'] ) {
						$selected_Country	= 'selected'; 
					 }
					 
					 $location_list	.=  "<option class=\"category\" ".$selected_Country." value='".$country['name']."'>".$country['name']."</option>";
					 
					 if( isset( $states_list[$country['id']] ) ) {
						 $states	= $states_list[$country['id']];		
						 if( isset( $states ) && $states !='' && is_array($states) ) {
								foreach( $states as $key => $state ) {
									if( isset( $cities_list[$country['id']][$key] ) ) {
										$cities	= $cities_list[$country['id']][$key];
										if( isset( $cities ) && $cities !='' && is_array($cities) ) {
											foreach( $cities as $key => $city ) {
												$selected	= '';
												if( $selectedkey == $city['name'] ) {
													$selected	= 'selected'; 
												}
												
												$location_list	.=  "<option class=\"item\" ".$selected." value='".$city['name']."'>".$city['name']."</option>";
											}
										}
									}
								}
						  }
					 }
				}
			}
			
		} else if ( $cs_location_type == 'cities_only' ) {
			if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) ) {
				foreach( $cs_location_countries as $key => $country ) {
					 if( isset( $states_list[$country['id']] ) ) {
						 $states	= $states_list[$country['id']];
						 if( isset( $states ) && $states !='' && is_array($states) ) {
								foreach( $states as $key => $state ) {
									if( isset( $cities_list[$country['id']][$key] ) ) {
										$cities	= $cities_list[$country['id']][$key];
										if( isset( $cities ) && $cities !='' && is_array($cities) ) {
											foreach( $cities as $key => $city ) {
												$selected	= '';
												if( $selectedkey == $city['name'] ) {
													$selected	= 'selected'; 
												}
												$location_list	.=  "<option class=\"item\" ".$selected." value='".$city['name']."'>".$city['name']."</option>";
											}
										}
									}
								}
						  }
					 }
				}
			}
		} else if ( $cs_location_type == 'single_city' ) {
			$location_city		= isset( $cs_theme_options['cs_search_by_location_city'] ) ?  $cs_theme_options['cs_search_by_location_city'] : '';
			 if ( isset( $location_city ) && ! empty( $location_city ) ) {
				echo '<input type="hidden" name="location" value="'.$location_city.'">';
			 }
		}
		
		if ( $cs_location_type != 'single_city' ) {?>
		<script>
			jQuery(document).ready(function($) {
				jQuery(".search-custom-location").chosen({
					create_option: true,
					persistent_create_option: true,
					create_option_text: 'add',
				});
			});
		</script>
        <select class="dir-map-search single-select search-custom-location" id="directory-search-location" title="<?php _e('Location', 'directory');?>" placeholder="<?php _e('Postcode or location','directory');?>" name="location">
			<option value="" class="category" ><?php _e('Select Locations', 'directory');?></option>
			<?php echo $location_list; ?>
		</select>
	   <?php
		}
	 	
		$post_data = ob_get_clean();
        echo $post_data;
	}
}

//==============
//@ remove fields
//==============
function css_slugify_text($str) {
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
    $clean = strtolower(trim($clean, '_'));
    $clean = preg_replace("/[\/_|+ -]+/", '_', $clean);
    return $clean;
}
