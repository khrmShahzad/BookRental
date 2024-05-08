@extends('layouts.mainLayout')

@section('title', 'Book')

@section('content')

    <h1>Borrow A Book</h1>

    @if (session('status'))
        <div class="alert alert-success mt-5">
            {{ session('status') }}
        </div>
        @if (Session::get('message'))
            <div class="alert alert-warning" role="alert">
                {{ Session::get('message') }}
            </div>
        @endif
    @endif


    <div class="my-5">

        <form action="/checkout" method="post" id="payment-form" role="form" class="require-validation" data-cc-on-file="false"
        data-stripe-publishable-key="pk_test_51OuCTPDVHG7hkxkW1dYO9UcsVKKo8HP7V5Kf0zJVJWA6uB5ijXomKFTCQDd7zRAM7ZT8FLnWXMFQiDo7ZN0erRMf00P6tK2P36">
            @csrf

            <div class="row">

                <div class="col-sm-6">

                    <div class="mb-3">
                        <label for="" class="form-label">Book Cover</label>
                        <img src="{{ $book->cover != null ? asset('cover/' . $book->cover) : asset('cover/cover-default1.png') }}" alt="Book Cover" height="150px">
                        <input type='hidden' name="book_id" value="{{$book->id}}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Book Title</label>
                        <input type="text" class="form-control" name="book_title" readonly value="{{ $book->title }}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Book Code</label>
                        <input type="text" class="form-control" name="book_code" readonly value="{{ $book->book_code }}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Price</label>
                        <input type="text" class="form-control" id="charges" name="charges" readonly value="{{ $book->charges }}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Security</label>
                        <input type="text" class="form-control" id="security" name="security" readonly value="{{ $book->security ? $book->security : 0 }}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Choose No of Copies (Available Copies {{ $book->available_copies }})</label>
                        <input type="number" class="form-control" id="copies" name="copies" value="1" min="1" max="{{ $book->available_copies }}">
                    </div>


                </div>


                <div class='col-sm-6'>

                    <div class="mb-3">
                        <label class='form-label'>Name on Card</label>
                        <input class='form-control' size='4' type='text' value="" required>

                    </div>

                    <div class="mb-3">
                        <label class='form-label'>Card Number</label>
                        <input autocomplete='off' class='form-control card-number' value="" size='20' type='text' required>
                    </div>

                    <div class="mb-3">
                        <label class='form-label'>CVC</label>
                        <input autocomplete='off' class='form-control card-cvc' value="" placeholder='ex. 311' size='4' type='text' required>

                    </div>

                    <div class="mb-3">
                        <label class='form-label'>Expiration Month</label>
                        <input class='form-control card-expiry-month' placeholder='MM' value="" size='2' type='text' required>
                    </div>

                    <div class="mb-3">
                        <label class='form-label'>Expiration Year</label>
                        <input class='form-control card-expiry-year' placeholder='YYYY' value="" size='4' type='text' required>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-secondary btn-lg btn-block" type="submit" style="background-color: #178066;">Click To Pay $ - <span id="charges-area">{{ $book->charges + $book->security }}</span></button>
                </div>
            </div>

        </form>

    </div>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $(function() {
            var $form         = $(".require-validation");
            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {

                    var token = response['id'];

                    var book_id = $("#book_id").val();
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.append("<input type='hidden' name='book_id' value='" + book_id + "'/>");
                    $form.get(0).submit();
                }
            }


            $("#copies").change(function() {
                // Get the value of the input field
                var inputValue = $(this).val();
                var price = $("#charges").val()
                var security = $("#security").val();
                var sum = parseInt(security * inputValue) + parseInt((price * inputValue))
                $("#charges-area").text(sum)
            });

        });


    </script>

@endsection
