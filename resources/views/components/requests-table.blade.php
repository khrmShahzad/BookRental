<div>

    <div class="alert alert-success" id="success-msg" style="display: none;">

    </div>

    <table class="table table-bordered text-center">
        <thead class="table-success">
            <tr>
                <th class="col-sm-1">No.</th>
                <th class="col-sm-1">User</th>
                <th class="col-sm-1">Book Code</th>
                <th class="col-sm-1">Book Title</th>
                <th class="col-sm-1">Requested Copies</th>
                <th class="col-sm-1">Action</th>
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
                    <td>{{ $item->user->username }}</td>
                    <td title="{{ $bookName->book_code }} - {{ $bookName->title }}">{{ $bookName->book_code }}</td>
                    <td title="{{ $bookName->book_code }} - {{ $bookName->title }}">{{ $bookName->title }}</td>
                    <td>{{ $item->copies }}</td>

                    @if(Auth::User()->role_id === 3 && ($item->status == 'Pending' || $item->status == 'Returned'))
                        <td title="{{$item->status}}">{{ $item->status }}</td>

                    @elseif(Auth::User()->role_id === 3 && $item->status == 'Arrived')
                        <td onclick="updateStatus({{$item->id}}, 'Returned')" title="Click to update to Returned" style="cursor: pointer;"><button class="btn btn-success">{{ $item->status }}</button></td>

                    @elseif(Auth::User()->role_id === 3 && ($item->status == 'Accepted' || $item->status == 'Prepared' || $item->status == 'Courier' || $item->status == 'Shipped'))
                        <td title="{{$item->status}}">{{ $item->status }}</td>

                    @elseif(Auth::User()->role_id === 3 && $item->status == 'Delivered')
                        <td onclick="updateStatus({{$item->id}}, 'Arrived')" title="Click to update to Arrived"  style="cursor: pointer;"><button class="btn btn-success">{{ $item->status }}</button></td>

                    @elseif(Auth::User()->role_id !== 3 && $item->status == 'Pending')
                        <td onclick="updateStatus({{$item->id}}, 'Accepted')"  title="Click to update to Accepted" style="cursor: pointer;"><button class="btn btn-primary">Accept{{--{{ $item->status }}--}}</button></td>

                    @elseif(Auth::User()->role_id !== 3 && $item->status == 'Accepted')
                        <td onclick="updateStatus({{$item->id}}, 'Prepared')"  title="Click to update to Prepared" style="cursor: pointer;"><button class="btn btn-warning">{{ $item->status }}</button></td>

                    @elseif(Auth::User()->role_id !== 3 && $item->status == 'Prepared')
                        <td onclick="updateStatus({{$item->id}}, 'Courier')"  title="Click to update to Courier" style="cursor: pointer;"><button class="btn btn-warning">{{ $item->status }}</button></td>

                    @elseif(Auth::User()->role_id !== 3 && $item->status == 'Courier')
                        <td onclick="updateStatus({{$item->id}}, 'Shipped')"  title="Click to update to Shipped" style="cursor: pointer;"><button class="btn btn-warning">{{ $item->status }}</button></td>

                    @elseif(Auth::User()->role_id !== 3 && $item->status == 'Shipped')
                        <td onclick="updateStatus({{$item->id}}, 'Delivered')"  title="Click to update to Delivered" style="cursor: pointer;"><button class="btn btn-warning">{{ $item->status }}</button></td>

                    @elseif(Auth::User()->role_id !== 3 && ($item->status == 'Delivered' || $item->status == 'Returned'))
                        <td title="{{$item->status}}">{{ $item->status }}</td>

                    @elseif(Auth::User()->role_id !== 3 && $item->status == 'Arrived')
                        <td title="Delivered">Delivered</td>

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
                        window.location.href = "/rent-logs";
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
                        //window.location.href = "{ { route("admin-player-list")}}";
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
                        //window.location.href = "{ { route("admin-player-list")}}";
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
                        window.location.href = "/book-requests";
                    }, 2000);

                    console.log(response);

                } else {
                    console.log(response);
                }
            }
        });
    }

</script>
