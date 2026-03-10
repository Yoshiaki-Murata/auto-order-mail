<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
// 設備マスタ情報をすべて取得
try {
    $sql="SELECT equipment.id,equipment.equipment_name,equipment.supplier_id,equipment.memo,suppliers.company_name FROM equipment INNER JOIN suppliers ON suppliers.id=equipment.supplier_id;";

    $stmt=sql_get($sql);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    exit("エラー" . $e->getMessage());
}

?>
<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container mt-4">
        <?php include __DIR__ . "/../config/err_and_msg.php"; ?>
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">設備マスタ管理</h3>
                <a href="./create.php" class="btn btn-success">
                    ＋ 新規登録
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>設備名</th>
                            <th>施工会社</th>
                            <th>備考</th>
                            <th style="width:180px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= h($row["equipment_name"]) ?></td>
                                <td>
                                    <?= h($row["company_name"]) ?>
                                </td>
                                <td><?= h($row["memo"]) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="./edit.php" method="post">
                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <button class="btn btn-sm btn-primary">編集</button>
                                        </form>
                                        <form action="./delete.php" method="post"
                                            onsubmit="return confirm('削除しますか？');">
                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <button class="btn btn-sm btn-danger">削除</button>
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