import 'dart:developer';

import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';

void main() {
  runApp(MyApp());
}

class UserModel {
  final String email;
  final String password;

  UserModel({required this.email, required this.password});
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => UserProvider(),
      child: MaterialApp(
        debugShowCheckedModeBanner: false,
        title: 'Login App',
        theme: ThemeData(
          primarySwatch: Colors.blue,
        ),
        home: LoginPage(),
      ),
    );
  }
}

class LoginPage extends StatefulWidget {
  @override
  _LoginPageState createState() => _LoginPageState();
}

enum UserType { student, admin }

class _LoginPageState extends State<LoginPage> {
  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController userTypeController = TextEditingController(); // Aggiunto per gestire il tipo di accesso
  String message = '';

  Future<void> login(BuildContext context) async {
    String email = emailController.text;
    String password = passwordController.text;
    UserType userType = userTypeController.text == "admin" ? UserType.admin : UserType.student; // Controlla il tipo di accesso

    String url;
    if (userType == UserType.student) {
      url = 'http://192.168.193.29/P002_ProgettoPCTO/P002_ProgettoPCTOcode/api/student-login?email=$email&password=$password';
    } else {
      // Aggiungi URL per l'accesso degli amministratori
      url = 'http://192.168.193.29/P002_ProgettoPCTO/P002_ProgettoPCTOcode/api/admin-login?email=$email&password=$password';
    }

    var response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      var data = jsonDecode(response.body);

      if (data['success']) {
        var userProvider = Provider.of<UserProvider>(context, listen: false);
        userProvider.setUser(UserModel(email: email, password: password));

        if (userType == UserType.student) {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => HomeScreenState()),
          );
        } else {
          // Redirect alla pagina dell'amministratore
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => AdminHomeScreen()),
          );
        }
      } else {
        setState(() {
          message = 'Credenziali non valide';
        });
      }
    } else {
      setState(() {
        message = 'Errore durante la richiesta';
      });
    }
  }

  @override
 Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text('Login'),
    ),
    body: Padding(
      padding: EdgeInsets.all(20.0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          TextField(
            controller: emailController,
            decoration: InputDecoration(
              labelText: 'Email',
              border: OutlineInputBorder(),
            ),
          ),
          SizedBox(height: 20),
          TextField(
            controller: passwordController,
            decoration: InputDecoration(
              labelText: 'Password',
              border: OutlineInputBorder(),
            ),
            obscureText: true,
          ),
          SizedBox(height: 20),
          TextField(
            controller: userTypeController,
            decoration: InputDecoration(
              labelText: 'Tipo di accesso (student o admin)',
              border: OutlineInputBorder(),
            ),
          ),
          SizedBox(height: 20),
          ElevatedButton(
            onPressed: () => login(context),
            child: Text('Login'),
          ),
          SizedBox(height: 20),
          Text(
            message,
            style: TextStyle(color: Colors.red),
          ),
        ],
      ),
    ),
  );
}
}

class AdminHomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Admin'),
        actions: [
          IconButton(
            icon: Icon(Icons.account_circle),
            onPressed: () async {
              var userProvider = Provider.of<UserProvider>(context, listen: false);
              var user = userProvider.user;
              if (user != null) {
                var response = await http.get(Uri.parse('http://192.168.193.29/P002_ProgettoPCTO/P002_ProgettoPCTOcode/api/informazioniAmministratore?email=${user.email}'));
                if (response.statusCode == 200) {
                  var userData = jsonDecode(response.body)[0];
                  showDialog(
                    context: context,
                    builder: (context) {
                      return AlertDialog(
                        title: Text('Informazioni Account'),
                        content: Column(
                          mainAxisSize: MainAxisSize.min,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('Email: ${userData['Email']}'),
                            Text('Nome: ${userData['Nome']}'),
                            Text('Cognome: ${userData['Cognome']}'),
                            Text('Anno Accademico: ${userData['AnnoAccademico']}'),
                            Text('Classe: ${userData['Classe']}'),
                          ],
                        ),
                        actions: [
                          TextButton(
                            onPressed: () {
                              Navigator.pop(context);
                            },
                            child: Text('Chiudi'),
                          ),
                        ],
                      );
                    },
                  );
                } else {
                  print('Errore durante il recupero delle informazioni utente');
                }
              }
            },
          ),
        ],
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            ElevatedButton(
              onPressed: () async {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => StudentListScreen()),
                );
              },
              child: Text('Visualizza Studenti'),
            ),
            SizedBox(height: 20), // Spazio tra i pulsanti
            ElevatedButton(
              onPressed: () async {
                final response = await http.get(Uri.parse('http://192.168.193.29/P002_ProgettoPCTO/P002_ProgettoPCTOcode/api/aziende'));
                if (response.statusCode == 200) {
                  List<dynamic> companies = jsonDecode(response.body);
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => CompanyListScreen(companies: companies)),
                  );
                } else {
                  // Gestire il caso in cui la richiesta fallisce
                  showDialog(
                    context: context,
                    builder: (context) => AlertDialog(
                      title: Text('Errore'),
                      content: Text('Impossibile caricare l\'elenco delle aziende.'),
                      actions: [
                        TextButton(
                          onPressed: () {
                            Navigator.pop(context);
                          },
                          child: Text('Chiudi'),
                        ),
                      ],
                    ),
                  );
                }
              },
              child: Text('Visualizza Aziende'),
            ),
          ],
        ),
      ),
    );
  }
}


class StudentListScreen extends StatefulWidget {
  @override
  _StudentListScreenState createState() => _StudentListScreenState();
}

class _StudentListScreenState extends State<StudentListScreen> {
  List<dynamic> studenti = [];

  @override
  void initState() {
    super.initState();
    fetchStudenti();
  }

 Future<void> fetchStudenti() async {
  final response = await http.get(Uri.parse('http://192.168.193.29/p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/studenti'));

  if (response.statusCode == 200) {
    setState(() {
      studenti = jsonDecode(response.body);
    });
  } else {
    print('Failed to load studenti: ${response.statusCode}');
  }
}


  @override
 Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Lista degli Studenti'),
      ),
      body: ListView.builder(
        itemCount: studenti.length,
        itemBuilder: (context, index) {
          return ListTile(
            title: Text('${studenti[index]['Nome']} ${studenti[index]['Cognome']}'),
            subtitle: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Classe: ${studenti[index]['Classe']}'),
                Text('Anno Accademico: ${studenti[index]['AnnoAccademico']}'),
                Text('Email: ${studenti[index]['Email']}'),
                Text('Indirizzo: ${studenti[index]['Indirizzo']}'),
                Text('Voto: ${studenti[index]['Voto']}'),
                Text('CAP: ${studenti[index]['CAP']}'),
              ],
            ),
          );
        },
      ),
    );
  }
}

class HomeScreenState extends StatefulWidget{
  const HomeScreenState({super.key});
  
  @override
  HomeScreen createState() => HomeScreen();
  
}


class HomeScreen extends State<HomeScreenState> {
  String infoUtente = "";

  @override
 Widget build(BuildContext context) {
  var userProvider = Provider.of<UserProvider>(context);

  return Scaffold(
    appBar: AppBar(
      title: Text('Student'),
      actions: [
        IconButton(
          icon: Icon(Icons.account_circle),
          onPressed: () async {
            var user = context.read<UserProvider>().user;
            if (user != null) {
              var response = await http.get(Uri.parse('http://192.168.193.29/P002_ProgettoPCTO/P002_ProgettoPCTOcode/api/informazioniStudente?email=${user.email}'));
              if (response.statusCode == 200) {
                var userData = jsonDecode(response.body)[0];
                inspect(userData);

                showDialog(
                  context: context,
                  builder: (context) {
                    return AlertDialog(
                      title: Text('Informazioni Account'),
                      content: Column(
                        mainAxisSize: MainAxisSize.min,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                            Text('Email: ${userData['Email']}'),
                            Text('Nome: ${userData['Nome']}'),
                            Text('Cognome: ${userData['Cognome']}'),
                            Text('Anno Accademico: ${userData['AnnoAccademico']}'),
                            Text('Classe: ${userData['Classe']}'),
                        ],
                      ),
                      actions: [
                        TextButton(
                          onPressed: () {
                            Navigator.pop(context);
                          },
                          child: Text('Chiudi'),
                        ),
                      ],
                    );
                  },
                );
              } else {
                print('Errore durante il recupero delle informazioni utente');
              }
            }
          },
        ),
      ],
    ),
    body: Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Benvenuto, ${userProvider.user!.email}'),
          ElevatedButton(
            onPressed: () async {
              final response = await http.get(Uri.parse('http://192.168.193.29/P002_ProgettoPCTO/P002_ProgettoPCTOcode/api/aziende'));
              if (response.statusCode == 200) {
                List<dynamic> aziende = jsonDecode(response.body);
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => CompanyListScreen(companies: aziende),
                  ),
                );
              } else {
                print('Failed to load companies');
              }
            },
            child: Text('Visualizza Aziende'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => DiarioScreen()),
              );
            },
            child: Text('Diario'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => VisualizzaDiarioScreen(email: userProvider.user!.email),
                ),
              );
            },
            child: Text('Visualizza Diario'),
          ),
        ],
      ),
    ),
  );
}
}



class DiarioScreen extends StatelessWidget {
    final TextEditingController IDStudenteController = TextEditingController();
  final TextEditingController giornoController = TextEditingController();
  final TextEditingController descrizioneController = TextEditingController();
  final TextEditingController ruoloController = TextEditingController();
  final TextEditingController entrataMattinoController = TextEditingController();
  final TextEditingController uscitaMattinoController = TextEditingController();
  final TextEditingController entrataPomeriggioController = TextEditingController();
  final TextEditingController uscitaPomeriggioController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Compila Diario'),
      ),
      body: Padding(
        padding: EdgeInsets.all(20.0),
        child: SingleChildScrollView(
          scrollDirection: Axis.vertical,
          child: Column(
            children: [
              TextField(
                controller: IDStudenteController,
                decoration: InputDecoration(labelText: 'ID'),
                keyboardType: TextInputType.number,
              ),
              TextField(
                controller: giornoController,
                decoration: InputDecoration(labelText: 'Giorno'),
              ),
              TextField(
                controller: descrizioneController,
                decoration: InputDecoration(labelText: 'Descrizione'),
              ),
              TextField(
                controller: ruoloController,
                decoration: InputDecoration(labelText: 'Ruolo'),
              ),
              Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: entrataMattinoController,
                      decoration: InputDecoration(labelText: 'Entrata Mattino'),
                      keyboardType: TextInputType.number,
                    ),
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: TextField(
                      controller: uscitaMattinoController,
                      decoration: InputDecoration(labelText: 'Uscita Mattino'),
                      keyboardType: TextInputType.number,
                    ),
                  ),
                ],
              ),
              Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: entrataPomeriggioController,
                      decoration: InputDecoration(labelText: 'Entrata Pomeriggio'),
                      keyboardType: TextInputType.number,
                    ),
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: TextField(
                      controller: uscitaPomeriggioController,
                      decoration: InputDecoration(labelText: 'Uscita Pomeriggio'),
                      keyboardType: TextInputType.number,
                    ),
                  ),
                ],
              ),
              ElevatedButton(
                onPressed: () {
                  inviaDiario(context);
                },
                child: Text('Invia Diario'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void inviaDiario(BuildContext context) async {
    String ID = IDStudenteController.text;
    String giorno = giornoController.text;
    String descrizione = descrizioneController.text;
    String ruolo = ruoloController.text;
    int entrataMattino = int.tryParse(entrataMattinoController.text) ?? 0;
    int uscitaMattino = int.tryParse(uscitaMattinoController.text) ?? 0;
    int entrataPomeriggio = int.tryParse(entrataPomeriggioController.text) ?? 0;
    int uscitaPomeriggio = int.tryParse(uscitaPomeriggioController.text) ?? 0;

    var url = 'http://192.168.193.29/P002_ProgettoPCTO/P002_ProgettoPCTOcode/api/inserisci-diario';
    var response = await http.post(Uri.parse(url), body: {
      'studente_id': ID, // Sostituisci con il vero valore dello studente_id
      'giorno': giorno,
      'descrizione': descrizione, 
      'ruolo': ruolo,
      'entrataMattino': entrataMattino.toString(),
      'uscitaMattino': uscitaMattino.toString(),
      'entrataPomeriggio': entrataPomeriggio.toString(),
      'uscitaPomeriggio': uscitaPomeriggio.toString(),
    });

    if (response.statusCode == 200) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Diario inviato con successo'),
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Errore durante l\'invio del diario'),
        ),
      );
    }
  }
}

class VisualizzaDiarioScreen extends StatefulWidget {
  final String email;

  VisualizzaDiarioScreen({required this.email});

  @override
  _VisualizzaDiarioScreenState createState() => _VisualizzaDiarioScreenState();
}

class _VisualizzaDiarioScreenState extends State<VisualizzaDiarioScreen> {
  List<Map<String, dynamic>> diario = [];
  String formatTime(String timeString) {
    final parsedTime = DateTime.parse("2024-01-01 " + timeString); // Aggiungi una data arbitraria
    final formattedTime = DateFormat.jm().format(parsedTime); // Formatta l'orario
    return formattedTime;
  }

  @override
  void initState() {
    super.initState();
    _fetchDiario();
  }

  Future<void> _fetchDiario() async {
    String email = widget.email;
    String url = 'http://192.168.193.29/p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/visualizza-diario?email=$email';

    try {
      var response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        setState(() {
          diario = List<Map<String, dynamic>>.from(jsonDecode(response.body));
        });
      } else {
        print('Failed to load diario: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching diario: $e');
    }
  }

void modificaDiario(String diarioIdStr, String nuovaDescrizione, String nuovoRuolo,
      String nuovaEntrataMattino, String nuovaUscitaMattino, String nuovaEntrataPomeriggio, String nuovaUscitaPomeriggio, int index) async {
    try {
      int diarioId = int.parse(diarioIdStr); // Converti la stringa in un intero
      
      // Serializza i dati da inviare nel corpo della richiesta PUT
      var requestBody = jsonEncode({
        'diario_id': diarioId.toString(),
        'descrizione': nuovaDescrizione,
        'ruolo': nuovoRuolo,
        'entrataMattino': nuovaEntrataMattino,
        'uscitaMattino': nuovaUscitaMattino,
        'entrataPomeriggio': nuovaEntrataPomeriggio,
        'uscitaPomeriggio': nuovaUscitaPomeriggio,
      });
      
      // Esegui la richiesta HTTP PUT al server
      var url = 'http://192.168.193.29/p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/modifica-diario';
      var response = await http.put(
        Uri.parse(url),
        headers: {"Content-Type": "application/json"},
        body: requestBody,
      );

      // Analizza la risposta dal server
      if (response.statusCode == 200) {
        print('Diario aggiornato con successo.');
        // Se l'aggiornamento è riuscito, aggiorna lo stato per riflettere i cambiamenti sulla schermata
        setState(() {
          diario[index]['Descrizione'] = nuovaDescrizione;
          diario[index]['Ruolo'] = nuovoRuolo;
          diario[index]['EntrataMattino'] = nuovaEntrataMattino;
          diario[index]['UscitaMattino'] = nuovaUscitaMattino;
          diario[index]['EntrataPomeriggio'] = nuovaEntrataPomeriggio;
          diario[index]['UscitaPomeriggio'] = nuovaUscitaPomeriggio;
        });
      } else {
        print('Errore durante l\'aggiornamento del diario: ${response.statusCode}');
        // Gestisci l'errore e fornisci feedback all'utente
      }
    } catch (e) {
      print('Errore durante la conversione del parametro diarioId: $e');
      // Gestisci l'errore e fornisci feedback all'utente
    }
}




void eliminaDiario(String diarioIdStr) async {
  try {
    int diarioId = int.parse(diarioIdStr); // Converti la stringa in un intero
    // Esegui la richiesta HTTP POST al server per eliminare il diario
    var url = 'http://192.168.193.29/p002_ProgettoPCTO/p002_ProgettoPCTOcode/api/elimina-diario';
    var response = await http.post(Uri.parse(url), body: {
      'diario_id': diarioId.toString(),
    });

    // Analizza la risposta dal server
    if (response.statusCode == 200) {
      print('Voce diario eliminata con successo.');
      // Se l'eliminazione è riuscita, aggiorna lo stato per riflettere i cambiamenti sulla schermata
      setState(() {
        diario.removeWhere((item) => item['IDDiario'] == diarioId);
      });
    } else {
      print('Errore durante l\'eliminazione della voce diario: ${response.statusCode}');
    }
  } catch (e) {
    print('Errore durante la conversione del parametro diarioId: $e');
  }
}


  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Visualizza Diario'),
      ),
      body: diario.isEmpty
          ? Center(
              child: CircularProgressIndicator(),
            )
          : ListView.builder(
            scrollDirection : Axis.vertical,
              itemCount: diario.length,
              itemBuilder: (context, index) {
                final diarioItem = diario[index];
                final diarioId = diarioItem['IDDiario'];

                // Inizializza i controller per i campi di testo con i valori attuali
                TextEditingController descrizioneController = TextEditingController(text: diarioItem['Descrizione']);
                TextEditingController ruoloController = TextEditingController(text: diarioItem['Ruolo']);
                TextEditingController entrataMattinoController = TextEditingController(text: diarioItem['EntrataMattino']);
                TextEditingController uscitaMattinoController = TextEditingController(text: diarioItem['UscitaMattino']);
                TextEditingController entrataPomeriggioController = TextEditingController(text: diarioItem['EntrataPomeriggio']);
                TextEditingController uscitaPomeriggioController = TextEditingController(text: diarioItem['UscitaPomeriggio']);

                return ListTile(
                  title: Text('Giorno: ${diarioItem['Giorno']}'),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      TextField(
                        controller: descrizioneController,
                        onChanged: (value) => diarioItem['Descrizione'] = value,
                        decoration: InputDecoration(labelText: 'Descrizione'),
                      ),
                      TextField(
                        controller: ruoloController,
                        onChanged: (value) => diarioItem['Ruolo'] = value,
                        decoration: InputDecoration(labelText: 'Ruolo'),
                      ),
                      TextField(
                        controller: entrataMattinoController,
                        onChanged: (value) => diarioItem['EntrataMattino'] = value,
                        decoration: InputDecoration(labelText: 'Entrata Mattino'),
                      ),
                      TextField(
                        controller: uscitaMattinoController,
                        onChanged: (value) => diarioItem['UscitaMattino'] = value,
                        decoration: InputDecoration(labelText: 'Uscita Mattino'),
                      ),
                      TextField(
                        controller: entrataPomeriggioController,
                        onChanged: (value) => diarioItem['EntrataPomeriggio'] = value,
                        decoration: InputDecoration(labelText: 'Entrata Pomeriggio'),
                      ),
                      TextField(
                        controller: uscitaPomeriggioController,
                        onChanged: (value) => diarioItem['UscitaPomeriggio'] = value,
                        decoration: InputDecoration(labelText: 'Uscita Pomeriggio'),
                      ),
                    ],
                  ),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                     ElevatedButton(
                        onPressed: () => modificaDiario(
                          diarioId.toString(),
                          descrizioneController.text,
                          ruoloController.text,
                          entrataMattinoController.text,
                          uscitaMattinoController.text,
                          entrataPomeriggioController.text,
                          uscitaPomeriggioController.text,
                          index,
                        ),
                        child: Text('Salva Modifica'),
                      ),
                      SizedBox(width: 8),
                      ElevatedButton(
                        onPressed: () => eliminaDiario(diarioId.toString()),
                        child: Text('Elimina'),
                      ),
                    ],
                  ),
                );
              },
            ),
    );
  }
}


class CompanyListScreen extends StatelessWidget {
  final List<dynamic> companies;

  CompanyListScreen({required this.companies});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Elenco delle Aziende'),
      ),
      body: ListView.builder(
        itemCount: companies.length,
        itemBuilder: (context, index) {
          return ListTile(
            title: Text(companies[index]['Nome']),
            trailing: ElevatedButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => CompanyDetailsScreen(company: companies[index]),
                  ),
                );
              },
              child: Text('Informazioni Azienda'),
            ),
          );
        },
      ),
    );
  }
}

class CompanyDetailsScreen extends StatelessWidget {
  final dynamic company;

  CompanyDetailsScreen({required this.company});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Informazioni Azienda'),
      ),
      body: Padding(
        padding: EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Nome: ${company['Nome']}'),
            Text('Specializzazione: ${company['Specializzazione']}'),
            Text('Descrizione: ${company['Descrizione']}'),
            Text('Indirizzo: ${company['Indirizzo']}'),
            Text('CAP: ${company['CAP']}'),
          ],
        ),
      ),
    );
  }
}


class UserProvider extends ChangeNotifier {
  UserModel? _user;

  UserModel? get user => _user;

  void setUser(UserModel user) {
    _user = user;
    notifyListeners();
  }
}

