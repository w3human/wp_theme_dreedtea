<?php 
	/* Clear Float Element */ 
	function clearFloat() {return '<div style="clear: both;"></div>';}

	/* Random Token */
	function createToken($leng=32) {
		$letters="qwertyuiopasdfjklzxcvbnm"; $token="";
		
		for($T=1; $T<=$leng; $T++) {
			$type=rand(1, 2);
						
			switch ($type) {
				case 1: 
					$token.=$letters[(rand(0, 26))];
					break;
								
				default:
					$token.=rand(0, 9);
			}
		}
		
		return $token;
	}
	
	$wp_imageSizes=array("thumbnail", "medium", "large", "full");
	
	/* Theme Options */
	include "theme-options.php"; $theme_options=get_option("the_theme_options");

	/* Registing Menus */
	register_nav_menus(array(
		"main_menu" => "Main Menu",
		"top_menu" => "Top Menu",
	));
	
	/* Register Sidebars */
	function widget_area($id, $name, $description) {
		register_sidebar(array(
			"id" => $id,
			"name" => $name,
			"description" => $description,
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		));
	}

	function theme_widgets_init() {
		widget_area("sidebar_a", "Sidebar A", "The Sidebar");
	}
	
	add_action("init", "theme_widgets_init");
	
	/* Post Functions */
	/* Support Thumbnail */ add_theme_support("post-thumbnails");
	add_filter('the_content', 'wpautop');
		
	function so_handle_038($content) {
    $content = str_replace(array("&#038;","&amp;"), "&", $content); 
		return $content;
	}

	add_filter('the_content', 'so_handle_038', 199, 1);
			
	/* Get Photo From Post */ function post_pic($atmentSize, $defaultSize) {
		global $post, $matches;
		
		$positables=array("thumbnail", "medium", "large", "full");
		$atment_size=((in_array($atmentSize, $positables))?$atment_size:"full");
		
		if (has_post_thumbnail($post->ID)) {
			$image_src=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $atment_size); 
			$imageURL=$image_src[0];
		}
		
		if ($imageURL=="" || $imageURL==null) {
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			if (! empty($matches[1][0])) {
				$imageURL=$matches[1][0];
			}
		}
		
		if (empty($imageURL) && ! empty($defaultSize)) {
			$imageURL="http://placehold.it/". $defaultSize ."&text=%3F";
		}
		
		return $imageURL;
	}
	
	function get_postPreview($atment_Size, $default_Size, $classNames, $description) {
		global $post;
		
		$aDescription=esc_attr(strip_tags($post->post_content)); if (empty($aDescription)) {$classNames.=" noExcerpt";}
		
		$html="<div class='postPreview ". $classNames ."'>";
			$html.="<a href='". get_permalink($post->ID) ."' class='link'>";
				$html.="<div class='theImage' style='background-image: url(". post_pic($atment_Size, $default_Size) .");'></div>";
				$html.="<h3 class='title'>". $post->post_title ."</h3>";
			$html.="</a>";	
			
			if (! empty($description) && ! empty($aDescription)) {
				if (is_int($description)) {$html.="<p class='excerpt'>". substr($aDescription, 0, $description) ."...</p>";}
				elseif ($description==true) {$html.="<p class='excerpt'>". $aDescription ."...</p>";}
			}
		$html.="</div>";
		
		return $html;
	}
	
	function postPreview($atment_Size, $default_Size, $classNames, $description) {
		global $post;
		
		echo get_postPreview($atment_Size, $default_Size, $classNames, $description);
	}
	
	function anArchive() {
		global $post; $count=0;
		
		echo '<div id="anArchive">';
			while (have_posts()) {
				the_post(); 
				$count=$count+1;
				
				postPreview("large", "300X220", "postOf", 110);
			}
		echo '</div>';
		
		echo clearFloat();
	}
	
	/* Comments Props */ include "comment_vars.php";
	
	/* Comment Callback/Walker */
	function comment_walker($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; extract($args, EXTR_SKIP); 
		global $user_identity, $comment_inputEles, $replyingTo, $user_string, $post; ?>
		 		
		<li <?php comment_class("aComment"); ?> id="comment-<?= comment_ID() ?>">
			<aside class="goLeft" id="aside">
				<span id="comment-author-avatar"><?= get_avatar($comment->comment_author_email, "40"); ?></span>
			
				<?php 
					comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])));
					edit_comment_link(__('Edit'));
				?>
			</aside>
		
			<div class="goLeft" id="main">
				<div class="top" name="commentTOP">
					<div class="goLeft"><span class="comment-author"><?= get_comment_author(); ?></span>&nbsp;<span class="says">says:</span></div>
					<div class="goRight"><time class="comment-date"><?= get_comment_date()."  ".get_comment_time(); ?></time></div>
					<?= clearFloat(); ?>
				</div>
				
				<div class="middle">
					<?php if ($comment->comment_approved=="0") : ?>
						<em><?php _e('Your comment is awaiting moderation.') ?></em>
					<?php else : ?>
						<?= comment_text(); ?>
					<?php endif; ?>
				</div>
			</div>
		
			<?= clearFloat(); ?>
		</li>
		
		<?= clearFloat(); ?>
	
		<?php if ($replyingTo==get_comment_ID()) { ?>
			<li class="depth-<?= ($depth+1) ?>" id="respond">
				<div class="writeAcomment">
					<div id="beforeForm">
						<div class="goLeft">
							<h2 class="title">Reply to comment</h2>
						</div>
					
						<div class="goRight">
							<?= cancel_comment_reply_link('<img src="'. get_bloginfo('template_url') .'/resources/icons/x.png" id="close-reply" />'); ?>
						</div>
					
						<?= clearFloat() ?>
					</div>

					<form action="<?= home_url() ?>/wp-comments-post.php" method="post" name="reply-to-comment" class="validateThis">
						<div class="top">
							<?php if (is_user_logged_in()) : ?>
								<div class="goLeft"><?= $user_string["name"] ?></div>
								<div class="goRight"><?= $user_string["logout"] ?></div>							
							<?php else : ?>
								<div class="goLeft"><?= $comment_inputEles["name"] ?></div>
								<div class="goRight"><?= $comment_inputEles["email"] ?></div>
							<?php endif; ?>
							
							<?= clearFloat(); ?>
						</div>
						
						<div class="middle"><?= $comment_inputEles["comment"] ?></div>
						
						<div class="bottom">
							<div class="goLeft"><input name="submit"type="submit" value="Send It" /></div>
							<div class="goRight"><?php if (! is_user_logged_in()) { ?><em id="email_warning">Your email address will <b>not be</b> published.</em><?php } ?></div>
							<?= clearFloat(); ?>
						</div>
						
						<?= clearFloat(); ?>
						
						<?php comment_id_fields(); do_action('comment_form', $post->ID); ?>
					</form>
				</div>
			</li>
			
			<?= clearFloat(); ?>	
<?php	
		}
	}
	
	function postGallery($atts) {
		global $post, $site;
		
		$d_atts=array("columns" => "3", "order" => "ASC", "orderby" => "menu_order",); 
		
		$attribute=((is_array($atts))?array_merge($d_atts, $atts):$d_atts);
		
		if ($attribute["columns"]<1) {$attribute["columns"]="3";}
		
		$galleryOrder=array();
		
		$css=""; for ($col=1; $col<=9; $col++) {$css.="#post_gallery.col". $col ." .item{width: ". (90/$col) ."%;} \n";}
		
		$galleryHTML.='
			<style type="text/css">'. $css .'</style>
			<script type="text/javascript">var postGallery_items=new Object();</script>
			<div id="post_gallery" class="col'. $attribute['columns'] .'">
		';		
			if (isset($attribute["ids"])) {
				$the_images=get_posts(array(
					"post_type" => "attachment",
					"post_mime_type" => "image",
					"include" => $attribute["ids"],
					"orderby" => "id",
					"numberposts" => -1,
				));
			}
			else {
				$the_images=get_posts(array(
					"post_parent" => $post->ID,
					"post_type" => "attachment",
					"post_mime_type" => "image",
					"orderby" => $attribute["orderby"],
					"numberposts" => -1,
				));
			}
			 
			foreach ($the_images as $an_image) {
				$galleryOrder[]=$an_image->ID;
				
				$image=array();
					$image["thumbnail"]=wp_get_attachment_image_src($an_image->ID, "thumbnail"); $image["thumbnail"]=$image["thumbnail"][0];
					$image["medium"]=wp_get_attachment_image_src($an_image->ID, "medium"); $image["medium"]=$image["medium"][0];
					$image["large"]=wp_get_attachment_image_src($an_image->ID, "large"); $image["large"]=$image["large"][0];
					$image["full"]=wp_get_attachment_image_src($an_image->ID, "full"); $image["full"]=$image["full"][0];
				
				$imageToJS=""; foreach($image as $name => $value) {$imageToJS.='"'. $name .'": "'. $value .'", ';}
				
				$galleryHTML.='
					<div id="'. $an_image->ID .'" class="item" name="postGallery_item" style="background-image: url('. (($attribute["cols"]<=2)?$image["large"]:$image["thumbnail"]) .');"></div>
					
					<script type="text/javascript">
						postGallery_items["'. $an_image->ID .'"]={
							"title":  "'. $an_image->post_title .'",
							"caption": "'. rawurlencode($an_image->post_excerpt) .'",
							"description": "'. rawurlencode($an_image->post_content) .'",
							
							"image": {
								'. $imageToJS .'
								"alt": "'. get_post_meta($an_image->ID, '_wp_attachment_image_alt', true) .'",
							},
						};
					</script>
				';
			}
			
		$galleryHTML.='
			</div>
			
			<script type="text/javascript">var postGallery_items_order=new Array('. join(", ", $galleryOrder) .');</script>
		';
		
		return $galleryHTML;
	}
	
	add_shortcode("gallery", "postGallery");
	
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
			
	function convert_fsVars() {
		global $flashsale_data, $product_data, $productsInFlashsale, $wp_imageSizes;
		
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
	}
?>
