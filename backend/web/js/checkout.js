$(function () {

    Checkout.init();
});

var Checkout = {
    form: null,
    delivery: {
        TYPE_POST: 'post',
        TYPE_COURIER: 'courier'
    },
    payment: {
        PAYMENT_CASH: 'cash',
        PAYMENT_CARD: 'card'
    },
    init: function () {

        var self = this;

        self.form = $('#checkoutForm');

        self.swithcDeliveryType(self.delivery.TYPE_POST);

        var input_recepient = self.form.find('#orderform-custom_recepient');
        self.customizeRecepient(input_recepient.is(':checked'));

        // Phone mask
        self.form.find("#orderform-user_phone, #orderform-recepient_phone").inputmask({
            mask: "+38 999 999 99 99",
            greedy: false,
            definitions: {
                '*': {
                    validator: "[0-9]",
                    cardinality: 1,
                    casing: "lower"
                }
            }
        });
    },
    swithcDeliveryType: function (deliveryType) {

        if (!deliveryType || !deliveryType.length) {
            return false;
        }

        var self = this,
            address_1 = self.form.find('.field-orderform-address_1'),
            address_2 = self.form.find('.field-orderform-address_2');

        switch (deliveryType) {

            case self.delivery.TYPE_POST:
                address_1.removeClass('hidden');
                address_2.addClass('hidden');
                address_2.find('input[type="text"]').val(null).trigger('change');
                break;

            case self.delivery.TYPE_COURIER:
                address_2.removeClass('hidden');
                address_1.addClass('hidden');
                self.clearWareHouses();
                break;
        }
    },
    getWareHouses: function (cityName) {

        if (!cityName || !cityName.length) {
            return false;
        }

        var self = this,
            select = self.form.find('#orderform-address_1');

        $.ajax({
            url: '/order/search-warehouse',
            type: 'get',
            dataType: 'json',
            data: {
                term: cityName
            },
            success: function (response) {

                for (var i = 0; i < response.results.length; i++) {
                    var newOption = new Option(response.results[i].text, response.results[i].id, false, false);
                    select.append(newOption).trigger('change');
                }

                select.select2('open');
            },
            complete: function () {
                self.form.removeClass('loading');
            },
            beforeSend: function () {
                self.form.addClass('loading');
                self.clearWareHouses();
            }
        });
    },
    clearWareHouses: function () {

        var self = this,
            select = self.form.find('#orderform-address_1'),
            newOption = new Option('-- Select --', null, false, false);;

        select.html(newOption).val(null).trigger('change');
    },
    customizeRecepient: function (checked) {

        var self = this,
            input_name = self.form.find('.field-orderform-recepient_name'),
            input_phone = self.form.find('.field-orderform-recepient_phone');

        if (checked) {
            input_name.removeClass('hidden');
            input_phone.removeClass('hidden');
            return;
        }

        input_name.addClass('hidden');
        input_phone.addClass('hidden');
    }
};