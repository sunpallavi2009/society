@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 p-0">
              <a href="javascript:void(0)" id="back-button">
                  <div class="col-sm-1 card">
                      <div class="card-header pb-0 p-0" style="background-color: none;">
                      <div class="card-header-right top-0">
                          <ul class="list-unstyled card-option">
                          <li>
                              <div><i class="icon-settings icon-angle-double-left"></i></div>
                          </li>
                          </ul>
                      </div>
                      </div>
                  </div>
              </a>
            </div>
            <div class="col-sm-6 p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a>
                      </li>
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
                  <div class="row">
                    <div class="col-sm-10">
                      <div class="text-center text-white">
                          <h6 class="text-black">
                              <h3><b>{{ $society->name }}</b></h3>
                              <h6>{{ $society->address1 }}</h6>
                          </h6>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="btn-group">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true"> Reports</button>
                        <ul class="dropdown-menu dropdown-block" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 37px);">
                          <li><a class="dropdown-item" href="{{ route('receipts.index', ['from_date' => date("01-m-Y"),'to_date' => date("01-m-Y"), 'guid' => $societyGuid]) }}">Receipts</a></li>
                        </ul>
                      </div>
                    </div>
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
                            <h6 style="color: #000000 !important;">Member Ledgers</h6>
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
                            <h6 style="color: #000000 !important;">Other Ledgers</h6>
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
                            <h6 style="color: #000000 !important;">Bills</h6>
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
                            <h6 style="color: #000000 !important;">Outstanding</h6>
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
                            <h6 style="color: #000000 !important;">Add Voucher</h6>
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
                        <a href="{{ route('dayBook.index', ['from_date' => date("01-m-Y"),'to_date' => date("01-m-Y"), 'guid' => $societyGuid]) }}" style="color: #337ab7;">
                          <h6 style="color: #000000 !important;">Day Book</h6>
                        </a>
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
