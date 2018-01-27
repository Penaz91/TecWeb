const SYMBOL_REGEX = /[%,$,£,",!,&,/,(,),=,?,',^,@,#,+,-,*,\\,\s]+/;
const LOWERCASE_REGEX = /[a-z]+/;
const UPPERCASE_REGEX = /[A-Z]+/;
const DIGITS_REGEX = /\d+/;
const DATE_REGEX = /^\d{2}\/\d{2}\/\d{4}$/;
const TIME_REGEX = /^\d{2}:0{2}$/;
const EMAIL_REGEX = /^([\w\+\-]+\.?[\w\+\-\.]*)\@([\w\+\-]+)\.([\w\+\-]+)$/;
const PHONE_REGEX = /^\d{6,11}$/;

const PHMAP = new Map(
        [
                ["Rusername", "Inserisci il tuo nome utente"],
                ["Remail", "Inserisci la tua email"],
                ["Rtel", "Inserisci il tuo numero telefonico"],
                ["username", "Inserisci il tuo nome utente"]
        ]
)


function getSafety(fieldname){
        var field = document.getElementById(fieldname);
        var pwd = field.value;
        var sym = 0;
        var maimin = 0;
        var len = 0;
        var num = 0;
        var indicator = document.getElementById("SecInd");
        if (pwd.match(SYMBOL_REGEX)){
                sym=1;
        }
        if (pwd.match(LOWERCASE_REGEX) && pwd.match(UPPERCASE_REGEX)){
                maimin = 1;
        }
        if (pwd.match(DIGITS_REGEX)){
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
                indicator.classList.add("unsafepwd");
                indicator.classList.remove("avgsafepwd");
                indicator.classList.remove("safepwd");
                indicator.innerHTML = "Password Non Sicura";
        }else if(security < 7){
                indicator.classList.remove("unsafepwd");
                indicator.classList.remove("safepwd");
                indicator.classList.add("avgsafepwd");
                indicator.innerHTML = "Sicurezza Media";
        }else{
                indicator.classList.remove("unsafepwd");
                indicator.classList.remove("avgsafepwd");
                indicator.classList.add("safepwd");
                indicator.innerHTML = "Sicurezza Alta";
        }
}

function getCoupling(field1, field2){
        var div = document.getElementById("CoupInd");
        var pwd1 = document.getElementById(field1);
        var pwd2 = document.getElementById(field2);
        if (pwd1.value != pwd2.value){
                div.innerHTML = "Le due password non Corrispondono!";
                pwd1.classList.add("wrong");
                pwd2.classList.add("wrong");
        }else{
                div.innerHTML = "";
                pwd1.classList.remove("wrong");
                pwd2.classList.remove("wrong");
        }
}

function checkDateFormat(field, statusdiv){
        var value = document.getElementById(field).value;
        var div = document.getElementById(statusdiv);
        if (!(value.match(DATE_REGEX))){
                div.innerHTML="La data dovrebbe avere formato gg/mm/aaaa";
                field.classList.add("wrong");
        }else{
                div.innerHTML="";
                field.classList.remove("wrong");
        }
}

function checkHourFormat(field, statusdiv){
        var value = document.getElementById(field).value;
        var div = document.getElementById(statusdiv);
        if (!(value.match(TIME_REGEX))){
                div.innerHTML="L'ora dovrebbe avere formato hh:00. Non sono ammesse mezz'ore.";
                field.classList.add("wrong");
        }else{
                div.innerHTML="";
                field.classList.remove("wrong");
        }
}

function checkEmailFormat(fieldname, statusdiv){
        var field = document.getElementById(fieldname);
        var value = field.value;
        if (!(value.match(EMAIL_REGEX))){
                field.classList.add("wrong");
        }else{
                field.classList.remove("wrong");
        }
}

function checkPhoneFormat(fieldname, statusdiv){
        var field = document.getElementById(fieldname);
        var value = field.value;
        if (!(value.match(PHONE_REGEX))){
                field.classList.add("wrong");
        }else{
                field.classList.remove("wrong");
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

function putPlaceholder(fieldname){
        if (PHMAP.has(fieldname)){
                setPlaceholder(fieldname, PHMAP.get(fieldname));
        }else{
                console.log("Field non trovato nella mappa: " + fieldname);
        }
}

function setRegistrationPH(){
        putPlaceholder("Rusername");
        putPlaceholder("Remail");
        putPlaceholder("Rtel");
}

function setLoginPH(){
        putPlaceholder("username");
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

function setInstrumentNameAddPH(){
        setPlaceholder("Nome", "Inserisci il nome della strumentazione qui.");
}

function unsetInstrumentNameAddPH(){
        unsetPlaceholder("Nome");
}

function setInstrumentCostAddPH(){
        setPlaceholder("Costo", "Inserisci il costo al giorno, ad esempio: 35");
}

function unsetInstrumentCostAddPH(){
        unsetPlaceholder("Costo");
}

function setInstrumentAvailPH(){
        setPlaceholder("Disp", "Inserisci il numero di strumenti disponibili, ad esempio: 3");
}

function unsetInstrumentAvailPH(){
        unsetPlaceholder("Disp");
}

function setInstrumentDescPH(){
        setPlaceholder("Desc", "Inserisci una breve descrizione dell'articolo.");
}

function unsetInstrumentDescPH(){
        unsetPlaceholder("Desc");
}

function setInstrumentImgPH(){
        setPlaceholder("imgname", "Inserisci il nome del file immagine da collegare al prodotto (con estensione)");
}

function unsetInstrumentImgPH(){
        unsetPlaceholder("imgname");
}

function setInstrumentImgAltPH(){
        setPlaceholder("imgalt", "Inserisci una breve descrizione testuale dell'immagine");
}

function unsetInstrumentImgAltPH(){
        unsetPlaceholder("imgalt");
}

function setInstrumentAddPH(){
        setInstrumentCostAddPH();
        setInstrumentAvailPH();
        setInstrumentNameAddPH();
        setInstrumentDescPH();
        setInstrumentImgPH();
        setInstrumentImgAltPH()
}

function chiudiLightbox(){
        var lb = document.getElementById("lightboxBG");
        lb.style.display = "none";
}

function apriLightbox(imglink){
        var img = document.getElementById("lightboximg");
        var lb = document.getElementById("lightboxBG");
        img.src = imglink;
        lb.style.display = "block";
        return false;
}

function preparaLightbox(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange=function(){
                if (this.readyState == 4 && this.status == 200){
                        document.getElementById("lightboxcontainer").innerHTML=this.responseText;
                        document.close();
                }
        }
        xhttp.open("GET", "lightbox.html", true);
        xhttp.send();
}
