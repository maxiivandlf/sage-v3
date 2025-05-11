<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POF</title>
    <style>
        td{
            font-size: 12px;
        }
    </style>
</head>
<body>
    <section id="container" >
        <section id="main-content">
            <section class="content-wrapper">
                <!-- Inicio Selectores -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Inicio Tabla-Card -->
                        <div class="card card-lightblue">
                            <div class="card-header" style="display: flex; align-items: center;">
                                <div style="flex: 1;">
                                    <img src="http://sage.larioja.edu.ar/img/logo_gob_lr.jpg" alt="SAGE" class="brand-image img-circle elevation-3" style="opacity: .8;width:50px">
                                </div>
                                <div style="text-aling:center">
                                    <h3 class="card-title">PLANTA ORGANICO FUNCIONAL</h3>
                                </div>
                            </div>
                            
                            @php
                                $datosInst=DB::table('tb_institucion_extension')
                                ->where('CUECOMPLETO',session('CUECOMPLETO'))
                                ->where('idTurnoUsuario',session('idTurnoUsuario'))
                                ->first();

                               //$datos['infoNodos'][0]->CUECOMPLETO;
                            @endphp
                            
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example" class="table table-bordered table-striped" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center" colspan="3">Institución: <b>{{session('Nombre_Institucion')}}</b></th>
                                            <th style="text-align:center" colspan="3">CUE: <b>{{session('CUECOMPLETO')}}</b></th>
                                            <th style="text-align:center" colspan="3">Fecha de Entrega: <b>{{ $datos['FechaActual'] }}</b></th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center" colspan="3">Domicilio: <b>{{$datosInst->Domicilio_Institucion}}</b></th>
                                            <th style="text-align:center" colspan="3">Email: <b>{{$datosInst->CorreoElectronico}}</b></th>
                                            <th style="text-align:center" colspan="3">Teléfono: <b>{{$datosInst->Telefono}}</b></th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center" colspan="3">Zona: <b>{{$datosInst->Zona}}</b></th>
                                            <th style="text-align:center" colspan="3">Localidad: <b>{{$datosInst->Localidad}}</b></th>
                                            <th style="text-align:center" colspan="3">Departamento: <b>{{$datosInst->Departamento}}</b></th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center">Apellido y Nombre</th>
                                            <th style="text-align:center">CUIL</th>
                                            <th style="text-align:center">Cargo</th>
                                            <th style="text-align:center">C&oacute;digo</th>
                                            <th style="text-align:center">Secci&oacute;n</th>
                                            <th style="text-align:center">Turno</th>
                                            <th style="text-align:center">Domicilio</th>
                                            <th style="text-align:center">Origen del Cargo</th>
                                            <th style="text-align:center">Asistencias</th>
                                            <th style="text-align:center">Observaciones</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php
                                        //obtengo lista de agentes que trabaja en dicho cue
                                        $ListaAgentes = DB::table('tb_agentes')
                                        ->join('tb_nodos','tb_nodos.Agente','=','tb_agentes.Documento')
                                        ->where('tb_nodos.CUECOMPLETO',session('CUECOMPLETO'))
                                        ->where('tb_nodos.idTurnoUsuario',session('idTurnoUsuario'))
                                        ->select('tb_agentes.*','tb_nodos.*')
                                        ->distinct()    //solo traigo 1 de cada 1
                                        ->get();
                                       
                                        //traigo solo las bases para llenar la primera parte
                                           /* $personas = DB::table('tb_nodos')
                                            ->where('CUECOMPLETO',session('CUECOMPLETO'))       //por cue
                                            ->where('idTurnoUsuario',session('idTurnoUsuario')) //por turno
                                            ->where('PosicionAnterior',null)            //son base a root
                                            ->get();*/

                                            foreach ($ListaAgentes as $persona) {
                                                //consulto a agentes para traer a los datos del agente
                                                $desglose = DB::table('tb_agentes')
                                                ->where('Documento',$persona->Agente)   //su documento
                                                ->first();
                                                if($desglose->Calle !="" )
                                                    $domicilio = $desglose->Calle." N&deg;".$desglose->Numero_Casa;
                                                else {
                                                    $domicilio = "Sin Asignar";
                                                }
                                                //consulto el cargo
                                                $cargo = DB::table('tb_cargossalariales')
                                                ->where('idCargo',$persona->CargoSalarial)
                                                ->first();
                                                
                                                //consulto la division
                                                //consulto el cargo
                                                $Divisiones = DB::table('tb_divisiones')
                                                ->where('tb_divisiones.idDivision',$persona->Division)
                                                ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
                                                ->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
                                                ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
                                                ->select(
                                                    'tb_divisiones.*',
                                                    'tb_divisiones.Descripcion as DescripcionDivi',
                                                    'tb_cursos.*',
                                                    'tb_division.*',
                                                    'tb_turnos.Descripcion as DescripcionTurno',
                                                    'tb_turnos.idTurno',
                                                )
                                                ->orderBy('tb_cursos.idCurso','ASC')
                                                ->first();
                                                
                                                
                                                echo '
                                                <tr>
                                                    <td>'.$desglose->ApeNom.'</td>
                                                    
                                                     <td style="text-align: center">'.$desglose->Cuil.'</td> 
                                                     <td style="text-align: center">'.$cargo->Cargo.'</td>  
                                                     <td style="text-align: center"><b>'.$cargo->Codigo.'</b></td>  
                                                     <td style="text-align: center">'.$Divisiones->DescripcionDivi.'</td>  
                                                     <td style="text-align: center">'.$Divisiones->DescripcionTurno.'</td>  
                                                     <td style="text-align: center">'.$domicilio.'</td>  
                                                     <td style="text-align: center">FALTA</td>  
                                                     <td style="text-align: center">'.$persona->CantidadAsistencia.'</td>
                                                     <td style="text-align: center">'.$persona->Observaciones.'</td>
                                                </tr>
                                                ';
                                            }
                                            
                                        @endphp
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
    
                   
                </div>
                
            </section>
        </section>
    </section>
</body>
</html>
