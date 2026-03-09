<?php
session_start();

require_once __DIR__ . "/../config/function.php";
require_once __DIR__ . "/../mail/send_order_mail.php";
check_login();

check_array($_POST);

// 必須チェックと変スカ
$all_array = ["supplier_id", "part_id", "quantity"];
$int_keys = ["supplier_id", "part_id", "quantity"];
$required_keys = ["supplier_id", "part_id", "quantity"];
// 必須チェック
empty_array_check($_POST, $required_keys, "create.php");

$supplier_id = (int)$_POST["supplier_id"];
$part_ids = $_POST["part_id"];
$qtys = $_POST["quantity"];


$db = db_connect();

try {
    // ordersに情報登録
    $sql_orders = "INSERT INTO orders
                    (order_date,supplier_id,user_id,created_at)
                    VALUES
                    (NOW(),:supplier_id,:user_id,NOW())";
    $stmt_orders = $db->prepare($sql_orders);
    $stmt->

} catch (PDOException $e) {
    exit("エラー" . $e->getMessage());
}
