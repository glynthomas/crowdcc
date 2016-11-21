/*! cg.js v1.00.00 
| (c) 2015 crowdcc. 
| connection get * check connection to  twitter * google 
| returns true * no network connection (to default twitter) * returns false * connected (to default twitter) * if ( cc_connect() ) { rtnwebapp('error' , 'error_tamper' , 'post', '', ''); exit(); }
| in addtion this client side idea js check * include in watch.js * offline app check
| crowdcc.com/use */

/* in addtion this client side idea js check * include in watch.js * offline app check */

function isonline(no,yes){
    var xhr = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHttp');
    xhr.onload = function(){
        if(yes instanceof Function){
            yes();
        }
    }
    xhr.onerror = function(){
        if(no instanceof Function){
            no();
        }
    }
    xhr.open("GET","gc.php",true);
    xhr.send();
}

isOnline(
    function(){
        alert("Sorry, we currently do not have Internet access.");
    },
    function(){
        alert("Succesfully connected!");
    }
);