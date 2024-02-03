$(()=>{
    $('#submitbutton').on("click", (e) => {
        try{
            $('#contentvalue').val($('#render'));
        }catch(e) {}

        $('#mainform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
});