document.addEventListener('DOMContentLoaded', function () {
  var blogContent = document.getElementsByClassName("blogEntries");
  var color = "#FFFFFF";
  for ( i = 0; i < blogContent.length; i++ )
    {
     if ( color == "#FFFFFF" ) color = "#DDDDAA";
     else color = "#FFFFFF";
     blogContent[i].style.backgroundColor = color;
    }
});
