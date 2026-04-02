<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
check_array($_POST);

// 配列情報まとめ（必須項目の入力チェックと$_POSTの変数化の準備）
$all_array = ["employee_number", "employee_name", "name", "password", "email", "role_id"]; //すべての入力項目
$int_keys = ["role_id"]; //文字タイプがint型の入力項目
$required_keys = ["employee_number", "employee_name", "name", "password", "email", "role_id"]; //必須項目の入力項目
$dir = "create.php";
// 必須項目の入力チェック
empty_array_check($_POST, $required_keys, $dir);
// 変数化処理
set_all_post_vars($all_array, $int_keys);

try {
    $db = db_connect();

    // トランザクション開始
    $db->beginTransaction();

    // 1. 社員情報 (employees) の登録
    $sql_emp = "INSERT INTO `employees`
                (`employee_number`, `name`, `email`, `created_at`) 
                VALUES 
                (:emp_num, :employee_name, :email, NOW())";
    
    $stmt_emp = $db->prepare($sql_emp);
    $stmt_emp->bindValue(":emp_num", $employee_number, PDO::PARAM_STR);
    $stmt_emp->bindValue(":employee_name", $employee_name, PDO::PARAM_STR);
    $stmt_emp->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt_emp->execute();

    // 2. 今作成した社員の ID (PK) を取得
    $new_employee_id = $db->lastInsertId();

    // 3. ユーザー情報 (users) の登録
    $sql_user = "INSERT INTO `users`
                (`name`, `password`, `user_id`, `role_id`, `created_at`) 
                VALUES 
                (:name, :password, :user_id, :role_id, NOW())";
    
    $stmt_user = $db->prepare($sql_user);
    // users.name はログインIDとして使う想定
    $stmt_user->bindValue(":name", $name, PDO::PARAM_STR); 
    // パスワードは必ずハッシュ化する
    $stmt_user->bindValue(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
    // ここで employees.id を紐付ける
    $stmt_user->bindValue(":user_id", $new_employee_id, PDO::PARAM_INT);
    $stmt_user->bindValue(":role_id", $role_id, PDO::PARAM_INT);
    $stmt_user->execute();

    // 両方成功したらコミット
    $db->commit();

    msg("社員およびユーザーアカウントを登録しました");
    he("list.php");

} catch (PDOException $e) {
    // どちらかで失敗したら、両方の書き込みを取り消す
    if ($db) $db->rollBack();
    exit("登録エラー：" . $e->getMessage());
}
