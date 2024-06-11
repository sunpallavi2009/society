@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 p-0">
                <h3>Webpanel</h3>
            </div>
            <div class="col-sm-6 p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item active">Webpanel</li>
                   
                </ol>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid ecommerce-dashboard">
    <div class="row"> 
      
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center text-white">
                        <h6 class="text-black">
                            <h3><b>{{ $society->name }}</b></h3>
                            <h6>{{ $society->address1 }}</h6>
                        </h6>
                    </div>
                </div>
            </div>
        </div>

      <div class="col-xxl-12 col-xl-12 col-lg-12 box-col-12">
        <div class="row"> 

          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card total-sales">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                    <div class="d-flex"> 
                        <span> 
                            <svg>
                            <use href="{{ asset('assets/svg/icon-sprite.svg#Revenue') }}"></use>
                            </svg>
                        </span>
                      <div class="flex-shrink-0 pt-4"> 
                        {{-- <h4>$73,927</h4> --}}
                        <a href="{{ route('members.index', ['group' => 'Sundry Debtors', 'guid' => $societyGuid]) }}" style="color: #337ab7;">
                            <h6>Member Ledgers</h6>
                        </a>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card total-sales">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                    <div class="d-flex up-sales"><span> 
                        <svg>
                          <use href="{{ asset('assets/svg/icon-sprite.svg#Sales') }}"></use>
                        </svg></span>
                      <div class="flex-shrink-0 pt-4"> 
                        {{-- <h4>24k USD</h4> --}}
                        <a href="{{ route('members.index', ['group' => '!Sundry Debtors', 'guid' => $societyGuid]) }}" style="color: #337ab7;">
                            <h6>Other Ledgers</h6>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card total-sales">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                    <div class="d-flex total-customer"><span> 
                        <svg>
                          <use href="{{ asset('assets/svg/icon-sprite.svg#Customer') }}"></use>
                        </svg></span>
                      <div class="flex-shrink-0 pt-4"> 
                        {{-- <h4>62,828</h4> --}}
                        <a href="{{ route('bills.index', ['date' => date("01-m-Y"), 'guid' => $societyGuid]) }}" style="color: #337ab7;">
                            <h6>Bills</h6>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card total-sales">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                    <div class="d-flex total-product"><span> 
                        <svg>
                          <use href="{{ asset('assets/svg/icon-sprite.svg#Product') }}"></use>
                        </svg></span>
                      <div class="flex-shrink-0 pt-4"> 
                        {{-- <h4>72,982</h4> --}}
                        <a href="{{ route('memberOutstanding.index', ['from_date' => date("01-m-Y"),'to_date' => date("01-m-Y"), 'guid' => $societyGuid]) }}" style="color: #337ab7;">
                            <h6>Outstanding</h6>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card total-sales">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                    <div class="d-flex total-customer"><span> 
                        <svg>
                          <use href="{{ asset('assets/svg/icon-sprite.svg#Customer') }}"></use>
                        </svg></span>
                      <div class="flex-shrink-0 p-4"> 
                        {{-- <h4>62,828</h4> --}}
                        {{-- <a href="{{ route('members.index') }}?guid={{ $societyGuid }}" style="color: #337ab7;"> --}}
                            <h6>Add Voucher</h6>
                        {{-- </a> --}}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card total-sales">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                    <div class="d-flex total-product"><span> 
                        <svg>
                          <use href="{{ asset('assets/svg/icon-sprite.svg#Product') }}"></use>
                        </svg></span>
                      <div class="flex-shrink-0 p-4"> 
                        {{-- <h4>72,982</h4> --}}
                        <h6>Day Book</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      
    </div>
</div>
@endsection
@php
function getFinancialYearStart() {
    $currentMonth = date('m');
    $currentYear = date('Y');

    if ($currentMonth < 4) {
        return date('01-04-Y', strtotime('last year'));
    } else {
        return date('01-04-Y', strtotime('this year'));
    }
}
@endphp
