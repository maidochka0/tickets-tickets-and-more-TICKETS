<?php

function generateBarcode($length = 8) {
    return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT); //str_pad для числел с ведущими нулями
}

function bookOrder($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $pdo) {
    //добавил $pdo
    //предпологается, что добавление инкрементирующегося id и даты создания были настроенны при создании таблицы в first_task_db_create.sql
    
    $barcode = generateBarcode();
    
    $response = mockApiBook($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $barcode);
    
    //далее, будет выдавать варнинги не найденных ключей
    if ($response['error'] === 'barcode already exists') {
        return bookOrder($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $pdo);
    }
    
    $approveResponse = mockApiApprove($barcode);
    
    if ($approveResponse['message'] === 'order successfully aproved') {
        $equal_price = ($ticket_adult_price * $ticket_adult_quantity) + ($ticket_kid_price * $ticket_kid_quantity);
        $stmt = $pdo->prepare("INSERT INTO orders (event_id, event_date, ticket_adult_price, ticket_adult_quantity, ticket_kid_price, ticket_kid_quantity, barcode, equal_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $barcode, $equal_price]);
        return true;
    } else {
        //обработка $approveResponse['error']
        return false;
    }
}

function mockApiBook($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $barcode) {
    if (rand(0, 1)) {
        return ['message' => 'order successfully booked'];
    } else {
        return ['error' => 'barcode already exists'];
    }
}

function mockApiApprove($barcode) {
    $responses = [
        ['message' => 'order successfully aproved'],
        ['error' => 'event cancelled'],
        ['error' => 'no tickets'],
        ['error' => 'no seats'],
        ['error' => 'fan removed']
    ];
    return $responses[array_rand($responses)];
}
