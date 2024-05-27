<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="home.css">

<title>Scrivi Recensione</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
    }
    label {
        display: block;
        margin-bottom: 10px;
    }
    input, textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
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
                <li><a href="paginaPreferiti">Preferiti</a></li>
                <li><a href="diarioStudente">Diario</a></li>
                <li><a href="https://github.com/N3ON19">Contact</a></li>
            </ul>
        </div>
        </div>
<h1>Scrivi Recensione</h1>

<form id="recensione-form">    
<input type="hidden" id="azienda_id" name="azienda_id" value="<?php echo $_GET['azienda_id']; ?>">

    <label for="commento">Commento:</label>
    <textarea id="commento" name="commento" rows="4" required></textarea>
    
    <label for="voto">Voto:</label>
    <input type="number" id="voto" name="voto" min="1" max="5" required>
    
    <button type="submit">Invia Recensione</button>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('recensione-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); 

        var formData = new FormData(form);

        fetch('scriviRecensione', {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            if (response.ok) {
                return response.text(); 
            }
            throw new Error('Errore durante la richiesta.');
        })
        .then(function(data) {
            alert(data); // Mostra il messaggio restituito dalla route
        })
        .catch(function(error) {
            console.error('Si è verificato un errore:', error);
        });
    });
});

</script>

</body>
</html>
