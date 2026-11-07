<?php
declare(strict_types=1);
function is_logged_in(): bool { return !empty($_SESSION['user']['id']); }
function is_admin(): bool { return is_logged_in() && ($_SESSION['user']['role'] ?? '')==='admin'; }
function require_login(): void { if(!is_logged_in()){ flash('warning','Please sign in to continue.'); redirect('auth/login.php'); } }
function require_admin(mysqli $conn): void {
    require_login();
    $stmt=$conn->prepare("SELECT id,name,email,role,status FROM users WHERE id=? LIMIT 1");
    $stmt->bind_param('i',$_SESSION['user']['id']); $stmt->execute(); $u=$stmt->get_result()->fetch_assoc();
    if(!$u || $u['role']!=='admin' || $u['status']!=='active'){ http_response_code(403); exit('403 — administrator access required.'); }
    $_SESSION['user']=$u;
}
function login_user(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user']=['id'=>(int)$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']];
}
function logout_user(): void { $_SESSION=[]; if(ini_get('session.use_cookies')){ $p=session_get_cookie_params(); setcookie(session_name(),'',$p['lifetime'] ? time()-42000 : time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy(); }
