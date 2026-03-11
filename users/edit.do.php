<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

check_array($_POST);
// "SELECT users.id,users.name,users.password,users.user_id,users.role_id,
// 		employees.id,employees.employee_number,employees.name AS employee_name,employees.email,
//         roles.id,roles.role
// 		FROM users 
//         INNER JOIN employees ON employees.id =users.user_id
//         INNER JOIN roles ON roles.id=users.role_id
//         WHERE users.id=:id";

// users配列情報まとめ（必須項目の入力チェックと$_POSTの変数化の準備）
$all_array_users = ["id","name","user_id","role_id","password"]; //すべての入力項目
$int_keys_users = ["id","user_id","role_id"]; //文字タイプがint型の入力項目
$required_keys_users = ["id","name","user_id","role_id"]; //必須項目の入力項目
$dir = "edit.php";

// 必須項目の入力チェック
empty_array_check($_POST, $required_keys_users, $dir);
// 変数化処理
set_all_post_vars($all_array_users, $int_keys_users);

// usersに情報を編集する。
// try {
//     $params = ph_creat($all_array, $int_keys);
//     $sql = "UPDATE parts SET
//      part_name=:part_name,
//      equipment_id=:equipment_id,
//      model_number=:model_number,
//      price=:price,
//      category_id=:category_id,
//      memo=:memo 
//       WHERE id=:id";
//     $stmt=sql_get($sql);
//     $stmt->execute($params);

//     msg("編集完了しました");
//     he("list.php");
// } catch (PDOException $e) {
//     exit("エラー：" . $e->getMessage());
// }

// // employeesに情報を編集する。
// try {
//     $params = ph_creat($all_array, $int_keys);
//     $sql = "UPDATE parts SET
//      part_name=:part_name,
//      equipment_id=:equipment_id,
//      model_number=:model_number,
//      price=:price,
//      category_id=:category_id,
//      memo=:memo 
//       WHERE id=:id";
//     $stmt=sql_get($sql);
//     $stmt->execute($params);

//     msg("編集完了しました");
//     he("list.php");
// } catch (PDOException $e) {
//     exit("エラー：" . $e->getMessage());
// }
