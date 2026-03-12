<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

check_array($_POST);

// users配列情報まとめ（必須項目の入力チェックと$_POSTの変数化の準備）
if (empty($_post["password"])) {
    //空の時はパスワード以外
    $all_array_users = ["id", "name", "user_id", "role_id", "password"];
} else {
    // 全項目取得
    $all_array_users = ["id", "name", "user_id", "role_id", "password"];
}
$int_keys_users = ["id", "user_id", "role_id"]; //文字タイプがint型の入力項目
$required_keys_users = ["id", "name", "user_id", "role_id"]; //必須項目の入力項目
$dir = "edit.php";

// 必須項目の入力チェック
empty_array_check($_POST, $required_keys_users, $dir);
// 変数化処理
set_all_post_vars($all_array_users, $int_keys_users);
// DB呼び出し
$db = db_connect();

// usersに情報を編集する。
try {
    if (!empty($password)) {
        // パスワードが入力されたとき

        $sql = "UPDATE users SET name=:name,password=:password,user_id=user_id,role_id=:role_id WHERE id=:id";

        // パスワードチェック（半角英数８文字以上）
        if (!preg_match("\A[a-zA-Z0-9]{8,}\z/", $password)) {
            err_msg("パスワードが半角英数８文字以上に該当しません。");
            he("edit.php");
        }
        // パスワードハッシュ化
        $password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // パスワードが入力されなかった時
        $sql = "UPDATE users SET name=:name,user_id=user_id,role_id=:role_id WHERE id=:id";
    }
    $stmt = sql_get($sql);
    $stmt->bindParam(":id",$id,PDO::PARAM_INT);
    $stmt->bindParam(":name",$name,PDO::PARAM_STR);
    $stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);
    $stmt->bindParam(":role_id",$role_id,PDO::PARAM_INT);

    // パスワードチェック
    if(!empty($password)){
        $stmt->bindParam(":password",$password,PDO::PARAM_STR);
    }

    $stmt->execute($params);
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}

// // employeesに情報を編集する。
// 全項目取得
$all_array_emps = ["user_id","employee_number", "employee_name", "email"];
$int_keys_emps = ["id", "user_id", "role_id"]; //文字タイプがint型の入力項目
$required_keys_emps = ["id", "name", "user_id", "role_id"]; //必須項目の入力項目

// 必須項目の入力チェック
empty_array_check($_POST, $required_keys_emps, $dir);
// 変数化処理
set_all_post_vars($all_array_emps, $int_keys_emps;
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
