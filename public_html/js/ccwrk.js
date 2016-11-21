/*! ccwrk.js v1.00.00 
| (c) 2015 crowdcc. 
| start_wotch() * load_more() * stop_wotch()
| crowdcc.com/use */

function start_wotch() {

    var currentTime = new Date()
    $("#time").html(currentTime.getHours() + ":" + currentTime.getMinutes() + ":" + currentTime.getSeconds());
    
    if (typeof(Worker)!=="undefined"){
        worker = new Worker("js/ccwotch.js");
        worker.onmessage = function(event) { $("#timeR").html(event.data); };
        worker.postMessage('start');    
    } else {  
        $.getScript('js/ccwatch.js', function(){
        start_watch();   
        });
   }

}

function load_more() {

  if (typeof(Worker)!=="undefined"){
       worker.postMessage('more');
       // worker.terminate();
  // } else {
       //clearTimeout(tz);  
  }

}

function stop_wotch() {

  if (typeof(Worker)!=="undefined"){ 
      worker.postMessage('stop');
      // worker.terminate();
  //} else {
      //stop_watch();
      // clearTimeout(tz);

  }

}
