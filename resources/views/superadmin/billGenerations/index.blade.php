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
                        </svg></a></li>
                    <li class="breadcrumb-item active">Bill Generation list</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center text-white">
                        <h6 class="text-black">
                            @foreach ($society as $company)
                                <h3><b>{{ $company->name }}</b></h3>
                                <h6>{{ $company->address1 }}</h6>
                            @endforeach
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                  <div class="row g-xl-5 g-3">
                    <div class="col-xxl-3 col-xl-4 box-col-4e sidebar-left-wrapper">
                      <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" id="detail-product-tab" data-bs-toggle="pill" href="#detail-product" role="tab" aria-controls="detail-product" aria-selected="false">
                            <div class="nav-rounded">
                              <div class="product-icons">
                                <svg class="stroke-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#product-detail') }}"></use>
                                </svg>
                              </div>
                            </div>
                            <div class="product-tab-content">
                              <h6>Add Society Details</h6>
                              <p>Add Regular heads</p>
                            </div></a></li>
                        <li class="nav-item"> <a class="nav-link" id="gallery-product-tab" data-bs-toggle="pill" href="#gallery-product" role="tab" aria-controls="gallery-product" aria-selected="false">
                            <div class="nav-rounded">
                              <div class="product-icons">
                                <svg class="stroke-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#product-gallery') }}"></use>
                                </svg>
                              </div>
                            </div>
                            <div class="product-tab-content">
                              <h6>Interest</h6>
                              <p>calculated as per outstanding</p>
                            </div></a></li>
                        <li class="nav-item"> <a class="nav-link" id="category-product-tab" data-bs-toggle="pill" href="#category-product" role="tab" aria-controls="category-product" aria-selected="false">
                            <div class="nav-rounded">
                              <div class="product-icons">
                                <svg class="stroke-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#product-category') }}"></use>
                                </svg>
                              </div>
                            </div>
                            <div class="product-tab-content">
                              <h6>Product Categories</h6>
                              <p>Add Product category, Status and Tags</p>
                            </div></a></li>
                        <li class="nav-item"><a class="nav-link" id="pricings-tab" data-bs-toggle="pill" href="#pricings" role="tab" aria-controls="pricings" aria-selected="false">
                            <div class="nav-rounded">
                              <div class="product-icons">
                                <svg class="stroke-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#pricing') }}"> </use>
                                </svg>
                              </div>
                            </div>
                            <div class="product-tab-content">
                              <h6>Selling prices</h6>
                              <p>Add Product basic price & Discount</p>
                            </div></a></li>
                        <li class="nav-item"><a class="nav-link" id="advance-product-tab" data-bs-toggle="pill" href="#advance-product" role="tab" aria-controls="advance-product" aria-selected="false">
                            <div class="nav-rounded">
                              <div class="product-icons">
                                <svg class="stroke-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#advance') }}"> </use>
                                </svg>
                              </div>
                            </div>
                            <div class="product-tab-content">
                              <h6>Advance</h6>
                              <p>Add Meta details & Inventory details</p>
                            </div></a></li>
                      </ul>
                    </div>
                    <div class="col-xxl-9 col-xl-8 box-col-8 position-relative">
                      <div class="tab-content" id="add-product-pills-tabContent">
                        <div class="tab-pane fade show active" id="detail-product" role="tabpanel" aria-labelledby="detail-product-tab">
                          <div class="sidebar-body">
                            <form class="row g-2">

                                <div class="col-xxl-11 col-sm-12">
                                    <label class="form-label" for="validationCustom04">Ledger</label>
                                        <select class="form-select" name="ledger" id="validationCustom04" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            @foreach($voucherEntries as $voucherEntry)
                                                <option value="{{ $voucherEntry->id }}">{{ $voucherEntry->ledger }}</option>
                                            @endforeach
                                        </select>
                                    <div class="invalid-feedback">Please select a valid Ledger.</div>
                                </div>

                            </form>

                            {{--  <div class="product-buttons">
                              <div class="btn">
                                <div class="d-flex align-items-center gap-sm-2 gap-1" id="nextbtn" onclick="nextStep()">Next
                                  <svg>
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#front-arrow') }}">  </use>
                                  </svg>
                                </div>
                              </div>
                            </div>  --}}

                          </div>
                        </div>

                        <div class="tab-pane fade" id="gallery-product" role="tabpanel" aria-labelledby="gallery-product-tab">
                          <div class="sidebar-body">
                            



                          </div>
                        </div>

                        <div class="tab-pane fade" id="category-product" role="tabpanel" aria-labelledby="category-product-tab">
                          <div class="sidebar-body">
                           



                          </div>
                        </div>

                        <div class="tab-pane fade" id="pricings" role="tabpanel" aria-labelledby="pricings-tab">
                          <div class="sidebar-body">
                            


                          </div>
                        </div>

                        <div class="tab-pane fade" id="advance-product" role="tabpanel" aria-labelledby="advance-product-tab">
                          <div class="sidebar-body advance-options">
                            <ul class="nav nav-tabs border-tab mb-0" id="advance-option-tab" role="tablist">
                              <li class="nav-item"><a class="nav-link active" id="manifest-option-tab" data-bs-toggle="tab" href="#manifest-option" role="tab" aria-controls="manifest-option" aria-selected="true">Inventory</a></li>
                              <li class="nav-item"><a class="nav-link" id="additional-option-tab" data-bs-toggle="tab" href="#additional-option" role="tab" aria-controls="additional-option" aria-selected="false">Additional Options</a></li>
                              <li class="nav-item"><a class="nav-link" id="dropping-option-tab" data-bs-toggle="tab" href="#dropping-option" role="tab" aria-controls="dropping-option" aria-selected="false">Shipping</a></li>
                            </ul>
                            <div class="tab-content" id="advance-option-tabContent">
                              <div class="tab-pane fade show active" id="manifest-option" role="tabpanel" aria-labelledby="manifest-option-tab">
                                <div class="meta-body">
                                  <form id="advance-tab">
                                    <div class="row g-3 custom-input">
                                      <div class="col-sm-6">
                                        <label class="form-label">Stock Availability</label>
                                        <select class="form-select" aria-label="Default select example">
                                          <option selected="">In stock</option>
                                          <option value="1">Out of stock</option>
                                          <option value="2">Pre-order</option>
                                        </select>
                                      </div>
                                      <div class="col-sm-6">
                                        <label class="form-label">Low Stock</label>
                                        <select class="form-select" aria-label="Default select example">
                                          <option selected="">Low Stock (5 or less)</option>
                                          <option value="1">Low Stock (10 or less)</option>
                                          <option value="2">Low Stock (20 or less)</option>
                                          <option value="2">Low Stock (25 or less)</option>
                                          <option value="2">Low Stock (30 or less)</option>
                                        </select>
                                      </div>
                                      <div class="col-lg-3 col-sm-6">
                                        <label class="form-label" for="exampleFormControlInput1">SKU <span class="txt-danger">*</span></label>
                                        <input class="form-control" id="exampleFormControlInput1" type="text">
                                      </div>
                                      <div class="col-lg-3 col-sm-6">
                                        <label class="form-label" for="exampleFormControlInputa">Stock Quantity <span class="txt-danger">*</span></label>
                                        <input class="form-control" id="exampleFormControlInputa" type="number" value="7" min="0">
                                      </div>
                                      <div class="col-lg-3 col-sm-6">
                                        <label class="form-label" for="exampleFormControlInputb">Restock Date <span class="txt-danger">*</span></label>
                                        <input class="form-control" id="exampleFormControlInputb" type="number">
                                      </div>
                                      <div class="col-lg-3 col-sm-6">
                                        <label class="form-label" for="exampleFormControlInputc">Pre-Order <span class="txt-danger">*</span></label>
                                        <input class="form-control" id="exampleFormControlInputc" type="number">
                                      </div>
                                      <div class="col-12">
                                        <label class="form-label">Allow Backorders</label>
                                        <div class="form-check">
                                          <input class="form-check-input" id="gridCheck" type="checkbox">
                                          <label class="form-check-label m-0" for="gridCheck">This is a digital Product</label>
                                          <p class="f-light">Decide if the product is a digital or physical item. Shipping may be necessary for real-world items.</p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="product-buttons">
                                      <div class="btn">
                                        <div class="d-flex align-items-center gap-sm-2 gap-1">
                                          <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#back-arrow') }}"></use>
                                          </svg>Previous
                                        </div>
                                      </div>
                                      <div class="btn">
                                        <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                          <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#front-arrow') }}"></use>
                                          </svg>
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="additional-option" role="tabpanel" aria-labelledby="additional-option-tab">
                                <div class="meta-body">
                                  <form>
                                    <div class="row g-3">
                                      <div class="col-12">
                                        <div class="row g-3">
                                          <div class="col-sm-6">
                                            <div class="row custom-input">
                                              <div class="col-12">
                                                <label class="form-label" for="tagTitle">Additional Tag Title</label>
                                              </div>
                                              <div class="col-12">
                                                <input class="form-control" id="tagTitle" type="text">
                                                <p class="f-light">Add a new tag title. Keywords should be simple and accurate.</p>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-sm-6">
                                            <div class="row product-tag">
                                              <label class="form-label col-12">Specific Tags</label>
                                              <div class="col-12">
                                                <input id="specificTag" name="basic-tags1" value="watches, sports, clothes, bottles">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-12">
                                            <div class="row">
                                              <div class="col-12">
                                                <label class="form-label col-12">Additional Description</label>
                                                <div class="toolbar-box">
                                                  <div id="toolbar4"><span class="ql-formats">
                                                      <select class="ql-size"></select></span><span class="ql-formats">
                                                      <button class="ql-bold">Bold </button>
                                                      <button class="ql-italic">Italic </button>
                                                      <button class="ql-underline">underline</button>
                                                      <button class="ql-strike">Strike </button></span><span class="ql-formats">
                                                      <button class="ql-list" value="ordered">List </button>
                                                      <button class="ql-list" value="bullet"> </button>
                                                      <button class="ql-indent" value="-1"> </button>
                                                      <button class="ql-indent" value="+1"></button></span><span class="ql-formats">
                                                      <button class="ql-link"></button>
                                                      <button class="ql-image"></button>
                                                      <button class="ql-video"></button></span></div>
                                                  <div id="editor4"></div>
                                                </div>
                                                <p class="f-light">Enhance your SEO ranking with an added tag description for the product.</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="product-buttons">
                                      <div class="btn">
                                        <div class="d-flex align-items-center gap-sm-2 gap-1">
                                          <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#back-arrow') }}"></use>
                                          </svg>Previous
                                        </div>
                                      </div>
                                      <div class="btn">
                                        <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                          <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#front-arrow') }}"></use>
                                          </svg>
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="dropping-option" role="tabpanel" aria-labelledby="dropping-option-tab">
                                <div class="meta-body">
                                  <form>
                                    <div class="row g-3 custom-input">
                                      <div class="col-12">
                                        <div class="row gx-xl-3 gx-md-2 gy-md-0 g-2">
                                          <div class="col-12">
                                            <label class="form-label" for="exampleFormControlInput1">Where can I pick up my order?</label>
                                          </div>
                                          <div class="col-md-4 col-sm-6">
                                            <input class="form-control" id="inputZip" type="number" placeholder="Zip code (10001)">
                                          </div>
                                          <div class="col-md-4 col-sm-6">
                                            <input class="form-control" id="inputCity" type="text" placeholder="City">
                                          </div>
                                          <div class="col-md-4">
                                            <select class="form-select" id="inputState">
                                              <option selected="">State</option>
                                              <option>Gujarat</option>
                                              <option>Punjab</option>
                                              <option>Himachal pradesh</option>
                                              <option>Goa </option>
                                              <option>Sikkim </option>
                                              <option>Telangana</option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12">
                                        <div class="row">
                                          <div class="col-12">
                                            <label class="form-label" for="exampleFormControlInput1">Weight (kg)</label><i class="icon-help-alt ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="set proper weight for product items."></i>
                                          </div>
                                          <div class="col-12">
                                            <input class="form-control" id="inputCitya" type="number">
                                            <p class="f-light">Decide if the product is a digital or physical item. Shipping may be necessary for real-world items.</p>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12">
                                        <div class="row gx-xl-3 gx-md-2 gy-md-0 g-2">
                                          <div class="col-12">
                                            <label class="form-label" for="exampleFormControlInput1">Dimensions </label><i class="icon-help-alt ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="set proper length/width and height for product items."></i>
                                          </div>
                                          <div class="col-md-4 col-sm-6">
                                            <input class="form-control" id="inputCityb" type="number" placeholder="Length[l]">
                                          </div>
                                          <div class="col-md-4 col-sm-6">
                                            <input class="form-control" id="inputCityc" type="number" placeholder="Width[w]">
                                          </div>
                                          <div class="col-md-4">
                                            <input class="form-control" id="inputCityd" type="number" placeholder="Height[h]">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12">
                                        <div class="row">
                                          <div class="col-12">
                                            <label class="form-label" for="exampleFormControlInput1">Shipping Class</label>
                                          </div>
                                          <div class="col-md-12">
                                            <select class="form-select" id="inputState1">
                                              <option selected="">Basic Shipping</option>
                                              <option>Expedited Shipping</option>
                                              <option>International Shipping</option>
                                              <option>Free Shipping</option>
                                              <option>Same-Day or Next-Day Shipping</option>
                                              <option>Flat Rate Shipping</option>
                                              <option>Local Pickup </option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="product-buttons">
                                      <div class="btn">
                                        <div class="d-flex align-items-center gap-sm-2 gap-1">
                                          <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#back-arrow') }}"></use>
                                          </svg>Previous
                                        </div>
                                      </div>
                                      <div class="btn">
                                        <div class="d-flex align-items-center gap-sm-2 gap-1">Submit
                                          <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#front-arrow') }}"></use>
                                          </svg>
                                        </div>
                                      </div>
                                    </div>
                                  </form>
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
    </div>
</div>
@endsection

@push('javascript')
    <!-- Include DataTables Buttons and SearchBuilder JS -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js"></script>
@endpush
