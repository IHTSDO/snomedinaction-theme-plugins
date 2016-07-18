<?php 
if ( !class_exists('SingleTemplates') ) {
	
	class SingleTemplates{
		
		function __construct(){
			// Constructor Code here..
		}
		
		public function cs_directory_flex_slider( $sliderData, $thumbArray , $is_thumb,$cs_directory_type_select,$post_titleArray){
				global $cs_node,$post,$cs_theme_options;
				$cs_post_counter = rand(40, 9999999);
				
				$cs_video_switch = get_post_meta($cs_directory_type_select, "post_video_switch", true);
				$cs_video_url	 = get_post_meta((int)$post->ID, 'cs_video_url', true); 
 				?>
				<!-- Flex Slider -->
				<div id="slider-<?php echo esc_attr( $cs_post_counter );?>" class="flexslider cs-loading">
                  <ul class="slides">
                   <?php 
						$cs_counter = 1;
						$cs_title_counter = 0;
						foreach ( $sliderData as $as_node ){
							$post_title	 ='';
							$post_title	 ='';
							if(isset($post_titleArray[$cs_title_counter])){
								$post_title	= $post_titleArray[$cs_title_counter];
								$caption	= '<figcaption>'.$post_title.'</figcaption>';
							}
							echo '<li>
									<figure>
										<a href="'.esc_url( $as_node ).'" data-rel="prettyPhoto[gallery]"><img src="'.esc_url( $as_node ).'" alt="'.$post_title.'" title="'.$post_title.'"></a>
										'.$caption.'
									</figure>
									
							</li>';
							$cs_title_counter++;
 							$cs_counter++;
						}
					?>
                    <li>
                    <?php
                    if ( isset( $cs_video_url ) && $cs_video_url != '' && $cs_video_switch == 'on' ) {
						$custom_height = 408;
						$video	= wp_oembed_get($cs_video_url,array('height' => $custom_height));
						$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
						echo '<figure>';
							echo str_replace($search,'',$video);
						echo '</figure>';
					}
					?>
                    </li>
                    <!-- items mirrored twice, total of 12 -->
                  </ul>
                </div>
                <?php if ( isset( $is_thumb ) && $is_thumb == 'true' ){?>
                <div id="carousel-<?php echo esc_attr( $cs_post_counter );?>" class="flexslider">
                  <ul class="slides">
                   <?php 
						$cs_counter = 1;
						$cs_title_counter = 0;
						foreach ( $thumbArray as $as_node ){
							$post_title	 ='';
							if(isset($post_titleArray[$cs_title_counter])){
								$post_title	= $post_titleArray[$cs_title_counter];
							}
							echo '<li>
									<figure>
										<img src="'.esc_url( $as_node ).'" alt="'.$post_title.'" title="'.$post_title.'">';
									?>
									</figure>
								  </li>
						<?php 
						$cs_title_counter++;
						$cs_counter++;
						}
					?>
                    <!-- items mirrored twice, total of 12 -->
                    <?php if ( isset( $cs_video_url ) && $cs_video_url != '' && $cs_video_switch == 'on' ) { ?><li><figure><i class="icon-video"></i></figure></li><?php } ?>
                  </ul>
                </div>
				<?php }
				cs_enqueue_flexslider_script(); ?>
				<!-- Flex Slider Javascript Files -->
				<script type="text/javascript">
					jQuery(window).load(function() {
					  // The slider being synced must be initialized first
					  var target_flexslider = jQuery('#slider-<?php echo esc_attr( $cs_post_counter );?>');
					  <?php if (isset( $is_thumb ) && $is_thumb == 'true' ){?>
					  jQuery('#carousel-<?php echo esc_attr( $cs_post_counter );?>').flexslider({
						animation: "slide",
						controlNav: false,
						smoothHeight : true,
						animationLoop: false,
						slideshow: false,
						itemWidth: 65,
						itemMargin: 5,
						asNavFor: '#slider-<?php echo esc_attr( $cs_post_counter );?>'

					  });
					  <?php } ?>
					   
					  jQuery('#slider-<?php echo esc_attr( $cs_post_counter );?>').flexslider({
						animation: "slide",
						controlNav: false,
						smoothHeight : true,
						animationLoop: false,
						slideshow: false,
						sync: "#carousel-<?php echo esc_attr( $cs_post_counter );?>",
						start: function(slider) {
						   target_flexslider.removeClass('cs-loading');
					   }						
					  });
					});
				</script>
			<?php
			}
		
		public function cs_direcotry_map_location_display(){
			global $post, $cs_xmlObject,$cs_theme_options;
			$map_height	= '300';
			$event_map_heading = '';
			$map_attribute = array('column_size'=>'','cs_map_section_title'=> $event_map_heading,'map_title'=>'','map_height'=> $map_height,'map_view'=>'ROADMAP','map_info'=>'','map_info_width'=>'200','map_info_height'=>'70','map_marker_icon'=>'','map_show_marker'=>'true','map_controls'=>'false','map_draggable' => 'true','map_scrollwheel' => 'true','map_conactus_content' => '','map_border' => '','map_border_color' => '','cs_custom_class' => '','cs_custom_animation' => '','cs_custom_animation_duration'=>'1','cs_map_directions'=>'on');
			
			$address_map   = get_post_meta($post->ID, "dynamic_post_location_address", true);
			$cs_latitude   = get_post_meta($post->ID, "dynamic_post_location_latitude", true);
			$cs_longitude  = get_post_meta($post->ID, "dynamic_post_location_longitude", true);
			//$cs_zoom   	   = get_post_meta($post->ID, "dynamic_post_location_zoom", true);
			$cs_zoom = 17;
			
			if(isset($cs_latitude) && $cs_latitude <> ''){
				$map_attribute['map_lat'] = (string)$cs_latitude;
			}
			if(isset($cs_longitude) && $cs_longitude <> ''){
				$map_attribute['map_lon'] = (string)$cs_longitude;
			}
 
			$map_marker_icon = get_template_directory_uri().'/assets/images/map-marker.png';
			$map_attribute['map_marker_icon'] = $map_marker_icon;
			
			if(isset($address_map) && $address_map <> ''){
				$map_attribute['map_info'] = $address_map;
			}
			
			if(isset($cs_zoom) && $cs_zoom <> ''){
				$map_attribute['map_zoom'] = (int)$cs_zoom;
			} else {
				$map_attribute['map_zoom'] = 14;
			}
			echo cs_map_shortcode($map_attribute);
		}
		
		public function cs_direcotry_25_element($organizerID, $cs_related_ads_option, $cs_directory_type_select, $cs_post_request_form_option, $cs_post_opening_hours_option, $cs_single_template, $cs_latitude, $cs_longitude, $cs_zoom){
			global $post, $cs_xmlObject;
			
			$cs_map_place = get_post_meta((int)$cs_directory_type_select, 'post_detail_map', true);
			?>
			<div style="display:none;" class="element-size-25">
			<div class="col-md-12">
				<!-- User Info-->
				<div class="csuser_info">
					<div class="cs_druserprofile">
					 <?php 
						$cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
						$cs_display_image = '';
							$cs_display_image = cs_get_user_avatar(1 ,$organizerID);
							if( $cs_display_image <> ''){?>
								<figure>
									<a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>">
									<img height="60" width="60" src="<?php echo esc_url( $cs_display_image );?>" alt="" />
									</a>
								</figure>
							<?php }else{?>
								<figure>
									<a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>">
									<?php echo get_avatar(get_the_author_meta('user_email',$organizerID), apply_filters('PixFill_author_bio_avatar_size', 60));?>
									</a>
								</figure>
							<?php }?>
							<div class="cs_info">
								<h6>
									<a href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>">
										<?php echo get_the_author_meta('display_name',$organizerID );?>
									</a>
								</h6>
								<span>
									<?php 
									$userType =  get_the_author_meta('roles',$organizerID);
									if(isset($userType[0])) echo esc_attr($userType[0]);
									?>
								</span>
							</div>
					</div>
					<?php 
						$cs_mobile         = $cs_landline = $cs_email = $cs_skype = $cs_website =  '';
						$cs_mobile         = get_post_meta( $post->ID, 'user_mobile', true); 
						$cs_landline       = get_post_meta( $post->ID, 'user_landline', true);
						$cs_email          = get_post_meta( $post->ID, 'user_email', true);
						$cs_skype          = get_post_meta( $post->ID, 'user_skype', true);
						$cs_website        = get_post_meta( $post->ID, 'user_website', true);
					if( $cs_mobile !='' || $cs_landline !='' || $cs_email !='' || $cs_skype !='' || $cs_website !='' ) {
					?>
                    <ul>
						<?php if( $cs_mobile !='' ) {?>
                        <li><i class="icon-mobile-phone"></i><span><?php esc_html_e('Mobile:','directory');?><?php echo cs_allow_special_char($cs_mobile);?></span></li>
                        <?php }?>
						<?php if( $cs_landline !='' ) {?>
                        <li><i class="icon-phone8"></i><span><?php esc_html_e('Landline:','directory');?><?php echo cs_allow_special_char($cs_landline);?></span></li>
                        <?php }?>
                        <?php if( $cs_email !='' ) {?>
                        <li><i class="icon-mail"></i><span><?php esc_html_e('Email:','directory');?><?php echo cs_allow_special_char($cs_email);?></span></li>
                        <?php }?>
                        <?php if( $cs_skype !='' ) {?>
                        <li><i class="icon-skype"></i><span><?php esc_html_e('Skype:','directory');?><?php echo cs_allow_special_char($cs_skype);?></span></li>
                        <?php }?>
                        <?php if( $cs_website !='' ) {?>
                        <li><i class="icon-fax"></i><span><?php esc_html_e('Website:','directory');?><?php echo esc_url($cs_website);?></span></li>
                        <?php }?>
					</ul>
                    <?php }?>
					<div class="social-media">
						<ul>
							 <?php 
								$facebook = $twitter = $linkedin = $pinterest = $google_plus ='';
								$facebook = get_the_author_meta('facebook',$organizerID); 
								$twitter  = get_the_author_meta('twitter',$organizerID);
								$linkedin = get_the_author_meta('linkedin',$organizerID);
								$pinterest = get_the_author_meta('pinterest',$organizerID);
								$google_plus = get_the_author_meta('google_plus',$organizerID);
								$instagram = get_the_author_meta('instagram',$organizerID);
								$skype = get_the_author_meta('skype',$organizerID);
								if(isset($facebook) and $facebook <> ''){
									echo '<li><a href="'.esc_url($facebook).'"><i class="icon-facebook2"></i></a></li>';
								}
								if(isset($twitter) and $twitter <> ''){
									echo '<li><a href="'.esc_url($twitter).'"><i class="icon-twitter6"></i></a></li>';
								}
								if(isset($linkedin) and $linkedin <> ''){
									echo '<li><a href="'.esc_url($linkedin).'"><i class="icon-linkedin2"></i></a></li>';
								}
								if(isset($pinterest) and $pinterest <> ''){
									echo '<li><a href="'.esc_url($pinterest).'"><i class="icon-pinterest"></i></a></li>';
								}
								if(isset($google_plus) and $google_plus <> ''){
									echo '<li><a href="'.esc_url($google_plus).'"><i class="icon-google-plus"></i></a></li>';
								}
								if(isset($skype) and $skype <> ''){
									echo '<li><a href="skype:'.esc_url($skype).'?chat"><i class="icon-skype"></i></a></li>';
								}
								if(isset($instagram) and $instagram <> ''){
									echo '<li><a href="'.esc_url($instagram).'"> <i class="icon-instagram"></i></a></li>';
								}
							?> 
						</ul> 
					</div> 
				</div>
				<?php 
				if (isset( $cs_related_ads_option ) && $cs_related_ads_option == 'on' ) {
					wp_reset_postdata();
					$args_cat  = array('author' => $organizerID);
					$post_type = array('directory');
					$args = array( 
						'post_type'       => $post_type, 
						'post_status'     => 'publish',
						'meta_key'        => 'directory_organizer',
						'meta_value'      => $organizerID,
						'order'           => 'DESC',
						'posts_per_page' => 3,
						'post__not_in'   => array ($post->ID),
					);
					$custom_query = new WP_Query($args);
					$post_count = $custom_query->post_count;
					if( isset( $post_count ) && $post_count > 0 ) {  
						$dir_payment_date = get_post_meta($post->ID, "dir_payment_date", true);
						if($dir_payment_date == ''){
							$dir_payment_date = get_the_date();
						}
					?>
						<div class="csuser_review addreview">
							<h6><?php _e('MORE ads BY','directory');?><?php echo get_the_author_meta('display_name',$organizerID);?></h6>
							<ul>
								<?php     
								 if ( $custom_query->have_posts() ): 
										while ( $custom_query->have_posts() ) : $custom_query->the_post();
											$cs_directory_review = get_post_meta($post->ID, "cs_directory_meta", true);
											if ( $cs_directory_review <> "" ) {
												$cs_xmlObject_reviews = new SimpleXMLElement($cs_directory_review);
											}
											$width  = 150;
											$height = 150;
											$image_url = get_post_meta($post->ID, '_directory_image_gallery', true );
                                        
											$image_url = array_filter( explode( ',', $image_url ) );
											
											if ( isset( $image_url ) && ! empty( $image_url ) ) {
												$img_class = 'no-image';    
												$image_url = isset($image_url[0]) ? cs_attachment_image_src( $image_url[0], $width, $height ) : '';
											} else {
												$img_class = '';
												$image_url = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
											}
										?>
										<li id="post-<?php the_ID();?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
											<?php if ( isset( $image_url ) && $image_url !='' ){?>
                                            <figure>
                                              <img src="<?php echo esc_url( $image_url );?>" alt=""/>
                                            </figure>
                                            <?php } ?>
                                            <h5><a  href="<?php esc_url(the_permalink());?>"><?php the_title();?></a></h5>
                                            <?php echo cs_get_directory_price( $post->ID ); ?>
										</li>
									<?php  
									endwhile;
								endif;
								wp_reset_postdata();    
							?>
							</ul>
							<div class="seller-view-all">
								<i class="icon-plus-square"></i>
								<a href="<?php echo cs_user_profile_link($cs_page_id, 'my_ads', $organizerID);?>"><?php _e('Recent Ads','directory');?></a>
							</div>
						</div>
					<?php 
					}
				}
				
				// Map
				if ( $cs_map_place == 'Sidebar' ) {
					if ( isset( $cs_latitude ) 
					  && isset( $cs_longitude ) 
					  && isset( $cs_zoom )
					  && $cs_latitude   != ''
					  && $cs_longitude  != ''
					  && $cs_zoom        != '' 
					) {
					?>
						<div class="cs-sidebar-map"> 
							<?php $cs_single_template->cs_direcotry_map_location_display(); ?>
						</div>
					<?php 
					}
				}
				
				// Request Detail Start
				if ( isset( $cs_post_request_form_option ) && $cs_post_request_form_option == 'on' ) {
					cs_user_conatct_form( $organizerID );
				}

				global $cs_xmlObject;
				$is_opening	= false;
				$weekdays	= array( "Sun" => "Sunday", "Mon" => "Monday", "Tue" => "Tuesday", "Wed" => "Wednesday", "Thu" => "Thursday", "Fri" => "Friday", "Sat" => "Saturday" );
				$output		= '';
				$opening    = get_post_meta($post->ID,'opening_hours',false);
									
				foreach($weekdays as $key=>$value){
					$weekday_text	= 'openhours_'.$key.'_text';
					$weekday_start	= 'openhours_'.$key.'_start';
					$weekday_end	= 'openhours_'.$key.'_end';
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
				
				$cs_open_hours_text = get_post_meta((int)$cs_directory_type_select, 'cs_post_opening_hours_input', true);
				
				$cs_open_hours_text = $cs_open_hours_text <> '' ? $cs_open_hours_text : __('Contact Timings', 'directory');
				
				if( isset( $cs_post_opening_hours_option ) && $cs_post_opening_hours_option == 'on' && $is_opening ) { ?>
                <div class="opening-hours">
					<h5><?php echo cs_allow_special_char($cs_open_hours_text); ?></h5>
					<div class="cs-opening">
						<ul>
							<?php echo balanceTags($output, true);?>
						</ul>
					</div>
				</div>
				<?php  
				}
				?> 
		   </div>
		  </div>
		  	<?php
		}
		
		public function cs_direcotry_75_element( $cs_views, $cs_single_template, $cs_directory_type_select, $cs_related_ads_option, $address_map, $cs_latitude, $cs_longitude, $cs_zoom,$organizerID,$cs_post_favourites_option ){
			global $post, $cs_xmlObject, $current_user, $cs_report_counter;
			$cs_feature_options	= get_post_meta((int)$cs_directory_type_select, 'cs_feature_meta', true);
			$cs_map_place = get_post_meta((int)$cs_directory_type_select, 'post_detail_map', true);
			$featureList			= get_post_meta((int)$post->ID, 'cs_feature_list', true);
			if ( isset( $featureList ) && !empty( $featureList ) ) {
				$featureList		= explode( ',', $featureList );
			} else {
				$featureList		= array();
			}
			$cs_video_url			= get_post_meta((int)$post->ID, 'cs_video_url', true);              
			$cs_report_counter		= rand(342, 4534009);
   			$cs_claim_counter		= rand(547, 1576579);
			?>
            <div class="element-size-100">
                <div class="col-md-12">
                    <div class="cs-post-title" itemscope itemtype="http://schema.org/Thing"><h1 itemprop="name"><?php the_title(); ?></h1></div>
                    <div class="directory-top">
                        <!--<ul class="dr_postoption">
                         <li>
                          <?php 
                            $dir_payment_date      = get_post_meta($post->ID, "dir_payment_date", true);
                            if($dir_payment_date == '')
                              $dir_payment_date = get_the_date($post->ID);
                              if($dir_payment_date == ''){
								  $dir_payment_date =get_option( 'Y-m-d' );  
                              }
                              _e('Posted on','directory');
                              ?>
                              <time datetime="<?php echo esc_attr( date_i18n( 'Y-m-d',strtotime( $dir_payment_date ) ) );?>">
                                <?php echo esc_attr( date_i18n( get_option( 'date_format' ),strtotime( $dir_payment_date ) ) );?></time>
                                <?php $categories_list = get_the_term_list ( get_the_id(), 'directory-category', '' , ', ', '' );
                                if ( $categories_list ){

                                  _e('in', 'directory');
                                  printf( __( '%s', 'directory'),$categories_list);
                                ?>
                           <?php } ?>
                           </li>
                           
						  <?php $address_map    = $cs_xmlObject->dynamic_post_location_address;
						   if ( isset( $address_map ) && $address_map !='' ) {?>
                              <li itemscope itemtype="http://schema.org/CreativeWork">
                                <span itemprop="Place"><i class="icon-map-marker"></i><?php echo esc_attr( $address_map );?></span>
                              </li>
                          <?php } ?>
                          
                        </ul> -->
                       <div class="favorites-section" style="display:none;">
                            <ul>
                              <li>
                                <?php cs_addthis_script_init_method(); ?>
                                <a class="csshare cs-btnsharenow btnshare addthis_button_compact"><i class="icon-share-alt"></i></a>
                               </li>
                              <li>
                               <a class="report" data-target="#myReport" data-toggle="modal"  href="#" title="report"><i class="icon-warning4"></i></a>
                               <div aria-hidden="true" role="dialog" tabindex="-1" id="myReport" class="modal fade review-modal">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-body">
                                        <button data-dismiss="modal" class="close" type="button">
                                        <span aria-hidden="true"><i class="icon-times"></i></span><span class="sr-only"><?php _e('Close','directory');?></span>
                                      </button>
                                      <h5><?php _e('Write your Report','directory');?></h5>
                                     <div id="report-loading-<?php echo esc_attr($cs_report_counter); ?>"></div>
                                     <div class="report-message-type-<?php echo esc_attr($cs_report_counter); ?> succ_mess" style="display:none"><p></p></div>
                                     <form name="report-form" id="cs-report-form-<?php echo esc_attr($cs_report_counter); ?>">
                                      <ul class="reviews-modal">
                                          <li>
                                              <input type="hidden" name="action" value="cs_add_report" />
                                              <input type="hidden" name="directory_id" value="<?php echo absint($post->ID);?>" />
                                              <label><?php _e('Name','directory');?></label>
                                              <input type="text" id="report_from_name_<?php echo esc_attr($cs_report_counter); ?>" name="report_from_name_<?php echo esc_attr($cs_report_counter); ?>">
                                          </li>
                                          <?php  if ( !is_user_logged_in() ) { ?>
                                          <li>    
                                              <label><?php _e('Email','directory');?></label>
                                              <input type="text" id="report_from_email_<?php echo esc_attr($cs_report_counter); ?>" name="report_from_email_<?php echo esc_attr($cs_report_counter); ?>">
                                          </li>
                                          <?php }?>
                                          <li>
                                              <label><?php _e('Subject','directory');?></label>
                                              <input type="text" id="report_title_<?php echo esc_attr($cs_report_counter); ?>" name="report_title_<?php echo esc_attr($cs_report_counter); ?>">
                                         
                                          </li>
                                          <li>
                                              <input type="hidden" name="report_type_<?php echo esc_attr($cs_report_counter); ?>" id="report_type_<?php echo esc_attr($cs_report_counter); ?>" value="Report" />
                                          </li>
                                          <li>
                                              <label><?php _e('Write Description','directory');?></label>
                                              <textarea name="report_description_<?php echo esc_attr($cs_report_counter); ?>" id="report_description_<?php echo esc_attr($cs_report_counter); ?>"></textarea>
                                          </li>
                                          <li>
                                            <div class="report-modal-footer">
                                                <input type="hidden" name="report_counter" id="report_counter" value="<?php echo esc_attr($cs_report_counter); ?>" />
                                                <input type="button" value="<?php _e('Send','directory');?>" class="cs-bgcolor" onclick="cs_report_submission('<?php echo admin_url('admin-ajax.php')?>', '<?php echo get_template_directory_uri()?>','cs-report-form-<?php echo esc_attr($cs_report_counter); ?>','report', '<?php echo esc_attr($cs_report_counter); ?>');">
                                            
                                            </div>
                                          </li>
                                      </ul>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              </li>
                              <li>
                               <a  class="claim" data-target="#myClaim" data-toggle="modal" href="#" title="claim"><i class="icon-new"></i></a>
                               <div aria-hidden="true" role="dialog" tabindex="-1" id="myClaim" class="modal fade review-modal">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-body">
                                        <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="icon-times"></i></span><span class="sr-only">Close</span></button>
                                      <h5><?php _e('Write your Claim','directory');?></h5>
                                     <div id="claim-loading-<?php echo esc_attr($cs_claim_counter); ?>"></div>
                                     <div class="claim-message-type-<?php echo esc_attr($cs_claim_counter); ?> succ_mess" style="display:none"><p></p></div>
                                     <form name="claim-form" id="cs-claim-form-<?php echo esc_attr($cs_claim_counter); ?>">
                                      <ul class="reviews-modal">
                                          <li>
                                              <input type="hidden" name="action" value="cs_add_report" />
                                              <input type="hidden" name="directory_id" value="<?php echo absint($post->ID);?>" />
                                              <label><?php _e('Name','directory');?></label>
                                              <input type="text" id="report_from_name_<?php echo esc_attr($cs_claim_counter); ?>" name="report_from_name_<?php echo esc_attr($cs_claim_counter); ?>">
                                          </li>
                                          <?php  if ( !is_user_logged_in() ) { ?>
                                          <li>    
                                              <label><?php _e('Email','directory');?></label>
                                              <input type="text" id="report_from_email_<?php echo esc_attr($cs_claim_counter); ?>" name="report_from_email_<?php echo esc_attr($cs_claim_counter); ?>">
                                          </li>
                                          <?php }?>
                                          <li>
                                              <label><?php _e('Subject','directory');?></label>
                                              <input type="text" id="report_title_<?php echo esc_attr($cs_claim_counter); ?>" name="report_title_<?php echo esc_attr($cs_claim_counter); ?>">
                                          </li>
                                          <li><input type="hidden" name="report_type_<?php echo esc_attr($cs_claim_counter); ?>" id="report_type_<?php echo esc_attr($cs_claim_counter); ?>" value="Claim" /></li>
                                          <li>
                                              <label><?php _e('Write Description','directory');?></label>
                                              <textarea name="report_description_<?php echo esc_attr($cs_claim_counter); ?>" id="report_description_<?php echo esc_attr($cs_claim_counter); ?>"></textarea>
                                          </li>
                                          <li> 
                                              <div class="claim-modal-footer">
                                                  <input type="hidden" name="report_counter" value="<?php echo esc_attr($cs_claim_counter); ?>" />
                                                  <input type="button" value="<?php _e('Send','directory');?>" class="cs-bgcolor" onclick="cs_report_submission('<?php echo admin_url('admin-ajax.php')?>', '<?php echo get_template_directory_uri()?>','cs-claim-form-<?php echo esc_attr($cs_claim_counter); ?>','claim', '<?php echo esc_attr($cs_claim_counter); ?>');">
                                              </div>
                                          </li>
                                      </ul>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              </li>
                              <li><span class="add-views"><i class="icon-eye3"></i> <?php echo intval( $cs_views );?></span></li>
                              <li class="post-<?php echo intval( $post->ID );?>">
                                <?php
                                    if ( isset( $cs_post_favourites_option ) && $cs_post_favourites_option == 'on' ){
                                        cs_add_dirpost_favourite($post->ID );
                                     }
                                 ?>
                               </li>
                            </ul>
                        </div>
                       
                            <?php
                            wp_directory::cs_prettyPhoto_scripts();
                            $galleryDataThumb    = array();
                            $galleryData        = array();
                            $directory_image_gallery = get_post_meta( $post->ID, '_directory_image_gallery', true );
                           
                            $attachments = array_filter( explode( ',', $directory_image_gallery ) );
                            $attachmentsArray    = array();
                            $thumbArray            = array();
							$post_titleArray       = array();
                            
							if ( $attachments ) {
                                foreach ( $attachments as $attachment_id ) {
                                    $class			= '';
                                    $iconZoom		= '';
                                    $width_thumb	= 150;
                                    $height_thumb	= 150;
                                    $width			= 842;
                                    $height			= 474;
                                    $ZoomClass		= '';
                                    $thumb_url		= cs_attachment_image_src( $attachment_id ,$width_thumb,$height_thumb); 
                                    $image_url		= cs_attachment_image_src( $attachment_id ,$width,$height);
                                    $attachments_data	= get_post( $attachment_id, 'OBJECT', 'raw' );
									$post_title			= $attachments_data->post_excerpt;
                                    
									if ( isset( $image_url ) && $image_url != '' ){ 
                                        $attachmentsArray[] = $image_url;
										$post_titleArray[] = $post_title;
                                    }
                                    if (isset( $thumb_url ) && $thumb_url != '' ){ 
                                        $thumbArray[] = $thumb_url;
                                    }
                                }
                            }

                            $attachments    = array_merge( $galleryData , $attachmentsArray );
                            $thumbnail        = array_merge( $galleryDataThumb , $thumbArray );
							
							if($attachments){
						    	echo '<div class="directory-gallery lightbox">';
								$cs_single_template->cs_directory_flex_slider( $attachments , $thumbnail , 'true' ,$cs_directory_type_select , $post_titleArray);
								
							}else{
								$cs_video_switch = get_post_meta($cs_directory_type_select, "post_video_switch", true);
								if ( isset( $cs_video_url ) && $cs_video_url != '' && $cs_video_switch == 'on' ) {
									$custom_height = 408;
									$video	= wp_oembed_get($cs_video_url,array('height' => $custom_height));
									$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
									echo '<div class="directory-gallery lightbox"><div class="cs-video-icon"><ul class="slides"><li><figure class="detailpost">';
										echo  str_replace($search,'',$video);
									echo '</figure></li><li><figure><i class="icon-video"></i></figure></li></ul></div></div>';
								}
							}
							
							if( !empty($attachments) && is_array($attachments) ){
                            ?>
                            <div class="dr_pricesection posts-<?php echo absint($post->ID); ?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <?php echo cs_get_directory_price( $post->ID, 'true' ); ?>
                            </div></div>
                            <?php }?>
                        </div>
                        <!--post Description Start-->
                        <div id="cs_description">
                        	<?php if( empty($attachments) ){ ?>
                            <div class="dr_pricesection post-<?php echo absint($post->ID); ?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <?php echo cs_get_directory_price( $post->ID, 'true' ); ?>
                            </div>
                            <?php }?>
                            <!--post Specifications Start-->    
                            <?php cs_get_post_specification($cs_directory_type_select); ?>
                            <!--post Specifications End-->
                        <div class="rich_editor_text">
                                <h5><?php _e('Description', 'directory'); ?></h5>
                                <?php 
                                    the_content();
                                    wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'directory' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                                ?>
 							<br>
 							<h5><?php _e('Scope', 'directory'); ?></h5>
 							<p><?php the_scope(); ?></p>
                            <p><?php 
echo get_post_meta( get_the_ID(), 'dir_scope', true ); ?>
                            </p>
 							<br>
 							<h5><?php _e('How Snomed CT will be used', 'directory'); ?></h5>
 							<p>
                            <?php 
echo get_post_meta( get_the_ID(), 'dir_how', true ); ?>
</p>
 							<br>
 							<h5><?php _e('Why Snomed CT was selected', 'directory'); ?></h5>
 							<p>
                            <?php 
echo get_post_meta( get_the_ID(), 'dir_why', true ); ?>
</p>
 							</div>
                            <!-- Tags -->
                            
                            <?php 
                            $post_tags_show_text = __('Tags', 'directory');
                            $categories_list = get_the_term_list ( get_the_id(), 'directory-tag', '<li>', '</li><li>', '</li>' );
                            if ( isset($categories_list) and $categories_list <> '' ){ ?>
                                <div class="cs-tags">
                                  <!-- cs Tages Start -->
                                  <h5><?php echo esc_attr($post_tags_show_text);?></h5>
                                  <ul><?php printf( __( '%1$s', 'directory'),$categories_list ); ?></ul>
                                </div>   
                            <?php
                            }
                            ?>
                        </div>
                        <!-- Post Description End -->
                      
                    <?php
                    if(isset($cs_feature_options) && is_array($cs_feature_options) && count($cs_feature_options)>0){
                    ?>
                        <div class="featured-list">                        
							
							<h5><?php _e('Feature List','directory');?></h5>
                            <ul>
                                <?php 
                                foreach($cs_feature_options as $feature_key=>$feature){
                                    if(isset($feature_key) && $feature_key <> ''){
                                        $counter_feature = $feature_id = $feature['feature_id'];
                                        $feature_title      = $feature['feature_title'];
                                        $feature_slug       = $feature['feature_slug'];
                                        $is_feature         = 'icon-cross5';
                                        
                                        if ( is_array( $featureList ) && in_array( $feature_slug , $featureList )  ) {
                                            $is_feature     = 'icon-check-circle';
                                        }
                                        ?>
                                        <li><i class="<?php echo sanitize_html_class( $is_feature );?>"></i><a><?php echo esc_attr( $feature_title );?></a></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                       </div>
                    <?php
                    }
                    
                    $cs_meta_options    = get_post_meta((int)$post->ID, 'cs_directory_meta', true); 
                    $cs_xmlObject_faq    = new SimpleXMLElement( $cs_meta_options );
                    $faqData            = $cs_xmlObject_faq->children()->faqs;
                    $cs_faq_title        = get_post_meta((int)$cs_directory_type_select, 'cs_post_faqs_input', true);
                    $as_node            = new stdClass();
                    $i    = 0;
                    if ( isset( $faqData ) && !empty($faqData) ){?>
                        <!--FAQ  Start-->
                        <div id="cs_faq" class="tab-pane" role="tabpanel">
                            <h5><?php echo esc_attr( $cs_faq_title ); ?></h5>
                            <div id="accordion30" class="panel-group faqs-veiw">
                                <?php
                                foreach ( $faqData as $as_node ){
                                    $i++; 
									$sc_colapse_class = absint($i) == '1' ? '' : 'collapsed';
                                    ?>
                                    <div class="panel panel-default">
                                      <div class="panel-heading">
                                        <h4 class="panel-title">
                                          <a href="#collapse<?php echo absint($i);?>" data-parent="#accordion30" class="<?php echo sanitize_html_class($sc_colapse_class); ?>" data-toggle="collapse"><?php echo esc_attr( $as_node->faq_title );?></a>
                                        </h4>
                                      </div>
                                      <div class="panel-collapse collapse <?php echo absint($i) == '1' ? 'in' : '';?> " id="collapse<?php echo absint($i);?>">
                                        <div class="panel-body">
                                          <p><?php echo preg_replace("@[/\\\]@", "", $as_node->faq_description );?></p>
                                        </div>
                                      </div>
                                    </div>
                                 <?php 
                                 }
                                 ?>
                            </div>
                        </div>
                        <!--FAQ End-->
                    <?php }?>
                    
                    <?php 
					$attachments_file = array();
					$directory_attachment = '';
					$cs_hint	= '';
					$cs_total_attahmets = 0;
					if(isset($post->ID) && !empty($post->ID)){ 
						if ( metadata_exists( 'post', $post->ID, '_directory_file_attachment' ) ) {
							$directory_attachment = get_post_meta( $post->ID, '_directory_file_attachment', true );
							$attachments_file = array_filter( explode( ',', $directory_attachment ) );
						}

						if ( isset( $attachments_file ) && !empty( $attachments_file ) ) {?>
                        <div id="cs_faq" class="tab-pane" role="tabpanel">
                            <h5><?php echo __('Attachments','directory');?></h5>
                            <ul class="list-attachment-wrap">
                                <?php
                                    foreach ( $attachments_file as $attachment_file_id ) {
                                        $cs_total_attahmets++;
                                        $cs_hint		= 'style="display:none"';
                                        $attachments	= get_post( $attachment_file_id, 'OBJECT', 'raw' );
                                        $mime			= get_post_mime_type( $attachment_file_id );
                                        $mime_type_icon = wp_mime_type_icon($mime);
										$file_url 		= wp_get_attachment_url( $attachment_file_id );
										$filetype 		= wp_check_filetype( $file_url );
                                        
                                        echo '<li class="cs_attachments"><a title="'.$attachments->post_excerpt.'"  href="'.wp_get_attachment_url( $attachment_file_id ).'">
                                                <span class="cs-attachment-list">
                                                    <i class="'.cs_get_attachment_icon($filetype['ext']).'"></i>
                                                </span>
                                                <span class="cs-download">
                                                    <i class="icon-arrow-circle-o-down"></i>
                                                </span>
												<span class="name">'.$attachments->post_excerpt.'</span>
                                            </a></li>';
                                       }      
                                ?>
                            </ul>
                     	</div>
                    <?php
					  }
                    }
					// Map
					if ( $cs_map_place == 'Content' ) {
						if ( isset( $cs_latitude ) 
						  && isset( $cs_longitude ) 
						  && isset( $cs_zoom )
						  && $cs_latitude   != ''
						  && $cs_longitude  != ''
						  && $cs_zoom        != '' 
						) {
						?>
							<div class="cs-sidebar-map"> 
								<?php $cs_single_template->cs_direcotry_map_location_display(); ?>
							</div>
						<?php 
						}
					}
					?>
                    
                    <!--Reviews Start-->
                    <?php 
                    if ( cs_dir_switch($post->ID)  == 'on' ) {
                    ?>
                        <div id="cs_reviews" class="tab-pane profile-review has-border <?php echo isset( $_GET['action'] ) && $_GET['action'] == 'reviews'  ? 'active' : '';?>" role="tabpanel">
                        	<?php
								wp_reset_postdata();
								$count_args = array(
									'posts_per_page'             => "-1",
									'post_type'                  => 'cs-reviews',
									'post_status'                => 'publish',
									'meta_key'                   => 'cs_reviews_directory',
									'meta_value'                 => $post->ID,
									'meta_compare'               => "=",
									'orderby'                    => 'meta_value',
									'order'                      => 'ASC',
								);
								$count_query = new WP_Query($count_args);
								$reviews_count = $count_query->post_count;
							?>
                            <div class="cs-leave-review">
                                <meta itemprop="itemReviewed" content="<?php echo get_the_title($post->ID);?>">
                                <meta itemprop="ratingCount" content="<?php echo absint($reviews_count);?>">
                                <meta itemprop="bestRating" content="5">
                                <meta itemprop="worstRating" content="0">
                                <meta itemprop="ratingValue" content="<?php echo cs_total_reviews_score( $post->ID, false );?>">
                                <h4><?php printf(__('%s Reviews','directory'), $reviews_count);?></h4>
                                <?php
                                echo '<span class="cs-total-score">' . cs_total_reviews_score( $post->ID, false ) . '</span>';
                                cs_total_reviews_stars( $post->ID );
                                ?>
                                <button class="add_review_btn custom-btn" data-target="#myModal" data-toggle="modal" type="button">
                                    <i class="icon-star6"></i>
                                    <?php _e('Leave us a review','directory');?>
                                </button>
                            </div>
                            <div class="cs-ratings-upper">
							<?php
							cs_total_score_section($post->ID);
                            wp_reset_postdata();
                            $cs_reviews_post_per_page = isset($cs_theme_options['reviews_per_page']) ? $cs_theme_options['reviews_per_page'] : 10;
                            
                            $page_id_all = '';
                            if(isset($_GET['page_id_all']) && $_GET['page_id_all'] !=''){
                                $page_id_all    = $_GET['page_id_all'];
                            }
                            $reviews_args = array(
                                'posts_per_page'	=> "$cs_reviews_post_per_page",
                                'paged'				=> $page_id_all,
                                'post_type'			=> 'cs-reviews',
                                'post_status'		=> 'publish',
                                'meta_key'			=> 'cs_reviews_directory',
                                'meta_value'		=> $post->ID,
                                'meta_compare'		=> "=",
                                'orderby'			=> 'ID',
                                'order'				=> 'DESC',
                            );
                            $reviews_query = new WP_Query($reviews_args);
                      
                            if ( $reviews_query->have_posts() <> "" ){
                                while ( $reviews_query->have_posts() ): $reviews_query->the_post();    
                                    $var_cp_rating = get_post_meta($post->ID, "cs_reviews_rating", true);
                                    $var_cp_reviews_members = get_post_meta($post->ID, "cs_reviews_user", true);
                                    $cs_reviews_directory = get_post_meta($post->ID, "cs_reviews_directory", true);
                                    $cs_directory_type_select = get_post_meta($cs_reviews_directory, "directory_type_select", true);
                                    $cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);
                                    $rating = 0;
                                    $rating_array = array();
                                    if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
										foreach($cs_rating_options as $rating_key=>$rating){
											if(isset($rating_key) && $rating_key <> ''){
											$counter_rating = $rating_id = $rating['rating_id'];
											$rating_title = $rating['rating_title'];
											$rating_slug  = $rating['rating_slug'];
											$rating_point = get_post_meta($post->ID, $rating_slug, true);
											if($rating_point)
												$rating_array[] = $rating_point;
											}
										}
										$rating = round(array_sum($rating_array)/count($cs_rating_options), 2);
                                    }
                                    if(isset($rating)){$rating = $rating;} else {$rating = 0;}
                                    ?>            
                                    <article class="cs-reviews">
                                        <?php 
                                        $cs_display_image = '';
                                        $cs_display_image = cs_get_user_avatar(1 ,$var_cp_reviews_members);
                                        $userId    = cs_get_user_id();
										?>
                                        <figure>
                                        <?php
                                        if( $cs_display_image <> ''){?>
                                            <a class="info-thumb"><img height="60" width="60" src="<?php echo esc_url( $cs_display_image );?>"  /></a>
                                            <?php } else { ?>
                                            <a class="info-thumb"><?php echo get_avatar(get_the_author_meta('user_email',$var_cp_reviews_members), apply_filters('PixFill_author_bio_avatar_size', 60));?></a>
										<?php } ?>
                                            <figcaption>
                                            	<div class="cs-iconstyle"><span><i class="icon-star"></i> <?php echo cs_allow_special_char($rating); ?> <i class="icon-plus-square-o"></i></span></div>
                                            </figcaption>
                                            <?php
											$rating_array = array();
											if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
												echo '<ul class="plus-review">';
												foreach($cs_rating_options as $rating_key=>$rating){
													if(isset($rating_key) && $rating_key <> ''){
													$counter_rating = $rating_id = $rating['rating_id'];
													$rating_title = $rating['rating_title'];
													$rating_slug  = $rating['rating_slug'];
													$rating_point = get_post_meta($post->ID, $rating_slug, true);
													if($rating_point)
														$rating_array[] = $rating_point;
													}
													echo '<li><span class="cs-shorttitle">'.$rating_title.'</span>
															<div class="cs-ratingstar-wrap"><div class="cs-ratingstar"><span style="width:'.cs_allow_special_char($rating_point*20).'%"></span></div></div>
														 </li>';
												}
												echo '</ul>';
											}
											?>
                                        </figure>
                                        <div class="left-sp">
                                            <?php
                                                echo '<span class="cs-rating-desc">'.get_the_title().'</span>';
                                                echo '<div class="cs-review-description">'.get_the_content().'</div>';
                                                cs_get_ad_reviews($post->ID);
                                             ?>
                                        </div>
                                    </article>
                                    <?php 
                                endwhile;
                            } else { 
                            ?>
                                <div class="rich_editor_text succ_mess">
                                	<p><?php echo esc_html_e('Looking for review? There is no review here!','directory');?></p>
                                </div>
                            <?php 
                            }
							?>
                            </div>
                            <?php
                            wp_reset_postdata();
                            $qrystr = '';
                            if (  $reviews_count > $cs_reviews_post_per_page) {
                                if ( isset($_GET['page_id']) ) $qrystr .= "&page_id=".$_GET['page_id'];
                                $qrystr .= "&action=reviews";
                                echo cs_pagination($reviews_count, $cs_reviews_post_per_page,$qrystr);
                            } 
                            $cs_review_modal_class = '';
                            if ( is_user_logged_in() ) {
                                $cs_review_modal_class = 'review-modal';
                            }
                            ?>
                            <!-- Modal Review -->
                            <div aria-hidden="true" role="dialog" tabindex="-1" id="myModal" class="modal fade add-to-favborites-modal <?php echo esc_attr($cs_review_modal_class); ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                          <?php  
                                            if ( is_user_logged_in() ) {
                                                wp_directory::cs_enqueue_rating_style_script();
                                                $user_id            = cs_get_user_id();
                                                $user_reviews_args = array(
                                                    'posts_per_page'    => "-1",
                                                    'post_type'            => 'cs-reviews',
                                                    'post_status'        => 'any',
                                                    'author'             => $organizerID,
                                                    'meta_key'            => 'cs_reviews_directory',
                                                    'meta_value'        => $post->ID,
                                                    'meta_compare'        => "=",
                                                    'orderby'            => 'meta_value',
                                                    'order'                => 'ASC',
                                                );
                                                $user_reviews_query = new WP_Query($user_reviews_args);
                                                $user_reviews_count = $user_reviews_query->post_count;
                                                $cs_directory_type_select = get_post_meta($post->ID, "directory_type_select", true);
                                                $cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);
                                                if( isset( $user_reviews_count ) && $user_reviews_count > 0 ){
                                                //printf('%s','Already Submited Review');
                                                ?>    
                                                <p><?php _e('Already submit Review.','directory');?></p>
                                                <?php 
                                                } elseif ( $organizerID == $current_user->ID ) {
                                                ?>
                                                <p><?php _e('Oops! You cannot add review to your own Ad.','directory');?></p>
                                                <?php 
                                                } else {
                                                ?>
                                                
                                                <h2><?php _e('Add Review','directory');?></h2>
												<button data-dismiss="modal" class="close" type="button">
                                                  <span aria-hidden="true"><i class="icon-times"></i></span><span class="sr-only">Close</span>
                                                </button>
                                                <div class="review-message-type succ_mess" style="display:none"><p></p></div>
                                                <form name="reviews-form" id="cs-reviews-form">
                                                    <div id="loading"></div>
                                                    <ul class="reviews-modal">
                                                        <?php
                                                        if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
                                                            foreach($cs_rating_options as $rating_key=>$rating){
                                                                if(isset($rating_key) && $rating_key <> ''){
                                                                    $rating_title = $rating['rating_title'];
                                                                    $rating_slug = $rating['rating_slug'];
                                                                    $rating = get_post_meta($post->ID, $rating_slug, true);
                                                                    if(isset($rating)){$rating = $rating*20;} else {$rating = 0;}
                                                                    ?>
                                                                    <script type="text/javascript">
                                                                    jQuery(document).ready(function(){
                                                                        jQuery(".<?php echo esc_js($rating_slug);?>").jRating({
                                                                            step:true, 
                                                                            bigStarsPath : "<?php echo esc_js(wp_directory::plugin_img_url());?>assets/images/cs-stars.png",
                                                                            smallStarsPath : "<?php echo esc_js(wp_directory::plugin_img_url());?>assets/images/small.png",
                                                                            rateMax : 5,
                                                                            length : 5,
                                                                            canRateAgain : true,
                                                                            nbRates : 10,
                                                                            onClick : function(element,rate) {
                                                                                jQuery('#<?php echo esc_js($rating_slug);?>').val(rate);
                                                                            }
                                                                        });
                                                                    });
                                                                    </script>
                                                                    <li>
                                                                    <label><?php echo esc_attr($rating_title);?></label>
                                                                    <div class="<?php echo sanitize_html_class($rating_slug);?>" data-average="0" data-id="0"></div>
                                                                        <input type="hidden" name="<?php echo esc_attr($rating_slug);?>" id="<?php echo esc_attr($rating_slug);?>" value="0" />
                                                                    </li>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <li>
                                                            <input type="hidden" name="action" value="cs_add_reviews" />
                                                            <input type="hidden" name="directory_id" value="<?php echo absint($post->ID);?>" />
                                                            <label><?php _e('Subject','directory');?></label>
                                                            <input type="text" id="reviews_title" name="reviews_title">
                                                            <input type="hidden" name="user_id" value="<?php echo cs_get_user_id();?>" />
                                                        </li>
                                                        <li>
                                                            <label><?php _e('Write Review','directory');?></label>
                                                            <textarea name="reviews_description" id="reviews_description"></textarea>
                                                            <input type="button" value="<?php _e('Add Review','directory');?>" class="cs-bgcolor" onclick="cs_reviews_submission('<?php echo admin_url('admin-ajax.php')?>', '<?php echo get_template_directory_uri()?>');">
                                                        </li>
                                                    </ul>
                                                </form>
                                            <?php 
                                            }
                                        } else {
                                            $cs_login_message= __('You must login to give Review','directory');
                                            cs_login_section($cs_login_message,'','cs-login-favorites');
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Model End-->
                        </div>
                    <?php 
                    }
                    ?>
                    <!--Reviews End-->
               
                </div>
            </div>
            <?php
			
            	if( $cs_related_ads_option == 'on' ){
                        if ( empty($cs_xmlObject->cs_related_post_title) ) 
                        $cs_related_post_title = __('Related Listing', 'directory'); else $cs_related_post_title = $cs_xmlObject->cs_related_post_title;
                        $custom_taxterms		= '';
                        $width					= '370';
                        $height					= '280';
                        $postname				= 'directory';
                        $cs_tags_name			= 'directory-tag';
                        $cs_categories_name		= 'directory-category';
                        $custom_taxterms = wp_get_object_terms( $post->ID, array($cs_categories_name, $cs_tags_name), array('fields' => 'ids') );
                        $args = array(
                            'post_type' => $postname,
                            'post_status' => 'publish',
                            'posts_per_page' => 4,
                            'orderby' => 'DESC',
                            'tax_query' => array(
                                'relation' => 'OR',
                                array(
                                    'taxonomy' => $cs_tags_name,
                                    'field' => 'id',
                                    'terms' => $custom_taxterms
                                ),
                                array(
                                    'taxonomy' => $cs_categories_name,
                                    'field' => 'id',
                                    'terms' => $custom_taxterms
                                )
                            ),
                            'post__not_in' => array ($post->ID),
                        );
                        $custom_query = new WP_Query($args);
                        if ( $custom_query->have_posts() <> "" ) {
                        ?>
                       	<div class="element-size-100">
                        	<div class="col-md-12">
                                <div class="cs-related-post grid_two_listing">
                                    <div class="cs-section-title">
                                          <h2><?php echo esc_attr( $cs_related_post_title ?  $cs_related_post_title : 'Related Listing' );?></h2>
                                      </div>
                                    <div class="row">
                                        <?php 
                                        $title_limit         = 5;
                                        while ($custom_query->have_posts()) : $custom_query->the_post();
                                            $cs_post_id = $post->ID;
                                            $cs_directory_featured             = get_post_meta($cs_post_id, "directory_featured", true);
                                            $dir_featured_till                 = get_post_meta($cs_post_id, "dir_featured_till", true);
                                            $cs_directory_featured    = 'no';
                                            if ( isset( $dir_featured_till ) && $dir_featured_till !='' ){
                                                $current_date = date("Y-m-d H:i:s");
                                                if( strtotime( $dir_featured_till ) > strtotime( $current_date ) ) {
                                                    $cs_directory_featured    = 'yes';
                                                }
                                            }
                                            
                                            $image_url = get_post_meta($cs_post_id, '_directory_image_gallery', true );
                                            
                                            $image_url = array_filter( explode( ',', $image_url ) );
                                            if ( isset( $image_url ) && ! empty( $image_url ) ) {
                                                $img_class = 'no-image';    
                                               $image_url= isset($image_url[0]) ? cs_attachment_image_src( $image_url[0] ,$width,$height) : '';
                                            } else {
                                                $img_class = '';
                                                $image_url = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
                                            }                         
                                            ?>
                                            <article class="col-md-3">
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
                                                                <h2 itemscope itemtype="http://schema.org/Thing"><a  itemprop="name" href="<?php echo esc_url(get_permalink($cs_post_id ));?>">
                                                                	<?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>

                                                                </a></h2>
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
                                                            <div class="cs-location-address">
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
                                        wp_reset_postdata();
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
}
?>
