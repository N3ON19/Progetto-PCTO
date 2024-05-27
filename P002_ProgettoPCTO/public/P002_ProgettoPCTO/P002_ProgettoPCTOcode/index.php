<?php

require 'fatfree-core-master/base.php';
session_start();
$f3 = Base::instance();

$db = new DB\SQL('mysql:host=localhost;port=3306;dbname=progettopcto', 'root', '');

function stampaStudenti($db, $f3) {
    // Assicurati di avere l'ID dell'amministratore corrente memorizzato nella sessione
    if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'amministratore') {
        $amministratore_id = $_SESSION['amministratore_id'];
        
        // Modifica la query per selezionare gli studenti e le aziende assegnate della classe dell'amministratore corrente
        $studentiQuery = "SELECT s.*, a.Nome AS Azienda
                          FROM STUDENTE s
                          LEFT JOIN ASSEGNA aa ON s.IDStudent = aa.IDStudente
                          LEFT JOIN AZIENDA a ON aa.IDAzienda = a.IDAzienda
                          WHERE s.Classe IN (SELECT Classe FROM AMMINISTRATORE WHERE IDAmministratore = :amministratore_id)";
        $studentiStatement = $db->prepare($studentiQuery);
        $studentiStatement->execute(array(':amministratore_id' => $amministratore_id));
        $studentiResult = $studentiStatement->fetchAll(PDO::FETCH_ASSOC);
        
        if ($studentiResult) {
            // header('Content-Type: application/json');
            $f3->set("CONTENT_TYPE", "application/json");
            echo json_encode($studentiResult);
        } else {
            echo "Nessuno studente trovato nella tua classe.";
        }
    } else {
        echo "Non sei autorizzato a visualizzare questa pagina.";
    }
}

$f3->route('GET /studenti', function($f3) use ($db) {
    // Controlla se l'utente è un amministratore prima di visualizzare gli studenti
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'amministratore') {
        stampaStudenti($db, $f3);
    } else {
        echo "Non sei autorizzato a visualizzare gli studenti.";
    }
});

 $f3->route('GET /paginaStudenti', function($f3) use ($db) {
     $view = new View();
    echo $view->render("studenti.php");
 });

//  $f3->route('GET /api/studenti', function($f3) use ($db) {
//     // Richiama la funzione per stampare gli studenti
//     stampaStudenti($db, $f3);
// });



function stampaAziende($db) {
    // Modifica la query per selezionare tutte le aziende
    $aziendeQuery = "SELECT * FROM AZIENDA";
    $aziendeStatement = $db->prepare($aziendeQuery);
    $aziendeStatement->execute();
    $aziendeResult = $aziendeStatement->fetchAll(PDO::FETCH_ASSOC);
    
    if ($aziendeResult) {
        // header('Content-Type: application/json');
        header("Content-Type","application/json");
        echo json_encode($aziendeResult);
    } else {
        echo "Nessuna azienda trovata.";
    }
}

$f3->route('GET /paginaAziende', function($f3) use ($db) {
    $view = new View();
   echo $view->render("azienda.php");
});


$f3->route('GET /aziende', function($f3) use ($db) {
    stampaAziende($db);
    
});

$f3->route('GET /datiAziende', function($f3) use ($db) {
    $aziendeQuery = "SELECT * FROM AZIENDA";
    $aziendeResult = $db->exec($aziendeQuery);
    header("Content-Type","application/json");
    echo json_encode($aziendeResult);
});

$f3->route('POST /assegnaAzienda', function($f3) use ($db) {

    $body = json_decode($f3->get("BODY"), true);
        $idAzienda = $body["idAzienda"];
        $idAmministratore = $_SESSION['amministratore_id'];
        $idStudente = $body['idStudente'];
        
        // Esegui la query per aggiornare la tabella ASSEGNA
        try{
            $query = "INSERT INTO ASSEGNA (IDStudente, IDAzienda, IDAmministratore) VALUES (:idStudente, :idAzienda, :idAmministratore)";
        $statement = $db->prepare($query);
        $statement->execute(array(':idStudente' => $idStudente, ':idAzienda' => $idAzienda, ':idAmministratore' => $idAmministratore));

        echo json_encode(["messaggio" => ["invio dei dati avvenuto con successo"]]);
        }
        catch(Exception $e){
            echo json_encode(["messaggio" => ["Diego, stai provando ad inserire 2 volte lo stesso dato ad 1 studente"]]);
            
        }
        exit();
        
});



$f3->route("GET /",function(){
    $view = new View();
    echo $view->render("login.php");
});



// $f3->route('GET /p002_ProgettoPCTO/p002_ProgettoPCTOcode/a', function() {
//     echo "PROVA";
// });


$f3->route('POST /student-login', function($f3) use ($db) {
    $email = $f3->get('POST.email');
    $password = $f3->get('POST.password');

    $studentQuery = "SELECT * FROM STUDENTE WHERE Email = :email AND Password = :password";
    $studentResult = $db->exec($studentQuery, array(':email' => $email, ':password' => $password));

    if ($studentResult) {
        $_SESSION['user_type'] = 'studente';
        $_SESSION['studente_id'] = $studentResult[0]['IDStudent']; 
        header("Location: home.php");
        exit;
    } else {
        echo "<p>Credenziali studente non valide.</p>";
    }
});



$f3->route('POST /admin-login', function($f3) use ($db) {
    $email = $f3->get('POST.email');
    $password = $f3->get('POST.password');

    // Verifica il login dell'amministratore
    $adminQuery = "SELECT * FROM AMMINISTRATORE WHERE Email = :email AND Password = :password";
    $adminResult = $db->exec($adminQuery, array(':email' => $email, ':password' => $password));

    if ($adminResult) {
        // Login amministratore riuscito, memorizza l'ID dell'amministratore nella sessione
        $_SESSION['user_type'] = 'amministratore';
        $_SESSION['amministratore_id'] = $adminResult[0]['IDAmministratore'];
        header("Location: home.php");
        exit; // Assicura che il reindirizzamento avvenga senza eseguire ulteriori istruzioni
    } else {
        echo "<p>Credenziali amministratore non valide.</p>";
    }
});

$f3->route('GET /logout', function($f3) {

    session_destroy();
 
    header("Location: home.php"); 
    exit; 
});

$f3->route('POST /student-register', function($f3) use ($db) {
    $nome = $f3->get('POST.nome');
    $cognome = $f3->get('POST.cognome');
    $classe = $f3->get('POST.classe');
    $annoAccademico = $f3->get('POST.anno_accademico');
    $email = $f3->get('POST.email');
    $password = $f3->get('POST.password');
    $indirizzo = $f3->get('POST.indirizzo');
    $cap = $f3->get('POST.cap');

    // Query per inserire lo studente nel database
    $insertStudentQuery = "INSERT INTO STUDENTE (Nome, Cognome, Classe, AnnoAccademico, Email, Password, Indirizzo, CAP) 
                           VALUES (:nome, :cognome, :classe, :annoAccademico, :email, :password, :indirizzo, :cap)";

    // Esegui la query
    $result = $db->exec($insertStudentQuery, array(
        ':nome' => $nome,
        ':cognome' => $cognome,
        ':classe' => $classe,
        ':annoAccademico' => $annoAccademico,
        ':email' => $email,
        ':password' => $password,
        ':indirizzo' => $indirizzo,
        ':cap' => $cap
    ));

    if ($result) {
        // Registrazione studente avvenuta con successo, reindirizza alla home
        header("Location: home.php");
        exit;
    } else {
        echo "<p>Errore durante la registrazione dello studente.</p>";
    }
});


$f3->route('POST /admin-register', function($f3) use ($db) {
    $nome = $f3->get('POST.nome');
    $cognome = $f3->get('POST.cognome');
    $classe = $f3->get('POST.classe');
    $annoAccademico = $f3->get('POST.anno_accademico');
    $email = $f3->get('POST.email');
    $password = $f3->get('POST.password');

    // Query per inserire l'amministratore nel database
    $insertAdminQuery = "INSERT INTO AMMINISTRATORE (Nome, Cognome, Classe, AnnoAccademico, Email, Password) 
                         VALUES (:nome, :cognome, :classe, :annoAccademico, :email, :password)";

    // Esegui la query
    $result = $db->exec($insertAdminQuery, array(
        ':nome' => $nome,
        ':cognome' => $cognome,
        ':classe' => $classe,
        ':annoAccademico' => $annoAccademico,
        ':email' => $email,
        ':password' => $password
    ));

    if ($result) {
        // Registrazione amministratore avvenuta con successo, reindirizza alla home
        header("Location: home.php");
        exit;
    } else {
        echo "<p>Errore durante la registrazione dell'amministratore.</p>";
    }
});

$f3->route('GET /aziendePreferite', function($f3) use ($db) {
    if(isset($_SESSION['studente_id'])) {
        $student_id = $_SESSION['studente_id'];

        $aziendeQuery = "SELECT A.* FROM AZIENDA A INNER JOIN PREFERENZA P ON A.IDAzienda = P.IDAzienda WHERE P.IDStudente = :student_id";
        $stmt = $db->prepare($aziendeQuery);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $aziendeResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: application/json");

        echo json_encode($aziendeResult);
    } else {
        echo json_encode(array("error" => "IDStudente non trovato"));
    }
});

$f3->route('DELETE /togliPreferiti', function($f3) use ($db) {
    $requestData = json_decode($f3->get('BODY'), true);

    if (isset($requestData['azienda_id'])) {
        $aziendaId = $requestData['azienda_id'];

        $deleteQuery = "DELETE FROM PREFERENZA WHERE IDAzienda = :azienda_id";
        $stmt = $db->prepare($deleteQuery);
        $stmt->bindParam(':azienda_id', $aziendaId, PDO::PARAM_INT);
        $stmt->execute();

        $f3->status(200);
    } else {
        $f3->status(400);
    }
});


$f3->route('GET /paginaPreferiti', function($f3) use ($db) {
    $view = new View();
   echo $view->render("preferiti.php");
});

function compilaDiario($db, $studente_id, $giorno, $descrizione, $ruolo, $entrataMattino, $uscitaMattino, $entrataPomeriggio, $uscitaPomeriggio) {
    // Query per inserire i dati del diario nel database
    $query = "INSERT INTO DIARIO (IDStudente, Giorno, Descrizione, Ruolo, EntrataMattino, UscitaMattino, EntrataPomeriggio, UscitaPomeriggio)
              VALUES (:studente_id, :giorno, :descrizione, :ruolo, :entrataMattino, :uscitaMattino, :entrataPomeriggio, :uscitaPomeriggio)";
    
    // Esegui la query
    $result = $db->exec($query, array(
        ':studente_id' => $studente_id,
        ':giorno' => $giorno,
        ':descrizione' => $descrizione,
        ':ruolo' => $ruolo,
        ':entrataMattino' => $entrataMattino,
        ':uscitaMattino' => $uscitaMattino,
        ':entrataPomeriggio' => $entrataPomeriggio,
        ':uscitaPomeriggio' => $uscitaPomeriggio
    ));
    
    return $result; // Ritorna il risultato dell'inserimento nel database
}




function verificaAziendaAssegnata($db, $studente_id) {
    $query = "SELECT COUNT(*) AS count FROM ASSEGNA WHERE IDStudente = :studente_id";
    $statement = $db->prepare($query);
    $statement->execute(array(':studente_id' => $studente_id));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    
    return ($row['count'] > 0);
}

$f3->route('GET /compila-diario', function($f3) use ($db) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'studente') {
        // Controlla se lo studente ha un'azienda assegnata
        $aziendaAssegnata = verificaAziendaAssegnata($db, $_SESSION['studente_id']);
        
        if ($aziendaAssegnata) {
            // Includi il file HTML per il form di compilazione del diario
            include('compila-diario.php');
        } else {
            echo "Non puoi compilare il diario perché non hai un'azienda assegnata.";
        }
    } else {
        echo "Non sei autorizzato a compilare il diario.";
    }
});


$f3->route('POST /inserisci-diario', function($f3) use ($db) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'studente') {
        $giorno = $f3->get('POST.giorno');
        $descrizione = $f3->get('POST.descrizione');
        $ruolo = $f3->get('POST.ruolo');
        $entrataMattino = $f3->get('POST.entrataMattino');
        $uscitaMattino = $f3->get('POST.uscitaMattino');
        $entrataPomeriggio = $f3->get('POST.entrataPomeriggio');
        $uscitaPomeriggio = $f3->get('POST.uscitaPomeriggio');
        
        compilaDiario($db, $_SESSION['studente_id'], $giorno, $descrizione, $ruolo, $entrataMattino, $uscitaMattino, $entrataPomeriggio, $uscitaPomeriggio);
        
        echo "Dati del diario salvati con successo.";
    } else {
        echo "Non sei autorizzato a compilare il diario.";
    }
});

// $f3->route('PUT /modifica-diario', function($f3) use ($db) {

//     $requestBody = file_get_contents('php://input');
//     $requestData = json_decode($requestBody, true);
//     $diario_id = $requestData['diario_id'];
//     $descrizione = $requestData['Descrizione']; // Assicurati che i nomi corrispondano esattamente
//     $ruolo = $requestData['Ruolo'];
//     $entrataMattino = $requestData['EntrataMattino'];
//     $uscitaMattino = $requestData['UscitaMattino'];
//     $entrataPomeriggio = $requestData['EntrataPomeriggio'];
//     $uscitaPomeriggio = $requestData['UscitaPomeriggio'];
    
//     $query = "UPDATE DIARIO SET Descrizione = :descrizione, Ruolo = :ruolo, EntrataMattino = :entrataMattino, UscitaMattino = :uscitaMattino, EntrataPomeriggio = :entrataPomeriggio, UscitaPomeriggio = :uscitaPomeriggio WHERE IDDiario = :diario_id";
//     $result = $db->exec($query, array(
//         ':descrizione' => $descrizione,
//         ':ruolo' => $ruolo,
//         ':entrataMattino' => $entrataMattino,
//         ':uscitaMattino' => $uscitaMattino,
//         ':entrataPomeriggio' => $entrataPomeriggio,
//         ':uscitaPomeriggio' => $uscitaPomeriggio,
//         ':diario_id' => $diario_id
//     ));

//     if ($result) {
//         echo "Diario aggiornato con successo.";
//     } else {
//         echo "Errore durante l'aggiornamento del diario.";
//     }
// });


$f3->route('GET /diarioStudente', function($f3) use ($db) {
    $view = new View();
   echo $view->render("diarioStudente.php");
});

$f3->route('GET /visualizza-diario', function($f3) use ($db) {

    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'amministratore') {
        $studente_id = $f3->get('GET.id');

        $diarioQuery = "SELECT * FROM DIARIO WHERE IDStudente = :studente_id";
        $diarioResult = $db->exec($diarioQuery, array(':studente_id' => $studente_id));

        $studenteQuery = "SELECT Nome, Cognome FROM STUDENTE WHERE IDStudent = :studente_id";
        $studenteResult = $db->exec($studenteQuery, array(':studente_id' => $studente_id));

        $nomeStudente = $studenteResult[0]['Nome'];
        $cognomeStudente = $studenteResult[0]['Cognome'];

        if ($diarioResult) {
            include('diario.php');
        } else {
            echo "Nessun diario trovato per lo studente.";
        }

    } else {
        echo "Non sei autorizzato a visualizzare il diario.";
    }
});



$f3->route('GET /visualizza-preferiti', function($f3) use ($db) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'amministratore') {
        $studente_id = $f3->get('GET.id'); 
        
        $preferitiQuery = "SELECT A.* FROM AZIENDA A INNER JOIN PREFERENZA P ON A.IDAzienda = P.IDAzienda WHERE P.IDStudente = :studente_id";
        $stmt = $db->prepare($preferitiQuery);
        $stmt->bindParam(':studente_id', $studente_id, PDO::PARAM_INT);
        $stmt->execute();
        $preferitiResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
        if ($preferitiResult) {
            $f3->set('preferiti', $preferitiResult);
            header("Content-Type: application/json");
            echo json_encode($preferitiResult);
        } else {
            echo json_encode(array("message" => "Nessuna azienda preferita trovata."));
        }
    } else {
        echo json_encode(array("message" => "Non sei autorizzato a visualizzare gli preferiti."));
    }
});


$f3->route('GET /preferitiStudenti', function($f3) use ($db) {
    $view = new View();
   echo $view->render("preferitiStudenti.php");
});


$f3->route('GET /diario', function($f3) use ($db) {
    $id = $_SESSION['studente_id'];
    // $diarioQuery = "SELECT * FROM DIARIO WHERE IDStudente = :$id";
    $diarioResult = $db->exec('SELECT * FROM diario WHERE IDStudente = ?', $id);  

    header("Content-Type: application/json");
    echo json_encode($diarioResult);
});



$f3->route('GET /informazioniUtente', function($f3) use ($db) {
    if (!isset($_SESSION['user_type'])) {
        echo json_encode(array("error" => "Devi effettuare l'accesso per visualizzare le informazioni dell'utente."));
        return;
    }

    $userType = $_SESSION['user_type'];
    $query = '';
    $params = array();

    if ($userType === 'studente') {
        $studentId = $_SESSION['studente_id'];
        $query = "SELECT Nome, Cognome, Classe, AnnoAccademico, Email, Indirizzo, Voto, CAP FROM STUDENTE WHERE IDStudent = :studentId";
        $params = array(':studentId' => $studentId);
    } elseif ($userType === 'amministratore') {
        $adminId = $_SESSION['amministratore_id'];
        $query = "SELECT Nome, Cognome, Classe, AnnoAccademico, Email FROM AMMINISTRATORE WHERE IDAmministratore = :adminId";
        $params = array(':adminId' => $adminId);
    } else {
        echo json_encode(array("error" => "Utente non riconosciuto."));
        return;
    }

    if (!empty($query)) {
        $statement = $db->exec($query, $params);

        if ($statement) {
            $userInfo = $statement[0];
            echo json_encode($userInfo);
        } else {
            echo json_encode(array("error" => "Impossibile recuperare le informazioni dell'utente."));
        }
    }
});


$f3->route('GET /account', function($f3) use ($db) {
    $view = new View();
   echo $view->render("account.php");
});


$f3->route('POST /aziendaPreferita', function($f3) use ($db) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'studente') {
        $studenteId = $_SESSION['studente_id'];
        $aziendaId = $f3->get('POST.azienda_id');
        $aggiungi = $f3->get('POST.aggiungi') === '1'; 
        
        if ($aggiungi) {
            $countQuery = "SELECT COUNT(*) as count FROM PREFERENZA WHERE IDStudente = :studente_id";
            $countResult = $db->exec($countQuery, array(':studente_id' => $studenteId));
            if ($countResult && $countResult[0]['count'] >= 3) {
                http_response_code(400); 
                echo "Hai già raggiunto il limite massimo di aziende preferite (3).";
                exit;
            }
        }

        $insertQuery = $aggiungi ? "INSERT INTO PREFERENZA (IDStudente, IDAzienda) VALUES (:studente_id, :azienda_id)" : "DELETE FROM PREFERENZA WHERE IDStudente = :studente_id AND IDAzienda = :azienda_id";
        $result = $db->exec($insertQuery, array(':studente_id' => $studenteId, ':azienda_id' => $aziendaId));

        $updatePreferenceQuery = "UPDATE aziende SET Preferito = :preferito WHERE IDAzienda = :azienda_id AND EXISTS (SELECT 1 FROM PREFERENZA WHERE IDStudente = :studente_id AND IDAzienda = :azienda_id)";
        $db->exec($updatePreferenceQuery, array(':preferito' => $aggiungi ? 1 : 0, ':studente_id' => $studenteId, ':azienda_id' => $aziendaId));

        if ($result) {
            http_response_code(200);
            echo $aggiungi ? "Azienda aggiunta ai preferiti con successo!" : "Azienda rimossa dai preferiti con successo!";
        } else {
            http_response_code(500);
            echo "Si è verificato un errore durante l'aggiornamento dei preferiti.";
        }
    } else {
        http_response_code(403);
        echo "Non sei autorizzato a eseguire questa operazione.";
    }
});

$f3->route('GET /recensioni', function($f3) use ($db) {
    $azienda_id = $f3->get('GET.azienda_id');

    $recensioni = $db->exec(
        'SELECT * FROM RECENSIONE WHERE IDAzienda = :id_azienda',
        array(':id_azienda' => $azienda_id)
    );
    header("Content-Type: application/json");
    echo json_encode($recensioni);
});

function verificaAziendaRecensita($db, $studente_id, $azienda_id) {
    $query = "SELECT COUNT(*) AS count FROM RECENSIONE WHERE IDStudente = :studente_id AND IDAzienda = :azienda_id";
    $statement = $db->prepare($query);
    $statement->execute(array(':studente_id' => $studente_id, ':azienda_id' => $azienda_id));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    
    return ($row['count'] > 0);
}



$f3->route('POST /scriviRecensione', function($f3) use ($db) {
    $postData = $f3->get('POST');

    $studenteId = $_SESSION['studente_id'];
    $aziendaId = $f3->get('POST.azienda_id');
    $commento = $postData['commento']; 
    $voto = $postData['voto'];

    if (!verificaAssegnazioneAzienda($db, $studenteId, $aziendaId)) {
        echo json_encode(array("message" => "Non sei autorizzato a recensire questa azienda."));
        return;
    }

    if (verificaAziendaRecensita($db, $studenteId, $aziendaId)) {
        echo json_encode(array("message" => "Hai già recensito questa azienda."));
        return;
    }

    if (empty($commento) || empty($voto)) { 
        $f3->error(400, "Tutti i campi sono obbligatori.");
    }
    
    $result = $db->exec(
        'INSERT INTO RECENSIONE (IDStudente, IDAzienda, Commento, Voto) VALUES (:id_studente, :id_azienda, :commento, :voto)',
        array(
            ':id_azienda' => $aziendaId,
            ':id_studente' => $studenteId,
            ':commento' => $commento, 
            ':voto' => $voto
        )
    );

    if ($result) {
        $f3->status(200);
        echo "Recensione inserita con successo.";
    } else {
        $f3->error(500, "Si è verificato un errore durante l'inserimento della recensione.");
    }
});

function verificaAssegnazioneAzienda($db, $studente_id, $azienda_id) {
    $query = "SELECT COUNT(*) AS count FROM ASSEGNA WHERE IDStudente = :studente_id AND IDAzienda = :azienda_id";
    $statement = $db->prepare($query);
    $statement->execute(array(':studente_id' => $studente_id, ':azienda_id' => $azienda_id));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    
    return ($row['count'] > 0);
}






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$f3->route('GET /api/student-login', function($f3) use ($db) {
    // Recupera le credenziali dall'URL
    $email = $f3->get('GET.email');
    $password = $f3->get('GET.password');

    // Effettua la query per verificare le credenziali
    $studentQuery = "SELECT * FROM STUDENTE WHERE Email = :email AND Password = :password";
    $studentResult = $db->exec($studentQuery, array(':email' => $email, ':password' => $password));

    // Verifica se le credenziali sono corrette
    if ($studentResult) {
        // Login studente riuscito
        $response = array('success' => true, 'message' => 'Login studente riuscito');
    } else {
        // Credenziali non valide
        $response = array('success' => false, 'message' => 'Credenziali non valide');
    }

    // Restituisci la risposta come JSON
    header('Content-Type: application/json');
    echo json_encode($response);
});

$f3->route('GET /api/admin-login', function($f3) use ($db) {
    // Recupera le credenziali dall'URL
    $email = $f3->get('GET.email');
    $password = $f3->get('GET.password');

    $adminQuery = "SELECT * FROM AMMINISTRATORE WHERE Email = :email AND Password = :password";
    $adminResult = $db->exec($adminQuery, array(':email' => $email, ':password' => $password));

    if ($adminResult) {
        $response = array('success' => true, 'message' => 'Login studente riuscito');
    } else {
        // Credenziali non valide
        $response = array('success' => false, 'message' => 'Credenziali non valide');
    }

    // Restituisci la risposta come JSON
    header('Content-Type: application/json');
    echo json_encode($response);
});


$f3->route('GET /api/informazioniStudente', function($f3) use ($db) {
    // echo json_encode(["messaggio"  => "riuscito"]); 
    $email = $f3->get('GET.email');

    $userInfoQuery = "SELECT * FROM STUDENTE WHERE Email = :email";
    $userInfoResult = $db->exec($userInfoQuery, array(':email' => $email));

   
        $userInfo = $userInfoResult;

        echo json_encode($userInfo);
   
});

$f3->route('GET /api/informazioniAmministratore', function($f3) use ($db) {
    // echo json_encode(["messaggio"  => "riuscito"]); 
    $email = $f3->get('GET.email');

    $userInfoQuery = "SELECT * FROM AMMINISTRATORE WHERE Email = :email";
    $userInfoResult = $db->exec($userInfoQuery, array(':email' => $email));

   
        $userInfo = $userInfoResult;

        echo json_encode($userInfo);
   
});

$f3->route('GET /p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/studenti', function($f3) use ($db) {
    $aziendeQuery = "SELECT * FROM STUDENTE";
    $aziendeResult = $db->exec($aziendeQuery);
    header("Content-Type","application/json");
    echo json_encode($aziendeResult);
});

function APIstampaAziende($db) {
    // Modifica la query per selezionare tutte le aziende
    $aziendeQuery = "SELECT * FROM AZIENDA";
    $aziendeStatement = $db->prepare($aziendeQuery);
    $aziendeStatement->execute();
    $aziendeResult = $aziendeStatement->fetchAll(PDO::FETCH_ASSOC);
    
    if ($aziendeResult) {
        // header('Content-Type: application/json');
        header("Content-Type","application/json");
        echo json_encode($aziendeResult);
    } else {
        echo "Nessuna azienda trovata.";
    }
}

$f3->route('GET /api/aziende', function($f3) use ($db) {
    APIstampaAziende($db);
});


$f3->route('POST /api/inserisci-diario', function($f3) use ($db) {
    $studente_id = $f3->get('POST.studente_id');
    $giorno = $f3->get('POST.giorno');
    $descrizione = $f3->get('POST.descrizione');
    $ruolo = $f3->get('POST.ruolo');
    $entrataMattino = $f3->get('POST.entrataMattino');
    $uscitaMattino = $f3->get('POST.uscitaMattino');
    $entrataPomeriggio = $f3->get('POST.entrataPomeriggio');
    $uscitaPomeriggio = $f3->get('POST.uscitaPomeriggio');
    
    compilaDiario($db, $studente_id, $giorno, $descrizione, $ruolo, $entrataMattino, $uscitaMattino, $entrataPomeriggio, $uscitaPomeriggio);
    
    echo "Dati del diario salvati con successo.";
});

$f3->route('GET /p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/visualizza-diario', function($f3) use ($db) {

    header('Content-Type: application/json');

    $email = $f3->get('GET.email');

    $studenteQuery = "SELECT IDStudent FROM STUDENTE WHERE Email = :email";
    $studenteResult = $db->exec($studenteQuery, array(':email' => $email));

    if ($studenteResult) {
        $studente_id = $studenteResult[0]['IDStudent'];

        $diarioQuery = "SELECT * FROM DIARIO WHERE IDStudente = :studente_id";
        $diarioResult = $db->exec($diarioQuery, array(':studente_id' => $studente_id));

        if ($diarioResult) {
            // Codifica il risultato del diario in JSON e lo stampa
            echo json_encode($diarioResult);
        } else {
            echo "Nessun diario trovato per lo studente.";
        }
    } else {
        echo "Studente non trovato.";
    }
});

$f3->route('PUT /p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/modifica-diario', function($f3) use ($db) {
    // Ottieni i dati inviati nella richiesta POST
    $requestBody = json_decode($f3->get('BODY'), true);
    $diario_id = $requestBody['diario_id'];
    $descrizione = $requestBody['descrizione'];
    $ruolo = $requestBody['ruolo'];
    $entrataMattino = $requestBody['entrataMattino'];
    $uscitaMattino = $requestBody['uscitaMattino'];
    $entrataPomeriggio = $requestBody['entrataPomeriggio'];
    $uscitaPomeriggio = $requestBody['uscitaPomeriggio'];

    // Esegui l'aggiornamento del diario nel database
    $query = "UPDATE DIARIO SET Descrizione = :descrizione, Ruolo = :ruolo, EntrataMattino = :entrataMattino, UscitaMattino = :uscitaMattino, EntrataPomeriggio = :entrataPomeriggio, UscitaPomeriggio = :uscitaPomeriggio WHERE IDDiario = :diario_id";
    $result = $db->exec($query, array(
        ':descrizione' => $descrizione,
        ':ruolo' => $ruolo,
        ':entrataMattino' => $entrataMattino,
        ':uscitaMattino' => $uscitaMattino,
        ':entrataPomeriggio' => $entrataPomeriggio,
        ':uscitaPomeriggio' => $uscitaPomeriggio,
        ':diario_id' => $diario_id
    ));

    // Verifica se l'aggiornamento è stato eseguito con successo
    if ($result) {
        echo "Diario aggiornato con successo.";
    } else {
        echo "Errore durante l'aggiornamento del diario.";
    }
});


$f3->route('POST /p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/elimina-diario', function($f3) use ($db) {
    // Ottieni l'ID del diario da eliminare dalla richiesta POST
    $diario_id = $f3->get('POST.diario_id');

    // Esegui la query per eliminare la voce diario dal database
    $query = "DELETE FROM DIARIO WHERE IDDiario = :diario_id";
    $result = $db->exec($query, array(':diario_id' => $diario_id));

    // Verifica se l'eliminazione è stata eseguita con successo
    if ($result) {
        echo "Voce diario eliminata con successo.";
    } else {
        echo "Errore durante l'eliminazione della voce diario.";
    }
});




$f3->run();



?>