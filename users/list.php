<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
// 設備マスタ情報をすべて取得
try {
    $sql="SELECT 
        users.id AS user_primary_id,
        users.name,
        users.password,
        users.user_id,
        users.role_id,
		employees.id AS employees_id,
        employees.employee_number,
        employees.name AS employee_name,
        employees.email,
        roles.id,
        roles.role
		FROM users 
        INNER JOIN employees ON employees.id =users.user_id
        INNER JOIN roles ON roles.id=users.role_id;";

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
                            <th>社員番号</th>
                            <th>氏名</th>
                            <th>メールアドレス</th>
                            <th>権限</th>
                            <th style="width:180px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td>
                                    <?= h($row["employee_number"]) ?>
                                </td>
                                 <td>
                                    <?= h($row["employee_name"]) ?>
                                </td>
                                <td>
                                    <?= h($row["email"]) ?>
                                </td>
                                 <td>
                                    <?= h($row["role"]) ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="./delete.php" method="post">
                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <button class="btn btn-sm btn-success">詳細</button>
                                        </form>
                                        <form action="./edit.php" method="post">
                                            <input type="hidden" name="id" value="<?= $row["user_primary_id"] ?>">
                                            <button class="btn btn-sm btn-primary">編集</button>
                                        </form>
                                        <form action="./delete.php" method="post"
                                            onsubmit="return confirm('削除しますか？');">
                                            <input type="hidden" name="id" value="<?= $row["user_primary_id"] ?>">
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