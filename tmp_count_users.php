<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$count = $pdo->query('SELECT count(*) FROM users')->fetchColumn();
echo $count . PHP_EOL;
