<?php
session_start();
require_once __DIR__ . "/config/function.php";
check_login();
?>
<?php include __DIR__ . "/config/page_head.php"; ?>
<body>
    <?php include __DIR__ . "/config/header.php"; ?>
    <main role="main" class="container mt-4">
        <h1 class="mb-4">トップページ</h1>
        <div class="list-group">
            <a href="./dashboard/dashboard.php"
                class="list-group-item list-group-item-action">
                ダッシュボード
            </a>
            <a href="./orders/list.php"
                class="list-group-item list-group-item-action">
                発注業務
            </a>
               <a href="./users/list.php"
                class="list-group-item list-group-item-action">
                ユーザーマスタ管理
            </a>
            <a href="./suppliers/list.php"
                class="list-group-item list-group-item-action">
                企業マスタ管理
            </a>
            <a href="./equipment/list.php"
                class="list-group-item list-group-item-action">
                設備マスタ管理
            </a>
            <a href="./categories/list.php"
                class="list-group-item list-group-item-action">
                部品カテゴリ管理
            </a>
            <a href="./parts/list.php"
                class="list-group-item list-group-item-action">
                部品マスタ管理
            </a>
            <a href="./export/export_excel.php"
                class="list-group-item list-group-item-action">
                データ出力（準備中）
            </a>
        </div>
        <div class="mt-4">
            <a href="./auth/logout.php" class="btn btn-danger">
                ログアウト
            </a>
        </div>
    </main>
</body>