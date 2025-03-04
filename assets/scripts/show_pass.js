const inputpassword = document.getElementById("user_password");
const eyepass = document.getElementById("eyepass");

eyepass.addEventListener("click", function(){
    showHide_password(inputpassword);
});

function showHide_password(p_iput){
    if(p_iput.type === "password"){
        p_iput.type = "text";
    }else{
        p_iput.type = "password";
    }
}