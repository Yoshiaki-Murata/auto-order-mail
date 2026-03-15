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
    $sql = "SELECT
        users.id AS user_primary_id,
        users.name,
        users.password,
        users.user_id,
        users.role_id,
	    employees.id AS employees_id,
        employees.employee_number,
        employees.name AS employee_name,
        employees.email,
        roles.id AS roles_primary_id,
        roles.role
		FROM users 
        INNER JOIN employees ON employees.id =users.user_id
        INNER JOIN roles ON roles.id=users.role_id
        WHERE users.id=:id";
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

// 権限情報を取得
try {
    $target1 = db_all_get("roles");
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
                            <i class="bi bi-pencil-square"></i> 社員情報編集
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="./edit.do.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?php echo $result["user_primary_id"]; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $result["user_id"]; ?>">
                            <div class="mb-4">
                                <label for="employee_number" class="form-label fw-bold">
                                    社員番号
                                </label>
                                <input
                                    type="text"
                                    name="employee_number"
                                    id="employee_number"
                                    class="form-control form-control-lg"
                                    value="<?php echo h(get_old_val("employee_number", $result)); ?>"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="employee_name" class="form-label fw-bold">
                                    氏名
                                </label>
                                <input
                                    type="text"
                                    name="employee_name"
                                    id="employee_name"
                                    class="form-control form-control-lg"
                                    value="<?php echo h(get_old_val("employee_name", $result)); ?>"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    ユーザー名
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control form-control-lg"
                                    value="<?php echo h(get_old_val("name", $result)); ?>"
                                    required>
                            </div>
                            <div class="mb-4">
                                <!-- パスワードは非表示。変更の時だけ入力 -->
                                <label for="password" class="form-label fw-bold">
                                    パスワード
                                </label>
                                <input
                                    type="text"
                                    name="password"
                                    id="password"
                                    class="form-control form-control-lg"
                                    value="">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    メールアドレス
                                </label>
                                <input
                                    type="text"
                                    name="email"
                                    id="email"
                                    class="form-control form-control-lg"
                                    value="<?php echo h(get_old_val("email", $result)); ?>"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="role_id" class="form-label fw-bold">
                                    権限
                                </label>
                                <select
                                    name="role_id"
                                    id="role_id"
                                    class="form-select form-select-lg">
                                    <?php foreach ($target1 as $r): ?>
                                        <option
                                            value="<?php echo $r["id"] ?>"
                                            <?php echo $r["id"] === $result["role_id"] ? "selected" : ""; ?>>
                                            <?php echo h($r["role"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
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