$(document).ready(() => {
    $('#addWord').on('click', () => {
        $('#wordsContainer').append($('#wordsContainer .row').last().clone());

        let number = $('#wordsContainer .row').length;
        $('#wordsContainer .row').last()
            .find('.word-input')
            .attr('id', 'word' + number + 'Field')
            .attr('name', 'wordField[' + number + ']')
            .siblings('label')
            .attr('for', 'word' + number + 'Field');

        $('#wordsContainer .row').last()
            .find('.count-input')
            .attr('id', 'word' + number + 'CountField')
            .attr('name', 'wordCountField[' + number + ']')
            .siblings('label')
            .attr('for', 'word' + number + 'CountField');
    });
});
