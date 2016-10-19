<?php 
	require_once "../../../../../wp-config.php";
	
	header("Content-type: text/javascript");
	
	// Flashsale Veritables
	$get_flashsales=get_posts(array("post_type" => "flashsale", "numberposts" => -1, "order" => "ASC",  "orderby" => "menu_order", "post_status" => "any"));
	$get_products=get_posts(array("post_type" => "product", "numberposts" => -1, "order" => "ASC",  "orderby" => "menu_order", "post_status" => "any"));
	
	$flashsale_data=array();
	$product_data=array();	
	$productsInFlashsale=array();
	$styleSpotlight_ofFlashsale=array();
	
	foreach ($get_flashsales as $fs_post) {
		$current_fs=$fs_post->post_title;
		
		$flashsale_data[($current_fs)]=array(
			"id" => $fs_post->ID,
			"slug" => str_replace("-", "_", $fs_post->post_name),
			"name" => $current_fs,
			"description" => rawurlencode($fs_post->post_content),
			"status" => $fs_post->post_status,
			
			"catID" => get_cat_ID(get_post_meta($fs_post->ID, "categoryOfProducts", true)),
			"endDate" => get_post_meta($fs_post->ID, "enddate", true),
			"url" => $site["url"] . "/flashsale/" . $fs_post->post_name,
		);
	}
	
	foreach ($get_products as $p_post) {
		if (strpos($p_post->post_title, "[STYLE SPOTLIGHT]") == false) {
			$p_name=$p_post->post_title;
		
			foreach (wp_get_post_categories($p_post->ID) as $p_cat) {
				foreach ($flashsale_data as $fs_name => $varitables) {
					if ($varitables["catID"]==$p_cat) {
						$productsInFlashsale[($fs_name)][]=$p_name;
						break;
					}
				}
			}
			
			$image_src=wp_get_attachment_image_src(get_post_thumbnail_id($p_post->ID), "large"); 
				$featured_img=$image_src[0];
			
			$product_data[($p_name)]=array(
				"id" => $p_post->ID,
				"name" => $p_name,
				"price" => get_post_meta($p_post->ID, "price", true),
				"soldout" => get_post_meta($p_post->ID, "soldout", true),
				"description" => rawurlencode($p_post->post_content),
				"featured_image" => $featured_img,
				"addToCart" => rawurlencode(get_post_meta($p_post->ID, "addtocart", true)),
				"url" => get_permalink($p_post->ID),
			);
			
			$product_data[($p_name)]["images"]=array();
				$p_images=get_posts(array("post_parent" => $p_post->ID,"post_type" => "attachment", "post_mime_type" => "image","orderby" => "menu_order","numberposts" => -1,));
			
				foreach ($wp_imageSizes as $size) {
					$product_data[($p_name)]["images"][$size]=array();
					
					foreach ($p_images as $p_img) {
						$url=wp_get_attachment_image_src($p_img->ID, $size); $url=$url[0];
						$product_data[($p_name)]["images"][$size][]=$url;
					}
				}
		}
		else {
			foreach (wp_get_post_categories($p_post->ID) as $p_cat) {
				foreach ($flashsale_data as $fs_name => $varitables) {
					if ($varitables["catID"]==$p_cat) {
						$image_src=wp_get_attachment_image_src(get_post_thumbnail_id($p_post->ID), "full"); 
							$featured_img=$image_src[0];
						
						$styleSpotlight_ofFlashsale[$fs_name]=array(
							"picture" => $featured_img,
							"lines" => get_post_meta($p_post->ID, "addtocart", true),
							"quote" => $p_post->post_content,
						);
						
						break;
					}
				}
			}
			
		}
	}
			
	print_r("var flashsale_data=new Object(), allProducts=new Array(), product_data=new Object(), productsInFlashsale=new Object();");
	
	foreach ($flashsale_data as $fs_name => $valitables) {
		print_r('flashsale_data["'. $fs_name .'"]=new Object();'); print_r("\n");
		
		foreach ($valitables as $name => $value) {
			print_r('flashsale_data["'. $fs_name .'"]["'. $name .'"]="'. $value .'";'); print_r("\n");
		}
	}
	
	foreach ($product_data as $p_name => $valitables) {
		print_r('allProducts.splice(allProducts.length, 0, "'. $p_name .'");'); print_r("\n");
		print_r('product_data["'. $p_name .'"]=new Object();'); print_r("\n");
		
		foreach ($valitables as $name => $value) {
			if ($name != "images") {
				print_r('product_data["'. $p_name .'"]["'. $name .'"]="'. $value .'";'); print_r("\n");
			}
			else {
				print_r('product_data["'. $p_name .'"]["images"]=new Object();'); print_r("\n");
			}
		}
		
		foreach ($wp_imageSizes as $size) {
			print_r('product_data["'. $p_name .'"]["images"]["'. $size .'"]=new Array("'. join('", "', $valitables["images"][$size]) .'");'); print_r("\n");
		}
	}
			
	foreach ($productsInFlashsale as $fs_name => $products) {
		print_r('productsInFlashsale["'. $fs_name .'"]=new Array();'); print_r("\n");
		
		foreach ($products as $p_name) {
			print_r('productsInFlashsale["'. $fs_name .'"].splice(productsInFlashsale["'. $fs_name .'"].length, 0, "'. $p_name .'");'); print_r("\n");
		}
	}
?>
