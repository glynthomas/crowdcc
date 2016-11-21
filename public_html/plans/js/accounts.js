/*! accounts.js v1.00.00 
| (c) 2015 crowdcc.
| plans
| accounts.js client-side javascript
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
  
   console.log(window.location.href);
   // var uintxt = escapehtml(uriarr[5]);
   // console.log( uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] +'/'+ uriarr[3] + '/' +uriarr[4] + '/accounts'  );
   

   //var backlen = -1; 
   //    history.go(-backlen);
   //    window.history.replaceState('', 'crowdcc:', uriarr[0] + '/'+ uriarr[1] +'/'+ uriarr[2] +'/'+ uriarr[3] + '/' +uriarr[4] + '/accounts' );


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

   // }

   // _hacked_ccc();

};


function _hacked_ccc() {
  set_css_class('alert', 'backgroundColor', '#4488F6');
  set_css_class('alert', 'borderBottomColor', '#4488F6'); 
  set_css_class('notices', 'display', 'block');
  set_css_id('hacked', 'display', 'block');
  // new datepkr ('last', {'dateFormat': 'd/m/Y'});
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

  console.log(event.target.id);
  console.log('tagclass :' + tagclass);
  console.log('tagid :' + tagid);
   
  switch (true) {

    case (tagid === 'signon'):
    case (tagclass === 'signon'):
          console.log('go to signon');
          /* window.location.href = "http://crowdcchq.com"; */
          window.open('http://crowdcchq.com','_blank');
    break;

    case (tagid === 'submit_feedback'):
    case (tagclass === 'cc_btn_enable'):
          console.log('signin feedback * open blog for account pricing');
          window.location.href = "feedback";
    break;
  
  }

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

  var unchars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";

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


