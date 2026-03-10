<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

try {
    $suppliers = db_all_get("suppliers");
    $parts = db_all_get("parts");
} catch (PDOException $e) {
    exit("エラー:" . $e->getMessage());
}
?>

<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>
    <?php include __DIR__ . "/../config/header.php"; ?>
    <main class="container py-5">
        <?php include __DIR__ . "/../config/err_and_msg.php"; ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h4 class="mb-0">
                            <i class="bi bi-cart-plus"></i>
                            部品発注作成
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="confirm.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    発注先
                                </label>
                                <select name="supplier_id" class="form-select form-select-lg" required>
                                    <option value="">選択してください</option>
                                    <?php foreach ($suppliers as $s): ?>
                                        <option value="<?php echo $s["id"]; ?>">
                                            <?php echo h($s["company_name"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="orderTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:35%">部品</th>
                                            <th style="width:15%">単価</th>
                                            <th style="width:15%">数量</th>
                                            <th style="width:20%">金額</th>
                                            <th style="width:15%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderBody">
                                        <tr>
                                            <td>
                                                <select name="part_id[]" class="form-select partSelect">
                                                    <option value="">選択</option>
                                                    <?php foreach ($parts as $p): ?>
                                                        <option
                                                            value="<?php echo $p["id"]; ?>"
                                                            data-price="<?php echo $p["price"]; ?>">
                                                            <?php echo h($p["part_name"]); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="price text-end">
                                                0
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    name="quantity[]"
                                                    class="form-control qty"
                                                    value="1"
                                                    min="1">
                                            </td>
                                            <td class="rowTotal text-end fw-bold">
                                                0
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger removeRow">
                                                    削除
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button
                                    type="button"
                                    id="addRow"
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-plus-circle"></i>
                                    部品追加
                                </button>
                                <h4>
                                    合計金額
                                    <span class="text-success">
                                        ¥ <span id="grandTotal">0</span>
                                    </span>
                                </h4>
                            </div>
                            <div class="text-center mt-5">
                                <button class="btn btn-success btn-lg px-5">
                                    <i class="bi bi-check-circle"></i>
                                    確認画面へ
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
    <script src="../assets/js/order.js"></script>
</body>