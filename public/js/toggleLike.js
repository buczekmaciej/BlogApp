$(document).ready(function() {
  $(".glyphicon-thumbs-up").on("click", function(e) {
    e.preventDefault();

    var $link = $(e.currentTarget);
    $link.toggleClass("like-o").toggleClass("liked");

    $.ajax({
      method: "POST",
      url: $link.attr("href")
    }).done(function(data) {
      $(".likesCounter").html(data.likes);
    });
  });
});
