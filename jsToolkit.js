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
