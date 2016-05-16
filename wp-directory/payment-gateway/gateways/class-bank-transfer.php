<?php
/**
 *  File Type: Pre Bank Transfer
 *
 */
 
if( ! class_exists( 'CS_PRE_BANK_TRANSFER' ) ) {
	class CS_PRE_BANK_TRANSFER extends CS_PAYMENTS{
		
		public function __construct()
		{
			global $cs_gateway_options;
			$cs_gateway_options	= get_option('cs_theme_options');
		}
		
		public function settings(){
			global $post;
			
			$on_off_option =  array("show" => "on","hide"=>"off"); 
			
			$cs_settings[] = array("name" => "Bank Transfer Settings",
											"id" => "tab-heading-options",
											"std" => "Bank Transfer Settings",
											"type" => "section",
											"options" => ""
										);
			$cs_settings[] = array( "name" 		=> "Custom Logo",
									"desc" 		=> "",
									"hint_text" => "",
									"id" 		=> "cs_pre_bank_transfer_logo",
									"std" 		=>  wp_directory::plugin_url().'payment-gateway/images/bank.png',
									"display"	=>"none",
									"type" 		=> "upload logo"
								);
								
			$cs_settings[] = array( "name" 		=> "Default Status",
                            "desc" 				=> "",
                            "hint_text" 		=> "Show/Hide Gateway On Front End.",
                            "id" 				=> "cs_pre_bank_transfer_status",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        );
			$cs_settings[] = array( "name" 		=> "Bank Information",
                            "desc" 				=> "",
                            "hint_text" 		=> "Enter the bank name to which you want to transfer payment",
                            "id" 				=> "cs_bank_information",
                            "std" 				=> "",
                            "type" 				=> "text"
                        );
			$cs_settings[] = array( "name" 		=> "Account Number",
                            "desc" 				=> "",
                            "hint_text" 		=> "Enter your bank Account ID.",
                            "id" 				=> "cs_bank_account_id",
                            "std" 				=> "",
                            "type" 				=> "text"
                        ); 
			$cs_settings[] = array( "name" 		=> "Other Information",
                            "desc" 				=> "",
                            "hint_text" 		=> "Enter your bank Other Information.",
                            "id" 				=> "cs_other_information",
                            "std" 				=> "",
                            "type" 				=> "textarea"
                        ); 
						
			return $cs_settings;
		}
		
		public function cs_proress_request( $params = '' ){
			global $post, $cs_theme_options, $cs_gateway_options;
			
			extract( $params );
			
			$transaction_id	= $this->cs_get_string(10);
			$pck_price	= '0';
			## Add Transection 
			$directory_id 		= $cs_post_id;
			$cs_current_date	= date('Y-m-d H:i:s');
			$transaction_array  = array();
			
			$user_id 	 = get_current_user_id();
			$package_id  = $cs_package;
			$cs_packages_options 		= get_option('cs_packages_options');
			
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

				$featured	= 'no';
				
				if(isset($cs_featured)){
					$featured = $cs_featured;
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
				
				if(isset($transaction_id) && $transaction_id <> ''){
					$tnx_type = 'transaction';	
				} else {
					$tnx_type = 'subscription';	
				}
				
				/*----------------------------------------------------	
				 * All Transactions Data Saved
				/*----------------------------------------------------*/
				$cs_directory_status    = isset($cs_theme_options['cs_directory_visibility']) ? $cs_theme_options['cs_directory_visibility'] : 'pending';
				$package_featured_ads	= isset( $cs_theme_options['directory_featured_ad_price'] ) ? $cs_theme_options['directory_featured_ad_price'] : 0; 
				
				//$cs_tra_meta[$directory_id][$index_count][$tnx_type] = $_POST;
				
				update_option('cs_directory_transaction_meta', $cs_tra_meta);
				
				$directory_post 				= array();
				$directory_post['ID'] 			= (int)$directory_id;
				$directory_post['post_status']  = 'pending';
				wp_update_post( $directory_post );
				
				if( isset( $cs_package ) && $cs_package !='0000000000' ){
					$package_title	= $cs_packages_options[$cs_package]['package_title'];
				}else{
					$package_title	 = 'Unlimited - Free';
				}
				
				
				if(isset($transaction_id) && $transaction_id <> ''){
					$transection_array = array();
					
					$currency				= isset( $cs_gateway_options['cs_currency_type'] ) && $cs_gateway_options['cs_currency_type'] !='' ? $cs_gateway_options['cs_currency_type'] : 'USD';
					$transection_array['user_id'] 		= esc_attr($user_id);
					$transection_array['package_id'] 	= esc_attr($package_id);
					$transection_array['item_name'] 	= '';
					$transection_array['txn_id'] 		= esc_attr($transaction_id);
					$transection_array['payment_date']  = date('Y-m-d H:i:s');
					$transection_array['payer_email'] 	= $this->cs_get_user_data($user_id,'user_email');
					$transection_array['payment_gross'] = $cs_price;
					$transection_array['mc_currency'] 	= $currency;
					$transection_array['address_name'] 	= $this->cs_get_user_data($user_id,'first_name').' '.$this->cs_get_user_data($user_id,'last_name');
					$transection_array['ipn_track_id'] 	= '';
					$transection_array['transaction_status']		= 'pending';
					$transection_array['payment_method']			= 'cs_pre_bank_transfer';
					$transection_array['full_address']	= $this->cs_get_user_data($user_id,'address');
					$transection_array['first_name'] 	= $this->cs_get_user_data($user_id,'first_name');
					$transection_array['last_name'] 	= $this->cs_get_user_data($user_id,'last_name');
					$transection_array['purchase_on'] 	= date('Y-m-d H:i:s');
					$transection_array['post_id'] 		= (int)$directory_id;
					
					$cs_pack_tra_meta[$trans_counter] 	= $transection_array;
					
					$payment_date = date_i18n( 'Y-m-d H:i:s', strtotime(esc_attr(date('Y-m-d H:i:s'))));
					
					update_post_meta((int)$directory_id, 'dir_pakage_transaction_meta', $cs_pack_tra_meta);
					update_post_meta((int)$directory_id, 'dir_payment_date', $payment_date);
					update_post_meta((int)$directory_id, 'current_gateway', 'cs_pre_bank_transfer' );
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
						$pck_price	= '0';
						$package_meta = get_post_meta($directory_id, "_pakage_meta", true);
					} else {
						$cs_packages_options 	= get_option('cs_packages_options');
						$package_meta 			= $cs_packages_options[$package_id];
					}
		
					if(isset($package_meta['package_duration']) && $package_meta['package_duration'] == 'unlimited' ){
						update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $cs_current_date);

						if( isset( $cs_directory_type ) && absint($cs_directory_type) ) {
							$cs_directory_type_price = get_post_meta($cs_directory_type, "cs_directory_post_prcie", true);
							$cs_directory_type_price = $cs_directory_type_price > 0 ? $cs_directory_type_price : '0';
							$package_meta['package_price'] = $package_meta['package_price'] + $cs_directory_type_price;
						}
						
					} else if(isset($package_meta['package_duration'])){
						$package_duration = $package_meta['package_duration'];
						$date 		 	  = strtotime("+".$package_duration." days", strtotime($payment_date));
						$expire_date 	  = date("Y-m-d H:i:s", $date);
						update_post_meta((int)$directory_id, 'dir_pkg_expire_date', $expire_date);
						
						$pck_price	= $package_meta['package_price'];
						if ( isset( $featured ) && $featured == 'yes' ) {
							$package_meta['package_price'] = $package_meta['package_price'] + $package_featured_ads;
						}
						
						if( isset( $cs_directory_type ) && absint($cs_directory_type) ) {
							$cs_directory_type_price = get_post_meta($cs_directory_type, "cs_directory_post_prcie", true);
							$cs_directory_type_price = $cs_directory_type_price > 0 ? $cs_directory_type_price : '0';
							$package_meta['package_price'] = $package_meta['package_price'] + $cs_directory_type_price;
						}
						
						$package_meta['transection_status']	= 'completed';
						$package_meta['transection_id']		= $transaction_id;
						$package_meta['payment_gateway']	= 'cs_pre_bank_transfer';
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
			
			$cs_directory_type_price = 'Free';
			if( isset( $cs_directory_type ) && absint($cs_directory_type) ) {
				$cs_directory_type_price = get_post_meta($cs_directory_type, "cs_directory_post_prcie", true);
				$cs_directory_type_price = $cs_directory_type_price > 0 ? $cs_directory_type_price : 'Free';
			}
			
			$currency_type				= isset( $cs_gateway_options['currency_sign'] ) && $cs_gateway_options['currency_sign'] !='' ? $cs_gateway_options['currency_sign'] : 'USD';
			
			
			if ( isset( $cs_directory_type_price ) && $cs_directory_type_price > 0  ) {
				$directory_price	= $currency_type.$cs_directory_type_price;
			} else{
				$directory_price	= 'Free';
			}
				
			$cs_bank_transfer	= '<div class="cs-bank-transfer">';
				$cs_bank_transfer	.= '<h2>Order detail</h2>';
			
				$cs_bank_transfer	.= '<ul class="list-group">';
				  
				  $cs_bank_transfer	.= '<li class="list-group-item">';
					$cs_bank_transfer	.= '<span class="badge">#'.$transaction_id.'</span>';
					$cs_bank_transfer	.= 'Order ID';
				  $cs_bank_transfer	.= '</li>';
				  
				  $cs_bank_transfer	.= '<li class="list-group-item">';
					$cs_bank_transfer	.= '<span class="badge">'.$package_meta['package_title'].'</span>';
					$cs_bank_transfer	.= 'Package Title';
				  $cs_bank_transfer	.= '</li>';
				  
				  if ( isset( $featured ) && $featured == 'yes' ) {
					  $package_featured_ads	= $package_featured_ads && $package_featured_ads !='' ? $package_featured_ads : '0';
					  $cs_bank_transfer	.= '<li class="list-group-item">';
						$cs_bank_transfer	.= '<span class="badge">'.$currency_type.$package_featured_ads.'</span>';
						$cs_bank_transfer	.= 'Featured Price';
					  $cs_bank_transfer	.= '</li>';
				  }
				  
				  
				  $cs_bank_transfer	.= '<li class="list-group-item">';
					$cs_bank_transfer	.= '<span class="badge">'.$directory_price.'</span>';
					$cs_bank_transfer	.= 'Directory Price';
				  $cs_bank_transfer	.= '</li>';
				  
				  $cs_bank_transfer	.= '<li class="list-group-item">';
					$cs_bank_transfer	.= '<span class="badge">'.$currency_type.$pck_price.'</span>';
					$cs_bank_transfer	.= 'Package Price';
				  $cs_bank_transfer	.= '</li>';
				  
				  $cs_bank_transfer	.= '<li class="list-group-item">';
					$cs_bank_transfer	.= '<span class="badge">'.$currency_type.$package_meta['package_price'].'</span>';
					$cs_bank_transfer	.= 'Total Price';
				  $cs_bank_transfer	.= '</li>';
				  
				  
				   $cs_bank_transfer	.= '<li class="list-group-item">';
					$cs_bank_transfer	.= '<span class="badge">ID : #'.$cs_post_id.'</span>';
					$cs_bank_transfer	.= '<span class="badge">Title : <a href="'.get_the_permalink($cs_post_id).'">'.get_the_title($cs_post_id).'</a></span>';
					$cs_bank_transfer	.= 'Ad Detail';
				  $cs_bank_transfer	.= '</li>';
				  
				 $cs_bank_transfer	.= '</ul>';
				 
				$cs_bank_transfer	.= '<ul class="list-group">';
				  $cs_bank_transfer	.= '<h2>Bank detail</h2>';
				  $cs_bank_transfer	.= '<p>Please transfer amount To this account, After payment Received we will process your Order.</h1>';

				  if( isset( $cs_gateway_options['cs_bank_information'] ) && $cs_theme_options['cs_bank_information'] !='' ) {
					  $cs_bank_transfer	.= '<li class="list-group-item">';
						$cs_bank_transfer	.= '<span class="badge">'.$cs_theme_options['cs_bank_information'].'</span>';
						$cs_bank_transfer	.= 'Bank Information';
					  $cs_bank_transfer	.= '</li>';
				  }
				  
				  if( isset( $cs_gateway_options['cs_bank_account_id'] ) && $cs_theme_options['cs_bank_account_id'] !='' ) {
					  $cs_bank_transfer	.= '<li class="list-group-item">';
						$cs_bank_transfer	.= '<span class="badge">'.$cs_theme_options['cs_bank_account_id'].'</span>';
						$cs_bank_transfer	.= 'Account No';
					  $cs_bank_transfer	.= '</li>';
				  }
				  
				  if( isset( $cs_gateway_options['cs_other_information'] ) && $cs_theme_options['cs_other_information'] !='' ) {
					  $cs_bank_transfer	.= '<li class="list-group-item">';
						$cs_bank_transfer	.= '<span class="badge">'.$cs_gateway_options['cs_other_information'].'</span>';
						$cs_bank_transfer	.= 'Other Information';
					  $cs_bank_transfer	.= '</li>';
				  }
				  
				$cs_bank_transfer	.= '</ul>';
			$cs_bank_transfer	.= '</div>';
			
			echo force_balance_tags($cs_bank_transfer);
			die;
		}
		
		public function cs_get_user_data( $user_id='' , $key=''){
			if( $user_id  != '' ) {
				if( $key  != ''  ) {
					return get_the_author_meta($key,$user_id);
				}
			}
			return;
		}
	}
}