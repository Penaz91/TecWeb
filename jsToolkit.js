const SYMBOL_REGEX = /[%,$,£,",!,&,/,(,),=,?,',^,@,#,+,-,*,\\,\s]+/;
const LOWERCASE_REGEX = /[a-z]+/;
const UPPERCASE_REGEX = /[A-Z]+/;
const DIGITS_REGEX = /^\d+$/;
const DATE_REGEX = /^\d{2}\/\d{2}\/\d{4}$/;
const TIME_REGEX = /^\d{2}:0{2}$/;
const EMAIL_REGEX = /^([\w\+\-]+\.?[\w\+\-\.]*)\@([\w\+\-]+)\.([\w\+\-\.]+)$/;
const PHONE_REGEX = /^\d{6,11}$/;
const FILEFORMAT_REGEX = /^[\w,\d]+.[\w,\d]+$/;

const PHMAPIT = new Map(
        [
                ["Rusername", "Inserisci il tuo nome utente"],
                ["Remail", "Inserisci la tua email"],
                ["Mmail", "Inserisci la tua email"],
                ["Rtel", "Inserisci il tuo numero telefonico"],
                ["Mtel", "Inserisci il tuo numero telefonico"],
                ["username", "Inserisci il tuo nome utente"],
                ["SUserName", "Inserisci il termine di ricerca qui."],
                ["cerca", "Inserisci il termine di ricerca qui."],
                ["Nome", "Inserisci il nome della sala qui. (Italiano)"],
                ["Funzione", "Inserisci il servizio offerto dalla sala qui. (Italiano)"],
                ["PrezzoOrario", "Inserisci qui il prezzo all'ora della sala. Esempio: 15."],
                ["SRoom", "Inserisci qui il termine da cercare."],
                ["Ora", "Inserisci l'ora da cui far partire la prenotazione. Esempio: 14:00"],
                ["Durata", "Inserisci la durata (in ore) della prenotazione. Esempio: 2"],
                ["NomeS", "Inserisci il nome della strumentazione qui."],
                ["Costo", "Inserisci il costo al giorno, ad esempio: 35"],
                ["Disp", "Inserisci il numero di strumenti disponibili. Ad esempio: 3"],
                ["Desc", "Inserisci una breve descrizione dell'articolo. (Italiano)"],
                ["imgname", "Inserisci il nome del file immagine da collegare al prodotto (con estensione)"],
                ["imgalt", "Inserisci l'alternativa testuale all'immagine di 'imgname' (Italiano)"],
                ["qty", "Inserisci il numero di pezzi da noleggiare. Esempio: 3"],
                ["EngNome", "Inserisci qui il nome della stanza (versione Inglese)"],
                ["EngFunc", "Inserisci il servizio offerto dalla stanza (versione Inglese)"],
                ["EngDesc", "Inserisci una breve descrizione dell'oggetto (versione Inglese)"],
                ["EngAlt", "Inserisci l'alternativa testuale all'immagine di 'imgname' (Inglese)"]
        ]
);

const PHMAPEN = new Map(
        [
                ["Rusername", "Insert your Username"],
                ["Remail", "Insert your Email"],
                ["Mmail", "Insert your Email"],
                ["Rtel", "Insert your phone number"],
                ["Mtel", "Insert your phone number"],
                ["username", "Insert your Username"],
                ["SUserName", "Insert your search terms here"],
                ["cerca", "Insert your search terms here"],
                ["EngNome", "Insert the room name here. (English)"],
                ["EngFunc", "Insert the service the room offers here. (English)"],
                ["PrezzoOrario", "Insert here the hourly price of the room. For Instance: 15."],
                ["SRoom", "Insert here your search terms."],
                ["Ora", "Insert the booking start time here, 24 hour format. For Instance: 14:00"],
                ["Durata", "Insert the duration (in hours) of your booking. For INstance: 2"],
                ["NomeS", "Insert the instrumentation name here."],
                ["Costo", "Insert the daily cost here, for instance: 35."],
                ["Disp", "Insert the amount of available instrumentation. For Instance: 3."],
                ["EngDesc", "Insert a short description of the item. (English)"],
                ["Desc", "Insert a short description of the item. (Italian)"],
                ["imgname", "Insert the filename to link to the item (including extension)."],
                ["Engalt", "Insert the text alternative to the image inserted earlier. (English)"],
                ["qty", "Insert the number of items to rent. For Instance: 3."],
                ["Nome", "Insert the room name here. (Italian)"],
                ["Funzione", "Insert the service the room offers here. (Italian)"]
        ]
);

const SPECIAL_PH = new Map(
        [
                ["Data", setDatePH],
                ["dataInizio", setDatePH],
                ["dataFine", setDatePH]
        ]
);

const MESSAGESIT = new Map(
        [
                ["unsafepwd", "Password Non Sicura"],
                ["avgsafepwd", "Sicurezza Password Media"],
                ["safepwd", "Sicurezza Password Alta"],
                ["uncoupledpwd", "Le due Password Non Corrispondono!"],
                ["dateformaterr", "La data dovrebbe avere formato gg/mm/aaaa"],
                ["fileformaterr", "Un nome di file dovrebbe avere formato nome.estensione"],
                ["hourformaterr", "L'ora dovrebbe avere formato hh:00. Non sono ammesse mezz'ore."],
                ["emailformaterr", "L'email dovrebbe avere formato nome@dominio.estensione"],
                ["phoneformaterr", "Il numero di telefono deve avere tra 6 ed 11 cifre."],
                ["fieldnotfounderr", "Field non trovato nella mappa: "],
                ["qtyerr", "La quantità dovrebbe essere un numero maggiore di 1"],
                ["durationerr", "La durata dovrebbe essere un numero maggiore di 1"],
                ["priceerr", "Il prezzo dovrebbe essere un numero maggiore di 1"],
                ["exampleprefix", "Per esempio: "]
        ]
);

const MESSAGESEN = new Map(
        [
                ["unsafepwd", "Unsafe Password"],
                ["avgsafepwd", "Average Password Safety"],
                ["safepwd", "High Password Safety"],
                ["uncoupledpwd", "The two Passwords don't match!"],
                ["dateformaterr", "The date format should be dd/mm/yyyy."],
                ["fileformaterr", "A filename should have the following format: name.extension."],
                ["hourformaterr", "The time should have the following format: hh:00. Only o'clock times are allowed."],
                ["emailformaterr", "The Email address should have the following format: name@domain.extension"],
                ["phoneformaterr", "The phone number should have between 6 and 11 digits"],
                ["fieldnotfounderr", "Field not found in the map: "],
                ["qtyerr", "Quantity should be a number higher than 1"],
                ["durationerr", "Duration should be a number higher than 1."],
                ["priceerr", "Price should be a number higher than 1."],
                ["exampleprefix", "For Instance: "]
        ]
);

PHMAP = PHMAPIT;
MESSAGES = MESSAGESIT;

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
                indicator.innerHTML = MESSAGES.get("unsafepwd");
        }else if(security < 7){
                indicator.classList.remove("unsafepwd");
                indicator.classList.remove("safepwd");
                indicator.classList.add("avgsafepwd");
                indicator.innerHTML = MESSAGES.get("avgsafepwd");
        }else{
                indicator.classList.remove("unsafepwd");
                indicator.classList.remove("avgsafepwd");
                indicator.classList.add("safepwd");
                indicator.innerHTML = MESSAGES.get("safepwd");
        }
}

function getCoupling(field1, field2){
        var div = document.getElementById("CoupInd");
        var pwd1 = document.getElementById(field1);
        var pwd2 = document.getElementById(field2);
        if (pwd1.value != pwd2.value){
                div.innerHTML = MESSAGES.get("uncoupledpwd");
                pwd1.classList.add("wrong");
                pwd2.classList.add("wrong");
        }else{
                div.innerHTML = "";
                pwd1.classList.remove("wrong");
                pwd2.classList.remove("wrong");
        }
}

function genericCheck(field, statusdiv, pattern, wrongvalue){
        var realfield = document.getElementById(field);
        var value = document.getElementById(field).value;
        var div = document.getElementById(statusdiv);
        if (!(value.match(pattern))){
                div.innerHTML=wrongvalue;
                realfield.classList.add("wrong");
        }else{
                div.innerHTML="";
                realfield.classList.remove("wrong");
        }

}

function checkDateFormat(field, statusdiv){
        genericCheck(field, statusdiv, DATE_REGEX, MESSAGES.get("dateformaterr"));
}

function checkFileFormat(field, statusdiv){
        genericCheck(field, statusdiv, FILEFORMAT_REGEX, MESSAGES.get("fileformaterr"));
}

function checkDigitFormat(field, statusdiv, message){
        genericCheck(field, statusdiv, DIGITS_REGEX, message);
}

function checkHourFormat(field, statusdiv){
        genericCheck(field, statusdiv, TIME_REGEX, MESSAGES.get("hourformaterr"));
}

function checkEmailFormat(fieldname, statusdiv){
        genericCheck(fieldname, statusdiv, EMAIL_REGEX, MESSAGES.get("emailformaterr"));
}

function checkPhoneFormat(fieldname, statusdiv){
        genericCheck(fieldname, statusdiv, PHONE_REGEX, MESSAGES.get("phoneformaterr"));
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
        if (SPECIAL_PH.has(fieldname)){
                setPlaceholder(fieldname, SPECIAL_PH.get(fieldname)());
        }else{
                if (PHMAP.has(fieldname)){
                        setPlaceholder(fieldname, PHMAP.get(fieldname));
                }else{
                        console.log(MESSAGES.get("fieldnotfounderr") + fieldname);
                }
        }
}

function setDatePH(field){
        var today = new Date();
        var d = today.getDate();
        var m = today.getMonth()+1;
        var y = today.getFullYear();
        d = (d<10 ? '0'+d : d);
        m = (m<10 ? '0'+m : m);
        return MESSAGES.get("exampleprefix") + d + "/" + m + "/" + y;
}

function preparePlaceholders(){
        var inputs = document.getElementsByTagName("input");
        for (var i = 0, len = inputs.length; i < len; i++) {
                if (inputs[i].type=="text"){
                        putPlaceholder(inputs[i].id);
                }
        }
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
                        var lbclose = document.getElementById("lightboxClose");
                        lbclose.onclick = function(){chiudiLightbox();};
                        var lbbg = document.getElementById("lightbox");
                        lbbg.onclick = function(){chiudiLightbox();};
                        document.close();
                }
        }
        //TODO CODICE TRADUZIONE
        xhttp.open("GET", "lightbox.html", true);
        xhttp.send();
}

function setCookie(name, value, days){
        var today = new Date();
        var expires = new Date();
        expires.setTime(today.getTime() + 24*days*3600000);
        document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString() + ";path=/;";
}

function delCookie(name){
        setCookie(name, "", -3);
}

function getCookie(name){
        var cookies = document.cookie.split("; ");
        var res = "";
        for (var i = 0; i < cookies.length; i++){
                var info = cookies[i].split("=");
                if (name == info[0]){
                        res=unescape(info[1]);
                }
        }
        return res;
}

function setLang(lang){
        setCookie("lang", lang, 3);
}

function getLang(){
        return getCookie("lang");
}

window.onload = function(){
        // Lingua (in caso non sia settata)
        if (getLang()==""){
                setLang("it");
        }
        // Preparazione pezzi di lingua :P
        PHMAP = (getLang() == "it" ? PHMAPIT : PHMAPEN);
        MESSAGES = (getLang() == "it" ? MESSAGESIT : MESSAGESEN);
        // Codice specifico per i link che settano la lingua
        var langbtnsen = Array.prototype.slice.call(document.getElementsByClassName("langbtnen"));
        var langbtnsit = Array.prototype.slice.call(document.getElementsByClassName("langbtnit"));
        var langbtns = langbtnsen.concat(langbtnsit);
        for (var i = 0; i < langbtns.length; i++){
                langbtns[i].onclick = function(){setLang((this.getAttribute("class") == "langbtnit") ? "it": "en");};
        }
        // Preparazione placeholder
        var inputs = Array.prototype.slice.call(document.getElementsByTagName("input"));
        var tb = Array.prototype.slice.call(document.getElementsByTagName("textarea"));
        var all = inputs.concat(tb);
        for(var i = 0; i < all.length; i++){
                var type = all[i].getAttribute("type");
                var tag = all[i].tagName;
                if (tag=="TEXTAREA" || type=="text"){
                        putPlaceholder(all[i].id);
                        all[i].onblur= function(){putPlaceholder(this.getAttribute("id"));};
                        all[i].onfocus = function(){unsetPlaceholder(this.getAttribute("id"));};
                }
        }
        // Preparazione controlli di forma telefono
        var inputs = document.getElementsByClassName("phonecheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkPhoneFormat(this.getAttribute("id"), "errphone");}
        }
        // Preparazione controlli di forma email
        var inputs = document.getElementsByClassName("emailcheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkEmailFormat(this.getAttribute("id"), "errmail");}
        }
        // Preparazione controlli di forma data
        var inputs = document.getElementsByClassName("datecheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkDateFormat(this.getAttribute("id"), "errdate");}
        }
        // Preparazione controlli di forma data (secondo campo solo per verifica disponibilità)
        var inputs = document.getElementsByClassName("datecheck2");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkDateFormat(this.getAttribute("id"), "errdate2");}
        }
        // Preparazione controlli di forma ora
        var inputs = document.getElementsByClassName("timecheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkHourFormat(this.getAttribute("id"), "errtime");}
        }
        // Preparazione controlli di forza password
        var inputs = document.getElementsByClassName("strengthcheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){getSafety(this.getAttribute("id"));}
        }
        // Preparazione controlli di formato durata
        var inputs = document.getElementsByClassName("durationcheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkDigitFormat(this.getAttribute("id"), "durationerr", MESSAGES.get("durationerr"));};
        }
        // Preparazione controlli di formato quantità
        var inputs = document.getElementsByClassName("qtycheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkDigitFormat(this.getAttribute("id"), "qtyerr", MESSAGES.get("qtyerr"));};
        }
        // Preparazione controlli di formato prezzo
        var inputs = document.getElementsByClassName("pricecheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkDigitFormat(this.getAttribute("id"), "priceerr", MESSAGES.get("priceerr"));};
        }
        // Preparazione controlli di formato nomi file
        var inputs = document.getElementsByClassName("filecheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkFileFormat(this.getAttribute("id"), "fileerr");};
        }

        // Preparazione controlli di accoppiamento password
        var input1 = document.getElementsByClassName("paircheck1");
        var input2 = document.getElementsByClassName("paircheck2");
        if (input1.length != 0 && input2.length != 0){
                input2[0].onchange = function(){getCoupling(input2[0].id,input1[0].id);};
        }
        // Preparazione Lightbox
        var lightboxedimgs = document.getElementsByClassName("lightboximg");
        if (lightboxedimgs.length != 0){
                preparaLightbox();
                for (var i=0; i < lightboxedimgs.length; i++){
                        lightboxedimgs[i].onclick = function(){apriLightbox(this.getAttribute("href")); return false;};
                }
        }
}
