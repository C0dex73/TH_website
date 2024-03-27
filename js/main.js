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
            window.isPosting();
            if(confirm("Vote article est prêt à être envoyé, veuillez confirmer l'envoi.\r\nRemarque : cette opération peut prendre jusqu'à une minute et risque de changer l'affichage de la page actuelle durant son éxécution, merci de patienter.")){
                window.render();
                $('#contentvalue').val(secure($('#render').html()));
            }else{
                return;
            }
        }catch(e) {}

        $('#mainform').submit();
    });

    $('#killbutton').on("click", (e) => {
        $('#killform').submit();
    });
});
