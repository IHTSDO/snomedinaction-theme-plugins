<?php
/**
	Shop Products Lisitng
 * @package LMS


 */
/***************** Shop Page ******************/
if(is_shop()){

	$cs_shop_id = woocommerce_get_page_id( 'shop' );
	
	if ( !isset($_SESSION["px_page_back"]) ||  isset($_SESSION["px_page_back"])){
		$_SESSION["px_page_back"] = $cs_shop_id;
	}
	$cs_page_bulider = get_post_meta($cs_shop_id, "cs_page_builder", true);
	$builder_active='';
	$container	= 'container';
	$contentClass	= '';
	$section_container_width ='';
	
	if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none" and  $cs_xmlObject->sidebar_layout->cs_page_layout == 'left' ) {
			$contentClass	= 'cs-left';
		}
	
	if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none" and  $cs_xmlObject->sidebar_layout->cs_page_layout == 'right'  ) {
			$contentClass	= 'cs-right';
		}
					
					
	$cs_xmlObject = new stdClass();
	if(isset($cs_page_bulider) && $cs_page_bulider<>''){
		$cs_xmlObject = new SimpleXMLElement($cs_page_bulider);
	}
	
	if (count($cs_xmlObject) > 0) {
		  $cs_counter_node = $column_no = 0;
		  $fullwith_style = '';
		  $section_container_style_elements =' ';
		  if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none"){
				$fullwith_style = 'style="width:100%;"';
				$section_container_style_elements =' width: 100%;';
				echo '<div class="container">';
					echo '<div class="row">';
					if ( $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none" and $cs_xmlObject->sidebar_layout->cs_page_layout == 'left') : ?>
						<aside class="page-sidebar <?php echo esc_attr($contentClass);?>">
								 <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_xmlObject->sidebar_layout->cs_page_sidebar_left) ) : endif; ?>
						</aside>
					<?php endif; 
					  wp_reset_query();
					echo '<div class="page-content">';
			}
	
		
		if (post_password_required()) {
				echo '<header class="heading"><h6 class="transform">' . get_the_title($cs_shop_id) . '</h6></header>';
				echo cs_password_form();
		} else {
			?>
				<section class="page-section">
                    <div class="container">		
                        <!-- Row Start -->
                        <div class="row">
                            <?php
                             get_template_part( 'shop/products-loop', 'page' );
                           ?>
                          </div>
                     </div>
               </section>
				 <?php
		}
					if(isset($cs_xmlObject->column_container)){
							foreach ($cs_xmlObject->column_container as $column_container) {
					
			   // $column_class_name = $column_container->attributes();
					$cs_section_bg_image = $cs_section_bg_image_position = $cs_section_bg_color = $cs_section_padding_top = $cs_section_padding_bottom = $cs_section_custom_style = 				$cs_section_css_id = $cs_layout = $cs_sidebar_left = $cs_sidebar_right = $css_bg_image = '';
					$section_style_elements = '';
					$section_container_style_elements = '';
					$section_video_element = '';
					if ( isset( $column_container ) ){
						$column_attributes= $column_container->attributes();
						 $column_class = $column_attributes->class;
						 //cs_section_parallax
						 $parallax_class = '';
						 $parallax_data_type = '';
						 $cs_section_parallax =  $column_attributes->cs_section_parallax;
						 if(isset($cs_section_parallax) && (string)$cs_section_parallax == 'yes'){
							 echo '<script>jQuery(document).ready(function($){cs_parallax_func()});</script>';
							 $parallax_class = 'parallex-bg';
							 $parallax_data_type = ' data-type="background"';
						 }
							//echo '<pre>';
							//var_dump($column_attributes);
							$cs_section_margin_top =  $column_attributes->cs_section_margin_top;
							$cs_section_margin_bottom = $column_attributes->cs_section_margin_bottom;
							$cs_section_padding_top =  $column_attributes->cs_section_padding_top;
							$cs_section_padding_bottom = $column_attributes->cs_section_padding_bottom;
							if(isset($cs_section_margin_top) && $cs_section_margin_top !=''){
								$section_style_elements .= 'margin-top: '.$cs_section_margin_top.'px;';
							}
							if(isset($cs_section_padding_top) && $cs_section_padding_top !=''){
								$section_style_elements .= 'padding-top: '.$cs_section_padding_top.'px;';
							}
							if(isset($cs_section_padding_bottom) && $cs_section_padding_bottom !=''){
								$section_style_elements .= 'padding-bottom: '.$cs_section_padding_bottom.'px;';
							}
							if(isset($cs_section_margin_bottom) && $cs_section_margin_bottom !=''){
								$section_style_elements .= 'margin-bottom: '.$cs_section_margin_bottom.'px;';
							}
							$cs_section_border_top = $column_attributes->cs_section_border_top;
							$cs_section_border_bottom = $column_attributes->cs_section_border_bottom;
							if(isset($cs_section_border_top) && $cs_section_border_top !=''){
								$section_style_elements .= 'border-top: '.$cs_section_border_top.'px; ';
							}
							if(isset($cs_section_border_bottom) && $cs_section_border_bottom !=''){
								$section_style_elements .= 'border-bottom: '.$cs_section_border_bottom.'px; ';
							}
						 $cs_section_background_option =  $column_attributes->cs_section_background_option;
						 $cs_section_bg_image_position = $column_attributes->cs_section_bg_image_position;
						 $cs_section_bg_color = $column_attributes->cs_section_bg_color;
						 
						 if(isset($cs_section_background_option) && $cs_section_background_option =='section-custom-background-image'){
							 $cs_section_bg_image = $column_attributes->cs_section_bg_image;
							 $cs_section_bg_image_position = $column_attributes->cs_section_bg_image_position;
							 
							 $cs_section_bg_imageg = '';
							 if(isset($cs_section_bg_image) && $cs_section_bg_image !=''){
								if(isset($cs_section_parallax) && (string)$cs_section_parallax == 'yes'){
									$cs_section_bg_imageg = 'url('.$cs_section_bg_image.') '.$cs_section_bg_image_position.' fixed';
								} else {
									$cs_section_bg_imageg = 'url('.$cs_section_bg_image.') '.$cs_section_bg_image_position;
								}
							}
							$section_style_elements .= 'background: '.$cs_section_bg_imageg.' '.$cs_section_bg_color.';';
							 
						 } else if(isset($cs_section_background_option) && $cs_section_background_option =='section_background_video'){
								$cs_section_video_url = $column_attributes->cs_section_video_url;
								$section_video_class = 'video-parallex';
								$file_type = wp_check_filetype( $cs_section_video_url);
								if(isset($file_type['type']) && $file_type['type'] <> ''){
									$file_type = $file_type['type'];
								} else {
									$file_type = 'video/mp4';	
								}
								echo "<script>jQuery(document).ready(function($) {
									// declare object for video
									jQuery('#player".$rand_player_id ."').mediaelementplayer({
									// shows debug errors on screen
										success: function (mediaElement, domObject) { 
											mediaElement.addEventListener('play', function (e) {
											mediaElement.setMuted('false')
										}, true);
										mediaElement.addEventListener('ended', function (e) {
											mediaElement.play()
										}, true);
										}
										
									});
								});</script>";
								$rand_player_id = rand(6,555);
								$section_video_element = '<div class="page-section-video" style="width: 100%; height:100%; opacity: 1;">
									<video  id="player'.$rand_player_id.'" width="100%" height="100%" autoplay="true" loop="true" preload="none" volume="false" controls="controls" class="nectar-video-bg" style="visibility: visible; width: 1927px; height: 1083px;">
										<source src="'.$cs_section_video_url.'" type="'.$file_type.'">
									</video>
								</div>';
						 } else {
							 if(isset($cs_section_bg_color) && $cs_section_bg_color !=''){
								 $section_style_elements .= 'background: '.$cs_section_bg_color.';';
							 }
						 }
						$cs_section_padding_top = $column_attributes->cs_section_padding_top;
						$cs_section_padding_bottom = $column_attributes->cs_section_padding_bottom;
						
						if(isset($cs_section_padding_top) && $cs_section_padding_top !=''){
							$section_container_style_elements .= 'padding-top: '.$cs_section_padding_top.'px; ';
						}
						if(isset($cs_section_padding_bottom) && $cs_section_padding_bottom !=''){
							$section_container_style_elements .= 'padding-bottom: '.$cs_section_padding_bottom.'px; ';
						}
						
						  $cs_section_custom_style = $column_attributes->cs_section_custom_style;
						 $cs_section_css_class = $column_attributes->cs_section_css_class;
						 if($cs_section_css_class){
							 $cs_section_css_class = $cs_section_css_class;
						 }
						 
						 $cs_section_css_id = $column_attributes->cs_section_css_id;
						 if(isset($cs_section_css_id) && trim($cs_section_css_id) !=''){
							 $cs_section_css_id = 'id="'.$cs_section_css_id.'"';
						 }
						 $cs_layout = $column_attributes->cs_layout;
						 if(!isset($cs_layout) || $cs_layout == ''){
							$cs_layout = "none"; 
						 }
						 $cs_sidebar_left = $column_attributes->cs_sidebar_left;
						 $cs_sidebar_right = $column_attributes->cs_sidebar_right;
					}
					if(isset($cs_section_bg_image) && $cs_section_bg_image <> '' && $cs_section_background_option == 'section-custom-background-image'){
						$css_bg_image = 'url('.$cs_section_bg_image.')';
					}
					$section_style_element = '';
					if($section_style_elements){
						$section_style_element = 'style="'.$section_style_elements.'"';	
					}
					$section_container_style ='';
					
					if ( $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none" and ( $cs_xmlObject->sidebar_layout->cs_page_layout == 'left' || $cs_xmlObject->sidebar_layout->cs_page_layout == 'right' ) ) {
						$container	= 'container';
						$section_container_style ='style="width:100%; padding:0px"';
					}
					
				?>
								<!-- Page Section -->
								<section <?php echo esc_attr($cs_section_css_id);?> class="page-section <?php echo esc_attr($cs_section_css_class.' '.$parallax_class); ?>" <?php echo esc_attr($parallax_data_type);?>  <?php echo esc_attr($section_style_element);?> >
								<?php echo esc_attr($section_video_element);?>
								<?php 
								if(isset($cs_section_background_option) && $cs_section_background_option =='section-custom-slider'){
									$cs_section_custom_slider = $column_attributes->cs_section_custom_slider;
									if($cs_section_custom_slider != ''){
										$shortcode = html_entity_decode($shortcode);
										echo do_shortcode($cs_section_custom_slider);
									}
								} 
								?>
								<!-- Container Start -->
								<div  class="<?php echo sanitize_html_class($container);?>"  <?php echo balanceTags($section_container_style);?>>
									<!-- Row Start -->
									<div class="row">
										<?php
										if (isset($cs_layout) && $cs_layout == "left" && $cs_sidebar_left <> '') {
												echo '<aside class="page-sidebar '.$contentClass.'">';
													if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_left)) : endif;
												echo '</aside>';
										}
										if (isset($cs_layout) && $cs_layout != "none"){
											echo '<div class="page-content"><div class="row">';	
										}
									   
										foreach ($column_container->children() as $column) {
										   
											$column_no++;
											foreach ($column->children() as $cs_node) {
												if ( $cs_node->getName() == "course" ) {
													$cs_counter_node++;
													get_template_part( 'page_course', 'page' );
												} else {
													$shortcode =trim((string)$cs_node->cs_shortcode);
													$shortcode = html_entity_decode($shortcode);
													echo do_shortcode($shortcode);
												}
											}
										}
										if (isset($cs_layout) && $cs_layout != "none"){
											echo '</div></div>';
										}
									   if (isset($cs_layout) && $cs_layout == "right" && $cs_sidebar_right <> '') {
											echo '<aside class="page-sidebar '.$contentClass.'">';
												if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_right)) : endif;
											echo '</aside>';
									  }
										?>
										</div></div>
										</section>
								<?php
								$column_no = 0;
							}
					}
							if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none"){				
								echo '</div>';
							}
							if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none" and $cs_xmlObject->sidebar_layout->cs_page_layout == 'right') : ?>
								<aside class="page-sidebar <?php echo esc_attr($contentClass);?>">
										<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_xmlObject->sidebar_layout->cs_page_sidebar_right) ) : endif; ?>
								 </aside>
							<?php endif; 
						if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none"){	
								echo '</div>';
							echo '</div>';
						}
					} else {
						?>
                       <section class="page-section">
                            <div class="container">		
                                <!-- Row Start -->
                                <div class="row">
									<?php
                                     get_template_part( 'shop/products-loop', 'page' );
                                   ?>
                                  </div>
                             </div>
                       </section>
	<?php
		} 


}
?>