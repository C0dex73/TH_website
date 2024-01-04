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

    let deleteDoubles = (tag, text) => {
        let Length = tag.length*2+5;
        if(text.length >= Length){
            for(let i = 0; i <= text.length-Length;) {
                if(text.substring(i, i+Length) == '<'+tag+'></'+tag+'>'){
                    text = text.substring(0, i) + text.substring(i+Length, text.length+1);
                }else{
                    i++;
                }
            }
        }
        return text;
    };

    let reverse = (text, str1, str2) => {
        let txt = "";
        let Length = Math.max(str1.length, str2.length)
        let i = 0;
        while (i <= text.length-Length){
            if(text.substring(i, i+str1.length) == str1){
                txt += str2;
                i += str1.length;
            }else if(text.substring(i, i+str2.length) == str2){
                txt += str1;
                i += str2.length;
            }else{
                txt += text[i];
                i++;
            }
        }
        txt += text.substring(i, text.length);
        return txt;
    };

    let modify = (command, args=null) =>{
        let text = "";
        let selected = txtAera.value.substring(txtAera.selectionStart, txtAera.selectionEnd);
        if(selected != ""){
            let before = txtAera.value.substring(0, txtAera.selectionStart);
            let after = txtAera.value.substring(txtAera.selectionEnd);
            if(command == 'i' || command == 'b' || command == 'u') {
                selected = reverse(selected, '<' + command + '>', '</' + command + '>');
                if(count(before, '<'+command+'>') > count(before, '</'+command+'>')){
                    text = before + '</'+command+'>' + selected + '<'+command+'>' + after;
                }else{
                    text = before + '<'+command+'>' + selected + '</'+command+'>' + after;
                }
                text = deleteDoubles('i', text);
            }else if(command == 'm'){
                text = before;
                for(let i = 0; i < selected.length ; i++) {
                    if(selected[i] === selected[i].toLowerCase() && selected[i].toUpperCase() !== selected[i]){
                        text += selected[i].toUpperCase();
                    }else if(selected[i] !== selected[i].toLowerCase() && selected[i].toUpperCase() === selected[i]){
                        text += selected[i].toLowerCase();
                    }else{
                        text += selected[i];
                    }
                }
                text += after;
            }else if(command == 'a'){
                let url = window.prompt("Entrez une url.");
                text = before + '<a target="_blank" href="' + url + '">' + selected + '</a>' + after;
            }else if(command == 'c' && args != null){
                selected = selected.replace(/<span.*class=\"p\">/, '').replace(/<\/span>/, '');
                text = before + '<span style="color: ' + args + '" class="p">' + selected + '</span>' + after;
            }
            return text;
        }else{
            return txtAera.value;
        }
    };

    $('#modifiers>button').each((id, trigger) => {
        $(trigger).on('click', (e) => {
            let Trigger = e.target;
            if(Trigger.localName != "button"){
                Trigger = Trigger.closest("button");
            }
            txtAera.value = bred(modify(Trigger.dataset.tag));
        });
    });

    $('input#color').on('change', (e) => {
        txtAera.value = bred(modify('c', e.target.value));
    });

    function Render() {
        txtAera.value = bred(txtAera.value);
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


    $('#file').on('change', function() {
        Render();
    });
});