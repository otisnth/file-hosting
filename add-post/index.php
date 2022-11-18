<?php
require_once ($_SERVER['DOCUMENT_ROOT']. '/admin/init.php');
require_once ($_SERVER['DOCUMENT_ROOT']. '/admin/templates/' . $config['template'] . '/header.php');
/* 
добавление поста

POST-запрос по адресу /admin/api/post

add-post-file - type file поле загрузки файла
add-post-discipline - select дисциплин data-id хранит id дисциплины
add-post-title - заголовок поста
add-post-text - текст
*/
?>

<form class="form-post">

    <input type="text" name="add-post-title" require>
    <textarea name="add-post-text" require></textarea>
    <select name="add-post-discipline" require></select>
    <input type="file" name="add-post-file" require multiple>
    <button type="submit">Добавить</button>

    <div class="error-block"></div>

</form>

<script src="/admin/assets/js/post.js"></script>