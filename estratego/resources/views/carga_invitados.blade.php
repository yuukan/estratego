<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
<!-- Your html goes here -->
@section('content')
<div class='panel panel-default'>
    <form method='post' action='{{CRUDBooster::mainpath('add-save')}}' enctype="multipart/form-data">
        <div class='panel-body'>
            <div class="col-sm-4">
                <label for="event">
                    Seleccione un evento
                </label>
                <select name="event" id="event" class="form-control" required>
                    <option value="">Seleccione un evento</option>
                    <?php if(!empty($events))
                    foreach($events as $e):?>
                        <option value="<?=$e->id;?>"><?=$e->nombre;?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-sm-12" style="margin-top: 15px;">
                <label for="fileToUpload">
                    Seleccione el archivo a subir
                </label>
                <div class='form-group'>
                    <input type="file" name="fileToUpload" id="fileToUpload" required>
                    @csrf
                </div>
            </div>
        </div>
        <div class='panel-footer'>
            <input type='submit' class='btn btn-primary' value='Cargar Archivo'/>
        </div>
    </form>
</div>
@endsection