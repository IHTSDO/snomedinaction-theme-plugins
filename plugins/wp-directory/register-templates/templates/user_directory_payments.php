<?php
global $post, $current_user, $cs_theme_options;
if(isset($_GET['uid']) && $_GET['uid'] <> ''){
	 $uid = absint($_GET['uid']);
} else {
	$uid= $current_user->ID;
}
$currency_sign = $cs_theme_options['currency_sign'];
$cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
?>
<div class="cs-section-title">
    <h2><?php _e('Payments','directory');?></h2>
</div>
<?php
	$argsss = array(
				'posts_per_page'			=> "-1",
				'post_type'					=> 'directory',
				'post_status'				=> array('publish', 'private'),
				'meta_key'					=> 'directory_organizer',
				'meta_value'				=> $uid,
				'meta_compare'				=> "=",
				'orderby'					=> 'ID',
				'order'						=> 'DESC',
			);
	 $custom_query_count = new WP_Query($argsss);
	 $count_post = $custom_query_count->post_count;
	 $args = array(
				'posts_per_page'			=> get_option('posts_per_page'),
				'paged'						=> $_GET['page_id_all'],
				'post_type'					=> 'directory',
				'post_status'				=> array('publish', 'private'),
				'meta_key'					=> 'directory_organizer',
				'meta_value'				=> $uid,
				'meta_compare'				=> "=",
				'orderby'					=> 'ID',
				'order'						=> 'DESC',
			);
	 $custom_query = new WP_Query($args);
	 if ( $custom_query->have_posts() <> "" ) {
		 $cs_dir_trans = get_option('cs_directory_transaction_meta', true);
		 ?>
		 <div class="directory-package">
			<table>
				<thead>
					<tr>
						<th>#</th>
						<th><?php _e('Name','directory');?></th>
						<th><?php _e('Package','directory');?></th>
						<th><?php _e('Duration in days','directory');?></th>
						<th><?php _e('Transection Detail','directory');?></th>
					</tr>
				</thead>
				<tbody>
		  <?php
		  $subs_counter = 1;
		  while ( $custom_query->have_posts() ): $custom_query->the_post();
		  if (isset($post->ID) &&  has_post_thumbnail( $post->ID ) ) {
			$thumb_id = get_post_thumbnail_id($post->ID);
		  } else {
				$thumb_id = '';  
		  }
		  $_pakage_transaction_meta	= get_post_meta((int)$post->ID, "dir_pakage_transaction_meta", true);
		  $pakage_subs_meta			= get_post_meta((int)$post->ID, "dir_pakage_trans_subsription_meta", true);
		  $package_meta 			= get_post_meta((int)$post->ID, "_pakage_meta", true);
		  $dir_payment_date 		= get_post_meta((int)$post->ID, "dir_payment_date", true);
		  $object	= new CS_PAYMENTS();
		  
		  if ( isset($package_meta) && is_array($package_meta) && count($package_meta)>0  && $current_user->ID == $uid ) {
				$counter = 0;
				$pakage_expire_date = get_post_meta($post->ID, "dir_pkg_expire_date", true);
				$package_id = $package_meta['package_id'];
				$package_title = $package_meta['package_title'];
				$package_price = $package_meta['package_price'];
				$package_duration = $package_meta['package_duration'];
				$transection_status = isset( $package_meta['transection_status'] ) ? $package_meta['transection_status'] : '';
				$transection_id 	= isset( $package_meta['transection_id'] ) ? $package_meta['transection_id'] : '';
				$payment_gateway 	= isset( $package_meta['payment_gateway'] ) ? $gateways[strtoupper($package_meta['payment_gateway'])] : '';
				$trans_data	= get_post_meta((int)$post->ID, 'dir_pakage_transaction_meta', false);
				
				$counter++;
				?>
					<tr>
						<td><?php echo absint($subs_counter);?></td>
						<td><a href="<?php esc_url(the_permalink());?>"><?php echo get_the_title();?></a></td>
						<td><?php echo esc_attr($package_title);?></td>
						<td><?php echo esc_attr($package_duration);?></td>
						<td>
							<?php
							if( is_array($trans_data) && sizeof($trans_data) > 0 ) {
							?>
							<a href="javascript:;" id="cs-trans-detail-<?php echo absint($subs_counter);?>" data-id="<?php echo absint($subs_counter);?>" class="cs-trans-detail"><?php _e('Detail','directory'); ?></a>
							<?php
							}
							?>
						</td>
					</tr>
					<?php
					if( is_array($trans_data) && sizeof($trans_data) > 0 ) {
					?>
					<tr id="cs-con-detail-<?php echo absint($subs_counter);?>">
						<td colspan="5" class="cs-payment-his">
							<table>
								<thead>
									<tr>
										<th class="odd"><?php _e('Transaction id','directory');?></th>
                                        <th class="even"><?php _e('Payment Gateway','directory');?></th>
										<th class="even"><?php _e('Name','directory');?></th>
										<th class="odd"><?php _e('Date','directory');?></th>
										<th class="even"><?php _e('Email','directory');?></th>
										<th class="odd"><?php _e('Amount','directory');?></th>
										<th class="odd"><?php _e('Payment Status','directory');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$trans_counter = 0;
									if( isset($trans_data[0]) ) {
										$trans_data[0] = array_reverse($trans_data[0], true);
										foreach($trans_data[0] as $trans => $trans_value) {
											$class			= ($counter and $counter%2 == 0) ? 'even' : 'odd';
											$first_name		= isset($trans_value['first_name']) ? $trans_value['first_name'] : '';
											$last_name		= isset($trans_value['last_name']) ? $trans_value['last_name'] : '';
											$payment_date	= isset($trans_value['payment_date']) ? $trans_value['payment_date'] : '';
											$payer_email	= isset($trans_value['payer_email']) ? $trans_value['payer_email'] : '';
											$payment_gross	= isset($trans_value['payment_gross']) ? $trans_value['payment_gross'] : '';
											$full_address	= isset($trans_value['full_address']) ? $trans_value['full_address'] : '';
											$txn_id			= isset($trans_value['txn_id']) ? $trans_value['txn_id'] : '';
											$trans_status	= isset($trans_value['transaction_status']) ? $trans_value['transaction_status'] : '';
											$payment_method	= isset($trans_value['payment_method']) ? $gateways[strtoupper($trans_value['payment_method'])] : '';
										?>
											<tr class="<?php echo sanitize_html_class($class);?>">
												<td><?php echo esc_attr($txn_id); ?></td>
                                                <td><?php echo esc_attr($payment_method); ?></td>
												<td><?php echo esc_attr($first_name) . ' ' . esc_attr($last_name); ?></td>
												<td><?php echo date_i18n('F j, Y', strtotime($payment_date)); ?></td>
												<td><?php echo sanitize_email($payer_email); ?></td>
												<td><?php echo esc_attr($currency_sign.$payment_gross); ?></td>
												<td><?php echo esc_attr($trans_status); ?></td>
											</tr>
										<?php
											$trans_counter++;
										}
									}
									?>
								</tbody>
							</table>
						</td>
					</tr>
					<?php
					}
				$subs_counter++;
				}
				
			endwhile;
			?>
			</tbody>
		</table>
		</div>
			<?php
			 $qrystr = '';
			 if ( $count_post > get_option('posts_per_page')) {
					if ( isset($_GET['page_id']) ) { $qrystr .= "&page_id=".$cs_page_id;}
					if ( isset($_GET['action']) ) $qrystr .= "&action=".$_GET['action'];
					if ( isset($_GET['post_type']) ) $qrystr .= "&post_type=".$_GET['post_type'];
					if ( isset($_GET['page']) ) $qrystr .= "&page=".$_GET['page'];
					//if ( isset($uid) ) $qrystr .= "&uid=".$uid;
					echo cs_pagination($count_post, get_option('posts_per_page'), $qrystr);
			 }
		} else {
			echo '<h4>'.__('No Result Found','directory').'</h4>';
		}
		?>

