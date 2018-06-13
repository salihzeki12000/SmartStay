@extends('admin.layout')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Spa</li>
@endsection
@section('css')
@endsection

@section('content')
    <div class="card"
         style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); padding: 10px;">
        <h2 id="serviceTitle"><i class="fas fa-sun" style="padding: 5px;"></i>Spa Appointment<a
                        href="{{ route('spa.create') }}"><i id="addGuest" class="fas fa-user-plus"></i></a></h2>

            <table class="table table-sm table-hover text-center" id="serviceTable">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <thead id="serviceTableHeader">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Guest Name</th>
                    <th scope="col">Room Nº</th>
                    <th scope="col">Day and Time</th>
                    <th scope="col">Spa Treatment Type</th>
                    <th scope="col">Completed?</th>
                    <th scope="col" colspan="3">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($spaAppointments->sortByDesc('updated_at') as $indexKey => $spaAppointment)
                    <tr>
                        <td>{{ ++$indexKey }}</td>
                        <td>{{ $spaAppointment->guest->firstname . ' ' . $spaAppointment->guest->lastname }}</td>
                        <td> {{ $spaAppointment->guest->rooms[0]->number }} </td>
                        <td>{{ $spaAppointment->day_hour }}</td>
                        <td>{{ $spaAppointment->spaTreatmentType->name }} </td>
                        <td class="text-center"><input type="checkbox" name="{{ $spaAppointment->id }}"
                                                       @if ($spaAppointment->status == '2') checked @endif></td>
                        <td>
                            <a href="{{ route('spa.show', $spaAppointment->id) }}" class="show-modal btn btn-success">
                                <span class="far fa-eye"></span>
                            </a>
                            <a href="{{ route('spa.edit', $spaAppointment->id) }}" class="edit-modal btn btn-info">
                                <span class="far fa-edit"></span>
                            </a>
                            {!! Form::open(['method' => 'DELETE','route' => ['spa.destroy', $spaAppointment->id], 'style'=>'display:inline']) !!}
                            {!! Form::button('<span class="far fa-trash-alt"></span>', array('type' => 'submit', 'class' => 'btn-delete btn btn-danger')) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    {{-- $spaAppointments->render() --}}
      {{-- <p>
            <span id="spaTotal">{{ $spaAppointments->total() }}</span> orders | page {{ $spaAppointments->currentPage() }} of {{ $spaAppointments->lastPage() }}
        </p>--}}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(':checkbox').change(function (event) {
                var $this = this;
                var route = 'statusSpa/' + event.target.name + '';
                if ($($this).is(':checked')) {

                    toastr.options = {'closeButton': false, 'timeOut': false, 'closeOnHover': false};
                    toastr.warning('<div><button type="button" id="cancelBtn" class="btn btn-primary">Cancel</button><button type="button" id="okBtn" class="btn" style="margin: 0 8px 0 8px">Ok</button></div>', 'Is it already completed?');


                    $('#okBtn').click(function () {
                        $.get(route, function (response, state) {
                            $this.checked = true;
                            console.log("Completed " + response);
                        }).fail(function () {
                            toastr.options = {'closeButton': true, 'timeOut': 5000, 'closeOnHover': true, 'progressBar': true};
                            toastr.warning('Something went wront', 'Alert!');
                        });
                    });

                    $('#cancelBtn, #toast-container').click(function () {
                        $this.checked = false;
                    });


                } else {
                    $.get(route, function (response, state) {
                        console.log("In process " + response);
                    }).fail(function () {
                        $this.checked = true;
                        toastr.options = {'closeButton': true, 'timeOut': 5000, 'closeOnHover': true, 'progressBar': true};
                        toastr.warning('Something went wront', 'Alert!');
                    });
                }
            });


            $('.btn-delete').click(function (e) {
                var $this = this;
                e.preventDefault();
                toastr.options = {'closeButton': true, 'timeOut': false, 'closeOnHover': false};
                toastr.error('<button type="button" class="btn-yes btn">Yes</button>', 'You are about to delete a order!');
                $('.btn-yes').click(function () {
                    var row = $($this).parents('tr');
                    var form = $($this).parents('form');
                    var url = form.attr('action');

                    row.fadeOut();
                    $.post(url, form.serialize(), function (result) {
                        $('#restaurantTotal').html(result.total);
                        toastr.options = {'closeButton': true, 'timeOut': 5000, 'closeOnHover': true, 'progressBar': true};
                        toastr.success(result.message);
                    }).fail(function () {
                        row.fadeIn();
                        toastr.options = {'closeButton': true, 'timeOut': 5000, 'closeOnHover': true, 'progressBar': true};
                        toastr.warning('Something went wront', 'Alert!');
                    });
                });
            });
        });
    </script>


    <script>
        document.getElementsByClassName("itemDropdown")[5].style.color="white";
    </script>
@endsection



    {{--
    @section('content')
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
             <div class="pull-right">
                <a class="btn btn-success" href="{{ route('spa.create') }}"> New Spa Order</a>
            </div>
            <table class="table">
              <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Spa Treatment Type</th>
                    <th scope="col">Day Hour</th>
                    <th scope="col">Price</th>
                    <th></th>
                    <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($spaAppointments as $spa)
                <tr>
                    <th scope="row"><a href="/service/spa/{{$spa->id}}">{{$spa->id}}</a></th>
                    <td>{{ $spa->treatment_type_id }}</td>
                    <td>{{ $spa ->day_hour }}</td>
                    <td>{{ $spa ->price }}</td>
                  <td>
                      <div class="btn-group" role="group" aria-label="Basic example">
                          <a href="{{ URL::to('service/spa/' . $spa->id . '/edit') }}">
                           <button type="button" class="btn btn-warning">Edit</button>
                          </a>&nbsp;
                        <form action="{{url('service/spa', [$spa->id])}}" method="POST">
                             <input type="hidden" name="_method" value="DELETE">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           <input type="submit" class="btn btn-danger" value="Delete"/>
                        </form>
                      </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
    @endsection

    --}}