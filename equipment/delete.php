<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

check_array($_POST);
// 配列情報まとめ（必須項目の入力チェックと$_POSTの変数化の準備）
$all_array = ["id"]; //すべての入力項目
$int_keys = ["id"]; //文字タイプがint型の入力項目
$required_keys = ["id"]; //必須項目の入力項目
$dir = "list.php";
// 必須項目の入力チェック
empty_array_check($_POST, $required_keys, $dir);

try {
    $params = ph_creat($all_array, $int_keys);
    $sql = "DELETE FROM equipment WHERE id=:id";
    $stmt=sql_get($sql);
    $stmt->execute($params);

    msg("削除しました");
    he("list.php");
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}

?>