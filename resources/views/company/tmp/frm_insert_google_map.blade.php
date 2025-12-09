<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        {{--        @include('layouts.test')--}}
    </x-slot>
    {{--{{ dd($adata['company']->company_id) }}--}}

    <?php
//$url= $team == 0? site_url('company/frm_insert_google_map/'.$company_id.'/1'): site_url('self/company_info/frm_insert_google_map/'.$company_id.'/1');
//echo form_open_multipart($url, frm_attr("google_map"));
    $row= $adata['company'];
    ?>
    <form id="google_map" action="{{ url('company/frm_insert_google_map/'.$row->company_id.'/1') }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <input type="hidden" name="id" value="{{ $row->company_id }}" />
        @if ( session()->has('message'))

        @endif
        <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="form-group col-xs-8">
                                <label><span class='label label-info'>Google Map Link
									<?php
                                    if($row->google_map_link != "")
                                        echo "<a href='".$row->google_map_link."'  target='_blank'> (Show Map)</a>";
                                    ?>
								</span>
                                    <a href="http://map.google.com" target="_blank">Open Google Map</a>
                                </label>
                                <input type="text" name="google_map_link" value="{{ old('google_map_link', $row->google_map_link) }}" class="form-control"  />
                                @error('google_map_link')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <input type="text" name="company_photo_current" value="{{ $row->company_photo }}" />
                            <?php
                            $str='<div class="col-xs-12">';
                            $str.='<label>-រូបភាពក្រុមហ៊ុន</label>';
                            if($row->company_photo != "" || $row->company_photo != NULL){
                                $str.= "File Name: <a href='".rurl("assets/images/".$row->company_photo)."' title='View File' target='_blank'>".$row->company_photo. "</a>";
                            }
                            $str.='<input type="file" name="company_photo" id="company_photo" />';
                            $str.='</div>';
                            echo $str;
                            ?>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <center><button type="submit" class="btn btn-success">រក្សាទុក</button></center>
                                <!--<div class="submit"><center><button type="submit">ADD</button></center></div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>

</x-admin.layout-main>

