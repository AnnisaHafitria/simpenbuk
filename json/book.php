<?php
session_start();
require_once('../variable/variable.php');

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;

    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT * FROM books WHERE {search} ORDER BY created_at DESC LIMIT 8 OFFSET {page}";

    $searchQuery = '(title LIKE "%{search}%" OR writer LIKE "%{search}%" OR publisher LIKE "%{search}%")';
    $searchQuery = str_replace('{search}', $search, $searchQuery);

    $sql = str_replace('{page}', ($page*8)-8, $sql);
    $sql = str_replace('{search}', $searchQuery, $sql);
    $countSql = $sql;

    $raw = $var->query($sql);
    $books = [];

    while ($row = $raw->fetch_assoc()) {
        $books[] = $row;
    }

    $sql = str_replace('*', 'COUNT(*) as count', $countSql);
    $sql = str_replace('LIMIT 8 OFFSET '.($page*8)-8, '', $sql);
    $raw = $var->query($sql);
    // var_dump($sql);
    // die();
    $total = $raw->fetch_assoc()['count'] / 8;
    echo json_encode([
        'data' => $books,
        'meta' => [
            'page' => (int) $page,
            'maxPage' => ceil($total)
        ]
    ]);
    return;
}

header("HTTP/1.1 403");
echo json_encode('unauthorize');
return;