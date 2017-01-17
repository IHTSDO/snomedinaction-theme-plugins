<?php
/**
 * The template for displaying Footer
 */
 global $wpdb,$cs_theme_options;
 $cs_footer_back_to_top = isset($cs_theme_options['cs_footer_back_to_top'])? $cs_theme_options['cs_footer_back_to_top'] : '';
 $cs_sub_footer_social_icons = isset($cs_theme_options['cs_sub_footer_social_icons'])? $cs_theme_options['cs_sub_footer_social_icons'] : '';
 ?>
        </div>
        </main>
        <div class="clear"></div>
        <!-- Footer -->
        		
		<?php

        $cs_footer_switch = '';
		$cs_footer_switch = isset($cs_theme_options['cs_footer_switch'])? $cs_theme_options['cs_footer_switch'] : '';
        if(isset($cs_footer_switch) and $cs_footer_switch=='on'){            
            $cs_footer_widget = $cs_theme_options['cs_footer_widget'];
            if(isset($cs_footer_widget) and $cs_footer_widget == 'on'){
                ?>
                <footer id="footer-sec">
                    <div class="container">
                        <div class="row">
                            <?php 
							$cs_footer_sidebar = (isset($cs_theme_options['cs_footer_widget_sidebar']) and $cs_theme_options['cs_footer_widget_sidebar'] <> "select sidebar") ? $cs_theme_options['cs_footer_widget_sidebar'] : 'footer-widget-1';
                            if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_footer_sidebar) ) : endif; 
                            ?>
                        </div>
                    </div>
                    </footer>
            <?php } ?>        
        <!-- Bottom Section -->
        <div class="footer-content">
           <?php
            $cs_footer_newsletter = $cs_theme_options['cs_footer_newsletter'];
            	if( isset($cs_footer_newsletter) and $cs_footer_newsletter=='on'){
            ?>
                    <div id="newslatter-sec">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="user-signup">
                                        <span class="news-title"><?php _e('Join Box Theme Newsletter be always Up to Date','dir');?></span>
                                         <?php  if ( function_exists( 'cs_custom_mailchimp' ) ) { echo cs_custom_mailchimp(); }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php } ?>
            <div id="copyright"> <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="footer_icon"><?php if ( function_exists( 'cs_footer_logo' ) ) { cs_footer_logo(); } ?></div>
                        <?php
                        $cs_sub_footer_menu = $cs_theme_options['cs_sub_footer_menu'];
                        if( isset($cs_sub_footer_menu) and $cs_sub_footer_menu == 'on' ){
                        ?>
                            <nav class="footer-nav">
                                <?php if ( function_exists( 'cs_navigation' ) ) { cs_navigation('footer-menu','','','1'); } ?>
                            </nav>
						<?php
                        } 
                        $cs_copy_right = isset($cs_theme_options['cs_copy_right'])? $cs_theme_options['cs_copy_right'] : '';
                        if(isset($cs_copy_right) and $cs_copy_right<>''){ 
							echo '<p>'.do_shortcode(htmlspecialchars_decode($cs_copy_right)).'</p>'; 
                        } else{
							echo '<p>&copy;'.gmdate("Y").' '.get_option("blogname").' Wordpress '.__('All rights reserved','dir').'.</p>';  
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php if( $cs_sub_footer_social_icons == 'on' ){ ?>
                            <div class="social-media">
                                <ul>
									<?php if ( function_exists( 'cs_social_network' ) ) { cs_social_network(); } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if($cs_footer_back_to_top == 'on'){ ?>
                                <a href="#" id="backtop"><i class="icon-angle-up"></i></a>
                    <?php } ?>
                </div>
            </div> </div>
        </div>
        <?php } ?>
     <div class="clear"></div>
    </div>
    <!-- Wrapper End -->   
    <?php wp_footer(); ?>
		<!--Start Cookie Script-->
<script type="text/javascript" charset="UTF-8" src="https://cookie-script.com/s/aaec2a439e8e0fc7199222b89576e345.js"></script>
<!--End Cookie Script-->
<!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41892858-4', 'auto');
  ga('send', 'pageview');
</script>

</body>
</html>