<!DOCTYPE html>

<html>

<head>

<style>
body{
    font-family: sans-serif;
}
img{
    width: 100%;
}
</style> 
</head>
<body>

 <img src="https://www.idrd.gov.co/sitio/idrd/sites/default/files/Cabezote%20limpio.jpg">

      <p style="font-family: times, serif; font-size:15pt; font-style:italic" align="center">{{$formulario->primer_nombre}}&nbsp;{{$formulario->segundo_nombre}}&nbsp;{{$formulario->primer_apellido}}&nbsp;{{$formulario->segundo_apellido}} </p>
      <p align="center"><strong>BIENVENIDO, YA HACES PARTE DE LA EXPEDICIÓN A SUMAPAZ 2017</strong></p><br>
      <p align="justify">Este documento será tu comprobante de inscripción, el cual deberás entregar impreso o descargarlo en tu celular para verificar los datos el día del evento.</p>
      <p>Fecha: Domingo 1 de octubre de 2017</p>
      <p>Hora: De 5:00 am a 8:00 pm</p>
      <p>Lugar: Salida IDRD al paramo de Sumapaz</p>

      <p align="center"><strong >DETALLES DE TU INSCRIPCIÓN</strong></p>
  
     <strong>Nombre  Completo:</strong> {{$formulario->primer_nombre}}&nbsp;{{$formulario->segundo_nombre}}&nbsp;{{$formulario->primer_apellido}}&nbsp;{{$formulario->segundo_apellido}}
     <br>      
     <strong>Documento:</strong> {{$formulario->cedula}}
     <br>
     <strong>Fecha  de Registro:</strong> {{$formulario->created_at}}
     <br>  
     <strong>Número  de Inscripción:</strong> <font  color="#00ade6">{{$formulario->id}}</font>
     <br><br>
      <p ><br>
        Cualquier inquietud acércate a las Instalaciones del IDRD Calle 63 No 59a-06 Área de Recreación, o llama al teléfono 6605400 Ext. 2007 y 3002.
      </p>
    </body>

</html>
