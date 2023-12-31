<?php
/* Child theme generated with WPS Child Theme Generator */

if (!function_exists('b7ectg_theme_enqueue_styles')) {
	add_action('wp_enqueue_scripts', 'b7ectg_theme_enqueue_styles');

	function b7ectg_theme_enqueue_styles()
	{
		wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
		wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
	}
}


//add_action('init', 'enrolls_students');
//Create a MPDF START
function enrolls_students()
{

	include_once(get_stylesheet_directory() . '/vendor/autoload.php');
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	$config = [
		'mode' => '+aCJK',
		// "allowCJKoverflow" => true, 
		"autoScriptToLang" => true,
		// "allow_charset_conversion" => false,
		"autoLangToFont" => true,
	];
	$mpdf = new \Mpdf\Mpdf($config);
	//$data = array();
	$pdfcontent = '<h1>こんにちは元気ですか</h1>
		<h2>Employee Details</h2>
		<table autosize="1">
		<tr>
		<?php echo "Hii"?>
		<td style="width: 33%"><strong>NAME</strong></td>
		<td style="width: 36%"><strong>ADDRESS</strong></td>
		<td style="width: 30%"><strong>PHONE</strong></td>
		</tr>
		</table>';

	$mpdf->WriteHTML($pdfcontent);
	$mpdf->SetDisplayMode('fullpage');
	//$mpdf->list_indent_first_level = 0; 
	//$mpdf->autoScriptToLang  = true;
	$mpdf->autoLangToFont  = true;

	//call watermark content and image
	//$mpdf->SetWatermarkText('etutorialspoint');
	//$mpdf->showWatermarkText = true;
	//$mpdf->watermarkTextAlpha = 0.1;

	//output in browser
	//$mpdf->Output();
	$mpdf->Output('MyPDF.pdf', 'D');
	die;
}
//Create a MPDF END

/**add_action('init', 'enrolls_studentss');
function enrolls_studentss()
{
	$html = "<table class='head container'>
	<tr>
		<td style='width:100%;text-align:center;' class='header'>
			
			
				<?php echo 'here'; ?>
				<h3 style='font-size: 18pt;'>見積書</h3>
		
				
			
		</td>
	</tr>
</table>";
	echo $html;
}**/

add_action('woocommerce_after_single_product_summary', 'wp_kama_woocommerce_after_single_product_summary_action');
function wp_kama_woocommerce_after_single_product_summary_action()
{
	global $woocommerce, $product, $post;
	$product_id = $product->get_id();
	$checkbox_value = get_field('variation_table_onoff');
	if ($checkbox_value != 'off') {


		if ($product->is_type('variable')) {
?>
			<style>
				div.woocommerce-variation-price,
				table.variations {
					display: none;
				}
			</style>
			<?php
			$available_variations = $product->get_available_variations();
			$name = $product->get_title();
			$id = $product->get_id();
			$product_id = $id;
			// echo 
			$product_attr = get_post_meta($id, '_product_attributes');
			?><?php
				$tr_count = array();

				$tr_description_count = array();
				$tr_dimensions_count = array();
				$tr_weight_count = array();

				$tr_description_display = array();
				$tr_dimensions_display = array();
				$tr_weight_display = array();

				//echo "<pre>";
				//print_r($available_variations);

				foreach ($available_variations as $key => $value) {

					if ($value['variation_description']) {
						array_push($tr_description_count, +1);
						array_push($tr_description_display, 'yes');
					}

					if ($value['dimensions']['length'] || ['dimensions']['width'] || $value['dimensions']['height']) {
						array_push($tr_weight_count, +1);
						array_push($tr_dimensions_display, 'yes');
					}

					$weight = $value['weight'];
					$weight_result = substr($weight, 0, 6);
					if ($weight_result  != "field_") {
						array_push($tr_count, +1);
						array_push($tr_weight_display, 'yes');
					}

					$tr = count($value['attributes']);
					array_push($tr_count, $tr);
				}

				$attributes_count = $tr_count[1];
				$description_count = $tr_description_count[0];
				$dimensions_count = $tr_dimensions_count[0];
				$weight_count = $tr_weight_count[0];
				$tr_count_final_total = $attributes_count + $description_count + $dimensions_count + $weight_count;
				$tr_count_final = $tr_count_final_total + 6;
				$loop_count = 4 - $tr_count_final_total;
				?>

			<!--Search HTML Start-->
			<form role="search" method="get" class="custom-variation-search-form">
				<label for="variation-search">Search by size or color...</label>
				<input type="text" id="variation-search" placeholder="Search by size or color">
				<label for="product-name">Search by Product Name:</label>
				<input type="search" id="product-name" class="search-field" placeholder="Search by product name..." value="" name="product_name_search" />

				<label for="color-search">Search by Color:</label>
				<input type="search" id="color-search" class="search-field" placeholder="Search by color..." value="" name="color_search" />

				<label for="size-search">Search by Size:</label>
				<input type="search" id="size-search" class="search-field" placeholder="Search by size..." value="" name="size_search" />
			</form>
			</br>
			<input type="hidden" value="<?php echo $product_id; ?>" class="current_product_id" />
			<!--Search HTML END-->

			<div class="varation_table_main" style="overflow-x:auto;">
				<table class="varation_table" style="width:100%;" id="varation_table">
					<tbody>
						<tr>
							<th style="text-align:center;background-color: #f5f5f5;width: 80px;"><input type='checkbox' name='selected[]' id="check-demo-all" value=''> 選択する</th>
							<th style="text-align:center;background-color: #f5f5f5;width: 80px;">画像</th>
							<th style="text-align:center;background-color: #f5f5f5;width: 140px;">商品コード</th>

							<?php
							if ($tr_count_final < 10) {

								$specifications_group_id = 8748;
								$specifications_fields = array();
								$fields = acf_get_fields($specifications_group_id);
								$counter = 0;
								foreach ($fields as $field) {
									$field_value = get_field($field['name']);

									if ($field_value && !empty($field_value)) {
										if ($field['label'] != "Variation table On/Off" && $field['label'] != "カラー") {
											$field['label'];
							?>
											<th style="text-align:center;background-color: #f5f5f5;width: 140px;"><?php echo $field['label']; ?></th>
							<?php
											$counter++;
											if ($counter >= $loop_count) {
												break;
											}
										}
									}
								}
								//acf group end
							}
							?>
							<?php //foreach ($available_variations as $key => $value) { 
							?>

							<?php if ($tr_description_display[0] == 'yes') { ?>
								<th style="text-align:center;background-color: #f5f5f5;width: auto;">説明</th>
							<?php } ?>

							<?php if ($tr_dimensions_display[0] == "yes") { ?>
								<th style="text-align:center;background-color: #f5f5f5;width: 160px;">寸法 (長×幅×高) (mm)</th>
							<?php } ?>

							<?php
							//$weight = $value['weight'];
							//$weight_result = substr($weight, 0, 6);
							if ($tr_weight_display[0] == "yes") {  ?>
								<th style="text-align:center;background-color: #f5f5f5;width: 100px;">重量 (g)</th>
							<?php } ?>
							<?php //} 
							?>
							<?php
							foreach ($product_attr as $attr) {
								foreach ($attr as $attribute) {
							?>
									<th style="text-align:center;background-color: #f5f5f5;">
										<?php $taxonomy   = $attribute['name'];

										echo $label_name = wc_attribute_label($taxonomy);
										?>
									</th>
							<?php
								}
							}
							?>
							<th style="text-align:center;background-color: #f5f5f5;">在庫</th>
							<th style="text-align:center;background-color: #f5f5f5;">価格</th>
							<th style="text-align:center;background-color: #f5f5f5;">数量</th>
							<!--<th style="text-align:center;background-color: #f5f5f5;">カートに追加</th>-->
						</tr>
						<?php foreach ($available_variations as $key => $value) {
						?>
							<tr class="variation-row" data-color='<?php echo $value['attributes']['attribute_color']; ?>' data-size='<?php echo $value['attributes']['attribute_size']; ?>'>

								<td><?php if ($value['max_qty'] > 0) { ?>
										<input type='checkbox' name='selected[]' id="check-demo-<?php echo $value['variation_id']; ?>" value='<?php echo $value['variation_id']; ?>' data-qty_<?php echo $value['variation_id']; ?>='1'>
									<?php } ?>
								</td>

								<td style="font-size: 12px !important;white-space: nowrap; text-align:center;">
									<img style="height: 65px;width: 65px;max-width: 65px;" src="<?php echo $value['image']['thumb_src']; ?>">
								</td>

								<td style="font-size: 12px !important;white-space: nowrap; text-align:center;">
									<div class="code" style="width: 140px;white-space: break-spaces;margin:auto; text-align:left;"><?php echo $value['sku']; ?></div>
								</td>

								<?php
								if ($tr_count_final < 10) {

									$specifications_group_id = 8748;
									$specifications_fields = array();
									$fields = acf_get_fields($specifications_group_id);
									$counter = 0;
									foreach ($fields as $field) {
										$field_value = get_field($field['name']);
										if ($field_value && !empty($field_value)) {
											if ($field['label'] != "Variation table On/Off" && $field['label'] != "カラー") {
												$field['label'];
								?>
												<td style="font-size: 12px !important;white-space: nowrap; text-align:center;">
													<div class="code" style="width: 140px;white-space: break-spaces;margin:auto; text-align:left;">
														<?php echo $specifications_fields[$field['name']]['value'] = $field_value; ?>
													</div>
												</td>
								<?php
												$counter++;
												if ($counter >= $loop_count) {
													break;
												}
											}
										}
									}
									//acf group end
								}
								?>

								<?php if ($tr_description_display[0] == 'yes') { ?>
									<td class="explanation" style="font-size: 12px !important; ">
										<?php

										echo $value['variation_description'];

										?>
									</td>
								<?php } ?>
								<?php if ($tr_dimensions_display[0] == "yes") { ?>
									<td class="size_bkp" style="font-size: 12px !important;white-space: nowrap;width: 160px; text-align: center !important;">
										<?php
										if ($value['dimensions']['length']) {
											echo $value['dimensions']['length'];
										}
										if ($value['dimensions']['width']) {
											echo '×' . $value['dimensions']['width'];
										}
										if ($value['dimensions']['height']) {
											echo '×' . $value['dimensions']['height'];
										}
										?>
									</td>
								<?php } ?>

								<?php
								//$weight = $value['weight'];
								//$weight_result = substr($weight, 0, 6);
								if ($tr_weight_display[0] == "yes") {
								?>
									<td class="weight_bkp" style="font-size: 12px !important;white-space: nowrap;width: 100px;">
										<?php
										$weight = $value['weight'];
										if ($weight) {
											$weight_result = substr($weight, 0, 6);
											if ($weight_result == "field_") {
												echo " ";
											} else {
												echo $weight;
											}
										}

										?>
									</td>
								<?php } ?>


								<?php
								foreach ($value['attributes'] as $attr_key => $attr_value) {
									//echo "<pre>";
									//print_r($attr_key);
									//echo $attr_value;
								?>
									<td style="font-size: 12px !important;white-space: nowrap; text-align:center !important;">
										<b>
											<?php
											$result = substr($attr_key, 0, 18);
											if ($result == 'attribute_wrapping') {
												$taxonomy = str_replace('attribute_', '', $attr_key);
												$koostis = $product->get_attribute($taxonomy);
												$str_arr = str_replace('|', ',', $koostis);
												$str_explode = explode(",", $str_arr);

											?>
												<select id="<?php echo $taxonomy; ?>" class="variable_<?php echo $value['variation_id']; ?>" data-id="<?php echo $value['variation_id']; ?>" name="<?php echo $attr_key; ?>" data-attribute_name="<?php echo $attr_key; ?>" data-show_option_none="yes">
													<option value="">
														<font style="vertical-align: inherit;">
															<font style="vertical-align: inherit;">選んでください</font>
														</font>
													</option>
													<?php foreach ($str_explode as $str) {  ?>

														<option value="<?php echo $str; ?>">
															<font style="vertical-align: inherit;">
																<font style="vertical-align: inherit;"><?php echo $str; ?></font>
															</font>
														</option>
													<?php } ?>
												</select>
											<?php
												//echo $attr_key;
											} else if (str_contains($attr_key, 'attribute_')) {
												echo $attr_value;
											} else {
												//echo $attr_value;
												global $wpdb;
												$slug = "SELECT name FROM `wp_terms` WHERE slug = '" . $attr_value . "'";
												$slug_query_array = $wpdb->get_results($slug);
												echo $slug_query_array[0]->name;
											}
											?>
										</b>
									</td>
								<?php } ?>

								<td style="font-size: 12px !important;white-space: nowrap;">
									<?php
									$stock = $value['max_qty'];
									if ($stock > 0) {
										echo "<div style='color:#77a464; font-weight:bold;'>";
										echo $stock;
										echo "<div>";
									} else {
										echo "<div style='color:red; font-weight:bold;'>";
										echo "在庫切れ";
										echo "<div>";
									}
									?>
								</td>
								<td class="sellprice" style="font-size: 12px !important;white-space: nowrap;">
									<?php
									echo "<div style='display: flex;align-items: baseline;justify-content: center'>";
									if ($value['display_price'] < $value['display_regular_price']) {
										$percentage = (($value['display_regular_price'] - $value['display_price']) / $value['display_regular_price']) * 100;
										echo "<div style='font-size: 14px;color: #666;font-weight: 500;text-decoration: line-through;margin-right: 5px;'>";
										echo "¥" . number_format($value['display_regular_price']);
										echo "</div>";
										echo "<div style='font-size: 14px;color: #ff3300;font-weight: 500;'>";
										$per = round($percentage);
										echo "(-" . $per . "%)";
										echo "</div>";
									}
									echo "</div>";
									echo "<div style='font-size: 24px;color: #ff3300;font-weight: 600;margin-right: 10px;'>";
									echo "¥" . number_format($value['display_price']);
									echo "</div>";

									?>
								</td>
								<td class="variant_product_quantity" style="font-size: 12px !important;white-space: nowrap;">
									<?php $permalink = get_permalink($product->get_id());
									$step = 1;
									$min_value = 1;
									$max_value = 10000;
									$input_name = $id;
									$input_value = 1;
									$input_name = $id;
									$sku = $product->get_sku();
									?>

									<div class="quantity">
										<label class="screen-reader-text" for="<?php echo esc_attr($id); ?>"><?php esc_html_e('Quantity', 'woocommerce'); ?></label>
										<input type="button" value="-" class="qty_button minus quantity_counter" data-id="<?php echo $value['variation_id'] ?>" />
										<input type="number" id="<?php echo esc_attr($id); ?>" class="input-text qty text quantity_counter" step="<?php echo esc_attr($step); ?>" min="<?php echo esc_attr($min_value); ?>" max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>" name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($input_value); ?>" title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'woocommerce'); ?>" size="4" />
										<input type="button" value="+" class="qty_button plus quantity_counter" data-id="<?php echo $value['variation_id'] ?>" />
									</div>
								</td>
								<!--<td style="width:100px;font-size: 12px !important;white-space: nowrap; text-align:center;">
								<form action="<?php //echo esc_url($product->add_to_cart_url()); 
												?>" method="post" enctype='multipart/form-data'>
									<input type="hidden" name="variation_id" value="<?php //echo $value['variation_id'] 
																					?>" />
									<input type="hidden" name="product_id" value="<?php //echo esc_attr($post->ID); 
																					?>" />
									<input type="hidden" name="add-to-cart" value="<?php //echo esc_attr($post->ID); 
																					?>" />
									<input type="hidden" id="<?php //echo "quantity_" . $value['variation_id'] 
																?>" name="quantity" class="quantity_value" value="1" />

									<?php
									/**if (!empty($value['attributes'])) {
										foreach ($value['attributes'] as $attr_key => $attr_value) {
									?>
											<input id="<?php echo $attr_key . "_" . $value['variation_id'] ?>" type="hidden" class="dynamic_variation_<?php echo $value['variation_id'] ?>" name="<?php echo $attr_key ?>" value="<?php echo $attr_value ?>">
									<?php
										}
									}
									?>
									<?php
									$stock = $value['max_qty'];
									if ($stock > 0) {
									?>
										<button type="submit" class="single_add_to_cart_button">
											<?php echo apply_filters('single_add_to_cart_text', __('', 'woocommerce'), $product->product_type); ?>
										</button>
									<?php } else { ?>
										<button type="submit" class="single_add_to_cart_button" disabled>
											<?php echo apply_filters('single_add_to_cart_text', __('', 'woocommerce'), $product->product_type); ?>
										</button>
									<?php }**/
									?>
								</form>
							</td>-->
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<input type="button" id="chk_btn" value="カートに追加">
				<input type="button" id="chk_btn_cart_pg" value="今すぐ購入">
			</div>
			<script>
				jQuery('.variable').on('change', function() {
					var dropdown_name = jQuery(this).attr('name');
					var product_id = jQuery(this).data('id');
					var select_value = this.value;
					jQuery('#' + dropdown_name + '_' + product_id).val(select_value);
				});
			</script>
		<?php
		} else {
		?>

			<style>
				div.woocommerce-variation-price,
				table.variations {
					display: block;
				}
			</style>

			<div class="varation_table_main" style="overflow-x: auto;">
				<table class="varation_table" style="width: 100%;">
					<tbody>
						<tr class="variation-row">
							<?php
							$specifications_group_id = 8748;
							$specifications_fields = array();
							$fields = acf_get_fields($specifications_group_id);
							foreach ($fields as $field) {
								$field_value = get_field($field['name']);
								if ($field_value && !empty($field_value)) {
									if ($field['label'] != "Variation table On/Off" && $field['label'] != "カラー") {
										$field['label'];
							?>
										<th style="text-align:center;background-color: #f5f5f5;width: 140px;"><?php echo $field['label']; ?></th>
							<?php
									}
								}
							}
							//acf group end
							?>
						</tr>
						<tr>
							<?php
							$specifications_group_id = 8748;
							$specifications_fields = array();
							$fields = acf_get_fields($specifications_group_id);
							foreach ($fields as $field) {
								$field_value = get_field($field['name']);
								if ($field_value && !empty($field_value)) {
									if ($field['label'] != "Variation table On/Off" && $field['label'] != "カラー") {
										$field['label'];
							?>
										<td style="font-size: 12px !important;white-space: nowrap; text-align:center;">
											<div class="code" style="width: 140px;white-space: break-spaces;margin:auto; text-align:center;">
												<?php echo $specifications_fields[$field['name']]['value'] = $field_value; ?>
											</div>
										</td>
							<?php
									}
								}
							}
							//acf group end
							?>
						</tr>
					</tbody>
				</table>
			</div>
	<?php
		}
	}
}


function add_this_script_footer()
{ ?>
	<script>
		jQuery(function(jQuery) {
			jQuery(document.body).on('click', '.plus, .minus', function() {
				var qty = jQuery(this).closest('.quantity').find('.qty');
				var val = parseFloat(qty.val());
				var max = parseFloat(qty.attr('max'));
				var min = parseFloat(qty.attr('min'));
				var step = parseFloat(qty.attr('step'));
				if (jQuery(this).is('.plus')) {
					if (max && (max <= val)) {
						qty.val(max);
					} else {
						qty.val(val + step);
						var plus_val = val + step

						var data_id_val = jQuery(this).attr("data-id");

						jQuery('#quantity_' + data_id_val).val(plus_val);
						jQuery('#check-demo-' + data_id_val).attr('data-qty_' + data_id_val, plus_val);
					}
				} else {
					if (min && (min >= val)) {
						qty.val(min);
					} else if (val > 1) {
						qty.val(val - step);
						var minus_val = val - step



						var data_id_val = jQuery(this).attr("data-id");
						jQuery('#quantity_' + data_id_val).val(minus_val);
						jQuery('#check-demo-' + data_id_val).attr('data-qty_' + data_id_val, minus_val);
					}
				}

			});
		});

		//Variant product listing Serach feature START

		//Filter with prouct name START
		jQuery(document).ready(function($) {
			jQuery('#product-name').on('keyup', function() {
				var value = jQuery(this).val().toLowerCase();
				console.log(value);
				jQuery('#varation_table .variation-row').filter(function() {
					jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1);
				});
			});
		});
		//Filter with prouct name END

		//Filter with product color START
		jQuery(document).ready(function($) {
			jQuery('#color-search').on('keyup', function() {
				var value = jQuery(this).val().toLowerCase();
				jQuery('#varation_table .variation-row').filter(function() {
					var color = jQuery(this).data('color').toLowerCase();
					jQuery(this).toggle(color.indexOf(value) > -1);
				});
			});
		});
		//Filter with product color END

		//Filter with product size START
		jQuery(document).ready(function($) {
			jQuery('#size-search').on('keyup', function() {
				var value = jQuery(this).val().toLowerCase();
				jQuery('#varation_table .variation-row').filter(function() {
					var size = jQuery(this).data('size').toLowerCase();
					jQuery(this).toggle(size.indexOf(value) > -1);
				});
			});
		});
		//Filter with product size END

		//Filter with color or size using single input type START
		jQuery(document).ready(function($) {
			jQuery('#variation-search').on('keyup', function() {
				var value = jQuery(this).val().toLowerCase();
				jQuery('#varation_table .variation-row').filter(function() {
					var size = jQuery(this).data('size').toLowerCase();
					var color = jQuery(this).data('color').toLowerCase();
					//console.log('size' + size.indexOf(value) > -1);
					//console.log('color' + color.indexOf(value) > -1);
					var data = size.indexOf(value) > -1 || color.indexOf(value) > -1;
					jQuery(this).toggle(data);
				})
			});
		});
		//Filter with color or size using using single input type END
		//Variant product listing Serach feature END	
	</script>

<?php }
add_action('wp_footer', 'add_this_script_footer');

function variable_ajax_call()
{ ?>
	<script type='text/javascript' src='https://www.osusumeshop.jp/wp-content/themes/martfury/js/plugins/notify.min.js?ver=1.0.0' id='notify-js'></script>
	<script>
		jQuery("#check-demo-all").click(function() {
			jQuery(".varation_table input[type=checkbox]").prop('checked', jQuery(this).prop('checked'));

		});

		var datas = [];
		var qty = [];
		jsonObj = [];
		var attribute = [];
		jQuery(document).on('click', '#chk_btn', function(e) {
			jQuery(".varation_table input[type=checkbox]:checked").each(function() {
				var variation_id = jQuery(this).val();
				var quantity = jQuery(this).attr('data-qty_' + variation_id);
				var selected_val = jQuery('.variable_' + variation_id).find(":selected").val();
				var selected_attr_name = jQuery('.variable_' + variation_id).attr('name');
				var product_id = jQuery('product_id').val();
				//alert(selected_val);
				jQuery('.dynamic_variation_' + variation_id).each(function(index, obj) {
					var variation_val = jQuery(this).val();
					var variation_name = jQuery(this).attr('name');
					attribute.push({
						variation_name: variation_name,
						variation_val: variation_val
					});
				});
				product = {};
				product["quantity"] = quantity;
				product["variation_id"] = variation_id;
				product["selected_val"] = selected_val;
				product['selected_attr_name'] = selected_attr_name;
				product["product_id"] = product_id;
				product["attributes"] = []
				var attributes = [];
				for (var i = 0; i < attribute.length; i++) {
					var obj = {
						attribute_name: attribute[i].variation_name,
						attribute_val: attribute[i].variation_val,
					}
					product["attributes"].push(obj)
				}
				jsonObj.push(product);
			});
			//console.log(jsonObj);
			//exit();
			e.preventDefault();
			//alert(datas);
			var datas = jsonObj;
			//console.log(data);
			jQuery.ajax({
				type: 'POST',
				dataType: 'json',
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: {
					'action': 'chk_val',
					'val': datas,
				},
				success: function(response) {
					//popup code start
					var $msg = "";
					var $content;
					var className = "success";
					var $msgtxt = 'view shopping cart';
					var $link = "http://localhost/testpr/cart/";
					$content = response.data.msg;
					$msg = '<a href="' + $link + '" class="btn-button">' + $msgtxt + "</a>";
					//alert($msg);
					$message = '<div class="message-box">' + $msg + "</div>";
					//alert($message);
					jQuery.notify.addStyle("martfury", {
						html: '<div><i class="icon-checkmark-circle message-icon"></i>' + $content + $message + '<span class="close icon-cross2"></span></div>',
					});
					jQuery.notify($content, {
						className: className,
						// if autoHide, hide after milliseconds
						autoHideDelay: 9000,
						style: "martfury",
						showAnimation: "fadeIn",
						hideAnimation: "fadeOut",
					});
					//popup code end

					/**Old code START */
					//window.location.reload();
					setTimeout(function() {
						location.reload();
					}, 9000);
					//jQuery(window).on('beforeunload', function() {
					//jQuery('body').hide();
					//jQuery(window).scrollTop(0);
					//});
					/**Old code END */
				}
			});
		});



		jQuery(document).on('click', '#chk_btn_cart_pg', function(e) {
			jQuery(".varation_table input[type=checkbox]:checked").each(function() {
				var variation_id = jQuery(this).val();
				var quantity = jQuery(this).attr('data-qty_' + variation_id);
				var selected_val = jQuery('.variable_' + variation_id).find(":selected").val();
				var selected_attr_name = jQuery('.variable_' + variation_id).attr('name');
				//alert(selected_attr_name);
				//exit;
				var product_id = jQuery('product_id').val();
				//alert(selected_val);
				jQuery('.dynamic_variation_' + variation_id).each(function(index, obj) {
					var variation_val = jQuery(this).val();
					var variation_name = jQuery(this).attr('name');
					attribute.push({
						variation_name: variation_name,
						variation_val: variation_val
					});
				});
				product = {};
				product["quantity"] = quantity;
				product["variation_id"] = variation_id;
				product["selected_val"] = selected_val;
				product['selected_attr_name'] = selected_attr_name;
				product["product_id"] = product_id;
				product["attributes"] = []
				var attributes = [];
				for (var i = 0; i < attribute.length; i++) {
					var obj = {
						attribute_name: attribute[i].variation_name,
						attribute_val: attribute[i].variation_val,
					}
					product["attributes"].push(obj)
				}
				jsonObj.push(product);
			});
			//console.log(jsonObj);
			//exit();
			e.preventDefault();
			//alert(datas);
			var datas = jsonObj;
			//console.log(data);
			jQuery.ajax({
				type: 'POST',
				dataType: 'json',
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: {
					'action': 'chk_val',
					'val': datas,
				},
				success: function(response) {
					//alert('success');
					//window.location.reload();
					window.location.href = "http://localhost/testpr/cart";
				}
			});
		});
	</script>

<?php }

add_action('wp_footer', 'variable_ajax_call');

add_action('wp_ajax_chk_val', 'chk_val_callback');
add_action('wp_ajax_nopriv_chk_val', 'chk_val_callback');
function chk_val_callback()
{
	//echo '<pre>';
	$data = $_POST['val'];
	//print_r($data);
	foreach ($data as $prd) {
		$variation_id = $prd['variation_id'];
		$selecetd_attr_name = $prd['selected_attr_name'];
		// Replace this with the quantity you want to add to the cart
		$quantity = $prd['quantity'];
		$selecetd_item = $prd['selected_val'];
		$product_id = $prd['product_id'];
		/**$cart_item_data = array(
			'wrapping' => $selecetd_item,
		);*/
		// Get the product variation data
		$variation_data = wc_get_product($variation_id);
		// Add the variation to the cart
		$variation_attributes = [];
		//print_r($prd['attributes']);
		foreach ($prd['attributes'] as $attr) {
			array_push($variation_attributes, [$attr['attribute_name'] => $attr['attribute_val']]);
		}

		$variation = wc_get_product($variation_id);
		// WC()->cart->add_to_cart($variation_id, $quantity);
		WC()->cart->add_to_cart($variation_id, $quantity, $variation_attributes, array($selecetd_attr_name => $selecetd_item));

		//WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation->get_variation_attributes(), array('variation' => $variation),$cart_item_data);
	}
	$cart_contents_count = WC()->cart->get_cart_contents_count();
	//$cart_url = wc_get_cart_url();
	//$notice_message = sprintf( __( 'Product added to cart. Cart now contains %d item(s).  <a href="%s" class="button wc-forward">View Shopping Cart</a>', 'your-text-domain' ), $cart_contents_count ,esc_url($cart_url));
	// Display the WooCommerce notice
	//wc_add_notice( $notice_message, 'success' );
	$result = array(
		'msg' => 'Product added to cart. Cart now contains ' . $cart_contents_count . 'item(s).',
	);
	if ($result == false) {
		wp_send_json_error();
	} else {
		wp_send_json_success($result);
	}
	return true;
	die();
}

/**Add custom tab panel in product details page admin START**/
add_filter('woocommerce_product_data_tabs', function ($tabs) {
	$tabs['additional_info'] = [
		'label' => __('Custom plugin fields', 'txtdomain'),
		'target' => 'additional_product_data',
		'class' => ['hide_if_external'],
		'priority' => 25
	];
	return $tabs;
});
/**Add custom tab panel in product details page admin END**/

/**Add fields to cutom tab on product details page START*/
add_action('woocommerce_product_data_panels', function () {
?><div id="additional_product_data" class="panel woocommerce_options_panel hidden">
		<?php
		woocommerce_wp_text_input([
			'id' => '_dummy_text_input',
			'label' => __('Dummy text input', 'txtdomain'),
			'wrapper_class' => 'show_if_simple',
		]);
		woocommerce_wp_checkbox([
			'id' => '_dummy_checkbox',
			'label' => __('Dummy checkbox', 'txtdomain'),
			'wrapper_class' => 'hide_if_grouped',
		]);
		woocommerce_wp_text_input([
			'id' => '_dummy_text_input',
			'label' => __('Dummy text input', 'txtdomain'),
			'type' => 'text',
		]);
		?></div>
<?php
});
/**Add fields to cutom tab on product details page END*/

add_action('woocommerce_process_product_meta', function ($post_id) {
	$product = wc_get_product($post_id);
	$product->update_meta_data('_dummy_text_input', sanitize_text_field($_POST['_dummy_text_input']));
	$dummy_checkbox = isset($_POST['_dummy_checkbox']) ? 'yes' : '';
	$product->update_meta_data('_dummy_checkbox', $dummy_checkbox);
	$product->update_meta_data('_dummy_number_input', sanitize_text_field($_POST['_dummy_number_input']));
	$product->save();
});


/**Get Breadcrumb in single.php START */
function get_breadcrumb()
{
	echo '<a href="' . home_url() . '" rel="nofollow">Home</a>';
	if (is_category() || is_single()) {
		echo "&nbsp;&#187;&nbsp;";
		the_category(' &bull; ');
		if (is_single()) {
			echo " &nbsp;&#187;&nbsp;";
			the_title();
		}
	} elseif (is_page()) {
		echo "&nbsp;&#187;&nbsp;";
		echo the_title();
	} elseif (is_search()) {
		echo "&nbsp;&#187;&nbsp;Search Results for... ";
		echo '"<em>';
		echo the_search_query();
		echo '</em>"';
	}
}
/**Get Breadcrumb in single.php END */


function filterexpost()
{
	$val = $_POST['val'];
	$string = implode(', ', $val);
?>
	<div class="container-custom mb-60">
		<div class="today-highlights">
			<div class="row flex-wrap-wrap">
				<?php
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => 3,
					'cat' => array($string),
					//'paged' => 1,
				);
				$loop = new WP_Query($args);
				while ($loop->have_posts()) : $loop->the_post();
				?>
					<div class="col-md-4" id="count-expage" data-countpage="<?php echo $loop->max_num_pages; ?>">
						<!--Star rating START-->
						<h6>Star Rating</h6>
						<div class="star-rating">
							<span class="fa fa-star" data-val="1" data-post-id=<?php echo get_the_ID(); ?>></span>
							<span class="fa fa-star" data-val="2" data-post-id=<?php echo get_the_ID(); ?>></span>
							<span class="fa fa-star" data-val="3" data-post-id=<?php echo get_the_ID(); ?>></span>
							<span class="fa fa-star" data-val="4" data-post-id=<?php echo get_the_ID(); ?>></span>
							<span class="fa fa-star" data-val="5" data-post-id=<?php echo get_the_ID(); ?>></span>
						</div>
						<!--Star rating END-->
						<div class="highlights-grid-post">
							<div class="highlights-post-image-wrapper">
								<a href="">
									<?php $url = wp_get_attachment_url(get_post_thumbnail_id($loop->ID)); ?>
									<div class="highlights-post-image" style="background-image: url('<?php echo $url; ?>');"></div>
								</a>
							</div>
							<div class="latestblog-post-details">
								<h3 class="latestblog-post-title">
									<a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a>
								</h3>
								<div class="latestblog-post-author">
									<div class="latestblog-post-author-section">
										<div class="latestblog-post-author-image">
											<a href="">
												<img src="https://transdirect.plutustec.in/wp-content/uploads/2022/08/Screen-Shot-2022-08-15-at-10.22.26-am-modified.png" alt="" />
											</a>
											<p class="latestblog-post-autho-name">
												<?php $categories = get_the_category(); ?>
												By<a href=""><?php echo $categories[0]->name; //get_field('author'); 
																?></a>
											</p>
										</div>
									</div>
									<div class="latestblog-post-date">
										<?php echo get_field('date'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile;
				?>
			</div>
		</div>
	</div>
	<?php wp_reset_postdata(); ?>
	<?php
	die;
}
add_action('wp_ajax_expost', 'filterexpost');
add_action('wp_ajax_nopriv_expost', 'filterexpost');


//Load more expost
function load_more_exposts()
{
	$val = $_POST['catid'];
	$string = implode(', ', $val);
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 3,
		'orderby' => 'date',
		'order' => 'DESC',
		'cat' => array($string),
		'paged' => $_POST['page'],
	);
	$loop = new WP_Query($args);
	while ($loop->have_posts()) : $loop->the_post();
	?>
		<div class="col-md-4" id="count-expage" data-countpage="<?php echo $loop->max_num_pages; ?>">
			<!--Star rating START-->
			<h6>Star Rating</h6>
			<div class="star-rating">
				<span class="fa fa-star" data-val="1" data-post-id=<?php echo get_the_ID(); ?>></span>
				<span class="fa fa-star" data-val="2" data-post-id=<?php echo get_the_ID(); ?>></span>
				<span class="fa fa-star" data-val="3" data-post-id=<?php echo get_the_ID(); ?>></span>
				<span class="fa fa-star" data-val="4" data-post-id=<?php echo get_the_ID(); ?>></span>
				<span class="fa fa-star" data-val="5" data-post-id=<?php echo get_the_ID(); ?>></span>
			</div>
			<!--Star rating END-->
			<div class="highlights-grid-post">
				<div class="highlights-post-image-wrapper">
					<a href="">
						<?php $url = wp_get_attachment_url(get_post_thumbnail_id($loop->ID)); ?>
						<div class="highlights-post-image" style="background-image: url('<?php echo $url; ?>');"></div>
					</a>
				</div>
				<div class="latestblog-post-details">
					<h3 class="latestblog-post-title">
						<a href=""><?php echo the_title(); ?></a>
					</h3>
					<div class="latestblog-post-author">
						<div class="latestblog-post-author-section">
							<div class="latestblog-post-author-image">
								<a href="">
									<img src="https://transdirect.plutustec.in/wp-content/uploads/2022/08/Screen-Shot-2022-08-15-at-10.22.26-am-modified.png" alt="" />
								</a>
								<p class="latestblog-post-autho-name">
									<?php $categories = get_the_category(); ?>
									By<a href=""><?php echo $categories[0]->name; //get_field('author'); 
													?></a>
								</p>
							</div>
						</div>
						<div class="latestblog-post-date">
							<?php echo get_field('date'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile;
	?>
<?php wp_reset_postdata();
	exit;
}
add_action('wp_ajax_load_expost', 'load_more_exposts');
add_action('wp_ajax_nopriv_load_expost', 'load_more_exposts');

/**Get current tag val START */
add_action('wp_ajax_get_tag_val', 'get_tag_callback');
add_action('wp_ajax_nopriv_get_tag_val', 'get_tag_callback');
function get_tag_callback()
{
	$tag = $_POST['param'];
	$lower_tag = strtolower($tag);
	$tag_slug = str_replace(' ', '-', $lower_tag);
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if (!$tag) {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
		);
		$loop = new WP_Query($args);
		$total_pg =  $loop->max_num_pages;
	} else {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'tag' => $tag_slug,
		);
		$loop = new WP_Query($args);
		$total_pg = $loop->max_num_pages;
	}
	$url = $_POST['base'];
	if ($tag) {
		$newbase = 'https://transdirect.plutustec.in/tag/' . $tag_slug;
	} else {
		$newbase = 'https://transdirect.plutustec.in/latest-blog/';
	}
?>
	<div class="row mt-5">
		<?php
		if ($tag) {
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'orderby' => 'date',
				'order' => 'DESC',
				'tag' => $tag_slug,
				'paged' => $paged
			);
		} else {
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'orderby' => 'date',
				'order' => 'DESC',
				'paged' => $paged
			);
		}
		$firstPosts = array();
		$loop = new WP_Query($args);
		?>
		<?php
		while ($loop->have_posts()) : $loop->the_post();
			$post_id = get_the_ID();
			$firstPosts[] = $post_id;
		?>
			<div class="col-12 wow fadeInUp mb-3 " data-wow-duration="2s">
				<div class="card item fitness">
					<div class="row no-gutters raw">
						<div class="col-md-8">
							<?php $url = wp_get_attachment_url(get_post_thumbnail_id($loop->ID)); ?>
							<a href="<?php the_permalink(); ?>"><img class="img-fluid" src="<?php echo $url; ?>" alt="..."></a>
						</div>
						<div class="col-md-4">
							<div class="card-body d-flex flex-wrap h-100 flex-column">
								<div>
									<?php $posttags = get_the_tags();
									if ($posttags) {
										foreach ($posttags as $tag) { ?>
											<span class="badge badge-secondary"><span class="badge badge-secondary"><?php echo $tag->name; ?></span></span>
									<?php }
									} ?>
								</div>
								<h3 class="card-text"><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h3>
								<div class="star-bottom d-flex flex-column justify-content-between">
									<div class="star-group">
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
									</div>
									<div class="drop-box">
										<div class="drop-img mr-2">
											<img class="img-fluid" src="https://transdirect.plutustec.in/wp-content/uploads/2022/06/spheres.svg" />
										</div>
										<div class="">
											<span><strong><?php echo get_the_author(); ?></strong></span>
											<span>-</span>
											<span class="text-muted">recovr.com</span>
											<p class="mb-0 text-muted"><strong><?php echo get_the_date('F jS, Y'); ?></strong></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		<?php
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ($tag) {
			$args = array(
				'post_type' => 'post',
				'orderby' => 'date',
				'order' => 'DESC',
				'post__not_in' => $firstPosts,
				'posts_per_page' => 6,
				'tag' => $tag_slug,
				'paged' => $paged
			);
		} else {
			$args = array(
				'post_type' => 'post',
				'orderby' => 'date',
				'order' => 'DESC',
				'post__not_in' => $firstPosts,
				'paged' => $paged,
				'posts_per_page' => 6,
			);
		}
		$loop = new WP_Query($args);
		while ($loop->have_posts()) : $loop->the_post();
		?>
			<div class="col-md-6 wow fadeInUp mb-3" data-wow-duration="2s">
				<div class="card item life h-100">
					<div>
						<?php $url = wp_get_attachment_url(get_post_thumbnail_id($loop->ID)); ?>
						<a href="<?php the_permalink(); ?>"><img src="<?php echo $url; ?>" class="card-img-top" alt="..."></a>
					</div>
					<div class="card-body d-flex flex-wrap h-100 flex-column">
						<div>
							<?php $posttags = get_the_tags();
							if ($posttags) {
								foreach ($posttags as $tag) { ?>
									<span class="badge badge-secondary"><span class="badge badge-secondary"><?php echo $tag->name; ?></span></span>
							<?php }
							} ?>
						</div>
						<h3 class="card-text"><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h3>
						<div class="star-bottom d-flex flex-column justify-content-between">
							<div class="star-group">
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
							</div>
							<div class="drop-box">
								<div class="drop-img mr-2">
									<img class="img-fluid" src="https://transdirect.plutustec.in/wp-content/uploads/2022/06/spheres.svg" />
								</div>
								<div class="">
									<span><strong><?php echo get_the_author(); ?></strong></span>
									<span>-</span>
									<span class="text-muted">recovr.com</span>
									<p class="mb-0 text-muted"><strong><?php echo get_the_date('F jS, Y'); ?></strong></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		endwhile;
		?>
		<?php wp_reset_postdata(); ?>
	</div>

	<!-- Start Pagination - WP-PageNavi -->
	<?php

	$big = 999999999;
	// Fallback if there is not base set.
	$fallback_base = str_replace($big, '%#%', esc_url(get_pagenum_link($big)));

	if ($tag) {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'post_status' => 'publish',
			'tag' => $tag_slug
		);
		//echo '<pre>';
		//print_r($args);
	} else {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'post_status' => 'publish'
		);
	}

	// Set the base.
	$base = isset($newbase) ? trailingslashit($newbase) . '%_%' : $fallback_base;
	$tag =  '<div class="append_page"><div class="pagination-box wow fadeInUp mb-3 filter-pagination pagination">';
	$tag .=  paginate_links(array(
		'base' => $base,
		'format' => 'page/%#%',
		'current' => max(1, get_query_var('paged')),
		'total' => $total_pg,
		'show_all' => true,
		'prev_text' => '<<',
		'next_text' => '>>'
	));
	$tag .=  '</div></div>';
	//echo $loop->max_num_pages;
	?>
	<?php
	//if ($loop->max_num_pages > 1) : 
	?>
	<ul class="pagination">
		<li class="tags"><?php echo $tag; ?></li>
	</ul>
	<?php //endif;
	?>
	<!-- End Pagination -->

	<div class="my-4 wow fadeInUp mb-3 Inside-filter">
		<div class="news-later">
			<div class="row align-items-center">
				<div class="col-md-6 wow fadeInUp mb-3 mb-md-0 line-text">
					<h5 class="footer-top-subtitile"> Subscribe to our Newsletter </h5>
					<h3 class="footer-title"> <span class="news-letter-text">For the latest in fitness,<br>wellbeing and trainer tips</span> </h3>
				</div>
				<div class="col-md-6 wow fadeInUp mb-3">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Email">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary" type="button" id="button-addon2">Sign Me Up</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	die;
}
/**Get current tag val END */
/* custom post type [START] */
function cptui_register_my_cpts_gym_member()
{
	$labels = [
		"name" => esc_html__("Gym Member", "custom-post-type-ui"),
		"singular_name" => esc_html__("Gym Member", "custom-post-type-ui"),
		"menu_name" => esc_html__("Gym Member", "custom-post-type-ui"),
	];

	$args = [
		"label" => esc_html__("Gym Member", "custom-post-type-ui"),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => ["slug" => "gym-member", "with_front" => true],
		"query_var" => true,
		"supports" => ["title", "editor", "thumbnail", "excerpt", "custom-fields"],
		"taxonomies" => ["category"],
		"show_in_graphql" => false,
	];

	register_post_type("gym-member", $args);
}
add_action('init', 'cptui_register_my_cpts_gym_member');
/* custom post type [END] */


// Add the custom columns to the post(ex:book) post type:
add_filter('manage_post_posts_columns', 'set_custom_edit_book_columns');
function set_custom_edit_book_columns($columns)
{
	unset($columns['author']);
	$columns['star-ratings'] = __('Star Ratings', 'your_star_ratings');
	return $columns;
}

// Add the data to the custom columns for the post(ex:book) post type:
add_action('manage_post_posts_custom_column', 'custom_book_column', 10, 2);
function custom_book_column($columns, $post_id)
{
	if ($columns) {
		echo $starrating = get_post_meta($post_id, 'starrating', true);
	}
	$i = 0;
?>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.checked {
			color: #586d39 !important;
		}

		.fa {
			color: #a9a9a9;
		}
	</style>
	<div class="star-rating">
		<?php
		for ($i = 0; $i < $starrating; $i++) {
		?>
			<span class="fa fa-star checked" data-val="1" data-post-id=<?php echo $post_id; ?>></span>
		<?php
		} ?>
	</div>
<?php
}

//Star rating value ajax START
add_action('wp_ajax_star_rating_val', 'star_rating_val_callback');
add_action('wp_ajax_nopriv_star_rating_val', 'star_rating_val_callback');
function star_rating_val_callback()
{
	$ratingval = $_POST['ratingval'];
	$current_post_id = $_POST['cur_post_id'];
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'user_rating_val', $ratingval);
	update_post_meta($current_post_id, 'starrating', $ratingval);
	die();
}
//Star rating value ajax END

//Update shipping weight on admin panel programatically START
add_action('woocommerce_admin_process_product_object', 'save_shipping_weight_value');
function save_shipping_weight_value($product)
{
	if (isset($_POST['_weight']) && !empty($_POST['_weight'])) {
		$product->update_meta_data('_weight', esc_attr($_POST['_weight']));
	}
}
//Update shipping weight on admin panel programatically END


//Display variation name & stock on shop page START
add_action('woocommerce_after_shop_loop_item', 'bbloomer_echo_stock_variations_loop');

function bbloomer_echo_stock_variations_loop()
{
	global $product;
	if ($product->get_type() == 'variable') {
		foreach ($product->get_available_variations() as $key) {
			$variation = wc_get_product($key['variation_id']);
			$stock = $variation->get_availability();
			$stock_string = $stock['availability'] ? $stock['availability'] : __('In stock', 'woocommerce');
			$attr_string = array();
			foreach ($key['attributes'] as $attr_name => $attr_value) {
				$attr_string[] = $attr_value;
			}
			echo '<br/>' . implode(', ', $attr_string) . ': ' . $stock_string;
		}
	}
}
//Display variation name & stock on shop page END


/* Creating custom API START */

/** You can fetch data in postman using http://localhost/testpr/wp-json/custom/v1/all-posts link **/
add_action('rest_api_init', 'custom_api_get_all_postss');

function custom_api_get_all_postss()
{
	register_rest_route('custom/v1', '/all-posts', array(
		'methods' => 'GET',
		'callback' => 'custom_api_get_all_posts_callback'
	));
}

// add_action('init','custom_api_get_all_posts_callbacks');
//function custom_api_get_all_posts_callback( $request ) {
function custom_api_get_all_posts_callbacks($request)
{
	/**Get posts data with pagination[Start]*/
	// Receive and set the page parameter from the $request for pagination purposes
	//$paged = $request->get_param( 'page' );
	//$paged = ( isset( $paged ) || ! ( empty( $paged ) ) ) ? $paged : 1; 
	// Get the posts using the 'post' and 'news' post types
	// $posts = get_posts( array(
	//         'paged' => $paged,
	//         //'post__not_in' => get_option( 'sticky_posts' ),
	//         //'posts_per_page' => -1, 
	// 		'posts_per_page' => 3,           
	//         //'post_type' => array( 'post', 'books', 'movies' ) // This is the line that allows to fetch multiple post types. 
	// 		'post_type' => array( 'post')
	//     )
	// ); 
	/**Get posts data with pagination[End]*/

	/**Get all posts data without pagination [Start] */
	$posts = get_posts(
		array(
			'posts_per_page' => -1,
			//'post_type' => array( 'post', 'books', 'movies' ) // This is the line that allows to fetch multiple post types. 
			//'post_type' => array( 'gym-member')
			//'post_type' => array( 'post','page'),
			'post_type' => array('post'),
		)
	);
	/**Get all posts data without pagination [End] */

	if (empty($posts)) {
		//return new WP_Error('post_not_found', 'Post not found', array('status' => 404));
		$response = array(
			'statusCode' => 404,
			'message' => 'Post not found',
			//'data' => $posts_data,
		);
		return new WP_REST_Response($response);
	}
	// Loop through the posts and push the desired data to the array we've initialized earlier in the form of an object
	foreach ($posts as $post) {
		$id = $post->ID;

		/**if (function_exists('elementor_pro') && \Elementor\Plugin::$instance->db->is_built_with_elementor($id)) {
			// Get the Elementor content and its styles
			$post_content = \Elementor\Plugin::$instance->frontend->get_builder_content($id);
			return $post_content;
		}else{
			$post_content = $post->post_content;
		}*/

		/**Get elementor post content with design [Start] */
		/**$contentElementor = "";

		if (class_exists("\\Elementor\\Plugin")) {
			$post_ID = $id;

			$pluginElementor = \Elementor\Plugin::instance();
			$contentElementor = $pluginElementor->frontend->get_builder_content($post_ID);
		}
		//echo $contentElementor;
		if($contentElementor){
			$post_content = $contentElementor; 
		}else{
			$post_content = $post->post_content;
		}*/
		/**Get elementor post content with design [End] */

		/**Modify the height and width of images in the post content [Start] **/
		$content = $post->post_content;
		// $content = preg_replace_callback('/<img[^>]+>/i', function ($matches) {
		//     // Modify the width and height attributes as needed
		//     $newWidth = 400; // Set your desired width
		//     $newHeight = 300; // Set your desired height
		//     $new_image_tag = preg_replace('/(width|height)="(\d+)"/i', 'width="' . $newWidth . '" height="' . $newHeight . '"', $matches[0]);

		//     // Add inline CSS to the image tag
		//     $inline_css = 'style="object-fit: fill;"'; // Modify the inline CSS as needed
		//     $new_image_tag = preg_replace('/<img(.*?)>/i', '<img$1 ' . $inline_css . '>', $new_image_tag);

		//     return $new_image_tag;
		// }, $content);
		/**Modify the height and width of images in the post content [End] **/

		// $dom = new DOMDocument();
		// $dom->loadHTML($content);

		// $anchor_tags = array();

		// // Find all anchor tags in the post content
		// foreach ($dom->getElementsByTagName('a') as $anchor) {
		// $anchor_tags[] = array(
		//     'text' => $anchor->nodeValue,
		//     'href' => $anchor->getAttribute('href'),
		// );

		// // Remove the anchor tag from the DOM
		// $anchor->parentNode->removeChild($anchor);
		// }

		// // Get the modified post content after removing anchor tags
		// $modified_content = $dom->saveHTML();


		$post_thumbnail = (has_post_thumbnail($id)) ? get_the_post_thumbnail_url($id) : null;

		// Get the post publish date
		$publish_date = get_the_date('Y-m-d H:i:s', $post);

		// Format the date as needed (e.g., to 'M d, Y')
		$formatted_date = date('M d, Y', strtotime($publish_date));

		$posts_data[] = (object) array(
			'id' => $id,
			'slug' => $post->post_name,
			'type' => $post->post_type,
			'title' => $post->post_title,
			//'content' => wp_strip_all_tags($post->post_content), //To remove html & css from content in response(getting only raw content)
			'content' => $content,
			//'content' => $post_content,
			//'content' => apply_filters('the_content', get_the_content()),
			'featured_img_src' => $post_thumbnail,
			//'post_published_date' => $post->post_date,
			'post_published_date' => $formatted_date,  // Updated date format
		);
	}
	//return $posts_data;  
	$response = array(
		'statusCode' => 200,
		'message' => 'Success',
		'data' => $posts_data,
	);
	return new WP_REST_Response($response);
	//return strip_tags($excerpt);                 
}

/* Creating custom API END */



/**Admin css [Start] */
function custom_admin_css()
{
	echo '<style>
        /* Your custom CSS rules go here */
		/**.widefat td{
			white-space: nowrap;
		}
		.table-view-list {
			min-width: 100% !important;
			width : auto !important; 
			overflow-x: auto;
  			//display: block;
		}*/
		.widefat td{
			white-space: nowrap;
		}
		.wp-list-table {
			overflow-x: auto !important;
			min-width: 100% !important;
			width : auto !important; 
			display: block;
		}
		
		/* Adjust the scrollbar appearance as needed */
		/* Create a sticky horizontal scrollbar after the posts listing */

		.scrollbar-spacer {
			height: 12px; /* Adjust the height as needed */
			background: #bdbdbd; /* Background color for the spacer */
			position: sticky;
			overflow-x: auto !important;
		}
		.wp-list-table::-webkit-scrollbar {
			height: 12px;
		}
		
		.wp-list-table::-webkit-scrollbar-track {
			background: #bdbdbd;
		}
		
		.wp-list-table::-webkit-scrollbar-thumb {
			background: #888;
		}
    </style>';
?>
	<script>
		jQuery(document).ready(function($) {
			// Insert a container after the posts listing
			$('.wp-list-table').after('<div class="scrollbar-spacer"></div>');
		});
	</script>
<?php
}
add_action('admin_footer', 'custom_admin_css');
/**Admin css [End] */



/**Get current tag val optimized START */
add_action('wp_ajax_get_tag_val_optimized', 'get_tag_callback_optimized');
add_action('wp_ajax_nopriv_get_tag_val_optimized', 'get_tag_callback_optimized');
function get_tag_callback_optimized()
{
	$tag = $_POST['param'];
	$lower_tag = strtolower($tag);
	$tag_slug = str_replace(' ', '-', $lower_tag);
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if (!$tag) {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
		);
		$loop = new WP_Query($args);
		$total_pg =  $loop->max_num_pages;
	} else {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'tag' => $tag_slug,
		);
		$loop = new WP_Query($args);
		$total_pg = $loop->max_num_pages;
	}
	$url = $_POST['base'];
	if ($tag) {
		$newbase = 'https://transdirect.plutustec.in/tag/' . $tag_slug;
	} else {
		$newbase = 'https://transdirect.plutustec.in/latest-blog/';
	}
?>
	<div class="row mt-5">
		<?php
		if ($tag) {
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'orderby' => 'date',
				'order' => 'DESC',
				'tag' => $tag_slug,
				'paged' => $paged
			);
		} else {
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'orderby' => 'date',
				'order' => 'DESC',
				'paged' => $paged
			);
		}
		$firstPosts = array();
		$loop = new WP_Query($args);
		?>
		<?php
		while ($loop->have_posts()) : $loop->the_post();
			$post_id = get_the_ID();
			$firstPosts[] = $post_id;
		?>
			<div class="col-12 wow fadeInUp mb-3 " data-wow-duration="2s">
				<div class="card item fitness">
					<div class="row no-gutters raw">
						<div class="col-md-8">
							<?php $url = wp_get_attachment_url(get_post_thumbnail_id($loop->ID)); ?>
							<a href="<?php the_permalink(); ?>"><img class="img-fluid" src="<?php echo $url; ?>" alt="..."></a>
						</div>
						<div class="col-md-4">
							<div class="card-body d-flex flex-wrap h-100 flex-column">
								<div>
									<?php $posttags = get_the_tags();
									if ($posttags) {
										foreach ($posttags as $tag) { ?>
											<span class="badge badge-secondary"><span class="badge badge-secondary"><?php echo $tag->name; ?></span></span>
									<?php }
									} ?>
								</div>
								<h3 class="card-text"><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h3>
								<div class="star-bottom d-flex flex-column justify-content-between">
									<div class="star-group">
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
										<i class="fa-solid fa-star main-color"></i>
									</div>
									<div class="drop-box">
										<div class="drop-img mr-2">
											<img class="img-fluid" src="https://transdirect.plutustec.in/wp-content/uploads/2022/06/spheres.svg" />
										</div>
										<div class="">
											<span><strong><?php echo get_the_author(); ?></strong></span>
											<span>-</span>
											<span class="text-muted">recovr.com</span>
											<p class="mb-0 text-muted"><strong><?php echo get_the_date('F jS, Y'); ?></strong></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		<?php
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ($tag) {
			$args = array(
				'post_type' => 'post',
				'orderby' => 'date',
				'order' => 'DESC',
				'post__not_in' => $firstPosts,
				'posts_per_page' => 6,
				'tag' => $tag_slug,
				'paged' => $paged
			);
		} else {
			$args = array(
				'post_type' => 'post',
				'orderby' => 'date',
				'order' => 'DESC',
				'post__not_in' => $firstPosts,
				'paged' => $paged,
				'posts_per_page' => 6,
			);
		}
		$loop = new WP_Query($args);
		while ($loop->have_posts()) : $loop->the_post();
		?>
			<div class="col-md-6 wow fadeInUp mb-3" data-wow-duration="2s">
				<div class="card item life h-100">
					<div>
						<?php $url = wp_get_attachment_url(get_post_thumbnail_id($loop->ID)); ?>
						<a href="<?php the_permalink(); ?>"><img src="<?php echo $url; ?>" class="card-img-top" alt="..."></a>
					</div>
					<div class="card-body d-flex flex-wrap h-100 flex-column">
						<div>
							<?php $posttags = get_the_tags();
							if ($posttags) {
								foreach ($posttags as $tag) { ?>
									<span class="badge badge-secondary"><span class="badge badge-secondary"><?php echo $tag->name; ?></span></span>
							<?php }
							} ?>
						</div>
						<h3 class="card-text"><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h3>
						<div class="star-bottom d-flex flex-column justify-content-between">
							<div class="star-group">
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
								<i class="fa-solid fa-star main-color"></i>
							</div>
							<div class="drop-box">
								<div class="drop-img mr-2">
									<img class="img-fluid" src="https://transdirect.plutustec.in/wp-content/uploads/2022/06/spheres.svg" />
								</div>
								<div class="">
									<span><strong><?php echo get_the_author(); ?></strong></span>
									<span>-</span>
									<span class="text-muted">recovr.com</span>
									<p class="mb-0 text-muted"><strong><?php echo get_the_date('F jS, Y'); ?></strong></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		endwhile;
		?>
		<?php wp_reset_postdata(); ?>
	</div>

	<!-- Start Pagination - WP-PageNavi -->
	<?php

	$big = 999999999;
	// Fallback if there is not base set.
	$fallback_base = str_replace($big, '%#%', esc_url(get_pagenum_link($big)));

	if ($tag) {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'post_status' => 'publish',
			'tag' => $tag_slug
		);
		//echo '<pre>';
		//print_r($args);
	} else {
		$args = array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
			'post_status' => 'publish'
		);
	}

	// Set the base.
	$base = isset($newbase) ? trailingslashit($newbase) . '%_%' : $fallback_base;
	$tag =  '<div class="append_page"><div class="pagination-box wow fadeInUp mb-3 filter-pagination pagination">';
	$tag .=  paginate_links(array(
		'base' => $base,
		'format' => 'page/%#%',
		'current' => max(1, get_query_var('paged')),
		'total' => $total_pg,
		'show_all' => true,
		'prev_text' => '<<',
		'next_text' => '>>'
	));
	$tag .=  '</div></div>';
	//echo $loop->max_num_pages;
	?>
	<?php
	//if ($loop->max_num_pages > 1) : 
	?>
	<ul class="pagination">
		<li class="tags"><?php echo $tag; ?></li>
	</ul>
	<?php //endif;
	?>
	<!-- End Pagination -->

	<div class="my-4 wow fadeInUp mb-3 Inside-filter">
		<div class="news-later">
			<div class="row align-items-center">
				<div class="col-md-6 wow fadeInUp mb-3 mb-md-0 line-text">
					<h5 class="footer-top-subtitile"> Subscribe to our Newsletter </h5>
					<h3 class="footer-title"> <span class="news-letter-text">For the latest in fitness,<br>wellbeing and trainer tips</span> </h3>
				</div>
				<div class="col-md-6 wow fadeInUp mb-3">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Email">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary" type="button" id="button-addon2">Sign Me Up</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	die;
}
/**Get current tag val END */

// jquery form validation ajax-call [Start]
add_action('wp_ajax_result_page_sub', 'result_page_sub_callback');
add_action('wp_ajax_nopriv_result_page_sub', 'result_page_sub_callback');
function result_page_sub_callback()
{
	//print_r($_POST);
	//return true;
	echo true;
	die;
}
// jquery form validation ajax-call [End]


add_action('init', 'create_and_store_global_var_val');
function create_and_store_global_var_val()
{
	/**Creating global variable [Start] */
	global $domain, $flag;
	$domain = 'Hey , I am gloal variable';
	$flag = false;
	/**Creating global variable [End] */

	/**Creating XML file [Start] */
	$sitemap = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
	//$path = ABSPATH;
	//echo $path;       
	$abspath = ABSPATH . 'wp-content/my-xml-files';
	$sitemap .= '<Export_Bfast_POINTEX>' . "\n" .

		"\t" . '<Entete_Commande>' . "\n" .
		"\t\t" . "<Id_commande>  KAVITA </Id_commande>" . "\n" .
		"\t" . '</Entete_Commande>' . "\n" .

		'</Export_Bfast_POINTEX>' . "\n\n";

	// To store file in any folders
	/* $filename = 'order_' . date('m-d-Y_hia') . '.xml';
	$fp = fopen($abspath . '/' . $filename, 'w');
	fwrite($fp, $sitemap);
	fclose($fp); */

	/* Download xml file [Start] */
	/* 	header('Content-type: text/xml');
    header('Content-Disposition: attachment; filename="text.xml"');
    echo $sitemap;
    exit(); */
	/* Download xml file [End] */
	/* Creating XML file [End] */
}


/**Creating custom cron for add-update post on admin panel [Start] */
/* function my_cron_schedules($schedules)
{
	if (!isset($schedules["1day"])) {
		$schedules["1day"] = array(
			'interval' => 86400,
			'display' => __('Once every day')
		);
	}
	return $schedules;
}
add_filter('cron_schedules', 'my_cron_schedules');

if (!wp_next_scheduled('my_task_hook')) {
	wp_schedule_event(time(), '1day', 'my_task_hook');
}

//add_action('init', 'my_task_function');
//add_action('init', 'my_task_function');
add_action('my_task_hook', 'my_task_function');
function my_task_function()
{
	require_once ABSPATH .'blog-posts-cron.php';
} */

//echo "Ignore message in git-commit due to git ignore file";

// Add a new interval of 180 seconds
// See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
/* add_filter('cron_schedules', 'isa_add_every_one_minutes');
function isa_add_every_one_minutes($schedules)
{
	$schedules['every_one_minutes'] = array(
		'interval'  => 60,
		'display'   => __('Every 1 Minutes')
	);
	return $schedules;
}

// Schedule an action if it's not already scheduled
if (!wp_next_scheduled('isa_add_every_one_minutes')) {
	wp_schedule_event(time(), 'every_one_minutes', 'isa_add_every_one_minutes');
}

// Hook into that action that'll fire every one minutes
add_action('isa_add_every_one_minutes', 'every_one_minutes_event_func');
function every_one_minutes_event_func()
{

}
 */

//cron file
/* add_filter('cron_schedules', 'example_add_cron_interval');

function example_add_cron_interval($schedules)
{
	$schedules['sixty_seconds'] = array(
		'interval' => 86400, //[interval] => 86400 once a daily
		'display' => esc_html__('Every one minute'),
	);

	return $schedules;
}

add_action('wp', 'launch_the_action');
function launch_the_action()
{
	if (!wp_next_scheduled("custom_cron_blog")) {
		wp_schedule_event(time(), "sixty_seconds", "custom_cron_blog");
	}
}

add_action('custom_cron_blog', 'create_update_blog');
//add_action('init', 'create_update_blog');
function create_update_blog()
{
	// do something
	require_once(ABSPATH . 'wp-load.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	global $wpdb;
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://recovr.com/wp-json/custom/v1/get-blog-posts',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);

	curl_close($curl);
	$res = json_decode($response);
	foreach ($res->data as $post_data) {
		$post_id = $post_data->post_id;
		$post_title = $post_data->post_title;
		$post_status = $post_data->post_status;
		$post_content = $post_data->post_content;
		$post_date = $post_data->post_published_date;
		$post_tags = $post_data->post_tag;
		$post_category = $post_data->post_category;

		$category_ids = array();

		// Loop through category names and get their IDs
		foreach ($post_category as $category_name) {
			$category = get_term_by('name', $category_name, 'category');
			if ($category && !is_wp_error($category)) {
				$category_ids[] = $category->term_id;
			} else {
				// Category doesn't exist, so create it
				$new_category_id = wp_create_category($category_name);
				if (!is_wp_error($new_category_id)) {
					$category_ids[] = $new_category_id;
				}
			}
		}
		// echo "category ids:";
		// print_r($category_ids);

		//$post_author = $post_data->post_author;
		$post_author_name = $post_data->post_author; // Replace with the actual author's name from your API data.
		$post_author_id = null;

		// Check if the author already exists.
		$existing_author = get_user_by('login', $post_author_name);

		if ($existing_author) {
			// Author exists, get their ID.
			$post_author_id = $existing_author->ID;
			//echo "user exist id:" . $post_author_id;
		} else {
			// Author doesn't exist, create a new user with the "author" role.
			$user_data = array(
				'user_login' => $post_author_name,
				'user_pass' => wp_generate_password(),
				'display_name' => $post_author_name,
				'role' => 'author', // Specify the role here.
			);

			$post_author_id = wp_insert_user($user_data);
			//echo "user created id:" . $post_author_id;
		}
		$image = $post_data->post_featured_img_src; // url of image

		//check if post with $post_data->post_id is already in database, if so, update post $post_data->post_id
		if (get_post_status($post_data->post_id)) {
			$post = array(
				'ID'  =>   $post_id,
				'post_title' =>  $post_title,
				'post_content' =>  $post_content,
				'post_status' =>  $post_status,
				'post_author' =>  $post_author_id,
				'post_type' => 'post',
				'post_date' => $post_date,
				'tags_input' => $post_tags,
				// 'tax_query' => array(
				// 	'relation' => 'AND',
				// 	array(
				// 		'taxonomy' => 'category', // Specify the taxonomy (e.g., 'category', 'custom_taxonomy').
				// 		'field' => 'name', // You can specify the field to search in (e.g., 'name', 'term_id', 'slug').
				// 		'terms' => $post_category, // Replace with the category names you want to query.
				// 		'operator' => 'IN', // Use 'IN' to include posts in any of the specified categories.
				// 	),
				// ),
			);

			// Update post categories using wp_set_post_categories
			if (!empty($category_ids)) {
				wp_set_post_categories($post_id, $category_ids);
			}
			// print_r($post);
			// echo '<br>';
			$post_id = wp_update_post($post);

			// Set the featured image
			// Extract the basename from the image URL
			$image_url_basename = wp_basename($image);
			//echo $image_url_basename;


			// Construct the SQL query to find the image by basename
			$sql = $wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s",
				'%' . $wpdb->esc_like($image_url_basename)
			);

			$image_id = $wpdb->get_var($sql);
			if (has_post_thumbnail($post_id)) {
				echo "image already set";
			} else {
				if ($image_id) {
					echo "image found";
					// An image with the same basename exists in the media library; set it as the featured image
					set_post_thumbnail($post_id, $image_id);
				} else {
					// The image does not exist in the media library, so let's add it
					echo "new image found & uploaded";
					$image_id = media_sideload_image($image, $post_id, '', 'id');
					if (!is_wp_error($image_id)) {
						set_post_thumbnail($post_id, $image_id);
					}
				}
			}
		}
		//if not in database, add post with $post_data->post_id
		else {
			$post = array(
				'import_id'  =>   $post_id, //Cretae post with custom id (get id by third-party api call)
				'post_title' =>  $post_title,
				'post_content' =>  $post_content,
				'post_status' =>  $post_status,
				'post_author' =>  $post_author_id,
				'post_type' => 'post',
				'post_date' => $post_date,
				'tags_input' => $post_tags,
				// 'tax_query' => array(
				// 	'relation' => 'AND',
				// 	array(
				// 		'taxonomy' => 'category', // Specify the taxonomy (e.g., 'category', 'custom_taxonomy').
				// 		'field' => 'name', // You can specify the field to search in (e.g., 'name', 'term_id', 'slug').
				// 		'terms' => $post_category, // Replace with the category names you want to query.
				// 		'operator' => 'IN', // Use 'IN' to include posts in any of the specified categories.
				// 	),
				// ),
			);
			// Update post categories using wp_set_post_categories
			if (!empty($category_ids)) {
				wp_set_post_categories($post_id, $category_ids);
			}
			// print_r($post);
			// echo '<br>';
			$post_id = wp_insert_post($post);

			// Set the featured image
			// Extract the basename from the image URL
			$image_url_basename = wp_basename($image);
			global $wpdb;

			// Construct the SQL query to find the image by basename
			$sql = $wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s",
				'%' . $wpdb->esc_like($image_url_basename)
			);

			$image_id = $wpdb->get_var($sql);
			if (has_post_thumbnail($post_id)) {
				echo "image already set";
			} else {
				if ($image_id) {
					echo "image found";
					// An image with the same basename exists in the media library; set it as the featured image
					set_post_thumbnail($post_id, $image_id);
				} else {
					// The image does not exist in the media library, so let's add it
					echo "new image found & uploaded";
					$image_id = media_sideload_image($image, $post_id, '', 'id');
					if (!is_wp_error($image_id)) {
						set_post_thumbnail($post_id, $image_id);
					}
				}
			}
		}
	}
} */


/**Image upload & set in cron job [Start] */
function upload_img_from_api(){
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://recovr.com/wp-json/custom/v1/get-blog-posts',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $res = json_decode($response);
    foreach ($res->data as $post_data) {
        /**Get API data [Start] */
        $post_id = $post_data->post_id;
        $image_url = $post_data->post_featured_img_src; // URL of the image
		//echo $image_url;
		$img_name = 'apipost_' . $post_id;
        $unique_image_name = 'apipost_' . $post_id.'.jpg'; // Generate a unique identifier for the image
        // Check if an image with the same unique identifier already exists in the media library
		$args = array(
			'post_type' => 'attachment',
			'post_status' => 'any',
			'posts_per_page' => -1,
			'fields' => 'ids',
			's' => $img_name, // Search by the image name
		);
	
		$query = new WP_Query($args);
		
        if ($query->have_posts()) {
            // The image with the same unique identifier exists, and $existing_attachment contains its details
            // Set the existing image as the featured image for the post
            $attachment_id = $query->posts[0];
            set_post_thumbnail($post_id, $attachment_id);
            echo '<br>Image already exists with ID: ' . $query->posts[0] . ' and set as featured image for post ' . $post_id;
        } else {
            // The image doesn't exist, download and upload it as before
            $temp_file = download_url($image_url);
            
            // ... (existing code for downloading and uploading with the unique name)
            
            // Check for download errors
            if (is_wp_error($temp_file)) {
                // Handle the error, e.g., log it or display a message
                echo 'Error downloading image: ' . $temp_file->get_error_message();
            } else {
                // Prepare the upload file array with the unique name
                $file_array = array(
                    'name'     => $unique_image_name,
                    'tmp_name' => $temp_file
                );

                // Upload the image to the media library
                $attachment_id = media_handle_sideload($file_array, 0);
                
                // Check for upload errors
                if (is_wp_error($attachment_id)) {
                    // Handle the error, e.g., log it or display a message
                    echo 'Error uploading image: ' . $attachment_id->get_error_message();
                } else {
                    // Image was successfully uploaded, and $attachment_id contains the media ID
                    // Set the uploaded image as the featured image for the post
                    set_post_thumbnail($post_id, $attachment_id);
                }

                // Clean up the temporary file
                unlink($temp_file);
            }
        }
    }
}
/**Image upload & set in cron job [End] */

/**Create cutom API to send post data [Start] */
/**Get pots data API [Start] */
add_action( 'rest_api_init', 'custom_api_get_all_posts' );   

function custom_api_get_all_posts() {
    register_rest_route( 'custom/v1', '/get-blog-posts', array(
        'methods' => 'GET',
        'callback' => 'custom_api_get_all_posts_callback'
    ));
}

add_action('init','custom_api_get_all_posts_callback');

function custom_api_get_all_posts_callback( $request ) {
    // Initialize the array that will receive the posts' data. 
    $posts_data = array();
    $posts = get_posts( array(
            'posts_per_page' => -1,   
		    'post_status' => 'publish',
			'post_type' => array( 'post')
        )
    ); 
	if (empty($posts)) {
		//return new WP_Error('post_not_found', 'Post not found', array('status' => 404));
		$response = array(
			'statusCode' => 404,
			'message' => 'post_not_found',
			'data' => 'null',
		);       
		return new WP_REST_Response($response);
	}
    // Loop through the posts and push the desired data to the array we've initialized earlier in the form of an object
    foreach( $posts as $post ) {
        $id = $post->ID; 
		$author_id=$post->post_author;
		$author_name = get_the_author_meta( 'display_name', $author_id );
		$tags_name = wp_get_post_terms($post->ID, 'post_tag', array("fields" => "names"));
		$tags_slug = wp_get_post_terms($post->ID, 'post_tag', array("fields" => "slugs"));
    	$categories_name = wp_get_post_terms($post->ID, 'category', array("fields" => "names"));
		$categories_slug = wp_get_post_terms($post->ID, 'category', array("fields" => "slugs"));

		/**Get featured image url [Start] */
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
		if(!empty($post_thumbnail_id)) {
		$post_thumbnail =  wp_get_attachment_url( get_post_thumbnail_id($post->ID));
		}else{
			$post_thumbnail = 'null';
		}
		if ($post_thumbnail === false) {
			$post_thumbnail = "";
		}
		/**Get featured image url [End] */

		/**Get posts content & clean up unwanted characters in post-content [Start] */
		$post_content = $post->post_content;
		// Use DOMDocument to parse the post content
		 $dom = new DOMDocument('1.0', 'UTF-8'); // Specify UTF-8 encoding
	     $dom->loadHTML(mb_convert_encoding($post_content, 'HTML-ENTITIES', 'UTF-8')); // Convert encoding
		// // Find all anchor tags in the post content
		// $anchorsToRemove = [];
		// foreach ($dom->getElementsByTagName('a') as $anchor) {
    	// 	$anchorsToRemove[] = $anchor;
		// }
		// // Remove the anchor tags from the DOM
		// foreach ($anchorsToRemove as $anchor) {
    	// 	$anchor->parentNode->removeChild($anchor);
		// }
		// // Get the modified post content after removing anchor tags
		// $modified_content = $dom->saveHTML();
		// // Clean up unwanted characters like Â and &nbsp;
		// $modified_content = str_replace(array("Â", "&nbsp;"), '', $modified_content);
		/**Get posts content & clean up unwanted characters in post-content [End] */

		 // Find all anchor tags in the post content
		 $anchorsToRemove = [];
		 foreach ($dom->getElementsByTagName('a') as $anchor) {
			 $parentElement = $anchor->parentNode;
			 if ($parentElement->tagName === 'figure') {
				 // If the anchor tag is within a figure element, remove only the anchor tag itself
				 $anchorsToRemove[] = $anchor;
			 }else{
				$anchorsToRemove[] = $anchor;
			 }
		 }
	 
		 // Remove the anchor tags from the DOM
		 foreach ($anchorsToRemove as $anchor) {
			 $parentElement = $anchor->parentNode;
			 $anchorChildren = [];
			 foreach ($anchor->childNodes as $child) {
				 $anchorChildren[] = $child;
			 }
			 // Remove the anchor tag
			 $parentElement->removeChild($anchor);
			 // Append the child nodes of the anchor tag back to the parent (figure) element
			 foreach ($anchorChildren as $child) {
				 $parentElement->appendChild($child);
			 }
		 }
	 
		 // Get the modified post content
		 $post_content = $dom->saveHTML();
	 
		 // Clean up unwanted characters like Â and &nbsp;
		 $post_content = str_replace(array("Â", "&nbsp;"), '', $post_content);

        $posts_data[] = array( 
            'post_id' => $id, 
            'post_type' => $post->post_type,
			'post_slug' => $post->post_name, 
            'post_title' => $post->post_title,
			'post_content' => $post_content,
            'post_featured_img_src' => $post_thumbnail,
			'post_status' => $post->post_status,
			'post_author' => $author_name,
			'post_category' => $categories_name,
			'post_category_slug' => $categories_slug,
			'post_tag' => $tags_name,
			'post_tag_slug' => $tags_slug,
			'post_published_date' => $post->post_date,
        );
    }    
	$response = array(
		'statusCode' => 200,
		'message' => 'Success',
		'data' => $posts_data,
	);       
	return new WP_REST_Response($response, 200);            
} 
/**Get pots data API [End] */

// function custom_500_error_handling() {
//     //if (is_500_error()) {
//         status_header(500);
//         include(get_template_directory() . '/500-error.php'); // Adjust the path as needed
//         exit;
//    // }
// }

// function is_500_error() {
//     return (
//         defined('WP_ADMIN') && WP_ADMIN === true &&
//         defined('WPINC') && file_exists(WPINC . '/class-wp-error.php')
//     );
// }
// add_action('wp', 'custom_500_error_handling');


?>