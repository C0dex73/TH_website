$(()=>{
    $('#submitbutton').on("click", (e) => {
        $('#contentvalue').val($('#content').val());
        $('#mainform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
});