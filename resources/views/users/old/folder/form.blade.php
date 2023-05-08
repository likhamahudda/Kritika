<form name="addFolder" id="addFolderForm" onsubmit="submitFolderForm(); return false;">
  <div class="form-group">
    <input type="hidden" name="form_type" id="form_type" value="create">
    <input type="hidden" name="fid" id="fid" value="{{$data->id ?? ''}}">
    <label for="folder_name">Folder Name:</label>
    <input type="text" class="form-control" id="folder_name" name="folder_name" value="{{$data->folder_name ?? ''}}" placeholder="Enter Folder Name">
  </div>
  <button type="submit" class="btn btn-default">Create</button>
</form>