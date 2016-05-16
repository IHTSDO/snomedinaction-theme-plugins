<?php 
/**
 *  File Type: Import Users
 *
 
 */     

if ( !class_exists('Import_User') ) {    
    class Import_User
    {        
        function __construct()
        {
            // Constructor Code here..
   		}
		
		public function cs_import_user(){
		
		global $wpdb, $wpdb_data_table;
		
			
		if ( ! defined( 'IS_IU_CSV_DELIMITER' ) )
			define ( 'IS_IU_CSV_DELIMITER', ',' );
	
			// User data fields list used to differentiate with user meta
			$userdata_fields       = array(
				'ID', 'user_login', 'user_pass',
				'user_email', 'user_url', 'user_nicename',
				'display_name', 'user_registered', 'first_name',
				'last_name', 'nickname', 'description',
				'rich_editing', 'comment_shortcuts', 'admin_color',
				'use_ssl', 'show_admin_bar_front', 'show_admin_bar_admin',
				'role'
			);
			
			$wp_user_table		= $wpdb->prefix.'users';
			$wp_usermeta_table	= $wpdb->prefix.'usermeta';
			
			//die;
			//require_once dirname( __FILE__ ).'../import-users/class-readcsv.php' );
			//require_once dirname( __FILE__ ) . '/include/theme-components/import-users/class-readcsv.php';
	
			// Loop through the file lines
			$file_handle = fopen( get_template_directory_uri().'/include/theme-components/import-users/import.csv', 'r' );
			$csv_reader = new ReadCSV( $file_handle, IS_IU_CSV_DELIMITER, "\xEF\xBB\xBF" ); // Skip any UTF-8 byte order mark.
	
			$first = true;
			$rkey = 0;
			while ( ( $line = $csv_reader->get_row() ) !== NULL ) {
	
				// If the first line is empty, abort
				// If another line is empty, just skip it
				if ( empty( $line ) ) {
					if ( $first )
						break;
					else
						continue;
				}
	
				// If we are on the first line, the columns are the headers
				if ( $first ) {
					$headers = $line;
					$first = false;
					continue;
				}
	
				// Separate user data from meta
				$userdata = $usermeta = array();
				foreach ( $line as $ckey => $column ) {
					$column_name = $headers[$ckey];
					$column = trim( $column );
	
					if ( in_array( $column_name, $userdata_fields ) ) {
						$userdata[$column_name] = $column;
					} else {
						$usermeta[$column_name] = $column;
					}
				}

				// A plugin may need to filter the data and meta
				//$userdata = apply_filters( 'is_iu_import_userdata', $userdata, $usermeta );
				//$usermeta = apply_filters( 'is_iu_import_usermeta', $usermeta, $userdata );
	
				// If no user data, bailout!
				if ( empty( $userdata ) )
					continue;
	
				// Something to be done before importing one user?
				//do_action( 'is_iu_pre_user_import', $userdata, $usermeta );
	
				$user = $user_id = false;
	
				if ( isset( $userdata['ID'] ) ) {
					$user = get_user_by( 'ID', $userdata['ID'] );
				}
	
				if ( ! $user ) {
					if ( isset( $userdata['user_login'] ) )
						$user = get_user_by( 'login', $userdata['user_login'] );
	
					if ( ! $user && isset( $userdata['user_email'] ) )
						$user = get_user_by( 'email', $userdata['user_email'] );
				}
				
				$update = false;
				if ( $user ) {
					$userdata['ID'] = $user->ID;
					$update = true;
				}
	
				// If creating a new user and no password was set, let auto-generate one!
				if ( ! $update && $update == false  && empty( $userdata['user_pass'] ) ) {
					$userdata['user_pass'] = wp_generate_password( 12, false );
				}
	
				/*if ( $update )
					$user_id = wp_update_user( $userdata );
				else
					$user_id = wp_insert_user( $userdata );*/
				
				if (isset($update)&& $update == true) {
					$userdata['ID']	= $usermeta['user_id'];
					//$sql = "UPDATE $wp_user_table SET VALUE=".$value_to_store." WHERE USER_ID=$wp_userid AND FIELD_ID=".$ef_details["ID"];
					$user_id = wp_update_user( $userdata );
				} else {
					$display_name	= '';
					$display_name	= $userdata['first_name'].' '.$userdata['last_name'];
					if( $userdata['display_name'] && $userdata['display_name'] !='' ){
						$display_name	= $userdata['display_name'];
					}
					
					$sql = "INSERT INTO $wp_user_table (ID, user_login, user_pass, user_email, user_registered,user_status, display_name, user_nicename, user_url) VALUES ('".$usermeta['user_id']."','".$userdata['user_login']."','".md5($userdata['user_pass'])."','".$userdata['user_email']."','".date('Y-m-d H:i:s')."',0,'".$display_name."','".$userdata['user_nicename']."','".$userdata['user_url']."')";
					$wpdb->query($sql);
					$new_user = new WP_User( $usermeta['user_id'] );
					$new_user->set_role( $userdata['role'] );	
					$user_id =	$usermeta['user_id'];
					
					// Include again meta fields
					$usermeta['description']	= $userdata['description'];
					$usermeta['first_name']		= $userdata['first_name'];
					$usermeta['last_name']		= $userdata['last_name'];
					$usermeta['nickname']		= $userdata['user_nicename'];
					
				}
				
				// Is there an error o_O?
				if ( is_wp_error( $user_id ) ) {
					$errors[$rkey] = $user_id;
				} else {
					// If no error, let's update the user meta too!
					
					$timings	= array( 'openhours_Sun_text',
										 'openhours_Sun_start',
										 'openhours_Sun_end',
										 'openhours_Mon_text',
										 'openhours_Mon_start',
										 'openhours_Mon_end',
										 'openhours_Tue_text',
										 'openhours_Tue_start',
										 'openhours_Tue_end',
										 'openhours_Wed_text',
										 'openhours_Wed_start',
										 'openhours_Wed_end',
										 'openhours_Thu_text',
										 'openhours_Thu_start',
										 'openhours_Thu_end',
										 'openhours_Fri_text',
										 'openhours_Fri_start',
										 'openhours_Fri_end',
										 'openhours_Sat_text',
										 'openhours_Sat_start',
										 'openhours_Sat_end',
									   );
					$opening_hours	= array();
					if ( $usermeta ) {
						foreach ( $usermeta as $metakey => $metavalue ) {
							
							$metavalue = maybe_unserialize( $metavalue );
							
							if( in_array($metakey,$timings) ) {
								$opening_hours[$metakey]	=  $metavalue; 
							} else {
								update_user_meta( $user_id, $metakey, $metavalue );
							}
							
						}
						
						update_user_meta( $user_id, 'opening_hours', $opening_hours );
					}
	
					// If we created a new user, maybe set password nag and send new user notification?
					if ( ! $update ) {
						/*if ( $password_nag )
							update_user_option( $user_id, 'default_password_nag', true, true );
	
						if ( $new_user_notification )
							wp_new_user_notification( $user_id, $userdata['user_pass'] );*/
					}
				}
	
				$rkey++;
			}
			fclose( $file_handle );
		}
	}
}