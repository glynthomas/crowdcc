/*! feedback.js v1.00.00 
| (c) 2015 crowdcc.
| plans 
| window.onload
| timezone, browser, platform 
| document.onclick
| document.onupkey
| process (processing post & get)
| callrequest (rtn from post & get)
| validate functions: trimspace, istimezone,
| istwitter, isemail, isempty, isradio,
| isvalidkey, isdate, escapehtml
| base64 obj
| crowdcc.com/use */


window.onload = function(){
  
   /*
   *  http:// [0/1] , localhost [2] , ~macbook [3] , crowdcc [4] ( any passed url string [5] )  
   *  console.log(uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] +'/'+ uriarr[3] + '/' +uriarr[4]);
   */ 

   // var uriarr = window.location.href.split('/');
  
   // console.log(window.location.href);
   // var uintxt = escapehtml(uriarr[5]);
   // console.log(uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] +'/'+ uriarr[3] + '/' +uriarr[4]);
   
   // var backlen = -1; 
   //    history.go(-backlen);
   //    window.history.replaceState('', 'crowdcc:', uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] +'/'+ uriarr[3] + '/' +uriarr[4]  + '/' + 'plans' + '/' + 'feedback' );


   //switch (true) {

   //  case (document.cookie.indexOf("__icu=true") === -1):
           /* no cookie is present * first run instance */
   //        document.cookie = "__icu=true;path=/";
           /* set onunload function */
   //        window.onunload = function(){ document.cookie ="__icu=true;path=/;expires=Thu, 01-Jan-1970 00:00:01 GMT";};
           /* load the only instance of app currently running */
   //        console.log('hacked module start');
   //        _hacked_ccc();
   //  break;

   //  case (document.cookie.indexOf("__icu=true") !== -1):
           /* more than one instance detected * redirect */
   //        _clearoff_ccc();
   //  break;

   //}

  process('get');
  cc_reset_form('user');
};


function _hacked_ccc() {
  set_css_class('alert', 'backgroundColor', '#4488F6');
  set_css_class('alert', 'borderBottomColor', '#4488F6'); 
  set_css_class('notices', 'display', 'block');
  set_css_id('hacked', 'display', 'block');
  // new datepkr ('last', {'dateFormat': 'd/m/Y'});
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
  
window.isdate = {
  dateUTC: function() { var date;date = new Date();date = date.getUTCFullYear() + '-' +('00' + (date.getUTCMonth()+1)).slice(-2) + '-' +('00' + date.getUTCDate()).slice(-2) + ' ' + ('00' + date.getUTCHours()).slice(-2) + ':' + ('00' + date.getUTCMinutes()).slice(-2) + ':' + ('00' + date.getUTCSeconds()).slice(-2);return date;date = null; },
  dateISO: function() { var date;date = new Date().toISOString().slice(0, 19).replace('T', ' '); return date;date = null; }
} 

window.isbrowser = { Useragent: function() {return navigator.userAgent;},Any: function() {return (isbrowser.Useragent() );} }
window.isplatform = {
    Android: function() {return navigator.userAgent.match(/Android/i) ? 'Android' : false;},
    BlackBerry: function() {return navigator.userAgent.match(/BlackBerry/i) ? 'BlackBerry' : false;},
    iPhone: function() {return navigator.userAgent.match(/iPhone/i) ? 'iPhone' : false;},
    iPad: function() {return navigator.userAgent.match(/iPad/i) ? 'iPad' : false;},
    iPod: function() {return navigator.userAgent.match(/iPod/i) ? 'iPod' : false;},
    IEMobile: function() {return navigator.userAgent.match(/IEMobile/i) ? 'IEMobile' : false;},
    OS: function() {return navigator.platform;},
    Any: function() {return (isplatform.Android() || isplatform.BlackBerry() || isplatform.iPhone() || isplatform.iPad() || isplatform.iPod() || isplatform.IEMobile() || isplatform.OS() );}};


window._ccf = { '_ccf': '', 'token': '', 'scode': '_ccf', 'vuser': '', 'vmail': '', 'comments': '', 'a': '', 'b': '', 'c': '', 'd': '', 'e': '', 'pltfrm': isplatform.Any(), 'browsr': isbrowser.Any(), 'date': isdate.dateUTC(), 'timezo': istimezone() };
window._keyup = { 'keyup': 0, 'uok': 0, 'mok': 0, 'cok': 0, 'off': 1 };
window._valid = 1;

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

  
  console.log(event.target.id);
  console.log('tagclass :' + tagclass);
  console.log('tagid :' + tagid);
  


  switch (true) {

    case (tagid === 'signon'):
    case (tagclass === 'signon'):
          console.log('go to signon');
          /* window.location.href = "http://crowdcchq.com"; */
          window.open('http://crowdcc.com','_blank');
    break;

    case (tagid === 'submit_feedback'):
    case (tagid === 'cc_btn_submit'):

        switch (true) {case (isradiock('a') === '1'):_ccf['a'] = 'y';break;case (isradiock('a') === '2'):_ccf['a'] = 'x';break;case (isradiock('a') === '3'):_ccf['a'] = 'n';break;}
        switch (true) {case (isradiock('b') === '1'):_ccf['b'] = 'y';break;case (isradiock('b') === '2'):_ccf['b'] = 'x';break;case (isradiock('b') === '3'):_ccf['b'] = 'n';break;}
        switch (true) {case (isradiock('c') === '1'):_ccf['c'] = 'y';break;case (isradiock('c') === '2'):_ccf['c'] = 'x';break;case (isradiock('c') === '3'):_ccf['c'] = 'n';break;}
        switch (true) {case (isradiock('d') === '1'):_ccf['d'] = 'y';break;case (isradiock('d') === '2'):_ccf['d'] = 'x';break;case (isradiock('d') === '3'):_ccf['d'] = 'n';break;}
        switch (true) {case (isradiock('e') === '1'):_ccf['e'] = 'y';break;case (isradiock('e') === '2'):_ccf['e'] = 'x';break;case (isradiock('e') === '3'):_ccf['e'] = 'n';break;}

        set_html_id('user_msg', '&nbsp;' ); set_html_id('cmail_msg', '&nbsp;' ); set_html_id('comments_msg', '&nbsp;' );

        if ( isemptystr( document.getElementById('user').value ) ) { set_html_id('user_msg', 'required field' ); _valid = 1; } else { set_html_id('user_msg', '&nbsp;' ); _valid = (_valid + 1); }
        if ( isemptystr( document.getElementById('cmail').value ) ) { set_html_id('cmail_msg', 'required field' ); _valid = 1} else { set_html_id('cmail_msg', '&nbsp;' ); _valid = (_valid + 1); }
        if ( isemptystr( document.getElementById('comments').value ) ) {set_html_id('comments_msg', 'required field' ); _valid = 1 } else { set_html_id('comments_msg', '&nbsp;' ); _valid = (_valid + 1);}

        switch (true) {

          case (_valid === 1):
                /* _valid === 4 * all fields are completed  */
                return false;
          break;
              
          /* initial validation */

          case ( document.getElementById('user').value !== '' ):
                 set_html_id('user_msg', '&nbsp;' );                   
                 if ( istwitter( document.getElementById('user').value ) === false ) { set_html_id('user_msg', 'user is invalid!' ); _valid = 1; } else { _ccf['vuser'] = document.getElementById('user').value }   
    
          case ( document.getElementById('cmail').value !== '' ):
                 set_html_id('cmail_msg', '&nbsp;' );                 
                 if ( isemail( document.getElementById('cmail').value )  === false ) { set_html_id('cmail_msg', 'email is invalid!' ); _valid = 1; } else { _ccf['vmail'] = document.getElementById('cmail').value }

          case ( document.getElementById('comments').value !== '' ):
                 set_html_id('comments_msg', '&nbsp;' );
                 /* check for valid comments * non html or invalid input check */
                 if ( isvalid( document.getElementById('comments').value ) === false ) { set_html_id('comments_msg', 'sorry we cannot accept html in this text area, please remove' ); _valid = 1; } else { _ccf['comments'] = tag_strip(document.getElementById('comments').value, '');set_html_id('comments_msg', '&nbsp;' ); } 
          break;

        }

        console.log('_valid : ' + _valid);

            /* _valid === 4 * all fields are completed  * validation passed */
        if (_valid === 1) { console.log('validation failed!'); clear_css_class('cc_btn_submit', 'cc_btn_unable');} 

        else 

        { 
          /* console.log('initial validation passed'); */
          /* check data ready for post ... */
          /* console.log('token : '+ _ccf['token']);console.log('scode : '+  _ccf['scode']);console.log('vuser : '+ _ccf['vuser']);console.log('vmail : '+ _ccf['vmail']);console.log('comments : '+ _ccf['comments']);console.log('a : '+ _ccf['a']);console.log('b : '+ _ccf['b']);console.log('c : '+ _ccf['c']);console.log('d : '+ _ccf['d']);console.log('e : '+ _ccf['e']);console.log('pltfrm : '+ _ccf['pltfrm']);console.log('browsr : '+ _ccf['browsr']);console.log('date : '+ _ccf['date']);console.log('timezo : '+ _ccf['timezo']); */
          /* alert('check data!'); */

          _ccf['token'] = base64.encode(document.getElementById('authenticity_token').value);
          _ccf['scode'] = base64.encode('_ccf');

          _ccf['vuser'] = encrypt(document.getElementById('user').value);
          _ccf['vmail'] = encrypt(document.getElementById('cmail').value);
          _ccf['comments'] = base64.encode(_ccf['comments']);

          _ccf['a'] =  base64.encode(_ccf['a']);
          _ccf['b'] = base64.encode(_ccf['b']);
          _ccf['c'] = base64.encode(_ccf['c']);
          _ccf['d'] = base64.encode(_ccf['d']);
          _ccf['e'] = base64.encode(_ccf['e']);

          _ccf['pltfrm'] =  base64.encode( _ccf['pltfrm']);
          _ccf['browsr'] =  base64.encode( _ccf['browsr']);
          _ccf['date'] =  base64.encode( _ccf['date']);
          _ccf['timezo'] =  base64.encode( _ccf['timezo']);
              
          clear_css_class('cc_btn_submit', 'cc_btn_enable');
          
          /* check data ready for post ... */
          /* console.log('token : '+ _ccf['token']);console.log('scode : '+  _ccf['scode']);console.log('vuser : '+ _ccf['vuser']);console.log('vmail : '+ _ccf['vmail']);console.log('comments : '+ _ccf['comments']);console.log('a : '+ _ccf['a']);console.log('b : '+ _ccf['b']);console.log('c : '+ _ccf['c']);console.log('d : '+ _ccf['d']);console.log('e : '+ _ccf['e']);console.log('pltfrm : '+ _ccf['pltfrm']);console.log('browsr : '+ _ccf['browsr']);console.log('date : '+ _ccf['date']);console.log('timezo : '+ _ccf['timezo']); */
          /* alert('check data!'); */

          process('post');
          elem = null; tagclass = null; tagid = null;

        }
           
    break;

    case (tagid === 'success_feedback'):
    case (tagid === 'cc_btn_success'):
          console.log('going to visit blog!');
          window.location.href = "http://crowdcchq.com/";
    break;

    case (tagid === 'error_feedback'):
    case (tagid === 'cc_btn_error'):
          console.log('going to visit blog!');
          window.location.href = "feedback";
    break;

    case (tagclass === 'ui-hacked-noticebar-close'):
          window.location.reload();
    break;

  }

}

document.onkeyup = function keyPress(event) {

 if (_keyup['off'] === 1) {

 var tagid = event.target.id;
 var focusid = document.activeElement;
 focusid = focusid.getAttribute('id');
  
 /* console.log(focusid); */

 switch (focusid) {

    case ('user'):
        switch (true){
          case ( isemptystr(document.getElementById('user').value) ):
                 set_html_id(tagid + '_msg', 'required field.');
                 _keyup['uok'] = 1;
          break;
          case ( istwitter( document.getElementById('user').value ) === false ):
                 set_html_id('user_msg', 'user is invalid!' );
                 _keyup['uok'] = 1;
          break;
          case ( !isemptystr(document.getElementById(tagid).value)):
                 set_html_id(tagid + '_msg', '&nbsp;');
                 _keyup['uok'] = 2;
          break;
        }
  break;
  case ('cmail'):
        switch (true){
          case ( isemptystr(document.getElementById('cmail').value) ):
                 set_html_id(tagid + '_msg', 'required field.');
                 _keyup['cok'] = 1;
          break;
          case ( isemail( document.getElementById('cmail').value ) === false ):
                 set_html_id('cmail_msg', 'email is invalid!' );
                 _keyup['cok'] = 1;
          break;
          case ( !isemptystr(document.getElementById(tagid).value)):
                 set_html_id(tagid + '_msg', '&nbsp;');
                 _keyup['cok'] = 2;
          break;
        }
  break;
  case ('comments'):
        switch (true){
          case ( isemptystr(document.getElementById('comments').value) ):
                 set_html_id(tagid + '_msg', 'required field.');
                 _keyup['mok'] = 1;
          break;
          case ( isvalid( document.getElementById('comments').value ) === false):
                 set_html_id('comments_msg', 'sorry we cannot accept html in this text area, please remove' );
                 _keyup['mok'] = 1;
          break;
          case ( !isemptystr(document.getElementById(tagid).value)):
                 set_html_id(tagid + '_msg', '&nbsp;');
                 _keyup['mok'] = 2;
          break;
        }

    }
 
 _keyup['keyup'] = _keyup['uok'] + _keyup['cok'] + _keyup['mok'];

 console.log('_keyup : ' + _keyup['keyup']);

 if ( _keyup['keyup'] === 6 ) { clear_css_class('cc_btn_submit', 'cc_btn_enable'); } else { clear_css_class('cc_btn_submit', 'cc_btn_unable'); }
 
 tagid = null; focusid = null;

}

 /* window._keyup = { 'keyup': 0, 'uok': 0, 'mok': 0, 'cok': 0 } :: delete later */ 

} 


function process(ctype) {
 
/* processing the get or post requests and passing on the responce data || error handling to callrequest() */
   
   var data = '';
   
   switch (ctype) {
      
      case ('get'):

             getdata('http://'+ window.location.hostname +'/fe', callrequest , 'url', '_ccf' );

      break;

      case ('post'):

             _ccf['_ccf'] = '_ccf' + '=' + _ccf['token'] + ':' + _ccf['scode'] + ':' + _ccf['vuser'] + ':' + _ccf['vmail'] + ':' +  _ccf['comments'] + ':' +  _ccf['a'] + ':' +  _ccf['b'] + ':' +  _ccf['c'] + ':' +   _ccf['d'] + ':' +  _ccf['e'] + ':' +  _ccf['pltfrm'] + ':' +  _ccf['browsr'] + ':' + _ccf['date'] + ':' +  _ccf['timezo'];

             postdata('http://'+ window.location.hostname +'/fe', callrequest , 'url',  _ccf['_ccf'] , '_ccf');
                   
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

              document.getElementById('authenticity_token').value = jsonobj.ccf;
              document.getElementsByName('authenticity_token').value = jsonobj.ccf;

              console.log('ccf token added to form: ' + document.getElementById('authenticity_token').value);
              /* console.log(document.getElementsByName('authenticity_token').value); */

              jsonobj = null;
       break;

       case ('post'):
              /* console.log(datastr[2]); */              
              /* post returned good (error not bubbled from post action) check postfrom */

              switch (true) {

                case (datastr[1] === '_ccf'):
                      /* now check for error bubbled, returned from process */
                     
                      switch (true) {
                        
                        case (datastr[2].indexOf('error') !== -1):
                              
                              _keyup['off'] = 0;  /* turn off keyup */

                              datastr[2] = trimquote(datastr[2]);
                              
                              console.log(' we have found an error ' + datastr[2]);

                              document.getElementById("user").value = '';
                              document.getElementById("cmail").value = '';
                              document.getElementById("comments").value = '';

                              switch (datastr[2]) {

  
                                case ('error feedback-fail'):
                                       set_css_id('feedback_submit_form','display','none');
                                       set_css_id('feedback_success_form','display','block');
                                       window.scroll(0, 0);
                                break;
                      
                                case ('error email-fail'):
                                case ('error username-fail'):
                                case ('error date-fail'):
                                case ('error data-tamper'):
                                      set_css_id('feedback_submit_form','display','none');
                                      set_css_id('feedback_error_form','display','block');
                                      window.scroll(0,0);
                                break;

                              }

                              // set_css_id('hacked', 'display', 'none');
                              // set_css_id('su', 'display', 'block');

                              // set_css_class('hacked_icc', 'backgroundColor', '#DB3939');
                              // set_css_class('hacked_icc', 'borderBottomColor', '#DB3939');

                              // set_css_class('ui-hacked-noticebar-close', 'z-index', '4');
                              // set_css_class('ui-hacked-noticebar-close', 'display', 'block');
                            
                              // set_html_class('message', 'crowdcc: please contact us, to protect your account, recovery has been suspended, please refresh this window.');


                        break;
                        case (datastr[2].indexOf('error') === -1):
                              /* return   ===    'correct process'          */
                              
                              _keyup['off'] = 0;  /* turn off keyup */

                              document.getElementById("user").value = '';
                              document.getElementById("cmail").value = '';
                              document.getElementById("comments").value = '';

                              set_css_id('feedback_submit_form','display','none');
                              set_css_id('feedback_success_form','display','block');
                              window.scroll(0,0);

                              console.log(' we have no error ' + datastr[2]);
                              /* alert('going to display an information instruction screen'); */
                              // set_css_id('red', 'display', 'none');
                              // set_css_id('redready', 'display', 'block');
                        break;

                      }
                      
                break;
                
                case (datastr[1] === '_cca'):
                      /* now check for error bubbled, returned from process */
                      /* console.log('datastr[2] : ' + datastr[2]);         */

                      switch (true) {
                        
                        case (datastr[2].indexOf('error') !== -1):
                              
                              _keyup['off'] = 0;  /* turn off keyup */
                              
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
             _keyup['off'] = 0;          /* turn off keyup */
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


function cc_reset_form(focus) {

  var item = ['a', 'b', 'c', 'd', 'e']; 
  var ele = '';

  ele = document.getElementsByName(item[0]);
  for (var i=0; i < ele.length;i++) { document.getElementsByName(item[0]).checked = false; }
  ele[1].checked = true;

  ele = document.getElementsByName(item[1]);
  for (var i=0; i < ele.length;i++) { document.getElementsByName(item[1]).checked = false; }
  ele[1].checked = true;

  ele = document.getElementsByName(item[2]);
  for (var i=0; i < ele.length;i++) { document.getElementsByName(item[2]).checked = false; }
  ele[1].checked = true;

  ele = document.getElementsByName(item[3]);
  for (var i=0; i < ele.length;i++) { document.getElementsByName(item[3]).checked = false; }
  ele[1].checked = true;

  ele = document.getElementsByName(item[4]);
  for (var i=0; i < ele.length;i++) { document.getElementsByName(item[4]).checked = false; }
  ele[1].checked = true;

  item = null; ele = null;

  document.getElementById("user").value = '';
  document.getElementById("cmail").value = '';
  document.getElementById("comments").value = '';
  
  set_css_id(focus, 'focus', 'focus');

  window.scroll(0, 0);

}


function isradiock(radin) {

  var radck = document.getElementsByName(radin);
  var sizes = radck.length;
  for (i=0; i < sizes; i++) {
       if (radck[i].checked === true) { /* no selected * value 0 */
          return radck[i].value;
      }
  }
  radck = null; sizes = null; 
}


function isvalid(html) {

  /* html === document.getElementById('comments').value */

  var unchars = "!@#$%^&*()+=-[]\\\';/{}|\":<>?";

  for (var i = 0; i < html.length; i++) {
    if (unchars.indexOf( html.charAt(i)) != -1 ) {
    /* alert ("Your username has special characters. \nThese are not allowed.\n Please remove them and try again."); */
    return false;
    unchars = null;
    }
  }
}

function isvalid_strip(html) {
  /* may have a problems with older browser IE8 support */
  var tmp = document.createElement('div');
  tmp.innerHTML = html;
  return tmp.textContent||tmp.innerText;
  tmp = null;
}


function tag_strip(input, allowed) {
  /* use for older browser support */
  allowed = (((allowed || '') + '')
  .toLowerCase()
  .match(/<[a-z][a-z0-9]*>/g) || [])
  .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
  var commentsandphptags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsandphptags, '')
  .replace(tags, function($0, $1) {
   return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
  });
  tags = null, commentsandphptags = null;
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
        case ( document.getElementById(key).value.length < 128 ):
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

/*
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
*/

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


