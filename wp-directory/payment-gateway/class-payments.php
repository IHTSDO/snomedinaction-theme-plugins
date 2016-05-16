<?php
/**
 *  File Type: Payemnts Base Class
 *
 */
 
if( ! class_exists( 'CS_PAYMENTS' ) ) {
	class CS_PAYMENTS{
		
		public $gateways;
		
		public function __construct()
		{
			global $gateways;
			//$gateways['class name']							= 'Gateway name'
			$gateways	= array();
			$gateways['CS_PAYPAL_GATEWAY']						= 'Paypal';
			$gateways['CS_AUTHORIZEDOTNET_GATEWAY']				= 'Authorize.net';
			$gateways['CS_PRE_BANK_TRANSFER']					= 'Bank Transfer';
			//$gateways['CS_2CHECKOUT_GATEWAY']					= '2Checkout';
			$gateways['CS_SKRILL_GATEWAY']						= 'Skrill-MoneyBooker';
		}
		
		public function cs_general_settings(){
			global $cs_settings;
			
			$cs_currencuies	= cs_get_currency();
			foreach($cs_currencuies as $key => $value ){
				$currencies[$key] = $value['name'].'-'.$value['code'];
			}
			
			$cs_settings[] = array( "name" 			=> "Select Currency",
									"desc" 			=> "",
									"hint_text" 	=> "Select Currency",
									"id" 			=> "cs_currency_type",
									"std" 			=> "USD",
									"type" 			=> "select_values",
									"options" 		=> $currencies
									);
									
			$cs_settings[] = array( "name" 			=> "Currency Sign",
									"desc" 			=> "",
									"hint_text" 	=> "Use Currency Sign eg: &pound;,&yen;",
									"id" 			=> "currency_sign",
									"std" 			=> "$",
									"type" 			=> "text");
			return $cs_settings;
		}
		
		public function cs_get_string($length = 3) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}
		
	}
}
