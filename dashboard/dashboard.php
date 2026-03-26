<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

$db = db_connect();
/* 今日の発注 */
$sql = "SELECT COUNT(*) 
        FROM orders
        WHERE DATE(order_date) = CURDATE()";
$today_orders = $db->query($sql)->fetchColumn();


/* 今月の発注数 */
$sql = "SELECT COUNT(*)
        FROM orders
        WHERE DATE_FORMAT(order_date,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')";
$month_orders = $db->query($sql)->fetchColumn();

/* 今月の発注金額 */
$sql = "SELECT SUM(total_price)
        FROM orders
        WHERE DATE_FORMAT(order_date,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')";
$month_total = $db->query($sql)->fetchColumn();
/* 最近の発注 */
$sql = "SELECT 
        orders.id,
        orders.order_date,
        suppliers.company_name,
        orders.total_price
        FROM orders
        INNER JOIN suppliers
        ON suppliers.id = orders.supplier_id
        ORDER BY orders.id DESC
        LIMIT 5";
$recent_orders = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container mt-4">
        <h2>ダッシュボード</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5>本日の発注</h5>
                        <h2><?= $today_orders ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5>今月の発注</h5>
                        <h2><?= $month_orders ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5>今月の金額</h5>
                        <h2>¥<?= number_format($month_total) ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <h4 class="mt-4">最近の発注</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>発注日</th>
                    <th>発注先</th>
                    <th>金額</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_orders as $o): ?>
                    <tr>
                        <td><?= $o["id"] ?></td>
                        <td><?= $o["order_date"] ?></td>
                        <td><?= h($o["company_name"]) ?></td>
                        <td>¥<?= number_format($o["total_price"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="./../index.php" class="btn btn-secondary px-4 ms-2">
                戻る
            </a>
        </div>
    </main>
</body>