const personalLink = document.getElementsByClassName("personal")[0];
const activityLink = document.getElementsByClassName("activity")[0];

const userData = document.getElementsByClassName("userData")[0];
const activity = document.getElementsByClassName("userActivity")[0];

const postsLink = document.getElementsByClassName("articles")[0];
const commentsLink = document.getElementsByClassName("comments")[0];

const posts = document.getElementsByClassName("articles-cont")[0];
const comments = document.getElementsByClassName("comments-cont")[0];

personalLink.addEventListener("click", function() {
  clearActive();
  userData.classList.add("active");
});

activityLink.addEventListener("click", function() {
  clearActive();
  activity.classList.add("active");

  postsLink.addEventListener("click", function() {
    clearDispl();
    posts.classList.add("disp");
  });
  commentsLink.addEventListener("click", function() {
    clearDispl();
    comments.classList.add("disp");
  });
});

function clearActive() {
  const currActive = document.getElementsByClassName("active")[0];
  currActive.classList.remove("active");
}

function clearDispl() {
  const currDispl = document.getElementsByClassName("disp")[0];
  currDispl.classList.remove("disp");
}
