
(function($){

    $('.add-product-addon').on('click', function(){
        const wrapper = $('.wc-product-addons');
        const addonItem = `<div class="addon-form-fields-wrap">
                                <div class="wc-addon-field">
                                    <label for="addon_name">Item title</label>
                                    <input type="text" id="addon_name" name="addon_name[]" >
                                </div>
                                <div class="wc-addon-field">
                                    <label for="addon_price">Item Price</label>
                                    <input type="text" id="addon_price" name="addon_price[]" >
                                </div>
                                <button type="button" class="button remove-product-addon">Remove</button>
                            </div>`;
        wrapper.append(addonItem);
    });

    $('.wc-product-addons').on('click', '.remove-product-addon', function(){
        $(this).closest('.addon-form-fields-wrap').remove();
    })
})(jQuery);