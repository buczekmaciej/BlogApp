$(document).ready(function() {
  $(".artLikes").on("click", function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);

    e.style.color = "blue";

    $.ajax({
      method: "POST",
      url: $link.attr("href")
    }).done(function(data) {
      $(".likesCounter").html(data.likes);
    });
  });
});
