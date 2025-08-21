@extends('layouts.app')
@section('content')
<div class="container">
  <h1>FAQ</h1>
  <div class="accordion" id="faqAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="faqOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">How do I register?</button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse">
        <div class="accordion-body">
          Use the “Register” link above to sign up as a volunteer, organisation or team. You will receive a confirmation email once registered.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="faqTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">How do I track my hours?</button>
      </h2>
      <div id="collapseTwo" class="accordion-collapse collapse">
        <div class="accordion-body">
          After attending an event, your hours are logged automatically. You can view them in your volunteer dashboard.
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
