
<?php
    require_once ($_SERVER['DOCUMENT_ROOT']. '/admin/templates/main/header.php');
?>
<body>
    <form action="#" method="post">
        <input type="email" name="email" require>
        <input type="password" name="password" require pattern="[a-zA-Z0-9\-]+">
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>