<?php
session_start();
require 'fatfree-core-master/base.php';

// Connessione al database e altre operazioni necessarie
$db = new DB\SQL('mysql:host=localhost;port=3306;dbname=progettopcto', 'root', '');

// Ottieni i dati dal modulo di compilazione del diario
// Assicurati di effettuare la validazione dei dati prima di salvarli nel database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assicurati di sanitizzare e validare i dati del modulo prima di utilizzarli
    $IDStudente = $_SESSION['student_id']; // Puoi ottenere l'ID dello studente dalla sessione o da altri metodi di autenticazione
    $giorno = $_POST['giorno'];
    $descrizione = $_POST['descrizione'];
    $ruolo = $_POST['ruolo'];
    $entrataMattino = $_POST['entrataMattino'];
    $uscitaMattino = $_POST['uscitaMattino'];
    $entrataPomeriggio = $_POST['entrataPomeriggio'];
    $uscitaPomeriggio = $_POST['uscitaPomeriggio'];

    // Esegui la query per salvare i dati nel database
    compilaDiaro($db, $IDStudente, $giorno, $descrizione, $ruolo, $entrataMattino, $uscitaMattino, $entrataPomeriggio, $uscitaPomeriggio);
}

// Funzione per inserire i dati nel diario nel database
function compilaDiaro($db, $IDStudente, $giorno, $descrizione, $ruolo, $entrataMattino, $uscitaMattino, $entrataPomeriggio, $uscitaPomeriggio) {
    // Prepara la query per l'inserimento dei dati nel diario
    $query = "INSERT INTO DIARIO (IDStudente, Giorno, Descrizione, Ruolo, EntrataMattino, UscitaMattino, EntrataPomeriggio, UscitaPomeriggio) 
              VALUES (:IDStudente, :giorno, :descrizione, :ruolo, :entrataMattino, :uscitaMattino, :entrataPomeriggio, :uscitaPomeriggio)";

    // Esegui la query
    $result = $db->exec($query, array(
        ':IDStudente' => $IDStudente,
        ':giorno' => $giorno,
        ':descrizione' => $descrizione,
        ':ruolo' => $ruolo,
        ':entrataMattino' => $entrataMattino,
        ':uscitaMattino' => $uscitaMattino,
        ':entrataPomeriggio' => $entrataPomeriggio,
        ':uscitaPomeriggio' => $uscitaPomeriggio
    ));

    if ($result) {
        // Inserimento nel diario avvenuto con successo
        echo "Inserimento nel diario avvenuto con successo.";
    } else {
        // Gestione degli errori
        echo "Si è verificato un errore durante l'inserimento nel diario.";
    }
}
?>
