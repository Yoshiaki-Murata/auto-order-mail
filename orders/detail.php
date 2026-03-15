<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
$id = (int)$_GET["id"];
$db = db_connect();
/* 発注基本情報 */
$sql_order = "SELECT
                orders.id,
                orders.order_date,
                orders.total_price,
                suppliers.company_name,
                employees.name AS employee_name,
                employees.email AS employee_email
            FROM orders
            INNER JOIN suppliers
                ON suppliers.id = orders.supplier_id
            INNER JOIN users
                ON users.id = orders.user_id
            INNER JOIN employees
                ON employees.id = users.user_id
            WHERE orders.id = :id";
$stmt = $db->prepare($sql_order);
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    exit("データが存在しません");
}
/* 発注部品情報 */
$sql_items = "SELECT
                parts.part_name,
                parts.model_number,
                order_items.quantity,
                order_items.price,
                order_items.total_price
            FROM order_items
            INNER JOIN parts
                ON parts.id = order_items.part_id
            WHERE order_items.order_id = :id";
$stmt2 = $db->prepare($sql_items);
$stmt2->bindParam(":id", $id, PDO::PARAM_INT);
$stmt2->execute();
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . "/../config/page_head.php"; ?>
<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container mt-4">
    <!-- <?php check_array($order);
          check_array($items)
    ?> -->
        <h2>発注詳細</h2>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="20%">発注ID</th>
                        <td><?= h($order["id"]) ?></td>
                    </tr>
                    <tr>
                        <th>発注日</th>
                        <td><?= h($order["order_date"]) ?></td>
                    </tr>
                    <tr>
                        <th>発注先</th>
                        <td><?= h($order["company_name"]) ?></td>
                    </tr>
                    <tr>
                        <th>担当者</th>
                        <td><?= h($order["employee_name"]) ?></td>
                    </tr>
                    <tr>
                        <th>メール</th>
                        <td><?= h($order["employee_email"]) ?></td>
                    </tr>
                </table>
            </div>
        </div>


        <h4>発注部品</h4>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>部品名</th>
                    <th>型式</th>
                    <th>数量</th>
                    <th>単価</th>
                    <th>金額</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i): ?>
                    <tr>
                        <td><?= h($i["part_name"]) ?></td>
                        <td><?= h($i["model_number"]) ?></td>
                        <td><?= h($i["quantity"]) ?></td>
                        <td><?= number_format($i["price"]) ?></td>
                        <td><?= number_format($i["total_price"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">合計金額</th>
                    <th><?= number_format($order["total_price"]) ?></th>
                </tr>
            </tfoot>
        </table>
        <a href="list.php" class="btn btn-secondary">
            戻る
        </a>
    </main>
</body>