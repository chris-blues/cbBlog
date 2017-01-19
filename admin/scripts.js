document.addEventListener('DOMContentLoaded', function () {
  var blogContent = document.getElementsByClassName("blogEntries");
  var color = "#FFFFFF";
  for ( i = 0; i < blogContent.length; i++ )
    {
     if ( color == "#FFFFFF" ) color = "#DDDDAA";
     else color = "#FFFFFF";
     blogContent[i].style.backgroundColor = color;
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
  //tag = document.getElementById("tag_" + id).getAttribute("data-" + id);
  if (document.getElementById('tags').value.length < 1) {
    console.log("tag field seems empty. -> " + id);
    document.getElementById('tags').value = id;
  }
  else {
    console.log("tag field not empty. -> " + id);
    document.getElementById('tags').value += ' ' + id;
  }
}