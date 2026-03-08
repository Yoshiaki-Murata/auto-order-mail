<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();
$array = ["id"];
$dir = "list.php";
empty_array_check($_POST, $array, $dir);
$id = (int)$_POST["id"];
// post送信から得た情報をもとにDBよりデーターを取得
try {
    $sql = "SELECT id, part_name,equipment_id,model_number,price,category_id,memo FROM parts WHERE id=:id";
    $stmt = sql_get($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($result)) {
        err_msg("指定されたデータがありません");
        he("list.php");
    }
} catch (PDOException $e) {
    exit("エラー：" . $e->getMessage());
}

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
                            <i class="bi bi-pencil-square"></i> 部品情報編集
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="./edit.do.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?php echo $result["id"]; ?>">
                            <div class="mb-4">
                                <label for="part_name" class="form-label fw-bold">
                                    部品名
                                </label>
                                <input
                                    type="text"
                                    name="part_name"
                                    id="part_name"
                                    class="form-control form-control-lg"
                                    value="<?php echo h(get_old_val("part_name", $result)); ?>"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="model_number" class="form-label fw-bold">
                                    型式
                                </label>
                                <input
                                    type="text"
                                    name="model_number"
                                    id="model_number"
                                    class="form-control form-control-lg"
                                    value="<?php echo h(get_old_val("model_number", $result)); ?>"
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
                                    value="<?php echo h(get_old_val("price", $result)); ?>"
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
                                        <option
                                            value="<?php echo $r["id"] ?>"
                                            <?php echo $r["id"] === $result["equipment_id"] ? "selected" : ""; ?>>
                                            <?php echo h($r["equipment_name"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <div class="mb-4">
                                    <label for="category_id" class="form-label fw-bold">
                                        部品カテゴリ
                                    </label>
                                    <select
                                        name="category_id"
                                        id="category_id"
                                        class="form-select form-select-lg">
                                        <?php foreach ($target2 as $r): ?>
                                            <option
                                                value="<?php echo $r["id"] ?>"
                                                <?php echo $r["id"] === $result["category_id"] ? "selected" : ""; ?>>
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
                                        placeholder="備考を入力してください"><?php echo h(get_old_val("memo", $result)); ?></textarea>
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