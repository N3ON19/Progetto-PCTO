<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="home.css">

<head>
    <title>Elenco Studenti</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td[contenteditable="true"] {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
<div class="navbar">
        <div class="logo">
            <h1>Zucc. PCTO</h1>
        </div>
    <div class="menu">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="account">Account</a></li>
                <li><a href="https://github.com/N3ON19">Contact</a></li>
            </ul>
        </div>
        </div>
    
</div>
    <h2>Elenco Studenti</h2>
    <button onclick="window.location.href = 'home.php';">Torna in Home</button>

    <input type="text" id="searchInput" onkeyup="searchStudents()" placeholder="Cerca studente per nome o cognome">
    <table id="studentsTable">
        <tr>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Classe</th>
            <th>Anno Accademico</th>
            <th>Email</th>
            <th>Indirizzo</th>
            <th>Voto</th>
            <th>CAP</th>
            <th>Visualizza Diario</th>
            <th>Visualizza Preferiti</th>
            <th>Azienda</th>
        </tr>
    </table>
    <button onclick="salvaModifiche()">Salva modifiche</button>

    <script>
        let studenti = "";

        function loadStudents() {
            fetch('studenti') 
                .then(response => response.json())
                .then(data => {
                    studenti = data;
                    fetch("datiAziende").then((response) => response.json()).then((datiAziende) => {

                        const table = document.getElementById('studentsTable');
                        console.log(datiAziende);
                        studenti.forEach(studente => {
                            const row = table.insertRow();
                            row.innerHTML = `
                        <td>${studente.Nome}</td>
                        <td>${studente.Cognome}</td>
                        <td>${studente.Classe}</td>
                        <td>${studente.AnnoAccademico}</td>
                        <td>${studente.Email}</td>
                        <td>${studente.Indirizzo}</td>
                        <td contenteditable="true">${studente.Voto}</td>
                        <td>${studente.CAP}</td>
                        <td><button onclick="visualizzaDiario(${studente.IDStudent})">${studente.Nome} - Visualizza Diario</button></td>
                        <td><button onclick="visualizzaPreferiti(${studente.IDStudent})">${studente.Nome} - Visualizza Preferiti</button></td>

                        <td>
                            <select>
                                <option>Seleziona azienda</option>
                            </select>
                      </td>`;

                            let select = row.querySelector("select");

                            for (let azienda of datiAziende) {
                                select.innerHTML = select.innerHTML + `<option>${azienda["Nome"]}</option>`
                            }
                            for (let azienda of datiAziende) {
                                if (azienda["Nome"] == studente.Azienda) {
                                    select.value = studente.Azienda;
                                }
                            }


                            select.addEventListener("change", () => {
                                let idAzienda = -1;
                                for (let azienda of datiAziende) {
                                if (select.value == azienda.Nome) {
                                    idAzienda =azienda.IDAzienda;
                                }
                            }
                                assegnaAzienda(select.value, idAzienda,studente.IDStudent);
                            });
                        }


                        );
                    });

                })
                .catch(error => console.error('Errore durante il caricamento degli studenti:', error));
        }

        function searchStudents() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase();
            var rows = document.getElementById("studentsTable").getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var found = false;
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
                if (found) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

        function visualizzaDiario(idStudente) {
            window.location.href = 'visualizza-diario?id=' + idStudente;
        }

        function visualizzaPreferiti(idStudente) {
            window.location.href = 'preferitiStudenti?id=' + idStudente;
        }

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
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
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

        function assegnaAzienda(nomeAzienda, idAzienda, idStudente) {

            let dati = {
                "nomeAzienda": nomeAzienda,
                "idAzienda" : idAzienda,
                "idStudente": idStudente
            };
            fetch("assegnaAzienda", {
                method: "POST",
                body: JSON.stringify(dati)
            }
            ).then((response) => response.json()).then((value) => {
                console.log(value);
            });
        }

        loadStudents();
    </script>
</body>

</html>