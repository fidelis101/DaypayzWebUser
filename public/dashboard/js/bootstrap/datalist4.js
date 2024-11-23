function validateNetwork(net,pack) {
    
    if (net.length == 0) { 
        document.getElementById("amount").innerHTML = "";
        return;
    } else if(net == '1'){
        document.getElementById("amount").innerHTML = `<option selected>Select amount</option>
                                                        <option value='75MBD'> 75MB (1 day)(N100)</option>
                                                        <option value='350MBD'> 350MB (7 days)(N300)</option>
                                                        <option value='1GBD'> 1GB (1 day)(N300)</option>
                                                        <option value='2GBD'> 2GB (1 day)(N500)</option>
                                                        <option value='1GBW'> 1GB (7 days)(N500)</option>
                                                        <option value='6GBW'> 6GB (7 days)(N1,500)</option>
                                                        <option value='1.5GB'> 1.5GB (30 days) (N1,000)</option>
                                                        <option value='2GB'> 2GB (30 days) (N1,200)</option>
                                                        <option value='3GB'> 3GB (30 days) (N1,500)</option>
                                                        <option value='4.5GB'>4.5GB (N2,000)</option>
                                                        <option value='6GB'>6GB (30 days)(N2,500)</option>
                                                        <option value='8GB'>8GB (30 days)(N3,000)</option>
                                                        <option value='11GB'>11GB (30 days)(N4,000)</option>
                                                        <option value='15GB'>15GB (30 days)(N5,100)</option>
                                                        <option value='40GB'>40GB (30 days)(N10,000)</option>
                                                        <option value='75GB'>75GB (30 days)(N15,100)</option>
                                                        <option value='110GB'>110GB (30 days)(N20,000)</option>`;
    }else if(net == '2')
    {
        document.getElementById("amount").innerHTML = `<option selected>Select amount</option>
                                                        <option disabled value='0.5'>500MB (N199)</option>
                                                        <option value='1'>1GB (N379)</option>
                                                        <option value='2'>2GB (N758)</option>
                                                        <option value='3'>3GB (N1137)</option>
                                                        <option value='5'>5GB (N1,898)</option>
                                                        <option value=''> Other Options</options>
                                                        <option value='1.5GBR'>1.5GB (N1,000) (30 days)</option>
                                                        <option value='2GBR'>2GB (N1,200) (30 days)</option>
                                                        <option value='3GBR'>3GB (N1,500) (30 days)</option>
                                                        <option value='4.5GBR'>4.5GB (N2,000) (30 days)</option>
                                                        <option value='6GBR'>6GB (N2,500) (30 days)</option>
                                                        <option value='8GBR'>8GB (N3,000) (30 days)</option>
                                                        <option value='10GBR'>10GB (N3,500) (30 days)</option>
                                                        <option value='15GBR'>15GB (N5,000) (30 days)</option>
                                                        <option value='40GBR'>40GB (N10,000) (30 days)</option>
                                                        <option value='75GBR'>75GB (N15,000) (30 days)</option>
                                                        <option value='110GBR'>110GB (N20,000) (30 days)</option>
                                                        <option value='1GBRD'>1GB (N350) (1 Day)</option>
                                                        
                                                        <option value='2GBRD'>2GB (N500) (1 Day)</option>
                                                        <option value='6GBRW'>6GB (N1,500) (7 Days)</option>
                                                        <option value='60GBR'>60GB (N20,000) (60 Days)</option>
                                                        <option value='120GBR'>120GB (N50,000) (90 Days)</option>
                                                        <option value='100GBR'>100GB (N30,000) (60 Days)</option>
                                                        <option value='15OGBR'>150GB (N70,000) (90 Days)</option>
                                                        <option value='75MBRD'>75MB (N100) (1 Day)</option>
                                                        <option value='25MBRD'>25MB (N50) (1 Day)</option>
                                                        <option value='200MBR-2D'>200MB (N200) (2 Days)</option>
                                                        <option value='1GBRW'>1GB (N500) (7 Days)</option>
                                                        <option value='750MBRW'>750MB (N500) (7 Days)</option>
                                                        <option value='350MBRW'>350MB (N300) (7 Days)</option>`;
    }else if(net == '3'){
       document.getElementById("amount").innerHTML = `<option selected>Select amount</option>
                                                        <option value='0.8'>GLO-1GB (N500) 14 Days</option>
                                                        <option value='2'>2GB (N1000)</option>
                                                        <option value='3.5'>GLO-3.5GB + 600MB (N1,450)</option>
                                                        <option value='4.5'>GLO-5.2GB + 600MB (N1,900)</option>
                                                        <option value='7.2'>7.2GB (N2,500)</option>
                                                        <option value='8.75'>8.75GB (N2,900)</option>
                                                        <option value='12.5'>12.5GB (N3900)</option>
                                                        <option value='15.6'>15.6GB (N4,800)</option>
                                                        <option value='25'>25GB (N7,560)</option>
                                                        <option value='52.5'>52.5GB (N14,200)</option>
                                                        <option value='62.5'>62.5GB (N17,000)</option>`;
    }else if(net == '4'){
        document.getElementById("amount").innerHTML = `<option selected>Select amount</option>
                                                        <option value='0.025D'>25MB (N50) (1-Day)</option>
                                                        <option value='0.1D'>100B (N100) (1-Day)</option>
                                                        <option value='0.25W'>250MB (N500) (7-Days)</option>
                                                        <option value='0.65D'>650MB (N200) (1-Day)</option>
                                                        <option value='1D'>1GB (N300) (1-Day)</option>
                                                        <option value='2-3D'>2GB (N500) (3-Days)</option>
                                                        <option value='7W'>7GB (N1,500) (7-Days)</option>
                                                        <option value='0.5'>500MB (N500)</option>
                                                        <option value='1.5'>1.5GB (N1,000)</option>
                                                        <option value='2.5'>2.5GB (N1900)</option>
                                                        <option value='3'>3GB (N1500)</option>
                                                        <option value='4'>4GB (N2,400)</option>
                                                        <option value='4.5'>4.5B (N2000)</option>
                                                        <option value='5.5'>5.5GB (N4,000)</option>
                                                        <option value='11.5'>11.5GB (N5,000)</option>
                                                        <option value='11'>11GB (N4,000)</option>
                                                        <option value='15'>15GB (N5000)</option>
                                                        <option value='27'>27GB (N15,500)</option>
                                                        <option value='40'>40GB (N10000)</option>
                                                        <option value='75'>75GB (N15000)</option>`;    
}
}
        
