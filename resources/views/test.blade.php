<form name="formCreateCase" action="{{ url('log34/upload/file') }}" method="POST" enctype="multipart/form-data">
@method('POST')
@csrf

Hello
    <input type="hidden" name="id" id="id" value="2" >
    <input type="file" id="file" name="file">
    <button type="submit" class="btn btn-success form-control">រក្សាទុក</button>
    <div id="error-message" style="color: red;"></div>
</form>
