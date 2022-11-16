<?php

namespace Helpers\query;
use PDO;


// Получение данных из тела запроса
function getFormData($method) {
    // GET или POST: данные возвращаем как есть
    if ($method === 'GET') {
        $data = $_GET;
    } else if ($method === 'POST') {
        $data = $_POST;

    } else {
        // PUT, PATCH или DELETE
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));

        foreach($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }
    }

    // Удаляем параметр q
    unset($data['q']);

    return $data;
}


// Получаем все данные о запросе
function getRequestData() {
    $method = $_SERVER['REQUEST_METHOD'];

    // Разбираем url
    $url = (isset($_GET['q'])) ? $_GET['q'] : '';
    $url = trim($url, '/');
    $urls = explode('/', $url);

    return array(
        'method' => $method,
        'formData' => getFormData($method),
        'urlData' => $urls,
        'router' => $urls[0]
    );

}


// Подключение к БД
function connectDB() {
    $host = '127.0.0.1';
    $port = '5432';
    $user = 'postgres';
    $password = '123';
    $dbname = 'file-hosting';


    $db = new PDO("pgsql:host={$host};port={$port};user={$user};password={$password};dbname={$dbname}");

    $db->setAttribute(
        PDO::ATTR_ERRMODE, 
        PDO::ERRMODE_EXCEPTION
    );

    return $db;
}


// Проверка роутера на валидность
function isValidRouter($router) {
    return in_array($router, array(
        'post',
        'user',
        'discipline',
    ));
}


// Проверка, существует ли пользователь с таким email
function isExistsUserByEmail($pdo, $email) {
    $query = 'SELECT users_id FROM users WHERE users_email=:email';
    $data = $pdo->prepare($query);
    $data->bindParam(':email', $email);
    $data->execute();

    return count($data->fetchAll()) === 1;
}


// Выводим ошибку http-запроса
function throwHttpError($code, $message, $header = '400 Bad Request') {
    header($_SERVER["SERVER_PROTOCOL"] . " " . $header);
    // TODO - старт сессии ограничивает отправку ответа, запрос не завершается
    // Удрать setcookie
    setcookie('query_error', json_encode([
        'code' => $code,
        'message' => $message
    ]), 0, "/");
}