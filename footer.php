		<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/afterBody.js"></script>
		<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/js-miniSlider.js"></script>
		<script type="text/javascript">FS.apply();</script>
		<script type="text/javascript">
			$(document).ready(function() {
				if (document.getElementById("hellobar-container") || HelloBar!=undefined) {aClass(doc_body, "hasHellobar");}
			});
		</script>
		
		<!-- Wordpress Footer --> <?php wp_footer(); ?>
		
		<!-- THE OTHERS -->
		<?= $theme_options["html"]; ?>
		
		<?php if ($theme_options["css"]) { ?><style type="text/css"><?= $theme_options["css"]; ?></style><?php } ?>
		<?php if ($theme_options["js"]) { ?><script type="text/javascript"><?= $theme_options["js"]; ?></script><?php } ?>
		
	</body>
</html>
