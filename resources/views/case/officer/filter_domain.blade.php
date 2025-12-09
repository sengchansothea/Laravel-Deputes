<form action="{{ url('domain') }}" method="GET">
    @csrf
    <div class="row">
        <div class="col-sm-6">

        </div>
        <div class="col-sm-5">
            @php
                $arrDomains = [
                    1 => 'ការិយាល័យទី១',
                    2 => 'ការិយាល័យទី២',
                    3 => 'ការិយាល័យទី៣',
                    4 => 'ការិយាល័យទី៤',
                    0 => 'ទាំងអស់',
                    ];
            @endphp
            {!! showSelect("domain",$arrDomains, old("domain", request('domain'))) !!}
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-success">បង្ហាញ</button>
        </div>
    </div>
</form>

