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

    $sql = "SELECT book_user.*, books.title, users.name FROM book_user JOIN books ON books.id = book_user.book_id JOIN users ON users.id = book_user.user_id WHERE ({search}) AND (1=1 {filter}) ORDER BY book_user.created_at DESC LIMIT 10 OFFSET {page}";
    $filter = "";
    $searchQuery = "";

    if ($currentUser['role'] == 'Member') {
        $filter = 'AND user_id="'.$currentUser['id'].'"';
        $searchQuery = 'books.title LIKE "%{search}%" OR books.writer LIKE "%{search}%" OR books.publisher LIKE "%{search}%"';
        $searchQuery = str_replace('{search}', $search, $searchQuery);
    }

    if ($currentUser['role'] == 'Superadmin' || $currentUser['role'] == 'Admin'  ) {
        $searchQuery = 'books.title LIKE "%{search}%" OR books.writer LIKE "%{search}%" OR books.publisher LIKE "%{search}%" OR users.name LIKE "%{search}%" OR users.email LIKE "%{search}%"';
        $searchQuery = str_replace('{search}', $search, $searchQuery);
    }

    $sql = str_replace('{page}', ($page*10)-10, $sql);
    $sql = str_replace('{filter}', $filter, $sql);
    $sql = str_replace('{search}', $searchQuery, $sql);
    $countSql = $sql;

    $raw = $var->query($sql);
    $bookUser = [];

    while ($row = $raw->fetch_assoc()) {
        $row['status'] = $var->getStatus($row['status']);
        $bookUser[] = $row;
    }

    $sql = str_replace('book_user.*, books.title, users.name', 'COUNT(*) as count', $countSql);
    $sql = str_replace('LIMIT 10 OFFSET '.($page*10)-10, '', $sql);
    $raw = $var->query($sql);

    $total = $raw->fetch_assoc()['count'] / 10;
    echo json_encode([
        'data' => $bookUser,
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