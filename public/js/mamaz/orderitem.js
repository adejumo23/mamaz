function minusBtnClick(e) {
    var condimentId = $(e.target).attr('id');
    var closestSpan = $('.condiment-' + condimentId)[0];
    var currentQty = $(closestSpan).text();
    currentQty = parseInt(currentQty);
    if (currentQty > 0) {
        currentQty--;
    }
    $('input[name="condiments[' + condimentId + ']"]').val(currentQty);
    $(closestSpan).text(currentQty);
}
function plusBtnClick(e) {
    var condimentId = $(e.target).attr('id');
    var closestSpan = $('.condiment-' + condimentId)[0];
    var currentQty = $(closestSpan).text();
    currentQty = parseInt(currentQty);
    currentQty++;
    $('input[name="condiments[' + condimentId + ']"]').val(currentQty);
    $(closestSpan).text(currentQty);
}
$(document).on('click', '.minus-btn', minusBtnClick);
$(document).on('click', '.plus-btn', plusBtnClick);