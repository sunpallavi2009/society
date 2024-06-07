<section>
    
        <form method="post" action="{{ route('profile.update') }}" class="card">
            @csrf
            @method('patch')
            <div class="card-header pb-0">
                <h4 class="card-title mb-0">Edit Profile</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
                <div class="row">

                <div class="col-sm-6 col-md-6">
                    <div class="mb-3">
                    <label class="form-label">Username</label>
                    <x-text-input id="name" name="name" type="text" class="form-control mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-6">
                    <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <x-text-input id="email" name="email" type="email" class="form-control mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm mt-2 text-gray-800">
                                    {{ __('Your email address is unverified.') }}
            
                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>
            
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
                </div>
            </div>
            <div class="card-footer text-end">

                <button class="btn btn-primary" type="submit">Update Profile</button>
                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    ></p>
                @endif
            </div>
        </form>
  
</section>
