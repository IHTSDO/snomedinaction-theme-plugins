<?php 
if ( !class_exists('MemberTemplates') ) {
	
	class MemberTemplates
	{
		
		function __construct()
		{
			// Constructor Code here..
		}
		//======================================================================
		// Member List View
		//======================================================================
		
		public function cs_list_view( $wp_user_query, $cs_dummy_image, $var_pb_members_description  ) {
		
		?>
 			<ul id="cs-filterable" class="cs-filterable agent-listing col-md-12">
				<?php
                $members			= 0;
                $members_counter	= 0;
                $isNoUser			= true;
                $authors			= $wp_user_query->get_results();
                    
                if (!empty($authors)) {
                    if ( is_array($authors) ) {
                        $isNoUser	= false;  
                        foreach ($authors as $cs_user_data) {
                        $members_counter++;
                        $profile_img		= cs_get_user_avatar(0 ,$cs_user_data->ID);
                        $cs_display_image	= '';
                        $cs_display_image	= cs_get_user_avatar(0 ,$cs_user_data->ID);
                        ?>
                            <li>
                                <div class="agentinfo-detail">
                                    <div class="about-info">
                                        <figure>
                                            <?php 
                                            $user_profile_public = get_the_author_meta('user_profile_public',$cs_user_data->ID );
                                            if( isset( $user_profile_public ) && $user_profile_public == '1' ) {
                                            
                                            ?>
                                                <a href="<?php echo cs_user_profile_link('', 'detail', cs_allow_special_char($cs_user_data->ID)); ?>">
                                            <?php
                                            }
                                            else { 
                                                echo '<a href="'.get_author_posts_url(get_the_author_meta('ID',$cs_user_data->ID)).'">';
                                            }
                                            
                                            if($profile_img<>''){
                                                echo '<img src="'.esc_url($profile_img).'" alt="" />';	
                                            }else{
                                                echo '<img src="'.esc_url($cs_dummy_image).'" alt="" />';
                                            }
                                            ?>
                                            </a>
                                        </figure>
                                        <div class="agentdetail-info">
                                            <div class="left-info">
                                                <a href="javascript:void(0)" style="display:none"><?php echo get_the_author_meta( 'display_name', $cs_user_data->ID );?></a>
                                                <h2><a href="<?php echo cs_user_profile_link('', 'detail', cs_allow_special_char($cs_user_data->ID)); ?>"><?php echo get_the_author_meta('display_name',$cs_user_data->ID );?></a></h2>
                                                <ul>
                                                    <?php 
                                                    $cs_mobile = $cs_landline = $cs_email = $cs_skype = $cs_user_url = '';
                                                    $cs_address		= get_the_author_meta('address',$cs_user_data->ID ); 
                                                    $cs_mobile		= get_the_author_meta('mobile',$cs_user_data->ID ); 
                                                    $cs_landline	= get_the_author_meta('landline',$cs_user_data->ID );
                                                    $cs_fax			= get_the_author_meta('fax',$cs_user_data->ID );
                                                    $cs_email		= get_the_author_meta('email',$cs_user_data->ID );
                                                    $cs_skype		= get_the_author_meta('skype',$cs_user_data->ID );
                                                    $cs_user_url	= get_the_author_meta('user_url',$cs_user_data->ID );
                                                    if($cs_landline <> ''){
                                                        echo '<li><i class="icon-home"></i> '.__("Phone","directory").':'.esc_attr( $cs_landline ).'</li>';
														
														
														
                                                    }
                                                    if($cs_fax <> ''){
                                                        echo '<li> <i class="icon-fax"></i>'.__("Fax","directory").':'.esc_attr( $cs_fax ).'</li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <div class="right-info">
                                                <?php cs_dir_listing_count($cs_user_data->ID); ?>
                                                <div class="social-media">
                                                    <ul>
                                                        <?php 
                                                        $facebook = $twitter = $linkedin = $pinterest = $google_plus ='';
                                                        $facebook = get_the_author_meta('facebook',$cs_user_data->ID ); 
                                                        $twitter  = get_the_author_meta('twitter',$cs_user_data->ID );
                                                        $linkedin = get_the_author_meta('linkedin',$cs_user_data->ID );
                                                        $pinterest = get_the_author_meta('pinterest',$cs_user_data->ID );
                                                        $google_plus = get_the_author_meta('google_plus',$cs_user_data->ID );
                                                        $instagram = get_the_author_meta('instagram',$cs_user_data->ID );
                                                        $skype = get_the_author_meta('skype',$cs_user_data->ID );
                                                        if(isset($facebook) and $facebook <> ''){
                                                            echo '<li><a data-original-title="facebook" href="'.esc_url($facebook).'"><i class="icon-facebook7"></i></a></li>';
                                                        }
                                                        if(isset($twitter) and $twitter <> ''){
                                                            echo '<li><a data-original-title="twitter" href="'.esc_url($twitter).'"><i class="icon-twitter2"></i></a></li>';
                                                        }
                                                        if(isset($linkedin) and $linkedin <> ''){
                                                            echo '<li><a data-original-title="linkedin" href="'.esc_url($linkedin).'"><i class="icon-linkedin2"></i></a></li>';
                                                        }
                                                        if(isset($pinterest) and $pinterest <> ''){
                                                            echo '<li><a data-original-title="pinterest" href="'.esc_url($pinterest).'"><i class="icon-pinterest"></i></a></li>';
                                                        }
                                                        ?> 
                                                    </ul>                             
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                    <?php 
                                        if($var_pb_members_description =='on'){
                                            echo '<div class="about-detail">';
                                            $description = get_the_author_meta( 'description', $cs_user_data->ID );
                                            if($description<>''){ 
	                                            echo wp_trim_words($description,'300','...');
												echo '<p>'.wp_trim_words($description,'300','...').'</p>';
                                            }
                                            echo '</div>';
                                        } 
                                    ?>
                                </div>
                               
                            </li>
                        <?php
                        }
                    }
                }
                else {
                    if ( $members <= 0 && $isNoUser ){
                        echo '<div class="error_mess"><p>'.__('No User found.', 'directory').'</p></div>';
                        $isNoUser = false;
                    }
                }
                ?>
             </ul>
 		<?php
		}
		// End of Member List View
		
		//======================================================================
		// Member Grid View
		//======================================================================
		
		public function cs_grid_view( $wp_user_query, $cs_dummy_image,$var_contact_fields  ) {
	 
			$members			= 0; 
			$members_counter	= 0;
			$isNoUser			= true;
			 echo '<ul class="cs-filterable" id="cs-filterable">';  
			$authors = $wp_user_query->get_results();
			if (!empty($authors)) {
				if ( is_array($authors) ) {
					
					$isNoUser	= false;  
					foreach ($authors as $cs_user_data) {
					$members_counter++;
					$profile_img = cs_get_user_avatar(0 ,$cs_user_data->ID);
				
					?>
					<li class="col-md-3">
						<div class="cs-team team-grid">
							<figure>
								<?php 
								$user_profile_public = get_the_author_meta('user_profile_public',$cs_user_data->ID );
								if( isset( $user_profile_public ) && $user_profile_public == '1' ) {
								
								?>
									<a href="<?php echo cs_user_profile_link('', 'detail', cs_allow_special_char($cs_user_data->ID)); ?>">
								<?php
								}
								else { 
									echo '<a href="'.get_author_posts_url(get_the_author_meta('ID',$cs_user_data->ID)).'">';
								}
								
								if($profile_img <> ''){
									echo '<img src="'.esc_url($profile_img).'" alt="" />';	
								}else{
									echo '<img src="'.esc_url($cs_dummy_image).'" alt="" />';
								}
								?>
								</a>
							</figure>
							<?php 
							if($var_contact_fields == 'on'){
							?>
								<div class="text">
									<h2> 
										<?php 
										$user_profile_public = get_the_author_meta('user_profile_public',$cs_user_data->ID );
										if( isset( $user_profile_public ) && $user_profile_public == '1' ){
										?>
										<a href="<?php echo cs_user_profile_link('', 'detail', cs_allow_special_char($cs_user_data->ID)); ?>">
										<?php
										} 
										else {
											echo '<a href="'.get_author_posts_url(get_the_author_meta('ID',$cs_user_data->ID)).'">';
										}
											echo cs_allow_special_char($cs_user_data->display_name);
										?>
										</a>
									</h2>
									<?php cs_dir_listing_count($cs_user_data->ID); ?>
								</div>
							<?php
							}
							?>
						</div>
					</li>
					<?php
				
					}
				}
				echo '</ul>';
			}
			else {
				if ( $members <= 0 && $isNoUser ){
					echo '<div class="error_mess"><p>'.__('No User found.', 'directory').'</p></div>';
					$isNoUser = false;
				}
			}
		 
		}
		// End of Member Grid View
		
		//======================================================================
		// Member Simple View
		//======================================================================
		
		public function cs_simple_view( $wp_user_query, $cs_dummy_image, $var_contact_fields, $cs_agent_col_class ) {
 		?>
         <div class="cs-team team-simple col-md-12 <?php echo sanitize_html_class($cs_agent_col_class); ?>"> 
            <ul id="cs-filterable" class="cs-filterable agent-listing">
				<?php
                $members			= 0; 
                $members_counter	= 0;
                $isNoUser			= true;
                 $authors = $wp_user_query->get_results();
                if (!empty($authors)) {
					if ( is_array($authors) ) {
						$isNoUser	= false;  
						foreach ($authors as $cs_user_data) {
						$members_counter++;
						$profile_img = cs_get_user_avatar(0 ,$cs_user_data->ID);
						?>
 							<li>
								<a style="display:none" href=""><?php echo get_the_author_meta('display_name',$cs_user_data->ID );?> </a>
								<figure>
								<?php 
								$user_profile_public = get_the_author_meta('user_profile_public',$cs_user_data->ID );
								if( isset( $user_profile_public ) && $user_profile_public == '1' ) {
								
								?>
									<a href="<?php echo cs_user_profile_link('', 'detail', cs_allow_special_char($cs_user_data->ID)); ?>">
								<?php
								}
								else { 
									echo '<a href="'.get_author_posts_url(get_the_author_meta('ID',$cs_user_data->ID)).'">';
								}
								
								if($profile_img<>''){
									echo '<img src="'.esc_url($profile_img).'" alt="" />';	
								}else{
									echo '<img src="'.esc_url($cs_dummy_image).'" alt="" />';
								}
								?>
								</a>
 								</figure>
								<?php if($var_contact_fields =='on'){?>
									<div class="text"> 
										<?php
										$user_profile_public = get_the_author_meta('user_profile_public',$cs_user_data->ID );
										if( isset( $user_profile_public ) && $user_profile_public =='1' ){
										?>
											<a href="<?php echo cs_user_profile_link('', 'detail', cs_allow_special_char($cs_user_data->ID)); ?>">
										<?php
										}
										else {
											echo '<a href="'.get_author_posts_url(get_the_author_meta('ID',$cs_user_data->ID)).'">';
										}?>         	 
										</a>
										<?php cs_dir_listing_count($cs_user_data->ID); ?>
									</div>
								<?php } ?>
							</li>
 						<?php
						}
					}
                }
                else {
                  
					if ( $members <= 0 && $isNoUser ){
						echo '<div class="error_mess"><p>'.__('No User found.', 'directory').'</p></div>';
						$isNoUser	= false;
					}
                }
                ?>
            </ul>
        </div>
 		
		<?php
                            
		}
		// End of Member Simple View
	}
	// End of Member Class
}