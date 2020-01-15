function getDropdownData(user, type) {
  const dropdownWindowEl = $(".dropdown-data-window");
  const dropdownTypeEl = $(".dropdown-data-type");
  if (!dropdownWindowEl.hasClass("active")) {
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
        dropdownWindowEl.addClass("active");
        dropdownTypeEl.val(type);
      }
    })
  } else {
    dropdownWindowEl.html("");
    dropdownWindowEl.removeClass("active");
  }
}
