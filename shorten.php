<?php
require 'database.php';

header('Content-Type: application/json');

$db = new Database('urls.db');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $original_url = $input['url'];

    if (filter_var($original_url, FILTER_VALIDATE_URL)) {
        $existing_short_code = $db->getShortCodeByUrl($original_url);
        if ($existing_short_code) {
            echo json_encode([
                'short_url' => 'http://localhost/' . $existing_short_code['short_code']
            ]);
        } else {
            $short_code = generateShortCode();
            $db->insertUrl($original_url, $short_code);

            echo json_encode([
                'short_url' => 'http://localhost/' . $short_code
            ]);
        }
    } else {
        echo json_encode([
            'error' => 'Неверный URL'
        ]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['code'])) {
    $short_code = $_GET['code'];
    $url = $db->getUrlByShortCode($short_code);

    if ($url) {
        header('Location: ' . $url['original_url']);
        exit();
    } else {
        echo json_encode([
            'error' => 'URL не найден'
        ]);
    }
}

function generateShortCode($length = 5)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $short_code = '';
    for ($i = 0; $i < $length; $i++) {
        $short_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $short_code;
}
