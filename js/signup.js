$(()=>{
    $('.formdiv').css("padding-top", ($(document).height()/2-$('#signup').height()/2).toString() + "px");

    $('#loginbutton').on("click", (e) => {
        $('#loginform').submit();
    });
})