<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="home.css">

<title>Informazioni Utente</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    #container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        color: #333;
    }
    #userInfo {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    #userInfo p {
        margin: 0;
        padding: 5px 0;
    }
    #userInfo p:first-child {
        font-weight: bold;
    }
    .error {
        color: red;
        font-weight: bold;
        text-align: center;
    }
</style>
<script>
function fetchUserInfo() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.error) {
                document.getElementById("userInfo").innerHTML = "<p class='error'>" + response.error + "</p>";
            } else {
                var userInfoHTML = "<p>Informazioni dell'utente:</p>";
                for (var key in response) {
                    if (response.hasOwnProperty(key)) {
                        userInfoHTML += "<p>" + key + ": " + response[key] + "</p>";
                    }
                }
                document.getElementById("userInfo").innerHTML = userInfoHTML;
            }
        }
    };
    xhttp.open("GET", "informazioniUtente", true);
    xhttp.send();
}

window.onload = fetchUserInfo;
</script>
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
<div id="container">
    <h1>Informazioni Utente</h1>
    <div id="userInfo">
        <!-- Le informazioni dell'utente saranno visualizzate qui -->
    </div>
</div>
</body>
</html>
