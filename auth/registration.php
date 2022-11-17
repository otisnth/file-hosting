<?php
require_once ($_SERVER['DOCUMENT_ROOT']. '/admin/init.php');
require_once ($_SERVER['DOCUMENT_ROOT']. '/admin/templates/' . $config['template'] . '/header.php');
/*
****** обработчик готов
****** url api = /admin/api/user/reg

$_SESSION['user'] - данные о пользователе
 
поля формы 
password - пароль
password-repeat - повтор пароля
email - email
name - имя
photo - фото
*/
?>
<form action="/admin/api/user/reg" method="post" enctype="multipart/form-data" class="form-reg">
    <input type="email" name="email">
    <input type="password" name="password" pattern="[a-zA-Z0-9\-]+">
    <input type="password" name="password-repeat" pattern="[a-zA-Z0-9\-]+">
    <input type="text" name="name" pattern="[а-яА-Я]+">
    <input type="file" name="photo" accept="image/*">
    <button type="submit">Зарегистрироваться</button>
    <div class="error-block"></div>
</form>
<script src="/admin/assets/js/reg.js"></script>