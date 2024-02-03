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
        return txt;
    }

    $('#submitbutton').on("click", (e) => {
        try{
            $('#contentvalue').val(secure($('#content').val()));
            alert("Poster des articles est temporairement désactivé.\r\nVeuillez réésayer plus tard !");
        }catch(e) {
            $('#mainform').submit();
        }
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
});
