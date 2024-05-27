<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Compila Diario di Bordo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type='date'],
        input[type='time'],
        input[type='text'],
        textarea {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button[type='submit'] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type='submit']:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class='container'>
        <a href='home.php' style='display: block; margin-bottom: 10px; text-align: center;'>Torna alla Home</a> 
        <h2>Compila Diario di Bordo</h2>
        <form action='inserisci-diario' method='POST'>
            <label for='giorno'>Giorno:</label>
            <input type='date' id='giorno' name='giorno' required>
    
            <label for='descrizione'>Descrizione:</label>
            <textarea id='descrizione' name='descrizione' rows='4' required></textarea>
    
            <label for='ruolo'>Ruolo:</label>
            <input type='text' id='ruolo' name='ruolo' required>
    
            <label for='entrataMattino'>Entrata Mattino:</label>
            <input type='time' id='entrataMattino' name='entrataMattino' required>
    
            <label for='uscitaMattino'>Uscita Mattino:</label>
            <input type='time' id='uscitaMattino' name='uscitaMattino' required>
    
            <label for='entrataPomeriggio'>Entrata Pomeriggio:</label>
            <input type='time' id='entrataPomeriggio' name='entrataPomeriggio' required>
    
            <label for='uscitaPomeriggio'>Uscita Pomeriggio:</label>
            <input type='time' id='uscitaPomeriggio' name='uscitaPomeriggio' required>
    
            <button type='submit'>Invia</button>
        </form>
    </div>
</body>
</html>
