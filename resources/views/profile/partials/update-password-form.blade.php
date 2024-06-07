<section>
    <form method="post" action="{{ route('password.update') }}" class="card mt-6 space-y-6">
        @csrf
        @method('put')
        <div class="card-header pb-0">
            <h4 class="card-title mb-0">Edit Password</h4>
            <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-sm-6 col-md-6">
                    <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-6">
                    <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <x-text-input id="update_password_password" name="password" type="password" class="form-control mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="col-sm-6 col-md-6">
                    <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer text-end">

            <button class="btn btn-primary" type="submit">Update Password</button>
            @if (session('status') === 'password-updated')
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
