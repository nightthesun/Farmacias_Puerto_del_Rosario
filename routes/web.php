<?php

use App\Http\Controllers\AdmAccionVentanaController;
use App\Http\Controllers\AdmBancoController;
use App\Http\Controllers\AdmCiudadController;
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
use App\Http\Controllers\AlmIngresoProductoController;
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

    //Ingreso de productos al almacen
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

    //Tienda
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
   
});
