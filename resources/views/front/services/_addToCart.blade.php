<form id="addCart" name="addCart" action="javascript:;" method="POST">
  <!-- @csrf -->
  <input type="hidden" name="service_id" value="{{ $service['id'] }}">
  <div class="row g-2">
    <div class="col-md-10">
      <div class="row g-2">
        <div class="col-md-4">
          <input id="start" name="start" class="date form-control px-3 small" placeholder="Mulai Tanggal ">
        </div>
        <div class="col-md-4">
          <input id="end" name="end" class="date form-control  px-3 small" placeholder="Sampai Tanggal">
        </div>
        <div class="col-md-4">
          <button class="btn btn-primary btn-sm px-3" type="submit">
            <span class="fa fa-heart"></span> Booking </button>
        </div>
      </div>
    </div>
  </div>
</form>
<div class="print-error-msg"></div>
<div class="print-success-msg"></div>