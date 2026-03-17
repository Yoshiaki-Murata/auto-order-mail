<?php
session_start();
require_once __DIR__ . "/../config/function.php";

if (!empty($_POST)) {
    if (!empty($_POST["name"]) && !empty($_POST["password"])) {
        $name = $_POST["name"];
        $password = $_POST["password"];
        try {
            $db = db_connect();
            $sql = "SELECT id,name,password FROM `users` WHERE name=:name LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // $resultがからの時（ユーザー名が存在しないとき）
            if (empty($result)) {
                err_msg("ユーザー名が存在しません");
                he("login.php");
            }

            // まだパスワードハッシュしていないので封印
            if (!password_verify($password, $result["password"])) {
                err_msg("パスワードが一致しませんでした");
                he("login.php");
            }
            // パスワードハッシュしたら消す
            // if ($password !== $result["password"]) {
            //     err_msg("パスワードが一致しませんでした");
            //     he("login.php");
            // }
            session_regenerate_id(true);
            $_SESSION["user_id"] = $result["id"];
            $_SESSION["user_name"] = $result["name"];

            msg("成功");
            he("../index.php");
        } catch (PDOException $e) {
            err_msg("DBとの通信に失敗しました");
            he("login.php");
        }
    }
}
err_msg("パスワードまたはユーザー名が入力されていません");
he("login.php");
