$(()=>{  
    $('.formdiv').css("padding-top", ($(document).height()/2-$('#loginform').height()/2).toString() + "px");
    
    if("<?php echo $Cemail;?>" == "OK"){
        alert("Votre adresse mail a bien été vérifiée.")
    }

    $('#signupbutton').on("click", (e) => {
        $('#signupform').submit();
    });
    
    $('#emailbutton').on("click", (e) => {
        $('#emailform').submit();
    })
});