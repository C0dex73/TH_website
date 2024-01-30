$(() => {

    const txtAera = $('#content')[0];

    let count = (fStr, str) => {
        return fStr.split(str).length-1;
    };

    let bred = (str) => {
        if(str.length < 6){
            return str.replace(/\n/g, "<br/>\n");
        }else{
            let i = 6;
            let l = str.length;
            while(i <= l-1) {
                if(str[i] == '\n' && str.substring(i-5, i) != '<br/>'){
                    str = str.substring(0, i) + '<br/>' + str.substring(i, str.length);
                    l=str.length;
                    i += 5;
                }
                i++;
            }
        }
        return str;
    };

    let removeDoubles = (str) => {
        let italic = false;
        let bold = false;
        let underlined = false;
        let link = false;

        //TODO : check for doubles
    }

    let parseToHTML = (str) => {

        //TODO : parse to HTML

        str = bred(str);
    }

    let modify = (...args) => {
        let selected = txtAera.value.substring(txtAera.selectionStart, txtAera.selectionEnd);

        if (selected == ""){
            return txtAera.value
        }

        let txt = "";

        let before = txtAera.value.substring(0, txtAera.selectionStart);
        let after = txtAera.value.substring(txtAera.selectionEnd);
        let toAdd = "";

        switch (args[0]) {
            case 'b' :
                toAdd = "**";
                break;
            case 'i' :
                toAdd = "*";
                break;
            case 'u' :
                toAdd = "__"
                break;
            case link :
                //TODO : transform to [txt](url)
                break;
            case 'c' :
                //TODO : transform to [txt](color)
                break;
        }

        if(toAdd == ""){
            return txt;
        }

        return removeDoubles(before + toAdd + selected + toAdd + after);
    }

    $('#modifiers>button').each((id, trigger) => {
        $(trigger).on('click', (e) => {
            let Trigger = e.target;
            if(Trigger.localName != "button"){
                Trigger = Trigger.closest("button");
            }
            txtAera.value = modify(Trigger.dataset.tag);
        });
    });

    $('input#color').on('change', (e) => {
        txtAera.value = modify('c', e.target.value);
    });

    function Render() {
        txtAera.value = parseToHTML(txtAera.value);
        $('#render').html(txtAera.value);
        multiImgPreview($('#file'), '#render');
    }

    $('#renderbutton').on('click', (e) => {
        Render();
    });

    function multiImgPreview(input, imgPreviewPlaceholder){

        if (input[0].files) {
            var filesAmount = input[0].files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img style="width:100%;height:auto">')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
                }

                reader.readAsDataURL(input[0].files[i]);
            }
        }

    };


    $('#file').on('change', () => {
        Render();
    });
});