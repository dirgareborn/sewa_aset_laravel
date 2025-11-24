<div class="row g-4 mt-5 pt-4 border-top">
    <div class="col-md-3">
      <h6 class="fw-semibold mb-3">Penilaian Pengguna</h6>
      <div class="text-center my-3">
        <h2 class="fw-bold mb-1">{{ number_format($average_rating, 1) }}</h2>
        <div class="text-warning small mb-1"> 
          @for ($i = 1; $i <= 5; $i++) 
          <i class="bi bi-star{{ $i <= $average_rating ? '-fill' : '' }}"></i> 
            @endfor 
          </div>
          <p class="text-muted small mb-0">{{ $total_reviews }} ulasan</p>
        </div> 
        @foreach ($rating_breakdown as $star => $count) 
        <div class="d-flex align-items-center mb-2">
          <span class="small me-2">{{ $star }}★</span>
          <div class="progress flex-grow-1 rounded-pill" style="height: 6px;">
            <div class="progress-bar bg-warning" style="width: {{ $rating_percentage[$star] }}%"></div>
          </div>
          <span class="small text-muted ms-2">{{ $rating_percentage[$star] }}%</span>
        </div> 
        @endforeach
      </div>
      <div class="col-md-9">
        <h6 class="fw-semibold mb-3">Ulasan Pengguna</h6> 
        @auth
        <!-- $canReview && ! --> 
        @if ($userReview) 
        <div class="mb-4 border-0 rounded-3">
          <h6 class="fw-semibold mb-2">Edit Ulasan Anda</h6>
            <form action="{{ route('service.review.update', $service->id) }}" method="POST"> @csrf @method('PUT') 
              <label class="form-label small">Rating</label>
              <div class="rating-stars mb-2">
                <input type="hidden" autocomplete="rating" name="rating" id="rating-value" value="{{ $userReview->rating ?? 0 }}">
                <div class="stars"> 
                  @for ($i = 1; $i <= 5; $i++) <i class="bi bi-star-fill star fs-5 me-1" data-value="{{ $i }}"></i> 
                  @endfor 
                </div>
                </div>
                <label class="form-label small">Ulasan</label>
                <textarea autocomplete="review" name="review" rows="2" class="form-control small mb-3" required>{{ $userReview->review }}</textarea>
                <div class="d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary btn-sm px-3">Update</button> {{-- DELETE --}}
                  <form action="{{ route('service.review.delete', $service->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')"> 
                    @csrf 
                    @method('DELETE') 
                    <button class="btn btn-outline-danger btn-sm px-3">Hapus</button>
                  </form>
                </div>
              </form>
          </div> 
          @else 
          <div class=" mb-4 rounded-3 border-0">
              <h6 class="fw-semibold mb-2">Tulis Ulasan</h6>
              <form action="{{ route('service.review.store', $service->id) }}" method="POST"> @csrf <div class="rating-stars mb-3">
                <input type="hidden" autocomplete="rating" name="rating" id="rating-value" value="{{ $userReview->rating ?? 0 }}">
                <div class="stars"> 
                  @for ($i = 1; $i <= 5; $i++) 
                  <i class="bi bi-star-fill star fs-5 me-1" data-value="{{ $i }}"></i> 
                @endfor 
              </div>
                </div>
                <label class="form-label small">Ulasan</label>
                <textarea autocomplete="review" name="review" rows="2" class="form-control small mb-3" required></textarea>
                <button type="submit" class="btn btn-primary btn-sm px-3">Kirim</button>
              </form>
          </div>
          @endif 
          @endauth 
          @forelse ($reviews as $rev) 
          <div class="border rounded-3 p-3 mb-3 bg-white">
            <strong>{{ $rev->user->name }}</strong>
            <div class="text-warning small"> 
              @for ($i = 1; $i <= 5; $i++) 
              <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i> 
              @endfor 
            </div>
            <p class="mb-2 small">{{ $rev->review }}</p>
            <small class="text-muted">{{ $rev->created_at->diffForHumans() }}</small>
          </div> @empty <p class="text-muted small">Belum ada ulasan.</p> @endforelse
        </div>
      </div>
    