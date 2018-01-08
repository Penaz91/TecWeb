function hideShowMenu() {
        var x = document.getElementById("menu");
        var y = document.getElementById("top");
        var z = document.getElementById("menubtn").getElementsByTagName("button")[0];
        if (x.style.display === "none"){
                x.style.display = "block";
                y.style.display = "none";
                z.innerHTML = "X";
        } else {
                x.style.display = "none";
                y.style.display = "block";
                z.innerHTML = "&#9776;";
        }
}
