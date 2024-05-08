<div>

    <div class="alert alert-success" id="success-msg" style="display: none;">

    </div>

    <table class="table table-bordered text-center">
        <thead class="table-success">
            <tr>
                <th class="col-sm-1">No.</th>
                @if(Auth::User()->role_id === 1)
                <th class="col-sm-1">User</th>
                @endif
                <th class="col-sm-1">Book</th>
                <th class="col-sm-1">Title</th>
                <th class="col-sm-1">Copies</th>
                <th class="col-sm-2">Ratings</th>
                <th class="col-sm-4">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rentlog as $item)

                @php
                    $bookName = \App\Models\Book::find($item->book_id);
                @endphp
                <tr
                    class="{{ $item->actual_return_date == null ? '' : ($item->return_date < $item->actual_return_date ? 'table-danger' : 'table-success') }}">
                    <td>{{ $loop->index + 1 }}</td>
                    @if(Auth::User()->role_id === 1)
                    <td>{{ $item->user->username }}</td>
                    @endif
                    <td title="{{ $bookName->book_code }} - {{ $bookName->title }}">{{ $bookName->book_code }}</td>
                    <td title="{{ $bookName->book_code }} - {{ $bookName->title }}">{{ $bookName->title }}</td>
                    <td>{{ $item->copies }}</td>
                    <td>

                        @if (Auth::User()->role_id === 3)

                            @for($i=1;$i<6;$i++)
                                @if($item->rating == 0)
                                    <i class="bi bi-star" title="Click to rate this books" style="cursor:pointer" onclick="rateBook({{$i}}, {{$item->id}})"></i>
                                @elseif($i <= $item->rating)
                                    <i class="bi bi-star" title="Click to rate this books" style="cursor:pointer; color: greenyellow" onclick="rateBook({{$i}}, {{$item->id}})"></i>
                                @else
                                    <i class="bi bi-star" title="Click to rate this books" style="cursor:pointer" onclick="rateBook({{$i}}, {{$item->id}})"></i>
                                @endif
                            @endfor

                        @else

                            @for($i=1;$i<6;$i++)
                                @if($item->rating == 0)
                                    <i class="bi bi-star" title="Rating of this books" style="cursor:pointer"></i>
                                @elseif($i <= $item->rating)
                                    <i class="bi bi-star" title="Rating of this books" style="cursor:pointer; color: greenyellow"></i>
                                @else
                                    <i class="bi bi-star" title="Rating of this books" style="cursor:pointer;"></i>
                                @endif
                            @endfor

                        @endif

                    </td>

                    @if(Auth::User()->role_id === 3 && $item->status == 'Pending')
                        <td title="{{$item->status}}">
                            <button class="btn btn-success">waiting...</button>
                            <i class="bi bi-truck" style="font-size: 1.5vw; padding-right: 6px;"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle"></i>
                            <button class="btn btn-success">delivered?</button>
                        </td>

                    @elseif(Auth::User()->role_id === 3 && $item->status == 'Accepted')

                        <td title="{{$item->status}}">
                            <button class="btn btn-success">{{ $item->status }}</button>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-truck" style="font-size: 1.5vw;"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <button class="btn btn-success">delivered?</button>
                        </td>

                    @elseif(Auth::User()->role_id === 3 && $item->status == 'Delivered')

                        <td title="{{$item->status}}">
                            <button class="btn btn-success">Accepted</button>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-circle" style="padding-right: 4px"></i>
                            <i class="bi bi-truck" style="font-size: 1.5vw; margin-right: 4px;"></i>
                            <button class="btn btn-success">delivered.</button>
                        </td>

                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal" tabindex="-1" role="dialog" id="comment-Modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Comments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal(1)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Add Your comments here</p>
                <input type="text" id="comment" placeholder="Comments..." class="form-control" value=""/>
                <input type="hidden" id="pk_id" value=""/>
                <input type="hidden" id="bk_id" value=""/>
                <input type="hidden" id="flag" value=""/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="returnBookSubmit()">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal(1)">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="security-Modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Security Amount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal(2)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/return-security" method="post" id="payment-form" role="form" class="require-validation" data-cc-on-file="false"
                  data-stripe-publishable-key="pk_test_51OuCTPDVHG7hkxkW1dYO9UcsVKKo8HP7V5Kf0zJVJWA6uB5ijXomKFTCQDd7zRAM7ZT8FLnWXMFQiDo7ZN0erRMf00P6tK2P36">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label class='form-label'>Security Amount Submitted</label>
                        <input type="hidden" id="sec_pk_id" name="sec_pk_id" value=""/>
                        <input type="hidden" id="sec_bk_id" name="sec_bk_id" value=""/>
                        <input type="number" id="security_submitted" name="security_submitted" class="form-control" value="" readonly/>
                    </div>

                    <div class="mb-3">
                        <label class='form-label'>Enter Security Amount to Return</label>
                        <input type="number" id="security_returned" name="security_returned" placeholder="Security Refund" class="form-control" value="" required/>
                    </div>

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

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal(2)">Close</button>
                </div>

            </form>
        </div>
    </div>
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

                var book_id = $("#sec_bk_id").val();
                var id = $("#sec_pk_id").val();
                var security_submitted = $("#security_submitted").val();
                var security_returned = $("#security_returned").val();
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.append("<input type='hidden' name='book_id' value='" + book_id + "'/>");
                $form.append("<input type='hidden' name='id' value='" + id + "'/>");
                $form.append("<input type='hidden' name='security_submitted' value='" + security_submitted + "'/>");
                $form.append("<input type='hidden' name='security_returned' value='" + security_returned + "'/>");
                $form.get(0).submit();
            }
        }


    });


</script>

<script>
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    function rateBook(star, id){

        var formData = new FormData();
        formData.append('star', star);
        formData.append('id', id);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url("rate-book") }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function (response) {
                if (response.status == "success") {
                    $("#success-msg").text('Rating has been updated');
                    $("#success-msg").show();
                    setTimeout(function () {
                        location.reload();
                        window.location.href = "/rented-books";
                    }, 2000);

                    console.log(response);

                } else {
                    console.log(response);
                }
            }
        });

    }

    function returnBook(id, book_id, flag, cmt){

        $("#pk_id").val(id);
        $("#bk_id").val(book_id);
        $("#flag").val(flag);
        if(flag == 1){
            $("#comment").val(cmt);
        }
        $("#comment-Modal").show();

    }

    function returnBookSubmit(){

        var id = $("#pk_id").val();
        var book_id = $("#bk_id").val();
        var comment = $("#comment").val();
        var flag = $("#flag").val();

        var formData = new FormData();
        formData.append('id', id);
        formData.append('book_id', book_id);
        formData.append('comment', comment);
        formData.append('flag', flag);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url("return-book") }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function (response) {
                if (response.status == "success") {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);

                    console.log(response);

                } else {
                    console.log(response);
                }
            }
        });
    }

    function refundSecurity(id, book_id, security_submitted){

        $("#sec_pk_id").val(id);
        $("#sec_bk_id").val(book_id);
        $("#security_submitted").val(security_submitted);

        $('.card-number').val('')
        $('.card-cvc').val('')
        $('.card-expiry-month').val('')
        $('.card-expiry-year').val('')

        $("#security-Modal").show();

    }

    function refundSecuritySubmit(){

        var id = $("#sec_pk_id").val();
        var book_id = $("#sec_bk_id").val();
        var security_submitted = $("#security_submitted").val();
        var security_returned = $("#security_returned").val();

        var formData = new FormData();
        formData.append('id', id);
        formData.append('book_id', book_id);
        formData.append('security_submitted', security_submitted);
        formData.append('security_returned', security_returned);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url("return-security") }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function (response) {
                if (response.status == "success") {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);

                    console.log(response);

                } else {
                    console.log(response);
                }
            }
        });
    }

    function closeModal(flag){
        if(flag == 1){

            $("#comment-Modal").hide();
        }else{
            $("#security-Modal").hide();
        }
    }

    function updateStatus(id, status){
        var formData = new FormData();
        formData.append('id', id);
        formData.append('status', status);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url("update-status") }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function (response) {
                if (response.status == "success") {
                    $("#success-msg").text('Status has been updated');
                    $("#success-msg").show();
                    setTimeout(function () {
                        location.reload();
                        window.location.href = "/rent-logs";
                    }, 2000);

                    console.log(response);

                } else {
                    console.log(response);
                }
            }
        });
    }

</script>
