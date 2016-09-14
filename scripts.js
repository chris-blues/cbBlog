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
   var OT = document.getElementsByClassName("otswitch");
   for (i = 0; i < OT.length; i++) {
     OT[i].addEventListener("click", switchofftopic);
   }

   var smiley = document.getElementsByClassName("smiley");
   for (i=0; i < smiley.length; i++) {
     smiley[i].addEventListener("click", function () { enterSmiley( this.getAttribute("data-id") ); });
   }

   document.getElementById("smileyButton").addEventListener("click", showhideSmileys);
  });


shown = 0;
function switchofftopic() {
  if (shown == "1") {
    $( "span.offtopic" ).fadeOut( 1000 );
    shown = 0;
   }
  else {
    $( "span.offtopic" ).fadeIn( 1000 );
    shown = 1;
   }
}
