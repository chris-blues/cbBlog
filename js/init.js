document.addEventListener('DOMContentLoaded', function () {
  hljs.initHighlightingOnLoad();

  if (document.getElementById("langOT") != null) {
    OTon = document.getElementById("langOT").getAttribute("data-OTon");
    OToff = document.getElementById("langOT").getAttribute("data-OToff");
    var OT = document.getElementsByClassName("otswitch");
    for (i = 0; i < OT.length; i++) {
      OT[i].addEventListener("click", switchofftopic);
    }
    var offtopicSpans = document.getElementsByClassName("offtopic");
    if (offtopicSpans != null) {
      for (i = 0; i < offtopicSpans.length; i++) {
        offtopicSpans[i].style.display = "none";
      }
    }
  }

  var tagButtons = document.getElementsByClassName("tagButton");
  for (i=0; i<tagButtons.length; i++) {
    tagButtons[i].addEventListener("click", function() {
      insertAtCursor( this.getAttribute("data-valueOpen"), this.getAttribute("data-valueClose"), "post_text" );
    });
  }

  var smiley = document.getElementsByClassName("smiley");
  for (i=0; i < smiley.length; i++) {
    smiley[i].addEventListener("click", function () {
      insertAtCursor( this.getAttribute("data-id"), "" ,"post_text");
    });
  }

  if (document.getElementById("switchTagHelp") != null) {
    document.getElementById("switchTagHelp").addEventListener("click", showhideTagHelp );
  }
  if (document.getElementById("smileyButton") != null) {
    document.getElementById("smileyButton").addEventListener("click", showhideSmileys );
  }

  if (typeof(Storage) !== "undefined" && document.getElementById("post_name") != null) {
    loadLocalStorage();
  }
  if (document.getElementById("post_name") != null) {
    document.getElementById("post_name").addEventListener("blur", function() {
      saveLocalStorage("name", document.getElementById("post_name").value);
    });
  }
  if (document.getElementById("post_notificationTo") != null) {
    document.getElementById("post_notificationTo").addEventListener("blur", function() {
      saveLocalStorage("email", document.getElementById("post_notificationTo").value);
    });
  }
  if (document.getElementById("post_website") != null) {
    document.getElementById("post_website").addEventListener("blur", function() {
      saveLocalStorage("website", document.getElementById("post_website").value);
    });
  }
  if (document.getElementById("buttonClearForm") != null) {
    document.getElementById("buttonClearForm").addEventListener("click", function() {
      document.getElementById("post_name").value = '';
      document.getElementById("post_notificationTo").value = '';
      document.getElementById("post_website").value = '';
      document.getElementById("post_text").value = '';
    });
  }
  if (document.getElementById("buttonPreview") != null) {
    document.getElementById("buttonPreview").addEventListener("click", function() {
      if (document.getElementById("post_text").value.length > 0) {
        document.getElementById("job").value = "showPreview";
        document.getElementById("postCommentForm").action += "#commentForm";
        this.form.submit();
      } else {
        document.getElementById("post_text").style.boxShadow = "0px 0px 5px 1px red";
        document.getElementById("post_text").focus();
      }
    });
  }
  if (document.getElementById("buttonSend") != null) {
    document.getElementById("buttonSend").addEventListener("click", function() {
      var time = Math.floor(Date.now() / 1000);
      document.getElementById("postCommentForm").action += "#" + time;
      document.getElementById("post_time").value = time;
      document.getElementById("job").value = "addComment";
//       this.form.submit();
    });
  }
});
