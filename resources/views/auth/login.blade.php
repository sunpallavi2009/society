<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


        <div class="login-main"> 
            <form class="theme-form" method="POST" action="{{ route('login') }}">
                @csrf
                <h3 class="h3login">Sign in to account</h3>
                <p class="plogin">Enter your email & password to login</p>
                <div class="form-group">
                    <label class="col-form-label" style="font-size: 14px;">Email Address</label>
                    <input class="form-control"  type="email" name="email" :value="old('email')" required placeholder="Test@gmail.com" >
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                    <input class="form-control" type="password"  name="password" required  placeholder="*********">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <div class="show-hide"><span class="show">                         </span></div>
                    </div>
                </div>
                <div class="form-group mb-0" style="position: relative;">
                    <div class=" p-0 inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <label class="ms-2 mt-2 text-muted" for="checkbox1" name="remember" >Remember password</label>
                    </div>
                    <div>
                        @if (Route::has('password.request'))
                            <a class="link text-primary text-gradient font-weight-bold" href="{{ route('password.request') }}" style="position: absolute;top: 10px;right: 0;">Forgot password?</a>
                        @endif
                    </div>

                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                    </div>
                </div>
                <p class="mt-4 mb-0 text-center">Don't have account? <a class="text-primary text-gradient font-weight-bold" href="{{ route('register') }}"> Create Account</a></p>
            </form>
      </div>



</x-guest-layout>
