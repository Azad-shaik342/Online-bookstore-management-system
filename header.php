<?php $pageTitle=$pageTitle ?? 'Online Bookstore'; $user=current_user(); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($pageTitle)?> · PageTurn</title><meta name="description" content="Discover your next great read at PageTurn Online Bookstore.">
<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link href="<?=e(url('assets/css/style.css'))?>" rel="stylesheet"></head><body>
<header class="site-header"><div class="container nav"><a class="brand" href="<?=e(url())?>"><span class="brand-mark">P</span> PageTurn</a>
<nav><a href="<?=e(url())?>">Home</a><a href="<?=e(url('books/index.php'))?>">Books</a><?php if($user):?><a href="<?=e(url('orders/index.php'))?>">My orders</a><?php if(is_admin()):?><a href="<?=e(url('admin/index.php'))?>">Admin</a><?php endif;?><span class="nav-user">Hi, <?=e($user['name'])?></span><a href="<?=e(url('auth/logout.php'))?>">Sign out</a><?php else:?><a href="<?=e(url('auth/login.php'))?>">Sign in</a><a class="nav-cta" href="<?=e(url('auth/register.php'))?>">Join</a><?php endif;?></nav>
<a class="cart-link" href="<?=e(url('cart/index.php'))?>">Bag <span>↗</span></a></div></header><main>
<?php foreach(get_flashes() as $f):?><div class="container"><div class="alert <?=e($f['type'])?>"><?=e($f['message'])?></div></div><?php endforeach;?>
