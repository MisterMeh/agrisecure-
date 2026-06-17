<?php
$pdo = new PDO('sqlite:database/database.sqlite');
foreach ($pdo->query('SELECT email FROM users') as $row) {
    echo $row['email'] . PHP_EOL;
}
