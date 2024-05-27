<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diario Studente</title>
    <link rel="stylesheet" type="text/css" href="home.css">

    <style>
        /* Copia e incolla qui il CSS fornito */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: #333;
        }
        
        p {
            margin: 0 0 10px;
            color: #555;
        }
        
        hr {
            border: 0;
            border-top: 1px solid #ccc;
            margin: 20px 0;
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
        
    <div id="diario-info"></div>

    <script>
        // Funzione per fare il fetch delle informazioni del diario
        function fetchDiario() {
            fetch('diario')
            .then(response => response.json())
            .then(data => {
                // Creazione dell'HTML con le informazioni del diario
                const diarioInfo = document.getElementById('diario-info');
                data.forEach(diario => {
                    const diarioHTML = `
                        <div class="container">
                            <h2>Diario Studente</h2>
                            <p><strong>Giorno:</strong> ${diario.Giorno}</p>
                            <p><strong>Descrizione:</strong> ${diario.Descrizione}</p>
                            <p><strong>Ruolo:</strong> ${diario.Ruolo}</p>
                            <p><strong>Entrata Mattino:</strong> ${diario.EntrataMattino}</p>
                            <p><strong>Uscita Mattino:</strong> ${diario.UscitaMattino}</p>
                            <p><strong>Entrata Pomeriggio:</strong> ${diario.EntrataPomeriggio}</p>
                            <p><strong>Uscita Pomeriggio:</strong> ${diario.UscitaPomeriggio}</p>
                            <hr>
                        </div>
                    `;
                    diarioInfo.innerHTML += diarioHTML;
                });
            })
            .catch(error => console.error('Errore durante il fetch delle informazioni del diario:', error));
        }

        // Eseguiamo il fetch delle informazioni del diario al caricamento della pagina
        fetchDiario();
    </script>
</body>
</html>
