/*! hacked.js v1.00.00 
| (c) 2015 crowdcc. 
| @current functions required list
| window.onload * timezone, browser, platform * document.onclick * document.onupkey * process (processing post & get) * callrequest (rtn from post & get) * validate functions: trimspace, istimezone, istwitter, isemail, isempty, isradio, isvalidkey, isdate, escapehtml, base64 obj.
| crowdcc.com/use */

window.onload = function(){
  
   /*
   *  http:// [0/1] , localhost [2] , ~macbook [3] , crowdcc [4] ( any passed url string [5] )  
   *  console.log(uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] +'/'+ uriarr[3] + '/' +uriarr[4]);
   */ 

   var uriarr = window.location.href.split('/');
  
   /* console.log(uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] +'/'+ uriarr[3]); */

   var backlen = -1; 
       history.go(-backlen);
 
       window.history.replaceState( '', 'crowdcc', uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] );
   
   switch (true) {

     case (document.cookie.indexOf("__icu=true") === -1):
           /* no cookie is present * first run instance */
           document.cookie = "__icu=true;path=/";
           /* set onunload function */
           window.onunload = function(){ document.cookie ="__icu=true;path=/;expires=Thu, 01-Jan-1970 00:00:01 GMT";};
           /* load the only instance of app currently running */
        
           console.log('hacked module start');
           _hacked_ccc();
     break;

     case (document.cookie.indexOf("__icu=true") !== -1):
           /* more than one instance detected * redirect */
           _clearoff_ccc();
     break;

   }


};


function _hacked_ccc() {
  set_css_class('alert', 'backgroundColor', '#4488F6');
  set_css_class('alert', 'borderBottomColor', '#4488F6'); 
  set_css_class('notices', 'display', 'block');
  set_css_id('hacked', 'display', 'block');
  new datepkr ('last', {'dateFormat': 'd/m/Y'});
  process('get');
  set_css_id('user', 'focus', 'focus'); 

}

function _clearoff_ccc() {

  var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('//', '/');
  var fullurl = document.URL;  /* or  window.location.pathname + window.location.search; */

  console.log(baseurl);
  console.log(fullurl);

  if (baseurl === fullurl) {
      // console.log('baseurl and fullurl are the same so you can fuck right off!');
      window.location.replace(baseurl + '/' + '@');
  }
  baseurl = null; fullurl = null;
} 


 /*! jsTimezoneDetect - v1.0.5 - 2013-04-01 */
  var jstz=function(){var b=function(a){a=-a.getTimezoneOffset();return null!==a?a:0},c=function(){return b(new Date(2010,0,1,0,0,0,0))},f=function(){return b(new Date(2010,5,1,0,0,0,0))},e=function(){var a=c(),d=f(),b=c()-f();return new jstz.TimeZone(jstz.olson.timezones[0>b?a+",1":0<b?d+",1,s":a+",0"])};return{determine_timezone:function(){
  return e()},determine:e,date_is_dst:function(a){var d=5<a.getMonth()?f():c(),a=b(a);return 0!==d-a}}}();jstz.TimeZone=function(b){var c=null,c=b;"undefined"!==typeof jstz.olson.ambiguity_list[c]&&function(){for(var b=jstz.olson.ambiguity_list[c],e=b.length,a=0,d=b[0];a<e;a+=1)if(d=b[a],jstz.date_is_dst(jstz.olson.dst_start_dates[d])){c=d;break}}();return{name:function(){return c}}};jstz.olson={};
  jstz.olson.timezones={"-720,0":"Etc/GMT+12","-660,0":"Pacific/Pago_Pago","-600,1":"America/Adak","-600,0":"Pacific/Honolulu","-570,0":"Pacific/Marquesas","-540,0":"Pacific/Gambier","-540,1":"America/Anchorage","-480,1":"America/Los_Angeles","-480,0":"Pacific/Pitcairn","-420,0":"America/Phoenix","-420,1":"America/Denver","-360,0":"America/Guatemala","-360,1":"America/Chicago","-360,1,s":"Pacific/Easter","-300,0":"America/Bogota","-300,1":"America/New_York","-270,0":"America/Caracas","-240,1":"America/Halifax",
  "-240,0":"America/Santo_Domingo","-240,1,s":"America/Asuncion","-210,1":"America/St_Johns","-180,1":"America/Godthab","-180,0":"America/Argentina/Buenos_Aires","-180,1,s":"America/Montevideo","-120,0":"America/Noronha","-120,1":"Etc/GMT+2","-60,1":"Atlantic/Azores","-60,0":"Atlantic/Cape_Verde","0,0":"Etc/UTC","0,1":"Europe/London","60,1":"Europe/Berlin","60,0":"Africa/Lagos","60,1,s":"Africa/Windhoek","120,1":"Asia/Beirut","120,0":"Africa/Johannesburg","180,1":"Europe/Moscow","180,0":"Asia/Baghdad",
  "210,1":"Asia/Tehran","240,0":"Asia/Dubai","240,1":"Asia/Yerevan","270,0":"Asia/Kabul","300,1":"Asia/Yekaterinburg","300,0":"Asia/Karachi","330,0":"Asia/Kolkata","345,0":"Asia/Kathmandu","360,0":"Asia/Dhaka","360,1":"Asia/Omsk","390,0":"Asia/Rangoon","420,1":"Asia/Krasnoyarsk","420,0":"Asia/Jakarta","480,0":"Asia/Shanghai","480,1":"Asia/Irkutsk","525,0":"Australia/Eucla","525,1,s":"Australia/Eucla","540,1":"Asia/Yakutsk","540,0":"Asia/Tokyo","570,0":"Australia/Darwin","570,1,s":"Australia/Adelaide",
  "600,0":"Australia/Brisbane","600,1":"Asia/Vladivostok","600,1,s":"Australia/Sydney","630,1,s":"Australia/Lord_Howe","660,1":"Asia/Kamchatka","660,0":"Pacific/Noumea","690,0":"Pacific/Norfolk","720,1,s":"Pacific/Auckland","720,0":"Pacific/Tarawa","765,1,s":"Pacific/Chatham","780,0":"Pacific/Tongatapu","780,1,s":"Pacific/Apia","840,0":"Pacific/Kiritimati"};
  jstz.olson.dst_start_dates={"America/Denver":new Date(2011,2,13,3,0,0,0),"America/Mazatlan":new Date(2011,3,3,3,0,0,0),"America/Chicago":new Date(2011,2,13,3,0,0,0),"America/Mexico_City":new Date(2011,3,3,3,0,0,0),"Atlantic/Stanley":new Date(2011,8,4,7,0,0,0),"America/Asuncion":new Date(2011,9,2,3,0,0,0),"America/Santiago":new Date(2011,9,9,3,0,0,0),"America/Campo_Grande":new Date(2011,9,16,5,0,0,0),"America/Montevideo":new Date(2011,9,2,3,0,0,0),"America/Sao_Paulo":new Date(2011,9,16,5,0,0,0),"America/Los_Angeles":new Date(2011,
  2,13,8,0,0,0),"America/Santa_Isabel":new Date(2011,3,5,8,0,0,0),"America/Havana":new Date(2011,2,13,2,0,0,0),"America/New_York":new Date(2011,2,13,7,0,0,0),"Asia/Gaza":new Date(2011,2,26,23,0,0,0),"Asia/Beirut":new Date(2011,2,27,1,0,0,0),"Europe/Minsk":new Date(2011,2,27,2,0,0,0),"Europe/Helsinki":new Date(2011,2,27,4,0,0,0),"Europe/Istanbul":new Date(2011,2,28,5,0,0,0),"Asia/Damascus":new Date(2011,3,1,2,0,0,0),"Asia/Jerusalem":new Date(2011,3,1,6,0,0,0),"Africa/Cairo":new Date(2010,3,30,4,0,0,
  0),"Asia/Yerevan":new Date(2011,2,27,4,0,0,0),"Asia/Baku":new Date(2011,2,27,8,0,0,0),"Pacific/Auckland":new Date(2011,8,26,7,0,0,0),"Pacific/Fiji":new Date(2010,11,29,23,0,0,0),"America/Halifax":new Date(2011,2,13,6,0,0,0),"America/Goose_Bay":new Date(2011,2,13,2,1,0,0),"America/Miquelon":new Date(2011,2,13,5,0,0,0),"America/Godthab":new Date(2011,2,27,1,0,0,0)};
  jstz.olson.ambiguity_list={"America/Denver":["America/Denver","America/Mazatlan"],"America/Chicago":["America/Chicago","America/Mexico_City"],"America/Asuncion":["Atlantic/Stanley","America/Asuncion","America/Santiago","America/Campo_Grande"],"America/Montevideo":["America/Montevideo","America/Sao_Paulo"],"Asia/Beirut":"Asia/Gaza Asia/Beirut Europe/Minsk Europe/Helsinki Europe/Istanbul Asia/Damascus Asia/Jerusalem Africa/Cairo".split(" "),"Asia/Yerevan":["Asia/Yerevan","Asia/Baku"],"Pacific/Auckland":["Pacific/Auckland",
  "Pacific/Fiji"],"America/Los_Angeles":["America/Los_Angeles","America/Santa_Isabel"],"America/New_York":["America/Havana","America/New_York"],"America/Halifax":["America/Goose_Bay","America/Halifax"],"America/Godthab":["America/Miquelon","America/Godthab"]};
  

var isbrowser = {Useragent: function() {return navigator.userAgent;},Any: function() {return (isbrowser.Useragent() );}}
var isplatform = {
    Android: function() {return navigator.userAgent.match(/Android/i) ? 'Android' : false;},
    BlackBerry: function() {return navigator.userAgent.match(/BlackBerry/i) ? 'BlackBerry' : false;},
    iPhone: function() {return navigator.userAgent.match(/iPhone/i) ? 'iPhone' : false;},
    iPad: function() {return navigator.userAgent.match(/iPad/i) ? 'iPad' : false;},
    iPod: function() {return navigator.userAgent.match(/iPod/i) ? 'iPod' : false;},
    IEMobile: function() {return navigator.userAgent.match(/IEMobile/i) ? 'IEMobile' : false;},
    OS: function() {return navigator.platform;},
    Any: function() {return (isplatform.Android() || isplatform.BlackBerry() || isplatform.iPhone() || isplatform.iPad() || isplatform.iPod() || isplatform.IEMobile() || isplatform.OS() );}};


var _ccr = { '_ccr': '', 'token': '', 'scode': '', 'ucode': '', 'ecode': '', 'lcode': '', 'dcode': '', 'pltfrm': isplatform.Any(), 'browsr': isbrowser.Any(), 'timezo': istimezone() };
var _ccr_set = { 'vtweet': 1, 'vmail': 1, 'vlock': 1, 'vdate': 1, 'ekey': 1, 'tkey': 1, 'enablekey': 1 };

var _valid = false;


document.onclick = function keyClick(event) {

  var elem = (event.target) ? event.target : event.srcElement;
  var tagclass = elem.className.split(" ")[0]; 
  var tagid = elem.id;
  var tagtxtlen = null;

  switch (true) {

    /* for display purposes only */

    case (tagid === 'ekey_display'):
          return false;
    break;
    case (tagid === 'tkey_display'):
          return false;
    break;

  }

  elem.value = null; 
  if (elem.value) { tagtxtlen = (elem.value.replace(/^\s\s*/, '').replace(/\s\s*$/, '')).length; } else { tagtxtlen = 0;}
  var tagclose = false;

  console.log(event.target.className.split(" ")[0]);
  console.log(event.target.id);
  console.log(tagtxtlen);
   
  switch (true) {

      case (tagclass === 'lookup_usr'):

            switch (true) {

              /* fields required */

              case ( isemptystr( document.getElementById('user').value ) ):
                     set_html_id('user_msg', 'required field' );
                   
              case ( isemptystr( document.getElementById('cmail').value ) ):
                     set_html_id('cmail_msg', 'required field' );           // $('.cmail_msg').html('required field');    
                     if ( isemail( document.getElementById('cmail').value ) === true) {  set_html_id('cmail_msg','&nbsp;'); }
          
              case ( isemptystr( document.getElementById('last').value ) ):
                     set_html_id('last_msg', 'required field' );            // $('.last_msg').html('required field');
                     if ( isdate( document.getElementById('last').value ) === true ) {  set_html_id('last_msg','&nbsp;'); }
  
              case ( isradio() === false ):
                     set_html_id('account_msg', 'required field' );         // $('.account_msg').html('required field');
                     if (isradio() === true ) { set_html_id('account_msg', '&nbsp;' ); }
                     /* console.log('is radio ->' + isradio()); */
              break;


              /* initial validate */

              case ( document.getElementById('user').value !== '' ):
                     set_html_id('user_msg', '&nbsp;' );                    // $('.user_msg').html('&nbsp;');
                     _ccr_set['vtweet'] = 0;
                     if ( istwitter( document.getElementById('user').value ) === false ) { set_html_id('user_msg', 'user is invalid!' );_ccr_set['vtweet'] = 1; }    
                     /* console.log('istwitter -->' + istwitter(document.getElementById('user').value));
                     console.log(_ccr_set['vtweet']); */
                     
              case ( document.getElementById('cmail').value !== '' ):
                     set_html_id('cmail_msg', '&nbsp;' );                   // $('.cmail_msg').html('&nbsp;');
                     _ccr_set['vmail'] = 0;
                     if ( isemail( document.getElementById('cmail').value )  === false ) { set_html_id('cmail_msg', 'email is invalid!' );_ccr_set['vmail'] = 1; }
                     /* console.log('isemail -->' + isemail( document.getElementById('cmail').value));
                     console.log(_ccr_set['vmail']); */

              case ( isradio() === true ):
                      set_html_id('account_msg', '&nbsp;' );                // $('.account_msg').html('&nbsp;');
                     _ccr_set['vlock'] = 0;
                     if (isradio() === false ) { set_html_id('account_msg','required field');_ccr_set['vlock'] = 1;}
                     /* console.log('isradio -->' + isradio());
                     console.log(_ccr_set['vlock']); */

              case ( document.getElementById('last').value  !== '' ):       //  $('#last').val()
                     set_html_id('last_msg', '&nbsp;' );                    //  $('.last_msg').html('&nbsp;');
                     _ccr_set['vdate'] = 0;
                     if ( isdate( document.getElementById('last').value ) === false ) { set_html_id('last_msg', 'date is invalid!'); _ccr_set['vdate'] = 1; }
                     /* console.log('isdate -->' + isdate( document.getElementById('last').value));
                     console.log(_ccr_set['vdate']); */
              break;

            }
      
            /* prepare to post */

            switch (true) {

              case (_ccr_set['vtweet'] + _ccr_set['vmail'] + _ccr_set['vlock'] + _ccr_set['vdate'] === 0):

                    console.log('initial validation passed');

                    _valid = true;

              /* console.log( _ccr['pltfrm'] + '|' + _ccr['browsr'] + '|' + _ccr['timezo'] ); */

                    _ccr['token'] = base64.encode(document.getElementById('authenticity_token').value);
                    _ccr['scode'] = '_ccu';
                    _ccr['ucode'] = encrypt(document.getElementById('user').value);
                    _ccr['ecode'] = encrypt(document.getElementById('cmail').value);

                    _ccr['lcode'] = base64.encode( _ccr['lcode']);

                    _ccr['dcode'] = base64.encode(document.getElementById('last').value);

                    _ccr['pltfrm'] =  base64.encode( _ccr['pltfrm']);
                    _ccr['browsr'] =  base64.encode( _ccr['browsr']);
                    _ccr['timezo'] =  base64.encode( _ccr['timezo']);

              /* 
                 console.log( _ccr['token'] + '|' +_ccr['scode'] + '|' + _ccr['ucode'] + '|' + _ccr['ecode'] + '|' + _ccr['lcode'] + '|'
                 + _ccr['dcode'] + '|' + _ccr['pltfrm'] + '|' + _ccr['browsr'] + '|' + _ccr['timezo']);
              */

                    process('post');
              break;

            }
           
      break;

      case ( tagid === 'access_yes' ):
             set_html_id('account_msg', '&nbsp;' );
             console.log('account is locked');
            
             _ccr['lcode'] = '1';
      break;

      case ( tagid === 'access_no' ):
             set_html_id('account_msg', '&nbsp;' );
             console.log('account is not locked');

             _ccr['lcode'] = '0';
      break;

      case ( tagid === 'last' ):
             set_html_id('last_msg', '&nbsp;' );
             if (isdate( document.getElementById('last')) === false ) { set_html_id('last_msg', 'date is invalid!' ); }
             console.log('last selected ...');
      break;

      case (tagclass === 'confirm_usr'):

            switch (true) {

              case ( isemptystr(document.getElementById('ekey').value) ):
                     set_html_id('ekey_msg', 'required field' );
           
              case ( isemptystr(document.getElementById('tkey').value) ):
                     set_html_id('tkey_msg', 'required field' );
              break;
            
              case ( isvalidkey('ekey') ):
                     set_html_id('ekey_msg', 'invalid key' );
                     _ccr_set['ekey'] = 1;
              break;
              case ( isvalidkey('tkey') ):
                     set_html_id('tkey_msg', 'invalid key' );
                     _ccr_set['tkey'] = 1;
              break;
              
              case ( document.getElementById('tkey').value !== escapehtml(document.getElementById('tkey').value) ):
                     set_html_id('tkey_msg', 'invalid keys' );
                     _ccr_set['tkey'] = 1;
              case ( document.getElementById('ekey').value !== escapehtml(document.getElementById('ekey').value) ):
                     set_html_id('ekey_msg', 'invalid keys' );
                     _ccr_set['ekey'] = 1;
              break;

            }

            /* prepare to post */

            console.log('_ccr_set[ekey] :' + _ccr_set['ekey']);
            console.log('_ccr_set[tkey] :' + _ccr_set['tkey']);

            /* retest key values */

            switch (true) {

              case ( get_html_id('ekey_msg') === '&nbsp;'):
              _ccr_set['ekey'] = 0;
              break;

            }

            switch (true) {

              case ( get_html_id('tkey_msg') === '&nbsp;'):
              _ccr_set['tkey'] = 0;
              break;
            }

            console.log('_ccr_set[ekey] :' + _ccr_set['ekey']);
            console.log('_ccr_set[tkey] :' + _ccr_set['tkey']);

    
            switch (true) {

              case (_ccr_set['ekey'] + _ccr_set['tkey'] === 0):

                    console.log('initial validation passed');

                    _valid = true;

                    _ccr['token'] = base64.encode(document.getElementById('authenticity_token').value);
                    _ccr['scode'] = '_cca';
                    _ccr['ucode'] = encrypt(document.getElementById('ekey').value);
                    _ccr['ecode'] = encrypt(document.getElementById('tkey').value);
                    
                    _ccr['pltfrm'] =  base64.encode( _ccr['pltfrm']);
                    _ccr['browsr'] =  base64.encode( _ccr['browsr']);
                    _ccr['timezo'] =  base64.encode( _ccr['timezo']);
 
            /* 
               console.log(_ccr['token'] + '|' 
               + _ccr['scode'] + '|' 
               + _ccr['ucode'] + '|' 
               + _ccr['ecode'] + '|' 
               + _ccr['pltfrm'] + '|' 
               + _ccr['browsr'] + '|' 
               + _ccr['timezo']); 
            */
                    
            
            /* if (document.getElementById('ekey').value.length === 42) {} */
            
            /* removeEventListener * on pastetxt for token keys */
                    
                    document.getElementById("ekey").removeEventListener("paste", _onpaste, false)                    
                    document.getElementById("tkey").removeEventListener("paste", _onpaste, false);
        
                    process('post');

            break;

            }
          
      break;

      case ( tagclass === 'ready_usr' ):
             process('get');
             set_css_id('redready', 'display', 'none');
             set_css_class('hacked_icc', 'backgroundColor', '#b27500');
             set_css_class('hacked_icc', 'borderBottomColor', '#664300');
             set_css_id('amber', 'display', 'block');
             set_css_id('ekey', 'focus', 'focus');
      break;

      case ( tagclass === 'complete_usr' ):
             var backlen = -1; 
             history.go(-backlen);
             window.history.replaceState('', 'login crowdcc', 'http://'+ window.location.hostname );
             window.location.href = window.location.href;
      break;

      case ( tagclass === 'ui-hacked-noticebar-close' ):
             var backlen = -1; 
             history.go(-backlen);
             window.history.replaceState('', 'login crowdcc', 'http://'+ window.location.hostname );
             window.location.href = window.location.href;
      break;

    }

    elem = null; tagclass = null; tagid = null; tagtxtlen = null; tagclose = null;

}

/* button class -> (red, lookup_usr), (redready, ready_usr), (amber, confirm_usr), (green, complete_usr) */

document.onkeyup = function keyPress(event) {

 /*
 console.log(event);
 console.log(event.target.id);
 console.log('we are in onkeyup!');
 */

 if ( _ccr_set['enablekey'] === 1 ) {

  /* console.log('we are inside onkeyup _ccr_set[enablekey] === 1 :' + _ccr_set['enablekey']); */

  var focusid = document.activeElement;

  if ( !focusid || focusid == document.body ) {focused = null;} else if (document.querySelector) {focusid = document.querySelector(":focus");}

  focusid = focusid.getAttribute('id');
  
  if ( typeof event == "undefined" ) { event = window.event; wkey = event.keyCode; }
 
  if ( document.layers ) { wkey = event.which;}

  var tagid = event.target.id;
  var keyok = false;

  if (tagid) {

   switch (true) {

    case (visible_css_id('red')):
     
      switch (true) {
     
        case ( isemptystr(document.getElementById(tagid).value) ):
               set_html_id(tagid + '_msg', 'required field.');
        break;

        case ( !isemptystr(document.getElementById(tagid).value) ):
               set_html_id(tagid + '_msg', '&nbsp;');
        break;
      }
    
    break;

    case (visible_css_id('amber')):
  
      switch (true) {
     
        case ( isemptystr(document.getElementById(tagid).value) ):   
               set_html_id(tagid + '_msg', 'required field.');
               keyok = false;
        break;

        case ( !isemptystr(document.getElementById(tagid).value) ):
               set_html_id(tagid + '_msg', '&nbsp;');
               keyok = true;
        break;
      }

      if (keyok) {

       switch (true) {

         case ( isvalidkey(focusid) === true):
                set_html_id(focusid + '_msg', 'invalid key' );
                _ccr_set[focusid] = 1;
         break;

         case ( isvalidkey(focusid) === false ):
                set_html_id(focusid + '_msg', '&nbsp;' );
                _ccr_set[focusid] = 0;
         break;
       }
      
      }
    break;
   }

   focusid = null;

  }

 }

  tagid = null; keyok = null;

}

function istrim(str) {
    str = str.replace(/^\s+/, '').replace(/^[\r\n]+|\.|[\r\n]+$/g, '');
    for (var i = str.length - 1; i >= 0; i--) {
        if (/\S/.test(str.charAt(i))) {
            str = str.substring(0, i + 1);
            break;
        }
    }
    return str;
}

/* addEventListener * on paste for token keys */
document.getElementById("ekey").addEventListener("paste", _onpaste, false);
document.getElementById("tkey").addEventListener("paste", _onpaste, false);

function _onpaste(event) {
   var tagid = event.target.id;
   event.preventDefault();

   var pastetxt = '';
   if (window.clipboardData && window.clipboardData.getData) { // IE
       pastetxt = window.clipboardData.getData('Text');
   } else if (event.clipboardData && event.clipboardData.getData) {
       pastetxt = event.clipboardData.getData('text/plain');
   }

   /* console.log(pastetxt); */

   switch (tagid) {    
      case ('ekey'):
            console.log('ekey selected');

            /* console.log('ekey paste : '  + pastetxt); */
            
            document.getElementById('ekey').value = istrim(pastetxt);
            
            set_html_id(tagid + '_msg', '&nbsp;' );
      break;
      case ('tkey'):
            console.log('tkey selected');
                      
            /* console.log('ekey paste : '  + pastetxt); */

            document.getElementById('tkey').value = istrim(pastetxt);
            
            set_html_id(tagid + '_msg', '&nbsp;' );
      break;
   }

   tagid = null; pastetxt = null;
}


function process(ctype) {
 
/* processing the get or post requests and passing on the responce data || error handling to callrequest() */
   
   var data = '';
   
   switch (ctype) {
      
      case ('get'):

             getdata('http://'+ window.location.hostname +'/callreset', callrequest , 'url', '_ccc' );

      break;

      case ('post'):

            switch (true) {

                case (_ccr['scode'] === '_ccu'): // 9 (0 - 8) items

                      _ccr['scode'] = base64.encode( _ccr['scode']);

                      _ccr['_ccr'] = '_ccr' + '=' + _ccr['token'] + ':' + _ccr['scode'] + ':' + _ccr['ucode'] + ':' + _ccr['ecode'] + ':' + _ccr['lcode'] + ':' + _ccr['dcode'] + ':' + _ccr['pltfrm'] + ':' +  _ccr['browsr'] + ':' + _ccr['timezo'];

                      postdata('http://'+ window.location.hostname +'/callreset', callrequest , 'url',  _ccr['_ccr'] , '_ccu');
                
                break;
                case (_ccr['scode'] === '_cca'): // 7 (0 - 6) items

                      _ccr['scode'] = base64.encode( _ccr['scode']);

                      _ccr['_ccr'] = '_ccr' + '=' + _ccr['token'] + ':' + _ccr['scode'] + ':' + _ccr['ucode'] + ':' + _ccr['ecode'] + ':' + _ccr['pltfrm'] + ':' +  _ccr['browsr'] + ':' + _ccr['timezo'];

                      postdata('http://'+ window.location.hostname +'/callreset', callrequest , 'url',  _ccr['_ccr'] , '_cca');
                     
                break;
            }

      break;
   }
   callrequest(data);
}


function callrequest(data) {
  /* responce data || user messaged || error handling  */

  console.log('this is the data : ' + data);

  if (data !== '') {
      var datastr = data.split(':*:');
 
      console.log('(status: post or get or error) -> ' + datastr[0]); /* status: post or get or error */
      console.log('(postfrom: who made request) -> ' + datastr[1]);   /* postfrom: who made the post */
      console.log('returned: ->' + datastr[2]); /* the data returned */

     switch (datastr[0]) {

       case ('get'):
              var jsonobj = JSON.parse(datastr[2]);

              document.getElementById('authenticity_token').value = jsonobj.ccc;
              document.getElementsByName('authenticity_token').value = jsonobj.ccc;

              console.log('ccc token added to form: ' + document.getElementById('authenticity_token').value);
              /* console.log(document.getElementsByName('authenticity_token').value); */

              jsonobj = null;
       break;

       case ('post'):
              /* console.log(datastr[2]); */              
              /* post returned good (error not bubbled from post action) check postfrom */

              switch (true) {

                case (datastr[1] === '_ccu'):
                      /* now check for error bubbled, returned from process */
                     
                      switch (true) {
                        
                        case (datastr[2].indexOf('error') !== -1):
                              _ccr_set['enablekey'] = 0;  /* turn off keyup */
                              /* console.log(' we have found an error ' + datastr[2]); */
                              set_css_id('hacked', 'display', 'none');
                              set_css_id('su', 'display', 'block');

                              set_css_class('hacked_icc', 'backgroundColor', '#DB3939');
                              set_css_class('hacked_icc', 'borderBottomColor', '#DB3939');

                              set_css_class('ui-hacked-noticebar-close', 'z-index', '4');
                              set_css_class('ui-hacked-noticebar-close', 'display', 'block');
                            
                              set_html_class('message', 'crowdcc: please contact us, to protect your account, recovery has been suspended, please refresh this window.');
                        break;
                        case (datastr[2].indexOf('error') === -1):
                              /* return   ===    'correct process'          */
                              _ccr_set['enablekey'] = 0;  /* turn off keyup */
                              console.log(' we have no error ' + datastr[2]);
                              /* alert('going to display an information instruction screen'); */
                              set_css_id('red', 'display', 'none');
                              set_css_id('redready', 'display', 'block');
                        break;

                      }
                      
                break;
                
                case (datastr[1] === '_cca'):
                      /* now check for error bubbled, returned from process */
                      /* console.log('datastr[2] : ' + datastr[2]);         */

                      switch (true) {
                        
                        case (datastr[2].indexOf('error') !== -1):
                              _ccr_set['enablekey'] = 0;  /* turn off keyup */
                              console.log(' we have found an error ' + datastr[2]);
                              set_css_id('hacked', 'display', 'none');
                              set_css_class('hacked_icc', 'backgroundColor', '#DB3939');
                              set_css_class('hacked_icc', 'borderBottomColor', '#DB3939');

                              set_css_class('ui-hacked-noticebar-close', 'z-index', '4');
                              set_css_class('ui-hacked-noticebar-close', 'display', 'block');

                              set_html_class('message', 'crowdcc: please contact us, to protect your account recovery has been suspended, please refresh this window.');

                              switch (true) {

                                case (datastr[2].indexOf('token-no-match') !== -1):
                                      set_html_class('message', 'crowdcc: input error detected, please re-select the account recovery email link and retry your account recovery.');
                                break;

                              }
                              
                        break;
                        case (datastr[2].indexOf('error') === -1):
                              /* return   ===    'correct success'          */
                              console.log(' we have no error ' + datastr[2]);
                              /* alert('going to change the screen from amber to green!'); */
                              set_css_class('hacked_icc', 'backgroundColor', '#009933');
                              set_css_class('hacked_icc', 'borderBottomColor', '#007A29');
                              set_css_id('amber', 'display', 'none');
                              set_css_id('green', 'display', 'block');

                              set_css_class('ui-hacked-noticebar-close', 'backgroundColor', '#009933');
                             
                              set_css_class('ui-hacked-noticebar-close', 'z-index', '4');
                              set_css_class('ui-hacked-noticebar-close', 'display', 'block');

                              /* your account has been recovered, please choose a stronger unguessable password! */
                        break;

                      }

                break;

              } 
                
       break;

             /* error bubbled from post */
       case ('error'):
             _ccr_set['enablekey'] = 0;  /* turn off keyup */
             set_css_id('hacked', 'display', 'none');
             set_css_class('hacked_icc', 'backgroundColor', '#DB3939');
             set_css_class('hacked_icc', 'borderBottomColor', '#DB3939');

             set_css_class('ui-hacked-noticebar-close', 'z-index', '3');
             set_css_class('ui-hacked-noticebar-close', 'display', 'block');
            
             set_html_class('message', 'crowdcc: we are currently unable to contact the network, please close this window, and try again later.');

             console.log(datastr[1]);
             console.log(datastr[2]);
             console.log('unable to connect to crowdcc, please try later')
       break;
     }
             /* console.log('we are back ->' + data ); */ 
  }
  datastr = null;
}

function istimezone() {

    var tz = jstz.determine();
    if (typeof (tz) === 'undefined') {
        response_text = 'No timezone found';
    } else {
        response_text = tz.name(); 
    }
    // console.log(tz.name());
    return response_text;
}

function istwitter(sn) {
    return /^[a-zA-Z0-9_]{1,15}$/.test(sn);
}

function isemail(email) {
    /* simple validation in order to check format correct */
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function isemptystr(str) {
    /*  return (!str || 0 === str.length);                    */
    str = str.replace(/^\s+|\s+$/g,""); /* trim remove spaces */
    if (str.length<1) {
    console.log('the string is empty!');
        return true;
    } else {
        return false;
    }
}

function isvalidkey(key) {
  var invalidkey = false;
  var elem = document.getElementById(key);
  if (typeof elem !== 'undefined' && elem !== null) {
      switch (true) {
        case ( document.getElementById(key).value.length < 42 ):
               invalidkey = true;
        break;
      }
  }
    return invalidkey;
}

function isvaliddate(s) {

    // checks a string to see if it in a valid date format
    // format D(D)/M(M)/(YY)YY
    var dateFormat = /^\d{1,4}[\.|\/|-]\d{1,2}[\.|\/|-]\d{1,4}$/;
    
    if (dateFormat.test(s)) {
        // remove any leading zeros from date values
        s = s.replace(/0*(\d*)/gi,"$1");
        var dateArray = s.split(/[\.|\/|-]/);
        // correct month value
        dateArray[1] = dateArray[1]-1;
        // correct year value
        if (dateArray[2].length<4) {
            // correct year value
            dateArray[2] = (parseInt(dateArray[2]) < 50) ? 2000 + parseInt(dateArray[2]) : 1900 + parseInt(dateArray[2]);
        }
        var testDate = new Date(dateArray[2], dateArray[1], dateArray[0]);
        if (testDate.getDate()!=dateArray[0] || testDate.getMonth()!=dateArray[1] || testDate.getFullYear()!=dateArray[2]) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}


function isdate(dateStr) {

  // format M(M)/D(D)/(YY)YY
  // var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;

  // format D(D)/M(M)/(YY)YY
  // var datePat = /^\d{1,4}[\.|\/|-]\d{1,2}[\.|\/|-]\d{1,4}$/;

  // to avoid JavaScript match is not a function error, check for input (and type) first ...
  if (typeof dateStr === 'string') {

  // format D(D)/M(M)/YYYY + leap year support (http://stackoverflow.com/questions/5465375/javascript-date-regex-dd-mm-yyyy)
  var datePat = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;

  var matchArray = dateStr.match(datePat); // is the format ok?

  if (matchArray == null) {
      // alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
  return false;
  }
  month = matchArray[1]; // p@rse date into variables
  day = matchArray[3];
  year = matchArray[5];
  if (month < 1 || month > 12) { // check month range
      // alert("Month must be between 1 and 12.");
  return false;
  }
  if (day < 1 || day > 31) {
      // alert("Day must be between 1 and 31.");
  return false;
  }
  if ((month==4 || month==6 || month==9 || month==11) && day==31) {
      // alert("Month "+month+" doesn`t have 31 days!")
  return false;
  }
  if (month == 2) { // check for february 29th
      var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
    if (day > 29 || (day==29 && !isleap)) {
        // alert("February " + year + " doesn`t have " + day + " days!");
    return false;
    }
  }

  return true; // date is valid

  }

}

function escapehtml(unsafe) {
    return unsafe
    .replace(/&/g,'')
    .replace(/%3C/g, '')
    .replace(/.*=/g,'')
    .replace(/\?/g,'')
    .replace(/%3E/g, '')
    .replace(/"/g, '')
    .replace(/'/g, '');
}

 var base64 = {
  /* private property */
  _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

  /* public method for encoding */
  encode : function (input) {
      var output = "";
      var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
      var i = 0;

      input = base64._utf8_encode(input);

    if (typeof input !== 'undefined') {  

      while (i < input.length) {

          chr1 = input.charCodeAt(i++);
          chr2 = input.charCodeAt(i++);
          chr3 = input.charCodeAt(i++);

          enc1 = chr1 >> 2;
          enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
          enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
          enc4 = chr3 & 63;

          if (isNaN(chr2)) {
              enc3 = enc4 = 64;
          } else if (isNaN(chr3)) {
              enc4 = 64;
          }

          output = output +
          base64._keyStr.charAt(enc1) + base64._keyStr.charAt(enc2) +
          base64._keyStr.charAt(enc3) + base64._keyStr.charAt(enc4);

      }

    }

      return output;
  },

  /* public method for decoding */
  decode : function (input) {
      var output = "";
      var chr1, chr2, chr3;
      var enc1, enc2, enc3, enc4;
      var i = 0;

    if (typeof input !== 'undefined') {  

      input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

      while (i < input.length) {

          enc1 = base64._keyStr.indexOf(input.charAt(i++));
          enc2 = base64._keyStr.indexOf(input.charAt(i++));
          enc3 = base64._keyStr.indexOf(input.charAt(i++));
          enc4 = base64._keyStr.indexOf(input.charAt(i++));

          chr1 = (enc1 << 2) | (enc2 >> 4);
          chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
          chr3 = ((enc3 & 3) << 6) | enc4;

          output = output + String.fromCharCode(chr1);

          if (enc3 != 64) {
              output = output + String.fromCharCode(chr2);
          }
          if (enc4 != 64) {
              output = output + String.fromCharCode(chr3);
          }

      }

    }

      output = base64._utf8_decode(output);

      return output;

  },

  /* private method for UTF-8 encoding */
  _utf8_encode : function (string) {
      
    if (string !== null) {
    /*  ** encode this string, but first, why don't we just randomly normalize all the line breaks for no good reason at all   
        string = string.replace(/\r\n/g,"\n"); */
      
      var utftext = "";

      for (var n = 0; n < string.length; n++) {

          var c = string.charCodeAt(n);

          if (c < 128) {
              utftext += String.fromCharCode(c);
          }
          else if((c > 127) && (c < 2048)) {
              utftext += String.fromCharCode((c >> 6) | 192);
              utftext += String.fromCharCode((c & 63) | 128);
          }
          else {
              utftext += String.fromCharCode((c >> 12) | 224);
              utftext += String.fromCharCode(((c >> 6) & 63) | 128);
              utftext += String.fromCharCode((c & 63) | 128);
          }

      }

    }

      return utftext;
  },

  /* private method for UTF-8 decoding */
  _utf8_decode : function (utftext) {
      var string = "";
      var i = 0;
      var c = c1 = c2 = 0;

      while ( i < utftext.length ) {

          c = utftext.charCodeAt(i);

          if (c < 128) {
              string += String.fromCharCode(c);
              i++;
          }
          else if((c > 191) && (c < 224)) {
              c2 = utftext.charCodeAt(i+1);
              string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
              i += 2;
          }
          else {
              c2 = utftext.charCodeAt(i+1);
              c3 = utftext.charCodeAt(i+2);
              string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
              i += 3;
          }

      }
      return string;
    }
}


