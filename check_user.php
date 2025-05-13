<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "as_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Errore connessione DB."]));
}

$response = ["status" => "error", "message" => "Richiesta non valida."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field = $_POST['field']; 
    $value = $_POST['value']; 

    if (in_array($field, ['username', 'email'])) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE $field = ?");
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response = ["status" => "taken", "message" => ucfirst($field) . " già in uso!"];
        } else {
            $response = ["status" => "available", "message" => ucfirst($field) . " disponibile."];
        }
        $stmt->close();
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>

