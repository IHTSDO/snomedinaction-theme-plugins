<?php
add_action( 'add_meta_boxes', 'cs_meta_woo_prod_add' );
function cs_meta_woo_prod_add()
{  
	add_meta_box( 'cs_meta_woo_prod', 'Product Layout Options', 'cs_meta_woo_prod', 'product', 'normal', 'low' );  
}
function cs_meta_woo_prod( $post ) {
	global $cs_theme_options,$cs_xmlObject;
	$post_xml = get_post_meta($post->ID, "product", true);
	//$cs_theme_options=get_option('cs_theme_options');
	$cs_builtin_seo_fields =$cs_theme_options['cs_builtin_seo_fields'];
 	if ( $post_xml <> "" ) {
		$cs_xmlObject = new SimpleXMLElement($post_xml);
	}
	?>
    <div class="page-wrap page-opts left" style="overflow:hidden; position:relative; height: 1432px;">
        <div class="option-sec" style="margin-bottom:0;">
            <div class="opt-conts">
                <div class="elementhidden">
                    <div class="tabs vertical">
                        <nav class="admin-navigtion">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active"><a href="#tab-general-settings" data-toggle="tab"><i class="icon-toggle-right"></i><?php _e('General Settings','dir');?> </a></li>
                                <li><a href="#tab-subheader-options" data-toggle="tab"><i class="icon-list-alt"></i><?php _e('Sub Header Options','dir');?>  </a></li>
                                <?php if($cs_builtin_seo_fields == 'on'){?>
                                	<li><a href="#tab-seo-advance-settings" data-toggle="tab"><i class="icon-dribbble"></i> <?php _e('Seo Options','dir');?></a></li>
                                <?php }?>
                          </ul>
                      </nav>
                        <div class="tab-content">
                         <div id="tab-general-settings" class="tab-pane fade active in">
                            <?php cs_sidebar_layout_options();?>
                        </div>
                        <div id="tab-subheader-options" class="tab-pane fade">
                            <?php cs_subheader_element();?>
                        </div>
                       
                       
                       <?php if($cs_builtin_seo_fields == 'on'){?>
                            <div id="tab-seo-advance-settings" class="tab-pane fade">
                                <div class="theme-help">
                                    <h4 style="padding-bottom:0px;"><?php _e('Seo Options','dir');?></h4>
                                    <div class="clear"></div>
                                </div>
                                <?php cs_seo_settitngs_element();?>
                            </div>
                        <?php }?>
                      </div>
                    </div>
                  </div>
                </div>
           <input type="hidden" name="post_woo_meta_form" value="1" />
        </div>
    </div>    
    <?php
}
if ( isset($_POST['post_woo_meta_form']) and $_POST['post_woo_meta_form'] == 1 ) {
	add_action( 'save_post', 'cs_meta_woo_post_save' );
	function cs_meta_woo_post_save( $post_id ) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
			if (empty($_POST["sub_title"])){ $_POST["sub_title"] = "";}
			
			if (empty($_POST["switch_footer_widgets"])){ $_POST["switch_footer_widgets"] = "";}
				$sxe = new SimpleXMLElement("<cs_meta_post></cs_meta_post>");
					$sxe = cs_page_options_save_xml($sxe);
					
		update_post_meta( $post_id, 'product', $sxe->asXML() );
	}
}
?>