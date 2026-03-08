<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

// 配列情報まとめ（必須項目の入力チェックと$_POSTの変数化の準備）
$all_array = ["equipment_name", "supplier_id", "memo"]; //すべての入力項目
$int_keys = ["supplier_id"]; //文字タイプがint型の入力項目
$required_keys = ["equipment_name", "supplier_id"]; //必須項目の入力項目
$dir = "list.php";
// 必須項目の入力チェック
empty_array_check($_POST, $required_keys, $dir);
// 変数化処理
set_all_post_vars($all_array, $int_keys);

try {
    $params = ph_creat($all_array, $int_keys);
    $sql = "INSERT INTO equipment
            (equipment_name,supplier_id,memo,created_at) 
            VALUES (:equipment_name,:supplier_id,:memo,now())";

    $stmt=sql_get($sql);
    $stmt->execute($params);

    msg("新規追加しました");
    he("list.php");
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}
