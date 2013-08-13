<?php
	theme_include('header');
	if (isset($messages)): ?>
		<section id="messages">
			<div class="container">
				<p class="grid 1of1">
					<?php echo $messages; ?>
				</p>
			</div>
		</section>
	<?php endif; ?>



<section id="stories">
	<div class="container">
		<hgroup class="grid 1of1">
			<h4 style="margin-bottom: 0;">Choose a chapter&hellip;</h4>
			<p><a href="#write"><em>or write your own</em></a></p>
		</hgroup>
		<ul class="selector-list">
			<?php while(get_all_posts()): ?>
			<li class="grid 1of1 force-grid">
				<hgroup class="grid 1of3 ralign">
					<h6 class="<?php echo article_custom_field('body_font') ?>"><strong><?php echo (article_id() > 0 ? 'Chapter ' . ucwords(num2wrd(article_id())) : 'The Prologue'); ?></strong></h6>
				</hgroup>
				<div class="grid 2of3">
					<a href="<?php echo article_url(); ?>"><h2 class="<?php echo article_custom_field('header_font'); ?>"><?php echo article_title(); ?></h2></a>
					<p class="<?php echo article_custom_field('body_font') ?>"><em>by <?php echo User::where('id', '=', Post::slug(article_slug())->author)->fetch()->real_name; ?></em></p>
				</div>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
</section>

<section id="about">
	<div class="container">
		<?php echo Page::slug('about')->content; ?>
	</div>
</section>

<?php theme_include('footer'); ?>