<?php
if( ! class_exists( 'cs_email_helper' ) ) {
	class cs_email_helper{
		
		public function __construct()
		{
			// Do Something
		}
		
		/*----------------------------------------------------------------------
		 * @ Email Header
		 *---------------------------------------------------------------------*/
		 public function cs_get_email_header( $cs_directory_title = '' ) {
			global $current_user;
			
			return true;	
		 }
	
		/*----------------------------------------------------------------------
		 * @ Email Footer
		 *---------------------------------------------------------------------*/
		 public function cs_get_email_footer( $params ='' ) {
			global $current_user;
		 }
		
		/*----------------------------------------------------------------------
		 * @ Email Add New Directory
		 *---------------------------------------------------------------------*/
		 public function cs_add_directory_notification( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$subject  = "{$cs_directory_title} creates successfully on (" . get_bloginfo() . ")";	
			
			$headers  = "From: " . esc_attr( $name ) . "\r\n";
			$headers .= "Reply-To: " . sanitize_email( $email ) . "\r\n";
			$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
			$headers .= "MIME-Version: 1.0" . "\r\n";
			
			$attachments 	= '';
			
			$body			 =  cs_get_email_header();
			$body 			.= 'Message here..';
			$body 			.= cs_get_email_footer();
			
			wp_mail( sanitize_email($current_user->email), $subjecteEmail, $body, $headers, $attachments );
		 }
		 
		 /*----------------------------------------------------------------------
		  * @ Email Purchase Package
		  *---------------------------------------------------------------------*/
		 public function cs_purchase_package_notification( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$subject  = "{$cs_package_name} purchased on (" . get_bloginfo() . ")";	
			
			$headers  = "From: " . esc_attr( $name ) . "\r\n";
			$headers .= "Reply-To: " . sanitize_email( $email ) . "\r\n";
			$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
			$headers .= "MIME-Version: 1.0" . "\r\n";
			
			$attachments	= '';
			
			$body			=  cs_get_email_header();
			$body			.= 'Message here..';
			$body 			.= cs_get_email_footer();
			
			wp_mail( sanitize_email($current_user->email), $subjecteEmail, $body, $headers, $attachments );
		 }
		 
		 /*----------------------------------------------------------------------
		  * @ Email User Registration
		  *---------------------------------------------------------------------*/
		 public function cs_user_registration_notification( $params = '' ) {
			global $current_user;
			extract( $params );
			
			$subject  = "Registration on (" . get_bloginfo() . ")";	
			
			$headers  = "From: " . esc_attr( $name ) . "\r\n";
			$headers .= "Reply-To: " . sanitize_email( $email ) . "\r\n";
			$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
			$headers .= "MIME-Version: 1.0" . "\r\n";
			
			$attachments 	= '';
			
			$body			 =  cs_get_email_header();
			$body 			.= 'Message here..';
			$body 			.= cs_get_email_footer();
			
			wp_mail( sanitize_email($current_user->email), $subjecteEmail, $body, $headers, $attachments );
		 }	 
	}
}