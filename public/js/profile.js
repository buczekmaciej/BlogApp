const personal = document.getElementById("personal");
const activity = document.getElementById("feed");
const articles = document.getElementById("articles");
const comments = document.getElementById("comments");

personal.addEventListener("click", e => {
  usePersonal();
});

activity.addEventListener("click", e => {
  useActivity();
});

articles.addEventListener("click", e => {
  useArticles();
});

comments.addEventListener("click", e => {
  useComments();
});
function usePersonal() {
  document.getElementById("data").style.display = "flex";
  document.getElementById("activity").style.display = "none";
}

function useActivity() {
  document.getElementById("data").style.display = "none";
  document.getElementById("activity").style.display = "flex";
}

function useArticles() {
  document.getElementById("commentsCont").style.display = "none";
  document.getElementById("articlesCont").style.display = "flex";
}

function useComments() {
  document.getElementById("articlesCont").style.display = "none";
  document.getElementById("commentsCont").style.display = "flex";
}
