<?php 


if ( !class_exists('BlogTemplates') ) {    
    class BlogTemplates
    {        
        function __construct()
        {
            // Constructor Code here..
   		}
        //======================================================================
        // Blog Small View
        //======================================================================
        public function cs_small_view( $description,$excerpt,$args,$cs_category, $animation ) {
            global $post;
            $width = '370';
            $height = '280';
            $title_limit = 1000;
            $query = new WP_Query( $args );
            $post_count = $query->post_count;
            if ( $query->have_posts() ) {  
              $postCounter    = 0;
              $blogObject    = new BlogTemplates();
              while ( $query->have_posts() )  : $query->the_post();             
              	$thumbnail = cs_get_post_img_src( $post->ID, $width, $height );                
              $post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view  = $cs_xmlObject->post_thumb_view;
                    $post_thumb_audio = $cs_xmlObject->post_thumb_audio;
                    $post_thumb_video = $cs_xmlObject->post_thumb_video;
				}else{
                    $post_thumb_view  = '';
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
 				}
            ?>
            <div class="col-md-12 post-<?php echo intval($post->ID);?> <?php echo cs_allow_special_char($animation);?>"> 
                <div class="cs-blog blog-medium blog-small">
              <?php if ( $post_thumb_view == 'Single Image' ){
						if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
						  <div class="main-thumb">
							<figure>
                            	<a href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo esc_url( $thumbnail );?>"></a>
                                  <figcaption>
                                    <a class="hover-icon" href="<?php esc_url(the_permalink());?>">
                                        <img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png">
                                    </a>
                                  </figcaption>
							</figure>
						  </div>
						<?php }
               } else if ( $post_thumb_view == 'Slider' ) {
                        echo '<div class="main-thumb">';
                                cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                        echo '</div>';
               } elseif ($post_thumb_view == "Audio" and $post_thumb_view <> ''){  
                       $thumbView = '<i class="icon-microphone"></i>';                    
                ?>
                    <div class="main-thumb">
                      <?php
                      echo do_shortcode('[audio mp3="'.$post_thumb_audio.'"][/audio]');
                      ?>
                    </div>
                <?php    
                }
                else if ( $post_thumb_view == "Video" and $post_thumb_video <> '' and $post_thumb_view <> '' ) {
                    $thumbView    = '<i class="icon-film"></i>';
                    $url = parse_url($post_thumb_video);
                    	if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                  <?php
                          echo '';
                    }else {
                        $video = wp_oembed_get($post_thumb_video, array('height' => '290'));
                        $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                        echo str_replace($search,'',$video);
                    	}
                 }
                ?>
              <div class="bloginfo-sec">
                  <?php cs_featured(); ?>
                  <h2>
                  	<a href="<?php esc_url(the_permalink());?>">
				  		<?php echo wp_trim_words(get_the_title(),$title_limit); ?>
                    </a>
                  </h2>
                  <?php if ($description == 'yes') {?>
                  <p> 
				  <?php 
				  	// echo wp_trim_words(get_the_content,$excerpt,'...');
				  	echo cs_get_the_excerpt($excerpt,'ture','');?>
                   </p>
					<?php 
					} 
				  
				  ?> 
                  <div class="post-thumb">
                      <ul class="thumb-options">
                        <li><?php _e('Posted On','dir');?>
                        	<time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>">
								<?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?>
                            </time>
                         </li>  
                         <li>
                          <?php  $this->cs_get_categories( $cs_category );?>
                         </li>
                      </ul>
                      <a href="<?php esc_url(the_permalink());?>" class="read-more"><?php _e('Read more','dir');?></a>
                    </div>
                </div>
              </div>
            </div>
            <?php endwhile;
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    esc_html_e('No Record Found','dir');
                echo '</p></div></div>';
            }
        }
		
		//======================================================================
        // Blog Small Thumb View
        //======================================================================
        public function cs_small_thumb_view( $description,$excerpt,$args,$cs_category, $animation ) {
            global $post;
            $width = '370';
            $height = '280';
            $title_limit = 1000;
            $query = new WP_Query( $args );
            $post_count = $query->post_count;
            if ( $query->have_posts() ) {  
              $postCounter    = 0;
              $blogObject    = new BlogTemplates();
              while ( $query->have_posts() )  : $query->the_post();             
              	$thumbnail = cs_get_post_img_src( $post->ID, $width, $height );                
              $post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view  = $cs_xmlObject->post_thumb_view;
                    $post_thumb_audio = $cs_xmlObject->post_thumb_audio;
                    $post_thumb_video = $cs_xmlObject->post_thumb_video;
				}else{
                    $post_thumb_view  = '';
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
 				}
            ?>
            <div class="col-md-12 post-<?php echo intval($post->ID);?> <?php echo cs_allow_special_char($animation);?>"> 
                <div class="cs-blog blog-medium blog-small-thumb">
                <?php if ( $post_thumb_view == 'Single Image' ){
						if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
						  <div class="main-thumb">
							<figure>
                            	<a href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo esc_url( $thumbnail );?>"></a>
                                  <figcaption>
                                    <a class="hover-icon" href="<?php esc_url(the_permalink());?>">
                                        <img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png">
                                    </a>
                                  </figcaption>
							</figure>
						  </div>
						<?php }
               } else if ( $post_thumb_view == 'Slider' ) {
                        echo '<div class="main-thumb">';
                                cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                        echo '</div>';
               } elseif ($post_thumb_view == "Audio" and $post_thumb_view <> ''){  
                       $thumbView = '<i class="icon-microphone"></i>';                    
                ?>
                    <div class="main-thumb">
                      <?php
                      echo do_shortcode('[audio mp3="'.$post_thumb_audio.'"][/audio]');
                      ?>
                    </div>
                <?php    
                }
                else if ( $post_thumb_view == "Video" and $post_thumb_video <> '' and $post_thumb_view <> '' ) {
                    $thumbView    = '<i class="icon-film"></i>';
                    $url = parse_url($post_thumb_video);
                    	if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                  <?php
                          echo '';
                    }else {
                        $video = wp_oembed_get($post_thumb_video, array('height' => '290'));
                        $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                        echo str_replace($search,'',$video);
                    	}
                 }
                ?>
              <div class="bloginfo-sec">
                  <?php cs_featured(); ?>
                  <h2><a href="<?php esc_url(the_permalink());?>">
				  		<?php echo wp_trim_words(get_the_title(),$title_limit); ?>
                        </a>
                  </h2>
                  <div class="post-thumb">
                      <ul class="thumb-options">
                        <li><?php _e('Posted On','dir');?>
                        	<time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?>
                            </time>
                         </li>  
                         <li>
                          <?php  $this->cs_get_categories( $cs_category );?>
                         </li>
                      </ul>
                    </div>
                    <?php if ($description == 'yes') {?><p> <?php
						//echo wp_trim_words(get_the_content,$excerpt,'...');
					 	echo cs_get_the_excerpt($excerpt,'ture','');?></p>
					 <?php } ?> 
                </div>
              </div>
            </div>
            <?php endwhile;
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    esc_html_e('No Record Found','dir');
                echo '</p></div></div>';
            }
        }
		
        //======================================================================
        // Blog Medium View
        //======================================================================
        public function cs_medium_view( $description,$excerpt,$args,$cs_category, $animation ) { 
            global $post;
            $width = '370';
            $height = '280';
            $title_limit = 1000;            
            $query = new WP_Query( $args );
            $post_count = $query->post_count;
            if ( $query->have_posts() ) {  
              $postCounter    = 0;
              $blogObject    = new BlogTemplates();
              while ( $query->have_posts() )  : $query->the_post();             
              $thumbnail = cs_get_post_img_src( $post->ID, $width, $height );                
              $post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view = $cs_xmlObject->post_thumb_view;    
                    $post_thumb_audio = $cs_xmlObject->post_thumb_audio;
                    $post_thumb_video = $cs_xmlObject->post_thumb_video;
				}
				else{
					$post_thumb_view = '';    
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
				}
            ?>
            <div class="col-md-12 post-<?php echo intval($post->ID);?> <?php echo cs_allow_special_char($animation);?>"> 
                <div class="cs-blog blog-medium">
                  <?php if ( $post_thumb_view == 'Single Image' ){
                        if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                          <div class="main-thumb">
                            <figure><a href="<?php esc_url(the_permalink());?>"><img alt="thumbnail" src="<?php echo esc_url( $thumbnail );?>"></a>
                              <figcaption>
                                    <a class="hover-icon" href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png">
                                    </a>
                                </figcaption>
                            </figure>
                          </div>
                        <?php }
                   } else if ( $post_thumb_view == 'Slider' ) {
                            echo '<div class="main-thumb">';
                                    cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                            echo '</div>';
                   } elseif ($post_thumb_view == "Audio" and $post_thumb_view <> ''){  
                           $thumbView    = '<i class="icon-microphone"></i>';                        
                    ?>
                        <div class="main-thumb">
                          <?php
                          echo do_shortcode('[audio mp3="'.$post_thumb_audio.'"][/audio]');
                          ?>
                        </div>
                    <?php    
                    }
                    else if ( $post_thumb_view == "Video" and $post_thumb_video <> '' and $post_thumb_view <> '' ) {
                        $thumbView    = '<i class="icon-film"></i>';
                        $url = parse_url($post_thumb_video);
                        if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                      <?php
                              echo '';
                        }else {
                            $video = wp_oembed_get($post_thumb_video, array('height' => '290'));
                            $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                            echo str_replace($search,'',$video);
                        }
                     }
                    ?>
                  <div class="bloginfo-sec">
                      <?php cs_featured(); ?>
                      <h2>
                      	<a href="<?php esc_url(the_permalink());?>">
					  		<?php echo wp_trim_words(get_the_title(),$title_limit); ?>
                        </a>
                       </h2>
                      <?php if ($description == 'yes') {?><p> <?php echo cs_get_the_excerpt($excerpt,'ture','');?></p><?php } ?> 
                      <div class="post-thumb">
                          <ul class="thumb-options">
                                <li>
                                    <?php esc_html_e('Posted On','dir');?>
                                        <time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time>
                                   </li>  
                                <li><?php esc_html_e('Posted by','dir');?><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author();?></a></li>  
                                <li><?php  $this->cs_get_categories( $cs_category );?></li>
                          </ul>
                          <a href="<?php esc_url(the_permalink());?>" class="read-more"><?php _e('Read more','dir');?></a>
                        </div>
                    </div>
                  </div>
            </div>
            <?php endwhile;
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    esc_html_e('No Record Found','dir');

                echo '</p></div></div>';
            }
        }
        //======================================================================
        // Blog Large View
        //======================================================================
        public function cs_large_view( $description,$excerpt,$args,$cs_category, $animation ) {
            global $post;
            $width = '842';
            $height = '474';
            $title_limit = 1000;
              $thumbView    = '<i class="icon-camera"></i>';            
            $query = new WP_Query( $args );
            $post_count = $query->post_count;
            if ( $query->have_posts() ) {  
              $postCounter    = 0;
              $blogObject    = new BlogTemplates();
              while ( $query->have_posts() )  : $query->the_post();             
              $thumbnail = cs_get_post_img_src( $post->ID, $width, $height );                
              $post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view = $cs_xmlObject->post_thumb_view;
                    $post_thumb_audio = $cs_xmlObject->post_thumb_audio;    
                    $post_thumb_video = $cs_xmlObject->post_thumb_video;
				}
				else{
					$post_thumb_view = '';    
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
				}
            ?>
            <div class="col-md-12 post-<?php echo intval($post->ID);?> <?php echo cs_allow_special_char($animation);?>"> 
            <!--// Blog Lrg Start //-->
            <div class="cs-blog blog-lrg">
              <?php if ( $post_thumb_view == 'Single Image' ){
                      $thumbView    = '<i class="icon-camera"></i>';
                    if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                      <div class="main-thumb">
                        <figure><a href="<?php esc_url(the_permalink());?>"><img alt="thumbnail" src="<?php echo esc_url( $thumbnail );?>"></a>
                          <figcaption>
                          	<a class="hover-icon" href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png"></a>
                            </figcaption>
                        </figure>
                      </div>
                    <?php }
               } else if ( $post_thumb_view == 'Slider' ) {
                        $thumbView    = '<i class="icon-unsorted"></i>';
                        echo '<div class="main-thumb">';
                                cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                        echo '</div>';
               } elseif ($post_thumb_view == "Audio" and $post_thumb_view <> ''){  
                       $thumbView    = '<i class="icon-microphone"></i>';                    
                ?>
                    <div class="main-thumb">
                      <?php echo do_shortcode('[audio mp3="'.$post_thumb_audio.'"][/audio]'); ?>
                    </div>
                <?php    
                }
                else if ( $post_thumb_view == "Video" and $post_thumb_video <> '' and $post_thumb_view <> '' ) {
                    $thumbView    = '<i class="icon-film"></i>';
                    $url = parse_url($post_thumb_video);
                    if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                  <?php
                          echo '';
                    }else {
                        $video = wp_oembed_get($post_thumb_video, array('height' => '490'));
                        $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                        echo str_replace($search,'',$video);
                    }
                 }
                ?>
              <div class="bloginfo-sec">
                <div class="tablerow">
                  <div class="date-time">
				 
                    	<time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php  echo date_i18n('M',strtotime(get_the_date()));?>
                    	<small><?php echo date_i18n('j',strtotime(get_the_date()));?></small>
                        </time>
                    <?php echo force_balance_tags( $thumbView );?>
                  </div>
                  <section class="blog-text">
                    <?php if ( is_sticky() ){?><?php cs_featured(); ?><?php }?>
                    <h2><a href="<?php esc_url(the_permalink());?>">
				  		<?php echo wp_trim_words(get_the_title(),$title_limit); ?>
                    </a></h2>
                    <?php if ($description == 'yes') {?><p> <?php echo cs_get_the_excerpt($excerpt,'ture','');?></p><?php } ?> 
                        <div class="post-thumb">
                          <figure> 
                          <?php
								$current_user       = wp_get_current_user();
								$custom_image_url = '';
								if( class_exists('wp_directory') ){
									$custom_image_url = cs_get_user_avatar(1, get_the_author_meta('ID'));
								}
								$size = 35;
								if( isset( $custom_image_url ) && $custom_image_url <> '') {
									echo '<img src="'.$custom_image_url.'"  width="'.$size.'" height="'.$size.'" alt="'.$current_user->display_name .'" />';
								} else {
								?>
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
									<?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('PixFill_author_bio_avatar_size', 35)); ?> 
                                 </a>
                            <?php 
                            }
                      ?>
                      </figure>
                      <ul class="thumb-options">
                        <li><?php _e('Posted by','dir');?><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
						<?php echo get_the_author();?>
                        </a></li>  
                         <li><?php  $this->cs_get_categories( $cs_category );?></li>
                      </ul>
                      <a href="<?php esc_url(the_permalink());?>" class="read-more"><?php _e('Read more','dir');?></a>
                    </div>
                  </section>
                </div>
              </div>
            </div>
            <!--// Blog Lrg End //--> 
            </div>
            <?php endwhile;
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    esc_html_e('No Record Found','dir');
                echo '</p></div></div>';
            }
        }
        
        //======================================================================
        // Blog Mesnory View
        //======================================================================
        public function cs_mesnory_view( $description,$excerpt,$args,$cs_category, $animation ) {
            global $post;
            $title_limit = 1000;            
            $thumbView    = '<i class="icon-camera"></i>';
            $query = new WP_Query( $args );
            $post_count = $query->post_count;
            if ( $query->have_posts() ) {  
				  $postCounter    = 0;
				  $blogObject    = new BlogTemplates();
				  while ( $query->have_posts() )  : $query->the_post();             
              		$thumbnail = cs_get_post_img_src( $post->ID, '', '' );                
              $post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view = $cs_xmlObject->post_thumb_view;    
                    $post_thumb_audio = $cs_xmlObject->post_thumb_audio;
                    $post_thumb_video = $cs_xmlObject->post_thumb_video;
				}
				else{
					$post_thumb_view = '';    
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
				}
            ?>
            <div class="col-md-4 post-<?php echo intval($post->ID);?> <?php echo cs_allow_special_char( $animation );?>"> 
                <div class="cs-blog blog-masnery">
				  <?php if ( $post_thumb_view == 'Single Image' ){
                        $thumbView    = '<i class="icon-camera"></i>';
                        if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                          <div class="main-thumb">
                            <figure><a href="<?php esc_url(the_permalink());?>"><img alt="thumbnail" src="<?php echo esc_url( $thumbnail );?>"></a>
                              <figcaption>
                                    <a class="hover-icon" href="<?php esc_url(the_permalink());?>">
                                        <img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png">
                                    </a>
                                </figcaption>
                            </figure>
                          </div>
                        <?php }
               } else if ( $post_thumb_view == 'Slider' ) {
                          $thumbView    = '<i class="icon-unsorted"></i>';
                        echo '<div class="main-thumb">';
                                cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                        echo '</div>';
               } elseif ($post_thumb_view == "Audio" and $post_thumb_view <> ''){  
                       $thumbView    = '<i class="icon-microphone"></i>';                    
                ?>
                    <div class="main-thumb">
                      <?php
                      echo do_shortcode('[audio mp3="'.$post_thumb_audio.'"][/audio]');
                      ?>
                    </div>
                <?php    
                }
                else if ( $post_thumb_view == "Video" and $post_thumb_video <> '' and $post_thumb_view <> '' ) {
                    $thumbView    = '<i class="icon-film"></i>';
                    $url = parse_url($post_thumb_video);
                    if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                  <?php
                          echo '';
                    }else {
                        $video = wp_oembed_get($post_thumb_video, array('height' => '290'));
                        $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                        echo str_replace($search,'',$video);
                    }
                 }
                ?>
              <div class="bloginfo-sec">
                  <ul class="post-options">
                        <?php if ( is_sticky() ){?><li><?php cs_featured(); ?></li><?php }?>
                        <li><time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time></li>  
                  </ul>
                  <h2><a href="<?php esc_url(the_permalink());?>">
				  		<?php echo wp_trim_words(get_the_title(),$title_limit); ?>
                    </a>
                   </h2>
                  <?php if ($description == 'yes') {?><p> <?php echo cs_get_the_excerpt($excerpt,'ture','');?></p><?php } ?> 
                  <div class="post-thumb">
                      <ul class="thumb-options">
                        <li><?php _e('Posted by','dir');?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author();?></a></li>  
                         <li><?php  $this->cs_get_categories( $cs_category );?></li>
                      </ul>
                      <?php echo force_balance_tags( $thumbView );?>
                    </div>
                </div>
              </div>
            </div>
            <?php endwhile;
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    esc_html_e('No Record Found','dir');
                echo '</p></div></div>';
            }        
        }       
        //======================================================================
        // Blog Grid View
        //======================================================================
        public function cs_grid_view( $description,$excerpt,$args,$cs_category, $animation, $layout ) {
            global $post;
            $width = '370';
            $height = '280';
            $title_limit = 4;            
            $query = new WP_Query( $args );
            $post_count = $query->post_count;
            if ( $query->have_posts() ) {  
				$postCounter    = 0;
				$blogObject    = new BlogTemplates();
				while ( $query->have_posts() )  : $query->the_post();             
				$thumbnail = cs_get_post_img_src( $post->ID, $width, $height );                
				$post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view = $cs_xmlObject->post_thumb_view;    
                    $post_thumb_audio = $cs_xmlObject->post_thumb_audio;
                    $post_thumb_video = $cs_xmlObject->post_thumb_video;
				}
				else{
					$post_thumb_view = '';    
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
				}
            ?>
            <div class="<?php echo esc_attr( $layout );?> post-<?php echo intval($post->ID);?> <?php echo cs_allow_special_char($animation);?>"> 
                <div class="cs-blog blog-grid">
					<?php if ( $post_thumb_view == 'Single Image' ){
                        if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                          <div class="main-thumb">
                            <figure><a href="<?php esc_url(the_permalink());?>"><img alt="thumbnail" src="<?php echo esc_url( $thumbnail );?>"></a>
                              <figcaption>
                              		<a class="hover-icon" href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png"></a>
                               </figcaption>
                            </figure>
                          </div>
                        <?php }
               } else if ( $post_thumb_view == 'Slider' ) {
                        echo '<div class="main-thumb">';
                                cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                        echo '</div>';
               } elseif ($post_thumb_view == "Audio" and $post_thumb_view <> ''){  
                       $thumbView = '<i class="icon-microphone"></i>';
                    
                ?>
                    <div class="main-thumb">
                      <?php echo do_shortcode('[audio mp3="'.$post_thumb_audio.'"][/audio]'); ?>
                    </div>
                <?php    
                }
                else if ( $post_thumb_view == "Video" and $post_thumb_video <> '' and $post_thumb_view <> '' ) {
                    $thumbView    = '<i class="icon-film"></i>';
                    $url = parse_url($post_thumb_video);
                    if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                  <?php
                          echo '';
                    }else {
                        $video = wp_oembed_get($post_thumb_video, array('height' => '220'));
                        $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                        echo str_replace($search,'',$video);
                    }
                 }
                ?>
              <div class="bloginfo-sec">
                  <ul class="post-options">
                        <?php if ( is_sticky() ){?><li><?php cs_featured(); ?></li><?php }?>
                        <li><time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time></li>  
                  </ul>
                  <h2><a href="<?php esc_url(the_permalink());?>">
				  	<?php echo wp_trim_words(get_the_title(),$title_limit,'...');	?>
                    </a>
                  </h2>
                  <?php if ($description == 'yes') {?><p> <?php
				  		//	echo wp_trim_words(get_the_content(),$excerpt,'...');
				   		echo cs_get_the_excerpt($excerpt,'ture','');?></p>
				   
				   <?php } ?> 
                  <div class="post-thumb">
                      <ul class="thumb-options">
                        <li><?php _e('Posted by','dir');?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author();?></a></li>  
                         <li><?php  $this->cs_get_categories( $cs_category );?></li>
                      </ul>
                    </div>
                </div>
              </div>
            </div>
            <?php endwhile;
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    esc_html_e('No Record Found','dir');
                echo '</p></div></div>';
            }
        }
        
        //======================================================================
        // Blog Crosel View
        //======================================================================
        public function cs_crousel_view( $description,$excerpt,$args,$cs_category, $animation, $layout ) {
            global $post;
            $width = '370';
            $height = '280';
            $title_limit = 4;            
            $query = new WP_Query( $args );
            $post_count = $query->post_count;
            if ( $query->have_posts() ) {
                $randId    = cs_generate_random_string(5);    
                cs_owl_carousel();
                 echo  '<div class="cs-directory cs-blog-crousel col-md-12">'; 
                 echo  '<script>  
                jQuery(document).ready(function($) {
                    $("#owl-directory-'.$randId.'").owlCarousel({
                        nav: true,
                        margin: 30,
                        navText: [
                            "<i class=icon-angle-left></i>",
                            "<i class=icon-angle-right></i>"
                        ],
                        responsive: {
                            0: {
                                items: 1 // In this configuration 1 is enabled from 0px up to 479px screen size 
                            },
                            480: {
                                items: 1, // from 480 to 677 
                                nav: false // from 480 to max 
                            },
                            678: {
                                items: 2, // from this breakpoint 678 to 959
                                center: false // only within 678 and next - 959
                            },
                            960: {
                                items: 3, // from this breakpoint 960 to 1199
                                center: false,
                                loop: false
                
                            },
                            1200: {
                                items: 3
                            }
                        }
                        });
                 }); 
            </script>';
             echo  '<div class="owl-carousel nxt-prv-v2 cs-theme-carousel " id="owl-directory-'.$randId.'">';  
              $postCounter    = 0;
              $blogObject    = new BlogTemplates();
              while ( $query->have_posts() )  : $query->the_post();             
              $thumbnail = cs_get_post_img_src( $post->ID, $width, $height );                
              $post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view = $cs_xmlObject->post_thumb_view;    
                    $post_thumb_audio = $cs_xmlObject->post_thumb_audio;
                    $post_thumb_video = $cs_xmlObject->post_thumb_video;
				}
				else{
					$post_thumb_view = '';    
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
				}
            ?>
            <div class="cs-blog blog-grid">
                  <?php if ( $post_thumb_view == 'Single Image' ){
                    if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                      <div class="main-thumb">
                        <figure><a href="<?php esc_url(the_permalink());?>"><img alt="thumbnail" src="<?php echo esc_url( $thumbnail );?>"></a>
                          <figcaption>
                          	<a class="hover-icon" href="<?php echo the_permalink();?>"><img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png"></a>
                           </figcaption>
                        </figure>
                      </div>
                    <?php }
               } else if ( $post_thumb_view == 'Slider' ) {
                        echo '<div class="main-thumb">';
                                cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                        echo '</div>';
               } elseif ($post_thumb_view == "Audio" and $post_thumb_view <> ''){  
                       $thumbView = '<i class="icon-microphone"></i>';                    
                ?>
                    <div class="main-thumb">
                      <?php
                      echo do_shortcode('[audio mp3="'.$post_thumb_audio.'"][/audio]');
                      ?>
                    </div>
                <?php    
                }
                else if ( $post_thumb_view == "Video" and $post_thumb_video <> '' and $post_thumb_view <> '' ) {
                    $thumbView    = '<i class="icon-film"></i>';
                    $url = parse_url($post_thumb_video);
                    if($url['host'] == $_SERVER["SERVER_NAME"]) {?>
                  <?php
                          echo '';
                    }else {
                        $video = wp_oembed_get($post_thumb_video, array('height' => '220'));
                        $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                        echo str_replace($search,'',$video);
                    }
                 }
                ?>
              <div class="bloginfo-sec">
                  <ul class="post-options">
                        <?php if ( is_sticky() ){?><li><?php cs_featured(); ?></li><?php }?>
                        <li><time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time></li>  
                  </ul>
                  <h2>
                  	<a href="<?php esc_url(the_permalink());?>">
						<?php echo wp_trim_words(get_the_title(),$title_limit,'...');	?>
					</a>
					</h2>
                  <?php if ($description == 'yes') {?><p> <?php echo cs_get_the_excerpt($excerpt,'ture','');?></p><?php } ?> 
                  <div class="post-thumb">
                      <ul class="thumb-options">
                        <li><?php _e('Posted by','dir');?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author();?></a></li>  
                         <li><?php  $this->cs_get_categories( $cs_category );?></li>
                      </ul>
                    </div>
                </div>
              </div>
            <?php endwhile;
            echo '</div></div>';
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    esc_html_e('No Record Found','dir');
                echo '</p></div></div>';
            }
        }        
        //======================================================================
        // Blog Box View
        //======================================================================
        public function cs_box_view( $description,$excerpt,$args,$cs_category, $animation, $box_col_class = 'col-md-3' ) {
            global $post;
            $width = '380';
            $height = '380';
            $title_limit = 1000;
            
            $query = new WP_Query( $args );
            $post_count = $query->post_count;

            if ( $query->have_posts() ) {  
              $postCounter    = 0;
              $blogObject    = new BlogTemplates();
              while ( $query->have_posts() )  : $query->the_post();             
              $thumbnail = cs_get_post_img_src( $post->ID, $width, $height );   
              $post_xml = get_post_meta(get_the_id(), "post", true);
                if ( $post_xml <> "" ) {
                    $cs_xmlObject = new SimpleXMLElement($post_xml);
                    $post_thumb_view = $cs_xmlObject->post_thumb_view;    
                }
				else{
					$post_thumb_view = '';    
                    $post_thumb_audio = '';
                    $post_thumb_video = '';
				}
            ?>
            <div class="<?php echo cs_allow_special_char($box_col_class); ?> post-<?php echo intval($post->ID);?> <?php echo cs_allow_special_char($animation);?>"> 
                <div class="cs-blog blog-box">
              <?php 
                    if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                      <div class="main-thumb">
                        <figure><a href="<?php esc_url(the_permalink());?>"><img alt="thumbnail" src="<?php echo esc_url( $thumbnail );?>"></a>
                          <figcaption>
                                <div class="bloginfo-sec">
                                    <ul class="post-options">
                                      <?php if ( is_sticky() ){?><li><?php cs_featured(); ?></li><?php }?>
                                      <li>
                                      	<time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time>
                                      </li>
                                    </ul>
                                <h5><a href="<?php esc_url(the_permalink());?>">
								  	<?php echo wp_trim_words(get_the_title(),$title_limit,'...');	?>
                                </a></h5>
                                  <div class="post-thumb">
                                    <ul class="thumb-options"> 
                                      <li><?php _e('Posted by','dir');?><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author();?></a></li> 
                                    </ul>
                                    <a href="<?php esc_url(the_permalink());?>" class="read-more"><?php _e('Read more','dir');?></a>
                                  </div>
                              </div>
                          </figcaption>
                        </figure>
                      </div>
                    <?php } ?>
              </div>
            </div>
            <?php endwhile;
            } else {
                echo '<div class="col-md-12"><div class="succ_mess"><p>';
                    _e('No Record Found','dir');
                echo '</p></div></div>';
            }
        }
        //======================================================================
        // Blog Categories
        //======================================================================
        public function cs_get_categories( $cs_blog_cat ) {             
             global $post,$wpdb;                                 
             if ( isset( $cs_blog_cat ) && $cs_blog_cat !='' && $cs_blog_cat !='0' ){ 
                _e('in','dir');
                $row_cat = $wpdb->get_row($wpdb->prepare("SELECT * from $wpdb->terms WHERE slug = %s", $cs_blog_cat ));
                echo '<a href="'.site_url().'?cat='.$row_cat->term_id.'">'.$row_cat->name.'</a>';
             } else {
                 /* Get All Categories */
                  $before_cat = "in , ";
                  $categories_list = get_the_term_list ( get_the_id(), 'category', $before_cat , ' , ', '' );
                  if ( $categories_list ){
                    printf( __( '%1$s', 'dir'),$categories_list );
                  } 
                 // End if Categories 
             }
        }
    }
}