<?php
if(!function_exists('cs_activate_widget')){	
	function cs_activate_widget(){		
		$sidebars_widgets = get_option('sidebars_widgets');	
	/********************** Footer Siderbar Setting Start **********************/	
		 /* ---- Footer Contact Us --- */
		/*----------------------------*/
			$footer_contactinfo = array();
			$footer_contactinfo[1] = array(
				'title' => 'Contact Us',
				'image_url' => '',
				'about_text' => 'Evilly within below recast yet beyond besides read ahead monogamous prema turely terrier lizard.',
				'address' => 'Patricia C. Amedee 4401 Waldeck Street Grapevine Nashville, TX 76051',
				'phone' => '00 500 500 500',
				'fax' => '123 500 500 500',
				'email' => 'london@ChimpDesigns.com',
			);						
			$footer_contactinfo['_multiwidget'] = '1';
			update_option('widget_contactinfo',$footer_contactinfo);
			$footer_contactinfo = get_option('widget_contactinfo');
			krsort($footer_contactinfo);
			foreach($footer_contactinfo as $key1=>$val1){
				$footer_contactinfo_key = $key1;
				if(is_int($footer_contactinfo_key)){
					break;
				}
			}				
		 /* ---- Footer Latest Posts --- */
		/*----------------------------*/
			$footer_recent_post = array();
			$footer_recent_post[1] = array(
				"title"		=>	'Recent Blogs',
				"select_category" 	=> '',
				"showcount" => '3',
				"thumb" => false
				);					
			$footer_recent_post['_multiwidget'] = '1';
			update_option('widget_recentposts',$footer_recent_post);
			$footer_recent_post = get_option('widget_recentposts');
			krsort($footer_recent_post);
			foreach($footer_recent_post as $key1=>$val1){
				$footer_recent_post_key = $key1;
				if(is_int($footer_recent_post_key)){
					break;
				}
			}		
		 /* ---- Footer Flickr Gallery --- */
		/*----------------------------*/
			$footer_reviews = array();
			$footer_reviews[1] = array(
				"title"		=>	'Recent Reviews',
				"get_post_slug" 	=> '',
				"showcount" => '3',
				);					
			$footer_reviews['_multiwidget'] = '1';
			update_option('widget_cs_reviews',$footer_reviews);
			$footer_reviews = get_option('widget_cs_reviews');
			krsort($footer_reviews);
			foreach($footer_reviews as $key1=>$val1){
				$footer_reviews_key = $key1;
				if(is_int($footer_reviews_key)){
					break;
				}
			}
		
		 /* ---- Default Sidebar Facebook widget setting --- */
		/*----------------------------------*/
	
			$facebook_module = array();
			$facebook_module[1] = array(
				"title"		=> 'Facebook',
				"pageurl" 	=> "https://www.facebook.com/envato",
				"showfaces" => "on",
				"showstream" => "",
				"likebox_height" => "265",
				"fb_bg_color" => "#ffffff",
				);						
			$facebook_module['_multiwidget'] = '1';
			update_option('widget_facebook_module',$facebook_module);
			$facebook_module = get_option('widget_facebook_module');
			krsort($facebook_module);
			foreach($facebook_module as $key1=>$val1) {
				$facebook_module_key = $key1;
				if(is_int($facebook_module_key)) {
					break;
				}
			}		
		 /* ---- Default Sidebar Twitter widget setting ----- */
		/*-----------------------------------*/
			$cs_twitter_widget = array();
			$cs_twitter_widget[1] = array(
				"title"		=>	'Twitter',
				"username" 	=>	"envato",
				"numoftweets" => "3",
				);						
			$cs_twitter_widget['_multiwidget'] = '1';
			update_option('widget_cs_twitter_widget',$cs_twitter_widget);
			$cs_twitter_widget = get_option('widget_cs_twitter_widget');
			krsort($cs_twitter_widget);
			foreach($cs_twitter_widget as $key1=>$val1){
				$cs_twitter_widget_key = $key1;
				if(is_int($cs_twitter_widget_key)){
					break;
				}
			}		
		 /* ---- Blog Sidebar Recent Posts --- */
		/*----------------------------*/
			$blog_recent_post = array();
			$blog_recent_post[2] = array(
				"title"		=>	'Latest Posts',
				"select_category" 	=> '',
				"showcount" => '4',
				"thumb" => true
				);					
			$blog_recent_post['_multiwidget'] = '1';
			update_option('widget_recentposts',$blog_recent_post);
			$blog_recent_post = get_option('widget_recentposts');
			krsort($blog_recent_post);
			foreach($blog_recent_post as $key1=>$val1){
				$blog_recent_post_key = $key1;
				if(is_int($blog_recent_post_key)){
					break;
				}
			}		
		 /* ---- Text widget1 ---- */
		/*---------------------------*/
			$text = array();
			$text = get_option('widget_text');
			$text[1] = array(
				'title' => '',
				'text' => '[cs_ads id="516659823"]',
			);						
			$text['_multiwidget'] = '1';
			update_option('widget_text',$text);
			$text = get_option('widget_text');
			krsort($text);
			foreach($text as $key1=>$val1){
				$text_key = $key1;
				if(is_int($text_key)){
					break;
				}
			}
				 /* ---- Text widget2 for testimonial ---- */
		/*---------------------------*/
			$text2 = array();
			$text2 = get_option('widget_text');
			$text2[2] = array(
				'title' => 'Happy Users',
				'text' => '[cs_testimonials column_size="1/1" testimonial_style="simple" cs_testimonial_text_align="left"][testimonial_item testimonial_author="takimata" testimonial_company="dolore" testimonial_img="http://directory.chimpgroup.com/wp-content/uploads/Box-theme-blogs-20.jpg" testimonial_img25="Browse"]What we appreciated most about working with Joe and his team was their ability to cut through the predictable politics of a project, keep everyone happy, and deliver the expected results and then some.[/testimonial_item][testimonial_item testimonial_author="Allard" testimonial_company="Googles" testimonial_img="http://directory.chimpgroup.com/wp-content/uploads/Box-theme-blogs-14.jpg" testimonial_img159="Browse"]When I first installed MT, I had been looking for a php based weblog engine, but could not find one I liked as much as MT. Having now discovered WordPress, I am a happy man, and will not be looking back.[/testimonial_item] [/cs_testimonials]',
			);						
			$text2['_multiwidget'] = '1';
			update_option('widget_text',$text2);
			$text2 = get_option('widget_text');
			krsort($text2);
			foreach($text2 as $key1=>$val1){
				$text2_key = $key1;
				if(is_int($text2_key)){
					break;
				}
			}
				
		 /* ---- Agents Widget Key --- */
		/*----------------------------*/
			$agent_widget = array();
			$agent_widget[1] = array(
				"title"		=>	'Top Ageants',
				'get_username' 	=> '',
					);					
			$agent_widget['_multiwidget'] = '1';
			update_option('widget_cs_agentlist',$agent_widget);
			$agent_widget = get_option('widget_cs_agentlist');
			krsort($agent_widget);
			foreach($agent_widget as $key1=>$val1){
				$agent_widget_key = $key1;
				if(is_int($agent_widget_key)){
					break;
				}
			}			
		 /* ---- Advance Search Key --- */
		/*----------------------------*/
			$advance_search = array();
			$advance_search[1] = array(
				"title"								=> 'Reviews',
				'cs_directory_search_result_page'	=> '',
					);					
			$advance_search['_multiwidget'] = '1';
			update_option('widget_cs_advance_search',$advance_search);
			$advance_search = get_option('widget_cs_advance_search');
			krsort($advance_search);
			foreach($advance_search as $key1=>$val1){
				$advance_search_key = $key1;
				if(is_int($advance_search_key)){
					break;
				}
			}			
		 /* ---- Banners Key --- */
		/*----------------------------*/
			$ads_banner = array();
			$ads_banner[1] = array(
				"title"			=> 'Reviews',
				'banner_style'	=> 'random',
				'banner_code'	=> '',
				'banner_view'	=> 'sidebar_banner',
				'showcount'		=> '1',
					);					
			$ads_banner['_multiwidget'] = '1';
			update_option('widget_cs_ads_banner',$ads_banner);
			$ads_banner = get_option('widget_cs_ads_banner');
			krsort($ads_banner);
			foreach($ads_banner as $key1=>$val1){
				$ads_banner_key = $key1;
				if(is_int($ads_banner_key)){
					break;
				}
			}		
		/* ---- Search widget setting --- */
		/*--------------------------------*/
			$search = array();
			$search[1] = array(
				"title"		=>	'',
			);	
			$search['_multiwidget'] = '1';
			update_option('widget_search',$search);
			$search = get_option('widget_search');
			krsort($search);
			foreach($search as $key1=>$val1){
				$search_key = $key1;
				if(is_int($search_key)){
					break;
				}
			}		
		 /* ------  Blog Sidebar Tags ----- */
		/*--------------------------------*/
			$tag_cloud = array();
	
			$tag_cloud[1] = array(	
				"title"		=>	'TAG CLOUD',
				"taxonomy" => 'post_tag',
			);	
			$tag_cloud['_multiwidget'] = '1';
			update_option('widget_tag_cloud',$tag_cloud);
			$tag_cloud = get_option('widget_tag_cloud');
			krsort($tag_cloud);
			foreach($tag_cloud as $key1=>$val1){
				$tag_cloud_key = $key1;
				if(is_int($tag_cloud_key)){
					break;
				}
			}
		 /* ---- calendar --- */
		/*--------------------------------*/
			$calendar = array();
			$calendar[1] = array(
				"title"		=>	'Caledar',
			);	
			$calendar['_multiwidget'] = '1';
			update_option('widget_calendar',$calendar);
			$calendar = get_option('widget_calendar');
			krsort($calendar);
			foreach($calendar as $key1=>$val1){
				$calendar_key = $key1;
				if(is_int($calendar_key)){
					break;
				}
			}		
		/* ---- Archive--- */
		/*--------------------------------*/
			$blog_archives = array();
			$blog_archives[1] = array(
				"title"		=>	'Archives',
				"dropdown" 	=>	false,
				"count" => false,
					);						
			$blog_archives['_multiwidget'] = '1';
			update_option('widget_archives',$blog_archives);
			$blog_archives = get_option('widget_archives');
			krsort($blog_archives);
			foreach($blog_archives as $key1=>$val1){
				$blog_archives_key = $key1;
				if(is_int($blog_archives_key)){
					break;
				}
			}		
		/* ---- Footer Sidebar Cats --- */
		/*----------------------------*/
			$blog_cats = array();
			$blog_cats[1] = array(
				"title"		=>	'Featured Categories',
				"dropdown" 	=> '',
				"count"		=> '',
				"hierarchical" => ''
					);					
			$blog_cats['_multiwidget'] = '1';
			update_option('widget_categories',$blog_cats);
			$blog_cats = get_option('widget_categories');
			krsort($blog_cats);
			foreach($blog_cats as $key1=>$val1){
				$blog_cats_key = $key1;
				if(is_int($blog_cats_key)){
					break;
				}
			}
		
		/* ---- CS Mailchimp --- */
		/*----------------------------*/
			$mailchimp_widget = array();
			$mailchimp_widget[1] = array(
								"title"			=>	'Subscribe Widget',
								"description"	=> '',
							);					
			$mailchimp_widget['_multiwidget'] = '1';
			update_option('widget_cs_mailchimp',$mailchimp_widget);
			$mailchimp_widget = get_option('widget_cs_mailchimp');
			krsort($mailchimp_widget);
			foreach($mailchimp_widget as $key1=>$val1){
				$mailchimp_widget_key = $key1;
				if(is_int($mailchimp_widget_key)){
					break;
				}
			}
			
			 /* ------  Faqs custom Menu ----- */
		/*--------------------------------*/
			$faqs_cus_menu = array();
			$menu_name = '';
			$faqs_cus_menu_id = '';
			$menu_object = wp_get_nav_menu_object( $menu_name );
			if ( ( $menu_object = wp_get_nav_menu_object( $menu_name ) ) && isset( $menu_object->term_id ) ) {
				$faqs_cus_menu_id = $menu_object->term_id;
			}
			$faqs_cus_menu[1] = array(
				"title"		=>	'Faqs',
				"nav_menu"	=>	$faqs_cus_menu_id,
			);	
			$faqs_cus_menu['_multiwidget'] = '1';
			update_option('widget_nav_menu',$faqs_cus_menu);
			$faqs_cus_menu = get_option('widget_nav_menu');
			krsort($faqs_cus_menu);
			foreach($faqs_cus_menu as $key1=>$val1){
				$faqs_cus_menu_key = $key1;
				if(is_int($faqs_cus_menu_key)){
					break;
				}
			}	
		 /* ------  Features custom Menu ----- */
		/*--------------------------------*/
			$cs_fancy_menu = array();
			$cs_fancy_menu[1] = array(
									"title"    => '',
									"cs_sticky_menu"  => '',
									"cs_menu_name"  => 'Features',
								);     
			$cs_fancy_menu['_multiwidget'] = '1';
			update_option('widget_cs_fancy_menu',$cs_fancy_menu);
			$cs_fancy_menu = get_option('widget_cs_fancy_menu');
			krsort($cs_fancy_menu);
			foreach($cs_fancy_menu as $key1=>$val1){
				$cs_fancy_menu_key = $key1;
				if(is_int($cs_fancy_menu_key)){
					break;
				}
			}
		
		 /* ------  Typography custom Menu ----- */
		/*--------------------------------*/
			$cs_fancy_menu2 = array();
			$cs_fancy_menu2 = get_option('widget_cs_fancy_menu');
			$cs_fancy_menu2[2] = array(
									"title"    => '',
									"cs_sticky_menu"  => '',
									"cs_menu_name"  => 'Typography',
								);     
			$cs_fancy_menu2['_multiwidget'] = '1';
			update_option('widget_cs_fancy_menu',$cs_fancy_menu2);
			$cs_fancy_menu2 = get_option('widget_cs_fancy_menu');
			krsort($cs_fancy_menu2);
			foreach($cs_fancy_menu2 as $key1=>$val1){
				$cs_fancy_menu2_key = $key1;
				if(is_int($cs_fancy_menu2_key)){
					break;
				}
			}
		
		 /* ------  Common Elements custom Menu ----- */
		/*--------------------------------*/
			$cs_fancy_menu3 = array();
			$cs_fancy_menu3 = get_option('widget_cs_fancy_menu');
			$cs_fancy_menu3[3] = array(
									"title"    => '',
									"cs_sticky_menu"  => '',
									"cs_menu_name"  => 'Common Elements',
								);     
			$cs_fancy_menu3['_multiwidget'] = '1';
			update_option('widget_cs_fancy_menu',$cs_fancy_menu3);
			$cs_fancy_menu3 = get_option('widget_cs_fancy_menu');
			krsort($cs_fancy_menu3);
			foreach($cs_fancy_menu3 as $key1=>$val1){
				$cs_fancy_menu3_key = $key1;
				if(is_int($cs_fancy_menu3_key)){
					break;
				}
			}
			
		 /* ------  Media Elements custom Menu ----- */
		/*--------------------------------*/
			$cs_fancy_menu4 = array();
			$cs_fancy_menu4 = get_option('widget_cs_fancy_menu');
			$cs_fancy_menu4[4] = array(
									"title"    => '',
									"cs_sticky_menu"  => '',
									"cs_menu_name"  => 'Media Elements',
								);     
			$cs_fancy_menu4['_multiwidget'] = '1';
			update_option('widget_cs_fancy_menu',$cs_fancy_menu4);
			$cs_fancy_menu4 = get_option('widget_cs_fancy_menu');
			krsort($cs_fancy_menu4);
			foreach($cs_fancy_menu4 as $key1=>$val1){
				$cs_fancy_menu4_key = $key1;
				if(is_int($cs_fancy_menu4_key)){
					break;
				}
			}
			
	/* ----  Add widgets in sidebars  --- */
		$sidebars_widgets['blogs_sidebar'] = array("search-$search_key", "recentposts-$blog_recent_post_key", "tag_cloud-$tag_cloud_key", "calendar-$calendar_key", "archives-$blog_archives_key");
		$sidebars_widgets['contact'] 		= array("cs_ads_banner-$ads_banner_key", "cs_twitter_widget-$cs_twitter_widget_key","facebook_module-$facebook_module_key");
		$sidebars_widgets['home_directory'] = array("cs_ads_banner-$ads_banner_key","cs_twitter_widget-$cs_twitter_widget_key","text-$text2_key",
		"facebook_module-$facebook_module_key");
		$sidebars_widgets['agents'] = array("cs_advance_search-$advance_search_key", "cs_agentlist-$agent_widget_key");
		// new sidebar
		$sidebars_widgets['faqs'] = array("nav_menu-$faqs_cus_menu_key","cs_ads_banner-$ads_banner_key");
		$sidebars_widgets['features'] = array("cs_fancy_menu-$cs_fancy_menu_key");
		$sidebars_widgets['typography'] = array("cs_fancy_menu-$cs_fancy_menu2_key");
		$sidebars_widgets['common_elements'] = array("cs_fancy_menu-$cs_fancy_menu3_key");
		$sidebars_widgets['media_elements'] = array("cs_fancy_menu-$cs_fancy_menu4_key");
		$sidebars_widgets['shop'] = array();
		$sidebars_widgets['footer-widget-1'] = array("contactinfo-$footer_contactinfo_key", "categories-$blog_cats_key", "recentposts-$blog_recent_post_key", "cs_reviews-$footer_reviews_key");		
		update_option('sidebars_widgets',$sidebars_widgets); //save widget informations
	
	}
}