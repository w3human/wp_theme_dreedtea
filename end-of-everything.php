					<?= clearFloat(); ?>
				</section>
				
				<?php if ($theMiddle_layout=="c-s") { ?>
					<section id="layout-sidebarA"><?php if (is_active_sidebar("sidebar_a")) {dynamic_sidebar("sidebar_a");} ?></section>
				<?php } ?>
				
				<?= clearFloat(); ?>
			</div>
			
			<!-- #footer -->
			<footer id="layout-footer">
				<?php 
					$partners=get_posts(array("post_type"=>"partner", "numberposts" => -1, "orderby"=>"menu_order", "order" => "ASC"));
					
					$numberOfPartners=0;
					
					if (count($partners) >= 1 && ! is_singular("flashsale") && ! is_singular("product") && ! is_page("shops")) { ?>
						<!-- Parterns -->
						<div id="partners" class="hwm">
							<div class="banner_con black" id="partnerBanner"><div class="leftEND"></div><div class="theMiddle">
								<div class="words">Our Partner<?php echo ((count($partners) >= 2)?"s":""); ?></div>
							</div><div class="rightEND"></div></div>
							
							<?php 
								foreach ($partners as $partner) {
									$numberOfPartners=$numberOfPartners+1; ?>
									
									<div id="<?= $partner->ID ?>_partner" class="aPart"><div class="inner">
										<a href="<?php get_post_meta($partner->ID, "url", true) ?>" target="_blank">
											<?= get_the_post_thumbnail( $partner->ID ); ?>
										</a>
										
										<div id="<?= $partner->ID ?>_feed" class="theFeed"></div>
									</div></div><?php
								}
							?>
						</div>
						
						<?php 
							if (((is_front_page() || is_home()) && ($theme_options["partners_rss_number"] >= 1))) {
								foreach ($partners as $partner) { ?>
									<script type="text/javascript">grss("<?= get_post_meta($partner->ID, "rss", true) ?>", <?= $theme_options["partners_rss_number"] ?>, "<?= $partner->ID ?>_feed");</script><?php
								}
							}
						?>
						
						<style type="text/css">#layout-footer #partners .aPart{width: <?= (98.5/$numberOfPartners) ?>%;}</style><?php 
					}
				?>
				
				<?php if ($theme_options["linece"]) { ?>
					<!-- Site Linece -->
					<div id="site_linece"><?= $theme_options["linece"] ?></div>
				<?php } ?>
			</footer>
		</div>	
		<!-- End Of #everything -->
