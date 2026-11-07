<?php
require __DIR__.'/../includes/bootstrap.php';require_login();$uid=(int)current_user()['id'];$error='';
$st=$conn->prepare("SELECT ci.book_id,ci.quantity,b.title,b.price,b.stock FROM cart c JOIN cart_items ci ON ci.cart_id=c.id JOIN books b ON b.id=ci.book_id WHERE c.user_id=?");$st->bind_param('i',$uid);$st->execute();$items=$st->get_result()->fetch_all(MYSQLI_ASSOC);if(!$items)redirect('cart/index.php');
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$address=post_string('shipping_address',500);$phone=post_string('phone',25);
if(strlen($address)<10||!preg_match('/^[0-9+\-\s]{7,25}$/',$phone))$error='Enter a complete address and valid phone number.';
else{try{$conn->begin_transaction();$total=0;foreach($items as $it){if((int)$it['stock']<(int)$it['quantity'])throw new RuntimeException('Insufficient stock for '.$it['title']);$total+=(float)$it['price']*(int)$it['quantity'];}
$st=$conn->prepare("INSERT INTO orders(user_id,total_amount,shipping_address,phone,status) VALUES(?,?,?,?,'pending')");$st->bind_param('idss',$uid,$total,$address,$phone);$st->execute();$oid=$conn->insert_id;
$oi=$conn->prepare("INSERT INTO order_items(order_id,book_id,quantity,unit_price) VALUES(?,?,?,?)");$stock=$conn->prepare("UPDATE books SET stock=stock-? WHERE id=? AND stock>=?");
foreach($items as $it){$bid=(int)$it['book_id'];$qty=(int)$it['quantity'];$price=(float)$it['price'];$oi->bind_param('iiid',$oid,$bid,$qty,$price);$oi->execute();$stock->bind_param('iii',$qty,$bid,$qty);$stock->execute();if($stock->affected_rows!==1)throw new RuntimeException('Stock changed during checkout. Please try again.');}
$del=$conn->prepare("DELETE ci FROM cart_items ci JOIN cart c ON c.id=ci.cart_id WHERE c.user_id=?");$del->bind_param('i',$uid);$del->execute();$conn->commit();flash('success','Order placed successfully. Order #'.$oid);redirect('orders/index.php');
}catch(Throwable $e){$conn->rollback();$error=$e instanceof RuntimeException?$e->getMessage():'Checkout failed. Please try again.';}}
}
$pageTitle='Checkout';require __DIR__.'/../includes/header.php';?>
<section class="page-hero"><div class="container"><p class="eyebrow">ALMOST YOURS</p><h1>Complete <em>your order.</em></h1></div></section><section class="container section"><form method="post" class="auth-card checkout-card"><?=csrf_field()?><?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?><label>Shipping address<textarea name="shipping_address" required minlength="10" rows="3"></textarea></label><label>Phone number<input name="phone" required></label><p class="checkout-total">Order total <strong><?=money(array_sum(array_map(fn($x)=>(float)$x['price']*(int)$x['quantity'],$items)))?></strong></p><button class="btn btn-dark full">Place order ↗</button><small class="muted">Demo checkout: no payment is collected. Orders are recorded in your database.</small></form></section>
<?php require __DIR__.'/../includes/footer.php';?>
