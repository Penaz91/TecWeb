<?php
        class DBAccess {

                const HOST_DB = "localhost";
                //const USERNAME = "id3939012_audiogram";
                const USERNAME = "root";
                //const PASSWORD = "audiogram";
                //const PASSWORD = "Zuperman";
		const PASSWORD = "unapasswordacaso";
                //const DATABASE_NAME = "id3939012_audiogram";
                const DATABASE_NAME = "TecWeb";

                public $connessione;
                public function openDBConnection() {

                        $this->connessione = mysqli_connect(static::HOST_DB, static::USERNAME, static::PASSWORD, static::DATABASE_NAME); //Restituisce un oggetto "connessione" che uso per fare interrogazioni
                        if(!$this->connessione){ //Test di Apertura Connessione
                                return false;
                        }
                        else {
                                return true;
                        }
                }

                public function closeDBConnection(){
                        mysqli_close($this->connessione);
                }

                public function checkUserData($datum, $queryString, $errorString){
                        if ($query = $this->connessione->prepare($queryString)){
                                mysqli_stmt_bind_param($query, "s", $datum);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $result, $attivo);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                if ($result == $datum){
                                        return true;
                                }else{
                                        return false;
                                }
                        }else{
                                die($errorString . ": " . mysqli_error($this->connessione));
                        }
                }

                public function checkUser($username){
                        return self::checkUserData($username, "SELECT Username, Attivo FROM Utenti WHERE Username=? AND Attivo=1", "Errore nell'esecuzione della query di controllo username");
                }

                public function checkMail($email){
                        return self::checkUserData($email,
                                "SELECT MailRegistrazione, Attivo FROM Utenti WHERE MailRegistrazione=? AND Attivo=1",
                                "Errore nell'esecuzione della query di controllo email");
                }

                public function checkLogin($username, $password){
                        if ($query = $this->connessione->prepare("SELECT Username, Password, Amministratore, Attivo FROM Utenti WHERE Username=? AND Password=? AND Attivo=1")){
                                mysqli_stmt_bind_param($query, "ss", $username, $password);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $resUser, $resPass, $resAdmin, $attivo);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                if ($resUser == $username && $resPass == $password){
                                        return 1+$resAdmin;
                                }else{
                                        return 0;
                                }
                        }else{
                                die("Errore nell'esecuzione della query di login: " . mysqli_error($this->connessione));
                        }
                }

                public function addUser($username, $mail, $tel, $pwd){
                        if ($query = $this->connessione->prepare("INSERT INTO Utenti VALUES(?,?,?,?,0,1)")){
                                mysqli_stmt_bind_param($query, "ssss", $username, $mail, $tel, $pwd);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di aggiunta: " . mysqli_error($this->connessione));
                        }
                }

                public function getUserData($datum, $queryString, $errorString){
                        if ($query = $this->connessione->prepare($queryString)){
                                mysqli_stmt_bind_param($query, "s", $datum);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $result, $attivo);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die($errorString . ": " . mysqli_error($this->connessione));
                        }
                }
                public function getMail($username){
                        return self::getUserData($username,
                                "SELECT MailRegistrazione, Attivo from Utenti WHERE Username=? AND Attivo=1",
                                "Errore nell'esecuzione della query di recupero email");
                }

                public function getTelefono($username){
                        return self::getUserData($username,
                                "SELECT Telefono, Attivo from Utenti WHERE Username=? AND Attivo=1",
                                "Errore nell'esecuzione della query di recupero telefono"
                        );
                }

                public function editUserData($username, $email, $tel){
                        if ($query = $this->connessione->prepare("UPDATE Utenti SET MailRegistrazione=?, Telefono=? WHERE Username=? AND Attivo=1")){
                                mysqli_stmt_bind_param($query, "sss", $email, $tel, $username);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di aggornamento: " . mysqli_error($this->connessione));
                        }
                }

                public function checkAvailability($instrument, $datestart, $dateend){
                        if ($query = $this->connessione->prepare("select VerificaDisponibilita(?,?,?)")){
                                mysqli_stmt_bind_param($query, "sss", $instrument, $datestart, $dateend);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $result);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di verifica disponibilita strumento: " . mysqli_error($this->connessione));
                        }
                }

                public function deleteUser($username){
                        if ($query = $this->connessione->prepare("CALL EliminaUtente(?)")){
                                mysqli_stmt_bind_param($query, "s", $username);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di cancellazione utente: " . mysqli_error($this->connessione));
                        }
                }

                public function newBooking($username, $room, $service, $date, $time, $length){
                        if($query = $this->connessione->prepare("CALL NuovaPrenotazione(?,?,?,?,?,?)")){
                                mysqli_stmt_bind_param($query, "sssssi", $username, $room, $service, $date, $time, $length);
                                $result = mysqli_stmt_execute($query);
                                return mysqli_stmt_error($query);
                                mysqli_stmt_close($query);
                        }else{
                                die("Errore nell'esecuzione della query di inserimento nuova prenotazione" . mysqli_error($this->connessione));
                        }
                }

                public function newRental($username, $instrument, $datestart, $dateend, $q){
                        if($query = $this->connessione->prepare("CALL NuovoNoleggio(?,?,?,?,?)")){
                                mysqli_stmt_bind_param($query, "sssss", $username, $instrument, $datestart, $dateend, $q);
                                mysqli_stmt_execute($query);
                                return mysqli_stmt_error($query);
                                mysqli_stmt_close($query);
                        }else{
                                die("Errore nell'esecuzione della query di inserimento nuovo noleggio" . mysqli_error($this->connessione));
                        }
                }

                public function editPassword($username, $newpass){
                        if ($query = $this->connessione->prepare("UPDATE Utenti SET Password=? WHERE Username=?")){
                                mysqli_stmt_bind_param($query, "ss", $newpass, $username);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di cambio password: " . mysqli_error($this->connessione));
                        }
                }

                public function genericUserSearch($datum, $queryString, $errorString, $likeness){
                        if ($query = $this->connessione->prepare($queryString)){
                                $gendatum = $datum;
                                if ($likeness){
                                        $gendatum = "%$datum%";
                                }
                                mysqli_stmt_bind_param($query, "s", $gendatum);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $usercol, $mailcol, $telcol, $ammcol);
                                $result = array("User" => array(), "Mail" => array(), "Tel" => array(), "Amm" => array());
                                while(mysqli_stmt_fetch($query)){
                                        $result['User'][] = $usercol;
                                        $result['Mail'][] = $mailcol;
                                        $result['Tel'][] = $telcol;
                                        $result['Amm'][] = $ammcol;
                                }
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die($errorString . ": " . mysqli_error($this->connessione));
                        }
                }

                public function doUserSearch($username){
                        return self::genericUserSearch($username,
                                "SELECT Username, MailRegistrazione, Telefono, Amministratore FROM Utenti WHERE Username LIKE ? AND Attivo=1",
                                "Errore nell'esecuzione della query di recupero telefono",
                                true);
                }

                public function genericRoomSearch($datum, $queryString, $errorString, $likeness){
                        if ($query = $this->connessione->prepare($queryString)){
                                $gendatum = $datum;
                                if ($likeness){
                                        $gendatum = "%$datum%";
                                }
                                mysqli_stmt_bind_param($query, "s", $gendatum);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $namecol, $funccol, $pricecol);
                                $result = array("Room" => array(), "Func" => array(), "Price" => array());
                                while(mysqli_stmt_fetch($query)){
                                        $result['Room'][] = $namecol;
                                        $result['Func'][] = $funccol;
                                        $result['Price'][] = $pricecol;
                                }
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die($errorString . ": " . mysqli_error($this->connessione));
                        }
                }

                public function doRoomSearch($room){
                        return  self::genericRoomSearch($room,
                                "SELECT Nome, Funzione, PrezzoOrario FROM Sale WHERE Nome LIKE ?",
                                "Errore nell'esecuzione della query di recupero Sale",
                                true);
                }

                public function doRoomSearchFunc($room){
                        return  self::genericRoomSearch($room,
                                "SELECT Nome, Funzione, PrezzoOrario FROM Sale WHERE Funzione LIKE ?",
                                "Errore nell'esecuzione della query di recupero Sale",
                                true);
                }

                public function doRoomSearchCost($cost){
                        return  self::genericRoomSearch($cost,
                                "SELECT Nome, Funzione, PrezzoOrario FROM Sale WHERE PrezzoOrario = ?",
                                "Errore nell'esecuzione della query di recupero Sale",
                                false);
                }

                public function doRoomSearchMinCost($cost){
                        return  self::genericRoomSearch($cost,
                                "SELECT Nome, Funzione, PrezzoOrario FROM Sale WHERE PrezzoOrario >= ?",
                                "Errore nell'esecuzione della query di recupero Sale",
                                false);
                }

                public function doRoomSearchMaxCost($cost){
                        return self::genericRoomSearch($cost,
                                "SELECT Nome, Funzione, PrezzoOrario FROM Sale WHERE PrezzoOrario <= ?",
                                "Errore nell'esecuzione della query di recupero Sale",
                                false);
                }

                public function setAdmin($username, $adminbool){
                        if ($query = $this->connessione->prepare("UPDATE Utenti SET Amministratore=? WHERE Username=?")){
                                mysqli_stmt_bind_param($query, "is", $adminbool, $username);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di aggornamento: " . mysqli_error($this->connessione));
                        }
                }

                public function checkBookings($room, $date){
                        if ($query = $this->connessione->prepare("SELECT Nominativo, SalaPrenotata, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE SalaPrenotata=? AND DataPrenotazione=?")){
                                mysqli_stmt_bind_param($query, "ss", $room, $date);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $namecol, $roomcol, $datecol, $timecol, $durcol);
                                $result = array("Time" => array(), "Status" => array());
                                for ($i = 12; $i <=24; $i++){
                                        $result['Time'][] = $i;
                                        $result['Status'][] = "Disponibile";
                                }
                                while(mysqli_stmt_fetch($query)){
                                        if ($_SESSION['username']==$namecol){$writing = "Tua Prenotazione";}else{$writing = "Occupato";}
                                        $dur = $durcol;
                                        $dt = DateTime::createFromFormat("H:i:s", $timecol);
                                        $index = $dt->format('H') - 12;
                                        while ($dur > 0){
                                                $dur--;
                                                $result['Status'][$index+$dur] = $writing;
                                        }
                                }
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di recupero Prenotazioni: " . mysqli_error($this->connessione));
                        }
                }

                public function getRoomList(){
                        $result = array("Nome" => array(), "Funzione" => array());
                        if ($query = $this->connessione->prepare("SELECT Nome, Funzione FROM Sale")){
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $nome, $funz);
                                while(mysqli_stmt_fetch($query)){
                                        $result['Nome'][] = $nome;
                                        $result['Funzione'][] = $funz;
                                }
                                mysqli_stmt_close($query);
                        }else{
                                die("Errore nell'esecuzione della query di recupero Sale: " . mysqli_error($this->connessione));
                        }
                        return $result;
                }

                public function checkUserBookings($user){
                        if ($query = $this->connessione->prepare("SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE Nominativo=? AND DataPrenotazione >= ?")){
                                $today = Date("Ymd");
                                mysqli_stmt_bind_param($query, "ss", $user, $today);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $namecol, $roomcol, $servicecol, $datecol, $timecol, $durcol);
                                $result = array("Room" => array(), "Service" => array(), "Date" => array(), "Time" => array(), "Duration" => array());
                                while(mysqli_stmt_fetch($query)){
                                        $result['Room'][] = $roomcol;
                                        $result['Service'][] = $servicecol;
                                        $result['Date'][] = $datecol;
                                        $result['Time'][] = $timecol;
                                        $result['Duration'][] = $durcol;
                                }
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di recupero Prenotazioni: " . mysqli_error($this->connessione));
                        }
                }

                public function deleteBooking($username, $sala, $servizio, $data, $ora){
                        if ($query = $this->connessione->prepare("DELETE FROM Prenotazioni WHERE Nominativo=? AND SalaPrenotata=? AND ServizioRichiesto=? AND DataPrenotazione=? AND OrarioPrenotazione=?")){
                                mysqli_stmt_bind_param($query, "sssss", $username, $sala, $servizio, $data, $ora);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di cancellazione prenotazione: " . mysqli_error($this->connessione));
                        }
                }

                public function addRoom($nome, $funzione, $prezzo){
                        if ($query = $this->connessione->prepare("INSERT INTO Sale VALUES (?,?,?)")){
                                mysqli_stmt_bind_param($query, "sss", $nome, $funzione, $prezzo);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di cancellazione prenotazione: " . mysqli_error($this->connessione));
                        }
                }

                public function editRoom($vecchionome, $vecchiafunzione, $nome, $funzione, $prezzo){
                        if ($query = $this->connessione->prepare("UPDATE Sale SET Nome=?, Funzione=?, PrezzoOrario=? WHERE Nome=? AND Funzione=?")){
                                mysqli_stmt_bind_param($query, "sssss", $nome, $funzione, $prezzo, $vecchionome, $vecchiafunzione);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di Modifica Stanza: " . mysqli_error($this->connessione));
                        }
                }

                public function deleteRoom($nome, $funzione){
                        if ($query = $this->connessione->prepare("DELETE FROM Sale WHERE Nome=? AND Funzione=?")){
                                mysqli_stmt_bind_param($query, "ss", $nome, $funzione);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di cancellazione stanza: " . mysqli_error($this->connessione));
                        }
                }

                public function genericBookingSearch($datum, $queryString, $errorString, $likeness){
                        if ($query = $this->connessione->prepare($queryString)){
                                $gdatum = $datum;
                                if ($likeness){
                                        $gdatum= "%$datum%";
                                }
                                mysqli_stmt_bind_param($query, "s", $gdatum);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $namecol, $roomcol, $servicecol, $datecol, $timecol, $durcol);
                                $result = array("Nom" => array(), "Room" => array(), "Func" => array(), "Data" => array(), "Ora" => array(), "Dur" => array());
                                while(mysqli_stmt_fetch($query)){
                                        $result['Nom'][] = $namecol;
                                        $result['Room'][] = $roomcol;
                                        $result['Func'][] = $servicecol;
                                        $result['Data'][] = $datecol;
                                        $result['Ora'][] = $timecol;
                                        $result['Dur'][] = $durcol;
                                }
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die($errorString . ": " . mysqli_error($this->connessione));
                        }
                }

                public function checkBookingsByName($name){
                        return  self::genericBookingSearch($name,
                                "SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE Nominativo LIKE ?",
                                "Errore nell'esecuzione della query di recupero Prenotazioni",
                                true);
                }

                public function checkBookingsByRoom($name){
                        return  self::genericBookingSearch($name,
                                "SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE SalaPrenotata LIKE ?",
                                "Errore nell'esecuzione della query di recupero Prenotazioni",
                                true);
                }

                public function checkBookingsByService($name){
                        return  self::genericBookingSearch($name,
                                "SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE ServizioRichiesto LIKE ?",
                                "Errore nell'esecuzione della query di recupero Prenotazioni",
                                true);
                }

                public function checkBookingsByDate($date){
                        return  self::genericBookingSearch($date,
                                "SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE DataPrenotazione = ?",
                                "Errore nell'esecuzione della query di recupero Prenotazioni",
                                false);
                }

                public function insertInstrument($nome, $costo, $descrizione, $disponib, $imglink, $imgalt){
                        if ($query = $this->connessione->prepare("INSERT INTO Strumentazione VALUES (?,?,?,?,?,?)")){
                                mysqli_stmt_bind_param($query, "sdsssd", $nome, $costo, $descrizione, $imglink, $imgalt, $disponib);
                                mysqli_stmt_execute($query);
                                return mysqli_stmt_error($query);
                                mysqli_stmt_close($query);
                        }else{
                                die("Errore nell'esecuzione della query di inserimento strumentazione: " . mysqli_error($this->connessione));
                        }
                }

                public function genericInstrumentationSearch($datum, $queryString, $errorString, $likeness){
                        if ($query = $this->connessione->prepare($queryString)){
                                $gdatum = $datum;
                                if ($likeness){
                                        $gdatum= "%$datum%";
                                }
                                mysqli_stmt_bind_param($query, "s", $gdatum);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $namecol, $costcol, $desccol, $imgcol, $imgaltcol, $qtycol);
                                $result = array("Nom" => array(), "Cost" => array(), "Desc" => array(), "Img" => array(), "ImgAlt" => array(), "Qty" => array());
                                while(mysqli_stmt_fetch($query)){
                                        $result['Nom'][] = $namecol;
                                        $result['Cost'][] = $costcol;
                                        $result['Desc'][] = $desccol;
                                        $result['Img'][] = $imgcol;
                                        $result['ImgAlt'][] = $imgaltcol;
                                        $result['Qty'][] = $qtycol;
                                }
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die($errorString . ": " . mysqli_error($this->connessione));
                        }
                }

                public function searchInstrumentByName($name){
                        return  self::genericInstrumentationSearch($name,
                                "SELECT * FROM Strumentazione WHERE Nome LIKE ?",
                                "Errore nell'esecuzione della query di recupero Strumentazione",
                                true);
                }

                public function searchInstrumentByNameExact($name){
                        return  self::genericInstrumentationSearch($name,
                                "SELECT * FROM Strumentazione WHERE Nome=?",
                                "Errore nell'esecuzione della query di recupero Strumentazione",
                                false);
                }

                public function searchInstrumentByCost($cost){
                        return  self::genericInstrumentationSearch($cost,
                                "SELECT * FROM Strumentazione WHERE CostoGiornalieroCad=?",
                                "Errore nell'esecuzione della query di recupero Strumentazione",
                                false);
                }

                public function searchInstrumentByCostMinimum($cost){
                        return  self::genericInstrumentationSearch($cost,
                                "SELECT * FROM Strumentazione WHERE CostoGiornalieroCad>=?",
                                "Errore nell'esecuzione della query di recupero Strumentazione",
                                false);
                }

                public function searchInstrumentByCostMaximum($cost){
                        return  self::genericInstrumentationSearch($cost,
                                "SELECT * FROM Strumentazione WHERE CostoGiornalieroCad<=?",
                                "Errore nell'esecuzione della query di recupero Strumentazione",
                                false);
                }

                public function searchInstrumentByStock($num){
                        return  self::genericInstrumentationSearch($cost,
                                "SELECT * FROM Strumentazione WHERE QuantitaMAX=?",
                                "Errore nell'esecuzione della query di recupero Strumentazione",
                                false);
                }

                public function deleteInstrument($nome){
                        if ($query = $this->connessione->prepare("DELETE FROM Strumentazione WHERE Nome=?")){
                                mysqli_stmt_bind_param($query, "s", $nome);
                                $res = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $res;
                        }else{
                                die("Errore nell'esecuzione della query di eliminazione strumentazione: " . mysqli_error($this->connessione));
                        }
                }

                public function editInstrument($vecchionome, $nuovonome, $nuovocosto, $nuovadesc, $nuovadisp, $nuovaimg, $nuovoalt){
                        if ($query = $this->connessione->prepare("UPDATE Strumentazione SET Nome=?, CostoGiornalieroCad=?, Descrizione=?, ImgLink=?, ImgAlt=?, QuantitaMAX=? WHERE Nome=?")){
                                mysqli_stmt_bind_param($query, "sdsssds", $nuovonome, $nuovocosto, $nuovadesc, $nuovaimg, $nuovoalt, $nuovadisp, $vecchionome);
                                $res = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $res;
                        }else{
                                die("Errore nell'esecuzione della query di modifica strumentazione: " . mysqli_error($this->connessione));
                        }
                }

                public function getInstrumentationList(){
                        $result = array("Nome" => array(), "Costo" => array(), "Descr" => array(), "Img" => array(), "ImgAlt" => array(), "Qty" => array());
                        if ($query = $this->connessione->prepare("SELECT * FROM Strumentazione")){
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $nome, $costo, $desc, $img, $imgalt, $qty);
                                while(mysqli_stmt_fetch($query)){
                                        $result['Nome'][] = $nome;
                                        $result['Costo'][] = $costo;
                                        $result['Descr'][] = $desc;
                                        $result['Img'][] = $img;
                                        $result['ImgAlt'][] = $imgalt;
                                        $result['Qty'][] = $qty;
                                }
                                mysqli_stmt_close($query);
                        }else{
                                die("Errore nell'esecuzione della query di recupero Strumentazione: " . mysqli_error($this->connessione));
                        }
                        return $result;
                }

                public function genericRentalSearch($datum, $queryString, $errorString, $likeness){
                        $result = array("Cliente" => array(), "Strum" => array(), "DataInizio" => array(), "DataFine" => array(), "Qty" => array(), "Durata" => array());
                        if ($query = $this->connessione->prepare($queryString)){
                                $gdatum = $datum;
                                if ($likeness){
                                        $gdatum = "%$datum%";
                                }
                                mysqli_stmt_bind_param($query, "s", $gdatum);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $nome, $strum, $dini, $dfin, $qty, $dur);
                                while(mysqli_stmt_fetch($query)){
                                        $result['Cliente'][] = $nome;
                                        $result['Strum'][] = $strum;
                                        $result['DataInizio'][] = $dini;
                                        $result['DataFine'][] = $dfin;
                                        $result['Qty'][] = $qty;
                                        $result['Durata'][] = $dur;
                                }
                                mysqli_stmt_close($query);
                        }else{
                                die($errorString . ": " . mysqli_error($this->connessione));
                        }
                        return $result;
                }

                public function searchInstrumentationBookByName($name){
                        return self::genericRentalSearch($name,
                                "SELECT * FROM Noleggio WHERE Cliente LIKE ?",
                                "Errore nell'esecuzione della query di recupero Noleggi",
                                true);
                }

                public function searchInstrumentationBookByInstrument($name){
                        return self::genericRentalSearch($name,
                                "SELECT * FROM Noleggio WHERE Strumento LIKE ?",
                                "Errore nell'esecuzione della query di recupero Noleggi",
                                true);
                }

                public function searchInstrumentationBookBeganAfter($date){
                        return self::genericRentalSearch($name,
                                "SELECT * FROM Noleggio WHERE DataInizioNoleggio >= ?",
                                "Errore nell'esecuzione della query di recupero Noleggi",
                                false);
                }

                public function searchInstrumentationBookEndedBefore($date){
                        return self::genericRentalSearch($name,
                                "SELECT * FROM Noleggio WHERE DataFineNoleggio<= ?",
                                "Errore nell'esecuzione della query di recupero Noleggi",
                                false);
                }

                public function searchInstrumentationBookByDuration($dur){
                        return self::genericRentalSearch($name,
                                "SELECT * FROM Noleggio WHERE DurataNoleggio = ?",
                                "Errore nell'esecuzione della query di recupero Noleggi",
                                false);
                }

                public function deleteRental($username, $strum, $di, $df){
                        if ($query = $this->connessione->prepare("DELETE FROM Noleggio WHERE Cliente=? AND Strumento=? AND DataInizioNoleggio=? AND DataFineNoleggio=?")){
                                mysqli_stmt_bind_param($query, "ssss", $username, $strum, $di, $df);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di cancellazione noleggio: " . mysqli_error($this->connessione));
                        }
                }
        }
?>
