<?php
	global $post, $current_user,$cs_theme_options;

	if(isset($_GET['uid']) && $_GET['uid'] <> ''){
		 $uid = absint($_GET['uid']);
	} else {
		$uid= $current_user->ID;
	}
	
	$directory_pagination		= "Show Pagination";
	$cs_directory_per_page		= get_option('posts_per_page');
	$currency_sign		= $cs_theme_options['currency_sign'];
 	$cs_page_id					=  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
	$cs_directory_ads_allow		= $cs_theme_options['cs_directory_ads_allow'];
	?>
    <div class="cs-section-title cs-fav-clearall">
    <h2>
    <?php
    	if ( is_user_logged_in() &&  $current_user->ID == $uid ) { 
   	 		_e('My Projects','directory');
    	} else {
			_e('User Projects','directory');
    	}
    ?>
    </h2>
    <?php 
    $ad_status = isset($_GET['ad_status']) ? $_GET['ad_status'] : '';
    if( isset( $cs_directory_ads_allow ) && $cs_directory_ads_allow == 'on' && is_user_logged_in() &&  $current_user->ID == $uid ){
    $add_directory_link = add_query_arg( array('action'=>'add-directory','uid'=> urlencode( $uid ) ), esc_url( get_permalink( $cs_page_id ) ) );
    ?>
    <div class="profile-title"><a href="<?php echo esc_url($add_directory_link);?>" class="btn-style1"><i class="icon-database"></i><?php _e('Create New Project','directory');?></a></div>
   
    <?php }?>
    </div>
    <div class="has-border">
    <div class="main-content-in">
    <?php

    $ad_status_array = array('publish', 'private', 'pending');
	if (empty($_GET['page_id_all'])) $_GET['page_id_all'] = 1;
    $argsss = array(
    'posts_per_page'			=> "-1",
    'post_type'					=> 'directory',
    'post_status'				=> $ad_status_array,
    'meta_query' => array(
        array(
            'key'     => 'directory_organizer',
            'value'   => $uid,
            'compare' => '=',
        ),
    ),
    'orderby'					=> 'ID',
    'order'						=> 'DESC',
    );
    
	$custom_query_count = new WP_Query($argsss);
    $count_post = $custom_query_count->post_count;        
    
  
    // expipred , deactivated,active,pending
    $args = array(
    'posts_per_page'			=> "$cs_directory_per_page",
    'post_type'					=> 'directory',
    'post_status'				=> $ad_status_array,
    'meta_query' => array(
        array(
            'key'     => 'directory_organizer',
            'value'   => $uid,
            'compare' => '=',
        ),
    ),
	'paged' => $_GET['page_id_all'],
    'orderby'					=> 'ID',
    'order'						=> 'DESC',
    );
    
    $custom_query = new WP_Query($args);
    if ( $custom_query->have_posts() <> "" ) {
    while ( $custom_query->have_posts() ): $custom_query->the_post();
    $cs_directory = get_post_meta($post->ID, "cs_directory_meta", true);
    $directory_type_select = get_post_meta($post->ID, "directory_type_select", true);
    if ( $cs_directory <> "" ) {
    $cs_xmlObject = new SimpleXMLElement($cs_directory);
    }
    $reviews_args = array(
    'posts_per_page'			=> "-1",
    'post_type'					=> 'cs-reviews',
    'post_status'				=> 'publish',
    'meta_key'					=> 'cs_reviews_directory',
    'meta_value'				=> $post->ID,
    'meta_compare'				=> "=",
    'orderby'					=> 'meta_value',
    'order'						=> 'DESC',
    );
    $reviews_query 		= new WP_Query($reviews_args);
    $reviews_count 		= $reviews_query->post_count;
    if(empty($reviews_count) || $reviews_count == ''){$reviews_count = 0;}
    $directory_rating = get_post_meta($post->ID, 'cs_directory_review_rating', true);
    if(isset($directory_rating)){$directory_rating = $directory_rating*20;} else {$directory_rating = 0;}
    $likes_counter 					= cs_get_directory_likes();
    $pakage_expire_date 			= get_post_meta($post->ID, "dir_pkg_expire_date", true);
    $cs_directory_pkg_names 		= get_post_meta( $post->ID, "cs_directory_pkg_names", true);
    $_pakage_transaction_meta 		= get_post_meta($post->ID, "dir_pakage_transaction_meta", true);
    $currency_sign 					= isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';
    $dynamic_post_location_address  = get_post_meta($post->ID,'dynamic_post_location_address',true);
    $dynamic_post_sale_newprice 	= get_post_meta($post->ID, "dynamic_post_sale_newprice", true);
    $cs_directory_featured 			= get_post_meta($post->ID, "directory_featured", true);
    $dir_payment_date 				= get_post_meta($post->ID, "dir_payment_date", true);
    $dir_featured_till 				= get_post_meta($post->ID, "dir_featured_till", true);
    
    if($dir_payment_date == '')
    $dir_payment_date = get_the_date();
    
    $isFeatured	= false;
    if ( isset( $dir_featured_till ) && $dir_featured_till !='' ){
    $current_date = date("Y-m-d H:i:s");
    if( strtotime( $dir_featured_till ) > strtotime( $current_date ) ) {
        $isFeatured	= true;
    }
    }	
    
    ?>
    <article class="ads-in post-<?php echo intval($post->ID); ?>">
    
    <?php 
    $cs_noimage 	= '';
    $width 			= 370;
    $height 		= 280;
    $image_id 		= get_post_thumbnail_id( $post->ID );
    $image_url 		= cs_get_post_img_src($post->ID, $width, $height);
    if($image_url <> ''){
    ?>
    <figure><a href="<?php esc_url(the_permalink());?>"><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo get_the_title();?>" /></a></figure>
    <?php
    }
    ?>
    <h4><a href="<?php esc_url(the_permalink());?>"><?php echo get_the_title();?></a></h4>
    <!--<div class="save-like-btns">
    <a class="saved-btn"><i class="icon-star-o"></i><?php echo absint($likes_counter);?> </a>
    <?php
    if($reviews_count > 0){
        echo '<a href="'.esc_url(get_permalink()).'#cs_reviews" class="reviews-btn"><i class="icon-thumbs-up"></i>'.intval($reviews_count).'</a>';
    }
    else{
        echo '<a class="reviews-btn"><i class="icon-thumbs-up"></i>'.intval($reviews_count).'</a>';
    }
    ?>
    </div>-->
    <ul class="dr_postoption">
	 <!--<li><div class="cs-rating"><span style="width:<?php echo absint($directory_rating);?>%" class="rating-box"></span></div></li>-->
    <li>
        <?php 
        if( isset( $isFeatured ) && $isFeatured == true ) {
            if($cs_directory_featured == 'yes' || $cs_directory_featured == 'on'){
            ?>
                <span class="add-featured"><?php _e('Urgent','directory');?></span>
            <?php 
            }
        }
        ?>
    </li>
    <li><?php _e('Start date','directory');?> <time datetime="<?php echo esc_attr( date_i18n( get_option( 'Y-m-d' ),strtotime( $dir_payment_date ) ) );?>"><?php echo esc_attr( date_i18n( get_option( 'date_format' ),strtotime( $dir_payment_date ) ) ); ?></time> 
    </li>
    </ul>
    
    <div class="bottom-sec">
    <?php 
    //echo '<span class="expiry-date">';
    if( is_user_logged_in() && $current_user->ID == $uid && 1 == 0){
    
    if(isset( $cs_directory_pkg_names ) && isset( $pakage_expire_date ) && $pakage_expire_date <> ''){
        $current_date = date("Y-m-d H:i:s");
        if( strtotime( $pakage_expire_date ) > strtotime( $current_date )){
            _e('Expire Date:','directory');
            echo date_i18n( 'd F, Y H:i', strtotime( $pakage_expire_date ) );
        } elseif($pakage_expire_date == 'unlimited'){
			_e('Expire Date:','directory'); echo cs_allow_special_char($pakage_expire_date);
		}else {
            $edit_directory_link = add_query_arg( array('action'=>'add-directory','uid'=> urlencode($uid),'dir-pkg'=>'renew','directory_id'=> urlencode($post->ID)), esc_url(get_permalink($cs_page_id)) );
            echo '<a href="'.esc_url($edit_directory_link).'" class="edit-btn"><i class="icon-star-o"></i>'.__('Renew Package','directory').'</a>';
        }
    } else if(isset($cs_directory_pkg_names) && empty($cs_directory_pkg_names)){
        $edit_directory_link = add_query_arg( array('action'=>'add-directory','uid'=> urlencode( $uid ),'dir-pkg'=>'renew','directory_id'=>  urlencode( $post->ID ) ), esc_url( get_permalink($cs_page_id) ) );
        echo '<a href="'.esc_url($edit_directory_link).'" class="edit-btn"><i class="icon-star-o"></i>'.__('Purchase Package','directory').'</a>';
    } else {
        $edit_directory_link = add_query_arg( array('action'=>'add-directory','uid'=>  urlencode( $uid ),'dir-pkg'=>'renew','directory_id'=>  urlencode( $post->ID ) ),  esc_url( get_permalink($cs_page_id) ) );
        echo '<a href="'.esc_url($edit_directory_link).'" class="edit-btn"><i class="icon-star-o"></i>'.__('Purchase Package','directory').'</a>';
    }
    }
    
    //echo '</span>';
    
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        jQuery('.tolbtn').tooltip('hide');
        jQuery('.tolbtn').popover('hide');
    });
    </script>
    <ul>
    <?php
    $dir_pkg = get_post_meta($post->ID, "_pakage_meta", true);
    if ( isset($dir_pkg) && is_array($dir_pkg) && count($dir_pkg)>0  && $current_user->ID == $uid ) {
    ?>
    <!--<li><a href="#" class="toggle reviews-btn" id="toggle-package<?php echo absint($post->ID);?>"><i class="icon-gear"></i><?php _e('Package', 'directory');?></a></li>-->
    <?php 
    }
    if ( isset($_pakage_transaction_meta) && is_array($_pakage_transaction_meta) && count($_pakage_transaction_meta)>0  && $current_user->ID == $uid ) {
    ?>
        <li><a href="#" class="toggle reviews-btn" id="toggle<?php echo absint($post->ID);?>"><i class="icon-star-o"></i><?php _e('Transactions','directory');?></a></li>
    <?php
    }
    if( is_user_logged_in() && $current_user->ID == $uid ){
        $post_status = get_post_status($post->ID);
        echo '<li><a><i class="icon-eye3"></i>';
            $current_date = date("Y-m-d H:i:s");
            if(isset( $pakage_expire_date ) and $pakage_expire_date <> 'unlimited' and strtotime( $pakage_expire_date ) < strtotime( $current_date ) and $post_status != 'pending'){
                _e('Approved','directory');
                //	echo date_i18n( 'd F, Y H:i', strtotime( $pakage_expire_date ) );
            }elseif($post_status == 'pending'){
                _e('Pending','directory');
            }elseif($post_status == 'private'){
                _e('Deactivated','directory');
            }else {
                _e('Active','directory');
            }
        echo '</a></li>';
        if(isset( $pakage_expire_date ) and strtotime( $pakage_expire_date ) > strtotime( $current_date ) and $post_status != 'pending'){
        ?>
        
        <li>
        <a onclick="javascript:cs_directory_post_status('<?php echo esc_js(admin_url('admin-ajax.php'));?>','<?php echo esc_js($post->ID);?>')" class="deactive-btn deactive-<?php echo intval($post->ID); ?>"><i class="icon-eye3"></i>
        <?php
            $post_status = get_post_status($post->ID);
            if($post_status == 'private'){
                _e('Active','directory');
            } else {
                _e('de active','directory');
            }
        ?>
        </a></li>
        <?php
        }
        if( $ad_status <> 'expired' ){
        ?>
    <?php
    }
    
    $isEditAllow = $cs_theme_options['cs_directory_editing'];
    if ( isset( $isEditAllow ) && $isEditAllow == 'on' && $current_user->ID == $uid ) {
        $edit_directory_link = add_query_arg( array('action'=>'add-directory','uid'=> urlencode( $uid ),'directory_id'=>  urlencode( $post->ID )),  esc_url( get_permalink($cs_page_id)) );
        ?>
        <li><a href="<?php echo esc_url($edit_directory_link);?>" class="edit-btn"><i class="icon-edit3"></i><?php _e('Edit','directory');?></a></li>
        <?php
    }
    ?>
    <li><button data-toggle="tooltip" data-placement="top" title="<?php _e('Remove','directory');?>" onclick="javascript:cs_delete_directory_post('<?php echo esc_js(admin_url('admin-ajax.php'));?>','<?php echo esc_js($post->ID);?>')" type="button" class="tolbtn close close-<?php echo intval($post->ID); ?>" data-dismiss="alert"><em class="icon-trash-o"></em></button></li>
    <?php }?>
    </ul>
    </div>
    <script type="text/javascript">
        jQuery('#toggle<?php echo esc_js($post->ID);?>').click(function($) {
            jQuery('.toggle-div<?php echo esc_js($post->ID);?>').slideToggle();
            jQuery('#toggle<?php echo esc_js($post->ID);?>').toggleClass('active');
            return false;
        });
        jQuery('#toggle-package<?php echo esc_js($post->ID);?>').click(function($) {
            jQuery('.toggle-package-div<?php echo esc_js($post->ID);?>').slideToggle();
            jQuery('#toggle-package<?php echo esc_js($post->ID);?>').toggleClass('active');
            return false;
        });
      </script>
        <?php
        if ( isset($dir_pkg) && is_array($dir_pkg) && count($dir_pkg)>0  && $current_user->ID == $uid ) {
        ?>
            <div class="toggle-sec">
                <div class="toggle-package-div<?php echo absint($post->ID);?>" style="display:none;">
                    <div class="directory-package">
                        <?php
                            $data	= get_post_meta((int)$post->ID, 'dir_pakage_trans_subsription_meta', true);
                            $pakage_expire_date = isset($pakage_expire_date) && $pakage_expire_date <> '' ? $pakage_expire_date : '';
                            cs_package_info($post->ID,$dir_pkg,$pakage_expire_date);
                            
                        ?>
                    </div>
                </div>
             </div>
             <?php
            }
            if ( isset($_pakage_transaction_meta) && is_array($_pakage_transaction_meta) && count($_pakage_transaction_meta)>0  && $current_user->ID == $uid ) {
            ?>
			<div class="toggle-sec">
				<div class="toggle-div<?php echo absint($post->ID);?>" style="display:none;">
                    <div class="directory-donation">
                        <table>
                            <thead>
                                <tr>
                                    <th class="odd">#</th>
                                    <th class="even"><?php _e('Name','directory'); ?></th>
                                    <th class="odd"><?php _e('Date','directory'); ?></th>
                                    <th class="even"><?php _e('Email','directory'); ?></th>
                                    <th class="odd"><?php _e('Transaction id','directory'); ?></th>
                                    <th class="even"><?php _e('Amount','directory'); ?></th>
                                    <th class="odd"><?php _e('IPN Track id','directory'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 0;
                                foreach ($_pakage_transaction_meta as $transct ){
                                    $counter++;
                                    $address_name = $transct['address_name'];
                                    $payment_date = $transct['payment_date'];
                                    $txn_id = $transct['txn_id'];
                                    $payer_email = $transct['payer_email'];
                                    $payment_gross = $transct['payment_gross'];
									$class = ($counter and $counter%2 == 0) ? 'even' : 'odd';
                                   ?>
                                    <tr class="<?php echo sanitize_html_class($class);?>">
                                        <td><?php echo absint($counter);?></td>
                                        <td><?php echo esc_attr($address_name);?></td>
                                        <td><?php echo esc_attr($payment_date);?></td>
                                        <td><?php echo esc_attr($payer_email);?></td>
                                        <td><?php echo esc_attr($txn_id);?></td>
                                        <td><?php echo esc_attr($currency_sign.$payment_gross);?></td>
                                        <td><?php if(isset($transct['ipn_track_id']))echo esc_attr($transct['ipn_track_id']);?></td>
                                    </tr>
                                 <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
        <?php
        }
    ?>

    </article>
    <?php
    endwhile;
    wp_reset_query();
    $qrystr = '';
    if ( $count_post > $cs_directory_per_page ) {
    if ( isset($_GET['page_id']) ) { $qrystr .= "&page_id=".$cs_page_id; }
    if ( isset($_GET['action']) ) $qrystr .= "&action=".$_GET['action'];
    if ( isset($uid) ) $qrystr .= "&uid=".$uid;
		echo cs_pagination($count_post, $cs_directory_per_page, $qrystr);
    }
    ?>
    <script type="text/javascript">
		jQuery(document).ready(function($) {
			cs_progress_bar();
		});
    </script>
    <?php
    } else {
		echo '<h4>'.__('No Result Found','directory').'</h4>';
    }
    ?>
    </div>
    </div>
