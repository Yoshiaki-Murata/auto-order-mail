<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
// 設備マスタ情報をすべて取得
try {
$result=db_all_get("categories");


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
                <h3 class="mb-0">部品カテゴリマスタ管理</h3>
                <a href="./create.php" class="btn btn-success">
                    ＋ 新規企業登録
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>分類名</th>
                            <th style="width:180px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= h($row["name"]) ?></td>
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