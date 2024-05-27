<?php

// Connessione al database
$db = new PDO('mysql:host=localhost;port=3306;dbname=progettopcto', 'root', '');

// Recupera i dati inviati tramite metodo POST
$data = json_decode(file_get_contents("php://input"), true);

// Cicla attraverso i dati e aggiorna il database
foreach ($data as $studente) {
    $email = $studente['email']; // Identificatore univoco dello studente

    // Verifica se l'email dello studente è stata fornita
    if (isset($email)) {
        $voto = $studente['voto']; // Nuovo voto dello studente

        // Query per aggiornare il voto dello studente nel database
        $query = "UPDATE STUDENTE SET Voto = :voto WHERE Email = :email";

        $stmt = $db->prepare($query);

        $stmt->bindParam(':voto', $voto);
        $stmt->bindParam(':email', $email);

        // Esegui la query
        if ($stmt->execute()) {
            // Voto aggiornato con successo per lo studente con questa email
            echo json_encode(array('success' => true, 'message' => 'Voto aggiornato con successo per lo studente con email: ' . $email));
        } else {
            // Errore durante l'aggiornamento del voto per lo studente con questa email
            echo json_encode(array('success' => false, 'message' => 'Errore durante l\'aggiornamento del voto per lo studente con email: ' . $email));
        }
    } else {
        // Errore: l'email dello studente non è stata fornita
        echo json_encode(array('success' => false, 'message' => 'Email dello studente non fornita.'));
    }
}

?>
