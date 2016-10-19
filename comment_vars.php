<?php
	$numberOfMents=get_comments_number();
	
	$replyingTo=isset($_GET['replytocom']) ? $_GET['replytocom']:false; 
	
	$user_string=array();
		$user_string["name"]='You are logged in as <a href="'. admin_url("profile.php") .'">'. $user_identity .'</a>';
		$user_string["logout"]='<a href="'. wp_logout_url() .'" title="Log out of this account">Log out?</a>';

	$comment_inputEles=array();
		$comment_inputEles["name"]='
			<div class="anInput">
				<div class="name"><label for="author">Name</label>:</div>
				<div class="theInput"><input type="text" name="author" required="" value="'. $comment_author .'" /></div>
			</div>
		';
		
		$comment_inputEles["email"]='
			<div class="anInput">
				<div class="name"><label for="email">Email</label>:</div>
				<div class="theInput"><input type="email" name="email" required="" value="'. $comment_author_email .'" /></div>
			</div>
		';
		
		$comment_inputEles["comment"]='<textarea name="comment" required=""></textarea>';
?>
