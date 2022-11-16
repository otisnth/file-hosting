<?php

// Роутинг, основная функция
function route($data) {
    // POST /post
    if ($data['method'] === 'POST' && count($data['urlData']) === 1) {      
        echo json_encode(addPost($data['formData']));
        exit;
    }

    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');
}

function addPost($fData) {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    // TODO проверка дисциплины на существование    

    // добавление поста
    try {
        $query = 'INSERT INTO posts (disciplines_id, users_id, posts_title, posts_text) 
                    VALUES (:discipline, :user_id, :title, :text)';
                    
        $data = $pdo->prepare($query);
        $data->execute([
            'discipline' => (int) $fdata['add-post-discipline'],
            'title' => filter_var(trim($fdata['add-post-title']), FILTER_SANITIZE_STRING),
            'text' => filter_var(trim($fdata['add-post-text']), FILTER_SANITIZE_STRING) ,
            'user_id' => $_SESSION['user_id']
        ]);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error (post addition)', $e->getMessage());
        exit;
    }

    $postID = (int)$pdo->lastInsertId();

    // добавление файла в таблицу файлов
    try {
        $files = \Helpers\files\loadfile('add-post-file');

        $query = 'INSERT INTO files (files_path, posts_id) 
                    VALUES (:path, :posts_id)';

        foreach($files as $file) {
            $data = $pdo->prepare($query);
            $data->execute([
                'posts_id' => $postID,
                'path' => $file
            ]);
        }

    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error (file addition)', $e->getMessage());
        exit;
    } catch (Exception $e) {
        \Helpers\query\throwHttpError('file load error', $e->getMessage());
        exit;
    }

    return ['id' => $postID];
}
