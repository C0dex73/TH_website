$(()=>{

    let secure = (str) => {
        alert(str.replace('"', "'"));
        return str.replace('"', "'");
    }

    $('#submitbutton').on("click", (e) => {
        try{
            $('#contentvalue').val(secure($('#content').val()));
        }catch(e) {}

        $('#mainform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
});