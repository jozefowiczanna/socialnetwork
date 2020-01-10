window.addEventListener("DOMContentLoaded", function() {
  let scrollDiv;
  
  if (document.getElementById("scroll-messages")) {
    scrollDiv = document.getElementById("scroll-messages");
    scrollDiv.scrollTop = scrollDiv.scrollHeight;
  }
  
  function getUsers(value, user) {
    $.post("includes/handlers/ajax_friend_search.php", {query: value, userLoggedIn: user}, function(data) {
      $(".results").html(data);
    })
  }
  
  if (document.querySelector("#msg-to")) {
    const msgTo = document.querySelector("#msg-to");
    const userLoggedIn = document.querySelector(".js-userLoggedIn").getAttribute("href");

    msgTo.addEventListener("keyup", function() {
      getUsers(this.value, userLoggedIn)
    });
  }

})
