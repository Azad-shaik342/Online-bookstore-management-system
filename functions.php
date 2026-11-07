<?php
declare(strict_types=1);
function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path=''): string { return rtrim(BASE_URL,'/') . '/' . ltrim($path,'/'); }
function redirect(string $path): never { header('Location: ' . (str_starts_with($path,'http') ? $path : url($path))); exit; }
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="'.e($_SESSION['csrf_token'] ?? '').'">'; }
function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
        http_response_code(419); exit('Your form session expired. Please go back and try again.');
    }
}
function flash(string $type,string $message): void { $_SESSION['flash'][]=['type'=>$type,'message'=>$message]; }
function get_flashes(): array { $f=$_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $f; }
function money(int|float|string $v): string { return '₹'.number_format((float)$v,2); }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function post_string(string $key,int $max=500): string {
    $v=$_POST[$key] ?? ''; return is_string($v) ? trim(mb_substr($v,0,$max)) : '';
}
function valid_image_upload(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($file['size'] ?? 0)>2*1024*1024) throw new RuntimeException('Image must be under 2 MB.');
    $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($file['tmp_name']);
    $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime] ?? null;
    if (!$ext) throw new RuntimeException('Only JPG, PNG and WEBP images are allowed.');
    $name=bin2hex(random_bytes(16)).'.'.$ext; $dir=__DIR__.'/../uploads/books/';
    if (!is_dir($dir)) mkdir($dir,0755,true);
    if (!move_uploaded_file($file['tmp_name'],$dir.$name)) throw new RuntimeException('Could not save uploaded image.');
    return 'uploads/books/'.$name;
}
