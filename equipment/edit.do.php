<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

check_array($_POST);

// 配列情報まとめ（必須項目の入力チェックと$_POSTの変数化の準備）
$all_array = ["id","equipment_name","supplier_id","memo"]; //すべての入力項目
$int_keys = ["id","supplier_id"]; //文字タイプがint型の入力項目
$required_keys = ["id", "equipment_name", "supplier_id"]; //必須項目の入力項目
$dir = "edit.php";
// 必須項目の入力チェック
empty_array_check($_POST, $required_keys, $dir);
// 変数化処理
set_all_post_vars($all_array, $int_keys);

try {
    $params = ph_creat($all_array, $int_keys);
    $sql = "UPDATE equipment SET equipment_name=:equipment_name,supplier_id=:supplier_id,memo=:memo WHERE id=:id";
    $stmt=sql_get($sql);
    $stmt->execute($params);

    msg("編集完了しました");
    he("list.php");
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}
