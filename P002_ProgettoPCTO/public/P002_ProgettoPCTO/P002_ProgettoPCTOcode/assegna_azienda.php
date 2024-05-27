<?php
$db = new PDO('mysql:host=localhost;dbname=progettopcto', 'root', '');

// Verifica se i dati sono stati inviati tramite metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodifica i dati JSON inviati
    $data = json_decode(file_get_contents("php://input"), true);
    
    
    // Estrarre i dati dall'array JSON
    $idAzienda = $data['idAzienda'];
    $idAmministratore = $data['idAmministratore'];
    $idStudente = $data['idStudente'];
    
    // Esegui la query per aggiornare la tabella ASSEGNA
    $query = "INSERT INTO ASSEGNA (IDStudente, IDAzienda, IDAmministratore) VALUES (:idStudente, :idAzienda, :idAmministratore)";
    $statement = $db->prepare($query);
    $statement->execute(array(':idStudente' => $idStudente, ':idAzienda' => $idAzienda, ':idAmministratore' => $idAmministratore));
    
    // Restituisci una risposta HTTP al client
    http_response_code(200);
} else {
    // Se la richiesta non è stata fatta tramite metodo POST, restituisci un errore HTTP
    http_response_code(405);
}
?>
