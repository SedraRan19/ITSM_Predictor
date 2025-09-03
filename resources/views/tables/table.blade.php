@extends('index')

@section('content')
<div class="card mb-4" style="border-block-color: #1b4459">
  
      <div class="row">
        {{-- <div class="row mt-4">
          <div class="col-md-6">
            <div class="card p-3 shadow-sm">
              <h5>ML Model Accuracy</h5>
              <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Precision</th>
                        <th>Recall</th>
                        <th>F1-score</th>
                        <th>Support</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report as $label => $metrics)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ $metrics['precision'] }}</td>
                            <td>{{ $metrics['recall'] }}</td>
                            <td>{{ $metrics['f1'] }}</td>
                            <td>{{ $metrics['support'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-info">
                        <td colspan="5"><strong>Accuracy: {{ $accuracy }}</strong></td>
                    </tr>
                </tbody>
            </table>
          </div>

          <div class="col-md-6">
            <div class="card p-3 shadow-sm">
              <h5>Ticket Metrics</h5>
              <canvas id="metricsChart"></canvas>
            </div>
          </div>
        </div> --}}
        <!-- Total Tickets -->
       <div class="col-md-3">
          <div class="card shadow">
              <div class="card-header text-center text-white" style="background-color: #4e73df;">
                  Total Tickets
              </div>
              <div class="card-body bg-white text-center">
                  <h3 class="text-dark fw-bold">{{ $totalTickets }}%</h3>
                  {{--  --}}
              </div>
          </div>
      </div>

        <!-- Bad Categorization -->
        <div class="col-md-3">
          <div class="card shadow">
              <div class="card-header text-center text-white" style="background-color: #e74a3b;">
                  Bad Categorization
              </div>
              <div class="card-body bg-white text-center">
                  <h3 class="text-dark fw-bold">{{ $badCategorization }}%</h3>
              </div>
          </div>
        </div>

        <!-- Wrong Ticket Type -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header text-center text-white" style="background-color: #f6c23e;">
                    Wrong Ticket Type
                </div>
                <div class="card-body bg-white text-center">
                    <h3 class="text-dark fw-bold">{{ $badType }}%</h3>

                </div>
            </div>
        </div>

        <!-- Accuracy of Model -->
         <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header text-center text-white" style="background-color: #1cc88a;">
                    Resolved Tickets
                </div>
                <div class="card-body bg-white text-center ">
                    <h3 class="text-dark fw-bold">{{ $resolvedTickets }}%</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
      <div class="col-md-6">
        <div class="table-responsive text-nowrap mt-2">
          <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr class="small text-center">
                        <th>Label</th>
                        <th>Precision</th>
                        <th>Recall</th>
                        <th>F1-score</th>
                        <th>Support</th>
                    </tr>
                </thead>
                <tbody class="small text-center">
                    @foreach($typeReport as $label => $metrics)
                        <tr>
                            {{-- <td>{{ $label == 0 ? 'Request' : 'Incident' }}</td> --}}
                            <td>
                            @if($label == 0)
                                <span class="badge bg-danger">Request</span>
                            @else
                                <span class="badge bg-success">Incident</span>
                              @endif
                            </td>
                            <td>{{ $metrics['precision'] }}</td>
                            <td>{{ $metrics['recall'] }}</td>
                            <td>{{ $metrics['f1'] }}</td>
                            <td>{{ $metrics['support'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>

      <div class="col-md-6">
        <div class="table-responsive text-nowrap mt-2">
            <table class="table table-bordered table-sm mb-0">
              <thead class="table-light">
                  <tr class="small text-center">
                      <th>Category</th>
                      <th>Precision</th>
                      <th>Recall</th>
                      <th>F1-score</th>
                      <th>Support</th>
                  </tr>
              </thead>
              <tbody class="small text-center">
                  @foreach($categoryReport as $label => $metrics)
                      <tr>
                          <td>{{ $label }}</td>
                          <td>{{ $metrics['precision'] }}</td>
                          <td>{{ $metrics['recall'] }}</td>
                          <td>{{ $metrics['f1'] }}</td>
                          <td>{{ $metrics['support'] }}</td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
        </div>
      </div>
    </div>

</div>
<div class="card" style="border-block-color: #1b4459">
        <nav class="navbar navbar-expand-lg navbar-light mb-1">
          <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <h3 class="mb-0" style="color: #1b4459">Incidents</h3>
                </li>
              </ul>
             <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
              <form action="{{route('incidents.research')}}" method="Post" class="d-flex align-items-center">
                  @csrf
                {{-- Service Desk Dropdown --}}
                <div class="me-2">
                  <select name="service_desk" class="form-select">
                    <option value="">Service Desk</option>
                    @foreach($serviceDesks as $sd)
                      <option value="{{ $sd->name }}">{{ $sd->name }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Start Date --}}
                <div class="me-2">  
                  <input type="date" name="start_date" class="form-control" placeholder="Start Date">
                </div>

                {{-- End Date --}}  
                <div class="me-2">
                  <input type="date" name="end_date" class="form-control" placeholder="End Date">
                </div>

                {{-- Priority Dropdown --}}
                <div class="me-2">
                  <select name="priority" class="form-select">
                    <option value="">Priority</option>
                    <option value="1">Critical</option>
                    <option value="2">High</option>
                    <option value="3">Moderate</option>
                    <option value="4">Low</option>
                  </select>
                </div>

                {{-- Search Button --}}
                <div class="me-2">
                  <button type="submit" class="btn btn-success">
                    <span class="tf-icons bx bx-search"></span>&nbsp; Search
                  </button>
                </div>
              </form>
              <li class="nav-item"> 
              <form action="{{ route('incidents.generateAll') }}" method="POST">
                  @csrf
                  <input type="hidden" name="incident_ids" value="{{ $incidents->pluck('id')->implode(',') }}">
                  <button type="submit" class="btn btn-outline-secondary">
                      <span class="tf-icons bx bx-download"></span>&nbsp; Export
                  </button>
              </form>
              </li> 
              <li class="nav-item"> 
                <form action="{{ route('incidents.generateAll') }}" method="POST"> 
                @csrf 
                <input type="hidden" name="incident_ids" value="{{ $incidents->pluck('id')->implode(',') }}"> 
                <button type="submit" class="btn btn-outline-secondary">
                <span class="tf-icons bx bx-sync"></span>&nbsp; Generate
                </button> 
                </form>
              </li> 
            </ul>
            </div>
          </div>
        </nav>
        
          
        <div class="table-responsive text-nowrap mt-3">
          <table class="table table-hover">
            <thead style="background-color: #1b4459"> 
              <tr>
                {{-- <th>-</th> --}}
                <th style="color: #fff">Number</th>
                <th style="color: #fff">Requested for</th>
                <th style="color: #fff">Priority</th>
                {{-- <th style="color: #fff">Category</th> --}}
                <th style="color: #fff">Service desk</th>
                <th style="color: #fff">Assignment group</th>
                <th style="color: #fff">Short description</th>
                <th style="color: #fff">Description</th> 
                <th style="color: #fff">Category prediction</th> 
                <th style="color: #fff">Type of ticket</th> 
              </tr>
            </thead>  
            <tbody class="table-border-bottom-0">
            @foreach($incidents as $incident)
            <tr>
              {{-- <td>
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault"></label>
              </td> --}}
              <td title="{{$incident->number}}">
                <a href="{{ route('display_incident', $incident->id) }}" style="color: #1b4459">
                  {{ $incident->number ?? '' }}
                </a>
              </td>
              <td class="limited-text" title="{{ $incident->requested_for ?? '' }}">
                {{ $incident->requested_for ?? '' }}
              </td>
              <td>
                {{ $incident->priority ?? '' }}
              </td>
              {{-- <td>
                {{ $incident->category ?? '' }}
              </td> --}}
              <td>
                  {{ $incident->service_desk ?? ''}}
              </td>
              <td title="{{$incident->assignment_group}}">
                  {{ $incident->assignment_group ?? ''}}
              </td>
              <td class="limited-text" title="{{ $incident->short_description ?? '' }}">
                {{ $incident->short_description ?? '' }}
              </td>
              <td class="limited-text" title="{{ $incident->description ?? '' }}">
                {{ $incident->description ?? '' }}
              </td>
              <td>
                {{$incident->predict_category}}
              </td>
              <td>
              @if($incident->incident == 0)
                  <span class="badge bg-danger">Request</span>
              @else
                  <span class="badge bg-success">Incident</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
          </table>
          <!-- Basic Pagination -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="demo-inline-spacing">
                  <!-- Basic Pagination -->
                  <!-- Pagination Links -->
                  <div class="d-flex justify-content-center">
                      {{ $incidents->links() }}
                  </div>
                  <!--/ Basic Pagination -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection

