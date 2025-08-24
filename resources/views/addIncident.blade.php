@extends('index')

@section('content')
 <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <!-- Card -->
                    <div class="card shadow-sm">
                        <div class="card-header " style="background-color: #1b4459">
                            <h5 class="mb-0" style="color: #fff">Import Incidents via API</h5>
                        </div>

                        <div class="card-body">
                           @if(session()->has("success"))
                            <div class="alert alert-success" >
                                {{session()->get('success')}}
                            </div>
                            @endif
                            <form method="POST" action="{{route('incidents.import')}}">
                                @csrf
                                <div class="mb-3 mt-3">
                                    <label for="service_desk" class="form-label">Service Desk</label>
                                    <select class="form-select" id="service_desk" name="service_desk" required>
                                        @foreach ( $serviceDesks as $sd)
                                          <option value="{{$sd->id}}">{{$sd->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="service_desk" class="form-label">Priority</label>
                                    <select class="form-select" id="service_desk" name="priority" required>
                                        <option value="1">Critical</option>
                                        <option value="2">High</option>
                                        <option value="3">Moderate</option>
                                        <option value="4">Low</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>

                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100 mt-3" style="background-color: #39b408">Submit & Import</button>
                            </form>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            </div>
        </div>
@endsection