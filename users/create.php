<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

// 設備情報を取得
try {
    $target1 = db_all_get("equipment");
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}
// 部品分類情報を取得
try {
    $target2 = db_all_get("categories");
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
                            <i class="bi bi-pencil-square"></i> 部品情報新規追加
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="./create_do.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <div class="mb-4">
                                <label for="part_name" class="form-label fw-bold">
                                    部品名
                                </label>
                                <input
                                    type="text"
                                    name="part_name"
                                    id="part_name"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="model_number" class="form-label fw-bold">
                                    型番
                                </label>
                                <input
                                    type="text"
                                    name="model_number"
                                    id="model_number"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="price" class="form-label fw-bold">
                                    単価
                                </label>
                                <input
                                    type="text"
                                    name="price"
                                    id="price"
                                    class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="equipment_id" class="form-label fw-bold">
                                    使用設備名
                                </label>
                                <select
                                    name="equipment_id"
                                    id="equipment_id"
                                    class="form-select form-select-lg">
                                    <?php foreach ($target1 as $r): ?>
                                        <option value="<?php echo $r["id"] ?>">
                                            <?php echo h($r["equipment_name"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-bold">
                                    部品カテゴリー
                                </label>
                                <select
                                    name="category_id"
                                    id="category_id"
                                    class="form-select form-select-lg">
                                    <?php foreach ($target2 as $r): ?>
                                        <option value="<?php echo $r["id"] ?>">
                                            <?php echo h($r["name"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="memo" class="form-label fw-bold">
                                    備考
                                </label>
                                <textarea
                                    name="memo"
                                    id="memo"
                                    class="form-control"
                                    rows="4"
                                    placeholder="備考を入力してください">
                                </textarea>
                            </div>
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button class="btn btn-primary btn-lg px-4">
                                    登録する
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