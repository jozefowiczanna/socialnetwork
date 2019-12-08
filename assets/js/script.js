window.addEventListener('DOMContentLoaded', (event) => {
  
const linkLogin = document.querySelector(".link-login-js");
const formLogin = document.querySelector(".form-login-js");

const linkRegister = document.querySelector(".link-register-js");
const formRegister = document.querySelector(".form-register-js");

linkLogin.addEventListener("click", function(e) {
  e.preventDefault();
  formLogin.classList.remove("form--is-active");
  formRegister.classList.add("form--is-active");
}, false)

linkRegister.addEventListener("click", function(e) {
  e.preventDefault();
  formRegister.classList.remove("form--is-active");
  formLogin.classList.add("form--is-active");
}, false)

});