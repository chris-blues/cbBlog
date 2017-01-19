function showhideSmileys()
      {
       var smileys = document.getElementById('smileys');
       if (smileys.style.display == "none" | smileys.style.display == "")
         { smileys.style.display = "block"; }
       else
         { smileys.style.display = "none"; }
      }
function enterSmiley(number)
      {
       var area = document.getElementById('post_text');
       area.value += "&#" + number + ";";
      }

document.addEventListener('DOMContentLoaded', function ()
  {
   hljs.initHighlightingOnLoad();
   OTon = document.getElementById("langOT").getAttribute("data-OTon");
   OToff = document.getElementById("langOT").getAttribute("data-OToff");
   var OT = document.getElementsByClassName("otswitch");
   for (i = 0; i < OT.length; i++) {
     OT[i].addEventListener("click", switchofftopic);
   }

   var smiley = document.getElementsByClassName("smiley");
   for (i=0; i < smiley.length; i++) {
     smiley[i].addEventListener("click", function () { enterSmiley( this.getAttribute("data-id") ); });
   }

   document.getElementById("post_name").addEventListener("click", function() { document.getElementById("post_name").value = ''; });
   document.getElementById("smileyButton").addEventListener("click", showhideSmileys);
  });

var shown = 0;
function switchofftopic() {
  switches = document.getElementsByClassName("otswitch");

  if (shown == "1") {
    for (i=0; i<switches.length; i++) {
      switches[i].innerHTML = OTon;
     }
    $( "span.offtopic" ).fadeOut( 1000 );
    shown = 0;
   }
  else {
    for (i=0; i<switches.length; i++) {
      switches[i].innerHTML = OToff;
     }
    $( "span.offtopic" ).fadeIn( 1000 );
    shown = 1;
   }
}
