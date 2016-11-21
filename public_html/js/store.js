/*! store.js v1.00.00 
| (c) 2015 crowdcc. 
| sessionStorage & session storage management
| crowdcc.com/use */

'use strict';

/* nested children for store.js */
ccc.str = ccc.str || {};
ccc.str.istw = ccc.str.istw || {};


function lstore_clear() {

  /* clear the session, reset the tweet flags */
  Session.clear();
  ccc.str.tw.fi = [];
  ccc.str.tw.fn = [];
  ccc.str.tw.fd = [];
  ccc.str.tw.bd = [];

  sessionStorage.clear();

}

if (JSON && JSON.stringify && JSON.parse) var ccsession = ccsession || (function () {   
/* if (JSON && JSON.stringify && JSON.parse) var Session = Session || (function () { */
    /* window object */
    var win = window.top || window;
    /* session store */
    var store = (win.name ? JSON.parse(win.name) : {});
    /* save store on page unload */

    function Save() {
        win.name = JSON.stringify(store);
    };

    /* page unload event */

    if (window.addEventListener) window.addEventListener("unload", Save, false);

    else if (window.attachEvent) window.attachEvent("onunload", Save);

    else window.onunload = Save;

    /* public methods */
    return {
        /* set a session variable */
        set: function (name, value) {
            store[name] = value;
        },
        /* get a session value */
        get: function (name) {
            return (store[name] ? store[name] : undefined);
        },
        /* clear session */
        clear: function () {
            store = {};
        },
        /* dump session data */
        dump: function () {
            return JSON.stringify(store);
        }
    };

})();

  /* initialize application defaults */
  /* window.tw = Session.get("tw") || { */
 
  ccc.str.tw = ccsession.get('tw') || {

  /* usr name space */
  usr: [],

  /* fw forward */
  fi: [],  /* forward read in first data stream */
  fn: [],  /* forward read new data  */
  fd: [],  /* forward read in start date / time origin */
 
  /* bk backward */
  bn: [],  /* backward read new data */
  bd: [],  /* backward read in start date / time origin */
  mi: [],  /* more foward input */

  cc: [],  /* crowd carbon forward read input: */
  cb: [],  /* crowd carbon backward read input */
  ct: [],  /* crowd carbon store total */
  cp: [],  /* crowd carbon back page scroll count */

  cg: [],  /* crowd carbon page depth */

  sc: [],  /* store count, used to space out content as required */
  st: [],  /* store total, this is the current total of sessionStorage content */
  sp: [],  /* store content back page scroll count */

  cw: [],  /* popular current duplicate records, public view, display one page */

  tg: [],  /* global time (15 mins) :: 900 */
  tp: [],  /* api time (get more fwd content) (every 3 mins or so ...) :: 120 */

  ts: [],  /* global timer status, start === 1, stop (reset) === 0, pause === 2, disable === 3 */
  
  pg: [],  /* global page depth */

  ls: [],  /* sessionStorage character count */
  tm: [],  /* temporary session store */
  };


ccc.str.istw = { '_tw': '', 'twtextcc': '', 'twtextcc_rt': '', 'twtextfi': '', 'twtextfi_rt': '', 'twmediafi_url': '', 'mouse': 0, 'ckoff': 0, 'trshid': 0, 'trshcc': 0 };

window.onload = function() {  /* onload */

  /* window.onload moved from signin.js to store.js :: start */  

  _ccleanup();

  switch (true) {
    case (isempty(ccc.str.tw.usr.ccuser)):
          /* status guest, just visiting no signin or registered user */
          /* console.log('no signin ... public unauthorised view'); */      
          if (!visible_css_class('notices') ) { set_css_id('signbtn', 'display', 'block') }
          /* public sales splash web app page */
          set_css_id('su','display','block');
    break;
    case (ccc.str.tw.usr.ccuser === 'soc'):
          /* status registered soc user, non confirmed email address
             just visiting non crowdcc user, no settings section visible in menu
             but user can register email address and convert to a ccn user, 
             nag bar with an email confirmation button visible */
          read_array('soc');
    break;
    case (ccc.str.tw.usr.ccuser === 'ccn'):
          /* status registered ccn user, non confirmed email address
             menu settings is greyed out and not accessible from this menu
             but, Username and Update email is availiable from top bar
             nag bar with an email confirmation button */
          read_array('ccn');
    break;
    case (ccc.str.tw.usr.ccuser === 'ccc'):
          /* confirmed registered ccc user, no nag bar, email confirmed */
          read_array('ccc');
    break;
  }

  ccc.str.errors = location.search.split('errors=')[1];
  /* get last signular parameter in URL * (location.search.split('myParam=')[1]||'').split('&')[0] * use with multiple params */  
  /* http://stackoverflow.com/questions/979975/how-to-get-the-value-from-the-url-parameter */

  ccc.str.verified = window.location.pathname.split( '/' );                  
  ccc.str.verified = ccc.str.verified[ccc.str.verified.length-1];                      // without .php
  /* ccc.str.verified = ccc.str.verified.substring(0, ccc.str.verified.length - 4);    // with    .php */

  ccc.str.verify = window.location.pathname.split( '/' );
  ccc.str.verify = ccc.str.verify[ccc.str.verify.length-1];                            // without .php
  ccc.str.verify = ccc.str.verify.substring(0, ccc.str.verify.length);                 

  ccc.str.ccconfirm = window.location.pathname.split( '/' );
  ccc.str.ccconfirm = ccc.str.ccconfirm[ccc.str.ccconfirm.length-1];                   // without .php
  ccc.str.ccconfirm = ccc.str.ccconfirm.substring(0, ccc.str.ccconfirm.length);        

  /* check for social twitter user, need to remove history as user may goback in error to twitter oauth transfer page */

  /* console.log('check path array for twitter url needs to be removed from path : ' + window.location.pathname.split( '/' )); */
  /* console.log('this is ccc.str.verified: ' + ccc.str.verified); */
  /* console.log('this is errors: ' + ccc.str.errors); */

  switch (true) {

    case (ccc.str.errors === 'error_ureg'):
    case (ccc.str.errors === 'error_ereg'):
    case (ccc.str.errors === 'error_tamper'):
          /* check for error code sent back in the ecode from callback, before passing on */
          process_err(ccc.str.errors);
          return false;
    break;

    case (ccc.str.verify === 'confirm'):
          /* check for error code sent back in the ecode from verify, before passing on */
          procces();
    break;

    case (ccc.str.verify === 'verify'):
          /* check for error code sent back in the ecode from verify, before passing on */
          procces();
    break;

    case (ccc.str.verified === 'verified'):
          /* user is authorised by twitter */

          /* clean up signin to twitter history, return to app. */
          /* console.log('we are run!'); */
  
          window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
          window.history.pushState('', 'crowdcc', 'http://'+ window.location.hostname );

          /* toggle spinner function */
          ccc_toploader();

          /* process twitter object returned */
          process_obj(_verifyme['_ccc'], _ccode['ceode']);

          /* clean up completed */
          clear_html_id('verify');

          /* clear_obj(_ccode); * clear_obj(_verifyme); * clear_obj(_cctokenset); */
          clear_ccprop();
       
          /* console.log('check props * _ccode * _verifyme * _ccc_token_set'); */

          set_css_class('name', 'margin-top', '0px');
          get_in_ccn();
    break;

    case (ccc.str.verified === ''):
          _instancc();
    break;

  }

  /* window.onload moved from signin.js to store.js :: end */ 

  /*
  console.log('ccc.str.tw.cc length :' + ccc.str.tw.cc.length);
  console.log('testing --> : should be null : ' + sessionStorage.getItem('_cc.fi.0') );
  console.log('ccc.str.tw.cp value :' + ccc.str.tw.cp[0] );
  console.log( 'value of ccc.str.tw.sp[0] === ' + ccc.str.tw.sp[0] );
  */

 /* protect page depth on page refresh */

  switch (true) {

    case (typeof ccc.str.tw.sp[0] === 'undefined'):
    case (ccc.str.tw.sp[0] === 0):
          /* console.log('this is true -> ccc.str.tw.sp[0] === undefined or 0'); */
          readtwin_obj(20);
          /* check window.ccc.ccw.flameon === 0 if ccc.ccw.flameon still 0 after 1s then show  */
    break;
    case (ccc.str.tw.sp[0] > 0):
          /* console.log('this is true -> ccc.str.tw.sp[0] > 0'); */
          readtwin_obj(ccc.str.tw.sp[0]);
          /* check window.ccc.ccw.flameon === 0 if ccc.ccw.flameon still 0 after 1s then show  */
    break;

  }

  /* re-set on document reload */
  ccc.str.istw['ckoff'] = 0; ccc.str.istw['mouse'] = 0; ccc.str.istw['trshid'] = 0; ccc.str.istw['trshcc'] = 0;

  reload_cc_signin();

  /* test for modern browser support * Object.keys(object).length */

}


function get_cc_signin(msg) {

/* signin * view check * allow for api data latency */

console.log('get_cc_signin * called start');
  
 if ( ccc.str.tw.tm[24] !== 1 ) {
     /* guard condition * code executed once * run once flag ccc.str.tw.tm[24] */
  
  /* console.log('get_cc_signin * called process'); */

  switch (true) {

    case (ccc.str.verified === 'verified'):
          ccc.str.tw.tm[24] = 1;
          set_css_id('su', 'display', 'none');
          set_css_id('in', 'display', 'block');
          
          /* toggle spinner function */
          ccc_toploader(msg);
          _global_actions('enable');

          /* console.log('we have come from twitter oAuth yay!'); */
    break;

    case (ccc.str.tw.usr.ccuser === 'soc'):
    case (ccc.str.tw.usr.ccuser === 'ccn'):
    case (ccc.str.tw.usr.ccuser === 'ccc'):
          
          pausecc();

          ccc.ccw.tidcs = setTimeout( function(){ chk_cc_signin() } , 500);
  
    break;

    case (typeof ccc.str.tw.usr.ccuser === 'undefined'):
          
          set_css_id('su', 'display', 'block');
          set_css_id('in', 'display', 'none');

          /* console.log('get_cc_signin * ccc.str.tw.usr.ccuser === undefined'); */
    break;

  }

 }
    /* console.log('get_cc_signin * called end'); */
}


function chk_cc_signin() {

/* signin check function * allow for api data latency half a second */

  /* console.log( 'chk_cc_signin() * called' ); */

  switch (true) {

    case ( typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null ):
          /* guard condition * code executed * only when content is in */
          
          ccc.str.tw.tm[24] = 1; /* prevent mutiple execution */
          
          /* toggle spinner function */
  
          _global_actions('enable');
          get_in_ccn();

          set_css_id('su', 'display', 'none');
          set_css_id('in', 'display', 'block');

          ccc_toploader('notweet');

          clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
          resumecc();
          return true;

    break;

    case ( ccc.str.tw.tm[24] === 2 ):
          /* guard condition * code executed * only when content is known to be zero (no tweets at all!) */

          ccc.str.tw.tm[24] = 1; /* prevent mutiple execution */
          
          /* toggle spinner function */
   
          _global_actions('enable');
          get_in_ccn();

          set_css_id('su', 'display', 'none');
          set_css_id('in', 'display', 'block');

          ccc_toploader('notweet');

          clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
          resumecc();
          return true;
    
    break;

    default:
          /* console.log('signin error?'); */
          clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
          resumecc();
          return false;
    break;
  
  }
}


function reload_cc_signin() {

  if ( typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null ) {
       /* guard condition * code executed * only when content is in */

    if (ccc.str.tw.ts[0] !== 0) { 
        /* guard condition * double check timer * paused */
        resumecc();
    } 
  }
}


function get_cc_auth() {

/* client side api token ccauth check */

  var _cau = { 'cau':'', 'flg':'', 'usr':'' };

  _cau['flg'] = base64_encode('_cau');
  _cau['usr'] = encrypt(base64_decode(ccc.sig.iset['ucode']));

  _cau['cau'] = '_cau' + '=' + _cau['flg'] + ':' + _cau['usr'];

  _process('post', '_cau', _cau['cau'], 'ca');

  _cau['usr'] = null; _cau['flg'] = null; _cau['cau'] = null; _cau = null;

}


function get_in_ccn() {

/* ccn user, non confirmed email address * msg bar with email confirmation button */

  if ( typeof ccc.str.tw.usr.screen_name !== 'undefined' && ccc.str.tw.usr.ccuser === 'ccn') {
      
       /* enable nag bar * error_pc5de * notices */

       set_css_class('notices', 'display', 'block');
       set_css_class('error_pc5de', 'display', 'block');
       set_css_class('ui-dialog-noticebar-close', 'display', 'none');
       set_css_id('in','margin-top','-15px');
       set_css_id('cc','margin-top','-15px');
       /* set_css_id('noticebar-close-flix', 'display', 'block'); */

       /* enable nag bar * error_pc5de * notices */
       /* console.log('get_in_ccn * non confirmed email address msg bar!'); */
  }

}


function get_in_follow(msg) {

 /* ccc.str.tw.usr.ccfollow === 0 : no follow * ccc.str.tw.usr.ccfollow === 2 : promise follow * ccc.str.tw.usr.ccfollow === 1 : following */

  switch (true) {

    case (typeof ccc.str.tw.usr.screen_name !== 'undefined' && ccc.str.tw.usr.ccfollow === 2 ):
    case (msg === 'inapp' && ccc.str.tw.usr.ccfollow !== 1):

         /* flag signal * ccc.str.tw.usr.ccfollow === 1 */

         var _foo = { 'foo':'', 'flg':'', 'usr':'' };

         _foo['flg'] = base64_encode('_foo');
         _foo['usr'] = encrypt(ccc.str.tw.usr.screen_name);

         _foo['foo'] = '_foo' + '=' + _foo['flg'] + ':' + _foo['usr'];
          
         _process('post', '_fo', _foo['foo'] ,'fo');

         _foo['usr'] = null; _foo['flg'] = null; _foo['foo'] = null; _foo = null;
          
         /* console.log('follow flag to process'); */

    break;
  
  }

}


function get_in_fwobj() {

/* collect from session window.global screen_name ? currenty using ccc.sig.iset['ucode'] default to 30 */

  var _cff = { 'cff':'', 'flg':'', 'cfw':'', 'usr':'', 'tok':'' };

  _cff['flg'] = base64_encode('_cf');
  _cff['cfw'] = base64_encode('30');
  _cff['usr'] = encrypt(base64_decode(ccc.sig.iset['ucode']));
  _cff['tok'] = base64_encode(ccc.str.tw.usr.cctoken);

  _cff['cff'] = '_cff' + '=' + _cff['flg'] + ':' + _cff['cfw'] + ':' + _cff['usr'] + ':' + _cff['tok'];

  _process('post', '_cf', _cff['cff'] ,'fw');

  _cff['tok'] = null; _cff['usr'] = null; _cff['cfw'] = null; _cff['flg'] = null; _cff['cff'] = null; _cff = null;

}


function get_in_bkobj() {

/* collect from session window.global screen_name ? currenty using ccc.sig.iset['ucode'] default currenty 80 optimised at 120 */

  var _cbb = { 'cbb':'', 'flg':'', 'cbk':'', 'usr':'', 'xbk':'', 'tok':'' };

  _cbb['flg'] = base64_encode('_cb');
  _cbb['cbk'] = base64_encode('80');
  _cbb['usr'] = encrypt(base64_decode(ccc.sig.iset['ucode']));
  _cbb['xbk'] = base64_encode(JSON.parse(sessionStorage['_cc.fi.0']).id_str);
  _cbb['tok'] = base64_encode(ccc.str.tw.usr.cctoken);
 
  _cbb['cbb'] = '_cbb' + '=' + _cbb['flg'] + ':' + _cbb['cbk'] + ':' + _cbb['usr'] + ':' + _cbb['xbk'] + ':' + _cbb['tok'];

  _process('post', '_cb', _cbb['cbb'] ,'bk');

  _cbb['tok'] = null; _cbb['xbk'] = null; _cbb['usr'] = null; _cbb['cbk'] = null; _cbb['flg'] = null; _cbb['cbb'] = null; _cbb = null;

}

function get_in_ccobj(screen_name, start, count) {

  console.log('we are in ccobj!');

  /* collect from session window.global screen_name 
     check for signin ... line 1289 : get_in_ccobj(ccc.str.tw.usr.screen_name, 0, 10); 
     for api access.

     get_in_ccobj(screen_name, start, count);
     get_in_ccobj('glynthom', 0, 20);

     also cc used directly for safe deletion. 
  */
  
  var _cco = { 'cco':'', 'flg':'', 'usr':'', 'cst':'', 'cfw':'' };
  
  _cco['flg']  = '_co';
  _cco['usr']  =  screen_name;
  _cco['cst']  =  start.toString();      /* start row record to returned from crowdcc API */
  _cco['cfw']  =  count.toString();      /* records to get returned from start row from crowdcc API */
  
  console.log( 'screen_name: ' + _cco['usr'] + '|' + _cco['cst'] + '|' + _cco['cfw']);
              
  _cco['cco'] = '_cco' + '=' + base64_encode( _cco['flg'] ) + ':' + encrypt( _cco['usr'] ) + ':' + base64_encode( _cco['cst'] ) + ':' + base64_encode( _cco['cfw'] );
      
  _process('post', '_co', _cco['cco'] ,'cc');

  _cco['cfw'] = null; _cco['cst'] = null; _cco['usr'] = null; _cco['flg'] = null; _cco['cco'] = null; _cco = null;

}


function _process(ctype, cwho, cdata, cpath) {

    var data = '';
    var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('//', '/') + '/';
    /* var baseurl = window.location.protocol + "//" + (window.location.host + "/" + window.location.pathname).replace('//', '/'); */
    /* configured to allow for requests to different resources for load balance option * default * baseurl */
    /* console.log('using default baseurl :' + baseurl); */
   
    switch (ctype) {

       case ('get'):

             getdata(baseurl + cpath, callrequest, 'url', cwho);

       break;
      
       case ('post'):

            switch (cwho) {

              case ('_cf'):  /* forwards */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;
              case ('_cb'):  /* backwards */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;
              case ('_cc'):  /* cc current * store */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;
              case ('_co'):  /* cc copy * store */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;
              case ('_rt'): /* retweet tweet */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;
              case ('_rf'): /* retweet * favor tweet */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);

              break;
              case ('_ft'): /* favor tweet */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;
              case ('_rp'): /* reply tweet */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

              case ('_rm'): /* reply tweet with media */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

              case ('_cm'): /* mail tweet */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

              case ('_st'): /* share / create tweet */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

              case ('_sm'): /* share / create tweet with media */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;
              case ('_sh'):  /* cc copy * store * safe tra_sh */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

              case ('_on'):  /* cc carbon * copy * carb_on copy */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

              case ('_cp'):  /* cp most (or duplicate) records, display users who have stored the most data with crowdcc  */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
                    /* console.log('we are in process _cp'); */
              break;

              case ('_cw'):  /* cw same (or duplicate) records, display most frequent tweets with crowdcc  */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
                    /* console.log('we are in process _cw'); */
              break;

              case ('_fo'):  /* ccc.str.tw.usr.ccfollow === 0 : no follow * ccc.str.tw.usr.ccfollow === 2 : promise follow * ccc.str.tw.usr.ccfollow === 1 : following */
              
                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
                    /* console.log('follow flag to process'); */
              break;


              /* moved from signin.js :: start */

              case ('_ccc'):

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
           
              break;

              case ('_sin'):

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
              
              break;

              case ('_cci'):

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
              
              break;

              case ('_cco'):

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
          
              break;

              case ('_cau'): /* ccid token check ... */

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
  
              break;

              case ('_cya'):

                    postdata(baseurl + cpath, callrequest , 'url',  cdata , cwho);
                  
              break;

              /* moved from signin.js :: end */
    
            }

       break;
    }

    baseurl = null;

    callrequest(data);

    data = null;
}


function callrequest(data) {
   
  /* responce data || user messaged || error handling  */

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
                  
                  ccc.sig.iset['kcode'] = datastr[3];
                  set_html_val('authenticity_token', datastr[3]);

                  /* console.log('update auth token: ' + datastr[3]); */

                  /*
                  document.getElementsByTagName("input")[0].value = datastr[3];
                  document.getElementById("authenticity_token").value = datastr[3]; 
                  */   
            break;

            case ('error'):
                  ccc.sig.isvalid['enablekey'] = 0;  /* turn off keyup */
                         
                  /*  error handler for normal scope errors :
                      error_pc4de - connection, could not connect to twitter, returned from Oauth lib */

                  set_css_id('ccc_load', 'display', 'none');
                  set_css_id('signbtn', 'display', 'none');
                  
                  /* var err_str = JSON.stringify(data); */
                  /* console.log(err_str); */
                  /* err_str = null; */
                  
                  set_css_class('controls', 'display', 'none');
                  set_css_class('notices', 'display', 'block');
                  set_css_class('ui-dialog-noticebar-close', 'display', 'block');

                  set_css_class('error_pc4de', 'display', 'block');
                  set_css_id('frmsig', 'display', 'none');
                  _clear_sign();
   
                  /* console.log(datastr[1]); */
                  /* console.log(datastr[2]); */
                  /* console.log('unable to connect to crowdcc, please try later') */
            break;
             
            }
            datastr = null;
      break;

      case ('post'):          
          /* post returned good (error not bubbled from post action) check postfrom */
  
            switch (true) {

              case (datastr[1] === '_rt'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */
                     
                     switch ( trimquote(datastr[3]) ) {

                       case ('retweet_success_200'):
                             ccc.str.tw.tm[14] = 2;     /* retweet ccc.str.tw.tm[14] tweet success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }   
                    }
              break;

              case (datastr[1] === '_rf'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */

                     switch ( trimquote(datastr[3]) ) {

                       case ('refavor_success_200'):
                             ccc.str.tw.tm[14] = 2; ccc.str.tw.tm[15] = 2;     /* refavor ccc.str.tw.tm[15] tweet success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }
                    }
              break;

              case (datastr[1] === '_ft'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */

                     switch ( trimquote(datastr[3]) ) {

                       case ('favor_success_200'):
                             ccc.str.tw.tm[15] = 2;     /* favor ccc.str.tw.tm[15] tweet success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }
                    }
              break;

              case (datastr[1] === '_rp'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */
                     
                     switch ( trimquote(datastr[3]) ) {

                       case ('reply_success_200'):
                             ccc.str.tw.tm[16] = 2;     /* reply ccc.str.tw.tm[16] tweet success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }  
                    }
              break;

              case (datastr[1] === '_rm'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */
                     
                     switch ( trimquote(datastr[3]) ) {

                       case ('reply_media_success_200'):
                             ccc.str.tw.tm[17] = 2;     /* reply with media ccc.str.tw.tm[17] tweet success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }  
                    }
              break;

              case (datastr[1] === '_cm'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */
                     
                     switch ( trimquote(datastr[3]) ) {

                       case ('mail_success_200'):
                             ccc.str.tw.tm[19] = 2;     /* mail ccc.str.tw.tm[19] sent success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }  
                    }
              break;

              case (datastr[1] === '_st'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */
                     
                     switch ( trimquote(datastr[3]) ) {

                       case ('share_success_200'):
                             ccc.str.tw.tm[20] = 2;     /* share / create ccc.str.tw.tm[20] sent success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }  
                    }
              break;

              case (datastr[1] === '_sm'):
                    if ( twitter_call_err( datastr[2], trimquote(datastr[3]) ) ) {

                        /* console.log('we are here ... good'); */
                        /* console.log( datastr[2] ); */
                     
                     switch ( trimquote(datastr[3]) ) {

                       case ('share_media_success_200'):
                             ccc.str.tw.tm[21] = 2;     /* share / create with media ccc.str.tw.tm[21] sent success */

                             refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                       break;

                     }  
                    }
              break;

              case (datastr[1] === '_sh'):
                 
                    /* console.log('we are here ... good'); */
                    /* console.log( datastr[2] ); */

                    if ( twitter_api_err( datastr[2] ) ) {
                                
                      datastr[2] = JSON.parse( datastr[2] );

                      switch ( datastr[2].ok[0].message ) {
                     
                        case ('Trash, media success'):
                              ccc.str.tw.tm[22] = 2;     /* trash media ccc.str.tw.tm[22] sent success */
                              refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                              update_space(0);

                              /* console.log('trash_media_success_200'); */
                        break;

                        case ('Trash, media not found'):
                              ccc.str.tw.tm[22] = 3;     /* trash media ccc.str.tw.tm[22] error tweet not found */
                              refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
                              /* console.log('trash_media_error_205'); */
                        break;

                      }  
                    }
              break;

              case (datastr[1] === '_on'):
                 
                    /* console.log('we are here in carbon ... good '); */
                    /* console.log( datastr[2] ); */

                    if ( twitter_api_err( datastr[2] ) ) {

                      datastr[2] = JSON.parse( datastr[2] );

                      switch ( datastr[2].ok[0].message ) {
                     
                        case ('Carbon, media success'):
                              ccc.str.tw.tm[23] = 2;     /* carbon media ccc.str.tw.tm[23] copied success * success_200*/
                              refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

                              update_space(1);
                              
                              /* console.log('media success'); */
                        break;

                        case ('Carbon, media already posted'):
                              ccc.str.tw.tm[23] = 3;     /* carbon media ccc.str.tw.tm[23] already copied * error_205 */
                              refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
                              /* console.log('media already posted'); */
                        break;

                        case ('Carbon, media limit reached'):
                              ccc.str.tw.tm[23] = 7;     /* carbon media ccc.str.tw.tm[23] limit reached * error_211 */
                              refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
                              /* console.log('media limit reached!'); */
                        break;

                      }
                    }
              break;

              case (datastr[1] === '_cf'):
   
                    /* no error objects returned, assume correctly returned json object */

                    if ( twitter_api_err( datastr[2] ) ) {

                        /* console.log('_cf :: readin_fw_srtwobj(JSON.parse( datastr[2] ))'); */
                        /* console.log(JSON.parse( datastr[2] )); */
                        readin_fw_srtwobj(JSON.parse( datastr[2] ));
                    }
              break;

              case (datastr[1] === '_cb'):

                    /* no error objects returned, assume correctly returned json object */

                    if ( twitter_api_err( datastr[2] ) ) {
                        /* console.log(JSON.parse( datastr[2] )); */

                        readin_bk_srtwobj( JSON.parse( datastr[2] ));

                        ccc.str.tw.sp[0] = (ccc.str.tw.sc[0] + ccc.str.tw.pg[0]);

                        readtwin_obj(ccc.str.tw.sp[0]);
                    }
              break;

              case (datastr[1] === '_cc'):

                    /* no error objects returned, assume correctly returned json object */

                    if ( twitter_api_err( datastr[2] ) ) {
                        /* console.log( JSON.parse( datastr[2] ) ); */

                        readin_fw_srccobj( JSON.parse( datastr[2]), 10);
                    }
              break;

              case (datastr[1] === '_co'):

                    /* no error objects returned, assume correctly returned json object */
                    /* firefox 26 browser bug undefined type ccc.str.tw.cc and JSON not parsed correctly! */

                    /* console.log( JSON.parse( datastr[2] ) ); */

                    if ( twitter_api_err( datastr[2] ) ) {

                      var co_json = ''; var co_err = '';

                      try { co_json = JSON.parse( datastr[2] ) } catch (co_err) { co_json = datastr[2] }
    
                      /* console.log( 'json error :' + co_err  ); */

                      if (ccc.str.tw.cc.length === 0) {

                        switch (true) {
                          case (co_err === ''):
                          break;
                          case (co_err !== ''):
                                /* console.log( 'json error :' + co_err  ); */
                          break;
                        }

                        readin_fw_srccobj( co_json, 10);
                    
                      } else {

                        switch (true) {
                          case (co_err === ''):
                          break;
                          case (co_err !== ''):
                                /* console.log( 'json error :' + co_err  ); */
                          break;
                        }

                        readin_bk_srccobj( co_json, 10);
                    
                      }

                    co_json = null; co_err = null;

                   }

              break;

              case (datastr[1] === '_cp'):

                    /* no error objects returned, assume correctly returned json object */

                    if ( twitter_api_err( datastr[2] ) ) {

                         /* console.log( JSON.parse( datastr[2] ) ); */

                         /* not implemented yet */

                         /* readin_cc_srtwobj( _obj ); * readin_cc_srtwobj( JSON.parse( datastr[2] )); * readin_fw_srccobj( JSON.parse( datastr[2]), 10); */


                    }
              break;

              case (datastr[1] === '_cw'):

                    /* no error objects returned, assume correctly returned json object */

                    if ( twitter_api_err( datastr[2] ) ) {
                     var cw_json = '';

                         /* console.log( JSON.parse( datastr[2] ) ); */

                         cw_json = readin_cw_srccobj( JSON.parse(datastr[2]) );

                         if (cw_json > 0) {

                             /* console.log('yeah we read it in ...!'); */
                             read_cwin_obj(30); /* readto whatever is the full page view for imac */

                         }

                         cw_json = null;
                        
                        /* readin_cc_srtwobj( _obj ); * readin_cc_srtwobj( JSON.parse( datastr[2] )); * readin_fw_srccobj( JSON.parse( datastr[2]), 10); */

                    }
              break;

              case (datastr[1] === '_fo'):

                    /* no error objects returned, assume correctly returned json object */

                    /* console.log('back from * follow flag to process !'); */
                    /* console.log( datastr[2] ); */

                    switch (true) {

                      case (datastr[2].indexOf('correct') > -1): /* > -1 * alt to * !== 1 */
                            ccc.str.tw.usr.ccfollow = 1;
                      break;

                      case (datastr[2].indexOf('error') > -1):  /* > -1 * alt to * !== 1 */
                            ccc.str.tw.usr.ccfollow = 0;
                      break;

                    }

              break;


              /* moved from signin.js :: start */

              case (datastr[1] === '_ccc'):
                   /* now check for error bubbled, returned from process */

                   /* console.log('datastr[2] ...' . datastr[2]); */
                   /* alert('ok we are here datastr[1] ...' . datastr[1]); */

                   switch (true) {

                      case (datastr[2].indexOf('ccn') !== -1):
                      case (datastr[2].indexOf('ccc') !== -1):
                            /* return   ===    'correct process'          */
                            /* console.log(' we no error ' + datastr[2]); */
                            /* console.log('process social signin'); */
                            process_obj(datastr[2]);

                      break;

                      case (datastr[2].indexOf('_uri') !== -1):
                            
                            /* console.log('this is a url that needs clean up and redirection ...'); */
                            /* console.log(datastr[3]); */
                            datastr[3] = datastr[3].replace(/\\\//g, "/");
                            datastr[3] = trimquote(datastr[3]);

                            ccc.str.istw['_tw'] = datastr[3];
                          
                            /* console.log('URL redirect ...'); */

                            pausecc();

                            ccc.ccw.tidcs = setTimeout( function(){ curi() } , 500);

                      break;

                      case (datastr[2].indexOf('error') !== -1):
                            ccc.sig.isvalid['enablekey'] = 0;  /* turn off keyup */
                            _ccleanup('error');  
                            _click('reset');
                              
                            datastr[3] = trimquote(datastr[3]);
            
                            /* console.log(' we have found an error ' + datastr[2]); */
                            /* console.log(' error code *|' + datastr[3] + '|*'); */

                            switch (true) {

                              case (datastr[3] === 'error_ucode'):
                                    isbarone('fix');
                                    ccstate('error_ucode');
                              break;

                              case (datastr[3] === 'error_fail_ecode'): /* email pass (found in db, pass to token function), but have failed to be able to send it! */
                              case (datastr[3] === 'error_ecode'):
                                    isbarone('fix');
                                    ccstate('error_ecode');
                              break; 

                              case (datastr[3] === 'error_pcode'):
                                    isbarone('fix');
                                    ccstate('error_pcode');
                              break;

                              case (datastr[3] === 'error_tcode'):
                                    ccstate('error_tcode');
                              break;

                              case (datastr[3] === 'error_pass_tcode'):
                                    ccstate('pass_tcode');
                                    /* console.log('... email and password added to screen name confirmed'); */
                              break;

                              case (datastr[3] === 'error_pass_scode_fcode'):
                                    /* console.log(' we are going to ccstate (pass_scode_fcode) '); */
                                    ccstate('pass_scode_fcode');
                                    /* console.log('... email and password added to screen name unconfirmed'); */
                              break;

                              case (datastr[3] === 'error_pass_scode'):
                                    /* console.log(' we are going to ccstate (pass_scode) '); */
                                    ccstate('pass_scode');
                                    /* console.log('... email and password added to screen name unconfirmed'); */
                              break;

                              case (datastr[3] === 'error_pass_ecode'):
                                    isbarone('fix');
                                    ccstate('pass_ecode');  
                              break;

                              case (datastr[3] === 'error_tamper'):
                                    ccstate('error_tamper');
                              break;

                              case (datastr[3] === 'error_network'):
                                    ccstate('error_network');
                              break;

                              case (datastr[3] === 'error_refresh'):
                                    ccstate('error_refresh');
                              break;
                            }
                  
                        break;
  
                      }
                      
              break;

              case (datastr[1] === '_sin'):
                 /* now check for error bubbled, returned from process */
                    /* console.log('datastr[2] : ' + datastr[2]); */
                    /* console.log('datastr[3] : ' + datastr[3]); */

                      datastr[3] = trimquote(datastr[3]);

                      switch (true) {
                        
                        case ( datastr[2].indexOf('error') !== -1 ):
                              ccc.sig.isvalid['enablekey'] = 0;  /* turn off keyup */
                              /* console.log(' we have found an error datastr[2]' + datastr[2]); */
                              /* console.log(' we have found an error datastr[3]' + datastr[3]); */

                              switch (true) {

                                case (datastr[3] === 'error_pc0de'): /* error no tokenstore / timestamp match! */
                                      ccstate('error_pc0de');
                                break;

                                case (datastr[3] === 'error_pc1de'): /* invalid link or password already changed! */
                              
                                case (datastr[3] === 'error_pc2de'): /* the email record not found / is bad! */
                                
                                case (datastr[3] === 'error_pc3de'): /* failure db record error update! */
                                      ccstate('error_pc1de');
                                break;

                              }

                        break;

                        case ( datastr[2].indexOf('error') === -1 ):
                              /* return   ===    'correct success'          */
                              /* console.log(' we have no error ' + datastr[2]); */
                              ccstate('pass_pcode');
                              window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
                              /* console.log('... the password should be updated!'); */
                        break;
                      
                      }

              break;

              case (datastr[1] === '_cau'):
                    /* now check for error bubbled, returned from process */
                    datastr[2] = trimquote(datastr[2]);

                    var spacedat = datastr[2].split(',');

                    /* set account limit * set current space used */

                    ccc.str.tw.usr.cclimit = Number(spacedat[1]); /* 50cc */
                    ccc.str.tw.usr.ccspace = Number(spacedat[2]); /* 36cc */

                    set_html_id('acc-spa-tag-num',( spacedat[1] - spacedat[2] ));
              
                    spacedat = null;      
 
              break;

              case (datastr[1] === '_cci'):

                      datastr[3] = trimquote(datastr[3]);

                      switch (true) {
                        
                        case ( datastr[2].indexOf('error') !== -1 ):
                              ccc.sig.isvalid['enablekey'] = 0;  /* turn off keyup */
                              /* console.log(' we have found an error ' + datastr[2]); */

                              switch (true) {

                                case (datastr[3] === 'error_em0n'):  /* error invalid timestamp */
                                    
                                case (datastr[3] === 'error_em1n'):  /* error timestamp token too old */
                                      ccstate('error_em0n');
                                break;
                              
                                case (datastr[3] === 'error_em2n'):  /* token invalid or expired, not found in db! */
                                
                                case (datastr[3] === 'error_em3n'):  /* email match or not found in db! */
                                    
                                case (datastr[3] === 'error_em4n'):  /* email match or not found in db! */
                             
                                case (datastr[3] === 'error_em5n'):  /* the timezone of user do not match in db! */
                             
                                case (datastr[3] === 'error_em6n'):  /* password match or not found failure! */
                                      ccstate('error_em6n');
                                break;

                              }

                        break;

                        case ( datastr[2].indexOf('error') === -1 ):
                              /* return   ===    'correct success'          */
                              /* console.log(' we have no error ' + datastr[2]); */
                              ccstate('pass_emin');
                              window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
                              /* console.log('email has been confirmed, the db has been updated!'); */
                              _cctokenset._ccc = ''; _cctokenset.ecode = ''; _cctokenset.emsg = ''; _cctokenset.pcode = ''; 
                        break;

                      }  

              break;

              case (datastr[1] === '_cco'):

                      datastr[3] = trimquote(datastr[3]);

                      /* console.log(' error code *|' + datastr[3] + '|*'); */

                      switch (true) {
                        
                        case ( datastr[2].indexOf('error') !== -1 ):
                              ccc.sig.isvalid['enablekey'] = 0;  /* turn off keyup */

                              /* console.log(' we have found an error ' + datastr[2]); */
                              /* console.log(' the value of datastr[3] == ' + datastr[3]); */

                              switch (true) {

                                case (datastr[3] === 'error_ucode'):      /* invalid link or link expired */
                                      ccstate('error_uc0de');
                                break;

                                case (datastr[3] === 'error_ecode'):      /* invalid link or link expired */
                                      ccstate('error_ec0de');  
                                break;

                                case (datastr[3] === 'error_snd_ecode'):  /* just inform user that the email has been sent to given email address. */
                                      /* console.log('we should be going to ... ccstate snd_ecode ->line 861'); */
                                      ccstate('snd_ecode');
                                break;

                                case (datastr[3] === 'error_rst_ecode'):  /* email match or not found in db! */
                                      ccstate('rst_ecode');  
                                break;
                                    
                                case (datastr[3] === 'error_idb_ecode'):  /* error new email address already in use! */
                                      ccstate('idb_ecode');  
                                break;
                             
                                case (datastr[3] === 'error_end_ecode'):  /* fatal error the original email address not found! */
                                      ccstate('end_sess');  
                                break;

                                case (datastr[3] === 'error_tamper'):
                                      ccstate('error_tamper');
                                break;
                              }

                        break;

                        case ( datastr[2].indexOf('error') === -1 ):
                              /* return   ===    'correct success'          */
                              /* console.log(' we have no error ' + datastr[2]); */
                 
                              ccstate('pass_emin');
                              window.history.replaceState('', 'crowdcc', 'http://'+ window.location.hostname );
                              /* console.log('email has been confirmed, the db has been updated!'); */
                        break;

                      }  

              break;

                
              case (datastr[1] === '_cya'):
                      /* now check for error bubbled, returned from process */
                   
                      /* console.log(' error code *|' + datastr[2] + '|*'); */

                      switch (true) {

                        case ( datastr[2].indexOf('session') !== -1 ):
                               /* return   ===    'correct success' */
                               /* console.log(' we have no error ' + datastr[2]); */
                               /* console.log('everything should be processed!'); */
                        break;
                        
                        /*
                        case ( datastr[2].indexOf('cookie') !== -1 ):
                              // returned from cya cookie's monstered success, now to eat the session
                              // post _cya eat the server session without milk!
                              console.log('we is back to ready to eat session !');
                              _process('post', '_cya', 'signout=goodbye', 'cya');
                        break;
                        */

                        case ( datastr[2].indexOf('error') !== -1 ):
                              ccc.sig.isvalid['enablekey'] = 0;  /* turn off keyup */
                              /* console.log(' we have found an error ' + datastr[2]); */
                        break;

                        case ( datastr[2].indexOf('error') === -1 ):
                              /* return   ===    'correct success' */
                              /* console.log(' we have no error ' + datastr[2]); */
                        break;

                      }

              break;

              /* moved from signin.js :: end */

            }
     break;

     case ( datastr[2].indexOf('error_obj') !== -1):
            /* console.log('error, no obj returned, data => ' + data); */
     break; 

     /* bubbled error from postsend */
     case ('error'):
           ccc.sig.isvalid['enablekey'] = 0;  /* turn off keyup */
           
           /*  error handler for normal scope errors :
               error_pc4de - connection, could not connect to twitter, returned from Oauth lib */

           set_css_id('ccc_load', 'display', 'none');
           set_css_id('signbtn', 'display', 'none');

           /* var err_str = JSON.stringify(data); */

           /*  error_pc4de -> Could not connect to Twitter. Refresh the page or try again later.
               search string for error message returned, or return error codes ... then display to user
               for now display generic connection error message to user ... */

           /* console.log(err_str); */
           /* err_str = null; */

           set_css_class('controls', 'display', 'none');
           set_css_class('notices', 'display', 'block');
           set_css_class('ui-dialog-noticebar-close', 'display', 'block');
           set_css_class('error_pc4de', 'display', 'block');
           set_css_id('frmsig', 'display', 'none');
           isbarone('fix');

           /* clear down signin details ... */
           _clear_sign();
       
           /* console.log(datastr[1]); */
           /* console.log(datastr[2]); */
           /* console.log('unable to connect to crowdcc, please try later'); */
     break;

    }

  }

  datastr = null;

}


function curi() {
  /* console.log( ccc.str.istw['_tw'] ); */
  document.location.href = ccc.str.istw['_tw'];
  clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
  resumecc();
  ccc.str.istw['_tw'] = [];
}


function twitter_call_err(arr_err, arr_code) {

  /* console.log(arr_err); */
  /* console.log(arr_code); */

  switch (true) {

    case (arr_err.indexOf('error') !== -1):

          /* API errors * modals * start */
                  
          /* re-set modal headers */
          set_css_id('retweet-tweet-dialog-header', 'display', 'none');
          set_css_id('reply-tweet-dialog-header', 'display', 'none');
          set_css_id('mail-tweet-dialog-header', 'display', 'none');
          set_css_id('create-tweet-dialog-header', 'display', 'none');

          /* close down modal */
          set_css_id('modal-inner', 'display', 'none');
          set_css_id('modal-text', 'display', 'none');

          /* twitter API 403 forbidden * retweets forbidden * 404 has been deleted * already retweeted * tweet has been deleted * tweet blocked by the user * tweet cannot retweet yourself. */

         switch (true) {

            case (arr_code.indexOf('valid') !== -1):
            case (arr_code.indexOf('retweet_error_403') !== -1):
            case (arr_code.indexOf('retweet_error_404') !== -1):

            case (arr_code.indexOf('favor_error_403') !== -1):
            case (arr_code.indexOf('favour_error_404') !== -1):

            case (arr_code.indexOf('reply_error_403') !== -1):
            case (arr_code.indexOf('reply_error_404') !== -1):

            case (arr_code.indexOf('reply_media_error_403') !== -1):
            case (arr_code.indexOf('reply_media_error_404') !== -1):

            case (arr_code.indexOf('share_error_403') !== -1):
            case (arr_code.indexOf('share_error_404') !== -1):

            case (arr_code.indexOf('share_media_error_403') !== -1):
            case (arr_code.indexOf('share_media_error_403') !== -1):
       
            case (arr_code.indexOf('mail_error') !== -1):

                  /* API 403 404 * mail_error * sharing is not permissible * tweet already re-tweeted re-favoured deleted or a protected tweet */

                  message_('message_close', 'rrot');
                  msgtmr_('close');
                  console.log('twitter call error: ' + arr_code);
                  return false;
            break;

            case (arr_code.indexOf('retweet_error_327') !== -1):
            case (arr_code.indexOf('favor_error_327') !== -1):
            case (arr_code.indexOf('favor_error_139') !== -1):

                  /* API 327 * sharing is not permissible * tweet already re-tweeted re-favoured or a protected tweet! */

                  message_('message_close', 'prot');
                  msgtmr_('close');
                  console.log('twitter call error: ' + arr_code);
                  return false;
            break;
            /* API errors * modals * end */

          }

    break;

  }

  return true;

}


function twitter_api_err(obj) {

  /* api twitter errors are returned in the JSON format, they are;

    304: {"errors":[{"message":"There was no data to return","code":304}]}
    34:  {"errors":[{"message":"Sorry, that page does not exist","code":34}]}
    130: {"errors":[{"message":"Over capacity","code":130}]}
    131: {"errors":[{"message":"Internal error","code":131}]}

  */

  try {

  if (typeof obj !== 'undefined' && obj !== null || obj !== '') {

  /* TypeError: _obj is null rtn from API */

  var _obj = JSON.parse( obj );

  /* console.log(_obj); */

    if (_obj.errors) {

      set_css_class('sdn_more', 'display', 'block');
      set_css_class('sdn_wait', 'display', 'none');
                     
      switch (_obj.errors[0].message) {

        case ('Over capacity'):
              console.log('Woaw, everything is a bit busy right now, please try later : over capacity!');
              message_('message_close', 'cape');
              /* msgtmr_('close'); */
              _obj = null;
              return false;
        break;

        case ('Rate limit exceeded'):
              console.log('Woaw, everything is a bit busy right now, please try later : rate limit exceeded!');
              message_('message_close', 'cape');
              /* msgtmr_('close'); */
              _obj = null;
              /* _signout(); */
              return false;
        break;

        case ('Internal error'):
              console.log('Woaw, something has gone wrong, while we investigate, please try to re-signin later : internal error!');
              message_('message_close', 'inte');
              /* msgtmr_('close'); */
              _obj = null;
              return false;
        break;

        case ('Sorry, that page does not exist'):
              console.log('Woaw, something has gone wrong, while we investigate, please try to re-signin : page does not exist!');
              message_('message_close', 'page');
              /* msgtmr_('close'); */
              _obj = null;
              return false;
        break;

        case ('There was no new data to return'):
              /* random return null from twitter API, maybe config issues, need to investigate ... 23rd sept
                 https://github.com/J7mbo/twitter-api-php/commit/a31eca4ae5740061f9140d3c25ca360e108204b4 */
              console.log('There was no new data to return : no new data null returned!');
              _obj = null;
              return true;
        break;
      
      }
    }
  }

  return true;

  } catch (e) {

    /* error handle * error check */
 
    switch (true) {
      case (e instanceof TypeError):
            /* SyntaxError: JSON.parse: unexpected character at line 1 column 1 of the JSON data * var _obj = JSON.parse( obj ); */
            /* console.log('TypeError detected : ' + e); */
      break;

      case (e instanceof RangeError):
            /* console.log('RangeError detected : ' + e); */
      break;

    }
  }
}


function readin_fw_srtwobj(data) {

  console.log('readin_fw_srtwobj :: data length : ' + (data.length) );

  switch (true) {

    case (data.length !== 0):

          /* console.log('process fw obj'); */
          if (sessionStorage.length === 0) {

            for (var i = 0; i < data.length; i++) {
                var item = data[i];      
                ccc.str.tw.fi.unshift(item);
                /* Session.set('tw', tw); * moved to global namespace */
                ccsession.set('tw',ccc.str.tw);
            }

            for (var i = 0; i <  ccc.str.tw.fi.length; i++) {      
                sessionStorage.setItem('_cc.fi.' + i, JSON.stringify( ccc.str.tw.fi[i] ));
            }

            ccc.str.tw.ls[0] = JSON.stringify(sessionStorage).length;
            ccc.str.tw.bd[0] = JSON.parse(sessionStorage[ '_cc.fi.0' ]).created_at;
            ccc.str.tw.fd[0] = JSON.parse(sessionStorage[ '_cc.fi.'+ (JSON.stringify(Object.keys(sessionStorage)).split(',').length-1)]).created_at;

            /* console.log('ok this is the first time'); */

            ccc.str.tw.sc[0] = 0;       // declare session 0
            ccc.str.tw.sp[0] = 0;       // declare session 0
   
            readtwin_obj(20);
            item = null;

          } else {
          /* ok so the fi is not empty, so need to cycle through the create_date of the new incoming feed */
  
          /* console.log('the ccc.str.tw.fi[] is not empty!'); */

            for (var itemindex in data) {
              if (typeof itemindex !== 'undefined' && itemindex !== null) {
                
                 var item = data[itemindex];
                 var di =  new Date(item.created_at);
                 var fd =  new Date(ccc.str.tw.fd[0]);
   
                if (di.getTime() > fd.getTime() && item.id_str !== JSON.parse(sessionStorage['_cc.fi.'+ itemindex]).id_str) {          
           
                    ccc.str.tw.fn.unshift(item);
                    /* Session.set('tw', tw); * moved to global namespace */
                    ccsession.set('tw',ccc.str.tw);
           
                    /* console.log('1 new tweet checking from: ' + item.user.name + ' | ' + di.getTime() + ' is > than ' + fd.getTime()); */
                    ccc.str.tw.mi[0] = ccc.str.tw.mi[0] + 1;

                    read_msg_obj();

                } else {
                  /* console.log(di.getTime() + ' is not > than ' + fd.getTime()); */
                } 
              }
            }

            item = null; di = null; fd = null;

            if (ccc.str.tw.fn.length > 0) {
              for (var i = 0; i < (JSON.stringify(Object.keys(sessionStorage)).split(',').length); i++) {
                   ccc.str.tw.fi.push( JSON.parse(sessionStorage.getItem('_cc.fi.' + i, JSON.stringify( ccc.str.tw.fi[i]) )  ));
              }
              sessionStorage.clear();
              for (var i = 0; i < ccc.str.tw.fn.length; i++) {
                if (ccc.str.tw.fn[i].id_str !== ccc.str.tw.fi[ccc.str.tw.fi.length-1].id_str) {
                    /* console.log('fn id str ->' + ccc.str.tw.fn[i].id_str + '!==' + ccc.str.tw.fi[ccc.str.tw.fi.length-1].id_str); */
                    ccc.str.tw.fi.push(ccc.str.tw.fn[i]);
                    /* Session.set('tw', tw); * moved to global namespace */
                    ccsession.set('tw',ccc.str.tw);
                }          
                /* console.log('added to main _cc.fi. -> ' + ccc.str.tw.fn[i]); */
              }
              for (var i = 0; i <  ccc.str.tw.fi.length; i++) {
                try {
                     sessionStorage.setItem('_cc.fi.' + i, JSON.stringify( ccc.str.tw.fi[i] ));
                } catch (e) { trash_store('ss'); }
              }

              i = null;

              ccc.str.tw.ls[0] = JSON.stringify(sessionStorage).length;
              ccc.str.tw.bd[0] = JSON.parse(sessionStorage[ '_cc.fi.0' ]).created_at;
              ccc.str.tw.fd[0] = JSON.parse(sessionStorage[ '_cc.fi.'+ (JSON.stringify(Object.keys(sessionStorage)).split(',').length-1)]).created_at;

              /* console.log('check this --> ' + JSON.parse( sessionStorage['_cc.fi.0'] ).created_at ); */
              trash_store('ss'); /* ccc.str.tw.fi.length here to monitor tweet store level and report back to client */

            }
            ccc.str.tw.st[0] = ( Number(JSON.stringify(Object.keys(sessionStorage)).split(',').length ) - 1);
          }

          ccc.str.tw.fn = [];
          ccc.str.tw.fi = [];

    break;

    case (data.length === 0):
          /* console.log('1581 nothing here but us chickens ... no tweets found!'); */
          if (ccc.str.tw.tm[24] !== 1) { ccc.str.tw.tm[24] = 2; }
          get_cc_signin('notweet');
          msgnil('inzero');
    break;
  }
}


function get_fi_length() {

  /* console.log('... fi length -> '+ (Number(ccc.str.tw.fi.length))); */

}


function readin_bk_srtwobj(data) {
    
  /* scrolling back via twitter API,limited by;

   - API twitter limit managed by ccwatch.js
   - server limit set by user account on crowdcc server
   - browser limit of sessionStorage managed by function croptwstore()   

   */

  /* browser limit check */

  /* alert('sessionStorage length : ' + sessionStorage.length);
     console.log('sessionStorage length : ' + sessionStorage.length); */

  /* ok so the fi is not empty, so need to cycle through the create_date of the new incoming feed */
  
    /* console.log('process bk obj'); */

    ccc.str.tw.bd[0] = JSON.parse(sessionStorage[ '_cc.fi.0' ]).created_at;

    var cont = 0;

    for (var itemindex in data) {
           var item = data[itemindex];
           var di =  new Date(item.created_at);
           var bd =  new Date(ccc.str.tw.bd[0]);
 
           if (di.getTime() < bd.getTime() && item.id_str !== JSON.parse(sessionStorage[ '_cc.fi.0']).id_str) {
        
               ccc.str.tw.bn.push(item);

               /* Session.set('tw', tw); * moved to global namespace */
               ccsession.set('tw',ccc.str.tw);
               /* console.log('1 new tweet checking from: '+ (sessionStorage.key('_cc.fi.').split('.')[2]) + ' | ' + item.user.name + ' | ' + di.getTime() + ' is < than ' + bd.getTime()); */
           } else {
               cont = cont + 1;
               /* console.log(di.getTime() + ' is not > than ' + bd.getTime() + ' count ->' + cont); */
           }

    }

    if (ccc.str.tw.bn.length > 0) {
        for (var i = 0; i < (JSON.stringify(Object.keys(sessionStorage)).split(',').length); i++) {
             ccc.str.tw.fi.push( JSON.parse(sessionStorage.getItem('_cc.fi.' + i, JSON.stringify( ccc.str.tw.fi[i]) )  ));
        }
        sessionStorage.clear();
        for (var i = 0; i < ccc.str.tw.bn.length; i++) {
             if (ccc.str.tw.bn[i].id_str !== ccc.str.tw.fi[ccc.str.tw.fi.length-1].id_str) {
                 /* console.log('fn id str ->' + ccc.str.tw.bn[i].id_str + '!==' + ccc.str.tw.fi[ccc.str.tw.fi.length-1].id_str); */
           
                 ccc.str.tw.fi.unshift(ccc.str.tw.bn[i]);
                 /* Session.set('tw', tw); * moved to global namespace */
                 ccsession.set('tw',ccc.str.tw);
             }          
             /* console.log('added to main _cc.fi. -> ' + ccc.str.tw.bn[i]); */
        }
       
        for (var i = 0; i <  ccc.str.tw.fi.length; i++) {
          try {
             sessionStorage.setItem('_cc.fi.' + i, JSON.stringify( ccc.str.tw.fi[i] ));
           } catch (e) { trash_store('ss'); }   
        }

        ccc.str.tw.ls[0] = JSON.stringify(sessionStorage).length;    
        ccc.str.tw.bd[0] = JSON.parse(sessionStorage[ '_cc.fi.0' ]).created_at;
        ccc.str.tw.fd[0] = JSON.parse(sessionStorage[ '_cc.fi.'+ (JSON.stringify(Object.keys(sessionStorage)).split(',').length-1)]).created_at;
       
        /* console.log('check this --> ' + JSON.parse( sessionStorage['_cc.fi.0'] ).created_at ); */
        trash_store('ss'); /* ccc.str.tw.bn.length here to monitor tweet store level and report back to client */
        /*  trash_store moved ( line: 3625 ) in order to return false and stop trip to server if tw_trash_min limits reached  */
    }
    ccc.str.tw.st[0] = ( Number(JSON.stringify(Object.keys(sessionStorage)).split(',').length ) - 1); /* sessionStorage total update */
    
  ccc.str.tw.bn = [];
  ccc.str.tw.fi = [];

  i, itemindex, item, di, bd = null;
  
}

function trash_store(from) {

 /* 

 trash store : local store limit reached for sessionStorage and array cc store, older content removed, store(s) updated. 
 from : ss sessionStorage or cc copy store

 approx 5k characters allowed in all browsers that support sessionStorage, trash_store() will remove older content in
 order to continue procesing newer content based on custom limits ... 

 */

 /* set trash min === 300 */
 
  var tw_trash_min = 300;
  var cc_trash_min = 100;
 
  /* set sessionStorage total */
  /* console.log('sessionStorage total : ' + sessionStorage.length); */
  
  /* content added */
  /* console.log('content forward  added : ' + ccc.str.tw.fn.length); */
  /* console.log('content backward added : ' + ccc.str.tw.bn.length); */
  /* console.log('content fi : ' + ccc.str.tw.fi.length); */

  switch (from) {

    case ('ss'):

          switch (true) {
    
            case (sessionStorage.length > tw_trash_min):

              /*
               * optional display message, min has been reached
               * console.log('sessionStorage : '+ sessionStorage.length + ' > tw_trash_min : ' + tw_trash_min);
               * set_css_class('sdn_more', 'display', 'block');
               * set_css_class('sdn_wait', 'display', 'none');
               */

               /* removed 23rd Sept as limit displayed anyway with freq tweets in timeline (not always older tweets) */
               /* message_('message_close', 'cut'); */
               /* msgtmr_('close'); */

               var adj = 0;

               /* maintain store equilibrium, when met, if 10 tweets added, minus ten tweets from the oldest tweet(s) */
               /* console.log('trash min reached :' + tw_trash_min); */

               /* move object store to array for manipulation */
               /* console.log(JSON.stringify(Object.keys(sessionStorage)).split(',').length); */

               for (var i = 0; i < (JSON.stringify(Object.keys(sessionStorage)).split(',').length); i++) {
                    ccc.str.tw.fi.push( JSON.parse(sessionStorage.getItem('_cc.fi.' + i, JSON.stringify( ccc.str.tw.fi[i]) )  ));
               }

               /* trash selected array objects */
               /* test for sessionStorage max set to 500 (absolute max === 1.3K ) */

               if ( sessionStorage.length > 500) {
                    adj = 50;
               }

               for (var i = 0; i < (ccc.str.tw.fi.length + adj) ; i++) {

                 if (ccc.str.tw.fi.length > tw_trash_min) {
                     ccc.str.tw.fi.splice(0, i);
                             
                  /* switch (true) { */
                  /*  case (ccc.str.tw.fn.length !== 0): */
                  /*        if (i < (ccc.str.tw.fn.length + adj) ) { ccc.str.tw.fi.splice(0, i);} */
                  /*  break;         */
                  /*  case (ccc.str.tw.bn.length !== 0): */
                  /*        if (i < (ccc.str.tw.bn.length + adj) ) { ccc.str.tw.fi.splice(0, i);} */
                  /*  break;         */
                  /* }               */

                  }
                  /* console.log('remove from array : _cc.fi.'+i); */
                }

                 /* re-sort array object currently not required */
                 /* ccc.str.tw.fi.sort(date_sort_desc_obj); */

                 /* Session.set('tw', tw); * moved to global namespace */
                  ccsession.set('tw',ccc.str.tw);

                  /* backwards */
                  for (var i = sessionStorage.length; i >= 0; i-- ) {
                       var key = sessionStorage.key('_cc.fi.' + i);
                  /* if(/foo/.test(key)) { */
                       sessionStorage.removeItem(key);
                  /* }         */
                  }

                  sessionStorage.clear();

                  /* move array back to store */

                  for (var i = 0; i <  ccc.str.tw.fi.length; i++) {
                    try {
                      sessionStorage.setItem('_cc.fi.' + i, JSON.stringify( ccc.str.tw.fi[i] ));
                      } catch(e) {
                      /* console.log('trash * sessionStorage limit reached'); */
                      set_css_class('sdn_more', 'display', 'block');
                      set_css_class('sdn_wait', 'display', 'none');
                      /* display error messsage */
                    }
                  }

                  ccc.str.tw.ls[0] = JSON.stringify(sessionStorage).length;    
                  ccc.str.tw.bd[0] = JSON.parse(sessionStorage[ '_cc.fi.0' ]).created_at;
                  ccc.str.tw.fd[0] = JSON.parse(sessionStorage[ '_cc.fi.'+ (JSON.stringify(Object.keys(sessionStorage)).split(',').length-1)]).created_at;

                  ccc.str.tw.st[0] = ( Number(JSON.stringify(Object.keys(sessionStorage)).split(',').length ) - 1); /* sessionStorage total update */

                  ccc.str.tw.fi = [];
                  readtwin_obj(20);
                  /* reset_watch(); */
               
            break;

            /* removed 23rd Sept as limit displayed anyway with freq tweets in timeline (not always older tweets)
            case (sessionStorage.length > (tw_trash_min - 20)):
                  // warning message to indicate, near store limit
                  console.log('sessionStorage : '+ sessionStorage.length + ' (tw_trash_min - 20) : ' + (tw_trash_min - 20) );
                  message_('message_close', 'nar');
                  msgtmr_('close');
            break;
            */

            case (sessionStorage.length < tw_trash_min):
                  /* running within sessionStorage limits  */
                  /* console.log('sessionStorage : '+ sessionStorage.length + ' < tw_trash_min : ' + tw_trash_min); */
            break;
     
          }
    break;
  
    case ('cc'):

        switch (true) {

          case ( ccc.str.tw.cc.length > cc_trash_min  ):
                 /* console.log('ccc.str.tw.cc : '+ ccc.str.tw.cc.length + ' > tw_trash_min : ' + tw_trash_min); */
                 set_css_class('sdn_more', 'display', 'block');
                 set_css_class('sdn_wait', 'display', 'none');
                 message_('message_close', 'lie');
                 return false;
          break;

          case ( ccc.str.tw.cc.length > (cc_trash_min - 20) ):
                 /* warning message to indicate, near store limit */
                 /* console.log('ccc.str.tw.cc : '+ ccc.str.tw.cc.length + ' (tw_trash_min - 60) : ' + (tw_trash_min - 20) ); */
                 message_('message_close', 'nar');
                 msgtmr_('close');
                 return true;
          break;

          case ( ccc.str.tw.cc.length < cc_trash_min  ):
                 /* console.log('ccc.str.tw.cc : '+ ccc.str.tw.cc.length + ' < tw_trash_min : ' + tw_trash_min); */
                 return true;
          break;
   
        }

    break;

  }

  tw_trash_min = null; cc_trash_min = null;
  
}


function trashcanvas(canvasid, width, height) {
      /* trashcanvas('img-place', '50', '50') */         
  var canvas = document.getElementById(canvasid);
      canvas.width = width;
      canvas.height = height;
  var ctx = canvas.getContext("2d");
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      canvas = null; ctx = null;
}


function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();

  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  var shortdate = time_value.substr(4,2) + " " + time_value.substr(0,3);
  delta = delta + (relative_to.getTimezoneOffset() * 60);
      
  if (delta < 60) {
        return 'now';
      } else if(delta < 120) {
        return '1m';
      } else if(delta < (60*60)) {
        return (parseInt(delta / 60)).toString() + 'm';
      } else if(delta < (120*60)) {
        return '1h';
      } else if(delta < (24*60*60)) {
        return (parseInt(delta / 3600)).toString() + 'h';
      } else if(delta < (48*60*60)) {
        //return '1 day';
        return shortdate;
      } else {
        return shortdate;
  }

  values = null; parsed_date = null; relative_to = null; delta = null; shortdate = null; 
    
}


function compare_time(time_value) {
  /* compare given date to today */
  /* console.log('start : compare : ' + id + ' to today'); */
  var one_day=1000*60*60*24;
  /* create date from input value */
  var id = new Date(time_value);
  var td = new Date();
  var diff_ms = td.getTime() - id.getTime();
  /* convert back to days and return */
  /* console.log( Math.round(diff_ms/one_day) ); */

  if ( Math.round(diff_ms/one_day) < 91 ) {
       /* enable share icon if less than 3 months ( 91 days ) */   
       /* enable share icon if less than 2 months ( 61 days ) */    
       /* console.log('share icon enabled!'); */
       return true;
     } else {
       /* console.log('share icon disabled!'); */
       return false;
  }

  one_day = null; id = null; td = null; diff_ms = null;

}


function load_cc_local() {

  /*  overload page depth from the server.  */

  /*

  ccc.str.tw.cp[0] === local store number of items
  ccc.str.tw.cg[0] === local store page depth

  */

  /* console.log('you are in load local requesting content locally!'); */

  more_bounce('sdn_cc');

  /* read_ccin_obj( ccc.str.tw.cg[0] + ccc.str.tw.cp[0] ); */
  read_ccin_obj( ccc.str.tw.cg[0] + ccc.str.tw.cp[0] , 'cclimit');

  /* console.log('page depth + page req = total records requested from store: ' + (ccc.str.tw.cg[0] + ccc.str.tw.cp[0]) ); */
  /* console.log('page depth :' + ccc.str.tw.cp[0]); */
  /* console.log('page req :' +  ccc.str.tw.cg[0]); */
  /* console.log('ccc.str.tw.cc.length :' + ccc.str.tw.cc.length); */

}


function load_cc_more() {

  /* console.log('going to try and get some ...'); */
  /* console.log('ccc.str.tw.ct[0] : ' + ccc.str.tw.ct[0]); */
  /* console.log('ccc.str.tw.cc.length : ' + ccc.str.tw.cc.length); */
  
  /* if (ccc.str.tw.ct[0] !== ccc.str.tw.cc.length) { more_bounce('sdn_cc'); } */

  more_bounce('sdn_cc');
  get_in_ccobj(ccc.str.tw.usr.screen_name, ccc.str.tw.cc.length, ccc.str.tw.cg[0] + ccc.str.tw.cp[0]);

  /* get_in_ccobj(ccc.str.tw.usr.screen_name, ccc.str.tw.cc.length, ccc.str.tw.usr.cclimit); */

}


function readin_fw_srccobj(data, depth) {

/* 
          
  1st time read from server, (local sorting) sort in order the set page depth of carboned content 
         
  2nd time and subsequent reads are when the user has copied tweet(s) to the server db,
      however this will result in many re-reads of server content in order to get the most current
      carbon copy store state (1st solution)
        
  2nd time, better 2nd solution is to ensure the tweet(s) are carboned to the server, but instead of
      performing a new total re-read of records, instead, the local carbon store ccc.str.tw.cc[] is updated
      locally to reflect the new server content, this saves on server load ...

  3rd time, paging back through the content will require server reads updating ccc.str.tw.cc[].

*/
    
  /* console.log('readin_fw_srccobj data: ' + data); */

  switch (true) {

    case (data !== ''):

          /* console.log('readin_fw_srccobj :: process fw cc obj'); */
          /* console.log(ccc.str.tw.cc.length); */

          var date_sort_desc_obj = function (a, b) {
          /* default :: comparison function that will result in dates being sorted in DESCENDING order. */
          return new Date(b.created_at) - new Date(a.created_at)
          };

          if (ccc.str.tw.cc.length === 0) {

              for (var i = 0; i < data.length; i++) {
                   ccc.str.tw.cc.push( data[i] );
                   /* Session.set('tw', tw); * moved to global namespace */
                   ccsession.set('tw',ccc.str.tw);
              }

              ccc.str.tw.cp[0] = 0;       // declare session 0
              /* console.log('readin_fw_srccobj :: session first time'); */
       
              /* default :: sort dates in descending order and output the results. */
              data.sort(date_sort_desc_obj);
              date_sort_desc_obj = null;
    
              /* console.log('readin_fw_srccobj :: ccc.str.tw.cc[] === empty'); */

          } else {

              /* default :: sort dates in descending order and output the results. */
              /* console.log('readin_fw_srccobj :: ccc.str.tw.cc[] !== empty'); */
              date_sort_desc_obj = null;
          }

    break;

    case (data === ''):
          /* console.log('readin_fw_srccobj :: nothing to do !'); */
    break;
  
  }
}

function readin_cw_srccobj(data) {

  /* depth not required, one fresh page public view */

  switch (true) {

    case (data !== ''):

          /* console.log('readin_cw_srccobj:: process cw cc obj'); */
          /* console.log(ccc.str.tw.cw.length); */

          var date_sort_desc_obj = function (a, b) {
          /* default :: comparison function that will result in dates being sorted in DESCENDING order. */
          return new Date(b.created_at) - new Date(a.created_at)
          };

          if (ccc.str.tw.cw.length === 0) {

              for (var i = 0; i < data.length; i++) {
                   ccc.str.tw.cw.push( data[i] );
                   /* Session.set('tw', tw); * moved to global namespace */
                   ccsession.set('tw',ccc.str.tw);
              }
  
              /* console.log('readin_cw_srccobj :: first time'); */
       
              /* default :: sort dates in descending order and output the results. */
              data.sort(date_sort_desc_obj);
              date_sort_desc_obj = null;
              /* console.log('readin_cw_srccobj :: ccc.str.tw.cw[] === empty'); */

          } else {

              /* default :: sort dates in descending order and output the results. */
              /* console.log('readin_cw_srccobj :: ccc.str.tw.cw[] !== empty'); */
              date_sort_desc_obj = null;
          }

    break;

    case (data === ''):
          /* console.log('readin_cw_srccobj :: nothing to do!'); */
    break;

  }

  return ccc.str.tw.cw.length;

}


function update_space(msg) {

  var _ups = {'ups':'', 'lim':'' };
      _ups['lim'] = ccc.str.tw.usr.cclimit;
      _ups['ups'] = get_html_id('acc-spa-tag-num');

  /* console.log(_ups['ups']); console.log( _ups['lim']); */
 
  switch (msg) {

     case (1): /* added record */
             if ( _ups['ups'] > 0) {
                  _ups['ups'] = +_ups['ups'] -1;
             
             /* } else {
                  console.log('cannot store any more tweets in this version, please upgrade!'); */
             }
     break;

     case (0): /* minus record */
             if ( _ups['ups'] !== _ups['lim']) {
                  _ups['ups'] = +_ups['ups'] +1;
             }
     break;

  }
  
  set_html_id('acc-spa-tag-num', _ups['ups']);

  /* ccc.str.tw.usr.ccspace = 100 - 48 (equals 52) , may need to check if 100 - 101 ulp! */
  ccc.str.tw.usr.ccspace = (_ups['lim'] - _ups['ups']);

  _ups['lim'] = null; _ups['ups'] = null; _ups = null;

 }


 function update_fw_calobj(msg) {

  /* console.log('update_fw_calobj :: process fw cc obj'); */
  /* console.log(ccc.str.tw.cc.length); */

  var date_sort_desc_obj = function (a, b) {
      /* default :: comparison function that will result in dates being sorted in DESCENDING order. */
      return new Date(b.created_at) - new Date(a.created_at)
  };

   switch (msg) {

     case('update'):

        if (ccc.str.tw.cb.length !== 0) {
       
            /* default :: sort dates in descending order and output the results. */
        
            if( ccc.str.tw.cc.push.apply(ccc.str.tw.cc, ccc.str.tw.cb) ) {
                ccc.str.tw.cb = [];
            }

            ccc.str.tw.cc.sort(date_sort_desc_obj);

            /* read_ccin_obj(ccc.str.tw.cg[0]); was default page depth (10) * changed to ccc.str.tw.cp[0] * crowd carbon back page scroll count */
            
            /* read_ccin_obj(ccc.str.tw.cp[0]); */

            read_ccin_obj(ccc.str.tw.cp[0], 'cclength');
            
            date_sort_desc_obj = null; 

            /* console.log('update_fw_calobj :: update!'); */

        } else {

          /* default :: sort dates in descending order and output the results. */

          /* console.log('update_fw_calobj :: ccc.str.tw.cb[] === empty'); */
        }

     break;

     case ('trash'):

       if (ccc.str.tw.cc.length !== 0) {

           ccc.str.tw.cc.splice(ccc.str.istw['trshcc'], 1);

           ccc.str.tw.cc.sort(date_sort_desc_obj);

           /* read_ccin_obj(ccc.str.tw.cg[0]); was default page depth (10) * changed to ccc.str.tw.cp[0] * crowd carbon back page scroll count */
           
           /* read_ccin_obj(ccc.str.tw.cp[0]); */

           read_ccin_obj(ccc.str.tw.cp[0], 'cclength');

           date_sort_desc_obj = null; 

           /* console.log('update_fw_calobj :: trash!'); */

       } else {

           /* console.log('update_fw_calobj :: ccc.str.tw.cc[] === empty'); */

       }

     break; 

   }

 }


 function readin_bk_srccobj(data, depth) {

   /* console.log('readin_bk_srccobj :: process bk cc obj'); */
   /* console.log(ccc.str.tw.cb.length); */

   if (data.length !== 0) {

    var date_sort_desc_obj = function (a, b) {
        /* default :: comparison function that will result in dates being sorted in DESCENDING order. */
        return new Date(b.created_at) - new Date(a.created_at)
    };

    if (ccc.str.tw.cb.length === 0) {

        for (var i = 0; i < data.length; i++) {
            ccc.str.tw.cb.push( data[i] );
            /* Session.set('tw', tw); * moved to global namespace */
            ccsession.set('tw',ccc.str.tw);
        }

        ccc.str.tw.cp[0] = 0;       // declare session 0
        /* console.log('readin_bk_srccobj :: first time'); */
       
        /* default :: sort dates in descending order and output the results. */
        data.sort(date_sort_desc_obj);
      
        if( ccc.str.tw.cc.push.apply(ccc.str.tw.cc, ccc.str.tw.cb) ) {
            ccc.str.tw.cb = [];
        }

        /* read_ccin_obj(ccc.str.tw.cc.length); */
        read_ccin_obj(ccc.str.tw.cc.length, 'cclength');
        date_sort_desc_obj = null; 
      
     } else {

       /* default :: sort dates in descending order and output the results. */

       /* console.log('readin_bk_srccobj :: ccc.str.tw.cb[] !== empty'); */
       date_sort_desc_obj = null;

     }

   } else {

      /* console.log('readin_bk_srccobj :: ccc.str.tw.cb[] === empty'); */
      message_('message_close', 'nae');
      msgtmr_('close');
      date_sort_desc_obj = null;
   }

 }


 function imgerr(img) {
      /* directory to root * window.location.pathname */
      /* img.src = window.location.pathname  + '/img/default_profile_4_normal.png'; */
      img.src = (window.location.pathname .replace(/\/?$/, '/')) + 'img/default_profile_4_normal.png';
      img.title = "profile image changed!";
      img.onerror = null;
      return true;

 }


 function processtwlinks(text) {

      // var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
      var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi; // greedy flag added for two links in a row
      text = text.replace(exp, "<a href='$1' target='_blank'>$1</a>");
      exp = /(^|\s)#(\w+)/g;
      text = text.replace(exp, "$1<a href='http://search.twitter.com/search?q=%23$2' target='_blank'>#$2</a>");
      exp = /(^|\s)@(\w+)/g;
      text = text.replace(exp, "$1<a href='http://www.twitter.com/$2' target='_blank'>@$2</a>");
      text = text.replace(/(\r\n|\n|\r)/gm, " ").replace(/(\\')/g, "'").replace(/(\\")/g, "\"");
      return text;

      exp = null;
      
 }


 function read_cwin_obj(readto) {
  /* cw * same (or duplicate) records, display most frequent tweets with crowdcc  */
  /* console.log('read_cwin_obj :: read cw one page same (or duplicate) records public view  ... start!'); */

  var i = 0; var readup = 0; var feedHTML = '';

   if (ccc.str.tw.cw.length > 0) {

      for (var i = 0; i <  ccc.str.tw.cw.length; i++) {

       if (readup === readto ) {
           break;
       } 

       /* console.log('read_cwin_obj :: ' + ccc.str.tw.cc[i].created_at); */

       if (typeof ccc.str.tw.cw[i].retweeted_status === 'undefined') {

        ccc.str.istw['twtextcw'] = processtwlinks(ccc.str.tw.cw[i].text);
          
        /* add media url link used within tweets * format for media * legacy ccc.str.tw.cw[i].entities.media.media_url * update ccc.str.tw.cw[i].entities.media[0].media_url * [0][1][2][3] */
        /* tests for legacy or update entities media * ccc.str.tw.cw[i].entities.hasOwnProperty('media') * or * ('media' in ccc.str.tw.cw[i].entities) */
        /* console.log('JSON parse IN test -> entities media : _cw.fi.'+ i +':' + ('media' in ccc.str.tw.cw[i].entities );
        console.log('JSON parse hasOwn test -> entities media : _cw.fi.'+ i +':' + ccc.str.tw.cw[i].entities.hasOwnProperty('media') ); */
        
        switch (true) {
          case ( '0' in ccc.str.tw.cw[i].entities.media ):                 /* key in test * entities media */ 
                 if (ccc.str.tw.cw[i].entities.media[0].media_url !== 'false') { 
                     ccc.str.istw['twtextcw'] = '<a href="'+ ccc.str.tw.cw[i].entities.media[0].url+'" target="_blank"><img src="'+ ccc.str.tw.cw[i].entities.media[0].media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextcw'];
                 }
          break;

          case ( 'media' in ccc.str.tw.cw[i].entities ):                    /* key in test * entities media * or * case ( ccc.str.tw.cc[i].entities.hasOwnProperty('media') ): */
                 if (ccc.str.tw.cw[i].entities.media.media_url !== 'false') {
                     ccc.str.istw['twtextcw'] = '<a href="'+ ccc.str.tw.cw[i].entities.media.url+'" target="_blank"><img src="'+ ccc.str.tw.cw[i].entities.media.media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextcw'];
                 }
          break;
        }

        feedHTML += '<div class="twcw'+i+' twitter-article" >';

        feedHTML += '<div class="twitter-pic" contenteditable="false"><a href="https://twitter.com/'+ccc.str.tw.cw[i].user.screen_name+'" target="_blank"><img src="'+ccc.str.tw.cw[i].user.profile_image_url+'" width="42" height="42" alt="twitter icon" onerror="imgerr(this)" id="profile_cc" /></a>';
        feedHTML += '<div class="share-items"><div class="context"><span class="badge-retweeted"></span></div>';
        feedHTML += '<ul class="twitter-actions '+ccc.str.tw.cw[i].id_str+'_ion">';

        feedHTML += '<li class="share">';
        feedHTML += '<a class="with-icn js-action-share" data-modal="tw.cw.'+i+'">';
    
        feedHTML += '<i class="sm-share"></i>';
        feedHTML += '<span class="tooltip" alt="share">';
        feedHTML += '<span class="share '+ccc.str.tw.cw[i].id_str+'_sha icon-share" data-modal="tw.cw.'+i+'" aria-hidden="true"></span></span></a></li>';
 
        feedHTML += '<li class="top_cc">';
        feedHTML += '<a class="with-icn js-action-share" data-modal="tw.cw.'+i+'">';
     
        feedHTML += '<i class="sm-share"></i>';
        feedHTML += '<span class="tooltip" alt="'+ccc.str.tw.cw[i].count+' cc">';
        feedHTML += '<span class="top_cc '+ccc.str.tw.cw[i].id_str+'_sha icon-circle-sml" data-modal="tw.cw.'+i+'" aria-hidden="true"></span></span></a></li>';
  
        feedHTML += '</ul></div></div>';

        feedHTML += '<div id="'+ccc.str.tw.cw[i].id_str+'_txt" class="twitter-text">';
        feedHTML += '<span class="tweetprofilelink" spellcheck="false"><strong>';

        feedHTML += '<a class="urlprofilelink" target="_blank" title="'+ccc.str.tw.cw[i].user.screen_name+'" href="https://twitter.com/'+ccc.str.tw.cw[i].user.screen_name+'">'+ccc.str.tw.cw[i].user.screen_name+'</a></strong></span>';
        feedHTML += '<span class="tweet-time"><a target="_blank" href="https://twitter.com/'+ccc.str.tw.cw[i].user.screen_name+'/status/'+ccc.str.tw.cw[i].id_str+'" title="'+ccc.str.tw.cw[i].created_at+'">'+relative_time(ccc.str.tw.cw[i].created_at)+'</a></span>';
        // feedHTML += '<div id="js-srctxtcc" class="'+ccc.str.tw.cw[i].id_str+'_src">'+ccc.str.istw['twtextcc']+'</div>';
        feedHTML += '<div class="twitter-txt">'+ccc.str.istw['twtextcc']+'</div>';
        feedHTML += '</div></div>';
       
       } else {
             /* console.log('read_cwin_obj :: retweet yes!'); */
       }

       readup  =  readup + 1;

     }
    
     set_html_id('cw', '<div class="twitter-content">'+feedHTML+'</div>');

     feedHTML = null;

     /* console.log('read_cwin_obj :: readto : ' + readto); */
     /* console.log('read_cwin_obj :: readup : ' + readup); */

     ccc.str.istw['twtextcw'] = '';

     /* console.log('read_cwin_obj :: cw one page same (or duplicate) records public view  ... finish!'); */

   }

   i = null, readup = null;

 } 


 function read_ccin_obj(readto, lengthy) {

    /* console.log('read_ccin_obj :: read cctweetin ... start!'); */

    /* console.log('read_ccin_obj :: ' + visible_css_id('cc') ); console.log('read_ccin_obj :: ' + visible_css_id('in') ); */

    var i = 0; var readup = 0; var feedHTML = ''; var readun = ''; /* lengthy === 'cclimit' * lengthy === 'cclength' */

     /* when building ref cclimit * cclimit  * when trashing * for example * read cclength */

    if (lengthy === 'cclength') { readun  =  ccc.str.tw.cc.length; /* console.log( 'read_ccin_obj :: readun = ccc.str.tw.cc.length;'); */ } else { readun  =  ccc.str.tw.usr.cclimit; /* console.log( 'read_ccin_obj :: readun = ccc.str.tw.usr.cclimit;'); */ }  

    /* console.log('read_ccin_obj :: ccc.str.tw.cc.length :' + ccc.str.tw.cc.length + ' ccc.str.tw.usr.cclimit :' + ccc.str.tw.usr.cclimit); */

    /* console.log('read_ccin_obj :: readun === : ' + readun ); */

    if (ccc.str.tw.cc.length > 0) {

      /* var feedHTML = ''; */
      /* for (var i = 0; i <  ccc.str.tw.usr.cclimit; i++) { // when building   */
      /* for (var i = 0; i <  ccc.str.tw.cc.length; i++) {   // when deleteing  */

      for (var i = 0; i < readun; i++) {

       if (readup == readto ) {
           break;
       } 

       /* 
       console.log('read_ccin_obj :: ' + ccc.str.tw.cc[i].created_at);
       console.log('read_ccin_obj :: ' + ccc.str.tw.cc[i].retweeted_status : ' + ccc.str.tw.cc[i].retweeted_status );
       */ 
      
       if (typeof ccc.str.tw.cc[i].retweeted_status === 'undefined' ) {
       /* if ( ! ccc.str.tw.cc[i].hasOwnProperty('retweeted_status') ) { */
      
        ccc.str.istw['twtextcc'] = processtwlinks(ccc.str.tw.cc[i].text);

        /* add media url link used within tweets * format for media * legacy ccc.str.tw.cc[i].entities.media.media_url * update ccc.str.tw.cc[i].entities.media[0].media_url * [0][1][2][3] */
        /* tests for legacy or update entities media * ccc.str.tw.cc[i].entities.hasOwnProperty('media') * or * ('media' in ccc.str.tw.cc[i].entities) */
        
        /*
         console.log('read_ccin_obj :: JSON media parse IN test -> entities media : _cc.fi.'+ i +':' + ( 'media' in ccc.str.tw.cc[i].entities ) );
         console.log('read_ccin_obj :: JSON media[0] parse IN test -> entities media[0] : _cc.fi.'+ i +':' + ( '0' in ccc.str.tw.cc[i].entities.media ) );
        */
        
        switch (true) {
          case ( '0' in ccc.str.tw.cc[i].entities.media ):                 /* key in test * entities media */ 
                 if (ccc.str.tw.cc[i].entities.media[0].media_url !== 'false') { 
                     ccc.str.istw['twtextcc'] = '<a href="'+ ccc.str.tw.cc[i].entities.media[0].url+'" target="_blank"><img src="'+ ccc.str.tw.cc[i].entities.media[0].media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextcc'];
                 }
          break;

          case ( 'media' in ccc.str.tw.cc[i].entities ):                    /* key in test * entities media * or * case ( ccc.str.tw.cc[i].entities.hasOwnProperty('media') ): */
                 if (ccc.str.tw.cc[i].entities.media.media_url !== 'false') {
                     ccc.str.istw['twtextcc'] = '<a href="'+ ccc.str.tw.cc[i].entities.media.url+'" target="_blank"><img src="'+ ccc.str.tw.cc[i].entities.media.media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextcc'];
                 }
          break;
        }
      
        feedHTML += '<div class="twcc'+i+' twitter-article" >';

        feedHTML += '<div class="twitter-pic" contenteditable="false"><a href="https://twitter.com/'+ccc.str.tw.cc[i].user.screen_name+'" target="_blank"><img src="'+ccc.str.tw.cc[i].user.profile_image_url+'" width="42" height="42" alt="twitter icon" onerror="imgerr(this)" id="profile_cc" /></a>';
        feedHTML += '<div class="share-items"><div class="context"><span class="badge-retweeted"></span></div>';
        feedHTML += '<ul class="twitter-actions '+ccc.str.tw.cc[i].id_str+'_ion">';

        feedHTML += '<li class="trash_cc">';  
        feedHTML += '<a class="with-icn js-action-pin" data-modal="tw.cc.'+i+'">';

        feedHTML += '<i class="sm-pin"></i>';
        feedHTML += '<span class="tooltip" alt="delete">'
        feedHTML += '<span class="delete_cc '+ccc.str.tw.cc[i].id_str+'_del icon-remove" data-modal="tw.cc.'+i+'" data-id="'+ccc.str.tw.cc[i].id_str+'" aria-hidden="true"></span></span></a></li>';
    
        /* API * deleted * recycled tweet id's after 2 months * age out the share option ( re-tweet * re-favor ) to prevent tweet id index err * delete tweet traffic */
        /* origin * feedHTML += '<span class="share '+ccc.str.tw.cc[i].id_str+'_sha icon-share" data-modal="tw.cc.'+i+'" aria-hidden="true"></span></span></a></li>'; */

        if ( compare_time(ccc.str.tw.cc[i].created_at) ) {
             /* compare time_in date string ms to today date string * if < less than 61 days (2 months) or 91 days (3 months) * enable share */

         feedHTML += '<li class="share">';
         feedHTML += '<a class="with-icn js-action-share" data-modal="tw.cc.'+i+'">';
 
         feedHTML += '<i class="sm-share"></i>';
         feedHTML += '<span class="tooltip" alt="share">';

         feedHTML += '<span class="share '+ccc.str.tw.cc[i].id_str+'_sha icon-share" data-modal="tw.cc.'+i+'" aria-hidden="true"></span></span></a></li>';

        } else {
             /* compare time_in date string ms to today date string * if < less than 61 days (2 months) or 91 days (3 months) * unable share */
         
         feedHTML += '<li class="share">';
         feedHTML += '<a class="with-icn js-action-share icon-share" data-modal="tw.cc.'+i+'">';
 
         feedHTML += '<i class="sm-share"></i>';
         feedHTML += '<span class="tooltip" alt="share">';

         feedHTML += '<span class="share '+ccc.str.tw.cc[i].id_str+'_sha" data-modal="tw.cc.'+i+'" aria-hidden="true"></span></span></a></li>';

        }

        feedHTML += '</ul></div></div>';

        feedHTML += '<div id="'+ccc.str.tw.cc[i].id_str+'_txt" class="twitter-text">';
        feedHTML += '<span class="tweetprofilelink" spellcheck="false"><strong>';

        feedHTML += '<a class="urlprofilelink" target="_blank" title="'+ccc.str.tw.cc[i].user.screen_name+'" href="https://twitter.com/'+ccc.str.tw.cc[i].user.screen_name+'">'+ccc.str.tw.cc[i].user.screen_name+'</a></strong></span>';
        feedHTML += '<span class="tweet-time"><a target="_blank" href="https://twitter.com/'+ccc.str.tw.cc[i].user.screen_name+'/status/'+ccc.str.tw.cc[i].id_str+'" title="'+ccc.str.tw.cc[i].created_at+'">'+relative_time(ccc.str.tw.cc[i].created_at)+'</a></span>';
        // feedHTML += '<div id="js-srctxtcc" class="'+ccc.str.tw.cc[i].id_str+'_src">'+ccc.str.istw['twtextcc']+'</div>';
        feedHTML += '<div class="twitter-txt">'+ccc.str.istw['twtextcc']+'</div>';
        feedHTML += '</div></div>';
       
       } else {
             /* console.log('read_ccin_obj :: retweet yes!'); */
       }

       readup  =  readup + 1;

     }

     if (ccc.str.tw.cp[0] < 15 && visible_css_id('sdn_cc')) {
         set_css_id('sdn_cc_more', 'display', 'block');  
     }
     
     set_html_id('cc', '<div class="twitter-content">'+feedHTML+'</div>');

     feedHTML = null;

     ccc.str.tw.cp[0] = readto;
     ccc.str.tw.ct[0] = readup;

     /* console.log('read_ccin_obj :: readto : ' + readto); */
     /* console.log('read_ccin_obj :: readup : ' + readup); */

     /* console.log('read_ccin_obj :: read cctweetin completed!'); */

    } else {
      /* trash cc * ! ccc.str.tw.cc.length > 0 */
      /* console.log('read_ccin_obj :: woaw there ... we are on the last tweet to delete!'); */
      set_html_id('cc', '');
    }

    i = null, readup = null; readun = null;
    
 }


 function readtwin_obj(readto) {

    ccc.str.tw.mi[0] = 0;

    /* console.log('readtwin_obj :: read tweetin ... start!'); */

    var feedHTML = '';
    var i = 0, readup = 0;

    if (sessionStorage.key('_cc.fi.')) {
    /* or * if ( sessionStorage.length !== 0 ) { */
 
    /* newest tweets first ... */
    
    for (var i = (JSON.stringify(Object.keys(sessionStorage)).split(',').length-1); i >=0; i--) {

     if (readup == readto ) {
         break;
     } 

     /* console.log(readtwin_obj :: JSON.parse(sessionStorage['_cc.fi.'+i]).created_at); */

           if (typeof JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status == 'undefined') {
                
               ccc.str.istw['twtextfi'] = processtwlinks(JSON.parse(sessionStorage['_cc.fi.'+i]).text);

                /* add media url link used within tweets * format for media * legacy ccc.str.tw.cc[i].entities.media.media_url * update ccc.str.tw.cc[i].entities.media[0].media_url * [0][1][2][3] */
                /* tests for legacy or update entities media * ccc.str.tw.cc[i].entities.hasOwnProperty('media') * or * ('media' in ccc.str.tw.cc[i].entities) */
                /* console.log('readtwin_obj :: JSON parse IN test -> entities media : _cc.fi.'+ i +':' + ('media' in JSON.parse(sessionStorage['_cc.fi.'+i]).entities ));
                console.log('readtwin_obj :: JSON parse hasOwn test -> entities media : _cc.fi.'+ i +':' + (JSON.parse(sessionStorage['_cc.fi.'+i]).entities.hasOwnProperty('media') )); */
                switch (true) {
                  case ( 'media' in JSON.parse(sessionStorage['_cc.fi.'+i]).entities ):                 /* key in test * entities media */
                  case ( 'media[0]' in JSON.parse(sessionStorage['_cc.fi.'+i]).entities ):              /* key in test * entities media */
                  /* case ('readtwin_obj :: ' + JSON.parse(sessionStorage['_cc.fi.'+i]).entities.hasOwnProperty('media') ): /* hasownproperty test * entities media */
                         
                  switch (true) {
                    case (typeof JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].media_url !== 'undefined' && JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].media_url !== 'false' ):
                          /* console.log('readtwin_obj :: new format * media[0]'); */
                          ccc.str.istw['twtextfi'] = '<a href="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].url+'" target="_blank"><img src="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextfi'];
                    break;
                    case (typeof JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.media_url !== 'undefined' && JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.media_url !== 'false'):
                          /* console.log('readtwin_obj :: legacy format * media'); */
                          ccc.str.istw['twtextfi'] = '<a href="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.url+'" target="_blank"><img src="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextfi'];
                    break;
                  }

                  break;
                }
                
                feedHTML += '<div class="_ccfi'+i+' twitter-article">';
       
                feedHTML += '<span class="_ccfi'+i+'">'; 

                feedHTML += '<div class="twitter-pic" contenteditable="false"><a href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'" target="_blank"><img src="'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.profile_image_url_https+'" width="42" height="42" alt="" /></a>';

                feedHTML += '<div class="share-items"><div class="context"><span class="badge-retweeted"></span></div><ul class="twitter-actions"><li class="ccc"><a class="with-icn js-action-pin" data-modal="_cc.fi.'+i+'"><i class="sm-pin"></i><span class="tooltip" alt="copy"><span aria-hidden="true" class="ccc icon-ccc_small" data-modal="_cc.fi.'+i+'"></span></span></a></li><li class="share"><a class="with-icn js-action-share" data-modal="_cc.fi.'+i+'"><i class="sm-share"></i><span class="tooltip" alt="share"><span aria-hidden="true" class="share icon-share" data-modal="_cc.fi.'+i+'"></span></span></a></li></ul></div>';
              
                feedHTML += '</div>';          

                feedHTML += '<div class="twitter-text"><span class="tweetprofilelink" spellcheck="false"><strong><a class="urlprofilelink" href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'" title="'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.name+'" target="_blank">'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'</a></strong></span><span class="tweet-time"><a title="'+JSON.parse(sessionStorage['_cc.fi.'+i]).created_at+'" href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'/status/'+JSON.parse(sessionStorage['_cc.fi.'+i]).id_str+'" target="_blank">'+relative_time(JSON.parse(sessionStorage['_cc.fi.'+i]).created_at)+'</a></span><div class="twitter-txt">'+ccc.str.istw['twtextfi']+'</div>';
                // <div id="js-srctxt">'+ccc.str.istw['twtextfi']+'</div>';
                
                feedHTML += '</div></span>';
               
                feedHTML += '</div>'; 

                }else{

                /* console.log('readtwin_obj :: retweet yes!'); */

                ccc.str.istw['twtextfi_rt'] = processtwlinks(JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.text);

                /* add media url link used within tweets */
                switch (true) {
                  case ( 'media' in JSON.parse(sessionStorage['_cc.fi.'+i]).entities ):                 /* key in test * entities media */
                  case ( 'media[0]' in JSON.parse(sessionStorage['_cc.fi.'+i]).entities ):              /* key in test * entities media */
                  /* case ( JSON.parse(sessionStorage['_cc.fi.'+i]).entities.hasOwnProperty('media') ): /* hasownproperty test * entities media */
              
                  switch (true) {
                    case (typeof JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].media_url !== 'undefined' && JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].media_url !== 'false' ):
                          /* console.log('new format * media[0]'); */
                          ccc.str.istw['twtextfi_rt'] = '<a href="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].url+'" target="_blank"><img src="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media[0].media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextfi_rt'];
                    break;
                    case (typeof JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.media_url !== 'undefined' && JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.media_url !== 'false'):
                          /* console.log('legacy format * media'); */
                          ccc.str.istw['twtextfi_rt'] = '<a href="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.url+'" target="_blank"><img src="'+ JSON.parse(sessionStorage['_cc.fi.'+i]).entities.media.media_url +'" height="50" width="50" style="float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" onerror="imgerr(this)" ></a>' + ccc.str.istw['twtextfi_rt'];
                    break;
                  }

                  break;
                }
                     
                feedHTML += '<div class="_ccfi'+i+' twitter-article">';

                feedHTML += '<span class="_ccfi'+i+'">'; 
                
                feedHTML += '<div class="rttwitter-pic" contenteditable="false"><a href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'" target="_blank"><img src="'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.profile_image_url+'"images/twitter-feed-icon.png" width="20" height="20" alt="twitter icon" /></a></div><div class="twitter-pic" contenteditable="false"><a href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.user.screen_name+'" target="_blank"><img src="'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.user.profile_image_url+'" width="42" height="42" alt="twitter icon" /></a>';

                feedHTML += '<div class="share-items"><div class="context"><span class="badge-retweeted"></span></div><ul class="twitter-actions"><li class="ccc"><a class="with-icn js-action-pin" data-modal="_cc.fi.'+i+'"><i class="sm-pin"></i><span class="tooltip" alt="copy"><span aria-hidden="true" class="ccc icon-ccc_small" data-modal="_cc.fi.'+i+'"></span></span></a></li><li class="share"><a class="with-icn js-action-share" data-modal="_cc.fi.'+i+'"><i class="sm-share"></i><span class="tooltip" alt="share"><span aria-hidden="true" class="share icon-share" data-modal="_cc.fi.'+i+'"></span></span></a></li></ul></div>';
            
                feedHTML += '</div>';
 
                feedHTML += '<div class="twitter-text"><span class="tweetprofilelink" spellcheck="false"><strong><a class="urlprofilelink" href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.user.screen_name+'" title="'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.user.name+'" target="_blank">'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.user.screen_name+'</a></strong></span><span class="rtarrow">&#8592;</span><span class="rtprofilelink" spellcheck="false"><a href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'" title="'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'" target="_blank">'+JSON.parse(sessionStorage['_cc.fi.'+i]).user.screen_name+'</a></span><span class="tweet-time"><a title="'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.created_at+'" href="https://twitter.com/'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.user.screen_name+'/status/'+JSON.parse(sessionStorage['_cc.fi.'+i]).retweeted_status.id_str+'" target="_blank">'+relative_time(JSON.parse(sessionStorage['_cc.fi.'+i]).created_at)+'</a></span><div class="twitter-txt">'+ccc.str.istw['twtextfi_rt']+'</div>';
                //<div id="js-srctxt">'+ccc.str.istw['twtextfi_rt']+'</div>';
                       
                feedHTML += '</div></span>';

                feedHTML += '</div>';
   
           }
      
      readup  =  readup + 1;

      }

      /* console.log('readtwin_obj :: more : ' +  ccc.str.tw.mi[0]); */

      if (ccc.str.tw.mi[0] === 0) {set_css_class('icon-ccc_large','color','#484848');set_doc_title('crowdcc');}

      set_html_id('in', '<div class="twitter-content">'+feedHTML+'</div>');

      feedHTML = null; 

      ccc.str.tw.sc[0] = readto;

      /* set sdn_more click icon status (scoll down to put sdn icon into view) */

      /* console.log('readtwin_obj :: check to see scroll value : ' + hascrollbar()); */

      if ( hascrollbar()) {
        
           set_css_id('sdn', 'display', 'none');
           set_css_class('sdn_more', 'display', 'block');
           set_css_class('sdn_wait', 'display', 'none');

      } else {

           set_css_id('sdn', 'display', 'block');
           set_css_class('sdn_more', 'display', 'block');
           set_css_class('sdn_wait', 'display', 'none');

      }   

      read_msg_obj(); 

      /* console.log('readtwin_obj :: read tweetin ... completed!'); */
  
      get_cc_signin();

    }

    i = null, readup = null;

 }

 function read_msg_obj() {

   switch (true) {
       
     case ( ccc.str.tw.mi[0] === 1 ):
            /* set_css_class('icon-ccc_large','color','#4488F6'); */
            set_css_class('icon-ccc_large','color','#55ACEE');
            set_doc_title('('+ccc.str.tw.mi[0]+') crowdcc');
            set_html_id('now', 'view ' +  ccc.str.tw.mi[0] + ' new tweet');
            message_('message_close', 'now');
            msgtmr_('close');
     break;
     case ( ccc.str.tw.mi[0] > 1 ):
            /* set_css_class('icon-ccc_large','color','#4488F6'); */
            set_css_class('icon-ccc_large','color','#55ACEE');
            set_doc_title('('+ccc.str.tw.mi[0]+') crowdcc');
            set_html_id('now', 'view ' +  ccc.str.tw.mi[0] + ' new tweets');
            message_('message_close', 'now');
            msgtmr_('close');
     break;
             
   }
 }

 function clear_bk_obj(data) {

     /* console.log('clear_bk_obj :: clear tweetin obj'); */
     ccc.str.tw.bn = [],  // backwards read new data
     ccc.str.tw.bd = [];  // backwards read in start date / time origin
 }  

 function _display(page) {

   set_css_class('watchbar', 'display', 'none');  
   set_css_id('in', 'display', 'none');
   set_css_id('cc', 'display', 'none');
   set_css_id('cw', 'display', 'none');
   set_css_id('cp', 'display', 'none');
   set_css_id('su', 'display', 'none');
 
   switch (page) {
     case ('in'):
          set_css_id('in', 'display', 'block');
     break;
     case ('cc'):
          set_css_id('cc', 'display', 'block');
     break;
     case ('cw'):
          set_css_id('cw', 'display', 'block');
     break;
     case ('cp'):
          set_css_id('cp', 'display', 'block');
     break;
     case ('su'): /* support view, all the curated crowdcc support tweets #support */
          set_css_id('su', 'display', 'block');
          
     break;

   }
 }

 function cleartw_obj(data) {

     /* console.log('clear tweetin obj'); */

     /* clear the session, reset the tweet flags */
     sessionStorage.clear();

     ccsession.clear();
     ccc.str.tw.fi = [];
     ccc.str.tw.fn = [];
     ccc.str.tw.fd = [];
     ccc.str.tw.bd = [];
 }

 function hascrollbar() {
    /* the modern solution */
    if (typeof window.innerWidth === 'number') {
        return window.innerWidth > document.documentElement.clientWidth;
    }
    /* rootElem for quirksmode */
    var rootElem = document.documentElement || document.body;
    
    /* check overflow style property on body for fauxscrollbars */
    var overflowStyle;

    if (typeof rootElem.currentStyle !== 'undefined') {
        overflowStyle = rootElem.currentStyle.overflow
    }
        overflowStyle = overflowStyle || window.getComputedStyle(rootElem, '').overflow;

    var contentOverflows = rootElem.scrollHeight > rootElem.clientHeight;

  return (contentOverflows && overflowShown);

  rootElem = null; overflowStyle = null; contentOverflows = null;
 }

 function scroller() {
 
   var _scrlvr = { 'scrolltop': '', 'scrollleft': '', 'winwidth': '', 'winheight': '', 'docheight': '', 'docbody': '', 'dochtml': '' };
   _scrlvr['docbody'] = document.body;
   _scrlvr['dochtml'] = document.documentElement;
   _scrlvr['docheight'] = Math.max( _scrlvr['docbody'].scrollHeight, _scrlvr['docbody'].offsetHeight, _scrlvr['dochtml'].clientHeight, _scrlvr['dochtml'].scrollHeight, _scrlvr['dochtml'].offsetHeight );
   _scrlvr['scrolltop']  = document.body.scrollTop || document.documentElement.scrollTop;
   _scrlvr['scrollleft'] = document.body.scrollLeft || document.documentElement.scrollLeft;
   _scrlvr['winwidth'] = isNaN(window.innerWidth) ? window.clientWidth : window.innerWidth;
   _scrlvr['winheight'] = isNaN(window.innerHeight) ? window.clientHeight : window.innerHeight;

   switch (true) {

      case ( _scrlvr['scrolltop'] === 0 ):
            /* console.log( 'scroller :: we are at the top :: width: ' + _scrlvr['winwidth'] + ' :: height: ' + _scrlvr['winheight'] ); */
      break;
      case ( _scrlvr['scrolltop'] + _scrlvr['winheight'] < ( _scrlvr['docheight'] - ( _scrlvr['winheight'] - ( _scrlvr['winheight']/1.1 ) )) ):
      
             switch (true) {

               case (visible_css_id('in')):
                     set_css_id('sdn', 'display', 'none');
                     set_css_id('sdn_cc', 'display', 'none');
                     set_css_id('sdn_cw', 'display', 'none');
               break;

               case (visible_css_id('cc')):        
                     set_css_id('sdn', 'display', 'none');
                     set_css_id('sdn_cc', 'display', 'none');
                     set_css_id('sdn_cw', 'display', 'none');
               break;

               case (visible_css_id('cw')):
                     set_css_id('sdn', 'display', 'none');
                     set_css_id('sdn_cc', 'display', 'none');
                     set_css_id('sdn_cw', 'display', 'none');
               break;

             }
      
      break;
      case ( _scrlvr['scrolltop'] + _scrlvr['winheight'] == _scrlvr['docheight'] ):
             /* console.log('scroller :: we are at the bottom'); */
      
             switch (true) {

               case (visible_css_id('in')):
                     set_css_id('sdn', 'display', 'block');
                     set_css_id('sdn_cc', 'display', 'none');
                     set_css_id('sdn_cw', 'display', 'none');
               break;

               case (visible_css_id('cc')):
                     set_css_id('sdn', 'display', 'none');
                     set_css_id('sdn_cc', 'display', 'block');
                     set_css_id('sdn_cw', 'display', 'none');
               break;

               case (visible_css_id('cw')):
                     set_css_id('sdn', 'display', 'none');
                     set_css_id('sdn_cc', 'display', 'none');
                     set_css_id('sdn_cw', 'display', 'block');
               break;

             }

      break;
   }
      
      _scrlvr = { 'scrolltop': '', 'scrollleft': '', 'winwidth': '', 'winheight': '', 'docheight': '', 'docbody': '', 'dochtml': '' };
   
      _scrlvr['scrolltop'] = null; _scrlvr['scrollleft'] = null; _scrlvr['winwidth'] = null; _scrlvr['winheight'] = null; _scrlvr['docheight'] = null; _scrlvr['docbody'] = null; _scrlvr['dochtml'] = null;
   
      _scrlvr = null;

 }

window.onscroll = scroller;
 

/*  handlefile :: ui event handlers  */

function handlefile(filein, imgplace) {


    var fileinput = document.getElementById(filein);
   
    var createBinaryFile = function(uintArray) {
    var data = new Uint8Array(uintArray);
    var file = new BinaryFile(data);

    file.getByteAt = function(iOffset) {
         return data[iOffset];}; 
    file.getBytesAt = function(iOffset, iLength) {
    var aBytes = [];
    for (var i = 0; i < iLength; i++) {
         aBytes[i] = data[iOffset + i];
    }
         return aBytes;
    };
    file.getLength = function() {
         return data.length;
    };

    /* console.log('handlefile :: picure size in bytes: ' +data.length); */



    switch (true) {

        case (data.length > 3000000):

              /* console.log('handlefile :: your picture is larger than 3MB!'); */
              file = null;
              message_('modal_close','mag');
              msgtmr_('close-error');
              return;
        break;

        case (data.length > 0):
              /* pic has been added * flag ccc.str.tw.tm[17] === 1 */
              ccc.str.tw.tm[17] = 1;
        break;

        }

    return file;
    };

    var listener = function(e) {

 
    /* console.log('handlefile :: ' + e); */
      e.preventDefault();
      if(this.files.length === 0) return;
      var imageFile = this.files[0];
      var img = new Image();
      var url = window.URL ? window.URL : window.webkitURL;
      img.src = url.createObjectURL(imageFile);

      img.onload = function(e) {
      url.revokeObjectURL(this.src);
      var width;
      var height;
      var binaryReader = new FileReader();

      binaryReader.onloadend = function(d) {
      var exif, transform = "none";
      exif=ccc.exi.EXIF.readFromBinaryFile(createBinaryFile(d.target.result)); /* exif = EXIF.readFromBinaryFile(createBinaryFile(d.target.result)); * now in global name space */

      if(typeof exif != 'undefined') {
 
        switch (exif.Orientation) {

          case  8:
            width = img.height;
            height = img.width;
            transform = "left";
          break;
          case  6:
            width = img.height;
            height = img.width;
            transform = "right";
          break;
          case  1:
            width = img.width;
            height = img.height;
          break;
          case  3:
            width = img.height;
            height = img.width;
            transform = "flip";
          break;

          default:
            width = img.width;
            height = img.height;
        } 

        /* crowdcc test * picture size * width = 250; height = 250; */
        /* crowdcc * picture size * ref: http://blog.filemobile.com/twitter-image-preview/ */
        width = 440; height = 440;
          
        var canvas = document.getElementById('reply-img-place-snd');

        canvas.width = width;
        canvas.height = height;
        var ctx = canvas.getContext("2d");
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        switch (transform) {

          case ('left'):
                ctx.setTransform(0, -1, 1, 0, 0, height);
                ctx.drawImage(img, 0, 0, height, width);
          break;
          case ('right'):
                ctx.setTransform(0, 1, -1, 0, width, 0);
                ctx.drawImage(img, 0, 0, height, width);
          break;
          case ('flip'):
                ctx.setTransform(1, 0, 0, -1, 0, height);
                ctx.drawImage(img, 0, 0, width, height);
          default:
                ctx.setTransform(1, 0, 0, 1, 0, 0);
                ctx.drawImage(img, 0, 0, width, height);
        }

                ctx.setTransform(1, 0, 0, 1, 0, 0);


        ccc.str.tw.tm[18] =  canvas.toDataURL("image/png");

        /* crowdcc in-app preview * picture size * width = 50; height = 50; */
        width = 50;height = 50;
                  
        canvas = document.getElementById(imgplace);
        
        canvas.width = width;
        canvas.height = height;

        ctx = canvas.getContext("2d");
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        switch (transform) {

          case ('left'):
                ctx.setTransform(0, -1, 1, 0, 0, height);
                ctx.drawImage(img, 0, 0, height, width);
          break;
          case ('right'):
                ctx.setTransform(0, 1, -1, 0, width, 0);
                ctx.drawImage(img, 0, 0, height, width);
          break;
          case ('flip'):
                ctx.setTransform(1, 0, 0, -1, 0, height);
                ctx.drawImage(img, 0, 0, width, height);
          default:
                ctx.setTransform(1, 0, 0, 1, 0, 0);
                ctx.drawImage(img, 0, 0, width, height);
        }

                ctx.setTransform(1, 0, 0, 1, 0, 0);
      }
  
    };
 
    binaryReader.readAsArrayBuffer(imageFile);

    };

    fileinput.removeEventListener('change', listener, false);
    /* console.log('handlefile :: event listener removed check'); */
    /* console.log('handlefile :: ' +  e); */
  };

  fileinput.addEventListener('change', listener, false);

}


window.onpopstate = function popState(event) {
     /* console.log('popState :: popstate event : ' + event.state); */
     window.history.pushState('', 'crowdcc', 'http://'+ window.location.hostname );
     return event.preventDefault();
}


document.onmouseout = function mouseOut(event) {

  if ( ccc.str.istw['mouse'] === 1 ) {

    var elem = (event.target) ? event.target : event.srcElement;
    
    try { var tagclass = elem.className.split(" ")[0]; } catch(e) { var tagclass = null; }
    
    var tagid = elem.id;

    if (ccc.str.tw.tm[7] !== 0 && tagclass === 'modal-retweet') {
      switch (true) {
    
       case (get_css_id('modal-retweet', 'color', 'rgb(17, 17, 17)') ):
             set_css_class('modal-retweet', 'color', '#B4B4B4');
       break;
      }
    }

    if (ccc.str.tw.tm[7] !== 2 && tagclass === 'modal-email') {
      switch (true) {
    
       case (ccc.str.tw.usr.ccuser === 'soc'):
       case (ccc.str.tw.usr.ccuser === 'ccn'):
             set_css_id('modal-email','color','#B4B4B4'); 
       break;
       case (get_css_id('modal-email', 'color', 'rgb(17, 17, 17)') ):
             set_css_class('modal-email', 'color', '#B4B4B4');
       break;   
      }
    }

    if (ccc.str.tw.tm[7] !== 3 && tagclass === 'modal-reply') {
      switch (true) {
    
       case (get_css_id('modal-reply', 'color', 'rgb(17, 17, 17)') ):
            set_css_class('modal-reply', 'color', '#B4B4B4');
       break;
      }
    }
   
    if (ccc.str.tw.tm[7] !== 4 && tagclass === 'modal-favor') {
      switch (true) {

       case (get_css_id('modal-favor', 'color', 'rgb(17, 17, 17)') ):
             set_css_class('modal-favor', 'color', '#B4B4B4');
       break;
      }
    }
    
    elem = null; tagclass = null; tagid = null;

  }
}

document.onmouseover = function mouseOver(event) {

  if ( ccc.str.istw['mouse'] === 1 ) {

    var elem = (event.target) ? event.target : event.srcElement;

    try { var tagclass = elem.className.split(" ")[0]; } catch(e) { var tagclass = null; }
    
    var tagid = elem.id;

    if (ccc.str.tw.tm[7] !== 0 && tagclass === 'modal-retweet') {
      switch (true) {
    
       case (get_css_id('modal-retweet', 'color', 'rgb(180, 180, 180)') ):
             set_css_class('modal-retweet', 'color', '#111111');
       break;
      }
    }

    if (ccc.str.tw.tm[7] !== 2 && tagclass == 'modal-email') {
      switch (true) {

       case (ccc.str.tw.usr.ccuser === 'soc'):
       case (ccc.str.tw.usr.ccuser === 'ccn'):
             set_css_id('modal-email','color','#d40d12');
       break;
       case (get_css_id('modal-email', 'color', 'rgb(180, 180, 180)') ):
             set_css_class('modal-email', 'color', '#111111');
       break;
      }
    }

    if (ccc.str.tw.tm[7] !== 3 && tagclass == 'modal-reply') {
      switch (true) {

       case (get_css_id('modal-reply', 'color', 'rgb(180, 180, 180)') ):
             set_css_class('modal-reply', 'color', '#111111');
       break;
      }
    }

    if (ccc.str.tw.tm[7] !== 4 && tagclass == 'modal-favor') {
      switch (true) {

       case (get_css_id('modal-favor', 'color', 'rgb(180, 180, 180)') ):
             set_css_class('modal-favor', 'color', '#111111');
       break;
      }
    }

    elem = null; tagclass = null; tagid = null;

  }
}


document.onkeydown = function keyDown(event) {

  switch (ccc.str.tw.tm[7]) {

    case (7): /* create */
          var incount = '';
          var inhtml = document.getElementById('js-srctxt-new').innerHTML;
          inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
          document.getElementById('js-new_countdown').innerHTML =  incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );

          switch (true) {
      
            case (incount === 140):
                  set_css_id('modal-quill','color','#111111');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[20] = 0;
            break;
  
            case (incount < 0):
                  set_css_id('modal-quill','color','#d40d12');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[20] = 0;
            break;

            case (incount < 11):
                  set_css_id('js-new_countdown', 'color', '#D40D12');
                  set_css_id('modal-quill','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[20] = 1;
            break;

            case (incount < 140):
                  set_css_id('modal-quill','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[20] = 1;

            case (incount > 10):
                  set_css_id('js-new_countdown', 'color', '#8899A6'); 
            break;
            
          }
    break;

    case (3): /* reply */
          var incount = ''; var inpco = ''; var inpck = '';
          var inhtml = document.getElementById('js-srctxt-reply').innerHTML;
          ccc.str.tw.tm[16] = 0;

          /* console.log('keyDown :: reply :: inhtml:' + inhtml); */
          inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
          document.getElementById('js-reply_countdown').innerHTML = incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );

          inpco =  (document.getElementById('js-srctxt-reply').innerHTML).replace(/(<([^>]+)>)/ig,"").length;
          inpck = +(ccc.str.tw.tm[3].length + 2);

          /* check has tweet been replied too */
          /* console.log('keyDown :: reply :: inpco: ' + inpco ); */
          /* console.log('keyDown :: reply :: inpck: ' + inpck ); */
          /* console.log('keyDown :: reply :: content: ' + inhtml ); */
          /* console.log('keyDown :: reply :: reply countdown: ' + document.getElementById('js-reply_countdown').innerHTML); */
          /* console.log('keyDown :: reply :: incount: ' + incount); */

          switch (true) {

            case (inpco > inpck):
                  /* someone has typed a reply */
                  set_css_id('modal-reply','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[16] = 1;
            break;

            case (inpco === inpck):
                  /* nothing to do, content has not been replied too */
                  set_css_id('modal-reply','color','#111111');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[16] = 0;
            break;
          }

          switch (true) {
            /*
            case (inpco > 140):
                  set_css_id('modal-reply','color','#d40d12');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[16] = 0;
            break;
            */
            case (incount < 0):
                  set_css_id('modal-reply','color','#d40d12');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[16] = 0;
            break;
    
            case (incount < 11):
                  set_css_id('js-reply_countdown', 'color', '#D40D12');
                  set_css_id('modal-reply','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[16] = 1;
            break;
            
            case (incount > 10):
                  set_css_id('js-reply_countdown', 'color', '#8899A6');   
            break;
          }
    break;

    case (2): /* ccmail */
          var incount = '';
          var inhtml = document.getElementById('js-srctxt-mail').innerHTML;
          ccc.str.tw.tm[19] = 0;

          inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
          document.getElementById('js-mail_countdown').innerHTML = incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );

          switch (true) {

            case (!isemail( document.getElementById('email-to').value )):
                  set_css_id('modal-email','color','#111111');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[19] = 0;
            break;
            
            case (isemail( document.getElementById('email-to').value )):
                  set_css_id('modal-email','color','#3DB3E5');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[19] = 1;
            break;

          }

          switch (true) {
          
            case (incount < 0):
                  set_css_id('js-mail_countdown', 'color', '#8899A6');  
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[19] = 0;
            break;

            case (incount < 11):
                  set_css_id('js-mail_countdown', 'color', '#D40D12');
                  set_css_id('modal-email','color','#3DB3E5');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[19] = 1;
            break;

            case (incount > 10):
                  set_css_id('js-mail_countdown', 'color', '#8899A6');   
            break;
          }
    break;
  }
  
  incount = null; inpco = null; inpck = null; inhtml = null;
  /* console.log(keyDown :: reply :: inhtml); */

  if ( ccc.sig.isvalid['enablekey'] === 1 ) { var focusid = document.activeElement;
   
   if ( !focusid || focusid === null || focusid == document.body ) { focusid = ''; } else if ( document.querySelector ) { focusid = document.querySelector(":focus"); focusid = focusid.getAttribute('id');}
  
    var tagid = event.target.id;
  
    if (tagid) {

      switch (tagid) {

          case ('passcode_account_settings'):
          
              switch (true) {

                case (document.getElementById('passcode_account_settings').value === ''):
                      document.getElementById('passcode_account_settings_verify').value = '';
                case (document.getElementById('passcode_account_settings').value.length === 1):
                      document.getElementById('passcode_account_settings_verify').value = '';
                case (document.getElementById('passcode_account_settings_verify').value.length === 1):
                      document.getElementById('passcode_account_settings').style.borderColor = '#cccccc';
                      document.getElementById('passcode_account_settings_verify').style.borderColor = '#cccccc'; 
                break;

              }

                document.getElementById('passcode_account_settings_verify').disabled=false;
                
          break;

      }
    }    
    
    tagid = null;
  
  }
}


document.onkeyup = function keyPress(event) {

  switch (ccc.str.tw.tm[7]) {

    case (7): /* create */
          var incount = '';
          var inhtml = document.getElementById('js-srctxt-new').innerHTML;
          ccc.str.tw.tm[20] = 0;

          /* console.log('keyPress :: inhtml:' + inhtml); */
          inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
          document.getElementById('js-new_countdown').innerHTML = incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );
  
          /* console.log('keyPress :: reply :: ' + incount); */

          switch (true) {
            
            case (incount === 140):
                  set_css_id('modal-quill','color','#111111');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[20] = 0;
            break;
          
            case (incount < 0):
                  set_css_id('modal-quill','color','#d40d12');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[20] = 0;
            break;

            case (incount < 11):
                  set_css_id('js-new_countdown', 'color', '#D40D12');
                  set_css_id('modal-quill','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[20] = 1;
            break;
            
            case (incount < 140):
                  set_css_id('modal-quill','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');

            case (incount > 10):
                  set_css_id('js-new_countdown', 'color', '#8899A6');  
            break;
          }
    break;

    case (3): /* reply */
          var incount = ''; var inpco = ''; var inpck = '';
          var inhtml = document.getElementById('js-srctxt-reply').innerHTML;
          ccc.str.tw.tm[16] = 0;

          /* console.log('keyPress :: reply :: inhtml:' + inhtml); */
          inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
          document.getElementById('js-reply_countdown').innerHTML = incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );

          inpco =  (document.getElementById('js-srctxt-reply').innerHTML).replace(/(<([^>]+)>)/ig,"").length;
          inpck = +(ccc.str.tw.tm[3].length + 2);

          /* check has tweet been replied too */
          /* console.log('keyPress :: reply :: inpco: ' + inpco ); */
          /* console.log('keyPress :: reply :: inpck: ' + inpck ); */
          /* console.log('keyPress :: reply :: content: ' + inhtml ); */
          /* console.log('keyPress :: reply :: reply countdown: ' + document.getElementById('js-reply_countdown').innerHTML); */
          /* console.log('keyPress :: reply :: incount: ' + incount); */

          switch (true) {

            case (inpco > inpck):
                  /* someone has typed a reply */
                  set_css_id('modal-reply','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[16] = 1;
            break;

            case (inpco === inpck):
                  /* nothing to do, content has not been replied too */
                  set_css_id('modal-reply','color','#111111');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[16] = 0;
            break;
          }

          switch (true) {
            /*
            case (inpco > 140):
                  set_css_id('modal-reply','color','#d40d12');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[16] = 0;
            break;
            */
            case (incount < 0):
                  set_css_id('modal-reply','color','#d40d12');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[16] = 0;
            break;
            
            case (incount < 11):
                  set_css_id('js-reply_countdown', 'color', '#D40D12');
                  set_css_id('modal-reply','color','#3da7f2');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[16] = 1;
            break;

            case (incount > 10):
                  set_css_id('js-reply_countdown', 'color', '#8899A6'); 
            break;
          }
    break;

    case (2): /* ccmail */
          var incount = '';
          var inhtml = document.getElementById('js-srctxt-mail').innerHTML;
          inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
          document.getElementById('js-mail_countdown').innerHTML = incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );

          switch (true) {

            case (!isemail( document.getElementById('email-to').value )):
                  set_css_id('modal-email','color','#111111');
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[19] = 0;
            break;
            
            case (isemail( document.getElementById('email-to').value )):
                  set_css_id('modal-email','color','#3DB3E5');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[19] = 1;
            break;

          }

          switch (true) {
          
            case (incount < 0):
                  set_css_id('js-mail_countdown', 'color', '#8899A6'); 
                  set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
                  ccc.str.tw.tm[19] = 0;
            break;

            case (incount < 11):
                  set_css_id('js-mail_countdown', 'color', '#D40D12');
                  set_css_id('modal-email','color','#3DB3E5');
                  set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
                  ccc.str.tw.tm[19] = 1;
            break;

            case (incount > 10):
                  set_css_id('js-mail_countdown', 'color', '#8899A6');   
            break;
          }
    break;
  }

  incount = null; inpco = null; inpck = null; inhtml = null;

  /* from signin.js document.onkeyup = function keyPress(event) :: start */

  if ( ccc.sig.isvalid['enablekey'] === 1 ) { var focusid = document.activeElement;
   
   if ( !focusid || focusid === null || focusid == document.body ) { focusid = ''; } else if ( document.querySelector ) { focusid = document.querySelector(":focus"); focusid = focusid.getAttribute('id');}
  
    var tagid = event.target.id;
    var keyok = false;

    /* keyPress :: reply :: global --> var ccc.sig.isvalid = { 'vp1': 0, 'vp2': 0, 'vp3': 0, 've': 0}; */
  
    if (tagid) {

    switch (tagid) {

      case ('pcode_new'):

          switch(true) {
            case ( ccc.sig.isvalid['vp1'] === 0 ):
            case ( isemptystr(document.getElementById(tagid).value) ):
                   document.getElementById("new-ccc").disabled=true;
            break;
          }
                               
          ccc.sig.isvalid['vp1'] = pvalidate( trimspace(pcode_new.value) , 'pcode_new', 'pcode_new_msg', 'please enter all passwords', 'pcode_new_validate_msg' );  
          
          switch(true) {
            case (trimspace(pcode_new.value) === trimspace(pcode_retype.value)):
                  ccc.sig.isvalid['vp1'] = pvalidate( trimspace(pcode_retype.value) , 'pcode_retype', 'pcode_new_msg', 'new passwords match', 'pcode_new_validate_msg' );
                  if ( (ccc.sig.isvalid['vp1'] + ccc.sig.isvalid['vp2']) == 2 ) {  
            
                      set_html_id('pcode_new_msg','Password entered: <span style="color:#999999">verification required</span>');
                      set_html_id('pcode_new_validate_msg','<span style="font-size:12px;color:#444444">Save changes</span> to verify data');
                      document.getElementById("new-ccc").disabled=true;

                  }  
            break;
            case (ccc.sig.isvalid['vp1'] === 0):
                  document.getElementById("new-ccc").disabled=true;
            break;
            case (trimspace(pcode_new.value) !== trimspace(pcode_retype.value)):
                  ccc.sig.isvalid['vp1'] = pvalidate( trimspace(pcode_new.value) , 'pcode_new', 'pcode_new_msg', 'new passwords do not match', 'pcode_new_validate_msg' );  
            break;
          }

      break;

      case ('eirm_retype'):

          ccc.sig.isvalid['vp1'] = pvalidate( trimspace(eirm_retype.value) , 'eirm_retype', 'eirm_new_msg', 'verification required', 'eirm_new_validate_msg' ); 

          switch(true) {
             
            case ( isemptystr(document.getElementById(tagid).value) ):

                   set_css_id('eirm_retype','borderColor','#cccccc');
                   set_css_id('eirm_new_msg', 'visibility', 'hidden');
                   set_css_id('eirm_new_validate_msg', 'visibility', 'hidden');

                   document.getElementById("eirm-ccc").disabled=true;

                   /* console.log('keyPress :: eirm_retype :: ccc.sig.isvalid[vp1] => ' + ccc.sig.isvalid['vp1']); */
            break;
            case ( !isemptystr(document.getElementById(tagid).value) ):

                   document.getElementById("eirm-ccc").disabled=true;

                   set_css_id('eirm_new_validate_msg','display','none');
                   set_css_id('eirm_new_msg', 'visibility', 'visible');
                   set_css_id('eirm_new_validate_msg', 'visibility', 'visible');

                   if ( ccc.sig.isvalid['vp1'] === 1 ) {

                      set_html_id('eirm_new_validate_msg', '<span style="font-size:12px;color:#444444">Save changes</span> to verify data');
                      set_css_id('eirm_new_validate_msg','display','block');

                      document.getElementById("eirm-ccc").disabled=false;
   
                   }

                   /* console.log('keyPress :: eirm_retype :: ccc.sig.isvalid[vp1] => ' + ccc.sig.isvalid['vp1']); */
            break;
          }

      break;

      case ('pcode_retype'):

          switch(true) {
                case(ccc.sig.isvalid['vp2'] === 0):
              
                case ( isemptystr(document.getElementById(tagid).value) ):
                  
                       document.getElementById("new-ccc").disabled=true;
                break;
          }
  
          ccc.sig.isvalid['vp2'] = pvalidate( trimspace(pcode_retype.value) , 'pcode_retype', 'pcode_new_msg', 'please enter all passwords', 'pcode_new_validate_msg' );  
          switch(true) {
            case( trimspace(pcode_new.value) === trimspace(pcode_retype.value) ):
                      
                  ccc.sig.isvalid['vp2'] = pvalidate( trimspace(pcode_retype.value) , 'pcode_retype', 'pcode_new_msg', 'new passwords match', 'pcode_new_validate_msg' );
                      
                  if ( (ccc.sig.isvalid['vp1'] + ccc.sig.isvalid['vp2']) === 2 ) {

                      
                   
                      
                      set_html_id('pcode_new_msg', 'Password entered: <span style="color:#999999">verification required</span>');
                      set_html_id('pcode_new_validate_msg', '<span style="font-size:12px;color:#444444">Save changes</span> to verify data');

                      document.getElementById("new-ccc").disabled=false;
                      
                  }  
            break;
            case(ccc.sig.isvalid['vp2'] === 0):
                 document.getElementById("new-ccc").disabled=true;
            break;
            case(trimspace(pcode_new.value) !== trimspace(pcode_retype.value)):
                 ccc.sig.isvalid['vp2'] = pvalidate( trimspace(pcode_retype.value) , 'pcode_retype', 'pcode_new_msg', 'new passwords do not match', 'pcode_new_validate_msg' );  
            break;
          }

      break;

      case ('pcode_reset'):

          /* console.log('keyPress :: pcode_reset :: ...' + tagid); */

          if( isemptystr(document.getElementById(tagid).value) ) {
                 
              document.getElementById("reset-ccc").disabled=true;
          }

          ccc.sig.isvalid['ve'] = evalidate( trimspace(pcode_reset.value) , 'pcode_reset', 'pcode_reset_msg', 'pcode_reset_validate_msg' );

          switch(true) {
              case(ccc.sig.isvalid['ve'] === 1):
          
                   set_html_id('pcode_reset_msg', 'Email entered: <span style="color:#999999">verification required</span>');
                   set_html_id('pcode_reset_validate_msg', '<span style="font-size:12px;color:#444444">Save changes</span> to verify data');
                   document.getElementById("reset-ccc").disabled=false;

              break;
              case(ccc.sig.isvalid['ve'] === 0):
                   document.getElementById("reset-ccc").disabled=true;
              break;
          }

      break;

      case ('email_account_settings'):

          /* console.log('keyPress :: email_account_settings :: ...' + tagid); */

          if( isemptystr(document.getElementById(tagid).value) ) {

              document.getElementById("passcode_account_settings").disabled=false;
              document.getElementById("passcode_account_settings_verify").disabled=false;
              document.getElementById("account-settings-save-changes").disabled=true;

          
          } else {
              
              document.getElementById("passcode_account_settings").disabled=true;
              document.getElementById("passcode_account_settings_verify").disabled=true;
          }

          ccc.sig.isvalid['ve'] = evalidate( trimspace(email_account_settings.value) , 'email_account_settings', 'ecode_account_settings_msg', 'account_settings_msg' );

      break;

      case ('passcode_account_settings'):

          /* console.log('keyPress :: passcode_account_settings :: ...' + tagid); */

          if( isemptystr(document.getElementById(tagid).value) ) {
  
              document.getElementById("email_account_settings").disabled=false;
     
          } else {
      
              document.getElementById("email_account_settings").disabled=true;
              document.getElementById("account-settings-save-changes").disabled=true;
          }

          ccc.sig.isvalid['vp1'] = pvalidate( trimspace(passcode_account_settings.value) , 'passcode_account_settings', 'pcode_account_settings_msg', 'please enter all passwords', 'account_settings_msg' );  
          
          switch(true) {
            case(ccc.sig.isvalid['vp1'] + ccc.sig.isvalid['vp2'] === 2):

                 set_html_id('pcode_account_settings_msg', 'Password entered: <span style="color:#999999">verification required</span>');
                 set_html_id('account_settings_msg', '<span style="font-size:12px;color:#444444">Save changes</span> to verify data');
                 document.getElementById("account-settings-save-changes").disabled=false;
                 
            break;
          }
     
      break;

      case ('passcode_account_settings_verify'): 

          if( isemptystr(document.getElementById(tagid).value) ) {

              document.getElementById("email_account_settings").disabled=false;
          
          } else {
         
              document.getElementById("email_account_settings").disabled=true;
              document.getElementById("account-settings-save-changes").disabled=true;
          }

          ccc.sig.isvalid['vp2'] = pvalidate( trimspace(passcode_account_settings_verify.value) , 'passcode_account_settings_verify', 'pcode_account_settings_msg', 'please enter all passwords', 'account_settings_msg' );      
          
          switch(true) {

            case(trimspace(passcode_account_settings.value) === 0):
            break;

            case(trimspace(passcode_account_settings.value) === trimspace(passcode_account_settings_verify.value)):
                 ccc.sig.isvalid['vp2'] = pvalidate( trimspace(passcode_account_settings_verify.value) , 'passcode_account_settings_verify', 'pcode_account_settings_msg', 'Current passwords match', 'account_settings_msg' );
                 if ( (ccc.sig.isvalid['vp1'] + ccc.sig.isvalid['vp2']) == 2 ) {
                      
                     set_html_id('account_settings_msg', '<span style="font-size:12px;color:#444444">Save changes</span> to verify data');
                     document.getElementById("account-settings-save-changes").disabled=false;
                      
                 }  
            break;

            case(trimspace(passcode_account_settings.value) !== trimspace(passcode_account_settings_verify.value)):
                 ccc.sig.isvalid['vp2'] = pvalidate( trimspace(passcode_account_settings_verify.value) , 'passcode_account_settings_verify', 'pcode_account_settings_msg', 'Current passwords do not match', 'account_settings_msg' );  
            break;

            case(ccc.sig.isvalid['vp1'] + ccc.sig.isvalid['vp2'] === 2):

                 set_html_id('pcode_account_settings_msg', 'Password entered: <span style="color:#999999">verification required</span>');
                 set_html_id('account_settings_msg', '<span style="font-size:12px;color:#444444">Save changes</span> to verify data');
                 document.getElementById("account-settings-save-changes").disabled=false;
                 
            break;

          }

      break;

     }

    }

 }

  /* from signin.js document.onkeyup = function keyPress(event) :: end */
  incount = null; inhtml = null; focusid = null; tagid = null; keyok = null;

}


document.onclick = function keyClick(event) {

  var elem = (event.target) ? event.target : event.srcElement;
 
  try { var tagclass = elem.className.split(" ")[0]; } catch(e) { var tagclass = null; }
  var tagid = elem.id;

  var tagtxtlen = trimspace(elem.innerHTML).length;
  var tagclass = elem.className.split(" ")[0];
  var tagtype = elem.tagName.toLowerCase();
  var limit = parseInt( document.getElementById('js-srctxt-new').innerHTML.length );

  /* from signin.js document.onclick = function keyClick(event) :: start */

  switch (true) {

    case (elem.value):
          tagtxtlen = (elem.value.replace(/^\s\s*/, '').replace(/\s\s*$/, '')).length;
    case (tagclass === 'ui-dialog-titlebar-close'):
          tagtxtlen = 1;
    break;
    case (tagclass === 'ui-button-text'):
    case (tagclass === 'ui-dialog-noticebar-close'):
          tagtxtlen = 1;
    break;
    case (tagid === 'pin' && (tagclass.length + tagtxtlen.length === 0)):
    case (tagclass.length  + tagid.length + tagtxtlen.length  === 0):
          /* console.log('keyClick :: pin :: body click close ... x'); */
          tagtxtlen = 0;
          _click('reset');
    break;

  }
 
  var tagclose = false;

  /* from signin.js document.onclick = function keyClick(event) :: end */
   
  ccc.str.tw.tm[8] = 0;

  /* console.log('keyClick :: classname :' +elem.className.split(" ")[0]); */
  /* console.log('keyClick :: tagclass:' + tagclass); */
  /* console.log('keyClick :: id :' +elem.id); */
  /* console.log('keyClick :: text :' + tagtxtlen); */
  /* console.log('keyClick :: tagtype: ' +tagtype); */

  switch (true) {

    case (tagclass === 'ccc-top'):
      


          switch (true) {

            case ( get_css_id('ccc-top', 'color', 'rgb(216, 222, 229)') ):
            case ( get_css_id('ccc-top', 'color', 'rgb(102, 117, 127)') ):
            case ( get_css_id('ccc-top', 'color', 'rgb(72, 72, 72)') ):
         
             /* watch bar comment out for production */

             /*
             switch (true) {

                case ( visible_css_class('watcher') ):
                       set_css_class('watchbar', 'display', 'none');
                       set_css_class('ccwatch', 'display', 'none');
                break;
                case ( !visible_css_class('watcher') ):
                       set_css_class('watchbar', 'display', 'block');
                       set_css_class('ccwatch', 'display', 'block');
                break;
             }
             */

            break;

            case ( get_css_id('ccc-top', 'color', 'rgb(85, 172, 238)') ):
                 
                  switch(true) {

                    case ( visible_css_id('in') ):
                           set_css_class('icon-ccc_large','color','#484848');
                           /* console.log('keyClick :: ccc-top :: this is in!'); */
                    break;
                    case ( visible_css_id('cc') ):
                           set_css_class('icon-ccc_large','color','#D8DEE5');
                           /* ux * click new tweets * remain in cc store view    */
                           /* console.log('keyClick :: ccc-top :: this is cc!'); */
                           instore(); /* ux * click new tweets * switch to default in store view */
                    break;
                    case ( visible_css_id('cw') ):
                    break;

                  }

        
                  /* used to be tagid === more */
                  
                  if (ccc.str.tw.mi[0] !== 0) {
                    switch (true) {
                      case (ccc.str.tw.sc[0] !== 0):
                            readtwin_obj(ccc.str.tw.sc[0]);
                      break;
                      case (ccc.str.tw.sc[0] === 0):
                            readtwin_obj(20);      /* current content default */
                      break;
                    }
                    set_doc_title('crowdcc');
                  }

            break;
            
          }

    break;

    case (tagclass === 'container'):
    case (tagclass === ''):
          switch (true) {

            case (tagid === ''):
            case (tagid === 'bottom-pad'):
                  /* console.log('keyClick :: its all clicking off ...'); */
                  /* console.log('keyClick :: status =>' + has_css_class('acc-help','menu-select') ); */
                  /* console.log('keyClick :: status notice-cd =>' + visible_css_id('notice-cd')); */
                
                  if (!visible_css_id('notice-cd')) {
                      _click('reset');
                      toggle_signin('reset');
                  }
            break;
          }

          /* temp test(s) for css positioning! */
          /* see_me(); */
          /* see_load(); */
    break;

    case (tagclass === 'me'):
          if ( visible_css_id('accfrm') ) {
             set_css_id('accfrm', 'display', 'none');
             set_css_id('account', 'display','none');

          } else {

             message_('message_close', '');

             set_css_id('accfrm', 'display', 'block');
             set_css_id('account', 'display','block');

             if ( get_html_id('acc-spa-tag-num') < 1 ) { set_css_id('acc-spa-tag-num','color','#d40d12'); set_css_id('acc-spa-tag-cc','color','#d40d12'); }
          }
    break;

    case (tagclass === 'crowd'):
    case (tagclass === 'cw'):
    
          
          set_css_class('watchbar', 'display', 'none');
          set_css_class('icon-ccc_large','color','#4683EA');
          /* crowd view, the most popular copied tweets #ccpop */
          set_css_id('in', 'display', 'none');
          set_css_id('cc', 'display', 'none');
         
          set_css_id('cw', 'display', 'block');
          set_css_id('cp', 'display', 'none');
          set_css_id('su', 'display', 'none');

          /* get #ccpop public view */
          /* console.log('keyClick :: cw :: clicked cw ...'); */
          process_public('cw');
    break;

    case (tagclass === 'cc'):
    case (tagclass === 'cp'):
 

          set_css_class('watchbar', 'display', 'none');
          set_css_class('icon-ccc_large','color','#36A64F');
          /* cc view, the users using the most storage #cctop */
          set_css_id('in', 'display', 'none');
          set_css_id('cc', 'display', 'none');

          set_css_id('cw', 'display', 'none');
          set_css_id('cp', 'display', 'block');
          set_css_id('su', 'display', 'none');

          /* get #cctop public view */
      
    break;

    case (tagclass === 'support'):
    case (tagclass === 'su'):
 

         set_css_class('watchbar', 'display', 'none');
         set_css_class('icon-ccc_large','color','#FF1919');
         /* support view, all the curated crowdcc support tweets #support */
         set_css_id('in', 'display', 'none');
         set_css_id('cc', 'display', 'none');

         set_css_id('cw', 'display', 'none');
         set_css_id('cp', 'display', 'none');
         set_css_id('su', 'display', 'block');

         /* get #support public view */
    break;

    /* API timer stop/start by click on crowdcc icon */

    /* reset * crop * clear to be handled automatically

       reset * pause managed by navigating to different views 
       crop  * is now managed by the trash_store
       clear * is for signout */

    /* API timer started when signin ccc.str.verified */

    case (tagclass === 'reset'):
          /* console.log('keyClick :: reset :: reset timer!'); */
          reset_watch();
    break;

    case (tagclass === 'crop'):
          /* console.log('keyClick :: crop :: trash store!'); */
          trash_store('crop');
    break;

    case (tagclass === 'clear'):
          /* console.log('keyClick :: clear ::clear store!'); */
          cleartw_obj();
          /* alert('session store cleared ... optional screen refresh!'); */
          window.location.href = window.location.href;
    break;

    /* above section to be commented out */

    case (tagclass === 'js-retweet'):
 
          switch (true) {
            case (ccc.str.tw.tm[8] == 1):
                  /* console.log('keyClick :: js-retweet :: the retweet button has been disabled'); */
                  set_attrib_id('js-share-retweettweet', 'contenteditable', 'false');
            
            return false;
            break;
            case (ccc.str.tw.tm[8] == 0):
                  switch (true) {
                    case (ccc.str.tw.tm[7] == 1):
                  
                          if ( (/\s+$/.test( document.getElementById('js-srctxt').innerHTML )) ) {

                             var el = document.getElementById('js-srctxt');
                             /* console.log('keyClick :: js-retweet :: ' + el.innerHTML); */
                             tstxt = el.innerHTML.replace(/(&nbsp;)+$/, '');
                             /* console.log('keyClick :: js-retweet :: ' + tstxt); */           
                             /* console.log('keyClick :: js-retweet :: tweet retweet edit: space detected at end of string'); */
                          }
                    break;
                    case (ccc.str.tw.tm[7] == 0):
                          /* console.log('keyClick :: tweet no retweet'); */
                    break;
                  }
          
                  /* console.log('keyClick :: js-retweet :: the retweet button is enabled'); */
                  /* retweet :: user signin screen_name (from signin), id_str of tweet to re-tweet _cc.fi.26 */
                  
                  set_css_id('modal-retweet','color','#3DB3E5');
                
                  switch (get_html_id('retweet-button')) {

                    case ('Retweet'):
                          set_html_id('retweet-button', 'Ok');
                          ccc.str.tw.tm[14] = 1; /* retweet flag enabled */ 
                    break;

                    case ('Ok'):
                          /* alert('Ok pressed!'); */
                          /* process retweet ccc.str.tw.tm[14] or favour ccc.str.tw.tm[15] or replyto ccc.str.tw.tm[16] or ccmail ccc.str.tw.tm[17] => refavor(retweet, favor, replyto, ccmail) */
                          refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
                    break;

                  }          
          
            break;
          }

    break;

    case (tagclass === 'js-favor'):

          /* console.log('keyClick :: js-favor :: favor button pressed!'); */
          set_css_id('modal-favor','color','#3DB3E5');

    case (tagid === 'favor-button'):
         
          /* console.log('keyClick :: js-favor :: favor button pressed!'); */

          get_html_id('favor-button');

          /* console.log(  get_html_id('favor-button')  ); */

          switch (get_html_id('favor-button')) {

            case ('Favor'):
                   set_html_id('favor-button', 'Ok');
                   ccc.str.tw.tm[15] = 1; /* favor flag enabled */ 
            break;

            case ('Ok'):
                   /* alert('Ok pressed!'); */
                   /* process retweet ccc.str.tw.tm[14] or favour ccc.str.tw.tm[15] or replyto ccc.str.tw.tm[16] or ccmail ccc.str.tw.tm[17] => refavor(retweet, favor, replyto, ccmail) */
                   refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
            break;

          }

    break;

    case (tagclass === 'watch_srt'):
    
          if (typeof ccc.str.tw.tm[10] == "undefined" ) {ccc.str.tw.tm[10] = 0;}
          
            switch (true) {

              case (ccc.str.tw.tm[10] == 0):
                    /* console.log('keyClick :: watch_srt :: start watch'); */
                    clear_css_class('watch_srt', 'icon-thunder');
                    start_watch();
                    ccc.str.tw.tm[10] = 1;
              break;
          
              case (ccc.str.tw.tm[10] == 1):
                    /* console.log('keyClick :: watch_srt :: stop watch'); */
                    clear_css_class('watch_srt', 'icon-cloud');
                    stop_watch();
                    ccc.str.tw.tm[10] = 0;
              break;
            }

    break;

    case (tagclass === 'create'):
          /* pause * timer */
          pausecc();

          ccc.str.tw.tm[7] = 7;

          /* retweet * favor * reply * email * share/create : flags */
          
          ccc.str.tw.tm[14] = 0; /* retweet */ 
          ccc.str.tw.tm[15] = 0; /* favor   */
          ccc.str.tw.tm[16] = 0; /* reply   */ 
          ccc.str.tw.tm[17] = 0; /* reply with media */
          ccc.str.tw.tm[18] = 0; /* reply with media canvas */
          ccc.str.tw.tm[19] = 0; /* ccmail */

          ccc.str.tw.tm[20] = 0; /* share / create tweet */
          ccc.str.tw.tm[21] = 0; /* share / create tweet with media */
          ccc.str.tw.tm[22] = 0; /* trash  copy store media */
          ccc.str.tw.tm[23] = 0; /* carbon copy store tweet */

          ccc.str.tw.tm[30] = 0; /* share_cc disabled = 0 */

          /* var width = 50;var height = 50;var canvas = document.getElementById('img-place');canvas.width = width;canvas.height = height;var ctx = canvas.getContext("2d");ctx.clearRect(0, 0, canvas.width, canvas.height);width= null; height=null; canvas = null; ctx = null; */

          trashcanvas('img-place', '50', '50');

          set_css_id('modal-inner', 'display', 'block');
          
          set_css_id('retweet-tweet-dialog-header', 'display', 'none');
          set_css_id('reply-tweet-dialog-header', 'display', 'none');
          set_css_id('create-tweet-dialog-header', 'display', 'block');
          set_css_class('modal-retweet', 'display', 'none');
     
          set_css_class('modal-favor', 'display', 'none');
          set_css_class('modal-reply', 'display', 'none');
          set_css_class('modal-email', 'display', 'none');
          
          set_css_id('modal-cancel','display','block');
          set_css_id('modal-cancel-table','display','none');

          set_css_class('modal-quill', 'color', '#111111');
          set_css_class('modal-quill', 'display', 'block');
    
          set_css_class('modal-carbon-count', 'color', '#F0F0F0');

          set_css_class('retweet-form', 'display', 'none');
          set_css_class('share-form', 'display', 'block');
          set_css_class('reply-form', 'display', 'none');
          set_css_class('email-form', 'display', 'none');
          set_css_class('modal-share', 'display', 'block');

          document.getElementById('share-form').getElementsByClassName('twitter-pic')[0].getElementsByTagName('a')[0].setAttribute('href', 'http://twitter.com/' + ccc.str.tw.usr.screen_name);
          document.getElementById('share-form').getElementsByClassName('twitter-pic')[0].getElementsByTagName('img')[0].setAttribute('src', ccc.str.tw.usr.profile_image_url);

          document.getElementById('share-form').getElementsByClassName('tweetprofilelink')[0].getElementsByTagName('a')[0].setAttribute('title', ccc.str.tw.usr.screen_name);
          document.getElementById('share-form').getElementsByClassName('tweetprofilelink')[0].getElementsByTagName('a')[0].setAttribute('href', 'http://twitter.com/' + ccc.str.tw.usr.screen_name);
          document.getElementById('share-form').getElementsByClassName('tweetprofilelink')[0].getElementsByTagName('a')[0].innerHTML =  ccc.str.tw.usr.screen_name;
          document.getElementById('share-form').getElementsByClassName('new-share-time')[0].getElementsByTagName('a')[0].setAttribute('href', 'http://twitter.com/' + ccc.str.tw.usr.screen_name);
         
          set_css_class('semantic-content', 'display', 'block');
          set_attrib_id('js-srctxt-new', 'contenteditable', 'true');

          document.getElementById('js-new_countdown').innerHTML = addzero(( 140 - parseInt( document.getElementById('js-srctxt-new').innerHTML.length )),3);

          set_css_class('retweet-footer', 'display', 'none');
          set_css_class('favor-footer', 'display', 'none');
          set_css_class('reply-footer', 'display', 'none');
          set_css_class('share-footer', 'display', 'block');
          set_css_class('email-footer', 'display', 'none');
  
          set_css_id('js-srctxt-new', 'focus', 'focus');

          set_html_class('send-action','Share');
          set_html_id('share-button','Share');
    break;

    case (tagclass === 'js-reshare'):
          /* share-button (create tweet ... yes you ... ) */
          /* console.log('keyClick :: js-reshare :: create tweet, just text!'); */
          /* pre-amble * flag set * re-set */
          ccc.str.tw.tm[14] = 0; ccc.str.tw.tm[15] = 0; ccc.str.tw.tm[16] = 0; ccc.str.tw.tm[17] = 0; ccc.str.tw.tm[19] = 0; ccc.str.tw.tm[20] = 0; ccc.str.tw.tm[21] = 0; ccc.str.tw.tm[22] = 0; ccc.str.tw.tm[23] = 0;
        
          /* console.log('keyClick :: js-reshare :: ' + ccc.str.tw.tm[18]); */

                  if ( ccc.str.tw.tm[18] === 0 ) {
                       ccc.str.tw.tm[20] = 1; /* share * create flag enabled */
                       /* console.log('keyClick :: js-reshare :: share * create tweet flag enabled'); */
                  } else {
                       ccc.str.tw.tm[21] = 1; /* share * create with media flag enabled */
                       /* console.log('keyClick :: js-reshare :: share * create tweet with media flag enabled'); */
                  }
            
           /* refavor( retweet, favor, replyto, replytomedia, ccmail, share, sharetomedia, trash, carbon ) */
            
           refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

           /* console.log('keyClick :: js-reshare :: ccc.str.tw.tm[20] :' + ccc.str.tw.tm[20] + ' ccc.str.tw.tm[21]: ' + ccc.str.tw.tm[21]); */
           /* console.log('keyClick :: js-reshare :: user screen name : ' +  ccc.str.tw.usr.screen_name); */ 
           /* console.log('keyClick :: js-reshare :: user email : ' + base64_decode(ccc.str.tw.usr.ccmail0)); */
           /* console.log('keyClick :: js-reshare :: user email : ' + ccc.str.tw.usr.ccuser ); */
                    
    break;

    case (tagclass === 'instore'):
          if (ccc.str.istw['ckoff'] === 0 ) {
           /* console.log('keyClick :: instore :: instore'); */
    
           set_css_id('cc', 'display', 'none');
           set_css_id('in', 'display', 'block');

           set_css_id('topbar', 'backgroundColor', '#FFFFFF');
           
           set_css_id('topbar', 'borderBottomColor', '#CCD6DD');

           set_css_class('nav', 'color', '#66757f');

           clear_css_class('create-data', 'create');
           set_html_id('create-data', '<span class="create icon icon-quill icon--large icon-nibble-right" id="create"></span><span class="create text" id="create-txt">create</span>' );

           clear_css_class('fromuser_img', 'me');
           add_css_class('fromuser_img', 'account');

           clear_css_class('profile_img', 'me');
           add_css_class('profile_img', 'account');
           add_css_class('profile_img', 'icon');
           add_css_class('profile_img', 'avatar-topbar');
           add_css_class('profile_img', 'size32'); 

           clear_css_class('me', 'me');
           add_css_class('me', 'textin');

           set_html_id('toptiptime-data', '<span class="instore icon icon-left icon-play_left icon--large" id="instore" style="color: rgb(17, 17, 17);"></span>' );

           if (visible_css_id('sdn_cc') ) { set_css_id('sdn_cc', 'display', 'none'); set_css_id('sdn', 'display', 'block'); }   

           set_css_class('icon-play_left', 'color', '#111111');
           set_css_class('icon-play_right', 'color', '');
           set_css_class('ccnumber', 'color', '');

           if ( ccc.str.tw.mi[0] > 0 ) {} else { set_css_class('icon-ccc_large', 'color', '#484848');set_doc_title('crowdcc'); }
         
     

           set_css_background('#F5F8FA');
           if (get_css_id('sdn', 'display', 'none')) {  /* console.log('keyClick :: instore :: sdn was display none'); */ set_css_id('sdn_cc','display','none');set_css_id('sdn','display','block');set_css_class('sdn_more','display','block'); } 
          
           if ( sessionStorage.length === 0 ) {
                msgnil('inzero');
           }
          }
    break;

    case (tagclass === 'ccstore'):
          if (ccc.str.istw['ckoff'] === 0 ) {
           
           /* close account menu if open */
           set_css_id('accfrm', 'display', 'none');
           set_css_id('account', 'display','none');
           
           /* console.log('keyClick :: ccstore :: ccstore'); */

           set_css_id('cc', 'display', 'block');
           set_css_id('in', 'display', 'none');
 
           set_css_id('topbar', 'backgroundColor', '#292E35');
          
           set_css_id('topbar', 'borderBottomColor', '#292E35');
           
           set_css_class('nav', 'color', '#E5EAF1');

           add_css_class('create-data', 'ccdrk');
           set_html_id('create-data', '<span class="create ccdrk icon icon-quill icon--large icon-nibble-right" id="create"></span><span class="create ccdrk text" id="create-txt">create</span>' );

           clear_css_class('fromuser_img', 'me');
           add_css_class('fromuser_img', 'ccdrk');
           add_css_class('fromuser_img', 'account');

           clear_css_class('profile_img', 'me');
           add_css_class('profile_img', 'ccdrk');
           add_css_class('profile_img', 'account');
           add_css_class('profile_img', 'icon');
           add_css_class('profile_img', 'avatar-topbar');
           add_css_class('profile_img', 'size32'); 

           clear_css_class('me', 'me');
           add_css_class('me', 'ccdrk');
           add_css_class('me', 'textin');

           add_css_class('instore', 'ccdrk');

           clear_css_class('sdn_cc', 'mre');
           add_css_class('sdn_cc', 'mredrk');

           if (visible_css_id('sdn') ) { set_css_id('sdn', 'display', 'none'); set_css_id('sdn_cc', 'display', 'block'); }   

           set_css_class('icon-play_left', 'color', '#C0C0C0');
           set_css_class('icon-play_right', 'color', '#626E82');
           set_css_class('ccnumber', 'color', '#111111');

           if ( ccc.str.tw.mi[0] > 0 ) {} else { set_css_class('icon-ccc_large','color','#D8DEE5');set_doc_title('crowdcc'); }


           set_css_background('#F5F8FA');

            if (ccc.str.tw.cc.length > 0) {

             switch (true) {

               case (typeof ccc.str.tw.cp[0] === 'undefined'):

               case (ccc.str.tw.cp[0] === 0):
                   
                     read_ccin_obj(10, 'cclength');
               break;

               case (ccc.str.tw.cp[0] > 0):
                  
                     read_ccin_obj(ccc.str.tw.cc.length, 'cclength');
               break;
             }
          
             set_css_id('cc', 'display', 'block');
             if (get_css_id('sdn_cc', 'display', 'none')) {  /* console.log('keyClick :: ccstore :: sdn_cc was display none'); */ set_css_id('sdn','display','none');set_css_id('sdn_cc','display','block');set_css_class('sdn_cc_more','display','block'); }   

            } else {
              /* ccc.str.tw.cc.length === 0 * nothing to see here */
              msgnil('cczero');
            }   
          }
    break;

    case (tagclass === 'delete_cc'):
         if (ccc.str.istw['ckoff'] === 0 ) {
          /* pause * timer */
          pausecc();
         
          /*  need to ask user are you sure you wish to delete (trash) this record ! */
          
          /* console.log('keyClick :: delete_cc :: we are in safe delete from the copy store!'); */
          
          ccc.str.istw['trshcc'] = JSON.stringify( event.target.attributes['data-modal'].value ).replace(/\"/g,'').split(/[. ]+/).pop();
          
          /* console.log('keyClick :: delete_cc :: ccc.str.istw[trshcc] :' + ccc.str.istw['trshcc']); */

          ccc.str.istw['trshid'] = JSON.stringify( event.target.attributes['data-id'].value ).replace(/\"/g,'');

          var a = document.getElementById( ccc.str.istw['trshid']+'_txt').getElementsByTagName("a");
     
          for (var i = 0; i < a.length; i++) {
               var tmplnk = a[i];
               tmplnk.style.background = '#DB3939';
               tmplnk.style.opacity = '0.92';
               tmplnk.style.color = '#FEFFF1';
          }

          set_css_class( ccc.str.istw['trshid'] + '_src', 'background', '#DB3939');
          set_css_class( ccc.str.istw['trshid'] + '_src', 'opacity', '0.92');
          
          set_css_class( ccc.str.istw['trshid'] + '_src', 'color', '#FEFFF1');

          set_css_class( ccc.str.istw['trshid'] + '_del', 'color', '#DB3939');
          set_css_class( ccc.str.istw['trshid'] + '_sha', 'color', '#DB3939');
          set_css_class( ccc.str.istw['trshid'] + '_ion', 'opacity', '0.95');

          ccc.str.istw['ckoff'] = 1;

          set_css_id('notice-cd', 'display', 'block');

          a = null; tmplnk = null; i = null;

          if ( ccc.str.tw.usr.ccuser === 'ccn') {

               set_css_id('noticebar-close-flix','display','block');
               set_html_id('notice-cd', '<span class="massage">Confirm email <span style="white-space: nowrap;">address registered</span>  <span style="white-space: nowrap;">with us to delete</span> this crowdcc copy</span>');
          
          } else {

               set_css_id('noticebar-close-fixon','display','block');
               set_html_id('notice-cd', '<span class="message">Are you sure you want to delete this crowdcc copy?</span><a class="trash-button wiggle">yes</a>');
          }

          msgtmr_('open');
    
         } else {
          message_('message_close', '');
         }
    break;

    case (tagclass === 'share_cc'):
          
          if (ccc.str.istw['ckoff'] === 0 ) {
              _share( event.target.attributes['data-modal'].value );
          }
          
    break;

    case (tagclass === 'share'):   
          
              _share( event.target.attributes['data-modal'].value );
         
          
          /* default modal option ... */
    case (tagclass === 'modal-retweet'):

          if ( visible_css_class('semantic-content') ) {

            if (ccc.str.tw.tm[7] != 7) {
                ccc.str.tw.tm[7] = 0;

            /* console.log('keyClick :: modal-retweet :: ' + get_css_id('modal-favor', 'color', 'rgb(61, 179, 229)') ); */

         
            switch (true) {

              case (get_css_id('modal-favor', 'color', 'rgb(61, 179, 229)') ):
                    set_css_class('modal-reply', 'color', '#B4B4B4');
                    set_css_class('modal-email', 'color', '#B4B4B4');
              break;

              case (get_css_id('modal-retweet', 'color', 'rgb(61, 179, 229)') ):
                    set_css_class('modal-retweet', 'color', '#B4B4B4');
                    set_css_class('modal-favor', 'color', '#B4B4B4');
                    set_css_class('modal-email', 'color', '#B4B4B4');
              break;
    
              case (get_css_id('modal-retweet', 'color', 'rgb(17, 17, 17)') ):
                    set_css_class('modal-favor', 'color', '#B4B4B4');
                    set_css_class('modal-reply', 'color', '#B4B4B4');
                    set_css_class('modal-email', 'color', '#B4B4B4');
              break;
              
            }

            set_css_id('retweet-tweet-dialog-header', 'display', 'block');
            set_css_id('reply-tweet-dialog-header', 'display', 'none');
            set_css_id('mail-tweet-dialog-header', 'display', 'none');
            set_css_id('create-tweet-dialog-header', 'display', 'none');

          
            set_css_class('modal-retweet', 'color', '#111111');
            
            set_css_class('retweet-form', 'display', 'block');
            set_css_class('reply-form', 'display', 'none');
            set_css_class('email-form', 'display', 'none');

            document.getElementById('share-retweet-form').getElementsByClassName('twitter-text')[0].style.cursor = 'pointer';
            document.getElementById('share-retweet-form').getElementsByClassName('urlprofilelink')[0].setAttribute('href', ccc.str.tw.tm[1]);
            document.getElementById('share-retweet-form').getElementsByClassName('urlprofilelink')[0].setAttribute('title', ccc.str.tw.tm[2]);
            document.getElementById('share-retweet-form').getElementsByClassName('urlprofilelink')[0].innerHTML = ccc.str.tw.tm[3];
            document.getElementById('js-share-retweettweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('a')[0].setAttribute('href', ccc.str.tw.tm[4]);
            document.getElementById('js-share-retweettweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('img')[0].setAttribute('src', ccc.str.tw.tm[5]);

           
            /* console.log('keyClick :: modal-retweet :: ccc.str.tw.tm[30]:' + ccc.str.tw.tm[30]); */
            /* console.log('keyClick :: modal-retweet ::  ccc.str.tw.tm[6]:' + ccc.str.tw.tm[6]);  */

            /* set_attrib_id( ccc.str.tw.tm[30], 'contenteditable', 'false'); */
            document.getElementById('share-retweet-form').getElementsByClassName('twitter-txt')[0].setAttribute('contenteditable', 'false');
           
            set_css_id('js-share-retweettweet', 'borderColor', '#FFFFFF');
            set_css_class('reply-footer', 'display', 'none');
            set_css_class('favor-footer', 'display', 'none');
            set_css_class('retweet-footer', 'display', 'block');
            set_css_class('share-footer', 'display', 'none');
            set_css_class('email-footer', 'display', 'none');

        
       
            set_html_id('retweet-button','Retweet');
            ccc.str.tw.tm[14] = 0; /* retweet flag set * reset */ 
          
            }

          }
    break;

    case (tagclass === 'modal-reply'):
          if (ccc.str.tw.tm[7] != 7) {
              ccc.str.tw.tm[7] = 3;
          /* console.log('keyClick :: modal-reply :: modal-reply has been clicked!'); */

          switch (true) {

            case (get_css_id('modal-favor', 'color', 'rgb(17, 17, 17)') ):
                  set_css_class('modal-favor', 'color', '#B4B4B4');

            case (get_css_id('modal-email', 'color', 'rgb(213, 25, 30)') ):
            case (get_css_id('modal-email', 'color', 'rgb(61, 179, 229)') ):
            case (get_css_id('modal-email', 'color', 'rgb(17, 17, 17)') ):
                  set_css_class('modal-email', 'color', '#B4B4B4');
            break;

            case (get_css_id('modal-retweet', 'color', 'rgb(61, 179, 229)') ):
            case (get_css_id('modal-favor', 'color', 'rgb(61, 179, 229)') ):

            break;

            case (get_css_id('modal-retweet', 'color', 'rgb(17, 17, 17)') ):
                  set_css_class('modal-retweet', 'color', '#B4B4B4');
            break;
           
          }

          set_css_id('retweet-tweet-dialog-header', 'display', 'none');
          set_css_id('reply-tweet-dialog-header', 'display', 'block');
          set_css_id('mail-tweet-dialog-header', 'display', 'none');
          set_css_id('create-tweet-dialog-header', 'display', 'none');

          set_css_class('modal-reply', 'color', '#111111');

          set_css_class('reply-form', 'display', 'block');
          set_css_class('retweet-form', 'display', 'none');
          set_css_class('email-form', 'display', 'none');

          set_html_id('js-share-replytweet', ccc.str.tw.tm[0] );

          set_css_class('share-items', 'display', 'none');

          document.getElementById('share-reply-form').getElementsByClassName('twitter-text')[0].style.cursor = 'pointer';
          document.getElementById('share-reply-form').getElementsByClassName('urlprofilelink')[0].setAttribute('href', ccc.str.tw.tm[1]);
          document.getElementById('share-reply-form').getElementsByClassName('urlprofilelink')[0].setAttribute('title', ccc.str.tw.tm[2]);
          document.getElementById('share-reply-form').getElementsByClassName('urlprofilelink')[0].innerHTML = ccc.str.tw.tm[3];
          
          document.getElementById('js-share-replytweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('a')[0].setAttribute('href', ccc.str.tw.tm[4]);
          document.getElementById('js-share-replytweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('img')[0].setAttribute('src', ccc.str.tw.tm[5]);

          document.getElementById('js-reply-new').getElementsByClassName('twitter-pic')[0].getElementsByTagName('a')[0].setAttribute('href', 'http://twitter.com/' + ccc.str.tw.usr.screen_name);
          document.getElementById('js-reply-new').getElementsByClassName('twitter-pic')[0].getElementsByTagName('img')[0].setAttribute('src', ccc.str.tw.usr.profile_image_url);

        
          /* set_html_class(  ccc.str.tw.tm[30] , ccc.str.tw.tm[6] ); // why ? :: will update all twitter.txt class instance with target content? */
          /* console.log('keyClick :: modal-reply :: ccc.str.tw.tm[30]:' + ccc.str.tw.tm[30]); */
          /* console.log('keyClick :: modal-reply :: ccc.str.tw.tm[6]:' + ccc.str.tw.tm[6]);   */

          /* set_attrib_id( ccc.str.tw.tm[30], 'contenteditable', 'false'); */
          document.getElementById('share-reply-form').getElementsByClassName('twitter-txt')[0].setAttribute('contenteditable', 'false');

          set_css_id('js-share-replytweet', 'borderColor', '#FFFFFF');
          set_css_class('retweet-footer', 'display', 'none');
          set_css_class('favor-footer', 'display', 'none');
          set_css_class('reply-footer', 'display', 'block');
          set_css_class('share-footer', 'display', 'none');
          set_css_class('email-footer', 'display', 'none');

          /* set_css_class('close-action', 'opacity', 0.2); chenge this from opacity to add & remove class */
          set_html_id('reply-button','Reply');
          /* set_prop_id('reply-button', 'disabled'); */

          set_html_id('js-srctxt-reply', '@' + ccc.str.tw.tm[3] + '&nbsp;');
     
          document.getElementById('js-reply_countdown').innerHTML = addzero(( 140 - parseInt( document.getElementById('js-srctxt-reply').innerHTML.length )),3);

          var content = document.getElementById('js-srctxt-reply');
          var chara = (document.getElementById('js-srctxt-reply').innerHTML.length) - 5; // character at which to place caret
          var sel = '';
          content.focus();
          if (document.selection) {sel = document.selection.createRange(); sel.moveStart('character', chara); sel.select();
          } else { sel = window.getSelection(); sel.collapse(content.firstChild, chara);}

          content = null; chara = null; sel = null;

          set_css_id('modal-reply','color','#111111');
          set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
          
          ccc.str.tw.tm[16] = 0; /* reply flag set * reset */
          ccc.str.tw.tm[17] = 0; /* reply flag set with media * reset */


          }
    break;

    case (tagid === 'reply-button'):
          /* check reply */
          /* console.log('keyClick :: reply-button :: reply button pressed!'); */
          
          /* pre-amble * flag set * re-set */
          ccc.str.tw.tm[14] = 0; ccc.str.tw.tm[15] = 0; ccc.str.tw.tm[17] = 0; ccc.str.tw.tm[19] = 0; ccc.str.tw.tm[20] = 0; ccc.str.tw.tm[21] = 0; ccc.str.tw.tm[22] = 0; ccc.str.tw.tm[23] = 0;

          /* refavor( retweet, favor, replyto, replytomedia, ccmail, share, sharetomedia, trash, carbon ) */
 
          switch (true) {

            case (ccc.str.tw.tm[16] === 1):
                  /* ready to reply to tweet */
                  if ( ccc.str.tw.tm[18] === 0 ) {
                       /* console.log('keyClick :: reply-button :: reply to tweet with no media!'); */
                  
                       refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
                  
                  } else {
                       /* console.log(ccc.str.tw.tm[18]); */
                       /* console.log('keyClick :: reply-button :: reply to tweet media!'); */
                  
                       ccc.str.tw.tm[16] = 0; ccc.str.tw.tm[17] = 1;
                  
                       refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
              
                  }

            break;

            case (ccc.str.tw.tm[16] === 0):
                  /* nothing to do * reset flags */
                  set_css_id('modal-reply','color','#111111');
                  set_css_id('btn-gray','backgroundColor','#9eabb6');
                  ccc.str.tw.tm[17] = 0; /* reply flag set with media * reset */
            break;

          }
                    
    break;

    case (tagid === 'email-button'):
          /* check ccmail */
          /* console.log('keyClick :: email-button :: email button pressed!'); */

          /* pre-amble * flag set * re-set */
          ccc.str.tw.tm[14] = 0; ccc.str.tw.tm[15] = 0; ccc.str.tw.tm[16] = 0; ccc.str.tw.tm[17] = 0; ccc.str.tw.tm[20] = 0; ccc.str.tw.tm[21] = 0; ccc.str.tw.tm[22] = 0; ccc.str.tw.tm[23] = 0;
        
          switch (true) {

            case (ccc.str.tw.tm[19] === 1):
                   /* ready to ccmail tweet */
                   refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);
            break;

            case (ccc.str.tw.tm[19] === 0):
                   /* nothing to do * reset flags */
                   set_css_id('modal-email','color','#111111');
                   set_css_id('btn-gray','backgroundColor','#9eabb6');
                   ccc.str.tw.tm[19] = 0; /* ccmail flag set * reset */
            break;

          }
    break;

    case (tagclass === 'trash-button'):
          /* need to ask user are you sure you wish to delete (trash) this record ! */
          /* console.log('keyClick :: trash-button :: we are in trash button!');    */

          ccc.str.tw.tm[14] = 0; /* retweet */ 
          ccc.str.tw.tm[15] = 0; /* favor   */
          ccc.str.tw.tm[16] = 0; /* reply   */ 
          ccc.str.tw.tm[17] = 0; /* reply with media */
          ccc.str.tw.tm[18] = 0; /* reply with media canvas */
          ccc.str.tw.tm[19] = 0; /* ccmail */

          ccc.str.tw.tm[20] = 0; /* share / create tweet */
          ccc.str.tw.tm[21] = 0; /* share / create tweet with media */
          ccc.str.tw.tm[22] = 1; /* trash  copy store tweet */
          ccc.str.tw.tm[23] = 0; /* carbon copy store tweet */

          refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

    break;

    case (tagclass === 'modal-favor'):
          if (ccc.str.tw.tm[7] != 7) {
              ccc.str.tw.tm[7] = 4;

          /* console.log('keyClick :: modal-favor :: ' + get_css_id('modal-retweet', 'color', 'rgb(61, 179, 229)') ); */      
          
          /* ccc.str.tw.tm[11] = 0; /* mouseover : mouseout diabled */
      
          switch (true) {

            case (get_css_id('modal-retweet', 'color', 'rgb(61, 179, 229)') ):
                  set_css_class('modal-reply', 'color', '#B4B4B4');
                  set_css_class('modal-email', 'color', '#B4B4B4');
            break;

            case (get_css_id('modal-favor', 'color', 'rgb(61, 179, 229)') ):
                  set_css_class('modal-reply', 'color', '#B4B4B4');
                  set_css_class('modal-email', 'color', '#B4B4B4');
                  set_css_class('modal-retweet', 'color', '#B4B4B4');
            break;

            case (get_css_id('modal-favor', 'color', 'rgb(17, 17, 17)') ):
                  set_css_class('modal-retweet', 'color', '#B4B4B4');
                  set_css_class('modal-reply', 'color', '#B4B4B4');
                  set_css_class('modal-email', 'color', '#B4B4B4');
            break;
          
          }

          set_css_id('retweet-tweet-dialog-header', 'display', 'block');
          set_css_id('reply-tweet-dialog-header', 'display', 'none');

          set_css_class('modal-favor', 'color', '#111111');
          set_css_class('retweet-form', 'display', 'block');

          set_css_class('reply-form', 'display', 'none');
          set_css_class('email-form', 'display', 'none');
  
  
          set_attrib_class( ccc.str.tw.tm[30] , 'contenteditable', 'false');
        
          set_css_class('favor-footer', 'display', 'block');
          set_css_class('retweet-footer', 'display', 'none');
          set_css_class('reply-footer', 'display', 'none');
          set_css_class('email-footer', 'display', 'none');
       
          /* set_css_class('close-action', 'opacity', 1); */
          set_html_id('favor-button','Favor');
          ccc.str.tw.tm[15] = 0; /* favor flag set / reset */ 

          }
    break;

    case (tagclass === 'modal-carbon-count'):
          if (ccc.str.tw.tm[7] != 7) {
              ccc.str.tw.tm[7] = 1;

          set_css_class('modal-carbon-count', 'color', '#111111');
          set_css_class('modal-email', 'color', '#B4B4B4');
          set_css_class('modal-retweet', 'color', '#B4B4B4');
          set_css_class('modal-favor', 'color', '#B4B4B4');

          set_css_class('retweet-form', 'display', 'block');
          set_css_class('email-form', 'display', 'none');
          set_css_id('js-share-retweettweet', 'borderColor', '#CCCCCC');

          document.getElementById('share-retweet-form').getElementsByClassName('twitter-text')[0].style.cursor = 'text';

          if (typeof document.getElementById('js-share-retweettweet').getElementsByClassName('rttwitter-pic')[0] !== 'undefined') {
          
            document.getElementById('js-share-retweettweet').getElementsByClassName('rttwitter-pic')[0].style.display = 'none';         
            document.getElementById('js-share-retweettweet').getElementsByClassName('rtprofilelink')[0].style.display = 'none';
            document.getElementById('js-share-retweettweet').getElementsByClassName('rtarrow')[0].style.display = 'none';
            document.getElementById('js-share-retweettweet').getElementsByClassName('tweet-time')[0].style.display = 'none';

          }
         
          document.getElementById('js-share-retweettweet').getElementsByClassName('tweetprofilelink')[0].getElementsByTagName('a')[0].setAttribute('href', 'http://twitter.com/'+ ccc.str.tw.usr.screen_name );
          document.getElementById('js-share-retweettweet').getElementsByClassName('tweetprofilelink')[0].getElementsByTagName('a')[0].setAttribute('title', ccc.str.tw.usr.screen_name );
          document.getElementById('js-share-retweettweet').getElementsByClassName('tweetprofilelink')[0].getElementsByTagName('a')[0].innerHTML = ccc.str.tw.usr.screen_name;
          document.getElementById('js-share-retweettweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('a')[0].setAttribute('href', 'http://twitter.com/'+ ccc.str.tw.usr.screen_name );
          document.getElementById('js-share-retweettweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('img')[0].setAttribute('src', ccc.str.tw.usr.profile_image_url );
 
          set_attrib_class( ccc.str.tw.tm[30] , 'contenteditable', 'false');
       
          document.getElementById('js-countdown').innerHTML = addzero(( 140 - parseInt(  document.getElementById('js-share-retweettweet').getElementsByClassName('twitter-txt')[0].innerHTML.length )),3);

          switch (true) {
            case (limit < 141):
            case (limit == tagtxtlen): 
       

                  console.log('ccc.str.tw.tm[30]: ' + document.getElementById('js-share-retweettweet').getElementsByClassName( ccc.str.tw.tm[30] )[0].innerHTML );

                  if( !(/\s+$/.test( (document.getElementById('js-share-retweettweet').getElementsByClassName( ccc.str.tw.tm[30] )[0])  )) ) {

            
                     var el = (document.getElementById('js-share-retweettweet').getElementsByClassName( ccc.str.tw.tm[30] )[0]);

                     el.innerHTML = el.innerHTML + '&nbsp;';
                     /* console.log('keyClick :: modal-carbon-count :: ' + el.innerHTML); */
                  }
            break;
          }

          set_css_class('retweet-footer', 'display', 'block');
          set_css_class('share-footer', 'display', 'none');
          set_css_class('email-footer', 'display', 'none');

  
          set_css_class( ccc.str.tw.tm[30], 'focus', 'focus');

          document.getElementsByClassName('send-action')[0].innerHTML = 'Retweet';

          }
    break;
    case (tagclass === 'modal-email'):
          if (ccc.str.tw.usr.ccuser === 'ccc') {

           if (ccc.str.tw.tm[7] != 7) {
               ccc.str.tw.tm[7] = 2;

           switch (true) {

            case (get_css_id('modal-favor', 'color', 'rgb(17, 17, 17)') ):
                  set_css_class('modal-favor', 'color', '#B4B4B4');

            case (get_css_id('modal-reply', 'color', 'rgb(61, 167, 242)') ):
            case (get_css_id('modal-reply', 'color', 'rgb(213, 25, 30)') ):
         
            case (get_css_id('modal-reply', 'color', 'rgb(17, 17, 17)') ):
                  set_css_class('modal-reply', 'color', '#B4B4B4');
            break;

            case (get_css_id('modal-retweet', 'color', 'rgb(61, 179, 229)') ):
            case (get_css_id('modal-favor', 'color', 'rgb(61, 179, 229)') ):

            break;

            case (get_css_id('modal-retweet', 'color', 'rgb(17, 17, 17)') ):
                  set_css_class('modal-retweet', 'color', '#B4B4B4');
            break;
           
           }

          set_css_class('modal-email', 'color', '#111111');

          set_css_id('retweet-tweet-dialog-header', 'display', 'none');
          set_css_id('reply-tweet-dialog-header', 'display', 'none');
          set_css_id('mail-tweet-dialog-header', 'display', 'block');
          set_css_id('create-tweet-dialog-header', 'display', 'none');
                  
          set_css_class('retweet-form', 'display', 'none');
          set_css_class('reply-form', 'display', 'none');
          set_css_class('email-form', 'display', 'block');

          set_css_class('text-input', 'borderColor', '#CCCCCC');
          set_html_id('js-share-email', ccc.str.tw.tm[0]);
          set_css_class('twitter-actions', 'display', 'none');

          set_css_class('retweet-footer', 'display', 'none');
          set_css_class('favor-footer', 'display', 'none');
          set_css_class('reply-footer', 'display', 'none');
          set_css_class('share-footer', 'display', 'none');
          set_css_class('email-footer', 'display', 'block');

          console.log('we are trying to clear the text!');

          /* set from email field */
          set_html_id('email-frm', ccc.str.tw.usr.name + ' (twitter)');

          document.getElementById('email-to').value = '';
          document.getElementById('js-srctxt-mail').innerHTML = '';
          document.getElementById('js-mail_countdown').innerHTML = addzero(( 140 - parseInt( document.getElementById('js-srctxt-mail').innerHTML.length )),3);
          /* set_css_id('js-srctxt-mail', 'focus', 'focus'); */

          set_css_id('email-to','focus','focus');
          set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
          set_css_id('email-button','backgroundColor','#9eabb6');
          set_html_id('email-button','Email');
        
          ccc.str.tw.tm[19] = 0; /* email flag set / reset */ 
          }
         }
    break;

    case (tagclass === 'add-img'):
    case (tagid == 'fileinput'):

          ccc.str.tw.tm[17] = 0;
          handlefile('fileinput', 'img-place');
          set_css_id('js-srctxt-new', 'focus', 'focus');
    break;

    case (tagid === 'reply-fileinput'):

          ccc.str.tw.tm[17] = 0;
          handlefile('reply-fileinput','reply-img-place');
          set_css_id('js-srctxt-reply', 'focus', 'focus');

    break;

    case (tagclass === 'ccc'):
          if (typeof ( event.target.attributes['data-modal'] ) !== 'undefined' && ( event.target.attributes['data-modal'] ) !== null) {
          /* console.log('keyClick :: ccc :: we are carbon button'); */
            
            /* console.log('keyClick :: ccc :: ccc.str.tw.usr.cclimit :' + ccc.str.tw.usr.cclimit); */
            /* console.log('keyClick :: ccc :: ccc.str.tw.usr.ccspace :' + ccc.str.tw.usr.ccspace); */

            /* client side check first for ccstore limit */
            if (ccc.str.tw.usr.ccspace < ccc.str.tw.usr.cclimit) {
          
              /* pause * timer */
              pausecc();

              ccc.str.tw.tm[14] = 0; /* retweet */ 
              ccc.str.tw.tm[15] = 0; /* favor   */
              ccc.str.tw.tm[16] = 0; /* reply   */ 
              ccc.str.tw.tm[17] = 0; /* reply with media */
              ccc.str.tw.tm[18] = 0; /* reply with media canvas */
              ccc.str.tw.tm[19] = 0; /* ccmail */

              ccc.str.tw.tm[20] = 0; /* share / create tweet */
              ccc.str.tw.tm[21] = 0; /* share / create tweet with media */
              ccc.str.tw.tm[22] = 0; /* trash  copy store tweet */
              ccc.str.tw.tm[23] = JSON.stringify( event.target.attributes['data-modal'].value ).replace(/\"/g,''); /* carbon copy store tweet */

              /* refavor( retweet, favor, replyto, replytomedia, ccmail, share, sharetomedia, trash, carbon ) */
              refavor(ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23]);

              /* console.log('keyClick :: ccc :: ' + JSON.stringify( event.target.attributes['data-modal'].value ).replace(/\"/g,'') ); */
              /* console.log('keyClick :: ccc :: ccc.str.tw.tm[23] length: ' + ccc.str.tw.tm[23].length); */

            } else {
              /* pause * timer */
              pausecc();
              /* console.log('keyClick :: ccc :: tweet store limit has been reached'); */
              /* turn off acccount menu */
              set_css_id('accfrm', 'display', 'none');
              set_css_id('account', 'display','none');
              message_('message_close', 'lie');
              /* set_css_id('noticebar-close-flix', 'display', 'block'); already set in get_in_ccn() */
              /* msgtmr_('close'); */

            }
          }
    break;   
   
    case (tagclass == 'js-cancel-modal'):
    case (tagclass == 'modal-close'):
          /* here we re-set any arrays or objs */
          /* console.log('keyClick :: modal-close :: * resume * timer'); */

          /* resume * timer */
          // resumecc();

          message_('modal_close','');

    break;

    case (tagclass === 'ui-button-text'):
    case (tagclass === 'ui-dialog-noticebar-close'):
          message_('message_close', '');
          _click('reset');
    break;

    case (tagclass === 'sdn_cw_more'):
    case (tagid === 'sdn_cw'):
          /* one imac public page of frequent (duplicate count) content, no requirment to multi-page */
          /* console.log('keyClick :: sdn_cw_more :: we have reached end!'); */
          
          message_('message_close', 'cw');
          msgtmr_('close');
    break;

    case (tagclass === 'sdn_cc_more'):
    case (tagid === 'sdn_cc'):
          /* console.log('keyClick :: sdn_cc_more'); */
         
          /* client request for crowdcc API content */

          /* console.log('keyClick :: sdn_cc_more :: ccc.str.tw.ct[0] : ' + ccc.str.tw.ct[0]); */
          /* console.log('keyClick :: sdn_cc_more :: ccc.str.tw.cc.length : ' + ccc.str.tw.cc.length); */

          // if (typeof ccc.str.tw.ct[0] == 'undefined') { ccc.str.tw.ct[0] = (Number( ccc.str.tw.cc.length ) - 1) };

            /* console.log('keyClick :: sdn_cc_more :: page depth :' + ccc.str.tw.cp[0]); */
            /* console.log('keyClick :: sdn_cc_more :: page req :' +  ccc.str.tw.cg[0]);  */

            switch (true) {

              case ( ccc.str.tw.ct[0] === (Number( ccc.str.tw.cc.length )) ):
                     /* console.log('keyClick :: sdn_cc :: more content request'); */
                     /* console.log(ccc.str.tw.cp[0]); */

                     if (trash_store('cc')) {
                         load_cc_more();
                     }

              break;

              case ( (ccc.str.tw.ct[0] - (Number( ccc.str.tw.cc.length ) - 1)) < ccc.str.tw.cg[0] ):  // (ccc.str.tw.ct[0] 37) - (Number( ccc.str.tw.cc.length ) - 1) == 30 remainder 7 which is less than 10
                      ccc.str.tw.cp[0] = (Number( ccc.str.tw.cc.length ) - 1) + (ccc.str.tw.ct[0] - (Number( ccc.str.tw.cc.length ) - 1));
                      /* console.log('keyClick :: sdn_cc :: 1st case state: '+ccc.str.tw.cp[0]); */
                      load_cc_local();
              break;

              case ( (ccc.str.tw.ct[0] - (Number( ccc.str.tw.cc.length ) - 1)) > ccc.str.tw.cg[0] ):
                     ccc.str.tw.cp[0] = ((Number( ccc.str.tw.cc.length ) - 1) + ccc.str.tw.cg[0] );   // set pagebak at (20 + 10) 30 ...
                     /* console.log('keyClick :: sdn_cc :: 2nd case state: '+ccc.str.tw.cp[0]); */
                     load_cc_local();
              break;
  
            }

    break;

    case (tagclass === 'sdn_more'):
    case (tagid === 'sdn'):
          /* console.log('keyClick :: sdn'); */

          /* console.log('keyClick :: sdn :: ccc.str.tw.ts[0]: ' + ccc.str.tw.ts[0]); */
          /* console.log('keyClick :: sdn :: ccc.str.tw.st[0]: ' + ccc.str.tw.st[0]); */
          /* console.log('keyClick :: sdn :: ccc.str.tw.sc[0]: ' + ccc.str.tw.sc[0]); */
          /* console.log('keyClick :: sdn :: ccc.str.tw.pg[0]: ' + ccc.str.tw.pg[0]); */

          if ( ccc.str.tw.ts[0] === 0) {

           /* more_bounce(); replaced set_css_class sdn_more and sdn_wait */  
           /* set_css_class('sdn_more', 'display', 'none');set_css_class('sdn_wait', 'display', 'block'); */

           more_bounce('sdn');
   
           /* client request for twitter API content */
    
           /* server request for content ( debounced and throttled )
              console.log( clk load more de-bounced detected');
              debounce ( throttle (gettwsrbkobj(),100), 500); */

           ccc.str.tw.pg[0] = 10;

           if (typeof ccc.str.tw.st[0] === 'undefined') { ccc.str.tw.st[0] = (Number( JSON.stringify(Object.keys(sessionStorage)).split(',').length ) - 1) };

             switch (true) {

               case ( ccc.str.tw.st[0] === ccc.str.tw.sc[0] ):
                     /* console.log('keyClick :: sdn :: more content request'); */
                     
                     var mre = '';
  
                     if ( ccc.str.tw.ts[0] !== 3 ) {
                          mre = load_more();
                     } else {
                          /* display please cc wait error message */
                          /* console.log('keyClick :: sdn :: just catching up with your request please try now'); */
                          more_wait('cc');
                     }

                     if (mre === false) {
                         /* display please api wait error message */
                         /* console.log('keyClick :: sdn :: don\'t panic, paging older content will re-enabled shortly'); */
                         more_wait('api');
                     }

                     mre = null;
               break;

               case ( (ccc.str.tw.st[0] - ccc.str.tw.sc[0]) < ccc.str.tw.pg[0] ):  /* (ccc.str.tw.st[0] 37) - now ccc.str.tw.sc[0] == 30 remainder 7 which is less than 10 */
                     ccc.str.tw.sp[0] = ccc.str.tw.sc[0] + (ccc.str.tw.st[0] - ccc.str.tw.sc[0]);
                     /* console.log('keyClick :: sdn :: 1st case state: '+ccc.str.tw.sp[0]); */
                     load_local();
               break;

               case ( (ccc.str.tw.st[0] - ccc.str.tw.sc[0]) > ccc.str.tw.pg[0] ):
                     ccc.str.tw.sp[0] = (ccc.str.tw.sc[0] + ccc.str.tw.pg[0] );    /* set pagebak at (20 + 10) 30 ... */
                     /* console.log('keyClick :: sdn :: 2nd case state: '+ccc.str.tw.sp[0]); */
                     load_local();
               break;
             }
          
          } else {

            message_('message_close', 'pae');
            msgtmr_('close');
     
          }
            
    break;

    case (tagid === 'more'):
          /* console.log('keyClick :: more :: more'); */

          if (ccc.str.tw.mi[0] !== 0) {
            switch (true) {
              case (ccc.str.tw.sc[0] !== 0):
                    readtwin_obj(ccc.str.tw.sc[0]);
              break;
              case (ccc.str.tw.sc[0] == 0):
                    readtwin_obj(20);      /* current content default */
              break;
            }
          }

    break;

    /* from signin.js document.onclick = function keyClick(event) :: start */

    case(tagclass === 'fromuser_img'):
         /* console.log('keyClick :: fromuser image :: click'); */
    case(tagclass === 'fromuser'):
         /* console.log('keyClick :: fromuser :: click'); */
    case(tagclass === 'fromuser_profile'):
         /* console.log('keyClick :: fromuser profile :: click'); */
            
        switch (true) {        
            case (ccc.str.tw.usr.ccuser === 'soc'):
                 /* console.log('keyClick :: fromuser_profile :: social only, no user email or password found, set up a limited crowdcc menu with a crowdcc signup'); */
                 _toggle_menu('soc');
            break;
            case (ccc.str.tw.usr.ccuser === 'ccn'):
                 /* console.log('keyClick :: fromuser_profile :: ccn free account, user email, password found, but email not confirmed restricted crowdcc account, with confirm email nag button'); */
                 _toggle_menu('ccn');
            break;
            case (ccc.str.tw.usr.ccuser === 'ccc'):
                 /*  console.log('keyClick :: fromuser_profile :: ccc free account, user email, passsword found, set up a crowdcc free menu (no crowdcc signin req)'); */
                 _toggle_menu('ccc');
            break;
            case (typeof ccc.str.tw.usr.ccuser !== 'undefined'):
                  set_css_id('frmsig', 'height', '54px');
                  set_css_class('twitter-signbtn', 'display', 'none');
                  toggle_css('frmsig', 'display');
                  set_css_id('username', 'focus', 'focus');
            break;

        }

    break;

    case (tagclass === 'signin-tweet'):
          /* console.log('keyClick :: signin-tweet :: signin twitter!'); */
          /* toggle spinner function * ccc_toploader(); */
          _signin('twitter');
    break;

    case (tagclass === 'signin-feedback'):
          /* console.log('keyClick :: signin-feedback :: signin feedback * open blog for account pricing'); */
          window.open('plans/feedback','_blank');
    break;

    case (tagclass === 'btn_upgrade_acc'):
          /* console.log('keyClick :: btn_upgrade_acc :: account upgrade * crowdcc store limit reached, for more storage, features'); */
          /* close crowdcc store reached message * display settings * account options */
          msgcls();
          acc_space();
    break;

    case (tagclass === 'standard-crowdcc-btn'):
          /* console.log('keyClick :: standard-crowdcc-btn :: signin feedback * open blog for account pricing'); */
          window.open('plans/feedback','_blank');
    break;

    case (tagclass === 'pro-crowdcc-btn'):
          /* console.log('keyClick :: pro-crowdcc-btn :: signin feedback * open blog for account pricing'); */
          window.open('plans/feedback','_blank');
    break;

    case (tagclass === 'signin-crowdcc'):
          /* console.log('keyClick :: signin crowdcc!'); */
          _signin('crowdcc_process');
    break;

    case (tagclass === 'signin-crowdcc-btn'):
          /* console.log('keyClick :: signin crowdcc btn :: _signin'); */
          /* console.log('keyClick :: going to signin'); */

          _signin('crowdcc_validate');
           
   
    break;

    case (tagclass === 'signup-ccc'):
          /* pause * timer */
          pausecc();
          
          /* console.log('keyClick :: signup-ccc :: complete your account * timer pause * modal display!'); */

          _click('reset');

          set_css_id('modal-inner','display','none');
          set_css_id('modal-text', 'display', 'block');

          set_css_id('accfrm', 'display', 'none');
       
          set_css_id('modal-email', 'display', 'block');
          set_css_class('ui-dialog-titlebar-close', 'display', 'block'); 

          /* clear_css_class('acc-status', 'menu-select'); */
          set_css_class('ui-dialog', 'display', 'block');
          set_css_class('ui-dialog-content', 'display', 'block');
          set_css_class('ui-dialog-welcome-content','display', 'none');
          set_css_class('ui-dialog-email-content','display', 'block');
          if ( ccc.str.tw.usr.ccfollow ) { set_prop_id('modal-social-follow-complete-checkbox', 'disabled') }
    
    break;

    case (tagid === 'eirm-ccc'):

          /* final validation of new password * before we continue * pvalidate(pc, id, id_errmsg, passmsg, gid_errmsg) */

          switch (true) {

            case (get_html_id('eirm_new_validate_msg').indexOf('verify data') === -1):
                  pvalidate( trimspace(eirm_retype.value) , 'eirm_retype', 'eirm_new_msg', 'verification required', 'eirm_new_validate_msg' );
                  /* console.log('keyClick :: eirm-ccc :: we think password is problem!'); */
            break;

            case (get_html_id('eirm_new_validate_msg').indexOf('verify data') !== -1):
                  /* double check the list of untrusted characters ! */

                  ccc.sig.isvalid['vp1']=/[*|\":<>[\]{}`\\()';!@&$]/;

                  if (ccc.sig.isvalid['vp1'].test(trimspace(eirm_retype.value))) {
                      
                      eirm_retype.value = '';
                      document.getElementById("eirm-ccc").disabled=true;
                      set_html_id('eirm_new_msg', '');
                      set_html_id('eirm_new_validate_msg', '');
                      set_css_id('eirm_new_msg', '');
                      set_css_id('eirm_retype', 'borderColor', '#cccccc');
                      ccc.sig.isvalid['vp1'] = 0;
                  
                  } else {

                      /* console.log('keyClick :: eirm-ccc :: we think password is problem!'); */
                      ccc.sig.isvalid['vp1'] = 1;
                      _eirmin(trimspace(eirm_retype.value));
                  }    
            break;
          }

    break;

    case (tagid === 'new-ccc'):

          /* final validation of new password * before we continue * pvalidate(pc, id, id_errmsg, passmsg, gid_errmsg) */

          switch (true) {

            case (trimspace(pcode_new.value) !== trimspace(pcode_retype.value)):
                  pvalidate( trimspace(pcode_new.value) , 'pcode_new', 'pcode_new_msg', 'new passwords do not match', 'pcode_new_validate_msg' );
            break;

            case (get_html_id('pcode_new_validate_msg').indexOf('verify data') === -1):
                  pvalidate( trimspace(pcode_new.value) , 'pcode_new', 'pcode_new_msg', 'verification required', 'pcode_new_validate_msg' );
                  /* console.log('keyClick :: new-ccc :: we think password is problem!'); */
            break;

            case (get_html_id('pcode_new_validate_msg').indexOf('verify data') !== -1):
                  /* double check the list of untrusted characters ! */

                  ccc.sig.isvalid['vp1']=/[*|\":<>[\]{}`\\()';!@&$]/;

                  if (ccc.sig.isvalid['vp1'].test(trimspace(pcode_new.value))) {
                      
                      pcode_new.value = ''; pcode_retype.value = '';
                      document.getElementById("new-ccc").disabled=true;
                      set_html_id('pcode_new_msg', '');
                      set_html_id('pcode_new_validate_msg', '');
                      set_css_id('pcode_new_msg', '');
                      set_css_id('pcode_new', 'borderColor', '#cccccc');
                      set_css_id('pcode_retype', 'borderColor', '#cccccc');
                      ccc.sig.isvalid['vp1'] = 0;
                      ccc.sig.isvalid['vp2'] = 0;

                  } else {

                      /* console.log('keyClick :: new-ccc :: we think password is good!'); */
                      ccc.sig.isvalid['vp1'] = 1;
                      ccc.sig.isvalid['vp2'] = 1;
                      _pinsin(base64_encode(trimspace(pcode_retype.value)));
                  }    
            break;
          }

    break;

    case (tagid === 'reset-ccc'):

          /* console.log('keyClick :: reset-ccc :: we are reset'); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[ecode] ==' + base64_decode(ccc.sig.iset['ecode'])); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[pcode] ==' + base64_decode(ccc.sig.iset['pcode'])); */         
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[sncode] ==' + ccc.sig.iset['sncode']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[uscode] ==' + ccc.sig.iset['uscode']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[pltfrm] ==' + ccc.sig.iset['pltfrm']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[browsr] ==' + ccc.sig.iset['browsr']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[timezo] ==' + ccc.sig.iset['timezo']); */
          /* console.log(pcode_reset.value); */
          
          ccc.sig.iset['pcode'] = trimspace(pcode_reset.value);
          ccc.sig.iset['pcode'] = base64_encode(ccc.sig.iset['pcode']); 

          ccc.sig.iset['ecode']  = ccc.sig.iset['pcode'];
          ccc.sig.iset['encode'] = ccc.sig.iset['pcode'];

          ccc.sig.iset['sncode'] = ccc.sig.iset['uscode'] = '_reset';
          ccc.sig.isvalid['valid'] = true;

          /* console.log('keyClick :: reset-ccc :: we are returning to _signin()'); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[ecode] ==' + base64_decode(ccc.sig.iset['ecode'])); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[pcode] ==' + base64_decode(ccc.sig.iset['pcode'])); */             
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[sncode] ==' + ccc.sig.iset['sncode']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[uscode] ==' + ccc.sig.iset['uscode']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[pltfrm] ==' + ccc.sig.iset['pltfrm']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[browsr] ==' + ccc.sig.iset['browsr']); */
          /* console.log('keyClick :: reset-ccc :: ccc.sig.iset[timezo] ==' + ccc.sig.iset['timezo']); */

          _signin('rst_usr');
    
    break;

    case (tagclass === 'acc-space'):
          /* pause * timer */
          pausecc();

          /* console.log('keyClick :: acc-space :: we are account space'); */

          acc_space();

    break;

    case (tagclass === 'upgrade-ccc'):

          set_css_class('ui-dialog', 'display', 'block');
          set_css_id('modal-email', 'display', 'block');
          clear_css_class('acc-status', 'menu-select' );
          set_css_class('ui-dialog-account-upgrade', 'display', 'block');
          if ( ccc.str.tw.usr.ccfollow ) { set_prop_id('modal-social-follow-upgrade-checkbox', 'disabled') }
               /* console.log('keyClick :: upgrade-ccc :: we are twitter follow flag set'); */

          _click();

    break;

    case (tagclass === 'acc-settings'):
          /* pause * timer */
          pausecc();
       
         if (! has_css_class('acc-settings','no-hover') ) {

         /* console.log('keyClick :: acc-settings :: we are account settings'); */

         ccc.sig.isvalid['enablekey'] = 1;

         /* console.log('keyClick :: acc-settings :: ccc.sig.iset[ucode] == ' + base64_decode(ccc.sig.iset['ucode'])); */
         /* console.log('keyClick :: acc-settings :: ccc.sig.iset[encode] == ' + base64_decode(ccc.sig.iset['encode'])); */

         set_css_id('modal-text', 'display', 'block');
         set_css_id('modal-inner', 'display', 'none');

         set_css_class('ui-dialog', 'display', 'block');
         set_css_class('ui-dialog-titlebar-close', 'display', 'block');
         set_css_class('ui-dialog-content', 'display', 'block');

         set_css_id('modal-email', 'display', 'block');
         set_css_class('ui-dialog-account-settings', 'display', 'block');

         set_css_id('accfrm', 'display', 'none');

         set_attrib_id('uname_account_settings', 'placeholder',  base64_decode(ccc.sig.iset['ucode']) );
         set_attrib_id('email_account_settings', 'placeholder',  base64_decode(ccc.sig.iset['encode']) );
         
         set_prop_id('account-settings-save-changes', 'disabled');
         set_prop_id('passcode_account_settings', 'enabled');
         set_prop_id('email_account_settings', 'enabled');
         set_css_id('email_account_settings', 'focus', 'focus');

         switch (true) {

          case (typeof ccc.str.tw.usr.ccuser !== 'undefined' && elem !== null):
          case (ccc.str.tw.usr.ccuser === 'ccc'):
                 set_html_id('ecode_account_settings_msg', '');
                 set_css_class('passcode_row', 'display', 'block');
                 set_css_id('account-settings', 'color', '#FFFFFF');
                 clear_attrib_id('passcode_account_settings_verify', 'style');
          break;

         }      
      
         _click(); 

         }
    break;
     
    case (tagclass === 'reset_ecode'):
          /* pause * timer */
          pausecc();

          ccc.sig.isvalid['enablekey'] = 1;

          if (! has_css_class('account_status','no-hover') ) {

          switch (true) {
            case ( visible_css_class('error_pc5de') ):
                   /* ecode_account_settings_msg */
            break;
          }

          set_css_id('frmsig', 'display', 'none');

          set_css_class(' ui-dialog-titlebar-close', 'display', 'block');

          set_css_id('modal-text', 'display', 'block');
          set_css_id('modal-inner', 'display', 'none');

          set_css_class('ui-dialog', 'display', 'block');
          set_css_class('ui-dialog-content', 'display', 'block');
           
     

          set_css_class('ui-dialog-account-settings', 'display', 'block');
          set_css_class('passcode_row', 'display', 'none');
          set_attrib_id('uname_account_settings', 'placeholder', base64_decode(ccc.sig.iset['ucode']) );
          set_attrib_id('email_account_settings', 'placeholder', base64_decode(ccc.sig.iset['encode']) );
          set_prop_id('account-settings-save-changes', 'disabled');
          set_css_id('email_account_settings', 'focus', 'focus');

          /* console.log('keyClick :: reset_ecode :: reset_ecode'); */

          document.getElementById("account-settings-save-changes").disabled=false;

          }

    break;

    case (tagclass === 'confirm_ecode'):
          _click('reset');

          /* console.log('keyClick :: confirm_ecode :: btn_confirm_ecode'); */
          /* console.log('keyClick :: confirm_ecode :: ... send confirmation email'); */

          _confirm('_cco');    
    break;

    case (tagid === 'account-settings-save-changes'):
          /* pause timer */
          pausecc();

          /* re-test :: ccc.sig.isvalid['ve'] === 1 */
          ccc.sig.isvalid['ve'] = evalidate( trimspace(email_account_settings.value) , 'email_account_settings', 'ecode_account_settings_msg', 'account_settings_msg' );
          /* console.log('keyClick :: account-settings-save-changes :: re-test :: ccc.sig.isvalid[ ve ] === 1'); */
          
          ccc.sig.isvalid['enablekey'] = 0;

     /*
       email update, check valid and also not same as current email ...
       password change, check valid, and the new passwords (new & verify) match ... email check, is the email update field, ready for verify ?    
       case($.trim(passcode_account_settings_new.value) == $.trim(passcode_account_settings_verify.value)):
     */

     /* console.log('keyClick :: account-settings-save-changes :: save changes'); */
     /* console.log('keyClick :: account-settings-save-changes :: email acc prop : ' + get_prop_id( 'email_account_settings', 'enabled' )); */
     /* console.log('keyClick :: account-settings-save-changes :: passcode acc prop : ' + get_prop_id( 'passcode_account_settings', 'enabled' )); */

        switch(true) {

            case( get_prop_id( 'email_account_settings', 'enabled' )):

                /* console.log('keyClick :: account-settings-save-changes :: we are email account'); */

                if( ccc.sig.isvalid['ve'] === 1 ) { 
                    /* console.log('keyClick :: account-settings-save-changes :: only email updated ... ready to follow email forgot / update procedure'); */
                    set_css_class('ui-dialog-titlebar-close', 'display', 'none');
                    set_css_class('ui-dialog-content', 'display', 'none');
                    set_css_class('ui-dialog-account-settings', 'display', 'none');
                    set_css_id('modal-text', 'display', 'none');

                    isbarone('fix');

                    set_css_class('notices', 'display', 'block');

                    /* console.log('keyClick :: account-settings-save-changes :: email_account_settings.value |' + email_account_settings.value + ' |');     */
                    /* console.log('keyClick :: account-settings-save-changes :: ccc.str.tw.usr.ccmail0 | ' + base64_decode(ccc.str.tw.usr.ccmail0) + ' |'); */
                    /* console.log('keyClick :: account-settings-save-changes :: ccc.str.tw.usr.ccmail1 | ' + base64_decode(ccc.str.tw.usr.ccmail1) + ' |'); */
                    /* console.log('keyClick :: account-settings-save-changes :: ccc.str.tw.usr.ccmail2 | ' + base64_decode(ccc.str.tw.usr.ccmail2) + ' |'); */
                    /* console.log('keyClick :: account-settings-save-changes :: ccc.str.tw.usr.ccname * before update | ' + base64_decode(ccc.str.tw.usr.ccname) + ' |'); */
                    /* console.log('keyClick :: account-settings-save-changes :: ccc.str.tw.usr.ccname | ' + base64_decode(ccc.str.tw.usr.ccname) + ' |');   */

                    /* optional: test email update against current registered email, if same prevent update ! */
                    // if (email_account_settings.value === base64_decode(ccc.str.tw.usr.ccmail0)) { console.log('you know this is the same as! :' + email_account_settings.value); return true;}

                    /* console.log('keyClick :: account-settings-save-changes :: ccc.str.tw.usr.ccname * before update | ' + base64_decode(ccc.str.tw.usr.ccname) + ' |');   */
                    /* console.log('keyClick :: account-settings-save-changes :: ccc.sig.iset[encode] * before update | ' + base64_decode( ccc.sig.iset['encode'] ) + ' |'); */

                    ccc.str.tw.usr.ccmail1 = base64_encode(email_account_settings.value);
                    ccc.sig.iset['encode'] = base64_encode(email_account_settings.value);

                    /* console.log('keyClick :: account-settings-save-changes :: ccc.str.tw.usr.ccname * after update | ' + base64_decode(ccc.str.tw.usr.ccname) + ' |');   */
                    /* console.log('keyClick :: account-settings-save-changes :: ccc.sig.iset[encode] * after update | ' + base64_decode( ccc.sig.iset['encode'] ) + ' |'); */

                    set_css_class('ui-dialog', 'display', 'none');

                    if (base64_decode(ccc.str.tw.usr.ccmail1) === '_ccu') {
                        set_html_class('messagetxt', 'Confirm email ' + base64_decode(ccc.sig.iset['encode']) + '. ');
                    } else {
                        set_html_class('messagetxt', 'Confirm new email ' + base64_decode(ccc.sig.iset['encode']) + '. ');
                    }

                    set_css_class('btn_confirm_ecode', 'color', '#FFFFFF');
                    set_css_id('frmsig', 'display', 'none');
                    set_css_class('confirm', 'display', 'block');

                    /* enable nag bar * error_pc5de * notices */

                    set_css_class('error_pc5de', 'display', 'block');
                    set_css_class('ui-dialog-noticebar-close', 'display', 'none');
                    set_css_id('in','margin-top','-15px');
                    set_css_id('cc','margin-top','-15px');
               
                    /* enable nag bar * error_pc5de * notices */

                    /* console.log('keyClick :: account-settings-save-changes :: email account settings * non confirmed email address msg bar!'); */
                    
                    clear_css_class('account-settings', 'no-hover');
                    clear_css_class('acc-settings', 'noclass');
                    set_css_id('account-settings', 'color', 'grey');
                    ccc.str.tw.usr.ccuser = 'ccn';

                    /* restart timer */
                    resumecc();

                } 

            case( get_prop_id( 'passcode_account_settings', 'enabled' )):

                  /* console.log('keyClick :: account-settings-save-changes :: we are in passcode account'); */

                  if(ccc.sig.isvalid['vp1'] + ccc.sig.isvalid['vp2'] === 2) {              

                      if( trimspace(passcode_account_settings.value) == trimspace(passcode_account_settings_verify.value) ) {
                          
                          /* console.log('keyClick :: account-settings-save-changes :: passwords changed ready to follow forgotten / update password procedure'); */
                          set_css_class('ui-dialog-titlebar-close', 'display', 'none');
                          set_css_class('ui-dialog-content', 'display', 'none');
                          
                          /* console.log('keyClick :: account-settings-save-changes :: we are reset'); */
                          /* console.log(pcode_reset.value); */

                          ccc.sig.iset['pcode'] = trimspace(pcode_reset.value);
                          /* console.log('keyClick :: account-settings-save-changes :: we are returning to _signin() ...'); */
                          _signin('rst_usr');
                
                      }          
                  }
            break;

    }
                 
    break;

    /* case (tagid === 'account-help'): */
    case (tagclass === 'acc-help'):
          /* pause * timer */
          pausecc();

          /* console.log('keyClick :: acc-help :: we are help'); */

          set_css_id('accfrm', 'display', 'none');
          set_css_id('modal-inner', 'display', 'none'); 
          set_css_id('modal-text','display','block');
          set_css_id('ui-dialog', 'display', 'block');
      
          set_css_class('ui-dialog-content', 'display', 'block'); 
          set_css_class('ui-dialog-account-help', 'display', 'block');
              
          set_css_class('ui-dialog-titlebar-close', 'display', 'block');

          if ( ccc.str.tw.usr.ccfollow ) { set_prop_id('modal-social-follow-help-checkbox', 'disabled') }
          /* console.log('keyClick :: acc-help :: twitter follow flag set'); */
    break;


    case (tagid === 'account-signout'):
          /* console.log('keyClick :: account-signout :: account signout called!'); */
          _signout();
    break;

    case (tagclass === 'signon'):
   
          /* console.log('keyClick :: signon :: we are signon'); */
 
          /* re-set the default */

          if (ccc.sig.iset['uscode'] !== 'new_usr') {

              _ccleanup() /* clear up any open error bar dialogs */

              /* console.log('keyClick :: signon :: new_usr'); */
             
            switch (true) {

              case ( visible_css_id('accaller') ):
                     set_css_id('sigfrm', 'display', 'none');
                     set_css_id('accaller', 'display', 'none');
                      
                     /* console.log('keyClick :: signon :: watch started @'); */
              break;
              case ( !visible_css_id('accaller') ):
                     set_css_id('sigfrm', 'display', 'block');
                     set_css_id('accaller', 'display', 'block');

                     /* console.log('keyClick :: signon :: watch stopped @'); */
              break;
            }
             
          return false;

         }

    break;


    case (tagclass === 'signin-menu'):
          /* console.log('keyClick :: signin-menu :: toggle_signin(start)'); */
          toggle_signin('start');

    break;

    case (tagclass === 'btn_reset_pcode'):
          /* console.log('keyClick :: btn_reset_pcode :: pcode error button selected'); */

         ccc.sig.isvalid['enablekey'] = 1;

         toggle_signin('reset');

         set_prop_id('reset-ccc', 'disabled');
         
         /* console.log('keyClick :: btn_reset_pcode :: notices display none'); */
         
         set_css_class('notices', 'display', 'none');
         set_css_id('frmsig', 'display', 'none');
         set_css_class('ui-dialog-account-settings', 'display', 'none');
         set_css_class('ui-dialog-password-new', 'display', 'none');
         set_css_class('ui-dialog-social-content', 'display', 'none');
         set_css_class('ui-dialog-titlebar-close', 'display', 'block');

         set_css_id('modal-text', 'display', 'block');
         set_css_id('modal-inner', 'display', 'none');

         set_css_class('ui-dialog', 'display', 'block');
         set_css_class('ui-dialog-content', 'display', 'block');

  
         
         set_html_val('pcode_reset', '');
         set_css_class('ui-dialog-password-content', 'display', 'block');
         set_css_class('error_pc0de', 'display', 'none');
         set_css_class('error_pc1de', 'display', 'none');
         set_css_id('pcode_reset', 'focus', 'focus');
    
    break;

    case (tagid === 'signin_radio_1'): /* new to crowdcc */

          set_html_class('signin-button span', 'Sign Up to Crowdcc');
          set_html_class('ecode_row label', 'Your current email');
          set_html_class('pcode_row label', 'Create a password');

          set_css_id('email', 'borderColor', '');
          set_css_id('passcode', 'borderColor', '');
          set_html_val('email', '');
          set_html_val('passcode','');

          set_css_id('ecode_msg','color','#B8C2DC');
              
          document.getElementsByName('email')[0].placeholder='current email address';
    
          set_html_id('ecode_msg', '... must be valid format');

          set_css_id('pcode_msg','visibility','visible');
          set_css_id('pcode_forgot','visibility','hidden');

          set_css_id('pcode_msg','color', '#B8C2CA');
          set_html_id('pcode_msg', '... must be 6 - 20 characters');

          set_css_id('email', 'focus', 'focus');

          /* console.log('keyClick :: signin_radio_1 :: new to crowdcc'); */
    
    break;

    case (tagid === 'signin_radio_2'): /* yes i have a password */

          set_html_class('signin-button span', 'Sign In to Crowdcc');
          set_html_class('ecode_row label', 'Your username or email');
          set_html_class('pcode_row label', 'Your password');

          set_css_id('email', 'borderColor', '');
          set_css_id('passcode', 'borderColor', '');
          set_html_val('email', '');
          set_html_val('passcode','');

          document.getElementsByName('email')[0].placeholder='twitter username';
          
          set_css_id('ecode_msg','color', '#B8C2CA');
       
          set_html_id('ecode_msg', '');

          set_css_id('pcode_msg','visibility','hidden');
          set_css_id('pcode_forgot','visibility','visible');

          set_css_id('email', 'focus', 'focus');

          /* console.log('keyClick :: signin_radio_2 :: we have password'); */
    
    break;

    case (tagid === 'btn_space_close'):
          ccc.sig.isvalid['enablekey'] = 0;
    
          
          /* @crowdccteam follow on twitter ... selected */
          if ( document.getElementById('modal-social-follow-space-checkbox').checked ) {
              
              /* console.log('keyClick :: btn_space_close'); */
              
              get_in_follow('inapp');
          }
          
          _click('reset');
          resumecc();
    break;

    case (tagid === 'btn_upgrade_close'):
          ccc.sig.isvalid['enablekey'] = 0;
          _ccleanup();

          set_css_id('frmsig','display','none');
          
          /* @crowdccHQ follow on twitter ... selected */
          if ( document.getElementById('modal-social-follow-upgrade-checkbox').checked ) {
              
              /* console.log('keyClick :: btn_upgrade_close'); */
              
              get_in_follow('inapp');
          }
          
          _click('reset');
    break;

    case (tagid === 'btn_help_close'):
          ccc.sig.isvalid['enablekey'] = 0;
       
          /* @crowdHQ follow on twitter ... selected */
          if ( document.getElementById('modal-social-follow-help-checkbox').checked ) {
              
              /* console.log('keyClick :: btn_help_close');  */

              get_in_follow('inapp');
          }
          
          _click('reset');
          resumecc();
    break;

    case (tagclass === 'ui-dialog-noticebar-close'):

          /* console.log('keyClick :: ui-dialog-noticebar-close'); */

          set_css_class('error', 'display', 'none');
          
          /* console.log('keyClick :: ui-dialog-noticebar-close :: notices display none'); */
          
          set_css_class('notices', 'display', 'none');
          tagclose = true;
     
    case (tagtxtlen === 1):
          /* 'x marks the spot' */
          ccc.sig.isvalid['enablekey'] = 0;
          /* console.log('keyClick :: ui-dialog-noticebar-close :: x'); */

        switch(true) {

         case ( visible_css_class('ui-dialog-account-help') ):
         case ( visible_css_class('ui-dialog-email-content') ):
         case ( visible_css_class('ui-dialog-account-space') ):
         case ( visible_css_class('ui-dialog-account-settings') ):

               set_css_id('modal-text', 'display', 'none'); 
               set_css_id('ui-dialog', 'display', 'none');
               set_css_class('ui-dialog-content', 'display', 'none');
               
               set_css_class('ui-dialog-account-help', 'display', 'none');
               set_css_class('ui-dialog-account-space', 'display', 'none');
               set_css_class('ui-dialog-email-content', 'display', 'none');
               set_css_class('ui-dialog-account-settings', 'display', 'none');

               set_css_class('ui-dialog-titlebar-close', 'display', 'none');

               /* clear down aborted * reset borders * change email + change password values */
               document.getElementById('email_account_settings').value = '';
               document.getElementById('email_account_settings').style.borderColor = '#cccccc';
               document.getElementById('passcode_account_settings').value = '';
               document.getElementById('passcode_account_settings').style.borderColor = '#cccccc';
               document.getElementById('passcode_account_settings').className = '';

               set_html_id('pcode_account_settings_msg', '');

               /* console.log('keyClick :: ui-dialog-noticebar-close :: close down ... space, settings, help ... restart timer'); */
               
               resumecc(); /* re-start api timer */

               return true;
         break;

         case ( visible_css_class('efim') ):

               /* console.log('keyClick :: ui-dialog-noticebar-close :: efim'); */

               set_css_class('efim', 'display', 'none');
               
               /* console.log('keyClick :: ui-dialog-noticebar-close :: efim :: notices display none'); */
               
               set_css_class('notices', 'display', 'none');
               set_css_id('signinbtn', 'display', 'none');
               
               _ccleanup();
               
               window.location.href = window.location.href;
          
               return true;
         break;

         case ( visible_css_class('pfim') ):

               /* console.log('keyClick :: ui-dialog-noticebar-close :: pfim'); */
               
               set_css_class('pfim', 'display', 'none');
               
               /* console.log('keyClick :: ui-dialog-noticebar-close :: pfim :: notices display none'); */
               
               set_css_class('notices', 'display', 'none');
               set_css_id('signinbtn', 'display', 'none');

               _ccleanup();
               
               window.location.href = window.location.href;
         
               return true;
         break;

         case ( visible_css_class('error_pc5de') ):
          
               /* console.log('keyClick :: ui-dialog-noticebar-close :: error_pc5de'); */
               /* ui-dialog-titlebar-close ... */

               switch (true) {

                case ( visible_css_class('ui-dialog-titlebar-close') ):

                      set_css_class('ui-dialog-account-space', 'display', 'none');
                      clear_css_class('account-space', 'noclass');
                      set_css_class('ui-dialog-account-upgrade', 'display', 'none');
                      clear_css_class('account-status', 'noclass');
                      set_css_class('ui-dialog-account-settings', 'display', 'none');
                      clear_css_class('account-settings', 'noclass');   
                      set_css_class('ui-dialog-account-help', 'display', 'none');
                      clear_css_class('account-help', 'noclass');  

                      _click('reset');

                      /* account space remove menu select ... */
                      /* console.log('keyClick :: ui-dialog-noticebar-close :: error_pc5de'); */
                break;

                case ( visible_css_class('ui-dialog-noticebar-close') ):

                      set_css_class('error', 'display', 'none');
                      set_css_class('confirm', 'display', 'none');
                      set_css_class('ui-dialog-noticebar-close', 'display', 'none');
                      set_css_class('ui-dialog-noticebar-close','z-index', '');

                      /* console.log('keyClick :: ui-dialog-noticebar-close :: error_pc5de close'); */
                break;

               }

               set_css_class('ui-dialog', 'display', 'none');
               set_css_id('signbtn', 'display', 'none');
               clear_css_class('acc-settings', 'noclass');
               set_css_id('frmsig', 'display', 'none');

               window.location.href = window.location.href;

               return true;
         break;

         case (ccc.str.tw.usr.ccuser === 'ccc'):
               /* console.log('keyClick :: ccc.str.tw.usr.ccuser :: ccc'); */

               set_css_id('account-settings', 'color', '#333333');

               set_css_class('notices', 'padding-top', '1px');
               set_css_class('ui-dialog-account-settings', 'display', 'none');
               set_css_class('ui-dialog', 'display', 'none');
               set_css_id('ccc', 'padding-top', '51px');
               set_css_id('pin', 'padding-top', '51px');
      
               set_html_val('email_account_settings', '');

               set_css_id('email_account_settings', 'borderColor', '');
               set_html_id('account_settings_msg', ''); 

               set_html_val('passcode_account_settings', ''); 
               set_css_id('passcode_account_settings', 'borderColor', '');

               set_html_val('passcode_account_settings_verify', '');
               
               set_css_id('passcode_account_settings_verify', 'borderColor', '');
               set_html_id('pcode_account_settings_msg', ''); 
               set_html_id('account_settings_msg', '');
               set_css_id('frmsig', 'display', 'none');  
               clear_css_class('account-space', 'noclass');
               set_css_class('ui-dialog-account-space', 'display', 'none');
               clear_css_class('account-status', 'noclass');
               set_css_class('ui-dialog-account-upgrade', 'display', 'none');
               clear_css_class('acc-settings', 'noclass');
               clear_css_class('account-help', 'noclass');
               set_css_class('ui-dialog-account-help', 'display', 'none');

               _click('reset');
               return true;
         break;

         case (ccc.str.tw.usr.ccuser === 'soc'):
               /* console.log('keyClick :: ccc.str.tw.usr.ccuser :: soc'); */
          
               set_css_id('signbtn', 'display', 'none');
               clear_css_class('account-space', 'noclass');
               set_css_class('ui-dialog-account-space', 'display', 'none');
               clear_css_class('account-status', 'noclass');
               set_css_class('ui-dialog-email-content', 'display', 'none');  

               _click('reset');
         break;

         case (typeof ccc.str.tw.usr.ccuser === 'undefined'):
               /* console.log('keyClick :: ccc.str.tw.usr.ccuser ===  undefined'); */
               /* close except sign in .... */
        
               set_css_id('signbtn', 'display', 'block');
               set_css_class('controls', 'display', 'block');

               set_html_id('pcode_reset_msg','');
               set_html_id('pcode_reset_validate_msg','');

               set_css_id('pcode_reset','borderColor','#BEBEBE');


         break;

         case ( visible_css_class('ui-dialog-password-content') ):

               /* console.log('keyClick :: ui-dialog-password-content'); */
               _signout();
         
         break;

         }

         /* console.log('keyClick :: tagclose === false');   */

         if (tagclose === true) {

         /* console.log('keyClick :: tagclose === true');    */

         /* console.log( visible_css_class('error_pc4de') ); */

             switch (true) {

              case ( visible_css_class('error_tamper') ):
                     /* console.log('keyClick :: we are error_tamper'); */
              case ( visible_css_class('error_ec0de') ):
                     /* console.log('keyClick :: we are error_ec0de'); */
              case ( visible_css_class('error_ec1de') ):
                     /* console.log('keyClick :: we are error_ec1de'); */
              case ( visible_css_class('psnd') ):
                     /* console.log('keyClick :: we are psnd'); */
              case ( visible_css_class('pfim') ):
                     /* console.log('keyClick :: we are pfim'); */
              case ( visible_css_class('esnd') ):
              case ( visible_css_class('efim') ):
                     /* console.log('keyClick :: we are exxx'); */
                   
                     _clear_sign();
                     _ccoff();
              break;
           
              case ( visible_css_class('error_ucode') ):
                     /* console.log('keyClick :: error_ucode !'); */
                    
              case ( visible_css_class('error_ec3de') ):
              case ( visible_css_class('error_pc4de') ):
              
                    _clear_sign();
                    /* console.log('keyClick :: end of close click switch!'); */
              break;
      
            }

          /* console.log('keyClick :: tagclose === false'); */

             tagclose = false;
         }

         /* ok now close down everything */
         /* console.log('keyClick :: notices display none'); */
         
         set_css_class('notices', 'display', 'none');
         set_css_class('ui-dialog', 'display', 'none');
         set_css_class('ui-dialog-noticebar-close', 'display', 'none');
         
         set_css_id('ccc_load', 'display', 'none');

         set_css_class('ui-dialog', 'display', 'none');
         set_css_class('error', 'display', 'none');
         set_css_class('psnd', 'display', 'none');

         /* finally close menu ....  */

         set_css_id('frmsig', 'display', 'none');

         /* test for other exit codes */
        
         switch(true) {

          case (ccc.sig.iset['uscode'] === '_err_tcode'):
                set_css_id('signbtn', 'display', 'block');
                set_prop_id('signbtn', 'enabled');
                set_css_id('frmsig', 'display', 'block');
          break;

         }

         _ccleanup();

         /* remove any passed tokens ... */

         var backlen = -1; 
         history.go(-backlen);
         window.history.replaceState('', 'login Twitter', 'http://'+ window.location.hostname );
      
    break;

    case (tagtxtlen > 2800):
          set_css_class('ui-dialog', 'display', 'none');
          set_html_id('frmsig', 'display', 'none');
          _click('reset');
    break;

    /* from signin.js document.onclick = function keyClick(event) :: end */
  
  }

  elem = null; tagclass= null; tagid = null; tagtxtlen = null; tagclass = null; tagtype = null; tagclose = null;

}


function acc_space() {

  /* calc user account type */
  var _state = '';
  var _cclimit = ccc.str.tw.usr.cclimit;
  var _ccspace = ccc.str.tw.usr.ccspace;

  set_css_id('bar-free','backgroundColor','#4488F6');
       
  /* used space * ccc.str.tw.usr.ccspace * account limit * ccc.str.tw.usr.cclimit */

  switch (true) {

    case ( ccc.str.tw.usr.ccuser === 'soc' ):
           set_html_id('_state-recommend','<span style="color:#1c1c2f;">Recommend upgrade :</span> register your current <a class="signup-ccc" data-nav="upgrade-account" style="text-decoration: underline; cursor: pointer; cursor: hand;">email address</a> and double your crowdcc copy storage to 20cc for free, also click <span style="background-color: #B9D4F5;">account feedback</span> to help us build crowdcc the way you want:<div class="upg_buttons"> <input id="btn_standard_close" class="standard-crowdcc-btn close-action send-action" type="submit" value="standard account feedback" style="width:106%;"> <br> &nbsp; <br> <input id="btn_pro_close" class="pro-crowdcc-btn close-action send-action btn-pad" type="submit" value="pro account feedback" style="width:106%"> </div>');
    break;
    case ( ccc.str.tw.usr.ccuser === 'ccn' ):
           set_html_id('_state-recommend','<span style="color:#1c1c2f;">Recommend upgrade :</span> complete the registration of your account and double your crowdcc copy storage to 20cc for free, also click <span style="background-color: #B9D4F5;">account feedback</span> to help us build crowdcc the way you want:<div class="upg_buttons"> <input id="btn_standard_close" class="standard-crowdcc-btn close-action send-action" type="submit" value="standard account feedback" style="width:106%;"> <br> &nbsp; <br> <input id="btn_pro_close" class="pro-crowdcc-btn close-action send-action btn-pad" type="submit" value="pro account feedback" style="width:106%"> </div>');
    break;
    case ( ccc.str.tw.usr.ccuser === 'ccc' ):
           /* set_html_id('_state-recommend','<span style="color:#1c1c2f;">Recommend upgrade :</span> to get more storage capacity, upgrade to a pay per month account:<div class="upg_buttons"> <input id="btn_standard_close" class="standard-crowdcc-btn close-action send-action" type="submit" value="standard account $6 per month&nbsp;" title="what standard account features would be useful? ... click here to visit our blog"> <br> &nbsp; <br> <input id="btn_pro_close" class="pro-crowdcc-btn close-action send-action btn-pad" type="submit" value="pro account $12 per month" title="what pro account features would be useful? ... click here to visit our blog"> </div>'); */
           set_html_id('_state-recommend','<span style="color:#1c1c2f;">Recommend upgrade :</span> to get more storage capacity, upgrade to a pay per month account:<div class="upg_buttons"> <input id="btn_standard_close" class="standard-crowdcc-btn close-action send-action" type="submit" value="standard account feedback" style="width:106%;"> <br> &nbsp; <br> <input id="btn_pro_close" class="pro-crowdcc-btn close-action send-action btn-pad" type="submit" value="pro account feedback" style="width:106%"> </div>');
    break;
  }

  /* for display purposes only * 15% represents the lowest value and 85% the corresponding highest value */
          
  switch (true) {

    case ( ccc.str.tw.usr.cclimit === 10 ):
           _state = 'lite 10cc';
           _cclimit = _cclimit * 10;
           _ccspace = _ccspace * 10;
    break;

    case ( ccc.str.tw.usr.cclimit === 20 ):
           _state = 'lite 20cc';
           _cclimit = _cclimit * 5;
           _ccspace = _ccspace * 5;
    break;

    case (ccc.str.tw.usr.cclimit === 100 ):
          _state = 'lite 100cc';
    break;

  }

  set_html_id('bar-used', ccc.str.tw.usr.ccspace + 'cc<br>used');
  set_html_id('bar-free', (ccc.str.tw.usr.cclimit - ccc.str.tw.usr.ccspace) + 'cc<br>free');

  /* console.log('acc_space :: _cclimit : ' + _cclimit); */
  /* console.log('acc_space :: _ccspace : ' + _ccspace); */

  switch (true) {

    case (_ccspace < 1):
          /* console.log('acc_space :: _ccspace < 1'); */
          set_html_id('bar-used', '&nbsp;<br>&nbsp;');
          set_html_id('bar-free', (ccc.str.tw.usr.cclimit - ccc.str.tw.usr.ccspace) + 'cc<br>free');
    break;

    case (_cclimit < 1):
          /* console.log('acc_space :: _cclimit < 1'); */
          set_html_id('bar-used', '&nbsp;<br>&nbsp;');
          set_html_id('bar-free', (ccc.str.tw.usr.cclimit - ccc.str.tw.usr.ccspace) + 'cc<br>free');
    break;

    case (_ccspace === _cclimit):
          /* console.log('acc_space :: _ccspace === _cclimit'); */
          set_html_id('bar-used', ccc.str.tw.usr.ccspace + 'cc<br>used');
          set_html_id('bar-free', '');
          set_css_id('bar-free','backgroundColor','#308FB7');
    break;

  }

  set_css_id('accfrm', 'display', 'none');
  set_css_id('modal-inner', 'display', 'none');
  set_css_id('modal-text','display','block');
  set_css_id('ui-dialog', 'display', 'block');
      
  set_css_class('ui-dialog-content', 'display', 'block'); 
  set_css_class('ui-dialog-account-space', 'display', 'block');
  set_css_class('ui-dialog-titlebar-close', 'display', 'block');
  document.getElementById('profile_img_space').setAttribute('src', ccc.str.tw.usr.profile_image_url);
  set_html_id('profile_space', ccc.str.tw.usr.screen_name );
  set_html_id('_state', _state );

  set_css_id('bar-used', 'width', _ccspace + '%');
  set_css_id('bar-free', 'width', (_cclimit - _ccspace) + '%');

  set_html_id('_state-center', '<span class="state-used">' + ccc.str.tw.usr.ccspace + 'cc</span>' + ' used ' + '<span class="state-icon icon-ccc_small icon"></span>' + '<span class="state-free">' + (ccc.str.tw.usr.cclimit - ccc.str.tw.usr.ccspace) + 'cc</span>' + ' free');

  if ( ccc.str.tw.usr.ccfollow ) { set_prop_id('modal-social-follow-space-checkbox', 'disabled') }
  /* console.log('acc_space :: twitter follow flag set'); */

  _state = null; _cclimit = null; _ccspace = null;

}


function instore() {

  /* console.log('instore() :: instore function'); */
    
  set_css_id('cc', 'display', 'none');
  set_css_id('in', 'display', 'block');
  set_css_id('topbar', 'backgroundColor', '#FFFFFF');
  set_css_id('topbar', 'borderBottomColor', '#CCD6DD');
  set_css_class('nav', 'color', '#66757f');
  clear_css_class('create-data', 'create');
  set_html_id('create-data', '<span class="create icon icon-quill icon--large icon-nibble-right" id="create"></span><span class="create text" id="create-txt">create</span>' );
  clear_css_class('fromuser_img', 'me');
  add_css_class('fromuser_img', 'account');

  clear_css_class('profile_img', 'me');
  add_css_class('profile_img', 'account');
  add_css_class('profile_img', 'icon');
  add_css_class('profile_img', 'avatar-topbar');
  add_css_class('profile_img', 'size32'); 

  clear_css_class('me', 'me');
  add_css_class('me', 'textin');

  set_html_id('toptiptime-data', '<span class="instore icon icon-left icon-play_left icon--large" id="instore" style="color: rgb(17, 17, 17);"></span>' );

  if (visible_css_id('sdn_cc') ) { set_css_id('sdn_cc', 'display', 'none'); set_css_id('sdn', 'display', 'block'); }   

  set_css_class('icon-play_left', 'color', '#111111');
  set_css_class('icon-play_right', 'color', '');
  set_css_class('ccnumber', 'color', '');

  /* if ( ccc.str.tw.mi[0] > 0 ) {} else {set_css_class('icon-ccc_large', 'color', '#484848');} */

  set_css_class('icon-ccc_large', 'color', '#484848');
         
  set_css_background('#F5F8FA');   

}


function reply() {

  var incount = '';
  var inhtml = document.getElementById('js-srctxt-reply').innerHTML;

  /* console.log('reply() :: inhtml:' + inhtml); */
  
  inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
  document.getElementById('js-reply_countdown').innerHTML = incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );

  switch (true) {
    case (incount === 140):
          set_css_id('modal-reply','color','#111111');
          set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
          ccc.str.tw.tm[20] = 0;
    break;
    case (incount < 0):
          set_css_id('modal-reply','color','#d40d12');
          set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
          ccc.str.tw.tm[20] = 0;
    break;
    case (incount < 11):
          set_css_id('js-reply_countdown', 'color', '#D40D12');
          set_css_id('modal-reply','color','#3da7f2');
          set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
          ccc.str.tw.tm[20] = 1;
    break;
    case (incount < 140):
          set_css_id('modal-reply','color','#3da7f2');
          set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
    case (incount > 10):
          set_css_id('js-reply_countdown', 'color', '#8899A6');  
    break;
  }
  
  incount = null; inhtml = null;
  clearTimeout( ccc.ccw.tidcs ); ccc.ccw.tidcs = null;
  resumecc(); 

}

function quill() {
          
  var incount = '';
  var inhtml = document.getElementById('js-srctxt-new').innerHTML;

  /* console.log('quill() :: inhtml:' + inhtml); */
  
  inhtml = inhtml.replace(/(<([^>]+)>)/ig,"");
  document.getElementById('js-new_countdown').innerHTML = incount = 140 - ccc.str.twttr.txt.getTweetLength( inhtml );

  switch (true) {
    case (incount === 140):
          set_css_id('modal-quill','color','#111111');
          set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
          ccc.str.tw.tm[20] = 0;
    break;
    case (incount < 0):
          set_css_id('modal-quill','color','#d40d12');
          set_css_class('btn-gray', 'backgroundColor', '#9eabb6');
          ccc.str.tw.tm[20] = 0;
    break;
    case (incount < 11):
          set_css_id('js-new_countdown', 'color', '#D40D12');
          set_css_id('modal-reply','color','#3da7f2');
          set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
          ccc.str.tw.tm[20] = 1;
    break;
    case (incount < 140):
          set_css_id('modal-quill','color','#3da7f2');
          set_css_class('btn-gray', 'backgroundColor', '#1c1c2f');
    case (incount > 10):
          set_css_id('js-new_countdown', 'color', '#8899A6');  
    break;
  }

  incount = null; inhtml = null;
  clearTimeout( ccc.ccw.tidcs ); ccc.ccw.tidcs = null; 
  resumecc();

}


document.onpaste = function keyPaste(event) {

  /* console.log('KeyPaste(event) :: onpaste'); */

  switch (true) {

    case (ccc.str.tw.tm[7] === 3):

          pausecc();

          ccc.ccw.tidcs = setTimeout( function() { reply() } , 50);

    break;
    
    case (ccc.str.tw.tm[7] === 7):

          pausecc();

          ccc.ccw.tidcs = setTimeout( function() { quill() } , 50);
                        
    break;

  }
}


function mre_sdn() {
   set_css_class('sdn_more', 'display', 'block'); set_css_class('sdn_wait', 'display', 'none');
   clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
}

function mre_sdn_cc() {
   /* console.log('mre_sdn_cc :: clearTimeout'); */
   set_css_class('sdn_cc_more', 'display', 'block'); set_css_class('sdn_cc_wait', 'display', 'none');
   clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
}

function mre_sdn_cw() {
   set_css_class('sdn_cw_more', 'display', 'block'); set_css_class('sdn_cw_wait', 'display', 'none');
   clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
}


function more_bounce(msg) {

  /* more_bounce * may require adjustment for any/all network delay issues */

  /* console.log('more_bounce :: msg'); */

  switch (msg) {

    case ('sdn'):

      /* wait status, busy waiting for more content */
      set_css_class('sdn_more', 'display', 'none'); set_css_class('sdn_wait', 'display', 'block');
      /* waiting timeout set due to network delays, reset wait status */
      
      ccc.ccw.tidcs = setTimeout( function() {  mre_sdn(); } , 5000);

    break;

    case ('sdn_cc'):

      /* wait status, busy waiting for more content */
      set_css_class('sdn_cc_more', 'display', 'none'); set_css_class('sdn_cc_wait', 'display', 'block');
      /* waiting timeout set due to network delays, reset wait status */

      /* console.log('more_bounce :: sdn_cc'); */
      
      ccc.ccw.tidcs = setTimeout( function() {  mre_sdn_cc(); } , 3000);
    
    break;

    case ('sdn_cw'):

      /* wait status, busy waiting for more content */
      set_css_class('sdn_cw_more', 'display', 'none'); set_css_class('sdn_cw_wait', 'display', 'block');
      /* waiting timeout set due to network delays, reset wait status */

      ccc.ccw.tidcs = setTimeout( function() {  mre_sdn_cw(); } , 5000);

    break;


  }
}

function more_wait(msg) {
  
  /* console.log('more_wait :: msg :: we are more wait irony!'); */

  switch (msg) {

    case ('api'):
          message_('message_close', 'api');
          msgtmr_('close');
          /* disable sdn_more icon and grey out color */
    break;

    case ('cc'):
          message_('message_close', 'cat');
          msgtmr_('close');
    break;

  }

  set_css_class('sdn_wait', 'display', 'none');
  set_css_class('sdn_more', 'display', 'block');

}

function _share( evt ) {

/* share function for in * cc * cw */

  if (typeof ( evt ) !== 'undefined' && ( evt ) !== null) {
      /* pause * timer */
      pausecc();

      /* console.log('_share :: evt :: we are _share'); */

      /* retweet * favor * reply * email * share/create : flags */
          
      ccc.str.tw.tm[14] = 0; /* retweet */ 
      ccc.str.tw.tm[15] = 0; /* favor   */
      ccc.str.tw.tm[16] = 0; /* reply   */ 
      ccc.str.tw.tm[17] = 0; /* reply with media */
      ccc.str.tw.tm[18] = 0; /* reply with media canvas */
      ccc.str.tw.tm[19] = 0; /* ccmail */

      ccc.str.tw.tm[20] = 0; /* share / create tweet */
      ccc.str.tw.tm[21] = 0; /* share / create tweet with media */
      ccc.str.tw.tm[22] = 0; /* trash  copy store media */
      ccc.str.tw.tm[23] = 0; /* carbon copy store tweet */

      /* ccc.str.tw.tm[30] = 'js-srctxt'; /* share_cc disabled */

      set_css_id('modal-inner','display','block');
      set_css_id('modal-text', 'display', 'none');
      set_css_id('ui-dialog', 'display', 'none');

      trashcanvas('reply-img-place', '50', '50');

      /* set mouse over flag : enable */
      
      ccc.str.istw['mouse'] = 1;
          
      /* console.log('_share :: evt :: mouseover and out enabled'); */

      set_css_id('retweet-tweet-dialog-header', 'display', 'block');
      set_css_id('reply-tweet-dialog-header', 'display', 'none');
      set_css_id('mail-tweet-dialog-header', 'display', 'none');
      set_css_id('create-tweet-dialog-header', 'display', 'none');

      set_css_class('share-form', 'display', 'none');
      set_css_class('modal-quill', 'display', 'none');
      set_css_class('modal-carbon-count', 'display', 'none');

      set_css_class('retweet-footer', 'display', 'block');
      set_css_class('favor-footer', 'display', 'none');
      set_css_class('reply-footer', 'display', 'none');
      set_css_class('share-footer', 'display', 'none');
      set_css_class('email-footer', 'display', 'none');

      set_css_id('modal-cancel','display','none');
      set_css_id('modal-cancel-table','display','block');

      set_css_class('modal-retweet', 'color', '#111111');
      set_css_class('modal-favor', 'color', '#B4B4B4');
      set_css_class('modal-reply', 'color', '#B4B4B4');
      set_css_class('modal-email', 'color', '#B4B4B4');

      set_css_class('modal-email', 'display', 'block');
      set_css_class('modal-favor', 'display', 'block');
      set_css_class('modal-reply', 'display', 'block');
      set_css_class('modal-retweet', 'display', 'block');
       
      set_css_class('semantic-content', 'display', 'block');

      /* prep dialog complete, switch, _frm who */

      /* console.log('_share :: evt :: ' + visible_css_id('in') ); */
      /* console.log('_share :: evt :: ' + visible_css_id('cc') ); */
      /* console.log('_share :: evt :: ' + visible_css_id('cw') ); */

      ccc.str.tw.tm[12] = (evt);

      var _frm = ''; 

      switch (true) {
        case( visible_css_id('in') ):
              _frm = 'in';
              /* console.log('_share :: evt :: ccc.str.tw.tm[12]: '+ ccc.str.tw.tm[12]); */
              
              ccc.str.tw.tm[12] = JSON.parse(sessionStorage[ ccc.str.tw.tm[12] ]).id_str;
              
              /* console.log('_share :: evt :: ccc.str.tw.tm[12]: ' + ccc.str.tw.tm[12]); */
        break;
        case( visible_css_id('cc') ):
              _frm = 'cc';
              
              /* console.log('_share :: evt :: ccc.str.tw.tm[12]: '+ ccc.str.tw.tm[12]); */
              
              ccc.str.tw.tm[12] = ccc.str.tw.tm[12].substring(ccc.str.tw.tm[12].length - 1);
              ccc.str.tw.tm[12] = ccc.str.tw.cc[ccc.str.tw.tm[12]].id_str;
              
              /* console.log('_share :: evt :: ccc.str.tw.tm[12]: ' + ccc.str.tw.tm[12]); */
        break;
        case( visible_css_id('cw') ):
              _frm = 'cw';
              
              /* console.log('_share :: evt :: ccc.str.tw.tm[12]: '+ ccc.str.tw.tm[12]); */
              
              ccc.str.tw.tm[12] = ccc.str.tw.tm[12].substring(ccc.str.tw.tm[12].length - 1);
              ccc.str.tw.tm[12] = ccc.str.tw.cw[ccc.str.tw.tm[12]].id_str;
              
              /* console.log('_share :: evt :: ccc.str.tw.tm[12]: ' + ccc.str.tw.tm[12]); */
        break;
          
      }

      /* ccc.str.tw.tm[12]: _cc.fi.1 | ccc.str.tw.tm[12]: 601318977122476032 | _frm : in | evt : "_cc.fi.1" */
      /* ccc.str.tw.tm[12]:  tw.cc.0 | ccc.str.tw.tm[12]: 601318977122476032 | _frm : cc | evt : "tw.cc.0"  */

      /* ccc.str.tw.tm[30] = 'js-srctxt';  /* share_cc disabled */
      ccc.str.tw.tm[30] = 'twitter-txt';   /* share_cc disabled */

      /* console.log( '_share :: evt :: _frm : ' + _frm ); */
      /* console.log( '_share :: evt ::  evt : ' + JSON.stringify( evt ).replace(/\./g,'').replace(/\"/g,'') ); */
    
      ccc.str.tw.tm[0] = document.getElementById(_frm).getElementsByClassName(''+JSON.stringify( evt ).replace(/\./g,'').replace(/\"/g,'')+'')[0].innerHTML;    
      /* console.log('_share :: evt :: ccc.str.tw.tm[0]: '+ ccc.str.tw.tm[0]); */

      set_html_id('js-share-retweettweet', ccc.str.tw.tm[0] );

      ccc.str.tw.tm[1] = document.getElementById('share-retweet-form').getElementsByClassName('urlprofilelink')[0].getAttribute('href');
      /* console.log('_share :: evt :: ccc.str.tw.tm[1]: '+ ccc.str.tw.tm[1]); */
   
      ccc.str.tw.tm[2] = document.getElementById('share-retweet-form').getElementsByClassName('urlprofilelink')[0].getAttribute('title');
      /* console.log('_share :: evt :: ccc.str.tw.tm[2]: '+ ccc.str.tw.tm[2]); */
   
      ccc.str.tw.tm[3] = document.getElementById('share-retweet-form').getElementsByClassName('urlprofilelink')[0].innerHTML;
      /* console.log('_share :: evt :: ccc.str.tw.tm[3]: '+ ccc.str.tw.tm[3]); */
          
      ccc.str.tw.tm[4] = document.getElementById('js-share-retweettweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('a')[0].getAttribute('href');
      /* console.log('_share :: evt :: ccc.str.tw.tm[4]: ' + ccc.str.tw.tm[4]); */

      ccc.str.tw.tm[5] = document.getElementById('js-share-retweettweet').getElementsByClassName('twitter-pic')[0].getElementsByTagName('img')[0].getAttribute('src');
      /* console.log('_share :: evt :: ccc.str.tw.tm[5]: ' + ccc.str.tw.tm[5]); */

      ccc.str.tw.tm[6] = document.getElementById('share-retweet-form').getElementsByClassName( 'twitter-txt' )[0].innerHTML;
      /* console.log('_share :: evt :: ccc.str.tw.tm[6]: ' + ccc.str.tw.tm[6]); */

      var _ccaction = document.getElementById('share-retweet-form').getElementsByClassName('twitter-actions');
          _ccaction[0].style.display = 'none';
          
          _ccaction = null;
          _frm = null;

  }
}


function reset_watchbar() {

  set_css_class('su','color','#FFFFFF');
  set_css_class('cp','color','#FFFFFF');
  set_css_class('cw','color','#FFFFFF');
  set_css_class('watchbar', 'display', 'none');
  set_css_class('icon-ccc_large','color','#000000');

  set_css_class('instore','color','#000000');
  set_css_class('ccstore','color','#C0C0C0');

  /* default timeline feed view, the users timeline view */
  set_css_id('in', 'display', 'block');
  set_css_id('cc', 'display', 'none');

  set_css_id('cw', 'display', 'none');
  set_css_id('cp', 'display', 'none');
  set_css_id('su', 'display', 'none');

}


function process_public( msg ) {

  /* su support * cp crowd popular (users) * cw crowd frequent (tweets) */

  /* console.log('process_public :: msg :: we are process public'); */

  switch(msg) {

    case ('cp'):

    /* console.log('process_public :: msg :: cp'); */

    /* most (or duplicate) records, display users who have stored the most data with crowdcc  */
          var _ccp = { 'ccp':'', 'flg':'', 'usr':''};
              _ccp['flg']  = '_cp';
              _ccp['usr']  = 'guest';
              
              /* console.log('process_public :: msg :: flg: ' + _ccp['flg'] ); */
              
              _ccp['ccp'] = '_ccp' + '=' + base64_encode( _ccp['flg'] ) + ':' + base64_encode( _ccp['usr'] );

              /* console.log('process_public :: msg :: screen_name: ' + _ccp['usr'] ); */

              _process('post', '_cp', _ccp['ccp'] ,'cu');
    break;

    case ('cw'):

    /* console.log('process_public :: msg :: cw'); */

    /* same (or duplicate) records, display most frequent (popular) tweets with crowdcc  */

          if (ccc.str.tw.cw.length === 0) {
          
          var _ccw = { 'ccw':'', 'flg':'', 'usr':''};
              _ccw['flg']  = '_cw';
              _ccw['usr']  = 'guest';
              
              /* console.log('process_public :: msg :: flg: ' + _ccw['flg'] ); */
              
              _ccw['ccw'] = '_ccw' + '=' + base64_encode( _ccw['flg'] ) + ':' + base64_encode( _ccw['usr'] );
              
              /* console.log('process_public :: msg :: screen_name: ' + _ccw['usr'] ); */
              
              _process('post', '_cw', _ccw['ccw'] ,'cu');
          
          } else {

            read_cwin_obj(30); /* readto whatever is the full page view for imac */

          }

          set_css_class('icon-play_left', 'color', '#111111');
          set_css_class('icon-play_right', 'color', '');
          set_css_class('ccnumber', 'color', '');

          set_css_id('sdn', 'display', 'none');
          set_css_id('sdn_cc', 'display', 'none');
          set_css_class('sdn_cw', 'display', 'block');
         
    break;

  }

}


function refavor( retweet, favor, replyto, replytomedia, ccmail, share, sharetomedia, trash, carbon ) {

      /* refavor( ccc.str.tw.tm[14], ccc.str.tw.tm[15], ccc.str.tw.tm[16], ccc.str.tw.tm[17], ccc.str.tw.tm[19], ccc.str.tw.tm[20], ccc.str.tw.tm[21], ccc.str.tw.tm[22], ccc.str.tw.tm[23] ) */

  /* console.log('refavor() :: we are refavor'); */

  /* console.log(retweet); */
  /* console.log(favor); */
  /* console.log(replyto); */
  /* console.log(ccmail); */
  /* console.log(share); */
  /* console.log(sharetomedia); */

  switch (true) {

    case (retweet === 1): /* retweet tweet */
          /* console.log('refavor() :: retweet tweet'); */

          if (favor === 0) {
       
          var _crt = { 'crt':'', 'flg':'', 'usr':'', 'tok':'', 'tid':'' };
              _crt['flg']  = '_rt';
              _crt['usr']  =  ccc.str.tw.usr.screen_name;
              _crt['tok']  =  ccc.str.tw.usr.cctoken; 
              _crt['tid']  =  ccc.str.tw.tm[12];
              
              /* console.log( 'refavor() :: screen_name: ' + _crt['usr'] + ' tweeter id: ' + ccc.str.tw.tm[12] ); */
          
              _crt['crt'] = '_crt' + '=' + base64_encode( _crt['flg'] ) + ':' + encrypt( _crt['usr'] ) + ':' + base64_encode( _crt['tok'] ) +':' + base64_encode( _crt['tid'] );
  
              _process('post', '_rt', _crt['crt'] ,'tw');

              _crt['tid'] = null; _crt['tok'] = null; _crt['usr'] = null; _crt['flg'] = null; _crt['crt'] = null; _crt = null;
          
          } else {

          var _crf = { 'crf':'', 'flg':'', 'usr':'', 'tok':'', 'tid':'' };
              _crf['flg']  = '_rf';
              _crf['usr']  =  ccc.str.tw.usr.screen_name;
              _crf['tok']  =  ccc.str.tw.usr.cctoken; 
              _crf['tid']  =  ccc.str.tw.tm[12];
              
              /* console.log( 'refavor() :: screen_name: ' + _crf['usr'] + ' tweeter id: ' + ccc.str.tw.tm[12] ); */
          
              _crf['crf'] = '_crf' + '=' + base64_encode( _crf['flg'] ) + ':' + encrypt( _crf['usr'] ) + ':' + base64_encode( _crf['tok'] ) +':' + base64_encode( _crf['tid'] );
  
              _process('post', '_rf', _crf['crf'] ,'tw');

              _crf['tid'] = null; _crf['tok'] = null; _crf['usr'] = null; _crf['flg'] = null; _crf['crf'] = null; _crf = null;

          }

    break;

    case (favor === 1):   /* favor tweet */
          /* console.log('refavor() :: favor tweet'); */

          var _cft = { 'cft':'', 'flg':'', 'usr':'', 'tok':'', 'tid':'' };
              _cft['flg']  = '_ft';
              _cft['usr']  =  ccc.str.tw.usr.screen_name;
              _cft['tok']  =  ccc.str.tw.usr.cctoken; 
              _cft['tid']  =  ccc.str.tw.tm[12];   
              
              /* console.log( 'refavor() :: screen_name: ' + _cft['usr'] + ' tweeter id: ' + ccc.str.tw.tm[12] ); */
              

              _cft['cft'] = '_cft' + '=' + base64_encode( _cft['flg'] ) + ':' + encrypt( _cft['usr'] ) + ':' + base64_encode( _cft['tok'] ) +':' + base64_encode( _cft['tid'] );
              
              _process('post', '_ft', _cft['cft'] ,'tw');

              _cft['tid'] = null; _cft['tok'] = null; _cft['usr'] = null; _cft['flg'] = null; _cft['cft'] = null; _cft = null;
    break;

    case (replyto === 1):   /* replyto tweet */
          /* console.log('refavor() :: replyto tweet'); */

     
          var _crp = { 'crp':'', 'flg':'', 'usr':'', 'tok':'', 'tid':'', 'rpl':'' };
              _crp['flg']  = '_rp';
              _crp['usr']  =  ccc.str.tw.usr.screen_name;
              _crp['tok']  =  ccc.str.tw.usr.cctoken; 
              _crp['tid']  =  ccc.str.tw.tm[12];
              _crp['rpl']  =  get_html_id('js-srctxt-reply');

              /* console.log('refavor() :: before data scrub: ' + _crp['rpl']); */

              _crp['rpl']  = _crp['rpl'].replace(/^\s*<br\s*\/?>|<br\s*\/?>\s*$/g,'');   /* data scrub <br> from string */
              
              /* console.log('refavor() :: after data scrub: ' + _crp['rpl']); */
              
              /* console.log('refavor() :: screen_name: ' + _crp['usr'] + ' tweeter id: ' + ccc.str.tw.tm[12] ); */
              

              _crp['crp'] = '_crp' + '=' + base64_encode( _crp['flg'] ) + ':' + encrypt( _crp['usr'] ) + ':' + base64_encode( _crp['tok'] ) + ':' + base64_encode( _crp['tid'] ) + ':' + base64_encode( _crp['rpl'] );

              _process('post', '_rp', _crp['crp'] ,'tw');

              _crp['rpl'] = null; _crp['tid'] = null; _crp['tok'] = null; _crp['usr'] = null; _crp['flg'] = null; _crp['crp'] = null; _crp = null;
    break;

    case (replytomedia === 1):   /* replyto with media tweet */
          /* console.log('refavor() :: replyto with media tweet'); */
          /* console.log('refavor() :: replytomedia :: ' + ccc.str.tw.tm[18]); */

          var _crm = { 'crm':'', 'flg':'', 'usr':'', 'tok':'', 'tid':'', 'rpl':'', 'img':'' };
              _crm['flg']  = '_rm';
              _crm['usr']  =  ccc.str.tw.usr.screen_name;
              _crm['tok']  =  ccc.str.tw.usr.cctoken; 
              _crm['tid']  =  ccc.str.tw.tm[12];
              _crm['rpl']  =  get_html_id('js-srctxt-reply');

              _crm['rpl']  = _crm['rpl'].replace(/^\s*<br\s*\/?>|<br\s*\/?>\s*$/g,'');   /* data scrub <br> from string */

              /* console.log('refavor() :: after data scrub: ' + _crm['rpl']); */

              _crm['img']  =  ccc.str.tw.tm[18];   
              
              /* console.log('refavor() :: screen_name: ' + _crm['usr'] + ' tweeter id: ' + ccc.str.tw.tm[12] ); */
              
              _crm['crm'] = '_crm' + '=' + base64_encode( _crm['flg'] ) + ':' + encrypt( _crm['usr'] ) + ':' + base64_encode( _crm['tok'] ) + ':' + base64_encode( _crm['tid'] ) + ':' + base64_encode( _crm['rpl'] ) + ':' + base64_encode( _crm['img'] );
              
              _process('post', '_rm', _crm['crm'] ,'tw');

              _crm['img'] = null; _crm['rpl'] = null; _crm['tid'] = null; _crm['tok'] = null; _crm['usr'] = null; _crm['flg'] = null; _crm['crm'] = null; _crm = null;
    break;

    case (ccmail === 1):   /* ccmail tweet */
          /* only ccc fully reg user can use this function (require valid user email address * ccc.str.tw.usr.ccmail0 ) */
          /* console.log('refavor() :: ccmail tweet'); */

          var _ccm = { 'ccm':'', 'flg':'', 'usr':'', 'tok':'', 'to':'', 'msg':'', 'turl':'', 'tusr':'', 'timg':'', 'twt':'', 'ttf':'', 'ttt':'', 'tta':''};
              _ccm['flg']  = '_cm';
              _ccm['usr']  =  ccc.str.tw.usr.screen_name;                               /* user screen (twitter) @screen_name */
              _ccm['tok']  =  ccc.str.tw.usr.cctoken; 
              _ccm['to']   =  document.getElementById('email-to').value;                /* email to send tweet and message to */
              /* _ccm['frm']  =  base64_decode(ccc.str.tw.usr.ccmail0); /*              /* ccc fully reg user mail address via signin module */
              _ccm['msg']  =  get_html_id('js-srctxt-mail');                            /* message to send with email */

              _ccm['msg']  = eschtml(_ccm['msg']);
          
              /* re-build tweet */

              /* url   * https://twitter.com/TechCrunch */
              _ccm['turl']  =  ccc.str.tw.tm[1];

              /* name  * TechCrunch */
              _ccm['tusr']  =  ccc.str.tw.tm[2];

              /* url name img * https://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png */       
              _ccm['timg']  =  ccc.str.tw.tm[5];
        
              /* tweet *  <a href="http://t.co/NCNLvurd0g" target="_blank"><img src="http://pbs.twimg.com/media/BtVFXbGIgAAEP8h.jpg" style="position:relative;float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" height="50" width="50"></a>
                 Samsung might have found a shortcut to mobile virtual reality through Oculus VR <a href="http://t.co/bbSzt4pkdI" target="_blank">http://t.co/bbSzt4pkdI</a> <a href="http://t.co/NCNLvurd0g" target="_blank">http://t.co/NCNLvurd0g</a> */  
              _ccm['twt']   =  eschtml(ccc.str.tw.tm[6]);

              /* tweet time tag  * https://twitter.com/TechCrunch/status/492376003143286784 (for tweet time)  */
              _ccm['ttf']  =  document.getElementById('js-share-email').getElementsByClassName('tweet-time')[0].getElementsByTagName('a')[0].getAttribute('href');
              
              /* tweet title tag * Thu Jul 24 18:29:21 +0000 2014 (for tweet time) */
              _ccm['ttt']  =  document.getElementById('js-share-email').getElementsByClassName('tweet-time')[0].getElementsByTagName('a')[0].getAttribute('title');

              /* tweet tag html value * 20h (for tweet time) */
              _ccm['tta']  =  document.getElementById('js-share-email').getElementsByClassName('tweet-time')[0].getElementsByTagName('a')[0].innerHTML;


              /*
              alert('ccc.str.tw.tm[1] :' + ccc.str.tw.tm[1] );
              alert('ccc.str.tw.tm[2] :' + ccc.str.tw.tm[2] );
              alert('ccc.str.tw.tm[3] :' + ccc.str.tw.tm[3] );
              alert('ccc.str.tw.tm[4] :' + ccc.str.tw.tm[4] );
              alert('ccc.str.tw.tm[5] :' + ccc.str.tw.tm[5] );
              alert('ccc.str.tw.tm[6] :' + ccc.str.tw.tm[6] );
              
              alert('_ccm[ttf] :' + _ccm['ttf'] );
              alert('_ccm[ttt] :' + _ccm['ttt'] );
              alert('_ccm[tta] :' + _ccm['tta'] );
              */

              _ccm['ccm']  = '_ccm' + '=' + base64_encode( _ccm['flg'] ) + ':' + encrypt( _ccm['usr'] ) + ':' + base64_encode( _ccm['tok'] ) + ':' + base64_encode( _ccm['to'] ) + ':' + base64_encode( _ccm['msg'] ) + ':' + base64_encode( _ccm['turl'] ) + ':' + base64_encode( _ccm['tusr'] ) + ':' +base64_encode( _ccm['timg'] ) + ':' + base64_encode( _ccm['twt'])  + ':' + base64_encode( _ccm['ttf'])  + ':' + base64_encode( _ccm['ttt']) + ':' + base64_encode( _ccm['tta']);
              
              _process('post', '_cm', _ccm['ccm'] ,'tw');

              _ccm['tta'] = null; _ccm['ttt'] = null; _ccm['ttf'] = null; _ccm['twt'] = null; _ccm['timg'] = null; _ccm['tusr'] = null; _ccm['turl'] = null; _ccm['msg'] = null; _ccm['to'] = null; _ccm['tok'] = null; _ccm['usr'] = null; _ccm['flg'] = null; _ccm['ccm'] = null; _ccm = null;
    break;

    case (share === 1): /* share * create tweet */
          /* console.log('refavor() :: share/create tweet'); */
          
          var _cst = { 'cst':'', 'flg':'', 'usr':'', 'tok':'', 'rpl':'' };
              _cst['flg']  = '_st';
              _cst['usr']  =  ccc.str.tw.usr.screen_name;
              _cst['tok']  =  ccc.str.tw.usr.cctoken;  
              _cst['rpl']  =  get_html_id('js-srctxt-new');
              _cst['rpl']  =  _cst['rpl'].replace(/^\s*<br\s*\/?>|<br\s*\/?>\s*$/g,'');   /* data scrub <br> from string */
                
              /* console.log('refavor() :: share * create tweet :: screen_name: ' + _cst['usr']); */

              _cst['cst'] = '_cst' + '=' + base64_encode( _cst['flg'] ) + ':' + encrypt( _cst['usr'] ) + ':' + base64_encode( _cst['tok'] ) + ':' + base64_encode( _cst['rpl'] );
              
              _process('post', '_st', _cst['cst'] ,'tw');
              
              _cst['rpl'] = null; _cst['tok'] = null; _cst['usr'] = null; _cst['flg'] = null; _cst['cst'] = null; _cst = null;
    break;

    case (sharetomedia === 1): /* share / create tweet with media */
          /* console.log('refavor() :: share/create tweet with media'); */
          /* console.log('refavor() :: share/create tweet with media :: ' + ccc.str.tw.tm[18]); */

          var _csm = { 'csm':'', 'flg':'', 'usr':'', 'tok':'', 'rpl':'', 'img':'' };
              _csm['flg']  = '_sm';
              _csm['usr']  =  ccc.str.tw.usr.screen_name;
              _csm['tok']  =  ccc.str.tw.usr.cctoken;
   
              _csm['rpl']  =  get_html_id('js-srctxt-new');
              _csm['rpl']  =  _csm['rpl'].replace(/^\s*<br\s*\/?>|<br\s*\/?>\s*$/g,'');   /* data scrub <br> from string */

              _csm['img']  =  ccc.str.tw.tm[18];   
              
              /* console.log('refavor() :: share/create tweet with media :: screen_name: ' + _csm['usr']); */
              
              _csm['csm'] = '_csm' + '=' + base64_encode( _csm['flg'] ) + ':' + encrypt( _csm['usr'] ) + ':' + base64_encode( _csm['tok'] ) + ':' + base64_encode( _csm['rpl'] ) + ':' + base64_encode( _csm['img'] );
           
              _process('post', '_sm', _csm['csm'] ,'tw');

              _csm['img'] = null; _csm['rpl'] = null; _csm['tok'] = null; _csm['usr'] = null; _csm['flg'] = null; _csm['csm'] = null; _csm = null;
    break;

    case (trash === 1): /* trash tweet */
          /* console.log('refavor() :: trash media'); */
          /* console.log('refavor() :: trash media :: ' + ccc.str.tw.tm[18]); */

          var _csh = { 'csh':'', 'flg':'', 'usr':'', 'tim':'', 'tid':''};
              _csh['flg']  = '_sh';
              _csh['usr']  =  ccc.str.tw.usr.screen_name;
              _csh['tim']  =  ''+ Math.round(+new Date()/1000) +''; /* generate unix type time id string */
              _csh['tid']  =  ccc.str.istw['trshid'];

              /* console.log('refavor() :: trash tweet :: * screen_name: ' + _csh['usr'] + ' tweet id: ' + _csh['tid']); */
              
              _csh['csh'] = '_csh' + '=' + base64_encode( _csh['flg'] ) + ':' + encrypt( _csh['usr'] ) + ':' + base64_encode( _csh['tim'] ) + ':' + base64_encode( _csh['tid'] );
              
              _process('post', '_sh', _csh['csh'] ,'cc');

              _csh['tid'] = null; _csh['tim'] = null; _csh['usr'] = null; _csh['flg'] = null; _csh['csh'] = null; _csh = null;
    break;

    case (carbon.length > 6): /* carbon tweet */
         
          /* console.log('refavor() :: we are carbon: ' + carbon); */

          /*  for display purpose only ... start

          console.log('tweet id: ' + JSON.parse(sessionStorage[ carbon ]).id_str);
          console.log('from user id: ' + JSON.parse(sessionStorage[ carbon ]).user.id_str);
          console.log('tweet owner: ' + 'glynthom');
          console.log('tweet create date: ' + JSON.parse(sessionStorage[ carbon ]).created_at);
          console.log('tweet text: ' + JSON.parse(sessionStorage[ carbon ]).text);
          console.log('source: ' + (JSON.parse(sessionStorage[ carbon ]).source).replace(/(<([^>]+)>)/ig,"") );
          console.log('source url: ' + JSON.parse(sessionStorage[ carbon ]).source);
          console.log('retweet count: ' + JSON.parse(sessionStorage[ carbon ]).retweet_count);
          console.log('favorite count: ' + JSON.parse(sessionStorage[ carbon ]).favorite_count);
          console.log('from user: ' + JSON.parse(sessionStorage[ carbon ]).user.screen_name);
          console.log('from user name: ' + JSON.parse(sessionStorage[ carbon ]).user.name);
          console.log('from location: ' + JSON.parse(sessionStorage[ carbon ]).user.location);
          console.log('from description: ' + JSON.parse(sessionStorage[ carbon ]).user.description);
          console.log('from url: ' + JSON.parse(sessionStorage[ carbon ]).user.url);      
          console.log('followers count: ' + JSON.parse(sessionStorage[ carbon ]).user.followers_count);
          console.log('friends count: ' + JSON.parse(sessionStorage[ carbon ]).user.friends_count);
          console.log('listed count: ' + JSON.parse(sessionStorage[ carbon ]).user.listed_count);
          console.log('created at: ' + JSON.parse(sessionStorage[ carbon ]).user.created_at);
          console.log('favourites count: ' + JSON.parse(sessionStorage[ carbon ]).user.favourites_count);
          console.log('time zone: ' + JSON.parse(sessionStorage[ carbon ]).user.time_zone);
          console.log('statuses count: ' + JSON.parse(sessionStorage[ carbon ]).user.statuses_count);
          console.log('profile image url: ' + JSON.parse(sessionStorage[ carbon ]).user.profile_image_url);

          switch (true) {
           case ( typeof (JSON.parse(sessionStorage[ carbon ]).entities.media) !== 'undefined' ):
                  console.log('we have some entites media urls');
                  console.log('entites mediaurl: ' + JSON.parse(sessionStorage[ carbon ]).entities.media[0].media_url);
        
           case ( typeof (JSON.parse(sessionStorage[ carbon ]).entities.hashtags.value) !== 'undefined'):
                  console.log('we have some entities hashtags');
                  console.log('entites hashtags: ' + JSON.parse(sessionStorage[ carbon ]).entities.hashtags);
           case ( JSON.parse(sessionStorage[ carbon ]).entities.urls.length === 0 ):
                  console.log('entites urls: false');
           break;
           case ( JSON.parse(sessionStorage[ carbon ]).entities.urls.length !== 0 ):
                  console.log('we have some entities url');
                  console.log(JSON.parse(sessionStorage[ carbon ]).entities.urls.length);
                  console.log('entites urls: ' + JSON.parse(sessionStorage[ carbon ]).entities.urls[0].expanded_url);
           break;
          }

          display purpose only ... end */

          var _con = { 'con':'', 'flg':'', 'tweet_id':'', 'from_user_id':'', 'tweet_owner':'', 'tweet_create_date':'', 'tweet_text':'', 'source':'', 'source_url':'', 'retweet_count':'', 'favorite_count':'', 'from_user':'', 'from_user_name':'', 'from_location':'', 'from_description':'', 'from_url':'', 'followers_count':'', 'friends_count':'', 'listed_count':'', 'created_at':'', 'favorites_count':'', 'time_zone':'', 'statuses_count':'', 'profile_image_url':'', 'entities_urls':'', 'entities_hashtags':'', 'entities_media_url':'', 'entities_url':'' };
 
              _con['flg']  = '_on';
              _con['tweet_id']  = JSON.parse(sessionStorage[ carbon ]).id_str;
              _con['from_user_id']  = JSON.parse(sessionStorage[ carbon ]).user.id_str;

              _con['tweet_owner']  = ccc.str.tw.usr.screen_name;
              _con['tweet_create_date'] = JSON.parse(sessionStorage[ carbon ]).created_at;

              _con['tweet_text'] = JSON.parse(sessionStorage[ carbon ]).text;
              _con['tweet_text'] = trashslash(_con['tweet_text']);

              /* console.log(  'tweet_text : ' + _con['tweet_text'] ); */
              /* _con['tweet_text'] = addslashes(_con['tweet_text']); // optional? escape char on input */
              
              if ( typeof (JSON.parse(sessionStorage[ carbon ]).retweeted_status) !== 'undefined') {
                /* retweet found * check for sensitive format */     
                switch (true) {
                   case ( ('RT @'+ JSON.parse(sessionStorage[ carbon ]).retweeted_status.user.screen_name +': '+ JSON.parse(sessionStorage[ carbon ]).retweeted_status.text) !== (JSON.parse(sessionStorage[ carbon ]).text)):
                         
                         var retxt = JSON.parse(sessionStorage[ carbon ]).retweeted_status.text;
                         retxt = trashslash(retxt);
                         /* retxt = addslashes(retxt); // optional ? escape characters on input */
                         _con['tweet_text'] = 'RT @'+ JSON.parse(sessionStorage[ carbon ]).retweeted_status.user.screen_name +': '+ retxt;
                   
                   break;
                }

                retxt = null;
              }

              _con['source'] = (JSON.parse(sessionStorage[ carbon ]).source).replace(/(<([^>]+)>)/ig,"");

              _con['source_url'] = htmlspecialchars( JSON.parse(sessionStorage[ carbon ]).source );

              _con['retweet_count'] = (''+ JSON.parse(sessionStorage[ carbon ]).retweet_count +'');
              _con['favorite_count'] = (''+ JSON.parse(sessionStorage[ carbon ]).favorite_count +'');
              _con['from_user'] = JSON.parse(sessionStorage[ carbon ]).user.screen_name;
              _con['from_user_name'] = JSON.parse(sessionStorage[ carbon ]).user.screen_name;
              _con['from_location'] = JSON.parse(sessionStorage[ carbon ]).user.location;

              /* _con['from_description'] = addslashes( JSON.parse(sessionStorage[ carbon ]).user.description ); // optional? escape char on input */
              _con['from_description'] = JSON.parse(sessionStorage[ carbon ]).user.description;
              _con['from_description'] = trashslash(_con['from_description']);

              _con['from_url'] = (''+ JSON.parse(sessionStorage[ carbon ]).user.url +''); // null or string value
              _con['followers_count'] = (''+ JSON.parse(sessionStorage[ carbon ]).user.followers_count +'');
              _con['friends_count'] = (''+ JSON.parse(sessionStorage[ carbon ]).user.friends_count +'');
              _con['listed_count'] = (''+ JSON.parse(sessionStorage[ carbon ]).user.listed_count +'');
              _con['created_at'] =  JSON.parse(sessionStorage[ carbon ]).user.created_at;
              _con['favourites_count'] = (''+ JSON.parse(sessionStorage[ carbon ]).user.favourites_count +'');
              _con['time_zone'] =  (''+ JSON.parse(sessionStorage[ carbon ]).user.time_zone +''); // null or string value
              _con['statuses_count'] =  (''+ JSON.parse(sessionStorage[ carbon ]).user.statuses_count +'');
              _con['profile_image_url'] =  JSON.parse(sessionStorage[ carbon ]).user.profile_image_url;

              _con['entities_urls'] = 'false';
              _con['entities_hashtags'] =  'false';
              _con['entities_media_url'] =  'false';
              _con['entities_url'] = 'false';
        
          switch (true) {

            case ( (''+ JSON.parse(sessionStorage[ carbon ]).user.url +'') === 'null'):
                  _con['from_url'] =   'false';
            case ( (''+ JSON.parse(sessionStorage[ carbon ]).user.time_zone +'') === 'null'):
                  _con['time_zone'] =  'false';
            break;
          }

          switch (true) {

           case ( typeof (JSON.parse(sessionStorage[ carbon ]).entities.media) !== 'undefined' ):   
                  _con['entities_media_url'] =  JSON.parse(sessionStorage[ carbon ]).entities.media[0].media_url;
                  _con['entities_url'] =  JSON.parse(sessionStorage[ carbon ]).entities.media[0].url;

           case ( typeof (JSON.parse(sessionStorage[ carbon ]).entities.hashtags.value) === 'undefined'):
                  _con['entities_hashtags'] =  'false';
           break;
     
           case ( typeof (JSON.parse(sessionStorage[ carbon ]).entities.hashtags.value) !== 'undefined'):
                  _con['entities_hashtags'] =  JSON.parse(sessionStorage[ carbon ]).entities.hashtags;
           break;
          
          }

          switch (true) {

           case ( JSON.parse(sessionStorage[ carbon ]).entities.urls.length === 0 ):
                  _con['entities_urls'] = 'false';
           break;
           
           case ( JSON.parse(sessionStorage[ carbon ]).entities.urls.length !== 0 ):
                  _con['entities_urls'] = JSON.parse(sessionStorage[ carbon ]).entities.urls[0].expanded_url;
           break;

          }

          /* ccc.str.tw.cb[0] = JSON.parse('{"tweet_owner":"' + _con['tweet_owner'] + '","created_at":"' + _con['tweet_create_date'] + '","id_str":"' + _con['tweet_id'] + '","text":"' + JSON.parse(sessionStorage[ carbon ]).text + '","retweet_count":"' + _con['retweet_count'] + '","favorite_count":"' + _con['favorite_count'] + '","user":{"id":"' + _con['from_user_id'] + '","id_str":"' + _con['from_user_id'] + '","name":"' + _con['from_user'] + '","screen_name":"' + _con['from_user_name'] + '","location":"' + _con['from_location'] + '","description":"' + JSON.parse(sessionStorage[ carbon ]).user.description + '","url":"' + _con['from_url'] + '","entities":{"url":{"urls":{"url":"' + _con['entities_urls'] + '","expanded_url":"' + _con['entities_urls'] + '"}}},"followers_count":"' + _con['followers_count'] + '","friends_count":"' + _con['friends_count'] + '","listed_count":"' + _con['listed_count'] + '","created_at":"' + _con['created_at'] + '","favourites_count":"' + _con['favourites_count'] + '","time_zone":"' + _con['time_zone'] + '","statuses_count":"' + _con['statuses_count'] + '","profile_image_url":"' + _con['profile_image_url'] + '"},"entities":{"urls":"' + _con['entities_urls'] + '","hashtags":"' + _con['entities_hashtags'] + '","media":[{"media_url":"' + _con['entities_media_url'] + '","url":"' + _con['entities_url'] + '"}]},"possibly_sensitive":false,"lang":"en"}'); */
          /* SyntaxError: JSON.parse: expected ',' or '}' after property value in object at line 1 column 112 of the JSON data  *  ...['entities_urls'] + '","expanded_url":"' + _con['entities_urls'] + '"}}},"follow... * caused by unescaped characters * trashslash(_con['tweet_text']); * please check if happens in future */
          /* SyntaxError: JSON.parse: bad control character in string literal at line 1 column 466 of the JSON data  *  ...['entities_urls'] + '","expanded_url":"' + _con['entities_urls'] + '"}}},"follow... * caused by unescaped characters * trashslash(_con['from_description']);      * please check if happens in future */


          ccc.str.tw.cb[0] = JSON.parse('{"tweet_owner":"' + _con['tweet_owner'] + '","created_at":"' + _con['tweet_create_date'] + '","id_str":"' + _con['tweet_id'] + '","text":"' +  _con['tweet_text'] + '","retweet_count":"' + _con['retweet_count'] + '","favorite_count":"' + _con['favorite_count'] + '","user":{"id":"' + _con['from_user_id'] + '","id_str":"' + _con['from_user_id'] + '","name":"' + _con['from_user'] + '","screen_name":"' + _con['from_user_name'] + '","location":"' + _con['from_location'] + '","description":"' + _con['from_description'] + '","url":"' + _con['from_url'] + '","entities":{"url":{"urls":{"url":"' + _con['entities_urls'] + '","expanded_url":"' + _con['entities_urls'] + '"}}},"followers_count":"' + _con['followers_count'] + '","friends_count":"' + _con['friends_count'] + '","listed_count":"' + _con['listed_count'] + '","created_at":"' + _con['created_at'] + '","favourites_count":"' + _con['favourites_count'] + '","time_zone":"' + _con['time_zone'] + '","statuses_count":"' + _con['statuses_count'] + '","profile_image_url":"' + _con['profile_image_url'] + '"},"entities":{"urls":"' + _con['entities_urls'] + '","hashtags":"' + _con['entities_hashtags'] + '","media":[{"media_url":"' + _con['entities_media_url'] + '","url":"' + _con['entities_url'] + '"}]},"possibly_sensitive":false,"lang":"en"}');


          /* carbon re-build tweet locally (type media false) */
          /* "{"created_at":"Thu Aug 28 04:34:28 +0000 2014","id":504849471021150200,"id_str":"504849471021150208","text":"Samsung's New Gear S Smartwatch Features A Curved Screen And 3G Connectivity http://t.co/MnFN9VCeHs by @drizzled","source":"<a href=\"http://10up.com\" rel=\"nofollow\">10up Publish Tweet</a>","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":816653,"id_str":"816653","name":"TechCrunch","screen_name":"TechCrunch","location":"San Francisco, CA","description":"Breaking Technology News And Opinions From TechCrunch","url":"http://t.co/FQzFJNIg8e","entities":{"url":{"urls":[{"url":"http://t.co/FQzFJNIg8e","expanded_url":"http://techcrunch.com","display_url":"techcrunch.com","indices":[0,22]}]},"description":{"urls":[]}},"protected":false,"followers_count":3788213,"friends_count":873,"listed_count":90609,"created_at":"Wed Mar 07 01:27:09 +0000 2007","favourites_count":144,"utc_offset":-25200,"time_zone":"Pacific Time (US & Canada)","geo_enabled":true,"verified":true,"statuses_count":79111,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":true,"profile_background_color":"149500","profile_background_image_url":"http://pbs.twimg.com/profile_background_images/489072831733321729/QTO84QJO.png","profile_background_image_url_https":"https://pbs.twimg.com/profile_background_images/489072831733321729/QTO84QJO.png","profile_background_tile":false,"profile_image_url":"http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png","profile_image_url_https":"https://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png","profile_banner_url":"https://pbs.twimg.com/profile_banners/816653/1401204346","profile_link_color":"097000","profile_sidebar_border_color":"FFFFFF","profile_sidebar_fill_color":"DDFFCC","profile_text_color":"222222","profile_use_background_image":true,"default_profile":false,"default_profile_image":false,"following":true,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":51,"favorite_count":23,"entities":{"hashtags":[],"symbols":[],"urls":[{"url":"http://t.co/MnFN9VCeHs","expanded_url":"http://tcrn.ch/VOKMQl","display_url":"tcrn.ch/VOKMQl","indices":[77,99]}],"user_mentions":[{"screen_name":"drizzled","name":"Darrell Etherington","id":15425183,"id_str":"15425183","indices":[103,112]}]},"favorited":false,"retweeted":false,"possibly_sensitive":false,"lang":"en"}" */

          /* carbon re-build tweet locally (type media true) */
          /* "{"created_at":"Thu Aug 28 07:03:07 +0000 2014","id":504886882057338900,"id_str":"504886882057338880","text":"This 3D-printed key can open almost any lock http://t.co/dTaQrCenDN http://t.co/bzDi3tTmhF","source":"<a href=\"http://www.socialflow.com\" rel=\"nofollow\">SocialFlow</a>","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":816653,"id_str":"816653","name":"TechCrunch","screen_name":"TechCrunch","location":"San Francisco, CA","description":"Breaking Technology News And Opinions From TechCrunch","url":"http://t.co/FQzFJNIg8e","entities":{"url":{"urls":[{"url":"http://t.co/FQzFJNIg8e","expanded_url":"http://techcrunch.com","display_url":"techcrunch.com","indices":[0,22]}]},"description":{"urls":[]}},"protected":false,"followers_count":3788330,"friends_count":873,"listed_count":90612,"created_at":"Wed Mar 07 01:27:09 +0000 2007","favourites_count":144,"utc_offset":-25200,"time_zone":"Pacific Time (US & Canada)","geo_enabled":true,"verified":true,"statuses_count":79111,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":true,"profile_background_color":"149500","profile_background_image_url":"http://pbs.twimg.com/profile_background_images/489072831733321729/QTO84QJO.png","profile_background_image_url_https":"https://pbs.twimg.com/profile_background_images/489072831733321729/QTO84QJO.png","profile_background_tile":false,"profile_image_url":"http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png","profile_image_url_https":"https://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png","profile_banner_url":"https://pbs.twimg.com/profile_banners/816653/1401204346","profile_link_color":"097000","profile_sidebar_border_color":"FFFFFF","profile_sidebar_fill_color":"DDFFCC","profile_text_color":"222222","profile_use_background_image":true,"default_profile":false,"default_profile_image":false,"following":true,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":203,"favorite_count":120,"entities":{"hashtags":[],"symbols":[],"urls":[{"url":"http://t.co/dTaQrCenDN","expanded_url":"http://tcrn.ch/1tLeQJ5","display_url":"tcrn.ch/1tLeQJ5","indices":[45,67]}],"user_mentions":[],"media":[{"id":504886881881169900,"id_str":"504886881881169920","indices":[68,90],"media_url":"http://pbs.twimg.com/media/BwG38a5IIAAUjyc.jpg","media_url_https":"https://pbs.twimg.com/media/BwG38a5IIAAUjyc.jpg","url":"http://t.co/bzDi3tTmhF","display_url":"pic.twitter.com/bzDi3tTmhF","expanded_url":"http://twitter.com/TechCrunch/status/504886882057338880/photo/1","type":"photo","sizes":{"small":{"w":340,"h":222,"resize":"fit"},"large":{"w":650,"h":424,"resize":"fit"},"thumb":{"w":150,"h":150,"resize":"crop"},"medium":{"w":600,"h":391,"resize":"fit"}}}]},"extended_entities":{"media":[{"id":504886881881169900,"id_str":"504886881881169920","indices":[68,90],"media_url":"http://pbs.twimg.com/media/BwG38a5IIAAUjyc.jpg","media_url_https":"https://pbs.twimg.com/media/BwG38a5IIAAUjyc.jpg","url":"http://t.co/bzDi3tTmhF","display_url":"pic.twitter.com/bzDi3tTmhF","expanded_url":"http://twitter.com/TechCrunch/status/504886882057338880/photo/1","type":"photo","sizes":{"small":{"w":340,"h":222,"resize":"fit"},"large":{"w":650,"h":424,"resize":"fit"},"thumb":{"w":150,"h":150,"resize":"crop"},"medium":{"w":600,"h":391,"resize":"fit"}}}]},"favorited":false,"retweeted":false,"possibly_sensitive":false,"lang":"en"}" */

          /* entities":{"hashtags":[],"symbols":[],"urls":[{"url":"http://t.co/dTaQrCenDN","expanded_url":"http://tcrn.ch/1tLeQJ5","display_url":"tcrn.ch/1tLeQJ5","indices":[45,67]}],"user_mentions":[],"media":[{"id":504886881881169900,"id_str":"504886881881169920","indices":[68,90],"media_url":"http://pbs.twimg.com/media/BwG38a5IIAAUjyc.jpg","media_url_https":"https://pbs.twimg.com/media/BwG38a5IIAAUjyc.jpg","url":"http://t.co/bzDi3tTmhF","display_url":"pic.twitter.com/bzDi3tTmhF","expanded_url":"http://twitter.com/TechCrunch/status/504886882057338880/photo/1","type":"photo","sizes":{"small":{"w":340,"h":222,"resize":"fit"},"large":{"w":650,"h":424,"resize":"fit"},"thumb":{"w":150,"h":150,"resize":"crop"},"medium":{"w":600,"h":391,"resize":"fit"}}}]},"extended_entities":{"media" ... */


          _con['con'] = '_con' + '=' + base64_encode( _con['flg'] ) + ':' + base64_encode( _con['tweet_id'] ) + ':' + base64_encode( _con['from_user_id'] ) + ':' + encrypt( _con['tweet_owner'] ) + ':' + base64_encode( _con['tweet_create_date'] ) + ':' + base64_encode( _con['tweet_text'] ) + ':' + base64_encode( _con['source'] ) + ':' + base64_encode( _con['source_url'] ) + ':' + base64_encode( _con['retweet_count'] ) + ':' + base64_encode( _con['favorite_count'] ) + ':' + base64_encode( _con['from_user'] ) + ':' + base64_encode( _con['from_user_name'] ) + ':' + base64_encode( _con['from_location'] ) + ':' + base64_encode( _con['from_description'] ) + ':' + base64_encode( _con['from_url'] ) + ':' + base64_encode( _con['followers_count'] ) + ':' + base64_encode( _con['friends_count'] ) + ':' + base64_encode( _con['listed_count'] ) + ':' + base64_encode( _con['created_at'] ) + ':' + base64_encode( _con['favourites_count'] ) + ':' + base64_encode( _con['time_zone'] ) + ':' + base64_encode( _con['statuses_count'] ) + ':' + base64_encode( _con['profile_image_url'] ) + ':' + base64_encode( _con['entities_urls'] ) + ':' + base64_encode( _con['entities_hashtags'] ) + ':' + base64_encode( _con['entities_media_url'] ) + ':' + base64_encode( _con['entities_url'] );
          
          _process('post', '_on', _con['con'] ,'cc');

          _con['entities_url'] = null; _con['entities_media_url'] = null; _con['entities_hashtags'] = null; _con['entities_urls'] = null; _con['profile_image_url'] = null; _con['statuses_count'] = null; _con['time_zone'] = null; _con['favorites_count'] = null; _con['created_at'] = null; _con['listed_count'] = null; _con['friends_count'] = null; _con['followers_count'] = null; _con['from_url'] = null; _con['from_description'] = null; _con['from_location'] = null; _con['from_user_name'] = null; _con['from_user'] = null; _con['favorite_count'] = null; _con['retweet_count'] = null; _con['source_url'] = null; _con['source'] = null; _con['tweet_text'] = null; _con['tweet_create_date'] = null; _con['tweet_owner'] = null; _con['from_user_id'] = null; _con['tweet_id'] = null; _con['flg'] = null; _con['con'] = null; _con = null;

    break;
    
    case (retweet === 2 && favor === 2 && replyto === 2 && ccmail === 2):

          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been retweeted favored replied too & ccmail'); */
          message_('modal_close', 'rtfvrpcm');
          msgtmr_('close');

    break;

    case (retweet === 2 && favor === 2 && replytomedia === 2 && ccmail === 2):

          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been retweeted favored replied too with media & ccmail'); */
          cclcanvas('reply-img-place');
          message_('modal_close', 'rtfvrpcm');
          msgtmr_('close');

    break;

    case (retweet === 2 && favor === 2 && replyto === 2):

          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been retweeted favored & replied too'); */
          message_('modal_close', 'rtfvrp');
          msgtmr_('close');

    break;

    case (retweet === 2 && favor === 2 && replytomedia === 2):

          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been retweeted favored & replied too with media'); */
          cclcanvas('reply-img-place');
          message_('modal_close', 'rtfvrp');
          msgtmr_('close');

    break;

    case (retweet === 2 && favor === 2):

          /* console.log('refavor() :: refavor tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been retweeted & favored'); */
          message_('modal_close', 'rtfv');
          msgtmr_('close');

    break;

    case (retweet === 2):       /* retweet ccc.str.tw.tm[14] tweet success */
          
          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been retweeted'); */
          message_('modal_close', 'rt');
          msgtmr_('close');

    break;

    case (favor === 2):         /* favor ccc.str.tw.tm[15] tweet success */
          
          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been favorite'); */
          message_('modal_close', 'fv');
          msgtmr_('close');
     
    break;

    case (replyto === 2):       /* replyto ccc.str.tw.tm[16] tweet success */
          
          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been reply to'); */
          message_('modal_close', 'rp');
          msgtmr_('close');

    break;

    case (replytomedia === 2):  /* replyto with media ccc.str.tw.tm[17] tweet success */
          
          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been reply to with media'); */
          cclcanvas('reply-img-place');
          message_('modal_close', 'rm');
          msgtmr_('close');

    break;

    case (ccmail === 2):        /* ccmail ccc.str.tw.tm[19] tweet success */
          
          /* console.log('refavor() :: tweeter id: ' + ccc.str.tw.tm[12] + ' tweet has been ccmail'); */
          message_('modal_close', 'cm');
          msgtmr_('close');

    break;

    case (share === 2):         /* share ccc.str.tw.tm[20] tweet success */
          
          /* console.log('refavor() :: your tweet has been sent'); */
          message_('modal_close', 'st');
          msgtmr_('close');

    break;

    case (sharetomedia === 2):  /* share with media ccc.str.tw.tm[21] tweet success */
          
          /* console.log('refavor() :: tweet has been sent with media'); */
          cclcanvas('img-place');
          message_('modal_close', 'sm');
          msgtmr_('close');

    break;

    case (trash === 2):  /* trash media ccc.str.tw.tm[22] tweet success */
          
          /* console.log('refavor() :: tweet has been trashed'); */
          message_('message_close', 'sh');
          msgtmr_('close-trash');

    break;

    case (trash === 3):  /* trash media ccc.str.tw.tm[22] tweet error */
          
          /* console.log('refavor() :: tweet not found'); */
          message_('message_close', 'she');
          msgtmr_('close-trash');

    break;

    case (carbon === 2):  /* carbon copy ccc.str.tw.tm[23] tweet success */
          
          /* console.log('refavor() :: tweet has been carbon copied'); */
          message_('message_close', 'on');
          msgtmr_('close-carbon');

    break;

    case (carbon === 3):  /* carbon copy ccc.str.tw.tm[23] error already copied */
          
          /* console.log('refavor() :: tweet has already been carbon copied'); */
          message_('message_close', 'ond');
          msgtmr_('close');

    break;

    case (carbon === 7):  /* carbon limit ccc.str.tw.tm[23] error already reached */
          
          /* console.log('refavor() :: tweet store limit has been reached'); */
          message_('message_close', 'lie');
          msgtmr_('close');

    break;

    case (retweet === 4):
    case (favor === 4):
    case (replyto === 4):
    case (replytomedia === 4): 
    case (ccmail === 4):
    case (trash === 4):
    case (carbon === 4):  
          /* console.log('refavor() :: woaw there has been a network error, please try later'); */
          message_('message_close', 'em');
          msgtmr_('close-error');
    break;

  }

  /* re-set * refavor flags */
  ccc.str.tw.tm[14] = 0; /* retweet */ 
  ccc.str.tw.tm[15] = 0; /* favor   */
  ccc.str.tw.tm[16] = 0; /* reply   */ 
  ccc.str.tw.tm[17] = 0; /* reply with media */
  ccc.str.tw.tm[18] = 0; /* reply with media canvas */
  ccc.str.tw.tm[19] = 0; /* ccmail */

  ccc.str.tw.tm[20] = 0; /* share / create tweet */
  ccc.str.tw.tm[21] = 0; /* share / create tweet with media */
  ccc.str.tw.tm[22] = 0; /* trash  copy store media */
  ccc.str.tw.tm[23] = 0; /* carbon copy store tweet */

  ccc.str.tw.tm[30] = 0; /* share_cc disabled = 0 */

  /* resume * timer */
  resumecc();
  /* console.log('refavor() :: we have resumed * check timer'); */
}

function jsonvalid(obj) {
   
   try{
       JSON.parse(obj);
   }catch(err){
       return 0;
   }

}

function cclcanvas(canvas) {

  var canvasid = document.getElementById(canvas);
  var ctx = canvasid.getContext("2d");

  /* store the current transformation matrix */
  ctx.save();

  /* use the identity matrix while clearing the canvas */
  ctx.setTransform(1, 0, 0, 1, 0, 0);
  ctx.clearRect(0, 0, canvasid.width, canvasid.height);

  /* restore the transform */
  ctx.restore();

  /* ccc.str.tw.tm[18] = 0; should be cleared on modal close */
  canvasid = null, ctx = null;

}

function eschtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function ccreply() {
  console.log('we are ccreply');

    var _crp = { 'crp':'', 'flg':'', 'usr':'', 'tid':'' };
        _crp['flg']  =  '_rp';
        _crp['usr']  =  ccc.str.tw.usr.screen_name;
        _crp['tid']  =  ccc.str.tw.tm[12];
        _crp['rpl']  =  get_html_id('js-srctxt-reply');   
        
        /* console.log('ccreply :: screen_name: ' + _crp['usr'] + ' tweeter id: ' + ccc.str.tw.tm[12] ); */

        _crp['crp'] = '_crp' + '=' + base64_encode( _crp['flg'] ) + ':' + encrypt( _crp['usr'] ) + ':' + base64_encode( _crp['tid'] ) + ':' + base64_encode( _crp['rpl'] );
        
        _process('post', '_rp', _crp['crp'] ,'tw');

}

function message_(msg, msg_code) {

 /* console.log('message_ :: ui-dialog-noticebar-close has been clicked'); */

  switch (msg) {

    case ('modal_close'):

      set_css_class('twitter-actions', 'display', 'block');
      set_css_class('semantic-content', 'display', 'none');
      /* set_css_class('close-action', 'opacity', 1); */

      set_css_class('share-items', 'display', 'block');

      set_css_class('retweet-form', 'display', 'none');
      set_css_class('share-form', 'display', 'none');
      set_css_class('reply-form', 'display', 'none');
      set_css_class('email-form', 'display', 'none');
      set_css_class('modal-share', 'display', 'none');

      set_css_id('retweet-tweet-dialog-header','display', 'none');
      set_css_id('reply-tweet-dialog-header','display', 'none');
      set_css_id('mail-tweet-dialog-header','display', 'none');
      set_css_id('create-tweet-dialog-header','display', 'none');

      set_css_class('retweet-footer', 'display', 'none');
      set_css_class('favor-footer', 'display', 'none');
      set_css_class('reply-footer', 'display', 'none');
      set_css_class('share-footer', 'display', 'none');
      set_css_class('email-footer', 'display', 'none');

      /* editable_elements[0].setAttribute("contentEditable", false); */
      /* find out which elements need to be reset to contenteditiable= false */
          
      set_html_id('js-srctxt-new', null);
    
      set_html_id('img-place', null);

      ccc.str.istw['mouse'] = 0;
      
      /* console.log('message_ :: mouseover and out disabled!'); */

      ccc.sig.isvalid['enablekey'] = 0;

      /* console.log('message_ :: keyup settings dialog disabled!'); */
      /* ccc.str.tw.tm = []; */

    break;

    case ('message_close'):
    
      /* error * actionable * display none * force * soft reset * signin.js */   
      set_css_class('error', 'display', 'none');
      set_css_class('actionable', 'display', 'none');

      set_css_class('ui-dialog-noticebar-close', 'display', 'none');

      if ( typeof ccc.str.istw['trshid'] !== 'undefined' && ccc.str.istw['trshid'] !== null ) {
    
        if ( ccc.str.istw['trshid'] !== 0) {

          var a = document.getElementById( ccc.str.istw['trshid']+'_txt').getElementsByTagName("a");
          var tmplnk = '';

          for (var i = 0; i < a.length; i++) {
            
               tmplnk = a[i];
               tmplnk.style.background = '';
               tmplnk.style.opacity = '1';
               tmplnk.style.color = '';
          }
        
          set_css_class( ccc.str.istw['trshid'] + '_src', 'background', '');
          set_css_class( ccc.str.istw['trshid'] + '_src', 'opacity', '');
          
          set_css_class( ccc.str.istw['trshid'] + '_src', 'color', '');

          set_css_class( ccc.str.istw['trshid'] + '_del', 'color', '');
          set_css_class( ccc.str.istw['trshid'] + '_sha', 'color', '');
          set_css_class( ccc.str.istw['trshid'] + '_ion', 'opacity', '');

          set_css_id('notice-cd', 'display', 'none');
    
          a = null; tmplnk = null; i = null;  
        }
      }

      if (ccc.str.tw.usr.ccuser === 'ccn') {
         
          /* enable nag bar * error_pc5de * notices */

          set_css_class('notices', 'display', 'block');
          set_css_class('error_pc5de', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'display', 'none');
          set_css_id('in','margin-top','-15px');
          set_css_id('cc','margin-top','-15px');
          /* set_css_id('noticebar-close-flix', 'display', 'block'); */

          document.getElementById('btn_confirm_email').style.visibility = 'visible';
          document.getElementById('or_email').style.visibility = 'visible';
          document.getElementById('up_email').style.visibility = 'visible';
          
          /* enable nag bar * error_pc5de * notices */

          /* console.log('message_close :: message close * non confirmed email address msg bar'); */
      
      } else {
          set_css_class('notices', 'display', 'none');
          isbarone('clear');
      }

      ccc.str.istw['trshid'] = 0;
      ccc.str.istw['ckoff'] = 0;

    break; 
  }

  /* alert('store * 7491 start'); */

  if (msg_code !== '') {
      set_css_class('notices', 'display', 'block');
      set_css_class('ui-dialog-noticebar-close', 'display', 'block');

    /* alert('store * 7491 end'); */

    switch (true) {

      case (msg_code.indexOf('e') === -1):
            set_css_class('ui-dialog-noticebar-close', 'backgroundColor', 'rgba(85, 172, 238, 0.1)');
            /* console.log('we think this is not an error message'); */
      break;

      case (msg_code.indexOf('e') !== -1):
            set_css_class('ui-dialog-noticebar-close', 'backgroundColor', '#DB3939');
            /* console.log('we think this is an error message!'); */
      break;
    }

  }

  switch (msg_code) {

    case ('rtfvrpcm'):
          /*  (ccc.str.tw.tm[2]) + ' ' + (ccc.str.tw.tm[6].substring(0,10)+ '...' ) */
          /* console.log('from message_ tweet: https://twitter.com/'+ ccc.str.tw.tm[2] +'/status/'+ ccc.str.tw.tm[12] + ' has been retweeted favored replied to and ccmail'); */
          /* set_css_class('notice-rtfvrpcm', 'display', 'block'); */
    break;

    case ('rtfvrp'):
          /*  (ccc.str.tw.tm[2]) + ' ' + (ccc.str.tw.tm[6].substring(0,10)+ '...' ) */
          /* console.log('from message_ tweet: https://twitter.com/'+ ccc.str.tw.tm[2] +'/status/'+ ccc.str.tw.tm[12] + ' has been retweeted favored and replied to'); */
          set_css_class('notice-rtfvrt', 'display', 'block');
    break;

    case ('rtfv'):
          /*  (ccc.str.tw.tm[2]) + ' ' + (ccc.str.tw.tm[6].substring(0,10)+ '...' ) */
          /* console.log('from message_ tweet: https://twitter.com/'+ ccc.str.tw.tm[2] +'/status/'+ ccc.str.tw.tm[12] + ' has been retweeted & favored'); */
          set_css_class('notice-rtfv', 'display', 'block');
    break;

    case ('rt'):
          /* console.log('from message_ tweet id: ' + ccc.str.tw.tm[12] + ' tweet has been retweeted'); */
          set_css_class('notice-rt', 'display', 'block');
    break;

    case ('fv'):
          /* console.log('from message_ tweet id: ' + ccc.str.tw.tm[12] + ' tweet has been favored'); */
          set_css_class('notice-fv', 'display', 'block');
    break;

    case ('rp'):
          /* console.log('from message_ tweet id: ' + ccc.str.tw.tm[12] + ' tweet has been replied to'); */
          set_css_class('notice-rp', 'display', 'block');
    break;

    case ('rm'):
          /* console.log('from message_ tweet id: ' + ccc.str.tw.tm[12] + ' tweet has been replied to with media'); */
          set_css_class('notice-rm', 'display', 'block');
    break;

    case ('cm'):
          /* console.log('from message_ tweet id: ' + ccc.str.tw.tm[12] + ' tweet has been mailed to'); */
          set_css_class('notice-cm', 'display', 'block');
    break;

    case ('st'):
          /* console.log('your tweet has been sent'); */
          set_css_class('notice-st', 'display', 'block');
    break;

    case ('sm'):
          /* console.log('your tweet has been sent with media'); */
          set_css_class('notice-sm', 'display', 'block');
    break;

    case ('sh'):
          /* console.log('your tweet has been trashed'); */
          set_css_class('notice-sh', 'display', 'block');
         
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', 'rgba(219, 57, 57, 0.1)');
    break;

    case ('she'):
          /* console.log('your tweet not found error'); */
          set_css_class('notice-she', 'display', 'block');
    break;

    case ('cw'):
          /* console.log('wow, just a sample of the popular cc tweets trending right now'); */
          set_css_class('notice-cw', 'display', 'block');
    break;

    case ('on'):
          /* console.log('your tweet has been carboned'); */
          set_css_class('notice-on', 'display', 'block');
    break;

    case ('now'):
          /* console.log('view new content'); */
          set_css_class('notice-now', 'display', 'block');
    break;

    case ('ond'):
          /* console.log('your tweet has been already been carboned'); */
          set_css_class('notice-ond', 'display', 'block');
    break;

    case ('pae'):
          /* console.log('timer is paused'); */
          set_css_class('notice-pae', 'display', 'block');
    break;

    case ('nar'):
          /* console.log('near to end of your crowdcc store'); */
          set_css_class('notice-nar', 'display', 'block');
    break;

    case ('nae'):
          /* console.log('woaw, cannot find any more cc tweets in the store'); */
          set_css_class('notice-nae', 'display', 'block');
          set_css_class('sdn_cc_more', 'display', 'none'); set_css_class('sdn_cc_wait', 'display', 'block');
    break;

    case ('lie'):
          /* console.log('cannot store any more tweets in this version, please upgrade'); */
          set_css_class('notice-lie', 'display', 'block');
    break;

    case ('api'):
          /* console.log('Don\'t panic, paging older content will be re-enabled shortly'); */
          if ( Math.floor(ccc.str.tw.tg[0] / 60) > 1 ) {
            set_html_id('api', 'Don\'t panic, paging older content will be re-enabled in ' + Math.floor(ccc.str.tw.tg[0] / 60) + ' mins');
          } else {
            set_html_id('api', 'Don\'t panic, paging older content will be re-enabled within a minute');
          }
          set_css_class('notice-api', 'display', 'block');
    break;

    case ('cat'):
          /* console.log('just catching up with your request please try now'); */
          set_css_class('notice-cat', 'display', 'block');
    break;

    case ('cut'):
          /* console.log('crowdcc paging older content in this version is limited, please upgrade!'); */
          set_css_class('notice-cut', 'display', 'block');
    break;

    case ('mag'):
          /* console.log('image file is larger than 3MB, this is not supported in this version, please upgrade'); */
          set_css_class('notice-mag', 'display', 'block');
    break;

    /* cc api error messages */

    case ('cape'):
          console.log('Woaw, everything is a bit busy right now, please try later : over capacity!');
          set_css_class('notice-api-cape', 'display', 'block');
    break;

    case ('rrot'):
          console.log('Woaw, retweet is not permissible for this status!');
          set_css_class('notice-api-rrot', 'display', 'block');
    break;

    case ('prot'):
          /* console.log('Woaw, tweet already re-tweeted re-favoured or a protected tweet!'); */
          set_css_class('notice-api-prot', 'display', 'block');
    break;
    /*
    case ('inte'):
          console.log('Woaw, something has gone wrong, while we investigate, please try to re-signin.');
          set_css_class('notice-api-inte', 'display', 'block');
    break;
    */
    case ('page'):
          /* console.log('woaw, something has gone wrong, while we investigate, please try to re-signin.'); */
          set_css_class('notice-api-page', 'display', 'block');
    break;

  }

  ccc.str.tw.tm = [];
  clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
  resumecc();   /* resume * timer */

}

function msgnil(msg) {
  /* nil content * choices intweet or cctweet */

  switch(msg) {

    case ('inzero'):
          /* nothing, no tweets * no followed users with tweets ! */
          /* console.log('msgnil :: inzero'); */

          /* clear * html */
          set_html_id('in', '');
          set_css_class('notices', 'display', 'block');
          set_css_class('notice-ino', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', 'rgba(85, 172, 238, 0.1)');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_id('noticebar-close-float','display','none');
    break;
    case ('cczero'):
          /* nothing, no tweets found * in ccstore ! */
          /* console.log('msgnil :: cczero'); */

          set_css_class('notices', 'display', 'block');
          set_css_class('notice-cno', 'display', 'block');
          set_css_class('ui-dialog-noticebar-close', 'backgroundColor', 'rgba(85, 172, 238, 0.1)');
          set_css_class('ui-dialog-noticebar-close', 'display', 'block');
          set_css_id('noticebar-close-float','display','none');
          msgtmr_('close');
    break;
  }
}

function msgnot() {

  set_css_class('notices', 'display', 'block');
  set_css_class('notice-cd', 'display', 'block');
  /* console.log('msgnot'); */

  if (ccc.str.tw.usr.ccuser === 'ccn') {

      document.getElementById('btn_confirm_email').style.visibility = 'hidden';
      document.getElementById('or_email').style.visibility = 'hidden';
      document.getElementById('up_email').style.visibility = 'hidden';

  }

  set_css_class('ui-dialog-noticebar-close', 'backgroundColor', 'rgba(219, 57, 57, 0.1)');
  set_css_class('ui-dialog-noticebar-close', 'display', 'block');

  clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
  resumecc();
}

function msgcls() {

  set_css_class('error', 'display', 'none');
  set_css_class('actionable', 'display', 'none');
  set_css_class('ui-dialog-noticebar-close', 'display', 'none');

  if (ccc.str.tw.usr.ccuser === 'ccn') {
      /* console.log('msgcls :: ccn :: display block!'); */

      /* enable nag bar * error_pc5de * notices */

      set_css_class('notices', 'display', 'block');
      set_css_class('error_pc5de', 'display', 'block');
      set_css_class('ui-dialog-noticebar-close', 'display', 'none');
      set_css_id('in','margin-top','-15px');
      set_css_id('cc','margin-top','-15px');
      /* set_css_id('noticebar-close-flix', 'display', 'block'); */

      /* enable nag bar * error_pc5de * notices */
      
      /* console.log('msgcls :: ccn :: non confirmed email address msg bar'); */

  } else {
      /* console.log('msgcls :: ccn :: notices display none!'); */
      set_css_class('notices', 'display', 'none');
  }

  /* message * Woaw, we cannot find anymore cc tweets in the store ! * sdn_cc_wait */
  set_css_class('sdn_cc_wait', 'display', 'none'); set_css_class('sdn_cc_more', 'display', 'block');

  clearTimeout(ccc.ccw.tidcs); ccc.ccw.tidcs = null;
  resumecc();
}

function msgtmr_(msg) {

  switch (msg) {

    case ('close'):
       
          pausecc();

          /* console.log('msgtmr_ :: start close timer message'); */
          ccc.ccw.tidcs = setTimeout( function() { msgcls(); } , 2500);
          /* console.log('msgtmr_ :: end close timer message'); */
         
    break;

    case ('close-trash'):
       
          pausecc();

          ccc.ccw.tidcs = setTimeout( function() { msgcls(); } , 2500);
          /* console.log('msgtmr_ :: end close trash timer message'); */
          update_fw_calobj('trash');
    break;

    case ('close-carbon'):
    
          pausecc();

          ccc.ccw.tidcs = setTimeout( function() { msgcls(); } , 2500);
          /* console.log('msgtmr_ :: end close carbon timer message'); */
          update_fw_calobj('update');
  
    break;

    case ('close-error'):

          pausecc();

          ccc.ccw.tidcs = setTimeout( function() { msgcls(); } , 2500);
          /* console.log('msgtmr_ :: end close error timer message!'); */
          resumecc();

    break;

    case ('open'):

           pausecc();
           ccc.ccw.tidcs = setTimeout( function() { msgnot(); } , 300);
           /* console.log('msgtmr_ :: end open timer message!'); */
           
    break;
  }

}


/* on paste for keys */

/*

// document.getElementById('js-srctxt-new').addEventListener("paste", _onpaste, false);
// document.getElementById('js-srctxt-new').addEventListener("input", _oninput, false);

function _onpaste(event) { console.log('paste detected');ccc.str.tw.tm[9] = 1;}
function _oninput(event) {

  if (ccc.str.tw.tm[9] == 1) {
     var el = document.getElementById('js-srctxt-new');
         el.innerHTML = linkify( el.innerHTML );

         var psh = el.innerHTML;
         el.innerHTML = '';
         pastehtmlatcaret(psh);
         set_html_class('js-new_countdown', addzero( ( 140 - ccc.str.twttr.txt.getTweetLength( get_html_id('js-srctxt-new') )) ,3) );
         console.log('text: ' + get_html_id('js-srctxt-new') );
     ccc.str.tw.tm[9] = 0;
  }
}

*/
