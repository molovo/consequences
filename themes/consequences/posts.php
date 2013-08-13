<?php theme_include('header'); ?>

<section id="stories">
	<div class="container">
		<hgroup class="grid 1of1">
			<h4 style="margin-bottom: 0;">Choose a chapter&hellip;</h4>
			<p><a href="#write"><em>or write your own</em></a></p>
		</hgroup>
		<ul class="selector-list">
			<?php while(posts()): ?>
			<li class="grid 1of1 force-grid">
				<hgroup class="grid 1of3 ralign">
					<h6 class="<?php echo article_custom_field('body_font') ?>"><strong><?php echo (article_id() > 0 ? 'Chapter ' . ucwords(num2wrd(article_id())) : 'The Prologue'); ?></strong></h6>
				</hgroup>
				<div class="grid 2of3">
					<a href="<?php echo article_url(); ?>"><h2 class="<?php echo article_custom_field('header_font'); ?>"><?php echo article_title(); ?></h2></a>
					<p class="<?php echo article_custom_field('body_font') ?>"><em>by <?php echo article_author('real_name'); ?></em></p>
				</div>
			</li>
			<?php endwhile; ?>
			<!--<li class="grid 1of1 force-grid">
				<hgroup class="grid 1of3 ralign">
					<h6 class="tk-adelle"><strong>Chapter One</strong></h6>
				</hgroup>
				<div class="grid 2of3">
					<a href="gisele.html"><h2 class="tk-adelle heading">The Door</h2></a>
					<p class="tk-adelle"><em>by Benjamin Hawkyard</em></p>
				</div>
			</li>
			<li class="grid 1of1 force-grid">
				<hgroup class="grid 1of3 ralign">
					<h6 class="tk-skolar"><strong>Chapter Two</strong></h6>
				</hgroup>
				<div class="grid 2of3">
					<a href="gisele.html"><h2 class="tk-proxima-nova">This is a really really long title to see how the layout responds to that</h2></a>
					<p class="tk-skolar"><em>by Joe Bloggs</em></p>
				</a>
			</li>-->
		</ul>
	</div>
</section>

<?php theme_include('footer'); ?>