@extends('layouts.web')

@section('content')
    @include('front.partials.breadcumb')
    <!-- Team Start -->
    <section class="py-5 bg-soft">
        <div class="container py-5 text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <!-- Section Header -->
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-6">
                    <h2 class="section-title">Badan Pengembangan Bisnis</h2>
                    <p class="text-muted">Bersinergi Membangun Manfaat untuk Semua</p>
                </div>
            </div>

            <!-- Team Members -->
            @foreach ($teams as $team)
                <div class="row g-4">
                    <!-- Team Member 1 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="team-card h-100">
                            <img src="{{ is_user('storage/' . $team->image) }}" alt="Team member" class="team-image w-100">
                            <div class="p-4">
                                <span class="role-tag">Founder</span>
                                <h5>Alexandra Chen</h5>
                                <p class="text-muted mb-4">Visionary leader with a passion for innovation and sustainable
                                    growth.</p>
                                <div class="member-social d-flex">
                                    <a href="#" class="social-link">LinkedIn</a>
                                    <a href="#" class="social-link">Twitter</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
