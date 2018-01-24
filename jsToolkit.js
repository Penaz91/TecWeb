function getSafety(fieldname){
        var field = document.getElementById(fieldname);
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

function getCoupling(field1, field2){
        var div = document.getElementById("CoupInd");
        var pwd1 = document.getElementById(field1);
        var pwd2 = document.getElementById(field2);
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
                }
        }
        xhttp.open("GET", "lightbox.html", true);
        xhttp.send();
}

function dayOfTheWeek(y, m, d){
        var t = [0, 3, 2, 5, 0, 3, 5, 1, 4, 6, 2, 4];
        var k = (m < 3 ? 1 : 0);
        var Y = y - k;
        return (Y + Math.floor(Y/4) - Math.floor(Y/100) + Math.floor(Y/400) + t[m-1] + d) % 7;
}

function leapYear(year){
        return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
}

function prepareCal(month, year){
        var buttons = document.getElementById("daypicker").getElementsByTagName("button");
        document.getElementById("meseanno").innerHTML = month + "/" + year;
        var months31 = [1, 3, 5, 7, 8, 10, 12];
        var months30 = [4, 6, 9, 11];
        var maxnum = 28;
        if (leapYear(year)){
                maxnum=29
        }
        if (months31.indexOf(month) > -1){
                maxnum=31;
        }
        if (months30.indexOf(month) > -1){
                maxnum=30;
        }
        var initialday = dayOfTheWeek(year, month, 1);
        for (var i = 0; i < initialday; i++) {
                buttons[i].disabled = true;
                buttons[i].innerHTML = "-"
        }
        for (var i = initialday; i < initialday + maxnum; i++){
                buttons[i].disabled = false;
                buttons[i].innerHTML=i-initialday+1;
                buttons[i].onclick=function(arg){return function(){
                        console.log(arg + "/" + month + "/" + year);
                }}(i-initialday+1);
        }
        for (var i = initialday+maxnum; i < 42; i++){
                buttons[i].disabled = true;
                buttons[i].innerHTML = "-"
        }
        var prevm = month-1;
        var prevy = year;
        if (prevm == 0){
                prevm = 12;
                prevy = year-1;
        }
        var prevbtn = document.getElementById("prev").onclick=function(){prepareCal(prevm, prevy);};
        var nextm = month+1;
        var nexty = year;
        if (nextm == 13){
                nextm = 1;
                nexty = year+1;
        }
        var prevbtn = document.getElementById("next").onclick=function(){prepareCal(nextm, nexty);};
}
