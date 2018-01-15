<?php
        class DBAccess {

                const HOST_DB = "localhost";
                //const USERNAME = "id3939012_audiogram";
                const USERNAME = "root";
                //const PASSWORD = "audiogram";
                const PASSWORD = "Zuperman";
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

                public function checkUser($username){
                        if ($query = $this->connessione->prepare("SELECT Username, Attivo FROM Utenti WHERE Username=? AND Attivo=1")){
                                mysqli_stmt_bind_param($query, "s", $username);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $result, $attivo);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                if ($result == $username){
                                        return true;
                                }else{
                                        return false;
                                }
                        }else{
                                die("Errore nell'esecuzione della query di controllo username: " . mysqli_error($this->connessione));
                        }
                }

                public function checkMail($email){
                        if ($query = $this->connessione->prepare("SELECT MailRegistrazione, Attivo FROM Utenti WHERE MailRegistrazione=? AND Attivo=1")){
                                mysqli_stmt_bind_param($query, "s", $email);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $result, $attivo);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                if ($result == $email){
                                        return true;
                                }else{
                                        return false;
                                }
                        }else{
                                die("Errore nell'esecuzione della query di controllo email: " . mysqli_error($this->connessione));
                        }
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

                public function getMail($username){
                        if ($query = $this->connessione->prepare("SELECT MailRegistrazione, Attivo from Utenti WHERE Username=? AND Attivo=1")){
                                mysqli_stmt_bind_param($query, "s", $username);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $result, $attivo);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di recupero email: " . mysqli_error($this->connessione));
                        }
                }

                public function getTelefono($username){
                        if ($query = $this->connessione->prepare("SELECT Telefono, Attivo from Utenti WHERE Username=? AND Attivo=1")){
                                mysqli_stmt_bind_param($query, "s", $username);
                                mysqli_stmt_execute($query);
                                mysqli_stmt_bind_result($query, $result, $attivo);
                                mysqli_stmt_fetch($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di recupero telefono: " . mysqli_error($this->connessione));
                        }
                }

                public function editUserData($username, $email, $tel){
                        if ($query = $this->connessione->prepare("UPDATE Utenti SET MailRegistrazione=?, Telefono=? WHERE Username=? AND Attivo=1")){
                                mysqli_stmt_bind_param($query, "sds", $email, $tel, $username);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
                        }else{
                                die("Errore nell'esecuzione della query di aggornamento: " . mysqli_error($this->connessione));
                        }
                }

                public function checkAvailability($instrument, $datestart, $dateend){
                        if ($query = $this->connessione->prepare("select VerificaDisponilita(?,?,?)")){
                                mysqli_stmt_bind_param($query, "sss", $username, $datestart, $dateend);
                                $result = mysqli_stmt_execute($query);
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

                public function newHire($username, $instrument, $datestart, $dateend){
                        if($query = $this->connessione->prepare("CALL NuovoNoleggio(?,?,?,?)")){
                                mysqli_stmt_bind_param($query, "ssss", $username, $instrument, $datestart, $dateend);
                                $result = mysqli_stmt_execute($query);
                                mysqli_stmt_close($query);
                                return $result;
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

                public function doUserSearch($username){
                        if ($query = $this->connessione->prepare("SELECT Username, MailRegistrazione, Telefono, Amministratore FROM Utenti WHERE Username LIKE ? AND Attivo=1")){
                                $genuser = "%".$username."%";
                                mysqli_stmt_bind_param($query, "s", $genuser);
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
                                die("Errore nell'esecuzione della query di recupero telefono: " . mysqli_error($this->connessione));
                        }
                }

                public function doRoomSearch($room){
                        if ($query = $this->connessione->prepare("SELECT Nome, Funzione, PrezzoOrario FROM Sale WHERE Nome LIKE ?")){
                                $genroom = "%".$room."%";
                                mysqli_stmt_bind_param($query, "s", $genroom);
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
                                die("Errore nell'esecuzione della query di recupero Sale: " . mysqli_error($this->connessione));
                        }
                }

                public function doRoomSearchFunc($room){
                        if ($query = $this->connessione->prepare("SELECT Nome, Funzione, PrezzoOrario FROM Sale WHERE Funzione LIKE ?")){
                                $genroom = "%".$room."%";
                                mysqli_stmt_bind_param($query, "s", $genroom);
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
                                die("Errore nell'esecuzione della query di recupero Sale: " . mysqli_error($this->connessione));
                        }
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
                                        $index = $timecol - 12;
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

                public function checkBookingsByName($name){
                        if ($query = $this->connessione->prepare("SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE Nominativo LIKE ?")){
                                $gname = "%$name%";
                                mysqli_stmt_bind_param($query, "s", $gname);
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
                                die("Errore nell'esecuzione della query di recupero Prenotazioni: " . mysqli_error($this->connessione));
                        }
                }

                public function checkBookingsByRoom($name){
                        if ($query = $this->connessione->prepare("SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE SalaPrenotata LIKE ?")){
                                $gname = "%$name%";
                                mysqli_stmt_bind_param($query, "s", $gname);
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
                                die("Errore nell'esecuzione della query di recupero Prenotazioni: " . mysqli_error($this->connessione));
                        }
                }

                public function checkBookingsByService($name){
                        if ($query = $this->connessione->prepare("SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE ServizioRichiesto LIKE ?")){
                                $gname = "%$name%";
                                mysqli_stmt_bind_param($query, "s", $gname);
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
                                die("Errore nell'esecuzione della query di recupero Prenotazioni: " . mysqli_error($this->connessione));
                        }
                }

                public function checkBookingsByDate($date){
                        if ($query = $this->connessione->prepare("SELECT Nominativo, SalaPrenotata, ServizioRichiesto, DataPrenotazione, OrarioPrenotazione, DurataPrenotazione FROM Prenotazioni WHERE DataPrenotazione = ?")){
                                mysqli_stmt_bind_param($query, "s", $date);
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
                                die("Errore nell'esecuzione della query di recupero Prenotazioni: " . mysqli_error($this->connessione));
                        }
                }
        }
?>
