
<?php
    require_once ($_SERVER['DOCUMENT_ROOT']. '/admin/init.php');
    require_once ($_SERVER['DOCUMENT_ROOT']. '/admin/templates/' . $config['template'] . '/header.php');
?>
<body>
    <form action="#" method="post" class="form-auth">
        <input type="email" name="email" require>
        <input type="password" name="password" require pattern="[a-zA-Z0-9\-]+">
        <button type="submit">Зарегистрироваться</button>
        <div class="error-block"></div>
    </form>
    <script src="/admin/assets/js/auth.js"></script>
</body>
</html>