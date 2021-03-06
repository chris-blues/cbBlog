document.addEventListener('DOMContentLoaded', function () {

  var buttonSettings = document.getElementById("buttonSettings");
  if (buttonSettings != null) {
    buttonSettings.addEventListener("click", function() {
      window.location.href = "?job=settings";
    });
  }

  var buttonNewFeed = document.getElementById("buttonNewFeed");
  if (buttonNewFeed != null) {
    buttonNewFeed.addEventListener("click", function() {
      window.location.href = "?job=settings&new=feed#newFeed";
    });
  }

  var buttonDeleteFeed = document.getElementsByClassName("buttonDeleteFeed");
  if (buttonDeleteFeed != null) {
    for (i=0; i<buttonDeleteFeed.length; i++) {
      buttonDeleteFeed[i].addEventListener("click", function() {
        var id = this.getAttribute("data-id");
        document.getElementById("name_" + id).value = "";
        document.getElementById("settings_feeds").submit();
      });
    }
  }

  var buttonsEmptyField = document.getElementsByClassName("emptyField");
  if (buttonsEmptyField != null) {
    for (i=0; i< buttonsEmptyField.length; i++) {
      buttonsEmptyField[i].addEventListener("click", function() {
        var id = this.getAttribute("data-id");
        document.getElementById(id).value = "";
      });
    }
  }

  var buttonUp = document.getElementById("buttonUp");
  if (buttonUp != null) {
    buttonUp.addEventListener("click", function() {
      window.scroll({
        top: 0,
        left: 0,
        behavior: 'smooth'
      });
    });
  }

  var buttonNewBlogpost = document.getElementById("buttonNewBlogpost");
  if (buttonNewBlogpost != null) {
    buttonNewBlogpost.addEventListener("click", function() {
      document.getElementById("formJob").value = "addBlog";
      document.getElementById("formEditBlog").submit();
    });
  }

  var buttonSwitchCategory = document.getElementById("switchCategory");
  if (buttonSwitchCategory != null) {
    buttonSwitchCategory.addEventListener("click", function () {
      if (buttonSwitchCategory.getAttribute("data-state") == "released") {
        var newState = "unreleased";
      } else {
        var newState = "released";
      }

      document.getElementById("formJob").value = "overview";
      document.getElementById("category").value = newState;
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

  var buttonsDeleteBlog = document.getElementsByClassName("deleteBlog");
  if (buttonsDeleteBlog != null) {
    for (i=0; i<buttonsDeleteBlog.length; i++) {
      buttonsDeleteBlog[i].addEventListener("click", function() {
        var reallyDelete = document.getElementById("localeData").getAttribute("data-reallyDelete");
        var confirm = window.confirm(reallyDelete);
        if (confirm === true) {
          document.getElementById("formJob").value = "deleteBlog";
          document.getElementById("formBlogId").value = this.getAttribute("data-id");
          document.getElementById("formEditBlog").submit();
        }
      });
    }
  }

  var buttonDeleteComment = document.getElementsByClassName("buttonDeleteComment");
  if (buttonDeleteComment != null) {
    for (i=0; i<buttonDeleteComment.length; i++) {
      buttonDeleteComment[i].addEventListener("click", function() {
        var reallyDelete = document.getElementById("localeData").getAttribute("data-reallyDelete");
        var confirm = window.confirm(reallyDelete);
        if (confirm === true) {
          document.getElementById("commentJob").value = "deleteComment";
          document.getElementById("commentId").value = this.getAttribute("data-id");
          document.getElementById("formEditComment").submit();
        }
      });
    }
  }

  var buttonsTags = document.getElementsByClassName("blogpost_taglist");
  if (buttonsTags != null  && buttonsTags[0] != undefined && buttonsTags[0].classList.length != 1) {
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
