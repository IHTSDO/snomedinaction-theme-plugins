<?php
if ( ! function_exists( 'cs_directory_sub_header' ) ) {
	function cs_directory_sub_header($cs_directory_header, $cs_banner, $map_args, $cs_directory_type, $cs_rev_slider_id, $cs_adsense_sh,$cs_directory_map_style){
		global $post,$wpdb,$cs_xmlObject,$cs_theme_options,$directory_title,$cs_subheader_bg_color,$cs_subheader_padding_top,$cs_subheader_padding_bottom;
		$cs_directory_map_style = isset($cs_directory_map_style) ? $cs_directory_map_style : 'style-1';
		if($cs_directory_header <> "blank-header"){
		$has_banner_class  = '';
		$cs_dir_background = '';
		$cs_padding_top    = '';
		$cs_padding_bottom = '';
		$cs_dir_wide_class = 'container';
		if($cs_directory_header == "banner"){
			if($cs_subheader_bg_color <> ''){
				$cs_dir_background = 'background: '.$cs_subheader_bg_color.';';
			}
			if($cs_banner <> ''){
				$has_banner_class = ' has_image';
			}
		}
		else{
			if($cs_subheader_bg_color <> ''){
				$cs_dir_background = 'background:'.$cs_subheader_bg_color.';';
			}
		}
		if($cs_directory_header == "map" || $cs_directory_header == "revolution-slider"){
			$cs_dir_wide_class = 'wide';
		}
		if($cs_subheader_padding_top <> ''){
			$cs_padding_top = ' padding-top: '.$cs_subheader_padding_top.'; ';
		}
		if($cs_subheader_padding_bottom <> ''){
			$cs_padding_bottom = ' padding-bottom: '.$cs_subheader_padding_bottom.'; ';
		}
	 
		?>
        <section style="margin-bottom: 30px; <?php echo cs_allow_special_char($cs_padding_top.$cs_padding_bottom); ?><?php echo cs_allow_special_char($cs_dir_background); ?>" class="page-section<?php echo cs_allow_special_char($has_banner_class); ?>">
            <div class="<?php echo sanitize_html_class($cs_dir_wide_class); ?>">
            <?php
			// Plain Heading Or Banner
            if($cs_directory_header == "plain-heading"){
            ?>
                <div class="element-detail">
                    <div class="element-title"> <h2><?php echo cs_allow_special_char($directory_title); ?></h2> </div>
                    <div class="element-breadcrumb">
						<?php cs_breadcrumbs(); ?>
                    </div>
                </div>
                <div class="element-info">
                    <span class="listing-count"><?php printf(__('%s Listings', 'directory'), cs_directory_type_post_count($cs_directory_type)); ?></span>
                </div>
            <?php
            }
			
			// Plain Heading Or Banner
            else if($cs_directory_header == "banner"){
				if($cs_banner <> ''){
            ?>
                <div class="cs-element-img"><img src="<?php echo cs_allow_special_char($cs_banner); ?>" alt="" /></div>
                <?php
				}
				?>
            <?php
            }
            
			// Adsense
            else if($cs_directory_header == "adsense"){
            ?>
                <div class="element-detail">
                    <div class="element-title"> <h2><?php echo cs_allow_special_char($directory_title); ?></h2> </div>
                    <div class="element-breadcrumb">
						<?php cs_breadcrumbs(); ?>
                    </div>
                </div>
                <?php
				if($cs_adsense_sh){
				?>
                <div class="element-info">
                    <div class="listing-count"><?php echo do_shortcode('[cs_ads id="'.$cs_adsense_sh.'"]');?></div>
                </div>
            <?php
				}
            }
			
			// Revolution Slider
            else if($cs_directory_header == "revolution-slider"){
            ?>
                <div class="cs-banner"> <?php echo do_shortcode('[rev_slider '.$cs_rev_slider_id.']');?> </div>
            <?php
            }
			
			// Map 
            else if($cs_directory_header == "map"){
				cs_map_view( $map_args,$cs_directory_map_style );
            }
			
			// Blank
			else{
				
			}
            ?>
            </div>
        </section>
        <?php
		}
	}
}