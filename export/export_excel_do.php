<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

$db = db_connect();

$start = $_GET["start_date"];
$end   = $_GET["end_date"];

$filename = $start . "_" . $end . "_orders.csv";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen("php://output", "w");
fprintf($output,chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($output, [
    "発注日",
    "設備",
    "部品名",
    "単価",
    "数量",
    "発注金額",
    "発注先",
    "担当者"
]);

$sql = "SELECT
    o.order_date,
    e.equipment_name,
    p.part_name,
    oi.price,
    oi.quantity,
    oi.total_price,
    s.company_name,
    u.name
FROM orders o
JOIN order_items oi
ON oi.order_id = o.id
JOIN parts p
ON oi.part_id = p.id
JOIN suppliers s
ON o.supplier_id = s.id
JOIN equipment e
ON p.equipment_id = e.id
JOIN users u
ON o.user_id = u.id
WHERE o.order_date BETWEEN :start AND :end
ORDER BY o.order_date
";

$stmt = $db->prepare($sql);
$stmt->bindValue(":start", $start);
$stmt->bindValue(":end", $end);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
// header("loca")
exit;