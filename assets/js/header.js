
window.addEventListener("DOMContentLoaded", function() {
  function getDropdownData(user, type) {
    const dropdownWindowEl = $(".dropdown-data-window");
    const dropdownTypeEl = $(".dropdown-data-type");
    if (!dropdownWindowEl.hasClass("is-visible")) {
      let pageName;
  
      if (type == 'notification') {
        pageName = "ajax_load_notifications.php";
        $(".js-num-notifications").remove();
      } else if (type == 'message') {
        pageName = "ajax_load_messages.php";
        $(".js-num-messages").remove();
      }
  
      let ajaxreq = $.ajax({
        url: "includes/handlers/" + pageName,
        type: "POST",
        data: "page=1&userLoggedIn=" + user,
        cache: false,
  
        success: function(response) {
          dropdownWindowEl.html(response);
          dropdownWindowEl.addClass("is-visible");
          dropdownTypeEl.val(type);
        }
      })
    } else {
      dropdownWindowEl.html("");
      dropdownWindowEl.removeClass("is-visible");
    }
  }

  const searchForm = document.querySelector(".js-search-form");
  const searchInput = document.querySelector(".js-search-input");
  const searchResults = document.querySelector(".js-search-results");
  
  searchForm.addEventListener("submit", e => {
    // e.preventDefault();
  });

  searchInput.addEventListener("focus", () => {
    searchResults.classList.add("is-visible");
  });

  searchInput.addEventListener("blur", () => {
    searchResults.classList.remove("is-visible");
    searchResults.innerHTML = "";
    searchInput.value = "";
  });

  searchInput.addEventListener("keyup", e => {
    const val = e.target.value;
    
    $.ajax({
      url: "includes/handlers/ajax_search.php",
      type: "POST",
      data: "q=" + val,
      cache: false,
      
      success: function(response) {
        searchResults.innerHTML = response;
      }
    })
  });

  const userInput = document.querySelector(".username");
  const msgBtn = document.querySelector(".js-message-btn");
  const notificationBtn = document.querySelector(".js-notification-btn");

  msgBtn.addEventListener("click", () => {
    const username = userInput.dataset.user; 
    getDropdownData(username, "message");
  });

  notificationBtn.addEventListener("click", () => {
    const username = userInput.dataset.user; 
    getDropdownData(username, "notification");
  });

  document.body.addEventListener("click", (e) => {
    if (!e.target.hasAttribute("data-dropdown-handler")) {
      document.querySelector(".dropdown-data-window").classList.remove("is-visible");
    }
  });

});
