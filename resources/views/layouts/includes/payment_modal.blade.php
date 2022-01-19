<!-- Modal -->
<div class="modal fade" id="payment_modal" data-keyboard="true" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h3 class="text-orange">PAYMENT INFORMATION</h3>
                <!-- <button type="button" class="close order-hold" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <form action="{{ route('payments.pay') }}" id="paymentForm" enctype="multipart/form-data"
                    method="post">
                    @csrf
                    <input type="hidden" name="order_id"  >
                    <div class="row">
                        <div class="col-12">
                            <input type="checkbox" name="payment_type" value="use_card" id="use_card_profile"
                                class="m-0 mx-1 scale-1_5">
                            <label for="use_card_profile" class="text-primary font-px-18"><strong>Use Card On
                                    File</strong></label>
                        </div>
                        <div class="col-12">
                            <select name="card_profile" id="card_profile_select" class="form-control">
                            </select>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-12">
                            <input type="checkbox" name="payment_type" value="new_card" id="use_another_card" class="m-0 mx-1 scale-1_5">
                            <label for="use_another_card" class="text-primary font-px-18"><strong>Enter Another
                                    Card</strong></label>
                        </div>
                        <div class="row mx-4 form-another-card">
                            <div class="col-12 mb-2">
                                <label for="card_name" class="text-dark">
                                    <strong>Name on Card:</strong>
                                </label>
                                <input type="text" name="card_name" payment-card-name id="card_name"
                                    class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="card_number" class="text-dark">
                                    <strong>Card Number:</strong>
                                </label>
                                <input type="text" maxlength="19" name="card_number" id="card_number"
                                    class="form-control cc-number-input">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="expire_date" class="text-dark">
                                    <strong>Expiration Date (Month/Year)</strong>
                                </label>
                                <div class="d-flex justify-content-start align-items-center ">
                                    <input type="text" name="expire_date_month" id="expire_date_month"
                                        class="form-control date-input-month col-4">
                                    <strong class="mx-2 scale-1_5">/</strong>
                                    <input type="text" name="expire_date_year" id="expire_date_year"
                                        class="form-control date-input-year col-4">
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="card_code" class="text-dark">
                                    <strong>Card Security Code</strong>
                                </label>
                                <input type="number" step="any" name="card_code" id="card_code"
                                    class="form-control col-4">
                            </div>
                            <div class="col-12 text-center mt-2">
                                <strong class="text-muted ">Card is encripted and saved for future
                                    transactions</strong>
                            </div>
                            <div class="col-12 mb-2">
                                <hr>
                            </div>
                            <div class="col-12 mb-2 text-center">
                                <strong class="text-dark">Total Payment: $<span
                                        payment-total-amount>48.00</span></strong>
                            </div>
                            <div class="col-12 mb-2">
                                <button class="btn btn-orange rounded-pill mx-auto d-block width-px-200 text-white font-weight-bold"
                                     id="submitPaymentBtn" type="submit"
                                >SUBMIT PAYMENT
                                </button>
                            </div>

                            <div class="col-12 mb-2 text-center">
                                <a class="text-primary font-weight-bold font-px-18 order-hold" href="#">Order Hold</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
