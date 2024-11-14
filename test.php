<?php
$dsn = 'pgsql:host=aws-0-us-east-1.pooler.supabase.com;port=6543;dbname=postgres';
$user = 'postgres.fpxtwklzucakdtcnrrbd';
$password = 'TyUdpy6d4d225JpC';

try {
    $pdo = new PDO($dsn, $user, $password);
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}