function validateNetwork(net,pack) {
    
        if (net.length == 0) { 
            document.getElementById("amount").innerHTML = "";
            return;
        } else if(net == '1'){
            document.getElementById("amount").innerHTML = "<option selected>Select amount</option><option value='999'> 1.5GB (N1,000)</option><option value='1999'>3.5GB (N2,000)</option><option disabled value='2499'>5GB (2850)</option><option value='4999'>10GB (N5,000)</option><option value='7999'>16GB (N8,100)</option><option value='9999'>22GB (N10,050)</option>";
        }else if(net == '2'){
            document.getElementById("amount").innerHTML = "<option selected>Select amount</option><option value='1000'>1GB (N480)</option><option value='2000'>2GB (N960)</option><option value='5000'>5GB (N2,370)</option>";
        }else if(net == '3'){
           document.getElementById("amount").innerHTML = "<option selected>Select amount</option><option value='1600.01'>2GB (N1000)</option><option value='3750.01'>4.5GB (N1,900)</option><option value='5000.01'>7.2GB (N2,250)</option><option value='6000.01'>8.75GB (N2,700)</option><option value='8000.01'>12.5GB (N3550)</option><option value='12000.01'>15.6GB (N4,400)</option><option value='16000.01'>25GB (N6,950)</option><option value='30000.01'>52.5GB (N12,900)</option><option value='45000.01'>62.5GB (N15,450)</option>";
        }else if(net == '4'){
            document.getElementById("amount").innerHTML = "<option selected>Select amount</option><option value='500.01'>500MB (N500)</option><option value='1000.01'>1GB (N1000)</option><option value='1500.01'>1.5GB (N1,350)</option><option value='2500.01'>2.5GB (N2200)</option><option value='4000.01'>4GB (N3,000)</option><option value='5500.01'>5.5GB (N4,000)</option><option value='11500.01'>11.5GB (N8,000)</option><option value='15000.01'>15GB (N10000)</option><option value='27000.01'>27.5GB (N18,500)</option>";
        
    }
}