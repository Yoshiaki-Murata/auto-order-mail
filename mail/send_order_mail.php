<?php
require_once __DIR__ . "/../config/function.php";

function send_order_mail($order_id)
{
    $db = db_connect();

    // 発注情報を取得する
    $sql_orders = "SELECT orders.id,orders.order_date,suppliers.company_name,suppliers. email,suppliers.contact_name
            FROM orders INNER JOIN suppliers 
            ON suppliers.id=orders.supplier_id 
            WHERE orders.id=:id";
    $stmt_orders = $db->prepare($sql_orders);
    $stmt_orders->bindParam(":id", $oredr_id, PDO::PARAM_INT);
    $stmt_orders->execute();

    $order = $stmt_orders->fetchAll(PDO::PARAM_INT);

    // 部品情報を取得
    $sql_parts = " SELECT parts.part_name,parts.model_number,order_items.quantity
                    FROM order_items
                    INNER JOIN parts
                    ON parts.id=order_items.part_id
                    WHERE order_items.order_id=:id";
    $stmt_parts = $db->prepare($sql_parts);
    $stmt_parts->bindParam(":id", $oredr_id, PDO::PARAM_INT);
    $stmt_parts->execute();

    $items = $stmt_parts->fetchAll(PDO::FETCH_ASSOC);

    // メール本文
    // まずは初期化
    $body = "";
    // 宛名を作成
    $body .= $order["company_name"] . "\n";
    $body .= $order["contact_name"] . "様\n\n";
    // 本文作成
    $body .= "いつもお世話になっております。\n";
    $body .= "下記の部品の発注をお願い致します。\n\n";
    $body .= "-----------------------------------\n";
    // 部品情報を作成する
    foreach ($items as $p) {
        $body .= $p["part_name"] . "・・・型式:";
        $body .= $p["model_number"] . "  数量:" . $p["quantity"];
        $body .= "/n";
    }
    $body .= "-----------------------------------\n";
    $body .= "よろしくお願いいたします。\n";

    // メール送信

    mb_language("japanese");
    mb_internal_encoding("UTF-8");

    $to = $order["email"];
    $subject="部品発注のお願い";

    $header="From: system@example.com";
    return mb_send_mail($to,$subject,$body,$header);
}
