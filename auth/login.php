<?php
session_start();
require_once __DIR__ . "/../config/function.php";
?>

<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>
    <main role="main" class="container" style="padding:60px 15px 0">
        <h1 class="my-5 text-center">ログイン</h1>
        <form action="./login_check.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php generate_csrf_token(); ?>">
            <div class="row justify-content-center">
                <div class="mb-3 col-6">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input type="text" name="name" id="name" class="form-control" autocomplete="username" required>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="mb-3 col-6">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" name="password" id="password" class="form-control" autocomplete="current-password" required>
                </div>
            </div>
            <div class="mb-3 text-center">
                <input type="submit" value="ログイン" class="btn btn-primary">
            </div>
        </form>
        <?php include __DIR__."/../config/err_and_msg.php" ?>
    </main>
</body>