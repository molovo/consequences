<?php

/**
 * Admin actions
 */
Route::action('auth', function() {
	if(Auth::guest()) return Response::redirect('login');
});

Route::action('guest', function() {
	if(Auth::user()) return Response::redirect('create');
});

Route::action('csrf', function() {
	if( ! Csrf::check(Input::get('token'))) {
		Notify::error(array('Invalid token'));

		return Response::redirect('login');
	}
});

/**
 * Important pages
 */
$home_page = Registry::get('home_page');
$posts_page = Registry::get('posts_page');

/**
 * The Home page
 */
if($home_page->id != $posts_page->id) {
	Route::get(array('/', $home_page->slug), function() use($home_page) {
		Registry::set('page', $home_page);

		return new Template('page');
	});
}

/**
 * Post listings page
 */
$routes = array($posts_page->slug, $posts_page->slug . '/(:num)');

if($home_page->id == $posts_page->id) {
	array_unshift($routes, '/');
}

Route::get($routes, function($offset = 1) use($posts_page) {
	// get public listings
	list($total, $posts) = Post::listing(null, $offset, $per_page = Config::meta('posts_per_page'));

	// get the last page
	$max_page = ($total > $per_page) ? ceil($total / $per_page) : 1;

	// stop users browsing to non existing ranges
	if(($offset > $max_page) or ($offset < 1)) {
		return Response::create(new Template('404'), 404);
	}

	$posts = new Items($posts);

	Registry::set('posts', $posts);
	Registry::set('total_posts', $total);
	Registry::set('page', $posts_page);
	Registry::set('page_offset', $offset);

	return new Template('posts');
});

/**
 * View posts by category
 */
Route::get(array('category/(:any)', 'category/(:any)/(:num)'), function($slug = '', $offset = 1) use($posts_page) {
	if( ! $category = Category::slug($slug)) {
		return Response::create(new Template('404'), 404);
	}

	// get public listings
	list($total, $posts) = Post::listing($category, $offset, $per_page = Config::meta('posts_per_page'));

	// get the last page
	$max_page = ($total > $per_page) ? ceil($total / $per_page) : 1;

	// stop users browsing to non existing ranges
	if(($offset > $max_page) or ($offset < 1)) {
		return Response::create(new Template('404'), 404);
	}

	$posts = new Items($posts);

	Registry::set('posts', $posts);
	Registry::set('total_posts', $total);
	Registry::set('page', $posts_page);
	Registry::set('page_offset', $offset);
	Registry::set('post_category', $category);

	return new Template('posts');
});

/**
 * View article
 */
Route::get($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	if( ! $post = Post::slug($slug)) {
		return Response::create(new Template('404'), 404);
	}

	Registry::set('page', $posts_page);
	Registry::set('article', $post);
	Registry::set('category', Category::find($post->category));

	return new Template('article');
});

/**
 * Post a comment
 */
Route::post($posts_page->slug . '/(:any)', function($slug) use($posts_page) {
	if( ! $post = Post::slug($slug) or ! $post->comments) {
		return Response::create(new Template('404'), 404);
	}

	$input = Input::get(array('name', 'email', 'text'));

	$validator = new Validator($input);

	$validator->check('email')
		->is_email(__('comments.email_missing'));

	$validator->check('text')
		->is_max(3, __('comments.text_missing'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
	}

	$input['post'] = Post::slug($slug)->id;
	$input['date'] = Date::mysql('now');
	$input['status'] = Config::meta('auto_published_comments') ? 'approved' : 'pending';

	// remove bad tags
	$input['text'] = strip_tags($input['text'], '<a>,<b>,<blockquote>,<code>,<em>,<i>,<p>,<pre>');

	// check if the comment is possibly spam
	if($spam = Comment::spam($input)) {
		$input['status'] = 'spam';
	}

	$comment = Comment::create($input);

	Notify::success(__('comments.created'));

	// dont notify if we have marked as spam
	if( ! $spam and Config::meta('comment_notifications')) {
		$comment->notify();
	}

	return Response::redirect($posts_page->slug . '/' . $slug . '#comment');
});

/**
 * Rss feed
 */
Route::get(array('rss', 'feeds/rss'), function() {
	$uri = 'http://' . $_SERVER['HTTP_HOST'];
	$rss = new Rss(Config::meta('sitename'), Config::meta('description'), $uri, Config::app('language'));

	$query = Post::where('status', '=', 'published');

	foreach($query->get() as $article) {
		$rss->item(
			$article->title,
			Uri::full(Registry::get('posts_page')->slug . '/' . $article->slug),
			$article->description,
			$article->created
		);
	}

	$xml = $rss->output();

	return Response::create($xml, 200, array('content-type' => 'application/xml'));
});

/**
 * Json feed
 */
Route::get('feeds/json', function() {
	$json = Json::encode(array(
		'meta' => Config::get('meta'),
		'posts' => Post::where('status', '=', 'published')->get()
	));

	return Response::create($json, 200, array('content-type' => 'application/json'));
});

/**
 * Search
 */
Route::get(array('search', 'search/(:any)', 'search/(:any)/(:num)'), function($slug = '', $offset = 1) {
	// mock search page
	$page = new Page;
	$page->id = 0;
	$page->title = 'Search';
	$page->slug = 'search';

	// get search term
	$term = Session::get($slug);

	list($total, $posts) = Post::search($term, $offset, Config::meta('posts_per_page'));

	// search templating vars
	Registry::set('page', $page);
	Registry::set('page_offset', $offset);
	Registry::set('search_term', $term);
	Registry::set('search_results', new Items($posts));
	Registry::set('total_posts', $total);

	return new Template('search');
});

Route::post('search', function() {
	// search and save search ID
	$term = filter_var(Input::get('term', ''), FILTER_SANITIZE_STRING);

	Session::put(slug($term), $term);

	return Response::redirect('search/' . slug($term));
});

/**
 * Author login
 */
Route::get('login', array('before' => 'guest', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();

	if( ! $page = Page::slug($slug = basename('login'))) {
		return Response::create(new Template('404'), 404);
	}

	if($page->redirect) {
		return Response::redirect($page->redirect);
	}

	Registry::set('page', $page);

	return new Template('login', $vars);
}));

Route::post('login', array('before' => 'csrf', 'main' => function() {
	$attempt = Auth::authorAttempt(Input::get('email'), Input::get('pass'));

	if( ! $attempt) {
		Notify::error(__('users.login_error'));

		return Response::redirect('login');
	}

	if( ! $page = Page::slug($slug = basename('login'))) {
		return Response::create(new Template('404'), 404);
	}

	if($page->redirect) {
		return Response::redirect($page->redirect);
	}

	Registry::set('page', $page);

	return Response::redirect('create');
}));

/*
	Log out
*/
Route::get('logout', function() {
	Auth::logout();
	Notify::notice(__('users.logout_notice'));
	return Response::redirect('home');
});

Route::get(array('create', 'edit', 'edit/(:any)'), array('before' => 'auth', 'main' => function($slug = '') {
	$vars['messages'] = Notify::read();

	if ( $post = Post::where('author', '=', Auth::user()->id)->fetch() ) {
		if ( $slug !== $post->slug ) {
			return Response::redirect('edit/' . $post->slug);
		}
	}

	if( $slug && !$post = Post::slug($slug) ) {
		return Response::create(new Template('404'), 404);
	}

	$vars['user'] = Auth::user();
	if ($slug) {
		$vars['post'] = $post;
		$vars['action'] = 'edit/' . $slug;
	} else {
		$vars['post'] = array();
		$vars['action'] = 'create';
	}

	if( ! $page = Page::slug(basename('create'))) {
		return Response::create(new Template('404'), 404);
	}

	if($page->redirect) {
		return Response::redirect($page->redirect);
	}

	Registry::set('page', $page);

	return new Template('create', $vars);
}));

Route::post('create', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('title', 'html'));
	$input['slug'] = str_replace(' ', '-', slug($input['title']));

	$validator = new Validator($input);

	$validator->add('duplicate', function($str) {
		return Post::where('slug', '=', $str)->count() == 0;
	});

	$validator->check('title')
		->is_max(3, __('posts.title_missing'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('create');
	}

	$input['created'] = Date::mysql('now');

	$user = Auth::user();
	$user_info = Input::get(array('real_name', 'twitter'));
	User::update($user->id, $user_info);

	$input['author'] = $user->id;

	$input['comments'] = 0;

	$input['status'] = 'draft';
	$input['id'] = Post::where('created', '<', $input['created'])->count();

	$post = Post::create($input);

	Extend::process('post', $post->id);

	Notify::success(__('posts.created'));

	return Response::redirect('edit/' . $input['slug']);
}));

Route::post('edit/(:any)', array('before' => 'auth', 'main' => function($slug) {
	$input = Input::get(array('title', 'html'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('posts.title_missing'));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('create');
	}

	$user = Auth::user();
	$user_info = Input::get(array('real_name', 'twitter'));
	User::update($user->id, $user_info);

	$input['comments'] = 0;

	$id = Post::slug($slug)->id;

	$post = Post::update($id, $input);

	Extend::process('post', $id);

	Notify::success(__('posts.created'));

	return Response::redirect('posts/' . $slug);
}));

Route::post('signup', function() {
	$vars['messages'] = Notify::read();

	$input = Input::get(array('email', 'real_name', 'password'));

	$input['username'] = str_replace(' ', '-', slug($input['real_name']));

	$validator = new Validator($input);

	$validator->check('username')
		->is_max(2, __('users.username_missing', 2));

	$validator->check('email')
		->is_email(__('users.email_missing'));

	$validator->check('password')
		->is_max(6, __('users.password_too_short', 6));

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect(Input::get('uri'));
	}

	$input['status'] = 'inactive';
	$input['role'] = 'user';
	$input['password'] = Hash::make($input['password']);

	User::create($input);

	Notify::success(__('users.created'));

	return Response::redirect(Input::get('uri'));
});

Route::get('like/(:any)', function($slug) {
	if (Post::like($slug)) {
		return Response::redirect('posts/' . $slug);
	}
});

/**
 * View pages
 */
Route::get('(:all)', function($uri) {
	$vars['messages'] = Notify::read();

	if( ! $page = Page::slug($slug = basename($uri))) {
		return Response::create(new Template('404'), 404);
	}

	if($page->redirect) {
		return Response::redirect($page->redirect);
	}
	// get public listings
	list($total, $posts) = Post::listing(null, 1, 9999);

	$posts = new Items($posts);

	/*Registry::set('posts', $posts);
	Registry::set('total_posts', $total);
	Registry::set('page', $posts_page);
	Registry::set('page_offset', $offset);*/
	Registry::set('page', $page);

	return new Template('page', $vars);
});