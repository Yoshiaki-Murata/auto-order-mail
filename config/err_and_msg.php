<?php if (!empty($_SESSION["msg"])): ?>
    <p><?php echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
        ?></p>
<?php endif; ?>
<?php if (!empty($_SESSION["err"])): ?>
    <p><?php echo $_SESSION["err"];
        unset($_SESSION["err"]);
        ?></p>
<?php endif; ?>