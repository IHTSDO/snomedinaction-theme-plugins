<?php
/**
 * The template for Home page 
 */ 
    get_header();
     global $cs_node,$cs_theme_options,$cs_counter_node;
    if(isset($cs_theme_options['cs_excerpt_length']) && $cs_theme_options['cs_excerpt_length'] <> ''){ 
        $default_excerpt_length = $cs_theme_options['cs_excerpt_length']; }else{ $default_excerpt_length = '255';
    } 
    $cs_layout     = isset($cs_theme_options['cs_default_page_layout']) ? $cs_theme_options['cs_default_page_layout'] : '';
    if ( isset( $cs_layout ) && ($cs_layout == "sidebar_left" || $cs_layout == "sidebar_right")) {
        $cs_page_layout = "page-content";
     } else {
        $cs_page_layout = "page-content-fullwidth";
     }
    $cs_sidebar    = $cs_theme_options['cs_default_layout_sidebar'];
    $cs_tags_name = 'post_tag';
    $cs_categories_name = 'category';
    ?>   
       <section class="page-section" style="padding:0;">
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
                    <?php 
                        if ( have_posts() ) : 
                               if (empty($_GET['page_id_all']))
                             	 $_GET['page_id_all'] = 1;
                              if (!isset($_GET["s"])) {
                             	 $_GET["s"] = '';
                              }
                            while ( have_posts() ) : the_post(); 
								$width = '372';
								$height = '279';
								$title_limit = 1000;
								$thumbnail = cs_get_post_img_src( $post->ID, $width, $height );
								$description = 'yes'; 
								$excerpt     = '255'; 
								$post_thumb_view = 'Single Image';
								$post_xml = get_post_meta(get_the_id(), "post", true);
                            if ( $post_xml <> "" ) {
                                $cs_xmlObject = new SimpleXMLElement($post_xml);
                                $post_thumb_view = $cs_xmlObject->post_thumb_view;    
                            }
                      ?>
                         <div class="col-md-12">
                            <article class="cs-blog blog-medium blog-small">
                            <?php if ( $post_thumb_view == 'Single Image' ){
                                    if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                                          <div class="main-thumb">
                                            <figure><a href="<?php esc_url(the_permalink());?>"><img alt="hover" src="<?php echo esc_url( $thumbnail );?>"></a>
                                              <figcaption>
                                                <a class="hover-icon" href="<?php esc_url(the_permalink());?>">
                                                <img alt="hover" src="<?php echo get_template_directory_uri();?>/assets/images/hover-img.png"></a>
                                               </figcaption>
                                            </figure>
                                          </div>
                                <?php }
                                  } else if ($post_thumb_view == 'Slider') {
                                        echo '<div class="main-thumb">';
                                        cs_featured();
                                        cs_post_flex_slider($width,$height,get_the_id(),'post-list');
                                        echo '</div>';
                                  } 
                             ?>
                              <div class="bloginfo-sec">
                                  <?php cs_featured(); ?>
                                  <h2><a href="<?php esc_url(the_permalink());?>">
                                    <?php echo substr(get_the_title(),0, $title_limit); if(strlen(get_the_title())>$title_limit){echo '...';}?></a></h2>
                                  <?php if ($description == 'yes') {?><p> <?php echo cs_get_the_excerpt($excerpt,'ture','');?></p><?php } ?> 
                                  <div class="post-thumb">
                                      <ul class="thumb-options">
                                        <li><?php _e('Posted On','dir');?>
                                            <time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>">
                                            <?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time> 
                                        <?php 
                                       /* Get All Cats */
                                        $categories_list = get_the_term_list ( get_the_id(), 'category', '' , ', ', '' );
                                        if ( $categories_list ){
                                            _e('In','dir');
                                          printf( __( '%1$s', 'dir'),$categories_list );
                                        } 
                                       // End if Cats 
                                    ?>
                                    </li>
                                      </ul>
                                      <a href="<?php esc_url(the_permalink());?>" class="read-more"><?php _e('Read more','dir');?></a>
                                    </div>
                                </div>
                            </article>
                         </div>
                    <?php 
                        endwhile; 
                    else:
                         if ( function_exists( 'cs_fnc_no_result_found' ) ) { cs_fnc_no_result_found(); }
                    endif; 
                    $qrystr = '';
                        if ( isset($_GET['page_id']) ) $qrystr .= "&page_id=".$_GET['page_id'];
                        if ($wp_query->found_posts > get_option('posts_per_page')) {
                           if ( function_exists( 'cs_pagination' ) ) { echo cs_pagination(wp_count_posts()->publish,get_option('posts_per_page'), $qrystr); } 
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

    <?php get_footer(); ?>