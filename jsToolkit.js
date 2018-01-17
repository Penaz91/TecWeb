function getSafety(){
        var field = document.getElementById("Rpwd");
        var pwd = field.value;
        var sym = 0;
        var maimin = 0;
        var len = 0;
        var num = 0;
        var indicator = document.getElementById("SecInd");
        if (pwd.match(/[%,$,£,",!,&,/,(,),=,?,',^,@,#,+,-,*,\\,\s]+/)){
                sym=1;
        }
        if (pwd.match(/[a-z]+/) && pwd.match(/[A-Z]+/)){
                maimin = 1;
        }
        if (pwd.match(/\d+/)){
                num = 1;
        }
        if (pwd.length < 6){
                len = 0;
        }else if (pwd.length < 12){
                len = 2;
        }else if (pwd.length < 16){
                len = 4;
        }else{
                len = 7;
        }
        var security = sym+maimin+len+num;
        delete pwd;
        if (security < 5){
                indicator.style.backgroundColor="#FF0000";
                indicator.innerHTML = "Password Non Sicura";
        }else if(security < 7){
                indicator.style.backgroundColor="#FF6600";
                indicator.innerHTML = "Sicurezza Media";
        }else{
                indicator.style.backgroundColor="#00FF00";
                indicator.innerHTML = "Sicurezza Alta";
        }
}

function getCoupling(){
        var div = document.getElementById("CoupInd");
        var pwd1 = document.getElementById("Rpwd");
        var pwd2 = document.getElementById("Rpwd2");
        if (pwd1.value != pwd2.value){
                div.innerHTML = "Le due password non Corrispondono!";
                pwd1.style.backgroundColor = "#FF2222"
                pwd2.style.backgroundColor = "#FF2222"
        }else{
                div.innerHTML = "";
                pwd1.style.backgroundColor = "#FFFFFF"
                pwd2.style.backgroundColor = "#FFFFFF"
        }
}

function checkDateFormat(field, statusdiv){
        var value = document.getElementById(field).value;
        var div = document.getElementById(statusdiv);
        if (!(value.match(/^\d{2}\/\d{2}\/\d{4}$/))){
                div.innerHTML="La data dovrebbe avere formato gg/mm/aaaa";
                field.style.backgroundColor="#FF2222";
        }else{
                div.innerHTML="";
                field.style.backgroundColor="#FFFFFF";
        }
}

function checkHourFormat(field, statusdiv){
        var value = document.getElementById(field).value;
        var div = document.getElementById(statusdiv);
        if (!(value.match(/^\d{2}:0{2}$/))){
                div.innerHTML="L'ora dovrebbe avere formato hh:00. Non sono ammesse mezz'ore.";
                field.style.backgroundColor="#FF2222";
        }else{
                div.innerHTML="";
                field.style.backgroundColor="#FFFFFF";
        }
}

function checkEmailFormat(fieldname, statusdiv){
        var field = document.getElementById(fieldname);
        var value = field.value;
        //var div = document.getElementById(statusdiv);
        if (!(value.match(/^([\w\+\-]+\.?[\w\+\-\.]*)\@([\w\+\-]+)\.([\w\+\-]+)$/))){
                //div.innerHTML="Il formato della email è errato.";
                field.style.backgroundColor="#FF2222";
        }else{
                //div.innerHTML="";
                field.style.backgroundColor="#FFFFFF";
        }
}

function checkPhoneFormat(fieldname, statusdiv){
        var field = document.getElementById(fieldname);
        var value = field.value;
        //var div = document.getElementById(statusdiv);
        if (!(value.match(/^\d{6,11}$/))){
                //div.innerHTML="Il formato del numero di telefono è errato.";
                field.style.backgroundColor="#FF2222";
        }else{
                //div.innerHTML="";
                field.style.backgroundColor="#FFFFFF";
        }
}

function setPlaceholder(fieldname, value){
        var field = document.getElementById(fieldname);
        if (field.value == ""){
                field.value=value;
                field.classList.add("placeholder");
        }
}

function unsetPlaceholder(fieldname){
        var field = document.getElementById(fieldname);
        if (field.classList.contains("placeholder")){
                field.value="";
                field.classList.remove("placeholder");
        }
}

function setNamePH(){
        setPlaceholder("Rusername", "Inserisci il tuo nome utente");
}

function unsetNamePH(){
        unsetPlaceholder("Rusername");
}

function setEmailPH(){
        setPlaceholder("Remail", "Inserisci la tua email")
}

function unsetEmailPH(){
        unsetPlaceholder("Remail");
}

function setTelPH(){
        setPlaceholder("Rtel", "Inserisci il tuo numero telefonico");
}

function unsetTelPH(){
        unsetPlaceholder("Rtel");
}

function toTextBox(fieldid){
        var field = document.getElementById(fieldid);
        field.type="text";
}

function toPasswordBox(fieldid){
        var field = document.getElementById(fieldid);
        field.type="password";
}

function setRegistrationPH(){
        setNamePH();
        setEmailPH();
        setTelPH();
}

function setUserPH(){
        setPlaceholder("username", "Inserisci il tuo nome utente");
}

function unsetUserPH(){
        unsetPlaceholder("username");
}

function setLoginPH(){
        setUserPH();
}

function setDatePH(field){
        var today = new Date();
        var d = today.getDate();
        var m = today.getMonth()+1;
        var y = today.getFullYear();
        d = (d<10 ? '0'+d : d);
        m = (m<10 ? '0'+m : m);
        setPlaceholder(field, "Esempio: " + d + "/" + m + "/" + y);
}

function setBookDatePH(){
        setDatePH("Data");
}

function unsetBookDatePH(){
        unsetPlaceholder("Data");
}

function setUserSearchPH(){
        setPlaceholder("SUserName", "Inserisci il termine di ricerca qui.")
}

function unsetUserSearchPH(){
        unsetPlaceholder("SUserName");
}

function setRoomSearchPH(){
        setPlaceholder("cerca", "Inserisci il termine di ricerca qui.");
}

function unsetRoomSearchPH(){
        unsetPlaceholder("cerca");
}

function setRoomNameAddPH(){
        setPlaceholder("Nome", "Inserisci il nome della nuova sala qui.");
}

function unsetRoomNameAddPH(){
        unsetPlaceholder("Nome");
}

function setRoomServiceAddPH(){
        setPlaceholder("Funzione", "Inserisci il servizio offerto dalla sala qui.");
}

function unsetRoomServiceAddPH(){
        unsetPlaceholder("Funzione");
}

function setRoomPriceAddPH(){
        setPlaceholder("PrezzoOrario", "Inserisci qui il prezzo all'ora della sala. Esempio: 15")
}

function unsetRoomPriceAddPH(){
        unsetPlaceholder("PrezzoOrario");
}

function setAddRoomPH(){
        setRoomPriceAddPH();
        setRoomServiceAddPH();
        setRoomNameAddPH();
}

function setRoomSearchPH_Admin(){
        setPlaceholder("SRoom", "Inserisci qui il termine da cercare.");
}

function unsetRoomSearchPH_Admin(){
        unsetPlaceholder("SRoom");
}

function setTimePH(){
        setPlaceholder("Ora", "Inserisci l'ora da cui far partire la prenotazione. Esempio: 14:00");
}

function unsetTimePH(){
        unsetPlaceholder("Ora");
}

function setDurationPH(){
        setPlaceholder("Durata", "Inserisci la durata (in ore) della prenotazione: Esempio: 2");
}

function unsetDurationPH(){
        unsetPlaceholder("Durata");
}

function setBookPH(){
        setBookDatePH();
        if (document.getElementById("Ora")){
                setTimePH();
                setDurationPH();
        }
}
