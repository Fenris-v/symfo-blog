$(document).ready(() => {
    $('#addWord').on('click', (e) => {
        e.preventDefault();
        $('#wordsContainer').append($('#wordsContainer .row').last().clone());

        let number = $('#wordsContainer .row').length;
        $('#wordsContainer .row').last()
            .find('.word-input')
            .attr('id', 'word' + number + 'Field')
            .attr('name', 'article_create_form[wordField][' + number + ']')
            .val(null)
            .siblings('label')
            .attr('for', 'word' + number + 'Field');

        $('#wordsContainer .row').last()
            .find('.count-input')
            .attr('id', 'word' + number + 'CountField')
            .attr('name', 'article_create_form[wordCountField][' + number + ']')
            .val(null)
            .siblings('label')
            .attr('for', 'word' + number + 'CountField');
    });
});
