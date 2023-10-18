$(()=>{

    let secure = (str) => {
        txt = "";
        for (let i = 0 ; i < str.length ; i++) {
            if(str[i] == '\''){
                txt += '\\\'';
            }else{
                txt += str[i];
            }
        }
        alert(txt);
        return txt;
    }

    $('#submitbutton').on("click", (e) => {
        try{
            $('#contentvalue').val(secure($('#content').val()));
        }catch(e) {
            alert(e.message)
        }

        $('#mainform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
});