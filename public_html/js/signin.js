/*! signin.js v1.00.00 
| (c) 2015 crowdcc. 
| module signin.js coupled with store.js
| crowdcc.com/use */

'use strict';

/* nested children for signin.js */
ccc.sig = ccc.sig || {};

ccc.sig.jstz = ccc.sig.jstz || {};
ccc.sig.isbrowser = ccc.sig.isbrowser || {};
ccc.sig.isplatform = ccc.sig.isplatform || {};
ccc.sig.iset = ccc.sig.iset || {};
ccc.sig.isvalid = ccc.sig.isvalid || {};

/* static persitent global vars */

/*! jsTimezoneDetect - v1.0.5 */
ccc.sig.jstz=function(){var b=function(a){a=-a.getTimezoneOffset();return null!==a?a:0},c=function(){return b(new Date(2010,0,1,0,0,0,0))},f=function(){return b(new Date(2010,5,1,0,0,0,0))},e=function(){var a=c(),d=f(),b=c()-f();return new ccc.sig.jstz.TimeZone(ccc.sig.jstz.olson.timezones[0>b?a+",1":0<b?d+",1,s":a+",0"])};return{determine_timezone:function(){
return e()},determine:e,date_is_dst:function(a){var d=5<a.getMonth()?f():c(),a=b(a);return 0!==d-a}}}();ccc.sig.jstz.TimeZone=function(b){var c=null,c=b;"undefined"!==typeof ccc.sig.jstz.olson.ambiguity_list[c]&&function(){for(var b=ccc.sig.jstz.olson.ambiguity_list[c],e=b.length,a=0,d=b[0];a<e;a+=1)if(d=b[a],ccc.sig.jstz.date_is_dst(ccc.sig.jstz.olson.dst_start_dates[d])){c=d;break}}();return{name:function(){return c}}};ccc.sig.jstz.olson={};
ccc.sig.jstz.olson.timezones={"-720,0":"Etc/GMT+12","-660,0":"Pacific/Pago_Pago","-600,1":"America/Adak","-600,0":"Pacific/Honolulu","-570,0":"Pacific/Marquesas","-540,0":"Pacific/Gambier","-540,1":"America/Anchorage","-480,1":"America/Los_Angeles","-480,0":"Pacific/Pitcairn","-420,0":"America/Phoenix","-420,1":"America/Denver","-360,0":"America/Guatemala","-360,1":"America/Chicago","-360,1,s":"Pacific/Easter","-300,0":"America/Bogota","-300,1":"America/New_York","-270,0":"America/Caracas","-240,1":"America/Halifax",
"-240,0":"America/Santo_Domingo","-240,1,s":"America/Asuncion","-210,1":"America/St_Johns","-180,1":"America/Godthab","-180,0":"America/Argentina/Buenos_Aires","-180,1,s":"America/Montevideo","-120,0":"America/Noronha","-120,1":"Etc/GMT+2","-60,1":"Atlantic/Azores","-60,0":"Atlantic/Cape_Verde","0,0":"Etc/UTC","0,1":"Europe/London","60,1":"Europe/Berlin","60,0":"Africa/Lagos","60,1,s":"Africa/Windhoek","120,1":"Asia/Beirut","120,0":"Africa/Johannesburg","180,1":"Europe/Moscow","180,0":"Asia/Baghdad",
"210,1":"Asia/Tehran","240,0":"Asia/Dubai","240,1":"Asia/Yerevan","270,0":"Asia/Kabul","300,1":"Asia/Yekaterinburg","300,0":"Asia/Karachi","330,0":"Asia/Kolkata","345,0":"Asia/Kathmandu","360,0":"Asia/Dhaka","360,1":"Asia/Omsk","390,0":"Asia/Rangoon","420,1":"Asia/Krasnoyarsk","420,0":"Asia/Jakarta","480,0":"Asia/Shanghai","480,1":"Asia/Irkutsk","525,0":"Australia/Eucla","525,1,s":"Australia/Eucla","540,1":"Asia/Yakutsk","540,0":"Asia/Tokyo","570,0":"Australia/Darwin","570,1,s":"Australia/Adelaide",
"600,0":"Australia/Brisbane","600,1":"Asia/Vladivostok","600,1,s":"Australia/Sydney","630,1,s":"Australia/Lord_Howe","660,1":"Asia/Kamchatka","660,0":"Pacific/Noumea","690,0":"Pacific/Norfolk","720,1,s":"Pacific/Auckland","720,0":"Pacific/Tarawa","765,1,s":"Pacific/Chatham","780,0":"Pacific/Tongatapu","780,1,s":"Pacific/Apia","840,0":"Pacific/Kiritimati"};
ccc.sig.jstz.olson.dst_start_dates={"America/Denver":new Date(2011,2,13,3,0,0,0),"America/Mazatlan":new Date(2011,3,3,3,0,0,0),"America/Chicago":new Date(2011,2,13,3,0,0,0),"America/Mexico_City":new Date(2011,3,3,3,0,0,0),"Atlantic/Stanley":new Date(2011,8,4,7,0,0,0),"America/Asuncion":new Date(2011,9,2,3,0,0,0),"America/Santiago":new Date(2011,9,9,3,0,0,0),"America/Campo_Grande":new Date(2011,9,16,5,0,0,0),"America/Montevideo":new Date(2011,9,2,3,0,0,0),"America/Sao_Paulo":new Date(2011,9,16,5,0,0,0),"America/Los_Angeles":new Date(2011,
2,13,8,0,0,0),"America/Santa_Isabel":new Date(2011,3,5,8,0,0,0),"America/Havana":new Date(2011,2,13,2,0,0,0),"America/New_York":new Date(2011,2,13,7,0,0,0),"Asia/Gaza":new Date(2011,2,26,23,0,0,0),"Asia/Beirut":new Date(2011,2,27,1,0,0,0),"Europe/Minsk":new Date(2011,2,27,2,0,0,0),"Europe/Helsinki":new Date(2011,2,27,4,0,0,0),"Europe/Istanbul":new Date(2011,2,28,5,0,0,0),"Asia/Damascus":new Date(2011,3,1,2,0,0,0),"Asia/Jerusalem":new Date(2011,3,1,6,0,0,0),"Africa/Cairo":new Date(2010,3,30,4,0,0,
0),"Asia/Yerevan":new Date(2011,2,27,4,0,0,0),"Asia/Baku":new Date(2011,2,27,8,0,0,0),"Pacific/Auckland":new Date(2011,8,26,7,0,0,0),"Pacific/Fiji":new Date(2010,11,29,23,0,0,0),"America/Halifax":new Date(2011,2,13,6,0,0,0),"America/Goose_Bay":new Date(2011,2,13,2,1,0,0),"America/Miquelon":new Date(2011,2,13,5,0,0,0),"America/Godthab":new Date(2011,2,27,1,0,0,0)};
ccc.sig.jstz.olson.ambiguity_list={"America/Denver":["America/Denver","America/Mazatlan"],"America/Chicago":["America/Chicago","America/Mexico_City"],"America/Asuncion":["Atlantic/Stanley","America/Asuncion","America/Santiago","America/Campo_Grande"],"America/Montevideo":["America/Montevideo","America/Sao_Paulo"],"Asia/Beirut":"Asia/Gaza Asia/Beirut Europe/Minsk Europe/Helsinki Europe/Istanbul Asia/Damascus Asia/Jerusalem Africa/Cairo".split(" "),"Asia/Yerevan":["Asia/Yerevan","Asia/Baku"],"Pacific/Auckland":["Pacific/Auckland",
"Pacific/Fiji"],"America/Los_Angeles":["America/Los_Angeles","America/Santa_Isabel"],"America/New_York":["America/Havana","America/New_York"],"America/Halifax":["America/Goose_Bay","America/Halifax"],"America/Godthab":["America/Miquelon","America/Godthab"]};
  
ccc.sig.isbrowser = {Useragent: function() {return navigator.userAgent;},Any: function() {return (ccc.sig.isbrowser.Useragent() );}}

ccc.sig.isplatform = {
    Android: function() {return navigator.userAgent.match(/Android/i) ? 'Android' : false;},
    BlackBerry: function() {return navigator.userAgent.match(/BlackBerry/i) ? 'BlackBerry' : false;},
    iPhone: function() {return navigator.userAgent.match(/iPhone/i) ? 'iPhone' : false;},
    iPad: function() {return navigator.userAgent.match(/iPad/i) ? 'iPad' : false;},
    iPod: function() {return navigator.userAgent.match(/iPod/i) ? 'iPod' : false;},
    IEMobile: function() {return navigator.userAgent.match(/IEMobile/i) ? 'IEMobile' : false;},
    OS: function() {return navigator.platform;},
    Any: function() {return (ccc.sig.isplatform.Android() || ccc.sig.isplatform.BlackBerry() || ccc.sig.isplatform.iPhone() || ccc.sig.isplatform.iPad() || ccc.sig.isplatform.iPod() || ccc.sig.isplatform.IEMobile() || ccc.sig.isplatform.OS() );}};

ccc.sig.iset = { '_ccc': '', 'ucode': '', 'ecode': '', 'encode': '', 'pcode': '', 'sncode': '', 'uscode': '', 'pltfrm': ccc.sig.isplatform.Any(), 'browsr': ccc.sig.isbrowser.Any(), 'timezo': istimezone(), 'kcode': '333dc638eb62fe4a57964afedfb2bac0a0e333' };
ccc.sig.isvalid = { 'vp1': 0, 'vp2': 0, 've': 0, 'enablekey': 1, 'valid': false };
  

function _signin(whofor){

  console.log('_signin :: we are in _signin()');

  switch (whofor) {
 
    case ('twitter'):
          console.log('_signin :: twitter :: signin detected');
          console.log('_signin :: twitter :: ccc.sig.iset[ecode] ==' + ccc.sig.iset['ecode']);
          console.log('_signin :: twitter :: ccc.sig.iset[pcode] ==' + ccc.sig.iset['pcode']);
          console.log('_signin :: twitter :: ccc.sig.iset[sncode] ==' + ccc.sig.iset['sncode']);

          switch (true) {

            case (ccc.sig.iset['ecode'] != ''):
                  ccc.sig.iset['sncode'] = '_$twitter';
                  ccstate('load');
                  ccc.sig.isvalid['valid'] = true;
            break;

            case (ccc.sig.iset['ecode'] == ''):
                  ccc.sig.iset['ecode'] = ccc.sig.iset['pcode'] = '_$twitter';
                  ccc.sig.iset['pcode']  = base64_encode(ccc.sig.iset['pcode']);
                  ccc.sig.iset['ecode']  = base64_encode(ccc.sig.iset['ecode']);
                  ccstate('load');
                  ccc.sig.isvalid['valid'] = true;
            break;

          } 

          /* optional change color of twitter #55ACEE icon */
          set_css_class('icon-twitter_med','color','#55ACEE');

          console.log('_signin :: twitter :: ccc.sig.iset[ecode] ==' + ccc.sig.iset['ecode']);
          console.log('_signin :: twitter :: ccc.sig.iset[pcode] ==' + ccc.sig.iset['pcode']); 
          console.log('_signin :: twitter :: ccc.sig.iset[sncode] ==' + ccc.sig.iset['sncode']);

          if ( document.getElementById('modal-social-follow-signin-checkbox').checked ) { ccc.sig.iset['kcode'] = ccc.sig.iset['kcode'] + 1; /* console.log('_signin :: twitter :: kcode + 1'); */ }

    break;

    case ('crowdcc_process'):
          toggle_signin();
          return false;
    break;

    case ('crowdcc_validate'): /* could be a new user or a current user ... */
          console.log('_signin :: crowdcc_validate :: we are validate');

          ccc.sig.isvalid['valid'] = validate();

          switch (ccc.sig.isvalid['valid']) {

            case (false):
                  console.log('_signin :: crowdcc_validate :: client details are wrong');
                  return false;
            break;
  
            case (true):
                  /* calculate at login stage if new user or current user ... */
                  console.log('_signin :: crowdcc_validate :: new user or current user :: ' + isradio() );
                    
                  ccc.sig.iset['uscode'] = isradio(); 

                  console.log('_signin :: crowdcc_validate :: uscode :: '+ ( ccc.sig.iset['uscode'] ));

                  switch ( ccc.sig.iset['uscode'] ) {

                    case ('new_usr'):

                        console.log('_signin :: crowdcc_validate :: new user'); 
                        
                        switch (true) {

                          case (ccc.sig.iset['sncode'] === '_$twitter'):
                                ccc.sig.iset['pcode']  = base64_encode(ccc.sig.iset['pcode']);
                                ccc.sig.iset['ecode']  = base64_encode(ccc.sig.iset['ecode']);
                                ccc.sig.iset['sncode'] = 'undefined';
                                ccc.sig.iset['uscode'] = 'cur_usr';
                          break;

                          case (typeof ccc.str.tw.usr.screen_name === 'undefined'):
                                /* a new user signing up to crowdcc, not already signed into twitter (social network) */
                                console.log('_signin :: crowdcc_validate :: new_usr: a new user signing up to crowdcc, not already signed into twitter (social network)'); 

                                ccc.sig.iset['pcode']  = base64_encode(ccc.sig.iset['pcode']);
                                ccc.sig.iset['ecode']  = base64_encode(ccc.sig.iset['ecode']);
                                ccc.sig.iset['sncode'] = 'undefined';
                                ccc.sig.iset['uscode'] = 'new_usr'; /* new_usr flag */
              
                                console.log('_signin :: crowdcc_validate :: new_usr :: ccstate error_tcode'); 

                                ccstate('error_tcode');
                                                              
                                return false;
                          break;

                          case (typeof ccc.str.tw.usr.screen_name !== 'undefined'):
                                /* a user signed into social network, identified as a new user signing up to crowdcc (new_usr) */
                                console.log('_signin :: crowdcc_validate :: new_usr: a user signed into social network, identified as a new user signing up to crowdcc (new_usr)');
                                console.log(ccc.str.tw.usr.screen_name); 
                                
                                ccc.sig.iset['sncode'] = ccc.str.tw.usr.screen_name;
                                ccc.sig.iset['uscode'] = 'new_usr';
                                
                                /* test * either ccc.sig.iset[uscode] = new_usr or ccc.sig.iset[uscode] = new_usr_upd */

                          break;
                        }

                    break;

                    case ('cur_usr'):

                          console.log('_signin :: crowdcc_validate :: cur_usr');
                          console.log('_signin :: crowdcc_validate :: cur_usr ccc.sig.iset[uscode] ==' + ccc.sig.iset['uscode']);
                          console.log('_signin :: crowdcc_validate :: cur_usr ccc.sig.iset[ecode] ==' + ccc.sig.iset['ecode']);
                          console.log('_signin :: crowdcc_validate :: cur_usr  ccc.sig.iset[pcode] ==' + ccc.sig.iset['pcode']);
                          console.log('_signin :: crowdcc_validate :: cur_usr ccc.sig.iset[sncode] ==' + ccc.sig.iset['sncode']);
                          console.log('_signin :: crowdcc_validate :: cur_usr ccc.sig.iset[encode] ==' + ccc.sig.iset['encode']);

                        switch (true) {
                          case (typeof ccc.str.tw.usr.screen_name === 'undefined'):                
                                /* a user not currently signed into twitter (social network) and not a currently a crowdcc user (new_usr) */
                                console.log('new_usr: a user not currently signed into twitter (social network) and not a currently a crowdcc user (new_usr)');
                                
                                ccc.sig.iset['pcode']  = base64_encode(ccc.sig.iset['pcode']);
                                ccc.sig.iset['ecode']  = base64_encode(ccc.sig.iset['ecode']);
                                ccc.sig.iset['sncode'] = 'undefined';
                                ccc.sig.iset['uscode'] = 'cur_usr';
                                
                          break;

                          case (typeof ccc.str.tw.usr.screen_name !== 'undefined'):
                                /* a user signed into social network, signing up as a new crowdcc user (new_usr) */
                                console.log('new_usr: a user signed into social network, signing up as a new crowdcc user (new_usr)');        
                                
                                ccc.sig.iset['pcode']  = base64_encode(ccc.sig.iset['pcode']);
                                ccc.sig.iset['ecode']  = base64_encode(ccc.sig.iset['ecode']);
                                ccc.str.tw.usr.ccmail0 = ccc.sig.iset['ecode'];

                                console.log(ccc.str.tw.usr.screen_name);

                                ccc.sig.iset['sncode'] = ccc.str.tw.usr.screen_name;
                                ccc.sig.iset['uscode'] = 'new_usr_upd';

                                /* ccc.sig.iset['uscode'] = 'new_usr'; */
                                /* test * either ccc.sig.iset[uscode] = new_usr or ccc.sig.iset[uscode] = new_usr_upd */
                                if ( document.getElementById('modal-social-follow-complete-checkbox').checked ) { ccc.sig.iset['kcode'] = ccc.sig.iset['kcode'] + 1; console.log('kcode + 1 ... !'); }

                          break;
                        }
                    break; /* cur_usr ... */
                  }
            break; /* new_usr ... */
          }
    break; /* crowdcc_validate ... */
      
    case ('rst_usr'):

          console.log('_signin :: crowdcc_validate :: rst_usr reset check encode =>' + ccc.sig.iset['encode'] );

          ccc.sig.iset['ecode']  = ccc.sig.iset['encode'];
          ccc.sig.iset['pcode']  = ccc.sig.iset['encode'];
          ccc.sig.iset['sncode'] = ccc.sig.iset['uscode'] = '_reset';
           
          console.log('_signin :: crowdcc_validate :: rst_usr :: ccc.sig.iset[uscode] =>' + ccc.sig.iset['uscode']);
          console.log('_signin :: crowdcc_validate :: rst_usr :: ccc.sig.iset[ecode]  =>' + ccc.sig.iset['ecode']); 
          console.log('_signin :: crowdcc_validate :: rst_usr :: ccc.sig.iset[pcode]  =>' + ccc.sig.iset['pcode']);
          console.log('_signin :: crowdcc_validate :: rst_usr :: ccc.sig.iset[sncode] =>' + ccc.sig.iset['sncode']);
          console.log('_signin :: crowdcc_validate :: rst_usr :: ccc.sig.iset[encode] =>' + ccc.sig.iset['encode']);

          set_html_val('passcode_account_settings', '');
          set_css_id('passcode_account_settings', 'borderColor', '');
          set_html_val('passcode_account_settings_verify', '');
          set_css_id('passcode_account_settings', 'borderColor', '');
          set_html_id('pcode_account_settings_msg', '');
          set_html_id('account_settings_msg', '');
          set_css_class('ui-dialog-noticebar-close', 'display', 'none');

          console.log('_signin :: crowdcc_validate :: rst_usr :: we are _reset');

    break;

  }

  ccstate('load');

  console.log('_signin :: post data :: callin');
  console.log('_signin :: ccc.sig.iset[ecode] ==' + base64_decode(ccc.sig.iset['ecode']));
  console.log('_signin :: ccc.sig.iset[pcode] ==' + base64_decode(ccc.sig.iset['pcode']));
              
  console.log('_signin :: ccc.sig.iset[sncode] ==' + ccc.sig.iset['sncode']);
  console.log('_signin :: ccc.sig.iset[uscode] ==' + ccc.sig.iset['uscode']);
  console.log('_signin :: ccc.sig.iset[kcode] ==' + ccc.sig.iset['kcode']);

  console.log('_signin :: ccc.sig.iset[pltfrm] ==' + ccc.sig.iset['pltfrm']);
  console.log('_signin :: ccc.sig.iset[browsr] ==' + ccc.sig.iset['browsr']);
  console.log('_signin :: ccc.sig.iset[timezo] ==' + ccc.sig.iset['timezo']);

  ccc.sig.iset['_ccc'] = '_ccc' + '=' + encrypt(base64_decode(ccc.sig.iset['ecode'])) + ':' + encrypt(base64_decode(ccc.sig.iset['pcode'])) + ':' + base64_encode(ccc.sig.iset['sncode']) + ':' + base64_encode(ccc.sig.iset['uscode']) + ':' + base64_encode(ccc.sig.iset['pltfrm']) + ':' + base64_encode(ccc.sig.iset['browsr']) + ':' + base64_encode(ccc.sig.iset['timezo']) + ':' + base64_encode(ccc.sig.iset['kcode']);

  console.log('_signin :: _process post :: callin');
  /* alert('ready to post ... check kcode does it equal anything!'); */

  /* need to add twitter connection check * before connecting directly to twitter */
  _process('post', '_ccc', ccc.sig.iset['_ccc'] ,'callin');
       
  /* toggle spinner function * assume * twitter connection ok */
  ccc_toploader();
  
  /* return to signoff and display message if no twitter connection * ccc_signoff();console.log('network connection does not exist!'); */

}

function see_me() {
  if (visible_css_id('sign')) {

   set_css_id('sign','display','none');
   set_css_id('acc','display','block');

   console.log('we are run!');

  } else {

   set_css_id('sign','display','block');
   set_css_id('acc','display','none');

  }

}

function see_load() {

  if (visible_css_id('acc')) {
  
   set_css_id('acc','display','none');
   set_css_id('sign','display','block');
  
  } else {
  
   set_css_id('acc','display','block');
   set_css_id('sign','display','none');
  
  }

}


function ccc_toploader(msg) {

  console.log('ccc_toploader :: message :' + msg);

  if (msg === 'notweet') {
  /* special usr case * without API content * no tweets * in zero tweets * we are signing in now */
      set_css_id('sign', 'display', 'none');
      set_css_id('acc', 'display', 'block');
      set_css_id('loader','display', 'none');
      set_css_id('me','display', 'block');
      set_css_id('signon','display', 'block');
      set_css_id('top-loader','display', 'none');
      
      console.log('ccc_toploader :: notweet :: we are signed in');
  }

  if ( typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null ) {

    /* in-app loader event * toggle * loader */
    switch (msg) {

      case (msg === 'toggle'):
            /* we are currently signed in */
            if (visible_css_id('loader')) {
                set_css_id('loader','display', 'none');
                set_css_id('me','display', 'block');
            } else {
                set_css_id('me','display', 'none');
                set_css_id('loader','display', 'block');
            }
      break;

    }
    /* default * without parameters */
    switch (true) {

      case (visible_css_id('sign')):
            /* we are signing in now */
            set_css_id('sign', 'display', 'none');
            set_css_id('acc', 'display', 'block');
            set_css_id('loader','display', 'none');
            set_css_id('me','display', 'block');
            set_css_id('signon','display', 'block');
            set_css_id('top-loader','display', 'none');

            console.log('ccc_toploader :: sign :: we are signed in');
      break;
    }

  } else {

    switch (true) {

      case (visible_css_id('signon')):
            set_css_id('signon','display', 'none');
            set_css_id('sigfrm','display','none');
            set_css_id('top-loader','display', 'block');
      break;
      case (!visible_css_id('signon')):
            set_css_id('signon','display', 'block');
            set_css_id('top-loader','display', 'none');
      break;
    }
  }
}


function ccc_signoff() {
  set_css_id('sigfrm', 'display', 'none');
  set_css_id('accaller', 'display', 'none');
  set_css_id('accfrm', 'display', 'none');
  set_css_id('account', 'display','none');
  toggle_signin('reset');
}

function ccc_signon() {
  set_css_id('sigfrm','height', '312px');
  set_css_id('sigfrm','display', 'block');
  set_css_id('accaller','display', 'block');
}

function _error_signin(data) {
     
  /*  error handler for normal scope errors :
      error_pc4de - connection, could not connect to twitter, returned from Oauth lib */

  var err_str = JSON.stringify(data);

  /*  error_pc4de -> Could not connect to Twitter. Refresh the page or try again later.
      search string for error message returned, or return error codes ... then display to user
      for now display generic connection error message to user ... */

  console.log(err_str);
  err_str = null;

  set_css_class('controls', 'display', 'none');
  set_css_id('accfrm', 'display', 'none');
  set_css_class('notices', 'display', 'block');
  set_css_class('error_pc4de', 'display', 'block');
  set_css_class('ui-dialog-noticebar-close', 'display', 'block');
  isbarone('fix');
  
  /* clear down signin details ... */
  _clear_sign();

}


function _instancc() {
 
/* crowdcc app and crowdcc messages for action only running in browser window 
 * so as to prevent two crowdcc apps running and creating a session storage
 * client-side problem ...      
 */

  var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('//', '/');
  
  /* see ccc.js */
  console.log('_instancc :: ' + _get_cookie('__icc') );
  console.log('_instance :: function run');

  switch (true) {

    case (document.cookie.indexOf("__icc=true") === -1):
          document.cookie = "__icc=true;path=/";
          /* Set the onunload function */
          window.onunload = function(){ document.cookie ="__icc=true;path=/;expires=Thu, 01-Jan-1970 00:00:01 GMT";};
          /* load the only instance of app currently running */
          console.log('_instancc :: signin module start');
    break;

    case (visible_css_class('error')):
          /* don't redirect if error message is displayed */
    break;      
            
    case (document.getElementById('acc').style.display === 'none'):
    case (typeof ccc.str.tw.usr.ccuser === 'undefined'):
          /* don't redirect if signed in */
          if ( sessionStorage.length === 0 ) {
              window.location.replace(baseurl + '@');
          }
    break;
  }
}


function _ccoff() {

  var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('//', '/');
  var fullurl = document.URL;  /* or  window.location.pathname + window.location.search; */

  console.log('_ccoff :: ' + baseurl);
  console.log('_ccoff :: ' + fullurl);

  if (baseurl === fullurl) {
      console.log('_ccoff :: baseurl and fullurl are the same!');
      window.location.replace(baseurl + '/' + '@');
  }

} 


function _ccleanup(msg) {

  /* wipe down all notice message bars, may need to custom wipe down for, ccn or ccc. */
  /* signin view check * in * default view * low server cost */

  switch (true) {

    case (typeof ccc.str.tw.usr.ccuser !== 'undefined'):
    case (typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null ):
          /* signin check * in * view * low server cost */
          console.log('_ccleanup() ... signin check * signed in!');

          set_css_id('su', 'display', 'none');
          set_css_id('sign', 'display', 'none');
          set_css_id('sdn_cc', 'display', 'none');
          set_css_id('acc', 'display', 'block');
          set_css_id('in', 'display', 'block');

          /* set * re-set * refavor flags */
          ccc.str.tw.tm[14] = 0; ccc.str.tw.tm[15] = 0; ccc.str.tw.tm[16] = 0; ccc.str.tw.tm[17] = 0; ccc.str.tw.tm[19] = 0; ccc.str.tw.tm[20] = 0; ccc.str.tw.tm[21] = 0; ccc.str.tw.tm[22] = 0; ccc.str.tw.tm[23] = 0;

          _global_actions('enable');

    break;                  
  
    case (typeof ccc.str.tw.usr.ccuser === 'undefined'):
          /* signout check * in * view * low server cost */
          console.log('_ccleanup() ... signin check * signed out!');
          _reset_vars();

    break;
    
  }

  /* init topbar icon color */
  
  set_css_class('icon-ccc_large', 'color', '#484848');

  /* clear down vars */

  ccc.sig.iset['ecode']  = '';
  ccc.sig.iset['ecode']  = '';
  ccc.sig.iset['pcode']  = '';
  ccc.sig.iset['sncode'] = '';

  /* clear signin email and passcode input fields */

  set_html_val('email','');
  set_html_val('passcode','');
  set_css_id('accaller','display','none');

  console.log('_ccleanup :: notices display none');

  set_css_class('notices', 'display', 'none');
  set_css_class('error_tamper', 'display', 'none');
  set_css_class('error_ucode', 'display', 'none');
  set_css_class('error_ecode', 'display', 'none');
  set_css_class('error_pcode', 'display', 'none');
  set_css_class('error_pc0de', 'display', 'none');
  set_css_class('error_pc1de', 'display', 'none');
  set_css_class('error_pc4de', 'display', 'none');
  set_css_class('error_ec0de', 'display', 'none');
        
  set_css_class('error_ec41e', 'display', 'none');
        
  set_css_class('error_ec2de', 'display', 'none');
  set_css_class('psnd', 'display', 'none');
  set_css_class('error_em0n', 'display', 'none');
  set_css_class('error_em6n', 'display', 'none');
  set_css_class('esnd', 'display', 'none');
  set_css_class('efim', 'display', 'none');
  set_css_class('error', 'backgroundColor', '#DB3939');
  set_css_class('error', 'borderBottomcolor', '#AC2020');
  set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
 
  /* include _resetdiagccc.sig.iset for test ... */

  set_css_class('ui-dialog', 'display', 'none');
  set_css_class('ui-dialog-noticebar-close', 'display', 'none');
  set_css_class('ui-dialog-content', 'display', 'none');
  set_css_class('ui-dialog-account-space', 'display', 'none');      
  clear_css_class('account-space', 'noclass');
  set_css_class('ui-dialog-account-upgrade', 'display', 'none');
        
  clear_css_class('account-status', 'noclass');

  set_css_class('ui-dialog-social-content', 'display', 'none');
  set_css_class('ui-dialog-email-content', 'display', 'none');
  set_css_class('ui-dialog-password-content', 'display', 'none');
  set_css_class('ui-dialog-password-new', 'display', 'none');
  set_css_class('ui-dialog-email-confirm', 'display', 'none');

  /* this needs conditional check * start */
  set_css_id('modal-text', 'display', 'none');
  set_css_id('modal-inner', 'display', 'none');
  /* this needs conditional check * end */

  set_css_class('ui-dialog-welcome-content', 'display', 'none');

  set_css_class('ui-dialog-account-settings', 'display', 'none');
  clear_css_class('account-settings', 'noclass');
  set_css_class('ui-dialog-account-help', 'display', 'none');
  clear_css_class('account-help', 'noclass');
  set_css_class('modal-notification', 'display', 'none');

  console.log('_ccleanup :: _ccleanup run complete');

}

function process_err(err) {

  window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
  window.history.pushState('', 'crowdcc', 'http://'+ window.location.hostname );

  switch (err) {

    case ('error_ureg'):
          /* set_css_id('error_ureg', 'display', 'block'); isbarone('fix'); */
          _signout('error_ureg');
    break;
    case ('error_ereg'):
          /* set_css_id('error_ereg', 'display', 'block'); isbarone('fix'); */
           _signout('error_ereg');
    break;
    case ('error_tamper'):
          /* set_css_id('error_tamper', 'backgroundColor', 'rgb(219, 57, 57)'); set_css_id('error_tamper', 'display', 'block'); isbarone('fix'); */
          _signout('error_tamper');
    break;

  }
}

function procces() {

  console.log('we are in procces');
  console.log(_cctokenset['emsg']);
  
  set_css_id('modal-inner', 'display', 'none');

  set_css_id('signbtn', 'display', 'none');
  set_css_class('hacked_icc', 'backgroundColor', '#DB3939');  
  set_css_class('ui-dialog', 'display', 'none');
  set_css_class('notices', 'display', 'block');
  clear_css_class('acc-settings', 'noclass');

  switch(_cctokenset['emsg']) {

    case('ccc_ecode'):

          set_css_id('modal-text', 'display', 'block');

          set_css_class('ui-dialog', 'display', 'block');
          set_css_class('ui-dialog-content', 'display', 'block');
          /* set_css_id('modal-email', 'display', 'block'); */
          
          set_css_class('ui-dialog-titlebar-close', 'display', 'block');
          set_css_class('ui-dialog-email-confirm', 'display', 'block');
          set_attrib_id('eshow_reset', 'placeholder', base64_decode(_ccode['ceode']));
          set_css_id('eirm_retype', 'focus', 'focus');
          set_prop_id('eirm-ccc', 'disabled');

          _cctokenset['pcode'] = geturlparameter('up');
          var backlen = -1; 
          history.go(-backlen);

          window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
          
    break;

    case('pass_pcode'):

          set_css_id('modal-text', 'display', 'block');

          set_css_class('ui-dialog', 'display', 'block');
          set_css_class('ui-dialog-content', 'display', 'block');
          /* set_css_id('modal-email', 'display', 'block'); */
          
          set_css_class('ui-dialog-titlebar-close', 'display', 'block');
          set_css_class('ui-dialog-password-new', 'display', 'block');
          set_prop_id('new-ccc', 'disabled');
          
    break; 

    case('error_pc0de'):
    /*    timestamp is invalid or expired or no token string match! */
            
          set_css_class('error_pc0de', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          isbarone('fix');

          window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
          
    break;

    case('error_pc3de'):
    /*    invalid link or password already changed! */
        
    case('error_pc2de'):
    /*    the email record not found / is bad! */      
     
    case('error_pc1de'):
    /*    error updating the tokenstore db record */
             
          set_css_class('error_pc1de', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          isbarone('fix');

          window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );

    break;

    case('error_ec0de'):
    /*    invalid link or link expired. */
          
          set_css_class('ui-dialog', 'display', 'none');    
          
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('error_ec0de', 'display', 'block');
          isbarone('fix');

          window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
              
          console.log('we are error_ec0de');

    break;

    case('error_ec1de'):
    /*    invalid link or already updated, please sign in to your account, and try again. */
          
          set_css_class('ui-dialog', 'display', 'none');    
          
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('error_ec1de', 'display', 'block');
          isbarone('fix');

          window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
              
          console.log('we are error_ec1de');

    break;

  }

} 


function _pinsin(data) {

  console.log(data);
  console.log('_pinsin :: ' + _cctokenset['ecode']);

  switch(true) {

    case(_cctokenset['ecode'].length === 84 && ccc.sig.isvalid['vp1'] == '1' && ccc.sig.isvalid['vp2'] == '1'):
   
          /* the data (pcode) passed, needs to be encrypted, before sending over the wire. */
          _cctokenset['pcode'] = encrypt(base64_decode(data));
          _cctokenset['_ccc'] = '_ccc' + '=' + _cctokenset['ecode'] + _cctokenset['pcode'];
          _process('post', '_sin', _cctokenset['_ccc'] ,'verify');
                     
    break;

    case(_cctokenset['ecode'].length !== 84):
    /*    timestamp is invalid or expired or no token string match! */
            
          set_css_class('error_pc0de', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          isbarone('fix');

          window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );

    break;

  }
    
}


function _eirmin(data) {

  switch(true) {

    case(_cctokenset['ecode'].length === 84 && _cctokenset['emsg'] == 'ccc_ecode'):


          var _cconfirm = {'_cci': ''};

          /* clean up signin to twitter history, return to app.
             var Backlen=history.length;
             var backlen = -1; 
             history.go(-backlen);
             window.history.replaceState('', 'login Twitter', 'http://'+ window.location.hostname ); moved to procces() */

          console.log('_eirmin :: ecode: ' + _cctokenset['ecode'] );
          console.log('_eirmin :: ceode: ' + base64_decode(_ccode['ceode']) );
          console.log('_eirmin :: data : ' + data);
      
          _cconfirm['_cci'] = '_cci' + '=' + base64_encode(_cctokenset['pcode']) + ':' + base64_encode(_cctokenset['ecode']) + ':' + encrypt(base64_decode(_ccode['ceode'])) + ':' + encrypt(data) + ':' + base64_encode((Math.round(new Date().getTime() / 1000)).toString(16)) + ':' + base64_encode(ccc.sig.isplatform.Any()) + ':' + base64_encode(ccc.sig.isbrowser.Any()) + ':' + base64_encode(istimezone());
        
          console.log(data);

          _process('post', '_cci', _cconfirm['_cci'] ,'confirm');
        
    break;

  }
    
}


function _confirm(data) {

  console.log('_confirm :: email change confirm function');
  /* check if email address updated to address in use */
  /* alert('we are in confirm!'); */

  if ( get_html_id('messagetxt').indexOf('is already in use') === -1 && visible_css_id('error_mailinuse') === false ) {

   switch (data) {
    
    case ('_cco'):

        var _usr = { '0': 0, '1': 0, '2': 0, '3': 0 };
      
        _usr['0'] = ccc.str.tw.usr.ccmail0; /* original email address */
        _usr['1'] = ccc.str.tw.usr.ccmail1; /* _ccu or new email address */
        _usr['2'] = ccc.str.tw.usr.ccmail2; /* original email address [ copy * backup ] */
        _usr['3'] = ccc.str.tw.usr.ccuser;  /* ccn * user status */

        console.log('_confirm :: start email set * update');

        console.log( '_confirm :: ccc.str.tw.usr.ccmail0 === ' + base64_decode(ccc.str.tw.usr.ccmail0) );
        console.log( '_confirm :: ccc.str.tw.usr.ccmail1 === ' + base64_decode(ccc.str.tw.usr.ccmail1) );
        console.log( '_confirm :: ccc.str.tw.usr.ccmail2 === ' + base64_decode(ccc.str.tw.usr.ccmail2) );
        console.log( '_confirm :: ccc.str.tw.usr.ccuser === ' + ccc.str.tw.usr.ccuser );

        ccc.sig.iset['pcode'] = geturlparameter('up');

        console.log( '_confirm :: ccc.sig.iset[pcode] === ' +  ccc.sig.iset['pcode'] );
        console.log( '_confirm :: ccc.sig.iset[ecode] === ' + base64_decode(ccc.sig.iset['ecode']) );
        console.log( '_confirm :: ccc.sig.iset[encode] === ' + base64_decode(ccc.sig.iset['encode']) );

        var _cconfirm = {'_cco': ''};

        switch (true) {

          case (_usr['0'] === _usr['1']):
                /* email  has been already updated once, so in order to correct client   */
                /* state change (without further server overhead and decreased security) */
                /* we need to ask the client to resignin to web app ...                  */
                
                console.log('_confirm :: error ec3de message!');
                
                ccstate('error_ec3de');
                return true;
          break;
              
          case (typeof _usr['3'] !== "undefined"):
                       
                if ( isemail( base64_decode(_usr['1']) ) ) {
                       
                    _cconfirm['_cco'] = '_cco' + '=' +  encrypt(base64_decode(ccc.str.tw.usr.ccmail1)) + ':' + encrypt(base64_decode(ccc.str.tw.usr.ccmail0)) + ':' + base64_encode(ccc.sig.iset['pcode']) + ':' + base64_encode((Math.round(new Date().getTime() / 1000)).toString(16)) + ':' + base64_encode(ccc.sig.isplatform.Any()) + ':' + base64_encode(ccc.sig.isbrowser.Any()) + ':' + base64_encode(istimezone());
     
                    console.log('ccc.str.tw.usr.ccname is an email => email update detected');
                } else {
      
                    _cconfirm['_cco'] = '_cco' + '=' +  encrypt('_ccu') + ':' + encrypt(base64_decode(ccc.str.tw.usr.ccmail0)) + ':' + base64_encode(ccc.sig.iset['pcode']) + ':' + base64_encode((Math.round(new Date().getTime() / 1000)).toString(16)) + ':' + base64_encode(ccc.sig.isplatform.Any()) + ':' + base64_encode(ccc.sig.isbrowser.Any()) + ':' + base64_encode(istimezone());

                    console.log('ccc.str.tw.usr.ccname is not an email => email confirm detected');
                }

                console.log('ccc.str.tw.usr.ccuser !== undefined');
          break;

          case (typeof _cctokenset['ecode'] !== "undefined"):
                            
                _cconfirm['_cco'] = '_cco' + '=' +  encrypt('_ccu') + ':' + encrypt(base64_decode(_ccode['ceode'])) + ':' + base64_encode(ccc.sig.iset['pcode']) + ':' + base64_encode((Math.round(new Date().getTime() / 1000)).toString(16)) + ':' + base64_encode(ccc.sig.isplatform.Any()) + ':' + base64_encode(ccc.sig.isbrowser.Any()) + ':' + base64_encode(istimezone());
                 
                console.log('_cctokenset[ecode] !== undefined');
          break;

          case (typeof ccc.sig.iset['encode'] !== "undefined"):
                 
                _cconfirm['_cco'] = '_cco' + '=' +  encrypt('_ccu') + ':' + encrypt(base64_decode(_ccode['encode'])) + ':' + base64_encode(ccc.sig.iset['pcode']) + ':' + base64_encode((Math.round(new Date().getTime() / 1000)).toString(16)) + ':' + base64_encode(ccc.sig.isplatform.Any()) + ':' + base64_encode(ccc.sig.isbrowser.Any()) + ':' + base64_encode(istimezone());
                 
                console.log('ccc.sig.iset[encode] !== undefined');
          break;

        }

        _process('post', '_cco', _cconfirm['_cco'] ,'confirm');

    break;

   }

    _usr['0'] = null; _usr['1'] = null; _usr['2'] = null; _usr['3'] = null; _usr = null;

  } else {

    console.log('email already in use ... close info bar * re-set update email process!');
    set_html_id('messagetxt', 'Confirm your email address to access all crowdcc features.');
       
    /* we have signed you out to protect your account * prevent mutiple email already in use requests! */
    _signout('error_mailinuse');
  }
            
}


function toggle_signin(msg) {

  console.log( get_css_id('sigfrm','height','104px') );
  console.log( msg );
        
  switch (true) {

    case ( get_css_id('sigfrm','height','312px') ):
           
           console.log('menu close');

           set_css_id('tt-acc-usr','display','block');
           set_css_id('divide-one','display','block');

           set_css_id('sigfrm','height','104px');
           set_css_id('signin-content','display','none');

           set_css_id('cc-acc-usr','backgroundColor','#FFFFFF');
           set_css_class('signin-menu', 'color', ''); 
           set_css_class('withcc', 'color', '#66757F'); 

           if (msg === 'reset') {
             
             set_css_id('accaller', 'display','none');
             set_css_id('sigfrm', 'display', 'none');

             set_css_id('top-loader', 'display', 'none');
             set_css_id('signon', 'display', 'block');

           }
    break;

    case ( get_css_id('sigfrm','height','104px') ):
           if (msg !== 'reset') {
             
             console.log('menu open');

             set_css_id('tt-acc-usr','display','none');
             set_css_id('divide-one','display','none');
         
             set_css_id('sigfrm','height','312px');

             set_css_id('cc-acc-usr','backgroundColor','#1C1C2F');
             set_css_class('signin-menu', 'color', '#FFFFFF'); 

             set_css_id('signin-content','display','block');
             set_css_id('email', 'focus', 'focus');

           } else {

             set_css_id('accaller', 'display','none');
             set_css_id('sigfrm', 'display', 'none');
            
           }
    break;

  }
 
};


function _toggle_menu(msg) {

  console.log('we are here first ...');
  console.log( visible_css_id('accfrm') );
       
  switch(true) {

    case ( visible_css_id('accfrm') ):
        
           set_css_id('accfrm', 'display', 'none');

    break;

    case (! visible_css_id('accfrm') ):

        set_css_id('account', 'display', 'block');
        set_css_id('accfrm', 'height', '186px');
        set_css_id('accfrm', 'width', '176px');

        switch(msg) {

          case ('soc'):
                  
                console.log('are we here ... at soc');
            
                set_css_id('account-signup', 'display', 'block');
                set_css_id('divide-one', 'display', 'none');
                set_css_id('account-settings', 'display', 'none');
                set_css_id('account-space', 'display', 'block');

          break;

          case('ccn'):

                console.log('are we here ... at ccn');
    
                set_css_id('divide-one', 'display', 'block');
                set_css_id('account-space', 'display', 'block');
                set_css_id('account-signup', 'display', 'none');
                set_css_id('account-confirm', 'display', 'block');
       
          break;
             
          case('ccc'):

                console.log('are we here ... at ccc');

                set_html_id('account-status', 'upgrade');
                set_css_id('account-status', 'display', 'block');
                set_css_id('divide-one', 'display', 'block');
                set_css_id('account-settings', 'display', 'block');
                clear_css_class('account-settings', 'noclass');
                set_css_id('account-settings', 'color', '#333333');

                set_css_id('account-confirm', 'display', 'none');

                set_css_id('account-space', 'display', 'block');
                set_css_id('account-signup', 'display', 'none');
           
          break;

        }

    break;

  }  
   
};


function signin_status(network, status) {

  switch (true) {
        
    case (status && network != ''):
          
          console.log("Logged in to " + network);
          
          return network;
    break;
    case (status == false && network != ''):
          
          console.log("Not logged in to " + network);
    
    break;

  }
          
}  
 
function process_obj(data, ceode) {

  /* 

  02/07/2104 upgrade function, receive two JSON objects, rebuild client JSON object. 

  old method was using an array, example ;

  [0] - soc (flag / status)
  [1] - username (base64_encoded)
  [2] - email or _$twitter ( primary email position )
  [3] - _ccu (base64_encoded) (secondary email position )
  [4] - _$twitter ( backup email position )

  new method is to rebuild an object to merge flag/status settings and twitter object
 
  0 ccuser   - soc (flag / status)
  1 ccname   - username
  2 ccmail1  - email ( primary email position )
  3 ccmail2  - _ccu ( base64_encoded) (secondary email position )
  4 ccmail3  - _$twitter ( backup email postion )
  5 ccfollow - 0 (or 1)

  */

  var jdata = '';
  var odata = {};

  console.log('....we are in process_obj');
  console.log('... we have either come from email signin: === data requires JSON.parse OR');
  console.log('... we have come from Oauth === data is already an object!');

  console.log(data);

  switch (true) {

    case (typeof data === 'object'):
          jdata = data;
    break;
    case (typeof data !== 'object'):
          jdata = JSON.parse(data);
    break;
    
  }

  console.log('flag => ' + jdata.usr['ccuser']);
   
  console.log(jdata);

  for (var obj in jdata){
    if (jdata.hasOwnProperty(obj)) {
      for (var prop in jdata[obj]) {
        if(jdata[obj].hasOwnProperty(prop)){
            /* console.log(prop + ':' + jdata[obj][prop]); */
            odata[prop] = jdata[obj][prop];
        }
      }
    }
  }

  console.log(odata);
  ccc.str.tw.usr = odata;

  /* Session.set('tw', tw); * moved to global namespace */
  ccsession.set('tw',ccc.str.tw);

  switch (true) {
    case (ccc.sig.iset['ecode'] === ''):

          ccc.str.tw.usr.ccmail1 =  base64_encode('_ccu');  // ( secondary email position )
    break;
    case (ccc.sig.iset['ecode'] !== ''):
        
          ccc.str.tw.usr.ccmail1 =  base64_encode('_ccu');  // ( secondary email position )
    break; 
  }

  console.log('end of new object build project ...');

  /* read_array() added jdata.usr['ccuser'] to pass user status flag => soc, ccn, ccc */

  read_array(jdata.usr['ccuser']);

  /* _toggle_menu(jdata.usr['ccuser']) added with the user status flag in order to set-up user style menu */

  _toggle_menu(jdata.usr['ccuser']);

  jdata = null;
  
}  


function access_obj(){

  for(var usr in tw) {
      console.log('key: ' + usr + '\n' + 'value: ' + tw[usr]);
  }

  usr = null;

}

function read_array(msg) {

  console.log('helo read_array ');

  /* ccc.str.tw.usr.ccmail0 --> email0@gmail.com */
  /* ccc.str.tw.usr.ccname  --> email1@gmail.com */
  /* ccc.str.tw.usr.ccuser status --> ccc */

  var create_HTML = '';
  var playleft_HTML = '';
  var playright_HTML = '';

  var fromimg_HTML = '';
  var fromuser_HTML = '';

  var _usr = { '0': 0, '1': 0, '2': 0, '3': 0, '4': 0};
      
  _usr['0'] = ccc.str.tw.usr.ccuser;
      
  console.log(msg);
  console.log( '_usr[0] :' + _usr['0'] );

  if (_usr['0']) {
 
      _usr['1'] = base64_decode( ccc.str.tw.usr.ccmail1 );
      _usr['4'] = base64_decode( ccc.str.tw.usr.ccmail2 );

      switch (true) {

        case (_usr['1'] === '_ccu'):
        
              ccc.sig.iset['encode'] = ccc.str.tw.usr.ccmail0;
              ccc.sig.iset['ucode']  = ccc.str.tw.usr.ccname;

              console.log('msg -> ccc.str.tw.usr.ccname === _ccu');
        break;

        case (_usr['1'] !== '_ccu'):
          
              ccc.sig.iset['encode'] = ccc.str.tw.usr.ccmail1;
              ccc.sig.iset['ucode']  = ccc.str.tw.usr.ccname;

              console.log('msg -> ccc.str.tw.usr.ccname !== _ccu');      
        break;

      }

      /* option * if (isemail(_usr['1'])) {ccc.sig.iset['encode'] = ccc.str.tw.usr.ccname; ccc.sig.iset['ucode'] = ccc.str.tw.usr.ccmail2;} */
      /* option * if (isemail(_usr['4'])) { ccc.str.tw.usr.ccmail2 = ccc.str.tw.usr.ccname; } */
     
      /* check abortive email change / update is now reset ... */
      console.log('ccc.str.tw.usr.ccmail2 --> ' + base64_decode( ccc.str.tw.usr.ccmail2 ));
      console.log('ccc.str.tw.usr.ccmail1 --> ' + base64_decode( ccc.str.tw.usr.ccmail1 ));
      console.log('ccc.str.tw.usr.ccmail0 --> ' + base64_decode( ccc.str.tw.usr.ccmail0 ));
      console.log('ccc.str.tw.usr.ccname --> ' + base64_decode( ccc.str.tw.usr.ccname ));
      console.log('ccc.str.tw.usr.ccuser --> ' + ccc.str.tw.usr.ccuser);
      console.log('ccc.str.tw.usr.cclimit --> ' + ccc.str.tw.usr.cclimit);
      console.log('ccc.str.tw.usr.ccspace --> ' + ccc.str.tw.usr.ccspace);
          
  }

  switch (true) {
    case (typeof ccc.str.tw.usr.ccuser !== 'undefined'):

          if (base64_decode(ccc.str.tw.usr.ccmail1) === '_ccu') {
              set_html_class('messagetxt', 'Confirm email ' + base64_decode(ccc.sig.iset['encode']) + '. ');
          } else {
              set_html_class('messagetxt', 'Confirm new email ' + base64_decode(ccc.sig.iset['encode']) + '. ');
          }

    break;
  }

  /* set instance cookie so as to prevent multiple signin and protect the local storage from corruption */

  if (document.cookie.indexOf("__icc=true") === -1) { document.cookie = "__icc=true;path=/"; window.onunload = function(){
      document.cookie ="__icc=true;path=/;expires=Thu, 01-Jan-1970 00:00:01 GMT"; } };

      console.log('instance cookie set to true ...');

      /* _display('in'); // in * api media feed signin view   */

      /* global static vars */

      ccc.str.tw.cg[0] = 10;     /* current page set depth */

      /* start the api local time function */

     if ( typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null ) {
          
         console.log('signin.js => pause(); timer paused, is that what you want ?');
         pausecc();

         /* pause() is in correct position for normal operations * check return from * adding email and password for confirmation * see 1678 * 1695 */
         /* if (ccc.str.tw.ts[0] !== 0) { resumecc(); console.log('resume(); timer resumed, is that what you want ?'); } */
         
      } else {

          console.log('signin.js => startcc(); timer re-set from the start, is that what you want ?');

          ccc.str.tw.tp[0] = 0;      /* init api time (get more fwd content) (every 3 mins or so ...) */
          ccc.str.tw.tg[0] = 'ccc';  /* init tg first state */
          resetcc(); startcc(); /* start the api local time function */

      }
  

      switch (true) {

        case (typeof ccc.str.tw.cp[0] === 'undefined'):
        case (ccc.str.tw.cp[0] === 0):
              console.log('first run ccc.str.tw.cc ...');

              /* ccc.str.tw.usr.cclimit); * 10 start cc limit * page * ccc.str.tw.usr.cclimit * checked adding content */
             
              get_in_ccobj(ccc.str.tw.usr.screen_name, 0, 10);

              console.log('start * going to check follow status * lazy check * :)');
              get_in_follow();
              console.log('end * going to check follow status * returned * good :)!');
        break;
  
      }    

      switch (true) {
              /* ready to trigger the main app ... */

        case (msg === 'soc'):
              /* status social guest, just visiting */
              console.log('soc status');
              set_css_class('ui-dialog-content', 'display', 'block'); 
              /* set_css_id('modal-email', 'display', 'block'); */
              set_css_id('account-signup', 'display', 'block');
              set_css_class('ui-dialog-titlebar-close', 'display', 'block');

              _process('get', '_kcode', '', 'confirm?token=' + encrypt( base64_decode( ccc.sig.iset['ucode'])) );

              /* message on content * prevent bounce * */

              set_css_id('in','margin-top','50px');
              set_css_id('cc','margin-top','50px');
             
        break;
        case (msg === 'ccn'):
              /* status registered ccn user, non confirmed email address  */
              /* want to show a nag bar with an email confirmation button */
              console.log('ccn status');
               
              set_css_class('ui-dialog-content', 'display', 'block'); 
              /* set_css_class('modal-email', 'display', 'block'); */

              set_css_class('ui-dialog-noticebar-close', 'display', 'block');
              set_css_id('account-signup', 'display', 'none');
              set_css_id('account-confirm', 'display', 'block');  

              /* start * nag bar */
              if (typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null) { get_in_ccn(); }
              // if (typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null) { instore(); get_in_ccn(); }
              /* end * nag bar */
     
              set_css_id('accfrm','display','none'); /* close account dialog */
              set_css_id('signin', 'z-index', 'auto');
         
              ccc.sig.iset['uscode'] = 'cur_usr';

              /* set_css_id('noticebar-close-flix', 'display', 'block');  default close button for ccn user * set in get_in_ccn() */
        break;
        case (msg === 'ccc'):
              /* confirmed registered ccc user, no nag bar, email confirmed */
              console.log('ccc status');

              console.log( 'cauth_token cookie : ' + _get_cookie('cauth_token') );
               
              set_css_class('ui-dialog-content', 'display', 'block');
              /* set_css_id('modal-email', 'display', 'block'); */

              set_css_class('ui-dialog-noticebar-close', 'display', 'block');
              set_css_class('error_ec5de', 'display', 'none');

              set_css_id('account-confirm', 'display', 'none');
              set_css_id('account-settings', 'display', 'block');

              /* set_css_class('acc-upgrade', 'display', 'block'); */

              set_css_class('acc-settings', 'display', 'block');

              set_css_id('signin', 'z-index', 'auto');
              set_css_id('ccc', 'padding-top', '51px');
              set_css_id('pin', 'padding-top', '51px');
 
              /* ccc * remove any notice messages * set margin-top */
          
              set_css_class('controls', 'z-index', '');
              /* console.log('notices display none!'); */
              set_css_class('notices', 'display', 'none');

              set_css_class('pfim', 'display', 'none');
              set_css_class('psnd', 'display', 'none');

              set_css_id('in','margin-top','50px');
              set_css_id('cc','margin-top','50px');               

              ccc.sig.iset['uscode'] = 'cur_usr';

              /* isbarone('fixon'); /* default close button for ccc user */
              set_css_id('noticebar-close-fixon','display','block');
        break;

    }

     _usr = [];

  get_cc_auth(); /* get * set * check space * limit */

}


function _global_actions(msg) {

  console.log('_global_actions called');

  switch (msg) {

    case ('enable'):

          /* ( activate controls at signin :: start ) */

          var create_HTML = ''; var playleft_HTML = ''; var playright_HTML = ''; var fromimg_HTML = ''; var fromuser_HTML = '';

          create_HTML += '<span id="create" class="create icon icon-quill icon--large icon-nibble-right"></span>';
          create_HTML += '<span id="create-txt" class="create text">create</span>';
          set_html_id('create-data', create_HTML);
          create_HTML = null;
          clear_css_class('create-data','create');
   
          playleft_HTML += '<span id="instore" class="instore icon icon-left icon-play_left icon--large"></span>';
          set_html_id('toptiptime-data', playleft_HTML);
          playleft_HTML = null;
          add_css_class('toptiptime-data','toptiptime');
          set_css_class('icon-play_left','color','#111111'); 

          playright_HTML += '<span id="ccstore" class="ccstore icon icon-right icon-play_right icon--large"></span>';
          set_html_id('toptipcarbs-data', playright_HTML);
          playright_HTML = null;
          add_css_class('toptipcarbs-data','toptipcarbs');
          set_css_class('icon-play_right','color','#C0C0C0');

          fromimg_HTML += '<img id="profile_img" class="me account icon avatar-topbar size32" src="'+ccc.str.tw.usr.profile_image_url+'">';
          fromimg_HTML += '<span id="me" class="me textin">me</span>';

          set_html_id('fromuser_img', fromimg_HTML);
          fromimg_HTML = null;

          fromuser_HTML += '<div id="user_id" class="account-group js-mini-current-user" data-screen-name="'+ccc.str.tw.usr.screen_name+'" data-user-id="'+ccc.str.tw.usr.id_str+'">';
          fromuser_HTML += '<div class="fromuser_table clearfix">';
          fromuser_HTML += '<div class="fromuser_table_row">';
          /* fromuser_HTML += '<div class="image"><img class="avatar-menu size32" src="'+ccc.str.tw.usr.profile_image_url+'"></div>'; */
          fromuser_HTML += '<div class="image"><img class="avatar-menu size42" src="'+ccc.str.tw.usr.profile_image_url+'"></div>'; /* update signon avatar size42 */
          fromuser_HTML += '<div class="fromuser">'+ccc.str.tw.usr.name+'</div><div class="fromuser_table_row"><div class="fromuser_profile">@'+ccc.str.tw.usr.screen_name+'</div></div></div>';

          set_html_id('fromuser_id', fromuser_HTML);
          fromuser_HTML = null;
          set_css_id('bottom-pad', 'display', 'block');

          /* ( activate controls at signin :: end ) */

    break;

    case ('unable'):

          /* ( de-activate controls at signout :: start ) */

          set_css_class('nav', 'color', ''); /* defaults to pre-signin nav color #66757f */

          clear_css_class('create-data','nocreate');
          var create_HTML = '';
          create_HTML += '<span id="create" class="nocreate icon icon-quill icon--large icon-nibble-right"></span>';
          create_HTML += '<span id="create-txt" class="nocreate text">create</span>';
          set_html_id('create-data', create_HTML);
          create_HTML = null;

          clear_css_class('toptiptime-data','noclass');
          var playleft_HTML = '';
          playleft_HTML += '<span id="instore" class="icon icon-left icon-play_left icon--large"></span>';
          set_html_id('toptiptime-data', playleft_HTML);
          playleft_HTML = null;
          set_css_class('icon-play_left','color','#DFDFDF'); 

          clear_css_class('toptipcarbs-data','noclass');
          var playright_HTML = '';
          playright_HTML += '<span id="ccstore" class="icon icon-right icon-play_right icon--large"></span>';
          set_html_id('toptipcarbs-data', playright_HTML);
          playright_HTML = null;
          set_css_class('icon-play_right','color','#DFDFDF');

          set_css_id('topbar', 'borderBottomColor', '');

          /* ( de-activate controls at signout :: end ) */

    break;

  }
}


function ccstate(msg) {

  switch (true) {
      
    case (typeof ccc.str.tw.usr.ccuser !== 'undefined'):
             
          /* set_css_class('twitter-header', 'display', 'none'); */
          set_css_class('ui-dialog-account-settings', 'display', 'none');
          /* set_css_class('notices', 'padding-top', '40px'); */

          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#4CA454');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_id('ccc', 'padding-top', '4px');
          set_css_id('pin', 'padding-top', '4px');
      
    break;

    
  }

  if ( msg !== 'load' ) {
       /* exitcc(); */
       /* ccc_toploader(); */      
       set_css_class('notices', 'display', 'block');
  }
    
  switch (true) {

    case (msg === 'load'):
          console.log('... normal load');
          set_css_class('error', 'display', 'none');
          set_css_id('accfrm', 'display', 'none');
    break;

    case (msg === 'error_tamper'):
          _signout('error_tamper');
    break;

    case (msg === 'error_network'):
          _signout('error_network');
    break;

    case (msg === 'error_refresh'):
          /* preserve token url * soft reload */
          history.go(0);
    break;

    case (msg === 'error_pcode'):
          console.log('error_pcode');
          ccc_toploader();
          set_css_class('error_pcode', 'display', 'block');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-social-content', 'display', 'none');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          /* set_css_id('signbtn', 'display', 'block'); */
          set_css_id('accfrm', 'display', 'block');  

          /* maybe optional in order to get forgotten password button to click, check css! */

          set_css_id('timer', 'display', 'none');  
          ccc.sig.iset['pcode'] = '';
          set_html_val('passcode', '');
          set_css_id('passcode', 'focus', 'focus');
    break;

    case (msg === 'error_pc0de'):
          console.log('error_pc0de');
          set_css_class('ui-dialog-content', 'display', 'block');
          /* set_css_id('modal-email', 'display', 'none'); */
          set_css_class('ui-dialog-noticebar-close', 'display', 'none');
    break;

    case (msg === 'error_pc1de'):
          console.log('error_pc1de');
          set_css_class('error_pc1de', 'display', 'block');
          set_css_class('ui-dialog-content', 'display', 'block');
          /* set_css_id('modal-email', 'display', 'none'); */
          set_css_class('ui-dialog-titlebar-close', 'display', 'none');
    break;

    case (msg === 'error_ucode'):
          console.log('error_ucode');
          ccc_toploader();
     
    case (msg === 'error_ecode'):
          console.log('error_ecode');
          set_html_val('email', '');
          set_html_val('passcode', '');

          set_css_class('error_ucode', 'display', 'block');
          set_css_class('ui-dialog-content', 'display', 'block');
          set_css_class('ui-dialog', 'display', 'none');     
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_id('accfrm', 'display', 'none');

          toggle_signin('reset');

          if (typeof ccc.str.tw.usr.screen_name === 'undefined' && ccc.str.tw.usr.screen_name === null ) {
              /* set_css_id('signbtn', 'display', 'block'); */
              /* console.log('line number 1106'); */
          }
    break;

    case (msg === 'error_tcode'):
          /* error_tcode -- user email and password no match to the social network screen/user name, no social network found! */
  
          switch(true) {

            case (typeof ccc.str.tw.usr.screen_name !== 'undefined' && ccc.str.tw.usr.screen_name !== null ):
            /* case (verified === 'verified'):
               case (ccc.str.tw.usr.ccuser === 'soc'):
               case (ccc.str.tw.usr.ccuser === 'ccn'):
               case (ccc.str.tw.usr.ccuser === 'ccc'): */
            /* console.log('already signed in ...?');  */
            break;
            
            case (ccc.sig.iset['sncode'] !== '_reset'):
                  console.log('how come we are here ? no reset');
                  set_css_id('modal-text','display', 'block');
                  set_css_class('ui-dialog', 'display', 'block');
                  set_css_class('ui-dialog-password-content', 'display', 'none');
                  set_css_class('ui-dialog-content', 'display', 'block');
                  set_css_class('ui-dialog-titlebar-close', 'display', 'block');
                  set_css_class('ui-dialog-social-content', 'display', 'block');
                  /* set_css_id('signbtn', 'display', 'block'); */
                  set_css_id('sigfrm', 'display', 'none');
            break;
            case (ccc.sig.iset['sncode'] === '_reset'):
                  console.log('how come we are here ? reset?');
                  set_css_id('modal-text','display', 'none');
                  set_css_class('ui-dialog', 'display', 'none');
                  set_css_class('ui-dialog-password-content', 'display', 'none');
                  ccc.sig.iset['uscode'] = '';
                  set_css_id('signbtn', 'display', 'block');
                  set_css_id('accfrm', 'display', 'block');    
                  ccc.sig.iset['pcode'] = '';
                  set_html_val('passcode', '');
                  set_css_id('passcode', 'focus', 'focus');
            break;

          }

          console.log('error_tcode');

          /* use the uscode array element to communicate the current user status ('new_usr')
             to the ; disable Sign In, and add social network close button on exit,
             default is ccc.sig.iset['uscode'] = 'new_usr'; 
          */
          console.log('leaving => error_tcode');
    break;

    case (msg === 'show_tcode'):
          console.log('show_tcode');
          set_css_id('accfrm', 'display', 'none');
          set_css_id('signinbtn', 'display', 'block');
    break;

    case (msg === 'pass_tcode'):
          /* pass_tcode -- user email and password added to the social network screen/user name, show full menu ... */
          console.log('pass_tcode');

          set_css_id('accfrm', 'display', 'none');  
          /* set_css_id('signbtn', 'display', 'none'); */
          set_css_id('ui-dialog', 'display', 'none');  
          set_css_class('ui-dialog-social-content', 'display', 'none');
          set_css_class('ui-dialog-email-content', 'display', 'none');

          ccc.str.tw.usr.ccuser = 'ccc';
          /* ccode = 'ccc'; */
          _toggle_menu('ccc');
    break;

    case (msg === 'pass_scode_fcode'):
          console.log('pass_scode_fcode');
        
          set_css_id('accfrm', 'display', 'none'); 
          set_css_id('signbtn', 'display', 'none'); 
          set_css_class('twitter-header', 'display', 'block');
          set_css_id('ui-dialog', 'display', 'none'); 
          set_css_class('ui-dialog-social-content', 'display', 'none');
          set_css_class('ui-dialog-email-content', 'display', 'none');
          clear_css_class('account-status', 'noclass');

          ccc.str.tw.usr.ccfollow = 2;

          ccc.str.tw.usr.ccuser = 'ccn';
          /* ccode = 'ccn'; */
          read_array('ccn');
          if (ccc.str.tw.ts[0] !== 0) { resumecc(); console.log('resume(); timer resumed, is that what you want ?'); }
    break;

    case (msg === 'pass_scode'):
          console.log('pass_scode');
        
          set_css_id('accfrm', 'display', 'none'); 
          set_css_id('signbtn', 'display', 'none'); 
          set_css_class('twitter-header', 'display', 'block');
          set_css_id('ui-dialog', 'display', 'none'); 
          set_css_class('ui-dialog-social-content', 'display', 'none');
          set_css_class('ui-dialog-email-content', 'display', 'none');
          clear_css_class('account-status', 'noclass');

          ccc.str.tw.usr.ccuser = 'ccn';
          /* ccode = 'ccn'; */
          read_array('ccn');
          if (ccc.str.tw.ts[0] !== 0) { resumecc(); console.log('resume(); timer resumed, is that what you want ?'); }
    break;

          /* error invalid timestamp or token too old */
    case (msg === 'error_ec0de'):
      
          set_css_class('controls', 'display', 'none');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-email-confirm', 'display', 'none');
          set_css_class('error_ec0de', 'display', 'block');
      
    break;

          /* error invalid timestamp or token too old */
    case (msg === 'error_ec1de'):
            
          set_css_class('controls', 'display', 'none');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-email-confirm', 'display', 'none');
          set_css_class('error_ec1de', 'display', 'block');

    break;

          /* error invalid timestamp or token too old */
    case (msg === 'error_ec2de'):
            
          set_css_class('controls', 'display', 'none');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-email-confirm', 'display', 'none');
          set_css_class('error_ec2de', 'display', 'block');

    break;

          /* email  has been already updated once, so in order to correct client state change  */
    case (msg === 'error_ec3de'):

          set_css_class('controls', 'display', 'none');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-email-confirm', 'display', 'none');
          set_css_class('error_ec3de', 'display', 'block');

          /* console.log('error ec3de test message!'); */
          _signout('error_ec3de');
    break;

          /* error invalid link or password already changed */        
    case (msg === 'error_em0n'):
            
          set_css_class('controls', 'display', 'none');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-email-confirm', 'display', 'none');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('error_em0n', 'display', 'block');
          isbarone('fix');
 
    break;

          /* password invalid! */           
    case (msg === 'error_em6n'):

          set_css_id('modal-text','display','none'); 
          set_css_class('controls', 'display', 'none');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-email-confirm', 'display', 'none');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('error_em6n', 'display', 'block');      
          isbarone('fix');

    break;

    case (msg === 'snd_ecode'):

          /* console.log('we are in ccstate(snd_ecode)'); */

          _sent_banner(base64_decode(ccc.sig.iset['encode']));
        
          console.log('just inform user that the email has been sent to email address given');
    break;

    case (msg === 'rst_ecode'):
            
          _sent_banner(base64_decode(ccc.sig.iset['encode']));
      
          console.log('just inform user that the email has been sent to email address given');

          switch (true) {
            case (typeof(ccc.str.tw.usr.ccname) !== 'undefined'):
                  set_css_class('ui-dialog', 'display', 'none');

                  /*
                    ccc.str.tw.usr.ccmail0 = ccc.str.tw.usr.ccname;
                    ccc.str.tw.usr.ccname = base64_encode('_ccu');
                    ccc.str.tw.usr.ccuser = 'ccn';
                    console.log('reset array positions, ccc.str.tw.usr.ccmail0 == new email, ccc.str.tw.usr.ccname == _ccu (original email is deleted');
                  */
            break;
          }
    break;

    case (msg === 'idb_ecode'):
            
          set_html_class('messagetxt', 'This email ' + base64_decode(ccc.sig.iset['encode']) + ' is already in use. ');
          set_html_class('messageand', ' and ');

          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
            
          console.log('error new email address already in use !');
      
    break;   

    case (msg === 'end_sess'):
          console.log('rtn fatal error the original email address not found !');
    break; 

    case (msg === 'hide'):

          set_css_id('accfrm', 'display', 'none');
          set_css_id('signbtn', 'display', 'block');
          set_css_id('ccc_load', 'display', 'none');
      
    break;

          /* email has been confirmed, next time you signin your account will be updated */
    case (msg === 'pass_emin'):

          console.log('we are in pass emin');
          set_css_id('modal-text','display','none');
          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog', 'display', 'none');    
          set_css_class('ui-dialog-email-confirm', 'display', 'none');

          set_css_class('ui-dialog-noticebar-close', 'display', 'block');      
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#4CA454');
          set_css_class('efim', 'display', 'block');
          isbarone('fix');

    break;

    case (msg === 'pass_pcode'):

          console.log('we are in pass pcode');
          set_css_id('modal-text','display','none');
          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog', 'display', 'none');
          /* set_css_id('modal-email', 'display', 'block'); */
          set_css_class('ui-dialog-content', 'display', 'block');
          set_css_class('ui-dialog-titlebar-close', 'display', 'block');

          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#4CA454');
          set_css_class('pfim', 'display', 'block');
          isbarone('fix');
      
    break;     

    case (msg === 'pass_ecode'):

          set_css_class('ui-dialog', 'display', 'none');
          set_css_id('ui-dialog-password-content', 'display', 'none');
          /* set_css_id('account-space', 'display', 'none'); */
          set_css_id('accfrm', 'width', '215px');
          set_css_class('ui-dialog-titlebar-close', 'display', 'none');
          console.log('now going to signout');

          _signout('pass_ecode');

    break;
  }

}


function _sent_banner(msg) {

  /* console.log('we are in _sent_banner(msg)'); */
  set_html_class('messagetxt', 'Confirm email instructions sent to ' + msg + ' or');

  if (has_css_class('btn_confirm_email', 'btn_confirm_ecode')) {

      set_css_class('error', 'backgroundColor', '#CC8600');
      set_css_class('error', 'borderBottomColor', '#986400');

      set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#CC8600');
      set_css_class('ui-dialog-noticebar-close', 'display', 'none');

      clear_css_class('btn_confirm_email', 'confirm_ecode'); 
      add_css_class('btn_confirm_email', 'btn_confirm_ecode_sent');

  } else {

      set_css_class('error', 'backgroundColor', '#DB3939');
      set_css_class('error', 'borderBottomColor', '#AC2020');

      set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
      set_css_class('ui-dialog-noticebar-close', 'display', 'none');
      
      clear_css_class('btn_confirm_email', 'confirm_ecode'); 
      add_css_class('btn_confirm_email', 'btn_confirm_ecode');
  }

  clear_css_class('acc-settings', 'noclass');  
              
}


function _reset_vars() {

  /* re-set ccwatch.js * start */
  ccc.ccw.tidcc = null;            /* timeout ID for global cc watch */
  ccc.ccw.timemark = new Date();   /* reference time mark */
  ccc.ccw.timemark = 0;            /* relative time left */
  
  ccc.ccw.freq = 1000;             /* 1000 == 1 second */

  ccc.ccw.lm = 1;                  /* load more (backward polling api hits) couple of seconds delay added to to request */
  ccc.ccw.mp = 0;                  /* more page (mp - more page flag) */
  ccc.ccw.ll = 1;                  /* load local delay */
  ccc.ccw.ml = 0;                  /* more local page (ccc.ccw.ml - more local page flag) */
  ccc.ccw.apihits = 0;             /* api hits   */
  ccc.ccw.calhits = 0;             /* local hits */
  ccc.ccw.flameon = 0;
  /* re-set ccwatch.js * end */
  /* re-set store.js * start */
  ccc.str.tw.cc.length = 0;

  ccc.str.tw.usr = {};
  ccc.str.tw.cp[0] = 0;
  ccc.str.tw.ls[0] = 0;
  ccc.str.tw.sc[0] = 0;
  ccc.str.tw.sp[0] = 0;
  ccc.str.tw.tg[0] = 900;
  ccc.str.tw.tm[0] = null;
  ccc.str.tw.tm[1] = null;
  ccc.str.tw.tm[2] = null;
  ccc.str.tw.tm[3] = null;
  ccc.str.tw.tm[4] = null;
  ccc.str.tw.tm[5] = null;
  ccc.str.tw.tm[6] = null;
  ccc.str.tw.tm[7] = null;
  ccc.str.tw.tm[8] = 0;
  ccc.str.tw.tm[9] = null;
  ccc.str.tw.tm[10] = null;
  ccc.str.tw.tm[11] = null;
  ccc.str.tw.tm[12] = null;
  ccc.str.tw.tm[13] = null;
  ccc.str.tw.tm[14] = null;
  ccc.str.tw.tm[15] = null;
  ccc.str.tw.tm[16] = null;
  ccc.str.tw.tm[17] = null;
  ccc.str.tw.tm[18] = null;
  ccc.str.tw.tm[19] = null;
  ccc.str.tw.tm[20] = null;
  ccc.str.tw.tm[21] = null;
  ccc.str.tw.tm[22] = null;
  ccc.str.tw.tm[23] = null;
  ccc.str.tw.tm[24] = 0;

  ccc.str.tw.tp[0] = 120;
  ccc.str.tw.ts[0] = 0;
  ccc.ccw.tidcs = null;
  
  ccc.str.istw = { '_tw': '', 'twtextcc': '', 'twtextcc_rt': '', 'twtextfi': '', 'twtextfi_rt': '', 'twmediafi_url': '', 'mouse': 0, 'ckoff': 0, 'trshid': 0, 'trshcc': 0 };
  
  ccc.str.verified = '';                
  ccc.str.verify = '';
  ccc.str.ccconfirm = '';
  
  /* re-set store.js * end */
  /* re-set signin.js * start */
  ccc.sig.iset = { 'ccc.sig.iset': '', 'ucode': '', 'ecode': '', 'encode': '', 'pcode': '', 'sncode': '', 'uscode': '', 'pltfrm': ccc.sig.isplatform.Any(), 'browsr': ccc.sig.isbrowser.Any(), 'timezo': istimezone(), 'kcode': '333dc638eb62fe4a57964afedfb2bac0a0e333' };
  ccc.sig.isvalid = { 'vp1': 0, 'vp2': 0, 've': 0, 'enablekey': 1, 'valid': false };
  /* re-set signin.js * end */
  /* window.profile_img = '';window.profile_img_space = ''; */
  window.name = '';

}


function _signout(whofor){
  
  /* optional * set main signout page i.e imdex.html (not index.inc ) */
  /* var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('//', '/') + '/';
     console.log('using default baseurl :' + baseurl); */

  /* similar behavior as clicking on a link * http://stackoverflow.com/questions/503093/how-can-i-make-a-redirect-page-in-jquery-javascript */
  /* optional * re-load or load from signin via twitter
     window.location.href = baseurl; */

  /* reset title and icon color */

  exitcc();
  set_css_id('sdn', 'display', 'none');
  set_css_id('sdn_cc', 'display', 'none'); 

  set_doc_title('crowdcc');  
  set_css_id('ccc-top', 'color', ''); 

  /* clean up all dialogs */
  _ccleanup();

  /* ensure private page views are clear */
  set_html_id('in', ''); 
  set_html_id('cc', '');

  /* ensure private data is cleared */
  set_html_id('fromuser_img', '<span class="me icon icon-user icon--large icon-nib-right"></span><span id="me" class="me textin">me</span>');

  /* ensure share modal data is cleared * reset on signin */
  /* set_html_id('share-retweet-form', '<div id="js-share-retweettweet" name="share-retweettweet" class="text-input js-text-retweettweet"></div><span>&nbsp;</span>'); */

  /* post _cya eat the cookies with some milk! */
  _process('post', '_cya', 'signout=farewell', 'cya');

  console.log('1. coming back from post-signout.php');
  
  /* support * public view */
  _display('su');

  set_css_id('acc', 'display', 'none');
 
  /* reset signin form size */
  set_css_id('divide-one', 'display', 'none');
  set_css_id('sigfrm','display','none');
  set_css_id('sign', 'display', 'block');

  set_css_id('sigfrm','height','312px');

  /* ( de-activate controls before signin :: start ) */

  _global_actions('unable');

  /* ( de-activate controls before signin :: end ) */

  set_css_id('topbar', 'backgroundColor', '');
  set_css_id('sdn', 'display', 'none');
  set_css_id('sdn_cc', 'display', 'none');
  set_css_id('sdn_cw', 'display', 'none');

  /* console.log('line 1765 notices display none!'); */
  set_css_class('notices', 'display', 'none');
  // set_css_class('error_pc5de', 'display', 'none');
  toggle_signin('reset');

  set_css_id('bottom-pad', 'display', 'none');

  /* clear the session, sessionStorage and reset the tweet flags */
 
  ccsession.clear();
  sessionStorage.clear();

  console.log('2. sessions clear local');

  document.cookie ="_crowdcc_sess=true;path=/;expires=Thu, 01-Jan-1970 00:00:01 GMT";

  console.log('3. clear auth token and ccid');

  /* reset the array (soft delete) flags */
  ccc.sig.iset['ecode'] = ''; ccc.sig.iset['pcode'] = ''; ccc.sig.iset['sncode'] = ''; ccc.sig.iset['ucode'] = ''; ccc.sig.iset['uscode'] = '';
  ccc.sig.isvalid['ve'] = 0;  ccc.sig.isvalid['vp1'] = 0; ccc.sig.isvalid['vp2'] = 0;

  /* reset array (hard delete) flag */
  delete ccc.sig.iset['encode'];

  console.log('4. arrays reset ...');

  set_html_val('email', '');
  set_html_val('email_account_settings','');
  set_html_val('passcode', '');
  set_html_val('passcode_account_settings',''); 
  set_html_val('passcode_account_settings_verify','');   
  set_html_id('msg', '')
  set_html_id('pin', '');

  console.log('5. end sign out');

  console.log('6. start reset window objects');

  _reset_vars();

  console.log('7. end reset window objects');
  console.log('8. start message display ...');


  switch (whofor) {

    case ('pass_ecode'):
          
          set_css_id('signin-space', 'display', 'block');
          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'color', '#ECF1EF');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');   
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#4CA454');  
          set_css_class('psnd', 'display', 'block');
          isbarone('fix');
              
    break;

    case ('error_ureg'):
          
          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_id('error_ureg', 'display', 'block');
          isbarone('fix');

          /* console.log('error_ureg display ... all ok?'); */
    break;

    case ('error_ereg'):

          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_id('error_ereg', 'display', 'block');
          isbarone('fix');

          /* console.log('error_ereg display ... all ok?'); */
    break;

    case ('error_pc4de'):
              
          set_css_id('signin-space', 'display', 'block');
          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'color', '#ECF1EF');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
          set_css_class('error_pc4de', 'backgroundColor', '#DB3939');
          set_css_class('error_pc4de', 'display', 'block');
          isbarone('fix');

          /* console.log('message display ... all ok?'); */
    break;

    case ('error_ec3de'):
              
          set_css_id('signin-space', 'display', 'block');
          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'color', '#ECF1EF');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');   
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#4CA454');
          set_css_class('error_ec3de', 'display', 'block');
          isbarone('fix');

          /* console.log('message display ... all ok?'); */
    break;

    case ('error_mailinuse'):

          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'color', '#ECF1EF');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
          set_css_class('error_mailinuse', 'backgroundColor', '#DB3939');
          set_css_class('error_mailinuse', 'display', 'block');
          isbarone('fix');

          /* console.log('message display ... all ok?'); */
    break; 

    case ('error_tamper'):

          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'color', '#ECF1EF');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
          set_css_class('error_tamper', 'backgroundColor', '#DB3939');
          set_css_class('error_tamper', 'display', 'block');
          isbarone('fix');

          /* console.log('message display ... all ok?'); */
    break;

    case ('error_network'):

          set_css_class('notices', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'color', '#ECF1EF');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
          set_css_class('error_network', 'backgroundColor', '#DB3939');
          set_css_class('error_network', 'display', 'block');
          isbarone('fix');

          /* console.log('message display ... all ok?'); */
    break;

  }

  /* check if signout with confirm email banner * prevent db store unsync index on prior stored posts */
  if (get_css_id('error_pc5de', 'display', 'block')) { 
      set_css_class('error_pc5de', 'display', 'none');
      set_css_class('notices', 'display', 'block');
      set_css_class('ui-dialog-noticebar-close', 'color', '#ECF1EF');
      set_css_class('ui-dialog-noticebar-close', 'display', 'block');   
      set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#4CA454');
      set_css_class('error_ec3de', 'display', 'block');
      isbarone('fix');

      console.log('we are error_ec3de');
  }

  set_css_class('error_pc5de', 'display', 'none');

  console.log('9. end message display ...');
  console.log('10. finally clear up uri(s)');

  window.history.pushState('', 'crowdcc', 'http://'+ window.location.hostname );
  window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
  window.scrollTo(0, 0); /* scroll to top */

}

/* potential timed messages for above */
function display_pass_ecode() { set_css_class('psnd', 'display', 'block');}
function display_error_pc4de() {set_css_class('error_pc4de', 'display', 'block');}
function display_error_ec3de() {set_css_class('error_ec3de', 'display', 'block');}
function display_error_tamper() {set_css_class('error_tamper', 'display', 'block');}


function validate() {

  /* main email and password validate function ... */

  var emReg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  var pcReg = /^[A-Za-z0-9!@#$%&*()_]{6,20}$/;
  var twReg = /^[A-Za-z0-9_]{3,15}$/;
  
  var hasError = false;
  var pc_errmsg = '';
  var em_errmsg = '';
  var emid = 'email';
  var pcid = 'passcode';
  var modder = '';
      
  if ( visible_css_class('ui-dialog-email-content') ) {
       /* complete your account modal */
       /* console.log('modal dialog email content'); */
       modder = '_modal';
       pcid = pcid + modder ;
       emid = emid + modder ;
       var em = trimspace(email_modal.value);
       var pc = trimspace(passcode_modal.value);

      /* console.log(em); */
      /* console.log(pc); */

      switch (true) {

        case (!em):
              /* console.log('we are here ...'); */
              set_css_id('ecode_modal_msg','color', '#FF5F5F');
              em_errmsg = 'please enter your current email';
              hasError = true;
        break;

        case (!emReg.test(em)):
              set_css_id('ecode_modal_msg','color', '#FF5F5F');
              em_errmsg = 'email must be valid.';
              /* set_prop_id('signin_button', 'disabled'); */
              set_css_id('ecode_modal_msg', 'focus', 'focus');
              hasError = true;
        break;

        case (em.length > 40):
              set_css_id('ecode_modal_msg','color', '#FF5F5F');
              em_errmsg = 'email too long (4 - 40 chars).';
              /* set_prop_id('signin_button', 'disabled'); */
              set_css_id('ecode_modal_msg', 'focus', 'focus');
              /* console.log('length of email => ' + em.length); */
              hasError = true;
        break;

        case (!pc):
              set_css_id('pcode_modal_msg','color', '#FF5F5F');
              pc_errmsg = 'please enter your password';
              hasError = true;
        break;

        case (pc.length > 20):
              set_css_id('ecode_modal_msg','color', '#FF5F5F');
              pc_errmsg = 'password too long (6 - 20 chars).';
              set_css_id('pcode_modal_msg', 'borderColor', 'red');
              clear_css_class('passcode', 'noshadow'); 
              set_css_id('pcode_modal_msg', 'focus', 'focus');
              /* console.log('length of email => ' + pc.length); */
              hasError = true;
        break;

        case (!pcReg.test(pc)):
              set_css_id('pcode_modal_msg','color', '#FF5F5F');
              pc_errmsg = 'password too short (6 - 20 chars).';
              set_css_id('pcode_modal_msg', 'borderColor', 'red');
              clear_css_class('passcode', 'noshadow'); 
              set_css_id('pcode_modal_msg', 'focus', 'focus');
              /* console.log('length of email => ' + pc.length); */
              hasError = true;
        break;



      }

  } else if( visible_css_class('signin-content') ) {

    /* console.log( 'is visible: ' + visible_css_class('signin-content') ); */        
    /* console.log('non modal dialog email content'); */
    
    modder = '';
    var em = trimspace(email.value);
    var pc = trimspace(passcode.value);

    switch (true) {

      case (document.getElementById('signin_radio_1').checked):
            /* i am new to crowdcc */
        switch (true) {

          case (!em):
                set_css_id('ecode_msg','color', '#FF5F5F');
                em_errmsg = 'please enter your current email';
                hasError = true;
          break;

          case (!emReg.test(em)):
                set_css_id('ecode_msg','color', '#FF5F5F');
                em_errmsg = 'email must be valid.';
                set_prop_id('account-settings-save-changes', 'disabled');
                set_css_id('email', 'borderColor', 'red');
                set_css_id('email', 'focus', 'focus');
                hasError = true;
          break;

          case (em.length > 40):
                set_css_id('ecode_msg','color', '#FF5F5F');
                em_errmsg = 'email too long (4 - 40 chars).';
                set_prop_id('account-settings-save-changes', 'disabled');
                set_css_id('email', 'borderColor', 'red');
                set_css_id('email', 'focus', 'focus');
                console.log('length of email => ' + em.length);
                hasError = true;
          break;

          case (!pc):
                set_css_id('pcode_msg','color', '#FF5F5F');
                set_css_id('email', 'borderColor', '');
                set_css_id('passcode', 'borderColor', 'red');
                pc_errmsg = 'please enter your password';
                hasError = true;
          break;

          case (pc.length > 20):
                set_css_id('ecode_msg','color', '#FF5F5F');
                pc_errmsg = 'password too long (6 - 20 chars).';
                set_css_id('passcode', 'borderColor', 'red');
                clear_css_class('passcode', 'noshadow'); 
                set_css_id('passcode', 'focus', 'focus');
                console.log('length of email => ' + pc.length);
                hasError = true;
          break;

          case (!pcReg.test(pc)):
                set_css_id('pcode_msg','color', '#FF5F5F');
                pc_errmsg = 'password too short (6 - 20 chars).';
                set_css_id('passcode', 'borderColor', 'red');
                clear_css_class('passcode', 'noshadow'); 
                set_css_id('passcode', 'focus', 'focus');
                hasError = true;
          break;

        }
      break;
      case (document.getElementById('signin_radio_2').checked):
            /* yes i have a password */

            /* filter @ symbol * so the user can enter it or not */
            em = trimspace(em.replace(/@/g, ''));

            /* console.log('username :' + em); */

        switch (true) {

          case (!em):
                set_css_id('ecode_msg','color', '#FF5F5F');
                em_errmsg = 'please enter your username';
                hasError = true;
          break;
  
          case (!twReg.test(em)):
                em_errmsg = 'username must be valid.';
                set_prop_id('account-settings-save-changes','disabled');
                set_css_id('ecode_msg', 'color', '#FF5F5F');
                set_css_id('email', 'borderColor', 'red');
                clear_css_class('email', 'noshadow')
                set_css_id('email', 'focus', 'focus');
                hasError = true;
          break;

          case (!pc):
                set_css_id('pcode_forgot','visibility','visible');
                set_css_id('pcode_forgot','color', '#FF5F5F');
                /* set_css_id('pcode_msg','color', '#FF5F5F'); */
                set_css_id('passcode', 'borderColor', 'red');
                set_css_id('email', 'borderColor', '');
                /* pc_errmsg = 'please enter your password'; */
                hasError = true;
          break;
          
          case (!pcReg.test(pc)):
                set_css_id('pcode_forgot','visibility','hidden');
                pc_errmsg = 'password too short (6 - 20 chars).';
                set_css_id('email', 'borderColor', '');
                set_css_id('passcode', 'borderColor', 'red');
                clear_css_class('passcode', 'noshadow'); 
                set_css_id('passcode', 'focus', 'focus');
                hasError = true;
          break;
        }
      break;
    }
  }

  /* console.log('ecode'+modder+'_msg'); */

  if (!hasError) {
     
      /* clear_css_class(emid, 'noclass'); */
      /* clear_css_class(pcid, 'noclass'); */

      set_html_id('ecode'+modder+'_msg', em_errmsg);
      set_html_id('pcode'+modder+'_msg', pc_errmsg);

      set_css_id('email', 'borderColor', '#8899A6');
      set_css_id('passcode', 'borderColor', '#8899A6');

      ccc.sig.iset['ecode'] = em;
      ccc.sig.iset['pcode'] = pc;

      pc, em, emReg, pcReg, hasError, em_errmsg, pc_errmsg, emid, pcid, modder = null;

      return true;
  } else {

      /* console.log('ecode'+modder+'_msg'); */
      /* console.log(modder); */
      /* console.log(em_errmsg); */
    
      set_html_id('ecode'+modder+'_msg', em_errmsg);
      set_html_id('pcode'+modder+'_msg', pc_errmsg);
      return false;
  }

   /* console.log('this is the email or username :' + em); */
   /* console.log('this is the password :' + pc); */

   pc, em, emReg, pcReg, hasError, em_errmsg, pc_errmsg, emid, pcid, modder = null;

}


 
function _clear_sign() {

  /* clear down uscode and prevent cross code contamination */
  ccc.sig.iset['ucode']  = '' ;
  ccc.sig.iset['uscode'] = '' ;
  ccc.sig.iset['ecode']  = '' ;
  ccc.sig.iset['pcode']  = '' ;

  /* clear down input fields */
  set_html_val('email', '');
  set_html_val('passcode', '');
}


function _click(id) {

  /* error * actionable * display none * force * soft reset * store.js */

  switch (true) {

    case ( has_css_class('acc-help','menu-select') ):
           /* console.log('we are in reset all!'); */
           /* console.log('notices display none!'); */
           set_css_class('notices', 'display', 'none');
           set_css_class('actionable', 'display', 'none');
    break;            
  }

  switch (true) {

    case (id === 'reset'): /* reset all menu selections */
          /* console.log('soft reset * check :: isbarone(clear) * clear down notice bar close controls!');

          /* close down * reset all dialogs */
          set_css_id('sigfrm', 'display', 'none');
          set_css_id('accaller', 'display', 'none');
          set_css_id('accfrm', 'display', 'none');
          set_css_id('account', 'display','none');

          set_css_id('accfrm', 'display', 'none');
          set_css_class('ui-dialog-content', 'display', 'block');
          set_css_id('modal-text', 'display', 'none');
          set_css_class('ui-dialog', 'display', 'none');
          set_css_class('ui-dialog-email-content', 'display', 'none');
          set_css_class('ui-dialog-account-help', 'display', 'none');
          set_css_class('ui-dialog-account-space', 'display', 'none');

          set_css_class('ui-dialog-content', 'display', 'none');
          set_css_class('ui-dialog-titlebar-close', 'display', 'none');

          clear_css_class('acc-space', 'acc-space');
          clear_css_class('acc-spa-tag', 'acc-space');

          clear_css_class('acc-status', 'acc-status');
               
          clear_css_class('acc-settings', 'acc-settings');
          clear_css_class('acc-set-tag', 'acc-settings');
          set_css_class('ui-dialog-account-settings','display', 'none');

          clear_css_class('acc-help', 'acc-help');
          clear_css_class('acc-hel-tag', 'acc-help');

          set_css_id('pcode_forgot', 'color', '#004B91');
          set_css_id('pcode_forgot', 'visibility', 'visible');

          ccc.str.istw['mouse'] = 0;

          /* clear down * reset all notice bar close controls */
          isbarone('clear');

    break;

    case (id !== 'reset'):             
                                 
    break;
  }   

  /* console.log('hard reset * signout * check :: psnd * pfim * esnd * efim * error_ucode * error_ureg * error_ecode * error_ereg * error_pcode * error_pc1de * error_pc4de * error_ec1de * error_ec3de * error_mailinuse * error_tamper * error_network'); */

  switch (true) {

    case (visible_css_id('psnd')):
          /* pass_ecode * sent to _signout('pass_ecode'); then cleans up here... */
    case (visible_css_id('pfim')):
    case (visible_css_id('esnd')):
    case (visible_css_id('efim')):
    case (visible_css_id('error_ucode')):
          /* We cannot find your crowdcc account, try another? Or sign in with a social network. */
    case (visible_css_id('error_ureg')):
          /* twitter username is already registered with us, sign out of twitter, try another? */
    case (visible_css_id('error_ecode')):
          /* We cannot find your crowdcc account, try another? Or sign in with a social network. */
    case (visible_css_id('error_ereg')):
          /* hard reset disabled * error :: email address is already registered with us, try another? */
    case (visible_css_id('error_pcode')):
          /* We cannot find sign in details, double check your password? */
    case (visible_css_id('error_pc1de')):
    case (visible_css_id('error_pc4de')):
          /* sent to _signout('error_pc4de'); then cleans up here... */
    case (visible_css_id('error_ec0de')):
    case (visible_css_id('error_ec1de')):
    case (visible_css_id('error_ec3de')):
          /* sent to _signout('error_ec3de'); then cleans up here... */
    case (visible_css_id('error_em0n')):
          /* email link invalid or expired, resend email confirmation? * send confirmation */
    case (visible_css_id('error_em6n')):
          /* password invalid, please sign in to crowdcc to update your account! */
    case (visible_css_id('error_mailinuse')):
          /* sent to _signout('error_mailinuse'); then cleans up here... */
    case (visible_css_id('error_tamper')):
          /* sent to _signout('error_tamper'); then cleans up here... */
    case (visible_css_id('error_network')):
          /* sent to _signout('error_network'); then cleans up here... */
          exitcc(); window.location.href = window.location.href;
    break;

  }

}


function evalidate(em, id, id_errmsg, gid_errmsg) {
  var emReg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  var hasError = false;
  var em_errmsg = '';
  var gm_errmsg = '';

  set_css_id(id, 'borderColor', '#CCCCCC');
  set_html_id(id_errmsg, em_errmsg);
  set_html_id(gid_errmsg, gm_errmsg);
   
  if(em.length !=0) {

    switch(true) {

      case (!em):
            em_errmsg = 'Please enter your email';
            hasError = true;
      break;

      case (!emReg.test(em)):
            em_errmsg = 'Email must be valid.';
            set_prop_id('account-settings-save-changes', 'disabled');
            set_css_id(id, 'borderColor', 'red');
            clear_css_class(id, 'noshadow')
            set_css_id(id, 'focus', 'focus');
               
            hasError = true;
      break;

      case (typeof(ccc.str.tw.usr.ccmail0) !== 'undefined'):

      break;

    }

    switch(true) {

      case (!hasError):

            clear_css_class(id, 'noclass');

            em_errmsg = 'New email address: <span style="color:#999999">verification required</span>';
            gm_errmsg = '<span style="font-size:12px;color:#444444">Save changes</span> to verify data';
          
            set_prop_id('account-settings-save-changes', 'enabled');
            set_css_id(id, 'borderColor', '#444444');
            set_css_id(id, 'color', '#444444');
            set_html_id(id_errmsg, em_errmsg); 
            set_html_id(gid_errmsg, gm_errmsg);
          
            return 1;
      break;

      case (hasError):
         
            set_css_id(id_errmsg, 'color', 'red');
            set_html_id(id_errmsg, em_errmsg); 
            set_html_id(gid_errmsg, gm_errmsg); 

            return 0;
      break;

    }

  }

 em, emReg, hasError, em_errmsg, gm_errmsg = null;

}


function pvalidate(pc, id, id_errmsg, passmsg, gid_errmsg) {
  /* var pcReg = /^[A-Za-z0-9!@#$%&*()_]{6,20}$/; * http://stackoverflow.com/questions/14745961/regular-expression-to-restrict-special-characters */
  
  var pcReg = /^[A-Za-z0-9|\":<>[\]{}`\\()';!@#$%&*()_]{6,20}$/;

  var hasError = false;
  var pc_errmsg = '';
  var gm_errmsg = '';

  /* passmsg -> please enter new password */

  set_html_id(id_errmsg, pc_errmsg);
  set_html_id(gid_errmsg, gm_errmsg);

  if (pc.length !=0) {

      if (!pc) { 
          pc_errmsg += 'Please enter your password';
          hasError = true;
          } else if(!pcReg.test(pc)) {
          pc_errmsg += 'Password must be 6 - 20 characters.';
          set_css_id(id, 'borderColor', 'red');
          clear_css_class(id,'noshadow');
          set_css_id(id, 'focus', 'focus');
          hasError = true;
      }
      if (!hasError) {
           
          clear_css_class(id, 'noshadow');

          pc_errmsg = 'Password entered: <span style="color:#999999">' + passmsg + '</span>';
          set_css_id(id, 'borderColor', '#444444');
          set_css_id(id_errmsg, 'borderColor', '#444444');
          set_html_id(id_errmsg, pc_errmsg);
          set_html_id(gid_errmsg, gm_errmsg);

          return 1;
      } else {

          set_css_id(id_errmsg, 'borderColor', 'red');  
          set_html_id(id_errmsg, pc_errmsg);
          set_html_id(gid_errmsg, gm_errmsg);
            
          return 0;
      }
  }

  pc, pcReg, hasError, pc_errmsg, gm_errmsg = null;

}


function isuri(str) {
  var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
  return regexp.test(str);
}


function geturlparameter(name) {
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
  var regexS = "[\\?&]"+name+"=([^&#]*)";  
  var regex = new RegExp( regexS );  
  var results = regex.exec( window.location.href ); 
  if( results == null ) {    
      return "";
   }  else {   
      return results[1];
  }
}


function isemptystr(str) {
  return (!str || 0 === str.length);
}


function isemptyobj(obj) {
  for (var prop in obj) {
       if ( obj.hasOwnProperty(prop) ) {
           return false;
       }
  }  
  return true;
}

 
function isblank(str) {
  return (!str || /^\s*$/.test(str));
}

function isbarone(str) {
  /* clear down all notice bar close controls */
  set_css_id('noticebar-close-fixon','display','none');
  set_css_id('noticebar-close-fix','display','none');
  set_css_id('noticebar-close-float','display','none');
  set_css_id('noticebar-close-flix','display','none');

  switch (str) {
    
    case ('fixon'):
          /* default close button for in-app ccc users */
          set_css_id('noticebar-close-fixon','display','block');
          console.log('isbarone: fixon run!');
    break;
    case ('fix'):
          /* close button for out-app error messages */
          set_css_id('noticebar-close-fix','display','block');
          console.log('isbarone: fix run!');
    break;
    case ('float'):
          /* close button special for in-app error messages */
          set_css_id('noticebar-close-float','display','block');
          console.log('isbarone: float run!');
    break;
    case ('flix'):
          /* close button for in-app error messages */
          set_css_id('noticebar-close-flix','display','block');
          console.log('isbarone: flix run!');
    break;
    case ('clear'):
          /* do nothing ! */
          console.log('isbarone: clear run!');
    break;

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


/* function _follow(who) {} * was here */


function _who(whofor){
   var who;
   if (typeof ccc.str.tw.usr.screen_name == 'undefined') {
       console.log('no user is logged in!');
       who = ':noone';
   } else {
       console.log(ccc.str.tw.usr.screen_name);
       who = ccc.str.tw.usr.screen_name;
   }
  return who;

  who =''; 

}


function istimezone() {
  var tz = ccc.sig.jstz.determine(); var response_text = '';
  if (typeof (tz) === 'undefined') {
      response_text = 'No timezone found';
  } else {
      response_text = tz.name(); 
  }
  return response_text;
  tz = null; response_text = null;
}


function isemail(email) {
  /* simple validation in order to check format correct */
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
}

