$(()=>{
    $('.formdiv').css("padding-top", ($(document).height()/2-$('#emailform').height()/2).toString() + "px");

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
})