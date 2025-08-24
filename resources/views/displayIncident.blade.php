@extends('index')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <!-- Card -->
      <div class="card shadow-lg rounded-4 border-0">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#1b4459; border-top-left-radius:1rem; border-top-right-radius:1rem;">
          <h5 class="mb-0 text-white">Incident Details</h5>
          <span class="badge bg-warning text-dark">Active</span>
        </div>

        <!-- Body -->
        <div class="card-body p-4">

          <div class="mb-3">
            <h6 class="text-muted">Incident Number</h6>
            <p class="fw-bold text-dark">{{$incident->number}}</p>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <h6 class="text-muted">Requested For</h6>
              <p class="fw-semibold">{{$incident->requested_for}}</p>
            </div>
            <div class="col-md-6 mb-3">
              <h6 class="text-muted">Category</h6>
              <span class="badge bg-primary">{{$incident->category}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
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
            <div class="col-md-6 mb-3">
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

          <div class="mb-3">
            <h6 class="text-muted">Predicted Category</h6>
            <span class="badge bg-info text-dark">{{$incident->predict_category}}</span>
          </div>

          <div class="mb-3">
            <h6 class="text-muted">Type of ticket</h6>
             @if($incident->incident == 0)
                  <span class="badge bg-danger">Request</span>
              @else
                  <span class="badge bg-success">Incident</span>
                @endif
          </div>

          <div>
            <h6 class="text-muted">Created At Servicenow</h6>
            <p class="fw-light">{{$incident->created_at_servicenow}}</p>
          </div>

        </div>
      </div>
      <!-- End Card -->

    </div>
  </div>
</div>

@endsection