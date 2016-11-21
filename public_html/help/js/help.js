/*! help * ccc.js v1.00.00 
| (c) 2015 crowdcc. 
| help.js client-side javascript
| crowdcc.com/use */

/* static persitent global vars */

/*! jsTimezoneDetect - v1.0.5 - 2013-04-01 */
window.jstz=function(){var b=function(a){a=-a.getTimezoneOffset();return null!==a?a:0},c=function(){return b(new Date(2010,0,1,0,0,0,0))},f=function(){return b(new Date(2010,5,1,0,0,0,0))},e=function(){var a=c(),d=f(),b=c()-f();return new jstz.TimeZone(jstz.olson.timezones[0>b?a+",1":0<b?d+",1,s":a+",0"])};return{determine_timezone:function(){
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
  
window.isbrowser = {Useragent: function() {return navigator.userAgent;},Any: function() {return (isbrowser.Useragent() );}}
window.isplatform = {
    Android: function() {return navigator.userAgent.match(/Android/i) ? 'Android' : false;},
    BlackBerry: function() {return navigator.userAgent.match(/BlackBerry/i) ? 'BlackBerry' : false;},
    iPhone: function() {return navigator.userAgent.match(/iPhone/i) ? 'iPhone' : false;},
    iPad: function() {return navigator.userAgent.match(/iPad/i) ? 'iPad' : false;},
    iPod: function() {return navigator.userAgent.match(/iPod/i) ? 'iPod' : false;},
    IEMobile: function() {return navigator.userAgent.match(/IEMobile/i) ? 'IEMobile' : false;},
    OS: function() {return navigator.platform;},
    Any: function() {return (isplatform.Android() || isplatform.BlackBerry() || isplatform.iPhone() || isplatform.iPad() || isplatform.iPod() || isplatform.IEMobile() || isplatform.OS() );}};

window._ccc = { '_ccc': '', 'ecode': '', 'encode': '', 'pltfrm': isplatform.Any(), 'browsr': isbrowser.Any(), 'timezo': istimezone(), 'kcode': '333dc638eb62fe4a57964afedfb2bac0a0e333' };

window.tidch = 0;

window.onload = function() {  /* onload */

  switch (true) {
            
    case (isemail( document.getElementById('ccmail-to').value )):
            
          if ( document.getElementById('ccmail-content').value ) {
               clear_css_class('send_ccmail', 'btn-able');
               set_css_id('ccmail-to','borderColor', '#2780F8');
               set_css_id('ccmail-content','borderColor', '#2780F8');
               console.log( document.getElementById('ccmail-content').value );
           }

    break;

   } 

}


/*
document.onkeydown = function keyDown(event) {

  var elem = (event.target) ? event.target : event.srcElement;
  var tagid = elem.id;

   switch (true) {

      case (!isemail( document.getElementById('ccmail-to').value )):
            set_css_id('ccmail-to','borderColor', '#FF5555');
            clear_css_class('send_ccmail', 'btn-unable');
      break;
            
      case (isemail( document.getElementById('ccmail-to').value )):
            clear_css_class('send_ccmail', 'btn-able');
      break;

   } 

  elem = null; tagid = null;

}
*/

document.onkeyup = function keyPress(event) {

  var elem = (event.target) ? event.target : event.srcElement;
  var tagid = elem.id;

   switch (true) {

      case (!isemail( document.getElementById('ccmail-to').value )):

            if ( document.getElementById('ccmail-content').value ) {
                 set_css_id('ccmail-content','borderColor', '#2780F8');
                 set_css_id('ccmail-to','borderColor', '#FF5555');
            } else {
                 set_css_id('ccmail-content','borderColor', '#FF5555');
                 set_css_id('ccmail-to','borderColor', '#FF5555');
            }
                 clear_css_class('send_ccmail', 'btn-unable');
      break;
            
      case (isemail( document.getElementById('ccmail-to').value )):
            
            if ( document.getElementById('ccmail-content').value ) {
                 clear_css_class('send_ccmail', 'btn-able');
                 set_css_id('ccmail-to','borderColor', '#2780F8');
                 set_css_id('ccmail-content','borderColor', '#2780F8');
                 console.log( document.getElementById('ccmail-content').value );
            } else {
                 clear_css_class('send_ccmail', 'btn-unable');
                 set_css_id('ccmail-to','borderColor', '#2780F8');
                 set_css_id('ccmail-content','borderColor', '#FF5555');
            }

      break;

   } 

  elem = null; tagid = null;

}

document.onclick = function keyClick(event) {

  var elem = (event.target) ? event.target : event.srcElement;
      /* elem.value = null; */
  var tagclass = elem.className.split(" ")[0];
  var tagid = elem.id;

  var tagtxtlen = trimspace(elem.innerHTML).length;
  var tagclass = elem.className.split(" ")[0];
  var tagtype = elem.tagName.toLowerCase();

  console.log('classname :' +elem.className.split(" ")[0]);
  console.log('tagclass:' + tagclass);
  console.log('id :' +elem.id);
  console.log('text :' + tagtxtlen);
  console.log('tagtype: ' +tagtype);

  switch (true) {

    case (tagclass === 'ui-dialog-noticebar-close'):
          window.location.replace('http://'+ window.location.hostname );
    break;

  	case (tagclass === 'signon'):
          /* window.open('http://localhost/~macbook/crowdcc', '_blank'); */
          window.location.replace('http://'+ window.location.hostname );
  	break;
  	
  	case (tagclass === 'ccc-top'):
          window.location.replace('http://'+ window.location.hostname );
  	break;

    case (tagid === 'sent_ccmail'):
          window.location.replace('http://'+ window.location.hostname );
    break;

  	case (tagclass === 'tab_caret'):
  		  console.log('we have clicked on the tab caret!');
  		  /* drop down menu toggle */
  		  if (visible_css_id('play_down')) {
  		  	  add_css_class('policies', 'open');
  		  } else {
  		  	  clear_css_class('policies', 'tab_set');
  		  	  add_css_class('policies', 'on_neutral_grey');
  		  }
  	break;

    case (tagid === 'send_ccmail'):
          console.log('checking for valid email ...');

          switch (true) {

            case (!isemail( document.getElementById('ccmail-to').value )):
                  
                  // set_css_id('ccmail-to','borderColor', '#FF5555');
                  // set_css_id('ccmail-content','borderColor', '#FF5555');
                  // clear_css_class('send_ccmail', 'btn-unable');

                  if ( document.getElementById('ccmail-content').value ) {
                       set_css_id('ccmail-content','borderColor', '#2780F8');
                       set_css_id('ccmail-to','borderColor', '#FF5555');
                  } else {
                       set_css_id('ccmail-content','borderColor', '#FF5555');
                       set_css_id('ccmail-to','borderColor', '#FF5555');
                  }
                       clear_css_class('send_ccmail', 'btn-unable');

            break;
            
            case (isemail( document.getElementById('ccmail-to').value )):

                  if ( document.getElementById('ccmail-content').value ) {
                       clear_css_class('send_ccmail', 'btn-able');
                       set_css_id('ccmail-content','borderColor', '#2780F8');
                       console.log( document.getElementById('ccmail-content').value );
                       process_ccmail( document.getElementById('ccmail-to').value, document.getElementById('ccmail-content').value);
                  } else {
                       clear_css_class('send_ccmail', 'btn-unable');
                       set_css_id('ccmail-to','borderColor', '#2780F8');
                       set_css_id('ccmail-content','borderColor', '#FF5555');
                  }
            
            break;

          }

    break;

  }

  elem = null; tagclass = null; tagid = null; tagtxtlen = null; tagclass = null; tagtype = null;

 }


 function process_ccmail(ccmail, ccmsg) {

   console.log('we are sending email to server!');

   _process('get', '_kcode', '', 'help?token=' + encrypt( ccmail ) );
   /* collect from session window.global data * ccmail * ccmail text content */

   _ccc['ecode'] = encrypt( ccmail );

   _ccc['encode'] = ccmsg.replace(/(<([^>]+)>)/ig,"");
   _ccc['encode'] = eschtml( _ccc['encode'] );
   _ccc['encode'] = encrypt( _ccc['encode'] );

   console.log( ccmail, ccmsg, isplatform.Any(), isbrowser.Any() , istimezone() ,  window._ccc['kcode']);

   tidch = setTimeout( function(){ send_ccmail() } , 500);
   
   console.log('_process ccmail: sent ccmail + message + client info to server');

 }


 function send_ccmail() {
  console.log( 'have we updated yet! :' + window._ccc['kcode'] );

    if (window._ccc['kcode'] !== '333dc638eb62fe4a57964afedfb2bac0a0e333') {
        /* continue process data */

        console.log('yes ... we have ... success process data!');

        _ccc['_ccc'] = '_ccc' + '=' + _ccc['ecode'] + ':' + _ccc['encode'] + ':' + base64_encode( _ccc['pltfrm'] ) + ':' + base64_encode( _ccc['browsr'] ) + ':' + base64_encode( _ccc['timezo'] ) + ':' + _ccc['kcode'];

        _process('post', '_sm', _ccc['_ccc'] ,'help');
      
    } else {

        console.log('local error message!');

    }

    window._ccc = { '_ccc': '', 'ecode': '', 'encode': '', 'pltfrm': isplatform.Any(), 'browsr': isbrowser.Any(), 'timezo': istimezone(), 'kcode': '333dc638eb62fe4a57964afedfb2bac0a0e333' };
   
   clearTimeout(tidch); tidch = null;
  
  return true;

 }


 function _process(ctype, cwho, cdata, cpath) {

    var data = '';
    /* var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('//', '/') + '/'; */
    var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('/contact', '').replace('//', '/') + '/';

    /* configured to allow for requests to different resources for load balance option * default * baseurl */
    console.log('using default baseurl :' + baseurl);
   
    switch (ctype) {

       case ('get'):

             getdata(baseurl + cpath, callrequest, 'url', cwho);

       break;
      
       case ('post'):

            switch (cwho) {

              case ('_sm'):  /* send ccmail */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

            }
      break;
    }

    baseurl = null;

    callrequest(data);

    data = null;
}


function callrequest(data) {
   
  /* responce data || user messaged || error handling  */

  console.log('data back * :' + data);

  var datastr = data.split(':*:');

  if (datastr[0] !== '') {
  
  /*
  console.log('(status: post or get or error) -> ' + datastr[0]); // status: post or get or error
  console.log('(postfrom: who made request) -> ' + datastr[1]);   // postfrom: who made the post 
  console.log('this is the data in -->' + data);
  */

    switch (datastr[0]) {

      case ('get'):

           switch (true) {

            case (datastr[1] === '_kcode'):
                  /* now check for error bubbled, returned from process */
                  /* datastr[2] = trimquote(datastr[2]); */
                  datastr[3] = trimquote(datastr[3]);
                  
                  window._ccc['kcode'] = datastr[3];
                  set_html_val('authenticity_token', datastr[3]);

                  console.log('update auth token: ' + datastr[3]);

                  /*
                  document.getElementsByTagName("input")[0].value = datastr[3];
                  document.getElementById("authenticity_token").value = datastr[3]; 
                  */   
            break;

            case ('error'):
                  _ccc_valid_set['enablekey'] = 0;  /* turn off keyup */
                         
                  /*  error handler for normal scope errors :
                      error_pc4de - connection, could not connect to twitter, returned from Oauth lib */

                  set_css_id('ccc_load', 'display', 'none');
                  set_css_id('signbtn', 'display', 'none');
                  var err_str = JSON.stringify(data);

                  console.log(err_str);
                  set_css_class('controls', 'display', 'none');
                  set_css_class('notices', 'display', 'block');
                  set_css_class('ui-dialog-noticebar-close', 'display', 'block');

                  set_css_class('error_pc4de', 'display', 'block');
                  set_css_id('frmsig', 'display', 'none');
                  _clear_sign();
   
                  console.log(datastr[1]);
                  console.log(datastr[2]);
                  console.log('unable to connect to crowdcc, please try later')
            break;
             
            }
            datastr = null;
      break;

      case ('post'):          
          /* post returned good (error not bubbled from post action) check postfrom */
  
            switch (true) {

              case (datastr[1] === '_sm'):
                    
                    console.log('we are here ... ok cool ;=)) ');
                    console.log( trimquote(datastr[2]) );

                    //switch (true) {

                    //  case ( trimquote(datastr[2]) === 'pass_emin' ):
                    //         console.log('... email success, sent to crowdcc team admin');
                    //         msgtmr_ccmail('pass_emin','close');
                    //  break;
                    // case ( trimquote(datastr[2]) === 'error_em7n' ):
                    //         console.log('... email fail, not sent to crowdcc team admin');
                    //         msgtmr_ccmail('error_em7n','close-error');
                    //  break;

                    //}

                    msgtmr_ccmail( trimquote(datastr[2]), 'close');

              break;
            }
      break;
    }
  }
}


function msgtmr_ccmail(msg, cls_msg) {

  set_css_class('notices', 'display', 'block');
  document.getElementById('ccmail-to').value = '';
  document.getElementById('ccmail-content').value = '';
  set_css_class('usrin', 'display', 'none');
  set_css_id('ccmail-to', 'display', 'none');
  set_css_id('ccmail-content', 'display', 'none');
  set_css_id('pror-msg', 'display', 'none');
  set_css_id('send_ccmail', 'display', 'none');
  set_css_id('sent_ccmail', 'display', 'block');

  switch (msg) {

    case ('pass_emin'):
           set_css_class('notice-pass_emin', 'display', 'block');
           set_css_id('sent-msg', 'display', 'block');
    break;

    case ('error_em4n'):
          set_css_class('notice-error_em4n', 'display', 'block');
          set_css_id('fail-msg', 'display', 'block');
    break;

    case ('error_em5n'):
          set_css_class('notice-error_em5n', 'display', 'block');
          set_css_id('fail-msg', 'display', 'block');
    break;

    case ('error_em7n'):
          set_css_class('notice-error_em7n', 'display', 'block');
          set_css_id('fail-msg', 'display', 'block');
    break;

    case ('error_tamper'):
          set_css_class('error_tamper', 'display', 'block');
          set_css_id('fail-msg', 'display', 'block');
    break;

  }

  switch (cls_msg) {

    case ('close'):
          console.log('start close timer message');
          tidcs = setTimeout( function() { msgcls(); } , 4000);
          console.log('end close timer message!');
    break;

  }
}


function msgcls() {

  set_css_class('error', 'display', 'none');
  set_css_class('notices', 'display', 'none');
  /* set_css_class('ui-dialog-noticebar-close', 'display', 'none'); */

  set_css_class('notice-pass_emin', 'display', 'none');
  set_css_class('notice-error_em7n', 'display', 'none');

  clearTimeout(tidch); tidch = null;

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


function eschtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}



