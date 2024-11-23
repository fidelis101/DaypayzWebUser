document.getElementById("topup_box").style.display = "none";

function validate(cardno) {
    if (cardno.length <= 8) { 
        document.getElementById("result").innerHTML = "";
        return;
    } else {
        document.getElementById("result").innerHTML = "<font color='green'>Validating... pls wait<font>";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("result").innerHTML = "<font color='green'>"+this.responseText+"</font>";
                document.getElementById("cnum").value = document.getElementById("cnum2").value = document.getElementById("cnum1").innerHTML;
                 document.getElementById("ivp").value = document.getElementById("ivp2").value = document.getElementById("ivp1").innerHTML;
                  document.getElementById("cname").value = this.responseText;
            }
        };
        xmlhttp.open("GET", "verifytvuser.php?provider=gotv&cardnumber=" + cardno, true);
        xmlhttp.send();
    }
}
function loadpackage(cable) {
    if (cable.length == 0) { 
        document.getElementById("amount").innerHTML = "<option>Select a cable</option>";
        return;
    } else if(cable == "02") {
        document.getElementById("amount").innerHTML = `<option selected>Select Package</option>
                                            <option value="00">Top Up</option>
                                            <option disabled value='01'>GOtv Lite- (N950.00)</option>
                                            <option value='02'>GOTV Jinja N3,350.00</option>
                                        <option value='03'>GOTV Jolli N4,900.00</option>
                                            <option value='04'>GOtv Max- (N7,250.00)</option>
                                            <option value='05'>GOtv Supa- (N9,650.00)</option>`;
        
    }else if(cable == "01") {
        document.getElementById("amount").innerHTML = `<option selected>Select Package</option>
                                        <option value="00">Top Up</option>
                                        
                                        <option value='01'>DStv Yonga - (N5,150.00)</option>
                                        <option value='02'>DStv Confam - (N9,350.00)</option>
                                        <option value='03'>DStv Compact - (N15,750.00)</option>
                                        <option value='04'>DStv Compact Plus - (N25,050.00)</option>
                                        <option value='05'>DStv Premium - (N37,050.00)</option>`;
        
    }else if(cable == "03") {
        document.getElementById("amount").innerHTML = `<option selected>Select Package</option>
                                        <option value='01'>StarTimes Nova - (N1,750.00)</option>
                                        <option value='02'>StarTimes Basic - (N3,550.00)</option>
                                        <option value='03'>StarTimes Smart - (N3,850.00)</option>
                                        <option value='04'>StarTimes Classic - (N4,550.00)</option>
                                        <option value='05'>StarTimes Super- (N7,550.00)</option>`;
    }
}

function packageselected(package) 
{
    if(package == "00")
        document.getElementById("topup_box").style.display = "block";
    else
        document.getElementById("topup_box").style.display = "none";
}
