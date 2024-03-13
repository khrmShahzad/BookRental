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

{{--        <form action="/checkout" method="POST" role="form" class="php-email-form" enctype="multipart/form-data">--}}

        {{--<form id="payment-form" role="form" action="/checkout" method="post" class="require-validation" data-cc-on-file="false"
            data-stripe-publishable-key="pk_test_51MJi96GnHe8EDgGnjksmzsLjffnAVp0JRJO5soO6ySn0GjBjghMxRNkr9qAxMvQ1EDbBEXSOFeAJPY5u7JtPzWtz00RuWYZA47">

            <div class="row">
                @csrf
                <div class="my-5 w-20 col-md-6">

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Book Cover</label>
                            <img src="{{ $book->cover != null ? asset('cover/' . $book->cover) : asset('images/cover-default.png') }}" alt="Book Cover" height="150px">
                            <input type='hidden' value="{{$book->id}}">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Book Code</label>
                            <input type="text" class="form-control" name="book_code" readonly value="{{ $book->book_code }}">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Status</label>
                            <input type="text" class="form-control" name="status" readonly value="{{ $book->status }}">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Charges</label>
                            <input type="text" class="form-control" name="charges" readonly value="{{ $book->charges }}">
                        </div>
                    </div>

                </div>

                <div class="my-5 w-20 col-md-6">

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Enter bank details to complete your request</label>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Name on Card</label>
                            <input type="text" class="form-control" name="card_holder_name" size='4'>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Card Number</label>
                            <input type="text" class="form-control" name="card_number" size='20'>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">CVC</label>
                            <input autocomplete='off' type="text" class="form-control" name="cvc" placeholder='ex. 311' size='4'>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Expiration Month</label>
                            <input autocomplete='off' type="text" class="form-control" name="expiration_month" placeholder='MM' size='2'>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Expiration Year</label>
                            <input autocomplete='off' type="text" class="form-control" name="expiration_year" placeholder='YYYY' size='4'>
                        </div>
                    </div>

                </div>



            </div>
            <div class="text-end"><button class="btn btn-sm btn-secondary" type="submit">Checkout</button></div>
        </form>--}}

        <form
            role="form"
            action="/checkout"
            method="post"
            class="require-validation"
            data-cc-on-file="false"
            data-stripe-publishable-key="pk_test_51IyFaOFkT1UraQaBJNVXP5C9keLc7QCLsGDgdQr3w3RFlMHhIf3UvP1nL3Vt0aGMmP4UPHK1HQhIUkYXBaRpShQK00mRgLwiSh"
            id="payment-form">
            @csrf

            <div class="row">

                <div class="col-sm-6">

                    <div class="mb-3">
                        <label for="" class="form-label">Book Cover</label>
                        <img src="{{ $book->cover != null ? asset('cover/' . $book->cover) : asset('images/cover-default.png') }}" alt="Book Cover" height="150px">
                        <input type='hidden' name="book_id" value="{{$book->id}}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Book Code</label>
                        <input type="text" class="form-control" name="book_code" readonly value="{{ $book->book_code }}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Status</label>
                        <input type="text" class="form-control" name="status" readonly value="{{ $book->status }}">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Charges</label>
                        <input type="text" class="form-control" name="charges" readonly value="{{ $book->charges }}">
                    </div>

                    {{--<div class="mb-3">
                        <label for="" class="form-label">Rent Date</label>
                        <input type="date" class="form-control" name="rent_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Return Date</label>
                        <input type="date" class="form-control" name="return_date" required>
                    </div>--}}

                </div>


                <div class='col-sm-6'>

                    <div class="mb-3">
                        <label class='form-label'>Name on Card</label>
                        <input class='form-control' size='4' type='text' value="Khurram Shahzad" required>

                    </div>

                    <div class="mb-3">
                        <label class='form-label'>Card Number</label>
                        <input autocomplete='off' class='form-control card-number' value="4242424242424242" size='20' type='text' required>
                    </div>

                    <div class="mb-3">
                        <label class='form-label'>CVC</label>
                        <input autocomplete='off' class='form-control card-cvc' value="123" placeholder='ex. 311' size='4' type='text' required>

                    </div>

                    <div class="mb-3">
                        <label class='form-label'>Expiration Month</label>
                        <input class='form-control card-expiry-month' placeholder='MM' value="12" size='2' type='text' required>
                    </div>

                    <div class="mb-3">
                        <label class='form-label'>Expiration Year</label>
                        <input class='form-control card-expiry-year' placeholder='YYYY' value="2025" size='4' type='text' required>
                    </div>

                </div>

            </div>

            {{--<div class='form-row row'>
                <div class='col-md-12 error form-group hide'>
                    <div class='alert-danger alert'>Please correct the errors and tryagain.</div>
                </div>
            </div>--}}

            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-secondary btn-lg btn-block" type="submit" style="background-color: #178066;">Click To Pay Pkr - {{ $book->charges }}</button>
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

        });
    </script>

@endsection
