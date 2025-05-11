/*var botonAgregarBloque = document.getElementById('AgregarBloque');
botonAgregarBloque.addEventListener('click',function(){
    // var timebase= document.getElementById('timebase');
    // var articulo=document.createElement('article');
    // articulo.textContent="holaaaaaaa";
    //             document.timebase.appendChild(articulo);
    var contenedor = document.getElementById("timebase");
    var articuloNuevo=document.createElement('article');
    
    articuloNuevo.className="timeline-item";
    articuloNuevo.innerHTML=` <div class="timeline-desk">
    <div class="panel"  style="width:250px;border:1px solid red;">
        <div class="panel-body" >
            <span class="arrow"></span>
            <span class="timeline-icon red"></span>
            <span class="timeline-date">5to 5ta - TM</span>
            <h1 class="red">DOCENTE: Loyola Leo Martin <i class="fa fa-ellipsis-h" data-toggle="modal" href="#myModal"></i></h1>
            <p>Asignatura: Equipos Electromecanico</p>
            <p>Sit Rev: Interino (horas: 8)</p>
            <i class="fa fa-plus-circle" style="color: green; font-size:14px;"><span>Agregar Licencia</span></i>
        
            <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Modal Tittle</h4>
                            </div>
                            <div class="modal-body">

                                Body goes here...

                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                <button class="btn btn-success" type="button">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal -->
        </div>
    </div>
</div>`;
    //contenedor.appendChild(articuloNuevo);  //al ultimo
    contenedor.prepend(articuloNuevo);  //al inicio
    console.log("borrando");

});
*/

function cargarAgenteEnForm(documento){
    alert("hola "+documento);
}

  //Arbol - Servicio trae info agente con su DNI
  function getAgenteDNI() {
    /*$("#contenidoCargosSalariales").html("");
    $.ajax({
     type: "get",
     url: "/getCargosSalariales/" + $("#RegimenSalarial").val(),
     success: function (response) {
         document.getElementById('contenidoCargosSalariales').innerHTML=response.msg;
     }
 });*/
 alert("prueba");
 }
 
 function seleccionarAgenteDNI($idCargo){
     /*var DescripcionCargoSalarialDefault = document.getElementById('DescripcionCargoSalarialDefault');
     var nomCargosSalarialesModal = document.getElementById('nomCargosSalarialesModal'+$idCargo);
     DescripcionCargoSalarialDefault.value=nomCargosSalarialesModal.value;
     document.getElementById('idCargoSalarial').value=$idCargo;*/
     
 }
