$(()=>{
    $('#verifbutton').on("click", (e) => {
        $('#mainform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
})