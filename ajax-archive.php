<?php 
	require_once("../../../wp-load.php");
	
	if (isset($_GET["term"]) && isset($_GET["id"]) && isset($_GET["offset"])) {
		$args=array("posts_per_page" => 9, "offset" => $_GET["offset"]);
		
		if ($_GET["term"]=="category") {
			$args["category"]=$_GET["id"];
		}
		elseif ($_GET["term"]=="tag") {
			$args["tax_query"]=array(
				array(
					"taxonomy" => "post_tag",
					"field" => "id",
					"terms" => $_GET["id"],
				)
			);
		}
		elseif ($_GET["term"]=="author") {
			$args["author"]=$_GET["id"];
		}
		
		$get_more=get_posts($args);
		
		if (count($get_more)==0) {
			echo "ALL POST ARE LOADED";
		}
		else {	
			foreach ($get_more as $post) {
				postPreview("large", "300X220", "postOf", 110);
			}
		}
	}
?>
