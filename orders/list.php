<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
$db = db_connect();
$sql = "SELECT 
        orders.id,
        orders.order_date,
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
        ORDER BY orders.order_date DESC";
$stmt = $db->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>発注履歴</h2>
            <a href="./create.php" class="btn btn-success">
                ＋ 新規登録
            </a>
        </div>
        <table id="orderTable" class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>発注日</th>
                    <th>発注先</th>
                    <th>発注者</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?= h($o["id"]) ?></td>
                        <td><?= h($o["order_date"]) ?></td>
                        <td><?= h($o["company_name"]) ?></td>
                        <td><?= h($o["employee_name"]) ?></td>
                        <td>
                            <a href="detail.php?id=<?= $o["id"] ?>" class="btn btn-sm btn-primary">
                                詳細
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <a href="../index.php" class="btn btn-secondary">
                ← トップに戻る
            </a>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/ja.json"
                }
            });
        });
    </script>
</body>