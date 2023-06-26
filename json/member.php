<?php
session_start();
require_once('../variable/variable.php');

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;

    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    if ($currentUser['role'] != 'Superadmin' && $currentUser['role'] != 'Admin') {
        header("HTTP/1.1 403");
        echo json_encode('unauthorize');
        return;
    }

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT * FROM users WHERE {search} AND role IN (3) ORDER BY created_at DESC LIMIT 10 OFFSET {page}";

    $searchQuery = '(name LIKE "%{search}%" OR email LIKE "%{search}%" OR phone LIKE "%{search}%" OR address LIKE "%{search}%")';
    $searchQuery = str_replace('{search}', $search, $searchQuery);

    $sql = str_replace('{page}', ($page*10)-10, $sql);
    $sql = str_replace('{search}', $searchQuery, $sql);
    $countSql = $sql;

    $raw = $var->query($sql);
    $users = [];

    while ($row = $raw->fetch_assoc()) {
        $row['gender'] = $row['gender'] == 0 ? 'Male' : 'Female';
        $row['role'] = $var->getRole($row['role']);
        $users[] = $row;
    }

    $sql = str_replace('*', 'COUNT(*) as count', $countSql);
    $sql = str_replace('LIMIT 10 OFFSET '.($page*10)-10, '', $sql);
    $raw = $var->query($sql);
    // var_dump($sql);
    // die();
    $total = $raw->fetch_assoc()['count'] / 10;
    echo json_encode([
        'data' => $users,
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