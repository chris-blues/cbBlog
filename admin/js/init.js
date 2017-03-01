document.addEventListener('DOMContentLoaded', function () {
  var buttonNewBlogpost = document.getElementById("buttonNewBlogpost");
  if (buttonNewBlogpost != null) {
    buttonNewBlogpost.addEventListener("click", function() {
      document.getElementById("formJob").value = "addBlog";
      document.getElementById("formEditBlog").submit();
    });
  }

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
        document.getElementById("formJob").value = "editBlog";
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
      });
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

  var inputNewTag = document.getElementById("inputNewTag");
  if (inputNewTag != null) {
    inputNewTag.addEventListener("blur", function() {
      var newTag = inputNewTag.value;
      inlineNewTag(newTag);
    });
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
