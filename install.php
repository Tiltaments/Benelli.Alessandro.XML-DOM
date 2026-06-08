<?php
require_once('dati_generali.php');
$conn = new mysqli($db_host, $db_user, $db_pass);

echo "<h1>Installazione Sistema Palestra 648</h1>";

$conn->query("CREATE DATABASE IF NOT EXISTS `$db_name`");
$conn->select_db($db_name);

$conn->query("CREATE TABLE IF NOT EXISTS `$tab_clienti` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50), cognome VARCHAR(50), 
    email VARCHAR(100) UNIQUE, password VARCHAR(255), cf VARCHAR(16) UNIQUE
)");

$conn->query("CREATE TABLE IF NOT EXISTS `$tab_istruttori` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50), cognome VARCHAR(50), specializzazione VARCHAR(100)
)");

$check = $conn->query("SELECT * FROM `$tab_istruttori`");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO `$tab_istruttori` (nome, cognome, specializzazione) VALUES 
    ('Marco', 'Rossi', 'Bodybuilding'), 
    ('Giulia', 'Bianchi', 'Pilates'), 
    ('Andrea', 'Verdi', 'CrossFit')");
}

$check_clienti = $conn->query("SELECT * FROM `$tab_clienti`");
if ($check_clienti->num_rows == 0) {
    $conn->query("INSERT INTO `$tab_clienti` (nome, cognome, email, password, cf) VALUES 
    ('Alessandro', 'Benelli', 'benelli.1983399@studenti.uniroma1.it', '44556677', 'BNLLSN02T04D810Z')");
}

echo "<p>✅ Database e Tabelle creati con successo!</p>";
echo "<a href='home.php'>Vai al sito web</a>";
