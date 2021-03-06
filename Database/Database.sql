DROP TABLE IF EXISTS Utenti;
create table Utenti (
Username varchar(30) primary key,
MailRegistrazione varchar(30) unique not null,
Telefono varchar(11),
Password varchar(15) not null,
Amministratore bool default false,
Attivo bool not null default true
) ENGINE= InnoDB CHARSET= utf8;

DROP TABLE IF EXISTS Strumentazione;
create table Strumentazione (
Nome varchar(30) primary key,
CostoGiornalieroCad int not null,
Descrizione longtext,
Description longtext,
ImgLink varchar(50),
ImgAlt varchar(256),
ImgAltEng varchar(256),
QuantitaMAX int not null default 1
) ENGINE= InnoDB CHARSET= utf8;

DROP TABLE IF EXISTS Sale;
create table Sale (
Nome varchar(30),
Funzione varchar(30),
Name varchar(30) not null,
Function varchar(30) not null,
PrezzoOrario smallint not null,
primary key(Nome, Funzione)
) ENGINE= InnoDB CHARSET= utf8;

DROP TABLE IF EXISTS Noleggio;
create table Noleggio (
Cliente varchar(30), /*NB IDENTIFICAZIONE AVVIENE ATTRAVERSO USERNAME*/
Strumento varchar(30),
DataInizioNoleggio date,
DataFineNoleggio date,
Quantita int not null default 1,
DurataNoleggio smallint default 1, /* Settata con un trigger*/
primary key(Cliente, Strumento, DataInizioNoleggio, DataFineNoleggio),
foreign key(Cliente) references Utenti(Username)
	on update cascade
	on delete cascade,
foreign key(Strumento) references Strumentazione(Nome)
	on update cascade
	on delete no action 
) ENGINE= InnoDB CHARSET= utf8;

DROP TABLE IF EXISTS Prenotazioni;
create table Prenotazioni (
Nominativo varchar(30), /*NB IDENTIFICAZIONE AVVIENE ATTRAVERSO USERNAME*/
SalaPrenotata varchar(30),
ServizioRichiesto varchar(30),
DataPrenotazione date,
OrarioPrenotazione time,
DurataPrenotazione smallint not null default 1, /*ESPRESSA IN ORE*/
primary key(Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione),
foreign key(Nominativo) references Utenti(Username)
	on update cascade
	on delete cascade,
foreign key(SalaPrenotata, ServizioRichiesto) references Sale(Nome, Funzione)
	on update cascade
	on delete cascade
) ENGINE= InnoDB CHARSET= utf8;
