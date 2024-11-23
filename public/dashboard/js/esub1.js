function validate(meterno,provider,type) {
    if (meterno.length <= 7) { 
        document.getElementById("result").innerHTML = "";
        
        return;
    } else {
        document.getElementById("result").innerHTML = "<font color='green'>Validating... pls wait<font>";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("result").innerHTML = this.responseText;
                
            }
        };
        xmlhttp.open("GET", "verifyeuser.php?provider="+provider+"&type="+type+"&meternumber=" + meterno, true);
        xmlhttp.send();
    }
}