<?php
/**
 * The template for displaying all single posts
 */
 	global $cs_node,$post,$cs_theme_options,$cs_counter_node;	
	$cs_uniq = rand(40, 9999999);
	if ( is_single() ) {
		cs_set_post_views($post->ID);
	}	
	$cs_node = new stdClass();
  	get_header();
 	$cs_layout = '';
	$leftSidebarFlag	= false;
	$rightSidebarFlag	= false;
	?>
	<!-- PageSection Start -->
	<section class="page-section blog-editor" style=" padding: 0; "> 
  	<!-- Container -->
  	<div class="container"> 
    	<!-- Row -->
    	<div class="row">
      	<?php
		if (have_posts()):
			while (have_posts()) : the_post();	
				$cs_tags_name = 'post_tag';
				$cs_categories_name = 'category';
				$postname = 'post';
				$image_url = cs_get_post_img_src($post->ID, 844, 475);	
				$post_xml = get_post_meta($post->ID, "post", true);	
			if ( $post_xml <> "" ) {			
				$cs_xmlObject = new SimpleXMLElement($post_xml);
				$cs_layout 			= $cs_xmlObject->sidebar_layout->cs_page_layout;
				$cs_sidebar_left 	= $cs_xmlObject->sidebar_layout->cs_page_sidebar_left;
				$cs_sidebar_right   = $cs_xmlObject->sidebar_layout->cs_page_sidebar_right;
				if(isset($cs_xmlObject->cs_related_post))
					$cs_related_post = $cs_xmlObject->cs_related_post;
				else 
					$cs_related_post = '';				
				if(isset($cs_xmlObject->cs_post_tags_show))
					$post_tags_show = $cs_xmlObject->cs_post_tags_show;
				else 
					$post_tags_show = '';				
				if(isset($cs_xmlObject->post_social_sharing))
					$cs_post_social_sharing = $cs_xmlObject->post_social_sharing;
				else 
					$cs_post_social_sharing = '';
				
				if(isset($cs_xmlObject->cs_post_author_info_show))
					 $cs_post_author_info_show = $cs_xmlObject->cs_post_author_info_show;
				else 
					$cs_post_author_info_show = '';

				if ( $cs_layout == "left") {
					$cs_layout = "page-content blog-editor";
					$custom_height = 408;
					$leftSidebarFlag	= false;
				}
				else if ( $cs_layout == "right" ) {
					$cs_layout = "page-content blog-editor";
					$custom_height = 408;
					$rightSidebarFlag	= true;
				}
				else {
					$cs_layout = "page-content-fullwidth";
					$custom_height = 408;
				}
				$postname = 'post';
			}else{
				$cs_layout = isset($cs_theme_options['cs_single_post_layout']) ? $cs_theme_options['cs_single_post_layout'] : '';
				if ( isset( $cs_layout ) && $cs_layout == "sidebar_left") {
					$cs_layout = "page-content blog-editor";
					$cs_sidebar_left	= $cs_theme_options['cs_single_layout_sidebar'];
					$custom_height = 408;
					$leftSidebarFlag	= true;
				} else if ( isset( $cs_layout ) && $cs_layout == "sidebar_right" ) {
					$cs_layout = "page-content blog-editor";
					$cs_sidebar_right	= $cs_theme_options['cs_single_layout_sidebar'];
					$custom_height = 408;
					$rightSidebarFlag	= true;
				} else {
					$cs_layout = "page-content-fullwidth";
					$custom_height = 408;
				}
  				$post_pagination_show = 'on';
				$post_tags_show = '';
				$cs_related_post = '';
				$post_social_sharing = '';
				$post_social_sharing = '';
				$cs_post_author_info_show = '';
				$postname = 'post';
				$cs_post_social_sharing = '';
			}
			if ($post_xml <> "") {
				$cs_xmlObject = new SimpleXMLElement($post_xml);
				$post_view = $cs_xmlObject->post_thumb_view;
				$inside_post_view = $cs_xmlObject->inside_post_thumb_view;
				$post_video = $cs_xmlObject->inside_post_thumb_video;
				$post_audio = $cs_xmlObject->inside_post_thumb_audio;
				$post_slider = $cs_xmlObject->inside_post_thumb_slider;
				$cs_related_post = $cs_xmlObject->cs_related_post;
				$cs_post_social_sharing = $cs_xmlObject->post_social_sharing;
				$post_tags_show = $cs_xmlObject->post_tags_show;
				$post_pagination_show = $cs_xmlObject->post_pagination_show;
				$cs_post_author_info_show = $cs_xmlObject->post_author_info_show;
				$postname = 'post';				
			}
			else {
				$cs_xmlObject = new stdClass();
				$post_view = '';
				$post_video = '';
				$post_audio = '';
				$post_slider = '';
				$post_slider_type = '';
				$cs_related_post = '';
				$post_pagination_show = '';
				$image_url = '';
				$width = 0;
				$height = 0;
				$image_id = 0;
				$cs_post_author_info_show = '';
				$postname = 'post';				
				$cs_xmlObject->post_social_sharing = '';
			}		
		$custom_height = 408;	
		$width  = 842;
		$height = 474;
		$image_url = cs_get_post_img_src($post->ID, $width, $height);
		?>
      <!--Left Sidebar Starts-->
      <?php if ($leftSidebarFlag == false){ ?>
          <aside class="page-sidebar">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_left) ) : ?>
                <?php endif; ?>
          </aside>
      <?php } ?>
      <!--Left Sidebar End--> 
      
      <!-- Blog Detail Start -->
      <div class="<?php echo esc_attr($cs_layout); ?>"> 
        <!-- Blog Start --> 
        <!-- Row -->   
            <div class="col-md-12">
                <div class="main-post">
                     <?php 
                        if (isset($inside_post_view) and $inside_post_view <> '') {
                        $viewType	= '<i class="icon-camera"></i>';
                        if( $inside_post_view == "Slider"){
                            $viewType = '<i class="icon-unsorted"></i>';
                            echo '<figure class="detailpost">';
                                cs_post_flex_slider($width,$height,get_the_id(),'post');
                            echo '</figure>';
                        } else if ($inside_post_view == "Single Image" && $image_url <> '') { 
                            $viewType	= '<i class="icon-camera"></i>';
                            echo '<figure class="detailpost">';
                                echo '<img src="'.$image_url.'" alt="" >';
                            echo '</figure>';
                        } elseif ( $inside_post_view == "Video" and $post_video <> '' and $inside_post_view <> '' ) {
                            $viewType	= '<i class="icon-film"></i>';
                            $url = parse_url($post_video);
                            if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                          <?php
                                echo '<figure class="detailpost">';
                                echo do_shortcode('[video width="'.$width.'" height="'.$height.'" mp4="'.$post_video.'"][/video]');
                                echo '</figure>';
                            } else {
                                $video	= wp_oembed_get($post_video,array('height' => $custom_height));
                                $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                                echo '<figure class="detailpost">';
                                    echo  str_replace($search,'',$video);
                                echo '</figure>';
                            }
                        } elseif ($inside_post_view == "Audio" and $inside_post_view <> ''){  
                            $viewType = '<i class="icon-microphone"></i>';
                        ?>
                            <figure class="detail_figure">
                              <?php
                                    echo '<figure class="detailpost">';
                                        echo do_shortcode('[audio mp3="'.$post_audio.'"][/audio]');
                                    echo '</figure>';
                              ?>
                            </figure>
                    <?php    
                        }
                    }
                    ?>
                    <h2><?php the_title();?></h2>
                    </div>
             <div class="tablerow">
               <div class="date-time">
                  <time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n('M',strtotime(get_the_date()));?>
                  	<small>
						<?php // echo date_i18n('j',strtotime(get_the_date()));?>
                    </small>
                  </time>
                <?php
                if(isset($viewType)){
					echo cs_allow_special_char( $viewType );
				}
				?>
               </div>
               <div class="post-option-panel">
                   <div class="post-thumb">
                      <ul class="thumb-options">
                         <li><?php _e('in','dir');?>
                           <?php 
                            if ( isset($cs_blog_cat) && $cs_blog_cat !='' && $cs_blog_cat !='0'){ 
                                echo '<a href="'.site_url().'?cat='.$row_cat->term_id.'">'.$row_cat->name.'</a>';
                             } else {
                                 /* Get All Tags */         
                                  $categories_list = get_the_term_list ( get_the_id(), 'category', '' , ', ', '' );
                                  if ( $categories_list ){
                                    printf( __( '%1$s', 'dir'),$categories_list );
                                  } 
                                 // End if Tags 
                             }
                        ?>
                        </li>
                        <li>
							<?php 
								if ( empty($cs_xmlObject->post_tags_show_text) ) 
									$post_tags_show_text = __('Tags', 'dir'); else $post_tags_show_text = $cs_xmlObject->post_tags_show_text; 
									echo esc_html($post_tags_show_text);
						    		$categories_list = get_the_term_list ( get_the_id(), 'post_tag', '', ', ', '' );
								  if ( $categories_list ){?>
								  <?php printf( __( '%1$s', 'dir'),$categories_list );
							  }
							?>
                        </li>
                      </ul>
                   </div>                   
                <div class="rich_editor_text"><?php the_content();?></div>
                <?php
					 $thumb_ID = get_post_thumbnail_id( $post->ID );
					 if ( $images = get_children(array(
					   'post_parent' => get_the_ID(),
					   'post_type' => 'attachment',
					  // 'post_mime_type' => 'image',
					   'exclude' => $thumb_ID,
					  ))) { ?>
                 <div class="cs-attachments">
                  <h6><?php _e('Attachments','dir');?></h6>
                  <ul>
					<?php
                      foreach( $images as $image ) {  ?>                        
                        <?php if ( $image->post_mime_type == 'image/png' 
                          		|| $image->post_mime_type == 'image/gif' 
                                || $image->post_mime_type == 'image/jpg'
                                || $image->post_mime_type == 'image/jpeg'
                              ) { 
							  	
								$image_url = cs_attachment_image_src((int)$image->ID, 150, 150 );
							  	
								?>
                          		 <li>
                                 	<figure>
                                    	<a href="<?php echo esc_url($image->guid);?>">
                                        <img src="<?php echo esc_url($image_url);?>" alt="<?php echo esc_attr($image->post_name);?>"></a>
                                    </figure>
                                   </li>
								 <?php } else if ( $image->post_mime_type == 'application/zip' ) { ?>
                                 <li>
                                 	<figure>
                                    	 <a href="<?php echo esc_url($image->guid);?>"><i class="icon-file-zip-o"></i></a>
                                    </figure>
                                  </li>
                                 <?php }else if ( $image->post_mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ) { ?>
                                 <li>
                                 	<figure>
                                    	<a href="<?php echo esc_url($image->guid);?>"><i class="icon-file-word-o"></i></a>
                                    </figure>
                                 </li>
                                 <?php } else if ( $image->post_mime_type == 'text/plain' ) { ?>
                                 <li>
                                 	<figure>
                                    	<a href="<?php echo esc_url($image->guid);?>"><i class="icon-file-text"></i></a>
                                    </figure>
                                  </li>
                                 <?php } else if ( $image->post_mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) { ?>
                                 <li>
                                 	<figure>
                                    	<a href="<?php echo esc_url($image->guid);?>"><i class="icon-file-excel-o"></i></a>
                                    </figure>
                                 </li>
                                 <?php } else { ?>
                                 <li>
                                 	<figure>
                                    	<a href="<?php echo esc_url($image->guid);?>"><i class="icon-align-justify"></i></a>
                                    </figure>
                                 </li>
                                 <?php } ?>
                        </li>
                    <?php } ?>
                    <!--
                    <li>
                      <figure> <a data-toggle="modal" data-target="#myModal" class="cs-add" href="#"><i class="icon-file-audio-o"></i></a> </figure>
                    </li>
                    -->
                  </ul>
                </div>
               <?php } ?>
                <div class="detail-post">
                  <div class="socialmedia">
                    <?php  
			  		if ($cs_post_social_sharing == "on"){
						if ( empty($cs_xmlObject->post_social_sharing_text) ) $post_social_sharing_text = __('Share', 'dir'); else $post_social_sharing_text = $cs_xmlObject->post_social_sharing_text;
							cs_social_share_blog(false,true,$post_social_sharing_text);
					 }?>
                  </div>
                </div>
              </div>
          </div>
          <!-- Post Content End--> 

          <!-- Post Button Start-->
           <?php if(isset($post_pagination_show) &&  $post_pagination_show == 'on'){
                  cs_next_prev_custom_links('post');
             }
          ?>           
          <!-- Col Author Start -->
          <?php if(isset($cs_post_author_info_show) &&  $cs_post_author_info_show == 'on'){ cs_author_description('show');} ?>

          <!-- Col Recent Posts Start -->
          <?php if( $cs_related_post =='on' ){
			if ( empty($cs_xmlObject->cs_related_post_title) ) $cs_related_post_title = __('Related Posts', 'dir'); else $cs_related_post_title = $cs_xmlObject->cs_related_post_title;
			
		  ?>
          <div class="post-recent">
            <div class="cs-section-title">
              <h2><?php echo esc_attr($cs_related_post_title);?></h2>
            </div>
            <div class="row">
              <?php 
				  $custom_taxterms='';
				  $width = '370';
				  $height = '280';
				  $title_limit  = 6;
				  $excerpt		= '140';
				  $custom_taxterms = wp_get_object_terms( $post->ID, array($cs_categories_name, $cs_tags_name), array('fields' => 'ids') );
				  $args = array(
					  'post_type' => $postname,
					  'post_status' => 'publish',
					  'posts_per_page' => 3,
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
				 while ($custom_query->have_posts()) : $custom_query->the_post();
					$image_url = cs_get_post_img_src($post->ID, $width, $height);
					
					if($image_url == ''){
						$img_class = 'no-image';	
						$image_url	= get_template_directory_uri().'/assets/images/no-image16x9.jpg';
					}else{
						$img_class  = '';
					}						 
					?>
              		<div class="col-md-4 post-<?php echo absint($post->ID);?>">
                  		<!-- Article -->
                  		<div class="cs-blog blog-grid">
						<?php if($image_url <> ""){?>
                            <div class="main-thumb">
                            	<figure>
                                	<a href="<?php esc_url(the_permalink());?>"><img alt="thumbnail" src="<?php echo esc_url( $image_url );?>"></a>
                              <figcaption>
                              		<a class="hover-icon" href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png"></a>
                              </figcaption>
                            </figure>
                          </div>
                        <?php }?>
                        <div class="bloginfo-sec">
                          <ul class="post-options">
                                <?php if ( is_sticky() ){?>
                                	<li><?php cs_featured(); ?></li>
								<?php }?>
                                <li>
                                	<time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time>
                                </li>  
                          </ul>
                          <h2><a href="<?php esc_url(the_permalink());?>">
							<?php echo wp_trim_words(get_the_title(),$title_limit,'...');	?>
						  	</a>
                          </h2>
                          <p><?php echo cs_get_the_excerpt($excerpt,'ture','');?></p> 
                          <div class="post-thumb">
                              <ul class="thumb-options">
                                <li><?php _e('Posted by','dir');?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author();?></a></li>  
                              </ul>
                          </div>
                        </div>
                  		</div>
                  		<!-- Article Close -->
              		</div>
              <?php endwhile; wp_reset_postdata(); ?>
          </div>
          </div>
           <?php } ?>
          <!-- Col Comments Start -->
		  <?php comments_template('', true); ?>
          <!-- Col Comments End -->      
          <!-- Col Recent Posts End --> 
          </div>
          <!-- Blog Post End --> 
        <!-- Blog End --> 
      </div>
      <!-- Blog Detail End --> 
      <!-- Right Sidebar Start --> 
		<?php if ($rightSidebarFlag == true){ ?>
      		<aside class="page-sidebar">
       			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_right) ) : endif; ?>
      		</aside>
      <?php } ?>
      <!-- Right Sidebar End -->
      <?php endwhile;   
		endif;?>
    </div>
  </div>
</section>
<!-- PageSection End --> 
<!-- Footer -->
<?php get_footer(); ?>
