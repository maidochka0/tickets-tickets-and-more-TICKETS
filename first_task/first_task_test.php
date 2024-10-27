<?php

require_once 'first_task.php';

$flag = false;
while($flag === false){ //траить до первого успешного добавления скозвь кучу сгенерированных ошибок
    try {
        $flag = true;
        $pdo = new PDO('mysql:host=localhost;dbname=TURIST', 'maid', 'password'); //бд и таблицу создать заранее
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $flag = bookOrder(3, '2021-08-21 13:00:00', 700, 1, 450, 0, $pdo);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    } finally {
        echo "\ntry and...";
    }
}
echo 'success';