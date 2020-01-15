window.addEventListener("DOMContentLoaded", function() {

  if(window.innerWidth < 600) {
    document.querySelector(".container").style.height = `${window.innerHeight}px`;
  }
})