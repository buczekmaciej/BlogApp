const counter = document.getElementsByClassName("sign-counter")[0];
const bioVal = document.getElementsByClassName("bio-value")[0];
const max = 200;

counter.innerHTML = bioVal.value.length + "/" + max;

bioVal.addEventListener("keyup", function() {
  counter.innerHTML = bioVal.value.length + "/" + max;
});
