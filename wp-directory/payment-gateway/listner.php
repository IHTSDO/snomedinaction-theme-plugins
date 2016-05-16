<?php
global $cs_theme_options;
include_once('../../../../wp-load.php');


//Build the data to post back to Paypal
$postback = 'cmd=_notify-validate'; 
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$postback .= "&$key=$value";
}

$ourFileName = "debug1_postdata.txt";
$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
fwrite($ourFileHandle, $postback);
fclose($ourFileHandle);
	
/*
 * Paypal Gateway Listner
 */

define("DEBUG", 1);
define("USE_SANDBOX", 1);
define("LOG_FILE", "./ipn.log");
if ( isset( $_POST['payment_status'] ) && $_POST['payment_status'] == 'Completed' ) {
	## Paypal Saving

	$directory_id 		= $_POST['item_number'];
	$cs_current_date	= date('Y-m-d H:i:s');
	$transaction_array  = array();
	if(isset($directory_id) && !empty($directory_id)){
		$cs_pack_tra_meta = get_post_meta($directory_id, "dir_pakage_transaction_meta", true);
		if(is_int($cs_pack_tra_meta)){
			$cs_pack_tra_meta = array();
		}
		if($cs_pack_tra_meta == ''){
			$cs_pack_tra_meta = array();
		}
		if(!is_array($cs_pack_tra_meta) || empty($cs_pack_tra_meta)  || $cs_pack_tra_meta == ''){
			$cs_pack_tra_meta = array();	
		}
		$trans_counter = 0;
		if(is_array($cs_pack_tra_meta) && count($cs_pack_tra_meta)>0){
			$trans_counter = count($cs_pack_tra_meta);
		}
		
		$custom_var =  $_POST['custom'];
		$custom_var_array = explode('_',$custom_var);
		
		if(isset($custom_var_array['0'])){
			$user_id = $custom_var_array['0'];
		}
		
		if(isset($custom_var_array['1'])){
			$package_id = $custom_var_array['1'];
		}
		
		$featured	= 'no';
		
		if(isset($custom_var_array['2'])){
			$featured = $custom_var_array['2'];
		}
		
		$index_count = 0;
		$cs_tra_meta = get_option('cs_directory_transaction_meta', true);
		
		if(is_int($cs_tra_meta)){
			$cs_tra_meta = array();
		}
		
		if(!isset($cs_tra_meta) || empty($cs_tra_meta) || !is_array($cs_tra_meta)){
			$cs_tra_meta = array();
		}
 		
		if(isset($cs_tra_meta[$directory_id]) && is_array($cs_tra_meta[$directory_id]) && count($cs_tra_meta[$directory_id])>0){
			$index_count = (int)count($cs_tra_meta[$directory_id]);
		}
 		
		if(isset($_POST['txn_id']) && $_POST['txn_id'] <> ''){
			$tnx_type = 'transaction';	
		} else {
			$tnx_type = 'subscription';	
		}
		
		/*----------------------------------------------------	
		 * All Transactions Data Saved
		/*----------------------------------------------------*/
		$cs_directory_status    = isset($cs_theme_options['cs_directory_visibility']) ? $cs_theme_options['cs_directory_visibility'] : 'pending';
		$package_featured_ads	= isset( $cs_theme_options['directory_featured_ad_price'] ) ? $cs_theme_options['directory_featured_ad_price'] : 0; 
		
		$cs_tra_meta[$directory_id][$index_count][$tnx_type] = $_POST;
		update_option('cs_directory_transaction_meta', $cs_tra_meta);
 		$directory_post 				= array();
		$directory_post['ID'] 			= (int)$directory_id;
		$directory_post['post_status']  = $cs_directory_status;
		wp_update_post( $directory_post );
		
		if(isset($_POST['txn_id']) && $_POST['txn_id'] <> ''){
			$transection_array = array();
			$transection_array['user_id'] 		= esc_attr($user_id);
			$transection_array['package_id'] 	= esc_attr($package_id);
			$transection_array['item_name'] 	= esc_attr($_POST['item_name']);
			$transection_array['txn_id'] 		= esc_attr($_POST['txn_id']);
			$transection_array['payment_date']  = esc_attr($_POST['payment_date']);
			$transection_array['payer_email'] 	= esc_attr($_POST['payer_email']);
			$transection_array['payment_gross'] = esc_attr($_POST['payment_gross']);
			$transection_array['mc_currency'] 	= esc_attr($_POST['mc_currency']);
			$transection_array['address_name'] 	= esc_attr($_POST['address_name']);
			$transection_array['ipn_track_id'] 	= esc_attr($_POST['ipn_track_id']);
			$transection_array['transaction_status']		= 'approved';
			$transection_array['payment_method']	= 'cs_paypal_gateway';
			$transection_array['full_address']	= esc_attr($_POST['address_street']).' '.esc_attr($_POST['address_city']).' '.esc_attr($_POST['address_country']);
			$transection_array['first_name'] 	= esc_attr($_POST['first_name']);
			$transection_array['last_name'] 	= esc_attr($_POST['last_name']);
			$transection_array['purchase_on'] 	= date('Y-m-d H:i:s');
			$transection_array['post_id'] 		= (int)$directory_id;
			
			if( $transection_array['payer_email'] == '' ) {
				$transection_array['payer_email']	= cs_get_user_data($user_id,'user_email');
			}
			
			$cs_pack_tra_meta[$trans_counter] 	= $transection_array;
			
			$payment_date = date_i18n( 'Y-m-d H:i:s', strtotime(esc_attr($_POST['payment_date'])));
			
			update_post_meta((int)$directory_id, 'current_gateway', 'cs_paypal_gateway' );
			update_post_meta((int)$directory_id, 'dir_pakage_transaction_meta', $cs_pack_tra_meta);
			update_post_meta((int)$directory_id, 'dir_payment_date', $payment_date);
			
			/*----------------------------------------------------	
			 * Update Post Status
			/*----------------------------------------------------*/
			$postStatus['ID'] 			= $directory_id;
			$postStatus['post_status']  = $cs_directory_status;
			wp_update_post( $postStatus );
			
			/*----------------------------------------------------	
			 * Update Featured Status
			/*----------------------------------------------------*/
			update_post_meta( $directory_id,'cs_directory_pkg_names', $package_id );
			
			if ( $package_id =='0000000000' ){
				$package_meta = get_post_meta($directory_id, "_pakage_meta", true);
			} else {
				$cs_packages_options 	= get_option('cs_packages_options');
				$package_meta 			= $cs_packages_options[$package_id];
			}

			if(isset($package_meta['package_duration']) && $package_meta['package_duration'] == 'unlimited' ){
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $cs_current_date);
			} else if(isset($package_meta['package_duration'])){
				$package_duration = $package_meta['package_duration'];
				$date 		 	  = strtotime("+".$package_duration." days", strtotime($payment_date));
				$expire_date 	  = date("Y-m-d H:i:s", $date);
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $expire_date);
				
				if ( isset( $featured ) && $featured == 'yes' ) {
					$package_meta['package_price'] = $package_meta['package_price'] + $package_featured_ads;
				}
				
				$package_meta['transection_status']	= 'completed';
				$package_meta['transection_id']		= $_POST['txn_id'];
				$package_meta['payment_gateway']	= 'cs_paypal_gateway';
				update_post_meta( $directory_id, '_pakage_meta', $package_meta );
			}
				
			/*----------------------------------------------------	
			 * Update Package Add Till Date
			/*----------------------------------------------------*/
 			if ( isset( $featured ) && $featured == 'yes' ) {
				$featured_days	= isset( $cs_theme_options['directory_featured_ad_days'] ) ? $cs_theme_options['directory_featured_ad_days'] : 0;
				if($featured_days < 1 || $featured_days == '')
					$featured_days = 0;
				
				$featured_date		 = strtotime("+".$featured_days." days", strtotime($payment_date));
				$featured_date 		 = date("Y-m-d H:i:s", $featured_date);
				update_post_meta($directory_id, 'dir_featured_till', $featured_date);
		 	 }			
		}

		/*----------------------------------------------------	
		 * User Payment Re-attempt 
		/*----------------------------------------------------*/
		if(isset($_POST['reattempt']) && $_POST['reattempt'] <> ''){
			$pakage_subs_meta = get_post_meta($directory_id, "dir_pakage_trans_subsription_meta", true);
			if(is_int($pakage_subs_meta)){
				$pakage_subs_meta = array();
			}
			if($pakage_subs_meta == ''){
				$pakage_subs_meta = array();
			}
			if(!is_array($pakage_subs_meta) || empty($pakage_subs_meta)  || $pakage_subs_meta == ''){
				$pakage_subs_meta = array();	
			}
			$trans_counter = 0;
 			if( is_array( $pakage_subs_meta ) && count( $pakage_subs_meta ) > 0 ){
				$trans_counter = count($pakage_subs_meta);
			}
		
 			$subs_directory_array = array();
			$subs_directory_array['user_id'] 		= esc_attr($user_id);
			$subs_directory_array['package_id'] 	= esc_attr($package_id);
			$subs_directory_array['item_name'] 		= esc_attr($_POST['item_name']);
			$subs_directory_array['payment_date']   = esc_attr($_POST['payment_date']);
			$subs_directory_array['payer_email'] 	= esc_attr($_POST['payer_email']);
			$subs_directory_array['amount3'] 		= esc_attr($_POST['amount3']);
			$subs_directory_array['mc_currency'] 	= esc_attr($_POST['mc_currency']);
			$subs_directory_array['address_name'] 	= esc_attr($_POST['address_name']);
			$subs_directory_array['ipn_track_id'] 	= esc_attr($_POST['ipn_track_id']);
			$subs_directory_array['transaction_status']		= 'approved';
			$subs_directory_array['payment_method']	= 'cs_paypal_gateway';
			$subs_directory_array['full_address']	= esc_attr($_POST['address_street']).' '.esc_attr($_POST['address_city']).' '.esc_attr($_POST['address_country']);
			$subs_directory_array['first_name'] 	= esc_attr($_POST['first_name']);
			$subs_directory_array['last_name'] 		= esc_attr($_POST['last_name']);
			$subs_directory_array['purchase_on'] 	= date('Y-m-d H:i:s');
			$subs_directory_array['post_id'] 		= (int)$directory_id;
			
			if( $transection_array['payer_email'] == '' ) {
				$transection_array['payer_email']	= cs_get_user_data($user_id,'user_email');
			}
			
			
			$pakage_subs_meta[$trans_counter] 		= $subs_directory_array;
			
			$payment_date = date_i18n( 'Y-m-d H:i:s', strtotime($_POST['payment_date']) );
			
			update_post_meta((int)$directory_id, 'dir_pakage_trans_subsription_meta', $pakage_subs_meta);
			update_post_meta((int)$directory_id, 'dir_payment_date', $payment_date);
 
			/*----------------------------------------------------	
			 * Update Post Status
			/*----------------------------------------------------*/
			$postStatus['ID'] 			= $directory_id;
			$postStatus['post_status']  = $cs_directory_status;
			wp_update_post( $postStatus );
			
			/*----------------------------------------------------	
			 * Update Featured Status
			/*----------------------------------------------------*/
			update_post_meta( $directory_id,'cs_directory_pkg_names', $package_id );
			
			if ( $package_id == '0000000000' ){
				$package_meta = get_post_meta($directory_id, "_pakage_meta", true);
			} else {
				$cs_packages_options 	= get_option('cs_packages_options');
				$package_meta 			= $cs_packages_options[$package_id];
			}
			
			if(isset($package_meta['package_duration']) && $package_meta['package_duration'] == 'unlimited' ){
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $cs_current_date);
			} else if(isset($package_meta['package_duration'])){
				
				$package_duration = $package_meta['package_duration'];
				$subscr_date      = esc_attr($_POST['payment_date']);
				$date = strtotime("+".$package_duration." days", strtotime($subscr_date));
				$expire_date = date("Y-m-d H:i:s", $date);
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $expire_date);
				
				if ( isset( $featured ) && $featured == 'yes' ) {
					$package_meta['package_price']	= $package_meta['package_price'] + $package_featured_ads;
				}
				
				$package_meta['transection_status']	= 'completed';
				$package_meta['transection_id']		= $_POST['txn_id'];
				$package_meta['payment_gateway']	= 'cs_paypal_gateway';
				update_post_meta( $directory_id, '_pakage_meta', $package_meta );
			}

			/*----------------------------------------------------	
			 * Update Package Add Till Date
			/*----------------------------------------------------*/
			if ( isset( $featured ) && $featured == 'yes' ) {
				
				$featured_days	= isset( $cs_theme_options['directory_featured_ad_days'] ) ? $cs_theme_options['directory_featured_ad_days'] : 0;
				if($featured_days < 1 || $featured_days == '')
					$featured_days = 0;
				
				$featured_date		 = strtotime("+".$featured_days." days", strtotime($payment_date));
				$featured_date 		 = date("Y-m-d H:i:s", $featured_date);
				update_post_meta($directory_id, 'dir_featured_till', $featured_date);
		 	 }	
		}
		
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
		}
	}
	
}else if (strcmp ($res, "INVALID") == 0) {
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	}
}

/*
 * Authorize Gateway Listner
 */
if ( isset( $_POST['x_response_code'] ) && $_POST['x_response_code'] == '1' ) {
	
	## Authorize.net Saving

	$directory_id 		= $_POST['x_po_num'];
	$item_var_array 	= explode('_',$directory_id);
	$directory_id 		= $item_var_array['0'];
	
	if(isset($item_var_array['1'])){
		$currency_type = $custom_var_array['1'];
	}
	
	$cs_current_date	= date('Y-m-d H:i:s');
	$transaction_array  = array();
	if(isset($directory_id) && !empty($directory_id)){
		$cs_pack_tra_meta = get_post_meta($directory_id, "dir_pakage_transaction_meta", true);
		if(is_int($cs_pack_tra_meta)){
			$cs_pack_tra_meta = array();
		}
		if($cs_pack_tra_meta == ''){
			$cs_pack_tra_meta = array();
		}
		if(!is_array($cs_pack_tra_meta) || empty($cs_pack_tra_meta)  || $cs_pack_tra_meta == ''){
			$cs_pack_tra_meta = array();	
		}
		$trans_counter = 0;
		if(is_array($cs_pack_tra_meta) && count($cs_pack_tra_meta)>0){
			$trans_counter = count($cs_pack_tra_meta);
		}
		
		$custom_var =  $_POST['x_cust_id'];
		$custom_var_array = explode('_',$custom_var);
		
		if(isset($custom_var_array['0'])){
			$user_id = $custom_var_array['0'];
		}
		
		if(isset($custom_var_array['1'])){
			$package_id = $custom_var_array['1'];
		}
		
		$featured	= 'no';
		
		if(isset($custom_var_array['2'])){
			$featured = $custom_var_array['2'];
		}
		
		
		
		$index_count = 0;
		$cs_tra_meta = get_option('cs_directory_transaction_meta', true);
		
		if(is_int($cs_tra_meta)){
			$cs_tra_meta = array();
		}
		
		if(!isset($cs_tra_meta) || empty($cs_tra_meta) || !is_array($cs_tra_meta)){
			$cs_tra_meta = array();
		}
 		
		if(isset($cs_tra_meta[$directory_id]) && is_array($cs_tra_meta[$directory_id]) && count($cs_tra_meta[$directory_id])>0){
			$index_count = (int)count($cs_tra_meta[$directory_id]);
		}
 		
		if(isset($_POST['txn_id']) && $_POST['txn_id'] <> ''){
			$tnx_type = 'transaction';	
		} else {
			$tnx_type = 'subscription';	
		}
		
		/*----------------------------------------------------	
		 * All Transactions Data Saved
		/*----------------------------------------------------*/
		$cs_directory_status    = isset($cs_theme_options['cs_directory_visibility']) ? $cs_theme_options['cs_directory_visibility'] : 'pending';
		$package_featured_ads	= isset( $cs_theme_options['directory_featured_ad_price'] ) ? $cs_theme_options['directory_featured_ad_price'] : 0; 
		
		$cs_tra_meta[$directory_id][$index_count][$tnx_type] = $_POST;
		update_option('cs_directory_transaction_meta', $cs_tra_meta);
 		$directory_post 				= array();
		$directory_post['ID'] 			= (int)$directory_id;
		$directory_post['post_status']  = $cs_directory_status;
		wp_update_post( $directory_post );
		
		if(isset($_POST['x_trans_id']) && $_POST['x_trans_id'] <> ''){
			$transection_array = array();
			$currency				= isset( $currency_type ) && $currency_type !='' ? $currency_type : 'USD';
			$transection_array['user_id'] 		= esc_attr($user_id);
			$transection_array['package_id'] 	= esc_attr($package_id);
			$transection_array['item_name'] 	= esc_attr($_POST['x_description']);
			$transection_array['txn_id'] 		= esc_attr($_POST['x_trans_id']);
			$transection_array['payment_date']  = date('Y-m-d H:i:s');
			$transection_array['payer_email'] 	= esc_attr($_POST['x_email']);
			$transection_array['payment_gross'] = esc_attr($_POST['x_amount']);
			$transection_array['mc_currency'] 	= $currency;
			$transection_array['address_name'] 	= esc_attr($_POST['x_first_name']).' '.esc_attr($_POST['x_last_name']);
			$transection_array['ipn_track_id'] 	= '';
			$transection_array['transaction_status']		= 'approved';
			$transection_array['payment_method']			= 'cs_authorizedotnet_gateway';
			$transection_array['full_address']	= esc_attr($_POST['x_address']).' '.esc_attr($_POST['x_city']).' '.esc_attr($_POST['x_country']);
			$transection_array['first_name'] 	= esc_attr($_POST['x_first_name']);
			$transection_array['last_name'] 	= esc_attr($_POST['x_last_name']);
			$transection_array['purchase_on'] 	= date('Y-m-d H:i:s');
			$transection_array['post_id'] 		= (int)$directory_id;
			
			$cs_pack_tra_meta[$trans_counter] 	= $transection_array;
			
			if( $transection_array['x_email'] == '' ) {
				$transection_array['payer_email']	= cs_get_user_data($user_id,'user_email');
			}
			
			if( $transection_array['first_name'] == '' ) {
				$transection_array['first_name']				= cs_get_user_data($user_id,'first_name');
			}
			
			if( $transection_array['last_name'] == '' ) {
				$transection_array['last_name']					= cs_get_user_data($user_id,'last_name');
			}
			
				
			$payment_date = date_i18n( 'Y-m-d H:i:s', strtotime(esc_attr(date('Y-m-d H:i:s'))));
			
			update_post_meta((int)$directory_id, 'current_gateway', 'cs_authorizedotnet_gateway' );
			update_post_meta((int)$directory_id, 'dir_pakage_transaction_meta', $cs_pack_tra_meta);
			update_post_meta((int)$directory_id, 'dir_payment_date', $payment_date);
			
			/*----------------------------------------------------	
			 * Update Post Status
			/*----------------------------------------------------*/
			$postStatus['ID'] 			= $directory_id;
			$postStatus['post_status']  = $cs_directory_status;
			wp_update_post( $postStatus );
			
			/*----------------------------------------------------	
			 * Update Featured Status
			/*----------------------------------------------------*/
			update_post_meta( $directory_id,'cs_directory_pkg_names', $package_id );
			
			if ( $package_id =='0000000000' ){
				$package_meta = get_post_meta($directory_id, "_pakage_meta", true);
			} else {
				$cs_packages_options 	= get_option('cs_packages_options');
				$package_meta 			= $cs_packages_options[$package_id];
			}

			if(isset($package_meta['package_duration']) && $package_meta['package_duration'] == 'unlimited' ){
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $cs_current_date);
			} else if(isset($package_meta['package_duration'])){
				$package_duration = $package_meta['package_duration'];
				$date 		 	  = strtotime("+".$package_duration." days", strtotime($payment_date));
				$expire_date 	  = date("Y-m-d H:i:s", $date);
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $expire_date);
				
				if ( isset( $featured ) && $featured == 'yes' ) {
					$package_meta['package_price'] = $package_meta['package_price'] + $package_featured_ads;
				}
				
				$package_meta['transection_status']	= 'completed';
				$package_meta['transection_id']		= $_POST['x_trans_id'];
				$package_meta['payment_gateway']	= 'cs_paypal_gateway';
				update_post_meta( $directory_id, '_pakage_meta', $package_meta );
			}
				
			/*----------------------------------------------------	
			 * Update Package Add Till Date
			/*----------------------------------------------------*/
 			if ( isset( $featured ) && $featured == 'yes' ) {
				$featured_days	= isset( $cs_theme_options['directory_featured_ad_days'] ) ? $cs_theme_options['directory_featured_ad_days'] : 0;
				if($featured_days < 1 || $featured_days == '')
					$featured_days = 0;
				
				$featured_date		 = strtotime("+".$featured_days." days", strtotime($payment_date));
				$featured_date 		 = date("Y-m-d H:i:s", $featured_date);
				update_post_meta($directory_id, 'dir_featured_till', $featured_date);
		 	 }			
		}
	}	

}

/*
 * Skrill Gateway Listner
 */

if( isset( $_POST['merchant_id'] ) ) {
	// Validate the Moneybookers signature
	$concatFields = $_POST['merchant_id']
		.$_POST['order_id']
		.strtoupper(md5('Paste your secret word here'))
		.$_POST['mb_amount']
		.$_POST['mb_currency']
		.$_POST['status'];
	
	$cs_theme_options	= get_option('cs_theme_options');
	
	$MBEmail = $cs_theme_options['skrill_email'];
	
	// Ensure the signature is valid, the status code == 2,
	// and that the money is going to you
	if ( isset( $_POST['status'] ) && $_POST['status'] == '2' && trim( $_POST['pay_to_email'] ) == trim( $MBEmail ) ){
		
	
	## Authorize.net Saving

	$data = explode('_',$_POST['transaction_id']);
	$user_id		= $data[0];
	$package_id		= $data[1];
	$featured		= $data[2];
	$directory_id	= $data[3];
	
	$cs_current_date	= date('Y-m-d H:i:s');
	$transaction_array  = array();
	if(isset($directory_id) && !empty($directory_id)){
		$cs_pack_tra_meta = get_post_meta($directory_id, "dir_pakage_transaction_meta", true);
		if(is_int($cs_pack_tra_meta)){
			$cs_pack_tra_meta = array();
		}
		if($cs_pack_tra_meta == ''){
			$cs_pack_tra_meta = array();
		}
		if(!is_array($cs_pack_tra_meta) || empty($cs_pack_tra_meta)  || $cs_pack_tra_meta == ''){
			$cs_pack_tra_meta = array();	
		}
		$trans_counter = 0;
		if(is_array($cs_pack_tra_meta) && count($cs_pack_tra_meta)>0){
			$trans_counter = count($cs_pack_tra_meta);
		}
		
		$custom_var =  $_POST['transaction_id'];
		$custom_var_array = explode('_',$custom_var);
		
		if(isset($custom_var_array['0'])){
			$user_id = $custom_var_array['0'];
		}
		
		if(isset($custom_var_array['1'])){
			$package_id = $custom_var_array['1'];
		}
		
		$featured	= 'no';
		
		if(isset($custom_var_array['2'])){
			$featured = $custom_var_array['2'];
		}
		
		$index_count = 0;
		$cs_tra_meta = get_option('cs_directory_transaction_meta', true);
		
		if(is_int($cs_tra_meta)){
			$cs_tra_meta = array();
		}
		
		if(!isset($cs_tra_meta) || empty($cs_tra_meta) || !is_array($cs_tra_meta)){
			$cs_tra_meta = array();
		}
 		
		if(isset($cs_tra_meta[$directory_id]) && is_array($cs_tra_meta[$directory_id]) && count($cs_tra_meta[$directory_id])>0){
			$index_count = (int)count($cs_tra_meta[$directory_id]);
		}
 		
		if(isset($_POST['mb_transaction_id']) && $_POST['mb_transaction_id'] <> ''){
			$tnx_type = 'transaction';	
		} else {
			$tnx_type = 'subscription';	
		}
		
		/*----------------------------------------------------	
		 * All Transactions Data Saved
		/*----------------------------------------------------*/
		$cs_directory_status    = isset($cs_theme_options['cs_directory_visibility']) ? $cs_theme_options['cs_directory_visibility'] : 'pending';
		$package_featured_ads	= isset( $cs_theme_options['directory_featured_ad_price'] ) ? $cs_theme_options['directory_featured_ad_price'] : 0; 
		
		$cs_tra_meta[$directory_id][$index_count][$tnx_type] = $_POST;
		update_option('cs_directory_transaction_meta', $cs_tra_meta);
 		$directory_post 				= array();
		$directory_post['ID'] 			= (int)$directory_id;
		$directory_post['post_status']  = $cs_directory_status;
		wp_update_post( $directory_post );
		
		if(isset($_POST['mb_transaction_id']) && $_POST['mb_transaction_id'] <> ''){
			$transection_array = array();
			$currency				= isset( $_POST['currency'] ) && $_POST['currency'] !='' ? $_POST['currency'] : 'USD';
			$transection_array['user_id'] 		= esc_attr($user_id);
			$transection_array['package_id'] 	= esc_attr($package_id);
			$transection_array['item_name'] 	= '';
			$transection_array['txn_id'] 		= esc_attr($_POST['mb_transaction_id']);
			$transection_array['payment_date']  = date('Y-m-d H:i:s');
			$transection_array['payer_email'] 	= esc_attr($_POST['pay_from_email']);
			$transection_array['payment_gross'] = esc_attr($_POST['mb_amount']);
			$transection_array['mc_currency'] 	= $currency;
			$transection_array['ipn_track_id'] 	= '';
			$transection_array['transaction_status']		= 'approved';
			$transection_array['payment_method']			= 'cs_skrill_gateway';
			
			if( $user_id != '' ) {
				if( $transection_array['pay_from_email'] == '' ) {
					$transection_array['pay_from_email']	= cs_get_user_data($user_id,'user_email');
				}
				
				$transection_array['first_name']				= cs_get_user_data($user_id,'first_name');
				$transection_array['last_name']					= cs_get_user_data($user_id,'last_name');
				$transection_array['full_address']				= cs_get_user_data($user_id,'address');
			}
	
			$transection_array['purchase_on'] 	= date('Y-m-d H:i:s');
			$transection_array['post_id'] 		= (int)$directory_id;
			
			$cs_pack_tra_meta[$trans_counter] 	= $transection_array;
			
			$payment_date = date_i18n( 'Y-m-d H:i:s', strtotime(esc_attr(date('Y-m-d H:i:s'))));
			
			update_post_meta((int)$directory_id, 'current_gateway', 'cs_skrill_gateway' );
			update_post_meta((int)$directory_id, 'dir_pakage_transaction_meta', $cs_pack_tra_meta);
			update_post_meta((int)$directory_id, 'dir_payment_date', $payment_date);
			
			/*----------------------------------------------------	
			 * Update Post Status
			/*----------------------------------------------------*/
			$postStatus['ID'] 			= $directory_id;
			$postStatus['post_status']  = $cs_directory_status;
			wp_update_post( $postStatus );
			
			/*----------------------------------------------------	
			 * Update Featured Status
			/*----------------------------------------------------*/
			update_post_meta( $directory_id,'cs_directory_pkg_names', $package_id );
			
			if ( $package_id =='0000000000' ){
				$package_meta = get_post_meta($directory_id, "_pakage_meta", true);
			} else {
				$cs_packages_options 	= get_option('cs_packages_options');
				$package_meta 			= $cs_packages_options[$package_id];
			}

			if(isset($package_meta['package_duration']) && $package_meta['package_duration'] == 'unlimited' ){
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $cs_current_date);
			} else if(isset($package_meta['package_duration'])){
				$package_duration = $package_meta['package_duration'];
				$date 		 	  = strtotime("+".$package_duration." days", strtotime($payment_date));
				$expire_date 	  = date("Y-m-d H:i:s", $date);
				update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $expire_date);
				
				if ( isset( $featured ) && $featured == 'yes' ) {
					$package_meta['package_price'] = $package_meta['package_price'] + $package_featured_ads;
				}
				
				$package_meta['transection_status']	= 'completed';
				$package_meta['transection_id']		= $_POST['x_trans_id'];
				$package_meta['payment_gateway']	= 'cs_paypal_gateway';
				update_post_meta( $directory_id, '_pakage_meta', $package_meta );
			}
				
			/*----------------------------------------------------	
			 * Update Package Add Till Date
			/*----------------------------------------------------*/
 			if ( isset( $featured ) && $featured == 'yes' ) {
				$featured_days	= isset( $cs_theme_options['directory_featured_ad_days'] ) ? $cs_theme_options['directory_featured_ad_days'] : 0;
				if($featured_days < 1 || $featured_days == '')
					$featured_days = 0;
				
				$featured_date		 = strtotime("+".$featured_days." days", strtotime($payment_date));
				$featured_date 		 = date("Y-m-d H:i:s", $featured_date);
				update_post_meta($directory_id, 'dir_featured_till', $featured_date);
		 	 }			
		}
	}	


	}else{
		// -2 == Order Pending
	}
}


function cs_get_user_data( $user_id='' , $key=''){
	if( $user_id  != '' ) {
		if( $key  != ''  ) {
			return get_the_author_meta($key,$user_id);
		}
	}
	return;
}