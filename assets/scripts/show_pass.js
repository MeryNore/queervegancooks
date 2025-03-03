const inputpassword = document.getElementById("user_password");
const eyepass = document.getElementById("eyepass");

eyepass.addEventListener("mousedown", function(){
    inputpassword.type = "text";
});

eyepass.addEventListener("mouseup", function(){
    inputpassword.type = "password";
});