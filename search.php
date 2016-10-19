<?php $theMiddle_layout="c"; include "header.php"; ?>
	<?php if (have_posts()) : ?>
		<div id="meta-data">
			<h1 id="_title">Search results for: "<span class="query"><?= get_search_query() ?></span>"</h1>
		</div>
	
		<?php anArchive(); ?>
	<?php else : ?>
		<div id="meta-data">
			<h1 id="_title">Nothing Found</h1>
			<div id="_description">Sorry, but nothing matched your search criteria. Please try again with some different keywords.</div>
		</div>
	<?php endif; ?>
<?php include "end-of-everything.php"; ?>
<?php include "footer.php"; ?>
