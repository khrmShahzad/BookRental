<div>

    <div class="alert alert-success" id="success-msg" style="display: none;">
        Rating has been updated
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th class="col-sm-1">No.</th>
                <th class="col-sm-1">User</th>
                <th class="col-sm-2">Book</th>
                <th class="col-sm-2">Rent Date</th>
                <th class="col-sm-2">Return Date</th>
                <th class="col-sm-2">Actual Return Date</th>
                <th class="col-sm-2">Ratings</th>
                <th class="col-sm-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rentlog as $item)
                <tr
                    class="{{ $item->actual_return_date == null ? '' : ($item->return_date < $item->actual_return_date ? 'table-danger' : 'table-success') }}">
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $item->user->username }}</td>
                    <td>{{ $item->book->book_code }} - {{ $item->book->title }}</td>
                    <td>{{ $item->rent_date }}</td>
                    <td>{{ $item->return_date }}</td>
                    <td>{{ $item->actual_return_date }}</td>
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
                    <td>
                        @if($item->actual_return_date == '' || $item->actual_return_date == null)
                            <button type="button" class="btn btn-primary" onclick="returnBook({{$item->id}}, {{$item->book_id}})">Return Book</button>
                        @else
                            Returned
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

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

    function returnBook(id, book_id){

        var formData = new FormData();
        formData.append('id', id);
        formData.append('book_id', book_id);

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

</script>
