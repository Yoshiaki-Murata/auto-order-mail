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
     <main class="container py-5">
        <?php include __DIR__ . "/../config/err_and_msg.php"; ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square"></i> 部品カテゴリー新規追加
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="./create_do.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    設備カテゴリー
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button class="btn btn-primary btn-lg px-4">
                                    更新する
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