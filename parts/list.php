<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
// 設備マスタ情報をすべて取得
try {
    $sql="SELECT 
    parts.id,
    parts.part_name,
    parts.equipment_id,
    parts.model_number,
    parts.price,
    parts.category_id,
    parts.memo,
    equipment.equipment_name,
    categories.name AS categories_name 
FROM `parts` 
INNER JOIN equipment ON parts.equipment_id = equipment.id 
INNER JOIN categories ON parts.category_id = categories.id;";

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
                <h3 class="mb-0">部品マスタ管理</h3>
                <a href="./create.php" class="btn btn-success">
                    ＋ 新規登録
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>部品名</th>
                            <th>型番</th>
                            <th>単価</th>
                            <th>使用設備名</th>
                            <th>部品カテゴリー</th>
                            <th>備考</th>
                            <th style="width:180px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td>
                                    <?= h($row["part_name"]) ?>
                                </td>
                                <td>
                                    <?= h($row["model_number"]) ?>
                                </td>
                                <td>
                                    <?= h($row["price"]) ?>
                                </td>
                                 <td>
                                    <?= h($row["equipment_name"]) ?>
                                </td>
                                <td>
                                    <?= h($row["categories_name"]) ?>
                                </td>
                                 <td>
                                    <?= h($row["memo"]) ?>
                                </td>
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
        <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-secondary">
                ← トップに戻る
            </a>
        </div>
    </main>

</body>