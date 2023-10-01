$(()=>{  
    $('.formdiv').css("padding-top", ($(document).height()/2-$('#loginform').height()/2).toString() + "px");
    
    if("<?php echo $Cemail;?>" == "OK"){
        //TODO : make popup warning email verified working (not tested yet)
        bootbox.prompt({
            title: 'Please enter something:',
            callback: function(result){
                console.log(result);
            },
            buttons: {
                cancel: {
                    className: 'd-none'
                }
            }
        });
    }
    
    $('#signupbutton').on("click", (e) => {
        $('#signupform').submit();
    });
    
    $('#emailbutton').on("click", (e) => {
        $('#emailform').submit();
    })
});