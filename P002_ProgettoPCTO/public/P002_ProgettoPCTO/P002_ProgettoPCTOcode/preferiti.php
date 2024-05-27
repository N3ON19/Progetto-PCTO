<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="home.css">

<title>Elenco Aziende Preferite</title>
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
                <li><a href="paginaPreferiti">Preferiti</a></li>
                <li><a href="diarioStudente">Diario</a></li>
                <li><a href="https://github.com/N3ON19">Contact</a></li>
            </ul>
        </div>
        </div>
    
</div>
<div class="container">
    <h1>Elenco Aziende Preferite</h1>
    <div id="aziende-lista"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('aziendePreferite')
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
                        <button class='recensioni-button' data-id='${azienda.IDAzienda}' onclick='visualizzaRecensioni(this)'>Visualizza recensioni</button>
                        <div class="recensioni" style="display: none;"></div>
                    </div>
                `;
                aziendeLista.appendChild(aziendaDiv);
            });
        })
        .catch(error => console.error('Errore durante il fetch delle aziende preferite:', error));
    });

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

    function togliAzienda(button) {
        var aziendaId = button.getAttribute('data-id');
        fetch('togliPreferiti', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ azienda_id: aziendaId })
        })
        .then(response => {
            if (response.ok) {
                button.parentElement.parentElement.remove();
            } else {
                console.error('Errore durante la rimozione dell\'azienda preferita');
            }
        })
        .catch(error => console.error('Errore durante la rimozione dell\'azienda preferita:', error));
    }

    function isStudente() {
        // Verifica se l'utente è un studente
        return '<?php echo isset($_SESSION["user_type"]) && $_SESSION["user_type"] === "studente"; ?>';
    }

    function returnHome() {
        window.location.href = 'home.php'; 
    }
</script>


</body>
</html>
