/*! terms.js v1.00.00 | (c) 2015 crowdcc. | KeyClick event | crowdcc.com/use */

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

  	case (tagclass === 'signon'):
          /* window.open('http://localhost/~macbook/crowdcc', '_blank'); */
          window.location.replace('http://'+ window.location.hostname );
  	break;
  	
  	case (tagclass === 'ccc-top'):
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

  }

  elem = null; tagclass = null; tagid = null; tagtxtlen = null; tagclass = null; tagtype = null;

 }