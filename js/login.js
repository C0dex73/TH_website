$(()=>{  
    $('.formdiv').css("padding-top", ($(document).height()/2-$('#loginform').height()/2).toString() + "px");
    
    $('#signupbutton').on("click", (e) => {
        $('#signupform').submit();
    });
    
    $('#emailbutton').on("click", (e) => {
        $('#emailform').submit();
    })
});