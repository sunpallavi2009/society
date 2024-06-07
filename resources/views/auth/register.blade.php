<x-guest-layout>
    <div class="login-main"> 
        <form class="theme-form" method="POST" action="{{ route('register') }}">
                @csrf
          <h4 class="h3login">Create your account</h4>
          <p class="plogin">Enter your personal details to create account</p>

            <div class="form-group">
                <label class="col-form-label pt-0">Your Name</label>
                <input class="form-control" type="text" name="name" :value="old('name')" required placeholder="First name">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
          
            <div class="form-group">
                <label class="col-form-label">Email Address</label>
                <input class="form-control" type="email" name="email" :value="old('email')" required placeholder="Test@gmail.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

          <div class="form-group">
            <label class="col-form-label">Password</label>
            <div class="form-input position-relative">
              <input class="form-control" type="password" name="password"
              required placeholder="*********">
              <div class="show-hide"><span class="show"></span></div>
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-form-label">Confirm Password</label>
            <div class="form-input position-relative">
              <input class="form-control" type="password" name="password_confirmation"
              required placeholder="*********">
              <div class="show-hide"><span class="show"></span></div>
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
          </div>



          <div class="text-end mt-3">
            <button class="btn btn-primary btn-block w-100" type="submit">Create Account</button>
          </div>

          <p class="mt-4 mb-0">Already have an account?<a class="ms-2 text-primary text-gradient font-weight-bold" href="{{ route('login') }}">Sign in</a></p>
        </form>
      </div>
</x-guest-layout>
