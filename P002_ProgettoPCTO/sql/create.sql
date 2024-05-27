-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 16, 2024 alle 22:27
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `progettopcto`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `amministratore`
--

CREATE TABLE `amministratore` (
  `IDAmministratore` int(11) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `Cognome` varchar(255) NOT NULL,
  `Classe` varchar(10) NOT NULL,
  `AnnoAccademico` varchar(9) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `amministratore`
--

INSERT INTO `amministratore` (`IDAmministratore`, `Nome`, `Cognome`, `Classe`, `AnnoAccademico`, `Email`, `Password`) VALUES
(5, 'Daniele', 'Dentico', '5IA', '2023/2024', 'daniele.dentico@itiszuccante.edu.it', '555'),
(6, 'Daniele', 'Capellazzo', '5IB', '2023/2024', 'daniele.capellazzo@itiszuccante.edu.it', '111');

-- --------------------------------------------------------

--
-- Struttura della tabella `assegna`
--

CREATE TABLE `assegna` (
  `IDStudente` int(11) NOT NULL,
  `IDAzienda` int(11) NOT NULL,
  `IDAmministratore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `assegna`
--

INSERT INTO `assegna` (`IDStudente`, `IDAzienda`, `IDAmministratore`) VALUES
(1, 5, 5),
(2, 3, 5),
(3, 3, 5),
(5, 1, 5),
(6, 6, 5),
(10, 10, 5),
(22, 9, 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `azienda`
--

CREATE TABLE `azienda` (
  `IDAzienda` int(11) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `Specializzazione` varchar(255) NOT NULL,
  `Descrizione` text NOT NULL,
  `Indirizzo` varchar(255) NOT NULL,
  `CAP` int(11) NOT NULL,
  `Image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `azienda`
--

INSERT INTO `azienda` (`IDAzienda`, `Nome`, `Specializzazione`, `Descrizione`, `Indirizzo`, `CAP`, `Image`) VALUES
(1, 'Tech Solutions', 'Informatica', 'Azienda specializzata nello sviluppo di software e soluzioni informatiche.', 'Via Roma 1', 20121, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(2, 'SysAdmin Expert', 'Sistemistica', 'Azienda che fornisce servizi di gestione e consulenza sistemistica.', 'Via Milano 2', 20122, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(3, 'Telecom Innovations', 'Telecomunicazioni', 'Azienda leader nella progettazione e implementazione di reti di telecomunicazioni.', 'Via Torino 3', 20123, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(4, 'CodeCrafters', 'Informatica', 'Azienda specializzata nello sviluppo di applicazioni web e mobile.', 'Via Napoli 4', 20124, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(5, 'NetWork Wizards', 'Sistemistica', 'Azienda che offre servizi di configurazione e gestione delle reti informatiche.', 'Via Firenze 5', 20125, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(6, 'Digital Communications', 'Telecomunicazioni', 'Azienda che fornisce soluzioni avanzate per la comunicazione digitale.', 'Via Venezia 6', 20126, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(7, 'CloudTech Solutions', 'Informatica', 'Azienda specializzata nella implementazione di soluzioni cloud computing.', 'Via Bologna 7', 20127, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(8, 'Security Experts', 'Sistemistica', 'Azienda leader nella sicurezza informatica e nella protezione dei dati.', 'Via Genova 8', 20128, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(9, 'Wireless Innovators', 'Telecomunicazioni', 'Azienda specializzata nello sviluppo di tecnologie wireless avanzate.', 'Via Palermo 9', 20129, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg'),
(10, 'Data Analytics Ltd.', 'Informatica', 'Azienda che fornisce servizi di analisi dati e business intelligence.', 'Via Padova 10', 20130, 'https://www.fgsdistribuzione.it/assets/img/slider/bg-azienda.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `classe`
--

CREATE TABLE `classe` (
  `Classe` varchar(10) NOT NULL,
  `AnnoAccademico` varchar(9) NOT NULL,
  `Indirizzo` varchar(255) NOT NULL,
  `PeriodoStage` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `classe`
--

INSERT INTO `classe` (`Classe`, `AnnoAccademico`, `Indirizzo`, `PeriodoStage`) VALUES
('4AA', '2023/2024', 'Automazione', ''),
('4AB', '2023/2024', 'Automazione', ''),
('4EA', '2023/2024', 'Elettronica', ''),
('4IA', '2023/2024', 'Informatica', ''),
('4IB', '2023/2024', 'Informatica', ''),
('4IC', '2023/2024', 'Informatica', ''),
('4TA', '2023/2024', 'Telecomunicazioni', ''),
('5AA', '2023/2024', 'Automazione', ''),
('5AB', '2023/2024', 'Automazione', ''),
('5EA', '2023/2024', 'Elettronica', ''),
('5IA', '2023/2024', 'Informatica', ''),
('5IB', '2023/2024', 'Informatica', ''),
('5IC', '2023/2024', 'Informatica', ''),
('5TA', '2023/2024', 'Telecomunicazioni', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `diario`
--

CREATE TABLE `diario` (
  `IDDiario` int(11) NOT NULL,
  `IDStudente` int(11) DEFAULT NULL,
  `Giorno` date NOT NULL,
  `Descrizione` text NOT NULL,
  `EntrataMattino` time NOT NULL,
  `UscitaMattino` time NOT NULL,
  `EntrataPomeriggio` time NOT NULL,
  `UscitaPomeriggio` time NOT NULL,
  `Ruolo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `diario`
--

INSERT INTO `diario` (`IDDiario`, `IDStudente`, `Giorno`, `Descrizione`, `EntrataMattino`, `UscitaMattino`, `EntrataPomeriggio`, `UscitaPomeriggio`, `Ruolo`) VALUES
(9, 14, '2024-04-23', 'iuwe hròiquvwy òiubtv ', '08:00:00', '12:00:00', '14:00:00', '18:00:00', 'Assistente'),
(14, 6, '2024-05-14', 'fe', '10:00:11', '00:00:11', '00:00:11', '00:00:11', 'er'),
(16, 6, '2024-05-09', 'bella giornata ', '00:00:10', '00:00:14', '00:00:15', '00:00:21', 'assistenza '),
(18, 6, '2024-05-16', 'wgw ', '04:04:00', '04:04:00', '04:44:00', '04:04:00', 'w ew'),
(19, 20, '2024-05-14', 'aaaa', '00:00:00', '00:00:00', '00:10:00', '00:00:00', 'assistenza '),
(20, 20, '0000-00-00', '', '11:11:11', '00:00:00', '00:00:00', '00:00:00', ''),
(23, 22, '2024-05-17', 'hhd', '00:01:11', '00:01:11', '00:04:11', '00:01:11', 'jiur'),
(25, 6, '2024-05-31', 'fw', '03:01:00', '12:12:00', '02:01:00', '12:03:00', 'efew');

-- --------------------------------------------------------

--
-- Struttura della tabella `preferenza`
--

CREATE TABLE `preferenza` (
  `IDStudente` int(11) NOT NULL,
  `IDAzienda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `preferenza`
--

INSERT INTO `preferenza` (`IDStudente`, `IDAzienda`) VALUES
(6, 2),
(6, 3),
(6, 9),
(12, 1),
(12, 3),
(19, 3),
(19, 4),
(19, 9);

-- --------------------------------------------------------

--
-- Struttura della tabella `recensione`
--

CREATE TABLE `recensione` (
  `IDRecensione` int(11) NOT NULL,
  `IDStudente` int(11) DEFAULT NULL,
  `IDAzienda` int(11) DEFAULT NULL,
  `Voto` int(11) NOT NULL,
  `Commento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `recensione`
--

INSERT INTO `recensione` (`IDRecensione`, `IDStudente`, `IDAzienda`, `Voto`, `Commento`) VALUES
(6, 1, 10, 3, 'fff'),
(7, 1, 1, 1, 'thr'),
(9, 12, 1, 2, 'ee'),
(10, 1, 3, 4, 'ewdw'),
(43, 6, 6, 4, 'effs ');

-- --------------------------------------------------------

--
-- Struttura della tabella `studente`
--

CREATE TABLE `studente` (
  `IDStudent` int(11) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `Cognome` varchar(255) NOT NULL,
  `Classe` varchar(10) NOT NULL,
  `AnnoAccademico` varchar(9) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Indirizzo` varchar(255) NOT NULL,
  `Voto` int(5) NOT NULL,
  `CAP` int(11) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `studente`
--

INSERT INTO `studente` (`IDStudent`, `Nome`, `Cognome`, `Classe`, `AnnoAccademico`, `Email`, `Indirizzo`, `Voto`, `CAP`, `Password`) VALUES
(1, 'Alexandru Teodor', 'Baidoc', '5IA', '2023/2024', 'alexandru.baidoc@itiszuccante.edu.it', 'Via Tevere 33', 7, 30173, 'password123'),
(2, 'Luca', 'Busetto', '5IA', '2023/2024', 'luca.busetto@itiszuccante.edu.it', 'via Camporese 73', 7, 30173, 'password123'),
(3, 'Alex Yi Seng', 'Chen', '5IA', '2023/2024', 'alex.chen@itiszuccante.edu.it', 'Via Trieste 140A', 7, 30175, 'password123'),
(4, 'Alessandro', 'Corlianò', '5IA', '2023/2024', 'alessandro.corliano@itiszuccante.edu.it', 'via Baglioni 33', 8, 30173, 'password123'),
(5, 'Riccardo', 'Costantini', '5IA', '2023/2024', 'riccardo.costantini@itiszuccante.edu.it', 'via Ludovico Ariosto 6', 0, 30038, 'password123'),
(6, 'Diego', 'De nunzio', '5IA', '2023/2024', 'diego.denunzio@itiszuccante.edu.it', 'via rielta 22D', 10, 30174, 'password123'),
(7, 'Michele', 'Di serio', '5IA', '2023/2024', 'michele.diserio@itiszuccante.edu.it', 'via emo 9', 0, 30173, 'password123'),
(8, 'Giacomo', 'Esposito', '5IA', '2023/2024', 'giacomo.esposito@itiszuccante.edu.it', 'via Lissa 13', 0, 30174, 'password123'),
(9, 'Francesco', 'Fassino', '5IA', '2023/2024', 'francesco.fassino@itiszuccante.edu.it', 'via correnti 19', 3, 30175, 'password123'),
(10, 'Marco', 'Isandelli', '5IA', '2023/2024', 'marco.isandelli@itiszuccante.edu.it', 'via Antonio de Curtis 59B', 0, 30038, 'password123'),
(11, 'Massimiliano', 'Marangon', '5IA', '2023/2024', 'massimiliano.marangon@itiszuccante.edu.it', 'via Cà lin 51', 0, 30174, 'password123'),
(12, 'Matteo', 'Piazzon', '5IA', '2023/2024', 'matteo.piazzon@itiszuccante.edu.it', 'via baracca 82', 6, 30173, 'password123'),
(13, 'Agnese', 'Ponga', '5IA', '2023/2024', 'agnese.ponga@itiszuccante.edu.it', 'via don ballan 11', 0, 30020, 'password123'),
(14, 'Simone', 'Riggio', '5IA', '2023/2024', 'simone.riggio@itiszuccante.edu.it', 'via Baglioni 37', 0, 30173, 'password123'),
(15, 'Filippo', 'Schierato', '5IA', '2023/2024', 'filippo.schierato@itiszuccante.edu.it', 'via Pietro mascani 22D', 0, 30037, 'password123'),
(16, 'Giulio', 'Semenzato', '5IA', '2023/2024', 'giulio.semenzato@itiszuccante.edu.it', 'via frusta 155', 0, 30030, 'password123'),
(17, 'Carlotta', 'Serena', '5IA', '2023/2024', 'carlotta.serena@itiszuccante.edu.it', 'via monte Berico 21/A', 0, 30020, 'password123'),
(18, 'Andrea', 'Sponchiado', '5IA', '2023/2024', 'andrea.sponchiado@itiszuccante.edu.it', 'via Adige 11/5', 10, 30020, 'password123'),
(19, 'Matteo', 'Valerii', '5IA', '2023/2024', 'matteo.valerii@itiszuccante.edu.it', 'via vettor pisani 50', 0, 30173, 'password123'),
(20, 'Andrea', 'Verdicchio', '5IA', '2023/2024', 'andrea.verdicchio@itiszuccante.edu.it', 'corso del popolo 12', 5, 30173, 'password123'),
(21, 'Andrea', 'Vernole', '5IA', '2023/2024', 'andrea.vernole@itiszuccante.edu.it', 'via bissuola 49', 6, 30173, 'password123'),
(22, 'Davide', 'Yeh', '5IA', '2023/2024', 'davide.yeh@itiszuccante.edu.it', 'via Oslavia 8', 11, 30171, 'password123');

-- --------------------------------------------------------

--
-- Struttura della tabella `tutor`
--

CREATE TABLE `tutor` (
  `IDTutor` int(11) NOT NULL,
  `IDAzienda` int(11) DEFAULT NULL,
  `Nome` varchar(255) NOT NULL,
  `Cognome` varchar(255) NOT NULL,
  `NumeroTelefono` varchar(10) NOT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tutor`
--

INSERT INTO `tutor` (`IDTutor`, `IDAzienda`, `Nome`, `Cognome`, `NumeroTelefono`, `Email`) VALUES
(1, 1, 'Mario', 'Rossi', '1234567890', 'mario.rossi@example.com'),
(2, 2, 'Giulia', 'Bianchi', '2345678901', 'giulia.bianchi@example.com'),
(3, 3, 'Luca', 'Verdi', '3456789012', 'luca.verdi@example.com'),
(4, 4, 'Anna', 'Neri', '4567890123', 'anna.neri@example.com'),
(5, 5, 'Marco', 'Giallo', '5678901234', 'marco.giallo@example.com'),
(6, 6, 'Laura', 'Rosa', '6789012345', 'laura.rosa@example.com'),
(7, 7, 'Paolo', 'Arancio', '7890123456', 'paolo.arancio@example.com'),
(8, 8, 'Sara', 'Viola', '8901234567', 'sara.viola@example.com'),
(9, 9, 'Giovanni', 'Celeste', '9012345678', 'giovanni.celeste@example.com'),
(10, 10, 'Roberta', 'Azzurro', '0123456789', 'roberta.azzurro@example.com');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `amministratore`
--
ALTER TABLE `amministratore`
  ADD PRIMARY KEY (`IDAmministratore`),
  ADD KEY `Classe` (`Classe`,`AnnoAccademico`);

--
-- Indici per le tabelle `assegna`
--
ALTER TABLE `assegna`
  ADD PRIMARY KEY (`IDStudente`,`IDAzienda`,`IDAmministratore`),
  ADD KEY `IDAzienda` (`IDAzienda`),
  ADD KEY `IDAmministratore` (`IDAmministratore`);

--
-- Indici per le tabelle `azienda`
--
ALTER TABLE `azienda`
  ADD PRIMARY KEY (`IDAzienda`);

--
-- Indici per le tabelle `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`Classe`,`AnnoAccademico`);

--
-- Indici per le tabelle `diario`
--
ALTER TABLE `diario`
  ADD PRIMARY KEY (`IDDiario`),
  ADD UNIQUE KEY `unique_diary_entry` (`IDStudente`,`Giorno`);

--
-- Indici per le tabelle `preferenza`
--
ALTER TABLE `preferenza`
  ADD PRIMARY KEY (`IDStudente`,`IDAzienda`),
  ADD KEY `IDAzienda` (`IDAzienda`);

--
-- Indici per le tabelle `recensione`
--
ALTER TABLE `recensione`
  ADD PRIMARY KEY (`IDRecensione`),
  ADD KEY `IDStudente` (`IDStudente`),
  ADD KEY `IDAzienda` (`IDAzienda`);

--
-- Indici per le tabelle `studente`
--
ALTER TABLE `studente`
  ADD PRIMARY KEY (`IDStudent`),
  ADD KEY `Classe` (`Classe`,`AnnoAccademico`);

--
-- Indici per le tabelle `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`IDTutor`),
  ADD KEY `IDAzienda` (`IDAzienda`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `amministratore`
--
ALTER TABLE `amministratore`
  MODIFY `IDAmministratore` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `azienda`
--
ALTER TABLE `azienda`
  MODIFY `IDAzienda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `diario`
--
ALTER TABLE `diario`
  MODIFY `IDDiario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT per la tabella `recensione`
--
ALTER TABLE `recensione`
  MODIFY `IDRecensione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT per la tabella `studente`
--
ALTER TABLE `studente`
  MODIFY `IDStudent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT per la tabella `tutor`
--
ALTER TABLE `tutor`
  MODIFY `IDTutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `amministratore`
--
ALTER TABLE `amministratore`
  ADD CONSTRAINT `amministratore_ibfk_1` FOREIGN KEY (`Classe`,`AnnoAccademico`) REFERENCES `classe` (`Classe`, `AnnoAccademico`);

--
-- Limiti per la tabella `assegna`
--
ALTER TABLE `assegna`
  ADD CONSTRAINT `assegna_ibfk_1` FOREIGN KEY (`IDStudente`) REFERENCES `studente` (`IDStudent`),
  ADD CONSTRAINT `assegna_ibfk_2` FOREIGN KEY (`IDAzienda`) REFERENCES `azienda` (`IDAzienda`),
  ADD CONSTRAINT `assegna_ibfk_3` FOREIGN KEY (`IDAmministratore`) REFERENCES `amministratore` (`IDAmministratore`);

--
-- Limiti per la tabella `diario`
--
ALTER TABLE `diario`
  ADD CONSTRAINT `diario_ibfk_1` FOREIGN KEY (`IDStudente`) REFERENCES `studente` (`IDStudent`);

--
-- Limiti per la tabella `preferenza`
--
ALTER TABLE `preferenza`
  ADD CONSTRAINT `preferenza_ibfk_1` FOREIGN KEY (`IDStudente`) REFERENCES `studente` (`IDStudent`),
  ADD CONSTRAINT `preferenza_ibfk_2` FOREIGN KEY (`IDAzienda`) REFERENCES `azienda` (`IDAzienda`);

--
-- Limiti per la tabella `recensione`
--
ALTER TABLE `recensione`
  ADD CONSTRAINT `recensione_ibfk_1` FOREIGN KEY (`IDStudente`) REFERENCES `studente` (`IDStudent`),
  ADD CONSTRAINT `recensione_ibfk_2` FOREIGN KEY (`IDAzienda`) REFERENCES `azienda` (`IDAzienda`);

--
-- Limiti per la tabella `studente`
--
ALTER TABLE `studente`
  ADD CONSTRAINT `studente_ibfk_1` FOREIGN KEY (`Classe`,`AnnoAccademico`) REFERENCES `classe` (`Classe`, `AnnoAccademico`);

--
-- Limiti per la tabella `tutor`
--
ALTER TABLE `tutor`
  ADD CONSTRAINT `tutor_ibfk_1` FOREIGN KEY (`IDAzienda`) REFERENCES `azienda` (`IDAzienda`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
