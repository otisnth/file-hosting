<?php

// Роутинг, основная функция
function route($data) {
    // GET /discipline
    if ($data['method'] === 'GET' && count($data['urlData']) === 1) {      
        echo json_encode(getDiscipline());
        exit;
    }

    // GET /discipline/random
    if ($data['method'] === 'GET' && count($data['urlData']) === 2 && $data['urlData'][1] == 'random') {      
        echo json_encode(getDiscipline($data['urlData'][1]));
        exit;
    }

    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');
}

function getDiscipline($check = '') {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    try {
        $query = 'SELECT * FROM disciplines';

        if ($check){
            $query = $query . 'ORDER BY random() LIMIT 8';
        } else {
            $query = $query . 'ORDER BY disciplines_name ASC';
        }

        $data = $pdo->prepare($query);
        $data->execute();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    $result = [];

    while($row = $data->fetch(PDO::FETCH_LAZY)) {
        $result[] = [
            'name' => $row->disciplines_name,
            'id' => $row->disciplines_id,
        ];
    }

    return $result;
}
