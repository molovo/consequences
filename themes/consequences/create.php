		<?php
			if($post) {
				$extend_article_img = Extend::field('post', 'header_img', $post->id);
				$article_img = Extend::value($extend_article_img, '');
				$extend_header_font = Extend::field('post', 'header_font', $post->id);
				$header_font = Extend::value($extend_header_font, '');
				$extend_body_font = Extend::field('post', 'body_font', $post->id);
				$body_font = Extend::value($extend_body_font, '');
				$extend_header_style = Extend::field('post', 'header_style', $post->id);
				$header_style = Extend::value($extend_header_style, '');
			}

			if (!$post) {
				$post = (object) array('title' => '', 'html' => '', 'id' => Post::count());
				$article_img = '';
				$header_font = '';
				$body_font = '';
				$header_style = '0';
			}

			theme_include('header');
		?>
		<section id="article-meta">
			<form action="<?php echo Uri::to($action); ?>" enctype="multipart/form-data" method="post" class="force-grid">
				<div class="container">
					<div class="grid 1of1"></div>
					<div class="grid 1of1"></div>
					<?php echo $messages; ?>

					<h4 class="ThirstyRough">Article Information</h4>

					<fieldset class="grid 1of3">
						<label for="title">Title</label>
						<?php echo Form::text('title', Input::previous('title'), array(
							'placeholder' => __('posts.title'),
							'autocomplete'=> 'off',
							'value' => $post->title,
							'id' => 'title'
						)); ?>
						<!--<input type="text" name="edTitle" id="edTitle" value="<?php echo $post->title; ?>" />-->
					</fieldset>

					<fieldset class="grid 1of3">
						<label for="real_name">Your Name</label>
						<input type="text" name="real_name" id="real_name" value="<?php echo $user->real_name; ?>" />
					</fieldset>

					<fieldset class="grid 1of3">
						<label for="twitter">Twitter Handle</label>
						<input type="text" name="twitter" id="twitter" placeholder="@" value="<?php echo $user->twitter; ?>" />
					</fieldset>

					<div class="grid 2of3 remove-padding force-grid">
						<h4 class="ThirstyRough">Typography</h4>

						<fieldset class="grid 1of2">
							<p>
								Enter the names (or URLs) of the two fonts you wish your article to be typeset in.
							</p>
								<small>You can leave these blank, and they'll be chosen for you. Fonts must either be free for commercial use, or on <a href="http://typekit.com" target="_blank" data-tooltip="If you know the correct Typekit class to call your desired fonts, enter this in the box to speed up the process.">Typekit</a>.</small>
						</fieldset>

						<fieldset class="grid 1of2">
							<label for="header_font">Header Font</label>
							<input type="text" name="extend[header_font]" id="extend_header_font" value="<?php echo $header_font; ?>" />
							<label for="body_font">Body Font</label>
							<input type="text" name="extend[body_font]" id="extend_body_font" value="<?php echo $body_font; ?>" />
						</fieldset>
					</div>

					<div class="grid 1of3 remove-padding">
						<h4 class="ThirstyRough">Header Image</h4>
						<fieldset class="grid 1of1">
							<label for="header_img">Upload an image file</label>
							<input type="file" name="extend[header_img]" id="extend_header_img" />
							<p></p>
							<p>
								<small>
									By uploading an image you confirm that you own the copyright for the image, or have permission from the copyright owner.
								</small>
							</p>
						</fieldset>
					</div>

					<h4 class="ThirstyRough" style="margin-bottom: 1.142857142857143em;">Header Style</h4>
					<!--<fieldset class="grid 1of1 force-grid">
						<label for="edHeaderStyle"><input type="radio" name="edHeaderStyle" value="split"></label>
						<label for="edHeaderStyle"><input type="radio" name="edHeaderStyle" value="left"></label>
						<label for="edHeaderStyle"><input type="radio" name="edHeaderStyle" value="center"></label>
						<label for="edHeaderStyle"><input type="radio" name="edHeaderStyle" value="blank"></label>
					</fieldset>-->
				</div>

				<header id="top" class="article" style="background: url('<?php echo ($article_img ? $article_img : theme_url("img/bg.jpg")); ?>') center no-repeat; background-size: cover;">
					<input type="hidden" name="extend[header_style]" id="extend_header_style" value="<?php echo $header_style; ?>" />
					<div class="container" style="padding: 0;">
						<h3 style="padding: 1.5rem;">
							<a href="#" class="unslider-arrow prev icon">&#59225;</a>
							<a href="#" class="unslider-arrow next icon">&#59226;</a>
						</h3>
					</div>
					<div class="headerType">
					    <ul>
					        <li>
								<div class="container">
									<div class="grid 1of1"></div>
									<hgroup class="grid 1of2 ralign">
										<h1 class="<?php echo $header_font; ?> article-title" style="margin-bottom: 0;"><?php echo ($post->title ? $post->title : 'Title'); ?></h1>
									</hgroup>
									<hgroup class="grid 1of2 lalign">
										<h4 class="<?php echo $body_font; ?>" style="font-weight: 700; font-style: normal; margin-bottom: 0;"><?php echo ($post->id > 0 ? 'Chapter ' . ucwords(num2wrd($post->id)) : 'The Prologue'); ?></h4>
										<h4 class="author-name <?php echo $body_font; ?>" style="margin-bottom: 0;">by <a <?php echo ($user->twitter ? 'href="http://twitter.com/' . $user->twitter . '"' : ''); ?> target="_blank"><?php echo $user->real_name; ?></a></h4>
									</hgroup>
								</div>
					        </li>
					        <li>
					        	<div class="container">
					        		<hgroup class="grid 1of1 1align">
					        			<h1 class="<?php echo $header_font; ?> article-title"><?php echo ($post->title ? $post->title : 'Title'); ?></h1>
					        			<h4 class="<?php echo $body_font; ?>" style="font-weight: 700; font-style: normal; margin-bottom: 0;"><?php echo ($post->id > 0 ? 'Chapter ' . ucwords(num2wrd($post->id)) : 'The Prologue'); ?></h4>
					        			<h4 class="author-name <?php echo $body_font; ?>" style="margin-bottom: 0;">by <a <?php echo ($user->twitter ? 'href="http://twitter.com/' . $user->twitter . '"' : ''); ?> target="_blank"><?php echo $user->real_name; ?></a></h4>
					        		</hgroup>
					        	</div>
					        </li>
					        <li>
					        	<div class="container">
					        		<hgroup class="grid 1of1 center">
					        			<h1 class="<?php echo $header_font; ?> article-title"><?php echo ($post->title ? $post->title : 'Title'); ?></h1>
					        			<h4 class="<?php echo $body_font; ?>" style="font-weight: 700; font-style: normal; margin-bottom: 0;"><?php echo ($post->id > 0 ? 'Chapter ' . ucwords(num2wrd($post->id)) : 'The Prologue'); ?></h4>
					        			<h4 class="author-name <?php echo $body_font; ?>" style="margin-bottom: 0;">by <a <?php echo ($user->twitter ? 'href="http://twitter.com/' . $user->twitter . '"' : ''); ?> target="_blank"><?php echo $user->real_name; ?></a></h4>
					        		</hgroup>
					        	</div>
					        </li>
					        <li>
					        	<div class="container">
						        	<div class="grid 1of1"></div>
						        	<div class="grid 1of1"></div>
						        	<p class="grid 8of12 offset-2">
						        		<small>* This option will hide the title and author information. Should only be selected if you have created a typographic image containing this information to go with your article.</small>
						        	</p>
					        	</div>
					        </li>
					    </ul>
					</div>
				</header>

				<section id="content" class="article-<?php echo article_id(); ?>">
					<div class="container" style="position: relative;">
						<script>
							function textAreaAdjust(o) {
							    o.style.height = "1px";
							    o.style.height = (25+o.scrollHeight)+"px";
							}
						</script>
						<article class="grid 8of12 offset-2 tk-ff-tisa-web-pro" id="preview">
							<?php echo parse($post->html); ?>
						</article>
						<textarea name="html" id="html" class="grid 8of12 offset-2 tk-ff-tisa-web-pro <?php echo $body_font; ?>" onkeyup="//textAreaAdjust(this);" onfocus="if(this.value=='Enter your content here&hellip; (Accepts HTML and Markdown)'){this.value='';}" onblur="if(this.value==''){this.value='Enter your content here&hellip; (Accepts HTML and Markdown)';}"><?php echo ($post->html ? $post->html : 'Enter your content here&hellip; (Accepts HTML and Markdown)'); ?></textarea>

						<fieldset class="grid 8of12 offset-2 ralign">
							<a href="#" id="save" class="button">Save</a>
						</fieldset>
					</div>
				</section>

			</form>
		</section>


<?php theme_include('footer'); ?>

<script>
	jQuery(document).ready(function($) {
		<?php if (isset($header_style)): ?>
			var pos = <?php echo $header_style; ?>;
			data.move(pos);
		<?php endif; ?>
	});
</script>