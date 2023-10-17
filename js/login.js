$(()=>{    
    $('#signupbutton').on("click", (e) => {
        $('#signupform').submit();
    });

    
    $('#emailbutton').on("click", (e) => {
        $('#emailform').submit();
    });
});