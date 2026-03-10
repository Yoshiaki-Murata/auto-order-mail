<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

/* 必須チェック */
$keys = ["supplier_id", "part_id", "quantity"];
empty_array_check($_POST, $keys, "create.php");

/* 取得 */
$supplier_id = (int)$_POST["supplier_id"];
$part_ids = $_POST["part_id"];
$qtys = $_POST["quantity"];
$db = db_connect();

/* 発注先取得 */
$sql = "SELECT company_name FROM suppliers WHERE id=:id";
$stmt = $db->prepare($sql);
$stmt->bindValue(":id", $supplier_id, PDO::PARAM_INT);
$stmt->execute();
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);
if (empty($supplier)) {
    err_redirect("create.php", "発注先が存在しません");
}

/* 部品情報取得 */
$items = [];
$total_sum = 0;
foreach ($part_ids as $index => $part_id) {
    $part_id = (int)$part_id;
    $qty = (int)$qtys[$index];
    if ($part_id <= 0 || $qty <= 0) {
        continue;
    }
    $sql = "
    SELECT part_name,price
    FROM parts
    WHERE id=:id
    ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":id", $part_id, PDO::PARAM_INT);
    $stmt->execute();
    $part = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$part) {
        continue;
    }
    $row_total = $part["price"] * $qty;
    $total_sum += $row_total;
    $items[] = [
        "part_id" => $part_id,
        "part_name" => $part["part_name"],
        "price" => $part["price"],
        "qty" => $qty,
        "total" => $row_total
    ];
}
if (empty($items)) {
    err_redirect("create.php", "部品が選択されていません");
}
?>
<?php include __DIR__ . "/../config/page_head.php"; ?>
<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-warning text-dark py-3">
                        <h4 class="mb-0">
                            <i class="bi bi-check2-square"></i>
                            発注内容確認
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="mb-4">
                            発注先：
                            <span class="fw-bold">
                                <?php echo h($supplier["company_name"]); ?>
                            </span>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>部品名</th>
                                        <th class="text-end">単価</th>
                                        <th class="text-end">数量</th>
                                        <th class="text-end">金額</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td>
                                                <?php echo h($item["part_name"]); ?>
                                            </td>
                                            <td class="text-end">
                                                ¥ <?php echo number_format($item["price"]); ?>
                                            </td>
                                            <td class="text-end">
                                                <?php echo $item["qty"]; ?>
                                            </td>
                                            <td class="text-end fw-bold">
                                                ¥ <?php echo number_format($item["total"]); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end">
                                            合計金額
                                        </th>
                                        <th class="text-end text-success">
                                            ¥ <?php echo number_format($total_sum); ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <form action="create_do.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">
                            <?php foreach ($items as $item): ?>
                                <input type="hidden" name="part_id[]" value="<?php echo $item["part_id"]; ?>">
                                <input type="hidden" name="quantity[]" value="<?php echo $item["qty"]; ?>">
                            <?php endforeach; ?>
                            <div class="text-center mt-4">
                                <button class="btn btn-success btn-lg px-5">
                                    <i class="bi bi-cart-check"></i>
                                    発注確定
                                </button>
                                <a href="../index.php" class="btn btn-outline-secondary btn-lg px-5">
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