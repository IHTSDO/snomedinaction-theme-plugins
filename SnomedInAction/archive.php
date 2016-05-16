<?php
    get_header();
    global $cs_node,$cs_theme_options,$cs_counter_node;    
    if( class_exists('wp_directory') && is_author() && isset($_GET['action']) && $_GET['action'] == 'detail' ) {
        $cs_agent_detail_template = wp_directory::plugin_dir().'directory/dir-agents/agents_detail.php';
        include $cs_agent_detail_template;
    }
    else {    
    $cs_layout     = '';
    if(isset($cs_theme_options['cs_excerpt_length']) && $cs_theme_options['cs_excerpt_length'] <> ''){ 
        $default_excerpt_length = $cs_theme_options['cs_excerpt_length']; }else{ $default_excerpt_length = '255';
    }
    $cs_layout = isset($cs_theme_options['cs_default_page_layout']) ? $cs_theme_options['cs_default_page_layout']:'';
     if ( isset( $cs_layout ) && ($cs_layout == "sidebar_left" || $cs_layout == "sidebar_right")) {
        $cs_page_layout = "page-content";
     } else {
        $cs_page_layout = "page-content-fullwidth";
     }
    $cs_sidebar    = isset($cs_theme_options['cs_default_layout_sidebar']) ? $cs_theme_options['cs_default_layout_sidebar']:'';
    $cs_tags_name = 'post_tag';
    $cs_categories_name = 'category';
 ?>
    <!-- PageSection Start -->
    <section class="page-section" style=" padding: 0; ">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                 <!--Left Sidebar Starts-->
                <?php if ($cs_layout == 'sidebar_left'){ ?>
                    <div class="page-sidebar">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?>
						<?php endif; ?>
                    </div>
                <?php } ?>
                <!--Left Sidebar End-->                
                <!-- Page Detail Start -->
                   <div class="<?php echo esc_attr($cs_page_layout); ?>">
                <!-- Blog Post Start -->
                 <?php 
                 if(is_author()){
                     global $author;
                     $userdata = get_userdata($author);
                 }
                 if(category_description() || is_tag() || (is_author() && isset($userdata->description) && !empty($userdata->description))){
                    echo '<div class="widget evorgnizer">';
                    if(is_author()){?>
                        <figure>
                            <a>
                            <?php 
							$cs_profile_img = '';
							if( class_exists('wp_directory') ){
								$cs_profile_img = cs_get_user_avatar(1 ,$author);
							}
                            if( class_exists('wp_directory') && $cs_profile_img <> '' ){
								$cs_profile_img = cs_get_user_avatar(1 ,$author);
                                echo '<img src="'.$cs_profile_img.'" alt="" />';
                            }else{
                                echo get_avatar($userdata->user_email, apply_filters('Cs_author_bio_avatar_size', 70));
                            }
                            ?>
                            </a>
                        </figure>
                        <div class="left-sp">
                            <h5><a><?php echo esc_attr($userdata->display_name); ?></a></h5>
                            <p><?php echo balanceTags($userdata->description, true); ?></p>
                        </div>
                    <?php } elseif ( is_category()) {
                            $category_description = category_description();
                            if ( ! empty( $category_description ) ) {
                            ?>
                                <div class="left-sp">
                                    <p><?php  echo category_description();?></p>
                                </div>
                            <?php }?>
                    <?php } elseif(is_tag()){  
                            $tag_description = tag_description();
                            if ( ! empty( $tag_description ) ) {
                            ?>
                            <div class="left-sp">
                                <p><?php echo apply_filters( 'tag_archive_meta', $tag_description );?></p>
                            </div>
                        <?php }
                    }
                    echo '</div>';
                }
                    if (empty($_GET['page_id_all']))
                        $_GET['page_id_all'] = 1;
                    if (!isset($_GET["s"])) {
                        $_GET["s"] = '';
                    }
                    $description = 'yes';
                    $taxonomy = 'category';
                    $taxonomy_tag = 'post_tag';
                    $args_cat = array();
                    if(is_author()){
                        $args_cat = array('author' => $wp_query->query_vars['author']);
                        $post_type = array( 'post','directory');
                    } elseif(is_date()){
                        if(is_month() || is_year() || is_day() || is_time()){
                            $args_cat = array('m' => $wp_query->query_vars['m'],'year' => $wp_query->query_vars['year'],'day' => $wp_query->query_vars['day'],'hour' => $wp_query->query_vars['hour'], 'minute' => $wp_query->query_vars['minute'], 'second' => $wp_query->query_vars['second']);
                        }
                        $post_type = array( 'post');
                    } else if ((isset( $wp_query->query_vars['taxonomy']) && !empty( $wp_query->query_vars['taxonomy'] )) ) {
                        $taxonomy = $wp_query->query_vars['taxonomy'];
                        $taxonomy_category='';
                        $taxonomy_category=$wp_query->query_vars[$taxonomy];
                        if ( $wp_query->query_vars['taxonomy']=='directory-category' || $wp_query->query_vars['taxonomy']=='directory-tag') {
                          $args_cat = array( $taxonomy => "$taxonomy_category");
                          $post_type='directory';
                         }else {
                            $taxonomy = 'category';
                            $args_cat = array();
                            $post_type='post';
                        }
                    } else if( is_category() ) {
                        $taxonomy = 'category';
                        $args_cat = array();
                        $category_blog = $wp_query->query_vars['cat'];
                        $post_type='post';
                        $args_cat = array( 'cat' => "$category_blog");                    
                    } else if ( is_tag() ) {
                         $taxonomy = 'category';
                        $args_cat = array();
                        $tag_blog = $wp_query->query_vars['tag'];
                        $post_type='post';
                        $args_cat = array( 'tag' => "$tag_blog");                    
                    } else {
                        $taxonomy = 'category';
                        $args_cat = array();
                        $post_type='post';
                    }                    
                    $args = array( 
                    'post_type'         => $post_type, 
					'post__in' => get_option( 'sticky_posts' ), 
                    'paged'             => $_GET['page_id_all'],
                    'post_status'     => 'publish', 
                    'order'             => 'ASC',
                );                
                $args = array_merge( $args_cat,$args );
                $custom_query = new WP_Query( $args );
                 if ( $custom_query->have_posts() ): 
                    while ( $custom_query->have_posts() ) : $custom_query->the_post();
                     ?> 
                    <div class="col-md-12">
                       <article class="cs-blog blog-medium blog-small">
                        <?php
                            $width = '370';
                            $height = '280';
                            $title_limit = 1000;
                            $thumbnail = cs_get_post_img_src( $post->ID, $width, $height );
                                if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                                    <div class="main-thumb">
                                        <figure><a href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo esc_url( $thumbnail );?>"></a>
                                          <figcaption><a class="hover-icon" href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png"></a></figcaption>
                                        </figure>
                                      </div>
                               <?php
                                }
                               ?>
                           <div class="bloginfo-sec">
                              <?php cs_featured(); ?>
                              <h2>
                              	<a href="<?php esc_url(the_permalink());?>"><?php echo substr(get_the_title(),0, $title_limit); if(strlen(get_the_title())>$title_limit){echo '...';}?>
                                </a>
                              </h2>
                              <?php if ($description == 'yes') {?><p> <?php echo cs_get_the_excerpt(255,'ture','');?></p><?php } ?> 
                              <div class="post-thumb">
                                  <ul class="thumb-options">
                                    <li><?php _e('Posted On','dir');?><time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>">
										<?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time> 
                                        <?php 
                                       /* Get All Cats */
                                        $categories_list = get_the_term_list ( get_the_id(), 'category', '' , ', ', '' );                                        
                                        if ( $categories_list ){
                                            _e('In','dir');
                                          printf( __( '%1$s', 'dir'),$categories_list );
                                        } 
                                    ?>
                                </li>
                                  </ul>
                                  <a href="<?php esc_url(the_permalink());?>" class="read-more"><?php _e('Read more','dir');?></a>
                                </div>
                            </div>
                       </article>
                     </div>
                <?php endwhile; 
                else:
                    if ( function_exists( 'cs_fnc_no_result_found' ) ) { cs_fnc_no_result_found(); }
                endif;  
                       $qrystr = '';
                    // pagination start
                        if ($custom_query->found_posts > get_option('posts_per_page')) {
                         if ( isset($_GET['page_id']) ) $qrystr .= "&page_id=".$_GET['page_id'];
                         if ( isset($_GET['author']) ) $qrystr .= "&author=".$_GET['author'];
                         if ( isset($_GET['tag']) ) $qrystr .= "&tag=".$_GET['tag'];
                         if ( isset($_GET['cat']) ) $qrystr .= "&cat=".$_GET['cat'];
                         if ( isset($_GET['events-categories']) ) $qrystr .= "&events-categories=".$_GET['events-categories'];
                         if ( isset($_GET['events-tags']) ) $qrystr .= "&events-tags=".$_GET['events-tags'];
                         if ( isset($_GET['m']) ) $qrystr .= "&m=".$_GET['m'];                         
                         if ( function_exists( 'cs_pagination' ) ) {  echo cs_pagination($custom_query->found_posts,get_option('posts_per_page'), $qrystr); }                                
                        }
                ?>
           </div>
                <!-- Page Detail End -->                
                <!-- Right Sidebar Start -->
                <?php if ( $cs_layout  == 'sidebar_right'){ ?>
                   <div class="page-sidebar"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?><?php endif; ?></div>
                <?php } ?>
                <!-- Right Sidebar End -->
          </div>
        </div>
     </section>
<?php 
    }
get_footer(); 
?> 