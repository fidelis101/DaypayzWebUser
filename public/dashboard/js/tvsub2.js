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
                                            <option value='02'>GOTV Jinja N2,300.00</option>
                                        <option value='03'>GOTV Jolli N3,350.00</option>
                                            <option value='04'>GOtv Max- (N4,900.00)</option>
                                            <option value='05'>GOtv Supa- (N6,450.00)</option>`;
        
    }else if(cable == "01") {
        document.getElementById("amount").innerHTML = `<option selected>Select Package</option>
                                        <option value="00">Top Up</option>
                                        
                                        <option value='01'>DStv Yonga - (N3,550.00)</option>
                                        <option value='02'>DStv Confam - (N6,250.00)</option>
                                        <option value='03'>DStv Compact - (N10,550.00)</option>
                                        <option value='04'>DStv Compact Plus - (N16,650.00)</option>
                                        <option value='05'>DStv Premium - (N24,550.00)</option>`;
        
    }else if(cable == "03") {
        document.getElementById("amount").innerHTML = `<option selected>Select Package</option>
                                        <option value='01'>StarTimes Nova - (N1,500.00)</option>
                                        <option value='02'>StarTimes Basic - (N2,600.00)</option>
                                        <option value='03'>StarTimes Smart - (N3,500.00)</option>
                                        <option value='04'>StarTimes Classic - (N3,800.00)</option>
                                        <option value='05'>StarTimes Super- (N6,500.00)</option>`;
        
    }
}

function packageselected(package) 
{
    if(package == "00")
        document.getElementById("topup_box").style.display = "block";
    else
        document.getElementById("topup_box").style.display = "none";
}
