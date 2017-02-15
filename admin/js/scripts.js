document.addEventListener('DOMContentLoaded', function () {
  var blogContent = document.getElementsByClassName("blogEntries");

  if ( document.getElementById("buttonBack") != null ) {
    document.getElementById("buttonBack").addEventListener("click", function() { window.location.href='showblog.php'; });
  }

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
  if (document.getElementById('tags').value.length < 1) {
    document.getElementById('tags').value = id;
  }
  else {
    document.getElementById('tags').value += ' ' + id;
  }
}