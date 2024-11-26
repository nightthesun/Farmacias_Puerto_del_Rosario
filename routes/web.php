<?php

use App\Http\Controllers\AdmAccionVentanaController;
use App\Http\Controllers\AdmBancoController;
use App\Http\Controllers\AdmCiudadController;
use App\Http\Controllers\AdmCredecialCorreoController;
use App\Http\Controllers\AdmDepartamentoController;
use App\Http\Controllers\AdmModuloController;
use App\Http\Controllers\AdmNacionalidadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmRegistroController;
use App\Http\Controllers\AdmRoleController;
use App\Http\Controllers\AdmSessionController;
use App\Http\Controllers\AdmSucursalController;
use App\Http\Controllers\AdmRubroController;
use App\Http\Controllers\AdmUserController;
use App\Http\Controllers\AdmUserRoleSucursalController;
use App\Http\Controllers\AdmVentanaModuloController;
use App\Http\Controllers\AlmAlmacenController;
use App\Http\Controllers\AlmCodificacionController;
use App\Http\Controllers\AlmIngresoProducto2Controller;
use App\Http\Controllers\AlmIngresoProductoController;
use App\Http\Controllers\CajaAperturaCierreController;
use App\Http\Controllers\CajaCreacionController;
use App\Http\Controllers\CajaEntradaSalidaController;
use App\Http\Controllers\CajaMonedaController;
use App\Http\Controllers\CajaTransaccionController;
use App\Http\Controllers\DirClienteController;
use App\Http\Controllers\DirDistribuidorController;
use App\Http\Controllers\DirDistriuidorController;
use App\Http\Controllers\DirProveedorController;
use App\Http\Controllers\EgrGastoController;
use App\Http\Controllers\EgrInversionController;
use App\Http\Controllers\EgrTesoreriaController;
use App\Http\Controllers\TdaTiendaController;
use App\Http\Controllers\GesPreVentaController;
use App\Http\Controllers\ParClienteController;
use App\Http\Controllers\ParDescServicioController;
use App\Http\Controllers\ProdCategoriaController;
use App\Http\Controllers\ProdDescuentoController;
use App\Http\Controllers\ProdDispenserController;
use App\Http\Controllers\ProdFormaFarmaceuticaController;
use App\Http\Controllers\ProdLineaController;
use App\Http\Controllers\ProdProductoController;
use App\Http\Controllers\ProdTipoDescuentoController;
use App\Http\Controllers\ProdTipoEntradaController;
use App\Http\Controllers\RrhCargoController;
use App\Http\Controllers\RrhEmpleadoController;
use App\Http\Controllers\RrhFormacionController;
use App\Http\Controllers\RrhProfesionController;
use App\Http\Controllers\RrhUnidadOrganizacionalController;
use App\Http\Controllers\SerAreaController;
use App\Http\Controllers\SerPrestacionController;
use App\Http\Controllers\SerVentaController;
use App\Http\Controllers\SerVentaMaestroController;
use App\Http\Controllers\TdaIngresoProductoController;
use App\Http\Controllers\InvAjusteNegativoController;
use App\Http\Controllers\InvAjustePositivoController;
use App\Http\Controllers\InvProcesarTraspasoController;
use App\Http\Controllers\InvRecepcionController;
use App\Http\Controllers\InvTrasladoController;
use App\Http\Controllers\InvTraspasoController;
use App\Http\Controllers\LogTrasladoController;
use App\Http\Controllers\LogVehiculoController;
use App\Http\Controllers\GesPreVenta2Controller;
use App\Http\Controllers\GestionPerimsoController;
use App\Http\Controllers\GetController;
use App\Http\Controllers\ParDescuentoController;
use App\Http\Controllers\ProdListaController;
use App\Http\Controllers\ProdRegistroPreXListController;
use App\Http\Controllers\TdaIngresoProducto2Controller;
use App\Http\Controllers\VenCaducidadController;
use App\Http\Controllers\VenGestorVentaController;
use App\Http\Controllers\VenGestorVentaVistaController;
use App\Models\Alm_IngresoProducto;
use App\Models\Tda_Tienda;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /* if(!is_null(session('idsucursal')))
        return redirect()->to('/selectsuc');    
    else */
    return view('contenido/contenido');
})->middleware('auth');

/* Route::get('/perfilusuario', function () {
        return view('contenido/perfilusuario');    
    
})->middleware('auth'); */

Route::get('/login', [AdmSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login.index');

Route::get('/recpass', [AdmSessionController::class, 'recpass'])
    ->middleware('guest')
    ->name('login.recpass');

Route::post('/recpass', [AdmSessionController::class, 'verEmail'])
    ->middleware('guest')
    ->name('login.vermail');

Route::get('/resetpass', [AdmSessionController::class, 'resetpass'])
    ->middleware('guest')
    ->name('login.codigo');

Route::post('/resetpass', [AdmSessionController::class, 'actpass'])
    ->middleware('guest')
    ->name('login.actpass');

Route::get('/enviar-email', [AdmSessionController::class, 'sendEmail'])
    ->middleware('guest');

Route::get('/selectsuc', [AdmSessionController::class, 'sucursal'])
    ->middleware('auth')
    ->name('login.sucursal');

Route::post('/selectsuc', [AdmSessionController::class, 'entrar'])
    ->middleware('auth')
    ->name('login.entrar');


Route::post('/login', [AdmSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');
Route::get('/logout', [AdmSessionController::class, 'destroy'])
    //->middleware('auth')
    ->name('login.destroy');

    
Route::get('/registro', [AdmRegistroController::class, 'create'])
    ->middleware('auth')
    ->name('registro.index');
Route::post('/registro', [AdmRegistroController::class, 'store'])
    ->middleware('auth')
    ->name('registro.store');



/* Route::get('/', function () {
    return view('contenido/contenido');
}); */

Route::group(['middleware' => 'auth'], function () {

    //////////////////////////////////////controlador  generales/////////////////////////////////////////////////////////////
    /*****************permiso**************** */        
    Route::get('/gestion_permiso_editar_eliminar', [GestionPerimsoController::class, 'permisos_editar_activar']);
    Route::get('/bloqueado', [GestionPerimsoController::class, 'bloqueado']);
    Route::get('/listar_alamcen_tienda_permisos', [GestionPerimsoController::class, 'listar_alamcen_tienda_permisos']);
    Route::get('/listar_tienda_alamce_generico_lista_x_rol_usuario', [GestionPerimsoController::class, 'listar_tienda_alamce_generico_lista_x_rol_usuario']);
        
    /*****************tipo vista**************** */
    Route::get('/listarSucursal', [GetController::class, 'listarSucursal']);
    Route::get('/listarSucursalGet', [GetController::class, 'listarSucursalGet']);
    Route::get('/listarTipoDoc', [GetController::class, 'listarTipoDoc']);  
    Route::get('/listarEx', [GetController::class, 'listarEx']);   
    Route::get('/listar_entradasXe', [GetController::class, 'listar_entradasXe']);   
    Route::get('/tiene_movimiento', [GetController::class, 'tiene_movimiento']);
    Route::get('/listarSucusal_TDA_ALM_sin_permiso', [GetController::class, 'listarSucusal_TDA_ALM_sin_permiso']);   
    Route::get('/getBancos', [GetController::class, 'getBancos']);     
    Route::get('/listarUser', [GetController::class, 'getUser']);   
    Route::get('/getEmpelado', [GetController::class, 'getEmpelado']); 
    
    /**********************verificador de apertura cierre retornod e datos****************************** */
    Route::get('/verificacionAperturaCierre', [GetController::class, 'listarAperturaCierre']);
    Route::get('/verificador_moneda_sistemas', [GetController::class, 'listarMoneda_2']);
    
    //adm///////////////////////////////////////////////////////////////////////////////////

    Route::get('/rubro', [AdmRubroController::class, 'index']);
    Route::post('/rubro/registrar', [AdmRubroController::class, 'store']);
    Route::put('/rubro/actualizar', [AdmRubroController::class, 'update']);
    Route::put('/rubro/desactivar', [AdmRubroController::class, 'desactivar']);
    Route::put('/rubro/activar', [AdmRubroController::class, 'activar']);
    Route::get('/rubro/selectrubro', [AdmRubroController::class, 'selectRubro']);

    Route::get('/sucursal', [AdmSucursalController::class, 'index']);
    Route::post('/sucursal/registrar', [AdmSucursalController::class, 'store']);
    Route::put('/sucursal/actualizar', [AdmSucursalController::class, 'update']);
    Route::put('/sucursal/desactivar', [AdmSucursalController::class, 'desactivar']);
    Route::put('/sucursal/activar', [AdmSucursalController::class, 'activar']);
    Route::get('/sucursal/selectsucursal', [AdmSucursalController::class, 'selectSucursal']);
    Route::get('/sucursal/listarListas', [AdmSucursalController::class, 'listarListas']);
    Route::post('/sucursal/registrarlista', [AdmSucursalController::class, 'registrarlista']);
    Route::get('/sucursal/listarArray', [AdmSucursalController::class, 'listarArray']);
      
    Route::get('/modulo', [AdmModuloController::class, 'index']);
    Route::post('/modulo/registrar', [AdmModuloController::class, 'store']);
    Route::put('/modulo/actualizar', [AdmModuloController::class, 'update']);
    Route::put('/modulo/desactivar', [AdmModuloController::class, 'desactivar']);
    Route::put('/modulo/activar', [AdmModuloController::class, 'activar']);
    Route::get('/modulo/selectmodulo', [AdmModuloController::class, 'selectModulo']);

    Route::get('/ventana', [AdmVentanaModuloController::class, 'index']);
    Route::post('/ventana/registrar', [AdmVentanaModuloController::class, 'store']);
    Route::put('/ventana/actualizar', [AdmVentanaModuloController::class, 'update']);
    Route::put('/ventana/desactivar', [AdmVentanaModuloController::class, 'desactivar']);
    Route::put('/ventana/activar', [AdmVentanaModuloController::class, 'activar']);
    Route::get('/ventana/selectventana', [AdmVentanaModuloController::class, 'selectVentana']);

    Route::get('/accion', [AdmAccionVentanaController::class, 'index']);
    Route::post('/accion/registrar', [AdmAccionVentanaController::class, 'store']);
    Route::put('/accion/actualizar', [AdmAccionVentanaController::class, 'update']);
    Route::put('/accion/desactivar', [AdmAccionVentanaController::class, 'desactivar']);
    Route::put('/accion/activar', [AdmAccionVentanaController::class, 'activar']);
    Route::get('/accion/selectaccion', [AdmAccionVentanaController::class, 'selectAccion']);


    Route::get('/usuario', [AdmUserController::class, 'index']);
    Route::post('/usuario/registrar', [AdmUserController::class, 'store']);
    Route::put('/usuario/actualizar', [AdmUserController::class, 'update']);
    Route::put('/usuario/desactivar', [AdmUserController::class, 'desactivar']);
    Route::put('/usuario/activar', [AdmUserController::class, 'activar']);
    Route::get('/usuario/listar-usuarios', [AdmUserController::class, 'listaUsuarios']);
    Route::get('/usuario/selectusuario', [AdmUserController::class, 'selectUsuario']);
     
    Route::get('/role', [AdmRoleController::class, 'index']);
    Route::post('/role/registrar', [AdmRoleController::class, 'store']);
    Route::put('/role/actualizar', [AdmRoleController::class, 'update']);
    Route::put('/role/desactivar', [AdmRoleController::class, 'desactivar']);
    Route::put('/role/activar', [AdmRoleController::class, 'activar']);
    Route::get('/role/selectrole', [AdmRoleController::class, 'selectRole']);

    Route::get('/userrolesuc', [AdmUserRoleSucursalController::class, 'index']);
    Route::post('/userrolesuc/registrar', [AdmUserRoleSucursalController::class, 'store']);
    Route::put('/userrolesuc/desactivar', [AdmUserRoleSucursalController::class, 'desactivar']);
    Route::put('/userrolesuc/activar', [AdmUserRoleSucursalController::class, 'activar']);
    Route::post('/userrolesuc/registrarEditar_Activar', [AdmUserRoleSucursalController::class, 'registrarEditar_Activar']);
    Route::post('/userrolesuc/asignar', [AdmUserRoleSucursalController::class, 'asignar']);
    Route::get('/userrolesuc/listarVentanas', [AdmUserRoleSucursalController::class, 'listarVentanas']);
    Route::get('/userrolesuc/listar_asig_permiso_e_a_s', [AdmUserRoleSucursalController::class, 'listar_asig_permiso_e_a_s']);
    Route::get('/userrolesuc/getUsersWithRolesAndSucursals', [AdmUserRoleSucursalController::class, 'getUsersWithRolesAndSucursals']);

    Route::get('/credenciales_correo', [AdmCredecialCorreoController::class, 'credencia_correo']);
    Route::post('/credenciales_correo/update', [AdmCredecialCorreoController::class, 'update']);   
    Route::put('/credenciales_correo/tipo_venta_update', [AdmCredecialCorreoController::class, 'tipo_venta_update']); 
    Route::post('/credenciales_correo/update_datos_empresa', [AdmCredecialCorreoController::class, 'update_datos_empresa']);   
    Route::get('/credenciales_correo/tipo_moneda', [AdmCredecialCorreoController::class, 'tipo_moneda']); 
    Route::post('/credenciales_correo/tipomonedaUpdate', [AdmCredecialCorreoController::class, 'tipomonedaUpdate']);    
    Route::post('/credenciales_correo/crear_banco', [AdmCredecialCorreoController::class, 'crear_banco']);   
    Route::put('/credenciales_correo/editar_banco', [AdmCredecialCorreoController::class, 'editar_banco']); 
    Route::put('/credenciales_correo/banco_desactivar', [AdmCredecialCorreoController::class, 'desactivar']); 
    Route::put('/credenciales_correo/banco_activar', [AdmCredecialCorreoController::class, 'activar']); 
    Route::get('/cuenta/listar_cuenta', [AdmCredecialCorreoController::class, 'get_cuenta']); 
    Route::post('/cuenta/crear_cuenta', [AdmCredecialCorreoController::class, 'crear_cuenta']);  
    Route::put('/cuenta/editar_cuenta', [AdmCredecialCorreoController::class, 'editar_cuenta']); 
    Route::put('/cuenta/desactivar_cuenta', [AdmCredecialCorreoController::class, 'desactivar_cuenta']); 
    Route::put('/cuenta/activar_cuenta', [AdmCredecialCorreoController::class, 'activar_cuenta']); 
    Route::put('/responsable/añadir_quitar', [AdmCredecialCorreoController::class, 'añadir_quitar_Encargado']); 
    Route::post('/credenciales_correo/limite_2', [AdmCredecialCorreoController::class, 'añadirLimite']);   
    Route::put('/super_usuario/añadir_quitar', [AdmCredecialCorreoController::class, 'añadir_quitar_superUsuario']);     
      
    Route::get('/dosificacion/getDataSucursal', [AdmCredecialCorreoController::class, 'getDataSucursal']);
    Route::post('/dosificacion/store_dosificacion', [AdmCredecialCorreoController::class, 'store_dosificacion']);
    Route::get('/dosificacion/index_dosificacion', [AdmCredecialCorreoController::class, 'index_dosificacion']);    
    Route::post('/dosificacion/update_dosificacion', [AdmCredecialCorreoController::class, 'update_dosificacion']);
    Route::get('/dosificacion/activar_verificar_dosificacion', [AdmCredecialCorreoController::class, 'activar_verificar_dosificacion']);  
    Route::get('/dosificacion/verifica_esta_activo_dosificacacion_x_sucursal', [AdmCredecialCorreoController::class, 'verifica_esta_activo_dosificacacion_x_sucursal']);  
    Route::put('/dosificacion/desactivar_dosificacion', [AdmCredecialCorreoController::class, 'desactivar_dosificacion']);  
    Route::put('/dosificacion/activar_dosificacion', [AdmCredecialCorreoController::class, 'activar_dosificacion']);  


       //*******para listar si tiene permisos de edicion y activacion usar en todos los reporte o modulos*/
    Route::get('/userrolesuc/listarPermiso_Activacion', [AdmUserRoleSucursalController::class, 'listarPermiso_Activacion']);
    //*******para listar si tiene permisos de ver esa sucursal, tienda , almacen....*/
    Route::get('/userrolesuc/listarMas_sucursales', [AdmUserRoleSucursalController::class, 'listarMas_sucursales']);
    //*****************************************funcino de sucursales y almacenes ********************************************************** */
    Route::get('/userrolesuc/listarSucursal', [AdmUserRoleSucursalController::class, 'listarSucursal']);


    Route::get('/depto/selectdepto', [AdmDepartamentoController::class, 'selectDepartamento']);

    Route::get('/ciudad/selectciudad', [AdmCiudadController::class, 'selectCiudad']);
    Route::post('/ciudad/registrar', [AdmCiudadController::class, 'store']);

    Route::get('/nacion/selectnacion', [AdmNacionalidadController::class, 'selectNacion']);
    Route::post('/nacion/registrar', [AdmNacionalidadController::class, 'store']);

    Route::get('/banco/selectbanco', [AdmBancoController::class, 'selectBanco']);
    Route::post('/banco/registrar', [AdmBancoController::class, 'store']);



    //rrhh ////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('/empleado', [RrhEmpleadoController::class, 'index']);
    Route::get('/empleado/perfil', [RrhEmpleadoController::class, 'perfil']);
    Route::post('/empleado/registrar', [RrhEmpleadoController::class, 'store']);
    Route::post('/empleado/actualizar', [RrhEmpleadoController::class, 'update']);
    Route::put('/empleado/desactivar', [RrhEmpleadoController::class, 'desactivar']);
    Route::put('/empleado/activar', [RrhEmpleadoController::class, 'activar']);
    Route::get('/empleado/selectempleado', [RrhEmpleadoController::class, 'selectEmpleado']);
    Route::get('/empleado/selectnouser', [RrhEmpleadoController::class, 'selectNoUser']);
    Route::get('/empleado/getsaldo', [RrhEmpleadoController::class, 'getsaldo']);    

    Route::get('/formacion', [RrhFormacionController::class, 'index']);
    Route::post('/formacion/registrar', [RrhFormacionController::class, 'store']);
    Route::put('/formacion/actualizar', [RrhFormacionController::class, 'update']);
    Route::put('/formacion/desactivar', [RrhFormacionController::class, 'desactivar']);
    Route::put('/formacion/activar', [RrhFormacionController::class, 'activar']);
    Route::get('/formacion/selectformacion', [RrhFormacionController::class, 'selectFormacion']);

    Route::get('/profesion', [RrhProfesionController::class, 'index']);
    Route::post('/profesion/registrar', [RrhProfesionController::class, 'store']);
    Route::put('/profesion/actualizar', [RrhProfesionController::class, 'update']);
    Route::put('/profesion/desactivar', [RrhProfesionController::class, 'desactivar']);
    Route::put('/profesion/activar', [RrhProfesionController::class, 'activar']);
    Route::get('/profesion/selectprofesion', [RrhProfesionController::class, 'selectProfesion']);

    Route::get('/cargo', [RrhCargoController::class, 'index']);
    Route::post('/cargo/registrar', [RrhCargoController::class, 'store']);
    Route::put('/cargo/actualizar', [RrhCargoController::class, 'update']);
    Route::put('/cargo/desactivar', [RrhCargoController::class, 'desactivar']);
    Route::put('/cargo/activar', [RrhCargoController::class, 'activar']);
    Route::get('/cargo/selectcargo', [RrhCargoController::class, 'selectCargo']);

    Route::get('/unidadorg', [RrhUnidadOrganizacionalController::class, 'index']);
    Route::post('/unidadorg/registrar', [RrhUnidadOrganizacionalController::class, 'store']);
    Route::put('/unidadorg/actualizar', [RrhUnidadOrganizacionalController::class, 'update']);
    Route::put('/unidadorg/desactivar', [RrhUnidadOrganizacionalController::class, 'desactivar']);
    Route::put('/unidadorg/activar', [RrhUnidadOrganizacionalController::class, 'activar']);
    Route::get('/unidadorg/selectuo', [RrhUnidadOrganizacionalController::class, 'selectUnidadOrg']);

    /////////////////////////SERVICIOS///////////////////////////////////////
    Route::get('/area', [SerAreaController::class, 'index']);
    Route::post('/area/registrar', [SerAreaController::class, 'store']);
    Route::put('/area/actualizar', [SerAreaController::class, 'update']);
    Route::put('/area/desactivar', [SerAreaController::class, 'desactivar']);
    Route::put('/area/activar', [SerAreaController::class, 'activar']);
    Route::get('/area/selectarea', [SerAreaController::class, 'selectArea']);

    Route::get('/prestacion', [SerPrestacionController::class, 'index']);
    Route::post('/prestacion/registrar', [SerPrestacionController::class, 'store']);
    Route::put('/prestacion/actualizar', [SerPrestacionController::class, 'update']);
    Route::put('/prestacion/desactivar', [SerPrestacionController::class, 'desactivar']);
    Route::put('/prestacion/activar', [SerPrestacionController::class, 'activar']);
    Route::get('/prestacion/selectprest', [SerPrestacionController::class, 'selectPrestacion']);
    Route::get('/prestacion/selectprestaciones', [SerPrestacionController::class, 'selectPrestaciones']);

    Route::get('/ventas/listar', [SerVentaController::class, 'ventasListar']);
    Route::post('/ventas/registrar', [SerVentaController::class, 'store']);
    Route::put('/ventas/desactivar', [SerVentaController::class, 'desactivar']);
    Route::put('/ventas/registrarventa', [SerVentaController::class, 'registrarVenta']);
    Route::get('/ventas/detalle', [SerVentaController::class, 'ventasDetalle']);

    Route::post('/ventamaestro/registrarventamaestro', [SerVentaMaestroController::class, 'store']);
    Route::get('/ventasmaestro', [SerVentaMaestroController::class, 'index']);
    Route::put('/ventasmaestro/desactivar', [SerVentaMaestroController::class, 'desactivar']);
    Route::put('/ventasmaestro/registrarventa', [SerVentaMaestroController::class, 'activar']);


    ////////////////////// PARAMETROS/////////////////////////////////////////////
    Route::get('/descuento', [ParDescServicioController::class, 'index']);
    Route::post('/descuento/registrar', [ParDescServicioController::class, 'store']);
    Route::put('/descuento/actualizar', [ParDescServicioController::class, 'update']);
    Route::put('/descuento/desactivar', [ParDescServicioController::class, 'desactivar']);
    Route::put('/descuento/activar', [ParDescServicioController::class, 'activar']);
    Route::get('/descuento/selectdescuento', [ParDescServicioController::class, 'selectDescuento']);
    Route::get('/obtenerfecha', [ParDescServicioController::class, 'obtenerFecha']);


    Route::get('/clientes', [ParClienteController::class, 'index']);
    Route::get('/clientes/selectclientes', [ParClienteController::class, 'selectClientes']);
    Route::get('/clientes/selectcli', [ParClienteController::class, 'selectCli']);
    Route::post('/clientes/registrar', [ParClienteController::class, 'store']);
    
    //////---descuentos
    Route::get('/descuento2/listarTipoDescuentos', [ParDescuentoController::class, 'listarTipoDescuentos']);
    Route::get('/descuento2/listarTipoTabla', [ParDescuentoController::class, 'listarTipoTabla']);
    Route::get('/descuento2/listarClienteX', [ParDescuentoController::class, 'listarClienteX']);
    Route::get('/descuento2/listarProductoX', [ParDescuentoController::class, 'listarProductoX']);
    Route::post('/descuento2/registrarDescuento', [ParDescuentoController::class, 'store']);
    Route::get('/descuento2/index', [ParDescuentoController::class, 'index']);
    Route::put('/descuento2/desactivar', [ParDescuentoController::class, 'desactivar']);
    Route::put('/descuento2/activar', [ParDescuentoController::class, 'activar']);
    Route::put('/descuento2/actualizar', [ParDescuentoController::class, 'update']);
    Route::post('/descuento2/asignar', [ParDescuentoController::class, 'asignar']);
    Route::get('/descuento2/listarAsignar', [ParDescuentoController::class, 'listarAsignar']);
    Route::get('/descuento2/listarSucursalesX_descuentos', [ParDescuentoController::class, 'listarSucursalesX_descuentos']);
    Route::post('/descuento2/quitarSucursal_z', [ParDescuentoController::class, 'quitarSucursal_z']);
    Route::post('/descuento2/eliminacion_descuento', [ParDescuentoController::class, 'eliminacion_descuento']);    
       

    ////////////////////////productos//////////////////////////////
    Route::get('/linea', [ProdLineaController::class, 'index']);
    Route::post('/linea/registrar', [ProdLineaController::class, 'store']);
    Route::put('/linea/actualizar', [ProdLineaController::class, 'update']);
    Route::put('/linea/desactivar', [ProdLineaController::class, 'desactivar']);
    Route::put('/linea/activar', [ProdLineaController::class, 'activar']);
    Route::get('/linea/selectlinea', [ProdLineaController::class, 'selectLinea']);
    Route::get('/linea/selectlinea2', [ProdLineaController::class, 'selectLinea2']);
    Route::get('/linea/codigolinea', [ProdLineaController::class, 'returnCodigoLiniea']);

    Route::get('/producto', [ProdProductoController::class, 'index']);
    Route::post('/producto/registrar', [ProdProductoController::class, 'store']);
    Route::post('/producto/actualizar', [ProdProductoController::class, 'update']);
    Route::put('/producto/desactivar', [ProdProductoController::class, 'desactivar']);
    Route::put('/producto/activar', [ProdProductoController::class, 'activar']);
    Route::get('/producto/selectproducto', [ProdProductoController::class, 'selectProducto']);
    Route::get('/producto/selectproducto2', [ProdProductoController::class, 'selectProducto2']);
    Route::get('/producto/getProductosTiendaAlamcenEnvase', [ProdProductoController::class, 'getProductosTiendaAlamcenEnvase']);
    Route::get('/producto/getProductosTiendaEnvase', [ProdProductoController::class, 'getProductosTiendaEnvase']);
    Route::get('/producto/selectproductoperecedero', [ProdProductoController::class, 'selectProductoPerecedero']);
   
   //------------------------------lista de productos 
    Route::get('/lista/listarSucursal', [ProdListaController::class, 'listarSucursal']); 
    Route::post('/lista/registrar', [ProdListaController::class, 'store']);
    Route::get('/lista', [ProdListaController::class, 'index']); 
    Route::put('/lista/actualizar', [ProdListaController::class, 'update']);
    Route::put('/lista/desactivar', [ProdListaController::class, 'desactivar']);
    Route::put('/lista/activar', [ProdListaController::class, 'activar']);
       

    Route::get('/producto/listarSucursal', [ProdRegistroPreXListController::class, 'listarSucursal']); 
    Route::get('/producto/listarProducto', [ProdRegistroPreXListController::class, 'listarProducto']);
    Route::get('/producto/listarLista', [ProdRegistroPreXListController::class, 'listarLista']);    
    Route::get('/producto/listarProductoRetorno', [ProdRegistroPreXListController::class, 'listarProductoRetorno']);
    Route::post('/producto/registrarLista', [ProdRegistroPreXListController::class, 'store']);
    Route::get('/producto/index', [ProdRegistroPreXListController::class, 'index']); 
    Route::put('/producto/desactivarListaX', [ProdRegistroPreXListController::class, 'desactivar']);
    Route::put('/producto/activarListaX', [ProdRegistroPreXListController::class, 'activar']);
    Route::put('/producto/actualizarListaX', [ProdRegistroPreXListController::class, 'update']);
    //------------------------fin de lista de prodcutos

    Route::get('/dispenser', [ProdDispenserController::class, 'index']);
    Route::post('/dispenser/registrar', [ProdDispenserController::class, 'store']);
    Route::put('/dispenser/actualizar', [ProdDispenserController::class, 'update']);
    Route::put('/dispenser/desactivar', [ProdDispenserController::class, 'desactivar']);
    Route::put('/dispenser/activar', [ProdDispenserController::class, 'activar']);
    Route::get('/dispenser/selectdispenser', [ProdDispenserController::class, 'selectDispenser']);
    Route::get('/dispenser/selectdispenser2', [ProdDispenserController::class, 'selectDispenser2']);  

    Route::get('/formafarm', [ProdFormaFarmaceuticaController::class, 'index']);
    Route::post('/formafarm/registrar', [ProdFormaFarmaceuticaController::class, 'store']);
    Route::put('/formafarm/actualizar', [ProdFormaFarmaceuticaController::class, 'update']);
    Route::put('/formafarm/desactivar', [ProdFormaFarmaceuticaController::class, 'desactivar']);
    Route::put('/formafarm/activar', [ProdFormaFarmaceuticaController::class, 'activar']);
    Route::get('/formafarm/selectformafarm', [ProdFormaFarmaceuticaController::class, 'selectFormaFarm']);
    Route::get('/formafarm/selectformafarm2', [ProdFormaFarmaceuticaController::class, 'selectFormaFarm2']);

    Route::get('/categoria', [ProdCategoriaController::class, 'index']);
    Route::post('/categoria/registrar', [ProdCategoriaController::class, 'store']);
    Route::put('/categoria/actualizar', [ProdCategoriaController::class, 'update']);
    Route::put('/categoria/desactivar', [ProdCategoriaController::class, 'desactivar']);
    Route::put('/categoria/activar', [ProdCategoriaController::class, 'activar']);
    Route::get('/categoria/selectcategoria', [ProdCategoriaController::class, 'selectCategoria']);
    Route::get('/categoria/selectcategoria2', [ProdCategoriaController::class, 'selectCategoria2']);

    Route::get('/tipoentrada', [ProdTipoEntradaController::class, 'index']);
    Route::post('/tipoentrada/registrar', [ProdTipoEntradaController::class, 'store']);
    Route::put('/tipoentrada/actualizar', [ProdTipoEntradaController::class, 'update']);
    Route::put('/tipoentrada/desactivar', [ProdTipoEntradaController::class, 'desactivar']);
    Route::put('/tipoentrada/activar', [ProdTipoEntradaController::class, 'activar']);

    Route::get('/estante', [AlmCodificacionController::class, 'index']);
    Route::post('/estante/registrar', [AlmCodificacionController::class, 'store']);
    Route::put('/estante/actualizar', [AlmCodificacionController::class, 'update']);
    Route::put('/estante/desactivar', [AlmCodificacionController::class, 'desactivar']);
    Route::put('/estante/activar', [AlmCodificacionController::class, 'activar']);
    Route::get('/estante/selectestante', [AlmCodificacionController::class, 'selectEstante']);


    Route::get('/imprimir_codigos', [AlmCodificacionController::class, 'imprimirCodigos']);

    Route::get('/almacen', [AlmAlmacenController::class, 'index']);
    Route::post('/almacen/registrar', [AlmAlmacenController::class, 'store']);
    Route::put('/almacen/actualizar', [AlmAlmacenController::class, 'update']);
    Route::put('/almacen/desactivar', [AlmAlmacenController::class, 'desactivar']);
    Route::put('/almacen/activar', [AlmAlmacenController::class, 'activar']);
    Route::get('/almacen/listaralmacenes', [AlmAlmacenController::class, 'listaralmacenes']);
    

    //Ingreso de productos al almacen nuevo 2
    Route::get('/almacen/listaAlmacen2', [AlmIngresoProducto2Controller::class, 'listaAlmacen']);
    Route::get('/almacen/listarProductos_almacen', [AlmIngresoProducto2Controller::class, 'listarProductos_almacen']);
    Route::post('/almacen/ingreso-producto2/registrar', [AlmIngresoProducto2Controller::class, 'store']);
    Route::get('/almacen/index', [AlmIngresoProducto2Controller::class, 'index']);
    Route::put('/almacen/ingreso-producto2/update', [AlmIngresoProducto2Controller::class, 'update']);
    Route::put('/almacen/ingreso-producto2/desactivar', [AlmIngresoProducto2Controller::class, 'desactivar']);
    Route::put('/almacen/ingreso-producto2/activar', [AlmIngresoProducto2Controller::class, 'activar']);

    //antiguo---
    Route::get('/almacen/ingreso-producto', [AlmIngresoProductoController::class, 'index']);
    Route::post('/almacen/ingreso-producto/registrar', [AlmIngresoProductoController::class, 'store']);
    Route::put('/almacen/ingreso-producto/actualizar', [AlmIngresoProductoController::class, 'update']);
    Route::put('/almacen/ingreso-producto/desactivar', [AlmIngresoProductoController::class, 'desactivar']);
    Route::put('/almacen/ingreso-producto/activar', [AlmIngresoProductoController::class, 'activar']);
    Route::get('/almacen/ingreso-producto/retornarProductosIngreoAlmacen', [AlmIngresoProductoController::class, 'retornarProductosIngreoAlmacen']);   

    Route::get('/tipodescuento/selecttipodescuento', [ProdTipoDescuentoController::class, 'selectTipoDescuento']);

    Route::get('/proddescuento', [ProdDescuentoController::class, 'index']);
    Route::post('/proddescuento/registrar', [ProdDescuentoController::class, 'store']);
    Route::put('/proddescuento/actualizar', [ProdDescuentoController::class, 'update']);
    Route::put('/proddescuento/desactivar', [ProdDescuentoController::class, 'desactivar']);
    Route::put('/proddescuento/activar', [ProdDescuentoController::class, 'activar']);
    Route::get('/proddescuento/selectdescuento', [ProdDescuentoController::class, 'selectDescuento']);

    Route::post('/ventamaestro/registrarventamaestro', [SerVentaMaestroController::class, 'store']);
    Route::get('/ventasmaestro', [SerVentaMaestroController::class, 'index']);
    Route::put('/ventasmaestro/desactivar', [SerVentaMaestroController::class, 'desactivar']);
    Route::put('/ventasmaestro/registrarventa', [SerVentaMaestroController::class, 'activar']);
   

    //Tienda--------------------------------------------------------------------------------

    //nuevo
    Route::get('/tienda/listaTienda2', [TdaIngresoProducto2Controller::class, 'listaTienda']);
    Route::get('/tienda/index', [TdaIngresoProducto2Controller::class, 'index']);
    Route::get('/tienda/listarProductos_tienda', [TdaIngresoProducto2Controller::class, 'listarProductos_tienda']);
    Route::post('/tienda/ingreso-producto2/registrar', [TdaIngresoProducto2Controller::class, 'store']);
    Route::put('/tienda/ingreso-producto2/update', [TdaIngresoProducto2Controller::class, 'update']);
    Route::put('/tienda/ingreso-producto2/desactivar', [TdaIngresoProducto2Controller::class, 'desactivar']);
    Route::put('/tienda/ingreso-producto2/activar', [TdaIngresoProducto2Controller::class, 'activar']);
    //antiguo
    Route::get('/tienda', [TdaTiendaController::class, 'index']);
    Route::put('/tienda/desactivar', [TdaTiendaController::class, 'desactivar']);
    Route::put('/tienda/activar', [TdaTiendaController::class, 'activar']);
    Route::get('/tienda/ingreso-producto', [TdaIngresoProductoController::class, 'index']);
    Route::post('/tienda/ingreso-producto/registrar', [TdaTiendaController::class, 'store']);
    Route::put('/tienda/ingreso-producto/actualizar', [TdaIngresoProductoController::class, 'update']);
    Route::put('/tienda/ingreso-producto/desactivar', [TdaIngresoProductoController::class, 'desactivar']);
    Route::put('/tienda/ingreso-producto/activar', [TdaIngresoProductoController::class, 'activar']);
   
    //////////////////////////////// Gestion Precio Venta//////////////////////////////////////////
        //---endpoint Inge.Helio
    Route::post('/gestionprecioventa/actualizar-registrar', [GesPreVentaController::class, 'update_store']);
    Route::get('/gestionprecioventa/verificarProductoConPrecio', [GesPreVentaController::class, 'verificarProductoPrecio']);
        //---endpoint Inge.Remberto
    Route::get('/gestionprecioventa2/listarSucursal', [GesPreVenta2Controller::class, 'listarSucursal']);
    Route::get('/gestionprecioventa2', [GesPreVenta2Controller::class, 'index']);
    Route::post('/gestionprecioventa2/registrar', [GesPreVenta2Controller::class, 'store']);
    Route::post('/gestionprecioventa2/actualizar', [GesPreVenta2Controller::class, 'update']);
    Route::get('/gestionprecioventa2/listarLista', [GesPreVenta2Controller::class, 'listarLista']);  
      
   
    /////////////////////////////////////////////Inventario///////////////////////////////////////////////////
    
    Route::get('/ajustes-negativo', [InvAjusteNegativoController::class, 'index']);
    Route::get('/ajustes-negativo/listarTipo', [InvAjusteNegativoController::class, 'listarTipo']);
    Route::get('/ajustes-negativo/listarProductoLineaIngreso', [InvAjusteNegativoController::class, 'listarProductoLineaIngreso']);
    Route::post('/ajustes-negativo/registrar', [InvAjusteNegativoController::class, 'store']);
    Route::put('/ajustes-negativo/actualizar', [InvAjusteNegativoController::class, 'update']);
    Route::put('/ajustes-negativo/desactivar', [InvAjusteNegativoController::class, 'desactivar']);
    Route::put('/ajustes-negativo/activar', [InvAjusteNegativoController::class, 'activar']);
    Route::get('/ajustes-negativo/listarSucursal', [InvAjusteNegativoController::class, 'listarSucursal']);
    Route::get('/ajustes-negativo/retornarProductosIngreso', [InvAjusteNegativoController::class, 'retornarProductosIngreso']);

    Route::get('/ajustes-positivo', [InvAjustePositivoController::class, 'index']);
    Route::get('/ajustes-positivo/listarTipo', [InvAjustePositivoController::class, 'listarTipo']);
    Route::get('/ajustes-positivo/listarProductoLineaIngreso', [InvAjustePositivoController::class, 'listarProductoLineaIngreso']);
    Route::post('/ajustes-positivo/registrar', [InvAjustePositivoController::class, 'store']);
    Route::put('/ajustes-positivo/actualizar', [InvAjustePositivoController::class, 'update']);
    Route::put('/ajustes-positivo/desactivar', [InvAjustePositivoController::class, 'desactivar']);
    Route::put('/ajustes-positivo/activar', [InvAjustePositivoController::class, 'activar']);
    Route::get('/ajustes-positivo/listarSucursal', [InvAjustePositivoController::class, 'listarSucursal']);
    Route::get('/ajustes-positivo/retornarProductosIngreso', [InvAjustePositivoController::class, 'retornarProductosIngreso']);
    Route::get('/ajustes-positivo/retornarProductosIngresoCero', [InvAjustePositivoController::class, 'retornarProductosIngresoCero']);
    Route::put('/ajustes-positivo/actualizarTdaAlm', [InvAjustePositivoController::class, 'updateTdaAlm']);   

    Route::get('/traspaso', [InvTraspasoController::class, 'index']);
    Route::get('/traspaso/listarSucursal', [InvTraspasoController::class, 'listarSucursal']);
    Route::get('/traspaso/listarProductoLineaIngreso', [InvTraspasoController::class, 'listarProductoLineaIngreso']);
    Route::post('/traspaso/registrar', [InvTraspasoController::class, 'store']);
    Route::put('/traspaso/desactivar', [InvTraspasoController::class, 'desactivar']);
    Route::put('/traspaso/activar', [InvTraspasoController::class, 'activar']);
    Route::get('/traspaso/retornarProductosIngreso', [InvTraspasoController::class, 'retornarProductosIngreso']);
    Route::put('/traspaso/actualizar', [InvTraspasoController::class, 'update']);
    Route::put('/traspaso/activarListo', [InvTraspasoController::class, 'activarListo']);
    Route::put('/traspaso/desactivarListo', [InvTraspasoController::class, 'desactivarListo']);

    Route::get('/recepcion', [InvRecepcionController::class, 'index']);
    Route::get('/recepcion/listarSucursal', [InvRecepcionController::class, 'listarSucursal']);   
    Route::get('/recepcion/listarTraspaso', [InvRecepcionController::class, 'listarTraspaso']); 
    Route::get('/recepcion/listarRetornoTraspaso', [InvRecepcionController::class, 'listarRetornoTraspaso']); 
    Route::post('/recepcion/registrar', [InvRecepcionController::class, 'store']);
    Route::put('/recepcion/actualizar', [InvRecepcionController::class, 'update']); 
    Route::put('/recepcion/desactivar', [InvRecepcionController::class, 'desactivar']);
    Route::put('/recepcion/activar', [InvRecepcionController::class, 'activar']);
   
    Route::get('/procesar-traspaso', [InvProcesarTraspasoController::class, 'index']);
    Route::get('/procesar-traspaso/listarSucursal', [InvProcesarTraspasoController::class, 'listarSucursal']);
    Route::get('/procesar-traspaso/listarUsuario', [InvProcesarTraspasoController::class, 'listarUsuario']);
    /////////////////////////////////LOGISTICO///////////////////////////////////////
    Route::get('/vehiculo/listarSucursal', [LogVehiculoController::class, 'listarSucursal']);
    Route::get('/vehiculo/listarUsuario', [LogVehiculoController::class, 'listarUsuario']);
    Route::post('/vehiculo/registrar', [LogVehiculoController::class, 'store']);
    Route::get('/vehiculo', [LogVehiculoController::class, 'index']);
    Route::put('/vehiculo/desactivar', [LogVehiculoController::class, 'desactivar']);
    Route::put('/vehiculo/activar', [LogVehiculoController::class, 'activar']);
    Route::put('/vehiculo/actualizar', [LogVehiculoController::class, 'update']);
    Route::post('/vehiculo/asignar', [LogVehiculoController::class, 'asignar']);
    Route::get('/vehiculo/listarAsignar', [LogVehiculoController::class, 'listarAsignar']);
    
    Route::get('/traslado/listarSucursal', [LogTrasladoController::class, 'listarSucursal']);    
    Route::get('/traslado/listarTraspaso', [LogTrasladoController::class, 'listarTraspaso']);  
    Route::get('/traslado/listarUsuario', [LogTrasladoController::class, 'listarUsuario']);  
    Route::get('/traslado/listarVehiculo', [LogTrasladoController::class, 'listarVehiculo']); 
    Route::get('/traslado/listarRetornoTraspaso', [LogTrasladoController::class, 'listarRetornoTraspaso']); 
    Route::post('/traslado/registrar', [LogTrasladoController::class, 'store']); 
    Route::get('/traslado/repetidor', [LogTrasladoController::class, 'repetidor']); 
    Route::get('/traslado', [LogTrasladoController::class, 'index']); 
    Route::put('/traslado/desactivar', [LogTrasladoController::class, 'desactivar']);
    Route::put('/traslado/activar', [LogTrasladoController::class, 'activar']);
    Route::put('/traslado/actualizar', [LogTrasladoController::class, 'update']);

    //////////////////////////////////////////////DIRECTORIO///////////////////////////////////////////////////   
    //---------cliente
    Route::post('/directorio/registrar', [DirClienteController::class, 'store']);
    Route::get('/directorio', [DirClienteController::class, 'index']); 
    Route::put('/directorio/desactivar', [DirClienteController::class, 'desactivar']);
    Route::put('/directorio/activar', [DirClienteController::class, 'activar']);
    Route::put('/directorio/actualizar', [DirClienteController::class, 'update']);
    //---------proveedor
    Route::get('/proveedor/get', [DirProveedorController::class, 'cliente']);
    Route::post('/proveedor/registrar', [DirProveedorController::class, 'store']);    
    Route::get('/proveedor/listarProveedor', [DirProveedorController::class, 'index']);
    Route::post('/proveedor/editar', [DirProveedorController::class, 'update']);  
    Route::put('/proveedor/desactivar', [DirProveedorController::class, 'desactivar']);  
    Route::put('/proveedor/activar', [DirProveedorController::class, 'activar']);  
    //---------distribuidor
    Route::get('/distribuidor/getLinea', [DirDistribuidorController::class, 'listarLinea']); 
    Route::post('/distribuidor/registrar', [DirDistribuidorController::class, 'store']);   
    Route::get('/distribuidor/listarDistribuidor', [DirDistribuidorController::class, 'index']);
    Route::post('/distribuidor/editar', [DirDistribuidorController::class, 'update']);  
    Route::put('/distribuidor/desactivar', [DirDistribuidorController::class, 'desactivar']);  
    Route::put('/distribuidor/activar', [DirDistribuidorController::class, 'activar']); 
     
    /////////////////////////////////////////////////VENTAS_PRODCUTOS///////////////////////////////////////////////////////    
    Route::get('/gestor_ventas/listarUsuario', [VenGestorVentaController::class, 'listarUsuario']);
    Route::get('/gestor_ventas/listarUsuarioRetorno', [VenGestorVentaController::class, 'listarUsuarioRetorno']);
    Route::get('/gestor_ventas/get_sucusal', [VenGestorVentaController::class, 'get_sucusal']);
    Route::get('/gestor_ventas/listarDescuentos_listas', [VenGestorVentaController::class, 'listarDescuentos_listas']);
    Route::get('/gestor_ventas/listarDescuento_Tipo_tabla', [VenGestorVentaController::class, 'listarDescuento_Tipo_tabla']); 
    Route::post('/gestor_ventas/venta', [VenGestorVentaController::class, 'venta']);
    Route::get('/gestor_ventas/venta/pdf', [VenGestorVentaController::class, 'venta']);
    Route::get('/gestor_ventas/venta/pdf2', [VenGestorVentaController::class, 'mostrarPDF']);
    Route::get('/gestor_ventas/verificador_dosificacion_o_facturacion', [VenGestorVentaController::class, 'verificador_dosificacion_o_facturacion']);    
    Route::get('/gestor_ventas/tieneApertura', [VenGestorVentaController::class, 'tieneApertura']);
    Route::get('/gestor_ventas/get_producto_bloque', [VenGestorVentaController::class, 'get_producto_bloque']);    

    //mostrar venta,re-imprimir,anular
    Route::get('/detalle_venta_2/index', [VenGestorVentaVistaController::class, 'index']);
    Route::get('/detalle_venta_2/re_imprecion',[VenGestorVentaVistaController::class, 're_imprecion']);
    Route::get('/detalle_venta_2/verCliente_x_venta', [VenGestorVentaVistaController::class, 'verCliente_x_venta']);
    Route::post('/detalle_venta_2/desactivar', [VenGestorVentaVistaController::class, 'desactivar']);
    Route::get('/detalle_venta_2/factura_dosificacion', [VenGestorVentaVistaController::class, 'factura_dosificacion']); 
    
    //caducidad---
    Route::get('/caducida/index', [VenCaducidadController::class, 'index']);
    Route::put('/caducida/prioridad', [VenCaducidadController::class, 'prioridad']); 
    Route::post('/caducida/darDeBaja', [VenCaducidadController::class, 'darDeBaja']);

    //////////////////////////////////////////////////CAJA/////////////////////////////////////////////////////////////////////
    
    //moneda--
    Route::get('/moneda/listarNacionalidad', [CajaMonedaController::class, 'listarNacionalidad']);
    Route::get('/moneda/index', [CajaMonedaController::class, 'index']);
    Route::post('/moneda/store', [CajaMonedaController::class, 'store']);
    Route::post('/moneda/actualizar', [CajaMonedaController::class, 'actualizar']); 
    Route::post('/moneda/activar', [CajaMonedaController::class, 'activar']); 
    Route::post('/moneda/desactivar', [CajaMonedaController::class, 'desactivar']);    

    //Apertura_cierre
    Route::get('/apertura_cierre/verificador_moneda_sistemas', [CajaAperturaCierreController::class, 'verificador_moneda_sistemas']);  
    Route::get('/apertura_cierre/cajaAnteriror', [CajaAperturaCierreController::class, 'cajaAnteriror']); 
    Route::post('/apertura_cierre/store', [CajaAperturaCierreController::class, 'store']);  
    Route::get('/apertura_cierre/index', [CajaAperturaCierreController::class, 'index']);  
    Route::get('/apertura_cierre/monedaModal', [CajaAperturaCierreController::class, 'monedaModal']);  
    Route::post('/apertura_cierre/cierre', [CajaAperturaCierreController::class, 'cierre_store']);  
    Route::get('/apertura_cierre/get_operacion', [CajaAperturaCierreController::class, 'suma_operacion_v2']); 

    //entrada_salida
    Route::post('/entrada_salida/store', [CajaEntradaSalidaController::class, 'store']); 
    Route::get('/entrada_salida/index', [CajaEntradaSalidaController::class, 'index']); 
    Route::post('/entrada_salida/validate-password', [CajaEntradaSalidaController::class, 'validatePassword']);
    Route::get('/entrada_salida/getmoneda', [CajaEntradaSalidaController::class, 'getmoneda']); 
    Route::get('/entrada_salida/monedaModal_vista', [CajaEntradaSalidaController::class, 'monedaModal_vista']);   
    Route::get('/entrada_salida/getlimite', [CajaEntradaSalidaController::class, 'listar_limite']);  
    
    //transaccion-----
    Route::get('/transaccion/cuenta_salida', [CajaTransaccionController::class, 'get_cuenta_salida']); 
    Route::post('/transaccion/registrar', [CajaTransaccionController::class, 'store']);  
    Route::get('/transaccion/listar_', [CajaTransaccionController::class, 'index']); 
    Route::post('/transaccion/editar', [CajaTransaccionController::class, 'update']);  
    Route::get('/transaccion/show', [CajaTransaccionController::class, 'show']);   
    
    //crear cajas------
    Route::post('/caja_crear/registrar', [CajaCreacionController::class, 'store']);

    //////////////////////////////////////////////////EGRESOS///////////////////////////////////////////////////
    
    //inversiones-----
    Route::get('/inversion/getCliente', [EgrInversionController::class, 'listarDistribuidor']); 
    Route::post('/inversion/crear', [EgrInversionController::class, 'store']);  
    Route::get('/inversion/listarInicio', [EgrInversionController::class, 'index']); 
    Route::post('/inversion/editar', [EgrInversionController::class, 'update']); 
    Route::put('/inversion/activar', [EgrInversionController::class, 'activar']); 
    Route::put('/inversion/desactivar', [EgrInversionController::class, 'desactivar']); 
    Route::get('/inversion/verproducto', [EgrInversionController::class, 'getProducto']); 
    Route::get('/inversion/producto_array', [EgrInversionController::class, 'getProducto_array']);    
       
    //gastos--------
    Route::get('/gasto/getCliente', [EgrGastoController::class, 'listarProveedor']); 
    Route::post('/gasto/crear', [EgrGastoController::class, 'store']);  
    Route::get('/gasto/listarInicio', [EgrGastoController::class, 'index']); 
    Route::post('/gasto/editar', [EgrGastoController::class, 'update']); 
    Route::put('/gasto/activar', [EgrGastoController::class, 'activar']); 
    Route::put('/gasto/desactivar', [EgrGastoController::class, 'desactivar']); 

    //tesoreria----------
    Route::get('/tesoreria/getTesoreria', [EgrTesoreriaController::class, 'getTesoreria']);
    Route::post('/tesoreria/crear', [EgrTesoreriaController::class, 'store']); 
    Route::get('/tesoreria/listarInicio', [EgrTesoreriaController::class, 'index']); 
    Route::post('/tesoreria/editar', [EgrTesoreriaController::class, 'update']);  
    
    
});
