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
    $sql = "SELECT id, name FROM categories WHERE id=:id";
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
// 企業情報を取得
try {
    $target = db_all_get("suppliers");
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
                            <i class="bi bi-pencil-square"></i> 部品カテゴリ編集
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="./edit.do.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?php echo $result["id"]; ?>">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    カテゴリ名
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control form-control-lg"
                                    value="<?php echo h(get_old_val("name", $result)); ?>"
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