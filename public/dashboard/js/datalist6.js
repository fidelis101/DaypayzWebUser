function validateNetwork(net,pack) {
    
    if (net.length == 0) { 
        document.getElementById("amount").innerHTML = "";
        return;
    } else if(net == '1'){
        document.getElementById("amount").innerHTML = `	<option selected>Select</option><option value="D-MFIN-1-40MB">40MB (1 day) = ₦50</option>
        <option value="D-MFIN-1-100MB">100MB (1 day) = ₦100</option>
        <option value="D-MFIN-1-200MB">200MB (3 days) = ₦200</option>
        <option value="D-MFIN-1-350MB">350MB (7 days) = ₦300</option>
        <option value="D-MFIN-1-750MB">750MB (14 days) = ₦500</option>
        <option value="D-MFIN-1-1.5GB">1.5GB (30 days) = ₦1000</option>
        <option value="D-MFIN-1-3GB">3GB (30 days) = ₦1500</option>
        <option value="D-MFIN-1-6GB">6GB (30 days) = ₦2500</option>
        <option value="D-MFIN-1-11GB">11GB (30 days) = ₦4000</option>
        <option value="D-MFIN-1-20GB">20GB (30 days) = ₦5000</option>
        <option value="D-MFIN-1-40GB">40GB (30 days) = ₦10000</option>
        <option value="D-MFIN-1-75GB">75GB (30 days) = ₦15000</option>
        <option value="D-MFIN-1-8GB">8GB (30 days) = ₦3000</option>
        <option value="D-MFIN-1-120GB">120GB (30 days) = ₦20000</option>
        <option value="D-MFIN-1-1GB1D">1GB (1 day) = ₦300</option>
        <option value="D-MFIN-1-2GB1D">2GB (1 day) = ₦500</option>
        <option value="D-MFIN-1-6GB1W">6GB (7 days) = ₦1500</option>
        <option value="D-MFIN-1-2GB1M">2GB (30 days) = ₦1200</option>
        <option value="D-MFIN-1-4.5GB">4.5GB (30 days) = ₦2000</option>
        <option value="D-MFIN-1-25GB1M">25GB (30 days) = ₦8000</option>
        <option value="D-MFIN-1-200GB1M">200GB (30 days) = ₦30000</option>`;
        
        
    }else if(net == '2'){
        document.getElementById("amount").innerHTML = `<option selected>Select amount</option>
                                                        <option disabled value='0.5'>500MB (N189)</option>
                                                        <option value='1'>1GB Corporate (N298)</option>
                                                        <option disabled value='2'>2GB Corporate (N596)</option>
                                                        <option disabled value='3'>3GB Corporate (N894)</option>
                                                        <option disabled value='5'>5GB Corporate (N1,490)</option>`;
    }else if(net == '3'){
       document.getElementById("amount").innerHTML = 
       `<option value="50">50MB 1 Day incl 5MB nite = ₦50</option>
<option value="100">150MB 1 Day incl 35MB nite = ₦100</option>
<option value="200">350MB 2 Days incl 110MB nite = ₦200</option>
<option value="500">1.8GB 14 Days incl 1GB nite = ₦500</option>
<option value="1000">3.9GB 30Days incl 2GB nite = ₦1000</option>
<option value="2000">9.2GB 30Days incl 4GB nite = ₦2000</option>
<option value="2500">10.8GB 30 days incl 4GB nite = ₦2500</option>
<option value="3000">14GB 30Days incl 4GB nite = ₦3000</option>
<option value="4000">18GB 30Days incl 4GB nite = ₦4000</option>
<option value="5000">24GB 30Days incl 4GB nite = ₦5000</option>
<option value="8000">29.5GB 30Days incl 2GB nite = ₦8000</option>
<option value="10000">50GB 30Days incl 4GB nite = ₦10000</option>
<option value="15000">93GB 30Days incl 7GB nite = ₦15000</option>
<option value="18000">119GB 30Days incl 10GB nite = ₦18000</option>`;

    }else if(net == '4'){
        document.getElementById("amount").innerHTML = `<option value="0">Select</option>
    <option value="50">25MB (1 Days) = ₦50</option>
    <option value="100">100MB (1 Days) = ₦100</option>
    <option value="200">650MB (1 Days) = ₦200</option>
    <option value="300">1GB (1 Days) = ₦300</option>
    <option value="500">500MB (30 Days) = ₦500</option>
    <option value="1000">1.5GB (30 Days) = ₦1000</option>
    <option value="1200">2GB (30 Days) = ₦1200</option>
    <option value="1500">7GB (7 Days) = ₦1500</option>
    <option value="2000">4.5GB (30 Days) = ₦2000</option>
    <option value="4000">11GB (30 Days) = ₦4000</option>
    <option value="5000">15GB (30 Days) = ₦5000</option>
    <option value="10000">40GB (30 Days) = ₦10000</option>
    <option value="15000">75GB (30 Days) = ₦15000</option>`;    
}else if(net == '5'){
        document.getElementById("amount").innerHTML = `<option selected>Select amount</option>
                                                        <option value='0.5SME'>500MB SME (N139)</option>
                                                        <option value='1SME'>1GB SME (N259)</option>
                                                        
                                                        <option value='2SME'>2GB SME (N518)</option>
                                                        
                                                        <option value='3SME'>3GB SME (N777)</option>
                                                        
                                                        <option value='5SME'>5GB SME (N1,295)</option>
                                                        <option value='10SME'>10GB SME (N2,590)</option>`;
    }
                                                        
}
        
