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

  var buttonsTags = document.getElementsByClassName("blogpost_taglist");
  if (buttonsTags != null) {
    for (i=0; i<buttonsTags.length; i++) {
      buttonsTags[i].addEventListener("click", function() {
        removeTag(this.id);
        this.remove();
      })
    }
  }

  var buttonsAvailableTags = document.getElementsByClassName("blogpost_availableTags");
  if (buttonsAvailableTags != null) {
    for (i=0; i<buttonsAvailableTags.length; i++) {
      buttonsAvailableTags[i].addEventListener("click", function() {
        addTag(this.id);
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
    tagButtons[i].addEventListener("click", function() {
      insertAtCursor( this.getAttribute("data-valueOpen"), this.getAttribute("data-valueClose"), "post_text" );
    });
  }

  if (document.getElementById("smileyButton") != null) {
    document.getElementById("smileyButton").addEventListener("click", showhideSmileys );
  }

  var smiley = document.getElementsByClassName("smiley");
  if (document.getElementById("smileyTarget") != null) {
    var target = document.getElementById("smileyTarget").getAttribute("data-target");
  }
  for (i=0; i < smiley.length; i++) {
    smiley[i].addEventListener("click", function () { insertAtCursor( this.getAttribute("data-id"), "", target ); });
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
  oldText = document.getElementById("data").getAttribute("data-text");
  newText = document.getElementById("post_text").value;

  form = document.getElementById("formEditComment");
  ID = document.getElementById("commentId");
  text = document.getElementById("commentText");

  if (oldText != newText) {
    ID.value = id;
    text.value = newText;
    form.action += "#" + id;
    form.submit();
  } else {
    ID.value = "";
    text.value = "";
  }
}

function resetComment(id) {
  oldText = document.getElementById("data").getAttribute("data-text");
  document.getElementById("post_text").value = oldText;
  // put editor back to its parking lot
  document.getElementById("editor_wrapper").appendChild(document.getElementById("editor"));
  // clean up the comment field
  document.getElementById("post_text").value="";
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

function removeTag(tag) {
  var inputs = document.getElementsByClassName("tagFields");
  for (i=0; i<inputs.length; i++) {
    if (inputs[i].value == tag) {
      inputs[i].remove();
    }
  }
}

function addTag(tag) {
  var alreadyThere = false;
  var tags = document.getElementsByClassName("tagFields");
  for (i=0; i<tags.length; i++) {
    if (tags[i].value == tag) { alreadyThere = true; }
  }

  if (alreadyThere != true) {
    var newTag = document.createElement("INPUT");
    newTag.type="hidden";
    newTag.className = "tagFields";
    newTag.name = "tags[]";
    newTag.value = tag;
    document.getElementById("tags").appendChild(newTag);

    var newAnchor = document.createElement("A");
    var text = document.createTextNode(tag);
    newAnchor.appendChild(text);
    newAnchor.id = tag;
    newAnchor.className = "blogpost_taglist editor notes";
    document.getElementById("tags").appendChild(newAnchor);
  }
}















