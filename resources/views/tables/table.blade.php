@extends('index')

@section('content')
<div class="card mb-4" style="border-block-color: #1b4459">
  
      <div class="row">
        <!-- Total Tickets -->
       <div class="col-md-3">
          <div class="card shadow">
              <div class="card-header text-center text-white" style="background-color: #4e73df; padding: 0.5rem;">
                  Total Tickets
              </div>
              <div class="card-body bg-white text-center py-2">
                  <h4 class="text-dark fw-bold mb-0">{{ $totalTickets }}</h4>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card shadow">
              <div class="card-header text-center text-white" style="background-color: #e74a3b; padding: 0.5rem;">
                  Bad Categorization
              </div>
              <div class="card-body bg-white text-center py-2">
                  <h4 class="text-dark fw-bold mb-0">{{ $badCategorization }}%</h4>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card shadow">
              <div class="card-header text-center text-white" style="background-color: #f6c23e; padding: 0.5rem;">
                  Wrong Ticket Type
              </div>
              <div class="card-body bg-white text-center py-2">
                  <h4 class="text-dark fw-bold mb-0">{{ $badType }}%</h4>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card shadow">
              <div class="card-header text-center text-white" style="background-color: #1cc88a; padding: 0.5rem;">
                  Resolved Tickets
              </div>
              <div class="card-body bg-white text-center py-2">
                  <h4 class="text-dark fw-bold mb-0">{{ $resolvedTickets }}%</h4>
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
                      <option value="{{ $sd->name }}" {{ request('service_desk') == $sd->name ? 'selected' : '' }}>
                        {{ $sd->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                {{-- Start Date --}}
                <div class="me-2">  
                  <input type="date" name="start_date" class="form-control" 
                        value="{{ request('start_date') }}" placeholder="Start Date">
                </div>

                {{-- End Date --}}  
                <div class="me-2">
                  <input type="date" name="end_date" class="form-control" 
                        value="{{ request('end_date') }}" placeholder="End Date">
                </div>

                {{-- Priority Dropdown --}}
                <div class="me-2">
                  <select name="priority" class="form-select">
                    <option value="">Priority</option>
                    <option value="1" {{ request('priority') == '1' ? 'selected' : '' }}>Critical</option>
                    <option value="2" {{ request('priority') == '2' ? 'selected' : '' }}>High</option>
                    <option value="3" {{ request('priority') == '3' ? 'selected' : '' }}>Moderate</option>
                    <option value="4" {{ request('priority') == '4' ? 'selected' : '' }}>Low</option>
                  </select>
                </div>


                {{-- Search Button --}}
                <div class="me-2">
                  <button type="submit" class="btn btn-success">
                    <span class="tf-icons bx bx-search"></span>&nbsp; Search
                  </button>
                </div>
              </form>
              <li class="nav-item me-2"> 
              <form action="{{ route('incidents.export') }}" method="POST">
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
                <button type="submit" id="generateBtn2" class="btn btn-outline-secondary" title="Model #1">
                  <span id="btnText2"><i class="bx bx-sync"></i>&nbsp; Generate</span>
                  <span class="loading-dots d-none" id="btnDots">
                    <span>.</span><span>.</span><span>.</span>
                  </span>
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
                  @php
                      $priorities = [
                          1 => 'Critical',
                          2 => 'High',
                          3 => 'Moderate',
                          4 => 'Low'
                      ];
                  @endphp

                  {{ $priorities[$incident->priority] ?? $incident->priority }}
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
                @if (empty($incident->predict_category))
                    --
                @else
                  {{$incident->predict_category}}
                @endif
              </td>
             <td>
                @if (empty($incident->predict_category))
                    --
                @else
                    {{-- Affichage du type (Request ou Incident) --}}
                    @if ($incident->incident == 0)
                        <span class="badge bg-danger">Request</span>
                    @else
                        <span class="badge bg-success">Incident</span>
                    @endif
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

