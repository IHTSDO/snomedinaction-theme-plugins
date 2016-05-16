<?php

if(!class_exists('cs_directory_options'))
{
    class cs_directory_options
    {
		public function __construct(){
			add_action('wp_ajax_cs_add_package_to_list', array(&$this, 'cs_add_package_to_list'));
		}
		
		//======================================================================
		// Settings Menu Function
		//======================================================================
		public function cs_register_directory_types_menu_page(){
			//add submenu page
			add_submenu_page('edit.php?post_type=directory', 'Directory Settings ', 'Directory Settings', 'manage_options', 'cs_directory_settings', array(&$this, 'cs_directory_settings'));
		}
		
		//======================================================================
		// Register Locations
		//======================================================================
		public function cs_register_locations(){
			//add submenu page
			add_submenu_page('edit.php?post_type=directory', 'Locations ', 'Locations', 'manage_options', 'cs_locations_settings', array(&$this, 'cs_locations_settings'));
		}
		
		//======================================================================
		// Location Listing
		//======================================================================
		public function cs_locations_settings()
		{
			global $wp;
			
			$cs_location_countries	= get_option('cs_location_countries');
			$cs_countries_list	= '';
			$cs_current_countries	= isset( $cs_location_countries ) && $cs_location_countries !='' ? $cs_location_countries : '';
			
			if(  isset( $cs_current_countries ) && $cs_current_countries !='' ) {
				foreach( $cs_current_countries as $key => $value ) {
					$cs_countries_list	.= '<option value="'.$key.'">'.$value['name'].'</option>';
				}
			}
			
			$output	= '';
			$output.='<ul class="form-elements" id="locations_wrap" data-themeurl="'.get_template_directory_uri().'" data-plugin_url="'.wp_directory::plugin_url().'" >
						<li class="locations_menu">
							<ul class="locations_menu_tabs">
								<li class="add_country"><a href="javascript:_createpop(\'cs_add_country\',\'filter\')" class="button">'.__('Add Country','directory').'</a> </li>
								<li class="add_states"><a href="javascript:_createpop(\'cs_add_states\',\'filter\')" class="button">'.__('Add State','directory').'</a> </li>
								<li class="add_cities"><a href="javascript:_createpop(\'cs_add_cities\',\'filter\')" class="button">'.__('Add City','directory').'</a> </li>
							</ul>
						</li>
						<li>
							<div class="location-listing">
								
								<table id="locations" class="display" cellspacing="0" width="100%">
								  <thead>
								  <tr>
									<th style="width:10%;" scope="col">#</th>
									<th style="width:25%;" scope="col">'.__('Name','directory').'</th>
									<th style="width:20%;" scope="col">'.__('Type','directory').'</th>
									<th style="width:25%;" scope="col">'.__('Parent','directory').'</th>
									<th style="width:20%;" scope="col">'.__('Action','directory').'</th>
								  </thead>
								  <tbody>';
									
								 $cs_location_countries	= get_option('cs_location_countries');
								 $cs_location_states	= get_option('cs_location_states');
								 $cs_location_cities	= get_option('cs_location_cities');
								 
								function find_parent($country='',$state='', $type='') {
									$cs_location_countries	= get_option('cs_location_countries');
									$cs_location_states		= get_option('cs_location_states');
									if( isset( $type ) && $type !='' ) {
										if( $type == 'country' ){
											return $cs_location_countries[$country]['name'];
										} else if( $type == 'state' ){
											return $cs_location_states[$country][$state]['name'];
										}
									}
								 }
								
								if ( ! function_exists( 'cs_edit_country' ) ) {
										function cs_edit_country($id='',$name='',$isocode_2='',$isocode_3='',$key=''){
										ob_start();
									?>
									<div  id="edit_country_form<?php echo esc_attr($id);?>" style="display: none;" class="table-form-elem edit-wrap">
										  <div class="cs-heading-area">
											<h5 style="text-align: left;"><?php _e( 'Edit Country', 'directory' ); ?></h5>
											<span onclick="javascript:removeoverlay('edit_country_form<?php echo esc_js($id)?>','append')" class="cs-btnclose"> <i class="icon-times"></i></span>
											<div class="clear"></div>
										  </div>
										<div class="message-wrap" style="display:none">
											<div class="cs-message updated"></div>
										</div>
										
										<ul class="form-elements">
										  <li class="to-label"><?php _e( 'Country Name', 'directory' ); ?></li>
										  <li class="to-field">
											<input type="text" name="cs_update_countries_<?php echo esc_attr($id);?>" id="cs_update_countries_<?php echo esc_attr($id);?>" value="<?php echo esc_attr( $name ); ?>" />
											
										  </li>
										</ul>
										<ul class="form-elements">
                                          <li class="to-label"><?php _e( 'ISO CODE2', 'directory' ); ?>             
                                          </li>
                                          <li class="to-field">
                                            <input type="text" name="cs_countries_code_2_<?php echo esc_attr($id);?>" id="cs_countries_code_2_<?php echo esc_attr($id);?>" value="<?php echo esc_attr( $isocode_2 ); ?>" />
                                            <p><?php _e( 'ISO CODE2 as:"US"', 'directory' ); ?> </p>
                                          </li>
                                        </ul>
                                        <ul class="form-elements">
                                          <li class="to-label">
										  
										  <?php printf(__( 'ISO CODE3 as:%s', 'directory' ), '"USA"'); ?>
										                                     
                                          </li>
                                          <li class="to-field">
                                            <input type="text" name="cs_countries_code_3_<?php echo esc_attr($id);?>" id="cs_countries_code_3_<?php echo esc_attr($id);?>" value="<?php echo esc_attr( $isocode_3 ); ?>" />
                                            <p>
											<?php printf(__( 'ISO CODE3 as:%s', 'directory' ), '"USA"'); ?>
											
											 </p>
                                          </li>
                                        </ul>  
                                         <input type="hidden" name="cs_country_id_<?php echo esc_attr($id);?>" value="<?php echo esc_attr( $key ); ?>" id="cs_country_id_<?php echo esc_attr($id);?>" />
										<ul class="form-elements noborder">
										  <li class="to-label"></li>
										  <li class="to-field">
											<input type="button" class="ajax_update_country" id="<?php echo esc_attr($id);?>" data-type="country" value="Update Country"  />
											<div class="loading"></div>
										  </li>
										</ul>
									</div>
								<?php
									 $post_data = ob_get_clean();
									 return $post_data;
									}
								}
								
								if ( ! function_exists( 'cs_edit_state' ) ) {
									function cs_edit_state($id='',$selected_key='',$state='',$state_key=''){
									ob_start();
								?>
								<div  id="edit_state_form<?php echo esc_attr($id);?>" style="display: none;" class="table-form-elem edit-wrap">
									  <div class="cs-heading-area">
										<h5 style="text-align: left;"><?php _e( 'Edit State', 'directory' ); ?></h5>
										<span onclick="javascript:removeoverlay('edit_state_form<?php echo esc_js($id)?>','append')" class="cs-btnclose"> <i class="icon-times"></i></span>
										<div class="clear"></div>
									  </div>
									<div class="message-wrap" style="display:none">
										<div class="cs-message updated"></div>
									</div>
									<ul class="form-elements">
									  <li class="to-label"><?php _e( 'Selected Country', 'directory' ); ?> </li>
									  <li class="to-field">
										<select name="cs_update_select_country" id="cs_update_select_country_<?php echo esc_attr($id);?>" class="cs-loc-disables" disabled="disabled" >
											<option value=""><?php _e( 'Select Country', 'directory' ); ?></option>
												<?php 
												$cs_location_countries	= get_option('cs_location_countries');
												$cs_countries_list		= '';
												$cs_current_countries	= isset( $cs_location_countries ) && $cs_location_countries !='' ? $cs_location_countries : '';
												
												if(  isset( $cs_current_countries ) && $cs_current_countries !='' ) {
													foreach( $cs_current_countries as $key => $value ) {
														$selected	= '';
														if(  trim($key) == trim($selected_key) ){
															$selected	= 'selected';
														}
														
													   echo '<option value="'.$key.'" '.$selected.'>'.$value['name'].'</option>';
													}
												}
											?>
											</select>
									  </li>
									</ul>
									<ul class="form-elements">
									  <li class="to-label"><?php _e( 'State Name', 'directory' ); ?> </li>
									  <li class="to-field">
										<textarea name="cs_update_states_<?php echo esc_attr($id);?>" id="cs_update_states_<?php echo esc_attr($id);?>" rows="15" cols="30"><?php echo esc_attr($state);?></textarea>
										<input type="hidden" name="cs_state_id_<?php echo esc_attr($id);?>" value="<?php echo esc_attr( $state_key ); ?>" id="cs_state_id_<?php echo esc_attr($id);?>" />
									  </li>
									</ul>   


									<ul class="form-elements noborder">
									  <li class="to-label"></li>
									  <li class="to-field">
										<input type="button" id="<?php echo esc_attr($id);?>" class="ajax_update_state" value="Update State"  />
										<div class="loading"></div>
									  </li>
									</ul>
								</div>
							<?php
								 $post_data = ob_get_clean();
								 return $post_data;
								}
								}
								if ( ! function_exists( 'cs_edit_city' ) ) {
									function cs_edit_city($id='',$country_key='',$state='',$city='',$city_key=''){
										ob_start();
									?>
									<div  id="edit_city_form<?php echo esc_attr($id);?>" style="display: none;" class="table-form-elem edit-wrap">
										  <div class="cs-heading-area">
											<h5 style="text-align: left;"><?php _e( 'Edit City', 'directory' ); ?></h5>
											<span onclick="javascript:removeoverlay('edit_city_form<?php echo esc_js($id)?>','append')" class="cs-btnclose"> <i class="icon-times"></i></span>
											<div class="clear"></div>
										  </div>
										<div class="message-wrap" style="display:none">
											<div class="cs-message updated"></div>
										</div>
										<ul class="form-elements">
										  <li class="to-label"><?php _e( 'Selected Country', 'directory' ); ?> </li>
										  <li class="to-field">
											<select name="cs_update_select_country" class="cs-loc-disables" id="cs_update_country_<?php echo esc_attr($id);?>" disabled="disabled" >
												<option value=""><?php _e( 'Selected Country', 'directory' ); ?> </option>
													<?php 
														$cs_location_countries	= get_option('cs_location_countries');
														$cs_countries_list	= '';
														$cs_current_countries	= isset( $cs_location_countries ) && $cs_location_countries !='' ? $cs_location_countries : '';
														
														if(  isset( $cs_current_countries ) && $cs_current_countries !='' ) {
															foreach( $cs_current_countries as $key => $value ) {
																$selected	= '';
																if(  trim($key) == trim($country_key) ){
																	$selected	= 'selected';
																}
																
															   echo '<option value="'.$key.'" '.$selected.'>'.$value['name'].'</option>';
															}
														}
													?>
												</select>
										  </li>
										</ul>
										<ul class="form-elements">
										  <li class="to-label"><?php _e( 'Selected State', 'directory' ); ?> 
											
										  </li>
										  <li class="to-field">
											<span class="loader-states" style="display:none"></span>
											<select name="cs_update_elect_states" class="cs-loc-disables" id="cs_update_states_<?php echo esc_attr($id);?>" disabled="disabled" >
												<option value=""><?php _e( 'Selected State', 'directory' ); ?> </option>
												<?php 
													$cs_location_states	= get_option('cs_location_states');
													$cs_states_list	= '';
													$cs_location_states	= isset( $cs_location_states[$country_key] ) && $cs_location_states[$country_key] !='' ? $cs_location_states[$country_key] : '';
													
													if(  isset( $cs_location_states ) && $cs_location_states !='' ) {
														foreach( $cs_location_states as $key => $value ) {
															if( $key !='no-state' ) {
																$selected	= '';
																if(  trim($key) == trim($state) ){
																	$selected	= 'selected';
																}
																
																echo '<option value="'.$key.'" '.$selected.'>'.$value['name'].'</option>';
															}
														}
													}
												?>
											</select>
										  </li>
										</ul>
										<ul class="form-elements">
										  <li class="to-label"><?php _e( 'City Name', 'directory' ); ?></li>
										  <li class="to-field">
											<textarea name="cs_update_cities_<?php echo esc_attr($id);?>" id="cs_update_cities_<?php echo esc_attr($id);?>" rows="15" cols="30"> <?php echo esc_attr($city);?></textarea>
											<input type="hidden" name="cs_city_id_<?php echo esc_attr($id);?>" value="<?php echo esc_attr( $city_key ); ?>" id="cs_city_id_<?php echo esc_attr($id);?>" />
											<p><?php _e( 'Put One or More Cities name as : "," comma separated like Newyork,Dallas,idaho', 'directory' ); ?> </p>
										  </li>
										</ul>   
										<ul class="form-elements noborder">
										  <li class="to-label"></li>
										  <li class="to-field">
											<input type="button" class="ajax_update_city" id="<?php echo esc_attr($id);?>" value="Update City"  />
											<div class="loading"></div>
										  </li>
										</ul>
									</div>
								<?php
									 $post_data = ob_get_clean();
									 return $post_data;
									}
								}
							
								$countries_list	= $cs_location_countries;
								$states_list	= $cs_location_states;
								$cities_list	= $cs_location_cities;
								
								if( isset( $countries_list ) && $countries_list !='' ) {
									 $counter	= 0;
									 $counter_country	= 0;
									 $counter_state		= 0;
									 $counter_city		= 0; 
									 foreach( $countries_list as $key => $country ) {
										$counter++;
										$counter_country++;
										$output.='<tr  id="'.$counter.'" class="country_'.$counter_country.'">';
										$output.='<td  style="width:10%;">'.$counter.'</td>';
										$output.='<td  style="width:25%;">|__'.stripslashes($country['name']).'</td>';
										$output.='<td  style="width:20%;">Country</td>';
										$output.='<td  style="width:25%;">None</td>';
										$output.='<td  style="width:20%;">
													<a href="javascript:_createpop(\'edit_country_form'.$counter.'\',\'filter\')" class="actions edit"><i class="icon-edit3"></i></a> 
													<a href="javascript:;" class="delete-it btndeleteit_node actions delete" data-type="country" id="'.$counter_country.'" data-node="'.$country['id'].'"><i class="icon-cross5"></i></a>
													'.cs_edit_country($counter,stripslashes($country['name']),$country['isocode2'],$country['isocode3'],$country['id']).'
													</td>
											</td>';
										$output.='</tr>';
	
										if( isset( $states_list[$country['id']] ) ) {
											$states	= $states_list[$country['id']];
	
											if( isset( $states ) && $states !='' && is_array($states) ) {
												foreach( $states as $key => $state ) {
													$counter++;
													$counter_state++;
													$state_key	= $key;
													if( isset( $key ) && $key !='no-state' ) {
														
														$output.='<tr  id="'.$counter.'" class="state_'.$counter_state.' country_'.$counter_country.'">';
														$output.='<td  style="width:10%;">'.$counter.'</td>';
														$output.='<td  style="width:25%;">&nbsp;&nbsp;|__'.stripslashes($state['name']).'</td>';
														$output.='<td  style="width:20%;">State</td>';
														$output.='<td  style="width:25%;">'.$country['name'].'</td>';
														$output.='<td  style="width:20%;">
																	<a href="javascript:_createpop(\'edit_state_form'.$counter.'\',\'filter\')" class="actions edit"><i class="icon-edit3"></i></a> 
																	<a href="javascript:;" class="delete-it btndeleteit_node actions delete" id="'.$counter_state.'"  data-type="state" data-node="'.$key.'" data-country_node="'.$country['id'].'"><i class="icon-cross5"></i></a>
																	'.cs_edit_state($counter,$state['country_id'],stripslashes($state['name']),$key).'
																	</td>
														</td>';
														$output.='</tr>';
													}
													
													
													if( isset( $cities_list[$country['id']][$key] ) ) {
														$cities	= $cities_list[$country['id']][$key];
														if( isset( $cities ) && $cities !='' && is_array($cities) ) {
															foreach( $cities as $key => $city ) {
																$counter++;
																$counter_city++;
																
																$parent = $state['name'];
																if( isset( $state_key ) && $state_key == 'no-state' ) {
																	$parent = $country['name'];
																}
																
																$output.='<tr  id="'.$counter.'" class="city_'.$counter_city.' state_'.$counter_state.' country_'.$counter_country.'">';
																$output.='<td  style="width:10%;">'.$counter.'</td>';
																$output.='<td  style="width:25%;">&nbsp;&nbsp;&nbsp;&nbsp;|__'.stripslashes($city['name']).'</td>';
																$output.='<td  style="width:20%;">City</td>';
																$output.='<td  style="width:25%;">'.$parent.'</td>';
																$output.='<td  style="width:20%;">
																			<a href="javascript:_createpop(\'edit_city_form'.$counter.'\',\'filter\')" class="actions edit"><i class="icon-edit3"></i></a> 
																			<a href="javascript:;" class="delete-it btndeleteit_node actions delete" id="'.$counter_city.'" data-type="city" data-node="'.$key.'" data-country_node="'.$country['id'].'"  data-state_node="'.$state_key.'"><i class="icon-cross5"></i></a>
																			'.cs_edit_city($counter,$state['country_id'],$state_key,stripslashes($city['name']),$key).'
																			</td>
																</td>';
																$output.='</tr>';
															}
														}
													}
												}
											}
										}
									 }
								  }
	
								$output.='<tbody></table>
							</div>
						</li>
						<li>
						<div id="cs_add_country" class="main-wrap" style="display: none;">
							<div class="cs-heading-area">
							  <h5> <i class="icon-plus-circle"></i>Add Country</h5>
							  <span class="cs-btnclose" onClick="javascript:removeoverlay(\'cs_add_country\',\'append\')"> <i class="icon-times"></i></span> 
							</div>
							<div class="message-wrap" style="display:none">
								<div class="cs-message updated"></div>
							</div>
							<ul class="form-elements">
							  <li class="to-label">'.__('Country Names','directory').' </li>
							  <li class="to-field">
								<textarea name="cs_countries" id="cs_countries" rows="15" cols="30"></textarea>
								<p>'.__('Put One or More Country name as Country Name(ISO CODE2)(ISO CODE3):"," comma seprated like Australia(AU)(AUS),United States of America(US)(USA)','directory').'</p>
							  </li>
							</ul> 
							<ul class="form-elements noborder">
							  <li class="to-label"></li>
							  <li class="to-field">
								<input type="button" id="ajax_add_country" value="Add Country"  />
								<div class="loading"></div>
							  </li>
							</ul>
					  </div>
					  
					  <div id="cs_add_states" class="main-wrap" style="display: none;">
							<div class="cs-heading-area">
							  <h5> <i class="icon-plus-circle"></i>
							  '.__('Add State','directory').'
							 </h5>
							  <span class="cs-btnclose" onClick="javascript:removeoverlay(\'cs_add_states\',\'append\')"> <i class="icon-times"></i></span> 
							</div>
							<div class="message-wrap" style="display:none">
								<div class="cs-message updated"></div>
							</div>
							<ul class="form-elements">
							  <li class="to-label"> '.__('Select Country','directory').'
								
							  </li>
							  <li class="to-field">
								<select name="cs_select_country" id="cs_select_country" >
									<option value="">'.__('Select Country','directory').'</option>'
									.$cs_countries_list.	
								'</select>
							  </li>
							</ul>
							<ul class="form-elements">
							  <li class="to-label">'.__('State Names','directory').'
								
							  </li>
							  <li class="to-field">
								<textarea name="cs_states" id="cs_states" rows="15" cols="30"></textarea>
								<p>'.__('Put One or More States name as:\",\"comma separated like New york,Dallas,idaho','directory').' </p>
							  </li>
							</ul>   
							<ul class="form-elements noborder">
							  <li class="to-label"></li>
							  <li class="to-field">
								<input type="button" id="ajax_add_state" value="Add State"  />
								<div class="loading"></div>
							  </li>
							</ul>
					  </div>
					  
					  <div id="cs_add_cities" class="main-wrap" style="display: none;">
							<div class="cs-heading-area">
							  <h5> <i class="icon-plus-circle"></i>Add City</h5>
							  <span class="cs-btnclose" onClick="javascript:removeoverlay(\'cs_add_cities\',\'append\')"> <i class="icon-times"></i></span> 
							</div>
							<div class="message-wrap" style="display:none">
								<div class="cs-message updated"></div>
							</div>
							<ul class="form-elements">
							  <li class="to-label">'.__('Select Country','directory').'
								
							  </li>
							  <li class="to-field">
								<select name="cs_city_select_country" id="cs_city_select_country" >
									<option value="">'.__('Select Country','directory').'</option>'
									.$cs_countries_list.	
								'</select>
							  </li>
							</ul>
							<ul class="form-elements">
							  <li class="to-label">'.__('Select State','directory').'
								
							  </li>
							  <li class="to-field">
								<span class="loader-states"></span>
								<select name="cs_select_states" id="cs_select_states" >
									<option value="">'.__('Select State','directory').'</option>
								</select>
							  </li>
							</ul>
							<ul class="form-elements">
							  <li class="to-label">'.__('City Names','directory').'
								
							  </li>
							  <li class="to-field">
								<textarea name="cs_cities" id="cs_cities" rows="15" cols="30"></textarea>
								<p>'.__('Put One or More Cities name as:\",\"comma separated like New york,Dallas,idaho','directory').'</p>
							  </li>
							</ul>   
							<ul class="form-elements noborder">
							  <li class="to-label"></li>
							  <li class="to-field">
								<input type="button" id="ajax_add_city" value="Add City"  />
								<div class="loading"></div>
							  </li>
							</ul>
					  </div>
					
					</li>
				';
			 $output.='</ul>';
			
				echo $output;
		  }
			 
		//======================================================================
		// Directory Menu Function
		//======================================================================
		public function cs_directory_settings()
		{
			global $wp;
			$url = admin_url('edit.php?post_type=directory&page=cs_directory_settings');
			
			if(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] <> ''){
				$sort_by = $_REQUEST['sort_by'];
			} else {
				$sort_by = '';
			}
			if(isset($_REQUEST['action']) && $_REQUEST['action'] <> ''){
				$action = $_REQUEST['action'];
			} else {
				$action = 'packages';
			}
			if(isset($_POST['submit']) && isset($_POST['dynamic_directory_package']) && $_POST['dynamic_directory_package'] == 1){
				$this->cs_package_options_save();
			}
			?>
			<div class="report-table-sec">
				<!-- Nav tabs -->
				<ul class="reports-tabs" role="tablisttt"> 
					<li <?php if($action == 'packages'){echo 'class="active"';}?>>
                    	<a href="<?php echo cs_allow_special_char($url.'&amp;action=packages');?>">
				  			<?php _e('Packages','directory'); ?>
                        </a>
                  	</li>
				  <li  <?php if($action == 'payment_methods'){echo 'class="active"';}?>><a href="<?php echo cs_allow_special_char($url.'&amp;action=payments');?>">
                  	<?php _e('Payment','directory'); ?></a>
                  </li>
				 
				</ul>
                <div class="tab-content reports-content">
                <?php
					if($action == 'packages'){
						$this->cs_packages_section();
					}elseif($action == 'payments'){
						$this->cs_payment_section();
						//include "user_directory_payments.php";
					}
                ?>
                </div>
           </div>
		<?php
		}
		public function cs_packages_section(){
			global $post, $package_id, $counter_package, $package_title, $package_price, $package_duration, $package_no_ads, $package_featured_ads, $cs_theme_options;
			$cs_packages_options  = get_option('cs_packages_options');
			$currency_sign = isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';
			
			$cs_free_package_switch  = get_option('cs_free_package_switch');
			$cd_checked	= '';
			if ( isset( $cs_free_package_switch ) && $cs_free_package_switch == 'on' ) {
				$cd_checked	= 'checked'; 
			}
			
			?>
            <form name="dir-package" method="post" action="">
			<input type="hidden" name="dynamic_directory_package" value="1" />
			<script>
                jQuery(document).ready(function($) {
                    $("#total_packages").sortable({
                        cancel : 'td div.table-form-elem'
                    });
                });
             </script>
              <ul class="form-elements">
                    <li class="to-label"><?php _e('Unlimited – Free Package On/Off','directory');?></li>
                    <li class="to-field">
                    	<input type="hidden" name="cs_free_package_switch" value="" />
						<label class="pbwp-checkbox"><input type="checkbox" value="on" name="cs_free_package_switch" id="cs_free_package_switch" class="cs-form-checkbox cs-input" <?php echo esc_attr( $cd_checked ) ;?>><span class="pbwp-box"></span></label>
                    </li>
               </ul>
              <ul class="form-elements">
                    <li class="to-label"><?php _e('Add Package','directory');?></li>
                    <li class="to-button"><a href="javascript:_createpop('add_package_title','filter')" class="button"><?php _e('Add Package','directory');?></a> </li>
               </ul>
              <div class="cs-list-table">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th style="width:80%;"><?php _e('Title','directory');?></th>
                    <th style="width:80%;" class="centr"><?php _e('Actions','directory');?></th>
                    <th style="width:0%;" class="centr"></th>
                  </tr>
                </thead>
                <tbody id="total_packages">
                  <?php
					if(isset($cs_packages_options) && is_array($cs_packages_options) && count($cs_packages_options)>0){
						foreach($cs_packages_options as $package_key=>$package){
							if(isset($package_key) && $package_key <> ''){
								$counter_package = $package_id = isset($package['package_id']) ? $package['package_id'] : '';
								$package_title			= isset($package['package_title']) ? $package['package_title'] : '';
								$package_price			= isset($package['package_price']) ? $package['package_price'] : '';
 								$package_duration		= isset($package['package_duration']) ? $package['package_duration'] : '';
								$package_featured_ads	= isset($package['package_featured_ads']) ? $package['package_featured_ads'] : '';
								$this->cs_add_package_to_list();
							}
						}
					}
                 ?>
                </tbody>
              </table>
              <input type="submit" class="button" name="submit" value="<?php _e('Save','directory');?>" />
                
              </div>
              </form>
              <div id="add_package_title" style="display: none;">
                <div class="cs-heading-area">
                  <h5> <i class="icon-plus-circle"></i> <?php _e('Package Settings','directory');?> </h5>
                  <span class="cs-btnclose" onClick="javascript:removeoverlay('add_package_title','append')"> <i class="icon-times"></i></span> </div>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Title','directory');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" id="package_title" name="package_title" value="<?php _e('Title','directory');?>" />
                  </li>
                </ul>   
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php printf(__('Price %s','directory'), $currency_sign);?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" id="package_price" name="package_price" value="" />
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('No of days','directory');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" id="package_duration" name="package_duration" value="" />
                  </li>
                </ul>
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                    <input type="button" value="<?php _e('Add Package to List','directory');?>" onClick="add_package_to_list('<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>')" />
                  </li>
                </ul>
              </div>
            <?php
		}
		public function cs_payment_section(){
			global $post, $current_user, $cs_theme_options,$gateways;
			$_GET['page_id_all'] = isset($_GET['page_id_all']) ? $_GET['page_id_all'] : 1;
			
			$uid = $current_user->ID;
			
			$currency_sign = isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';
		?>
			<div class="cs-section-title">
				<h2><?php _e('Payments','directory');?></h2>
			</div>
			<div class="my-ads has-border">
			<?php
				$argsss = array(
							'posts_per_page'			=> "-1",
							'post_type'					=> 'directory',
							'post_status'				=> array('publish', 'private'),
							'orderby'					=> 'ID',
							'order'						=> 'DESC',
						);
				 $custom_query_count = new WP_Query($argsss);
				 $count_post = $custom_query_count->post_count;
				 $args = array(
							'posts_per_page'			=> get_option('posts_per_page'),
							'paged'						=> $_GET['page_id_all'],
							'post_type'					=> 'directory',
							'post_status'				=> array('publish', 'private'),
							'orderby'					=> 'ID',
							'order'						=> 'DESC',
						);
				 $custom_query = new WP_Query($args);
				 if ( $custom_query->have_posts() <> "" ) {
					 $cs_dir_trans = get_option('cs_directory_transaction_meta', true);
					 ?>
					 <div class="directory-package">
						<table>
							<thead>
								<tr>
									<th>#</th>
									<th><?php _e('Name','directory');?></th>
									<th><?php _e('Package','directory');?></th>
									<th><?php _e('Duration in days','directory');?></th>
                                    <th><?php _e('Transection Detail','directory');?></th>
								</tr>
							</thead>
							<tbody>
					  <?php
					  $subs_counter = 1;
					  while ( $custom_query->have_posts() ): $custom_query->the_post();
					  if (isset($post->ID) &&  has_post_thumbnail( $post->ID ) ) {
						$thumb_id = get_post_thumbnail_id($post->ID);
					  } else {
							$thumb_id = '';  
					  }
					  $_pakage_transaction_meta	= get_post_meta($post->ID, "dir_pakage_transaction_meta", true);
					  $pakage_subs_meta			= get_post_meta($post->ID, "dir_pakage_trans_subsription_meta", true);
					  $package_meta 			= get_post_meta($post->ID, "_pakage_meta", true);
					  $dir_payment_date 		= get_post_meta($post->ID, "dir_payment_date", true);
					  $object	= new CS_PAYMENTS();
						if ( isset($package_meta) && is_array($package_meta) && count($package_meta)>0  && $current_user->ID == $uid ) {
							$counter = 0;
							$pakage_expire_date = get_post_meta($post->ID, "dir_pkg_expire_date", true);
							$package_id = $package_meta['package_id'];
							$package_title = $package_meta['package_title'];
							$package_price = $package_meta['package_price'];
							$package_duration = $package_meta['package_duration'];
							$transection_status = isset( $package_meta['transection_status'] ) ? $package_meta['transection_status'] : '';
							$transection_id 	= isset( $package_meta['transection_id'] ) ? $package_meta['transection_id'] : '';
							$payment_gateway 	= isset( $package_meta['payment_gateway'] ) ? $gateways[strtoupper($package_meta['payment_gateway'])] : '';
							$trans_data	= get_post_meta((int)$post->ID, 'dir_pakage_transaction_meta', false);
							
							$counter++;
							?>
								<tr>
									<td><?php echo absint($subs_counter);?></td>
									<td><a href="<?php esc_url(the_permalink());?>"><?php echo get_the_title();?></a></td>
									<td><?php echo esc_attr($package_title);?></td>
									<td><?php echo esc_attr($package_duration);?></td>
                                    <td>
										<?php
										if( is_array($trans_data) && sizeof($trans_data) > 0 ) {
										?>
                                        <a id="cs-trans-detail-<?php echo absint($subs_counter);?>" data-id="<?php echo absint($subs_counter);?>" class="cs-trans-detail"><?php _e('Detail','directory'); ?></a>
                                        <?php
										}
										?>
                                    </td>
								</tr>
                                <?php
								if( is_array($trans_data) && sizeof($trans_data) > 0 ) {
								?>
                                <tr id="cs-con-detail-<?php echo absint($subs_counter);?>">
                                	<td colspan="5">
                                    	<table>
                                            <thead>
                                                <tr>
                                                	<th class="odd"><?php _e('Transaction id','directory');?></th>
                                                    <th class="even"><?php _e('Payment Gateway','directory');?></th>
                                                    <th class="even"><?php _e('Name','directory');?></th>
                                                    <th class="odd"><?php _e('Date','directory');?></th>
                                                    <th class="even"><?php _e('Email','directory');?></th>
                                                    <th class="odd"><?php _e('Amount','directory');?></th>
                                                    <th class="even"><?php _e('Address','directory');?></th>
                                                    <th class="odd"><?php _e('Payment Status','directory');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
                                                $trans_counter = 0;
												if( isset($trans_data[0]) ) {
													$trans_data[0] = array_reverse($trans_data[0], true);
													foreach($trans_data[0] as $trans => $trans_value) {
														$class			= ($counter and $counter%2 == 0) ? 'even' : 'odd';
														$first_name		= isset($trans_value['first_name']) ? $trans_value['first_name'] : '';
														$last_name		= isset($trans_value['last_name']) ? $trans_value['last_name'] : '';
														$payment_date	= isset($trans_value['payment_date']) ? $trans_value['payment_date'] : '';
														$payer_email	= isset($trans_value['payer_email']) ? $trans_value['payer_email'] : '';
														$payment_gross	= isset($trans_value['payment_gross']) ? $trans_value['payment_gross'] : '';
														$full_address	= isset($trans_value['full_address']) ? $trans_value['full_address'] : '';
														$txn_id			= isset($trans_value['txn_id']) ? $trans_value['txn_id'] : '';
														$trans_status	= isset($trans_value['transaction_status']) ? $trans_value['transaction_status'] : '';
														$payment_method	= isset($trans_value['payment_method']) ? $gateways[strtoupper($trans_value['payment_method'])] : '';
													?>
														<tr class="<?php echo sanitize_html_class($class);?>">
															<td><?php echo esc_attr($txn_id); ?></td>
                                                            <td><?php echo esc_attr($payment_method); ?></td>
															<td><?php echo esc_attr($first_name) . ' ' . esc_attr($last_name); ?></td>
															<td><?php echo date_i18n('F j, Y, H:ia', strtotime($payment_date)); ?></td>
															<td><?php echo sanitize_email($payer_email); ?></td>
															<td><?php echo esc_attr($currency_sign.$payment_gross); ?></td>
															<td><?php echo esc_textarea($full_address); ?></td>
                                                            <td><?php echo esc_attr($trans_status); ?></td>
														</tr>
													<?php
														$trans_counter++;
													}
												}
												?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
								<?php
								}
							$subs_counter++;
							}
							
						endwhile;
						?>
						</tbody>
					</table>
					</div>
						<?php
						 $qrystr = '';
						 if ( $count_post > get_option('posts_per_page')) {
								if ( isset($_GET['page_id']) ) { $qrystr .= "&page_id=".$cs_page_id;}
								if ( isset($_GET['action']) ) $qrystr .= "&action=".$_GET['action'];
								if ( isset($_GET['post_type']) ) $qrystr .= "&post_type=".$_GET['post_type'];
								if ( isset($_GET['page']) ) $qrystr .= "&page=".$_GET['page'];
								//if ( isset($uid) ) $qrystr .= "&uid=".$uid;
								echo cs_pagination($count_post, get_option('posts_per_page'), $qrystr);
						 }
					} else {
						echo '<h4>'.__('No Result Found','directory').'</h4>';
					}
					?>
			</div>
		<?php
		}
		public function cs_add_package_to_list(){
			global $counter_package, $package_id, $package_title, $package_price, $package_duration, $package_featured_ads, $cs_theme_options;
			foreach ($_POST as $keys=>$values) {
				$$keys = $values;
			}
			if(isset($_POST['package_title']) && $_POST['package_title'] <> ''){
				$package_id = time();
			}
			if(empty($package_id)){
				$package_id = $counter_package;
			}
			$currency_sign = isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';
			?>
            <tr class="parentdelete" id="edit_track<?php echo esc_attr($counter_package)?>">
              <td id="subject-title<?php echo esc_attr($counter_package)?>" style="width:80%;"><?php echo esc_attr($package_title);?></td>
              <td class="centr" style="width:20%;"><a href="javascript:_createpop('edit_track_form<?php echo esc_js($counter_package)?>','filter')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
              <td style="width:0"><div  id="edit_track_form<?php echo esc_attr($counter_package);?>" style="display: none;" class="table-form-elem">
              	  <input type="hidden" name="package_id_array[]" value="<?php echo absint($package_id);?>" />
                  <div class="cs-heading-area">
                    <h5 style="text-align: left;"> <?php _e('Package Settings','directory');?></h5>
                    <span onclick="javascript:removeoverlay('edit_track_form<?php echo esc_js($counter_package)?>','append')" class="cs-btnclose"> <i class="icon-times"></i></span>
                    <div class="clear"></div>
                  </div>
                  <ul class="form-elements">
                    <li class="to-label">
                      <label><?php _e('Package Title','directory');?></label>
                    </li>
                    <li class="to-field">
                      <input type="text" name="package_title_array[]" value="<?php echo htmlspecialchars($package_title)?>" id="package_title<?php echo esc_attr($counter_package)?>" />
                    </li>
                  </ul>
                </ul>    
                    <ul class="form-elements">
                      <li class="to-label">
                        <label><?php printf(__('Price %s','directory'), $currency_sign);?></label>
                      </li>
                      <li class="to-field">
                        <input type="text" id="package_price<?php echo esc_attr($counter_package)?>" name="package_price_array[]" value="<?php if(isset($package_price))echo esc_attr($package_price);?>" />
                      </li>
                    </ul>
                    <ul class="form-elements">
                      <li class="to-label">
                        <label><?php _e('No of days','directory');?></label>
                      </li>
                      <li class="to-field">
                        <input type="text" id="package_duration<?php echo esc_attr($counter_package)?>" name="package_duration_array[]" value="<?php if(isset($package_duration))echo esc_attr($package_duration);?>" />
                      </li>
                    </ul>
                  <ul class="form-elements noborder">
                    <li class="to-label">
                      <label></label>
                    </li>
                    <li class="to-field">
                      <input type="button" value="<?php _e('Update Package','directory');?>" onclick="update_title(<?php echo esc_js($counter_package);?>); removeoverlay('edit_track_form<?php echo esc_js($counter_package);?>','append')" />
                    </li>
                  </ul>
                </div></td>
            </tr>
			<?php
			if ( isset($_POST['package_title']) && isset($_POST['cs_add_package_to_list']) ) die();
	}
	
	public function cs_package_options_save(){
			if(isset($_POST['submit']) && isset($_POST['dynamic_directory_package']) && $_POST['dynamic_directory_package'] == 1){
				$package_counter = 0;
				$package_array = $packages = array();
				
				if ( isset( $_POST['package_id_array'] ) && ! empty( $_POST['package_id_array'] ) ) {
					foreach($_POST['package_id_array'] as $keys=>$values){
						if($values){
							$package_array['package_id'] = $_POST['package_id_array'][$package_counter];
							$package_array['package_title'] = $_POST['package_title_array'][$package_counter];
							$package_array['package_price'] 		= $_POST['package_price_array'][$package_counter];
							$package_array['package_duration'] 		= $_POST['package_duration_array'][$package_counter];
							$packages[$values] = $package_array;
							$package_counter++;
						}
					}
				}
				
				update_option( 'cs_packages_options', $packages );
				
				$_POST['cs_free_package_switch']	=  $_POST['cs_free_package_switch'] ? $_POST['cs_free_package_switch'] : '';
				update_option( 'cs_free_package_switch', $_POST['cs_free_package_switch'] );
				
			}
	}
	
  } //End Class
}
if(class_exists('cs_directory_options')){
	$settings_object = new cs_directory_options();
	add_action('admin_menu', array(&$settings_object, 'cs_register_directory_types_menu_page'));
	add_action('admin_menu', array(&$settings_object, 'cs_register_locations'));
}

/*---------------------------------------------------
 * Add Locations
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_add_locations' ) ) {
	function cs_add_locations(){
		global $cs_theme_options;
		//cs_datatable_scripts();
		if( $_POST['type']	== 'countries' ) {
			$cs_locations	= get_option('cs_location_countries');
			$countries		= array();
			$cs_countries	= explode(',',$_POST['countries']);
			if( isset( $cs_countries ) && $cs_countries !='' ){
				$countries	= isset( $cs_locations ) && $cs_locations !='' ? $cs_locations : array();
				foreach( $cs_countries as $key => $value ){
					 $value 	 	= explode("(" , rtrim($value, ")"));
			 		 $value 	 	= str_replace(')','',$value);
					 $country_key	= css_slugify_text($value[0]);   
					 $countryData	= array();

					 $countryData['id'] 		= $country_key; 
					 $countryData['name'] 		= stripslashes($value[0]);
					 
					 $countryData['isocode2']	= '';
					 $countryData['isocode3']	= '';
					 
					 if( isset( $value[1] )  && $value[1] !='' ) {
					 	$countryData['isocode2'] 	= $value[1]; 
					 }
					 
					 if( isset( $value[2] ) && $value[2] !='' ) {
					 	$countryData['isocode3'] 	= $value[2]; 
					 }
					 
					 if( !empty( $country_key ) ) {
					 	$countries[$country_key]	= $countryData;
					 }
				}
				
				update_option('cs_location_countries',$countries);
			}

		} else if( $_POST['type']	== 'states' ){
			
			$cs_locations	= get_option('cs_location_states');	
			$cs_country		= trim( $_POST['country'] );
			$new_states		= explode(',',$_POST['states']);

			if( isset( $new_states ) && $new_states !='' ){
				
				if ( isset( $cs_locations ) && is_array($cs_locations)) {
					$original	= $cs_locations;
					if( !isset( $original[$cs_country] ) ) {
						$new		= array($cs_country => array());
						$original	= array_merge($original,$new);
					}
				} else {
					$original	= array($cs_country => array());
				}
				
				$states	= array();
				
				foreach( $new_states as $key => $value ){
					$key	= css_slugify_text($value);
					$statesData	= array();
					$statesData['name'] 	  = stripslashes($value);
					$statesData['country_id'] = $cs_country; 
					$states[$key]			  = $statesData;
				}
				
				$ele = add_locations( $original, $states, $cs_country  );
				update_option('cs_location_states',$ele);	
			}	
			
		} else if( $_POST['type']	== 'cities' ){
			
			$cs_locations	  	= get_option('cs_location_cities');
			$cs_country			= trim( $_POST['country'] );
			$cs_state		  	= trim( $_POST['state'] );
			$cs_cities		 	= explode(',',$_POST['cities']);
			
			if( isset( $cs_cities ) && !empty( $cs_cities ) ){
				if ( isset( $cs_locations ) && is_array($cs_locations)) {
					$original	= $cs_locations;
					if( !isset( $original[$cs_country] ) ) {
						$new		= array($cs_country => array($cs_state => array()) );
						$original	= array_merge($original,$new);
					} else {
						if( !isset($original[$cs_country][$cs_state]) ) {
							$new				= array($cs_state => array());
							$temp_origional		= $original;
							$original_temp		= array_merge($original[$cs_country],$new);
							$original			= array($cs_country => $original_temp);
							$original			= array_merge($temp_origional,$original);
						}
					}
				} else {
					$original	= array($cs_country => array($cs_state => array()));
				}

				$cities	= array();

				foreach( $cs_cities as $key => $value ){
					$key	= css_slugify_text($value);
					$citiesData	= array();
					$citiesData['name'] 	  = stripslashes($value); 
					$citiesData['state_id']   = $cs_state; 
					$citiesData['country_id'] = $cs_country; 
					$cities[$key]			  = $citiesData;	
				}
				
				
				
				$ele = add_locations( $original, $cities, $cs_country, $cs_state );
				
				update_option('cs_location_cities',$ele);
				
				if( isset( $cs_state ) && $cs_state == 'no-state' ) {
					
					$cs_locations	= get_option('cs_location_states');	
	
					if ( isset( $cs_locations ) && is_array($cs_locations)) {
						$original	= $cs_locations;
						if( !isset( $original[$cs_country] ) ) {
							$new		= array($cs_country => array());
							$original	= array_merge($original,$new);
						}
					} else {
						$original	= array($cs_country => array());
					}
					
					$states	= array();
					$statesData	= array();
					$statesData['name'] 	  	  = trim($cs_state); 
					$statesData['country_id'] 	  = $cs_country; 
					$states[$cs_state]			  = $statesData;
					
					$ele = add_locations( $original, $states, $cs_country  );
				
					update_option('cs_location_states',$ele);
				}
			
			}
			
			
		}  
		die();
	}
	add_action('wp_ajax_cs_add_locations', 'cs_add_locations');
}

/*---------------------------------------------------
 * Update Locations
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_update_locations' ) ) {
	function cs_update_locations(){
		global $cs_theme_options;
		
		$json	= array();
		if( $_POST['type']	== 'countries' ) {
			$cs_locations	= get_option('cs_location_countries');
			$countries		= array();
			$country_name	= trim( $_POST['country_name'] );
			$isocode2		= trim( $_POST['isocode2'] );
			$isocode3		= trim( $_POST['isocode3'] );
			$country_key	= trim( $_POST['country_key'] );
			
			if( isset( $country_name ) && ! empty( $country_name )  && ! empty( $country_key ) ){
				$countries	= isset( $cs_locations ) && $cs_locations !='' ? $cs_locations : array();
				$countryData			= array();
				$countryData['id'] 		= $country_key;
				$countryData['name'] 	= stripslashes($country_name);
				$countryData['isocode2'] 	= $isocode2;
				$countryData['isocode3'] 	= $isocode3;  
				$countries[$country_key]	= $countryData;
				update_option('cs_location_countries',$countries);
				
				$json['type']	= 'success';
				$json['message']	= 'Country Updated Successfully.';
			} else {
				$json['type']	= 'error';
				$json['message']	= 'Some error occur, please try again later.';
			}

		} else if( $_POST['type']	== 'states' ){
			
			$cs_locations	= get_option('cs_location_states');	
			$original		= $cs_locations;
			$country_id		= trim( $_POST['country_id'] );
			$state_id		= trim( $_POST['state_id'] );
			$state_name		= trim( $_POST['state_name'] );

			if( isset( $country_id ) && ! empty( $country_id )  && ! empty( $state_id ) && ! empty( $state_name ) ){
				if ( isset( $cs_locations ) && is_array($cs_locations)) {
					$original	= $cs_locations;
					if( ! $original[$country_id] ) {
						$new		= array($country_id => array());
						$original	= array_merge($original,$new);
					}
				} else {
					$original	= array($country_id => array());
				}
				
				$statesData	= array();
				$statesData['name'] 	  	  = stripslashes($state_name); 
				$statesData['country_id'] 	  = $country_id; 
				$states[$state_id]			  = $statesData;
				
				$ele = add_locations( $original, $states, $country_id  );
				update_option('cs_location_states',$ele);
				
				$json['type']		= 'success';
				$json['message']	= 'State Updated Successfully.';
				
			} else {
				$json['type']		= 'error';
				$json['message']	= 'Some error occur, please try again later.';
			}		
			
		} else if( $_POST['type']	== 'cities' ){
			
			$cs_locations	  	= get_option('cs_location_cities');
			
			$country_id			= trim( $_POST['country_id'] );
			$state_id			= trim( $_POST['state_id'] );
			$city_id			= trim( $_POST['city_id'] );
			$city_name			= trim( $_POST['city_name'] );
			
			if( isset( $country_id ) && ! empty( $country_id ) && ! empty( $city_id ) && ! empty( $city_name ) ){
				if ( isset( $cs_locations ) && is_array($cs_locations)) {
					$original	= $cs_locations;
			
					if( !isset( $original[$country_id] ) ) {
						$new		= array($country_id => array($state_id => array()) );
						$original	= array_merge($original,$new);
					} else {
						if( !isset( $original[$country_id][$state_id] ) ) {
							$new		= array($state_id => array());
							$original_temp		= array_merge($original[$country_id],$new);
							$original			= array($country_id => $original_temp);
						}
					}
				} else {
					$original	= array($country_id => array($state_id => array()));
				}
					
				$citiesData	= array();
				$citiesData['name'] 	      = stripslashes($city_name); 
				$citiesData['state_id']   	  = $state_id; 
				$citiesData['country_id'] 	  = $country_id; 
				$cities[$city_id]			  = $citiesData;	
				
				$ele = add_locations( $original, $cities, $country_id, $state_id );
				update_option('cs_location_cities',$ele);
				
				$json['type']		= 'success';
				$json['message']	= 'City Updated Successfully.';
				
			} else {
				$json['type']		= 'error';
				$json['message']	= 'Some error occur, please try again later.';
			}	
		}
		
		echo json_encode($json);  
		die();
	
	}
	add_action('wp_ajax_cs_update_locations', 'cs_update_locations');
}

/*---------------------------------------------------
 * Load States
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_load_states' ) ) {
	function cs_load_states(){
		global $cs_theme_options;
		$cs_locations	= get_option('cs_location_states');
		$states	= '';
		$cs_country	= $_POST['country'];
		if( $cs_country && $cs_country !=''  ) {
			$states_data	= isset( $cs_locations[$cs_country] ) ? $cs_locations[$cs_country] : '';
			$states	.= '<option value="">Select State</option>' ;
			if( isset( $states_data ) && $states_data !='' ){
				foreach( $states_data as $key => $value ) {
					if( $key !='no-state' ) {
						$states	.='<option value="'.$key.'">'.$value['name'].'</option>' ;
					}
				}
			}
		}
		echo $states;
		die();
	}
	add_action('wp_ajax_cs_load_states', 'cs_load_states');
}

/*---------------------------------------------------
 * Add New Locations
 *--------------------------------------------------*/
function add_locations( $original, $items_to_add, $country ,$state = '' ){
	
	if( !empty($state) ){
		$target = $original[$country][$state];
	}else{
		$target = $original[$country];
	}
	
	$new_arr = array_merge( $target, $items_to_add  );
	
	if( !empty($state) ){
		$original[$country][$state] = $new_arr;

	}else{
		$original[$country] = $new_arr;
	}
	
	return $original;
}

/*---------------------------------------------------
 * Delete Location
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_delete_location' ) ) {
	function cs_delete_location(){
		global $cs_theme_options;
		$type	= $_POST['type'];
		
		$cs_location_countries	= get_option('cs_location_countries');
		$cs_location_states		= get_option('cs_location_states');
		$cs_location_cities		= get_option('cs_location_cities');
		
		if( $type == 'country' ) {
			$node	= $_POST['node'];
			
			$cs_location_country	= cs_remove_location( $cs_location_countries, $cs_location_countries[$node] );
			if( isset( $cs_location_states[$node] ) ) {
				$cs_location_states		= cs_remove_location( $cs_location_states, $cs_location_states[$node] );
			}
			
			if( isset( $cs_location_cities[$node] ) ) {
				$cs_location_cities		= cs_remove_location( $cs_location_cities, $cs_location_cities[$node] );
			}

			update_option( 'cs_location_countries',$cs_location_country );
			update_option( 'cs_location_states',$cs_location_states );
			update_option( 'cs_location_cities',$cs_location_cities );
			
			 
		} else if( $type == 'state' ) {
			$node			= $_POST['node'];
			$country_node	= $_POST['country_node'];
			
			unset($cs_location_states[$country_node][$node]);
			
			if( isset( $cs_location_cities[$country_node][$node] ) ) {
				unset($cs_location_cities[$country_node][$node]);
			}
			
			update_option( 'cs_location_states',$cs_location_states );
			update_option( 'cs_location_cities',$cs_location_cities );
		
		} else if( $type == 'city' ) {
			$node			= $_POST['node'];
			$country_node	= $_POST['country_node'];
			$state_node		= $_POST['state_node'];
			
			unset( $cs_location_cities[$country_node][$state_node][$node] );
			
			update_option( 'cs_location_cities',$cs_location_cities );
		}
		die();
	}
	add_action('wp_ajax_cs_delete_location', 'cs_delete_location');
}

/*---------------------------------------------------
 * Remove Location
 *--------------------------------------------------*/
function cs_remove_location( $array, $item ) {
	$index = array_search($item, $array);
	if ( $index !== false ) {
		unset( $array[$index] );
	}

	return $array;
}

/*---------------------------------------------------
 * Get Country States
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_load_country_states' ) ) {
	function cs_load_country_states(){
		global $cs_theme_options;
		$cs_locations			= get_option('cs_location_states');
		$cities_list			= get_option('cs_location_cities');
		$states			= '';
		$cs_country		= $_POST['country'];
		$json			= array();
		$json['states']= '<option value="">Select State</option>' ;
		$json['cities']= '<option value="">Select City</option>' ;
		$cs_country	= trim( strtolower( str_replace(' ','-',$cs_country ) ) );   
		
		if( $cs_country && $cs_country !=''  ) {
			$states_data	= isset( $cs_locations[$cs_country] ) ? $cs_locations[$cs_country] : '';
			
			if( isset( $states_data ) && $states_data !='' ){
				
				foreach( $states_data as $key => $value ) {
					if( $key !='no-state' ) {
						$json['states']	.='<option value="'.$key.'">'.$value['name'].'</option>' ;
					}

					if( isset( $cities_list[$cs_country][$key] ) ) {
						$cities	= $cities_list[$cs_country][$key];
						if( isset( $cities ) && $cities !='' && is_array($cities) ) {
							
							foreach( $cities as $key => $city ) {
								$json['cities']	.=  "<option  value='".$city['name']."'>".$city['name']."</option>";
							}
						}
					}
				}
			}
		}
		
		echo json_encode($json);
		die();
	}
	add_action('wp_ajax_cs_load_country_states', 'cs_load_country_states');
}

/*---------------------------------------------------
 * Get Country States
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_load_country_cities' ) ) {
	function cs_load_country_cities(){
		global $cs_theme_options;
		$cs_locations			= get_option('cs_location_states');
		$cities_list			= get_option('cs_location_cities');

		$cs_country		= $_POST['country'];
		$cs_state		= $_POST['state'];
		$cs_country		= trim( strtolower( str_replace(' ','-',$cs_country ) ) );
		$cs_state		= trim( strtolower( str_replace(' ','-',$cs_state ) ) );
		
		$json			= array();
		$json['cities']= '<option value="">Select City</option>' ;
		   
		
		if( $cs_country && $cs_country !=''  ) {
			$states_data	= isset( $cs_locations[$cs_country] ) ? $cs_locations[$cs_country] : '';
			
			if( isset( $cities_list[$cs_country][$cs_state] ) ) {
				$cities	= $cities_list[$cs_country][$cs_state];
				if( isset( $cities ) && $cities !='' && is_array($cities) ) {
					foreach( $cities as $key => $city ) {
						$json['cities']	.=  "<option  value='".$city['name']."'>".$city['name']."</option>";
					}
				}
			}
		}
		
		echo json_encode($json);
		die();
	}
	add_action('wp_ajax_cs_load_country_cities', 'cs_load_country_cities');
}

/*---------------------------------------------------
 * Remove Post Submit Div
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_remove_post_boxes' ) ) {
	function cs_remove_post_boxes() {
		remove_meta_box( 'submitdiv' , 'directory_types' , 'side' ); 
		remove_meta_box( 'slugdiv' , 'directory_types' , 'side' );
		remove_meta_box( 'mymetabox_revslider_0' , 'directory_types' , 'normal' );
	}
	add_action( 'admin_menu' , 'cs_remove_post_boxes' );
}

/*---------------------------------------------------
 * Remove Post Submit Div
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_post_submit_meta_box' ) ) {
	function cs_post_submit_meta_box($post, $args = array() ) {
		global $action,$post;
	 
		$post_type = $post->post_type;
		$post_type_object = get_post_type_object($post_type);
		$can_publish = current_user_can($post_type_object->cap->publish_posts);
	?>
	<div class="submitbox directory-submit" id="submitpost">
	<div id="minor-publishing">
	<div style="display:none;">
	<?php submit_button( __( 'Save' ,'directory'), 'button', 'save' ); ?>
	</div>
	 
	<div id="minor-publishing-actions">
	
	<?php if ( $post_type_object->public ) : ?>
	<div id="preview-action">
	<?php
	if ( 'publish' == $post->post_status ) {
		$preview_link = esc_url( get_permalink( $post->ID ) );
		$preview_button = __( 'Preview Changes','directory' );
	} else {
		$preview_link = set_url_scheme( get_permalink( $post->ID ) );
	 
		/**
		 * Filter the URI of a post preview in the post submit box.
		 *
		 * @since 2.0.5
		 * @since 4.0.0 $post parameter was added.
		 *
		 * @param string  $preview_link URI the user will be directed to for a post preview.
		 * @param WP_Post $post         Post object.
		 */
		$preview_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( 'preview', 'true',  esc_url( $preview_link ) ), urlencode( $post ) ) );
		$preview_button = __( 'Preview','directory' );
	}
	?>
	</div>
	<?php endif; // public post type ?>
	<div class="clear"></div>
	</div><!-- #minor-publishing-actions -->
	</div>
	<div id="major-publishing-actions" style="border-top:0px">
	<?php
	/**
	 * Fires at the beginning of the publishing actions section of the Publish meta box.
	 *
	 * @since 2.7.0
	 */
	do_action( 'post_submitbox_start' );
	?>
	<div id="delete-action">
	<?php
	if ( current_user_can( "delete_post", $post->ID ) ) {
		if ( !EMPTY_TRASH_DAYS )
			$delete_text = __('Delete Permanently','directory');
		else
			$delete_text = __('Move to Trash','directory');
	if( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
	?>
	<a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
	} 
	}
	?>
	</div>
	 
	<div id="publishing-action">
	<span class="spinner"></span>
	<?php
	if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
		if ( $can_publish ) :
		
			if ( !empty($post->post_date_gmt) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Schedule','directory') ?>" />
			<?php submit_button( __( 'Schedule','directory'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
	<?php    else : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish','directory') ?>" />
			<?php submit_button( __( 'Publish','directory'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
	<?php    endif;
		else : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Submit for Review','directory') ?>" />
			<?php submit_button( __( 'Submit for Review','directory' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
	<?php
		endif;
	} else { 
			
			if( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
			?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update','directory') ?>" />
			<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update','directory') ?>" />
			<?php
			}
			else{
			?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish','directory') ?>">
			<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="<?php esc_attr_e('Publish','directory') ?>" accesskey="p">
	<?php
			}
	} ?>
	</div>
	<div class="clear"></div>
	</div>
	</div>
	 
	<?php
	}
}

/*------------------------------------------------------
 * Get Currency Symbol
 *-----------------------------------------------------*/
if ( ! function_exists( 'cs_get_currency_symbol' ) ) {
	function cs_get_currency_symbol(){
		$code	= $_POST['code'];
		$currency_list	= cs_get_currency();
		echo $currency_list[$code]['symbol'];
		die();
	}
	add_action('wp_ajax_cs_get_currency_symbol', 'cs_get_currency_symbol');
}
/*------------------------------------------------------
 * Get Currency List
 *-----------------------------------------------------*/
if ( ! function_exists( 'cs_get_currency' ) ) {
	function cs_get_currency(){
		return array (
						'USD' => array ( 'numeric_code'  =>	840	, 'code' => 'USD', 'name' => 'United States dollar', 'symbol' => '$', 'fraction_name' => 'Cent[D]', 'decimals' => 2 ),
						'AED' => array ( 'numeric_code'  =>	784	, 'code' => 'AED', 'name' => 'United Arab Emirates dirham',  'symbol' => 'د.إ', 'fraction_name' => 'Fils', 'decimals' => 2 ),
						'AFN' => array ( 'numeric_code'  =>	971	, 'code' => 'AFN', 'name' => 'Afghan afghani',               'symbol' => '؋', 'fraction_name' => 'Pul', 'decimals' => 2 ),
						'ALL' => array ( 'numeric_code'  =>	8	  , 'code' => 'ALL', 'name' => 'Albanian lek',                 'symbol' => 'L', 'fraction_name' => 'Qintar', 'decimals' => 2 ),
						'AMD' => array ( 'numeric_code'  =>	51	, 'code' => 'AMD', 'name' => 'Armenian dram',                'symbol' => 'դր.', 'fraction_name' => 'Luma', 'decimals' => 2 ),
						'AMD' => array ( 'numeric_code'  =>	51	, 'code' => 'AMD', 'name' => 'Armenian dram',                'symbol' => 'դր.', 'fraction_name' => 'Luma', 'decimals' => 2 ),
						'ANG' => array ( 'numeric_code'  =>	532	, 'code' => 'ANG', 'name' => 'Netherlands Antillean guilder',  'symbol' => 'ƒ', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'AOA' => array ( 'numeric_code'  =>	973	, 'code' => 'AOA', 'name' => 'Angolan kwanza',                 'symbol' => 'Kz', 'fraction_name' => 'Cêntimo', 'decimals' => 2 ),
						'ARS' => array ( 'numeric_code'  =>	32	, 'code' => 'ARS', 'name' => 'Argentine peso',                 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'AUD' => array ( 'numeric_code'  =>	36	, 'code' => 'AUD', 'name' => 'Australian dollar',              'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'AWG' => array ( 'numeric_code'  =>	533	, 'code' => 'AWG', 'name' => 'Aruban florin', 'symbol' => 'ƒ', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'AZN' => array ( 'numeric_code'  =>	944	, 'code' => 'AZN', 'name' => 'Azerbaijani manat', 'symbol' => 'AZN', 'fraction_name' => 'Qəpik', 'decimals' => 2 ),
						'BAM' => array ( 'numeric_code'  =>	977	, 'code' => 'BAM', 'name' => 'Bosnia and Herzegovina convertible mark', 'symbol' => 'КМ', 'fraction_name' => 'Fening', 'decimals' => 2 ),
						'BBD' => array ( 'numeric_code'  =>	52	, 'code' => 'BBD', 'name' => 'Barbadian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'BDT' => array ( 'numeric_code'  =>	50	, 'code' => 'BDT', 'name' => 'Bangladeshi taka', 'symbol' => '৳', 'fraction_name' => 'Paisa', 'decimals' => 2 ),
						'BGN' => array ( 'numeric_code'  =>	975	, 'code' => 'BGN', 'name' => 'Bulgarian lev', 'symbol' => 'лв', 'fraction_name' => 'Stotinka', 'decimals' => 2 ),
						'BHD' => array ( 'numeric_code'  =>	48	, 'code' => 'BHD', 'name' => 'Bahraini dinar', 'symbol' => 'ب.د', 'fraction_name' => 'Fils', 'decimals' => 3 ),
						'BIF' => array ( 'numeric_code'  =>	108	, 'code' => 'BIF', 'name' => 'Burundian franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'BMD' => array ( 'numeric_code'  =>	60	, 'code' => 'BMD', 'name' => 'Bermudian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'BND' => array ( 'numeric_code'  =>	96	, 'code' => 'BND', 'name' => 'Brunei dollar', 'symbol' => '$', 'fraction_name' => 'Sen', 'decimals' => 2 ),
						'BND' => array ( 'numeric_code'  =>	96	, 'code' => 'BND', 'name' => 'Brunei dollar', 'symbol' => '$', 'fraction_name' => 'Sen', 'decimals' => 2 ),
						'BOB' => array ( 'numeric_code'  =>	68	, 'code' => 'BOB', 'name' => 'Bolivian boliviano', 'symbol' => 'Bs.', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'BRL' => array ( 'numeric_code'  =>	986	, 'code' => 'BRL', 'name' => 'Brazilian real', 'symbol' => 'R$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'BSD' => array ( 'numeric_code'  =>	44	, 'code' => 'BSD', 'name' => 'Bahamian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'BTN' => array ( 'numeric_code'  =>	64	, 'code' => 'BTN', 'name' => 'Bhutanese ngultrum', 'symbol' => 'BTN', 'fraction_name' => 'Chertrum', 'decimals' => 2 ),
						'BWP' => array ( 'numeric_code'  =>	72	, 'code' => 'BWP', 'name' => 'Botswana pula', 'symbol' => 'P', 'fraction_name' => 'Thebe', 'decimals' => 2 ),
						'BWP' => array ( 'numeric_code'  =>	72	, 'code' => 'BWP', 'name' => 'Botswana pula', 'symbol' => 'P', 'fraction_name' => 'Thebe', 'decimals' => 2 ),
						'BYR' => array ( 'numeric_code'  =>	974	, 'code' => 'BYR', 'name' => 'Belarusian ruble', 'symbol' => 'Br', 'fraction_name' => 'Kapyeyka', 'decimals' => 2 ),
						'BZD' => array ( 'numeric_code'  =>	84	, 'code' => 'BZD', 'name' => 'Belize dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'CAD' => array ( 'numeric_code'  =>	124	, 'code' => 'CAD', 'name' => 'Canadian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'CDF' => array ( 'numeric_code'  =>	976	, 'code' => 'CDF', 'name' => 'Congolese franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'CHF' => array ( 'numeric_code'  =>	756	, 'code' => 'CHF', 'name' => 'Swiss franc', 'symbol' => 'Fr', 'fraction_name' => 'Rappen[I]', 'decimals' => 2 ),
						'CLP' => array ( 'numeric_code'  =>	152	, 'code' => 'CLP', 'name' => 'Chilean peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'CNY' => array ( 'numeric_code'  =>	156	, 'code' => 'CNY', 'name' => 'Chinese yuan', 'symbol' => '元', 'fraction_name' => 'Fen[E]', 'decimals' => 2 ),
						'COP' => array ( 'numeric_code'  =>	170	, 'code' => 'COP', 'name' => 'Colombian peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'CRC' => array ( 'numeric_code'  =>	188	, 'code' => 'CRC', 'name' => 'Costa Rican colón', 'symbol' => '₡', 'fraction_name' => 'Céntimo', 'decimals' => 2 ),
						'CUC' => array ( 'numeric_code'  =>	931	, 'code' => 'CUC', 'name' => 'Cuban convertible peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'CUP' => array ( 'numeric_code'  =>	192	, 'code' => 'CUP', 'name' => 'Cuban peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'CVE' => array ( 'numeric_code'  =>	132	, 'code' => 'CVE', 'name' => 'Cape Verdean escudo', 'symbol' => 'Esc', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'CZK' => array ( 'numeric_code'  =>	203	, 'code' => 'CZK', 'name' => 'Czech koruna', 'symbol' => 'Kč', 'fraction_name' => 'Haléř', 'decimals' => 2 ),
						'DJF' => array ( 'numeric_code'  =>	262	, 'code' => 'DJF', 'name' => 'Djiboutian franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'DKK' => array ( 'numeric_code'  =>	208	, 'code' => 'DKK', 'name' => 'Danish krone', 'symbol' => 'kr', 'fraction_name' => 'Øre', 'decimals' => 2 ),
						'DKK' => array ( 'numeric_code'  =>	208	, 'code' => 'DKK', 'name' => 'Danish krone', 'symbol' => 'kr', 'fraction_name' => 'Øre', 'decimals' => 2 ),
						'DOP' => array ( 'numeric_code'  =>	214	, 'code' => 'DOP', 'name' => 'Dominican peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'DZD' => array ( 'numeric_code'  =>	12	, 'code' => 'DZD', 'name' => 'Algerian dinar', 'symbol' => 'د.ج', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'EEK' => array ( 'numeric_code'  =>	233	, 'code' => 'EEK', 'name' => 'Estonian kroon', 'symbol' => 'KR', 'fraction_name' => 'Sent', 'decimals' => 2 ),
						'EGP' => array ( 'numeric_code'  =>	818	, 'code' => 'EGP', 'name' => 'Egyptian pound', 'symbol' => '£', 'fraction_name' => 'Piastre[F]', 'decimals' => 2 ),
						'ERN' => array ( 'numeric_code'  =>	232	, 'code' => 'ERN', 'name' => 'Eritrean nakfa', 'symbol' => 'Nfk', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'ETB' => array ( 'numeric_code'  =>	230	, 'code' => 'ETB', 'name' => 'Ethiopian birr', 'symbol' => 'ETB', 'fraction_name' => 'Santim', 'decimals' => 2 ),
						'EUR' => array ( 'numeric_code'  =>	978	, 'code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'FJD' => array ( 'numeric_code'  =>	242	, 'code' => 'FJD', 'name' => 'Fijian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'FKP' => array ( 'numeric_code'  =>	238	, 'code' => 'FKP', 'name' => 'Falkland Islands pound', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2 ),
						'GBP' => array ( 'numeric_code'  =>	826	, 'code' => 'GBP', 'name' => 'British pound[C]', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2 ),
						'GEL' => array ( 'numeric_code'  =>	981	, 'code' => 'GEL', 'name' => 'Georgian lari', 'symbol' => 'ლ', 'fraction_name' => 'Tetri', 'decimals' => 2 ),
						'GHS' => array ( 'numeric_code'  =>	936	, 'code' => 'GHS', 'name' => 'Ghanaian cedi', 'symbol' => '₵', 'fraction_name' => 'Pesewa', 'decimals' => 2 ),
						'GIP' => array ( 'numeric_code'  =>	292	, 'code' => 'GIP', 'name' => 'Gibraltar pound', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2 ),
						'GMD' => array ( 'numeric_code'  =>	270	, 'code' => 'GMD', 'name' => 'Gambian dalasi', 'symbol' => 'D', 'fraction_name' => 'Butut', 'decimals' => 2 ),
						'GNF' => array ( 'numeric_code'  =>	324	, 'code' => 'GNF', 'name' => 'Guinean franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'GTQ' => array ( 'numeric_code'  =>	320	, 'code' => 'GTQ', 'name' => 'Guatemalan quetzal', 'symbol' => 'Q', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'GYD' => array ( 'numeric_code'  =>	328	, 'code' => 'GYD', 'name' => 'Guyanese dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'HKD' => array ( 'numeric_code'  =>	344	, 'code' => 'HKD', 'name' => 'Hong Kong dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'HNL' => array ( 'numeric_code'  =>	340	, 'code' => 'HNL', 'name' => 'Honduran lempira', 'symbol' => 'L', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'HRK' => array ( 'numeric_code'  =>	191	, 'code' => 'HRK', 'name' => 'Croatian kuna', 'symbol' => 'kn', 'fraction_name' => 'Lipa', 'decimals' => 2 ),
						'HTG' => array ( 'numeric_code'  =>	332	, 'code' => 'HTG', 'name' => 'Haitian gourde', 'symbol' => 'G', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'HUF' => array ( 'numeric_code'  =>	348	, 'code' => 'HUF', 'name' => 'Hungarian forint', 'symbol' => 'Ft', 'fraction_name' => 'Fillér', 'decimals' => 2 ),
						'IDR' => array ( 'numeric_code'  =>	360	, 'code' => 'IDR', 'name' => 'Indonesian rupiah', 'symbol' => 'Rp', 'fraction_name' => 'Sen', 'decimals' => 2 ),
						'ILS' => array ( 'numeric_code'  =>	376	, 'code' => 'ILS', 'name' => 'Israeli new sheqel', 'symbol' => '₪', 'fraction_name' => 'Agora', 'decimals' => 2 ),
						'INR' => array ( 'numeric_code'  =>	356	, 'code' => 'INR', 'name' => 'Indian rupee', 'symbol' => '₨', 'fraction_name' => 'Paisa', 'decimals' => 2 ),
						'IQD' => array ( 'numeric_code'  =>	368	, 'code' => 'IQD', 'name' => 'Iraqi dinar', 'symbol' => 'ع.د', 'fraction_name' => 'Fils', 'decimals' => 3 ),
						'IRR' => array ( 'numeric_code'  =>	364	, 'code' => 'IRR', 'name' => 'Iranian rial', 'symbol' => '﷼', 'fraction_name' => 'Dinar', 'decimals' => 2 ),
						'ISK' => array ( 'numeric_code'  =>	352	, 'code' => 'ISK', 'name' => 'Icelandic króna', 'symbol' => 'kr', 'fraction_name' => 'Eyrir', 'decimals' => 2 ),
						'JMD' => array ( 'numeric_code'  =>	388	, 'code' => 'JMD', 'name' => 'Jamaican dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'JOD' => array ( 'numeric_code'  =>	400	, 'code' => 'JOD', 'name' => 'Jordanian dinar', 'symbol' => 'د.ا', 'fraction_name' => 'Piastre[H]', 'decimals' => 2 ),
						'JPY' => array ( 'numeric_code'  =>	392	, 'code' => 'JPY', 'name' => 'Japanese yen', 'symbol' => '¥', 'fraction_name' => 'Sen[G]', 'decimals' => 2 ),
						'KES' => array ( 'numeric_code'  =>	404	, 'code' => 'KES', 'name' => 'Kenyan shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'KGS' => array ( 'numeric_code'  =>	417	, 'code' => 'KGS', 'name' => 'Kyrgyzstani som', 'symbol' => 'KGS', 'fraction_name' => 'Tyiyn', 'decimals' => 2 ),
						'KHR' => array ( 'numeric_code'  =>	116	, 'code' => 'KHR', 'name' => 'Cambodian riel', 'symbol' => '៛', 'fraction_name' => 'Sen', 'decimals' => 2 ),
						'KMF' => array ( 'numeric_code'  =>	174	, 'code' => 'KMF', 'name' => 'Comorian franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'KPW' => array ( 'numeric_code'  =>	408	, 'code' => 'KPW', 'name' => 'North Korean won', 'symbol' => '₩', 'fraction_name' => 'Chŏn', 'decimals' => 2 ),
						'KRW' => array ( 'numeric_code'  =>	410	, 'code' => 'KRW', 'name' => 'South Korean won', 'symbol' => '₩', 'fraction_name' => 'Jeon', 'decimals' => 2 ),
						'KWD' => array ( 'numeric_code'  =>	414	, 'code' => 'KWD', 'name' => 'Kuwaiti dinar', 'symbol' => 'د.ك', 'fraction_name' => 'Fils', 'decimals' => 3 ),
						'KYD' => array ( 'numeric_code'  =>	136	, 'code' => 'KYD', 'name' => 'Cayman Islands dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'KZT' => array ( 'numeric_code'  =>	398	, 'code' => 'KZT', 'name' => 'Kazakhstani tenge', 'symbol' => '〒', 'fraction_name' => 'Tiyn', 'decimals' => 2 ),
						'LAK' => array ( 'numeric_code'  =>	418	, 'code' => 'LAK', 'name' => 'Lao kip', 'symbol' => '₭', 'fraction_name' => 'Att', 'decimals' => 2 ),
						'LBP' => array ( 'numeric_code'  =>	422	, 'code' => 'LBP', 'name' => 'Lebanese pound', 'symbol' => 'ل.ل', 'fraction_name' => 'Piastre', 'decimals' => 2 ),
						'LKR' => array ( 'numeric_code'  =>	144	, 'code' => 'LKR', 'name' => 'Sri Lankan rupee', 'symbol' => 'Rs', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'LRD' => array ( 'numeric_code'  =>	430	, 'code' => 'LRD', 'name' => 'Liberian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'LSL' => array ( 'numeric_code'  =>	426	, 'code' => 'LSL', 'name' => 'Lesotho loti', 'symbol' => 'L', 'fraction_name' => 'Sente', 'decimals' => 2 ),
						'LTL' => array ( 'numeric_code'  =>	440	, 'code' => 'LTL', 'name' => 'Lithuanian litas', 'symbol' => 'Lt', 'fraction_name' => 'Centas', 'decimals' => 2 ),
						'LVL' => array ( 'numeric_code'  =>	428	, 'code' => 'LVL', 'name' => 'Latvian lats', 'symbol' => 'Ls', 'fraction_name' => 'Santīms', 'decimals' => 2 ),
						'LYD' => array ( 'numeric_code'  =>	434	, 'code' => 'LYD', 'name' => 'Libyan dinar', 'symbol' => 'ل.د', 'fraction_name' => 'Dirham', 'decimals' => 3 ),
						'MAD' => array ( 'numeric_code'  =>	504	, 'code' => 'MAD', 'name' => 'Moroccan dirham', 'symbol' => 'Dh', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'MDL' => array ( 'numeric_code'  =>	498	, 'code' => 'MDL', 'name' => 'Moldovan leu', 'symbol' => 'L', 'fraction_name' => 'Ban', 'decimals' => 2 ),
						'MGA' => array ( 'numeric_code'  =>	969	, 'code' => 'MGA', 'name' => 'Malagasy ariary', 'symbol' => 'MGA', 'fraction_name' => 'Iraimbilanja', 'decimals' =>	5	),
						'MKD' => array ( 'numeric_code'  =>	807	, 'code' => 'MKD', 'name' => 'Macedonian denar', 'symbol' => 'ден', 'fraction_name' => 'Deni', 'decimals' => 2 ),
						'MMK' => array ( 'numeric_code'  =>	104	, 'code' => 'MMK', 'name' => 'Myanma kyat', 'symbol' => 'K', 'fraction_name' => 'Pya', 'decimals' => 2 ),
						'MNT' => array ( 'numeric_code'  =>	496	, 'code' => 'MNT', 'name' => 'Mongolian tögrög', 'symbol' => '₮', 'fraction_name' => 'Möngö', 'decimals' => 2 ),
						'MOP' => array ( 'numeric_code'  =>	446	, 'code' => 'MOP', 'name' => 'Macanese pataca', 'symbol' => 'P', 'fraction_name' => 'Avo', 'decimals' => 2 ),
						'MRO' => array ( 'numeric_code'  =>	478	, 'code' => 'MRO', 'name' => 'Mauritanian ouguiya', 'symbol' => 'UM', 'fraction_name' => 'Khoums', 'decimals' =>	5	),
						'MUR' => array ( 'numeric_code'  =>	480	, 'code' => 'MUR', 'name' => 'Mauritian rupee', 'symbol' => '₨', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'MVR' => array ( 'numeric_code'  =>	462	, 'code' => 'MVR', 'name' => 'Maldivian rufiyaa', 'symbol' => 'ރ.', 'fraction_name' => 'Laari', 'decimals' => 2 ),
						'MWK' => array ( 'numeric_code'  =>	454	, 'code' => 'MWK', 'name' => 'Malawian kwacha', 'symbol' => 'MK', 'fraction_name' => 'Tambala', 'decimals' => 2 ),
						'MXN' => array ( 'numeric_code'  =>	484	, 'code' => 'MXN', 'name' => 'Mexican peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'MYR' => array ( 'numeric_code'  =>	458	, 'code' => 'MYR', 'name' => 'Malaysian ringgit', 'symbol' => 'RM', 'fraction_name' => 'Sen', 'decimals' => 2 ),
						'MZN' => array ( 'numeric_code'  =>	943	, 'code' => 'MZN', 'name' => 'Mozambican metical', 'symbol' => 'MTn', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'NAD' => array ( 'numeric_code'  =>	516	, 'code' => 'NAD', 'name' => 'Namibian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'NGN' => array ( 'numeric_code'  =>	566	, 'code' => 'NGN', 'name' => 'Nigerian naira', 'symbol' => '₦', 'fraction_name' => 'Kobo', 'decimals' => 2 ),
						'NIO' => array ( 'numeric_code'  =>	558	, 'code' => 'NIO', 'name' => 'Nicaraguan córdoba', 'symbol' => 'C$', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'NOK' => array ( 'numeric_code'  =>	578	, 'code' => 'NOK', 'name' => 'Norwegian krone', 'symbol' => 'kr', 'fraction_name' => 'Øre', 'decimals' => 2 ),
						'NPR' => array ( 'numeric_code'  =>	524	, 'code' => 'NPR', 'name' => 'Nepalese rupee', 'symbol' => '₨', 'fraction_name' => 'Paisa', 'decimals' => 2 ),
						'NZD' => array ( 'numeric_code'  =>	554	, 'code' => 'NZD', 'name' => 'New Zealand dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'OMR' => array ( 'numeric_code'  =>	512	, 'code' => 'OMR', 'name' => 'Omani rial', 'symbol' => 'ر.ع.', 'fraction_name' => 'Baisa', 'decimals' => 3 ),
						'PAB' => array ( 'numeric_code'  =>	590	, 'code' => 'PAB', 'name' => 'Panamanian balboa', 'symbol' => 'B/.', 'fraction_name' => 'Centésimo', 'decimals' => 2 ),
						'PEN' => array ( 'numeric_code'  =>	604	, 'code' => 'PEN', 'name' => 'Peruvian nuevo sol', 'symbol' => 'S/.', 'fraction_name' => 'Céntimo', 'decimals' => 2 ),
						'PGK' => array ( 'numeric_code'  =>	598	, 'code' => 'PGK', 'name' => 'Papua New Guinean kina', 'symbol' => 'K', 'fraction_name' => 'Toea', 'decimals' => 2 ),
						'PHP' => array ( 'numeric_code'  =>	608	, 'code' => 'PHP', 'name' => 'Philippine peso', 'symbol' => '₱', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'PKR' => array ( 'numeric_code'  =>	586	, 'code' => 'PKR', 'name' => 'Pakistani rupee', 'symbol' => '₨', 'fraction_name' => 'Paisa', 'decimals' => 2 ),
						'PLN' => array ( 'numeric_code'  =>	985	, 'code' => 'PLN', 'name' => 'Polish złoty', 'symbol' => 'zł', 'fraction_name' => 'Grosz', 'decimals' => 2 ),
						'PYG' => array ( 'numeric_code'  =>	600	, 'code' => 'PYG', 'name' => 'Paraguayan guaraní', 'symbol' => '₲', 'fraction_name' => 'Céntimo', 'decimals' => 2 ),
						'QAR' => array ( 'numeric_code'  =>	634	, 'code' => 'QAR', 'name' => 'Qatari riyal', 'symbol' => 'ر.ق', 'fraction_name' => 'Dirham', 'decimals' => 2 ),
						'RON' => array ( 'numeric_code'  =>	946	, 'code' => 'RON', 'name' => 'Romanian leu', 'symbol' => 'L', 'fraction_name' => 'Ban', 'decimals' => 2 ),
						'RSD' => array ( 'numeric_code'  =>	941	, 'code' => 'RSD', 'name' => 'Serbian dinar', 'symbol' => 'дин.', 'fraction_name' => 'Para', 'decimals' => 2 ),
						'RUB' => array ( 'numeric_code'  =>	643	, 'code' => 'RUB', 'name' => 'Russian ruble', 'symbol' => 'руб.', 'fraction_name' => 'Kopek', 'decimals' => 2 ),
						'RWF' => array ( 'numeric_code'  =>	646	, 'code' => 'RWF', 'name' => 'Rwandan franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'SAR' => array ( 'numeric_code'  =>	682	, 'code' => 'SAR', 'name' => 'Saudi riyal', 'symbol' => 'ر.س', 'fraction_name' => 'Hallallah', 'decimals' => 2 ),
						'SBD' => array ( 'numeric_code'  =>	90	, 'code' => 'SBD', 'name' => 'Solomon Islands dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'SCR' => array ( 'numeric_code'  =>	690	, 'code' => 'SCR', 'name' => 'Seychellois rupee', 'symbol' => '₨', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'SDG' => array ( 'numeric_code'  =>	938	, 'code' => 'SDG', 'name' => 'Sudanese pound', 'symbol' => '£', 'fraction_name' => 'Piastre', 'decimals' => 2 ),
						'SEK' => array ( 'numeric_code'  =>	752	, 'code' => 'SEK', 'name' => 'Swedish krona', 'symbol' => 'kr', 'fraction_name' => 'Öre', 'decimals' => 2 ),
						'SGD' => array ( 'numeric_code'  =>	702	, 'code' => 'SGD', 'name' => 'Singapore dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'SHP' => array ( 'numeric_code'  =>	654	, 'code' => 'SHP', 'name' => 'Saint Helena pound', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2 ),
						'SLL' => array ( 'numeric_code'  =>	694	, 'code' => 'SLL', 'name' => 'Sierra Leonean leone', 'symbol' => 'Le', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'SOS' => array ( 'numeric_code'  =>	706	, 'code' => 'SOS', 'name' => 'Somali shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'SRD' => array ( 'numeric_code'  =>	968	, 'code' => 'SRD', 'name' => 'Surinamese dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'STD' => array ( 'numeric_code'  =>	678	, 'code' => 'STD', 'name' => 'São Tomé and Príncipe dobra', 'symbol' => 'Db', 'fraction_name' => 'Cêntimo', 'decimals' => 2 ),
						'SVC' => array ( 'numeric_code'  =>	222	, 'code' => 'SVC', 'name' => 'Salvadoran colón', 'symbol' => '₡', 'fraction_name' => 'Centavo', 'decimals' => 2 ),
						'SYP' => array ( 'numeric_code'  =>	760	, 'code' => 'SYP', 'name' => 'Syrian pound', 'symbol' => '£', 'fraction_name' => 'Piastre', 'decimals' => 2 ),
						'SZL' => array ( 'numeric_code'  =>	748	, 'code' => 'SZL', 'name' => 'Swazi lilangeni', 'symbol' => 'L', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'THB' => array ( 'numeric_code'  =>	764	, 'code' => 'THB', 'name' => 'Thai baht', 'symbol' => '฿', 'fraction_name' => 'Satang', 'decimals' => 2 ),
						'TJS' => array ( 'numeric_code'  =>	972	, 'code' => 'TJS', 'name' => 'Tajikistani somoni', 'symbol' => 'ЅМ', 'fraction_name' => 'Diram', 'decimals' => 2 ),
						'TMM' => array ( 'numeric_code'  =>	0	  , 'code' => 'TMM', 'name' => 'Turkmenistani manat', 'symbol' => 'm', 'fraction_name' => 'Tennesi', 'decimals' => 2 ),
						'TND' => array ( 'numeric_code'  =>	788	, 'code' => 'TND', 'name' => 'Tunisian dinar', 'symbol' => 'د.ت', 'fraction_name' => 'Millime', 'decimals' => 3 ),
						'TOP' => array ( 'numeric_code'  =>	776	, 'code' => 'TOP', 'name' => 'Tongan paʻanga', 'symbol' => 'T$', 'fraction_name' => 'Seniti[J]', 'decimals' => 2 ),
						'TRY' => array ( 'numeric_code'  =>	949	, 'code' => 'TRY', 'name' => 'Turkish lira', 'symbol' => 'TL', 'fraction_name' => 'Kuruş', 'decimals' => 2 ),
						'TTD' => array ( 'numeric_code'  =>	780	, 'code' => 'TTD', 'name' => 'Trinidad and Tobago dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'TWD' => array ( 'numeric_code'  =>	901	, 'code' => 'TWD', 'name' => 'New Taiwan dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'TZS' => array ( 'numeric_code'  =>	834	, 'code' => 'TZS', 'name' => 'Tanzanian shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'UAH' => array ( 'numeric_code'  =>	980	, 'code' => 'UAH', 'name' => 'Ukrainian hryvnia', 'symbol' => '₴', 'fraction_name' => 'Kopiyka', 'decimals' => 2 ),
						'UGX' => array ( 'numeric_code'  =>	800	, 'code' => 'UGX', 'name' => 'Ugandan shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						
						'UYU' => array ( 'numeric_code'  =>	858	, 'code' => 'UYU', 'name' => 'Uruguayan peso', 'symbol' => '$', 'fraction_name' => 'Centésimo', 'decimals' => 2 ),
						'UZS' => array ( 'numeric_code'  =>	860	, 'code' => 'UZS', 'name' => 'Uzbekistani som', 'symbol' => 'UZS', 'fraction_name' => 'Tiyin', 'decimals' => 2 ),
						'VEF' => array ( 'numeric_code'  =>	937	, 'code' => 'VEF', 'name' => 'Venezuelan bolívar', 'symbol' => 'Bs F', 'fraction_name' => 'Céntimo', 'decimals' => 2 ),
						'VND' => array ( 'numeric_code'  =>	704	, 'code' => 'VND', 'name' => 'Vietnamese đồng', 'symbol' => '₫', 'fraction_name' => 'Hào[K]', 'decimals' =>	10	),
						'VUV' => array ( 'numeric_code'  =>	548	, 'code' => 'VUV', 'name' => 'Vanuatu vatu', 'symbol' => 'Vt', 'fraction_name' => 'None', 'decimals' => NULL ),
						'WST' => array ( 'numeric_code'  =>	882	, 'code' => 'WST', 'name' => 'Samoan tala', 'symbol' => 'T', 'fraction_name' => 'Sene', 'decimals' => 2 ),
						'XAF' => array ( 'numeric_code'  =>	950	, 'code' => 'XAF', 'name' => 'Central African CFA franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'XCD' => array ( 'numeric_code'  =>	951	, 'code' => 'XCD', 'name' => 'East Caribbean dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'XOF' => array ( 'numeric_code'  =>	952	, 'code' => 'XOF', 'name' => 'West African CFA franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'XPF' => array ( 'numeric_code'  =>	953	, 'code' => 'XPF', 'name' => 'CFP franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2 ),
						'YER' => array ( 'numeric_code'  =>	886	, 'code' => 'YER', 'name' => 'Yemeni rial', 'symbol' => '﷼', 'fraction_name' => 'Fils', 'decimals' => 2 ),
						'ZAR' => array ( 'numeric_code'  =>	710	, 'code' => 'ZAR', 'name' => 'South African rand', 'symbol' => 'R', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						'ZMK' => array ( 'numeric_code'  =>	894	, 'code' => 'ZMK', 'name' => 'Zambian kwacha', 'symbol' => 'ZK', 'fraction_name' => 'Ngwee', 'decimals' => 2 ),
						'ZWR' => array ( 'numeric_code'  =>	0	, 'code' => 'ZWR', 'name' => 'Zimbabwean dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2 ),
						);
	}
}