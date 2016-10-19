<?php 
	include "comment_vars.php"; echo clearFloat(); 
	$commentClasses=array();
	
	if (! have_comments()) {$commentClasses[]="noComments";}
?>

<section id="comments" class="<?= join(' ', $commentClasses) ?>">
	<?php if (post_password_required()) : ?>
		<!-- This post is password protected. Enter the password to view any comments. -->
	<?php endif; ?>
	
	<?php if (have_comments()) : ?>
		<h2 class="nameOfPart"><?php echo "Has ". $numberOfMents ." Comment"; if ($numberOfMents>1) {echo "s";} echo ":"; ?></h2>
		<ul id="writtenComments"><?php wp_list_comments('callback=comment_walker'); ?></ul>
	<?php endif; ?>
	
	<?php if (! $replyingTo) : ?>
		<div class="writeAcomment">
			<div id="beforeForm">
				<h2 class="title">Write <?php if ($numberOfMents<1) : ?>the first<?php else : ?>a<?php endif; ?> comment</h2>
			</div>
			
			<form action="<?= home_url() ?>/wp-comments-post.php" method="post" name="write-a-comment" class="validateThis">
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
					<div class="goLeft"><input name="submit"type="submit" value="Post It" /></div>
					<div class="goRight">
						<?php if (! is_user_logged_in()) { ?><em id="email_warning">Your email address will <b>not be</b> published.</em><?php } ?>
					</div>
					
					<?= clearFloat(); ?>
				</div>
								
				<?= clearFloat(); ?>
				
				<?php comment_id_fields(); do_action("comment_form", $post->ID); ?>
			</form>
		</div>
	<?php endif; ?>
</section>

<script type="text/javascript">
	var written_commentTops=document.getElementsByName("commentTOP");
	
	for (atop=0; atop<written_commentTops.length; atop++) {
		var a_top=written_commentTops[atop];
		tClass(a_top, "longName", (a_top.offsetHeight>18));
	}
</script>

<style type="text/css"><?php 
	$maxComment_width=39.5; //em
	$currentWidth=$maxComment_width;
	$numOfChildren=10;
	$stepWidth=4.2;
	
	for ($X=2; $X<=$numOfChildren; $X++) {
		$currentWidth=$currentWidth-$stepWidth;
		echo "#writtenComments .depth-". $X .".aComment #main{width: ". $currentWidth ."em;} \n";
		echo "#respond.depth-". $X ." .writeAcomment{width: ". ($currentWidth+("1.".$X)+(".".($X))) ."em;} \n";
	}
?></style>
