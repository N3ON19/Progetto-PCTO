<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Amministrazione PCTO</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="home.css">
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
        <div class="signup">
            <?php
            if (isset($_SESSION['user_type'])) {
                echo '<a href="logout">Logout</a>';
            } else {
                echo '<a href="login.php">Login</a>';
            }
            ?>
        </div>
    </div>
    <div class="body">
        <div class="heading">
            <h1>Benvenuto ad Amministrazione PCTO</h1>
            <br>
            <p>Un semplice sito per migliorare e facilitare la relazione PCTO dell'alternanza scuola-lavoro </p>
            <br>
            <br>
            <a href="https://www.itiszuccante.edu.it/">Learn More</a>
        </div>
    
        <div class="places">
            <h2>Aziende</h2>
            <img src="azienda.jpeg" style="width: 300px; height: 250px; border-radius: 12px;">
            <br>
            <br>
            <a href="http://localhost/P002_ProgettoPCTO/P002_ProgettoPCTOcode/paginaAziende">Visualizza</a>
        </div>

        <?php
        if (isset($_SESSION['user_type'])) {
            $user_type = $_SESSION['user_type'];
            if ($user_type === 'amministratore') {
                echo '<div class="places">
                        <h2>Classe</h2>
                        <img src="classe.jpg" style="width: 300px; height: 250px; border-radius: 12px;">
                        <br>
                        <br>
                        <a href="http://localhost/P002_ProgettoPCTO/P002_ProgettoPCTOcode/paginaStudenti">Visualizza</a>
                    </div>';
            } elseif ($user_type === 'studente') {
                echo '<div class="places">
                        <h2>Diario</h2>
                        <img src="diario.jpg" style="width: 300px; height: 250px; border-radius: 12px;">
                        <br>
                        <br>
                        <a href="compila-diario">Compila Diario</a>
                    </div>';
            }
        } else {
            echo '<div class="places">
                    <img src="bloccato.webp" style="width: 300px; height: 250px; border-radius: 12px;">
                    <br>
                    <br>
                    <p>Devi accedere per visualizzare questa sezione.</p>
                </div>';
        }
        ?>
    </div>
    <!-- <div class="footer">
        <a href="#">Copyright</a>
        <a href="#">Terms and Conditions</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Cookies</a>
        <a href="#">Complaints</a>
    </div> -->
</body>
</html>
