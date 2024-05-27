<?php

require 'fatfree-core-master/base.php';
session_start();
$f3 = Base::instance();

$db = new DB\SQL('mysql:host=localhost;port=3306;dbname=progettopcto', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'studente') {
        $studenteId = $_SESSION['studente_id'];
        $aziendaId = $_POST['azienda_id'];
        $aggiungi = $_POST['aggiungi'] === '1'; // Converte il valore da stringa a booleano
        
        if ($aggiungi) {
            // Controlla se lo studente ha già raggiunto il limite massimo di preferiti
            $countQuery = "SELECT COUNT(*) as count FROM PREFERENZA WHERE IDStudente = :studente_id";
            $countResult = $db->exec($countQuery, array(':studente_id' => $studenteId));
            if ($countResult && $countResult[0]['count'] >= 3) {
                http_response_code(400); // Bad Request
                echo "Hai già raggiunto il limite massimo di aziende preferite (3).";
                exit;
            }
        }
        

    $insertQuery = $aggiungi ? "INSERT INTO PREFERENZA (IDStudente, IDAzienda) VALUES (:studente_id, :azienda_id)" : "DELETE FROM PREFERENZA WHERE IDStudente = :studente_id AND IDAzienda = :azienda_id";
    $result = $db->exec($insertQuery, array(':studente_id' => $studenteId, ':azienda_id' => $aziendaId));

if ($result) {
    http_response_code(200);
    echo $aggiungi ? "Azienda aggiunta ai preferiti con successo!" : "Azienda rimossa dai preferiti con successo!";
} else {
    http_response_code(500);
    echo "Si è verificato un errore durante l'aggiornamento dei preferiti.";
}

        if ($result) {
            http_response_code(200);
            echo $aggiungi ? "Azienda aggiunta ai preferiti con successo!" : "Azienda rimossa dai preferiti con successo!";
        } else {
            http_response_code(500);
            echo "Si è verificato un errore durante l'aggiornamento dei preferiti.";
        }
    } else {
        http_response_code(403);
        echo "Non sei autorizzato a eseguire questa operazione.";
    }
} else {
    http_response_code(405);
    echo "Metodo non consentito.";
}

?>

