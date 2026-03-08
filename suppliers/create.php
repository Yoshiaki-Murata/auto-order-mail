<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

?>

<?php include __DIR__ . "/../config/page_head.php"; ?>

<body>

    <?php include __DIR__ . "/../config/header.php"; ?>

    <main class="container mt-4">

        <?php include __DIR__ . "/../config/err_and_msg.php"; ?>

        <div class="card shadow">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">企業新規追加</h4>
            </div>

            <div class="card-body">

                <form action="./create_do.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">企業名</label>
                                <input
                                    type="text"
                                    name="company_name"
                                    class="form-control"
                                    autocomplete="organization"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">担当者名</label>
                                <input
                                    type="text"
                                    name="contact_name"
                                    class="form-control"
                                    autocomplete="name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">メールアドレス</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    autocomplete="email"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">電話番号</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    class="form-control"
                                    autocomplete="tel">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">備考</label>
                                <textarea
                                    name="memo"
                                    class="form-control"
                                    rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-primary px-4">
                            登録する
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