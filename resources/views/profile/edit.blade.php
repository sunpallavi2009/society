@extends('layouts.main')
@section('content')
<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>
            <div class="container-fluid">
              <div class="page-title">
                <div class="row">
                  <div class="col-sm-6 ps-0">
                    <h3>Edit Profile</h3>
                  </div>
                  <div class="col-sm-6 pe-0">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">                                       
                          <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                          </svg></a></li>
                      <li class="breadcrumb-item">Users</li>
                      <li class="breadcrumb-item active"> Edit Profile</li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
            <!-- Container-fluid starts-->
            <div class="container-fluid">
              <div class="edit-profile">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-header pb-0">
                        <h4 class="card-title mb-0">My Profile</h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                      </div>
                      <div class="card-body">
                        <form>
                          <div class="row mb-2">
                            <div class="profile-title">
                              <div class="d-flex"><img class="img-70 rounded-circle" alt="" src="{{ asset('assets/images/user/7.jpg') }}">
                                <div class="flex-grow-1">
                                  <h3 class="mb-1">{{ Auth::user()->name }}</h3>
                                  <p>{{ Auth::user()->email }}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                          {{-- <div class="mb-3">
                            <h6 class="form-label">Bio</h6>
                            <textarea class="form-control" rows="5">On the other hand, we denounce with righteous indignation</textarea>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Email-Address</label>
                            <input class="form-control" placeholder="your-email@domain.com">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" value="password">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input class="form-control" placeholder="http://Uplor .com">
                          </div>
                          <div class="form-footer">
                            <button class="btn btn-primary btn-block">Save</button>
                          </div> --}}
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-8">
                    
                    @include('profile.partials.update-profile-information-form')

                    @include('profile.partials.update-password-form')

                  </div>
                 
                </div>
              </div>
            </div>
           
     
@endsection
