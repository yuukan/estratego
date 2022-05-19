<?php

use Illuminate\Http\Request;
use App\Mail\SendMailable;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Login route
Route::post('/login', function (Request $request) {
	$email = $request->email;
	$password = $request->password;

	$exist = DB::select('select * from cms_users where email = ?', [$email]);

	//Si existe el usuario
	if($exist):
		$secret = "";
		// if(!empty($exist[0]->password)):
		// 	// echo $exist[0]->password;
		// 	$secret = Crypt::decryptString($exist[0]->password);
		// endif;
		//If exists and the paswords match
		// if($secret==$password):
		
		if(Hash::check($password, $exist[0]->password)):
			$dataArray = [
				'error' => 0,
		    	'id' => $exist[0]->id,
		    	'name' => $exist[0]->name
			];
		else:
			$dataArray = [
				'error' => 1,
		    	'message' => 'Usuario y/o correo inválido.'
			];
		endif;
	else:
		$dataArray = [
			'error' => 1,
			'message' => 'Usuario y/o correo inválido.'
		];
    endif;
	
	return $dataArray;
	
});

//Login Mobile route
Route::post('/login-mobile', function (Request $request) {
	$email = $request->email;
	$password = $request->password;

	$exist = DB::select('select * from cs_invitados_master where email = ?', [$email]);

	//Si existe el usuario
	if($exist):
		$secret = "";
		// if(!empty($exist[0]->password)):
		// 	// echo $exist[0]->password;
		// 	$secret = Crypt::decryptString($exist[0]->password);
		// endif;
		//If exists and the paswords match
		// if($secret==$password):
		
		if(Hash::check($password, $exist[0]->password)):
			$dataArray = [
				'error' => 0,
		    	'id' => $exist[0]->id,
		    	'name' => $exist[0]->nombre." ".$exist[0]->apellido
			];
		else:
			$dataArray = [
				'error' => 1,
		    	'message' => 'Usuario y/o correo inválido.'
			];
		endif;
	else:
		$dataArray = [
			'error' => 1,
			'message' => 'Usuario y/o correo inválido.'
		];
    endif;
	
	return $dataArray;
	
});

//Get Events
Route::post('/get-events', function (Request $request) {
	$events = DB::select('select * from cs_evento where fecha_inicio>=CURDATE()');
	return $events;
});

//Get Empresas
Route::post('/get-empresas', function (Request $request) {
	$empresas = DB::select('select id value,nombre label from cs_empresa group by id,nombre');
	return $empresas;
});

//Get Cargo Generico
Route::post('/get-cargo-generico', function (Request $request) {
	$cargo = DB::select('select id value,cargo label from cs_cargo_generico group by id,cargo');
	return $cargo;
});

//Get Puestos
Route::post('/get-puestos', function (Request $request) {
	$puesto = DB::select('select id value,puesto label from cs_puesto group by id,puesto');
	return $puesto;
});

//Get Segmentos
Route::post('/get-segmentos', function (Request $request) {
	$segmentos = DB::select('select id value,upper_gb label from cs_segmento_cliente group by id,upper_gb');
	return $segmentos;
});

//Get Guests filtering the ones that attend
Route::post('/get-guests', function (Request $request) {
	$event = $request->event;

	$sql = "select 	CONCAT(m.nombre,' ', IF(apellido is not null,apellido,''),':  ',m.email,' - ',telefono,' - ',IF(m.codigo is not null,m.codigo,''), '-',iF(e.nombre is not null,e.nombre,'')) label, 
					m.id `value`,
					m.nombre,
					apellido,
					m.email,
					telefono,
					codigo_empresarial,
					e.nombre empresa,
					cs_puesto_id,
					cs_cargo_generico_id,
					e.id empresa_id,
					IF(asistio,'Si','No') asistio,
					m.codigo,
					m.cs_segmento_cliente_id,
					b.id black
				from 	cs_invitado_evento i,
						cs_invitados_master m
				left join cs_empresa e on m.cs_empresa_id=e.id
				left join cs_evento_ban b on m.email=b.email and b.cs_evento_id=".$event."
				where 	i.cs_invitados_master_id = m.id
				and 	i.asistio is null
				and 	i.cs_evento_id=".$event;
	$guests = DB::select($sql);

	return $guests;

});
//Get All the Guests
Route::post('/get-guests-all', function (Request $request) {
	$event = $request->event;

	$sql = "select 	CONCAT(m.nombre,' ',apellido,':  ',email,' - ',telefono,' - ',codigo_empresarial, '-',iF(e.nombre,e.nombre,'')) label, 
					m.id `value`,
					m.nombre,
					apellido,
					email,
					telefono,
					codigo_empresarial,
					e.nombre empresa,
					cs_puesto_id,
					cs_cargo_generico_id,
					e.id empresa_id,
					IF(asistio,'Si','No') asistio
			from 	cs_invitado_evento i,
					cs_invitados_master m
			left join cs_empresa e on m.cs_empresa_id=e.id
			where 	i.cs_invitados_master_id = m.id
			and 	i.cs_evento_id=".$event;
	$guests = DB::select($sql);

	return $guests;

});
//Save attenance
Route::post('/save-attendance', function (Request $request) {
	$event = $request->event;
	$guest = $request->guest;

	$date = new DateTime("now", new DateTimeZone('America/Guatemala') );

	$dataArray = [
		'asistio' => $date->format('Y-m-d H:i:s')
	];

	DB::table('cs_invitado_evento')
				->where('cs_invitados_master_id', $guest)
				->where('cs_evento_id', $event)
				->update($dataArray);

	$dataArray = [
		'nombre' => $request->nombre,
		'apellido' => $request->apellido,
		'email' => $request->email,
		'telefono' => $request->telefono,
		'cs_empresa_id' => $request->empresa,
		'cs_puesto_id' => $request->puesto,
		'nombreO' => $request->nombreO,
		'apellidoO' => $request->apellidoO,
		'emailO' => $request->emailO,
		'telefonoO' => $request->telefonoO,
		'empresaO' => $request->empresaO,
		'puestoO' => $request->puestoO,
		'cs_segmento_cliente_id' => $request->segmento
	];

	DB::table('cs_invitados_master')
				->where('id', $guest)
				->update($dataArray);

	return $event;

});

//Save New attenance
Route::post('/save-new-attendance', function (Request $request) {
	$event = $request->event;
	$guest = $request->guest;

	$date = new DateTime("now", new DateTimeZone('America/Guatemala') );
	$now = $date->format('Y-m-d H:i:s');

	$dataArray = [
		'nombre' => $request->nombre,
		'apellido' => $request->apellido,
		'email' => $request->email,
		'telefono' => $request->telefono,
		'cs_empresa_id' => $request->empresa,
		'cs_puesto_id' => $request->puesto,
		'nombreO' => $request->nombreO,
		'apellidoO' => $request->apellidoO,
		'emailO' => $request->emailO,
		'telefonoO' => $request->telefonoO,
		'empresaO' => $request->empresaO,
		'puestoO' => $request->puestoO,
		'cs_status_invitado_id' => 1,
		'cs_tipo_invitado_id' => 1,
		'created_at' => $now
	];

	$guest = DB::table('cs_invitados_master')
				->insertGetId($dataArray);

	$dataArray = [
		'asistio' => $now,
		'cs_invitados_master_id' =>$guest,
		'cs_evento_id' => $event
	];

	DB::table('cs_invitado_evento')
				->insert($dataArray);

	return $event;

});

//Attendance Chart
Route::post('/get-attendance', function (Request $request) {
	$event = $request->event;

	$sql = "SELECT
				if(upper_gb is null,'NA',upper_gb) label,count(1) Asistentes
			FROM
				cs_invitado_evento i,
				cs_invitados_master m
			LEFT JOIN
				cs_segmento_cliente s
			ON
				m.cs_segmento_cliente_id = s.id
			WHERE
				i.cs_invitados_master_id = m.id AND i.cs_evento_id = ".$event."
				AND asistio IS NOT NULL
				and m.email not in (select email from cs_evento_ban where cs_evento_id = ".$event.")
			group by upper_gb";
	$rubros = DB::select($sql);

	$sql = "SELECT
				'Lista Negra' label,
				COUNT(1) Asistentes
			FROM
				cs_invitado_evento i,
				cs_evento_ban bl,
				cs_invitados_master m
			LEFT JOIN
				cs_segmento_cliente s
			ON
				m.cs_segmento_cliente_id = s.id
			WHERE
				i.cs_invitados_master_id = m.id AND i.cs_evento_id = ".$event." AND m.email = bl.email AND asistio IS NOT NULL and bl.cs_evento_id = ".$event."
			GROUP BY
				upper_gb";
	$bl = DB::select($sql);

	$rubros[] = $bl[0];

	$sql = "select 	count(1) q
			from 	cs_invitado_evento
			where 	cs_evento_id=".$event;
	$total = DB::select($sql);

	// $sql = "select 	count(1) q
	// 		from 	cs_invitado_evento
	// 		where 	cs_evento_id=".$event."
	// 		and asistio is not null";
	// $asistencia = DB::select($sql);

	// $array = [
	// 	'name'=> 'Asistencia', 
	// 	'total' => $total[0]->q,
	// 	'si'=> $asistencia[0]->q,
	// 	'no' => $total[0]->q - $asistencia[0]->q
	// ];
	$array = [
		$rubros,
		$total[0]
	];
	return $array;

});
//Attendance per hour
Route::post('/get-attendance-hour', function (Request $request) {
	$event = $request->event;

	$sql = "SELECT concat_ws('-',
						date_format(asistio - interval minute(asistio)%30 minute, '%H:%i'),
						date_format(asistio + interval 30-minute(asistio)%30 minute, '%H:%i')
					) as period,
					COUNT(1) cantidad
			FROM 	cs_invitado_evento
			where 	cs_evento_id=".$event."
			and 	asistio is not null
			GROUP 	BY period
			ORDER 	BY period ASC";
	$info = DB::select($sql);

	return $info;

});

//Get Meeting center
Route::post('/get-meeting-center', function (Request $request) {
	$user = $request->user;

	$sql = "SELECT 	mc.id,
					DATE_FORMAT(mc.fecha,'%d/%c/%Y') fecha,
					DATE_FORMAT(mc.fecha,'%l:%i %p') hora,
					mc.lugar, 
					e.nombre evento, 
					e.id evento_id 
			FROM 	cs_meeting_center mc, cs_evento e
			WHERE	mc.cs_evento_id=e.id
			and 	mc.cs_invitados_master_id=".$user;
	$mc = DB::select($sql);

	return $mc;

});

//Forgot route
Route::post('/forgot', function (Request $request) {
	$email = $request->email;

	$exist = DB::select('select * from cs_invitados_master where email = ?', [$email]);

	//Si existe el usuario
	if($exist):
		// $secret = Crypt::decrypt($exist[0]->password);
		$secret = str_random(8);
		$hashed_random_password = Hash::make($secret);

		$data = [
			'password' => $hashed_random_password
		];
		DB::table('cs_invitados_master')
				->where('id', $exist[0]->id)
				->update($data);

		$dataArray = [
			'error' => 0,
			'message' => 'Su nuevo password es '.$secret
		];
		// Mail::to($exist[0]->email)->send(new SendMailable($secret));
	else:
		$dataArray = [
			'error' => 1,
			'message' => 'Email incorrecto'
		];
    endif;
	
	return $dataArray;
	
});