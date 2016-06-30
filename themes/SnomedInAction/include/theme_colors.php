<?php
/**
 * The template Theme Colors
 */
 
/** 
 * @Set Theme Colors
 *
 *
 */ 
 if ( ! function_exists( 'cs_theme_colors' ) ) {
    function cs_theme_colors(){
        global $post,$cs_theme_options;
         $cs_theme_color = $cs_theme_options['cs_theme_color'];
        $sub_header_border_color = isset($cs_theme_options['cs_sub_header_border_color']) ? $cs_theme_options['cs_sub_header_border_color'] : '';
        $main_header_border_color = isset($cs_theme_options['cs_header_border_color']) ? $cs_theme_options['cs_header_border_color'] : '';        
        $page_header_style = '';
        $page_header_border_colr = '';
        $page_subheader_border_color = '';
        if(is_page() || is_single()){
            $cs_post_type = get_post_type($post->ID);
            switch($cs_post_type){
                case 'directory':
                    $post_type_meta = 'cs_directory_meta';
                    break;
				case 'product':
					$post_type_meta = 'product';
					break;
                default:
                    $post_type_meta = 'cs_page_builder';
            }            
            $cs_page_bulider = get_post_meta($post->ID, "$post_type_meta", true);
            $cs_xmlObject = new stdClass();
            if(isset($cs_page_bulider) && $cs_page_bulider <> ''){
                $cs_xmlObject = new SimpleXMLElement($cs_page_bulider);
                $page_header_style = $cs_xmlObject->header_banner_style;
                $page_header_border_colr = $cs_xmlObject->page_main_header_border_color;
                $page_subheader_border_color = $cs_xmlObject->page_subheader_border_color;
            }
        }
 ?>
<style type="text/css">
/*!
* Theme Color File */

/*!
* Theme Color */
.cs-color,/*widget*/
/* ShopButton */.woocommerce ul.products li.product a.add_to_cart_button, .woocommerce-page ul.products li.product a.add_to_cart_button,
.woocommerce #content input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page #content input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button /* ShopButton */,/*Category*/.select-category ul li a,/*CheckBox*/ul.check-box label,/*Widget*/aside .widget ul li:hover > a,/*DirectoryTab*/.dr_filtertabs .nav-tabs > li.active > a,.dr_filtertabs .nav-tabs > li.active > a i,.read-more,
.panel-group.default .panel-title a,.panel-group.simple .panel-title a,.pagination ul li a.active,.cs-filter-menu li a:hover,.cs-filter-menu li a.active,.grid-filter li.active a,.dr_shortoption .cs-organizer .organizer-name h6 a,.cs_assigment_tabs ul li:hover a,.cs_assigment_tabs ul li.active a,.realestate-gallery .dr_pricesection span,.post-thumb .thumb-options li a,.opening-hours ul li span i,.cs-tabs .nav-tabs > li.active > a,.cs-price .inner-sec,.cs-filter-menu li.active a,.go-home,.shortcode-nav ul > li:hover > a:after,.shortcode-nav ul > li:hover > a i,
.date-time time small,.thumblist li a.comment-reply-link,.registor-log a i,.become-agent,.cs-directory .dr_pricesection span,.listNav .ln-letters a,.cs_directory_categories ul li:hover > a,
.directory-section:hover h2 a,.widget ul li a:hover,.recentblog_post a:hover,.cs-blog:hover h2 a,.cs-team h2:hover a,.directory-section:hover h5 a,.cat-inner:hover a,.organizer-name:hover a,
.relevant-search ul li:hover a,.cs-grid-view article:hover h5 a,.element-breadcrumb .breadcrumbs ul li a:hover,.cs-post-sharebtn article:hover h5 a,.cs-about-author h5 a:hover,#comment .auther:hover a,.pagination ul li a:hover,.saved-ads h3 a:hover,.ads-in:hover h4 a,.directory-package td a:hover,.dr_postoption li a:hover,.team-grid .text span,.agentdetail-info:hover h2 a,
.cs-login-sec .cs-user-menu li a:hover,.twitter-section .text a,.sg-footer a:hover,.loc-info:hover h2 a,.main-navbar .cs-login-sec ul li .cs-user,
.woocommerce ul.products li.product:hover h3,.cs-services figure i, .grid_two_listing .cs-location-address i, .list-attachment-wrap .cs_attachments:hover .cs-download i {
 color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}
/*!
* Theme Background Color */
.cs-bg-color,/* ShopButton */.cs-bgcolor,
.woocommerce #content input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover,/* ShopButton *//*SubHeader*/.breadcrumb ul li:first-child:after,/*SocialMedia*/.social-media ul li a:hover,/*PriceRange*/.irs-bar,.irs-slider.to:before,.irs-slider.from:before,/*Blog*/.cs-post-sharebtn a.post-np:hover,/*Widget*/.widget_text a.read_more,/*DirectoryTab*/.dr_filtertabs .nav-tabs > li.active > a:before,.cs-tags ul li a:hover,.cs-attachments li:hover figure,/*dropcap*/.dropcap-one::first-letter,.cs-update-avatar,span.backtotop a,.cs-search form label input[type='submit'],.ui-widget-header,.gmapzoomplus:hover,.gmapzoomminus:hover,.gmaplock:hover,.gmapunlock:hover,.gmapcurrentloc:hover,.ui-slider-handle strong,
.directory-advanced-search-content .SumoSelect > .optWrapper > .options > li:hover,.directory-advanced-search-content .SumoSelect > .optWrapper > .options > li.selected,.cs-form-element li input[type='button'],.cs-form-element .SumoSelect:hover > .CaptionCont > label,.icon-input a,.cs_profile_tabs ul.cs-user-menu li.active,.fileUpload,.faq-form .dr_custmbtn,.widget_tag_cloud a,
/*RangeSlider*/.directory-advanced-search-content ul li .ui-state-default:before, .directory-advanced-search-content ul li .ui-widget-content .ui-state-default:before, .directory-advanced-search-content ul li .ui-widget-header .ui-state-default:before,.dr-filters ul li .ui-widget-header .ui-state-default:before/*RangeSlider*/,.more-photo,.right-info span.category-list,.directory-advanced-search-content form ul li .form-submit,.tablev2 thead,.cs-blogslide .owl-prev:hover,.cs-blogslide .owl-next:hover,
.user-signup form input[type='submit'],.page-not-found .cs-search-area input.btn,.cs-blog-crousel .post-options li time,.register-page input[type='button'],
.filter-nav ul li.our-agents a,#scroll-nav li,.main-filter.slide-loader .filter-nav:before,.donate-btn,.password_protected input[type='submit'],.password_protected .protected-icon a,
.cs_directory_categories ul > li:hover > span, .cs-paid-ad,.page-no-search .cs-search-area input.btn,.listNav .ln-letters a.ln-selected, .woocommerce-page.woocommerce ul.products li.product a.button.add_to_cart_button,.woocommerce ul.products li.product a.button.add_to_cart_button,.cs-search-v2 .directory-advanced-search-content .dir-search-fields,
.cs-search-v2 .directory-advanced-search-content,.service-btn,.widget_newsletter input[type='submit'],.cs-mailchimp input[type="submit"],.streetView:hover, .mapView:hover,
.directory-gallery ul li figure i:hover,.cs_gallery .modal-body button.close, .list-attachment-wrap .cs_attachments:hover .cs-attachment-list{
    background-color:<?php echo cs_allow_special_char($cs_theme_color); ?> !important;
}
/*!
* Theme Border Color */
.cs_counter.modren .cs-numcount,
/* ShopButton */.woocommerce ul.products li.product a.add_to_cart_button, .woocommerce-page ul.products li.product a.add_to_cart_button,
.woocommerce #content input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page #content input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button /* ShopButton */,.read-more,.cs-filter-menu li a:hover,.cs-filter-menu li a.active,.cs_assigment_tabs ul li:hover a,.cs_assigment_tabs ul li.active a,.post-thumb .thumb-options li a,.cs-filter-menu li.active a,.go-home,
.thumblist li a.comment-reply-link {
    border-color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}
.events-minimal:hover,.widget.event-calendar .eventsCalendar-list-wrap,.cs-tabs.vertical .nav-tabs .active a:before {
    border-left-color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}
.cs-tabs .nav-tabs > .active > a:before {
    border-top-color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}

<?php
if((is_page() || is_single()) and ($page_header_style == 'breadcrumb_header' and $page_subheader_border_color <> '')){
    ?>
    .breadcrumb-sec {
        border-top: 1px solid <?php echo cs_allow_special_char($page_subheader_border_color); ?>;
        border-bottom: 1px solid <?php echo cs_allow_special_char($page_subheader_border_color); ?>;
    }
    <?php
}
else{
    if($sub_header_border_color <> ''){
    ?>
        .breadcrumb-sec {
            border-top: 1px solid <?php echo cs_allow_special_char($sub_header_border_color); ?>;
            border-bottom: 1px solid <?php echo cs_allow_special_char($sub_header_border_color); ?>;
        }
    <?php
    }
}

if((is_page() || is_single()) and ($page_header_style == 'no-header' and $page_header_border_colr <> '')){
    ?>
    #main-header {
        border-bottom: 1px solid <?php echo cs_allow_special_char($page_header_border_colr); ?>;
    }
    <?php
}
else{
    if(isset($cs_theme_options['cs_default_header']) and $cs_theme_options['cs_default_header'] == 'No sub Header'){
        if($main_header_border_color <> ''){
        ?>
            #main-header {
                border-bottom: 1px solid <?php echo cs_allow_special_char($main_header_border_color); ?>;
            }
        <?php
        }
    }
}
?>

</style>
<?php } 
}


/** 
 * @Set Header color Css
 *
 *
 */
if ( ! function_exists( 'cs_header_color' ) ) {
    function cs_header_color(){
        global $cs_theme_options;
        
        $cs_header_bgcolor    = (isset($cs_theme_options['cs_header_bgcolor']) and $cs_theme_options['cs_header_bgcolor']<>'') ? $cs_theme_options['cs_header_bgcolor']: '';
        
        $cs_nav_bgcolor =  (isset($cs_theme_options['cs_nav_bgcolor']) and $cs_theme_options['cs_nav_bgcolor']<>'') ? $cs_theme_options['cs_nav_bgcolor']: '';
        
        $cs_menu_color = (isset($cs_theme_options['cs_menu_color']) and $cs_theme_options['cs_menu_color']<>'') ? $cs_theme_options['cs_menu_color']:'';
        
        $cs_menu_active_color = (isset($cs_theme_options['cs_menu_active_color']) and $cs_theme_options['cs_menu_active_color']<>'') ? $cs_theme_options['cs_menu_active_color']: '';
        
        $cs_submenu_bgcolor = (isset($cs_theme_options['cs_submenu_bgcolor']) and $cs_theme_options['cs_submenu_bgcolor']<>'' ) ? $cs_theme_options['cs_submenu_bgcolor']: '';
        
        $cs_submenu_color = (isset($cs_theme_options['cs_submenu_color']) and $cs_theme_options['cs_submenu_color']<>'') ? $cs_theme_options['cs_submenu_color']: '';
        
        $cs_submenu_hover_color = (isset($cs_theme_options['cs_submenu_hover_color']) and $cs_theme_options['cs_submenu_hover_color']<>'') ? $cs_theme_options['cs_submenu_hover_color']: '';
        
        $cs_topstrip_bgcolor = (isset($cs_theme_options['cs_topstrip_bgcolor']) and $cs_theme_options['cs_topstrip_bgcolor']<>'') ? $cs_theme_options['cs_topstrip_bgcolor']: '';
        
        $cs_topstrip_text_color = (isset($cs_theme_options['cs_topstrip_text_color']) and $cs_theme_options['cs_topstrip_text_color']<>'') ? $cs_theme_options['cs_topstrip_text_color']: '';
        
        $cs_topstrip_link_color = (isset($cs_theme_options['cs_topstrip_link_color']) and $cs_theme_options['cs_topstrip_link_color']<>'') ? $cs_theme_options['cs_topstrip_link_color']: '';
        
        $cs_menu_activ_bg = (isset($cs_theme_options['cs_theme_color'])) ? $cs_theme_options['cs_theme_color']: '';
        
        /* logo margins*/
        $cs_logo_margintb = (isset($cs_theme_options['cs_logo_margintb']) and  $cs_theme_options['cs_logo_margintb'] <> '') ? $cs_theme_options['cs_logo_margintb']: '0';
        $cs_logo_marginlr = (isset($cs_theme_options['cs_logo_marginlr']) and  $cs_theme_options['cs_logo_marginlr'] <> '') ? $cs_theme_options['cs_logo_marginlr']: '0';

        /* font family */
        $cs_content_font = (isset($cs_theme_options['cs_content_font'])) ? $cs_theme_options['cs_content_font']: '';
        $cs_content_font_att = (isset($cs_theme_options['cs_content_font_att'])) ? $cs_theme_options['cs_content_font_att']: '';
        
        $cs_mainmenu_font = (isset($cs_theme_options['cs_mainmenu_font'])) ? $cs_theme_options['cs_mainmenu_font']: '';
        $cs_mainmenu_font_att = (isset($cs_theme_options['cs_mainmenu_font_att'])) ? $cs_theme_options['cs_mainmenu_font_att']: '';
        
        $cs_heading_font = (isset($cs_theme_options['cs_heading_font'])) ? $cs_theme_options['cs_heading_font']: '';
        $cs_heading_font_att = (isset($cs_theme_options['cs_heading_font_att'])) ? $cs_theme_options['cs_heading_font_att']: '';
        
        $cs_widget_heading_font = (isset($cs_theme_options['cs_widget_heading_font'])) ? $cs_theme_options['cs_widget_heading_font']: '';
        $cs_widget_heading_font_att = (isset($cs_theme_options['cs_widget_heading_font_att'])) ? $cs_theme_options['cs_widget_heading_font_att']: '';
        
        // setting content fonts
        $cs_content_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_content_font_att);
        
        $cs_content_font_atts = cs_get_font_att_array($cs_content_fonts);
        
        // setting main menu fonts
        $cs_mainmenu_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_mainmenu_font_att);
        
        $cs_mainmenu_font_atts = cs_get_font_att_array($cs_mainmenu_fonts);
        
        // setting heading fonts
        $cs_heading_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_heading_font_att);
        
        $cs_heading_font_atts = cs_get_font_att_array($cs_heading_fonts);
        
        // setting widget heading fonts
        $cs_widget_heading_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_widget_heading_font_att);
        
        $cs_widget_heading_font_atts = cs_get_font_att_array($cs_widget_heading_fonts);
         
        /* font size */
        $cs_content_size = (isset($cs_theme_options['cs_content_size'])) ? $cs_theme_options['cs_content_size']: '';
        $cs_mainmenu_size = (isset($cs_theme_options['cs_mainmenu_size'])) ? $cs_theme_options['cs_mainmenu_size']: '';
        $cs_heading_1_size = (isset($cs_theme_options['cs_heading_1_size'])) ? $cs_theme_options['cs_heading_1_size']: '';
        $cs_heading_2_size = (isset($cs_theme_options['cs_heading_2_size'])) ? $cs_theme_options['cs_heading_2_size']: '';
        $cs_heading_3_size = (isset($cs_theme_options['cs_heading_3_size'])) ? $cs_theme_options['cs_heading_3_size']: '';
        $cs_heading_4_size = (isset($cs_theme_options['cs_heading_4_size'])) ? $cs_theme_options['cs_heading_4_size']: '';
        $cs_heading_5_size = (isset($cs_theme_options['cs_heading_5_size'])) ? $cs_theme_options['cs_heading_5_size']: '';
        $cs_heading_6_size = (isset($cs_theme_options['cs_heading_6_size'])) ? $cs_theme_options['cs_heading_6_size']: '';
        
        /* font Color */
        $cs_heading_h1_color = (isset($cs_theme_options['cs_heading_h1_color']) and $cs_theme_options['cs_heading_h1_color'] <> '') ? $cs_theme_options['cs_heading_h1_color']: '';
        $cs_heading_h2_color = (isset($cs_theme_options['cs_heading_h2_color']) and $cs_theme_options['cs_heading_h2_color'] <> '') ? $cs_theme_options['cs_heading_h2_color']: '';
        $cs_heading_h3_color = (isset($cs_theme_options['cs_heading_h3_color']) and $cs_theme_options['cs_heading_h3_color'] <> '') ? $cs_theme_options['cs_heading_h3_color']: '';
        $cs_heading_h4_color = (isset($cs_theme_options['cs_heading_h4_color']) and $cs_theme_options['cs_heading_h4_color'] <> '') ? $cs_theme_options['cs_heading_h4_color']:'';
        $cs_heading_h5_color = (isset($cs_theme_options['cs_heading_h5_color']) and $cs_theme_options['cs_heading_h5_color'] <> '') ? $cs_theme_options['cs_heading_h5_color']: '';
        $cs_heading_h6_color = (isset($cs_theme_options['cs_heading_h6_color']) and $cs_theme_options['cs_heading_h6_color'] <> '') ? $cs_theme_options['cs_heading_h6_color']: '';
        $cs_text_color = $cs_theme_options['cs_text_color'];         
        
        $cs_widget_heading_size = (isset($cs_theme_options['cs_widget_heading_size'])) ? $cs_theme_options['cs_widget_heading_size']: '';
		$cs_section_heading_size = (isset($cs_theme_options['cs_section_heading_size'])) ? $cs_theme_options['cs_section_heading_size']: '';
        
        if(
            ( isset( $cs_theme_options['cs_custom_font_woff'] ) && $cs_theme_options['cs_custom_font_woff'] <> '' ) &&
            ( isset( $cs_theme_options['cs_custom_font_ttf'] ) && $cs_theme_options['cs_custom_font_ttf'] <> '' ) &&
            ( isset( $cs_theme_options['cs_custom_font_svg'] ) && $cs_theme_options['cs_custom_font_svg'] <> '' ) &&
            ( isset( $cs_theme_options['cs_custom_font_eot'] ) && $cs_theme_options['cs_custom_font_eot'] <> '' )
        ):
        
        $font_face_html = "
        @font-face {
            font-family: 'cs_custom_font';
            src: url('".$cs_theme_options['cs_custom_font_eot']."');
            src:
                url('".$cs_theme_options['cs_custom_font_eot']."?#iefix') format('eot'),
                url('".$cs_theme_options['cs_custom_font_woff']."') format('woff'),
                url('".$cs_theme_options['cs_custom_font_ttf']."') format('truetype'),
                url('".$cs_theme_options['cs_custom_font_svg']."#cs_custom_font') format('svg');
            font-weight: 400;
            font-style: normal;
        }";
        
        $custom_font = true; else: $custom_font = false; endif;
     ?>
        <style type="text/css">
		
            <?php 
                if($custom_font == true){
                    echo cs_allow_special_char($font_face_html);
                }
                else{
                    echo cs_get_font_family($cs_content_font, $cs_content_font_att);
                    echo cs_get_font_family($cs_mainmenu_font, $cs_mainmenu_font_att);
                    echo cs_get_font_family($cs_heading_font, $cs_heading_font_att);
                    echo cs_get_font_family($cs_widget_heading_font, $cs_widget_heading_font_att);
                }
            ?>
    body,.main-section p {
        <?php 
        if($custom_font == true){
            echo 'font-family: cs_custom_font;';
            echo 'font-size: '.$cs_content_size.';';
        }
        else{
            echo cs_font_font_print($cs_content_font_atts, $cs_content_size, $cs_content_font);
        }
        ?>
         color:<?php echo cs_allow_special_char($cs_text_color);?>;
    }
    header .logo{
        margin:<?php echo cs_allow_special_char($cs_logo_margintb);?>px <?php echo cs_allow_special_char($cs_logo_marginlr);?>px !important;
       }
    .nav li a,.navigation ul li{
        <?php 
        if($custom_font == true){
            echo 'font-family: cs_custom_font;';
            echo 'font-size: '.$cs_mainmenu_size.';';
        }
        else{
             echo cs_font_font_print($cs_mainmenu_font_atts, $cs_mainmenu_size, $cs_mainmenu_font, true);
        }
        ?>
    }
     h1{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font;';
        echo 'font-size: '.$cs_heading_1_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_1_size, $cs_heading_font, true);
    }
     
    ?>}
    h2{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font;';
        echo 'font-size: '.$cs_heading_2_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_2_size, $cs_heading_font, true);
    }
    
    ?>}
    h3{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font;';
        echo 'font-size: '.$cs_heading_3_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_3_size, $cs_heading_font, true);
    }
    
    ?>}
    h4{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font;';
        echo 'font-size: '.$cs_heading_4_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_4_size, $cs_heading_font, true);
    }
    
    ?>}
    h5{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font;';
        echo 'font-size: '.$cs_heading_5_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_5_size, $cs_heading_font, true);
    }
    
    ?>}
    h6{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font;';
        echo 'font-size: '.$cs_heading_6_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_6_size, $cs_heading_font, true);
    }
    
    ?>}
    
    .main-section h1, .main-section h1 a {color: <?php echo cs_allow_special_char($cs_heading_h1_color);?> !important;}
    .main-section h2, .main-section h2 a{color: <?php echo cs_allow_special_char($cs_heading_h2_color);?> !important;}
    .main-section h3, .main-section h3 a{color: <?php echo cs_allow_special_char($cs_heading_h3_color);?> !important;}
    .main-section h4, .main-section h4 a{color: <?php echo cs_allow_special_char($cs_heading_h4_color);?> !important;}
    .main-section h5, .main-section h5 a{color: <?php echo cs_allow_special_char($cs_heading_h5_color);?> !important;}
    .main-section h6, .main-section h6 a{color: <?php echo cs_allow_special_char($cs_heading_h6_color);?> !important;}
    .widget .widget-section-title h2{
        <?php
        if($custom_font == true){
            echo 'font-family: cs_custom_font;';
            echo 'font-size: '.$cs_widget_heading_size.';';
        }
        else{
            echo cs_font_font_print($cs_widget_heading_font_atts, $cs_widget_heading_size, $cs_widget_heading_font, true);
        }
        ?>
    }
	  .cs-section-title h2{
        <?php
             echo 'font-size:'.$cs_section_heading_size.'px !important;';
          ?>
    }
	.top-bar,#lang_sel ul ul {background-color:<?php echo cs_allow_special_char($cs_topstrip_bgcolor);?>;}
	#lang_sel ul ul:before { border-bottom-color: <?php echo cs_allow_special_char($cs_topstrip_bgcolor);?>; }
	.top-bar p{color:<?php echo cs_allow_special_char($cs_topstrip_text_color);?> !important;}
	.top-bar a,.top-bar i{color:<?php echo cs_allow_special_char($cs_topstrip_link_color);?> !important;}
	.logo-section,.main-head{background:<?php echo cs_allow_special_char($cs_header_bgcolor);?> !important;}
	.main-navbar,#main-header .btn-style1,.wrapper:before {background:<?php echo cs_allow_special_char($cs_nav_bgcolor);?> !important;}
	.navigation ul > li > a {color:<?php echo cs_allow_special_char($cs_menu_color);?> !important;}
	.sub-dropdown { background-color:<?php echo cs_allow_special_char($cs_submenu_bgcolor);?> !important;}
	.navigation > ul ul li > a {color:<?php echo cs_allow_special_char($cs_submenu_color);?> !important;}
	.navigation > ul ul li:hover > a {color:<?php echo cs_allow_special_char($cs_submenu_hover_color);?>;color:<?php echo cs_allow_special_char($cs_submenu_hover_color);?> !important;}
	.navigation > ul > li:hover > a {color:<?php echo cs_allow_special_char($cs_menu_active_color);?> !important;}
	.navigation > ul > li.parentIcon:hover > a:before { background-color:<?php echo cs_allow_special_char($cs_menu_active_color);?> !important; }
	.cs-user,.cs-user-login { border-color:<?php echo cs_allow_special_char($cs_menu_active_color);?> !important; }
    {
        box-shadow: 0 4px 0 <?php echo cs_allow_special_char($cs_topstrip_bgcolor); ?> inset !important;
    }
    .header_2 .nav > li:hover > a,.header_2 .nav > li.current-menu-ancestor > a {
       
    }
    </style>
<?php
    }
}



/** 
 * @Set Footer colors
 *
 *
 */
if ( ! function_exists( 'cs_footer_color' ) ) {
    function cs_footer_color(){
        global $cs_theme_options;
        $cs_footerbg_color = (isset($cs_theme_options['cs_footerbg_color']) and $cs_theme_options['cs_footerbg_color'] <> '') ? $cs_theme_options['cs_footerbg_color']: '';
        
        $cs_footerbg_image = (isset($cs_theme_options['cs_footer_background_image']) and $cs_theme_options['cs_footer_background_image'] <> '') ? $cs_theme_options['cs_footer_background_image']: '';
            
        $cs_title_color = (isset($cs_theme_options['cs_title_color']) and $cs_theme_options['cs_title_color'] <> '') ? $cs_theme_options['cs_title_color']: '';
        
        $cs_footer_text_color = (isset($cs_theme_options['cs_footer_text_color']) and $cs_theme_options['cs_footer_text_color'] <> '') ? $cs_theme_options['cs_footer_text_color']: '';
        
        $cs_link_color = (isset($cs_theme_options['cs_link_color']) and $cs_theme_options['cs_link_color'] <> '') ? $cs_theme_options['cs_link_color']: '';
        
        $cs_sub_footerbg_color = (isset($cs_theme_options['cs_sub_footerbg_color']) and $cs_theme_options['cs_sub_footerbg_color'] <> '') ? $cs_theme_options['cs_sub_footerbg_color']: '';
        
        $cs_copyright_text_color = (isset($cs_theme_options['cs_copyright_text_color']) and $cs_theme_options['cs_copyright_text_color'] <> '') ? $cs_theme_options['cs_copyright_text_color']: '';
?>
<style type="text/css">
        footer#footer-sec, footer.group:before {
            background-color:<?php echo cs_allow_special_char($cs_sub_footerbg_color); ?> !important;
        }
        #footer-sec {
            background:url(<?php echo esc_url($cs_footerbg_image); ?>) <?php echo cs_allow_special_char($cs_footerbg_color); ?> no-repeat bottom center !important;
        }
        .footer-content {
            background-color:<?php echo cs_allow_special_char($cs_footerbg_color); ?> !important;
        }
        #copyright p {
            color:<?php echo cs_allow_special_char($cs_copyright_text_color); ?> !important;
        }
        footer a,footer .widget-form ul li input[type='submit'],footer.group .tagcloud a,footer.group .widget ul li a {
            color:<?php echo cs_allow_special_char($cs_link_color); ?> !important;
        }
        footer#footer-sec .widget h2, footer#footer-sec .widget h5,footer.group h2,footer#footer-sec h3,footer#footer-sec h4,footer#footer-sec h5,footer#footer-sec h6 {
            color:<?php echo cs_allow_special_char($cs_title_color); ?> !important;
        }
      #newslatter-sec,#newslatter-sec span,footer#footer-sec .widget ul li,footer#footer-sec .widget p, footer#footer-sec .widget_calendar tr td,footer.group,footer#footer-sec p,footer .widget_latest_post .post-options li,footer#footer-sec .widget i,.widget-form ul li i {
            color:<?php echo cs_allow_special_char($cs_footer_text_color); ?> !important;
        }
    </style>
<?php 
}
}