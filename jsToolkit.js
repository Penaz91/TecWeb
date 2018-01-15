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
        if (document.getElementById("Rpwd").value != document.getElementById("Rpwd2").value){
                div.innerHTML = "Le due password non Corrispondono!";
        }else{
                div.innerHTML = "";
        }
}
