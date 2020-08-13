let posts = document.getElementsByClassName("posts")[0];
let postsTab = document.getElementsByClassName("posts-content")[0];

let comments = document.getElementsByClassName("comments")[0];
let commentsTab = document.getElementsByClassName("comments-content")[0];

if (window.location.pathname == "/profile") {
  let edit = document.getElementsByClassName("data-edit")[0];
  let editTab = document.getElementsByClassName("data-content")[0];

  edit.onclick = () => showEdit();

  function showEdit() {
    if (!edit.classList.contains("active-tab")) {
      removeCurrentActive();
      edit.classList.add("active-tab");
      editTab.classList.add("active-content");
    }
  }
}

posts.onclick = () => showPosts();
comments.onclick = () => showComments();

function showPosts() {
  if (!posts.classList.contains("active-tab")) {
    removeCurrentActive();
    posts.classList.add("active-tab");
    postsTab.classList.add("active-content");
  }
}
function showComments() {
  if (!comments.classList.contains("active-tab")) {
    removeCurrentActive();
    comments.classList.add("active-tab");
    commentsTab.classList.add("active-content");
  }
}

function removeCurrentActive() {
  document
    .getElementsByClassName("active-tab")[0]
    .classList.remove("active-tab");
  document
    .getElementsByClassName("active-content")[0]
    .classList.remove("active-content");
}
