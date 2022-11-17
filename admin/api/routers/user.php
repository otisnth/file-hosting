<?php

// Роутинг, основная функция
function route($data) {
    // POST /user/reg
    if ($data['method'] === 'POST' && count($data['urlData']) === 2 && $data['urlData'][1] == 'reg') {      
        echo json_encode(addUser($data['formData']));
        exit;
    }

    // POST /user/auth
    if ($data['method'] === 'POST' && count($data['urlData']) === 2 && $data['urlData'][1] == 'auth') {      
        echo json_encode(authUser($data['formData']));
        exit;
    }

    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');
}

// регистрация - добавление пользователя в БД
function addUser($fData) {
    $password = filter_var(trim($fData['password']), FILTER_SANITIZE_EMAIL);
    $passwordRepeat = filter_var(trim($fData['password-repeat']), FILTER_SANITIZE_EMAIL);

    $user = [
        'email' => filter_var(trim($fData['email']), FILTER_SANITIZE_EMAIL),
        'name' => filter_var(trim($fData['name']), FILTER_SANITIZE_STRING),
        'photo' => null
    ];

    if(mb_strlen($password) < 6 
        || preg_match("/[a-zA-Z0-9\-]*/", $password) !== 1 
        || $password != $passwordRepeat 
    ) {
        \Helpers\query\throwHttpError('password not suitable', 'пароль не подходит', '400 bad password');
        exit;
    }

    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(\Helpers\query\isExistsUserByEmail($pdo, $user['email'])) {
        \Helpers\query\throwHttpError('user exists: ', 'пользователь с таким email уже существует', '400 user exists');
        exit();
    }

    try {
        if(!$_FILES['photo']['error']){
            $user['photo'] = \Helpers\files\loadfile('photo')[0];
        }

        $query = 'INSERT INTO users (users_email, users_name, users_photo, users_password) 
        VALUES (:email, :name, :photo, :password)';
        
        $data = $pdo->prepare($query);
        $data->execute([
            'email' => $user['email'],
            'name' => $user['name'],
            'photo' => $user['photo'],
            'password' => md5(md5($password). "-file-hosting") 
            ]);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    } catch(Exception $e) {
        \Helpers\query\throwHttpError('file error', $e->getMessage(), '400 file error');
        exit;
    }

    $newId = (int)$pdo->lastInsertId();

    $_SESSION['user_id'] = $newId;
    setcookie('user-token', md5($newId), 0, '/');

    $_SESSION['user'] = $user;

    return [
        'id' => $newId
    ];
}

// авторизация пользователя
function authUser($fData) {
    $email = filter_var(trim($fData['email']), FILTER_SANITIZE_EMAIL);
    $password = filter_var(trim($fData['password']), FILTER_SANITIZE_EMAIL);

    $password = md5(md5($password). "-file-hosting");

    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    try {
        $query = 'SELECT * FROM users WHERE users_password = :password AND users_email = :email';
        
        $data = $pdo->prepare($query);
        $data->execute([
            'password' => $password,
            'email' => $email, 
        ]);
        $row = $data->fetch(PDO::FETCH_LAZY);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }
    
    if(!$row) {
        \Helpers\query\throwHttpError('user not found', 'Неправильный email или пароль', '404 user not found');
        exit;
    }

    $_SESSION['user_id'] = $row->users_id;

    $_SESSION['user'] = [
        'name' => $row->users_name,
        'email' => $row->users_email,
        'photo' => $row->users_photo ?: null,
    ];

    setcookie('user-token', md5($_SESSION['user_id']), time()+60*60*24*30, '/');

    return ['email' => $_SESSION['user']['email']];
}
