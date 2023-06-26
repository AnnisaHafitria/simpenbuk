<?php
session_start();
require_once('../variable/variable.php');

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;

    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    $id = isset($_GET['id']) ? $_GET['id'] : 1;

    $sql = "SELECT book_user.*, books.title, users.name FROM book_user JOIN books ON books.id = book_user.book_id JOIN users ON users.id = book_user.user_id WHERE book_user.id = {id}";

    $sql = str_replace('{id}', $id, $sql);

    $raw = $var->query($sql);
   
    echo json_encode([
        'data' => $raw->fetch_assoc(),
    ]);
    return;
}

header("HTTP/1.1 403");
echo json_encode('unauthorize');
return;