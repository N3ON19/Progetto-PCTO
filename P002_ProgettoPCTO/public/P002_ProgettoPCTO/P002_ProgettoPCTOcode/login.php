<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login Studente</h2>
    <form method="post" action="student-login">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Login">
    </form>

    <h2>Login Amministratore</h2>
    <form method="post" action="admin-login">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Login">
    </form>

    <!-- <h2>Registrazione Studente</h2>
    <form method="post" action="student-register">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
        <label for="cognome">Cognome:</label>
        <input type="text" id="cognome" name="cognome">
        <label for="classe">Classe:</label>
        <input type="text" id="classe" name="classe">
        <label for="anno_accademico">Anno Accademico:</label>
        <input type="text" id="anno_accademico" name="anno_accademico">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <label for="indirizzo">Indirizzo:</label>
        <input type="text" id="indirizzo" name="indirizzo">
        <label for="cap">CAP:</label>
        <input type="text" id="cap" name="cap">
        <input type="submit" value="Registrati">
    </form>

    <h2>Registrazione Amministratore</h2>
    <form method="post" action="admin-register">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
        <label for="cognome">Cognome:</label>
        <input type="text" id="cognome" name="cognome">
        <label for="classe">Classe:</label>
        <input type="text" id="classe" name="classe">
        <label for="anno_accademico">Anno Accademico:</label>
        <input type="text" id="anno_accademico" name="anno_accademico">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Registrati">
    </form> -->
</div>

</body>
</html>
