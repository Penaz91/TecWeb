<?php
        $MESSAGES_IT = [
                /* Messaggi dal DB*/
                "0" => "Prenotazione oltre orario di chiusura.",
                "1" => "Prenotazione prima dell' orario di apertura.",
                "2" => "Sala richiesta occupata da una prenotazione successiva, selezionare una durata minore se si vuole mantenere lo stesso orario.",
                "3" => "Sala richiesta ancora occupata da una prenotazione precedente. Selezionare un orario diverso.",
                "4" => "Selezionata una data di Fine Noleggio antecedente a quella di Inizio Noleggio.",
                "5" => "Selezionata una quantità negativa.",
                "6" => "Quantità richiesta eccede quella disponibile.",
                "7" => "Attenzione! Chiave inglese (Name, Function) già presente.",
                /* Messaggi di OK*/
                "10" => "Noleggio Avvenuto Correttamente",
                "11" => "Prenotazione inserita con successo",
                "12" => "Sala Aggiunta correttamente",
                "13" => "Strumentazione aggiunta con successo",
                "14" => "Sala Modificata correttamente",
                "15" => "Strumentazione modificata con successo",
                "16" => "Modifica dei dati avvenuta correttamente!",
                "17" => "Password cambiata con successo",
                "18" => "Stanza Eliminata con successo",
                "19" => "Strumentazione Eliminata con successo",
                "20" => "Permessi di amministratore modificati con successo",
                "21" => "Utente Eliminato con successo",
                "22" => "Noleggio eliminato con successo",
                "23" => "Prenotazione eliminata con successo",
                "24" => "Caricamento avvenuto con successo, annota il nome del file ed inseriscilo nella schermata di aggiunta strumentazione",
                /* Titles */
                "100" => "Vai al campo Data Inizio (Sorgente dell'errore)",
                "101" => "Vai al campo Data Fine (Sorgente dell'errore)",
                "102" => "Vai al campo Quantità (Sorgente dell'errore)",
                "103" => "Vai al campo Data Inizio (Possibile sorgente dell'errore)",
                "104" => "Vai al campo Nome utente (Possibile sorgente dell'errore)",
                "105" => "Vai al campo Password (Possibile sorgente dell'errore)",
                "106" => "Vai al campo Email (Sorgente dell'errore)",
                "107" => "Vai al campo Telefono (Sorgente dell'errore)",
                "108" => "Vai al campo Nome utente (Sorgente dell'errore)",
                "109" => "Vai al campo di ricerca (Sorgente dell'errore)",
                "110" => "Vai al campo Disponibilità (Sorgente dell'errore)",
                "111" => "Vai al campo Costo (Sorgente dell'errore)",
                "112" => "Vai al campo Nome File (Sorgente dell'errore)",
                "113" => "Vai al campo Password Originale (Sorgente dell'errore)",
                "114" => "Vai al campo Durata (Sorgente dell'errore)",
                "115" => "Vai al campo Ora (Sorgente dell'errore)",
                "116" => "Vai al campo Data (Sorgente dell'errore)",
                "117" => "Vai alla pagina di prenotazione sale",
                "118" => "Vai alla pagina dei noleggi",
                "119" => "Vai alla pagina precedente",
                "120" => "Vai alla pagina successiva",
                /* Messaggi di errore (e pezzi)*/
                "200" => "Data Inizio Noleggio: ",
                "201" => "Data Fine Noleggio: ",
                "202" => "Inserire qualcosa nel campo data",
                "203" => "La data deve essere nel formato gg/mm/aaaa",
                "204" => "La data fa riferimento ad un anno passato",
                "205" => "La data inserita non esiste",
                "206" => "L'ammontare di denaro inserito deve essere un numero positivo",
                "207" => "La quantità inserita deve essere un numero.",
                "208" => "Il nome inserito non sembra essere quello di un file.",
                "209" => "L'ora deve essere nel formato hh:00 (Non sono ammesse mezz'ore)",
                "210" => "L'ora inserita non è valida",
                "211" => "La durata deve essere un intero maggiore o uguale ad 1.",
                "212" => "Ci sono errori nei dati inseriti:",
                "213" => "La data d'inizio noleggio riporta un valore uguale o successivo a quella di fine noleggio.",
                "214" => "Non ci sono abbastanza pezzi disponibili. Sono disponibili solo ",
                "215" => " pezzi.",
                "216" => "Si è verificato un errore durante l'aggiunta della sala.",
                "217" => "Inserimento Strumentazione fallito: ",
                "218" => "Si è verificato un errore durante la Modifica della sala",
                "219" => "Impossibile Modificare la Strumentazione",
                "220" => "Modifica dei dati fallita:",
                "221" => "L'indirizzo email non è nel formato corretto",
                "222" => "Il numero telefonico non è nel formato corretto",
                "223" => "Contattare l'amministratore",
                "224" => "Le due nuove password non corrispondono",
                "225" => "La password originale inserita non è corretta",
                "226" => "Lo username definito esiste già.",
                "227" => "La email definita è già registrata",
                "228" => "Le due password non corrispondono",
                "229" => "I campi password sono vuoti",
                "230" => "Si è verificato un errore:",
                "231" => "Impossibile Eliminare la stanza",
                "232" => "Impossibile Eliminare la strumentazione",
                "233" => "Si è verificato un errore durante la ricerca: ",
                "234" => 'Utente Non Trovato, riprova.',
                "235" => 'Password Errata.',
                "236" => 'Impossibile modificare i permessi di Amministratore, errore interno',
                "237" => "Impossibile eliminare l'utente, errore interno",
                "238" => "Impossibile eliminare il noleggio, errore interno",
                "239" => "Impossibile eliminare la prenotazione, errore interno",
                "240" => "Caricamento del file fallito: errore sconosciuto",
                "241" => "Caricamento rifiutato, il file è più grande di 2MB",
                "242" => "Caricamento rifiutato, file già esistente",
                "243" => "Caricamento rifiutato, gli unici formati accettati sono .png, .jpg e .jpeg",
                /* La caverna di Indy */
                "400" => "Il nostro personale Indiana Jones non ha trovato nulla",
                "401" => "Il nostro personale Indiana Jones ha trovato un Risultato",
                "402" => "Il nostro personale Indiana Jones ha trovato ",
                "403" => " Risultati",
                "404" => "Il Nostro personale Indiana Jones non ha trovato alcuna prenotazione",
                "405" => "Prenota ora una sala!",
                "406" => "Il Nostro personale Indiana Jones non ha trovato alcun noleggio",
                "407" => "Noleggia qualcosa ora!",
                /* Azioni */
                "410" => "Modifica Strumentazione",
                "411" => "Elimina Strumentazione",
                "412" => "Elimina Noleggio",
                "413" => "Elimina Prenotazione",
                "414" => "Elimina Sala",
                "415" => "Modifica Sala",
                "416" => "Elimina Utente",
                "417" => "Rendi Amministratore",
                "418" => "Rimuovi Permessi di Amministratore",
                "419" => "Visualizza Prenotazioni",
                "420" => "Visualizza Noleggi",
                /* Riempimenti tabelle */
                "700" => " Ore",
                "701" => " Giorni",
                "702" => " Ora",
                "703" => " Giorno",
                /* Stringhe speciali */
                "1000" => "Occupato",
                "1001" => "Disponibile",
                "1002" => "Tua Prenotazione",
                "1003" => "Precedente",
                "1004" => "Successiva",
                "1005" => "Pagina ",
                "1006" => " di ",
                "1007" => "Visualizzazione dei prodotti da ",
                "1008" => " a ",
                "1009" => " su un totale di ",
                "1010" => "Visualizzazione del prodotto ",
                "1011" => "Si",
                "1012" => "No"
        ];

        $MESSAGES_EN = [
				 /* Messaggi dal DB*/
                "0" => "Booking after closing time.",
                "1" => "Booking before opening time.",
                "2" => "Requested room occupied by a subsequent booking, select a shorter duration if you want to keep the same booking time.",
                "3" => "Requested room still occupied by a previous booking. Please select a different booking time.",
                "4" => "Selected a Rental End Date previous to the Rental Start Date.",
                "5" => "Selected a negative amount.",
                "6" => "Requested amount exceeds the available amount.",
                "7" => "Warning! English key (Name, Function) already in use.",
                 /* Messaggi di OK*/
                "10" => "Rental Done Correctly",
                "11" => "Booking Successfully Added",
                "12" => "Room Successfully Added",
                "13" => "Instrumentation Successfully Added",
                "14" => "Room has been correctly modified",
                "15" => "Instrumentation has been successfully modified",
                "16" => "Data has been correctly modified!",
                "17" => "Password Successfully Changed",
                "18" => "Room successfully deleted",
                "19" => "Instrumentation successfully deleted",
                "20" => "Administrator privileges successfully modified",
                "21" => "User successfully deleted",
                "22" => "Rental successfully deleted",
                "23" => "Booking successfully deleted",
                "24" => "Upload successfull, write down file's name and insert it in the instrumentation adding page",
                /* Titles */
                "100" => "Go to Start Date field (Error source)",
                "101" => "Go to End Date field (Error source)",
                "102" => "Go to Quantity field (Error source)",
                "103" => "Go to Start Date field (Possible error source)",
                "104" => "Go to username field (Possible error source)",
                "105" => "Go to password field (Possible error source)",
                "106" => "Go to email field (Possible error source)",
                "107" => "Go to phone field (Possible error source)",
                "108" => "Go to username field (Error source)",
                "109" => "Go to search field (Error source)",
                "110" => "Go to availability field (Error source)",
                "111" => "Go to price field (Error source)",
                "112" => "Go to file name field (Error source)",
                "113" => "Go to original password field (Error source)",
                "114" => "Go to duration field (Error source)",
                "115" => "Go to time field (Error source)",
                "116" => "Go to date field (Error source)",
                "117" => "Go to room booking page",
                "118" => "Go to rental page",
                "119" => "Go to previous page",
                "120" => "Go to next page",
                /* Messaggi di errore (e pezzi)*/
                "200" => "Rental Start Date: ",
                "201" => "Rental End Date: ",
                "202" => "Insert something in date field",
                "203" => "Date format has to be dd/mm/yyyy",
                "204" => "The date refers to a past year",
                "205" => "The inserted date doesn't exist",
                "206" => "The sum of money inserted must be a positive number",
                "207" => "The quantity inserted must be a number",
                "208" => "The inserted name doesn't seem to be a file name.",
                "209" => "Time must be in hh:00 (Half hours not admitted)",
                "210" => "Inserted time it's not valid",
                "211" => "Duration must be an integer greater or equal to 1.",
                "212" => "There are errors in inserted data: ",
                "213" => "The start date of the rental is equal or next to the rental end date.",
                "214" => "There aren't enough pieces available. There are only ",
                "215" => " pieces.",
                "216" => "An error occurred during room addition.",
                "217" => "Instrumentation insertion failed: ",
                "218" => "An error occurred during Room editing",
                "219" => "Unable to Modify Instrumentation",
                "220" => "Data modification failed: ",
                "221" => "Email address it's not in the correct format",
                "222" => "Phone number it's not in the correct format",
                "223" => "Contact the administrator",
                "224" => "The two new passwords don't match",
                "225" => "The inserted old password it's not correct",
                "226" => "The username is already taken.",
                "227" => "The chosen email has been already registered",
                "228" => "The two passwords don't match",
                "229" => "Empty password fields",
                "230" => "An error occurred:",
                "231" => "Cannot delete room",
                "232" => "Cannot delete instrumentation",
                "233" => "An error occurred during search: ",
                "234" => 'Cannot find user, retry.',
                "235" => 'Wrong Password.',
                "236" => 'Cannot modify Administrator privileges, internal error',
                "237" => "Cannot delete user, internal error",
                "238" => "Cannot delete rental, internal error",
                "239" => "Cannot delete booking, internal error",
                "240" => "File upload failed: unknown error",
                "241" => "Upload refused, file is bigger than 2MB",
                "242" => "Upload refused, file already exists",
                "243" => "Upload refused, accepted formats are only .png, .jpg and .jpeg",
                /* La caverna di Indy */
                "400" => "Our personal Indiana Jones didn't find anything",
                "401" => "Our personal Indiana Jones found a Result",
                "402" => "Our personal Indiana Jones found ",
                "403" => " Results",
                "404" => "Our personal Indiana Jones didn't find any booking",
                "405" => "Book a room now!",
                "406" => "Our personal Indiana Jones didn't find any rental",
                "407" => "Rent something now!",
                /* Azioni */
                "410" => "Modify Instrumentation",
                "411" => "Delete Instrumentation",
                "412" => "Delete Rental",
                "413" => "Delete Booking",
                "414" => "Delete Room",
                "415" => "Modify Room",
                "416" => "Delete User",
                "417" => "Make Administrator",
                "418" => "Remove Administration Privileges",
                "419" => "View Bookings",
                "420" => "View Rentals",
                /* Riempimenti tabelle*/
                "700" => " hours",
                "701" => " days",
                "702" => " hour",
                "703" => " day",
                /* Stringhe speciali */
                "1000" => "Occupied",
                "1001" => "Available",
                "1002" => "Your Booking",
                "1003" => "Previous",
                "1004" => "Next",
                "1005" => "Page ",
                "1006" => " of ",
                "1007" => "Viewing products from ",
                "1008" => " to ",
                "1009" => " out of ",
                "1010" => "Viewing product ",
                "1011" => "Yes",
                "1012" => "No"
        ];

?>
