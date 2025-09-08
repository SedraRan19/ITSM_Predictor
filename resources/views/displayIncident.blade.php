@extends('index')

@section('content')
<div class="container mt-5 mb-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <!-- Card -->
      <div class="card shadow-lg rounded-4 border-0">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#1b4459; border-top-left-radius:1rem; border-top-right-radius:1rem;">
          <h5 class="mb-0 text-white">Incident Details</h5>
          {{-- <span class="badge bg-warning text-dark">Active</span> --}}
        </div>

        <!-- Body -->
        <div class="card-body p-4">
          @if(session()->has("success"))
          <div class="alert alert-success" >
              {{session()->get('success')}}
          </div>
          @endif
          <div class="mb-2">
            <h6 class="text-muted">Incident Number</h6>
            <p class="fw-bold text-dark">{{$incident->number}}</p>
          </div>

          <div class="row">
            <div class="col-md-6">
              <h6 class="text-muted">Requested For</h6>
              <p class="fw-semibold">{{$incident->requested_for}}</p>
            </div>
            <div class="col-md-6">
              <h6 class="text-muted">Category</h6>
              <span class="badge bg-primary">{{$incident->category}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <h6 class="text-muted">Priority</h6>
              {{-- <span class="badge bg-danger">{{$incident->priority}}</span> --}}
              @php
                $priorityLabels = [
                    1 => ['label' => 'Critical', 'class' => 'bg-danger'],   // Red
                    2 => ['label' => 'High', 'class' => 'bg-warning'],      // Orange/Yellow
                    3 => ['label' => 'Moderate', 'class' => 'bg-primary'],  // Blue
                    4 => ['label' => 'Low', 'class' => 'bg-success'],       // Green
                ];

                $priority = $priorityLabels[$incident->priority] ?? ['label' => 'Unknown', 'class' => 'bg-secondary'];
            @endphp

            <span class="badge {{ $priority['class'] }}">
                {{ $priority['label'] }}
            </span>

            </div>
            <div class="col-md-6">
              <h6 class="text-muted">Service Desk</h6>
              <p class="badge bg-danger">{{$incident->service_desk}}</p>
            </div>
          </div>

          <div class="mb-3">
            <h6 class="text-muted">Assignment Group</h6>
            <p class="fw-semibold">{{$incident->assignment_group}}</p>
          </div>

          <div class="mb-3">
            <h6 class="text-muted">Short Description</h6>
            <p class="fw-normal">{{$incident->short_description}}</p>
          </div>

          <div class="mb-3">
            <h6 class="text-muted">Description</h6>
            <p class="fw-light">{{$incident->description}}</p>
          </div>
           <div>
            <h6 class="text-muted">Created At Servicenow</h6>
            <p class="fw-light">{{$incident->created_at_servicenow}}</p>
          </div>
          <form method="POST" action="{{ route('incidents.update', $incident->id) }}">
              @csrf
              @method('PUT')

              <div class="row">
                  <!-- Predicted Category -->
                  <div class="col-md-6 mb-3">
                      <h6 class="text-muted">Predicted Category</h6>
                      <span class="badge bg-info text-dark" id="categoryEdit">{{$incident->predict_category}}</span>
                      <span class="tf-icons bx bx-edit-alt" style="cursor: pointer;" onclick="toggleCategoryEdit()"></span>
                      <select id="categoryDisplay" class="form-select d-none" name="predict_category">
                          <option value="End user and support" {{ $incident->predict_category == 'End user and support' ? 'selected' : '' }}>End user and support</option>
                          <option value="Mobile money / Fintech" {{ $incident->predict_category == 'Mobile money / Fintech' ? 'selected' : '' }}>Mobile money / Fintech</option>
                          <option value="Enterprise Application" {{ $incident->predict_category == 'Enterprise Application' ? 'selected' : '' }}>Enterprise Application</option>
                          <option value="IT/Cloud" {{ $incident->predict_category == 'IT/Cloud' ? 'selected' : '' }}>IT/Cloud</option>
                          <option value="Data Center" {{ $incident->predict_category == 'Data Center' ? 'selected' : '' }}>Data Center</option>
                          <option value="Telecom" {{ $incident->predict_category == 'Telecom' ? 'selected' : '' }}>Telecom</option>
                      </select>
                  </div>

                  <!-- Type of Ticket -->
                  <div class="col-md-6 mb-3">
                      <h6 class="text-muted">Type of ticket</h6>
                      @if (empty($incident->predict_category))
                      @else
                          {{-- Affichage du type (Request ou Incident) --}}
                          @if ($incident->incident == 0)
                              <span class="badge bg-danger">Request</span>
                          @else
                              <span class="badge bg-success">Incident</span>
                          @endif
                      @endif
                      <span class="tf-icons bx bx-edit-alt" style="cursor: pointer;" onclick="toggleTicketTypeEdit()"></span>
                      <select id="ticketTypeSelect" class="form-select d-none" name="incidentType">
                          <option value="0" {{ $incident->incident == 0 ? 'selected' : '' }}>Request</option>
                          <option value="1" {{ $incident->incident == 1 ? 'selected' : '' }}>Incident</option>
                      </select>
                  </div>
              </div>

              <button type="submit" class="btn btn-outline-primary btn-sm w-100" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                Update and add into new dataset
              </button>
          </form>

         <div class="row">
          <div class="col-md-6">
              <form action="{{ route('predict_DL_cat', $incident->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-outline-dark w-100 mt-2">
                      <i class="bx bx-sync"></i> Generate category with model #2
                  </button>
              </form>
          </div>
          <div class="col-md-6">
              <form action="{{ route('predict_DL_typeOfTicket', $incident->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-outline-dark w-100 mt-2">
                      <i class="bx bx-sync"></i> Generate type of ticket with model #2
                  </button>
              </form>
          </div>
      </div>
          </div>
      </div>
      <!-- End Card -->
    </div>
  </div>
</div>

@endsection