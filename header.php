<!DOCTYPE HTML>

<?php 
	$themePath=get_bloginfo("template_url");

	$site=array();
		$site["url"]=home_url();
		$site["resources"]=get_bloginfo("template_url")."/resources";

	include "resources/mobileDetection.php"; $detect=new Mobile_Detect();
	
	if ($detect->isMobile()) {$onMobile="true";} else {$onMobile="false";}
	
	if (! isset($moreClassnames)) {$moreClassnames=array();}
	
	if (have_posts()) {$moreClassnames[]="havesPosts";}
	else {$moreClassnames[]="doesntHavePosts";}
	
	if (($paged>=2 || $page>=2) && is_home()) {$moreClassnames[]="show_mostRecent";}
	
	if (is_page()) {
		$moreClassnames[]="page-".$post->post_name;
	}
?>

<html <?php language_attributes(); ?> class="<?= join(' ', get_body_class()); ?> <?= join(' ', $moreClassnames) ?>">
	<head>
		<!-- [Start] Titles, Descriptions, And Social Media Tags -->
			<!-- 
				- Elements with "og" in the "name" attribute are for facebook.
				- Elements with attribute "itemprop" are for google plus.
			-->
		
		<title><?php
			wp_title("|", true, "right");

			bloginfo("name");

			/* Site description for home and front_page */ 
			if (get_bloginfo("description") && (is_home() || is_front_page())) {echo " | ". get_bloginfo("description");}
			
			/* Page Numbers */ if ($paged>=2 || $page>=2) {echo " | Page ".max($paged, $page);}
		?></title>
		
		<?php 
			$socialMeta=array();
			
			if (is_page() || is_single()) :
				$socialMeta["title"]=get_the_title();
				$socialMeta["excerpt"]=strip_tags(get_the_excerpt());
				$socialMeta["image"]=post_pic("thumbnail", "150X150");		
						
			elseif (is_author()) :
				$socialMeta["title"]="All posts by ".get_the_author()."";
				$socialMeta["excerpt"]=strip_tags(get_the_author_meta("description"));
				$socialMeta["image"]=get_avatar(get_the_author_meta("user_email"));
				
			elseif (is_category() || is_tag()) :
				$socialMeta["title"]=single_term_title("", false);
				$socialMeta["excerpt"]=strip_tags(term_description());
			
			else :
				$socialMeta["title"]=get_bloginfo("name");
				$socialMeta["excerpt"]=strip_tags(get_bloginfo("description"));
				$socialMeta["image"]="". $site['resources'] ."/images/logo2.png";
			endif;
		?>
		
		<?php if (! empty($socialMeta["title"])) { ?>	
			<meta property="og:title" content="<?= esc_attr($socialMeta["title"]) ?>" />
			<meta itemprop="name" content="<?= esc_attr($socialMeta["title"]) ?>" /> 
			
			<?php if (! empty($socialMeta["excerpt"])) { ?>
				<meta name="description" content="<?= esc_attr($socialMeta["excerpt"]) ?>" />
			
				<meta property="og:description" content="<?= esc_attr($socialMeta["excerpt"]) ?>" />
				<meta itemprop="description" content="<?= esc_attr($socialMeta["excerpt"]) ?>" />
			<?php } ?>
			
			<?php if ($socialMeta["image"]!=null) { ?>
				<meta property="og:image" content="<?= rawurlencode($socialMeta["image"]) ?>" /> 
				<meta itemprop="image" content="<?= rawurlencode($socialMeta["image"]) ?>" />
			<?php } ?>
		<?php } ?>
		
		<!-- Index And Follow Links --> <meta name="robots" content="follow" />
		<!-- [End] Titles, Descriptions, And Social Media Tags -->
		
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		<!-- CSS Files -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?= $site['resources'] ?>/css/elements.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?= $site['resources'] ?>/css/template.css" />
		<link rel="stylesheet" type="text/css" media="print" href="<?= $site['resources'] ?>/css/printing.css" />
		<link rel="stylesheet" type="text/css" href="<?= $themePath ?>/style.css" />
		<link rel="stylesheet" type="text/css" href="<?= $site['resources'] ?>/css/MOBILE.css" />
		
		<!-- Javascript Files -->
		<script type="text/javascript">
			/* Basic Vars */
			var site={
				"url": "<?= $site['url'] ?>",
				"theme": "<?= $themePath ?>",
				"resources": "<?= $site['resources'] ?>",
			};
			
			var onMobile=new Boolean(<?= $onMobile ?>);
		</script>
								
		<script type="text/javascript" src="<?= $site['resources'] ?>/jquery/corejquery.js"></script>
		<script type="text/javascript" src="<?= $site['resources'] ?>/zoom-feature/jquery.zoom-min.js"></script>
		<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/css_browser_selector.js"></script>
		<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/jsHelp.js"></script>

		<script type="text/javascript" src="<?= $site['resources'] ?>/jquery/jqueryUI.js"></script>
			<link rel="stylesheet" type="text/css" media="screen" href="<?= $site['resources'] ?>/jquery/jqueryUI.css" />
		
		<script type="text/javascript" src="<?= $site['resources'] ?>/jquery/plugins/jtweetsanywhere.js"></script>
			<link rel="stylesheet" type="text/css" href="<?= $site['resources'] ?>/jquery/plugins/jtweetsanywhere.css" />
		
		<script type="text/javascript" src="<?= $site['resources'] ?>/scripts/beforeBody.js"></script>
		
		<script type="text/javascript" src="<?= $site['resources'] ?>/FS/index.js"></script>
		<script type="text/javascript" src="<?= $site['resources'] ?>/FS/jBackup.js"></script>
		
		<!--[if lt IE 9]><script type="text/javascript" src="<?= $site['resources'] ?>/scripts/html5shiv.js"></script><![endif]-->
		
		<!-- Wordpress Head Things -->
		<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
	
		<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />   
		
		<?php if (is_singular()) {wp_enqueue_script("comment-reply");} ?>
		
		<?php wp_head(); ?>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-19938178-1']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>

	<body>
		<!-- Start Of #everything -->
		<div id="everything">
			<!-- #Header -->
			<header id="layout-header">
				<div class="goLeft" id="col_one">
					<a href="<?= get_option('home'); ?>" rel="home" title="<?= esc_attr(get_bloginfo('name', 'display')) ?>">
						<img src="<?= $themePath; ?>/screenshot.png" alt="<?= get_bloginfo('name') ?>" rel="logo" />							
					</a>
				</div>
				
				<div class="goRight" id="col_two">	
					<div id="col_two_top">
						<!-- Top Menu -->
						<div id="top-nav" class="goLeft"><?php wp_nav_menu(array("theme_location" => "top_menu",)); ?></div>
						
						<!-- Search Form -->
						<div class="goRight"><?php get_search_form(); ?></div>
						
						<?= clearFloat(); ?>
					</div>
									
					<!-- The Ad -->
					<a href="<?= $theme_options['ad_link'] ?>" target="_blank" id="Ad">
						<img src="<?= $theme_options['ad_image'] ?>" />
					</a>
				</div>
				
				<?= clearFloat(); ?>
			</header>
			
			<!-- #main_menu -->		
			<div id="main-menu"><?php wp_nav_menu(array("theme_location" => "main_menu",)); ?></div>
			
			<!-- #theMiddle -->
			<?php 
				if (! empty($theMiddle_layout) && in_array($theMiddle_layout, array("c", "s-c", "c-s"), true)) {}
				else {$theMiddle_layout="c-s";}
			?>
			
			<div id="layout-theMiddle" class="<?= $theMiddle_layout ?>">
				<?php if ($theMiddle_layout=="s-c") { ?>
					<section id="layout-sidebarA"><?php if (is_active_sidebar("sidebar_a")) {dynamic_sidebar("sidebar_a");} ?></section>
				<?php } ?>
				
				<section id="layout-content">
					
