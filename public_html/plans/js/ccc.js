/*! ccc.js v1.00.00 
| (c) 2015 crowdcc. 
| AJAX  * postdata(url, callback, header, data) * getdata(url)
| DOM
| addloadevent(func)
| CSS
| set_css_id(elem, option, value)
| get_css_id(elem, option, value)
| set_attrib_id(elem, attrib, value)
| set_prop_id(elem, prop)
| get_prop_id(elem, prop)
| visible_css_id(elem)
| visible_css_class(elem)
| set_css_class(elem, option, value)
| set_css_tag(elem, option, value)
| has_css_class(elem, cls)
| clear_css_class(elem, cls)
| add_css_class(elem, cls)
| set_css_all(selector, property, value)
| HTML 
| set_html_id(eid, value)
| set_html_val(eid, value)
| get_html_id(eid) 
| set_html_class(elem, value)
| clear_html_id(elem)
| SCRIPT
| clear_obj(cobj)
| get_obj(cobj)
| CONTROLS
| function isradio()
| TOGGLES
| toggle_css(elem, option)
| MISC
| trimspace(str)
| trimquote(str)
| isempty(val)
| isblank(val)
| isemail(val)
| base64 encode * decode
| WEB APP * MISC
| addzero (n, length) 
| movecursortoend(el)
| crowdcc.com/use */

'use strict';

/* XMLHTTP */

/* post data */

function postdata(url, callback, header, postdata, postfrom, boundary) { 

  var xmlhttp = createXMLHttp();

  xmlhttp.open('post', url, true);
  /* you can modify the post headers if needed */

  // xmlhttp.setRequestHeader("Cache-Control", "no-cache");
  // xmlhttp.setRequestHeader("Access-Control-Allow-Origin", "*"); /* for local testing - remove later */
  // xmlhttp.setRequestHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS, DELETE, PUT, HEAD");
  // xmlhttp.setRequestHeader("Access-Control-Max-Age", "1728000");
  // xmlhttp.setRequestHeader('X-Requested-With','XMLHttpRequest');  

  switch (header) {

    case ('text'):
          xmlhttp.setRequestHeader("Content-Type", "text/plain");
          xmlhttp.send(postdata);
    break;
    case ('url'):
          xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xmlhttp.send(postdata);
    break;
    case ('img'):
          // xmlhttp.setRequestHeader("Content-Type", "multipart/form-data; boundary="+boundary);
          xmlhttp.setRequestHeader("Content-Type", "multipart/form-data; boundary=d67b3e07c45e");
          xmlhttp.send(postdata);
    break;
    case ('json'):
          xmlhttp.setRequestHeader("Accept", "application/json, text/javascript, */*; q=0.01");
          xmlhttp.setRequestHeader("Content-Type", "application/json");   
          xmlhttp.send(JSON.stringify(postdata));
    break;

    default:
          xmlhttp.setRequestHeader("Content-Type", "text/plain");
          xmlhttp.send(postdata);
  }  
  
  xmlhttp.onreadystatechange = function() {

  if (xmlhttp.status === 200) {
      if (xmlhttp.readyState === 4) {
          callback('post:*:'+ postfrom + ':*:' + xmlhttp.responseText);
          xmlhttp = null;
          /* 
          console.log('xmlhttp.readyState ->' + xmlhttp.readyState);
          console.log('xmlhttp.status ->' + xmlhttp.status); 
          console.log('xmlhttp.responseText ->' + xmlhttp.responseText); 
          */
          
      }
  } 

  else if (xmlhttp.status !== 200) {

           /*
           console.log('xmlhttp.readyState ->' + xmlhttp.readyState);
           console.log('xmlhttp.status ->' + xmlhttp.status);
           console.log('xmlhttp.responseText ->' + xmlhttp.responseText); 
           */

           callback('error:*:'+ postfrom + ':*:' + xmlhttp.status);
           console.log('xmlhttp.status error code ->' + xmlhttp.status);
           xmlhttp = null;
  }

 };

}


/* get data */

function getdata(url, callback, header, getfrom) {
  var xmlhttp = createXMLHttp();

  xmlhttp.open('get', url, true);
   /* you can modify the get headers if needed */

  // xmlhttp.setRequestHeader("Cache-Control", "no-cache");
  // xmlhttp.setRequestHeader("Access-Control-Allow-Origin", "*"); /* for local testing - remove later */
  // xmlhttp.setRequestHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS, DELETE, PUT, HEAD");
  // xmlhttp.setRequestHeader("Access-Control-Max-Age", "1728000"); 

   switch (header) {

    case ('text'):
          xmlhttp.setRequestHeader("Content-Type", "text/plain");
    break;
    case ('url'):
          xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    break;
    case ('json'):
          xmlhttp.setRequestHeader("Content-Type", "application/json");
    break;

    default:
          xmlhttp.setRequestHeader("Content-Type", "text/plain");
  }  
  
  xmlhttp.send(null);

  var requesttimer = setTimeout(function() {
     xhReq.abort();
      /* handle timeout situation, e.g. retry or inform user. 2000 === 2 seconds */
   }, 2000);

  xmlhttp.onreadystatechange = function() {
 
  if (xmlhttp.status === 200) {
      if (xmlhttp.readyState === 4) {
          clearTimeout(requesttimer);
          callback('get:*:'+ getfrom + ':*:' + xmlhttp.responseText);
          xmlhttp = null;
          /* 
          console.log('xmlhttp.readyState ->' + xmlhttp.readyState);
          console.log('xmlhttp.status ->' + xmlhttp.status);
          console.log('xmlhttp.responseText ->' + xmlhttp.responseText); 
          */
      }
  } 

  else if (xmlhttp.status !== 200) {
           callback('error:*:'+ getfrom + ':*:' + xmlhttp.status);
           console.log('xmlhttp.status error code ->' + xmlhttp.status);
           xmlhttp = null;
  }

 };

}


function createXMLHttp() {
  // if XMLHttpRequest is available then using it
  if (typeof XMLHttpRequest !== undefined) {
    return new XMLHttpRequest;
  // if window.ActiveXObject is available than the user is using IE...so we have to create the newest version XMLHttp object
  } else if (window.ActiveXObject) {
    var ieXMLHttpVersions = ['MSXML2.XMLHttp.5.0', 'MSXML2.XMLHttp.4.0', 'MSXML2.XMLHttp.3.0', 'MSXML2.XMLHttp', 'Microsoft.XMLHttp'],
        xmlhttp;
  // in this array we are starting from the first element (newest version) and trying to create it.
  // if there is an exception thrown we are handling it (and doing nothing ^^)
    for (var i = 0; i < ieXMLHttpVersions.length; i++) {
      try {
        xmlhttp = new ActiveXObject(ieXMLHttpVersions[i]);
        return xmlhttp;
      } catch (e) {
      }
    }
  }
  ieXMLHttpVersions = null;
}


/* DOM STATE */

/* 
   
   function addloadevent(func)
   http://stackoverflow.com/questions/3698200/window-onload-vs-document-ready

   how to use: 
     
   example use: function addloadevent(func)
   
   addLoadEvent(nameOfSomeFunctionToRunOnPageLoad);
   addLoadEvent(function() {
   
   // more code to run on page load
   
   });

*/

function addloadevent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
      window.onload = func;
  } else {
      window.onload = function() {
      if (oldonload) {
          oldonload();
      }
      func();
      }
  }
  oldonload = null;
}


/* CSS */

/* id set */

function set_css_id(elem, option, value) {
  var elemcol = document.getElementById(elem);
  if (typeof elemcol !== 'undefined' && elemcol !== null) {
    switch (option) {

      case ('z-index'):
             elemcol.style.zIndex = value;
      break;
      case ('display'):
             elemcol.style.display = value;
      break;
      case ('visibility'):
             elemcol.style.visibility = value;
      break;
      case ('margin-top'):
             elemcol.style.marginTop = value;
      break;
      case ('padding-top'):
             elemcol.style.paddingTop = value;
      break;
      case ('position'):
             elemcol.style.position = value;
      break;
      case ('background'):
             elemcol.style.background = value;
      break;
      case ('backgroundColor'):
             elemcol.style.backgroundColor = value;
      break;
      case ('backgroundPosition'):
             elemcol.style.backgroundPosition = value;
      break;
      case ('borderColor'):
             elemcol.style.borderColor = value;
      break;
      case ('borderBottomColor'):
             elemcol.style.borderBottomColor = value;
      break;
      case ('top'):
             elemcol.style.top = value;
      break;
      case ('left'):
             elemcol.style.left = value;
      break;
      case ('height'):
             elemcol.style.height = value;
      break;
      case ('width'):
             elemcol.style.width = value;
      break;
      case ('opacity'):
             elemcol.style.opacity = value;
             /* filter: alpha(opacity = value); For IE8 and earlier */
      break;
      case ('visibility'):
             elemcol.style.opacity = value;
      break;
      case ('color'):
             elemcol.style.color = value;
      break;
      case ('focus'):
             elemcol.focus();
      break;
    }
  }
  elemcol = null;
}

function get_css_id(elem, option, value) {
  /* note check styles in-line not in css */
  var elemcol = document.getElementById(elem);
  var ivalue = '';
    switch (option) {

      case ('display'):
            ivalue = elemcol.style.display;
      break;
      
      case ('backgroundColor'):
            ivalue = elemcol.style.backgroundColor;
      break;
            
      case ('height'):
            ivalue = elemcol.style.height;
      break;

      case ('color'):
            ivalue = elemcol.style.color;
      break;

    }

  if (ivalue === value) {
      elemcol = null, ivalue = null;
      return true;
  }
  elemcol = null, ivalue = null;
  return false;
}

function get_css_val(elem, option) {
  var elemcol = document.getElementById(elem);
  if (typeof elemcol !== 'undefined' && elemcol !== null) {
  var ivalue = '';
      switch (option) {

          case ('backgroundColor'):
                ivalue = elemcol.style.backgroundColor;
          break;

          case ('height'):
                ivalue = elemcol.style.height;
          break;

      }
    return ivalue;
    elemcol = null, ivalue = null;
  }
}

function set_attrib_id(elem, attrib, value) {
  if (typeof elem !== 'undefined' && elem !== null) { 
      document.getElementById(elem).setAttribute(attrib, value);
  }
}

function clear_attrib_id(elem, attrib) {
  if (typeof elem !== 'undefined' && elem !== null) { 
      document.getElementById(elem).removeAttribute(attrib);
  }
}

function set_prop_id(elem, prop) {
  if (typeof elem !== 'undefined' && elem !== null) { 
    switch (prop) {
      case ('enabled'):
            document.getElementById(elem).disabled = false;
            /* document.getElementById(elemcol).removeAttribute('disabled'); */
      break;
      case ('disabled'):
            document.getElementById(elem).disabled = true;
            /* document.getElementById(elemcol).setAttribute('disabled','disabled'); */
      break;
    }
  }
}

function get_prop_id(elem, prop) {
   switch (prop) {
    case ('enabled'):
      if (document.getElementById(elem).disabled) {return false;} else {return true;}
    break;
    case ('disabled'):
      if (document.getElementById(elem).disabled) {return false;} else {return true;}
    break;
  }
}

function visible_css_id(elem) {
  /* for repeated operations, with no position fixed elements in search
     if (elem.offsetParent === null) { return true; } return false; } */
  var elemcol = document.getElementById(elem);
  var showcss = window.getComputedStyle(elemcol); /* for postion fixed elements too but slower */ 
  if (showcss.display === 'none') {
      elemcol = null;
      return false;
      showccs = null; 
  }
  elemcol = null
  return true;
  showccs = null;
}


/* class set */

function visible_css_class(elem) {
  /* for one class on its own * will fail with two or more */
  var elemcol = document.getElementsByClassName(elem);
  for (var i=0; i<elemcol.length; i++) { 
       if (elemcol[i].offsetWidth > 0 && elemcol[i].offsetHeight > 0) {
       elemcol = null; i = null;
       return true;
      }
  elemcol = null; i = null;
  return false;
  }
}

function set_css_class(elem, option, value) {
  var elemcol = document.getElementsByClassName(elem);
  if (typeof elemcol !== 'undefined' && elemcol !== null) {
    for (var i=0; i<elemcol.length; i++) { 
      switch (option) {

        case ('z-index'):
               elemcol[i].style.zIndex = value;
        break;
        case ('display'):
               elemcol[i].style.display = value;
        break;
        case ('opacity'):
               elemcol[i].style.opacity = value;
               elemcol[i].style.filter  = 'alpha(opacity='+(value * 100)+')'; // IE fallback
        break;
        case ('margin-top'):
               elemcol[i].style.marginTop = value;
        break;
        case ('padding-top'):
               elemcol[i].style.paddingTop = value;
        break;
        case ('margin-left'):
               elemcol[i].style.marginLeft = value;
        break;
        case ('margin-right'):
               elemcol[i].style.marginRight = value;
        break;
        case ('position'):
               elemcol[i].style.position = value;
        break;
        case ('background'):
               elemcol[i].style.background = value;
        break;
        case ('backgroundColor'):
               elemcol[i].style.backgroundColor = value;
        break;
        case ('borderBottomColor'):
               elemcol[i].style.borderBottomColor = value;
        break;
        case ('top'):
               elemcol[i].style.top = value;
        break;
        case ('left'):
               elemcol[i].style.left = value;
        break;
        case ('color'):
               elemcol[i].style.color = value;
        break;
        case ('opacity'):
               elemcol.style.opacity = value;
               filter: alpha(opacity = value); /* For IE8 and earlier */
        break;
        case ('float'):
               elemcol[i].style.cssFloat = value;
               elemcol[i].style.styleFloat = value;
        break;
      }
    }
  }
  elemcol = null; i = null;
}

/* tag set */

function set_css_tag(elem, option, value) {
  if (typeof elem !== 'undefined' && elem !== null) {
    switch (option) {

      case ('marginTop'):
            document.getElementsByTagName(elem)[0].style.marginTop = value;
      break;
    }
  }
}

function has_css_class(elem, cls) {
  if (typeof elem == "string") {elem = document.getElementById(elem);}
  if (!elem){ return false;}
  if (elem && typeof elem.classList != 'undefined') {
      return elem.classList.contains(cls);
  } else if (elem && (typeof elem.className != "undefined" || elem.getAttribute("class") !== null)) {
      return new RegExp('(^|\\s)' + cls + '(\\s|$)').test(elem.className || elem.getAttribute("class"));
  } else {
      return false;
  }
}

function clear_css_class(elem, cls) {
  var elemcol = document.getElementById(elem);
  if (typeof elemcol !== 'undefined' && elemcol !== null) {
      cls = cls || 'noclass';
      if (cls === 'noclass') {
          document.getElementById(elem).className = '';
      } else {
          document.getElementById(elem).className = '';
          document.getElementById(elem).className = cls;
      }
  }
  elemcol = null;
}

function add_css_class(elem, cls) {
  /* element must have an id */
  var elemcol = document.getElementById(elem);
  elemcol.className = elemcol.className + ' ' + cls;
  elemcol = null;
}

function set_css_all(selector, property, value) {
  for (var i=0; i<document.styleSheets.length;i++) { // loop through all styles
       // try add rule
       try { document.styleSheets[i].insertRule(selector+ ' {'+property+':'+value+'}', document.styleSheets[i].cssRules.length);
       } catch(err) {try { document.styleSheets[i].addRule(selector, property+':'+value);} catch(err) {}} //IE
  }
}


/* HTML */

/* html set id */

function set_html_id(eid, value) {
  var elem = document.getElementById(eid);
  if (typeof elem !== 'undefined' && elem !== null) {
      document.getElementById(eid).innerHTML = value;
  }
  elem = null;
}

function set_html_val(eid, value) {
  var elem = document.getElementById(eid);
  if (typeof elem !== 'undefined' && elem !== null) {
      elem.value = value;
  }
  elem = null;
}

/* html get id */

function get_html_id(eid) {
  var elem = document.getElementById(eid);
  if (typeof elem !== 'undefined' && elem !== null) {
      return document.getElementById(eid).innerHTML;
  }
  elem = null;
}

function get_html_val(eid) {
  var elem = document.getElementById(eid);
  if (typeof elem !== 'undefined' && elem !== null) {
      return document.getElementById(eid).value;      
  }
  elem = null;
}

/* html set class */

function set_html_class(elem, value) {
  var elemar = document.getElementsByClassName(elem);
  if (typeof elemar !== 'undefined' && elemar !== null) {
    for (var i = 0; i < elemar.length; i++){
         elemar[i].innerHTML = value;
    }
  }
  elemar = null;
}

/* html clear id : equivalent to jquery remove (you have been warned) */

function clear_html_id(elem) {
  var elemcol = document.getElementById(elem);
  if (typeof elemcol !== 'undefined' && elemcol !== null) {
      elemcol.parentNode.removeChild(elemcol);
  }
  elemcol = null;
}

/* html clear obj : delete obj ref, may iterate over objs in future */

function clear_obj(cobj) {
  if (typeof cobj === 'object' && cobj !== null) {
   for (var member in cobj){
        delete cobj[member];
   }
   cobj = {};  /* empty object check GC */
   cobj = null; member = null;
  }
}

function get_obj(cobj) {
  return cobj === Object(cobj);
}


/* CONTROLS */

/* is radio checked ? */

function isradio() {
    // return ($('input[type=radio]:checked').size() > 0);
    var isradio = null;
    var radios = document.getElementsByTagName('input');
    for (var i = 0; i < radios.length; i++) {
      if (radios[i].type === 'radio' && radios[i].checked) {
          // return allelems[i].value;
          isradio = radios[i].value;
      } 
    }
    return isradio;
    i = null; radios = null; isradio = null;
}


/* TOGGLES */

/* display hide and show */

function toggle_css(elem, option) {
  /* element must have an id */
  // console.log( visible_css_id(elem) );

  switch (option) {
    case ('display'):
          switch (true) {

             case (!visible_css_id(elem) ):
                    set_css_id(elem, option, 'block');
             break;
             case ( visible_css_id(elem) ):
                    set_css_id(elem, option, 'none');
             break;

          } 

    break;
    
  } 
   
}


/* MISC */

/* document title */

function set_doc_title(title) {
    document.title = title;
}

/* document background */

function set_css_background(color) {
    document.body.style.background = color;  
}

/* trimspace */

function trimspace(str) {
    if (typeof (str) !== 'undefined') {
    return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    }
}

/* trimquote */

function trimquote(str) {
    if (typeof (str) !== 'undefined') {
    return str.replace(/"/g, "");
    }
}

/* isempty */

function isempty(val){
    return (val === undefined || val == null || val.length <= 0) ? true : false;
}

/* isblank */

function isblank(str) {
    return (!str || /^\s*$/.test(str));
}

/* isemail */

function isemail(email) {
    /* simple validation in order to check format correct */
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

/* base64 encode * decode */

  var base64 = {
  /* private property */
  _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
  /* public method for encoding */
  encode : function (input) {
  var output = "";
  var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
  var i = 0;
  input = base64._utf8_encode(input);
  if (typeof input !== 'undefined') {  
      while (i < input.length) {
      chr1 = input.charCodeAt(i++);
      chr2 = input.charCodeAt(i++);
      chr3 = input.charCodeAt(i++);
      enc1 = chr1 >> 2;
      enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
      enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
      enc4 = chr3 & 63;
      if (isNaN(chr2)) { enc3 = enc4 = 64; } else if (isNaN(chr3)) { enc4 = 64;}
          output = output +
          base64._keyStr.charAt(enc1) + base64._keyStr.charAt(enc2) +
          base64._keyStr.charAt(enc3) + base64._keyStr.charAt(enc4);
      }
    }
      return output;
      output = null; chr1 = null; chr2 = null; chr3 = null; enc1 = null; enc2 = null; enc3 = null; enc4 = null; i = null;
  },
  /* public method for decoding */
  decode : function (input) {
  var output = "";
  var chr1, chr2, chr3;
  var enc1, enc2, enc3, enc4;
  var i = 0;
  if (typeof input !== 'undefined' && input !== null) {  
      input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
      while (i < input.length) {
      enc1 = base64._keyStr.indexOf(input.charAt(i++));
      enc2 = base64._keyStr.indexOf(input.charAt(i++));
      enc3 = base64._keyStr.indexOf(input.charAt(i++));
      enc4 = base64._keyStr.indexOf(input.charAt(i++));
      chr1 = (enc1 << 2) | (enc2 >> 4);
      chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
      chr3 = ((enc3 & 3) << 6) | enc4;
      output = output + String.fromCharCode(chr1);
      if (enc3 != 64) { output = output + String.fromCharCode(chr2); }
      if (enc4 != 64) { output = output + String.fromCharCode(chr3); }
      }
  }
      output = base64._utf8_decode(output);
      return output;
      output = null; chr1 = null; chr2 = null; chr3 = null; enc1 = null; enc2 = null; enc3 = null; enc4 = null; i = null;
  },
  /* private method for UTF-8 encoding */
  _utf8_encode : function (string) {
      
    if (typeof string !== 'undefined' && string !== null) {
      // ** encode this string, but first, why don't we just randomly normalize all the line breaks for no good reason at all   
      // string = string.replace(/\r\n/g,"\n");
     var utftext = "";
     for (var n = 0; n < string.length; n++) {
          var c = string.charCodeAt(n);
        if (c < 128) {
              utftext += String.fromCharCode(c);
        }
        else if((c > 127) && (c < 2048)) {
              utftext += String.fromCharCode((c >> 6) | 192);
              utftext += String.fromCharCode((c & 63) | 128);
        }
        else {
              utftext += String.fromCharCode((c >> 12) | 224);
              utftext += String.fromCharCode(((c >> 6) & 63) | 128);
              utftext += String.fromCharCode((c & 63) | 128);
        }
      }
    }
    return utftext;
    utftext = null; c = null;
  },
  /* private method for UTF-8 decoding */
  _utf8_decode : function (utftext) {
    var string = "";
    var i = 0;
    /* var c = c1 = c2 = 0; */
    var c = 0; var c1 = 0; var c2 = 0; var c3 = 0;
    while ( i < utftext.length ) {
          c = utftext.charCodeAt(i);
          if (c < 128) {
              string += String.fromCharCode(c);
              i++;
          }
          else if((c > 191) && (c < 224)) {
              c2 = utftext.charCodeAt(i+1);
              string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
              i += 2;
          }
          else {
              c2 = utftext.charCodeAt(i+1);
              c3 = utftext.charCodeAt(i+2);
              string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
              i += 3;
          }
      }
      return string;
      string = null; i = null; c = null; c1 = null; c2 = null; c3 = null; 
    }

}


/* WEB APP * MISC */

/* addzero (n, length) */

function addzero (n, length) {
    var str = (n > 0 ? n : -n) + "";
    var zeros = "";
    for (var i = length - str.length; i > 0; i--)
        zeros += "0";
    zeros += str;
    return n >= 0 ? zeros :  zeros;
    // return n >= 0 ? zeros : "-" + zeros;
    str = null; zeros = null; i = null;
}

/* movecursortoend(el) */

function movecursortoend(el) {
    if (typeof el.selectionStart == "number") {
        el.selectionStart = el.selectionEnd = el.value.length;
    } else if (typeof el.createTextRange != "undefined") {
        el.focus();
        var range = el.createTextRange();
        range.collapse(false);
        range.select();
    }
}

/* trashslash(str) */

function trashslash(str) {
   return str
   .replace(/\n{2,}/g, '\n')    /* remove mutiple newlines, replace with one */
   .replace(/\n/g, " ")         /* replace newline with single space */
   .replace(/[\"]/g, '\\\"');   /* replace (escape) double quote for JSON parse */
}

/* addslashes(str) */

function addslashes(str) {
   return str
    .replace(/[\\]/g, '\\\\')
    .replace(/[\"]/g, '\\\"')
    .replace(/[\']/g, '\\\'')
    .replace(/[\/]/g, '\\/') 
    .replace(/[\b]/g, '\\b')
    .replace(/[\f]/g, '\\f')
    .replace(/[\n]/g, '\\n')
    .replace(/[\r]/g, '\\r')
    .replace(/[\t]/g, '\\t');
};

/* stripslashes(str) */

function stripslashes(str) {
  /*     http://phpjs.org/functions/stripslashes */
  return (str + '')
    .replace(/\\(.?)/g, function(s, n1) {
      switch (n1) {
        case '\\':
          return '\\';
        case '0':
          return '\u0000';
        case '':
          return '';
        default:
          return n1;
      }
    });
}

/* htmlspecialchars(string, quote_style, charset, double_encode) */

function htmlspecialchars(string, quote_style, charset, double_encode) {

  //        example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
  //        returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
  //        example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES']);
  //        returns 2: 'ab"c&#039;d'
  //        example 3: htmlspecialchars('my "&entity;" is still here', null, null, false);
  //        returns 3: 'my &quot;&entity;&quot; is still here'

  var optTemp = 0,i = 0,noquotes = false;
  if (typeof quote_style === 'undefined' || quote_style === null) {
    quote_style = 2;
  }
  string = string.toString();
  if (double_encode !== false) { // Put this first to avoid double-encoding
    string = string.replace(/&/g, '&amp;');
  }
  string = string.replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');

  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  };
  if (quote_style === 0) {
    noquotes = true;
  }
  if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
    quote_style = [].concat(quote_style);
    for (i = 0; i < quote_style.length; i++) {
      // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
      if (OPTS[quote_style[i]] === 0) {
        noquotes = true;
      } else if (OPTS[quote_style[i]]) {
        optTemp = optTemp | OPTS[quote_style[i]];
      }
    }
    quote_style = optTemp;
  }
  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
    string = string.replace(/'/g, '&#039;');
  }
  if (!noquotes) {
    string = string.replace(/"/g, '&quot;');
  }

  optTemp = null; i = null; noquotes = null;
  return string;
}

/* _get_cookie(name) */

function _get_cookie(name) {
  if (typeof document.cookie !== 'undefined' && document.cookie !== null) {
     var i,x,y,arrcookies=document.cookie.split(";");
     for (i=0;i<arrcookies.length;i++) {
          x=arrcookies[i].substr(0,arrcookies[i].indexOf("="));
          y=arrcookies[i].substr(arrcookies[i].indexOf("=")+1);
          x=x.replace(/^\s+|\s+$/g,"");
      if (x==name) {
          return unescape(y);
      }
     }
     return 'cookie value unavailiable at this time';
  }
  i = null; x = null; y = null; arrcookies = null;
}


/* ~~~ CCC * END ~~~ */

