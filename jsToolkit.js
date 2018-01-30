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
                ["username", "Inserisci il tuo nome utente"],
                ["SUserName", "Inserisci il termine di ricerca qui."],
                ["cerca", "Inserisci il termine di ricerca qui."],
                ["Nome", "Inserisci il nome della sala qui."],
                ["Funzione", "Inserisci il servizio offerto dalla sala qui."],
                ["PrezzoOrario", "Inserisci qui il prezzo all'ora della sala. Esempio: 15."],
                ["SRoom", "Inserisci qui il termine da cercare."],
                ["Ora", "Inserisci l'ora da cui far partire la prenotazione. Esempio: 14:00"],
                ["Durata", "Inserisci la durata (in ore) della prenotazione. Esempio: 2"],
                ["NomeS", "Inserisci il nome della strumentazione qui."],
                ["Costo", "Inserisci il costo al giorno, ad esempio: 35"],
                ["Disp", "Inserisci il numero di strumenti disponibili. Ad esempio: 3"],
                ["Desc", "Inserisci una breve descrizione dell'articolo."],
                ["imgname", "Inserisci il nome del file immagine da collegare al prodotto (con estensione)"],
                ["imgalt", "Inserisci l'alternativa testuale all'immagine di 'imgname'"],
                ["qty", "Inserisci il numero di pezzi da noleggiare. Esempio: 3"]
        ]
)

const SPECIAL_PH = new Map(
        [
                ["Data", setDatePH],
                ["dataInizio", setDatePH],
                ["dataFine", setDatePH]
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
        genericCheck(field, statusdiv, DATE_REGEX, "La data dovrebbe avere formato gg/mm/aaaa");
}

function checkDigitFormat(field, statusdiv, message){
        genericCheck(field, statusdiv, DIGITS_REGEX, message);
}

function checkHourFormat(field, statusdiv){
        genericCheck(field, statusdiv, TIME_REGEX, "L'ora dovrebbe avere formato hh:00. Non sono ammesse mezz'ore.");
}

function checkEmailFormat(fieldname, statusdiv){
        genericCheck(fieldname, statusdiv, EMAIL_REGEX, "L'email deve avere formato nome@dominio.estensione");
}

function checkPhoneFormat(fieldname, statusdiv){
        genericCheck(fieldname, statusdiv, PHONE_REGEX, "Il numero di telefono deve avere tra 6 e 11 cifre");
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
                        console.log("Field non trovato nella mappa: " + fieldname);
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
        return "Esempio: " + d + "/" + m + "/" + y;
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
        xhttp.open("GET", "lightbox.html", true);
        xhttp.send();
}

window.onload = function(){
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
                inputs[i].onchange = function(){checkDigitFormat(this.getAttribute("id"), "durationerr", "La durata dovrebbe essere un numero maggiore di 1")}
        }
        // Preparazione controlli di formato quantità
        var inputs = document.getElementsByClassName("qtycheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkDigitFormat(this.getAttribute("id"), "qtyerr", "La quantità dovrebbe essere un numero maggiore di 1")}
        }
        // Preparazione controlli di formato prezzo
        var inputs = document.getElementsByClassName("pricecheck");
        for(var i = 0; i < inputs.length; i++){
                inputs[i].onchange = function(){checkDigitFormat(this.getAttribute("id"), "priceerr", "Il prezzo dovrebbe essere un numero maggiore di 1")}
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
