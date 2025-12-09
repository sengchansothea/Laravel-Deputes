@if ( session()->has('message'))

{{--    <div--}}
{{--        x-data="{ show: true }"--}}
{{--        x-show="show"--}}
{{--        x-init="setTimeout(() => show = false, 5000)"--}}
{{--        class="--}}
{{--            {{ session()->has('messageType')?--}}
{{--                (session('messageType') == "danger"? "bg-red-500": "bg-blue-500"): "bg-blue-500"--}}
{{--            }}--}}
{{--            text-white py-2 px-4 rounded-xl text-sm-center top-3" >--}}
{{--        {{ session('message') }}--}}
{{--    </div>--}}

    <div class="toastr">
        <div class="text-center">
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 9000)"
                class="
                {{ session()->has('messageType')?
               (session('messageType') == "danger"? "btn btn-danger": "btn btn-success"): "btn btn-success" }}
                 m-b-10 m-l-5" id="toastr-danger-top-right" style="font-size: 20px;" >
                {{ session('message') }}
            </div>
        </div>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
