
if (!Date.now) {
  Date.now = function() { return new Date().getTime(); }
}

function loadLocalStorage() {
  document.getElementById("post_name").value = localStorage.getItem("name");
  document.getElementById("post_notificationTo").value = localStorage.getItem("email");
  document.getElementById("post_website").value = localStorage.getItem("website");
}

function saveLocalStorage(field, value) {
  localStorage.setItem(field, value);
  console.log("field " + field + " = " + value);
}

var tagHelpShown = 0;
function showhideTagHelp () {
  if (tagHelpShown == 0) {
    $( "#tagHelp" ).slideDown( 1000 );
    tagHelpShown = 1;
  } else {
    $( "#tagHelp" ).slideUp( 1000 );
    tagHelpShown = 0;
  }
}

var smileysShown = 0;
function showhideSmileys() {
  var smileys = document.getElementById('smileys');
  if (smileysShown == 0) {
    $( "#smileys" ).slideDown( 1000 );
    smileysShown = 1;
  } else {
    $( "#smileys" ).slideUp( 1000 );
    smileysShown = 0;
  }
}

function insertAtCursor(open, close) {
  var myField = document.getElementById("post_text");
  //IE support
  if (document.selection) {
    myField.focus();
    sel = document.selection.createRange();
    sel.text = " " + open + close + " ";
  }
  //MOZILLA and others
  else if (myField.selectionStart || myField.selectionStart == '0') {
    var startPos = myField.selectionStart;
    var endPos = myField.selectionEnd;
    var stringBefore = myField.value.substring(0, startPos);
    var stringSelected = myField.value.substring(startPos, endPos);
    var stringAfter = myField.value.substring(endPos, myField.value.length);
    myField.value = stringBefore + " " + open + stringSelected + close + " " + stringAfter;
  } else {
    myField.value += " " + open + close + " ";
  }
  myField.focus();
  var finalEndPos = startPos + 1 + open.length + stringSelected.length + close.length + 1;
  setCaretPosition(finalEndPos);
}

function setCaretPosition(pos){
  var myField = document.getElementById("post_text");
  if(myField.setSelectionRange) {
    myField.focus();
    myField.setSelectionRange(pos,pos);
  } else if (myField.createTextRange) {
    var range = myField.createTextRange();
    range.collapse(true);
    range.moveEnd('character', pos);
    range.moveStart('character', pos);
    range.select();
  }
}

var OTshown = 0;
function switchofftopic() {
  if (OTshown == "1") {
    $( ".otswitch" ).innerHTML = OTon;
    $( "span.offtopic" ).slideUp( 1000 );
    OTshown = 0;
  } else {
    $( ".otswitch" ).innerHTML = OToff;
    $( "span.offtopic" ).slideDown( 1000 );
    OTshown = 1;
  }
}
