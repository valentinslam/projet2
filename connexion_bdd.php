<?php
$hote = 'localhost';
$bdd  = 'projet2';
$user = 'userP2';
$pass = 'mot2passFORT!';

try {
    $pdo = new PDO("mysql:host=$hote;dbname=$bdd;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}
?>