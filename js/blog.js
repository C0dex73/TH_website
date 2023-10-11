$(() => {
    $('#actionbutton').on('click', () => {
        $('form#action').submit();
    });

    $('.bubble:not(#action)').each((id, bubble) => {
        $(bubble).on('click', () => {
            $(bubble).find('form').submit();
        });
    });

    $('#return').on('click', () => {
        $('#killform').submit();
    });
});