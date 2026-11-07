<?php
require __DIR__.'/../includes/bootstrap.php';header('Content-Type: application/json; charset=utf-8');
$q=trim((string)($_GET['q']??''));if(mb_strlen($q)>100){http_response_code(400);echo json_encode(['error'=>'Query too long']);exit;}
$like='%'.$q.'%';$st=$conn->prepare("SELECT id,title,author,price FROM books WHERE status='active' AND (title LIKE ? OR author LIKE ?) ORDER BY title LIMIT 12");$st->bind_param('ss',$like,$like);$st->execute();echo json_encode($st->get_result()->fetch_all(MYSQLI_ASSOC),JSON_UNESCAPED_UNICODE);
