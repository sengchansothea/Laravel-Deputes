<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        {{--        @include('layouts.test')--}}
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('general.k_username') }} (សម្រាប់ Login ចូលប្រព័ន្ធ) <span class="badge badge-warning">{{ Auth::user()->username }}</span></h5>
                    </div>
                    <div class="card-body">
                        <form class="theme-form needs-validation" name="formChangePassword" action="{{ url('user/change_password/owner') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ Auth::user()->id }}" />
                            <div class="mb-3">
                                <label class="col-form-label pt-0" for="exampleInputEmail1">
                                    ពាក្យសម្ងាត់ចាស់
                                </label>
                                <input class="form-control" name="old_password" id="old_password" type="password" aria-describedby="passwordHelp" placeholder="Old Password" required>
                                @error('old_password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label pt-0" for="exampleInputPassword1">
                                    ពាក្យសម្ងាត់ថ្មី (តិចបំផុត៤ខ្ទង់)
                                </label>
                                <input class="form-control" name="new_password" id="new_password" type="password" placeholder="Password" minlength="4" required>
                                @error('new_password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label pt-0" for="exampleInputPassword1">
                                    ពាក្យសម្ងាត់ម្ដងទៀត
                                </label>
                                <input class="form-control" name="new_password_confirmation" id="confirm_new_password" type="password" minlength="4" placeholder="Confirm Password" required>
                                @error('new_password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br/>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-warning">ប្តូរលេខសម្ងាត់</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        {{--        <script src="{{ rurl('assets/js/form-validation-custom.js') }}"></script>--}}
    </x-slot>
</x-admin.layout-main>
