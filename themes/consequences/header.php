<!doctype html>
<html lang="en">
	<head>
		<!-- Site Info
	  ================================================== -->
		<meta charset="utf-8">
		<title><?php echo page_title('Page can’t be found'); ?> - <?php echo site_name(); ?></title>
		<meta name="description" content="<?php echo site_description(); ?>">
	    <meta name="generator" content="Anchor CMS">

		<!-- RSS
	  ================================================== -->
		<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo rss_url(); ?>">

		<!-- Favicons
	  ================================================== -->
  		<link rel="shortcut icon" href="<?php echo theme_url('img/favicon.png'); ?>">

		<!-- Site Metas
	  ================================================== -->
	    <meta property="og:title" content="<?php echo site_name(); ?>">
	    <meta property="og:type" content="website">
	    <meta property="og:url" content="<?php echo current_url(); ?>">
	    <meta property="og:image" content="<?php echo theme_url('img/og_image.gif'); ?>">
	    <meta property="og:site_name" content="<?php echo site_name(); ?>">
	    <meta property="og:description" content="<?php echo site_description(); ?>">

    	<!-- Mobile Specific Metas
      ================================================== -->
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    	<!-- CSS
      ================================================== -->
    	<link rel="stylesheet" href="<?php echo theme_url('css/style.css'); ?>">

		<!-- Typekit Embed
	  ================================================== -->
    	<script type="text/javascript" src="//use.typekit.net/pgu7wte.js"></script>
    	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

		<!-- JS
	  ================================================== -->
		<script>var base = '<?php echo theme_url(); ?>';</script>
		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<?php if (is_article()): ?>
			<style>
				header#top {
					background: url('<?php echo article_custom_field("header_img"); ?>') center no-repeat !important;
					background-size: cover;
				}
			</style>
		<?php endif ?>

    </head>
    <body class="<?php echo body_class(); ?>">

    	<header id="top" <?php echo (is_article() ? 'class="article"' : ''); ?>>
    		<nav id="main" class="ralign">
    			<div class="container">
    				<a href="<?php echo base_url(); ?>" class="ThirstyRough menu logo"><?php echo site_name(); ?></a>

					<?php if (Auth::user() && !is_homepage() && !is_article() && page_slug() !== 'rules'): ?>
						<a href="<?php echo Uri::to('logout'); ?>" class="button" style="font-size: .75em; margin-top: .75em; padding: .25em 1.5em 0;">Log Out</a>
						<a href="#" id="save" class="button white">Save</a>
					<?php else: ?>
	    				<a href="<?php echo (is_homepage() ? '' : base_url()); ?>#stories" class="button"><span class="icon">&#128213;</span><span class="text"> Choose a chapter</span></a>
	    				<a href="#write" class="button"><span class="icon">&#9998;</span><span class="text"> Write your own</span></a>
					<?php endif ?>
    			</div>

    			<form action="<?php echo Uri::to('signup'); ?>" id="signup" name="signup" method="post" class="lalign">
    				<div class="container">
    					<fieldset class="grid 4of12">
    						<input type="text" name="real_name" placeholder="Name" />

    						<?php echo Form::email('email', '', array(
    							'id' => 'email',
    							'autocapitalize' => 'off',
    							'autofocus' => 'true',
    							'placeholder' => __('users.email')
    						)); ?>

    						<?php echo Form::password('password', array(
    							'id' => 'password',
    							'placeholder' => __('users.password')
    						)); ?>
    						<input type="hidden" name="uri" value="<?php echo current_url(); ?>">
    					</fieldset>
    					<fieldset class="grid 6of12">
    						<p>
    							<label for="terms"><input type="checkbox" name="terms" /> Please check this box to confirm that you agree to the <a href="<?php echo Uri::to('rules'); ?>">Rules</a>.</label>
							</p>
							<p>
								You will be added to a waiting list, and will receive an email when it is time to write and upload your chapter.
    						</p>
    					<!--</fieldset>
    					<fieldset class="grid 2of12">
    						<input type="text" name="dummy" style="visibility: hidden;" />-->
    						<input type="submit" name="submit" value="Go" />
    					</fieldset>
    				</div>
    			</form>
    		</nav>

			<?php if (current_url() == '/' || current_url() == 'home' || current_url() == 'login'): ?>
	    		<div class="container center">
	    			<h1 class="ThirstyRough"><?php echo site_name(); ?></h1>
	    			<?php if (current_url() == '/' || current_url() == 'home'): ?>
		    			<h4><?php echo parse(site_description()); ?></h4>
	    			<?php endif ?>
	    		</div>

    			<?php if (current_url() == '/' || current_url() == 'home'): ?>
		    		<nav id="intro" class="center">
		    			<a href="#stories" class="button story">Dive in<span class="six-two-two">to the story</span></a> <small><u>or</u></small> <a href="#about" class="button about">Learn more<span class="six-two-two"> about the project</span></a>
		    		</nav>
    			<?php endif ?>
			<?php endif ?>

			<?php if (is_article()): ?>
				<?php $header_style = article_custom_field('header_style'); ?>
		        <?php if (!$header_style || $header_style == 0): ?>
					<div class="container">
						<div class="grid 1of1"></div>
						<hgroup class="grid 1of2 ralign">
							<h1 class="<?php echo article_custom_field('header_font'); ?> article-title" style="margin-bottom: 0;"><?php echo article_title(); ?></h1>
						</hgroup>
						<hgroup class="grid 1of2 lalign">
							<h4 class="<?php echo article_custom_field('body_font'); ?>" style="font-weight: 700; font-style: normal; margin-bottom: 0;"><?php echo (article_id() > 0 ? 'Chapter ' . ucwords(num2wrd(article_id())) : 'The Prologue'); ?></h4>
							<h4 class="<?php echo article_custom_field('body_font'); ?>" style="margin-bottom: 0;">by <a <?php echo (article_author_twitter() ? 'href="http://twitter.com/' . article_author_twitter() . '"' : ''); ?> target="_blank" class="author-name"><?php echo article_author(); ?></a></h4>
						</hgroup>
					</div>
		        <?php elseif ($header_style == 1): ?>
		        	<div class="container">
		        		<hgroup class="grid 1of1 1align">
		        			<h1 class="<?php echo article_custom_field('header_font'); ?> article-title"><?php echo article_title(); ?></h1>
		        			<h4 class="<?php echo article_custom_field('body_font'); ?>" style="font-weight: 700; font-style: normal; margin-bottom: 0;"><?php echo (article_id() > 0 ? 'Chapter ' . ucwords(num2wrd(article_id())) : 'The Prologue'); ?></h4>
		        			<h4 class="<?php echo article_custom_field('body_font'); ?>" style="margin-bottom: 0;">by <a <?php echo (article_author_twitter() ? 'href="http://twitter.com/' . article_author_twitter() . '"' : ''); ?> target="_blank" class="author-name"><?php echo article_author(); ?></a></h4>
		        		</hgroup>
		        	</div>
		        <?php elseif ($header_style == 2): ?>
		        	<div class="container">
		        		<hgroup class="grid 1of1 center">
		        			<h1 class="<?php echo article_custom_field('header_font'); ?> article-title"><?php echo article_title(); ?></h1>
		        			<h4 class="<?php echo article_custom_field('body_font'); ?>" style="font-weight: 700; font-style: normal; margin-bottom: 0;"><?php echo (article_id() > 0 ? 'Chapter ' . ucwords(num2wrd(article_id())) : 'The Prologue'); ?></h4>
		        			<h4 class="<?php echo article_custom_field('body_font'); ?>" style="margin-bottom: 0;">by <a <?php echo (article_author_twitter() ? 'href="http://twitter.com/' . article_author_twitter() . '"' : ''); ?> target="_blank" class="author-name"><?php echo article_author(); ?></a></h4>
		        		</hgroup>
		        	</div>
		        <?php elseif ($header_style == 3): ?>
		        	<div class="container">
			        	<div class="grid 1of1"></div>
			        	<div class="grid 1of1"></div>
			        	<div class="grid 1of1"></div>
		        	</div>
		        <?php endif; ?>
			<?php endif ?>
    	</header>