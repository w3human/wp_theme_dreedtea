<?php 
	foreach ($productsInFlashsale as $fs_name => $p_names) {
		if (in_array($post->post_title, $p_names)) {
			$fs_data=$flashsale_data[($fs_name)];
			break;
		}
	}

	$theMiddle_layout="c";

	include "header.php";
?>
	<script type="text/javascript">
		var p_data=product_data[("<?= $post->post_title ?>")];
		
		var endDate=new Date("<?= $fs_data['endDate'] ?>");
	</script>

	<header id="headerTwo">
		<a href="<?= $fs_data['url'] ?>" id="fs_name" class="goLeft"><h1><?= $fs_data["name"] ?></h1></a>	
	
		<div class="goRight">
			<div class="fs_counter fsButton">
				<span class="saleIsLive">
					<span class="fs_icon pink-clock"></span>
					<span class="fs_icon endsIn"></span>
					<span class="words timer" id="timeLeft"></span>
				</span>
				
				<span class="saleHasEnded">
					<span class="fs_icon black-clock"></span>
					<span class="fs_icon saleEnded"></span>
				</span>
			</div>
			
			<a style="margin-left: 1em;" class="saleIsLive" href="https://www.e-junkie.com/ecom/gb.php?c=cart&cl=216404&ejc=2" target="ej_ejc" onClick="return EJEJC_lc(this);">
				<div class="fsButton cartButton viewCart">
					<span class="fs_icon shoppingCart"></span><span class="words">view cart</span>
				</div>
			</a>	
		</div>
		
		<?= clearFloat(); ?>
	</header>
	
	<div id="_main" class="productViewer">
		<div class="gallery">
			<div id="imageBeingViewed_con" class="zoom">
				<div id="imageBeingViewed" class="beingViewed"></div>
				<img id="zoomIMG" style="display: none;" />
				<p>Grab</p>
			</div>
									
			<div id="product_images" class="selectors"></div>
		</div>
		
		<div class="productInfo">
			<h1 class="title"><?= $post->post_title ?></h1>
			<div class="priceCon">$<span class="thePrice"><?= get_custom_field("price") ?></span></div>
			
			<div class="addToCart_form saleIsLive"><?= get_custom_field("addtocart") ?></div>
			
			<div class="soldOUT_warning addToCart_form">
				<div class="fsButton cartButton addToCart"><span class="fs_icon shoppingCart"></span><span class="words"><b>SOLD OUT</b></span></div>
			</div>
			
			
			<div class="description"><?= $post->post_content ?></div>
		</div>
		
		<?= clearFloat(); ?>
	</div>
<?php include "end-of-everything.php"; ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#imageBeingViewed_con").zoom({on: "grab",});
		});
	</script>

	<script type="text/javascript">
		function set_beingViewed(imgSRC) {
			var imageData=new Image();
				imageData.src=imgSRC;
			
			imageData.onload=function() {
				rClass(document.getElementById("imageBeingViewed_con"), ["wide", "tall"]);
			
				if (imageData.naturalHeight>imageData.naturalWidth) {aClass(document.getElementById("imageBeingViewed_con"), "tall");}
				else {aClass(document.getElementById("imageBeingViewed_con"), "wide");}
				
				elementWidth=$("#imageBeingViewed_con").width();
				elementHeight=$("#imageBeingViewed_con").height();
				
				sRatio={
					"width": (imageData.naturalWidth>elementWidth)?elementWidth/imageData.naturalWIdth : imageData.naturalWidth/elementWidth,
					"height": (imageData.naturalHeight>elementHeight)?elementHeight/imageData.naturalHeight : imageData.naturalHeight/elementHeight,
				};					
				
				$(".single-product div#imageBeingViewed").css("background-size", (imageData.naturalWidth * sRatio.height) + "px "+ (imageData.naturalHeight * sRatio.height) +"px");
			};
			
			document.getElementById("imageBeingViewed").style.backgroundImage="url("+ imgSRC +")";
			$("#imageBeingViewed_con .zoomImg, #imageBeingViewed_con #zoomIMG").attr("src", imgSRC);
		}
		
		set_beingViewed("<?= $product_data[($post->post_title)]['featured_image'] ?>");
		
		for (img=0; img<p_data["images"]["full"].length; img++) {
			var image_selector=document.createElement("input");
				image_selector.type="image";
				image_selector.id=img;
				image_selector.src=p_data["images"]["thumbnail"][img];
				
			image_selector.onclick=function() {
				set_beingViewed(p_data["images"]["large"][(this.id)]);
			};
			
			document.getElementById("product_images").appendChild(image_selector);
		}
	</script>
	
	<?php if (get_custom_field("soldout")=="true"): ?>
		<script type="text/javascript">aClass(doc_body, "ITEM_soldout");</script>
	<?php endif; ?>

	<style type="text/css">
		#imageBeingViewed_con p{position: absolute; top: 3px; right: 28px; color: #555; font: bold 13px/1 sans-serif;}
		#imageBeingViewed_con .zoomIcon{width: 33px; height: 33px; position: absolute; top: 0; right: 0; background:url(<?= $site["resources"] ?>/zoom-feature/icon.png);}
		#imageBeingViewed_con .zoom{display:inline-block; position:relative;}
		#imageBeingViewed_con .zoom img::selection { background-color: transparent; }
		#imageBeingViewed_con img:hover { cursor: url(<?= $site["resources"] ?>/zoom-feature/grab.cur), default; }
		#imageBeingViewed_con img:active { cursor: url(<?= $site["resources"] ?>/zoom-feature/grabbed.cur), default; }
	</style>

	<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/fs_addToCart.js"></script>
	<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/fs_updateTimer.php"></script>
	
	<script type="text/javascript" src="http://www.e-junkie.com/ecom/box.js"></script>
<?php include "footer.php"; ?>
