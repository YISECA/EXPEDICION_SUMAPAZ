<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB as DB;
use Redirect;
use Validator;
use Session;
use App\Form;
use Idrd\Usuarios\Repo\Departamento;
use Idrd\Usuarios\Repo\Pais;
use Idrd\Usuarios\Repo\Ciudad;
use Idrd\Usuarios\Repo\Localidad;
use Idrd\Usuarios\Repo\Acceso;
use Mail;

class FormController extends BaseController


{
    var $url;
    private function cifrar($M)
    {   
      $C="";
      $k = 18; 
      for($i=0; $i<strlen($M); $i++)$C.=chr((ord($M[$i])+$k)%255);
      return $C;
    }

    private function decifrar($C)
    {   
      $M="";
      $k = 18;
      for($i=0; $i<strlen($C); $i++)$M.=chr((ord($C[$i])-$k+255)%255);
      return $M;
    }

    public function listar_datos()
    {
    $acceso = Form::whereYear('created_at', '=', date('Y'))->get(); 

    $tabla='<strong><table id="lista" style="color: #FFFFFF">

        <thead>
           <tr>
             <th style="text-transform: capitalize;">Código de Inscripción</th>
             <th style="text-transform: capitalize;">cedula</th>
             <th style="text-transform: capitalize;">tipo_documento</th>
             <th style="text-transform: capitalize;">primer_nombre</th>
             <th style="text-transform: capitalize;">segundo_nombre</th>
             <th style="text-transform: capitalize;">primer_apellido</th>
             <th style="text-transform: capitalize;">segundo_apellido</th>
             <th style="text-transform: capitalize;">genero</th>
             <th style="text-transform: capitalize;">fecha_nacimiento</th>
             <th style="text-transform: capitalize;">mail</th>
             <th style="text-transform: capitalize;">celular</th>
             <th style="text-transform: capitalize;">eps</th>
             <th style="text-transform: capitalize;">Talla</th> 
             <th style="text-transform: capitalize;">Barrio</th>   
             <th style="text-transform: capitalize;">Tipo de sangre</th>   
             <th style="text-transform: capitalize;">Nombre contacto emergencia</th>   
             <th style="text-transform: capitalize;">número de contacto de emergencia</th> 
             <th style="text-transform: capitalize;">Fecha de Inscripción</th>             
            </tr>
        </thead>
        <tbody id="tabla"></strong>';

      foreach ($acceso as $key => $value) 
      {

       $tabla.='<tr style="color: #000000"><td>'.$value->id.'</td>';
       $tabla.='<td>'.$value->cedula.'</td>';
       $tabla.='<td>'.$value->tipo_documento.'</td>';
       $tabla.='<td>'.$value->primer_nombre.'</td>';
       $tabla.='<td>'.$value->segundo_nombre.'</td>';
       $tabla.='<td>'.$value->primer_apellido.'</td>';
       $tabla.='<td>'.$value->segundo_apellido.'</td>';
       $tabla.='<td>'.$value->genero.'</td>';
       $tabla.='<td>'.$value->fecha_nacimiento.'</td>';
       $tabla.='<td>'.$value->mail.'</td>';
       $tabla.='<td>'.$value->celular.'</td>';
       $tabla.='<td>'.$value->eps.'</td>';
       $tabla.='<td>'.$value->talla.'</td>';
       $tabla.='<td>'.$value->barrio.'</td>';
       $tabla.='<td>'.$value->tipo_sangre.'</td>';
       $tabla.='<td>'.$value->nombre_contacto.'</td>';
       $tabla.='<td>'.$value->numero_contacto.'</td>';
       $tabla.='<td>'.$value->created_at.'</td></tr>';

      }

      $tabla.='</tbody></table>';
      echo $tabla;
    }

      public function logear(Request $request)

      {

      $usuario = $request->input('usuario');
      $pass = $request->input('pass');
      $acceso = Acceso::where('Usuario',$usuario)->where('Contrasena', sha1($this->cifrar($pass)) )->first();
      if (empty($usuario)) { return view('error',['error' => 'Usuario o contraseña invalida!'] ); exit(); }
      if (empty($acceso)) { return view('error',['error' => 'Usuario o contraseña invalida!'] ); exit(); }

      session_start() ;
      
      $_SESSION['id_usuario'] = json_encode($acceso);
      return view('admin'); exit(); 
      }

    public function insertar(Request $request)

    {

     $post = $request->input();
     $usuario = Form::where('cedula', $request->input('cedula'))->first(); 
     if (!empty($usuario)) { return view('error',['error' => 'Este usuario ya fue registrado!'] ); exit(); 
    }
     $formulario = new Form([]);

      //envio de correo

     if($this->inscritos()<=1100)

     {

        $formulario = $this->store($formulario, $request);

        //$this->store($formulario, $request->input());
        
        Mail::send('email', ['user' => $request->input('mail'),'formulario' => $formulario], function ($m) use ($request) 
        {
            $m->from('no-reply@idrd.gov.co', 'Registro Exitoso a la Ecotravesía Cerros Orientales');
            $m->to($request->input('mail'), $request->input('primer_nombre'))->subject('Registro Exitoso a la Ecotravesía cerros orientales!');
        });

      }else{
        return view('error', ['error' => 'Lo sentimos el limite de inscritos fue superado!']);
      }
        return view('error', ['error' =>'  BIENVENIDO, YA HACES PARTE DE LA ECOTRAVESÍA CERROS ORIENTALES 2017, verifica los datos registrados en tu correo electrónico o descarga tu comprobante de inscripción en el menú "Descargar inscripción" que se encuentra en la parte superior.']);
    }

 // conteo de la tabla

    private function inscritos()
    {

      $cant = Form::count('id');
      return $cant+1;
    }

    private function store($formulario, $input)

    {
        $formulario['cedula'] = $input['cedula'];
        $formulario['tipo_documento'] = $input['tipo_documento'];
        $formulario['primer_nombre'] = $input['primer_nombre'];
        $formulario['segundo_nombre'] = $input['segundo_nombre'];
        $formulario['primer_apellido'] = $input['primer_apellido'];
        $formulario['segundo_apellido'] = $input['segundo_apellido'];
        $formulario['genero'] = $input['genero'];
        $formulario['fecha_nacimiento'] = $input['fecha_nacimiento'];
        $formulario['mail'] = $input['mail'];
        $formulario['celular'] = $input['celular'];
        $formulario['eps'] = $input['eps'];
        $formulario['talla'] = $input['talla'];
        $formulario['barrio'] = $input['barrio'];
        $formulario['tipo_sangre'] = $input['tipo_sangre'];
        $formulario['nombre_contacto'] = $input['nombre_contacto'];
        $formulario['numero_contacto'] = $input['numero_contacto'];
        $formulario->save();
        return $formulario;        
    }

}

