<?php
require_once __DIR__ . "/db_info.php";

// DB接続
function db_connect(){
    $dsn="mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
    $db=new PDO($dsn,DB_USER,DB_PASS);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    return $db;
}

// function db_connect(){
//     $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
//     try {
//         $db = new PDO($dsn, DB_USER, DB_PASS);
//         // エラーが発生したときに「例外」を投げる設定
//         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
//         return $db;
//     } catch (PDOException $e) {
//         // 接続失敗時にここでエラー内容を直接表示
//         die("データベース接続失敗: " . $e->getMessage());
//     }
// }

// var_dump関数
function check_array($a){
    echo "<pre>";
    var_dump($a);
    echo "</pre>";
}

// エスケープ処理
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// header関数
function he($dir){
    header("location:".$dir);
    exit();
}

// エラーメッセージ関数
function msg($str){
    $_SESSION["msg"]=$str;
}

// エラーメッセージ関数
function err_msg($str){
    $_SESSION["err"]=$str;
}

// ログイン状態でないとトップページいけない
function check_login(){
    if(empty($_SESSION["user_id"])){
        err_msg("不正ログインを検出");
        he("/order_system/auth/login.php");
        exit();
    }
}

// csrfトークン生成関数
function generate_csrf_token(){
    if(!isset($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

// DBよりテーブル内全ての情報を取得する関数
function db_all_get($table_name){
$db=db_connect();
$sql="SELECT * FROM $table_name WHERE 1";
$stmt=$db->prepare($sql);
$stmt->execute();
$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

return $result;
}

// SQL文に応じた内容でプリペアまでする関数

function sql_get($s){
$db=db_connect();
$sql="$s";
$stmt=$db->prepare($sql);

return $stmt;
}

// 配列（$_POSTなど）と、チェックしたいキーのリストを受け取る関数
function empty_array_check($array, $keys, $dir) {
    // 1. まず配列自体が空っぽなら即アウト
    if (empty($array)) {
        err_msg("不正なアクセスです");
        he($dir);
    }

    // 2. 指定された各項目をループでチェック
    foreach ($keys as $key) {
        // もし1つでも空（または存在しない）ならアウト
        if (empty($array[$key])) {
            err_msg("入力項目（{$key}）が足りません");
            he($dir);
        }
    }
}

/**
 * POSTデータを一括取得する（必須・任意・型指定対応）
 * $keys      : 取得したい全ての項目（必須＋任意）
 * $int_keys  : 数値として扱いたい項目
 */
function set_all_post_vars($keys, $int_keys = []) {
    foreach ($keys as $key) {
        // 値が存在すればトリム、なければ空文字にする
        $val = isset($_POST[$key]) ? trim($_POST[$key]) : "";

        // 数値として扱いたい場合（かつ、空文字でなければintキャスト）
        if (in_array($key, $int_keys) && $val !== "") {
            $val = (int)$val;
        }
        // 変数化
        $GLOBALS[$key] = $val;
    }
}

// /**
//  * SQLとパラメータの配列を渡すと実行してくれる神関数
//  */
// function db_update($sql, $params) {
//     global $pdo; // PDOインスタンス
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute($params);
//     return $stmt;
// }


// プレースホルダを作成する関数
/**
 * POSTからSQL用パラメータ配列を作成する関数
 * $all_array: 使うキーのリスト
 * $int_keys : 数値として扱いたいキーのリスト
 */
function ph_creat($all_array, $int_keys = []) {
    $params = [];
    foreach ($all_array as $key) {
        // 値がなければ空文字、あればトリム
        $val = isset($_POST[$key]) ? trim($_POST[$key]) : "";
        
        // 指定されたキーなら int にキャスト
        if (in_array($key, $int_keys)) {
            $val = (int)$val;
        }
        
        // プレースホルダー用のキー名を作成 (:id, :company_name など)
        $params[":" . $key] = $val;
    }
    return $params;
}

// 入力エラー時に入力内容をセッションに保存してリダイレクトする関数
function err_redirect($url,$error_message){
    $_SESSION["old_input"]=$_POST;
    err_msg($error_message);
    he($url);
}

// リダイレクトされた際に保持された入力内容を表示する関数
function get_old_val($key,$result=[]){
    if(!empty($_SESSION["old_input"])){
        $var=$_SESSION["old_input"][$key];
        unset($_SESSION["old_input"][$key]);//表示したら消す
        return $var;
    }else{
        return $result[$key];//なければDBで取得した値を返す。
    }
}