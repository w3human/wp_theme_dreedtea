<?php
	the_post();  
	
	include "header.php";
?>
	<article id="a-page">
		<?php if (get_custom_field("hide_title")!="true") { ?>
			<header id="_meta">
				<h1 id="_title"><?= the_title() ?></h1>
			</header>
		<?php } ?>
		
		<div id="_main"><?= the_content(); ?></div>
	</article>
<?php include "end-of-everything.php"; ?>
<?php include "footer.php"; ?>
