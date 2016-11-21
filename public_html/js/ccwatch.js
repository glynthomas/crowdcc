/*! ccwatch.js v1.00.00 
| (c) 2015 crowdcc. 
| 15 mins API hit window, 1 a minute or ideally less than this * regulate API hit, by a static hit every 2min, so 7 hits every 15mins * dynamic (load more button) leaves 6 (max 7) hits availiable
| total hits must not exceed 15 in 15 minutes, but this is set to 14 * load more button, will load locally first with a small de-bounce, ideal * scenario would be 4 hits locally, then 1 large API hit, then 4 hits locally, repeat pattern * if all the local (more button) hits have been used, a contimued direct API (backward records) hit(s) must be regulated in order to avoid API vendor issues.
| crowdcc.com/use */


'use strict';

/* nested children for store.js */
ccc.ccw = ccc.ccw || {};


/* global ccwatch vars */

ccc.ccw.tidcc = null;           /* timeout ID for global cc watch */
ccc.ccw.tidcs = null;           /* timer ID for carbon store messages etc */

ccc.ccw.timemark = new Date();  /* reference time mark */
ccc.ccw.timeleft = 0;           /* relative time left */
ccc.ccw.freq = 1000;            /* 1000 == 1 second */

ccc.ccw.lm = 1;                 /* load more (backward polling api hits) couple of seconds delay added to to request */
ccc.ccw.mp = 0;                 /* more page (ccc.ccw.mp - more page flag) */

ccc.ccw.ll = 1;                 /* load local delay */
ccc.ccw.ml = 0;                 /* more local page (ccc.ccw.ml - more local page flag) */

ccc.ccw.apihits = 0;            /* api hits   */
ccc.ccw.calhits = 0;            /* local hits */
ccc.ccw.flameon = 0;            /* ccc.ccw.flameon * signin completed flag */

/* window.apimsg = '';          /* tmp global var for display purposes only */  

function init_cc() {

    ccc.str.tw.ts[0] = 0;
    ccc.str.tw.ls[0] = 0;
    startcc();            
}

function load_more() {

  switch (true) {

    case (ccc.ccw.apihits > 6):
          /* apimsg = 'reached'; */
          console.log('dynamic api hits reached, 7 static hits left (less than 15 in 15 mins)');
          return false;
    break;

    case (ccc.ccw.apihits < 6):
          /* --bak api hit-- */            
            
          ccc.ccw.mp = 1;

          /* --------------- */
    break;         

    case (ccc.ccw.apihits > 2 ):
          /* --bak api hit slow request-- */            
           
          ccc.ccw.lm = 2;
          ccc.ccw.mp = 1;

          /* ---------------------------- */
    break;
    
  }
}

function load_local() {ccc.ccw.ml = 1;}

function stopcc() {
    
    if (typeof ccc.str.tw.ts[0] === "undefined") { ccc.str.tw.ts[0] = 1;}
    ccc.str.tw.ts[0] = 1;
    clearTimeout(ccc.ccw.tidcc);
    ccc.str.tw.tg[0] = ccc.str.tw.tg[0] +1;
}

function startcc() {

    var mi = Math.floor(ccc.str.tw.tg[0] / 60);
    var se = ccc.str.tw.tg[0] - mi * 60;
    /* var apimsg = 'normal'; */
    ccc.ccw.freq = 1000;                            /* 1000 == 1 second */

    switch (true) {

      case (ccc.str.tw.tg[0] === 0):
      
            /* refresh * mitigate sluggish response near page linits * if in timeline view */
            if (get_css_id('in', 'display', 'block')) { window.location.href = window.location.href;}

            ccc.str.tw.tg[0] = 900;                 /* global time (15 mins) */
            ccc.str.tw.tp[0] = 120;                 /* api time (get more fwd content) (every 3 mins or so ...) */
            ccc.ccw.apihits = 0;
            ccc.ccw.freq = 1000;                    /* 1000 == 1 second */
            if (ccc.ccw.apihits > 0) {ccc.ccw.apihits = 0};
            /* apimsg = 'normal'; */
      break;

      case (ccc.ccw.lm === 0):
            /* --bak api hit-- */
            console.log('bak api hit * currently enabled !');
            
            get_in_bkobj();
                     
            /* --------------- */
            ccc.ccw.lm = 1;
            ccc.ccw.apihits = ccc.ccw.apihits + 1
            ccc.ccw.mp = 0;
      break;

      case (ccc.ccw.ll === 0):
            /* --bak local hit-- */
            console.log('bak local hit * currently enabled !');

            readtwin_obj(ccc.str.tw.sp[0]);

            /* --------------- */
            ccc.ccw.ll = 1;
            ccc.ccw.calhits = ccc.ccw.calhits + 1
            ccc.ccw.ml = 0;
      break;

      case (ccc.str.tw.tp[0] === 120):
            /* --fwd api hit-- */
            console.log('fwd api hit * currently enabled !');
            
            ccc.str.tw.ts[0] = 3                  /* disable more arrow down button in order to prevent timing clash issue */
            
            get_in_fwobj();

            ccc.str.tw.ts[0] = 0                  /* enable more arrow down button */

            /* --------------- */
            ccc.ccw.apihits = ccc.ccw.apihits + 1
      break;

      case (ccc.str.tw.tp[0] === 0):
            /* --fwd api hit-- */
            console.log('fwd api hit * curently enabled !');
            
            ccc.str.tw.ts[0] = 3                  /* disable more arrow down button in order to prevent timing clash issue */

            get_in_fwobj();

            ccc.str.tw.ts[0] = 0                  /* enable more arrow down button */

            /* --------------- */
            ccc.ccw.apihits = ccc.ccw.apihits + 1
            ccc.str.tw.tp[0] = 120;
      break;

    }

    if (typeof ccc.str.tw.tg[0] === "undefined" || ccc.str.tw.tg[0] === "ccc") {    /* api timer init */
        ccc.str.tw.tg[0] = 900;                                             /* global time (15 mins) */
        ccc.str.tw.tp[0] = 120;                                             /* api time (get more fwd content) (every 3 mins or so ...) */
        ccc.str.tw.ls[0] = 0;
        ccc.ccw.apihits = 0;
        ccc.ccw.freq = 1000;
        if (ccc.ccw.apihits > 0) {ccc.ccw.apihits = 0};
        /* apimsg = 'normal'; */
    } 
  
    switch (true) {
    
      case (ccc.ccw.mp === 1):
            ccc.ccw.lm--;
      break;

    }

    switch (true) {
    
      case (ccc.ccw.ml === 1):
            ccc.ccw.ll--;
      break;

    }

    switch (true) {

      case (ccc.str.tw.ts[0] === 1):
           
            /* set_html_id('ccwatch', mi+':'+se+':'+ccc.str.tw.tp[0]+':'+ccc.ccw.lm+':'+ccc.ccw.apihits+':'+apimsg+':'+ccc.ccw.ll+':local:'+ccc.ccw.calhits+':store:'+ ccc.str.tw.ls[0]); */

            return false;
      break;

      case (ccc.str.tw.ts[0] === 0):

            ccc.str.tw.tp[0]--;
            ccc.str.tw.tg[0]--;

            /* set_html_id('ccwatch', mi+':'+se+':'+ccc.str.tw.tp[0]+':'+ccc.ccw.lm+':'+ccc.ccw.apihits+':'+apimsg+':'+ccc.ccw.ll+':local:'+ccc.ccw.calhits+':store:'+ ccc.str.tw.ls[0]); */             

            ccc.ccw.tidcc = setTimeout( function() { startcc(); } ,ccc.ccw.freq );

            if ( typeof (sessionStorage.getItem('_cc.fi.0')) !== 'undefined' && (sessionStorage.getItem('_cc.fi.0')) !== null ) {
                 ccc.ccw.flameon = 1;
            }

            if ( sessionStorage.length === 0 && ccc.ccw.flameon === 1 ) {
                 console.log('we think session cleared while signed in * clear recent history!');
                 exitcc();
                _signout();   
            }
            
            if ( ccc.ccw.flameon === 1 && get_css_id('in', 'display', 'none') && get_css_id('cc', 'display', 'none') ) {
                 console.log('we think session cleared while signed in * restart browser!');
                 exitcc();
                _signout(); 
            } 
                     
      break;
    }

}


function pausecc() {
  /* instantiate the ccc.ccw.timeleft variable to be the same as the amount of time the timer initially had */
  ccc.str.tw.ts[0] = 1;
  ccc.ccw.timeleft = ccc.ccw.freq;  
  ccc.ccw.timeleft -= new Date() - ccc.ccw.timemark;
  /* clear the timer */
  clearTimeout( ccc.ccw.tidcc );
  console.log(ccc.ccw.timeleft);
}


function resumecc() {
  
  if (ccc.str.tw.ts[0] !== 0) { 
   ccc.str.tw.ts[0] = 0;
   console.log('check for zero, prevent mutiple resumecc');
   /* if( !ccc.ccw.timeleft ) { ccc.ccw.timeleft = ccc.ccw.freq; } */
   if( ccc.ccw.timeleft !== 0 ) { ccc.ccw.timeleft = ccc.ccw.freq; }
       console.log('save by zero');  
       ccc.ccw.tidcc = setTimeout( function() { startcc(); }, ccc.ccw.timeleft );
   }
  console.log(ccc.ccw.timeleft);
}


function resetcc() {
  ccc.str.tw.ts[0] = 0;
  ccc.str.tw.tg[0] = 900;
  ccc.str.tw.tp[0] = 120;
  ccc.ccw.lm = 1;
  ccc.ccw.apihits = 0;
  /* apimsg = 'normal'; */
  /* clear the timer */
  clearTimeout(ccc.ccw.tidcc);  
  /* set_html_id('ccwatch', '00:00:00:0:0:normal'); */

}

function exitcc() {
  ccc.str.tw.ts[0] = 0;
  ccc.str.tw.tg[0] = 900;
  ccc.str.tw.tp[0] = 120;
  ccc.ccw.lm = 1;
  ccc.ccw.apihits = 0;
  /* apimsg = 'normal'; */
  /* clear the timer IDs */
  clearTimeout(ccc.ccw.tidcc);
  clearTimeout(ccc.ccw.tidcs);
  ccc.ccw.tidcc = null;
  ccc.ccw.tidcs = null;   
}

/* 
function testcc() {
  console.log('we are testing the tweet message')
  message_('message_close', 'now');
}
*/
