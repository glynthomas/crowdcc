/*! ccwotch.js v1.00.00 
| (c) 2015 crowdcc. 
| ccwatch
| crowdcc.com/use */

var tid;

var gt = 900;  // global time (15 mins)
var pt = 120;  // api time (get more fwd content) (every 3 mins or so ...)

var pd = 80;   // api time increased to balance out he api hit requests to 15 every 15 mins

var lm = 2;    // load more (backward polling api hits) couple of seconds delay added to to request
var mp = 0;    // more page (mp - more page flag)

var apihits = 0;
var calhits = 0;

 self.onmessage = function(e) {

	 switch (true) {

	 	case(e.data == 'start'):
             ccwatch();
             console.log('start');
	 	break;

      case(e.data == 'more'):
            
             if (apihits != 14 && (Math.floor(gt / 60) != 15)) {
                   switch (true) {          
                     case (apihits > 5):
                        // pt = 120;
                           lm = 4;
                     break;
                   }
                           mp = 1;
            }  else {
                           apihits = 'api hits exceeded ... !';
            }
        
      break;

	 	case(e.data == 'stop'):
			  clearTimeout(tid);
			 // self.close();
	 	break;
	 }

 };
	


function ccwatch() {

    var freq = 1000;
    var mi = Math.floor(gt / 60);
    var se = gt - mi * 60;

    switch (true) {

      case (gt == 0):
            gt = 900;
            pt = 120;
            apihits = 0;
            freq = 0;
      break;

      case (lm == 0):
            lm = 2;
            apihits = apihits + 1
            // document.getElementById('apihits').value = apihits;
            mp = 0;
      break;

      case (pt == 0):
            apihits = apihits + 1
            // document.getElementById('apihits').value = apihits;
            pt = 120;
      break;

   }

   switch (true) {
    
      case (mp == 1):
            lm--;
      break;

   }

   pt--;
   gt--;

   // return output to Web Worker
   postMessage(mi+':'+se+':'+pt+':'+lm+':'+apihits);

   tid = setTimeout(function () { ccwatch(); },freq);
}


