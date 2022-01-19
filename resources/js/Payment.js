import helper from "./helper";


const Payment = {
    init() {
        this.onPaymentFormSubmit()
        let paymentTypeSelector = `[name="payment_type"]`;
        let inputsSelector = `.form-another-card input`;
        let cardProfileSelector = `[name="card_profile"]`;
        $(inputsSelector).prop('disabled', true);
        $(paymentTypeSelector).on('change', e => {
            let { target } = e;
            $(paymentTypeSelector).prop(`checked`, false);
            target.checked = true;
            if (target.getAttribute('id') == "use_another_card") {
                $(inputsSelector).prop('disabled', false);
                $(cardProfileSelector).prop('disabled', true);
            } else {
                $(cardProfileSelector).prop('disabled', false);
                $(inputsSelector).prop('disabled', true);
            }
            console.log(target);
        })

        this.orderHold();
    },
    onPaymentFormSubmit() {
        let form = $("#paymentForm");

        if (form.length) {
            form.on('submit', e => {
                e.preventDefault()

                const submitPaymentBtn = $('#submitPaymentBtn');
                const previousText = submitPaymentBtn.text();
                submitPaymentBtn.prop('disabled', true).text('PROCESSING...');

                let data = {};
                let formData = $(e.target).serializeArray()
                formData.forEach(f => {
                    if (f.name == "_token") return null;
                    data[f.name] = f.value;
                })
                $.post(helper.getSiteUrl(`/payment/pay`), data)
                    .done(res => {
                        console.log(res);
                        if (res.messages.resultCode == 'Error') {
                            let msgs = `<ul class="px-2">`;

                            if (res.transactionResponse.errors) {
                                res.transactionResponse.errors.forEach(e => {
                                    msgs += `<li class='text-danger'>${e.errorText}</li>`;
                                })
                            }

                            /*res.messages.message.forEach(m => {
                                msgs += `<li class='text-danger'>${m.text}</li>`;
                            })*/

                            msgs += '</ul>';

                            helper.alertError(msgs);

                            submitPaymentBtn.prop('disabled', false).text(previousText);
                        } else {
                            window.location.reload();
                        }
                    })
                    .fail(res => {
                        let f = res.responseJSON;
                        let msgs = `<ul>`;
                        //main message
                        msgs += "<li class='text-danger'><b>" + f.message + "</b></li>"
                        for (const property in f.errors) {
                            $(`[name^="${property}"]`).addClass('is-invalid');
                            msgs += "<li class='text-danger'>" + f.errors[property] + "</li>"
                        }
                        msgs += '</ul>';

                        helper.alertError(msgs);

                        submitPaymentBtn.prop('disabled', false).text(previousText);
                    })
            })
        }
    },

    loadCards(selectInput, userId) {
        const url = `${helper.getSiteUrl()}/payment/get-saved-cards/${userId}`;

        $.get(url)
        .done( res => {
            console.log(res);

            let html = '';

            $.each(res, (paymentProfileId, cardInfo) => {
                //console.log(paymentProfileId)
                html += `<option value="${paymentProfileId}">${cardInfo.cardType}: XXXX-XXXX-${cardInfo.cardNumber}  exp ${cardInfo.expDate}</option>`;
            });

            selectInput.html(html);
        })
        .fail(res => {
            helper.alertError('Unable to load cards due to server error.');
        })
    },

    orderHold() {
        $('.order-hold').on('click', () => {
            //Send email for order
            const orderId = $('[name="order_id"]').val();

            const url = `${helper.getSiteUrl()}/order/email/${orderId}`;
            $.get(url)
            .done( res => {
                window.location.reload();
            });
        });
    }
}

export default Payment;
