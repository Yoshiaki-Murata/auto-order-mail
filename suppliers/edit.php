<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

$array = ["id"];
$dir = "list.php";
empty_array_check($_POST, $array, $dir);

$id = (int)$_POST["id"];

try {

    $sql = "SELECT id, company_name, contact_name, email, phone, memo
        FROM suppliers
        WHERE id=:id";

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
?>

<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>

    <?php include __DIR__ . "/../config/header.php"; ?>

    <main class="container mt-4">

        <?php include __DIR__ . "/../config/err_and_msg.php"; ?>

        <div class="card shadow">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">企業情報編集</h4>
            </div>

            <div class="card-body">

                <form action="./edit.do.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="id" value="<?php echo $result["id"]; ?>">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">企業名</label>
                                <input
                                    type="text"
                                    name="company_name"
                                    class="form-control"
                                    autocomplete="organization"
                                    value="<?php echo h(get_old_val("company_name",$result)); ?>"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">担当者名</label>
                                <input
                                    type="text"
                                    name="contact_name"
                                    class="form-control"
                                    autocomplete="name"
                                    value="<?php echo h(get_old_val("contact_name",$result)); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">メールアドレス</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    autocomplete="email"
                                    value="<?php echo h(get_old_val("email",$result)); ?>"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">電話番号</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    class="form-control"
                                    autocomplete="tel"
                                    value="<?php echo h(get_old_val("phone",$result)); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">備考</label>
                                <textarea
                                    name="memo"
                                    class="form-control"
                                    rows="4"><?php echo h(get_old_val("memo",$result)); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-primary px-4">
                            更新する
                        </button>
                        <a href="./list.php" class="btn btn-secondary px-4 ms-2">
                            戻る
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>