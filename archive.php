<?php 
	$theMiddle_layout="c"; include "header.php";
		
	if (is_tag()) {
		$whattype="tag";
		
		$the_term=get_term(get_query_var("tag_id"), "post_tag");
		$numberOfAllPosts=$the_term->count;
		
		$termIDs=$the_term->term_id;
	}
	elseif (is_category()) {
		$whattype="category";
	
		$the_term=get_term($cat, "category");
			$categoryChildren= get_term_children($cat, "category");
			
		$numberOfAllPosts=$the_term->count;
			foreach ($categoryChildren as $childCat) {
				$c=get_category($childCat);
				
				$numberOfAllPosts=$numberOfAllPosts+$c->count;
			}
			
		$termIDs=$cat.",".join(",", $categoryChildren);
	}
	elseif (is_author()) {
		$whattype="author";
			$numberOfAllPosts=get_the_author_posts();
			$termIDs=$author;
	}	
?>

<header id="meta-data">
	<?php if (is_author()) :  ?>
		<?php if (get_the_author_meta("description")) : ?>
			<div class="author-info">	
				<h1 class="nameOfPart">About <?= get_the_author(); ?></h1>
			
				<div class="avatarH goLeft">
					<?= get_avatar(get_the_author_meta("user_email")); ?>
				</div>
					
				<div class="author-bio"><?= get_the_author_meta("description"); ?></div>
				
				<?= clearFloat(); ?>
			</div>
		<?php endif; ?>
		
		<h1 id="_title" style="text-align: center; margin-top: .5em; ">All posts by <?= get_the_author(); ?></h1>	
	<?php else: ?>
		<h1 id="_title"><?= $whattype ?>: <?= ((is_tag())?"<b>#</b>":"") ?><span class="query"><?= single_term_title() ?></span></h1>
			<div id="_description"><?= term_description() ?></div>
	<?php endif; ?>
</header>

<?php anArchive(); ?>

<div id="loadMoreContent">
	<div id="clickToLoad">
		<div class="goLeft">
			<div id="loadMore_button" class="buttonStyle_one">&laquo; Load More Posts</div>
			<div id="switchTo_autoLoad" class="buttonStyle_one">Auto Load</div>
		</div>
	</div>
	
	<div id="autoLoad">
		<div style="width: 16px; height: 16px; margin: auto; background: url(<?= $site['resources'] ?>/icons/ajax-loader.gif);"></div><br />
	
		<div id="stopAutoLoading" class="buttonStyle_one">Stop Auto Loading =)</div>
	</div>
</div>

<script type="text/javascript">
	var waitTime=1.4;
	
	var numberOfAllPosts=<?= $numberOfAllPosts ?>;
	
	var ajax=new Object();
		ajax["term"]="<?= $whattype ?>";
		ajax["id"]="<?= $termIDs ?>";
	
	var loadingStill=new Boolean(false);
	var allPostsHaveBeenLoad=new Boolean(false);
	
	function numberOfCurrentPosts() {
		var theCOUNT=0;
		
		for (child=0; child<document.getElementById("anArchive").childNodes.length; child++) {
			if (document.getElementById("anArchive").childNodes[child].tagName!=undefined) {
				if (hClass(document.getElementById("anArchive").childNodes[child], "postPreview")) {
					theCOUNT+=1;
				}
			}
		}
		
		return theCOUNT;
	}
	
	if (numberOfCurrentPosts() >= numberOfAllPosts) {
		allPostsHaveBeenLoad=new Boolean(true);		
		aClass(doc_body, "allHaveBeenLoaded");				
	}
	
	if (getItem("disableAutoLoad")) {
		aClass(doc_body, "disableAutoLoad");
	}
		
	if (window.XMLHttpRequest) {var xmlhttp=new XMLHttpRequest();}
	else {var xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if (numberOfCurrentPosts() < numberOfAllPosts) {
				setTimeout(function() {
					if (xmlhttp.responseText.match("ALL POST ARE LOADED")) {
						allPostsHaveBeenLoad=new Boolean(true);	
						aClass(doc_body, "allHaveBeenLoaded");
					}
					else {
						document.getElementById("anArchive").innerHTML+=xmlhttp.responseText;
					}
					
					loadingStill=new Boolean(false);
					rClass(doc_body, "loadingStill");
					
					if (numberOfCurrentPosts() >= numberOfAllPosts) {
						allPostsHaveBeenLoad=new Boolean(true);		
						aClass(doc_body, "allHaveBeenLoaded");				
					}
				}, ((! getItem("disableAutoLoad"))?waitTime*1000:0000));
			}
		}
	}
	
	function loadMoreContent() {
		if (numberOfCurrentPosts() >= numberOfAllPosts) {
			allPostsHaveBeenLoad=new Boolean(true);	
			aClass(doc_body, "allHaveBeenLoaded");					
		}			
		
		if (allPostsHaveBeenLoad==false) {
			if (loadingStill==true) {}
			else if (loadingStill==false) {
				loadingStill=new Boolean(true);
				aClass(doc_body, "loadingStill");

				xmlhttp.open("GET", "<?= $themePath ?>/ajax-archive.php?term="+ encodeURIComponent(ajax["term"]) +"&id="+ encodeURIComponent(ajax["id"]) +"&offset="+ numberOfCurrentPosts() +"", true);
				xmlhttp.send();	
			}
		}	
	}
	
	document.getElementById("stopAutoLoading").onclick=function() {
		setItem("disableAutoLoad", "true");
		aClass(doc_body, "disableAutoLoad");
	};
	
	document.getElementById("switchTo_autoLoad").onclick=function() {
		removeItem("disableAutoLoad");
		rClass(doc_body, "disableAutoLoad");
	};
	
	document.getElementById("loadMore_button").onclick=function() {
		loadMoreContent();
	};
		
	window.onscroll=function() {
		if (! getItem("disableAutoLoad") && window.scrollY >= (getDocHeight() - window.height - 10)) {
			loadMoreContent();
		}
	};
	
	if (getDocHeight()<=window.height) {
		loadMoreContent();
	}
</script>

<?php include "end-of-everything.php"; ?>
<?php include "footer.php"; ?>
