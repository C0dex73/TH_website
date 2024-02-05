$(()=>{

    let secure = (str) => {
        txt = "";
        for (let i = 0 ; i < str.length ; i++) {
            switch (str[i]) {
                case '\'' : txt += '\\\''; break;
                case '`' : txt += '\\`'; break;
                case '"' : txt += '\\"'; break;
                default : txt += str[i];
            }
        }
        return txt;
    }

    $('#submitbutton').on("click", (e) => {
        try{
            window.render();
            $('#contentvalue').val(secure($('#render').html()));
        }catch(e) {}

        $('#mainform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
});