<?php
//======================================================================
// Adding directory categories start
//=====================================================================
if (!function_exists('cs_directory_categories_shortcode')) {
	function cs_directory_categories_shortcode($atts, $content = "") {
		global $post;
		$defaults = array( 'column_size'=>'','cs_directory_categories_title' => '','cs_directory_categories_view' => 'grid','cs_directory_categories_page' => '','cs_directory_categories_bg_color' => '','cs_directory_categories_txt_color' => '', 'cs_directory_categories_cats' => '','cs_directory_categories_number' => '','cs_directory_categories_order' => '','cs_custom_class' => '','cs_custom_animation' => '');	
			extract( shortcode_atts( $defaults, $atts ) );
			$column_class  = cs_custom_column_class($column_size);
			$catColumn	= 'col-md-3';
			
			if(isset($cs_directory_categories_view) and $cs_directory_categories_view == 'grid'){
				$cat_class = 'cat-clean';
				$catColumn	= 'col-md-3';
			} else if( isset( $cs_directory_categories_view ) and $cs_directory_categories_view == 'gradient'){
				$cat_class = 'cat-gradient';
				$catColumn	= 'col-md-3';
			} else if(isset($cs_directory_categories_view) and $cs_directory_categories_view == 'simple'){
				$cat_class = 'cat-simple';
				$catColumn	= 'col-md-3';
			} else if(isset($cs_directory_categories_view) and $cs_directory_categories_view == 'image'){
				$cat_class = 'cat-image';
				$catColumn	= 'col-md-3';
			} else {
				$cat_class = '';
			}
			
			$sectionTitle	= '';
			
			if ( isset ( $cs_directory_categories_title ) && $cs_directory_categories_title !='' ) {
				$sectionTitle	= '<div class="cs-section-title"><h2>'.$cs_directory_categories_title.'</h2></div>';
			}
			
			// Number of categories to show
			$cs_directory_categories_number = ( is_numeric($cs_directory_categories_number) && $cs_directory_categories_number > 0 ) ? $cs_directory_categories_number : '10';
			
			$html = '';
			$html .= '<div class="cs_directory_categories '.$cat_class.' '.$column_class.' '.$cs_custom_class.' '.$cs_custom_animation.'">
				'.$sectionTitle.'
				<!--<ul class="row">-->';
				if(!empty($cs_directory_categories_cats)){
					$cs_directory_categories_cats = explode(",", $cs_directory_categories_cats);
					if(is_array($cs_directory_categories_cats)){
						$args = array(
							'posts_per_page'			=> "-1",
							'post_type'					=> 'directory_types',
							'post_status'				=> 'publish',
							'orderby'					=> 'ID',
							'order'						=> 'ASC',
						);
						$custom_query = new WP_Query($args);
						$conf_cats = array();
						while ( $custom_query->have_posts() ) : $custom_query->the_post();
							$conf_cats[] = $post->ID;
						endwhile;
						wp_reset_query();
						$exec_code = true;
						foreach($cs_directory_categories_cats as $cats){
							if(!in_array($cats, $conf_cats)){
								$exec_code = false;
								break;
							}
						}
						if($exec_code == true){
							
							
							$cs_directory_categories_sort_cats = array();
							//Directory Types foreach
							if( $cs_directory_categories_order == 'title' ){
								foreach($cs_directory_categories_cats as $type){
									$cs_directory_categories_sort_cats[] = get_the_title($type);
								}
								
								asort($cs_directory_categories_sort_cats);
								
								$cs_directory_categories_sort_cats_id = array();
								foreach($cs_directory_categories_sort_cats as $type){
									
									$page	= get_page_by_title( html_entity_decode($type), 'OBJECT', 'directory_types' );
									if( isset( $page->ID ) ) {
										$cs_type_id = $page->ID; 
										if( $cs_type_id <> '' ){
											$cs_directory_categories_sort_cats_id[] = $cs_type_id;
										}
									}
								}
							}
							else{
								$cs_directory_categories_sort_cats_id = $cs_directory_categories_cats;
							}
							
							foreach($cs_directory_categories_sort_cats_id as $type){
								
								$directory_categories_array = get_post_meta($type, "directory_types_categories", true);
								$directory_categories_array = explode(",", $directory_categories_array);
								$total_post_count = 0;
								
								$cs_total_posts	= cs_get_catgory_posts_count( $type );
								
								if(!empty($directory_categories_array) and is_array($directory_categories_array)){
									foreach($directory_categories_array as $cats){
										if(!empty($cats)){	
											$term = get_term_by( 'slug', $cats ,'directory-category');
											if(is_object($term)){
												$total_post_count += $term->count;
											}
										}
									}
									//end categories foreach
								}
								
								$directory_type_icon_imge = '';
								$directory_type_icon = get_post_meta($type, "cs_post_type_icon_input", true);
								if($directory_type_icon <> '') {
									$directory_type_icon_id = cs_get_attachment_id_from_url($directory_type_icon);
									if( isset( $directory_type_icon_id ) && $directory_type_icon_id != '' && $cs_directory_categories_view != 'image' ) {
										$width  = 150;
										$height = 150;
										$type_icon_image_url = cs_attachment_image_src((int)$directory_type_icon_id, $width, $height);
										$directory_type_icon_imge = '<img src="'.$type_icon_image_url.'" alt="" />';
									} else {
										$directory_type_icon_imge = '<img src="'.$directory_type_icon.'" alt="" />';
									}
									
								}
								
								$cs_list_page_id = $cs_directory_categories_page;
								$view_all_txt = $total_post_count == 0 ? __('No Post', 'directory') : __('View All', 'directory');
								
								if($cs_directory_categories_view == 'list'){
									
									$html .= '
									<!--<li class="'. sanitize_html_class($catColumn).'">-->
										<!--div class="cat-inner" style="background-color:'.$cs_directory_categories_bg_color.';color:'.$cs_directory_categories_txt_color.';">
											'.$directory_type_icon_imge.'
											<a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;submit=" style="color:'.$cs_directory_categories_txt_color.';">'.get_the_title($type).'</a>
											<a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;submit=" style="color:'.$cs_directory_categories_txt_color.';">('.$view_all_txt.')</a>
										</div>-->';
										
										if(!empty($directory_categories_array) and is_array($directory_categories_array)){
											$html 		  .= '<ul class="category-list">';
											$cats_counter  = 1;
											$cs_link 	   = '';
											$cs_more_cats  = '';
											
											asort($directory_categories_array);
											foreach($directory_categories_array as $cats){
												
												$cs_end ='';
												if(!empty($cats) && is_object($term)){	
													$term = get_term_by( 'slug', $cats ,'directory-category');
													if(is_object($term)){
														$term_id  = $term->term_id;
														$cat_meta = get_option( "directory_cat_$term_id" );
														if($cat_meta){
															if(isset($cat_meta['icon'])) $cat_img = '<i class="fa '.$cat_meta['icon'].'" style="color:'.$cs_directory_categories_txt_color.';"></i>';
														}
														if ($cs_directory_categories_number == $cats_counter ){
															// break;
															$this_class ="this.getAttribute('class')";
															$cs_link   .='<a class="cs-link-more cs-link-more-'.$type.' collapsed" onclick="cs_slide_toogle('.$this_class.','.$type.')" data-toggle="collapse" href="#'.$type.'" aria-expanded="false" aria-controls="'.$type.'">
															<i class="icon-plus8"></i>more categories</a>';
														}
														
														if ((int)$cs_directory_categories_number < $cats_counter ){
															$cs_more_cats .= '<li class="cat-item"><a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;directory_categories='.$term->slug.'&amp;submit=">'.$term->name.'</a><span>'.$term->count.'</span></li>';
														}
														
														if ((int)$cs_directory_categories_number >= $cats_counter ){
															$html .= '<li class="cat-item"><a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;directory_categories='.$term->slug.'&amp;submit=">'.$term->name.'</a><span>'.$term->count.'</span></li>';
														}
														
														$cats_counter++;
 													}
												}
											}
											
											if ( ($cs_directory_categories_number+1) < $cats_counter ){
												$html .= '<li class="collapse" id="'.$type.'"><ol>';
												$html .= $cs_more_cats;
												$html .= '</ol></li><br>';
											}
											
											//end categories foreach
											$html .= '<!--</ul>-->';
											if ( ($cs_directory_categories_number+1) < $cats_counter ){
												$html .= $cs_link;
											}
											
										}
									$html .= '<!--</li>-->';
								
								}
								else if($cs_directory_categories_view == 'simple'){
									$html .= '
									<li class="'. sanitize_html_class($catColumn).'">
										<div class="cat-inner" style="background-color:'.$cs_directory_categories_bg_color.';color:'.$cs_directory_categories_txt_color.';">
											<a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;submit=" style="color:'.$cs_directory_categories_txt_color.';">'.get_the_title($type).'</a>
										</div>';
										
										if(!empty($directory_categories_array) and is_array($directory_categories_array)){
											$html .= '<ul>';
											$cats_counter 	= 1;
											$cs_link 		= '';
											$cs_more_cats 	= '';
											asort($directory_categories_array);
											foreach($directory_categories_array as $cats){
												
												$cs_end = '';
												if(!empty($cats) && is_object($term)){	
													$term = get_term_by( 'slug', $cats ,'directory-category');
													if(is_object($term)){
														$term_id = $term->term_id;
														$cat_meta = get_option( "directory_cat_$term_id" );
														if($cat_meta){
															if(isset($cat_meta['icon'])) $cat_img = '<i class="fa '.$cat_meta['icon'].'" style="color:'.$cs_directory_categories_txt_color.';"></i>';
														}
													if ($cs_directory_categories_number == $cats_counter ){
														// break;
														$this_class ="this.getAttribute('class')";
														$cs_link .='<a class="cs-link-more cs-link-more-'.$type.' collapsed" onclick="cs_slide_toogle('.$this_class.','.$type.')" data-toggle="collapse" href="#'.$type.'" 
														aria-expanded="false" aria-controls="'.$type.'">
														<i class="icon-plus8"></i>more categories</a>';
													}
													
													if ((int)$cs_directory_categories_number < $cats_counter ){
														$cs_more_cats .= '<li><a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;directory_categories='.$term->slug.'&amp;submit=">'.$term->name.'</a></li>';
													}
													
													if ((int)$cs_directory_categories_number >= $cats_counter ){
														$html .= '<li><a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;directory_categories='.$term->slug.'&amp;submit=">'.$term->name.'</a></li>';
													}
													
 													$cats_counter++;
 												 }

												}
												
											}
											
											if ( ($cs_directory_categories_number+1) < $cats_counter ){
												$html .= '<li class="collapse" id="'.$type.'"><ol>';
												$html .= $cs_more_cats;
												$html .= '</ol></li>';
											}
											
											//end categories foreach
											$html .= '</ul>';
											if ( ($cs_directory_categories_number+1) < $cats_counter ){
												$html .= $cs_link;
											}
											
										}
									$html .= '</li>';
								
								
								} else if($cs_directory_categories_view == 'gradient'){
									$html .= '<li class="'. sanitize_html_class($catColumn).'">
									
									<div class="cat-inner" style="background-color:'.$cs_directory_categories_bg_color.';color:'.$cs_directory_categories_txt_color.';">'.$directory_type_icon_imge.'
									<a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;submit=" style="color:'.$cs_directory_categories_txt_color.';">'.get_the_title($type).'</a></div></li>';
								} else if($cs_directory_categories_view == 'thumbnail'){
									$html .= '<li class="'. sanitize_html_class($catColumn).'">
									<div class="cat-inner" style="background-color:'.$cs_directory_categories_bg_color.';color:'.$cs_directory_categories_txt_color.';">'.$directory_type_icon_imge.'
									<a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;submit=" style="color:'.$cs_directory_categories_txt_color.';">'.get_the_title($type).'</a>('.$cs_total_posts.')</div></li>';
								
								
								} else if($cs_directory_categories_view == 'image'){
									
									asort($directory_categories_array);
									$cs_cats_image_counter = 1;
									foreach($directory_categories_array as $cats){
										
										$cat_img = '';
										if(!empty($cats) && is_object($term)){	
											$term = get_term_by( 'slug', $cats ,'directory-category');
											if(is_object($term)){
												$term_id = $term->term_id;
												$cat_meta = get_option( "directory_cat_$term_id" );
												if($cat_meta){
													if(isset($cat_meta['image'])) $cat_img = '<img src="'.$cat_meta['image'].'" alt="" />';
												}
												
												if ($cs_directory_categories_number >= $cs_cats_image_counter ){
													$html .= '<article class="col-md-3"><figure>'.$cat_img.'<figcaption><a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;directory_categories='.$term->slug.'&amp;submit=">'.$term->name.'</a><figcaption></figure></article>';
												}
											}
										}
										
										$cs_cats_image_counter++;
									}
																		
								} else {
									$html .= '<li class="'. sanitize_html_class($catColumn).'">
									<div class="cat-inner" style="background-color:'.$cs_directory_categories_bg_color.';color:'.$cs_directory_categories_txt_color.';">'.$directory_type_icon_imge.'
									<a href="'.get_page_link($cs_directory_categories_page).'?&amp;filter=all&amp;type='.get_post($type)->post_name.'&amp;submit=" style="color:'.$cs_directory_categories_txt_color.';">'.get_the_title($type).'</a>('.$cs_total_posts.')</div></li>';
								}
							}
							//end Directory Types foreach
						}
					}
				}
				$html .= 
				'</ul>
			  </div>';
			$html = do_shortcode($html);
			return $html;
	}
	add_shortcode('cs_directory_categories', 'cs_directory_categories_shortcode');
}

function cs_get_catgory_posts_count( $id='' ){
	
	if ( $id == ''){
		return 0;
	}
	
	$meta_compare = "=";
	$meta_value   = $id;
	$meta_key	  = 'directory_type_select';

	$args = array(
		'posts_per_page'			=> "-1",
		'post_type'					=> 'directory',
		'post_status'				=> 'publish',
	);
	
	$meta_fields_array = array();
	$meta_fields_array = array('relation' => 'AND',);
	$meta_fields_array[] = array(
							'key' 		=> $meta_key,
							'value' 	=> $meta_value,
							'compare'   => $meta_compare,
						);
	
	if(is_array($meta_fields_array) && count($meta_fields_array)>1){
		$args['meta_query'] = $meta_fields_array;
	}
	
	$custom_query = new WP_Query($args);
	return $custom_query->post_count;
}
