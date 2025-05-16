<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AegisController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BandejaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LuiController;
use App\Http\Controllers\LupController;
use App\Http\Controllers\AgController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatBlogController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\PofHorizontalController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\RegistroMulticuenta;
use App\Http\Controllers\RegistroTituloController;
use App\Http\Controllers\Sage2_1Controller;
use App\Http\Controllers\SistemaController;
use App\Http\Controllers\Sistemas\Sage\ConsultasController;
use App\Http\Controllers\Sistemas\Sage\ControlIPEEscuelasController;
use App\Http\Controllers\SubirDocController;
use App\Http\Controllers\SuperiorController;
use App\Http\Controllers\LiquidacionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;


//mail
use Illuminate\Support\Facades\Mail;
use App\Mail\EjemploMail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
Route::get('/',[LoginController::class,'index']);
Route::get('/servicio',[ServicioGeneralController::class,'index'])->name('servicio');
Route::get('/servicio/ver/{id}',[ServicioGeneralController::class,'ver'])->name('ver');
Route::post('/servicio/guardar',[ServicioGeneralController::class,'guardar'])->name('guardar');
*/
//cambios nuevos
Route::get('/test', function () {
    return 'Prueba exitosa!';
});
//controla las rutas de errores
Route::fallback([Controller::class, 'show404']);
//Inicio y bandeja

Route::get('/correo', [PruebaController::class, 'index'])->name('Correo');
Route::post('/verDatos', [PruebaController::class, 'verDatos'])->name('verDatos');

//crear cuenta segun cue
Route::get('/nuevoPedirUsuarioAdminSage', [LoginController::class, 'pedirUsuario'])->name('pedirUsuario');
Route::post('/buscarCUE', [LoginController::class, 'buscarCUE'])->name('buscarCUE');
Route::get('/cargarInfoUsuario/{CUE}', [LoginController::class, 'cargarInfoUsuario'])->name('cargarInfoUsuario');


Route::get('/', [LoginController::class, 'index'])->name('Autenticar');
Route::post('/login', [LoginController::class, 'validar'])->name('login');
Route::get('/bandeja', [BandejaController::class, 'index'])->name('Bandeja');
Route::get('/dashboard-redirect', [DashboardController::class, 'redirect'])->name('dashboardRedirect');
/*Route::get('/edificio',[LuiController::class,'edificio'])->name('Edificio');*/

//LUI
Route::get('/getOrg', [LuiController::class, 'getOrg'])->name('getOrg');
Route::get('/getOpcionesOrg', [LuiController::class, 'getOpcionesOrg'])->name('getOpcionesOrg');
Route::get('/adjuntar_novedad/{idInstExt}', [LuiController::class, 'adjuntar_novedad'])->name('adjuntar_novedad');

Route::get('/verSubOrg', [LuiController::class, 'verSubOrg'])->name('verSubOrg');
Route::get('/Reestructura', [LuiController::class, 'Reestructura'])->name('Reestructura');
Route::get('/PlazaNueva/{idSubOrg}', [LuiController::class, 'PlazaNueva'])->name('PlazaNueva');
Route::get('/getCarrerasTodas/{nombre}', [LuiController::class, 'getCarrerasTodas'])->name('getCarrerasTodas');
Route::get('/getCarrerasPlanes', [LuiController::class, 'getCarrerasPlanes'])->name('getCarrerasPlanes');
Route::get('/getCarreras/{idSubOrg}', [LuiController::class, 'getCarreras'])->name('getCarreras');
Route::get('/getAsignatura/{nombre}', [LuiController::class, 'getAsignatura'])->name('getAsignatura');
Route::get('/getEspCurPlan/{idPlan}', [LuiController::class, 'getEspCurPlan'])->name('getEspCurPlan');
Route::get('/desvincularEspCur/{idEspCur}', [LupController::class, 'desvincularEspCur'])->name('desvincularEspCur');

Route::get('/getPlanes/{idSubOrg}', [LuiController::class, 'getPlanes'])->name('getPlanes');
Route::get('/verDivisiones', [LuiController::class, 'verDivisiones'])->name('verDivisiones');
Route::get('/getDivision/{idSubOrg}/{idPlanEstudio}', [LuiController::class, 'getDivision'])->name('getDivision');
Route::get('/editarDivision/{idDivision}', [LuiController::class, 'editarDivision'])->name('editarDivision');

Route::post('/formularioActualizarDivisiones', [LuiController::class, 'formularioActualizarDivisiones'])->name('formularioActualizarDivisiones');

Route::get('/getEspacioCurricular/{idNodo}', [LuiController::class, 'getEspacioCurricular'])->name('getEspacioCurricular');
Route::get('/getEspacioCurricularWeb/{idPlanEstudio}', [LuiController::class, 'getEspacioCurricularWeb'])->name('getEspacioCurricularWeb');
Route::post('/formularioInsertarEspCur', [LupController::class, 'formularioInsertarEspCur'])->name('formularioInsertarEspCur');
Route::get('/borrarRelNodoEspCur/{idEspCur}/{idNodo}', [LupController::class, 'borrarRelNodoEspCur'])->name('borrarRelNodoEspCur');


Route::post('/formularioEdificio', [LupController::class, 'formularioEdificio'])->name('formularioEdificio');
Route::post('/formularioNiveles', [LupController::class, 'formularioNiveles'])->name('formularioNiveles');
Route::post('/formularioTurnos', [LupController::class, 'formularioTurnos'])->name('formularioTurnos');
Route::post('/formularioInstitucion', [LupController::class, 'formularioInstitucion'])->name('formularioInstitucion');
Route::post('/formularioCarreras', [LupController::class, 'formularioCarreras'])->name('formularioCarreras');
Route::get('/desvincularCarrera/{idCarreraSubOrg}', [LupController::class, 'desvincularCarrera'])->name('desvincularCarrera');
Route::post('/formularioPlanes', [LupController::class, 'formularioPlanes'])->name('formularioPlanes');
Route::get('/desvincularPlan/{idPlanSubOrg}', [LupController::class, 'desvincularPlan'])->name('desvincularPlan');
Route::post('/formularioDivisiones', [LupController::class, 'formularioDivisiones'])->name('formularioDivisiones');
Route::get('/desvincularDivision/{idDivision}', [LupController::class, 'desvincularDivision'])->name('desvincularDivision');
Route::get('/verAsigEspCur', [LuiController::class, 'verAsigEspCur'])->name('verAsigEspCur');
Route::post('/formularioAsignaturas', [LupController::class, 'formularioAsignaturas'])->name('formularioAsignaturas');
Route::post('/formularioEspCur', [LupController::class, 'formularioEspCur'])->name('formularioEspCur');
Route::post('/formularioEspCurAct', [LupController::class, 'formularioEspCurAct'])->name('formularioEspCurAct');


Route::post('/formularioLogo', [LupController::class, 'formularioLogo'])->name('formularioLogo');
Route::post('/formularioImgEscuela', [LupController::class, 'formularioImgEscuela'])->name('formularioImgEscuela');
Route::get('/editarEspCur/{idEspCur}', [LuiController::class, 'editarEspCur'])->name('editarEspCur');

Route::get('/getCargosSalariales/{idRegimenSalarial}', [LuiController::class, 'getCargosSalariales'])->name('getCargosSalariales');
Route::post('/AltaPlaza', [LuiController::class, 'AltaPlaza'])->name('AltaPlaza');
//LUP
Route::get('/verArbol/{idSubOrg}', [LupController::class, 'verArbol'])->name('verArbol');
Route::get('/verAgentes/{idPlaza}', [LupController::class, 'verAgentes'])->name('verAgentes');
Route::get('/nuevoAgente', [LupController::class, 'nuevoAgente'])->name('nuevoAgente');
Route::post('/FormNuevoAgente', [LupController::class, 'FormNuevoAgente'])->name('FormNuevoAgente');
Route::get('/lista_de_agentes_inst', [LupController::class, 'lista_de_agentes_inst'])->name('lista_de_agentes_inst');
Route::get('/editarAgente/{idAgente}', [LupController::class, 'editarAgente'])->name('editarAgente');
Route::post('/FormActualizarAgente_ind', [LupController::class, 'FormActualizarAgente_ind'])->name('FormActualizarAgente_ind');
Route::get('/traerLocalidades/{idDepto}', [LupController::class, 'traerLocalidades'])->name('traerLocalidades');




//Servicio General
Route::post('/cambiarEstadoBorrado', [AgController::class, 'cambiarEstadoBorrado'])->name('cambiarEstadoBorrado');
Route::post('/cambiarEstadoEdicion', [AgController::class, 'cambiarEstadoEdicion'])->name('cambiarEstadoEdicion');

Route::get('/verArbolServicio', [AgController::class, 'verArbolServicio'])->name('verArbolServicio');
Route::get('/verArbolServicio2', [AgController::class, 'verArbolServicio2'])->name('verArbolServicio2');
Route::post('/activarFiltro', [AgController::class, 'activarFiltro'])->name('activarFiltro');

Route::get('/getAgentes/{DNI}', [AgController::class, 'getAgentes'])->name('getAgentes');
Route::get('/getBuscarAgente/{DNI}', [AgController::class, 'getBuscarAgente'])->name('getBuscarAgente');
Route::get('/getAgentesRel/{DNI}', [AgController::class, 'getAgentesRel'])->name('getAgentesRel');
Route::post('/agregarAgenteEscuela', [AgController::class, 'agregarAgenteEscuela'])->name('agregarAgenteEscuela');
Route::get('/getLocalidades/{localidad}', [AgController::class, 'getLocalidades'])->name('getLocalidades');
Route::get('/getLocalidadesInstitucion/{localidad}', [AgController::class, 'getLocalidadesInstitucion'])->name('getLocalidadesInstitucion');
Route::get('/getDepartamentos/{departamento}', [AgController::class, 'getDepartamentos'])->name('getDepartamentos');
Route::get('/agregaNodo/{nodo}', [AgController::class, 'agregaNodo'])->name('agregaNodo');
//Route::get('/agregaLic1/{nodo}',[AgController::class,'agregaLic'])->name('agregaLic');
Route::post('/agregaLic', [AgController::class, 'agregaLic'])->name('agregaLic');
Route::post('/ampliarLic', [AgController::class, 'ampliarLic'])->name('ampliarLic');

Route::get('/regresarNodo/{nodo}', [AgController::class, 'regresarNodo'])->name('regresarNodo');

Route::post('/agregarDatoANodo', [AgController::class, 'agregarDatoANodo'])->name('agregarDatoANodo');
Route::get('/getCargosFunciones/{nomCargoFuncionCodigo}', [AgController::class, 'getCargosFunciones'])->name('getCargosFunciones');
Route::get('/ActualizarNodoAgente/{idNodo}', [AgController::class, 'ActualizarNodoAgente'])->name('ActualizarNodoAgente');
Route::post('/formularioActualizarAgente', [AgController::class, 'formularioActualizarAgente'])->name('formularioActualizarAgente');
Route::post('/formularioActualizarHorario', [AgController::class, 'formularioActualizarHorario'])->name('formularioActualizarHorario');
Route::get('/getAgentesActualizar/{DNI}', [AgController::class, 'getAgentesActualizar'])->name('getAgentesActualizar');
Route::get('/desvincularDocente/{idNodo}', [AgController::class, 'desvincularDocente'])->name('desvincularDocente');
Route::get('/eliminarNodo/{idNodo}', [AgController::class, 'eliminarNodo'])->name('eliminarNodo');
Route::get('/getFiltrandoNodos/{idNodo}', [AgController::class, 'getFiltrandoNodos'])->name('getFiltrandoNodos');
Route::get('/retornarNodo/{idNodo}', [AgController::class, 'retornarNodo'])->name('retornarNodo');


Route::get('/ver_novedades/{valor}/{id?}', [AgController::class, 'ver_novedades'])->name('ver_novedades');
Route::get('/ver_novedades_altas', [AgController::class, 'ver_novedades_altas'])->name('ver_novedades_altas');
Route::get('/ver_novedades_licencias', [AgController::class, 'ver_novedades_licencias'])->name('ver_novedades_licencias');
Route::get('/ver_novedades_bajas', [AgController::class, 'ver_novedades_bajas'])->name('ver_novedades_bajas');
Route::get('/agregarNovedadParticular/{id?}', [AgController::class, 'agregarNovedadParticular'])->name('agregarNovedadParticular');
Route::post('/novedad_cambiar_estado_confirmar', [AgController::class, 'cambiarEstadoConfirmar']);
Route::post('/novedad_cambiar_estado_rechazar', [AgController::class, 'cambiarEstadoRechazar']);

Route::post('/editarNovedadParticular/{id?}', [AgController::class, 'editarNovedadParticular'])->name('editarNovedadParticular');
Route::get('/eliminarNovedadParticular/{id?}', [AgController::class, 'eliminarNovedadParticular'])->name('eliminarNovedadParticular');
// Ruta para obtener las condiciones según el tipo de novedad
Route::get('/condiciones/{idTipoNovedad}', [AgController::class, 'getCondiciones'])->name('get.condiciones');
Route::post('/novedad/pendiente/{id}', [AgController::class, 'marcarPendiente'])->name('novedad.pendiente');
Route::get('/consultaPruebaNovedad', [AgController::class, 'consultaPruebaNovedad'])->name('novedad.consultaPruebaNovedad');


Route::post('/buscar_agente', [AgController::class, 'buscar_agente'])->name('buscar_agente');
Route::post('/formularioNovedadParticular', [AgController::class, 'formularioNovedadParticular'])->name('formularioNovedadParticular');
Route::get('/verNovedadesParticulares', [AgController::class, 'verNovedadesParticulares'])->name('verNovedadesParticulares');
Route::post('/verNovedadesParticulares', [AgController::class, 'verNovedadesParticulares'])->name('verNovedadesParticulares');
Route::get('/ver_archivos', [AgController::class, 'ver_archivos'])->name('ver_archivos');

///Estadisticas
Route::get('/ver_novedades_cues', [EstadisticasController::class, 'ver_novedades_cues'])->name('ver_novedades_cues');
Route::get('/ver_info_por_Instituciones', [EstadisticasController::class, 'ver_info_por_Instituciones'])->name('ver_info_por_Instituciones');
Route::get('/ver_info_por_docentes', [EstadisticasController::class, 'ver_info_por_docentes'])->name('ver_info_por_docentes');
Route::get('/ver_info_por_Zonas', [EstadisticasController::class, 'ver_info_por_Zonas'])->name('ver_info_por_Zonas');
Route::get('/cargar_zona/{idZona}', [EstadisticasController::class, 'cargar_zona'])->name('cargar_zona');
Route::get('/traerPersonasIdInstExt', [EstadisticasController::class, 'traerPersonasIdInstExt'])->name('traerPersonasIdInstExt');
//liq
Route::get('/cargar_zona_liq/{idZona}', [EstadisticasController::class, 'cargar_zona_liq'])->name('cargar_zona_liq');




Route::get('/generar_pdf_novedades', [AgController::class, 'generar_pdf_novedades'])->name('generar_pdf_novedades');
Route::get('/limpiar_carga', [AgController::class, 'limpiar_carga'])->name('limpiar_carga');
//ADMIN
Route::get('/nuevoUsuario', [AdminController::class, 'nuevoUsuario'])->name('nuevoUsuario');
Route::get('/editarUsuario/{idUsuario}', [AdminController::class, 'editarUsuario'])->name('editarUsuario');
Route::get('/agregarCUEUsuario/{idUsuario}', [AdminController::class, 'agregarCUEUsuario'])->name('agregarCUEUsuario');
Route::post('/FormInsertarCUE', [AdminController::class, 'FormInsertarCUE'])->name('FormInsertarCUE');
Route::post('/FormActualizarUsuario', [AdminController::class, 'FormActualizarUsuario'])->name('FormActualizarUsuario');
Route::get('/escuelasCargadas', [AdminController::class, 'escuelasCargadas'])->name('escuelasCargadas');
Route::get('/asignarCUETecnico', [AdminController::class, 'asignarCUETecnico'])->name('asignarCUETecnico');
Route::post('/formAsignarTecnico', [AdminController::class, 'formAsignarTecnico'])->name('formAsignarTecnico');
Route::post('/FormQuitarAsignacion', [AdminController::class, 'FormQuitarAsignacion'])->name('FormQuitarAsignacion');
Route::get('/escuelasCargadasTecnico', [AdminController::class, 'escuelasCargadasTecnico'])->name('escuelasCargadasTecnico');
Route::post('/formActualizarEscTec', [AdminController::class, 'formActualizarEscTec'])->name('formActualizarEscTec');
Route::get('/escuelasCargadasIncompletasTec', [AdminController::class, 'escuelasCargadasIncompletasTec'])->name('escuelasCargadasIncompletasTec');
Route::get('/logs', [AdminController::class, 'logs'])->name('logs');



Route::post('/FormNuevoUsuario', [AdminController::class, 'FormNuevoUsuario'])->name('FormNuevoUsuario');
Route::post('/FormNuevoUsuario_CUE', [LoginController::class, 'FormNuevoUsuario_CUE'])->name('FormNuevoUsuario_CUE');

Route::post('/FormActualizarUsuario', [AdminController::class, 'FormActualizarUsuario'])->name('FormActualizarUsuario');

Route::get('/usuariosLista', [AdminController::class, 'usuariosLista'])->name('usuariosLista');
Route::get('/usuariosListaTec', [AdminController::class, 'usuariosListaTec'])->name('usuariosListaTec');
Route::get('/reiniciarCUE', [AdminController::class, 'reiniciarCUE'])->name('reiniciarCUE');
Route::get('/resetPof', [AdminController::class, 'resetPof'])->name('resetPof');

Route::get('/ver_lista_agentes', [AdminController::class, 'ver_lista_agentes'])->name('ver_lista_agentes');
Route::get('/salir', [BandejaController::class, 'salir'])->name('Salir')->withoutMiddleware('verificar.sesion');



//procesos solo de creacion o script
Route::get('/vincularSubOrgEdi', [SistemaController::class, 'vincularSubOrgEdi'])->name('vincularSubOrgEdi');
Route::post('/controlAsistencia', [LupController::class, 'controlAsistencia'])->name('controlAsistencia');
Route::get('/confirmarPOF', [LupController::class, 'confirmarPOF'])->name('confirmarPOF');
Route::get('/confirmarPofOriginal', [LupController::class, 'confirmarPofOriginal'])->name('confirmarPofOriginal');
Route::post('/generarPOF', [LupController::class, 'generarPOF'])->name('generarPOF');

Route::get('/buscar_dni_cue', [SistemaController::class, 'buscar_dni_cue'])->name('buscar_dni_cue');
Route::post('/buscar_dni_cue', [SistemaController::class, 'buscar_dni_cue'])->name('buscar_dni_cue');

//para liquidacion
Route::get('/buscar_dni_liq', [SistemaController::class, 'buscar_dni_liq'])->name('buscar_dni_liq');
Route::post('/buscar_dni_liq', [SistemaController::class, 'buscar_dni_liq'])->name('buscar_dni_liq');
Route::get('/buscar_cue_liq', [SistemaController::class, 'buscar_cue_liq'])->name('buscar_cue_liq');
Route::post('/buscar_cue_liq', [SistemaController::class, 'buscar_cue_liq'])->name('buscar_cue_liq');
Route::get('/buscar_zonas_consultas', [SistemaController::class, 'buscar_zonas_consultas'])->name('buscar_zonas_consultas');
Route::post('/buscar_zonas_consultas', [SistemaController::class, 'buscar_zonas_consultas'])->name('buscar_zonas_consultas');
Route::get('/actualizarValoresInstituciones', [SistemaController::class, 'actualizarValoresInstituciones'])->name('actualizarValoresInstituciones');

//subir documentos
Route::post('/upload', [SubirDocController::class, 'store'])->name('store');
Route::get('/traerArchivos', [SubirDocController::class, 'traerArchivos'])->name('traerArchivos');
Route::post('/borrarDocumentoAgente', [SubirDocController::class, 'borrarDocumentoAgente'])->name('borrarDocumentoAgente');

//Liquidacion




//chatBlog
Route::get('/chatBlog', [ChatBlogController::class, 'chatBlog'])->name('chatBlog');
Route::post('/chatBlog', [ChatBlogController::class, 'chatBlog'])->name('chatBlog');



//PARA REGISTRO DE TITULO
//TITULO SOLO
Route::get('/gestion_titulos', [RegistroTituloController::class, 'gestion_titulos'])->name('gestion_titulos');
Route::post('/formularioTitulos', [RegistroTituloController::class, 'formularioTitulos'])->name('formularioTitulos');
Route::post('/formularioActualizarTitulos', [RegistroTituloController::class, 'formularioActualizarTitulos'])->name('formularioActualizarTitulos');
Route::get('/eliminarTitulo/{idTitulo}', [RegistroTituloController::class, 'eliminarTitulo'])->name('eliminarTitulo');

//CERTIFICADO SOLO
Route::get('/gestion_certificados', [RegistroTituloController::class, 'gestion_certificados'])->name('gestion_certificados');
Route::post('/formularioCertificados', [RegistroTituloController::class, 'formularioCertificados'])->name('formularioCertificados');
Route::post('/formularioActualizarCertificado', [RegistroTituloController::class, 'formularioActualizarCertificado'])->name('formularioActualizarCertificado');
Route::get('/eliminarCertificado/{idCertificado}', [RegistroTituloController::class, 'eliminarCertificado'])->name('eliminarCertificado');

//AGENTE SOLO
Route::get('/gestion_agentes_alta', [RegistroTituloController::class, 'gestion_agentes_alta'])->name('gestion_agentes_alta');
Route::post('/formularioAgentesAlta', [RegistroTituloController::class, 'formularioAgentesAlta'])->name('formularioAgentesAlta');
Route::get('/gestion_agentes_consulta', [RegistroTituloController::class, 'gestion_agentes_consulta'])->name('gestion_agentes_consulta');
Route::get('/gestion_agentes_solicitudes_titulos', [RegistroTituloController::class, 'gestion_agentes_solicitudes_titulos'])->name('gestion_agentes_solicitudes_titulos');
Route::get('/gestion_agentes_solicitudes_certificados', [RegistroTituloController::class, 'gestion_agentes_solicitudes_certificados'])->name('gestion_agentes_solicitudes_certificados');

Route::get('/editarAgenteTitulo/{idAgente}', [RegistroTituloController::class, 'editarAgenteTitulo'])->name('editarAgenteTitulo');
Route::post('/formularioAgentesActualizar', [RegistroTituloController::class, 'formularioAgentesActualizar'])->name('formularioAgentesActualizar');

Route::get('/agregarTituloyCertificado/{idAgente}', [RegistroTituloController::class, 'agregarTituloyCertificado'])->name('agregarTituloyCertificado');
Route::get('/editarTituloCreado/{idRegistro}', [RegistroTituloController::class, 'editarTituloCreado'])->name('editarTituloCreado');

Route::post('/formularioTituloYCertificado', [RegistroTituloController::class, 'formularioTituloYCertificado'])->name('formularioTituloYCertificado');
Route::get('/agregarDocAgenteTitulo/{idAgente}', [RegistroTituloController::class, 'agregarDocAgenteTitulo'])->name('agregarDocAgenteTitulo');
Route::post('/formAgregarDocRegTitulo', [RegistroTituloController::class, 'formAgregarDocRegTitulo'])->name('formAgregarDocRegTitulo');


//ESTABLECIMIENTO SOLO
Route::get('/gestion_establecimientos', [RegistroTituloController::class, 'gestion_establecimientos'])->name('gestion_establecimientos');
Route::post('/formularioEstablecimientos', [RegistroTituloController::class, 'formularioEstablecimientos'])->name('formularioEstablecimientos');
Route::post('/formularioActualizarEstablecimiento', [RegistroTituloController::class, 'formularioActualizarEstablecimiento'])->name('formularioActualizarEstablecimiento');
Route::get('/eliminarEstablecimiento/{idEstablecimiento}', [RegistroTituloController::class, 'eliminarEstablecimiento'])->name('eliminarEstablecimiento');


//REGISTRO DE TITULO
Route::get('/gestion_reg_titulo', [RegistroTituloController::class, 'gestion_reg_titulo'])->name('gestion_reg_titulo');
Route::post('/formulario_reg_titulo', [RegistroTituloController::class, 'formulario_reg_titulo'])->name('formulario_reg_titulo');
Route::get('/download/{filename}', [RegistroTituloController::class, 'download'])->name('file.download');

//REGISTRO DE CERTIFICADO


//MODO SUPERIOR
Route::get('/usuariosListaSup', [SuperiorController::class, 'usuariosListaSup'])->name('usuariosListaSup');
Route::get('/usuariosListaSupRegistrado', [SuperiorController::class, 'usuariosListaSupRegistrado'])->name('usuariosListaSupRegistrado');

Route::get('/documentosListaSup', [SuperiorController::class, 'documentosListaSup'])->name('documentosListaSup');
Route::get('/documentosListaSupRegistrado', [SuperiorController::class, 'documentosListaSupRegistrado'])->name('documentosListaSupRegistrado');

Route::get('/editarUsuarioSup/{idUsuario}', [SuperiorController::class, 'editarUsuarioSup'])->name('editarUsuarioSup');
Route::get('/editarUsuarioSupDocumentos/{idUsuario}', [SuperiorController::class, 'editarUsuarioSupDocumentos'])->name('editarUsuarioSupDocumentos');

Route::post('/FormRegistrarUsuarioSup', [SuperiorController::class, 'FormRegistrarUsuarioSup'])->name('FormRegistrarUsuarioSup');
Route::post('/FormActualizarUsuarioSup', [SuperiorController::class, 'FormActualizarUsuarioSup'])->name('FormActualizarUsuarioSup');

Route::post('/FormRegistrarDocumentoSup', [SuperiorController::class, 'FormRegistrarDocumentoSup'])->name('FormRegistrarDocumentoSup');
Route::get('/editarTituloSup/{idtitulo}', [SuperiorController::class, 'editarTituloSup'])->name('editarTituloSup');
Route::post('/FormActualizarTituloSup', [SuperiorController::class, 'FormActualizarTituloSup'])->name('FormActualizarTituloSup');

Route::get('/altaAgenteSup', [SuperiorController::class, 'altaAgenteSup'])->name('altaAgenteSup');
Route::post('/FormRegUsuarioSuperior', [SuperiorController::class, 'FormRegUsuarioSuperior'])->name('FormRegUsuarioSuperior');
Route::post('/registrarTituloSuperior', [SuperiorController::class, 'registrarTituloSuperior'])->name('registrarTituloSuperior');

//control de IPE
Route::get('/controlDeIpe', [ControlIPEEscuelasController::class, 'controlDeIpe'])->name('controlDeIpe');
Route::get('/controlDeIpeAnterior', [ControlIPEEscuelasController::class, 'controlDeIpeAnterior'])->name('controlDeIpeAnterior');
Route::post('/consulta-medifan', [ControlIPEEscuelasController::class, 'consultar'])->name('consulta.medifan');

Route::get('/controlDeIpeSuper/{idInstitucionExtension}', [ControlIPEEscuelasController::class, 'controlDeIpeSuper'])->name('controlDeIpeSuper');
Route::get('/controlDeIpeSuperAnterior/{idInstitucionExtension}', [ControlIPEEscuelasController::class, 'controlDeIpeSuperAnterior'])->name('controlDeIpeSuperAnterior');

Route::get('/cantidadIPEInforme', [ControlIPEEscuelasController::class, 'cantidadIPEInforme'])->name('cantidadIPEInforme');
Route::get('/reporte/ipe/detalle', [ControlIPEEscuelasController::class, 'detalleIPE'])->name('reporte.ipe.detalle');

Route::get('/UnificarPofIpe', [ControlIPEEscuelasController::class, 'UnificarPofIpe'])->name('UnificarPofIpe');
Route::get('/controlDeIpeTec/{idInstitucionExtension}', [ControlIPEEscuelasController::class, 'controlDeIpeTec'])->name('controlDeIpeTec');
Route::delete('/agente-recuperar/{idPofIpe}/{cue}', [ControlIPEEscuelasController::class, 'recuperarAgenteEliminado'])->name('agente.recuperar');

Route::post('/FormNuevoAgenteAltaControlIpe', [ControlIPEEscuelasController::class, 'FormNuevoAgenteAltaControlIpe'])->name('FormNuevoAgenteAltaControlIpe');
Route::post('/verificar-dni-existe', [ControlIPEEscuelasController::class, 'verificarDni'])->name('verificar.dni.existe');



Route::post('/actualizar_ipe', [ControlIPEEscuelasController::class, 'actualizarIPE']);
Route::post('/actualizar_pertenece', [ControlIPEEscuelasController::class, 'actualizarPertenece']);
//ipe relacionados
Route::post('/actualizar_ipe_r1', [ControlIPEEscuelasController::class, 'actualizarIPER1']);
Route::post('/actualizar_pertenece_r1', [ControlIPEEscuelasController::class, 'actualizarPerteneceR1']);
Route::post('/eliminar_agente_relacionado', [ControlIPEEscuelasController::class, 'eliminarAgenteRelacionado']);
Route::post('/eliminar_agente_base', [ControlIPEEscuelasController::class, 'eliminarAgenteBase']);


Route::post('/actualizar_turno', [ControlIPEEscuelasController::class, 'actualizarTurno']);
Route::post('/actualizar_turno_relacionado', [ControlIPEEscuelasController::class, 'actualizarTurno_relacionado']);
Route::post('/actualizar_hora', [ControlIPEEscuelasController::class, 'actualizarHora']);
Route::post('/actualizar_hora_relacionado', [ControlIPEEscuelasController::class, 'actualizarHora_relacionado']);

Route::get('/getAgentesIPE/{DNI}', [ControlIPEEscuelasController::class, 'getAgentesIPE'])->name('getAgentesIPE');
Route::post('/agregar_agente_ipe', [ControlIPEEscuelasController::class, 'agregarAgente']);

//Registro muticuenta
Route::get('/registrarDocente', [RegistroMulticuenta::class, 'registrarDocente'])->name('registrarDocente');
Route::get('/preregistroDocente', [RegistroMulticuenta::class, 'preregistroDocente'])->name('preregistroDocente');

Route::post('/formRegDoc', [RegistroMulticuenta::class, 'formRegDoc'])->name('formRegDoc');
Route::post('/buscar_usuario', [RegistroMulticuenta::class, 'buscar_usuario'])->name('buscar_usuario');
Route::get('/recuperarClaveMulticuenta', [RegistroMulticuenta::class, 'recuperarClaveMulticuenta'])->name('recuperarClaveMulticuenta');
Route::post('/formRecDoc', [RegistroMulticuenta::class, 'formRecDoc'])->name('formRecDoc');
Route::post('/validar-dni', [RegistroMulticuenta::class, 'validarDni'])->name('validar.dni');


//manejo de perfil del docente
Route::get('/perfilMulticuenta', [DocenteController::class, 'perfilMulticuenta'])->name('perfilMulticuenta');
Route::post('/formPerfilDoc', [DocenteController::class, 'formPerfilDoc'])->name('formPerfilDoc');
Route::get('/datosPersonales', [DocenteController::class, 'datosPersonales'])->name('datosPersonales');

//AEGIS - Sistema de control unificado
//MANEJO DE REGTITULO
Route::get('/infoRegTitulo', [AegisController::class, 'infoRegTitulo'])->name('infoRegTitulo');

//--------FIN MANEJO REG TITULO--------------------------------------------------------------


//MANEJO DE SURI
Route::get('/infoSuri', [AegisController::class, 'infoSuri'])->name('infoSuri');

//--------FIN MANEJO DE SURI--------------------------------------------------------------



//MANEJO DE SAGE y MULTICUENTA
Route::get('/infoSAGE', [AegisController::class, 'infoSAGE'])->name('infoSAGE');
Route::post('/ActualizarPofmhRecibo', [AegisController::class, 'ActualizarPofmhRecibo'])->name('ActualizarPofmhRecibo');

//--------FIN MANEJO DE SAGE MULTICUENTA--------------------------------------------------------------



//MANEJO DE SUPERIOR - LO LEE AUTOMATICO
//Route::get('/infoSuperior',[superiorController::class,'infoSuperior'])->name('infoSuperior');
//Route::get('/infoSuperior2',[superiorController::class,'infoSuperior2'])->name('infoSuperior2');

//--------FIN MANEJO DE SUPERIOR--------------------------------------------------------------





//Nuevo modelo de POF horizontal
Route::get('/cargar_pof_horizontal', [PofHorizontalController::class, 'cargar_pof_horizontal'])->name('cargar_pof_horizontal');
Route::post('/crearRegistro', [PofHorizontalController::class, 'crearRegistro'])->name('crearRegistro');
Route::get('/escuelasCargadasPOFMH', [PofHorizontalController::class, 'escuelasCargadasPOFMH'])->name('escuelasCargadasPOFMH');
Route::get('/verPofMhidExt/{idExtension}', [PofHorizontalController::class, 'verPofMhidExt'])->name('verPofMhidExt');

//probando nueva estructrua
Route::get('/verPofMhidExtPrueba/{idExtension}', [PofHorizontalController::class, 'verPofMhidExtPrueba'])->name('verPofMhidExtPrueba');

//aqui comienzan las actualizaciones por celda
Route::post('/actualizarOrden', [PofHorizontalController::class, 'actualizarOrden'])->name('actualizarOrden');
Route::post('/actualizarDNI', [PofHorizontalController::class, 'actualizarDNI'])->name('actualizarDNI');
Route::post('/actualizarApeNom', [PofHorizontalController::class, 'actualizarApeNom'])->name('actualizarApeNom');
Route::post('/actualizarCargoSalarial', [PofHorizontalController::class, 'actualizarCargoSalarial'])->name('actualizarCargoSalarial');
Route::post('/actualizarAula', [PofHorizontalController::class, 'actualizarAula'])->name('actualizarAula');

Route::post('/actualizarDivision', [PofHorizontalController::class, 'actualizarDivision'])->name('actualizarDivision');
Route::post('/actualizarEspCur', [PofHorizontalController::class, 'actualizarEspCur'])->name('actualizarEspCur');

Route::post('/actualizarMatricula', [PofHorizontalController::class, 'actualizarMatricula'])->name('actualizarMatricula');

Route::post('/actualizarTurno', [PofHorizontalController::class, 'actualizarTurno'])->name('actualizarTurno');
Route::post('/actualizarHoras', [PofHorizontalController::class, 'actualizarHoras'])->name('actualizarHoras');
Route::post('/actualizarOrigen', [PofHorizontalController::class, 'actualizarOrigen'])->name('actualizarOrigen');
Route::post('/actualizarSitRev', [PofHorizontalController::class, 'actualizarSitRev'])->name('actualizarSitRev');
Route::post('/actualizarFechaAltaCargo', [PofHorizontalController::class, 'actualizarFechaAltaCargo'])->name('actualizarFechaAltaCargo');
Route::post('/actualizarFechaDesignado', [PofHorizontalController::class, 'actualizarFechaDesignado'])->name('actualizarFechaDesignado');
Route::post('/actualizarCondicion', [PofHorizontalController::class, 'actualizarCondicion'])->name('actualizarCondicion');
Route::post('/actualizarActivo', [PofHorizontalController::class, 'actualizarActivo'])->name('actualizarActivo');

Route::post('/actualizarFechaDesde', [PofHorizontalController::class, 'actualizarFechaDesde'])->name('actualizarFechaDesde');
Route::post('/actualizarFechaHasta', [PofHorizontalController::class, 'actualizarFechaHasta'])->name('actualizarFechaHasta');
Route::post('/actualizarMotivo', [PofHorizontalController::class, 'actualizarMotivo'])->name('actualizarMotivo');
Route::post('/actualizarDatosPorCondicion', [PofHorizontalController::class, 'actualizarDatosPorCondicion'])->name('actualizarDatosPorCondicion');
Route::post('/actualizarAntiguedad', [PofHorizontalController::class, 'actualizarAntiguedad'])->name('actualizarAntiguedad');
Route::post('/actualizarAgenteR', [PofHorizontalController::class, 'actualizarAgenteR'])->name('actualizarAgenteR');
//asistencias
Route::post('/actualizarAsistencia', [PofHorizontalController::class, 'actualizarAsistencia'])->name('actualizarAsistencia');
Route::post('/actualizarAsistenciaJustificada', [PofHorizontalController::class, 'actualizarAsistenciaJustificada'])->name('actualizarAsistenciaJustificada');
Route::post('/actualizarAsistenciaInjustificada', [PofHorizontalController::class, 'actualizarAsistenciaInjustificada'])->name('actualizarAsistenciaInjustificada');
Route::post('/actualizarObservaciones', [PofHorizontalController::class, 'actualizarObservaciones'])->name('actualizarObservaciones');

Route::post('/actualizarCarrera', [PofHorizontalController::class, 'actualizarCarrera'])->name('actualizarCarrera');
Route::post('/actualizarOrientacion', [PofHorizontalController::class, 'actualizarOrientacion'])->name('actualizarOrientacion');
Route::post('/actualizarTitulo', [PofHorizontalController::class, 'actualizarTitulo'])->name('actualizarTitulo');

Route::post('/actualizarZonaSupervision', [PofHorizontalController::class, 'actualizarZonaSupervision'])->name('actualizarZonaSupervision');

Route::post('/actualizarNovedades', [PofHorizontalController::class, 'actualizarNovedades'])->name('actualizarNovedades');

Route::post('/actualizarDatos', [PofHorizontalController::class, 'actualizarDatos'])->name('actualizarDatos');

//rutas para los combos
Route::get('/obtener_cargosSalariales', [PofHorizontalController::class, 'obtener_cargosSalariales'])->name('obtener_cargosSalariales');
Route::get('/obtener_aulas', [PofHorizontalController::class, 'obtener_aulas'])->name('obtener_aulas');
Route::get('/obtener_division', [PofHorizontalController::class, 'obtener_division'])->name('obtener_division');
Route::get('/obtener_aula_y_division_por_origen', [PofHorizontalController::class, 'obtenerAulaYDivisionPorOrigen']);
Route::get('/obtener_aulas_por_origen', [PofHorizontalController::class, 'obtenerAulasPorOrigen']);


Route::post('/obtener_turnos', [PofHorizontalController::class, 'obtener_turnos'])->name('obtener_turnos');
Route::get('/obtener_sitrev', [PofHorizontalController::class, 'obtener_sitrev'])->name('obtener_sitrev');
Route::get('/obtener_motivos', [PofHorizontalController::class, 'obtener_motivos'])->name('obtener_motivos');
Route::get('/obtener_condiciones', [PofHorizontalController::class, 'obtener_condiciones'])->name('obtener_condiciones');
Route::get('/obtener_activos', [PofHorizontalController::class, 'obtener_activos'])->name('obtener_activos');
Route::get('/obtener_origenes', [PofHorizontalController::class, 'obtener_origenes'])->name('obtener_origenes');
// En routes/web.php
Route::post('/borrarFilaPofmh', [PofHorizontalController::class, 'borrarFilaPofmh'])->name('borrarFilaPofmh');

//funcion de migracion de liq sep 2024
Route::get('/procesoliq', [PofHorizontalController::class, 'procesoliq'])->name('procesoliq');
Route::get('/procesoliq_mejor', [PofHorizontalController::class, 'procesoliq_mejor'])->name('procesoliq_mejor');
Route::get('/procesoliq_agentes_liq', [PofHorizontalController::class, 'procesoliq_agentes_liq'])->name('procesoliq_agentes_liq');

Route::post('/pofmhformularioNovedadParticular', [PofHorizontalController::class, 'pofmhformularioNovedadParticular'])->name('pofmhformularioNovedadParticular');
Route::get('/pofmhNovedades/{dni}/{cue}', [PofHorizontalController::class, 'pofmhNovedades'])->name('pofmhNovedades');
Route::post('/uploadpofmh', [PofHorizontalController::class, 'uploadpofmh'])->name('uploadpofmh');
Route::get('/traerArchivospofmh', [PofHorizontalController::class, 'traerArchivospofmh'])->name('traerArchivospofmh');
Route::post('/borrarDocumentoAgentePof', [PofHorizontalController::class, 'borrarDocumentoAgentePof'])->name('borrarDocumentoAgentePof');
Route::delete('/novedadesModal/{id}', [PofHorizontalController::class, 'novedadesModal'])->name('novedadesModal');

//cargos origen a pedido

Route::get('/procesarOrigenesCargos', [PofHorizontalController::class, 'procesarOrigenesCargos'])->name('procesarOrigenesCargos');
Route::get('/procesarOrigenesCargos_todos', [PofHorizontalController::class, 'procesarOrigenesCargos_todos'])->name('procesarOrigenesCargos_todos');
Route::get('/actualizarPorChunks', [PofHorizontalController::class, 'actualizarPorChunks'])->name('actualizarPorChunks');
Route::get('/verificoDuplicados', [PofHorizontalController::class, 'verificoDuplicados'])->name('verificoDuplicados');
Route::get('/controlDuplicadosEntreTablas', [PofHorizontalController::class, 'controlDuplicadosEntreTablas'])->name('controlDuplicadosEntreTablas');

//par decreto, los dejo en podfmh pero son de LUI
Route::post('/uploadpofmhdecreto', [PofHorizontalController::class, 'uploadpofmhdecreto'])->name('uploadpofmhdecreto');
Route::get('/traerArchivospofmhdecreto', [PofHorizontalController::class, 'traerArchivospofmhdecreto'])->name('traerArchivospofmhdecreto');
Route::post('/borrarDocumentoAgentePofDecreto', [PofHorizontalController::class, 'borrarDocumentoAgentePofDecreto'])->name('borrarDocumentoAgentePofDecreto');

//para novedades desde las super
Route::post('/uploadnovedadsuper', [PofHorizontalController::class, 'uploadnovedadsuper'])->name('uploadnovedadsuper');
Route::get('/traerArchivosNovedades', [PofHorizontalController::class, 'traerArchivosNovedades'])->name('traerArchivosNovedades');
Route::post('/borrarDocumentoNovedades', [PofHorizontalController::class, 'borrarDocumentoNovedades'])->name('borrarDocumentoNovedades');



Route::get('/verCargosCreados/{idExt}', [PofHorizontalController::class, 'verCargosCreados'])->name('verCargosCreados');
Route::get('/verCargosPofvsNominal/{idExt}', [PofHorizontalController::class, 'verCargosPofvsNominal'])->name('verCargosPofvsNominal');

Route::get('/verCargosNivelInicial', [PofHorizontalController::class, 'verCargosNivelInicial'])->name('verCargosNivelInicial');
Route::get('/verCargosCreadosAulas/{idExt}', [PofHorizontalController::class, 'verCargosCreadosAulas'])->name('verCargosCreadosAulas');

Route::post('/formularioCargosOriginales', [PofHorizontalController::class, 'formularioCargosOriginales'])->name('formularioCargosOriginales');
Route::post('/formularioAulaCargosOriginales', [PofHorizontalController::class, 'formularioAulaCargosOriginales'])->name('formularioAulaCargosOriginales');

Route::get('/desvincularOrigenCargo/{idCargo}', [PofHorizontalController::class, 'desvincularOrigenCargo'])->name('desvincularOrigenCargo');
Route::get('/desvincularAulaOrigenCargo/{idPadt}', [PofHorizontalController::class, 'desvincularAulaOrigenCargo'])->name('desvincularAulaOrigenCargo');


//para recuperar agentes
Route::get('/escuelasCargadasRecAgente', [PofHorizontalController::class, 'escuelasCargadasRecAgente'])->name('escuelasCargadasRecAgente');
Route::get('/buscar_dni_cue_pofmh/{CUECOMPLETO}', [PofHorizontalController::class, 'buscar_dni_cue_pofmh'])->name('buscar_dni_cue_pofmh');
Route::post('/buscar_dni_ajax', [PofHorizontalController::class, 'buscar_dni_ajax'])->name('buscar_dni_ajax');
Route::post('/insertar_usuario', [PofHorizontalController::class, 'insertar_usuario'])->name('insertar_usuario');

//verifico si existe el dni
Route::post('/verificarDNI', [PofHorizontalController::class, 'verificarDNI'])->name('verificarDNI');

//para supervisores
Route::get('/listaSupervisora', [PofHorizontalController::class, 'listaSupervisora'])->name('listaSupervisora');
Route::get('/listaGestionPrivada', [PofHorizontalController::class, 'listaGestionPrivada'])->name('listaGestionPrivada');
Route::get('/listaGestionMunicipal', [PofHorizontalController::class, 'listaGestionMunicipal'])->name('listaGestionMunicipal');

Route::get('/listaSupervisoraVinculada', [PofHorizontalController::class, 'listaSupervisoraVinculada'])->name('listaSupervisoraVinculada');
Route::get('/listaSupervisoraMensajes', [PofHorizontalController::class, 'listaSupervisoraMensajes'])->name('listaSupervisoraMensajes');

Route::get('/verPofMhidExtSuper/{idExtension}', [PofHorizontalController::class, 'verPofMhidExtSuper'])->name('verPofMhidExtSuper');
Route::post('/agregar_relacion_cue_super', [PofHorizontalController::class, 'agregar_relacion_cue_super'])->name('agregar_agregar_relacion_cue_superrelacion');
Route::post('/eliminar_relacion_cue_super', [PofHorizontalController::class, 'eliminar_relacion_cue_super'])->name('eliminar_reliminar_relacion_cue_superelacion');

//liquidacion
Route::get('/buscar_dni_cue_pofmh_liq', [PofHorizontalController::class, 'buscar_dni_cue_pofmh_liq'])->name('buscar_dni_cue_pofmh_liq');
Route::get('/listarInstarealiq', [PofHorizontalController::class, 'listarInstarealiq'])->name('listarInstarealiq');
Route::post('/actualizar-instarealiq', [PofHorizontalController::class, 'actualizarInstarealiq'])->name('actualizar_instarealiq');

Route::post('/buscar_dni_ajax_liq', [PofHorizontalController::class, 'buscar_dni_ajax_liq'])->name('buscar_dni_ajax_liq');

Route::get('/buscar_cue_pofmh_liq', [PofHorizontalController::class, 'buscar_cue_pofmh_liq'])->name('buscar_cue_pofmh_liq');
Route::post('/buscar_cue_ajax_liq', [PofHorizontalController::class, 'buscar_cue_ajax_liq'])->name('buscar_cue_ajax_liq');

Route::get('/traerTodoAgenteLiq', [PofHorizontalController::class, 'traerTodoAgenteLiq'])->name('traerTodoAgenteLiq');
Route::delete('/eliminar_pof_agente/{id}', [PofHorizontalController::class, 'eliminar_pof_agente'])->name('eliminar_pof_agente');

//procesos nuevos en liq
// Buscar las áreas por escu
Route::post('/buscar-areas', [PofHorizontalController::class, 'buscarAreas'])->name('buscar.areas');

// Buscar los CUEs por escu y area
Route::post('/buscar-cues', [PofHorizontalController::class, 'buscarCues'])->name('buscar.cues');


//tecnicos
Route::get('/buscar_dni_cue_pofmh_tec', [PofHorizontalController::class, 'buscar_dni_cue_pofmh_tec'])->name('buscar_dni_cue_pofmh_tec');
Route::post('/buscar_dni_ajax_tec', [PofHorizontalController::class, 'buscar_dni_ajax_tec'])->name('buscar_dni_ajax_tec');


//prueba de traer datos
Route::get('/traerDatosPof', [PofHorizontalController::class, 'traerDatosPof'])->name('traerDatosPof');
Route::get('/exportar_pof', [PofHorizontalController::class, 'exportar_pof'])->name('exportar_pof');
Route::get('/verificarDNIs', [PofHorizontalController::class, 'verificarDNIs'])->name('verificarDNIs');

Route::get('/consultas_pof_cantEscuela', [PofHorizontalController::class, 'consultas_pof_cantEscuela'])->name('consultas_pof_cantEscuela');
Route::get('/consultas_pof/{Nivel}', [PofHorizontalController::class, 'consultas_pof'])->name('consultas_pof');
Route::get('/consultas_pof_cantidad/{Nivel}', [PofHorizontalController::class, 'consultas_pof_cantidad'])->name('consultas_pof_cantidad');
Route::get('/consultas_pof_agrupadas/{Nivel}', [PofHorizontalController::class, 'consultas_pof_agrupadas'])->name('consultas_pof_agrupadas');
Route::get('/consultas_pof_agrupadas_ultima/{Nivel}', [PofHorizontalController::class, 'consultas_pof_agrupadas_ultima'])->name('consultas_pof_agrupadas_ultima');
Route::get('/consultas_pof_agrupadas_ultima_concargo/{Nivel}', [PofHorizontalController::class, 'consultas_pof_agrupadas_ultima_concargo'])->name('consultas_pof_agrupadas_ultima_concargo');

Route::get('/consultaBajas', [PofHorizontalController::class, 'consultaBajas'])->name('consultaBajas');


Route::get('/consultas_pof_agrupadas_detalle/{Nivel}', [PofHorizontalController::class, 'consultas_pof_agrupadas_detalle'])->name('consultas_pof_agrupadas_detalle');
Route::get('/consultas_pof_detalladas/{Nivel}', [PofHorizontalController::class, 'consultas_pof_detalladas'])->name('consultas_pof_detalladas');
Route::get('/consultas_pof_agrupadas_categoria/{Nivel}', [PofHorizontalController::class, 'consultas_pof_agrupadas_categoria'])->name('consultas_pof_agrupadas_categoria');

Route::get('/ver_info_por_Zonas_Liq', [EstadisticasController::class, 'ver_info_por_Zonas_Liq'])->name('ver_info_por_Zonas_Liq');
Route::get('/ver_info_por_Zonas_Liq_opt', [EstadisticasController::class, 'ver_info_por_Zonas_Liq_opt'])->name('ver_info_por_Zonas_Liq_opt');

Route::get('/cargar_zona_liq_opt/{idZona}', [EstadisticasController::class, 'cargar_zona_liq_opt'])->name('cargar_zona_liq_opt');
Route::get('/cargarAgentes', [EstadisticasController::class, 'cargarAgentes'])->name('cargarAgentes');
Route::get('/verInfoInstitucion/{inst}/pof', [EstadisticasController::class, 'verInfoInstitucion'])->name('verInfoInstitucion');
Route::get('/obtenerAulasPOFMH/{cue}/{id}', [PofHorizontalController::class, 'obtenerAulasPOFMH'])->name('obtenerAulasPOFMH');



Route::get('/comparacionLiqPof', [PofHorizontalController::class, 'comparacionLiqPof'])->name('comparacionLiqPof');


Route::post('/generarexcel', [PofHorizontalController::class, 'generarExcel'])->name('generarexcel');




//asistencias
Route::get('/asistencias_pofmh/{idExtension}', [PofHorizontalController::class, 'asistencias_pofmh'])->name('asistencias_pofmh');
Route::get('/buscarPofmh/{idExtension}', [PofHorizontalController::class, 'buscarPofmh'])->name('buscarPofmh');
Route::get('/buscarPofmhCompleto/{idExtension}', [PofHorizontalController::class, 'buscarPofmhCompleto'])->name('buscarPofmhCompleto');
Route::post('/colocarAsistncia', [PofHorizontalController::class, 'colocarAsistncia'])->name('colocarAsistncia');

//assitencia modelo nuevo solo novedad
Route::get('/asistencias_modelo_pofmh/{idExtension}', [PofHorizontalController::class, 'asistencias_modelo_pofmh'])->name('asistencias_modelo_pofmh');

//borrar porque es de recuperacion*
Route::get('/asistencias_pofmh_anterior/{idExtension}', [PofHorizontalController::class, 'asistencias_pofmh_anterior'])->name('asistencias_pofmh_anterior');
Route::get('/buscarPofmh_nov/{idExtension}', [PofHorizontalController::class, 'buscarPofmh_nov'])->name('buscarPofmh_nov');
Route::get('/buscarPofmhCompleto_nov/{idExtension}', [PofHorizontalController::class, 'buscarPofmhCompleto_nov'])->name('buscarPofmhCompleto_nov');
Route::post('/colocarAsistncia_nov', [PofHorizontalController::class, 'colocarAsistncia_nov'])->name('colocarAsistncia_nov');


Route::get('/calendario', [PofHorizontalController::class, 'calendario'])->name('calendario');
Route::get('/calendarioEsc', [PofHorizontalController::class, 'calendarioEsc'])->name('calendarioEsc');

Route::post('/FormNuevaFecha', [PofHorizontalController::class, 'FormNuevaFecha'])->name('FormNuevaFecha');
Route::get('/calendario/lista', [PofHorizontalController::class, 'obtenerFechas'])->name('calendario.lista');
Route::get('/calendario/listaEsc', [PofHorizontalController::class, 'obtenerFechasEsc'])->name('calendario.listaEsc');
Route::delete('/calendario/eliminar/{id}', [PofHorizontalController::class, 'eliminarFecha'])->name('calendario.eliminar');

Route::get('/sage2_prueba', [Sage2_1Controller::class, 'index'])->name('sage2_prueba');


//para contorlar consulta de hacienda
Route::get('/consultaOrigenCargos', [ConsultasController::class, 'consultaOrigenCargos'])->name('consultaOrigenCargos');

//proceso para cargar dinamicos las rutas faltantes
// Cargar rutas de sistemas
foreach (glob(__DIR__ . '/sistemas/*.php') as $routeFile) {
    require $routeFile;
}
