$(() => {

    const txtAera = $('#content')[0];
    window.txtAera = txtAera;

    let count = (fStr, str) => {
        return fStr.split(str).length-1;
    };

    let endsWith = (str, toCheck) => {
        if(toCheck == "*" && endsWith(str, "**")) { return false;}
        return str.substring(str.length-toCheck.length, str.length) == toCheck
    }

    let startsWith = (str, toCheck) => {
        if(toCheck == "*" && startsWith(str, "**")) { return false;}
        return str.substring(0, toCheck.length) == toCheck
    }

    let bred = (str) => {
        if(str.length < 6){ return str.replace(/\n/g, "<br/>\n"); }
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
        return str;
    };

    let switchCase = (char) => {
        try{
            if (char == char.toUpperCase()) { return char.toLowerCase(); }
            return char.toUpperCase();
        }catch(e){ return char; }
    }

    let parseToHTML = (str) => {

        let buffer = str;
        let txt = "";
        let italic = false;
        let bold = false;
        let underline = false;

        for (let i = 0; i < buffer.length; i++) {
            if (buffer.substring(i, i + 2) == "**"){
                if(bold){
                    txt += "</b>";
                }else{
                    txt += "<b>";
                }
                bold = !bold;
                i++;
            }else if (buffer.substring(i, i + 1) == "*"){
                if(italic){
                    txt += "</i>";
                }else{
                    txt += "<i>";
                }
                italic = !italic;
            }else if (buffer.substring(i, i + 2) == "__"){
                if(underline){
                    txt += "</u>";
                }else{
                    txt += "<u>";
                }
                underline = !underline;
                i++;
            }else if (buffer[i] === "<") {
                for(j = i; j < buffer.length; j++){
                    if(buffer[j] === "?"){
                        break;
                    }else if(buffer[j] !== " "){
                        txt += "<";
                        break;
                    }
                }
            }else {
                txt += buffer[i];
            }
        }

        
        let Llimit = -1;
        let Middle = -1;
        let Rlimit = -1;

        do{
            buffer = txt;
            Llimit = buffer.indexOf("[");
            Rlimit = Llimit == -1 ? -1 : buffer.lastIndexOf(")");
            Middle = (Rlimit == -1 || Rlimit < Llimit) ? -1 : buffer.lastIndexOf("](", Rlimit);
            if(Middle != -1){
                let before = buffer.substring(0, Llimit);
                let param = buffer.substring(Middle+2, Rlimit);
                let inside = buffer.substring(Llimit+1, Middle);
                let after = buffer.substring(Rlimit+1);

                if(param.length == 7){
                    txt = `${before}<span style="color:${param}">${inside}</span>${after}`;
                }else{
                    txt = `${before}<a target="_blank" href="${param}">${inside}</a>${after}`;
                }
            }
        }while(Middle != -1);
        

        /* (let i = 0; i < buffer.length; i++){
            if(buffer[i] == "["){
                for(let j = i+1; j < buffer.length-2; j++){
                    if(buffer[j] == "]" && buffer[j+1] == "("){
                        for(let k = j+1; k < buffer.length-1; k++){

                        }
                    }
                }
            }else{
                txt += buffer[i];
            }
        }*/


        return bred(txt);
    }

    let modify = (...args) => {
        let selected = txtAera.value.substring(txtAera.selectionStart, txtAera.selectionEnd);

        let txt = "";

        let before = txtAera.value.substring(0, txtAera.selectionStart);
        let after = txtAera.value.substring(txtAera.selectionEnd);
        let toAdd = "";

        switch (args[0]) {
            case 'b' : toAdd = "**"; break;
            case 'i' : toAdd = "*"; break;
            case 'u' : toAdd = "__"; break;
            case 'm' :
                let modifiedSelection = [];
                [...selected].forEach((char) => modifiedSelection.push(switchCase(char)));
                txt = before + modifiedSelection.join("") + after;
                break;
            case 'link' :
                let url = window.prompt("Entrez une url.");
                toAdd = `[texte](${url})`;
                txt = `${before}[${selected}](${url})${after}`;
                break;
            case 'c' :
                toAdd = `[texte](${args[1]})`;
                txt = `${before}[${selected}](${args[1]})${after}`;
                break;
            default :
                return txtAerate.value;
        }

        if (selected == ""){ return txtAera.value + toAdd; }

        if (txt != "") { return txt; }

        if(endsWith(selected, toAdd) && !endsWith(selected, '\\' + toAdd)){
            selected = selected.substring(0, selected.length-toAdd.length);
            after = toAdd + after;
        }

        if(startsWith(selected, toAdd) && !startsWith(selected, '\\' + toAdd)){
            selected = selected.substring(toAdd.length, selected.length);
            before += toAdd;
        }

        txt += (endsWith(before, toAdd) && !endsWith(before, '\\' + toAdd))  ? before.substring(0, before.length-toAdd.length) : (before + toAdd);

        txt += selected + ((startsWith(after, toAdd) && !startsWith(after, '\\' + toAdd)) ? after.substring(toAdd.length, after.length) : (toAdd + after));

        return txt;
    }

    $('#modifiers>button').each((id, trigger) => {
        $(trigger).on('click', (e) => {
            let Trigger = e.target;
            if(Trigger.localName != "button"){ Trigger = Trigger.closest("button"); }
            txtAera.value = modify(Trigger.dataset.tag);
        });
    });

    $('input#color').on('change', (e) => {
        txtAera.value = modify('c', e.target.value);
    });

    $('#renderbutton').on('click', (e) => {
        Render();
    });

    function multiFilePreview(input, FilePreviewPlaceholder){
        if (input[0].files) {
            var filesAmount = input[0].files.length;

            for (i = 0; i < filesAmount; i++) {
                switch (input[0].files[i].type.split('/')[0]) {
                    case 'image' :
                        var reader = new FileReader();
                        reader.onload = function(ev) {
                            $($.parseHTML('<img style="width:100%;height:auto"/>')).attr('src', ev.target.result).appendTo(FilePreviewPlaceholder);
                        }
                        reader.readAsDataURL(input[0].files[i]);
                    break;
                    case 'video' :
                        ($($.parseHTML('<video id="videoHolder_' + i + '"controls></video>')).append($($.parseHTML('<source/>')).attr('src', URL.createObjectURL(input[0].files[i])).attr('type', input[0].files[i].type === 'video/quicktime' ? 'video/mp4' : input[0].files[i].type))).appendTo(FilePreviewPlaceholder);
                    break;
                    default :
                        $($.parseHTML('<a></a>')).attr('href', URL.createObjectURL(input[0].files[i])).attr('download', input[0].files[i].name).append(
                            $($.parseHTML('<button type="button" class="download-button"><img class="icon" height="30" width="30" src="./medias/download.png" alt="Icône télécharger"/><p>Télécharger '+ input[0].files[i].name + '</p></button></a>'))
                        ).appendTo(FilePreviewPlaceholder);
                }
            }
        }

    };

    let Render = () => {
        console.log($('#render').html())
        $('#render').html(parseToHTML(txtAera.value));
        $($.parseHTML('<br/>')).appendTo($('#render'));
        multiFilePreview($('#file'), '#renderDiv');
        console.log($('#render').html())
    }
    window.render = Render;
    window.isPosting = () => { return true; }

    $('#file').on('change', () => {
        Render();
    });
});
