<?php theme_include('header'); ?>

		<section id="content" class="article-<?php echo article_id(); ?>">
			<div class="container">
				<article class="grid 8of12 offset-2 <?php echo article_custom_field('body_font'); ?>">
					<?php echo article_markdown(); ?>

					<a href="<?php echo (Session::get(article_slug()) == 'liked' ? '#' : Uri::to('like/' . article_slug())); ?>" class="button <?php echo (Session::get(article_slug()) == 'liked' ? 'liked' : ''); ?>"><span class="icon"><?php echo (Session::get(article_slug()) == 'liked' ? '&hearts;' : '&#9825;') ?></span> <?php echo (Session::get(article_slug()) == 'liked' ? 'Thanks!' : 'Love it!'); ?></a>
					<a href="https://twitter.com/intent/tweet?original_referer=<?php echo urlencode(article_url()); ?>&amp;text=<?php echo urlencode(article_title() . (article_author_twitter() ? ' by @' . article_author_twitter() : '') . ' :: '); ?>&amp;tw_p=tweetbutton&amp;url=<?php echo urlencode('http://consequences.co' . article_url()); ?>&amp;via=consequences_co" class="button"><span class="social-icon">&#62217;</span> Share</a>
				</article>
			</div>
		</section>

<?php theme_include('footer'); ?>