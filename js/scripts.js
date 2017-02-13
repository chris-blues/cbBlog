document.addEventListener('DOMContentLoaded', function ()
  {
   hljs.initHighlightingOnLoad();
   OTon = document.getElementById("langOT").getAttribute("data-OTon");
   OToff = document.getElementById("langOT").getAttribute("data-OToff");
   var OT = document.getElementsByClassName("otswitch");
   for (i = 0; i < OT.length; i++) {
     OT[i].addEventListener("click", switchofftopic);
   }

   var tagButtons = document.getElementsByClassName("tagButton");
   for (i=0; i<tagButtons.length; i++) {
     tagButtons[i].addEventListener("click", function() { insertAtCursor( this.getAttribute("data-valueOpen"), this.getAttribute("data-valueClose") ); });
   }

   var smiley = document.getElementsByClassName("smiley");
   for (i=0; i < smiley.length; i++) {
     smiley[i].addEventListener("click", function () { insertAtCursor( this.getAttribute("data-id"), "" ); });
   }

   document.getElementById("switchTagHelp").addEventListener("click", showhideTagHelp );
   document.getElementById("smileyButton").addEventListener("click", showhideSmileys );

   if (typeof(Storage) !== "undefined") {
      loadLocalStorage();
     }
   document.getElementById("post_name").addEventListener("blur", function() { saveLocalStorage("name", document.getElementById("post_name").value); });
   document.getElementById("post_notificationTo").addEventListener("blur", function() { saveLocalStorage("email", document.getElementById("post_notificationTo").value); });
   document.getElementById("post_website").addEventListener("blur", function() { saveLocalStorage("website", document.getElementById("post_website").value); });
   document.getElementById("buttonPreview").addEventListener("click", function() { document.getElementById("switchPreview").value = "1"; this.form.submit(); });
   document.getElementById("buttonSend").addEventListener("click", function() { document.getElementById("switchPreview").value = "0"; this.form.submit(); });
  });


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
  }
  else {
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
  }
  else {
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
   }
  else {
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
   }
  else if (myField.createTextRange) {
    var range = myField.createTextRange();
    range.collapse(true);
    range.moveEnd('character', pos);
    range.moveStart('character', pos);
    range.select();
   }
}

var OTshown = 0;
function switchofftopic() {
  switches = document.getElementsByClassName("otswitch");

  if (OTshown == "1") {
    for (i=0; i<switches.length; i++) {
      switches[i].innerHTML = OTon;
     }
    $( "span.offtopic" ).slideUp( 1000 );
    OTshown = 0;
   }
  else {
    for (i=0; i<switches.length; i++) {
      switches[i].innerHTML = OToff;
     }
    $( "span.offtopic" ).slideDown( 1000 );
    OTshown = 1;
   }
}