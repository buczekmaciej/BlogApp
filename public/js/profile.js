const personalLink = document.getElementsByClassName("personal")[0];
const activityLink = document.getElementsByClassName("activity")[0];

const userData = document.getElementsByClassName("userData")[0];
const activity = document.getElementsByClassName("userActivity")[0];

personalLink.addEventListener("click", function() {
  clearActive();
  userData.classList.add("active");
});

activityLink.addEventListener("click", function() {
  clearActive();
  activity.classList.add("active");
});

function clearActive() {
  const currActive = document.getElementsByClassName("active")[0];
  currActive.classList.remove("active");
}
