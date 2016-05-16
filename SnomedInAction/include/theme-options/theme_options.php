<?php
// Theme option function
if ( ! function_exists( 'cs_options_page' ) ) {
    function cs_options_page(){
        global $cs_theme_options, $cs_options;
        $cs_theme_options=get_option('cs_theme_options');
    ?>

<div class="theme-wrap fullwidth">
  <div class="inner">
    <div class="outerwrapp-layer">
      <div class="loading_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
        <?php _e('Saving changes...','dir');?>
      </div>
      <div class="form-msg"> <i class="icon-check-circle-o"></i>
        <div class="innermsg"></div>
      </div>
    </div>
    <div class="row">
      <form id="frm" method="post">
        <?php 
            $theme_options_fields = new theme_options_fields();
            $return = $theme_options_fields->cs_fields($cs_options);
        ?>
        <div class="col1">
          <nav class="admin-navigtion">
            <div class="logo"> <a href="#" class="logo1"><img src="<?php echo get_template_directory_uri()?>/include/assets/images/logo-themeoption.png" /></a> <a href="#" class="nav-button"><i class="icon-align-justify"></i></a> </div>
            <ul>
              <?php echo force_balance_tags($return[1],true); ?>
            </ul>
          </nav>
        </div>
        <div class="col2">
          <?php  echo force_balance_tags($return[0],true); /* Settings */ ?>
        </div>
        <div class="clear"></div>
        <div class="footer">
          <input type="button" id="submit_btn" name="submit_btn" class="bottom_btn_save" value="Save All Settings" onclick="javascript:theme_option_save('<?php echo esc_js(admin_url('admin-ajax.php'))?>', '<?php echo esc_js(get_template_directory_uri());?>');" />
          <input type="hidden" name="action" value="theme_option_save"  />
          <input class="bottom_btn_reset" name="reset" type="button" value="Reset All Options"  
                            onclick="javascript:cs_rest_all_options('<?php echo esc_js(admin_url('admin-ajax.php'))?>', '<?php echo esc_js(get_template_directory_uri())?>');" />
        </div>
      </form>
    </div>
  </div>
</div>
<div class="clear"></div>
<!--wrap--> 
<script type="text/javascript">
            // Sub Menus Show/hide
            jQuery(document).ready(function($) {
                jQuery(".sub-menu").parent("li").addClass("parentIcon");
                $("a.nav-button").click(function() {
                    $(".admin-navigtion").toggleClass("navigation-small");
                });
                
                $("a.nav-button").click(function() {
                    $(".inner").toggleClass("shortnav");
                });
                
                $(".admin-navigtion > ul > li > a").click(function() {
                    var a = $(this).next('ul')
                    $(".admin-navigtion > ul > li > a").not($(this)).removeClass("changeicon")
                    $(".admin-navigtion > ul > li ul").not(a) .slideUp();
                    $(this).next('.sub-menu').slideToggle();
                    $(this).toggleClass('changeicon');
                });
            });
            
            function show_hide(id){
                var link = id.replace('#', '');
                jQuery('.horizontal_tab').fadeOut(0);
                jQuery('#'+link).fadeIn(400);
            }
            
            function toggleDiv(id) { 
                jQuery('.col2').children().hide();
                jQuery(id).show();
                location.hash = id+"-show";
                var link = id.replace('#', '');
                jQuery('.categoryitems li').removeClass('active');
                jQuery(".menuheader.expandable") .removeClass('openheader');
                jQuery(".categoryitems").hide();
                jQuery("."+link).addClass('active');
                jQuery("."+link) .parent("ul").show().prev().addClass("openheader");
            }
            jQuery(document).ready(function() {
                jQuery(".categoryitems").hide();
                jQuery(".categoryitems:first").show();
                jQuery(".menuheader:first").addClass("openheader");
                jQuery(".menuheader").live('click', function(event) {
                    if (jQuery(this).hasClass('openheader')){
                        jQuery(".menuheader").removeClass("openheader");
                        jQuery(this).next().slideUp(200);
                        return false;
                    }
                    jQuery(".menuheader").removeClass("openheader");
                    jQuery(this).addClass("openheader");
                    jQuery(".categoryitems").slideUp(200);
                    jQuery(this).next().slideDown(200); 
                    return false;
                });
                
                var hash = window.location.hash.substring(1);
                var id = hash.split("-show")[0];
                if (id){
                    jQuery('.col2').children().hide();
                    jQuery("#"+id).show();
                    jQuery('.categoryitems li').removeClass('active');
                    jQuery(".menuheader.expandable") .removeClass('openheader');
                    jQuery(".categoryitems").hide();
                    jQuery("."+id).addClass('active');
                    jQuery("."+id) .parent("ul").slideDown(300).prev().addClass("openheader");
                } 
            });
            jQuery(function($) {
                $( "#cs_launch_date" ).datepicker({
                    defaultDate: "+1w",
                    dateFormat: "dd/mm/yy",
                    changeMonth: true,
                    numberOfMonths: 1,
                    onSelect: function( selectedDate ) {
                        $( "#cs_launch_date" ).datepicker();
                    }
                });
            });
        </script>
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri())?>/include/assets/css/jquery_ui_datepicker.css">
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri())?>/include/assets/css/jquery_ui_datepicker_theme.css">
<?php
    }
}

// Background Count function
if ( ! function_exists( 'cs_bgcount' ) ) {
     function cs_bgcount($name,$count) {
        for($i=0; $i<=$count; $i++){
            $pattern['option'.$i] = $name.$i;
        }
        return $pattern;
     }
}
add_action('init','cs_theme_option');
if ( ! function_exists( 'cs_theme_option' ) ) {
    function cs_theme_option(){
        global $cs_options,$cs_header_colors,$cs_theme_options;
        $cs_theme_options	= get_option('cs_theme_options');
        $on_off_option =  array("show" => "on","hide"=>"off"); 
        $navigation_style = array("left" => "left","center"=>"center","right"=>"right");
        $google_fonts =array('google_font_family_name'=>array('','',''),'google_font_family_url'=>array('','',''));
        $social_network =array('social_net_icon_path'=>array('','','','',''),'social_net_awesome'=>array('icon-facebook7','icon-twitter2','icon-skype','icon-linkedin4','icon-tumblr5'),'social_net_url'=>array('https://www.facebook.com/','https://www.twitter.com/','https://plus.skype.com/','https://www.linkedin.com/','https://www.tumblr.com/'),'social_net_tooltip'=>array('Facebook','Twitter','Skype','Linkedin','Tumbler'),'social_font_awesome_color'=>array('#cccccc','#cccccc','#cccccc','#cccccc','#cccccc'));
        
        $banner_fields =array('banner_field_title'=>array('Banner 1'),'banner_field_style'=>array('top_banner'),'banner_field_type'=>array('code'),'banner_field_image'=>array(''),'banner_field_url'=>array('#'),'banner_field_url_target'=>array('_self'),'banner_adsense_code'=>array(''),'banner_field_code_no'=>array('0'));
		
		$cs_free_package_switch	= 'on';
		$cs_packages_options	= array (
										  1419239913 => 
										  array (
											'package_id'		 => '1419239913',
											'package_title' 	 => 'Basic',
											'package_price' 	 => '10',
											'package_duration'   => '15',
										  ),
										  1419240147 => 
										  array (
											'package_id' 		=> '1419240147',
											'package_title' 	=> 'Standard',
											'package_price' 	=> '65',
											'package_duration'  => '30',
										  ),
										  1420186239 => 
										  array (
											'package_id' 		=> '1420186239',
											'package_title' 	=> 'Premium',
											'package_price' 	=> '90',
											'package_duration'  => '60',
										  ),
										);
        $sidebar = array(	
			'sidebar' => array(
				'blogs_sidebar'		=>	'Blogs Sidebar',
				'contact'			=>	'Contact',
				'home_directory'	=>	'Home Directory',
				'agents'			=>	'Agents',
 				'faqs' 				=> 	'faqs',
				'features'	 		=> 	'Features',
				'typography'		=> 	'Typography',
				'common_elements'	=> 	'Common Elements',
				'media_elements'	=> 	'Media Elements',
				'shop'				=> 	'Shop',
				)
			);
        $menus_locations = array_flip(get_nav_menu_locations());
        $breadcrumb_option = array("option1" => "option1","option2"=>"option2","option3"=>"option3");
        $deafult_sub_header = array('breadcrumbs_sub_header'=>'Breadcrumbs Sub Header','slider'=>'Revolution Slider','no_header'=>'No sub Header');
        $padding_sub_header = array('Default'=>'default','Custom'=>'custom');
        //Menus List
        $menu_option = get_registered_nav_menus();
        foreach($menu_option as $key=>$menu){
            $menu_location = $key;
            $menu_locations = get_nav_menu_locations();
            $menu_object = (isset($menu_locations[$menu_location]) ? wp_get_nav_menu_object($menu_locations[$menu_location]) : null);
            $menu_name[] = (isset($menu_object->name) ? $menu_object->name : '');
        }
        //Mailchimp List
        $mail_chimp_list[]='';
        if(isset($cs_theme_options['cs_mailchimp_key'])){
            $mailchimp_option = $cs_theme_options['cs_mailchimp_key'];
            if($mailchimp_option <> ''){
                $mc_list = cs_mailchimp_list($mailchimp_option);
                if(is_array($mc_list) && isset($mc_list['data'])){
                    foreach($mc_list['data'] as $list){
                        $mail_chimp_list[$list['id']]=$list['name'];
                    }
                }
            }
        }    
        
        //Map Search Pages
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-ad-search.php',
            'hierarchical' => 0
        ));
        
        $map_options    = array();
        $map_options[]    = 'Default';
        foreach($pages as $page){
            $map_options[$page->ID]    = $page->post_title;
        }
        
        //google fonts array
        $g_fonts = cs_googlefont_list(); 
         $g_fonts_atts = cs_get_google_font_attribute();
        
        global $cs_theme_options;
        if (isset($cs_theme_options) and $cs_theme_options <> '') {
            if(isset($cs_theme_options['sidebar']) and count($cs_theme_options['sidebar'])>0){
                $cs_sidebar =array('sidebar'=>$cs_theme_options['sidebar']);
            }elseif(!isset($cs_theme_options['sidebar'])){
                $cs_sidebar = array('sidebar'=>array());
            }
        }else{
            $cs_sidebar=$sidebar;
        }
         // Set the Options Array
        $cs_options = array();
        $cs_header_colors= cs_header_setting();
        /* general setting options */
        $cs_options[] = array(    
            "name"            =>__("General",'dir'),
            "fontawesome"     => 'icon-cog3',
            "type"             => "heading",
            "options"         => array(
                'tab-global-setting'    =>__('global','dir'),
                'tab-header-options'    =>__('Header','dir'),
                'tab-sub-header-options'=>__('Sub Header','dir'),
                'tab-footer-options'    =>__('Footer','dir'),
                'tab-social-setting'    =>__('social icons','dir'),
                'tab-social-network'    =>__('social sharing','dir'),
                'banner-fields'			=>__('Ads Unit Settings','dir'),
                'tab-custom-code'		=>__('custom code','dir'),
            ) 
        );
          $cs_options[] = array( 
            "name"             =>__("color",'dir'),
            "fontawesome"    => 'icon-magic',
            "hint_text"        => "",
            "type"             => "heading",
            "options"         => array(
                'tab-general-color'    =>__('general','dir'),
                'tab-header-color'    =>__('Header','dir'),
                'tab-footer-color'    =>__('Footer','dir'),
                'tab-heading-color'    =>__('headings','dir'),
            ) 
        );
        $cs_options[] = array( 
                    "name" =>__("typography / fonts",'dir'),
                    "fontawesome" => 'icon-font',
                    "desc" => "",
                    "hint_text" => "",
                    "type" => "heading",
                    "options" => array(
                        'tab-custom-font'=>__('Custom Font','dir'),
                        'tab-font-family'=>__('font family','dir'),
                        'tab-font-size'=>__('font size','dir'),
                    ) 
                );                    
    $cs_options[] = array(    
                    "name" =>__("sidebar",'dir'),
                    "fontawesome" => 'icon-columns',
                    "id" => "tab-sidebar",
                    "std" => "",
                    "type" => "main-heading",
                    "options" => ''
                );
    $cs_options[] = array(    
                    "name" =>__("SEO",'dir'),
                    "fontawesome" => 'icon-globe6',
                    "id" => "tab-seo",
                    "std" => "",
                    "type" => "main-heading",
                    "options" => ""
                );    
    $cs_options[] = array( 
                    "name" =>__("global",'dir'),
                    "id" => "tab-global-setting",
                    "type" => "sub-heading"
                );
    $cs_options[] = array( 
                    "name" =>__("Layout",'dir'),
                    "desc" => "",
                    "hint_text" =>__("Layout type",'dir'),
                    "id" =>   "cs_layout",
                    "std" =>  "full_width",
                    "options" => array(
                        "boxed" => "boxed",
                        "full_width"=>"full width"
                    ),
                    "type" => "layout",
                );        
                
    $cs_options[] = array( 
                    "name" => "",
                    "id" =>   "cs_horizontal_tab",
                    "class" =>  "horizontal_tab",
                    "type" => "horizontal_tab",
                    "std" => "",
                    "options" => array('Background'=>'background_tab','Pattern'=>'pattern_tab','Custom Image'=>'custom_image_tab')
                );

    $cs_options[] = array( 
                    "name" =>__("Background image",'dir'),
                    "desc" => "",
                    "hint_text" =>__("Choose from Predefined Background images.",'dir'),
                    "id" =>   "cs_bg_image",
                    "class" =>  "cs_background_",
                    "path" => "background",
                    "tab"=>"background_tab",
                    "std" =>  "bg1",
                    "type" => "layout_body",
                    "display"=>"block",
                    "options" => cs_bgcount('bg','10')
                );
                
    $cs_options[] = array( "name" =>__("Background pattern",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Choose from Predefined Pattern images.",'dir'),
                        "id" =>   "cs_bg_image",
                        "class" =>  "cs_background_",
                        "path" => "patterns",
                        "tab"=>"pattern_tab",
                        "std" =>  "bg1",
                        "type" => "layout_body",
                        "display"=>"none",
                        "options" => cs_bgcount('pattern','27')                     
                    );
    $cs_options[] = array( 
                    "name" =>__("Custom image",'dir'),
                    "desc" => "",
                    "hint_text" =>__("This option can be used only with Boxed Layout.",'dir'),
                    "id" =>   "cs_custom_bgimage",
                    "std" =>  "",
                    "tab"=>"custom_image_tab",
                    "display"=>"none",
                    "type" => "upload logo"
                );
    $cs_options[] = array( "name" =>__("Background image position",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Choose image position for body background",'dir'),
                        "id" =>   "cs_bgimage_position",
                        "std" =>  "Center Repeat",
                        "type" => "select",
                        "options" =>array(
                            "option1" => "no-repeat center top",
                            "option2"=>"repeat center top",
                            "option3"=>"no-repeat center",
                            "option4"=>"Repeat Center",
                            "option5"=>"no-repeat left top",
                            "option6"=>"repeat left top",
                            "option7"=>"no-repeat fixed center",
                            "option8"=>"no-repeat fixed center / cover"
                        )
                    );    
    $cs_options[] = array( "name" =>__("Custom favicon",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Custom favicon for your site.",'dir'),
                        "id" =>   "cs_custom_favicon",
                        "std" =>  get_template_directory_uri()."/assets/images/favicon.png",
                        "type" => "upload logo"
                    );

    $cs_options[] = array( "name" =>__("Smooth Scroll",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Lightweight Script for Page Scrolling animation",'dir'),
                        "id" =>   "cs_smooth_scroll",
                        "std" => "off",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );
    
    $cs_options[] = array( "name" =>__("RTL",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Turn RTL ON/OFF here for Right to Left languages like Arabic etc.",'dir'),
                        "id" =>   "cs_style_rtl",
                        "std" => "off",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );
                    
    $cs_options[] = array( "name" =>__("Responsive",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set responsive design layout for mobile devices ON/OFF here",'dir'),
                        "id" =>   "cs_responsive",
                        "std" => "off",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );

    // end global setting tab                    
    // Header top strip option end
    // Header options start
    $cs_options[] = array( "name" =>__("header",'dir'),
                        "id" => "tab-header-options",
                        "type" => "sub-heading"
                    );
    $cs_options[] = array( "name" =>__("Attention for Header Position!",'dir'),
                        "id" => "header_postion_attention",
                        "std"=>" <strong>Relative Position:</strong> The element is positioned relative to its normal position. The header is positioned above the content. <br> <strong>Absolute Position:</strong> The element is positioned relative to its first positioned. The header is positioned on the content.",
                        "type" => "announcement"
                    );
                    
    $cs_options[] = array( "name" =>__("Logo",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Upload your custom logo in .png .jpg .gif formats only.",'dir'),
                        "id" =>   "cs_custom_logo",
                        "std" => get_template_directory_uri()."/assets/images/logo-directory.png",
                        "type" => "upload logo"
                    );
    $cs_options[] = array( "name" =>__("Logo Height",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set exact logo height otherwise logo will not display normally.",'dir'),
                        "id" => "cs_logo_height",
                        "min" => '0',
                        "max" => '100',
                        "std" => "41",
                        "type" => "range"
                    );                
    $cs_options[] = array( "name" =>__("logo width",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set exact logo width otherwise logo will not display normally.",'dir'),
                        "id" => "cs_logo_width",
                        "min" => '0',
                        "max" => '250',
                        "std" => "180",
                        "type" => "range"
                    );                
    
    $cs_options[] = array( "name" =>__("Logo margin top and bottom",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Logo spacing/margin from top and bottom.",'dir'),
                        "id" => "cs_logo_margintb",
                        "min" => '0',
                        "max" => '200',
                        "std" => "0",
                        "type" => "range"
                    );    
    $cs_options[] = array( "name" =>__("Logo margin left and right",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Logo spacing/margin from left and right.",'dir'),
                        "id" => "cs_logo_marginlr",
                        "min" => '0',
                        "max" => '200',
                        "std" => "0",
                        "type" => "range"
                    );                                        
     /* header element settings*/
     
    $cs_options[] = array( "name" =>__("Header Elements",'dir'),
                        "id" => "tab-header-options",
                        "std" => "Header Elements",
                        "type" => "section",
                        "options" => ""
                    );    
    $cs_options[] = array( "name" =>__("Login Options",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Membership must be enabled from Dashboard Settings > General > Membership to allow user Registration ",'dir'),
                        "id" =>   "cs_login_options",
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );
    $cs_options[] = array( "name" =>__("Login Button Position",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set the Login Button Position.",'dir'),
                        "id" =>   "cs_login_button_position",
                        "std" => "Header",
                        "type" => "select",
                        "options" => array('top_strip'=>'Top Strip','header'=>'Header')
                    );
    if( function_exists( 'is_woocommerce' ) ) {
    	$cs_options[] = array( 
			"name" =>__("Cart Count",'dir'),
			"desc" => "",
			"hint_text" =>__("Enable/Disable Woocommerce Cart Count",'dir'),
			"id" =>   "cs_woocommerce_switch",
			"std" => "off",
			"type" => "checkbox",
			"options" => $on_off_option
        );
    }
                    
    $cs_options[] = array( "name" =>__("WPML",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set WordPress Multi Language switcher ON/OFF in header",'dir'),
                        "id" =>   "cs_wpml_switch",
                        "std" => "on",
                        "type" => "wpml",
                        "options" => $on_off_option
                    );
                        
    $cs_options[] = array( "name" =>__("Sticky Header On/Off",'dir'),
                        "desc" => "",
                        "id" =>   "cs_sitcky_header_switch",
                        "hint_text" =>__("If you enable this option , header will be fixed on top of your browser window.",'dir'),
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );
    $cs_options[] = array( "name" =>__("Post Ad",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Enable/Disable Ad Creation",'dir'),
                        "id" =>   "cs_get_started",
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option);
						
	$cs_options[] = array( "name" =>__("Post Ad Title",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_post_ad_title",
                        "std" => "Post an Ad",
                        "type" => "text",
                    );
                        
    $cs_options[] = array( "name" =>__("Header Position Settings",'dir'),
                        "id" => "tab-header-options",
                        "std" => "Header Position Settings",
                        "type" => "section",
                        "options" => ""
                    );
    $cs_options[] = array( "name" =>__("Select Header Position",'dir'),
                    "desc" => "",
                    "hint_text" =>__("Select header position as Absolute OR Relative",'dir'),
                    "id" =>   "cs_header_position",
                    "std" => "relative",
                    "type" => "select",
                    "options" => array('absolute'=>'absolute','relative'=>'relative')
                );
    $cs_options[] = array( "name" =>__("Header Background",'dir'),
                    "desc" => "",
                    "hint_text" =>__("Header settings made here will be implemented on default pages.",'dir'),
                    "id" =>   "cs_headerbg_options",
                    "std" => "Default Header Background",
                    "type" => "default header background",
                    "options" => array('none'=>'None','cs_rev_slider'=>'Revolution Slider','cs_bg_image_color'=>'Bg Image / bg Color')
            );                
     $cs_options[] = array( "name" =>__("Revolution Slider",'dir'),
                        "desc" => "",
                        "hint_text" => "<p>Please select Revolution Slider if already included in package. Otherwise buy Sliders from <a href='http://codecanyon.net/' target='_blank'>Code canyon</a>. But its optional</p>",
                        "id" =>   "cs_headerbg_slider",
                        "std" => "",
                        "type" => "headerbg slider",
                        "options" => ''
                    );
    $cs_options[] = array( "name" =>__("Background Image",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Upload your custom background image in .png .jpg .gif formats only.",'dir'),
                        "id" =>   "cs_headerbg_image",
                        "std" =>  "",
                        "type" => "upload"
                    );
    $cs_options[] = array( "name" =>__("Background Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("set header background color.",'dir'),
                        "id" =>   "cs_headerbg_color",
                        "std" => "",
                        "type" => "color"
                    );
    $cs_options[] = array( "name" =>__("Header Top Strip",'dir'),
                        "id" => "tab-header-options",
                        "std" => "Header Top Strip",
                        "type" => "section",
                        "options" => ""
                    );    
                    
    $cs_options[] = array( "name" =>__("Header Strip",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Enable/Disable header top strip.",'dir'),
                        "id" =>   "cs_header_top_strip",
                        "std" => "off",
                        "type" => "checkbox",
                        "options" => $on_off_option);                
    
    $cs_options[] = array( "name" =>__("Social Icon",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Enable/Disable social icon. Add icons from General > social icon",'dir'),
                        "id" =>   "cs_socail_icon_switch",
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option);                

    $cs_options[] = array( "name" =>__("Top Menu",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Menu location can be set from Appearance > Menu > Manage Menu Locations.",'dir'),
                        "id" =>   "cs_top_menu_switch",
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option);                        
    $cs_options[] = array( "name" =>__( "Short Text",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Write phone no, email or address for Header top strip",'dir'),
                        "id" =>   "cs_header_strip_tagline_text",
                        "std" => 'Call Us: 000-111-222-33 | <i class="fa fa-envelope-o"></i><a href="mailto: future@university.com"> future@university.com</a>',
                        "type" => "textarea");
    
    /* sub header element settings*/
    $cs_options[] = array( "name" =>__("sub header",'dir'),
                        "id" => "tab-sub-header-options",
                        "type" => "sub-heading"
                    );
    $cs_options[] = array( "name" =>__("Announcement!",'dir'),
                        "id" => "sub_header_announcement",
                        "std"=>"Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.",
                        "type" => "announcement"
                    );
                    
    $cs_options[] = array( "name" =>__("Default",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Sub Header settings made here will be implemented on all pages.",'dir'),
                        "id" =>   "cs_default_header",
                        "std" => "Breadcrumbs Sub Header",
                        "type" => "default header",
                        "options" => $deafult_sub_header
                    );
    $cs_options[] = array( "name" =>__("Content Padding",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Choose default or custom padding for sub header content.",'dir'),
                        "id" =>   "subheader_padding_switch",
                        "std" => "Default",
                        "type" => "default padding",
                        "options" => $padding_sub_header
                    );
                    
    $cs_options[] = array( "name" =>__("Header Border Color",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_header_border_color",
                        "std" => "",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" =>__( "Revolution Slider",'dir'),
                        "desc" => "",
                        "hint_text" => "<p>Please select Revolution Slider if already included in package. Otherwise buy Sliders from <a href='http://codecanyon.net/' target='_blank'>Code canyon</a>. But its optional</p>",
                        "id" =>   "cs_custom_slider",
                        "std" => "",
                        "type" => "slider code",
                        "options" => ''
                    );
    $cs_options[] = array( "name" =>__("Padding Top",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set custom padding for sub header content top area.",'dir'),
                        "id" => "cs_sh_paddingtop",
                        "min" => '0',
                        "max" => '200',
                        "std" => "45",
                        "type" => "range"
                    );
    $cs_options[] = array( "name" =>__("Padding Bottom",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set custom padding for sub header content bottom area.",'dir'),
                        "id" => "cs_sh_paddingbottom",
                        "min" => '0',
                        "max" => '200',
                        "std" => "45",
                        "type" => "range"
                    );                    
    $cs_options[] = array( "name" =>__("Content Text Align",'dir'),
                        "desc" => "",
                        "hint_text" =>__("select the text Alignment for sub header content.",'dir'),
                        "id" =>   "cs_title_align",
                        "std" => "left",
                        "type" => "select",
                        "options" => $navigation_style
                    );
    $cs_options[] = array( "name" =>__("Page Title",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set page title ON/OFF in sub header",'dir'),
                        "id" => "cs_title_switch",
                        "std" => "on",
                        "type" => "checkbox"
                    );
    
                    
    $cs_options[] = array( "name" =>__("Breadcrumbs",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_breadcrumbs_switch",
                        "std" => "on",
                        "type" => "checkbox"
                    );
    
    $cs_options[] = array( "name" =>__("Background Color",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_sub_header_bg_color",
                        "std" => "#e9e9e9",
                        "type" => "color"
                    );    
    $cs_options[] = array( "name" =>__("Text Color",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_sub_header_text_color",
                        "std" => "#ffffff",
                        "type" => "color"
                    );    
    $cs_options[] = array( "name" =>__("Border Color",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_sub_header_border_color",
                        "std" => "",
                        "type" => "color"
                    );            
    $cs_options[] = array( "name" =>__("Background",'dir'),
                        "desc"    => "",
                        "hint_text" =>__("Background Image",'dir'),
                        "id"     =>   "cs_background_img",
                        "std"    => "",
                        "type"    => "upload logo"
                    );            

    $cs_options[] = array( "name" =>__("Parallax",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_parallax_bg_switch",
                        "std" => "off",
                        "type" => "checkbox"
                    );                
    
    // start footer options    
                
    $cs_options[] = array( "name" =>__("footer options",'dir'),
                        "id" => "tab-footer-options",
                        "type" => "sub-heading"
                        );                        
    $cs_options[] = array( "name" =>__("Footer section",'dir'),
                        "desc" => "",
                        "hint_text" =>__("enable/disable footer area",'dir'),
                        "id" => "cs_footer_switch",
                        "std" => "on",
                        "type" => "checkbox"
                    );            
    $cs_options[] = array( "name" =>__("Footer Widgets",'dir'),
                        "desc" => "",
                        "hint_text" =>__("enable/disable footer widget area",'dir'),
                        "id" => "cs_footer_widget",
                        "std" => "on",
                        "type" => "checkbox"
                    );                    
    
        
    $cs_options[] = array( "name" =>__("Social Icons",'dir'),
                        "desc" => "",
                        "hint_text" =>__("enable/disable Social Icons",'dir'),
                        "id" => "cs_sub_footer_social_icons",
                        "std" => "on",
                        "type" => "checkbox");                        
    $cs_options[] = array( "name" =>__("Footer Menu",'dir'),
                        "desc" => "",
                        "hint_text" =>__("enable/disable Footer Menu",'dir'),
                        "id" => "cs_sub_footer_menu",
                        "std" => "on",
                        "type" => "checkbox");            
    $cs_options[] = array( "name" =>__("NewsLetter Signup",'dir'),
                        "desc" => "",
                        "hint_text" =>__("enable/disable NewsLetter Signup area",'dir'),
                        "id" => "cs_footer_newsletter",
                        "std" => "off",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Back to top",'dir'),
                        "desc" => "",
                        "hint_text" => "enable/disable Back to top",
                        "id" => "cs_footer_back_to_top",
                        "std" => "on",
                        "type" => "checkbox");        
                        
    $cs_options[] = array( "name" =>__("footer logo",'dir'),
                        "desc" => "",
                        "hint_text" =>__("set custom footer logo",'dir'),
                        "id" =>   "cs_footer_logo",
                        "std" => get_template_directory_uri()."/assets/images/footer-logo.png",
                        "type" => "upload logo");
    $cs_options[] = array( "name" =>__("Footer Background Image",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set custom Footer Background Image",'dir'),
                        "id" =>   "cs_footer_background_image",
                        "std" => get_template_directory_uri()."/assets/images/footer-bg.jpg",
                        "type" => "upload logo");
    $cs_options[] = array( "name" =>__("copyright text",'dir'),
                        "desc" => "",
                        "hint_text" =>__("write your own copyright text",'dir'),
                        "id" => "cs_copy_right",
                        "std" => "&copy; 2014 Theme Options Wordpress All rights reserved.",
                        "type" => "textarea"
                    );
     $cs_options[] = array( "name" =>__("Footer Widgets",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set footer widgets sidebar",'dir'),
                        "id" =>   "cs_footer_widget_sidebar",
                        "std" => "footer-widget-1",
                        "type" => "select_sidebar",
                        "options" => $cs_sidebar,
					);            
    // End footer tab setting
    /* general colors*/                
    $cs_options[] = array( "name" =>__("general colors",'dir'),
                        "id" => "tab-general-color",
                        "type" => "sub-heading"
                        );    
    $cs_options[] = array( "name" =>__("Theme Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Choose theme skin color",'dir'),
                        "id" => "cs_theme_color",
                        "std" => "#074d87",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" =>__("Background Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Choose Body Background Color",'dir'),
                        "id" => "cs_bg_color",
                        "std" => "#ffffff",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" =>__("Body Text Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Choose text color",'dir'),
                        "id" => "cs_text_color",
                        "std" => "#555555",
                        "type" => "color"
                    );    
                    
    // start top strip tab options
    $cs_options[] = array( "name" =>__("header colors",'dir'),
                        "id" => "tab-header-color",
                        "type" => "sub-heading"
                        );    
    $cs_options[] = array( "name" =>__("top strip colors",'dir'),
                        "id" => "tab-top-strip-color",
                        "std" => "Top Strip",
                        "type" => "section",
                        "options" => ""
                        );
    $cs_options[] = array( "name" =>__("Background Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Change Top Strip background color",'dir'),
                        "id" => "cs_topstrip_bgcolor",
                        "std" => "#ffffff",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" =>__("Text Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Change Top Strip text color",'dir'),
                        "id" => "cs_topstrip_text_color",
                        "std" => "#ffffff",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" =>__("Link Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Change Top Strip link color",'dir'),
                        "id" => "cs_topstrip_link_color",
                        "std" => "#ffffff",
                        "type" => "color"
                    );
                    
                        
    // end top stirp tab options
    // start header color tab options
    $cs_options[] = array( "name" =>__("Header Colors",'dir'),
                        "id" => "tab-header-color",
                        "std" => "Header Colors",
                        "type" => "section",
                        "options" => ""
                        );
    $cs_options[] = array( "name" =>__("Background Color",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Change Header background color",'dir'),
                        "id" => "cs_header_bgcolor",
                        "std" => "",
                        "type" => "color"
                    );                                            
    $cs_options[] = array( "name" => "Navigation Background Color",
                        "desc" => "",
                        "hint_text" => "Change Header Navigation Background color",
                        "id" => "cs_nav_bgcolor",
                        "std" => "#ffffff",
                        "type" => "color"
                    );
                    
    
                    
    $cs_options[] = array( "name" => "Menu Link color",
                        "desc" => "",
                        "hint_text" => "Change Header Menu Link color",
                        "id" => "cs_menu_color",
                        "std" => "#444444",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" => "Menu Active Link color",
                        "desc" => "",
                        "hint_text" => "Change Header Menu Active Link color",
                        "id" => "cs_menu_active_color",
                        "std" => "#074d87",
                        "type" => "color"
                    );
                    

    $cs_options[] = array( "name" => "Submenu Background",
                        "desc" => "",
                        "hint_text" => "Change Submenu Background color",
                        "id" => "cs_submenu_bgcolor",
                        "std" => "#ffffff",
                        "type" => "color",
                    );
            
    $cs_options[] = array( "name" => "Submenu Link Color ",
                        "desc" => "",
                        "hint_text" => "Change Submenu Link color",
                        "id" => "cs_submenu_color",
                        "std" => "#444444",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" => "Submenu Hover Link Color",
                        "desc" => "",
                        "hint_text" => "Change Submenu Hover Link color",
                        "id" => "cs_submenu_hover_color",
                        "std" => "#074d87",
                        "type" => "color"
                    );
    
    
    
    /* footer colors*/                
    $cs_options[] = array( "name" => "footer colors",
                        "id" => "tab-footer-color",
                        "type" => "sub-heading"
                        );                                
    $cs_options[] = array( "name" => "Footer Background Color",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_footerbg_color",
                        "std" => "#434343",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "Footer Title Color",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_title_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" => "Footer Text Color",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_footer_text_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
                    
    $cs_options[] = array( "name" => "Footer Link Color",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_link_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "Footer Widget Background Color",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_sub_footerbg_color",
                        "std" => "#ffffff",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "Copyright Text",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_copyright_text_color",
                        "std" => "#666666",
                        "type" => "color"
                    );
    
    /* heading colors*/                
    $cs_options[] = array( "name" => "heading colors",
                        "id" => "tab-heading-color",
                        "type" => "sub-heading"
                        );                                
    $cs_options[] = array( "name" => "heading h1",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_h1_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "heading h2",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_h2_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "heading h3",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_h3_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "heading h4",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_h4_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "heading h5",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_h5_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
    
    $cs_options[] = array( "name" => "heading h6",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_h6_color",
                        "std" => "#333333",
                        "type" => "color"
                    );
                                                                                                                                                                                                                
    // end header color tab options    
    
    /* start custom font family */
    $cs_options[] = array( "name" => "Custom Fonts",
                        "id" => "tab-custom-font",
                        "type" => "sub-heading"
                        );
                        
    $cs_options[] = array( "name" => "Custom Font .woff",
                        "desc" => "",
                        "hint_text" => "Custom font for your site upload .woff format file.",
                        "id" =>   "cs_custom_font_woff",
                        "std" =>  "",
                        "type" => "upload font"
                    );
                    
    $cs_options[] = array( "name" => "Custom Font .ttf",
                        "desc" => "",
                        "hint_text" => "Custom font for your site upload .ttf format file.",
                        "id" =>   "cs_custom_font_ttf",
                        "std" =>  "",
                        "type" => "upload font"
                    );
                    
    $cs_options[] = array( "name" => "Custom Font .svg",
                        "desc" => "",
                        "hint_text" => "Custom font for your site upload .svg format file.",
                        "id" =>   "cs_custom_font_svg",
                        "std" =>  "",
                        "type" => "upload font"
                    );
                    
    $cs_options[] = array( "name" => "Custom Font .eot",
                        "desc" => "",
                        "hint_text" => "Custom font for your site upload .eot format file.",
                        "id" =>   "cs_custom_font_eot",
                        "std" =>  "",
                        "type" => "upload font"
                    );    
                                    
    /* start font family */
    $cs_options[] = array( "name" => "font family",
                        "id" => "tab-font-family",
                        "type" => "sub-heading"
                        );
    $cs_options[] = array( "name" => "Content Font",
                        "desc" => "",
                        "hint_text" => "Set fonts for Body text",
                        "id" =>   "cs_content_font",
                        "std" => "Raleway",
                        "type" => "gfont_select",
                        "options" => $g_fonts
                    );
    $cs_options[] = array( "name" => "Content Font Attribute",
                        "desc" => "",
                        "hint_text" => "Set Font Attribute",
                        "id" =>   "cs_content_font_att",
                        "std" => "regular",
                        "type" => "gfont_att_select",
                        "options" => $g_fonts_atts
                    );
    $cs_options[] = array( "name" => "Main Menu Font",
                        "desc" => "",
                        "hint_text" => "Set font for main Menu. It will be applied to sub menu as well",
                        "id" =>   "cs_mainmenu_font",
                        "std" => "Raleway",
                        "type" => "gfont_select",
                        "options" => $g_fonts
                    );
    $cs_options[] = array( "name" => "Main Menu Font Attribute",
                        "desc" => "",
                        "hint_text" => "Set Font Attribute",
                        "id" =>   "cs_mainmenu_font_att",
                        "std" => "regular",
                        "type" => "gfont_att_select",
                        "options" => $g_fonts_atts
                    );
    $cs_options[] = array( "name" => "Headings Font",
                        "desc" => "",
                        "hint_text" => "Select font for Headings. It will apply on all posts and pages headings",
                        "id" =>   "cs_heading_font",
                        "std" => "Raleway",
                        "type" => "gfont_select",
                        "options" => $g_fonts
                    );
    $cs_options[] = array( "name" => "Headings Font Attribute",
                        "desc" => "",
                        "hint_text" => "Set Font Attribute",
                        "id" =>   "cs_heading_font_att",
                        "std" => "600",
                        "type" => "gfont_att_select",
                        "options" => $g_fonts_atts
                    );                    
    $cs_options[] = array( "name" => "Widget Headings Font",
                        "desc" => "",
                        "hint_text" => "Set font for Widget Headings",
                        "id" =>   "cs_widget_heading_font",
                        "std" => "Raleway",
                        "type" => "gfont_select",
                        "options" => $g_fonts
                    );
    $cs_options[] = array( "name" => "Widget Headings Font Attribute",
                        "desc" => "",
                        "hint_text" => "Set Font Attribute",
                        "id" =>   "cs_widget_heading_font_att",
                        "std" => "500",
                        "type" => "gfont_att_select",
                        "options" => $g_fonts_atts
                    );                                
     /* start font size */
    $cs_options[] = array( "name" => "Font size",
                        "id" => "tab-font-size",
                        "type" => "sub-heading"
                        );
     
    $cs_options[] = array( "name" => "Content",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_content_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "14",
                        "type" => "range"
                    );
    $cs_options[] = array( "name" => "Main Menu",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_mainmenu_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "14",
                        "type" => "range"
                    );
    $cs_options[] = array( "name" => "Heading 1",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_1_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "24",
                        "type" => "range"
                    );
    $cs_options[] = array( "name" => "Heading 2",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_2_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "18",
                        "type" => "range"
                    );
    $cs_options[] = array( "name" => "Heading 3",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_3_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "16",
                        "type" => "range"
                    );    
    $cs_options[] = array( "name" => "Heading 4",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_4_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "16",
                        "type" => "range"
                    );
    $cs_options[] = array( "name" => "Heading 5",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_5_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "14",
                        "type" => "range"
                    );
    $cs_options[] = array( "name" => "Heading 6",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_heading_6_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "14",
                        "type" => "range"
                    );
                    
    $cs_options[] = array( "name" => "Widget Heading",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_widget_heading_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "15",
                        "type" => "range"
                    );        
    $cs_options[] = array( "name" => "Section Heading",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_section_heading_size",
                        "min" => '6',
                        "max" => '50',
                        "std" => "24",
                        "type" => "range"
                    );                                                                      
    /* social icons setting*/                    
    $cs_options[] = array( "name" => "social icons",
                        "id" => "tab-social-setting",
                        "type" => "sub-heading"
                        );            
    $cs_options[] = array(  "name" => "Social Network",
							"desc" => "",
							"hint_text" => "",
							"id" => "cs_social_network",
							"std" => "",
							"type" => "networks",
							"options" => $social_network
                    ); 
    /* social icons end*/    
    /* social Network setting*/                    
                    
    $cs_options[] = array( "name" =>__("social Sharing",'dir'),
                        "id" => "tab-social-network",
                        "type" => "sub-heading"
                        );
    $cs_options[] = array( "name" =>__("Facebook",'dir'),
							"desc" => "",
							"hint_text" => "",
							"id" => "cs_facebook_share",
							"std" => "on",
							"type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Twitter",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_twitter_share",
                        "std" => "on",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Google Plus",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_google_plus_share",
                        "std" => "on",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Pinterest",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_pintrest_share",
                        "std" => "on",
                        "type" => "checkbox"
					);
                        
    $cs_options[] = array( "name" =>__("Tumblr",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_tumblr_share",
                        "std" => "on",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Dribbble",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_dribbble_share",
                        "std" => "off",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Instagram",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_instagram_share",
                        "std" => "on",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("StumbleUpon",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_stumbleupon_share",
                        "std" => "on",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("youtube",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_youtube_share",
                        "std" => "on",
                        "type" => "checkbox");
    
    $cs_options[] = array( "name" =>__("share more",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_share_share",
                        "std" => "off",
                        "type" => "checkbox");
    
    /* social network end*/
    
    
    
    /* custom code setting*/    
    $cs_options[] = array( "name" =>__("custom code",'dir'),
                        "id" => "tab-custom-code",
                        "type" => "sub-heading"
                    );
    $cs_options[] = array( "name" =>__("Custom Css",'dir'),
                        "desc" => "",
                        "hint_text" =>__("write you custom css without style tag",'dir'),
                        "id" => "cs_custom_css",
                        "std" => "",
                        "type" => "textarea"
                    );
                        
    $cs_options[] = array( "name" =>__("Custom JavaScript",'dir'),
                        "desc" => "",
                        "hint_text" =>__("write you custom js without script tag",'dir'),
                        "id" => "cs_custom_js",
                        "std" => "",
                        "type" => "textarea"
                    );
    
    //== Banner Fields
    $cs_options[] = array( "name" =>__("Ads Unit",'dir'),
                        "id" => "banner-fields",
                        "type" => "sub-heading"
                    );
    $cs_options[] = array( "name" =>__("Ads Unit Settings",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_banner_fields",
                        "std" => "",
                        "type" => "banner_fields",
                        "options" => $banner_fields
                    );
                    
    /* sidebar tab */
    $cs_options[] = array( "name" =>__("sidebar",'dir'),
                        "id" => "tab-sidebar",
                        "type" => "sub-heading"
                    );
    $cs_options[] = array( "name" => __("Sidebar",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Select a sidebar from the list already given. (Nine pre-made sidebars are given)",'dir'),
                        "id" => "cs_sidebar",
                        "std" => $sidebar,
                        "type" => "sidebar",
                        "options" => $sidebar
                    );
    
    $cs_options[] = array( "name" =>__("post layout",'dir'),
                        "id" => "cs_non_metapost_layout",
                        "std" => "single post layout",
                        "type" => "section",
                        "options" => ""
                        );                
    $cs_options[] = array( "name" =>__("Single Post Layout",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Use this option to set default layout. It will be applied to all posts",'dir'),
                        "id" =>   "cs_single_post_layout",
                        "std" => "sidebar_right",
                        "type" => "layout",
                        "options" => array(
                            "no_sidebar" =>__("full width",'dir'),
                            "sidebar_left"=>__("sidebar left",'dir'),
                            "sidebar_right"=>__("sidebar right",'dir'),
                            )
                        );
                    
    $cs_options[] = array( "name" =>__("Single Layout Sidebar",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Select Single Post Layout of your choice for sidebar layout. You cannot select it for full width layout",'dir'),
                        "id" =>   "cs_single_layout_sidebar",
                        "std" => "Default Pages",
                        "type" => "select_sidebar",
                        "options" => $cs_sidebar
                    );
                    
    $cs_options[] = array( "name" =>__("default pages",'dir'),
                        "id" => "default_pages",
                        "std" => "default pages",
                        "type" => "section",
                        "options" => ""
                        );
    $cs_options[] = array( "name" =>__("Default Pages Layout",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set Sidebar for all pages like Search, Author Archive, Category Archive etc",'dir'),
                        "id" =>   "cs_default_page_layout",
                        "std" => "sidebar_right",
                        "type" => "layout",
                        "options" => array(
                            "no_sidebar" =>__("full width",'dir'),
                            "sidebar_left"=>__("sidebar left",'dir'),
                            "sidebar_right"=>__("sidebar right",'dir'),
                            )
                        );                    
    $cs_options[] = array( "name" =>__("Sidebar",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Select pre-made sidebars for default pages on sidebar layout. Full width layout cannot have sidebars",'dir'),
                        "id" =>   "cs_default_layout_sidebar",
                        "std" => "Default Pages",
                        "type" => "select_sidebar",
                        "options" => $cs_sidebar
                    );    
    $cs_options[] = array( "name" =>__("Excerpt",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Set excerpt length/limit from here. It controls text limit for post's content",'dir'),
                        "id" => "cs_excerpt_length",
                        "std" => "255",
                        "type" => "text"
                    );        
    
    /* seo */
    $cs_options[] = array( "name" =>__("SEO",'dir'),
                        "id" => "tab-seo",
                        "type" => "sub-heading"
                        );
    $cs_options[] = array( "name" =>'<b>'.__("Attention for External SEO Plugins!",'dir').'</b>',
                        "id" => "header_postion_attention",
                        "std"=>" <strong> If you are using any external SEO plugin, Turn OFF these options. </strong>",
                        "type" => "announcement"
                    );

    $cs_options[] = array( "name" =>__("Built-in SEO fields",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Turn SEO options ON/OFF",'dir'),
                        "id" => "cs_builtin_seo_fields",
                        "std" => "on",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Meta Description",'dir'),
                        "desc" => "",
                        "hint_text" =>__("HTML attributes that explain the contents of web pages commonly used on search engine result pages (SERPs) for pages snippets",'dir'),
                        "id" => "cs_meta_description",
                        "std" => "",
                        "type" => "text"
                    );
                    
    $cs_options[] = array( "name" =>__("Meta Keywords",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Attributes of meta tags, a list of comma-separated words included in the HTML of a Web page that describe the topic of that page",'dir'),
                        "id" => "cs_meta_keywords",
                        "std" => "",
                        "type" => "text"
                    );
                    
                    
    /* maintenance mode*/                
    $cs_options[] = array( "name" =>__("Maintenance Mode",'dir'),
                        "fontawesome" => 'icon-tasks',
                        "id" => "tab-maintenace-mode",
                        "std" => "",
                        "type" => "main-heading",
                        "options" => ""
                        );    
    $cs_options[] = array( "name" =>__("Maintenance Mode",'dir'),
                        "id" => "tab-maintenace-mode",
                        "type" => "sub-heading"
                        );
    $cs_options[] = array( "name" =>__("Maintenace Page",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Users will see Maintenance page & logged in Admin will see normal site.",'dir'),
                        "id" => "cs_maintenance_page_switch",
                        "std" => "off",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Show Logo",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Show/Hide logo on Maintenance. Logo can be uploaded from General > Header in CS Theme options.",'dir'),
                        "id" => "cs_maintenance_logo_switch",
                        "std" => "on",
                        "type" => "checkbox");
                        
    $cs_options[] = array( "name" =>__("Maintenance Text",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Text for Maintenance page. Insert some basic HTML or use shortcodes here.",'dir'),
                        "id" => "cs_maintenance_text",
                        "std" =>'<h1>'.__("Sorry, We are down for maintenance",'dir').'</h1>','<p>'.__('We are currently under maintenance, if all goas as planned we will be back in','dir').'</p>',
                        "type" => "textarea"
                    );
                    
    $cs_options[] = array( "name" =>__("Launch Date",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Estimated date for completion of site on Maintenance page.",'dir'),
                        "id" => "cs_launch_date",
                        "std" => gmdate("dd/mm/yy"),
                        "type" => "text"
                    );
                    
    $cs_options[] = array( "name" =>__("Social Network",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Re-direct your users to social networking links when site is on Maintenance mode.",'dir'),
                        "id" => "cs_maintenance_social_network",
                        "std" => "on",
                        "type" => "checkbox");                                                
    /* api options tab*/
    $cs_options[] = array( "name" =>__("api settings",'dir'),
                        "fontawesome" => 'icon-chain',
                        "id" => "tab-api-options",
                        "std" => "",
                        "type" => "main-heading",
                        "options" => ""
                        );
    //Start Twitter Api    
    $cs_options[] = array( "name" =>__("all api settings",'dir'),
                        "id" => "tab-api-options",
                        "type" => "sub-heading"
                        );
                                   
    $cs_options[] = array( "name" =>__("Attention for API Settings!",'dir'),
                        "id" => "header_postion_attention",
                        "std"=>__("API Settings allows admin of the site to show their activity on site semi-automatically. Set your social account API once, it will be update your social activity automatically on your site.",'dir'),
                        "type" => "announcement"
                    );
	$cs_options[] = array( "name" =>__("Twitter",'dir'),
                        "id" => "Twitter",
                        "std" => "Twitter",
                        "type" => "section",
                        "options" => ""
                        ); 
    $cs_options[] = array( "name" =>__("Show Twitter",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Turn Twitter option ON/OFF",'dir'),
                        "id" => "cs_twitter_api_switch",
                        "std" => "on",
                        "type" => "checkbox"); 
                        
    $cs_options[] = array( "name" =>__("Consumer Key",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_consumer_key",
                        "std" => "",
                        "type" => "text");
                        
    $cs_options[] = array( "name" =>__("Consumer Secret",'dir'),
                        "desc" => "",
                        "hint_text" => "Insert consumer key. To get your account key, <a href='https://dev.twitter.com/' target='_blank'>Click Here </a>",
                        "id" =>   "cs_consumer_secret",
                        "std" => "",
                        "type" => "text");
                        
    $cs_options[] = array( "name" =>__("Access Token",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Insert Twitter Access Token for permissions. When you create your Twitter App, you get this Token",'dir'),
                        "id" =>   "cs_access_token",
                        "std" => "",
                        "type" => "text");
                        
    $cs_options[] = array( "name" =>__("Access Token Secret",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Insert Twitter Access Token Secret here. When you create your Twitter App, you get this Token",'dir'),
                        "id" =>   "cs_access_token_secret",
                        "std" => "",
                        "type" => "text");
    //end Twitter Api
    //Start Facebook Api
    
    if(class_exists('wp_directory'))
    {
        $cs_options[] = array( "name" =>__("Facebook",'dir'),
                            "id" => "Facebook",
                            "std" => "Facebook",
                            "type" => "section",
                            "options" => ""
                            );    
        $cs_options[] = array( "name" =>__("Facebook Login On/Off",'dir'),
                            "desc" => "",
                            "hint_text" =>__("Turn Facebook Login ON/OFF",'dir'),
                            "id" =>   "cs_facebook_login_switch",
                            "std" => "on",
                            "type" => "checkbox",
                            "options" => $on_off_option);
                            
        $cs_options[] = array( "name" =>__("Facebook Application ID",'dir'),
                            "desc" => "",
                            "hint_text" => "To get your Facebook Aplication ID <a href='https://developers.facebook.com/docs/graph-api/reference/v2.1/app' target='_blank'>Click Here </a>",
                            "id" =>   "cs_facebook_app_id",
                            "std" => "",
                            "type" => "text");
                            
        $cs_options[] = array( "name" =>__("Facebook  Secret",'dir'),
                            "desc" => "",
                            "hint_text" =>__("Put your Facebook Secret here. You can find it in your facebook Application Dashboard",'dir'),
                            "id" =>   "cs_facebook_secret",
                            "std" => "",
                            "type" => "text");
    }
    //end facebook api
    //start google api
    $cs_options[] = array( "name" =>__("Google",'dir'),
                        "id" => "Google",
                        "std" => "Google+",
                        "type" => "section",
                        "options" => ""
                        );    
    $cs_options[] = array( "name" =>__("Google+ Login On/Off",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Turn Google+ Login ON/OFF",'dir'),
                        "id" =>   "cs_google_login_switch",
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option);
                        
    $cs_options[] = array( "name" =>__("Google+ Client ID",'dir'),
                        "desc" => "",
                        "hint_text" =>__("Type your Google Login information here",'dir'),
                        "id" =>   "cs_google_client_id",
                        "std" => "",
                        "type" => "text");
                        
    $cs_options[] = array( "name" =>__("Google+ Client Secret",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_google_client_secret",
                        "std" => "",
                        "type" => "text");
                        
    $cs_options[] = array( "name" =>__("Google+ API key",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_google_api_key",
                        "std" => "",
                        "type" => "text");
                        
    $cs_options[] = array( "name" =>__("Fixed redirect url for login",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_google_login_redirect_url",
                        "std" => "",
                        "type" => "text");
    
    //end google api
    //start mailChimp api
    $cs_options[] = array( "name" =>__("MailChimp",'dir'),
                        "id" => "mailchimp",
                        "std" => "MailChimp",
                        "type" => "section",
                        "options" => ""
                        );    
    $cs_options[] = array( "name" =>__("MailChimp Key",'dir'),
                        "desc" =>__("Enter a valid MailChimp API key here to get started. Once you've done that, you can use the MailChimp Widget from the Widgets menu. You will need to have at least MailChimp list set up before the using the widget. You can get your mailchimp activation key",'dir'),
                        "hint_text" => "Get your mailchimp key by <a href='https://login.mailchimp.com/' target='_blank'>Clicking Here </a>",
                        "id" =>   "cs_mailchimp_key",
                        "std" => "90f86a57314446ddbe87c57acc930ce8-us2",
                        "type" => "text"
                        );
                        
    $cs_options[] = array( "name" =>__("MailChimp List",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_mailchimp_list",
                        "std" => "on",
                        "type" => "mailchimp",
                        "options" => $mail_chimp_list
                    );
                    
    $cs_options[] = array( "name" =>__("Flickr API Setting",'dir'),
                        "id" => "flickr_api_setting",
                        "std" => "Flickr API Setting",
                        "type" => "section",
                        "options" => ""
                        );                    
    $cs_options[] = array( "name" =>__("Flickr key",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "flickr_key",
                        "std" => "",
                        "type" => "text");
    $cs_options[] = array( "name" =>__("Flickr secret",'dir'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "flickr_secret",
                        "std" => "",
                        "type" => "text");
    
     if(class_exists('wp_directory')) {
    	
		if(class_exists('CS_PAYMENTS')) {
			$cs_options[] = array(
				"name" =>__("Payments",'directory'),
				"fontawesome" => 'icon-wallet2',
				"id" => "tab-payments",
				"std" => "",
				"type" => "heading",
				"options" => array(
					'tab-currency-settings' =>__('Currency','directory'),
					'tab-gateways-settings' =>__('Payment Gateways','directory'),
				)
			);
		}
		
		$cs_options[] = array(    
                    "name" =>__("Directory Settings",'directory'),
                    "fontawesome" => 'icon-cog3',
                    "type" => "heading",
                    "options" => array(
                        'tab-general-options'=>__('General','directory'),
						'tab-posttype_settings'	=>__('Post Type Settings','directory'),
                        'tab-ads-options'=>__('ADS SUBMISSION','directory'),
                        'tab-reviews-options'=>__('Reviews','directory'),
                        'tab-map-setting'=>__('Map Setting','directory'),
                        'tab-search-setting'=>__('Search Setting','directory'),
						'tab-advance-search-setting'=>__('Advance Search Settings','directory'),
                     ) 
                );

		if(class_exists('CS_PAYMENTS')) {
			// Currency Settings
			$cs_options[] = array(
				"name" =>__("Currency Settings",'directory'),
				"id" => "tab-currency-settings",
				"type" => "sub-heading"
			);
			
			global $gateways;
			$general_settings	= new  CS_PAYMENTS();
			$cs_settings	= $general_settings->cs_general_settings();
			
			foreach($cs_settings as $key => $params ){
				$cs_options[] = $params;
			}
			
			$cs_options[] = array(
				"name"  =>__("Gateways Settings",'directory'),
				"id" 	=> "tab-gateways-settings",
				"type"  => "sub-heading"
			);
						
			foreach( $gateways as $key => $value ) { 
				 if( class_exists( $key ) ) {
					$settings	= new  $key();
					$cs_settings	= $settings->settings();				
					foreach($cs_settings as $key => $params ){
						
						$cs_options[] = $params;
					}
				}
			}
		}
		
		//Map Setting
        $cs_options[] = array( 
                    "name" 	=>__("General Setting",'directory'),
                    "id" 	=> "tab-general-options",
                    "type"  => "sub-heading"
                );

        $cs_options[] = array( "name" 	=>__("User Profile Page",'directory'),
                            "desc" 		=> "",
                            "hint_text" =>__("Go to Pages and create New Page, Assign User Profile Page Template and then choose that New Page here",'directory'),
                            "id" =>   "cs_dashboard",
                            "std" => "",
                            "type" => "select_dashboard",
                            "options" => ''
                        );
        $cs_options[] = array( "name" =>__("Login Style",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Select user login View.",'directory'),
                            "id" =>   "cs_user_login_method",
                            "std" => "",
                            "type" => "select",
                            "options" => array(
                                'dashboard-login'=>__('Dashboard Login','directory'),
                                'dropdown-menu'=>__('Dropdown Menu','directory'),
                            )
                        );
        
            $cs_options[] = array( "name" =>__("Ad Address Settings",'directory'),
                        "id" => "tab-general-options",
                        "std" => "Ad Address Settings",
                        "type" => "section",
                        "options" => ""
                    );
			        $cs_options[] = array( "name" => "Address Style",
                            "desc" => "",
                            "hint_text" =>__("Select Address View.",'directory'),
                            "id" =>   "cs_dir_address_view",
                            "std" => "city-country",
                            "type" => "select",
                            "options" => array(
                                'city'=>__('City Only','directory'),
                                'country'=>__('Country Only','directory'),
								'city-country'=>__('City and Country','directory'),
								'full-address'=>__('Complete Address','directory'),
                            )
                        );
		    $cs_options[] = array( "name" => "Address Text Limit",
                        "desc" => "",
                        "hint_text" =>__("only work for complete address",'directory'),
                        "id" =>   "cs_address_limit",
                        "std" => "30",
                        "type" => "text");
			
			
			 $cs_options[] = array( "name" => "Language Settings",
                        "id" => "tab-general-options",
                        "std" => "Language Settings",
                        "type" => "section",
                        "options" => ""
                    );
					
			$dir = wp_directory::plugin_dir().'/languages/';
			$cs_plugin_language['']	= 'Select Language File';
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						$ext = pathinfo($file, PATHINFO_EXTENSION);
						if( $ext == 'mo' ) {
							$cs_plugin_language[$file]	= $file;
						}
					}
					closedir($dh);
				}
			}
			
			$cs_options[] = array( "name" => "Select Plugin Language",
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_language_file",
                        "std" => "30",
                        "type" => "select",
						"options" => $cs_plugin_language,
						);						
        
		/* Post Type Setting*/
    $cs_options[] = array( "name" => "Post Type Settings",
                        "id" => "tab-posttype_settings",
                        "type" => "sub-heading"
                    );
    // Directory Post Type                    
    $cs_options[] = array( "name" => "Directory Post Type",
                        "id" => "tab-directory-posttype",
                        "std" => "Directory Post Type",
                        "type" => "section",
                        "options" => ""
                        );                        
    $cs_options[] = array( "name" => "Directory Menu Title",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_directory_menu_title",
                        "std" => "Directory",
                        "type" => "text"
                    );
    $cs_options[] = array( "name" => "Directory Menu Slug",
                        "desc" => "",
                        "hint_text" => "",
                        "id" => "cs_directory_menu_slug",
                        "std" => "directory",
                        "type" => "text"
                    );    
    /* Post Type setting end*/
	
		//ADS Setting
        $cs_options[] = array( 
                    "name" 	=> __("ADS Setting",'directory'),
                    "id" 	=> "tab-ads-options",
                    "type" 	=> "sub-heading"
                );
        $cs_options[] = array( "name" => "Public Submissions",
                            "desc" => "",
                            "hint_text" => "Create Directory From Frontend",
                            "id" =>   "cs_directory_ads_allow",
                            "std" => "on",
                            "type" => "checkbox",
                            "options" => $on_off_option
                    );
        $cs_options[] = array( "name" =>__("New Ads Status",'directory'),
                            "desc"         => "",
                            "hint_text" =>__("Enable Public Submissions, You can set default status of user directory",'directory'),
                            "id"         => "cs_directory_visibility",
                            "std"         => "pending",
                            "type"         => "select",
                            "options"     => array('pending'=>'Pending','publish'=>'Publish')
                        );
        $cs_options[] = array( "name"         =>__("Allow Editing",'directory'),
                            "desc"         => "",
                            "hint_text" => "",
                            "id"         => "cs_directory_editing",
                            "std"         => "on",
                            "type"         => "checkbox",
                            "options"     => $on_off_option
                        );
         $cs_options[] = array( "name"         =>__("Maximum Ads per user",'directory'),
								"desc"         => "",
								"hint_text" => "",
								"id"         => "directory_submition_per_user",
								"std"         => "50",
								"type"         => "text");
        
		$cs_options[] = array("name" => "Maximum Image Upload Size (bytes)",
										"desc" => "Default Size is 9MB",
										"hint_text" => "",
										"id" => "cs_image_size",
										"std" => "9437184",
										"type" => "text");
		$cs_options[] = array("name" => "Maximum Attachment Upload Size (bytes)",
										"desc" => "Default Size is 5MB",
										"hint_text" => "",
										"id" => "cs_attachment_size",
										"std" => "5242880",
										"type" => "text");
			
		$cs_options[] = array( "name"	=>__("Directory Types Order",'directory'),
                            "desc"		=> "",
                            "hint_text"	=>__("Set Directory Types Order for ad submission page.",'directory'),
                            "id"		=> "cs_directory_type_order_by",
                            "std"		=> "By title",
                            "type"		=> "select",
                            "options"	=> array('By date'=>'By date','By title'=>'By title')
                        );
						
        $cs_options[] = array( "name" =>__("Create New Ad Instruction",'directory'),
                        "desc" => "",
                        "hint_text" =>__("It will display on Add Directory Page",'directory'),
                        "id" => "cs_add_directory_text",
                        "std" => "An event happening at a certain time and location, such as a concert, lecture, or festival.",
                        "type" => "textarea"
                    );
        $cs_options[] = array( "name" 		=>__("Terms & Conditions",'directory'),
                            "desc"         	=> "",
                            "hint_text" 	=>__("Enable Terms & Conditions on Create New Add",'directory'),
                            "id"         	=> "cs_directory_terms_enable",
                            "std"         	=> "on",
                            "type"         	=> "checkbox",
                            "options"     	=> $on_off_option
                        );
        $cs_options[] = array( "name" =>__("Terms & Conditions Description",'directory'),
                        	"desc" 	=> "",
							"hint_text" =>__("Write your own Directory text",'directory'),
							"id" 	=> "cs_directory_terms_text",
                        	"std" 	=> "som text",
                        "type" => "textarea"
                    );    
					
		$cs_options[] = array( "name" =>__("Featured Ad",'directory'),
                            "id" => "Featured Ad",
                            "std" => "Featured Ad",
                            "type" => "section",
                            "options" => ""
                            );
		$cs_currency_sign = isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';
        $cs_options[] = array( "name"		=> sprintf(__("Featured Ad Price %s", "dir"), $cs_currency_sign),
                            "desc"			=> "",
                            "hint_text"		=> "",
                            "id"			=> "directory_featured_ad_price",
                            "std"			=> "",
                            "type"			=> "text"
                        );
        $cs_options[] = array( "name"		=>__("Number of Days",'directory'),
                            "desc"			=> "",
                            "hint_text"		=> "",
                            "id"			=> "directory_featured_ad_days",
                            "std"			=> "30",
                            "type"			=> "text"
                        );
        $cs_options[] = array( "name"		=>__("Features package Description",'directory'),
							"desc"			=> "",
							"hint_text"		=> "",
							"id"			=>  "cs_featured_package_info",
							"std"			=> 'Have your Ad appear at the top of the category listings for 3, 7 or 14 days.',
							"type"			=> "textarea");
	
        $cs_options[] = array( "name" =>__("Ad Expiry Status",'directory'),
                            "id" => "Ad Expiry Status",
                            "std" => "Ad Expiry Status",
                            "type" => "section",
                            "options" => ""
                            );        
        
        $cs_options[] = array( "name"         =>__("Expired Ads Status",'directory'),
                            "desc"         => "",
                            "hint_text" => "",
                            "id"         => "cs_ad_expiry_status",
                            "std"         => "ad_expiry_private",
                            "type"         => "select",
                            "options"     => array(
                                'ad_expiry_free'        => 'Free',
                                'ad_expiry_private'        => 'Private',
                            )
                        );            
        // Review Setting
        $cs_options[] = array( 
                    "name" =>__("Review Setting",'directory'),
                    "id" => "tab-reviews-options",
                    "type" => "sub-heading"
                );
        $cs_options[] = array( "name" =>__("Reviews Status",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Reviews must be reviewed to be published.",'directory'),
                            "id" =>   "cs_review_status",
                            "std" =>  "publish",
                            "type" => "select",
                            "options" =>array(
                                "publish" => "approve",
                                "private"=>"pending",
                            )
                        );
        $cs_options[] = array( "name"         =>__("Reviews per page",'directory'),
                            "desc"         => "",
                            "hint_text" =>__("No of reviews at ad detail page.",'directory'),
                            "id"         => "reviews_per_page",
                            "std"         => "10",
                            "type"         => "text");              
        $cs_options[] = array( 
                    "name" =>__("Map Setting",'directory'),
                    "id" => "tab-map-setting",
                    "type" => "sub-heading"
                );
        $map_type_options = array('ROADMAP','SATELLITE','HYBRID','TERRAIN');    
        $cs_options[] = array( "name" =>__("Map Type",'directory'),
                            "desc" => "",
                            "hint_text" => "",
                            "id" =>   "cs_map_type",
                            "std" => "ROADMAP",
                            "type" => "select",
                            "options" => $map_type_options
                        );
        $map_marker_url = get_template_directory_uri()  ."/assets/images/culster-icon.png";
        $cs_options[] = array( "name" =>__("Cluster Map Marker",'directory'),
                        "desc" => "",
                        "hint_text" =>__("Default Map Marker URL.",'directory'),
                        "id" =>   "cluster_map_marker",
                        "std" =>  $map_marker_url,
                        "type" => "upload logo"
                    );
                    
        $cs_options[] = array( "name" =>__("Map Cluster Color ",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Default Map Cluster Color",'directory'),
                            "id" =>   "cluster_map_marker_color",
                            "std" => '#333',
                            "type" => "color");
        $product_marker_url = get_template_directory_uri()."/assets/images/map-marker.png";                
        $cs_options[] = array( "name" =>__("Map Marker",'directory'),
                        "desc" => "",
                        "hint_text" =>__("Default Map Marker URL",'directory'),
                        "id" =>   "product_map_marker",
                        "std" =>  $product_marker_url,
                        "type" => "upload logo"
                    );
        $cs_options[] = array( "name" =>__("Zoom",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Default Map Zoom Level",'directory'),
                            "id" =>   "map_zoom",
                            "std" => '11',
                            "type" => "text");
        $cs_options[] = array( "name" =>__("Map Auto Zoom",'directory'),
                        "desc" => "",
                        "hint_text" =>__("Manual Zoom will not work if Auto Zoom is on.",'directory'),
                        "id" =>   "cs_map_auto_zoom",
                        "std" => "off",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );
		$cs_options[] = array( "name" =>__("Map Lock",'directory'),
                        "desc" => "",
                        "hint_text" => "",
                        "id" =>   "cs_map_lock",
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );
        $cs_options[] = array( "name" =>__("Latitude",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Default Map Latitude",'directory'),
                            "id" =>   "map_latitude",
                            "std" => '51.54532829999999',
                            "type" => "text");
        
        $cs_options[] = array( "name" =>__("Longitude",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Default Map Longitude",'directory'),
                            "id" =>   "map_longitude",
                            "std" => '-0.08428670000000693',
                            "type" => "text");                
        $cs_options[] = array( 
                    "name" =>__("Searching Setting",'directory'),
                    "id" => "tab-search-setting",
                    "type" => "sub-heading"
                );
        
            
        $directory_types_options = array(''=>'Select Default Ad',);                        
        $args = array(
            'posts_per_page'            => "-1",
            'post_type'                    => 'directory_types',
            'post_status'                => 'publish',
            'orderby'                    => 'ID',
            'order'                        => 'ASC',
        );
        $custom_query = new WP_Query($args);
        if ( $custom_query->have_posts() <> "" ) {
            while ( $custom_query->have_posts() ): $custom_query->the_post();
                $directory_types_options[get_the_ID()] = get_the_title();
            endwhile;
        }
        
        $cs_options[] = array( "name" =>__("Default Ad Type",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Select Ad Type for user ads",'directory'),
                            "id" =>   "cs_default_ad_type",
                            "std" => "",
                            "type" => "ad_select",
                            "options" => $directory_types_options
                        );
        
		$cs_options[] = array( "name" =>__("Location",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Search Location Suggestions",'directory'),
                            "id" =>   "cs_directory_location_suggestions",
                            "std" =>  "google",
                            "type" => "select",
							"change" => "yes",
                            "options" =>array(
                            	"google"	=>__("Google",'directory'),
                            	"website"	=>__("Edit Locations",'directory'),
                            )
                        );
       
	   $cs_options[] = array( "name" =>__("Search By Location",'directory'),
                            "desc" => "",
                            "hint_text" =>__("Choose Search By Location here. it will work only if Location Suggestions is set as 'Edit Locations' ",'directory'),
                            "id" =>   "cs_search_by_location",
                            "std" =>  "",
                            "type" => "select_values",
							"change" => "yes",
                            "options" =>array(
                                "countries_only"   			  =>__("Countries only",'directory'),
								"countries_and_cities"    	  =>__("Countries and Cities",'directory'),
								"cities_only"      			  =>__("Cities only",'directory'),
								"single_city"      		  	  =>__( "Single City",'directory'),
                            )
                        );
		
		$cs_location_countries	= get_option('cs_location_countries');
		$states_list			= get_option('cs_location_states');
		$cities_list			= get_option('cs_location_cities');
		
		$cities_array	= array();
		$cities_array['']	= 'Select City';
		if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) ) {
			foreach( $cs_location_countries as $key => $country ) {
				 if( isset( $states_list[$country['id']] ) ) {
					 $states = $states_list[$country['id']];
					 if( isset( $states ) && $states !='' && is_array($states) ) {
							foreach( $states as $key => $state ) {
								if( isset( $cities_list[$country['id']][$key] ) ) {
									$cities	= $cities_list[$country['id']][$key];
									if( isset( $cities ) && $cities !='' && is_array($cities) ) {
										foreach( $cities as $key => $city ) {
											$cities_array[$city['name']]	=  $city['name'];
										}
									}
								}
							}
					  }
				 }
			}
		}
						
		$cs_options[] = array( "name" 	=>__("Select City",'directory'),
                            "desc" 		=> "",
                            "hint_text" => "",
                            "id" 		=>   "cs_search_by_location_city",
                            "std" 		=>  "",
                            "type" 		=> "select_values",
                            "options" 	=> $cities_array,
                        );
						
		}	
        $cs_options[] = array( "name" =>__("Map Search Element",'directory'),
                        "id" => "tab-map-options",
                        "std" => "Map Search Element",
                        "type" => "section",
                        "options" => ""
                    );    
   
        
		$cs_options[] = array( "name" =>__("Streat View",'directory'),
                            "desc" => "",
                            "hint_text" => "Enable Streat View",
                            "id" =>   "cs_streat_view",
                            "std" =>  "yes",
                            "type" => "select",
                            "options" =>array(
                                "yes"   => "Yes",
                                "no"    => "No",
                            )
                        );    
        
		$cs_options[] = array(  "name" 		=>__("Distance in km/miles",'directory'),
								"desc" 	    => "",
								"hint_text" => "",
								"id" 		=> "distance_km_miles",
								"std" 		=> "miles",
								"type"		=> "select",
								"options" 	=> array(
													"miles"   =>__("Miles",'directory'),
													"km"      =>__("Km",'directory'),
											 )
                        );
						
		$cs_options[] = array( "name" =>__("Default Distance",'directory'),
                        "desc" => "",
                        "hint_text" => "[Max Miles=300, Max KM=480 , Default=150]",
                        "id"   => "cs_loc_max_input",
                        "min"  => '0',
                        "max"  => '500',
                        "std"  => "150",
                        "type" => "range"
                    );
		 
							
        $cs_options[] = array( "name" =>__("Increment Steps",'directory'),
                        "desc" => "",
                        "hint_text" => "",
                        "id"   => "cs_loc_incr_step",
                        "min"  => '1',
                        "max"  => '480',
                        "std"  => "1",
                        "type" => "range"
                    );
                        
        $cs_options[] = array( "name" =>__("Search Result Page",'directory'),
                            "desc" => '',
                            "hint_text" =>__("Please make sure you have selected Search Result Page and on selected page there should be one default page builder element.",'directory'),
                            "id" =>   "cs_directory_search_result_page",
                            "std" => '',
                            "type" => "select_dashboard",
                            "options" => ''
                     );
    
	if(class_exists('wp_directory')) {
	
	$cs_options[] = array( 
                    "name" =>__("Advance Searching Setting",'directory'),
                    "id" => "tab-advance-search-setting",
                    "type" => "sub-heading"
                );
	
	$cs_options[] = array( "name" =>__("Keyword Search ON/OFF",'directory'),
                        "desc" 		  => "",
                        "hint_text"   =>__("Enable Keyword Search section in search element",'directory'),
                        "id" 		  => "cs_search_text",
                        "std" 		  => "off",
                        "type" 		  => "checkbox",
                        "options"     => $on_off_option
                    );
		
	$cs_options[] = array( "name" =>__("Categories  ON/OFF",'directory'),
					"desc" 		  => "",
					"hint_text"   =>__("Enable Categories section in sidebar search",'directory'),
					"id" 		  => "cs_search_categories",
					"std" 		  => "off",
					"type" 		  => "checkbox",
					"options"     => $on_off_option
				);
	
			
	$cs_options[] = array( "name" =>__("Price ON/OFF",'directory'),
					"desc" 		  => "",
					"hint_text"   =>__("Enable price section in search element",'directory'),
					"id" 		  => "cs_search_price",
					"std" 		  => "off",
					"type" 		  => "checkbox",
					"options"     => $on_off_option
				);
	 $cs_options[] = array( "name" =>__("Location Search",'directory'),
						"desc" => "",
						"hint_text" =>__("Location Search ",'directory'),
						"id" =>   "cs_directory_search_location",
						"std" =>  "yes",
						"type" => "select",
						"options" =>array(
							"yes"   => "Yes",
							"no"    => "No",
						)
					);
	
	$cs_options[] = array( "name" =>__("Radius  ON/OFF",'directory'),
					"desc" 		  => "",
					"hint_text"   =>__("Radius will only work if location is enable, otherwise it will not shown in search area.",'directory'),
					"id" 		  => "cs_search_radius",
					"std" 		  => "off",
					"type" 		  => "checkbox",
					"options"     => $on_off_option
				);
				
	$cs_options[] = array( "name" =>__("Enable Geo Location",'directory'),
						"desc" => "",
						"hint_text" =>__("Enable Geo Location",'directory'),
						"id" =>   "goe_location_enable",
						"std" =>  "yes",
						"type" => "select",
						"options" =>array(
							"yes"   => "Yes",
							"no"    => "No",
						)
					);
	
	}
	
	if(class_exists('wp_directory')) {
		// Location Listing
		/*$cs_options[] = array( 
						"name" => "Locations Listing",
						"id" => "tab-locations",
						"type" => "sub-heading"
					);
	
		
		$cs_options[] = array(  "name" 			=> "Add Locations",
								"desc" 			=> "",
								"hint_text" 	=> "",
								"id"			=> "add_locations",
								"std" 			=> "",
								"type" 			=> "add_locations"
						);*/
	}
											
	// import and export theme options tab
    $cs_options[] = array( "name" =>__("import & export",'directory'),
                        "fontawesome" => 'icon-database',
                        "id" => "tab-import-export-options",
                        "std" => "",
                        "type" => "main-heading",
                        "options" => ""
                    );    
    $cs_options[] = array( "name" =>__("import & export",'directory'),
                        "id" => "tab-import-export-options",
                        "type" => "sub-heading"
                        );    
    $cs_options[] = array( "name" =>__("Export",'directory'),
                        "desc" => "",
                        "hint_text" =>__("If you want to make changes in your site or want to preserve your current settings, Export them code by saving this code with you. You can restore your settings by pasting this code in Import section below ",'directory'),
                        "id" => "cs_export_theme_options",
                        "std" => "",
                        "type" => "export"
                    );    
                
    $cs_options[] = array( "name" =>__("Import",'directory'),
                        "desc" => "",
                        "hint_text" =>__("To Import your settings, paste the code that you got in above area and saved it with you",'directory'),
                        "id" => "cs_import_theme_options",
                        "std" => "",
                        "type" => "import"
                    );
	$cs_options[] = array(  "name" 			=>__("free_package_switch",'directory'),
							"desc" 			=> "",
							"hint_text" 	=> "",
							"id"			=> "free_package_switch",
							"std" 			=> $cs_free_package_switch,
							"type" 			=> "free_package"
                    );
	$cs_options[] = array(  "name" 			=>__("Add Packages",'directory'),
							"desc" 			=> "",
							"hint_text" 	=> "",
							"id" 			=> "add_dummy_packges",
							"std" 			=> $cs_packages_options,
							"type" 			=> "packages_data"
                    );
        update_option('cs_theme_data',$cs_options); 
    //update_option('cs_theme_options',$options);                       
    }
}
// saving all the theme options start
/**
*
*
* Header Colors Setting
 */
 
function cs_header_setting(){
    global $cs_header_colors;
      $cs_header_colors = array();
              $cs_header_colors['header_colors'] =array(
                      'header_1'=>array(
                          'color' =>array( 
                              'cs_topstrip_bgcolor'		=> '#00799F',
                              'cs_topstrip_text_color'	=> '#ffffff',
                              'cs_topstrip_link_color'	=> '#ffffff',
                              'cs_header_bgcolor'		=> '',
                              'cs_nav_bgcolor'			=> '#00799F',
                              'cs_menu_color'			=> '#ffffff',
                              'cs_menu_active_color'	=> '#ffffff',
                              'cs_submenu_bgcolor'		=> '#ffffff',
                              'cs_submenu_color'		=> '#333333',
                              'cs_submenu_hover_color'	=> '#00799F',
                          ),
                          'logo' =>array(
                              'cs_logo_with'			=> '173',
                              'cs_logo_height'			=> '35',
                              'cs_logo_margintb'		=> '0',
                              'cs_logo_marginlr'		=> '0',
                          )
                  ),
              );
                  
              return $cs_header_colors;
}