<?php 
	the_post();
	
	$theMiddle_layout="c"; 
	
	include "header.php";
?>
	<script type="text/javascript">
		var item_data={};
		var endDate=new Date("<?= get_custom_field("enddate") ?>");
		
		<?php if (get_custom_field("enddate_hour")) { ?>
			var endHour=(<?= (get_custom_field("enddate_hour")-1) ?>);
		<?php } ?>
	</script>
		
	<header id="headerTwo">
		<div class="partOne">
			<div class="headerImage" style="background-image: url(<?= get_custom_field('header_image') ?>);"></div>
			
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
							
			<?= clearFloat(); ?>
		</div>
			
		<div class="partTwo">
			<div class="goLeft description"><?= the_content(); ?></div>
			
			<a href="https://www.e-junkie.com/ecom/gb.php?c=cart&cl=216404&ejc=2" target="ej_ejc" onClick="return EJEJC_lc(this);">					
				<div class="goRight fsButton cartButton">
					<span class="fs_icon shoppingCart"></span><span class="words">view cart</span>
				</div>
			</a>
			
			<script type="text/javascript">
			<!--
			function EJEJC_lc(th) { return false; }
			// -->
			</script>
			
			<?= clearFloat(); ?>
		</div>
	</header>
		
	<div id="_main">
		<?php 
			$pNames=array(); $resetCounter=0; $totalCounter=0;
			
			foreach ($productsInFlashsale[($post->post_title)] as $p_name) {
				$p_data=$product_data[($p_name)]; $resetCounter=$resetCounter+1; $totalCounter=$totalCounter+1; $pNames[]=$p_data["name"];
				
				list($widthOf_postPhoto, $heightOf_postPhoto) = getimagesize($p_data["featured_image"]); ?>
				
				<div id="item-<?= $p_data['id'] ?>" class="productPreview num_<?= $resetCounter ?> <?= (($p_data['soldout']=='true')?'ITEM_soldout':'') ?>" name="flashsale-item">
					<a class="opener" id="<?= $p_data['name'] ?>" href="<?= $p_data['url'] ?>">
						<div id="effectContainer" class="<?= (($widthOf_postPhoto>$heightOf_postPhoto)?"wide":"tall") ?>">
							<div class="theImage_container">
								<div class="theImage" style="background-image: url(<?= $p_data['featured_image'] ?>);"></div>
							</div>
						
							<div class="theEffect">
								<div id="dark"></div>
								<div id="inner_circle">
									<div id="text"><?= (($p_data["soldout"]=="true")?"sold out":"view details") ?></div>
								</div>
							</div>
						</div>
						
						<h3 class="title"><?= $p_data["name"] ?></h3>
					</a>
					
					<?php if (! empty($p_data["price"])) { ?>
						<span class="price">$<?= $p_data["price"] ?></span>
					<?php } ?>
				</div><?php
			
				if ($totalCounter==2 && isset($styleSpotlight_ofFlashsale[($post->post_title)])) { 
					$resetCounter=$resetCounter+1; ?>
					
					<div id="style-spotlight" class="productPreview num_3">
						<div class="innerContent">
							<div class="_top">
								<div class="goLeft">
									<div class="image" style="background-image: url(<?= $styleSpotlight_ofFlashsale[($post->post_title)]["picture"] ?>);"></div>
								</div>
								
								<div class="goRight">
									<span class="fs_icon spotlight_words"></span>
									<span class="fs_icon uparrow"></span>
									
									<div class="_lines">
										<?= $styleSpotlight_ofFlashsale[($post->post_title)]["lines"] ?>
									</div>
								</div>
								
								<?= clearFloat(); ?>
							</div>
							
							<div class="_quote">
								<?= $styleSpotlight_ofFlashsale[($post->post_title)]["quote"] ?>
							</div>
						</div>
					</div><?php
				}
			
				if ($resetCounter==3) {
					$resetCounter=0;
					echo '<div class="clearingBetween '. ((count($productsInFlashsale[($post->post_title)])==$totalCounter)?"lastOne":"") .'"></div>';
				}
			}
		?>
		
		<?= clearFloat(); ?>
	</div>
<?php include "end-of-everything.php"; ?>
	<script type="text/javascript">var item_order=new Array(<?= "'". join("', '", $pNames)."'" ?>);</script>
	
	<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/fs_updateTimer.php"></script>
	<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/fs_addToCart.js"></script>
	<script type="text/javascript" src="http://www.e-junkie.com/ecom/box.js"></script>
<?php include "footer.php"; ?>

