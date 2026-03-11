<?php
session_start();
require_once __DIR__ . "/../config/function.php";

check_login();

$array = ["id"];
$dir = "list.php";
empty_array_check($_GET, $array, $dir);
$id = (int)$_GET["id"];
try {
    // 発注情報取得
    $sql = "SELECT 
                orders.id,
                orders.order_date,
                suppliers.company_name,
                suppliers.contact_name
            FROM orders
            INNER JOIN suppliers
            ON suppliers.id = orders.supplier_id
            WHERE orders.id = :id";
    $stmt = sql_get($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($order)) {
        err_msg("指定された発注データが存在しません");
        he("list.php");
    }
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}

try {
    // 発注明細取得
    $sql = "SELECT 
                parts.part_name,
                parts.model_number,
                order_items.quantity,
                order_items.price,
                order_items.total_price
            FROM order_items
            INNER JOIN parts
            ON parts.id = order_items.part_id
            WHERE order_items.order_id = :id";
    $stmt = sql_get($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}
?>
<?php include __DIR__ . "/../config/page_head.php"; ?>
<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container mt-4">
        <?php include __DIR__ . "/../config/err_and_msg.php"; ?>
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-0">
                    発注詳細
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p>
                        <strong>発注ID：</strong>
                        <?= h($order["id"]) ?>
                    </p>
                    <p>
                        <strong>発注日：</strong>
                        <?= h($order["order_date"]) ?>
                    </p>
                    <p>
                        <strong>発注先：</strong>
                        <?= h($order["company_name"]) ?>
                    </p>
                    <p>
                        <strong>担当者：</strong>
                        <?= h($order["contact_name"]) ?>
                    </p>
                </div>
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
                        <?php foreach ($items as $row): ?>
                            <tr>
                                <td>
                                    <?= h($row["part_name"]) ?>
                                </td>
                                <td>
                                    <?= h($row["model_number"]) ?>
                                </td>
                                <td>
                                    <?= h($row["quantity"]) ?>
                                </td>
                                <td>
                                    <?= number_format($row["price"]) ?>
                                </td>
                                <td>
                                    <?= number_format($row["total_price"]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <a href="./list.php" class="btn btn-secondary">
                ← 発注一覧に戻る
            </a>
        </div>
    </main>
</body>