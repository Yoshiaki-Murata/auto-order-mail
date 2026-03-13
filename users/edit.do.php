<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

$dir = "edit.php";

// 1. 変数定義（設計書の物理名に準拠）
// ※ password は任意入力（変更時のみ）なので required には含めない
$all_keys = ["id", "user_id", "employee_number", "employee_name", "name", "email", "role_id", "password"];
$required_keys = ["id", "user_id", "employee_number", "employee_name", "name", "email", "role_id"];
$int_keys = ["id", "user_id", "role_id"];

// 2. バリデーションと変数展開
empty_array_check($_POST, $required_keys, $dir);
set_all_post_vars($all_keys, $int_keys);

$db = db_connect();
check_array($_POST);

try {
    $db->beginTransaction();

    // --- ① usersテーブルの更新 (name, password, role_id) ---
    if ($password !== "") {
        // パスワードバリデーション
        if (!preg_match("/\A[a-zA-Z0-9]{8,}\z/", $password)) {
            err_redirect($dir, "パスワードは半角英数8文字以上で入力してください。");
        }
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql1 = "UPDATE users SET name = :name, password = :password, role_id = :role_id WHERE id = :id";
    } else {
        $sql1 = "UPDATE users SET name = :name, role_id = :role_id WHERE id = :id";
    }

    $stmt1 = $db->prepare($sql1);
    $stmt1->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt1->bindParam(":role_id", $role_id, PDO::PARAM_INT);
    $stmt1->bindParam(":id", $id, PDO::PARAM_INT);
    if ($password !== "") {
        $stmt1->bindValue(":password", $password_hash, PDO::PARAM_STR);
    }
    $stmt1->execute();

    // --- ② employeesテーブルの更新 (employee_number, name, email) ---
    // users.user_id が employees.id を指しているため、WHERE句は user_id を使用
    $sql2 = "UPDATE employees SET 
                employee_number = :employee_number, 
                name = :employee_name, 
                email = :email 
             WHERE id = :employees_id";

    $stmt2 = $db->prepare($sql2);
    $stmt2->bindValue(":employee_number", $employee_number, PDO::PARAM_STR);
    $stmt2->bindValue(":employee_name", $employee_name, PDO::PARAM_STR);
    $stmt2->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt2->bindValue(":employees_id", $user_id, PDO::PARAM_INT);
    $stmt2->execute();

    $db->commit();

    msg("社員番号：" . h($employee_number) . " の情報を更新しました。");
    he("list.php");

} catch (PDOException $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    // ログ出力などが必要な場合はここに追加
    exit("システムエラーが発生しました。");
}