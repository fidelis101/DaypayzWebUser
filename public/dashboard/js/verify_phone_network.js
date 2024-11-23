function check_network(net,num)
{
    var mtn_nums = ["0703","0706","0803","0806","0810","0813","0814","0816","0903","0906"];
    var eti_nums = ["0809","0817","0818","0909","0908"];
    var glo_nums = ["0705","0805","0807","0811","0815","0905"];
    var air_nums =["0701","0708","0802","0808","0812","0902","0907"];
    
    switch(net)
    {
        case "2":
            {
                if(mtn_nums.lastIndexOf(num)>-1)
                    return true;
                else
                    return false;
            }break;
        case "3":
                if(glo_nums.lastIndexOf(num)>-1)
                    return true;
                else
                    return false;
            break;
        case "1":
            {
                if(air_nums.lastIndexOf(num)>-1)
                    return true;
                else
                    return false;
            }break;
        case "4":
            {
                if(eti_nums.lastIndexOf(num)>-1)
                    return true;
                else
                    return false;
            }break;
        default:
            return false;
    }
}
function confirmNetwork(num)
{
    var net = document.getElementById('network').value;
    if(num.length > 3)
    {
        num = num.substring(0,4);
        if(!check_network(net,num))
        {
            document.getElementById("num_error").innerHTML = "<label class='text-danger'> Wrong Network Selected </label>";
        }else
        document.getElementById("num_error").innerHTML = "";
    }
}