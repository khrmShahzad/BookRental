<div>
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
                        @for($i=0;$i<5;$i++)

                            @if($i < $item->overall_rating)
                                @if (Auth::User() && Auth::User()->role_id === 3)
                                    <i class="bi bi-star" title="Click to rate this book" style="cursor:pointer" onclick="rateBook({{$i+1}}, {{$item->book_id}})"></i>
                                @else
                                    <i class="bi bi-star"  title="Login to rate this book" style="cursor:pointer"></i>
                                @endif

                            @else

                                @if (Auth::User() && Auth::User()->role_id === 3)
                                    <i class="bi bi-star" title="Click to rate this book" style="cursor:pointer; color: greenyellow" onclick="rateBook({{$i+1}}, {{$item->book_id}})"></i>
                                @else
                                    <i class="bi bi-star"  title="Login to rate this book" style="cursor:pointer; color: greenyellow"></i>
                                @endif

                            @endif
                        @endfor
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
                    /*setTimeout(function () {
                        window.location.href = "{ { route("admin-player-list")}}";
                    }, 1000);*/

                    console.log(response);

                } else {
                    console.log(response);
                }
            }
        });

    }
</script>
