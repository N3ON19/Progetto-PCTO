<?php
// Connessione al database
$db = new PDO('mysql:host=localhost;dbname=progettopcto', 'root', '');

// Funzione per visualizzare le recensioni di un'azienda
function visualizzaRecensioni($db, $aziendaId, $studenteId) {
    // Query per recuperare le recensioni per questa azienda
    $recensioniQuery = "SELECT * FROM RECENSIONE WHERE IDAzienda = :azienda_id";
    $recensioniStmt = $db->prepare($recensioniQuery);
    $recensioniStmt->execute(array(':azienda_id' => $aziendaId));
    $recensioni = $recensioniStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mostra le recensioni esistenti
    if ($recensioni) {
        echo "<h2>Recensioni esistenti:</h2>";
        foreach ($recensioni as $recensione) {
            echo "<div>";
            echo "<p>Voto: {$recensione['Voto']}</p>";
            echo "<p>Commento: {$recensione['Commento']}</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Nessuna recensione disponibile per questa azienda.</p>";
    }
}

// Controlla se l'ID dell'azienda è stato fornito nella query string
if (isset($_GET['azienda_id'])) {
    $aziendaId = $_GET['azienda_id'];
    
    // Query per recuperare le informazioni sull'azienda
    $aziendaQuery = "SELECT * FROM AZIENDA WHERE IDAzienda = :azienda_id";
    $aziendaStmt = $db->prepare($aziendaQuery);
    $aziendaStmt->execute(array(':azienda_id' => $aziendaId));
    $azienda = $aziendaStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($azienda) {
        // Verifica se lo studente ha già recensito l'azienda
        $studenteId = 1; // Per scopi dimostrativi, si considera uno studente con ID 1
        $recensioneEsistenteQuery = "SELECT COUNT(*) AS count FROM RECENSIONE WHERE IDStudente = :studente_id AND IDAzienda = :azienda_id";
        $recensioneEsistenteStmt = $db->prepare($recensioneEsistenteQuery);
        $recensioneEsistenteStmt->execute(array(':studente_id' => $studenteId, ':azienda_id' => $aziendaId));
        $recensioneEsistente = $recensioneEsistenteStmt->fetch(PDO::FETCH_ASSOC);
        
        // Mostra le informazioni sull'azienda
        echo "<h1>Recensioni per {$azienda['Nome']}</h1>";
        
        if ($recensioneEsistente && $recensioneEsistente['count'] > 0) {
            // Se lo studente ha già recensito l'azienda, visualizza solo le recensioni esistenti
            visualizzaRecensioni($db, $aziendaId, $studenteId);
        } else {
            // Se lo studente non ha ancora recensito l'azienda, mostra il form per l'invio di una nuova recensione
            // e le recensioni esistenti
            echo "<p>Hai già recensito questa azienda. Puoi inviare una recensione solo una volta.</p>";
            visualizzaRecensioni($db, $aziendaId, $studenteId);
            
            // Form per aggiungere una nuova recensione
            echo "<h2>Aggiungi una nuova recensione:</h2>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='azienda_id' value='{$aziendaId}'>";
            echo "<label for='voto'>Voto:</label>";
            echo "<select name='voto' id='voto'>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                  </select><br>";
            echo "<label for='commento'>Commento:</label><br>";
            echo "<textarea name='commento' id='commento' rows='4' cols='50'></textarea><br>";
            echo "<input type='submit' name='submit' value='Invia Recensione'>";
            echo "</form>";
            
            // Gestione dell'inserimento della nuova recensione
            if (isset($_POST['submit'])) {
                $voto = $_POST['voto'];
                $commento = $_POST['commento'];
                // Inserisci la nuova recensione nel database
                $inserisciRecensioneQuery = "INSERT INTO RECENSIONE (IDStudente, IDAzienda, Voto, Commento) VALUES (:id_studente, :id_azienda, :voto, :commento)";
                $inserisciRecensioneStmt = $db->prepare($inserisciRecensioneQuery);
                // Sostituisci :id_studente e :id_azienda con i valori appropriati
                $inserisciRecensioneStmt->execute(array(':id_studente' => $studenteId, ':id_azienda' => $aziendaId, ':voto' => $voto, ':commento' => $commento));
                echo "<p>Recensione inviata con successo!</p>";
                // Aggiorna la pagina per mostrare la nuova recensione
                header("Refresh:0");
            }
        }
    } else {
        echo "<p>Azienda non trovata.</p>";
    }
} else {
    echo "<p>ID azienda non specificato.</p>";
}
?>
