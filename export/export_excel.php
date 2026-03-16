<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

?>

<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>
    <?php include __DIR__ . "/../config/header.php"; ?>

    <main class="container py-5">
        <h2>データ出力</h2>
        <form action="export_excel_do.php" method="get">
            <div class="mb-4">
                <label for="start_date">開始日</label>
                <input type="date" name="start_date" class="form-control form-control-lg">
            </div>
            <div class="mb-4">
                <label for="end_date">終了日</label>
                <input type="date" name="end_date" class="form-control form-control-lg">
            </div>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button class="btn btn-primary btn-lg px-4">
                    出力する
                </button>
                <a href="../index.php" class="btn btn-secondary btn-lg px-4">
                トップに戻る
            </a>
            </div>
        </form>
    </main>
</body>