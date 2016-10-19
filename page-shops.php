<?php
	$theMiddle_layout="c";

	include "header.php"; 
?>

<div class="banner_con blue" id="shops_banner"><div class="leftEND"></div><div class="theMiddle">
	<div class="words">Flashsales - Grab 'em!</div>
</div><div class="rightEND"></div></div>

<div id="theSales">
	<?php 
		$counter=0;
		
		foreach ($flashsale_data as $fs_name => $fs_data) { if (is_user_logged_in() || $fs_data["status"]=="publish") {
			$counter=$counter+1;
			
			$image_src=wp_get_attachment_image_src(get_post_thumbnail_id($fs_data["id"]), "full"); 
				$featured_img=$image_src[0]; ?>
			
			<a href="<?= $fs_data['url'] ?>">
				<div id="<?= $fs_data['slug'].'_'.$fs_data['id'] ?>" class="aSALE <?= (($counter==1)?"_one":"_two") ?>">
					<div class="widthOf">
						<img src="<?= $featured_img ?>" class="product_icon" />
						
						<div class="fs_counter fsButton">
							<span class="saleIsLive">
								<span class="fs_icon pink-clock"></span>
								<span class="fs_icon endsIn"></span>
								<span class="words timer" id="<?= $fs_data['slug'] ?>_timer"></span>
							</span>
							
							<span class="saleHasEnded">
								<span class="fs_icon black-clock"></span>
								<span class="fs_icon saleEnded"></span>
							</span>
						</div>

						<div id="fsp_slider_<?= $fs_data['id'] ?>" class="miniSlider fsp_slider" timer="<?= (4*1000) ?>">
							<?php foreach ($productsInFlashsale[($fs_name)] as $p_name) {
								$p_data=$product_data[$p_name]; 
									list($widthOf_product, $heightOf_product) = getimagesize($p_data["featured_image"]); ?>
							
								<div class="slide">
									<div class="fsp_img <?= (($widthOf_product>$heightOf_product)?"wide":"tall") ?>" style="background-image: url(<?= $p_data['featured_image'] ?>);"></div>
								</div><?php	
							} ?>
							
							<div class="slidePicker" style="display: none;"></div>
						</div>
					</div>
				</div>
			</a>
	
			<script type="text/javascript">
				<?php 
					$counter_endDate=$fs_data["slug"]."_endDate";
					$counter_endHour=$fs_data["slug"]."_endHour";
					$counter_currentTime=$fs_data["slug"]."_currentTime";
				?>

				var <?= $counter_endDate ?>=new Date("<?= $fs_data["endDate"] ?>");
			</script>
			
			<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/fs_updateTimer.php?prefix=<?= $fs_data['slug'].'_' ?>&timeElement=<?= $fs_data['slug'] ?>_timer&endElement=<?= $fs_data['slug'].'_'.$fs_data['id'] ?>"></script>
			
			<?php 
			if ($counter==2) {
				$counter=0;
				clearFloat();
			}
		} }
	?>
</div>
<?php include "end-of-everything.php"; ?>

<?php include "footer.php"; ?>

