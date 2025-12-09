<form id="frm_search" action="{{ url('user/case/entry/'.$user->id) }}" method="get">
    <input type="hidden" name="opt_search" value="quick" />
    @csrf
    @php

        $arrInOrOutDomain = [
            '0' => 'មិនកំណត់',
            '1' => "ក្នុងដែនការិយាល័យ",
            '2' => "ក្រៅដែនការិយាល័យ",
            ];
        $arrDomain = [
            '0' => 'ការិយាល័យទាំងអស់',
            '1' => "ការិយាល័យវិវាទការងារទី១",
            '2' => "ការិយាល័យវិវាទការងារទី២",
            '3' => "ការិយាល័យវិវាទការងារទី៣",
            '4' => "ការិយាល័យវិវាទការងារទី៤",
            ];
        $arrCaseStatus = [
            '0' => 'បណ្តឹងទាំងអស់',
            '1' => 'កំពុងដំណើរការ',
            '2' => 'បញ្ចប់',
        ];
        $arrCaseStep = [
            '0' => 'បណ្តឹងទាំងអស់',
            '1' => 'បណ្តឹងថ្មី',
            '2' => 'លិខិតអញ្ជើញកម្មករ',
            '3' => 'លិខិតអញ្ជើញក្រុមហ៊ុន',
            '4' => 'កំណត់់ហេតុសួរកម្មករ',
            '5' => 'កំណត់ហេតុសួរក្រុមហ៊ុន',
            '6' => 'លិខិតអញ្ញើញផ្សះផ្សា',
            '7' => 'កំណត់ហេតុផ្សះផ្សា',
            '8' => 'លើកពេលផ្សះផ្សា',
            '9' => 'ផ្សះផ្សារចប់',
            '10' => 'បិទបញ្ចប់'
        ];
    @endphp
    <div class="row mt-3 mb-2">
        @if(chkUserIdentity() <= 3)
            <div class="form-group col-2">
                <label class="form-label mb-1 fw-bold">នាយកដ្ឋានវិវាទការងារ</label>
                {!! showSelect('domainID',$arrDomain, old('domainID', request('domainID')), "", "onchange='this.form.submit()'") !!}
            </div>
            <div class="form-group col-2">
                <label class="form-label mb-1 fw-bold">ក្នុងឫក្រៅដែនការិយាល័យ</label>
                {!! showSelect('inOutDomain',$arrInOrOutDomain, old('inOutDomain', request('inOutDomain')), "", "onchange='this.form.submit()'") !!}
            </div>
            <div class="form-group col-2">
                <label class="form-label mb-1 fw-bold">ស្ថានភាពបណ្តឹង</label>
                {!! showSelect('statusID',$arrCaseStatus, old('statusID', request('statusID')), "", "onchange='this.form.submit()'") !!}
            </div>
            <div class="form-group col-2">
                <label class="form-label mb-1 fw-bold">ដំណើរការបណ្តឹង</label>
                {!! showSelect('stepID',$arrCaseStep, old('stepID', request('stepID')), "", "onchange='this.form.submit()'") !!}
            </div>
            <div class="form-group col-sm-3">
                <label class="form-label mb-1 fw-bold" style="visibility: hidden">ff</label>
                <input type="text" id="search" name="search" placeholder="សូមវាយឈ្មោះ កម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control"  />
            </div>

            <div class="form-group col-sm-1">
                <label class="form-label mb-1 fw-bold" style="visibility: hidden">ff</label>
                <button type="submit" class="btn btn-success form-control fw-bold">ស្វែងរក</button>
            </div>
        @else
            <div class="form-group col-2">
                <label class="form-label mb-1 fw-bold">ក្នុងឫក្រៅដែនការិយាល័យ</label>
                {!! showSelect('inOutDomain',$arrInOrOutDomain, old('inOutDomain', request('inOutDomain')), "", "onchange='this.form.submit()'") !!}
            </div>
            <div class="form-group col-2">
                <label class="form-label mb-1 fw-bold">ស្ថានភាពបណ្តឹង</label>
                {!! showSelect('statusID',$arrCaseStatus, old('statusID', request('statusID')), "", "onchange='this.form.submit()'") !!}
            </div>
            <div class="form-group col-3">
                <label class="form-label mb-1 fw-bold">ដំណើរការបណ្តឹង</label>
                {!! showSelect('stepID',$arrCaseStep, old('stepID', request('stepID')), "", "onchange='this.form.submit()'") !!}
            </div>
            <div class="form-group col-sm-4">
                <label class="form-label mb-1 fw-bold" style="visibility: hidden">ff</label>
                <input type="text" id="search" name="search" placeholder="សូមវាយឈ្មោះ កម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control"  />
            </div>

            <div class="form-group col-sm-1">
                <label class="form-label mb-1 fw-bold" style="visibility: hidden">ff</label>
                <button type="submit" class="btn btn-success form-control fw-bold">ស្វែងរក</button>
            </div>
        @endif


    </div>
</form>








