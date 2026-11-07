<?php
require __DIR__.'/../includes/bootstrap.php';if(is_logged_in())redirect();
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$name=post_string('name',100);$email=strtolower(post_string('email',150));$pw=(string)($_POST['password']??'');
if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pw)<8){$error='Enter a name, valid email and password of at least 8 characters.';}
else{$hash=password_hash($pw,PASSWORD_DEFAULT);try{$st=$conn->prepare("INSERT INTO users(name,email,password_hash) VALUES(?,?,?)");$st->bind_param('sss',$name,$email,$hash);$st->execute();login_user(['id'=>$conn->insert_id,'name'=>$name,'email'=>$email,'role'=>'user']);flash('success','Welcome to PageTurn!');redirect();}catch(mysqli_sql_exception $e){$error=$e->getCode()===1062?'That email is already registered.':'Could not create your account.';}}
}
$pageTitle='Create account';require __DIR__.'/../includes/header.php';?>
<section class="auth-wrap"><form method="post" class="auth-card"><?=csrf_field()?><p class="eyebrow">JOIN THE BOOK CLUB</p><h1>Create an account</h1><p>Keep your next great read close.</p><?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?><label>Full name<input name="name" required maxlength="100" autocomplete="name"></label><label>Email address<input type="email" name="email" required autocomplete="email"></label><label>Password<input type="password" name="password" required minlength="8" autocomplete="new-password"></label><button class="btn btn-dark full">Create account ↗</button><p class="auth-foot">Already a member? <a href="<?=e(url('auth/login.php'))?>">Sign in</a></p></form></section>
<?php require __DIR__.'/../includes/footer.php';?>
