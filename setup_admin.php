<?php
// Run only from CLI: php setup_admin.php admin@example.com "StrongPassword123" "Admin Name"
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/config/database.php';
if($argc<4){fwrite(STDERR,"Usage: php setup_admin.php email password name\n");exit(1);}
$email=strtolower(trim($argv[1]));$password=$argv[2];$name=$argv[3];
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<12){fwrite(STDERR,"Use a valid email and a password of at least 12 characters.\n");exit(1);}
$hash=password_hash($password,PASSWORD_DEFAULT);
$stmt=$conn->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,'admin')");
$stmt->bind_param('sss',$name,$email,$hash);$stmt->execute();
echo "Administrator created. Store the credentials securely and remove this script from public hosting.\n";
