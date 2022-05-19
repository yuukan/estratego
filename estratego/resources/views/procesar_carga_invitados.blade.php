<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
<!-- Your html goes here -->
@section('content')
<?php 
$req = Request::all();
// $event = $req['event'];
$file = $req['fileToUpload'];
$path = public_path() . '/uploads/';
$file->move($path, $file->getClientOriginalName() );
$path = $path.$file->getClientOriginalName();

function clean_txt($r){
    return preg_replace('/[\x00-\x1F\x7F-\xFF]/', '',$r);
}

Excel::load($path, function($reader) {
    ?>
<div class='panel panel-default'>
    <div class="panel-body">
        <div class="box-body">
    <?php
        $req = Request::all();
        $event = $req['event'];

        $results = $reader->all();
        $results = json_decode($results);


        // $results = array_map("utf8_encode", $results );
        // echo $results[0]->titulo;


    // $handle = fopen($path, "r");
    // $header = true;
    // $headerContent = null;

    // $ca = [];

    // $repetidos = [];
    // $nuevos = [];

    // while ($csvLine = fgetcsv($handle, 1000, ",")):
    if(!empty($results))
    foreach($results as $csvLine):
    //     if ($header):
    //         $header = false;
    //         $headerContent = $csvLine;
    //     else:
            // $sql = "SELECT 	*
            //         FROM 	cs_invitados_master
            //         where   codigo='".$csvLine->codigo."'";
            $sql = "SELECT 	*
                    FROM 	cs_invitados_master
                    where   email='".$csvLine->email."'";
            $email = DB::select($sql);


            if(!empty($email)):
                $email = $email[0];

                $repetidos[] = [$email->codigo,$email->email];

                $dataArray = [
                    "cs_invitados_master_id" => $email->id,
                    "cs_evento_id" => $event
                ];

                DB::table('cs_invitado_evento')->where($dataArray)->delete();

                DB::table('cs_invitado_evento')->insert($dataArray);
            else:
                $dataArray = [];

                $nuevos[] = [$csvLine->codigo,$csvLine->email];

                $dataArray["codigo"] = $csvLine->codigo;

                // We get the invitado status
                $sql = "SELECT 	id
                        FROM 	cs_status_invitado
                        where   LOWER(status)='".trim(strtolower($csvLine->status_del_contacto))."'";
                $data = DB::select($sql);
                if(!empty($data)) $dataArray['cs_status_invitado_id'] = $data[0]->id; 
                else  $dataArray['cs_status_invitado_id'] = 0; 

                // We get the tipo invitado
                $sql = "SELECT 	id
                        FROM 	cs_tipo_invitado
                        where   LOWER(tipo)='".trim(strtolower($csvLine->tipo_de_asistente))."'";
                $data = DB::select($sql);
                if(!empty($data)):
                    $dataArray['cs_tipo_invitado_id'] = $data[0]->id; 
                else:  
                    $info = array(
                        'tipo' => trim(addslashes($csvLine->tipo_de_asistente))
                    );

                    $eid = DB::table('cs_tipo_invitado')->insertGetId($info);

                    $dataArray['cs_tipo_invitado_id'] = $eid; 
                endif;

                // Fuente
                $dataArray["fuente"] = $csvLine->fuente;

                // Ejecutivo que confirmó es #4

                // Tipo Empresa
                $sql = "SELECT 	id
                        FROM 	cs_tipo_empresa
                        where   LOWER(tipo)='".trim(strtolower($csvLine->tipo_de_empresa))."'";
                $data = DB::select($sql);
                if(!empty($data)) $dataArray['cs_tipo_empresa_id'] = $data[0]->id; 
                else  $dataArray['cs_tipo_empresa_id'] = 0; 

                // Código Empresarial
                $dataArray["codigo_empresarial"] = $csvLine->codigo_empresarial;

                // Código Contacto
                $dataArray["codigo_contacto"] = $csvLine->codigo_contacto;

                // Empresa
                $sql = "SELECT 	id
                        FROM 	cs_empresa
                        where   LOWER(nombre)='".trim(strtolower(addslashes($csvLine->empresa)))."'";
                $data = DB::select($sql);
                if(!empty($data)):
                    $dataArray['cs_empresa_id'] = $data[0]->id; 
                else :
                    $info = array(
                        'nombre' => trim(addslashes($csvLine->empresa))
                    );

                    $eid = DB::table('cs_empresa')->insertGetId($info);

                    $dataArray['cs_empresa_id'] = $eid; 
                endif;

                // Título
                $dataArray["titulo"] = $csvLine->titulo;

                // nombre
                $dataArray["nombre"] = $csvLine->nombre_y_apellido;

                // apellido
                $dataArray["apellido"] = $csvLine->apellido;

                // Puesto
                $sql = "SELECT 	id
                        FROM 	cs_puesto
                        where   LOWER(puesto)='".trim(strtolower($csvLine->puesto_especifico))."'";
                $data = DB::select($sql);
                if(!empty($data)):
                    $dataArray['cs_puesto_id'] = $data[0]->id; 
                else :
                    $info = array(
                        'puesto' => trim(addslashes($csvLine->puesto_especifico))
                    );

                    $pid = DB::table('cs_puesto')->insertGetId($info);

                    $dataArray['cs_puesto_id'] = $pid; 
                endif;

                // Cargo Genérico
                $sql = "SELECT 	id
                        FROM 	cs_cargo_generico
                        where   LOWER(cargo)='".trim(strtolower($csvLine->function_o_cargo_generico_validar))."'";
                $data = DB::select($sql);
                if(!empty($data)) $dataArray['cs_cargo_generico_id'] = $data[0]->id; 
                else  $dataArray['cs_cargo_generico_id'] = 0; 

                // Departamento Genérico
                $sql = "SELECT 	id
                        FROM 	cs_departamento_generico
                        where   LOWER(departamento)='".trim(strtolower($csvLine->departamento_generico_validar))."'";
                $data = DB::select($sql);
                if(!empty($data)) $dataArray['cs_departamento_generico_id'] = $data[0]->id; 
                else  $dataArray['cs_departamento_generico_id'] = 0; 

                // email
                $dataArray["email"] = $csvLine->email;

                // email2
                $dataArray["email2"] = $csvLine->email_2;

                // telefono
                $dataArray["telefono"] = $csvLine->telefono;

                // telefono 2
                $dataArray["telefono2"] = $csvLine->telefono2;

                // celular
                $dataArray["celular"] = clean_txt($csvLine->celular);

                // direccion
                $dataArray["direccion"] = $csvLine->direccion;

                // direccion 2
                $dataArray["direccion2"] = $csvLine->direccion2;

                // pais
                $dataArray["cs_pais_id"] = 1;

                // Departamento Geográfico
                $sql = "SELECT 	id
                        FROM 	cs_departamento
                        where   LOWER(departamento)='".trim(strtolower($csvLine->departamento))."'";
                $data = DB::select($sql);
                if(!empty($data)) $dataArray['cs_departamento_id'] = $data[0]->id; 
                else  $dataArray['cs_departamento_id'] = 0; 

                // Departamento Ciudad
                $sql = "SELECT 	id
                        FROM 	cs_ciudad
                        where   LOWER(ciudad)='".trim(strtolower($csvLine->ciudad))."'";
                $data = DB::select($sql);
                if(!empty($data)) $dataArray['cs_ciudad_id'] = $data[0]->id; 
                else  $dataArray['cs_ciudad_id'] = 0; 

                // Zona
                $sql = "SELECT 	id
                        FROM 	cs_zona
                        where   LOWER(zona)='".trim(strtolower($csvLine->zona))."'
                        and     cs_ciudad_id=".$dataArray['cs_ciudad_id'];
                $data = DB::select($sql);
                if(!empty($data)) $dataArray['cs_zona_id'] = $data[0]->id; 
                else  $dataArray['cs_zona_id'] = 0; 

                // Web
                $dataArray["pagina_web"] = $csvLine->pag._web;

                // We get the segmento Invitado
                $sql = "SELECT 	id
                        FROM 	cs_segmento_cliente
                        where   LOWER(upper_gb)='".trim(strtolower($csvLine->segmento))."'";
                $data = DB::select($sql);
                if(!empty($data)):
                    $dataArray['cs_segmento_cliente_id'] = $data[0]->id; 
                else:  
                    $info = array(
                        'upper_gb' => trim(addslashes($csvLine->segmento))
                    );

                    $eid = DB::table('cs_segmento_cliente')->insertGetId($info);

                    $dataArray['cs_segmento_cliente_id'] = $eid; 
                endif;
                

                $id = DB::table('cs_invitados_master')->insertGetId($dataArray);
                // echo $id."<br>";

                // var_dump($dataArray);
            endif;
    //     endif;
    // endwhile;
    endforeach;
    ?>  
            <?php if(!empty($repetidos)):?>
                <div class='col-sm-12'>
                    <h2>Estos invitados ya existían en la base de datos general</h2>
                    <ul>
                        <?php foreach($repetidos as $r):?>
                            <li>
                                <?=$r[1];?> (<?=$r[0];?>)
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            <?php endif;?>
            <?php if(!empty($nuevos)):?>
                <div class='col-sm-12'>
                    <h2>Estos invitados nuevos</h2>
                    <ul>
                        <?php foreach($nuevos as $r):?>
                            <li>
                                <?=$r[1];?> (<?=$r[0];?>)
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>
<?php 
}, 'UTF-8');
?>
@endsection