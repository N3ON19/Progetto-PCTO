<?php
// URL del server a cui fare la richiesta GET
$url = 'http://localhost/P002_ProgettoPCTO/P002_ProgettoPCTOcode/ottieni-elenco-studenti';

// Creazione del contesto per la richiesta
$options = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'Content-Type: application/json', // Imposta l'intestazione Content-Type se necessario
    )
);
$context = stream_context_create($options);

// Esegui la richiesta GET al server
$response = file_get_contents($url, true, $context);

// Stampa la risposta del server
echo $response;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Studenti</title>
                      <style>
                          table {
                              border-collapse: collapse;
                              width: 70%;
                          }
                          th, td {
                              padding: 8px;
                              text-align: left;
                              border-bottom: 1px solid #ddd;
                          }
                          th {
                              background-color: #f2f2f2;
                          }
                          td[contenteditable=\"true\"] {
                              background-color: #f9f9f9;
                          }
    </style>
</head>
<body>
<body>

<h2>Elenco Studenti</h2>

<button onclick="window.location.href = 'home.php';">Torna in Home</button>

<input type="text" id="searchInput" onkeyup="searchStudents()" placeholder="Cerca studente per nome o cognome">

<table id="studentsTable">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Classe</th>
        <th>Anno Accademico</th>
        <th>Email</th>
        <th>Indirizzo</th>
        <th>Voto</th>
        <th>CAP</th>
        <th>Visualizza Diario</th>
        <th>Assegna Azienda</th>
    </tr>";
<!-- 
foreach ($studentiResult as $studente) {
echo "<tr>
<td>{$studente['IDStudent']}</td>
<td>{$studente['Nome']}</td>
<td>{$studente['Cognome']}</td>
<td>{$studente['Classe']}</td>
<td>{$studente['AnnoAccademico']}</td>
<td>{$studente['Email']}</td>
<td>{$studente['Indirizzo']}</td>
<td contenteditable="true">{$studente['Voto']}</td>
<td>{$studente['CAP']}</td>
<td><button onclick="window.location.href = 'visualizza-diario?id={$studente['IDStudent']}';">{$studente['Nome']} - Visualizza Diario</button></td>
<td>

  <select onchange="assegnaAzienda(this.value, '{$amministratore_id}', '{$studente['IDStudent']}')">
      <option value="">Seleziona Azienda</option>";


$aziendeQuery = "SELECT * FROM AZIENDA";
$aziendeResult = $db->query($aziendeQuery);

foreach ($aziendeResult as $azienda) {
  echo "<option value="{$azienda['IDAzienda']}">{$azienda['Nome']}</option>";
}

echo "</select>
  </td>
  </tr>";
}
echo "</table> -->

<button onclick="salvaModifiche()">Salva modifiche</button>

<script>

document.addEventListener('DOMContentLoaded', function() {

  // Imposta le opzioni della richiesta
  var requestOptions = {
    method: 'GET',
  };

  // Effettua la richiesta al server REST
  fetch('ottieni-elenco-studenti', requestOptions)
    .then(response => {
      if (!response.ok) {
        throw new Error('Si è verificato un errore durante la richiesta.');
      }
      return response.json();
    })
    .then(data => {
      console.log('Risposta dal server:', data);
    })
    .catch(error => {
      console.error('Errore durante la richiesta:', error.message);
    });
});

    // Funzione per filtrare gli studenti nella tabella in base alla ricerca
    function searchStudents() {
        var input = document.getElementById("searchInput");
        var filter = input.value.toUpperCase();
        var rows = document.getElementById("studentsTable").getElementsByTagName("tr");
        
        // Ciclo attraverso tutte le righe della tabella
        for (var i = 1; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            var found = false;
            // Ciclo attraverso tutte le celle della riga corrente
            for (var j = 0; j < cells.length; j++) {
                var cell = cells[j];
                if (cell) {
                    var textValue = cell.textContent || cell.innerText;
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            // Visualizza o nasconde la riga a seconda se è stata trovata la corrispondenza
            if (found) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    // Funzione per salvare le modifiche
    function salvaModifiche() {
        var table = document.getElementById("studentsTable");
        var rows = table.getElementsByTagName("tr");
        var studenti = [];
        // Ciclo attraverso tutte le righe della tabella
        for (var i = 1; i < rows.length; i++) { // Parto da 1 per saltare l'intestazione della tabella
            var cells = rows[i].getElementsByTagName("td");
            var studente = {
                nome: cells[0].textContent,
                cognome: cells[1].textContent,
                classe: cells[2].textContent,
                annoAccademico: cells[3].textContent,
                email: cells[4].textContent,
                indirizzo: cells[5].textContent,
                voto: cells[6].textContent,
                cap: cells[7].textContent
            };
            studenti.push(studente);
        }
        // Effettua una richiesta AJAX per aggiornare il database
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert("Modifiche salvate con successo!");
                } else {
                    alert("Si è verificato un errore durante il salvataggio delle modifiche.");
                }
            }
        };
        xhr.open("POST", "aggiorna_studenti.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(studenti));
    }

    function assegnaAzienda(idAzienda, idAmministratore, idStudente) {

      // Definisci i dati da inviare come JSON
      var jsonData = {
          IDAzienda: idAzienda, // Sostituisci con l'ID dell'azienda
          IDAmministratore: idAmministratore, // Sostituisci con l'ID dell'amministratore
          IDStudente: idStudente
      };

      // Imposta le opzioni della richiesta
      var requestOptions = {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify(jsonData)
      };

      // Effettua la richiesta al server REST
      fetch('assegna_azienda', requestOptions)
      .then(response => {
          if (!response.ok) {
          throw new Error('Si è verificato un errore durante la richiesta.');
          }
          return response.json();
      })
      .then(data => {
          console.log('Risposta dal server:', data);
      })
      .catch(error => {
          console.error('Errore durante la richiesta:', error.message);
      });

  }
  
</script>

</body>
</body>
</html>