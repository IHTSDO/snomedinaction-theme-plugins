<?php 
/**
 * Widgets Classes & Functions
 */
/**
 * @Facebook widget Class
 *
 *
 */
if ( ! class_exists( 'facebook_module' ) ) { 
    class facebook_module extends WP_Widget {      
        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
         /**
         * @Facebook Module
         *
         *
         */
         function facebook_module(){
                $widget_ops = array('classname' => 'facebok_widget', 'description' => 'Facebook widget like box total customized with theme.' );
                $this->WP_Widget('facebook_module', 'CS : Facebook', $widget_ops);
          }            
        /**
         * @Facebook html Form
         *
         *
         */
         function form($instance) {
                $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
                $title = $instance['title'];
                $pageurl = isset( $instance['pageurl'] ) ? esc_attr( $instance['pageurl'] ) : '';
                $showfaces = isset( $instance['showfaces'] ) ? esc_attr( $instance['showfaces'] ) : '';
                $showstream = isset( $instance['showstream'] ) ? esc_attr( $instance['showstream'] ) : '';
                $showheader = isset( $instance['showheader'] ) ? esc_attr( $instance['showheader'] ) : '';
                $fb_bg_color = isset( $instance['fb_bg_color'] ) ? esc_attr( $instance['fb_bg_color'] ) : '';
                $likebox_height = isset( $instance['likebox_height'] ) ? esc_attr( $instance['likebox_height'] ) : '';                        
            ?>
            <p>
            	<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"> Title:
                	<input class="upcoming" id="<?php echo esc_attr($this->get_field_id('title')); ?>" size='40' name="<?php echo esc_attr($this->                    get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
              </label>
            </p>
            <p>
            	<label for="<?php echo esc_attr($this->get_field_id('pageurl')); ?>"> Page URL:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('pageurl')); ?>" size='40' name="<?php echo                 esc_attr($this->get_field_name('pageurl')); ?>" type="text" value="<?php echo esc_attr($pageurl); ?>" />
                <br />
                <small>Please enter your page or User profile url example: http://www.facebook.com/profilename OR <br />
                https://www.facebook.com/pages/wxyz/123456789101112 </small><br />
              </label>
            </p>
            <p>
            	<label for="<?php echo esc_attr($this->get_field_id('showfaces')); ?>"> Show Faces:
                <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('showfaces')); ?>" name="<?php echo esc_attr($this->                get_field_name('showfaces')); ?>" type="checkbox" <?php if(esc_attr($showfaces) != '' ){echo 'checked';}?> />
              </label>
            </p>
            <p>
            	<label for="<?php echo esc_attr($this->get_field_id('showstream')); ?>"> Show Stream:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('showstream')); ?>" name="<?php echo                cs_allow_special_char($this->get_field_name('showstream')); ?>" type="checkbox" <?php if(esc_attr($showstream) != '' )
				{echo 'checked';}?> />
              </label>
            </p>
            <p>
            	<label for="<?php echo cs_allow_special_char($this->get_field_id('likebox_height')); ?>"> Like Box Height:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('likebox_height')); ?>" size='2' name="<?php echo                cs_allow_special_char($this->get_field_name('likebox_height')); ?>" type="text" value="<?php echo esc_attr($likebox_height); ?>" />
              </label>
            </p>
            <p>
            	<label for="<?php echo cs_allow_special_char($this->get_field_id('fb_bg_color')); ?>"> Background Color:
                <input type="text" name="<?php echo cs_allow_special_char($this->get_field_name('fb_bg_color')); ?>" size='4' id="<?php echo                 cs_allow_special_char($this->get_field_id('fb_bg_color')); ?>"  value="<?php if(!empty($fb_bg_color))
				{ echo cs_allow_special_char($fb_bg_color);} ?>" class="fb_bg_color upcoming"  />
              </label>
            </p>            
            <?php       
        }        
        /**
         * @Facebook Update Form Data
         *
         *
         */
         function update($new_instance, $old_instance) {    
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			$instance['pageurl'] = $new_instance['pageurl'];
			$instance['showfaces'] = $new_instance['showfaces'];    
			$instance['showstream'] = $new_instance['showstream'];
			$instance['showheader'] = $new_instance['showheader'];
			$instance['fb_bg_color'] = $new_instance['fb_bg_color'];        
			$instance['likebox_height'] = $new_instance['likebox_height'];
			return $instance;
        }
        /**
         * @Facebook Widget Display
         *
         *
         */
         function widget($args, $instance) {    
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = htmlspecialchars_decode(stripslashes($title));
            $pageurl = empty($instance['pageurl']) ? ' ' : apply_filters('widget_title', $instance['pageurl']);
            $showfaces = empty($instance['showfaces']) ? ' ' : apply_filters('widget_title', $instance['showfaces']);
            $showstream = empty($instance['showstream']) ? ' ' : apply_filters('widget_title', $instance['showstream']);
            $showheader = empty($instance['showheader']) ? ' ' : apply_filters('widget_title', $instance['showheader']);
            $fb_bg_color = empty($instance['fb_bg_color']) ? ' ' : apply_filters('widget_title', $instance['fb_bg_color']);                        
            $likebox_height = empty($instance['likebox_height']) ? ' ' : apply_filters('widget_title', $instance['likebox_height']);
			if(isset($showfaces) AND $showfaces == 'on'){$showfaces ='true';}else{$showfaces = 'false';}
            if(isset($showstream) AND $showstream == 'on'){$showstream ='true';}else{$showstream ='false';}
           		echo cs_allow_special_char($before_widget);
			?>
            <style scoped>
				.facebookOuter {background-color:<?php echo cs_allow_special_char($fb_bg_color);?>; width:100%;padding:0;float:left;}
				.facebookInner {float: left; width: 100%;}
				.facebook_module, .fb_iframe_widget > span, .fb_iframe_widget > span > iframe { width: 100% !important;}
				.fb_iframe_widget, .fb-like-box div span iframe { width: 100% !important; float: left;}
			</style>
            <?php
            if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }    
        global $wpdb, $post;?>		
        
        	<div id="fb-root"></div>
			<script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
			
            <div style="background:<?php echo cs_allow_special_char($fb_bg_color);?>;" class="fb-like-box" data-href="<?php echo esc_url($pageurl);?>" data-height="<?php echo cs_allow_special_char($likebox_height);?>" data-colorscheme="light" data-show-faces="<?php echo cs_allow_special_char($showfaces);?>" data-header="false" data-stream="<?php echo cs_allow_special_char($showstream);?>" data-show-border="false"></div>
            
		<?php echo cs_allow_special_char($after_widget);
		}
	}    
}
add_action( 'widgets_init', create_function('', 'return register_widget("facebook_module");') );    
/**
 * @Social Network widget Class
 *
 *
 */
if ( ! class_exists( 'cs_social_network_widget' ) ) { 
    class cs_social_network_widget extends WP_Widget{    
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
     
    /**
     * @Social Network Module
     *
     *
     */
    function cs_social_network_widget(){
        $widget_ops = array('classname' => 'widget-socialnetwork', 'description' => 'Social Newtork widget.' );
        $this->WP_Widget('cs_social_network_widget', 'CS : Social Newtork', $widget_ops);
      }      
    /**
     * @Social Network html form
     *
     *
     */
    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
        ?>
        <p>
        	<label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size='40' name="<?php echo             cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          </label>
        </p>
	<?php
      }
      
    /**
     * @Social Network Update from data 
     *
     *
     */
     function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
      }      
    /**
     * @Social Network Widget
     *
     *
     */
     function widget($args, $instance){
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = htmlspecialchars_decode(stripslashes($title));
           	 echo cs_allow_special_char($before_widget);                
            if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }
                global $wpdb, $post;
                echo '<div class="followus">';
               		cs_social_network_widget();
                echo '</div>';
                echo cs_allow_special_char($after_widget);
            }
        }
}
add_action( 'widgets_init', create_function('', 'return register_widget("cs_social_network_widget");') );
/**
 * @Flickr widget Class
 *
 *
 */
if ( ! class_exists( 'cs_flickr' ) ) { 
    class cs_flickr extends WP_Widget {    
        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
             
        /**
         * @init Flickr Module
         *
         *
         */
        function cs_flickr() {
            $widget_ops = array('classname' => 'widget_gallery', 'description' => 'Type a user name to show photos in widget.');
            $this->WP_Widget('cs_flickr', 'CS : Flickr Gallery', $widget_ops);
        }         
         /**
         * @Flickr html form
         *
         *
         */
        function form($instance){
            $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
            $title = $instance['title'];
            $username = isset( $instance['username'] ) ? esc_attr( $instance['username'] ) : '';
            $no_of_photos = isset( $instance['no_of_photos'] ) ? esc_attr( $instance['no_of_photos'] ) : '';    
        ?>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('username')); ?>"> Flickr username:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('username')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('username')); ?>" type="text" value="<?php echo esc_attr($username); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('no_of_photos')); ?>"> Number of Photos:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('no_of_photos')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('no_of_photos')); ?>" type="text" value="<?php echo esc_attr($no_of_photos); ?>" />
                </label>
            </p>
        <?php
        }            
        /**
         * @Flickr update form data
         *
         *
         */
        function update($new_instance, $old_instance){
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['username'] = $new_instance['username'];
            $instance['no_of_photos'] = $new_instance['no_of_photos'];            
            return $instance;
        }    
        /**
         * @Display Flickr widget
         *
         *
         */
        function widget($args, $instance){
            global $cs_theme_options;            
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = htmlspecialchars_decode(stripslashes($title));
            $username = empty($instance['username']) ? ' ' : apply_filters('widget_title', $instance['username']);            
            $no_of_photos = empty($instance['no_of_photos']) ? ' ' : apply_filters('widget_title', $instance['no_of_photos']);    
            if($instance['no_of_photos'] == ""){$instance['no_of_photos'] = '3';}            
            echo cs_allow_special_char($before_widget);            
            if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }            
            $get_flickr_array = array();                    
            $apiKey = $cs_theme_options['flickr_key'];
            $apiSecret = $cs_theme_options['flickr_secret'];            
            if($apiKey <> ''){            
                // Getting transient
                $cachetime = 86400;
                $transient = 'flickr_gallery_data';
                $check_transient = get_transient($transient);                
                // Get Flickr Gallery saved data
                $saved_data = get_option('flickr_gallery_data');                
                $db_apiKey = '';
                $db_user_name = '';
                $db_total_photos = '';                
                if($saved_data <> ''){
                    $db_apiKey = isset($saved_data['api_key']) ? $saved_data['api_key'] : '';
                    $db_user_name = isset($saved_data['user_name']) ? $saved_data['user_name'] : '';
                    $db_total_photos = isset($saved_data['total_photos']) ? $saved_data['total_photos'] : '';
                }                
                if( $check_transient === false || ($apiKey <> $db_apiKey || $username <> $db_user_name || $no_of_photos <> $db_total_photos) ){                
                    $user_id = "https://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=".$apiKey."&username=".$username."&format=json&nojsoncallback=1";                    
                    $user_info = file_get_contents($user_id);
                    $user_info = json_decode($user_info, true);                                
                    if ($user_info['stat'] == 'ok') {                        
                        $user_get_id = $user_info['user']['id'];                        
                        $get_flickr_array['api_key'] = $apiKey;
                        $get_flickr_array['user_name'] = $username;
                        $get_flickr_array['user_id'] = $user_get_id;                        
                        $url = "https://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=".$apiKey."&user_id=".$user_get_id."&per_page=".$no_of_photos."&format=json&nojsoncallback=1";
                        $content = file_get_contents($url);
                        $content = json_decode($content, true);                        
                        if ($content['stat'] == 'ok') {
                            $counter = 0;
                            echo '<ul class="gallery-list">';                             
                            foreach ((array)$content['photos']['photo'] as $photo) {                                
                                $image_file = "https://farm{$photo['farm']}.staticflickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}_s.jpg";                                
                                $img_headers = get_headers($image_file);
                                if(strpos($img_headers[0], '200') !== false) {                                    
                                    $image_file = $image_file;
                                }
                                else{
                                    $image_file = "https://farm{$photo['farm']}.staticflickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}_q.jpg";
                                    $img_headers = get_headers($image_file);
                                    if(strpos($img_headers[0], '200') !== false) {                                        
                                        $image_file = $image_file;
                                    }
                                    else{
                                        $image_file = get_template_directory_uri().'/assets/images/no_image_thumb.jpg';
                                    }
                                }                                
                                echo '<li>';
                                echo "<a target='_blank' title='" . $photo['title'] . "' href='https://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/'>";
                                echo "<img alt='".$photo['title']."' src='".$image_file."'>";
                                echo "</a>";
                                echo '</li>';                                                        
                                $counter++;                                
                                $get_flickr_array['photo_src'][] = $image_file;
                                $get_flickr_array['photo_title'][] = $photo['title'];
                                $get_flickr_array['photo_owner'][] = $photo['owner'];
                                $get_flickr_array['photo_id'][] = $photo['id'];                                
                            }
                            echo '</ul>';                            
                            $get_flickr_array['total_photos'] = $counter;                            
                            // Setting Transient
                            set_transient( $transient, true, $cachetime );
                            update_option('flickr_gallery_data', $get_flickr_array);
                            if($counter == 0) _e('No result found.', 'dir');
                        }                        
                        else {
                            echo __('Error:', 'dir') . $content['code'] . ' - ' . $content['message'];
                        }
                    }                    
                    else {
                        echo __('Error:', 'dir') . $user_info['code'] . ' - ' . $user_info['message'];
                    }                
                }
                else{
                    if( get_option('flickr_gallery_data') <> '' ){                        
                        $flick_data = get_option('flickr_gallery_data');
                        echo '<ul class="gallery-list">';
                            if(isset($flick_data['photo_src'])):
                                $i = 0;
                                foreach($flick_data['photo_src'] as $ph){
                                    echo '<li>';
                                    echo "<a target='_blank' title='" . $flick_data['photo_title'][$i] . "' href='https://www.flickr.com/photos/" . $flick_data['photo_owner'][$i] . "/" . $flick_data['photo_id'][$i] . "/'>";
                                    echo "<img alt='".$flick_data['photo_title'][$i]."' src='".$flick_data['photo_src'][$i]."'>";
                                    echo "</a>";
                                    echo '</li>';
                                    $i++;
                                }
                            endif;
                        echo '</ul>';
                    }
                    else{
                        _e('No result found.', 'dir');
                    }
                }            
            }
            else{
                _e('Please Enter Flickr API key from Theme Options.', 'dir');
            }
            echo cs_allow_special_char($after_widget);            
        }
    }
}
add_action('widgets_init', create_function('', 'return register_widget("cs_flickr");'));
/**
 * @Recent posts widget Class
 *
 *
 */
if ( ! class_exists( 'recentposts' ) ) { 
    class recentposts extends WP_Widget{    
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */         
    /**
     * @init Recent posts Module
     *
     *
     */
     function recentposts(){
        $widget_ops = array('classname' => 'recentblog_post', 'description' => 'Recent Posts from category.' );
        $this->WP_Widget('recentposts', 'CS : Recent Posts', $widget_ops);
     }     
     /**
     * @Recent posts html form
     *
     *
     */
     function form($instance){
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
        $select_category = isset( $instance['select_category'] ) ? esc_attr( $instance['select_category'] ) : '';
        $showcount = isset( $instance['showcount'] ) ? esc_attr( $instance['showcount'] ) : '';    
        $thumb = isset( $instance['thumb'] ) ? esc_attr( $instance['thumb'] ) : '';
    ?>
        <p>
        	<label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
          		<input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          </label>
        </p>
        <p>
        	<label for="<?php echo cs_allow_special_char($this->get_field_id('select_category')); ?>"> Select Category:
            	<select id="<?php echo cs_allow_special_char($this->get_field_id('select_category')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('select_category')); ?>" style="width:225px">
              <option value="" >All</option>
              <?php
                $categories = get_categories();
                if($categories <> ""){
                    foreach ( $categories as $category ) {?>
                      <option <?php if($select_category == $category->slug){echo 'selected';}?> value="<?php echo cs_allow_special_char($category->slug);?>" ><?php echo cs_allow_special_char($category->name);?></option>
                    <?php 
                    }
                }?>
            </select>
          </label>
        </p>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>"> Number of Posts To Display:
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('showcount')); ?>" type="text" value="<?php echo esc_attr($showcount); ?>" />
          </label>
        </p>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('thumb')); ?>"> Display Thumbinals:
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('thumb')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('thumb')); ?>" value="true" type="checkbox"  <?php if(isset($instance['thumb']) && $instance['thumb']=='true' ) echo 'checked="checked"'; ?> />
          </label>
        </p>
        <?php
        }
        
        /**
         * @Recent posts update form data
         *
         *
         */
         function update($new_instance, $old_instance){
              $instance = $old_instance;
              $instance['title'] = $new_instance['title'];
              $instance['select_category'] = $new_instance['select_category'];
              $instance['showcount'] = $new_instance['showcount'];
              $instance['thumb'] = $new_instance['thumb'];            
              return $instance;
         }
         /**
         * @Display Recent posts widget
         *
         *
         */
         function widget($args, $instance){
              global $cs_node;        
              extract($args, EXTR_SKIP);
              $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			  $title = htmlspecialchars_decode(stripslashes($title));
              $select_category = empty($instance['select_category']) ? ' ' : apply_filters('widget_title', $instance['select_category']);          
              $showcount = empty($instance['showcount']) ? ' ' : apply_filters('widget_title', $instance['showcount']);    
              $thumb = isset( $instance['thumb'] ) ? esc_attr( $instance['thumb'] ) : '';                        
              if($instance['showcount'] == ""){$instance['showcount'] = '-1';}        
              echo cs_allow_special_char($before_widget);        
              if (!empty($title) && $title <> ' '){
                  echo cs_allow_special_char($before_title);
                  echo cs_allow_special_char($title);
                  echo cs_allow_special_char($after_title);
              }        
        global $wpdb, $post;?>
        <?php
              wp_reset_query();              
               /**
                 * @Display Recent posts
                 *
                 *
                 */
                if(isset($select_category) and $select_category <> ' ' and $select_category <> ''){
                    $args = array( 'posts_per_page' => "$showcount",'post_type' => 'post','category_name' => "$select_category");
                }else{
                    $args = array( 'posts_per_page' => "$showcount",'post_type' => 'post');
                }
              $custom_query = new WP_Query($args);
              if ( $custom_query->have_posts() <> "" ) {
                  while ( $custom_query->have_posts()) : $custom_query->the_post();
                  $post_xml = get_post_meta($post->ID, "post", true);    
                  $cs_xmlObject = new stdClass();
                  $cs_noimage = '';
                  if ( $post_xml <> "" ) {
                      $cs_xmlObject = new SimpleXMLElement($post_xml);

                  }//43                  
                  $cs_noimage = '';
                  $width = 150;
                  $height = 150;
                  $image_id = get_post_thumbnail_id( $post->ID );
                  $image_url = cs_get_post_img_src($post->ID, $width, $height);
                  if($image_id == ''){
                      $cs_noimage = ' cs-noimage';    
                  }
                  echo '<div class="post-lst">'; 
                  if($image_url <> '' and $thumb == true){
                  ?>           
                  <figure>
				  	<a href="<?php esc_url(the_permalink());?>"><img alt="<?php the_title();?>" src="<?php echo esc_url($image_url); ?>"></a>
				  </figure>
                  <?php } ?>
                  <article class="info_sec <?php echo cs_allow_special_char($cs_noimage); ?>">
                    <h6>
						<a href="<?php esc_url(the_permalink());?>"><?php 
						  $cs_post_id = get_the_ID(); 
						  $title_limit = 6;
						 echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...');
						//echo substr(get_the_title(),0,27);
						// if ( strlen(get_the_title()) > 27) echo "..."; ?></a>
					</h6>
                    <ul class="wg-pstoption">
                      <li><?php _e('Posted on','dir'); echo date_i18n(get_option('date_format'), strtotime(get_the_date()));?></li>
                      <li>by <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a></li>
                    </ul>
                  </article>
                  </div>
                  <?php
                endwhile; 
                
                  }
                  else {
                      if ( function_exists( 'cs_fnc_no_result_found' ) ) { cs_fnc_no_result_found(false); }
                  }
                echo cs_allow_special_char($after_widget);
              }
          }
}
add_action( 'widgets_init', create_function('', 'return register_widget("recentposts");') );
/**
 * @Twitter Tweets widget Class
 *
 *
 */
if ( ! class_exists( 'cs_twitter_widget' ) ) { 
    class cs_twitter_widget extends WP_Widget {        
        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
             
        /**
         * @init Twitter Module
         *
         *
         */
        function cs_twitter_widget() {
            $widget_ops = array('classname' => 'cs-twitter', 'description' => 'Twitter Widget');
            $this->WP_Widget('cs_twitter_widget', 'CS : Twitter Widget', $widget_ops);
        }        
        /**
         * @Twitter html form
         *
         *
         */
         function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = $instance['title'];
            $username = isset($instance['username']) ? esc_attr($instance['username']) : '';
            $numoftweets = isset($instance['numoftweets']) ? esc_attr($instance['numoftweets']) : '';
         ?>
            <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <span>Title: </span>
              <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
            <label for="screen_name">User Name<span class="required">(*)</span>: </label>
            	<input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('username')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('username')); ?>" type="text" value="<?php echo esc_attr($username); ?>" />
            <label for="tweet_count">
            <span>Num of Tweets: </span>
            	<input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('numoftweets')); ?>" size="2" name="<?php echo cs_allow_special_char($this->get_field_name('numoftweets')); ?>" type="text" value="<?php echo esc_attr($numoftweets); ?>" />
            <div class="clear"></div>
            </label>
            <?php
        }
        /**
         * @Twitter update form data 
         *
         *
         */
         function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['username'] = $new_instance['username'];
            $instance['numoftweets'] = $new_instance['numoftweets'];            
             return $instance;
         }
        /**
         * @Display Twitter widget
         *
         *
         */
           function widget($args, $instance) {
            global $cs_theme_options;
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
$title = htmlspecialchars_decode(stripslashes($title));
            $username = $instance['username'];
             $numoftweets = $instance['numoftweets'];        
             if($numoftweets == ''){$numoftweets = 2;}
            echo cs_allow_special_char($before_widget);
              // WIDGET display CODE Start
            if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title . $title . $after_title);
            }
            if(strlen($username) > 1){
                    $text ='';
                    $return = '';
                    $cacheTime =24;
                    $transName = 'latest-tweets';
                    require_once get_template_directory() . '/include/theme-components/cs-twitter/twitteroauth.php';                    
                    $consumerkey = $cs_theme_options['cs_consumer_key'];
                    $consumersecret = $cs_theme_options['cs_consumer_secret'];
                    $accesstoken = $cs_theme_options['cs_access_token'];
                    $accesstokensecret = $cs_theme_options['cs_access_token_secret'];
                     $connection = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);                    
                    $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$numoftweets);                    
                    if(!is_wp_error($tweets) and is_array($tweets)){                        
                        set_transient($transName, $tweets, 60 * $cacheTime);
                    }else{
                        $tweets= get_transient('latest-tweets');
                    }
                      if(!is_wp_error($tweets) and is_array($tweets)){
                        $rand_id = rand(5, 300);                        
                        $return .= "";
                            foreach($tweets as $tweet) {
                                $text = $tweet->{'text'};
                                foreach($tweet->{'user'} as $type => $userentity) {
                                    if($type == 'profile_image_url') {    
                                        $profile_image_url = $userentity;
                                    } else if($type == 'screen_name'){
                                        $screen_name = '<a href="https://twitter.com/' . $userentity . '" target="_blank" class="colrhover" title="' . $userentity . '">@' . $userentity . '</a>';
                                    }
                                }
                                foreach($tweet->{'entities'} as $type => $entity) {
                                if($type == 'urls') {                        
                                    foreach($entity as $j => $url) {
                                        $display_url = '<a href="' . $url->{'url'} . '" target="_blank" title="' . $url->{'expanded_url'} . '">' . $url->{'display_url'} . '</a>';
                                        $update_with = 'Read more at '.$display_url;
                                        $text = str_replace('Read more at '.$url->{'url'}, '', $text);
                                        $text = str_replace($url->{'url'}, '', $text);
                                    }
                                } else if($type == 'hashtags') {
                                    foreach($entity as $j => $hashtag) {
                                        $update_with = '<a href="https://twitter.com/search?q=%23' . $hashtag->{'text'} . '&amp;src=hash" target="_blank" title="' . $hashtag->{'text'} . '">#' . $hashtag->{'text'} . '</a>';
                                        $hashtag->{'text'};
                                        $text = str_replace('#'.$hashtag->{'text'}, $update_with, $text);
                                    }
                                } else if($type == 'user_mentions') {
                                        foreach($entity as $j => $user) {
                                              $update_with = '<a href="https://twitter.com/' . $user->{'screen_name'} . '" target="_blank" title="' . $user->{'name'} . '">@' . $user->{'screen_name'} . '</a>';
                                              $text = str_replace('@'.$user->{'screen_name'}, $update_with, $text);
                                        }
                                    }
                                } 
                                $large_ts = time();
                                $n = $large_ts - strtotime($tweet->{'created_at'});
                                if($n < (60)){ $posted = sprintf(__('%d seconds ago','dir'),$n); }
                                elseif($n < (60*60)) { $minutes = round($n/60); $posted = sprintf(_n('About a Minute Ago','%d Minutes Ago',$minutes,'dir'),$minutes); }
                                elseif($n < (60*60*16)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'dir'),$hours); }
                                elseif($n < (60*60*24)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'dir'),$hours); }
                                elseif($n < (60*60*24*6.5)) { $days = round($n/(60*60*24)); $posted = sprintf(_n('About a Day Ago','%d Days Ago',$days,'dir'),$days); }
                                elseif($n < (60*60*24*7*3.5)) { $weeks = round($n/(60*60*24*7)); $posted = sprintf(_n('About a Week Ago','%d Weeks Ago',$weeks,'dir'),$weeks); } 
                                elseif($n < (60*60*24*7*4*11.5)) { $months = round($n/(60*60*24*7*4)) ; $posted = sprintf(_n('About a Month Ago','%d Months Ago',$months,'dir'),$months);}
                                elseif($n >= (60*60*24*7*4*12)){$years=round($n/(60*60*24*7*52)) ; $posted = sprintf(_n('About a year Ago','%d years Ago',$years,'dir'),$years);}
                                $return .="<li><div class='test'>";
                                $return .= $text;
                                $return .= "<p><i class='icon-twitter2'></i><a href='https://twitter.com/" . $username . "'>".$username."</a>&nbsp;<time datetime='".date('Y-m-d')."'>" . $posted. "</time></p>";
                                $return .= "</div></li>";
                        }                    
                $return .= "";
                if(isset($profile_image_url) && $profile_image_url <> ''){$profile_image_url = '<img src="'.$profile_image_url.'" alt="">';} else {$profile_image_url = '';}
                $return .= '';
                echo '<ul>'.cs_allow_special_char($return).'</ul>';                
         }else{
            if(isset($tweets->errors[0]) && $tweets->errors[0] <> ""){
                echo '<span class="bad_authentication">'.$tweets->errors[0]->message.". Please enter valid Twitter API Keys </span>";
            }else{
                echo '<span class="bad_authentication">';
                    cs_fnc_no_result_found(false);
                echo '</span>';
            }
        }
    }else{
            echo '<span class="bad_authentication">';            
                cs_fnc_no_result_found(false);
            echo '</span>';
        }
        echo cs_allow_special_char($after_widget);
        }
     }
}
add_action('widgets_init', create_function('', 'return register_widget("cs_twitter_widget");'));
/**
 * @latest reviews widget Class
 *
 *
 */
class contactinfo extends WP_Widget{    
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
         
    /**
     * @init Contact Info Module
     *
     *
     */     
    function contactinfo()    {
        $widget_ops = array('classname' => 'widget_text', 'description' => 'Fotter Contact Information.' );
        $this->WP_Widget('contactinfo', 'CS : Contact info', $widget_ops);
    }    
    /**
     * @Contact Info html form
     *
     *
     */
    function form($instance){
        $instance = wp_parse_args( (array) $instance );
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $image_url     = isset( $instance['image_url'] ) ? esc_attr( $instance['image_url'] ) : '';
        $about_text     = isset( $instance['about_text'] ) ? esc_attr( $instance['about_text'] ) : '';
        $address     = isset( $instance['address'] ) ? esc_attr( $instance['address'] ) : '';    
        $phone         = isset( $instance['phone'] ) ? esc_attr( $instance['phone'] ) : '';
        $fax         = isset( $instance['fax'] ) ? esc_attr( $instance['fax'] ) : '';    
        $email         = isset( $instance['email'] ) ? esc_attr( $instance['email'] ) : '';
        $randomID   = rand(40, 9999999);
     ?>    
    <div style="margin-top:0px; float:left; width:100%;">
        <p>
        	<label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <span>Title: </span>
          		<input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo                cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </label>
        </p>
        <ul class="form-elements-widget">
          <li class="to-label" style="margin-top:20px;">
            <label>Image</label>
          </li>
          <li class="to-field">
          	<input id="form-widget_cs_widget_logo<?php echo absint($randomID)?>" name="<?php echo cs_allow_special_char($this->get_field_name('image_url')); ?>" type="hidden" class="" value="<?php echo esc_url($image_url); ?>"/>
            <label class="browse-icon">
            	<input name="form-widget_cs_widget_logo<?php echo absint($randomID)?>"  type="button" class="uploadMedia left" value="Browse"/>
            </label>
          </li>
        </ul>
        <div class="page-wrap"  id="form-widget_cs_widget_logo<?php echo absint($randomID)?>_box" style="margin-top:10px; margin-bottom:10px; float:left; overflow:hidden; display:<?php echo esc_url($image_url)&& trim($image_url) !='' ? 'inline' : 'none';?>">
          <div class="gal-active">
            <div class="dragareamain" style="padding-bottom:0px;">
              <ul id="gal-sortable" style="margin-bottom:0px;">
                <li class="ui-state-default" style="margin:6px">
                  <div class="thumb-secs"> <img src="<?php echo esc_url($image_url); ?>"  id="form-widget_cs_widget_logo<?php echo absint($randomID)?>_img" style="max-height:80px; max-width:180px"  />
                    <div class="gal-edit-opts"> <a   href="javascript:del_media('cs_widget_logo<?php echo absint($randomID)?>')" class="delete"></a> </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
    
        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('about_text')); ?>">Text:<br />
                <textarea cols="20" rows="5" id="<?php echo cs_allow_special_char($this->get_field_id('about_text')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('about_text')); ?>" style="width:315px"><?php echo esc_attr($about_text); ?></textarea>
            </label>
        </p>
        
        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('address')); ?>"> Address:<br />
                <textarea cols="20" rows="5" id="<?php echo cs_allow_special_char($this->get_field_id('address')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('address')); ?>" style="width:315px"><?php echo esc_attr($address); ?></textarea>
            </label>
        </p>
        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('phone')); ?>"> Phone #:<br />
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('phone')); ?>" size="40"
                name="<?php echo cs_allow_special_char($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>" />
            </label>
         </p>
     
         <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('fax')); ?>"> Fax #:<br />
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('fax')); ?>" size="40" 
                name="<?php echo cs_allow_special_char($this->get_field_name('fax')); ?>" type="text" value="<?php echo esc_attr($fax); ?>" />
            </label>
        </p>
    
        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('email')); ?>"> Email #:<br />
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('email')); ?>" size="40" 
                name="<?php echo cs_allow_special_char($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>" />
            </label>
        </p>
    <?php
    }
    
    /**
     * @Update Info html form
     *
     *
     */
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['image_url'] = $new_instance['image_url'];
        $instance['image_url'] = $new_instance['image_url'];
        $instance['about_text']   = $new_instance['about_text'];
        $instance['address']   = $new_instance['address'];
        $instance['phone']     = $new_instance['phone'];
        $instance['fax']    = $new_instance['fax'];
        $instance['email']     = $new_instance['email'];
         return $instance;
    }
    
    /**
     * @Widget Info html form
     *
     *
     */
    function widget($args, $instance){
        global $cs_node;
        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$title = htmlspecialchars_decode(stripslashes($title));
        $image_url = empty($instance['image_url']) ? '' : apply_filters('widget_title', $instance['image_url']);
        $about_text = empty($instance['about_text']) ? '' : apply_filters('widget_title', $instance['about_text']);
        $address = empty($instance['address']) ? '' : apply_filters('widget_title', $instance['address']);        
        $phone = empty($instance['phone']) ? '' : apply_filters('widget_title', $instance['phone']);
        $fax = empty($instance['fax']) ? '' : apply_filters('widget_title', $instance['fax']);
        $email = empty($instance['email']) ? '' : apply_filters('widget_title', $instance['email']);        
        echo cs_allow_special_char($before_widget);
        if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title) . $title . $after_title;
            }            
        if ( isset ( $image_url ) && $image_url != '' ) {
            echo '<div class="logo"><a href="'.esc_url( home_url() ).'"><img src="'.$image_url.'" alt="" /></a></div>';
        }
        if(isset ( $about_text ) && $about_text != '' ){
            echo '<div class="cont-info-address"><p>'.$about_text.'</p></div>';    
        }         
            echo '<ul>';
            if(isset($address) and $address<>''){
                echo '<li><i class="icon-map-marker"></i><p>'.do_shortcode(htmlspecialchars_decode($address)).'</p></li>';
            }
            if(isset($phone) and $phone<>''){
                echo '<li><i class="icon-phone6"></i><p>'.htmlspecialchars_decode($phone).'</p></li>';
            }
            if(isset($fax) and $fax<>''){
                echo '<li><i class="icon-fax"></i><p>'.htmlspecialchars_decode($fax).'</p></li>';
            }
            if(isset($email) and $email<>''){
                echo '<li>
					<i class="icon-envelope4"></i>
					<p><a href="mailto:'.htmlspecialchars_decode($email).'">'.htmlspecialchars_decode($email).'</a></p>
				</li>';
            }
            echo '</ul>';       

    echo cs_allow_special_char($after_widget);
    }
}
add_action('widgets_init', create_function('', 'return register_widget("contactinfo");'));
/**
 * @Contact form widget Class
 *
 *
 */
if ( ! class_exists( 'cs_contact_msg' ) ) { 
    class cs_contact_msg extends WP_Widget {    
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
         
    /**
     * @init Contact Module
     *
     *
     */
     function cs_contact_msg() {
        $widget_ops = array('classname' => 'widget-form', 'description' => 'Select contact form to show in widget.');
        $this->WP_Widget('cs_contact_msg', 'CS : Contact Form', $widget_ops);
     }     
     /**
     * @Contact html form
     *
     *
     */
     function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '' ));
        $title = $instance['title'];
        $contact_email = isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : '';
        $contact_succ_msg = isset($instance['contact_succ_msg']) ? esc_attr($instance['contact_succ_msg']) : '';
        ?>
            <p>
            	<label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
                	<input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
              </label>
            </p>        
            <p>
              	<label for="<?php echo cs_allow_special_char($this->get_field_id('contact_email')); ?>"> Contact Email:
                	<input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('contact_email')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('contact_email')); ?>" type="text" value="<?php echo esc_attr($contact_email); ?>" />
              </label>
            </p>        
            <p>
              <label for="<?php echo cs_allow_special_char($this->get_field_id('contact_succ_msg')); ?>"> Success Message:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('contact_succ_msg')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('contact_succ_msg')); ?>" type="text" value="<?php echo esc_attr($contact_succ_msg); ?>" />
              </label>
            </p>      

<?php
         }        
        /**
         * @Contact Update form data
         *
         *
         */
         function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['contact_email'] = $new_instance['contact_email'];
            $instance['contact_succ_msg'] = $new_instance['contact_succ_msg'];            
               return $instance;
        }        
        /**
         * @Display Contact widget
         *
         *
         */
        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            global $wpdb, $post;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = htmlspecialchars_decode(stripslashes($title));
            $contact_email = isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : '';
            $contact_succ_msg = isset($instance['contact_succ_msg']) ? esc_attr($instance['contact_succ_msg']) : '';            
            // WIDGET display CODE Start
            echo cs_allow_special_char($before_widget);
            if (strlen($title) <> 1 || strlen($title) <> 0) {
                echo cs_allow_special_char($before_title) . $title . $after_title;
            }   
            $msg_form_counter = rand(1, 999); 
            if ( function_exists( 'cs_enqueue_validation_script' ) ) { cs_enqueue_validation_script(); }
            $error    = __('An error Occured, please try again later.', 'dir');
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var container = $('');
                    var validator = jQuery("#frm<?php echo cs_allow_special_char($msg_form_counter);?>").validate({
                        messages:{
                            contact_name: '',
                            contact_email:{
                                required: '',
                                email:'',
                            },
                            subject: {
                                required:'',
                            },
                            contact_msg: '',
                        },
                        errorContainer: container,
                        errorLabelContainer: jQuery(container),
                        errorElement:'div',
                        errorClass:'frm_error',
                        meta: "validate"
                    });
                });
                function frm_submit<?php echo cs_allow_special_char($msg_form_counter)?>(){
                    var $ = jQuery;
                    $("#submit_btn<?php echo cs_allow_special_char($msg_form_counter) ?>").hide();
                    $("#loading_div<?php echo cs_allow_special_char($msg_form_counter) ?>").html('<img src="<?php echo get_template_directory_uri()?>/assets/images/ajax-loader.gif" alt="" />');
                    var datastring =$('#frm<?php echo cs_allow_special_char($msg_form_counter) ?>').serialize() +"&cs_contact_email=<?php echo cs_allow_special_char($contact_email);?>&cs_contact_succ_msg=<?php echo cs_allow_special_char($contact_succ_msg);?>&cs_contact_error_msg=<?php echo cs_allow_special_char($error);?>&action=cs_contact_form_submit";
                    $.ajax({
                        type: 'POST', 
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: datastring, 
                        dataType: "json",
                        success: function(response) {
                            if (response.type == 'error'){
                                $("#loading_div<?php echo cs_allow_special_char($msg_form_counter);?>").html('');
                                $("#loading_div<?php echo cs_allow_special_char($msg_form_counter);?>").hide();
                                $("#message<?php echo cs_allow_special_char($msg_form_counter); ?>").addClass('error_mess');
                                $("#message<?php echo cs_allow_special_char($msg_form_counter); ?>").show();
                                $("#message<?php echo cs_allow_special_char($msg_form_counter); ?>").html(response.message);
                            } else if (response.type == 'success'){
                                $("#loading_div<?php echo cs_allow_special_char($msg_form_counter); ?>").html('');
                                $("#form_hide<?php echo cs_allow_special_char($msg_form_counter); ?>").hide();
                                $("#message<?php echo cs_allow_special_char($msg_form_counter); ?>").addClass('succ_mess');
                                $("#message<?php echo cs_allow_special_char($msg_form_counter); ?>").show();
                                $("#message<?php echo cs_allow_special_char($msg_form_counter); ?>").html(response.message);
                            }
                        }
                    });
                }
            </script>
            
            <div id="form_hide<?php echo cs_allow_special_char($msg_form_counter);?>">
            <form id="frm<?php echo cs_allow_special_char($msg_form_counter) ?>" name="frm<?php echo cs_allow_special_char($msg_form_counter);?>" method="post" action="javascript:<?php echo "frm_submit".$msg_form_counter. "()";
                ?>" novalidate>
                <ul class="group">
                    <li>
                      <input type="text" placeholder="Name" name="contact_name" id="contact_name" class="nameinput {validate:{required:true}}">
                    </li>
                    <li>
                      <input type="text" placeholder="Email" name="contact_email" id="contact_email" class="emailinput {validate:{required:true ,email:true}}">
                    </li>
                    <li>
                      <textarea placeholder="Message" name="contact_msg" id="contact_msg" class="{validate:{required:true}}"></textarea>
                    </li>
                    <li>
                      <input type="hidden" value="<?php echo cs_allow_special_char($contact_email);?>" name="cs_contact_email">
                      <input type="hidden" value="<?php echo cs_allow_special_char($contact_succ_msg);?>" name="cs_contact_succ_msg">
                      <input type="hidden" name="bloginfo" value="<?php echo get_bloginfo() ?>" />
                      <input type="hidden" name="counter_node" value="<?php echo cs_allow_special_char($msg_form_counter); ?>" />
                      <span id="loading_div<?php echo cs_allow_special_char($msg_form_counter);?>"><i class="icon-envelope4"></i></span>
                      <div id="message<?php echo cs_allow_special_char($msg_form_counter);?>" style="display:none;"></div>
                      <input type="submit" value="Send message" name="submit" id="submit_btn<?php echo cs_allow_special_char($msg_form_counter);?>">
                    </li>
                </ul>
            </form>
            </div>
            <?php            
            echo cs_allow_special_char($after_widget); // WIDGET display CODE End
        }
    }
}
add_action('widgets_init', create_function('', 'return register_widget("cs_contact_msg");'));
/**
 * @Revies(s) list widget Class
 */
if ( ! class_exists( 'cs_reviews' ) ) {
    class cs_reviews extends WP_Widget {
    /**
     * Outputs the content of the widget
         * @param array $args
     * @param array $instance
     */
         
    /**
     * @init User list Module
             */
     function cs_reviews() {
        $widget_ops = array('classname' => 'widget_reviews', 'description' => 'Select Category to show Reviews in widget.');
        $this->WP_Widget('cs_reviews', 'CS : Reviews', $widget_ops);
     }    
    /**
     * @User list html form
             */
     function form($instance) {         
            $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
            $title = $instance['title'];
            $get_post_slug = isset( $instance['get_post_slug'] ) ? esc_attr( $instance['get_post_slug'] ) : '';
            $showcount = isset( $instance['showcount'] ) ? esc_attr( $instance['showcount'] ) : '';    
            ?>
            <p>
              <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
              </label>
            </p>
            <p>
              <label for="<?php echo cs_allow_special_char($this->get_field_id('get_post_slug')); ?>"> Select Category:
                <select id="<?php echo cs_allow_special_char($this->get_field_id('get_post_slug')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('get_post_slug')); ?>" style="width:225px">
                  <option value=""> Select Category</option>
                  <?php
                    global $wpdb,$post;
                    $categories = get_categories('taxonomy=directory-category&child_of=0&hide_empty=0'); 
                    if($categories != ''){}
                    foreach ( $categories as $category){
                    ?>
                  <option <?php if(esc_attr($get_post_slug) == $category->slug){echo 'selected';}?> value="<?php echo cs_allow_special_char($category->slug);?>" > 
				  <?php echo $category->name;?> </option>
                  <?php
                  }
                  ?>
                </select>
              </label>
            </p>
            <p>
              <label for="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>"> Number of Posts:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>" size="2" name="<?php echo cs_allow_special_char($this->get_field_name('showcount')); ?>" type="text" value="<?php echo esc_attr($showcount); ?>" />
              </label>
            </p>
        <?php
        }        
        /**
         * @User list update data
                         */
         function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['get_post_slug'] = esc_sql($new_instance['get_post_slug']);
            $instance['showcount'] = esc_sql($new_instance['showcount']);
              return $instance;
         }
        /**
         * @Display User list widget */
         function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            global $wpdb, $post,$cs_theme_options;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
$title = htmlspecialchars_decode(stripslashes($title));
            $get_post_slug = empty($instance['get_post_slug']) ? ' ' : apply_filters('widget_title', $instance['get_post_slug']);
            $showcount = $instance['showcount'];
            // WIDGET display CODE Start
            echo balanceTags($before_widget,false);
            $cs_page_id = '';
            
             if (strlen($title) <> 1 || strlen($title) <> 0) {
                 echo balanceTags($before_title . $title . $after_title,false);
             }
            $showcount = $showcount<>'' ? $showcount:3;            
            if(isset($get_post_slug) and $get_post_slug <> ' ' and $get_post_slug <> ''){
                    $args = array( 'posts_per_page' => "$showcount",'post_type' => 'directory','directory-category' => "$get_post_slug",'meta_key'=>'cs_directory_review_rating','orderby'=>'meta_value_num','order'=>'DESC');
                }else{
                    $args = array( 'posts_per_page' => "$showcount",'post_type' => 'directory','meta_key'=>'cs_directory_review_rating','orderby'=>'meta_value_num','order'=>'DESC');
                }
                $custom_query = new WP_Query($args);
               if ( $custom_query->have_posts() <> "" ) {
                 echo '<ul>';
                  while ( $custom_query->have_posts()) : $custom_query->the_post();
                  $width = 150;
                  $height = 150;
                  $image_url = cs_get_post_img_src($post->ID, $width, $height);
                  $rating =get_post_meta($post->ID,'cs_directory_review_rating',true)*100/5;
                  $post_title = strlen(get_the_title())>25?substr(get_the_title(),0,25).'...':get_the_title();
                  //$rating
                    echo '<li>';
                    if($image_url<>''){
                            echo '<figure><a href="'.esc_url(get_permalink()).'"><img src="'.esc_url($image_url).'"></a></figure>';
                    }
                    echo '<div class="infotext" itemprop="reviewRating">
                            <h6><a href="'.esc_url(get_permalink()).'">'.esc_attr($post_title).'</a></h6>
                            <div class="cs-rating"><span style="width:'.absint($rating).'%" class="rating-box"></span></div>
                             <ul>
                                <li><span class="post">'.__("on","dir").'</span>'.date_i18n(get_option("date_format"),strtotime(get_the_date())).',</li>
                                <li><span class="post_by">'.__("Posted by","dir").'</span><a class="admin" href="'.get_author_posts_url(get_the_author_meta("ID")).'">'.get_the_author().'</a></li>
                             </ul>
                          </div>
                        </li>';
                    endwhile;
                    echo '</ul>';
                }else{
                _e("No result found.","dir");
             }   
             echo balanceTags($after_widget,false); 
        }
    }
}
add_action('widgets_init', create_function('', 'return register_widget("cs_reviews");'));

/**
 * @MailChimp widget Class
 *
 *
 */
if ( ! class_exists( 'cs_mailchimp' ) ) { 
	class cs_mailchimp extends WP_Widget{	
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
		 
	/**
	 * @init MailChimp Module
	 *
	 *
	 */
	 function cs_mailchimp(){
		$widget_ops = array('classname' => 'widget_newsletter', 'description' => 'MailChimp Newsletter Widget.' );
		$this->WP_Widget('cs_mailchimp', 'CS : MailChimp', $widget_ops);
	 }	 
	 /**
	 * @MailChimp html form
	 *
	 *
	 */
	 function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		$description = isset( $instance['description'] ) ? esc_attr( $instance['description'] ) : '';
	?>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          </label>
        </p>        
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('description')); ?>"> Description:
            <textarea class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('description')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('description')); ?>"><?php echo cs_allow_special_char($description); ?></textarea>
          </label>
        </p>        
        <?php
        }		
		/**
		 * @MailChimp update form data
		 *
		 *
		 */
		 function update($new_instance, $old_instance){
			  $instance = $old_instance;
			  $instance['title'] = $new_instance['title'];
			  $instance['description'] = $new_instance['description'];			
			  return $instance;
		 }
		 /**
		 * @Display MailChimp widget
		 *
		 *
		 */
		 function widget($args, $instance){
			  global $cs_node;		
			  extract($args, EXTR_SKIP);
			  $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			  $description = empty($instance['description']) ? ' ' : apply_filters('widget_title', $instance['description']);				
			  echo cs_allow_special_char($before_widget);		
			  if (!empty($title) && $title <> ' '){
				  echo cs_allow_special_char($before_title);
				  echo cs_allow_special_char($title);
				  echo cs_allow_special_char($after_title);
			  }		
			  global $wpdb, $post;		
			  wp_reset_query();
			   /**
				 * @Display MailChimp
				 *
				 *
				 */				 
				if ( function_exists( 'cs_custom_mailchimp' ) ) { echo cs_custom_mailchimp($description); }				
			    echo cs_allow_special_char($after_widget);
			  }
		  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("cs_mailchimp");') );

/**
 * @Advance Search widget Class
 *
 *
 */
if ( ! class_exists( 'cs_advance_search' ) ) { 
    class cs_advance_search extends WP_Widget {    
        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
             
        /**
         * @init Advance Search Module
         *
         *
         */
        function cs_advance_search() {
            $widget_ops = array('classname' => 'cs_advance_search', 'description' => 'Advance Search Widget.');
            $this->WP_Widget('cs_advance_search', 'CS : Advance Search', $widget_ops);
        }         
         /**
         * @Advance Search html form
         *
         *
         */
        function form($instance){
            $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
            $title = $instance['title'];
            $cs_directory_search_result_page = isset( $instance['cs_directory_search_result_page'] ) ? esc_attr( $instance['cs_directory_search_result_page'] ) : '';
        ?>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>        
         <p>
              <label for="<?php echo cs_allow_special_char($this->get_field_id('cs_directory_search_result_page')); ?>"> <?php _e('Search Result Page','dir');?>
                <select id="<?php echo cs_allow_special_char($this->get_field_id('cs_directory_search_result_page')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('cs_directory_search_result_page')); ?>" style="width:225px">
                    <option value=""><?php _e('Select Page','dir');?></option>
                    <?php 
                    $args = array(
                            'sort_order' => 'ASC',
                            'sort_column' => 'post_title',
                            'hierarchical' => 1,
                            'exclude' => '',
                            'include' => '',
                            'meta_key' => '',
                            'meta_value' => '',
                            'authors' => '',
                            'child_of' => 0,
                            'parent' => -1,
                            'exclude_tree' => '',
                            'number' => '',
                            'offset' => 0,
                            'post_type' => 'page',
                            'post_status' => 'publish'
                        ); 
                      
                      $pages = get_pages($args);
                      foreach($pages as $page){
                          ?>
                          <option <?php if($page->ID==$cs_directory_search_result_page)echo "selected";?> value="<?php echo absint($page->ID);?>"><?php echo esc_attr($page->post_title);?></option>
                          <?php
                      }
                    ?>
                </select>
              </label>
        </p>
        <p>
            Please make sure you have selected Search Result Page and on selected page there should be one 'dir' page builder element
        </p>
        <?php
        }
            
        /**
         * @Advance Search update form data
         *
         *
         */
        function update($new_instance, $old_instance){
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['cs_directory_search_result_page'] = $new_instance['cs_directory_search_result_page'];                        
            return $instance;
        }    
        /**
         * @Display Advance Search widget
         *
         *
         */
        function widget($args, $instance){
            global $cs_theme_options, $post;            
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
$title = htmlspecialchars_decode(stripslashes($title));            
            $cs_directory_search_result_page = isset( $instance['cs_directory_search_result_page'] ) ? esc_attr( $instance['cs_directory_search_result_page'] ) : '';
            echo cs_allow_special_char($before_widget);            
            if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }            
            if(class_exists('wp_directory')){
                cs_get_directory_filters('','','','listing','4','widget',$cs_directory_search_result_page);
            }            
            echo cs_allow_special_char($after_widget);
        }
    }
}
add_action('widgets_init', create_function('', 'return register_widget("cs_advance_search");'));
/**
 * @Ads Banners Widget Class
 */
if ( ! class_exists( 'cs_ads_banner' ) ) {
    class cs_ads_banner extends WP_Widget {
    /**
     * Outputs the content of the widget
     * @param array $args
     * @param array $instance
     */
         
    /**
     * @init User list Module */
     function cs_ads_banner() {
        $widget_ops = array('classname' => 'cs_ads_banner', 'description' => 'Set Banners option in widget.');
        $this->WP_Widget('cs_ads_banner', 'CS : Ads Banners', $widget_ops);
     }    
    /**
     * @Ads Banners html form
             */
     function form($instance) {             
            $cs_rand_id = rand(23789,934578930);            
            $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'banner_code' => '' ) );
            $title = $instance['title'];
            $banner_style = isset( $instance['banner_style'] ) ? esc_attr( $instance['banner_style'] ) : '';
            $banner_code = $instance['banner_code'];
            $banner_view = isset( $instance['banner_view'] ) ? esc_attr( $instance['banner_view'] ) : '';
            $showcount = isset( $instance['showcount'] ) ? esc_attr( $instance['showcount'] ) : '';    
            ?>
            <p>
              <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
              </label>
            </p>
            <p>
              <label for="<?php echo cs_allow_special_char($this->get_field_id('banner_view')); ?>"> Banner View:
                <select onchange="cs_banner_widget_toggle(this.value, '<?php echo cs_allow_special_char($cs_rand_id) ?>')" id="<?php echo cs_allow_special_char($this->get_field_id('banner_view')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('banner_view')); ?>" style="width:225px">
                  <option value="single" <?php if(cs_allow_special_char($banner_view) == 'single'){echo 'selected';}?>>Single Banner</option>
                  <option value="random" <?php if(cs_allow_special_char($banner_view) == 'random'){echo 'selected';}?>>Random Banners</option>
                </select>
              </label>
            </p>
            <p id="cs_banner_code_field_<?php echo cs_allow_special_char($cs_rand_id) ?>" style="display:<?php echo cs_allow_special_char($banner_view) == 'single' ? 'block' : 'none'; ?>;">
              <label for="<?php echo cs_allow_special_char($this->get_field_id('banner_code')); ?>"> Banner Code:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('banner_code')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('banner_code')); ?>" type="text" value="<?php echo htmlspecialchars($banner_code); ?>" />
              </label>
            </p>
            <p id="cs_banner_style_field_<?php echo cs_allow_special_char($cs_rand_id) ?>" style="display:<?php echo cs_allow_special_char($banner_view) == 'random' ? 'block' : 'none'; ?>;">
              <label for="<?php echo cs_allow_special_char($this->get_field_id('banner_style')); ?>"> Banner Style:
                <select id="<?php echo cs_allow_special_char($this->get_field_id('banner_style')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('banner_style')); ?>" style="width:225px">
                  <option value="top_banner" <?php if(cs_allow_special_char($banner_style) == 'top_banner'){echo 'selected';}?>>Top Banner</option>
                  <option value="bottom_banner" <?php if(cs_allow_special_char($banner_style) == 'bottom_banner'){echo 'selected';}?>>Bottom Banner</option>
                  <option value="sidebar_banner" <?php if(cs_allow_special_char($banner_style) == 'sidebar_banner'){echo 'selected';}?>>Sidebar Banner</option>
                  <option value="vertical_banner" <?php if(cs_allow_special_char($banner_style) == 'vertical_banner'){echo 'selected';}?>>Vertical Banner</option>
                </select>
              </label>
            </p>
            <p id="cs_banner_number_field_<?php echo cs_allow_special_char($cs_rand_id) ?>" style="display:<?php echo cs_allow_special_char($banner_view) == 'random' ? 'block' : 'none'; ?>;">
              <label for="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>"> Number of Banners:
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>" size="2" name="<?php echo cs_allow_special_char($this->get_field_name('showcount')); ?>" type="text" value="<?php echo esc_attr($showcount); ?>" />
                  <br />
                <span><i>Set maximum number of Banners upto 10.</i></span>
              </label>
            </p>
        <?php
        }
        /**
         * @Ads Banners update data*/
         function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['banner_style'] = esc_sql($new_instance['banner_style']);
            $instance['banner_code'] = $new_instance['banner_code'];
            $instance['banner_view'] = esc_sql($new_instance['banner_view']);
            $instance['showcount'] = esc_sql($new_instance['showcount']);
              return $instance;
         }
        /**
         * @Ads Banners list widget */
         function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            global $wpdb, $post, $cs_theme_options;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = htmlspecialchars_decode(stripslashes($title));
            $banner_style = empty($instance['banner_style']) ? ' ' : apply_filters('widget_title', $instance['banner_style']);
            $banner_code = empty($instance['banner_code']) ? ' ' : $instance['banner_code'];
            $banner_view = empty($instance['banner_view']) ? ' ' : apply_filters('widget_title', $instance['banner_view']);
            $showcount = $instance['showcount'];
            // WIDGET display CODE Start
            echo balanceTags($before_widget,false);            
             if (strlen($title) <> 1 || strlen($title) <> 0) {
                 echo balanceTags($before_title . $title . $after_title,false);
             }
            $showcount = ( $showcount <> '' || !is_integer($showcount) ) ? $showcount : 2;
            
            if($banner_view == 'single'){
                echo do_shortcode($banner_code);
            }
            else{
                $cs_total_banners = ( is_integer($showcount) && $showcount > 10) ? 10 : $showcount;                
                if( isset($cs_theme_options['banner_field_title']) ) {                    
                    $i = 0;
                    $d = 0;
                    $cs_banner_array = array();
                    foreach($cs_theme_options['banner_field_title'] as $banner) :
                        if($cs_theme_options['banner_field_style'][$i] == $banner_style){
                            $cs_banner_array[] = $i;
                            $d++;
                        }
                        if($cs_total_banners == $d){
                            break;
                        }
                        $i++;
                    endforeach;
                    if(sizeof($cs_banner_array) > 0){
                        $cs_act_size = sizeof($cs_banner_array)-1;
                        $cs_rand_banner = rand(0, $cs_act_size);
                        
                        $cs_rand_banner = $cs_banner_array[$cs_rand_banner];
                        echo do_shortcode('[cs_ads id="'.$cs_theme_options['banner_field_code_no'][$cs_rand_banner].'"]');
                    }
                }
            }
              
             echo balanceTags($after_widget,false); 
        }
    }
}
add_action('widgets_init', create_function('', 'return register_widget("cs_ads_banner");'));
//=================================================================================
// @Recent posts widget Class
//================================================================================
if ( ! class_exists( 'cs_fancy_menu' ) ) { 
    class cs_fancy_menu extends WP_Widget{    
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
         
    /**
     * @init Feancy Menu Module
     *
     *
     */
     function cs_fancy_menu(){
        $widget_ops = array('classname' => 'cs-fancy-menu', 'description' => 'Menu List' );
        $this->WP_Widget('cs_fancy_menu', 'CS : Fancy Menu', $widget_ops);
     }     
     /**
     * @Fancy Menu html form
     *
     *
     */
     function form($instance){
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $cs_widget_title = $instance['title'];
        $cs_sticky_menu = isset( $instance['cs_sticky_menu'] ) ? esc_attr( $instance['cs_sticky_menu'] ) : '';
        $cs_menu_name = isset( $instance['cs_menu_name'] ) ? esc_attr( $instance['cs_menu_name'] ) : '';
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        // If no menus exists, direct the user to go and create some.
        if ( !$menus ) {
            echo '<p>'. sprintf( __('No menus have been created yet.', 'dir').'<a href="%s">Create some</a>', admin_url('nav-menus.php') ) .'</p>';
            return;
        }        
    ?>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> Title:
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($cs_widget_title); ?>" />
          </label>
        </p>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('cs_menu_name')); ?>"> Select Menu
            <select id="<?php echo cs_allow_special_char($this->get_field_id('cs_menu_name')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('cs_menu_name')); ?>" style="width:225px">
              <option value="" >All</option>
              <?php
              foreach ( $menus as $menu ) {
                $selected = $cs_menu_name == $menu->term_id ? ' selected="selected"' : '';
                echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
             }            
            ?>
            </select>
          </label>
        </p>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('cs_sticky_menu')); ?>"> <?php _e('Sticky','dir');?>
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('cs_sticky_menu')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('cs_sticky_menu')); ?>" value="true" type="checkbox"  <?php if(isset($instance['cs_sticky_menu']) && $instance['cs_sticky_menu']=='true' ) echo 'checked="checked"'; ?> />
          </label>
        </p>        
        <?php
        }        
        /**
         * @Fancy menu update form data
         *
         *
         */
        function update($new_instance, $old_instance){
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['cs_sticky_menu'] = $new_instance['cs_sticky_menu'];
            $instance['cs_menu_name'] = $new_instance['cs_menu_name'];
            return $instance;
        }
        function widget($args, $instance){
            global $cs_node,$wpdb, $post;            
            extract($args, EXTR_SKIP);
            $cs_widget_title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
            $cs_widget_title = htmlspecialchars_decode(stripslashes($cs_widget_title));
            $cs_sticky_menu = isset( $instance['cs_sticky_menu'] ) ? esc_attr( $instance['cs_sticky_menu'] ) : '';
            $cs_menu_name = empty($instance['cs_menu_name']) ? ' ' : apply_filters('widget_title', $instance['cs_menu_name']);            
            echo cs_allow_special_char($before_widget);    
            if (!empty($cs_widget_title) && $cs_widget_title <> ' '){
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($cs_widget_title);
                echo cs_allow_special_char($after_title);
            }
            $cs_menu_class = $cs_sticky_menu == true ? 'shortcode-nav cs-stickynav' : 'shortcode-nav';
            $cs_menu_arg = array(
                'theme_location'  => '',
                'menu'            => $cs_menu_name,
                'container'       => 'nav',
                'container_class' => $cs_menu_class,
                'container_id'    => '',
                'menu_class'      => 'menu',
                'menu_id'         => '',
                'echo'            => true,
                'fallback_cb'     => 'wp_page_menu',
                'before'          => '',
                'after'           => '',
                'link_before'     => '',
                'link_after'      => '',
                'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                'depth'           => 0,
                'walker'          => ''
            );
            wp_nav_menu( $cs_menu_arg );
            echo cs_allow_special_char($after_widget);
        }
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("cs_fancy_menu");') );
?>