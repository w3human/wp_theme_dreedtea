<?php 
	the_post();
	
	$currentPost=$post;
	$otherClasses=array();
		
	if (post_password_required()) {
		$passwordRequired=true;
		$otherClasses[]="passwordNeeded";
	}
	else { 
		$passwordRequired=false;
	}

	$post_tags_id=array();
		foreach (get_the_tags($currentPost->ID) as $tag) {$post_tags_id[]=$tag->term_id;}
	
	$relatedPosts=get_posts(array(
		"post_type" => "post",
		"orderby" => "rand",
		"numberposts" => 4,		
		
		"exclued" => $currentPost->ID,
		"category" => join(",", wp_get_post_categories($currentPost->ID)),
		"tax_query" => array(
			array(
				"taxonomy" => "post_tag",
				"field" => "id",
				"terms" => $post_tags_id,
			),
		),
	));
	
	wp_reset_postdata();

	include "header.php"; 
?>
	<article class="<?=join(' ', get_post_class()) ?> <?= join(' ', $otherClasses) ?>" id="entry-item">
		<header id="meta-data">
			<h1 id="_title"><?= the_title(); ?></h1>
			
			<div class="afterTitle">
				<time id="_date"><?= get_the_date(); ?></time><br />
				<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-width="410" data-show-faces="false"></div>
			</div>
		</header>
		
		<div id="_main"><?= the_content(); ?></div>
		
		<?= clearFloat(); ?>
		
		<footer id="_bottom">
			<?php if (count($relatedPosts)>2) : ?>
				<section id="related_posts">
					<h2 class="nameOfPart">Related Posts</h2>
					
					<div id="the_related">
						<?php foreach ($relatedPosts as $post) {postPreview("thumbnail", "150X120", "relatedPost", false);} ?>
					</div>
				</section>
				
				<script type="text/javascript">
					var allRelated=document.getElementById("the_related").childNodes;
					var highestHeight=0;
					
					for (rp=0; rp<allRelated.length; rp++) {
						var rePost=allRelated[rp];
						
						if (hClass(rePost, "relatedPost") && rePost.offsetHeight>highestHeight) {
							highestHeight=rePost.offsetHeight;
						}
					}
					
					addSheet("#related_posts .relatedPost{height: "+ highestHeight +"px;}");
				</script>
			<?php endif; wp_reset_postdata(); ?>
			
			<?php if (get_the_author_meta("description")) : ?>
				<section id="authorBit">	
					<h2 class="nameOfPart">About the author, <a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>"><?= get_the_author() ?></a></h2>
				
					<a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>">
						<div class="avatarH goLeft">
							<?= get_avatar(get_the_author_meta("user_email")); ?>
						</div>
					</a>
						
					<div class="author-bio"><?= get_the_author_meta("description"); ?></div>
					
					<?= clearFloat(); ?>
				</section>
			<?php endif; ?>
			
			<?php comments_template("/comments.php"); ?>
		</footer>
		
		<?= clearFloat(); ?>
	</article>
<?php include "end-of-everything.php"; $code_name="postGallery_popup"; ?>
	<div id="postGallery_popup" name="postGallery_popup" class="popup_con">
		<div id="CL_postGallery_popup" class="colorLayer"></div>
				
		<div class="popupLayer">
			<div id="THE_<?= $code_name ?>" class="thePopup">
				<a id="CLOSE_postGallery_popup" style="display: none;" onclick="closePopup('<?= $code_name ?>');"></a>
			
				<div class="theGoodStuff">
					<div style="text-align: center;">
						<img id="pg_theImage" />
						<div id="pg_caption"></div>
					</div>
					
					<div class="pn-button">
						<input type="image" src="<?= $site["resources"] ?>/icons/back-button.png" id="pg_backItem" class="goLeft" />
						<input type="image" src="<?= $site["resources"] ?>/icons/next-button.png" id="pg_nextItem" class="goLeft" />
						<input 
							type="image" src="<?= $site["resources"] ?>/icons/close-button.png" id="pg_closeItem" class="close goRight" 
							onclick="closePopup('<?= $code_name ?>');"
						/>
						
						<?= clearFloat(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="<?= $site["resources"] ?>/scripts/postGallery.js"></script>
<?php include "footer.php"; ?>
 
