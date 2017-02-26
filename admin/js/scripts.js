var lastId = 0;

document.addEventListener('DOMContentLoaded', function () {

  var buttonComments = document.getElementsByClassName("buttonComments");
  if (buttonComments != null) {
    for (i = 0; i < buttonComments.length; i++) {
      buttonComments[i].addEventListener("click", function() {
        document.getElementById("formCommentsId").value = this.getAttribute("data-id");
        document.getElementById("formComments").submit();
      });
    }
  }

  var buttonEditBlog = document.getElementsByClassName("editBlog");
  if (buttonEditBlog != null) {
    for (i = 0; i < buttonEditBlog.length; i++) {
      buttonEditBlog[i].addEventListener("click", function() {
        document.getElementById("formBlogId").value = this.getAttribute("data-id");
        document.getElementById("formEditBlog").submit();
      });
    }
  }

  var buttonSaveComment = document.getElementById("buttonSave");
  if (buttonSaveComment != null) {
    buttonSaveComment.addEventListener("click", function() {
      id = document.getElementById("data").getAttribute("data-id");
      saveComment(id);
    });
  }

  var buttonResetComment = document.getElementById("buttonReset");
  if (buttonResetComment != null) {
    buttonResetComment.addEventListener("click", function() {
      id = document.getElementById("data").getAttribute("data-id");
      resetComment(id);
    });
  }

  var tagButtons = document.getElementsByClassName("tagButton");
  for (i=0; i<tagButtons.length; i++) {
    tagButtons[i].addEventListener("click", function() { insertAtCursor( this.getAttribute("data-valueOpen"), this.getAttribute("data-valueClose") ); });
  }

  if (document.getElementById("smileyButton") != null) {
    document.getElementById("smileyButton").addEventListener("click", showhideSmileys );
  }

  var smiley = document.getElementsByClassName("smiley");
  for (i=0; i < smiley.length; i++) {
    smiley[i].addEventListener("click", function () { insertAtCursor( this.getAttribute("data-id"), "" ); });
  }

  var commentText = document.getElementsByClassName("commentText");
  if (commentText != null) {
    for (i = 0; i < commentText.length; i++) {
      commentText[i].addEventListener("click", function() { toggleCommentEditor(this.getAttribute("data-id")); });
    }
  }

  if ( document.getElementById("buttonBack") != null ) {
    var backLink = document.getElementById("navLinks").getAttribute("data-backLink");
    document.getElementById("buttonBack").addEventListener("click", function() { window.location.href = backLink; });
  }

  if (document.getElementById("buttonEditBlogpost") != null) {
    var editLink = document.getElementById("navLinks").getAttribute("data-editLink");
    document.getElementById("buttonEditBlogpost").addEventListener("click", function() { window.location.href = editLink; });
  }

  if (document.getElementById("buttonViewBlogpost") != null) {
    var viewLink = document.getElementById("navLinks").getAttribute("data-viewLink");
    document.getElementById("buttonViewBlogpost").addEventListener("click", function() { window.location.href = viewLink; });
  }

  var tags = document.getElementsByClassName("linkTags");
  for (i = 0; i < tags.length; i++) {
    tags[i].addEventListener("click", function() {
      id = this.getAttribute("data-id");
      addTag(id);
    });
   }
});

function addTag(id) {
  if (document.getElementById('tags').value.length < 1) {
    document.getElementById('tags').value = id;
  }
  else {
    document.getElementById('tags').value += ' ' + id;
  }
}

function toggleBlogPost(id) {
  $("#" + lastId).slideUp(750);

  lastId = id;
  if (document.getElementById(id).style.display == "none") {
    $("#" + id).slideDown(750);
  } else {
    $("#" + id).slideUp(750);
  }
}

function saveComment(id) {
  console.log("saveComment(" + id + ") has fired");
  oldText = document.getElementById("data").getAttribute("data-text");
  newText = document.getElementById("post_text").value;

  form = document.getElementById("formEditComment");
  ID = document.getElementById("commentId");
  text = document.getElementById("commentText");

  if (oldText != newText) {
    console.log(oldText + " != " + newText);
    ID.value = id;
    text.value = newText;
    form.action += "#" + id;
    form.submit();
  } else {
    console.log(oldText + " == " + newText);
    ID.value = "";
    text.value = "";
  }
}

function resetComment(id) {
  oldText = document.getElementById("data").getAttribute("data-text");
  document.getElementById("post_text").value = oldText;
}

function toggleCommentEditor(id) {
  parent = document.getElementById("wrapper_" + id);
  parent.appendChild(document.getElementById("editor"));
  var text = document.getElementById(id).getAttribute("data-text");
  document.getElementById("post_text").value = text;
  var data = document.getElementById("data");
  data.setAttribute("data-id", id);
  data.setAttribute("data-text", text);
}


















