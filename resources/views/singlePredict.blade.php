@extends('index')

@section('content')
<div class="container py-5">

  <div class="ai-form mb-2" style="background-color: #1b4459">
    <h4 style="color: #fff"><i class='bx bx-analyse' ></i> Generate </h4>
    <form method="POST" action="{{route('predict_category')}}">
        @csrf
        <textarea class="form-control mb-3" rows="5" placeholder="Paste your description..." name="ticket_text"></textarea>
        <button class="btn btn-warning" {{--onclick="showResult('result2')"--}} type="submit">
        <i class='bx bx-bulb'></i> Predict
        </button>
    </form>
    <div id="result2" class="result-section mt-3">
      <h5>Predictions:</h5>
      <table class="table table-dark table-hover">
        <thead>
          <tr><th>#</th><th>Status</th><th>Message</th></tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>✅ Success</td><td>Data refreshed successfully</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div>
<div class="container py-5">
    <h5>Predictions:</h5>
    <table class="table table-dark table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Description</th>
          <th>Category prediction</th>
          <th>Type of ticket</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($predictions as $prediction)
          <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$prediction->description}}</td>
          <td>{{$prediction->predict_category}}</td>
          <td>
            @if($prediction->incident == 0)
                <span class="badge bg-danger">Request</span>
            @else
                <span class="badge bg-success">Incident</span>
            @endif
          </td>
          </tr>
        @endforeach
      </tbody>
    </table>
</div>

@endsection