<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

// 役割情報を取得
try {
    $target1 = db_all_get("roles");
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}
?>
<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container py-5">
        <?php include __DIR__ . "/../config/err_and_msg.php"; ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square"></i> 社員情報編集
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="./create_do.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <div class="mb-4">
                                <label for="employee_number" class="form-label fw-bold">
                                    社員番号
                                </label>
                                <input
                                    type="text"
                                    name="employee_number"
                                    id="employee_number"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="employee_name" class="form-label fw-bold">
                                    氏名
                                </label>
                                <input
                                    type="text"
                                    name="employee_name"
                                    id="employee_name"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    ユーザー名
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">
                                    パスワード
                                </label>
                                <input
                                    type="text"
                                    name="password"
                                    id="password"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    メールアドレス
                                </label>
                                <input
                                    type="text"
                                    name="email"
                                    id="email"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="role_id" class="form-label fw-bold">
                                    権限
                                </label>
                                <select
                                    name="role_id"
                                    id="role_id"
                                    class="form-select form-select-lg">
                                    <?php foreach ($target1 as $r): ?>
                                        <option value="<?php echo $r["id"] ?>">
                                            <?php echo h($r["role"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button class="btn btn-primary btn-lg px-4">
                                    新規追加
                                </button>
                                <a href="./list.php" class="btn btn-outline-secondary btn-lg px-4">
                                    戻る
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>