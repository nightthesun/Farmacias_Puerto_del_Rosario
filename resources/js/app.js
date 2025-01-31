require('./bootstrap');
window._pl = require('./func_10251');
import { createApp } from 'vue';

createApp ({
    data:()=>{
        return{
            menu:0
        }
        
    }
})
    .component('principal-component',require('./components/Principal.vue').default)
    .component('Body_header', require('./components/plugin_vue/Body_header.vue').default)
    .component('example',require('./components/Example.vue').default)
    .component('another-example',require('./components/AnotherExample.vue').default)
    
    //administracion
    .component('rubros-component',require('./components/administracion/RubrosComponent.vue').default)
    .component('sucursal-component',require('./components/administracion/SucursalComponent.vue').default)
    .component('modulo-component',require('./components/administracion/ModuloVentanaAccionComponent.vue').default)
    .component('rolpermiso-component',require('./components/administracion/RolPermisoComponent.vue').default)
    .component('usuario-component',require('./components/administracion/UsuariosComponent.vue').default)
    .component('configuracion-component',require('./components/administracion/ConfiguracionComponent.vue').default)
    .component('dofisicacion-component',require('./components/administracion/DosificacionComponent.vue').default)
    //rrhh
    .component('rrhempleados-component',require('./components/recursos_humanos/EmpleadosComponent.vue').default)
    .component('rrhnivel-component',require('./components/recursos_humanos/NivelComponent.vue').default)
    .component('rrhprofesion-component',require('./components/recursos_humanos/ProfesionComponent.vue').default)
    .component('rrhcargos-component',require('./components/recursos_humanos/CargoComponent.vue').default)
    .component('rrhuorg-component',require('./components/recursos_humanos/UnidadOrgComponent.vue').default)


    //Servicios
    .component('area-component',require('./components/servicios/AreaComponent.vue').default)
    .component('prestaciones-component',require('./components/servicios/PrestacionComponent.vue').default)
    .component('ventas-component',require('./components/servicios/VentasComponent.vue').default)
    .component('histventas-component', require('./components/servicios/HistorialVentasComponent.vue').default)

    //Parametros
    .component('descuentos-component',require('./components/parametros/ServiciosDescuentoscomponent.vue').default)
    .component('descproductos-component', require('./components/parametros/DescProductosComponent.vue').default)
    .component('descuentosx-component', require('./components/parametros/DescuentosxComponent.vue').default)
    
    //Productos
    .component('linea-component', require('./components/productos/LineaComponent.vue').default)
    .component('producto-component', require('./components/productos/ProductoComponent.vue').default)
    .component('dispenser-component', require('./components/productos/DispenserComponent.vue').default)
    .component('formafarm-component', require('./components/productos/FormaFarmaceuticaComponent.vue').default)
    .component('categoria-component', require('./components/productos/CategoriaComponent.vue').default)
    .component('tipo-entrada-component', require('./components/productos/TipoEntradaComponent.vue').default)
    .component('lista-component', require('./components/productos/ListaComponent.vue').default)
    .component('reg-pre-x-lista-component', require('./components/productos/PrecioxlistaComponent.vue').default)
    
    //Almacen
    .component('codificacion-component', require('./components/almacenes/CodificacionComponent.vue').default)
    .component('almacen-component', require('./components/almacenes/AlmacenComponent.vue').default)
    .component('ingreso-producto-component', require('./components/almacenes/IngresoProductoComponent.vue').default)
    .component('nuevo-almacen-component', require('./components/almacenes/NuevoAlmacenComponent.vue').default)
    .component('nuevo-almacen2-component', require('./components/almacenes/IngresoProducto2Component.vue').default)
   
    
    //Tienda
    .component('tienda-component', require('./components/tienda/TiendaComponent.vue').default)
    .component('tienda-ingreso-producto-component', require('./components/tienda/TiendaIngresoProductoComponent.vue').default)
    .component('tienda-codificacion-component', require('./components/tienda/TiendaCodificacionComponent.vue').default)
    .component('tienda-ingreso-producto2-component', require('./components/tienda/TiendaIngresoProducto2Component.vue').default)
    
    //Gestion de precios
    .component('precio-venta-component', require('./components/gestion_precios/PrecioVentaComponent.vue').default)
    .component('precio-venta-component2', require('./components/gestion_precios/PrecioVenta2Component.vue').default)
    
    //Inventario 
    .component('ajuste-negativo-component', require('./components/inventario/AjusteNegativoComponent.vue').default)
    .component('ajuste-positivo-component', require('./components/inventario/AjustePositivoComponent.vue').default)
    .component('traspaso-component', require('./components/inventario/TraspasoComponent.vue').default)
    .component('procesar-traspasos-component', require('./components/inventario/ProcesarTraspasoComponent.vue').default)
    .component('recepcion-traspasos-component', require('./components/inventario/RecepcionComponent.vue').default)
    //Logistica
    .component('vehiculo-component', require('./components/logistica/VehiculoComponent.vue').default)
    .component('traslado-component', require('./components/logistica/TrasladosComponent.vue').default)
    //Directorio
    .component('cliente-component', require('./components/directorio/ClienteComponent.vue').default)
    .component('proveedor-component', require('./components/directorio/ProveedorComponent.vue').default)
    .component('distribuidor-component', require('./components/directorio/DistribuidorComponent.vue').default)  
    //Ventas
    .component('gestor-venta-component', require('./components/venta/GestorVentaComponent.vue').default)
    .component('venta-rapida-component', require('./components/venta/VentaRapidaComponent.vue').default)
    .component('venta-detalle-component', require('./components/venta/VentaDetalleComponent.vue').default)
    .component('caducidad-component', require('./components/venta/CaducidadComponent.vue').default)
    //Cajas
    .component('apertura_cierre-component', require('./components/caja/Apertura_cierreComponent.vue').default)
    .component('moneda-component', require('./components/caja/MonedaComponent.vue').default)
    .component('entrada_salida-component', require('./components/caja/Entrada_SalidaComponent.vue').default)
    .component('transaccion-component', require('./components/caja/TransaccionComponent.vue').default)
    .component('corregir_caja-component', require('./components/caja/Corregir_cajaComponent.vue').default)
    .component('crear_caja-component', require('./components/caja/Crear_cajaComponent.vue').default)       
    //compras
    .component('gastos-component', require('./components/egreso/GastosComponent.vue').default)
    .component('inversiones-component', require('./components/egreso/InversionesComponent.vue').default)
    .component('tesoreria-component', require('./components/egreso/TesoreriaComponent.vue').default)
    //siat
    .component('siat-configuracion-component', require('./components/siat/Configuracion_siatComponent.vue').default)
    .component('siat-sucursal-component', require('./components/siat/Sucursal_siatComponent.vue').default)
    .component('siat-administracion-component', require('./components/siat/Administracion_siatComponent.vue').default)
    .component('siat-emisor-component', require('./components/siat/Emisor_siatComponent.vue').default)

    .mount('#app');
 