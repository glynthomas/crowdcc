

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
  	case (tagclass === 'ccc-top'):
    case (tagclass === 'create'):
    case (tagclass === 'instore'):
    case (tagclass === 'ccstore'):
          window.location.replace('http://'+ window.location.hostname );
          
    break;

    case (tagclass === 'ui-dialog-noticebar-close'):

          console.log('we are now here ;-) you fucking piece of shit ...');
          set_css_class('error', 'display', 'none');
          console.log('line 5476 notices display none!');
          set_css_class('notices', 'display', 'none');

          window.location.href = window.location.href;
          return true;

    break;

  }

  elem = null; tagclass = null; tagid = null; tagtxtlen = null; tagclass = null; tagtype = null;

 }