<?php 
 
	$cs_directory_per_page = get_option('posts_per_page');
	$uid = '';
	if(isset($_GET['uid']) && $_GET['uid'] <> ''){
		$uid = $_GET['uid']; 
	}
	$userdata	= get_userdata($uid);
	$error		= '';
	$flag		= 'false';
	
	if (empty($_GET['page_id_all'])) $_GET['page_id_all'] = 1;
	
	$user_profile_public	= get_the_author_meta('user_profile_public',$uid );
	$cs_user_data			= get_userdata($uid);
	$cs_directory_options	= get_option('cs_theme_options');
	$currency_sign	= isset( $cs_directory_options['currency_sign'] ) ? $cs_directory_options['currency_sign'] : '$';
	$cs_dummy_image			= wp_directory::plugin_url().'/assets/images/dummy.jpg';
	
	if( $uid <> '' and  $cs_user_data->ID == $uid ) {
	
		if($user_profile_public == '1'){
		?>
		<!-- PageSection -->
		<section class="page-section">
			<!-- Container -->
			<div class="container">
				<!-- Row -->
				<div class="row">
				
					<aside class="page-sidebar agent-sidebar">
						<?php
						cs_user_conatct_form( $cs_user_data->ID );
						
						$opening	= get_user_meta($uid,'opening_hours',false);
						$is_opening	= false;
						global $cs_xmlObject;
						$weekdays	= array( "Sun" => "Sunday", "Mon" => "Monday", "Tue" => "Tuesday", "Wed" => "Wednesday", "Thu" => "Thursday", "Fri" => "Friday", "Sat" => "Saturday" );
						$output		= '';
						
						foreach( $weekdays as $key=>$value ) {
						
							$weekday_text   = 'openhours_'.$key.'_text';
							$weekday_start  = 'openhours_'.$key.'_start';
							$weekday_end 	= 'openhours_'.$key.'_end';
							
							if(isset($opening[0][$weekday_text]) && $opening[0][$weekday_text] <> '' && isset($opening[0][$weekday_start]) && $opening[0][$weekday_start] <> '' && isset($opening[0][$weekday_end]) && $opening[0][$weekday_end] <> ''){
								$is_opening	= true;
								$output .= '<li>';
								$output .= '<span class="day"><i class="icon-clock6"></i> '.$opening[0][$weekday_text].'</span>';
								$output .= '<div class="timehoure"><span class="time-start">'.$opening[0][$weekday_start].'</span>';
								$output .= '<span class="time-start">~</span>';
								$output .= '<span class="time-end">'.$opening[0][$weekday_end].'</span></div>';
								$output .= '</li>';
							}
						}          
						if( $is_opening ) {
						?>
						<div class="opening-hours">
							<h4><?php _e('Contact Timings','directory'); ?></h4>
							<div class="cs-opening">
								<ul>
									<?php echo balanceTags($output, true);?>
								</ul>
							</div>
						</div>
                        <?php }?>
					</aside>
					
					<div class="page-content">
						<div class="entry-content">
							<div class="col-md-12">
								<div class="agentinfo-detail">
									<div class="about-info">
										<figure>
											<?php 
											$cs_display_image = '';
											$cs_display_image = cs_get_user_avatar(1 ,$uid);
											if( $cs_display_image <> ''){?>
											<img height="100" width="136" src="<?php echo esc_url( $cs_display_image );?>" alt=""  />
											<?php }else{ ?>
											<img src="<?php echo cs_allow_special_char($cs_dummy_image); ?>" alt="" />
											<?php } ?>
										</figure>
										<div class="agentdetail-info">
											<div class="left-info">
												<h2><?php _e('About','directory');echo get_the_author_meta('display_name',$uid );?></h2>
                                                <span>
                                                    <?php 
                                                    $userType =  get_the_author_meta('roles',$uid);
                                                    if(isset($userType[0])) echo esc_attr($userType[0]);
                                                    ?>
                                                </span>
                                                               
												<ul>
													<?php 
                                                    $cs_mobile		= $cs_landline = $cs_email = $cs_skype = $cs_user_url = $cs_address = $cs_email = $cs_url ='';
                                                    $cs_address		= get_the_author_meta('address',$uid ); 
                                                    $cs_mobile		= get_the_author_meta('mobile',$uid ); 
                                                    $cs_landline	= get_the_author_meta('landline',$uid );
                                                    $cs_fax			= get_the_author_meta('fax',$uid );
                                                    $cs_email		= get_the_author_meta('email',$uid );
                                                    $cs_skype		= get_the_author_meta('skype',$uid );
                                                    $cs_user_url	= get_the_author_meta('user_url',$uid );
													$cs_email	= get_the_author_meta('email',$uid );
													$cs_url	= get_the_author_meta('url',$uid );
                                                    if($cs_landline <> ''){
														echo '<li><i class="icon-phone-square"></i>'.__("Phone","directory").':'.esc_attr( $cs_landline ).'</li>';
                                                    }
													if($cs_address <> ''){
														echo '<li> <i class="icon-map-marker"></i>'.__("Address","directory").':'.esc_attr( $cs_address ).'</li>';
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
														$facebook = get_the_author_meta('facebook',$uid ); 
														$twitter  = get_the_author_meta('twitter',$uid );
														$linkedin = get_the_author_meta('linkedin',$uid );
														$pinterest = get_the_author_meta('pinterest',$uid );
														$google_plus = get_the_author_meta('google_plus',$uid );
														$instagram = get_the_author_meta('instagram',$uid );
														$skype = get_the_author_meta('skype',$uid );
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
									<div class="about-detail">
										<?php 
										$description = get_the_author_meta('description',$cs_user_data->ID);
										if($description<>''){ 
											echo '<p>'.$description.'</p>';
										}
										echo '<ul class="cs-user-info">';
											if($cs_fax <> ''){
												echo '<li> <i class="icon-fax"></i> '.__("Fax","directory").':'.esc_attr( $cs_fax ).'</li>';
                                             }
											if($cs_email <> ''){
												echo '<li><i class="icon-envelope4"></i>'.__("Address","directory").':'.esc_attr( $cs_email ).'</li>';
                                             }
											if($cs_url <> ''){
												echo '<li><i class="icon-link4"></i><a href="'.esc_url( $cs_url ).'" target="_blank">'.__("Address","directory").':'.esc_url( $cs_url ).'</a></li>';
                                             }

										echo '</ul>';
										

										?>
									</div>
								</div>
				
								<?php
								if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
								
								$count_post = '';
								$agent_per_page = 0;
								
								// USER ALL ADS WITH EXPIRY DATE GREATER TO DATE
								
								$cs_agent_ads__in = array();
								$args = array(
									'post_type'			=> 'directory',
									'posts_per_page'	=> "-1",
									'post_status'		=> array('publish'),
									'meta_key'			=> 'directory_organizer',
									'meta_query'		=> array(
									'relation'			=> 'AND',
										array('key' => 'directory_organizer','value' => $cs_user_data->ID,'compare' => '=',),
										array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>=', 'type' => 'NUMERIC'),
									),
								);
								$custom_query = new WP_Query($args);
								while ( $custom_query->have_posts() ): $custom_query->the_post();
								$cs_agent_ads__in[] = get_the_id();
								endwhile;
								wp_reset_query();
								
								// USER ALL ADS WITH UNLIMITED DATE
								$args = array(
									'post_type' => 'directory',
									'posts_per_page' => "-1",
									'post_status'	=> array('publish'),
									'meta_key'	=> 'directory_organizer',
									'meta_query' => array(
									'relation'  => 'AND',
										array('key' => 'directory_organizer','value' => $cs_user_data->ID,'compare' => '=',),
										array('key'	=> 'dir_pkg_expire_date', 'value'  => 'unlimited', 'compare' => '='),
									),
								);
								$custom_query = new WP_Query($args);
								while ( $custom_query->have_posts() ): $custom_query->the_post();
								$cs_agent_ads__in[] = get_the_id();
								endwhile;
								wp_reset_query();
								
								$args = array(
								  'post_type'		=> 'directory',
								  'post__in'		=> $cs_agent_ads__in,
								);
								
								$custom_query = new WP_Query($args);
								$count_post = $custom_query->post_count;
								
								$agent_per_page = get_option('posts_per_page');
								
								// USER ALL ADS WITH EXPIRY DATE GREATER TO DATE
								$cs_agent_ads__in = array();
								$args = array(
									'post_type'			=> 'directory',
									'posts_per_page'	=> "$agent_per_page",
									'paged'				=> $_GET['page_id_all'],
									'post_status'		=> array('publish'),
									'meta_query'		=> array(
										'relation'		=> 'AND',
										array('key'	=> 'directory_organizer','value' => $cs_user_data->ID,'compare' => '=',),
										array('key'	=> 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>=', 'type' => 'NUMERIC'),
									),
								);
								$custom_query = new WP_Query($args);
								while ( $custom_query->have_posts() ): $custom_query->the_post();
								$cs_agent_ads__in[] = get_the_id();
								endwhile;
								wp_reset_query();
								
								// USER ALL ADS WITH UNLIMITED DATE
								$args = array(
								  'post_type'			=> 'directory',
								  'posts_per_page'		=> "$agent_per_page",
								  'paged'				=> $_GET['page_id_all'],
								  'post_status'			=> array('publish'),
								  'meta_query'			=> array(
										'relation'		=> 'AND',
										 array('key'	=> 'directory_organizer', 'value' => $cs_user_data->ID,'compare' => '=',),
										 array('key'	=> 'dir_pkg_expire_date', 'value'  => 'unlimited', 'compare' => '='),
								  ),
								);
								$custom_query = new WP_Query($args);
								while ( $custom_query->have_posts() ): $custom_query->the_post();
								$cs_agent_ads__in[] = get_the_id();
								endwhile;
								wp_reset_query();
								
								$args = array(
								  'post_type'		=> 'directory',
								  'post__in'		=> $cs_agent_ads__in,
								);
												  
								$custom_query = new WP_Query($args);
								if ( $custom_query->have_posts() <> "" ) {
								?>
									<div class="cs-related-post grid_two_listing">
										<div class="cs-section-title">
											<h2><?php _e('Recent Ads','directory');?></h2>
										</div>
										<div class="row">
											<?php	
											while ( $custom_query->have_posts() ): $custom_query->the_post();
											$width			= '370';
											$height			= '280';
											$title_limit	= 5;
											$cs_post_id		= $post->ID;
											$background		= '';
											$cs_directory	= get_post_meta($post->ID, "cs_directory_meta", true);
											$cs_directory_featured = get_post_meta($post->ID, "directory_featured", true);
											$directory_type_select = get_post_meta($post->ID, "directory_type_select", true);
											
											if ( $cs_directory <> "" ) {
												$cs_xmlObject = new SimpleXMLElement($cs_directory);
											}
											
											$image_url = get_post_meta( $post->ID, '_directory_image_gallery', true );
											$image_url = array_filter( explode( ',', $image_url ) );
											if ( isset( $image_url ) && ! empty( $image_url ) ) {
												$image_url 	= cs_attachment_image_src( $image_url[0] ,$width,$height); 
											} else {
												$image_url	= get_template_directory_uri().'/assets/images/no-image4x3.jpg';
											}
																  
											$directory_cat ='';
											if(isset($directory_type_select) && $directory_type_select <> ''){
												$post_id = absint($directory_type_select);
												$meta_options = cs_directory_custom_options_array();
												if(is_array($meta_options)) :
													foreach( $meta_options['params'] as $table_key=>$tablerows ) {
														$field_title = $tablerows['title'];
														foreach( $tablerows as $key=>$param ) :
															if($key == 'title')
																continue;
															if(is_array($param)) {
																$key_input = $key;
																if($param['type'] == 'checkbox'){
																	$meta_option_on = get_post_meta((int)$directory_type_select, $key, true);
																	if($meta_option_on == 'on'){
																		$$key = $meta_option_on;
																	}
																}
																if($param['type'] == 'text'){
																	$keyinputtitle = get_post_meta($directory_type_select, $key, true);
																	if(empty($keyinputtitle)) {
																		$keyinputtitle = $field_title;
																		$$key_input = $keyinputtitle;
																	}
																}
															}
														
														endforeach;
													}
											
												endif;
											}
											$randId	= cs_generate_random_string(5);
											?>
											<article class="col-md-4">
                                          <div class="directory-section">
                                            <div class="cs_thumbsection">
                                                <figure>
                                                    <?php if ( isset( $image_url ) && $image_url != '' ) { ?>
                                                    <a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                                                        <?php echo cs_show_featured_text($cs_post_id) ;?>
                                                        <img alt="" src="<?php echo esc_url( $image_url );?>">
                                                    </a>
                                                    <?php }?> 
                                                    <figcaption>
                                                        <div class="cs-text">
                                                            <?php cs_total_ad_rating($cs_post_id); ?>
                                                            <h2 itemscope itemtype="http://schema.org/Thing" itemprop="name">
                                                            <a href="<?php echo esc_url(get_permalink($cs_post_id ));?>">
	                                                            <?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
	                                                        </a>
                                                            </h2>
                                                                <div class="dr_pricesection" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                                                    <?php echo cs_get_directory_price( $cs_post_id ); ?>
                                                                </div>
                                                        </div>
                                                   </figcaption>
                                               </figure>
                                            </div>
                                          </div>
                                          <div class="content_info">
                                                 <?php
                                                    $cs_locationAddress = cs_get_location( $cs_post_id );
                                                        if ( isset ( $cs_locationAddress ) && $cs_locationAddress !='' ) {?>
                                                        <div class="cs-location-address" itemprop="Place" itemscope itemtype="http://schema.org/CreativeWork">
                                                         <i class="icon-map-marker"></i>
                                                           <?php echo esc_attr( $cs_locationAddress );?>
                                                       </div> 
                                                 <?php } ?>
                                                 <div class="dr_location post-<?php echo intval($cs_post_id);?>">
                                                    <?php cs_add_dirpost_favourite($cs_post_id); ?>
                                                </div>
                                          </div>
                                        </article>
											<?php 
											endwhile;
											wp_reset_query();
											?>
										</div>
									</div>
									<?php
									$qrystr = '';
									if ($count_post > $agent_per_page and $agent_per_page > 0) {
										if ( isset($_GET['action']) ) $qrystr .= "&amp;action=".$_GET['action'];
										if ( isset($_GET['uid']) ) $qrystr .= "&amp;uid=".$_GET['uid'];
										if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
										echo cs_pagination($count_post, $agent_per_page,$qrystr);
									}
								}
								else {
									echo '<div class="col-md-12"><div class="succ_mess"><p>';
										_e('No Directory Found','directory');
									echo '</p></div></div>';
								}
								?>
							</div>
						</div>
					</div>
					<!-- page content -->
				</div>
				<!-- Container -->
			</div>
			<!-- Row -->
		</section>
		<?php 
		}
		else{
			?>
            <section class="page-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                        	<?php _e('You are not authorized to access this page', 'directory'); ?>
                        </div>
                    </div>
                </div>
            </section>
                
            <?php
		}
	}else{
		echo '<p>'.__("Pleas login first to view detail page", "directory").'</p>';	
	}