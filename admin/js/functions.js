var lastId = 0;

function addTag(id) {
  if (document.getElementById('tags').value.length < 1) {
    document.getElementById('tags').value = id;
  }
  else {
    document.getElementById('tags').value += ' ' + id;
  }
}

function inlineNewTag(newTag) {
  document.getElementById("inputNewTag").value = "";
  tagArray = newTag.split(" ");
  for (i=0; i<tagArray.length; i++) {
    addTag(tagArray[i]);
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
  if (document.getElementById(tag) != null) {
    var tagName = document.getElementById(tag).innerHTML;
  } else {
    var tagName = tag;
  }

  var alreadyThere = false;
  var tags = document.getElementsByClassName("tagFields");
  for (i=0; i<tags.length; i++) {
    if (tags[i].value == tagName) { alreadyThere = true; }
  }

  if (alreadyThere != true) {
    var newTag = document.createElement("INPUT");
    newTag.type="hidden";
    newTag.className = "tagFields";
    newTag.name = "tags[]";
    newTag.value = tagName;
    document.getElementById("tags").appendChild(newTag);

    var newAnchor = document.createElement("A");
    var text = document.createTextNode(tagName);
    newAnchor.appendChild(text);
    newAnchor.id = tagName;
    newAnchor.className = "blogpost_taglist editor notes";
    document.getElementById("tags").appendChild(newAnchor);
    document.getElementById(tagName).addEventListener("click", function() {
      removeTag(tagName);
      document.getElementById(tagName).remove();
    });
  }
}















