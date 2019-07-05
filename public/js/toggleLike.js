$(document).ready(function() {
  $(".js-like-art").on("click", function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);
    $link.toggleClass("like-o").toggleClass("liked");
    $.ajax({
      method: "POST",
      url: $link.attr("href")
    }).done(function(data) {
      $(".likesConter").html(data.likes);
    });
  });
});
