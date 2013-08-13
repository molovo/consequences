			<footer id="bottom">
				<div class="container">
					<div class="grid 1of2 updates">
						<h3 class="ThirstyRough">Keep updated</h3>

						<p>
							Follow us on Twitter.
						</p>
						<a href="https://twitter.com/intent/follow?screen_name=consequences_co" class="button" target="_blank"><span class="social-icon">&#62217;</span> @Consequences_co</a>
					</div>

					<div class="grid 1of2 github">
						<h3 class="ThirstyRough">Help out</h3>

						<p>
							The story is a community project. This site is too.
						</p>
						<a href="http://github.com/molovo/consequences" class="button" target="_blank"><span class="social-icon">&#62208;</span> Contribute on Github</a>
					</div>
				</div>

				<div class="attribution">
					<div class="container">
						<div class="grid 1of2">
							An experiment by <a href="http://twitter.com/molovo" target="_blank">@molovo</a>
						</div>
						<div class="grid 1of2 ralign">
							<a href="<?php echo Uri::to('login'); ?>">Author Login</a>
						</div>
					</div>
				</div>
			</footer>

			<script src="<?php echo theme_url('js/smoothscroll.js'); ?>"></script>
			<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script>
				jQuery(document).ready(function($) {
					$('#stories a').click(function() {
						var heading = $(this).find('h2').attr('class');
						var paragraph = $(this).find('p').attr('class');

						$('section#content h1').attr('class', heading);
						$('section#content p').attr('class', paragraph);
					});

					var top = $('header#top').height() - $('nav#intro').height();
					$(window).scroll(function() {
						var y = $(this).scrollTop();

						if (!$('#signup').is(':visible')) {
							if (y > top) {
								$('.home nav#main, .article nav#main').fadeIn(300);
							} else {
								$('.home nav#main, .article nav#main').fadeOut(300);
							}
						}
					});

					$('a[href="#write"]').click(function() {
						$('nav#main').fadeIn(300);
						$('form#signup').fadeToggle(300);
						$('nav#main a[href="#write"]').toggleClass('active');
						return false;
					});
				});
			</script>

			<?php if (Registry::get('page')->slug == 'create'): ?>
			<script src="<?php echo theme_url('js/unslider.js'); ?>"></script>
			<script>
					/*$('.headerType').unslider({
						dots: true,
						keys: true
					});*/

					unslider = $('.headerType').unslider({
						keys: false,
						fluid: true
					});
					data = unslider.data('unslider');

						jQuery(document).ready(function($) {
							data.stop();
						});

					    $('.unslider-arrow').click(function() {
					        var fn = this.className.split(' ')[1];

					        //  Either do unslider.data('unslider').next() or .prev() depending on the className
					        unslider.data('unslider')[fn]();
					        return false;
					    });

					$('#title').keyup(function() {
						$('.article-title').html($(this).val());
					});

					$('#real_name').keyup(function() {
						$('.author-name').html($(this).val());
					});

					$('#twitter').keyup(function() {
						$('.author-name').attr('href', 'http://twitter.com/' + $(this).val());
					});

					//$('#edContent').trigger('keyup');

					$('#save').click(function() {
						$('#article-meta form').submit();
					});

					/**
					 * Populate placeholder when user selects a file to upload
					 */
					$(function() {
						var basename = function(path) {
							return path.replace(/\\/g,'/').replace(/.*\//, '');
						};

						$('input[type=file]').bind('change', function() {
							var input = $(this), placeholder = input.parent().parent().find('.current-file');

							placeholder.html(basename(input.val()));
						});
					});
			</script>
			<?php endif; ?>
		</body>
	</html>