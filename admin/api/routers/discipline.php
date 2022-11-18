<?php

// Роутинг, основная функция
function route($data) {
    // GET /discipline
    if ($data['method'] === 'GET' && count($data['urlData']) === 1) {      
        echo json_encode(getDiscipline());
        exit;
    }

    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');
}

function getDiscipline() {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    try {
        $query = 'SELECT * FROM disciplines ORDER BY disciplines_name ASC';

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
