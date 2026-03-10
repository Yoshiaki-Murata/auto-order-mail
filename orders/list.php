<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
try {
    $sql = "SELECT 
                orders.id,
                orders.order_date,
                suppliers.company_name,
                users.name
            FROM orders
            INNER JOIN suppliers
            ON suppliers.id = orders.supplier_id
            INNER JOIN users
            ON users.id = orders.user_id
            ORDER BY orders.id DESC";
    $stmt = sql_get($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    発注履歴
                </h3>
                <a href="./create.php" class="btn btn-success">
                    ＋ 新規発注
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>発注ID</th>
                            <th>発注日</th>
                            <th>発注先</th>
                            <th>発注者</th>
                            <th style="width:150px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td>
                                    <?= h($row["id"]) ?>
                                </td>
                                <td>
                                    <?= h($row["order_date"]) ?>
                                </td>
                                <td>
                                    <?= h($row["company_name"]) ?>
                                </td>
                                <td>
                                    <?= h($row["name"]) ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="./detail.php" method="get">
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= $row["id"] ?>">
                                            <button class="btn btn-sm btn-primary">
                                                詳細
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <a href="../index.php" class="btn btn-secondary">
                ← トップに戻る
            </a>
        </div>
    </main>
</body>