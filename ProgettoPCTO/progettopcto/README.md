# progettopcto


# SERVER
Il server segue una combinazione di operazioni CRUD (Create, Read, Update, Delete) e altre operazioni.

    Create (C):
        L'endpoint /api/inserisci-diario supporta l'inserimento di nuove voci diario nel database tramite richieste POST.

    Read (R):
        Gli endpoint /api/student-login e /api/admin-login consentono di recuperare informazioni dagli studenti e dagli amministratori rispettivamente.
        Gli endpoint /api/informazioniStudente e /api/informazioniAmministratore permettono di ottenere informazioni dettagliate sugli studenti e gli amministratori.
        Gli endpoint /p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/studenti e /api/aziende restituiscono elenchi di studenti e aziende rispettivamente.

    Update (U):
        L'endpoint /p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/modifica-diario consente di aggiornare voci diario esistenti nel database tramite richieste POST.

    Delete (D):
        L'endpoint /p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/elimina-diario consente di eliminare voci diario dal database tramite richieste POST.


# FLUTTER
README - Applicazione di Login e Gestione di Diario
Descrizione

Questo progetto è un'applicazione mobile sviluppata in Flutter per gestire il login degli utenti e la compilazione di un diario. L'applicazione consente agli utenti di accedere al sistema, visualizzare informazioni pertinenti e compilare un diario delle attività giornaliere.
Funzionalità Principali

    Login Utenti: Gli utenti possono accedere al sistema inserendo le proprie credenziali (email, password) e specificando il tipo di accesso (studente o amministratore).
    Navigazione Differenziata: Dopo il login, gli utenti vengono reindirizzati a schermate diverse in base al tipo di accesso (studente o amministratore).
    Visualizzazione Informazioni Utente: Gli utenti possono visualizzare le proprie informazioni personali come nome, cognome, anno accademico e classe.
    Gestione Diario: Gli utenti possono compilare, visualizzare, modificare ed eliminare le voci del diario contenenti informazioni riguardanti le attività svolte.

Componenti Principali

    LoginPage: Schermata per l'inserimento delle credenziali di accesso.
    HomeScreenState: Schermata principale dopo il login, visualizza informazioni di benvenuto e fornisce accesso alle funzionalità dell'applicazione.
    DiarioScreen: Schermata per la compilazione del diario giornaliero.
    VisualizzaDiarioScreen: Schermata per la visualizzazione e la gestione delle voci del diario.
    UserProvider: Provider per la gestione dello stato degli utenti.

Tecnologie Utilizzate

    Flutter: Framework per lo sviluppo di applicazioni mobili multi-piattaforma.
    Dart: Linguaggio di programmazione utilizzato per lo sviluppo dell'applicazione.
    HTTP: Pacchetto per effettuare richieste HTTP al server.
    Provider: Libreria per la gestione dello stato dell'applicazione.

Configurazione e Utilizzo

    Assicurati di avere Flutter e Dart installati sul tuo sistema.
    Clona il repository del progetto sul tuo computer.
    Esegui il comando flutter pub get per installare le dipendenze del progetto.
    Configura l'URL del server all'interno del codice sorgente, dove vengono effettuate le richieste HTTP.
    Avvia l'applicazione utilizzando il comando flutter run e segui le istruzioni per installarla sul dispositivo o utilizzarla in modalità di debug.


# RICHIESTE
    Richiesta di Login:
        Descrizione: Quando un utente inserisce le proprie credenziali (email e password) e preme il pulsante di login, viene effettuata una richiesta HTTP al server per verificare l'autenticità delle credenziali e ottenere l'accesso.
        Metodo: GET (per studenti) o POST (per amministratori) a seconda del tipo di accesso specificato dall'utente.
        Endpoint: L'endpoint varia in base al tipo di accesso:
            Per gli studenti: /api/student-login
            Per gli amministratori: /api/admin-login
        Parametri: Le credenziali dell'utente (email e password) vengono passate come parametri nell'URL della richiesta.

    Visualizzazione Informazioni Utente:
        Descrizione: Dopo il login, l'utente può visualizzare le proprie informazioni personali come nome, cognome, anno accademico e classe.
        Metodo: GET.
        Endpoint: /api/informazioniStudente (per gli studenti) o /api/informazioniAmministratore (per gli amministratori).
        Parametri: L'email dell'utente viene passata come parametro nell'URL per identificare l'utente di cui visualizzare le informazioni.

    Compilazione del Diario:
        Descrizione: Quando un utente compila un nuovo diario giornaliero con informazioni come la descrizione dell'attività, il ruolo, gli orari di entrata e uscita, viene effettuata una richiesta HTTP per inviare tali informazioni al server.
        Metodo: POST.
        Endpoint: /api/inserisci-diario.
        Parametri: I dettagli del diario, come l'ID dello studente, la data, la descrizione, il ruolo e gli orari di entrata e uscita, vengono passati come corpo della richiesta.

    Visualizzazione e Gestione del Diario:
        Descrizione: Gli utenti possono visualizzare, modificare ed eliminare le voci del diario.
        Metodo: GET per ottenere i dati del diario, PUT per aggiornare i dati del diario, POST per eliminare una voce del diario.
        Endpoint:
            Visualizzazione del diario: /api/visualizza-diario.
            Aggiornamento del diario: /api/modifica-diario.
            Eliminazione del diario: /api/elimina-diario.
        Parametri: Per l'aggiornamento e l'eliminazione del diario, l'ID della voce del diario viene passato come parametro.