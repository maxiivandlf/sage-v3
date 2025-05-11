<h3 style="display: block; background-color:rgb(145, 187, 190); padding: 5px;">
    Agentes de la Institución <b>{{ $instituciones->isNotEmpty() ? $instituciones[0]->Nombre_Institucion : 'No disponible' }}</b> - 
    Turno <b>{{ $instituciones->isNotEmpty() ? $instituciones[0]->Turno : 'No disponible' }}</b>
</h3>
<div class="table-responsive" style="width: 4000px">
    <table id="Agentes" class="table table-bordered table-striped" style="width: 4000px">
        <thead>
            <tr>
                <th>Orden</th>
                
                <th>DNI</th>
                <th>CUIL</th>
                <th>Apellido y Nombre</th>
                <th>SEXO</th>

                <th>POF Origen</th>
                <th>SitRev</th>
                <th>Horas</th>
                <th>Antigüedad</th>
                <th>Cargo Sal.</th>
                <th>Cod. Sal.</th>

                <th>Aula</th>
                <th>División</th>
                <th>Turno</th>
                <th>Esp.Cur</th>
                <th>Matricula</th>

                <th>F.AltaCargo</th>
                <th>F.Designado</th>
                <th>Condición</th>
                <th>Activo</th>
                <th>Motivo</th>
                <th>Obs.Condicion</th>
                <th>F.DesdeLic</th>
                <th>F.HastaLic</th>
                <th>Agente Supl.</th>
                <th>Asist.Total</th>
                <th>Justif..Total</th>
                <th>Injust.Total</th>
                <th>% de Asistencia</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agentes as $usuario)
                <tr>
                    <td>
                        @php
                            if($usuario->orden != null || $usuario->orden !=""){
                                echo $usuario->orden;
                            }else{
                                echo "S/D";
                            }
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Agente != null || $usuario->Agente !=""){
                                echo $usuario->Agente;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Cuil != null || $usuario->Cuil !=""){
                                echo $usuario->Cuil;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->ApeNom != null || $usuario->ApeNom !=""){
                                echo $usuario->ApeNom;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Sexo != null || $usuario->Sexo !=""){
                                echo $usuario->Sexo;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                         set_time_limit(0);
                         ini_set('memory_limit', '2028M');
                            $aula="";
                            if($usuario->Origen !=null || $usuario->Origen != ""){
                                //buscamos en la tabla origen para ver que selecciono
                                foreach ($infoOrigen as $i) {
                                    if($i->idOrigenCargo == $usuario->Origen){
                                       foreach ($infoCargosOrigen as $c) {
                                            if($c->idCargos_Pof_Origen == (int)$i->nombre_origen){
                                                echo $c->nombre_cargo_origen;
                                                break;
                                            }
                                       }
                                       break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->SitRev){
                                foreach ($infoSitRev as $i) {
                                    if($i->idSituacionRevista == $usuario->SitRev){
                                        echo (!empty($i->Descripcion)) ? $i->Descripcion : "S/D";
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Horas != null || $usuario->Horas !=""){
                                echo $usuario->Horas;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Antiguedad!= null || $usuario->Sexo !=""){
                                echo $usuario->Sexo;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Cargo){
                                foreach ($infoCargoSalarial as $i) {
                                    if($i->idCargo == $usuario->Cargo){
                                        echo $i->Cargo;
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Cargo){
                                foreach ($infoCargoSalarial as $i) {
                                    if($i->idCargo == $usuario->Cargo){
                                        echo $i->Codigo;
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Aula){
                                foreach ($infoAula as $i) {
                                    if($i->idAula == $usuario->Aula){
                                        echo (!empty($i->nombre_aula)) ? $i->nombre_aula : "S/D";
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Division){
                                foreach ($infoDivision as $i) {
                                    if($i->idDivision == $usuario->Division){
                                        echo (!empty($i->nombre_division)) ? $i->nombre_division : "S/D";
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Turno){
                                foreach ($infoTurno as $i) {
                                    if($i->idTurno == $usuario->Turno){
                                        echo (!empty($i->nombre_turno)) ? $i->nombre_turno : "S/D";
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                        
                            $aula="";
                            if($usuario->EspCur != null || $usuario->EspCur !=""){
                                echo $usuario->EspCur;
                            }else{
                                echo "S/D";
                            }
                                
                            
                        @endphp
                    </td>
                    <td>
                        @php
                        
                        
                            $aula="";
                            if($usuario->Matricula != null || $usuario->Matricula !=""){
                                echo $usuario->Matricula;
                            }else{
                                echo "S/D";
                            }
                                
                            
                        @endphp
                    </td>

                    <td>
                        @php
                        
                        
                            $aula="";
                            if($usuario->FechaAltaCargo != null || $usuario->FechaAltaCargo !=""){
                                echo $usuario->FechaAltaCargo;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                        
                            $aula="";
                            if($usuario->FechaDesignacion != null || $usuario->FechaDesignacion !=""){
                                echo $usuario->FechaDesignacion;
                            }else{
                                echo "S/D";
                            }
                              
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Condicion){
                                foreach ($infoCondicion as $i) {
                                    if($i->idCondicion == $usuario->Condicion){
                                        echo (!empty($i->Descripcion)) ? $i->Descripcion : "S/D";
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Activo){
                                foreach ($infoActivos as $i) {
                                    if($i->idActivo == $usuario->Activo){
                                        echo $i->nombre_activo;
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                            
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Motivo){
                                foreach ($infoMotivos as $i) {
                                    if($i->idMotivo == $usuario->Motivo){
                                        echo $i->Codigo."-".$i->Nombre_Licencia;
                                        break;
                                    }
                                }
                            }else{
                                echo "S/D";
                            }
                            
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->DatosPorCondicion != null || $usuario->DatosPorCondicion !=""){
                                echo $usuario->DatosPorCondicion;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->FechaDesde != null || $usuario->FechaDesde !=""){
                                echo $usuario->FechaDesde;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->FechaHasta != null || $usuario->FechaHasta !=""){
                                echo $usuario->FechaHasta;
                            }else{
                                echo "S/D";
                            }
                               
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->AgenteR != null || $usuario->AgenteR !=""){
                                echo $usuario->AgenteR;
                            }else{
                                echo "S/D";
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $asistencia=0;
                            if($usuario->Asistencia != null || $usuario->Asistencia !=""){
                                echo $usuario->Asistencia;
                                $asistencia = (int)$usuario->Asistencia;
                            }else{
                                echo "0";
                                $asistencia = 0;
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $justificada=0;
                            if($usuario->Justificada != null || $usuario->Justificada !=""){
                                echo $usuario->Justificada;
                                $justificada = (int)$usuario->justificada;
                            }else{
                                echo "0";
                                $justificada = 0;
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $injustificada=0;
                            if($usuario->Injustificada != null || $usuario->Injustificada !=""){
                                echo $usuario->Injustificada;
                                $injustificada = (int)$usuario->Injustificada;
                            }else{
                                echo "0";
                                $injustificada=0;
                            }
                                
                        @endphp
                    </td>
                    <td>
                        @php
                        
                            $totalAsistencias = $asistencia +  $justificada + $injustificada;
                            $porcentaje = ($totalAsistencias / 30) * 100;
                            $porcentaje = number_format($porcentaje, 2); // Formateamos el porcentaje a 2 decimales
                            
                           // $porcentaje =10;
                        @endphp
                    
                        <span style="
                            color: white;
                            padding: 5px;
                            border-radius: 5px;
                            background-color:
                                {{ $porcentaje >= 61 ? 'green' : ($porcentaje >= 41 ? 'orange' : 'red') }};
                        ">
                            {{ $porcentaje }}%
                        </span>
                    </td>
                    <td>
                        @php
                        
                            $aula="";
                            if($usuario->Observaciones != null || $usuario->Observaciones !=""){
                                echo $usuario->Observaciones;
                            }else{
                                echo "S/D";
                            }
                              
                        @endphp
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
