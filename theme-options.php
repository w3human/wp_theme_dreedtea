<?php 
	add_action('admin_init', 'theme_options_init');
	add_action('admin_menu', 'theme_options_add_page');

	function theme_options_init() {
		register_setting('theme_options', 'the_theme_options', 'theme_options_validate');
	}
	function theme_options_add_page() {
		add_theme_page('Theme Options', 'Theme Options', 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
	}
		
	function inputName($name) {return "the_theme_options[". $name ."]";}
		
	function theme_options_do_page() {
		if (! isset($_REQUEST['settings-updated'])) {$_REQUEST['settings-updated'] = false;} ?>
		
		<script type="text/javascript" src="<?= get_bloginfo("template_url"); ?>/resources/jquery/corejquery.js"></script>

		<link rel="stylesheet" type="text/css" media="screen" href="<?= get_bloginfo("template_url"); ?>/resources/jquery/jqueryUI.css" />
		<script type="text/javascript" src="<?= get_bloginfo("template_url"); ?>/resources/jquery/jqueryUI.js"></script>

		<link rel="stylesheet" type="text/css" href="<?= get_bloginfo("template_url") ?>/resources/css/elements.css" />
		<link rel="stylesheet" type="text/css" href="<?= get_bloginfo("template_url") ?>/style.css" />
		
		<div class="wrap" id="theme-options">
			<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'sampletheme' ) . "</h2>"; ?>
			
			<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
				<div class="updated fade"><p><strong><?php _e( 'Options saved', 'sampletheme' ); ?></strong></p></div>
			<?php endif; ?>
			
			<form method="post" action="options.php">
				<?php
					settings_fields('theme_options');
					$options=get_option('the_theme_options');
				?>
			
				<div id="theme_options_tabs">
					<ul>
						<li><a href="#ADs">ADs</a></li>	
						<li><a href="#partners">Partners</a></li>
						<li><a href="#other">Other</a></li>
					</ul>
					
					<div id="ADs">
						<h1>Ad's Link And Image</h1>
							<div class="anInput">
								<div class="name"><label for="<?= inputName('ad_link'); ?>">Link</label>:</div>
								<div class="theInput"><input type="text" name="<?= inputName('ad_link'); ?>" value="<?= $options['ad_link']; ?>" /></div>
							</div>
							<div class="anInput">
								<div class="name"><label for="<?= inputName('ad_image'); ?>">Image</label>:</div>
								<div class="theInput"><input type="text" name="<?= inputName('ad_image'); ?>" value="<?= $options['ad_image']; ?>" /></div>
							</div>
					</div>
					
					<div id="partners">
						<h1>Partners</h1>
						
						<div class="anInput">
							<div class="name"><label for="<?= inputName('partners_rss_number'); ?>">Number Of Posts</label>:</div>
							<div class="theInput">
								<input type="number" name="<?= inputName('partners_rss_number'); ?>" value="<?= $options['partners_rss_number']; ?>" />
							</div>
						</div>
						
						<span style="color: #FF0000;">* Note: Set To Zero(0) To Disable RSS Feeds</span>
					</div>
					
					<div id="other">
						<h1>Template's Ways:</h1>
											
						<div class="anInput">
							<div class="name"><label for="<?= inputName('linece'); ?>">Linece</label>:</div>
							<div class="theInput"><textarea name="<?= inputName('linece'); ?>"><?= $options["linece"]; ?></textarea></div>
						</div>
					
						<br /><br />
						
						<h1>Addition HTML, CSS, And JS:</h1>
						
						<div class="anInput">
							<div class="name"><label for="<?= inputName('html'); ?>">HTML</label>:</div>
							<div class="theInput"><textarea name="<?= inputName('html'); ?>"><?= $options["html"]; ?></textarea></div>
						</div>
						
						<div class="anInput">
							<div class="name"><label for="<?= inputName('css'); ?>">CSS</label>:</div>
							<div class="theInput"><textarea name="<?= inputName('css'); ?>"><?= $options["css"]; ?></textarea></div>
						</div>
						
						<div class="anInput">
							<div class="name"><label for="<?= inputName('js'); ?>">Javascript</label>:</div>
							<div class="theInput"><textarea name="<?= inputName('js'); ?>"><?= $options["js"]; ?></textarea></div>
						</div>
					</div>
				</div>
				
				<?php submit_button(); ?>
			</form>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function() {
				$("#theme_options_tabs").tabs();
				$("#homePageSliders").accordion({header: "> div > h3", collapsible: true,});
			});
		</script>
		<style type="text/css">
			#theme-options{font-size: .9em;}
				#theme-options textarea{width: 65em; height: 8em;}
				
				h1, h2, h3, h4, h5, h6{margin: .25em 0em;}
				
				textarea{border-left: 2px dashed #000; border-bottom: 2px dashed #000;}
		</style>
<?php 
	}
	
	function theme_options_validate( $input ) {return $input;}
?>
