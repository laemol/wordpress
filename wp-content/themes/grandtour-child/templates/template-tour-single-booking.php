<div class="sidebar_wrapper">
    	
 <div class="sidebar_top"></div>

 <div class="sidebar">
 
 	<div class="content">
 		
		 <?php

         // Get Product data
         $product = array_shift(apiGetRequest('products/' . $_GET['pid']));

            if (!empty($product['prices'])) {
                $tour_discount_price = get_post_meta($post->ID, 'tour_discount_price', true); ?>
 		<div class="single_tour_header_price">
 			<div class="single_tour_price">
 				<?php
                if ($product) {
                    ?>
					 <span class="normal_price">
					 &euro;  <?php echo $product['prices'][0]['originalPrice']; ?>
					 </span>
					 &euro; <?php echo $product['prices'][0]['currentPrice']; ?>
				 <?php
                } else {
                    ?>
					 &euro; <?php echo $product['prices'][0]['currentPrice']; ?>
				 <?php
                } ?>
 			</div>
 			<div class="single_tour_per_person">
 				<?php esc_html_e('Per Person', 'grandtour'); ?>
 			</div>
 		</div>
 		<?php
            }
        ?>

 		<?php
            //Get tour booking method
            $tour_booking_method = get_post_meta($post->ID, 'tour_booking_method', true);
        ?>
 		<div class="single_tour_booking_wrapper themeborder <?php echo esc_attr($tour_booking_method); ?>">
 			<?php
                    //Check how to display booking form
                    switch ($tour_booking_method) {
                     case 'contact_form7':
                     default:
                         $tour_booking_contactform7 = get_post_meta($post->ID, 'tour_booking_contactform7', true);
                        echo do_shortcode('[contact-form-7 id="' . esc_attr($tour_booking_contactform7) . '"]');
                     break;

                     case 'woocommerce_product':
                         $tour_booking_product = get_post_meta($post->ID, 'tour_booking_product', true);
             ?>
     				<div class="single_tour_booking_woocommerce_wrapper">
 	    				<?php
                            if (class_exists('Woocommerce')) {
                                $obj_product = wc_get_product($tour_booking_product);

                                if ($obj_product->is_type('simple')) {
                                    ?>
 		    			<?php esc_html_e('Click button below to book this tour and make a payment.', 'grandtour'); ?>
 		    			<button data-product="<?php echo esc_attr($tour_booking_product); ?>" data-processing="<?php esc_html_e('Please wait...', 'grandtour'); ?>" data-url="<?php echo admin_url('admin-ajax.php') . esc_attr('?action=grandtour_add_to_cart&product_id=' . $tour_booking_product); ?>" class="single_tour_add_to_cart button"><?php esc_html_e('Book This Tour', 'grandtour'); ?></button>
 		    			<?php
                                } //end simple product
                                elseif ($obj_product->is_type('variable')) {
                                    ?>
 				    	<form id="tour_variable_form">
 				    	<?php
                                    //Get all product variation
                                    $args = [
                                        'post_type' => 'product_variation',
                                        'post_status' => ['private', 'publish'],
                                        'numberposts' => -1,
                                        'orderby' => 'menu_order',
                                        'order' => 'ASC',
                                        'post_parent' => $tour_booking_product
                                    ];
                                    $variations = get_posts($args);

                                    foreach ($variations as $variation) {
                                        //Get variation ID
                                        $variation_ID = $variation->ID;

                                        //Get variations meta
                                        $product_variation = new WC_Product_Variation($variation_ID);

                                        //Get variation title & price
                                        $variation_price = $product_variation->get_price();
                                        $variation_price_html = $product_variation->get_price_html();

                                        $variation_title = wc_get_formatted_variation($product_variation->get_variation_attributes(), true, false); ?>
 							
 							<div class="tour_product_variable_wrapper">
 								<div class="tour_product_variable_title">
 									<?php echo esc_html($variation_title); ?>
 								</div>
 								<div class="tour_product_variable_qty">
 									<input type="number" name="<?php echo esc_attr($variation_ID); ?>" id="<?php echo esc_attr($variation_ID); ?>" value="0" min="0"/>
 								</div>
 								&nbsp;x&nbsp;
 								<div id="tour_product_variable_price_<?php echo esc_attr($variation_ID); ?>" class="tour_product_variable_price" data-price="<?php echo esc_attr($variation_price); ?>">
 									<?php echo $variation_price_html; ?>
 								</div>
 							</div>
 							
 							<?php
                                    } ?>
 				    	</form>
 							
 						<button data-product="<?php echo esc_attr($tour_booking_product); ?>" data-processing="<?php esc_html_e('Please wait...', 'grandtour'); ?>" data-url="<?php echo admin_url('admin-ajax.php') . esc_attr('?action=grandtour_add_to_cart&product_id=' . $tour_booking_product); ?>" class="single_tour_add_to_cart product_variable button"><?php esc_html_e('Book This Tour', 'grandtour'); ?></button>
 							
 				    		<?php
                                } //end variable product
                                else {
                                    ?>
 				    	<?php esc_html_e('Click button below to book this tour and make a payment.', 'grandtour'); ?>
 		    			<button data-product="<?php echo esc_attr($tour_booking_product); ?>" data-processing="<?php esc_html_e('Please wait...', 'grandtour'); ?>" data-url="<?php echo admin_url('admin-ajax.php') . esc_attr('?action=grandtour_add_to_cart&product_id=' . $tour_booking_product); ?>" class="single_tour_add_to_cart button"><?php esc_html_e('Book This Tour', 'grandtour'); ?></button>
 				    	<?php
                                }
                            }
                        ?>
     				</div>
     		<?php
                     break;

                     case 'external':
                         $tour_booking_url = get_post_meta($post->ID, 'tour_booking_url', true);
             ?>
     				<div class="single_tour_booking_external_wrapper">
     					<?php esc_html_e('Click button below to begin booking', 'grandtour'); ?>&nbsp;<?php the_title(); ?>
     					<a href="<?php echo esc_url($tour_booking_url); ?>" class="button" target="_blank"><?php esc_html_e('Book This Tour', 'grandtour'); ?></a>
     				</div>
     		<?php
                     break;

                     case 'html':
                         $tour_booking_html = get_post_meta($post->ID, 'tour_booking_html', true);
             ?>
     				<div class="single_tour_html_wrapper">
     					<?php echo grandtour_apply_content($tour_booking_html); ?>
     				</div>
     		<?php
                     break;
                    }

                    $tour_view_count = grandtour_get_post_view($post->ID, true);
                    if ($tour_view_count > 0) {
                        ?>
     		<div class="single_tour_view_wrapper themeborder">
     			<div class="single_tour_view_desc">
 	    			<?php esc_html_e("This tour's been viewed", 'grandtour'); ?>&nbsp;<?php echo number_format($tour_view_count); ?>&nbsp;<?php esc_html_e('times in the past week', 'grandtour'); ?>
     			</div>
     			
     			<div class="single_tour_view_icon ti-alarm-clock"></div>
     		</div>
     		<br class="clear"/>
     		<?php
                    }
                ?>
 		</div>
 		
 		<?php
            //Check if enable tour sharing
            $tg_tour_single_share = kirki_get_option('tg_tour_single_share');

            if (!empty($tg_tour_single_share)) {
                ?>
 		<a id="single_tour_share_button" href="javascript:;" class="button ghost themeborder"><span class="ti-email"></span><?php pll_e('Share'); ?></a>
 		<?php
            }
        ?>
 		
 		<?php 
            if (is_active_sidebar('single-tour-sidebar')) {
                ?>
    	    	<ul class="sidebar_widget">
    	    	<?php dynamic_sidebar('single-tour-sidebar'); ?>
    	    	</ul>
    	    <?php
            } ?>
 		
 		<?php 
            if (function_exists('users_online') && !isset($_COOKIE['grandtour_users_online'])): ?>
 		   <div class="single_tour_users_online_wrapper themeborder">
 			   <div class="single_tour_users_online_icon">
 				   	<span class="ti-info-alt"></span>
 			   </div>
 			   <div class="single_tour_users_online_content">
 			   		<?php users_online(); ?>
 			   </div>
 		   </div>
 		<?php endif; ?>
 	
 	</div>

 </div>
 <br class="clear"/>

 <div class="sidebar_bottom"></div>
</div>