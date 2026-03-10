<?php
session_start();

require_once __DIR__ . "/../config/function.php";
require_once __DIR__ . "/../mail/send_order_mail.php";
check_login();

check_array($_POST);

// 必須チェックと変数化
$all_array = ["supplier_id", "part_id", "quantity"];
$int_keys = ["supplier_id", "part_id", "quantity"];
$required_keys = ["supplier_id", "part_id", "quantity"];
// 必須チェック
empty_array_check($_POST, $required_keys, "create.php");
// 変数化
$supplier_id = (int)$_POST["supplier_id"];
$part_ids = $_POST["part_id"];
$qtys = $_POST["quantity"];
$user_id = $_SESSION["user_id"];


$db = db_connect();

try {
    // transaction開始
    $db->beginTransaction();

    // ordersに情報登録
    $sql_orders = "INSERT INTO orders
                    (order_date,supplier_id,user_id,created_at)
                    VALUES
                    (NOW(),:supplier_id,:user_id,NOW())";
    $stmt_orders = $db->prepare($sql_orders);
    $stmt_orders->bindParam(":supplier_id", $supplier_id, PDO::PARAM_INT);
    $stmt_orders->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt_orders->execute();
    $order_id = $db->lastInsertId();

    $sql = "INSERT INTO order_items
            (order_id,part_id,quantity,price,total_price) 
            VALUES 
            (:order_id,:part_id,:quantity,:price,:total_price)";
    $stmt = $db->prepare($sql);

    //送信されてきた$part_idと$qtysを分解する
    foreach ($part_ids as $i => $part_id) {
        $part_id = (int)$part_id;
        $qty = (int)$qtys[$i];

        if ($part_id <= 0 || $qty <= 0) {
            continue;
        }
        // 部品価格を取得
        $sql_parts = "SELECT price FROM parts WHERE id=:part_id";
        $stmt_parts = $db->prepare($sql_parts);
        $stmt_parts->bindParam(":part_id", $part_id, PDO::PARAM_INT);
        $stmt_parts->execute();

        $price = $stmt_parts->fetchColumn();
        $total_price = $price * $qty;

        // orders_itemに情報を登録
        $stmt->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $stmt->bindParam(":part_id", $part_id, PDO::PARAM_INT);
        $stmt->bindParam(":quantity", $qty, PDO::PARAM_INT);
        $stmt->bindParam(":price", $price, PDO::PARAM_INT);
        $stmt->bindParam(":total_price", $total_price, PDO::PARAM_INT);
        $stmt->execute();
    }
    $db->commit();

    // メール送信
    // send_order_mail($order_id);
    msg("発注が完了しました");
    he("list.php");
} catch (PDOException $e) {
    $db->rollBack();
    exit("エラー" . $e->getMessage());
}
