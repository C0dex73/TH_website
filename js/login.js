$(()=>{    
    $('#signupbutton').on("click", (e) => {
        $('#signupform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
    
    $('#emailbutton').on("click", (e) => {
        $('#emailform').submit();
    });
});