// Ajax Voting Script - http://coursesweb.net
var ivotings = Array();			// store the items with voting
var ar_elm = Array();	   	// store the items that will be send to votAjax()
var i_elm = 0;					// Index for elements aded in ar_elm
var itemvotin = '';       // store the voting of voted item
var votingfiles = 'votingfiles/';      // directory with files for script
var advote = 0;      // variable checked in addVote(), if is 0 cann vote, else, not

// gets all DIVs, store in $ivotings, and in $ar_elm DIVs with class: "vot_plus", "vot_updown", and ID="vt_..", sends to votAjax()
var getVotsElm = function () {
  var obj_div = document.getElementsByTagName('div');
  var nrobj_div = obj_div.length;
  for(var i=0; i<nrobj_div; i++) {
    // if contains class and id
    if(obj_div[i].className && obj_div[i].id) {
	    var elm_id = obj_div[i].id;
      // if class "vot_plus", "vot_updown1", "vot_updown2", or "vot_poll" and id begins with "vt_"
      if((obj_div[i].className=='vot_plus' || obj_div[i].className=='vot_updown1' || obj_div[i].className=='vot_updown2') && elm_id.indexOf("vt_")==0) {
        ivotings[elm_id] = obj_div[i];       // store object item in $ivotings

        ar_elm[i_elm] = elm_id;     // add item_ID in $ar_elm array, to be send in json string tp php
        i_elm++;             // index order in $ar_elm
      }
    }
  }
  // if there are elements in "ar_elm", send them to votAjax()
  if(ar_elm.length>0) votAjax(ar_elm, '');      // if items in $ar_elm pass them to votAjax()
};

// add the ratting data to element in page
function addVotData(elm_id, vote, nvotes, renot) {
  // exists elm_id stored in ivotings
  if(ivotings[elm_id]) {
    // sets to add "onclick" for vote up (plus), if renot is 0
    var clik_up = (renot == 0) ? ' onclick="addVote(this, 1)"' : ' onclick="alert(\'You already voted\')"';

    // if vot_plus, add code with <img> 'votplus', else, if vot_updown1/2, add code with <img> 'votup',  'votdown'
    if(ivotings[elm_id].className == 'vot_plus') {    // simple vote
      ivotings[elm_id].innerHTML = '<h4>'+ vote+ '</h4><span><img src="'+votingfiles+'votplus.gif" alt="1" title="Vote"'+ clik_up+ '/></span>';
    }
    else if(ivotings[elm_id].className=='vot_updown1') {   // up/down with total Votes
      // sets to add "onclick" for vote down (minus), if renot is 0
      var clik_down = (renot == 0) ? ' onclick="addVote(this, -1)"' : ' onclick="alert(\'You already voted\')"';

      ivotings[elm_id].innerHTML = '<div id="nvotes">Votes: <b>'+ nvotes+ '</b></div><h4>'+ vote+ '</h4><span><img src="'+votingfiles+'votup.png" alt="Vote Up" title="Vote Up"'+ clik_up+ '/> <img src="'+votingfiles+'votdown.png" alt="Vote Down" title="Vote Down"'+ clik_down+ '/></span>';
    }
    else if(ivotings[elm_id].className=='vot_updown2') {      // up/down with number of votes up and down
      var nvup = (nvotes*1 + vote*1) /2;       // number of votes up
      var nvdown = nvotes - nvup;              // number of votes down

      // sets to add "onclick" for vote down (minus), if renot is 0
      var clik_down = (renot == 0) ? ' onclick="addVote(this, -1)"' : ' onclick="alert(\'You already voted\')"';

      ivotings[elm_id].innerHTML = '<h4>'+ vote+ '</h4><span><img src="'+votingfiles+'votup.png" alt="Vote Up" title="Vote Up"'+ clik_up+ '/> <img src="'+votingfiles+'votdown.png" alt="Vote Down" title="Vote Down"'+ clik_down+ '/></span><div id="nupdown"><b id="nvup">'+ nvup+ '</b> &nbsp; &nbsp; <b id="nvdown">'+ nvdown+ '</b></div>';
    }
  }
}

// Sends data to votAjax(), that will be send to PHP to register the vote
function addVote(ivot, vote) {
  // if $advote is 0, can vote, else, alert message
  if(advote == 0) {
    var elm = Array();
    elm[0] = ivot.parentNode.parentNode.id;	     // gets the item-name that will be voted

    ivot.parentNode.innerHTML = '<i><b>Thanks</b></i>';
    votAjax(elm, vote);
  }
  else alert('You already voted');
}

/*** Ajax ***/

// create the XMLHttpRequest object, according to browser
function get_XmlHttp() {
  var xmlHttp = null;           // will stere and return the XMLHttpRequest

  if(window.XMLHttpRequest) xmlHttp = new XMLHttpRequest();     // Forefox, Opera, Safari, ...
  else if(window.ActiveXObject) xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");     // IE

  return xmlHttp;
}

// sends data to PHP and receives the response
function votAjax(elm, vote) {
  var cerere_http =  get_XmlHttp();		// get XMLHttpRequest object

  // define data to be send via POST to PHP (Array with name=value pairs)
  var datasend = Array();
  for(var i=0; i<elm.length; i++) datasend[i] = 'elm[]='+elm[i];
  // joins the array items into a string, separated by '&'
  datasend = datasend.join('&')+'&vote='+vote;

  cerere_http.open("POST", votingfiles+'voting.php', true);			// crate the request

  cerere_http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");    // header for POST
  cerere_http.send(datasend);		//  make the ajax request, poassing the data

  // checks and receives the response
  cerere_http.onreadystatechange = function() {
    if (cerere_http.readyState == 4) {
      // receives a JSON with one or more item:[vote, nvotes, renot]
      eval("var jsonitems = "+ cerere_http.responseText);

      // if jsonitems is defined variable
      if (jsonitems) {
        // parse the jsonitems object
        for(var votitem in jsonitems) {
          var renot = jsonitems[votitem][2];		// determine if the user can vote or not

          // if renot=3 displays alert that already voted, else, continue with the voting reactualization
           if(renot == 3) {
            alert("You already voted \n You can vote again tomorrow");
            window.location.reload(true);		// Reload the page
          }
          else addVotData(votitem, jsonitems[votitem][0], jsonitems[votitem][1], renot);	// calls function that shows voting
        }
      }

      // if renot is undefined or 2 (set to 1 NRVOT in voting.php), after vote, set $advote to 1
      if(vote != '' && (renot == undefined || renot == 2)) advote = 1;
	  }
  }
}

// this function is used to access the function we need after loading page
function addLoadVote(func) {
  var oldonload = window.onload; 

  // if the parameter is a function, calls it with "onload"
  // otherwise, adds the parameter into a function, and then call it
  if (typeof window.onload != 'function') window.onload = func;
  else { 
    window.onload = function() { 
      if (oldonload) { oldonload(); } 
      func();
    } 
  } 
}

addLoadVote(getVotsElm);       		// calls getVotsElm() after page loads