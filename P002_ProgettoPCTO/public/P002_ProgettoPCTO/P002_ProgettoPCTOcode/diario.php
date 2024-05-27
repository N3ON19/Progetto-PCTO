<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diario di <?php echo $nomeStudente . " " . $cognomeStudente; ?></title> 
    
<style>
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
<body>
    <div class="container">
        <h2>Diario di <?php echo $nomeStudente . " " . $cognomeStudente; ?></h2> 
        <?php foreach ($diarioResult as $diario): ?>
            <p><strong>Giorno:</strong> <?php echo $diario['Giorno']; ?></p>
            <p><strong>Descrizione:</strong> <?php echo $diario['Descrizione']; ?></p>
            <p><strong>Ruolo:</strong> <?php echo $diario['Ruolo']; ?></p>
            <p><strong>Entrata Mattino:</strong> <?php echo $diario['EntrataMattino']; ?></p>
            <p><strong>Uscita Mattino:</strong> <?php echo $diario['UscitaMattino']; ?></p>
            <p><strong>Entrata Pomeriggio:</strong> <?php echo $diario['EntrataPomeriggio']; ?></p>
            <p><strong>Uscita Pomeriggio:</strong> <?php echo $diario['UscitaPomeriggio']; ?></p>
            <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
