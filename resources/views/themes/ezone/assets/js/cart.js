import $ from 'jquery';

$(document).ready(function() {
    $('input[name^="items["]').on('input', function() {
        var itemId = $(this).data('item-id');
        var quantity = $(this).val();
        
        $.ajax({
            url: '/api/carts/update', 
            method: 'POST',
            data: {
                item_id: itemId,
                quantity: quantity
            },
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.error(error);
            }
        });
    });
});
