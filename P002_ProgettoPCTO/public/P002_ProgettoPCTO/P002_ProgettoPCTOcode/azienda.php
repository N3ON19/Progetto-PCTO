<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="home.css">

<title>Elenco Aziende</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    .azienda {
        border-bottom: 1px solid #ccc;
        padding: 20px 0;
    }
    .azienda:last-child {
        border-bottom: none;
    }
    .azienda img {
        max-width: 100px;
        max-height: 100px;
        margin-right: 20px;
        border-radius: 5px;
    }
    .azienda .info {
        flex: 1;
    }
    .azienda h2 {
        margin-top: 0;
    }
    .azienda p {
        margin: 5px 0;
    }
    .preferiti-button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #007bff; 
        }

        .preferiti-button:before {
            content: '★'; 
        }

        .preferiti-button:hover {
            color: #deb217; 
        }

        .preferiti-button.active:before {
            color: #ffc700; 
        }

    .scrivi-recensione-button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            border-radius: 5px;
            text-transform: uppercase;
        }

    .scrivi-recensione-button:hover {
            background-color: #0056b3;
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
                <?php
                if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'amministratore') {
                    echo '<li><a href="paginaPreferiti">Preferiti</a></li>';
                }
                ?>
                 <?php
                if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'amministratore') {
                    echo '<li><a href="diarioStudente">Diario</a></li>';
                }
                ?>
                <li><a href="https://github.com/N3ON19">Contact</a></li>
            </ul>
        </div>
        </div>
<div class="container">
    <h1>Elenco Aziende</h1>
    <div id="aziende-lista"></div>
</div>

<script>
   document.addEventListener("DOMContentLoaded", function() {
        fetch('aziende')
        .then(response => response.json())
        .then(data => {
            const aziendeLista = document.getElementById('aziende-lista');
            data.forEach(azienda => {
                const aziendaDiv = document.createElement('div');
                aziendaDiv.classList.add('azienda');
                const immagine = azienda.Image ? `<img src="${azienda.Image}" alt="${azienda.Nome}">` : '';
                aziendaDiv.innerHTML = `
                    ${immagine}
                    <div class="info">
                        <h2>${azienda.Nome}</h2>
                        <p><strong>Specializzazione:</strong> ${azienda.Specializzazione}</p>
                        <p><strong>Indirizzo:</strong> ${azienda.Indirizzo}, CAP: ${azienda.CAP}</p>
                        <p>${azienda.Descrizione}</p>
                        <h4>Tutor:</h4>
                        <button class='preferiti-button' data-id='${azienda.IDAzienda}' data-preferito='${azienda.Preferito ? 'true' : 'false'}' onclick='togglePreferito(this)'></button>

                        <button class='recensioni-button' data-id='${azienda.IDAzienda}' onclick='visualizzaRecensioni(this)'>Visualizza recensioni</button>
                        <?php if ($_SESSION['user_type'] !== 'amministratore'): ?>
                        <button class='scrivi-recensione-button' data-id='${azienda.IDAzienda}' onclick='scriviRecensione(this)'>Scrivi Recensione</button>
                        <?php endif; ?>                
                        <div class="recensioni" style="display: none;"></div>
                    </div>
                `;
                aziendeLista.appendChild(aziendaDiv);
            });
        })
        .catch(error => console.error('Errore durante il fetch delle aziende:', error));
    });

    function togglePreferito(button) {
        var idAzienda = button.getAttribute('data-id');
        var aggiungi = !button.classList.contains('active'); 
        var formData = new FormData();
        formData.append('azienda_id', idAzienda);
        formData.append('aggiungi', aggiungi ? '1' : '0');

        fetch('aziendaPreferita', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.text();
            }
            throw new Error('Errore durante l\'aggiornamento dei preferiti.');
        })
        .then(message => {
            alert(message); 
            if (aggiungi) {
                button.classList.add('active'); 
            } else {
                button.classList.remove('active'); 
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Si è verificato un errore durante l\'aggiornamento dei preferiti.');
        });
    }

    function visualizzaRecensioni(button) {
        var aziendaId = button.getAttribute('data-id');
        var recensioniDiv = button.parentElement.querySelector('.recensioni');

        fetch('recensioni?azienda_id=' + aziendaId)
            .then(response => response.json())
            .then(recensioni => {
                if (recensioni.length > 0) {
                    recensioniDiv.innerHTML = '';
                    recensioni.forEach(recensione => {
                        recensioniDiv.innerHTML += `
                            <p><strong>Commento:</strong> ${recensione.Commento}</p>
                            <p><strong>Voto:</strong> ${recensione.Voto}</p>
                            <hr>
                        `;
                    });
                    recensioniDiv.style.display = 'block';
                } else {
                    recensioniDiv.innerHTML = '<p>Non ci sono recensioni per questa azienda.</p>';
                    recensioniDiv.style.display = 'block';
                }
            })
            .catch(error => console.error('Errore durante il fetch delle recensioni:', error));
    }

    function scriviRecensione(button) {
    var aziendaId = button.getAttribute('data-id');
    window.location.href = 'scriviRecensioni.php?azienda_id=' + aziendaId;
}


    function returnHome() {
        window.location.href = 'home.php'; 
    }
</script>

</body>
</html>
