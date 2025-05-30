<template>
    <div>
        <div v-if="loading" class="loading-screen">
            
      <img src="/img/logo.png" alt="Cargando..." class="loading-image" />
          <!-- También puedes agregar un spinner en lugar de una imagen -->
        </div>
        <div v-else>
          <div v-if="tieneApertura_0===0">
            <main class="main">           
                  Sin apertura. debe realizar una apartura primero.                            
             </main>              
          </div>
          <div v-else>            
          <!-- Contenido de tu aplicación que se mostrará después de cargar -->
          <main class="main">
            <!-- Breadcrumb -->
                 <ol class="breadcrumb">
                     <li class="breadcrumb-item">Home</li>
                     <li class="breadcrumb-item"><a href="#">Admin</a></li>
                     <li class="breadcrumb-item active">Dashboard</li>
                 </ol>
                 <!-- inicio de index -->
                 <div class="container-fluid">
                     <div class="card">
                         <div class="card-header d-flex flex-wrap align-items-center">
                             <div style="margin-right: 50px;">
                                 <span class="flex-grow-1">Venta de rápida</span>
                             </div>
                            
                             <div class="d-flex flex-wrap align-items-center justify-content-end">
                               <div class="mr-2 mb-2">
                                 <span class="badge badge-pill badge-primary">{{"Lista: "+ nom_lista }}</span>
                               </div>
                               <div class="mr-2 mb-2" v-if="existe_cliente===1">
                                <span  class="badge badge-pill badge-success">Descuento: Cliente</span>
                              </div>
                              <div class="mr-2 mb-2" v-if="existe_producto===1">
                                <span class="badge badge-pill badge-danger">Descuento: Producto</span> 
                              </div>
                               
                               <div v-if="arrayDescuento.length > 0" class="d-flex flex-wrap align-items-center">
                                 <div v-for="d in arrayDescuento" :key="d.id" class="mr-2 mb-2">
                                    
                                     <span v-if="d.id_tabla == 1" class="badge badge-pill badge-warning">{{ "Descuento: "+d.nombre_descuento }}</span>
                                     <span v-if="d.id_tabla == 2" class="badge badge-pill badge-secondary">{{ "Descuento: "+d.nombre_descuento }}</span>
                                                               
                                     <span v-if="d.id_tabla == 5" class="badge badge-pill badge-dark" style="background-color: darkmagenta;">{{ "Descuento: "+d.nombre_descuento }}</span>      
                                
                                     <span v-if="d.id_tabla == 6" class="badge badge-pill badge-dark" style="background-color: chocolate;">{{ "Descuento: "+d.nombre_descuento }}</span>
                               
                                  
                                 </div>
                               </div>
                               <div v-else class="ml-2 mb-2">
                                 <span class="badge badge-pill badge-dark">{{ descuentoNombre }}</span>
                               </div>

                             </div>
                           </div>
                           
                         
                         
                 <div class="card-body" style="padding-top: 5px; padding-bottom: 1px;" >
                     <div class="form-group row" >
                        
                   
         
                         <div class="card w-100" style=" border-left: 3px solid #04660c; padding-bottom: 2px;">
         
                             <div class="card-body" style="margin-top: -1rem; margin-bottom: -1rem;">
                               <strong >Detalle de producto</strong> 
                               <hr> <!-- Línea horizontal -->                      
                               <div class="row" >
                             
                                 <table class="table table-bordered table-striped table-sm table-responsive">
           <thead>
             <tr>
               <th class="col-md-6" >Producto</th>
               <th class="col-md-1">P/U</th>
               <th class="col-md-1">Stock</th>
               <th class="col-md-1">F. Ven</th>
               <th class="col-md-1">Descuento</th>
               <th class="col-md-1">Cantidad</th>
               <th class="col-md-1">Accion</th>
             </tr>
           </thead>
           <tbody>
             <tr>
               
                 <td class="col-md-6">
                     <div class="input-group">
                         <div class="w-100">
                             <VueMultiselect
                                 v-model="selected"
                                 :options="arrayProducto"
                                 :max-height="150"
                            
                                 :block-keys="['Tab', 'Enter']"
                                
                                 placeholder="Seleccione una opción"
                                 label="leyenda"
                                 :custom-label="nameWithLang"
                                 track-by="id"
                                 class="w-100"
                                 selectLabel="Añadir a seleccion"
                                 deselectLabel="Quitar seleccion"
                                 selectedLabel="Seleccionado"
         
                                 @select="limpiar_cantida_cantida();" 
                                >
                                <template #noResult>
                                 No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                               </template>
                               <template #option="{ option }">
                                 <div :class="{'red-day': option.dias <= 20}">
                                <i :style="{ color: getColorByPriority(option.prioridad_caducidad) }" class="fa fa-bell" aria-hidden="true"></i> 
                                {{option.leyenda}} {{option.nombre_linea}} {{ "FV: "+option.fecha_vencimiento}} {{ "Dias: "+option.dias}}
                                 </div>
                         </template>
                             </VueMultiselect>
                         </div>
                         
                         <span class="input-group-btn">
                             <a data-toggle="modal" href="#modal">
                                 <button  v-if="selected===null" class="btn btn-secondary" type="button"><i class="fa fa-eye"></i></button>
                                 <button  v-else  @click="abrirModal('vista_bloque');listarProducto_seleccionado()"  class="btn btn-warning" type="button"><i class="fa fa-eye"></i></button>                                 
                             </a>
                         </span>
                     </div>
                 </td>
                 
               <td class="col-md-1" >
                 <span  v-if="selected" v-text=" selected.precio_lista_gespreventa">
                 </span>
               </td>
               <td class="col-md-1" >
                 <span  v-if="selected" v-text="selected.stock_ingreso">
                 </span>
               </td>
               <td class="col-md-1" >
                 <span  v-if="selected" v-text=" selected.fecha_vencimiento">
                 </span>
               </td>
             
                 <td class="col-md-1">
                     <input v-show="validadorPersonal === 1" :disabled="selected===null " type="text" class="form-control" id="inlineFormInputName" v-model="descuento">
                     
                     <span v-show="validadorPersonal === 2 || validadorPersonal === 4 || validadorPersonal === 5 || validadorPersonal ===6 || validadorPersonal ===7 || validadorPersonal ===99" style="text-align: center;">
                        Automatico
                     </span>
                     <span v-show="validadorPersonal === 3" style="color: black; display: flex; justify-content: center; align-items: center; font-size: 24px; width: 100%;">
                        <i class="fa fa-user-times" aria-hidden="true"></i>
                    </span>                       
                  

                    <button v-show="validadorPersonal === 0" type="button" class="btn btn-success">Calcular</button>
                 </td>
          
               <td class="col-md-1">
                 <input type="text" class="form-control" id="inlineFormInputName" v-model="numero" @keydown="filtrarNumeros" >
               </td>
               <td class="col-md-1">
                    <div v-if="validadorPersonal === 1">
                        <button v-if="selected!==null && numero!=='' && descuento!=='' &&numero>0" type="button" class="btn btn-success btn-sm" style="margin-right: 5px;" @click="agregarVenta();">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            </button> 
                        <button v-else  type="button" class="btn btn-secondary  btn-sm" style="margin-right: 5px;">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>  
                    </div>
                    <div v-if="validadorPersonal === 3 || validadorPersonal === 2 || validadorPersonal === 4 || validadorPersonal === 5 || validadorPersonal === 6 || validadorPersonal ===7 || validadorPersonal === 99" >
                        <button v-if="selected!==null && numero!=='' &&numero>0" type="button" class="btn btn-success btn-sm" style="margin-right: 5px;" @click="agregarVenta();">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            </button> 
                        <button v-else  type="button" class="btn btn-secondary  btn-sm" style="margin-right: 5px;">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>  
                    </div>               
                            
               </td>
             </tr>   
           </tbody>
         </table>               
                              </div> 
                             </div>
                           </div>
         <!----------------------------------------inicio de tabla------------------------------------------------->
         
          <div class="card w-100" style="margin-top: -20px;">
           <div class="card-body" >
             <h5 class="card-title" >Lista de productos</h5>
             <div class="row" >
                 <table class="table table-bordered table-sm table-responsive table-primary ">
                     <thead>
                         <tr>
                             <th class="col-1">Opciones</th>
                             <th class="col-1">Linea</th>
                             <th class="col-5">Producto</th>
                             
                             <th class="col-1">Cantidad</th>
                             <th class="col-1">Precio de venta</th>
                             <th class="col-1">Descuento</th>
                             <th class="col-2" >Sub Total</th>
                          
                         </tr>
                     </thead>
                     <tbody>
                         <tr v-for="venta in arrayVentas" :key="venta.id">
                             <td class="col-1" style="text-align:center">
                                 <button type="button" class="btn btn-danger btn-sm" @click="quitarVEnta(venta.id_contador)">
                                     <i class="fa fa-minus" aria-hidden="true"></i>
                                 </button>
                             </td>
                             <td class="col-1" v-text="venta.nombre_linea"></td>
                             <td class="col-5" v-text="venta.leyenda"></td>
                             <td class="col-1" v-text="venta.cantidad" style="text-align:right"></td>
                             <td class="col-1" v-text="venta.precio" style="text-align:right"></td>
                             <td class="col-1" v-text="venta.descuento" style="text-align:right"></td>
                             <td class="col-2" v-text="venta.subtotal" style="text-align:right"></td>
                         </tr>
                         <tr>
                             <th colspan="6" style="text-align:right">Suma Total:</th>
                             <th v-text="parseFloat(sumatotal).toFixed(2) + ' Bs.'" style="text-align:right"></th>
                         </tr>
        
                         <tr>
                            <th colspan="6" style="text-align:right">Descuento:</th>
                            <th v-text="parseFloat(descuento_final).toFixed(2) + ' Bs.'" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right">Total:</th>
                            <th v-text="parseFloat(tota_del_total).toFixed(2)  + ' Bs.'" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right">Monto GiftCard:</th>
                            <th><input type="number" v-model="gift_value" v-on:focus="selectAll"  :disabled="tipo_gift_===0" @keyup="reset_gift()" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right">Monto a pagar:</th>
                            <th v-text="parseFloat(monto_a_pagar).toFixed(2)  + ' Bs.'" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right">Importe base crédito fiscal:</th>
                            <th v-text="parseFloat(importe_fiscal).toFixed(2)  + ' Bs.'" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right">Efectivo:</th>
                            <th><input type="number" v-model="efectivo" v-on:focus="selectAll"  :disabled="sumatotal===0" @keyup="restartotal()" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right">Cambio:</th>
                            <th v-text="parseFloat(cambio).toFixed(2) + ' Bs.'" style="text-align:right"></th>
                        </tr>
                     </tbody>
            
                  
                 </table>
             </div>
             
           </div>
         </div>
         <!-------------------------------------------------------fin de tabla ------------------------------------------->
         
            
                                
         <div class="card w-100" style="border-left: 3px solid #f0ad4e;">
         
             <div class="card-body" >
               <strong >Detalle de cliente</strong> 
               <hr> <!-- Línea horizontal -->                               
               <div class="row">            
                 <div class="form-group col-sm-3" style="display: flex; align-items: center;" v-if="contador_cliente===0">
                     <input type="text" class="form-control"  placeholder="Número de Documento" v-model="buscarCliente">
                     <button class="btn btn-primary" type="button" 
                             @click="listarUsuario()" :disabled="buscarCliente==''">
                         Buscar
                     </button>
                   
                 </div>
                 <div class="form-group col-sm-1" style="display: flex; align-items: center;" v-else>
                    <input type="text" class="form-control"  placeholder="Número de Documento" disabled>
                    <button class="btn btn-light" type="button" disabled>
                        Buscar
                    </button>                  
                </div>
                 <div class="form-group col-sm-7" style="display: flex; align-items: center;">
                     <input type="text" class="form-control"  v-model="datos_cliete" disabled placeholder="Número Documento/Número de Cliente">
                     <button class="btn btn-primary" type="button" @click="abrirModal('lote_cliete');listarUsuarioRetorno();" v-if="contador_cliente===0">
                         <i class="fa fa-search-plus" aria-hidden="true"></i>
                     </button>
                     <button class="btn btn-light" style="color: white;" type="button" v-else>
                        <i class="fa fa-search-plus" aria-hidden="true"></i>
                    </button>                               
                     <button class="btn btn-info" type="button" style="color: white;"  @click="abrirModal('registrar_cliente');listarEX();listarTipoDoc();" v-if="contador_cliente===0">
                         <i class="fa fa-user-plus" aria-hidden="true"></i>
                     </button>
                     <button class="btn btn-light" type="button" style="color: white;"   v-else>
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </button>
                     <button class="btn btn-secondary" type="button" style="color: white;"  @click="limpiar();" v-if="contador_cliente===0">
                         Limpiar
                     </button>
                     <button class="btn btn-light" type="button" style="color: white;" v-else>
                        Limpiar
                    </button>
                 </div>             
                               
             </div> 
             
             <div class="row"> 
              <div class="form-group col-sm-2" style="display: flex;">
                <strong>Tipo de pago:</strong>
              </div>            
              <div class="form-group col-sm-3 d-flex gap-2">
        <button type="button" class="btn" :class="tipo_contado_s === 1 ? 'btn-primary' : 'btn-secondary'" data-toggle="tooltip" data-placement="bottom" :title="tipo_contado_s === 1 ? 'Pago al contado' : 'Pago al contado'"  @click="tipoPago_sis(1)">
            <i class="fa fa-money" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn" :class="tipo_qr_s === 1 ? 'btn-primary' : 'btn-secondary'" data-toggle="tooltip" data-placement="bottom" :title="tipo_qr_s === 1 ? 'Pago QR' : 'Pago QR'" @click="tipoPago_sis(2)">
            <i class="fa fa-qrcode" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn" :class="tipo_tarjeta === 1 ? 'btn-primary' : 'btn-secondary'" data-toggle="tooltip" data-placement="bottom" :title="tipo_tarjeta === 1 ? 'Pago con tarjeta' : 'Pago con tarjeta'" @click="tipoPago_sis(3)">
            <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
        </button> 
        <button type="button" class="btn" :class="tipo_gift_ === 1 ? 'btn-primary' : 'btn-secondary'" data-toggle="tooltip" data-placement="bottom" :title="tipo_gift_ === 1 ? 'Pago GiftCard' : 'Pago GiftCard'" @click="tipoPago_sis(4)">
          <i class="fa fa-gift" aria-hidden="true"></i>
        </button>   
    </div>
     
    <div v-show="tipo_pago_Qr_con_tar>=1" class="form-group col-sm-7" >
                      <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" v-model="TipoComprobate">
                        <option value="0" selected disabled>Comprobante</option>
                        <option value="1" :disabled="key_Activacion==2">Recibo</option>
                        <option value="2" :disabled="key_Activacion==2" :hidden="key_Activacion==0">Factura</option>                        
                      </select>
                  
                    <button v-if="selected === null || numero === '' || numero <= 0 || arrayVentas.length <= 0 || TipoComprobate===0 || datos_cliete==='' || isSubmitting==true ||key_1!=1 " type="button" class="btn btn-light">REALIZAR VENTA</button>                   
                    <button v-else type="button" class="btn btn-primary" @click="realizarVenta()">REALIZAR VENTA</button>
                </div> 
   

                </div>   
             </div>
           </div>

          <!-----------------------------tabla extra---------------------------------------------->

          <div  class="card w-100" style="border-left: 3px solid #9b111e;">
         
         <div class="card-body">
          <a class="btn btn-outline-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <strong >Datos extra</strong>
          </a>
          
           <hr> <!-- Línea horizontal -->                               
          
<div class="collapse" id="collapseExample">
  <div class="card card-body">
    <div class="alert alert-warning" role="alert">
Si no selecciona alguna opción automaticamente marca como efectivo en caso de otro tipo de pago seleccione otra opción 
</div>
    <div class="container">
                                 <div class="form-group row">
                                  
                                     <label class="col-md-2 form-control-label" for="text-input">
                                         Tipo de pago:                                       
                                     </label>
                                     <div class="col-md-3">                
                                         <select  class="form-control" v-model="selectPago_" @change="resertPago_siat()">
                                             <option value="0" disabled selected>Seleccionar...</option>
                                             <option v-for="t in arrayPago_"  :key="t.codigo" :value="t.codigo" v-text="t.descripcion">
                                             </option>                                  
                                         </select>
                                     </div> 
                                 
                                      <label v-show="selectPago_===2 || selectPago_==='2'" class="col-md-2 form-control-label" for="text-input">
                                        Numero de tarjeta:                                       
                                     </label>
                                     <label v-show="selectPago_===7 || selectPago_==='7'" class="col-md-2 form-control-label" for="text-input">
                                        Numero comprobante:                                       
                                     </label>
                                   
                                    <div class="col-md-3">
                                      <input v-show="selectPago_===2 || selectPago_==='2'||selectPago_===7 || selectPago_==='7'" style="text-align: right;" type="number" class="form-control"  placeholder="" v-model="numeroTarjeta_siat">
                                    </div>                                   
                                 </div>                                
    </div>  
    <div class="container">
                                 <div class="form-group row">
                                  
                                     <label v-show="selectPago_===2 || selectPago_==='2'||selectPago_===7 || selectPago_==='7'" class="col-md-2 form-control-label" for="text-input">
                                            Datos adicionales                              
                                     </label>                               
                                   
                                    <div class="col-md-3">
                                      <input v-show="selectPago_===2 || selectPago_==='2'||selectPago_===7 || selectPago_==='7'" style="text-align: right;" type="text" class="form-control"  placeholder="" v-model="cadenaOtros">
                                    </div>   
                                    <label  v-show="selectPago_===2 || selectPago_==='2'||selectPago_===7 || selectPago_==='7'" class="col-md-2 form-control-label" for="text-input">
                                         Banco:                                       
                                     </label>
                                     <div class="col-md-3">                
                                         <select  v-show="selectPago_===2 || selectPago_==='2'||selectPago_===7 || selectPago_==='7'"  class="form-control" v-model="selectBanco_v">
                                             <option value="0" disabled selected>Seleccionar...</option>
                                             <option v-for="b in arrayBanco"  :key="b.id" :value="b.id" v-text="b.nombre">
                                             </option>                                  
                                         </select>
                                     </div>                                 
                                 </div>                                
    </div>                               
   </div>
</div>
         </div>
       </div>



          <!----------------------------fin de la tabla extra--------------------------------------------------------->
                     </div>
                 </div>
                     </div>   
           
                 <!-- fin de index -->
                 </div>   
                    <!--Inicio del modal agregar/actualizar-->
                 <div class="modal fade"
                     tabindex="-1"
                     role="dialog"
                     arial-labelledby="myModalLabel"
                     id="registrar"
                     aria-hidden="true"
                     data-backdrop="static"
                     data-key="false" >
                     <div class="modal-dialog modal-primary modal-lg" role="document">
                         <div class="modal-content">
                             <div class="modal-header">
                                 <h4 class="modal-title">{{ tituloModal }}</h4>
                                 <button
                                     type="button"
                                     class="close"
                                     aria-label="Close"
                                     @click="cerrarModal('registrar')"
                                 >
                                     <span aria-hidden="true">x</span>
                                 </button>
                             </div>
                             <div class="modal-body">
                                 <div class="alert alert-warning" role="alert">
                                     Todos los campos con (*) son requeridos
                                 </div>
                                 <form action="" class="form-horizontal">
                                 
                                     <!-- insertar datos -->
                                     <div class="container">
                                         
                                         <div class="form-group row">
                                            
                                            
                 
                                         </div>
                                       
                                     
                                        
                                     </div>
                                 </form>
                             </div>
                           
                             <div class="modal-footer">
                                 <button
                                     type="button"
                                     class="btn btn-secondary"
                                     @click="cerrarModal('registrar')"
                                 >
                                     Cerrar
                                 </button>
                                 <button
                                     type="button"
                                     v-if="tipoAccion == 1"
                                     class="btn btn-primary"
                                    
                                     :disabled="!sicompleto"
                                 >
                                     Guardar
                                 </button>
                                 <button
                                     type="button"
                                     v-if="tipoAccion == 2"
                                     class="btn btn-primary"
                                   
                                 >
                                     Actualizar
                                 </button>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!--fin del modal-->
                   <!-- MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE  -->

          <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                     <h4 class="modal-title">Datos de cliente</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                        </div>
                         <div  class="alert alert-warning alert-dismissible fade show" role="alert">
                                     <strong>NO ESCRITO EN EL PADRÓN</strong> <br>
                                    <p>{{name_moda}}</p>                                     
                          </div>
   <div class="row justify-content-center">
                                     <div class="form-group col-sm-6">
                                         <strong>Razon social:</strong>
                                         <input type="text" class="form-control" v-model="razon_social_99001">
                                     </div>
                                 </div>                    
                             
 <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" @click="cerrarModal('cliente_modal')">Cerrar</button>
                                 <button  :disabled="razon_social_99001==''" class="btn btn-primary" @click="caso_99001();">Guardar</button>
                              </div>

                      </div>
                  </div>
              </div>
          </transition>           
          
                 <!-- fin modal add cliente -->
                   <!-- Modal para la busqueda de clientes por lote -->

          <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">Busqueda de clientes</h5>
                        <button type="button" class="close" @click="cerrarModal('lote_cliete')">
                            <span>&times;</span>
                        </button>
                        </div>

                          <div class="modal-body">
                               <form>
                                   <div class="mb-3">
                                       <label for="exampleInputEmail1" class="form-label">Introduzca numero de documento: </label>
                                       <input
                                           type="text"
                                           id="texto"
                                           name="texto"
                                           class="form-control"
                                           placeholder="Id cliente/Nro doccumento/Nombre a facturar"
                                           v-model="buscar"
                                           @input="listarUsuarioRetorno()" />                             
                                   </div>
                                   <div> <table class="table table-hover" id="tablaProductosIngreso" 
                                           style="height: 350px; display: block; overflow: scroll;"
                                       >
                                           <thead>
                                               <tr>
                                                   <th scope="col">Nro cliente.</th>
                                                   <th scope="col">Nombre.</th>
                                                   <th scope="col">Nombre a facturar</th>
                                                   <th scope="col">Datos de cliente</th>
                                               </tr>
                                           </thead>
         
                                           <tbody>
                                               <tr v-for="cliente in arrayClienteLote" :key=" cliente.id"  @click="caso_loteCliente(cliente);
                                                   listarUsuarioRetorno();">
                                                   <td v-text="cliente.id"></td>
                                                   <td v-text="cliente.nombre_completo"></td>
                                                   <td v-text="cliente.nom_a_facturar"></td>
                                                   <td v-text="cliente.num_documento+'/'+cliente.tipo_doc_datos+'-'+cliente.tipo_doc_nombre"></td>
                                               </tr>
                                           </tbody>
                                       </table>
                                   </div>
                               </form>
                           </div>
                           <div class="modal-footer">
                               <button
                                   type="button"
                                   class="btn btn-secondary"
                                   data-bs-dismiss="modal"
                                   @click="cerrarModal('lote_cliete');">
                                   Cerrar
                               </button>
                               <!--
                             <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" data-bs-dismiss="modal" @click="cerrarModal('staticBackdrop');abrirModal('actualizar',AjusteNegativos);ProductoLineaIngreso();">Cerraaaa</button>
                           
                           --->
         
                               <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                           </div>

                    </div> 
                  </div>
            </div>
          </transition>             

          
               <!-- Fun Modal para la busqueda de clientes por lote -->
               <!--Inicio del modal de registro de-->

          <transition name="fade">
            <div v-if="showModal_3" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_cliente')">
                            <span>&times;</span>
                        </button>
                        </div>

 <div class="modal-body">
                         <div class="alert alert-warning" role="alert">
                             Todos los campos con (*) son requeridos
                         </div>
                         <form action="" class="form-horizontal">
                             <!-- insertar datos -->
                             <div class="container">
                                 <div class="form-group row">
                                     <label class="col-md-3 form-control-label" for="text-input">
                                         Tipo de cliente:
                                         <span  v-if="selectTipo==0" class="error">(*)</span>
                                     </label>
                                     <div class="col-md-6">   
                 
                                         <select name="" id=""  class="form-control" v-model="selectTipo" @change="cambiarEstadoSElectrorCliente()">
                                             <option value="0" disabled selected>Seleccionar...</option>
                                             <option v-for="t in arrayTipo"
                                                 :key="t.id"
                                                 :value="t.id"
                                                 v-text="t.tipo">
                                             </option>                                  
                                         </select>
                                     </div>  
                                    
                                 </div>
                                 <div class="form-group row"  v-if="selectTipo!=0">
                                     <label class="col-md-3 form-control-label" for="text-input">
                                         Tipo de docuemento:
                                         <span  v-if="selectTipoDoc==0" class="error">(*)</span>
                                     </label>
                                     <div class="col-md-6" >        
                                         <select name="" id=""  class="form-control" v-model="selectTipoDoc" @change="estadoEX();" >
                                             <option value="0" selected disabled>-Seleccione un dato </option>
                                             <option v-for="t in arrayTipoDocumento" :key="t.id"
                                                 :value="t.id"
                                                 v-text="t.datos+'-'+t.nombre_doc">
                                             </option>                                  
                                         </select> 
                                     </div>
                                 </div>  
                                  <!---- acoordion ----> 
                                  <div class="accordion" id="accordionExample" v-if="selectTipoDoc!=0&&selectTipo!=0">
                                         <div class="card">
                                           <div class="card-header" id="headingOne">
                                             <h2 class="mb-0" v-if="selectTipo==1">
                                               <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                 Datos de persona
                                               </button>
                                             </h2>
                                             <h2 class="mb-0" v-else>
                                               <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                 Empresa
                                               </button>
                                             </h2>
                                             
                                           </div>
                                       
                                           <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" v-if="selectTipo==1">
                                             <div class="card-body">
                                                 <div class="row">
                                                     <div class="form-group col-sm-4">
                                                         <strong>Nombre: </strong>
                                                         <input type="text" class="form-control" v-model="nombres"  placeholder="Nombres."  >
                                                       
                                                     </div>
                                                     <div class="form-group col-sm-4">
                                                         <strong>Apellidos:</strong>
                                                         <input type="text" class="form-control" placeholder="Apellido Paterno / Apellido Materno."  v-model="apellidos" >
                                                       
                                                     </div>
                                                     <div class="form-group col-sm-4">
                                                         <strong>Número Documento: <span  v-if="num_documento==''" class="error">(*)</span></strong>
                                                         <input type="text"  class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento"  maxlength="12" @input="validateNumber" pattern="[0-9]*" title="Solo se permiten números" v-on:focus="selectAll" >
                                                         <span  v-if="num_documento==''" class="error">Debe ingresar el documento de identidad</span>
                                                     </div>
                                                 </div> 
                                                     <div class="row">
                                                         <div class="form-group col-sm-4" >
                                                             <strong>complemento de C.I.: </strong>
                                                             <input type="text" class="form-control" placeholder="Solo si tiene C.I." :maxlength="4"  v-model="complemento_"  :disabled="selectTipoDoc != 1">
                                           
                                                         </div>
                                                         <div class="form-group col-sm-4">
                                                             <strong>Correo: </strong>
                                                             <input type="email" class="form-control" placeholder="Correo@correo.es"  v-model="correo" v-on:focus="selectAll" required>
                                                  
                                                         </div>
                                                         <div class="form-group col-sm-4">
                                                             <strong>Nombre a Facturar: <span  v-if="nombre_a_facturar==''" class="error">(*)</span></strong>
                                                             <input type="text" @input="validateInput" class="form-control" placeholder="Razon social"  v-model="nombre_a_facturar" v-on:focus="selectAll">
                                                             <span  v-if="nombre_a_facturar==''" class="error">Debe ingresar un nombre a quien va la factura</span>
                                                         </div>                                             
                                                     </div>                                       
                                             </div>
                                           </div>
                                         <!------else del if-------->
                                         <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" v-else>
                                             <div class="card-body">
                                                <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Nombre de empresa: </strong>
                                                <input type="text" class="form-control" v-model="nombres"  placeholder="Nombres.">                                              
                                            </div>
                                            
                                        </div> 
                                                 <div class="row">
                                                     <div class="form-group col-sm-4">
                                                         <strong>Razon_social: <span  v-if="nombre_a_facturar==''" class="error">(*)</span></strong>
                                                         <input type="text" class="form-control" placeholder="Nombre a Facturar"  v-model="nombre_a_facturar" v-on:focus="selectAll">
                                                         <span  v-if="nombre_a_facturar==''" class="error">Debe ingresar un nombre del establecimiento</span>
                                                     </div>  
                                                     <div class="form-group col-sm-4">
                                                         <strong>Correo:</strong>
                                                         <input type="email" class="form-control" placeholder="Correo@correo.es"  v-model="correo" v-on:focus="selectAll" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" required>
                                                         
                                                     </div>
                                                     <div class="form-group col-sm-4">
                                                         <strong>Número Documento: <span  v-if="num_documento==''" class="error">(*)</span></strong>
                                                         <input type="text"  class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" maxlength="12" @input="validateNumber" pattern="[0-9]*" title="Solo se permiten números" v-on:focus="selectAll">
                                                         <span  v-if="num_documento==''" class="error">Debe ingresar el documento de identidad</span>
                                                     </div>
                                                 </div> 
                                                    
         
                                                 
                                             </div>
                                           </div>
         
                                         </div>
                                         <div class="card">
                                           <div class="card-header" id="headingTwo">
                                             <h2 class="mb-0">
                                               <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                 Datos Adicionales
                                               </button>
                                             </h2>
                                           </div>
                                           <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                             <div class="card-body">
                                                 <div class="row">
                                                     <div class="form-group col-sm-6">
                                                         <strong>Dispositivo de comunicación fijo o móvil:</strong>
                                                         <input type="text" class="form-control" v-model="telefono"  placeholder="Celular/telefono."  >
                                                       
                                                     </div>
                                                     <div class="form-group col-sm-6">
                                                         <strong>Dirección:</strong>
                                                         <input type="text" class="form-control" placeholder="Zona/Calle/Barrio/Numero de puerta."  v-model="direccion" >
                                                     
                                                     </div>
                                                 
                                                 </div> 
                                                 <div class="row">
                                                     <div class="form-group col-sm-6">
                                                         <strong>País:</strong>
                                                         <input type="text" class="form-control" v-model="pais"  placeholder="Lugar donde radica."  >
                                                       
                                                     </div>
                                                     <div class="form-group col-sm-6">
                                                         <strong>Ciudad:</strong>
                                                         <input type="text" class="form-control" placeholder="ciudad donde vive."  v-model="ciudad">
                                                     
                                                     </div>
                                                 
                                                 </div> 
                                             </div>
                                           </div>
                                         </div>
                                   
                                       </div>
                               </div>
                            
                         </form>
         
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar_cliente')">Cerrar</button>
                         <button type="button"  class="btn btn-primary"  @click="registrar_cliente_modal()">Guardar</button>              
                        
                     </div>

                      </div>
                    </div>
              </div>
          </transition>

             <!--fin del modal-->
            <!---------------------------inicio de vista modal bloque venta precio--------------------------->

             <!--Inicio del modal de registro de-->
          <transition name="fade">
            <div v-if="showModal_4" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('vista_bloque')">
                            <span>&times;</span>
                        </button>
                        </div>
   <div class="modal-body">                         
                              <form action="" class="form-horizontal">
                                <div class="modal-body"> 
                                  <div class="alert alert-secondary" v-if="arrayProducto_2.length<=0" role="alert">Sin datos</div>                     
                                  <table v-else class="table table-bordered table-striped table-sm table-responsive">
                                    <thead>
                                      <tr>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Opcion</th>
                                        <th class="col-md-4" style="font-size: 11px; text-align: center">Producto</th>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Lote</th>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Cantidad</th>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Precio</th>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Linea</th>
                                        <th class="col-md-2" style="font-size: 11px; text-align: center">Fecha Vencimiento</th>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Dias faltantes</th>
                                      </tr>                                                                         
                                    </thead>
                                    <tbody>
                                      <tr v-for="p in arrayProducto_2 " :key="p.id">
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">
                                          <button type="button" @click="volverLista(p)" class="btn btn-primary" style="font-size: 11px;">Seleccionar</button>
                                        </td>
                                        <td class="col-md-4" style="font-size: 11px; text-align: center">{{p.codigo_prod+" "+p.leyenda}}</td>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{p.lote}}</td>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{p.stock_ingreso}}</td>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{p.precio_lista_gespreventa}}</td>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{p.nombre_linea}}</td>
                                        <td class="col-md-2" style="font-size: 11px; text-align: center">{{p.fecha_vencimiento}}</td>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{p.dias}}</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>      
                                                        
                              </form>         
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" @click="cerrarModal('vista_bloque')">Cerrar</button>                       
                            </div>


                    </div>
                  </div>
              </div>
          </transition>            

             <!--fin del modal-->
            <!-------------------------fin de modal vista precio----------------------------->
             </main>

          </div>

        </div>
      </div>
  
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
// Asigna los fonts a pdfmake
pdfMake.vfs = pdfFonts.pdfMake.vfs;

//Vue.use(VeeValidate);
export default {
    components: { VueMultiselect },
    data() {
        return {
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            loading: true,
            selected: null,
            isSubmitting: false, // Controla el estado del botón de envío
      options: ['list', 'of', 'options'],
            tituloModal: "",
            name_moda:"",
            //selecto get sucursal
      
            //producto
            arrayProducto:[], 
            selectProducto:null,           
            inputTextBuscarProductoIngreso:'',
            //clientes
            buscarCliente:'',
            cliente_id:'',
            datos_cliete:'',
            nom_a_facturar:'',
            num_documento:'',
            //casos especiales
        
            id_tipo_doc:'',
            razon_social_99001:'',   
            razon_social_000:'', 
            //cliente_lote
            buscar:'',
           
            arrayClienteLote:[],
            tipoAccion:1,
            //add_cliente 
            arrayTipoDocumento:[],
         
            selectTipoDoc:0,
            arrayTipo:[{id:1,tipo:'Persona'},
                            {id:2,tipo:'Empresa'}],
            selectTipo:0, 
            arrayEX:[],
            selectEX:0,  
            //datos de cliente
            pais:'',
            ciudad:'',
            direccion:'',
            telefono:'',
            num_documento2:'',
            correo:'',
            nombre_a_facturar:'',
            nombres:'',
            complemento_siat:'',
            complemento_:'',
            //tipo de codigo 
            correo_cliente:'',
            extencion_tipodocumento:'',
            nombre_documento:'',
            TipoComprobate:0,          
            arrayTipoCom_v2:[],
    //----venta----
    arrayVentas:[],
    descuento_1:0,
    numero :'',
    descuento:0,
  
    //descuentos
    arrayDescuento:[],
    descuentoNombre:'',
    arrayDescuentoOperacion:[],
    //----- numeral
            
    codigo_tienda_almacen:'',
    //lista
    nom_lista:'',
    id_lista_v2:0,        
    validadorPersonal:'',
    arrayProducto_recibo_1:[],
    valorUnicoPersonalizado:0,

    caso5_id:'',
    caso5_nom:'',
    caso5_id_tabla:'',
    caso5_id_tabla_p:'',
    sumatotal:0,
                efectivo:0,
                cambio:0,
                gift_value:0,

     id_tabla_y2:'', 
     existe_cliente:0,  
     existe_cliente_f:0,
     cliente_des:'',
     cliente_id_descuento:'',
     cliente_id_tabla:'',       
     cliente_activo_descuento:'',
     cliente_id_sucursal_desc:'',
     cliente_bandera:0,

     contador_cliente:0,
 
     existe_producto:0,
     monto_a_pagar:0,
     importe_fiscal:0,
     tota_del_total:0,
     descuento_final:0,       
     id_descuento_x:0,
     existe_final:0,
     array_vetasQuery:[],    
     key_1:0,   
     controlador_venta_id:0,   
     array_ven__detalle_descuentos:[],    
     producto_bandera_1:0,

     estado_dosificacion_facctura:0,
     arrayEstado_dosificacion_facctura:[],
     arrayQuery_siat_:[],
     id_dosificacaion_1:'',
     nro_autorizacion_1:'',
     dosificacion_1:'', 
     fecha_e_1:'', 
     n_ini_facturacion_1:'', 
     n_ini_facturacion_1:'',
     n_fin_facturacion_1:'',
     n_act_facturacion_1:'',

     arrayProducto_2:[], 
     cadenaProducto_2:'',      

     tieneApertura_0:'',
      showModal: false,
      showModal_2: false,
      showModal_3: false,
      showModal_4: false,

     //-apertura
     id_apertura_cierre:'',

     //*siat----
            key_Activacion:'',
            id_sucursal_siat:'',
            id_caja_siat:'',
            arrayPago_:[],
            selectPago_:'0',
            numeroTarjeta_siat:'',
            cadenaOtros:'',  
            arrayUnidadMedida_2:[],

            
          

    // sistema contado=1, qr=2, tarje=3, giftcard
    tipo_pago_Qr_con_tar:0,
    tipo_contado_s:0,
    tipo_qr_s:0,
    tipo_tarjeta:0, 
    tipo_gift_:0,

    actividadEco_v2:'',
    leyenda_v2:'',

    arrayBanco:[],
    selectBanco_v:'0',
            
        };
    },
    created() {
    // Simula una carga de datos
    this.loadData().then(() => {
      // Una vez que los datos se cargan completamente, oculta la pantalla de carga
      this.loading = false;
    });
  },
   
watch: {
    selectTipoDoc: function (newValue) {
             let newTipo = this.arrayTipoDocumento.find(
                    (element) => element.id === newValue,
                );

                if (newTipo) {
                    this.extencion_tipodocumento = newTipo.datos;
                    this.nombre_documento =newTipo.nombre_doc;
                }         

        },

        descuento: function (valor) {
            let valorNumerico = parseFloat(valor);  
    
    let valorUnicoPersonalizadoNumerico = parseFloat(this.valorUnicoPersonalizado);

    if (this.arrayDescuentoOperacion.length === 1) {
        if (this.arrayDescuentoOperacion[0].id_nom_tabla === 5) {
            if (valorNumerico > valorUnicoPersonalizadoNumerico) {
                this.descuento = 0;
                Swal.fire(
                    "No puede ingresar un número mayor al descuento dado",
                    "Haga click en Ok",
                    "error"
                );
            }  
        }
    }
    
},
        selected: function (valor) {
            if (valor !=null) {
                if (this.arrayDescuentoOperacion.length===1) {
                    if (this.arrayDescuentoOperacion[0].id_nom_tabla===5) {
                    let operacion_21 = this.operacion_Numeral_Procentaje(this.arrayDescuentoOperacion[0].tipo_num_des, this.arrayDescuentoOperacion[0].monto_descuento,this.selected.precio_lista_gespreventa);
                    let { valorNumeral, porcentaje } = operacion_21;
                  //------casos 5 
                    
                this.valorUnicoPersonalizado = porcentaje;
                this.caso5_id=this.arrayDescuentoOperacion[0].id;
                this.caso5_nom=this.arrayDescuentoOperacion[0].nombre_descuento;
                this.caso5_id_tabla=this.arrayDescuentoOperacion[0].id_perso;//<====
                this.caso5_id_tabla_p=this.arrayDescuentoOperacion[0].id_nom_tabla;
        
            
                }  
                } 
               
            }       
        }, 
        
        numero: function (valor){
            if (this.selected!==null) {
                if (valor>this.selected.stock_ingreso) {
                    Swal.fire(
                    "No puede ingresar un número mayor a la cantidad del producto.",
                    "Haga click en Ok",
                    "error"
                );
                this.numero=0;
                } 
                
            }
           
        },
    
    },
    computed: {
       
        sicompleto() {
           let me = this;
     //       if (
          
       //         me.glosa != "" &&
       //         me.cantidadS != "" &&
     //           me.ProductoLineaIngresoSeleccionado
     //       )
       //         return true;
      //      else return false;
      },
        isActived: function () {
            return this.pagination.current_page;
        },

        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
       
    },

    methods: {
        
        loadData() {
      return new Promise(resolve => {
        setTimeout(() => {
          // Simulando la carga de datos durante 2 segundos
          resolve();
        }, 1090);
      });
    },
   /////////////////////////////////GENERAR PDF FACTURA DOSIFICACION/////////////////////////////////////////////////////////////////////////////
   generarPDF_factura_dosificacion( direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,nombre_empresa,actividad_economica,num_auto,cod_autorizacion,fecha_e_2,ciudad_su_1,departamento_su_1,numero_factura,cliente_id,descuento_final_2,total_literal,nit_2) {
 // Define el contenido del PDF



  // Itera sobre los datos y agrega filas a la tabla
  const tableBody_2=[];
  let sumador_subtotal=0;
  array_recibo.forEach(item => {
    
    let descuento_dosificacion_2="";
    if (Number.isInteger(Number(item.descuento))) {
      descuento_dosificacion_2= Number(item.descuento).toFixed(2);
    } else if (item.descuento === 0 || item.descuento === "0") {
      descuento_dosificacion_2= "0.00";
    } else {
        // Si es decimal, devuelve el valor tal como está
        descuento_dosificacion_2=item.descuento;
    }
    sumador_subtotal=sumador_subtotal+((item.cant * item.p_u)-descuento_dosificacion_2);
    tableBody_2.push([
    { text: item.descrip , fontSize: 7, alignment: 'left' }, // Salto de línea
      {},   
    ]);
    tableBody_2.push([
    { text: 'Unidad de medida: '+item.unidad_medida, fontSize: 7, margin: [5, 0, 0, 0],alignment: 'left' }, // Salto de línea
      {},   
    ]);
    tableBody_2.push([
    { text: item.cant+'.00 x '+item.p_u+' - '+(descuento_dosificacion_2), fontSize: 7, alignment: 'left' }, // Salto de línea
    { text: ((item.cant * item.p_u)-descuento_dosificacion_2).toFixed(2), fontSize: 7, alignment: 'right' },   
    ]);
  });
  
  const table_totales = [
    // Agrega los encabezados de la tabla
    [
      { text: 'SubTotal Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: (sumador_subtotal).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Descuento Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: (descuento_final_2).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Total Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: (total_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Monto gift card Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: '0.00', fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Monto a pagar Bs:   ',  fontSize: 7, alignment: 'right',bold: true }, 
      { text: (total_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Importe base crédito fiscal Bs:   ',  fontSize: 7, alignment: 'right',bold: true }, 
      { text: (total_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ]
  ];

  const table_pago_efe_cambio=[
  [
      { text: 'Pago en efectivo Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: (efectivo_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Cambio Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: (cambio_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
  ];
   
      const documentDefinition = {
        pageMargins: [10, 12, 10, 8], // Configura los márgenes en cero
        pageSize: {
    width: 80 * 2.83465, // Ancho en puntos (conversión a puntos desde mm)
    height: 'auto',
    columnGap: 2,
  },
 
      content: [
      {
        text: 'FACTURA', bold: true,
      
        style: 'header'
      },
      {
        text: 'CON DERECHO A CREDITO FISCAL', bold: true,
      
        style: 'header'
      },
      {
        text: nombre_empresa,
      
        style: 'header'
      },
      {
        text: nomsucursal,
   
        style: 'header'
      },
      {
        text: direccionMayusculas,
    
        style: 'header'
      },
      {
        text: 'Telefono: '+numero_referencia,
    
        style: 'header'
      },      
      {
        text: ciudad_su_1+' - '+departamento_su_1,
    
        style: 'header'
      },
      
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'NIT', bold: true,
    
        style: 'header'
      },
      {
        text: nit_2,
    
        style: 'header'
      },
      {
        text: 'FACTURA Nro',bold: true,    
        style: 'header'
      },
      {
        text: numero_factura,    
        style: 'header'
      },
      {
        text: 'CÓD. AUTORIZACION', bold: true,    
        style: 'header'
      },
      {
        text: num_auto,    
        style: 'header'
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
    
      {
        text: actividad_economica,
    
        style: 'header'
      },
      
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'NOMBRE/RAZÓN SOCIAL:    '+nom_a_facturar,
     style: 'normal', margin: [19, 0, 0, 0]        
      },
      {
        text: 'NIT/CI/CEX:    '+num_documento,
        style: 'normal', margin: [63, 0, 0, 0]             
      },
      {
        text: 'COD.CLIENTE:    '+cliente_id,
        style: 'normal', margin: [56, 0, 0, 0]        
      },
     
      {
        text: 'FECHA DE EMISIÓN:    '+fecha+' '+hora,
        style: 'normal', margin: [37, 0, 0, 0] 
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'DETALLE',
    
        style: 'header'
      },       

      {
       
        table: {
          headerRows: 1,
          widths: ['75%', '25%'], // Ajusta los anchos de las columnas
          body: tableBody_2
        },
        layout: 'noBorders'
		},
        {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
        {         
            table:{
                headesRows:1,
                widths: ['75%','25%'],
                body: table_totales
            }, layout: 'noBorders'
        },
    
    
      
      {
        text: 'Son: '+total_literal,      
        alignment: 'left',fontSize: 7
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },     
      {
        margin: [1, 1, 1, 1],         
            table:{
                headesRows:1,
                widths: ['75%','25%'],
                body: table_pago_efe_cambio
            }, layout: 'noBorders'
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },

      {
        text: 'Codigo de control: '+cod_autorizacion,    
        style: 'datos_f',margin: [6, 1, 6, 1]
      },
      {
        text: 'Fecha limite de emision: '+fecha_e_2,    
        style: 'datos_f',margin: [6, 0, 6, 1]
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY', bold: true,    
        style: 'header', margin: [7, 5, 7, 1]
      },

      {
        text: 'Ley N° 453: Está prohibido importar, distribuir o comercializar productos prohibidos o retirados en el país de origen por atentar a la integridad física y a la salud. Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea',   
        style: 'header' ,margin: [7, 1, 7, 3]
      },
      { qr:  nit_2+'|'+numero_factura+'|'+num_auto+'|'+fecha+'|'+total_venta+'|'+cod_autorizacion+'|'+cliente_id+'|0|0|0|0.00',alignment: 'center',  fit: '85',margin: [0, 4, 0, 4] },
      
        ],
        styles: {
            linea_2: {
                fontSize: 9,
                 margin: [1, 1, 1, 1],
                 alignment: 'center',  
            },
            normal: {
            fontSize: 7,                  
          },
          header: {
            fontSize: 7,
           
            alignment: 'center',         
          },
          header_1: {
            fontSize: 7,
           
            alignment: 'right', 
                    
          },
          datos_f: {
            fontSize: 7,
         
            alignment: 'left',         
          },
          
        tableHeader_1: {
  
        fontSize: 7,

            alignment: 'justify',
      },
      tableHeader_2: {

        fontSize: 7,
      
            alignment: 'center',
      }
        }
      };

      // Genera el PDF y abre una nueva ventana con el documento
      pdfMake.createPdf(documentDefinition).open();
    }, 
   ///////////////////////////////funciones para la venta///////////////////////////////////////////////////////
    generarPDF( direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,nombre_empresa,tipo_venta_1,monto_vale_1,monto_apagar_1) {
      // Define el contenido del PDF

 // Crea el cuerpo de la tabla dinámicamente
 const tableBody = [
    // Agrega los encabezados de la tabla
    [
      { text: 'CANT.', style: 'tableHeader_2' }, 
      { text: 'DESCRIPCIÓN', style: 'tableHeader_2' }, 
      { text: 'P.U.', style: 'tableHeader_2' }, 
      { text: 'TOT.', style: 'tableHeader_2' }
    ]
  ];

  // Itera sobre los datos y agrega filas a la tabla
  array_recibo.forEach(item => {
    tableBody.push([
      { text: item.cant, fontSize: 7, alignment: 'center' },
      { text: item.descrip, fontSize: 7, alignment: 'left' },
      { text: item.p_u, fontSize: 7, alignment: 'center' },
      { text: (item.cant * item.p_u).toFixed(2), fontSize: 7, alignment: 'center' } // Operación y formato
    ]);
  });

      const documentDefinition = {
        pageMargins: [5, 8, 5, 4], // Configura los márgenes en cero
        pageSize: {
    width: 80 * 2.83465, // Ancho en puntos (conversión a puntos desde mm)
    height: 'auto',
    columnGap: 2,
  },
 
      content: [
      {
        text: nombre_empresa,
      
        style: 'header'
      },
      {
        text: direccionMayusculas,
    
        style: 'header'
      },
      {
        text: nomsucursal,
   
        style: 'header'
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
        ],
        margin: [1, 5, 1, 5]
      },
      {
        text: 'TICKETT Nro.:'+'           '+nuevoComprobante,   
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        text: 'FECHA:'+'                       '+fecha+'   HORA:  '+hora,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        text: 'NIT/CI.:'+'                       '+num_documento,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        text: 'NOMBRE:'+'                   '+nom_a_facturar,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        text: 'TIPO VENTA:'+'              '+tipo_venta_1,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
        ],
        margin: [6, 4, 6, 4]
      },
      {
        style: 'tableExample',
        table: {
          headerRows: 1,
          widths: ['12%', '54%', '17%', '17%'], // Ajusta los anchos de las columnas
          body: tableBody
        },
        layout: 'noBorders'
		},
        {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
        ],
        margin: [6, 4, 8, 4]
      },
      {
        text: 'IMPORTE TOTAL: Bs.   '+total_sin_des.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'DESCUENTO: Bs.   '+descuento_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'TOTAL CON DESC.: Bs.   '+total_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'VALE: Bs.   '+monto_vale_1.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'IMPORTE A PAGAR: Bs.   '+monto_apagar_1,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      
      {
        text: 'IMPORTE NO VALIODO PARA CRÉDITO FISCAL.',      
        alignment: 'left',fontSize: 8, margin: [6, 4, 6, 4]
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
        ],
        margin: [6, 4, 6, 4]
      },
      {
        text: 'PAGO EN EFECTIVO: Bs.   '+efectivo_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'CAMBIO: Bs.   '+cambio_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },

      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
        ],
        margin: [6, 4, 6, 4]
      },
      {
        text: '* CODIGO DE CONTROL: '+nuevoComprobante,    
        style: 'datos_f'
      },
      {
        text: '* Valido desde '+ fecha +' hasta '+fechaMas7Dias,    
        style: 'datos_f'
      },
      {
        text: '* Ticket para el cambio valido hasta '+ fecha+' en la misma tienda, términos y codiciones según politica de cambios y devoluiones',
        style: 'datos_f'
      },
      {
        text: '* Atención al cliente whatsapp '+numero_referencia,
        style: 'datos_f'
      },
      {
        text: 'Usuario: '+ nombreCompleto_1,
        style: 'datos_f'
      },
      
        ],
        styles: {
          header: {
            fontSize: 7,
            bold: true,
            alignment: 'center',         
          },
          header_1: {
            fontSize: 7,
            bold: true,
            alignment: 'right', 
                    
          },
          datos_f: {
            fontSize: 7,
            bold: true,
            alignment: 'left',         
          },
          tableExample: {
			margin: [1, 6, 1, 6],
           
		},
        tableHeader_1: {
        bold: true,
        fontSize: 7,
            bold: true,
            alignment: 'justify',
      },
      tableHeader_2: {
        bold: true,
        fontSize: 7,
            bold: true,
            alignment: 'center',
      }
        }
      };

      // Genera el PDF y abre una nueva ventana con el documento
      pdfMake.createPdf(documentDefinition).open();
    },

    operacion_Numeral_Procentaje(tipo_num_des,monto_descuento,precio_lista_gespreventa){
       
        let me = this;  
            let var1=0;
            let porcentaje=0;
            let  valorNumeral=0;
   
        if(tipo_num_des === '#'){
            porcentaje=monto_descuento;
           
            valorNumeral=precio_lista_gespreventa-porcentaje;
            if (valorNumeral<0) {
                valorNumeral=precio_lista_gespreventa;
                porcentaje=0;
            }

            return { valorNumeral, porcentaje };           
        }
        else{
            if (tipo_num_des === '%') {
                porcentaje=monto_descuento/100;    
                var1=precio_lista_gespreventa*porcentaje;
                valorNumeral=precio_lista_gespreventa-var1;
                if (valorNumeral<0) {
                valorNumeral=precio_lista_gespreventa;
                porcentaje=0;
            }else{
                porcentaje=var1;
            }
               
         
                return { valorNumeral, porcentaje };

            }
        }

    },

    operacion_cantida_valor(tipo_can_valor,regla,cantidad_valor,precio_lista_gespreventa){

if (tipo_can_valor==='#') {
    if (regla==='>') {
                if (this.numero>cantidad_valor) {
                    return true;
                }
               
            }
    if (regla==='=') {
                if (this.numero===cantidad_valor) {
                    return true;
                }
            }
    if (regla==='<') {
                if (this.numero<cantidad_valor) {
                    return true;
                }          
} 
}
if (tipo_can_valor==='BS') {
    let va = cantidad_valor.toFixed(2).toString();

    if (regla==='>') {
                if (precio_lista_gespreventa>va) {
                    return true;
                }
            }      
    if (regla==='=') {
  
                if (precio_lista_gespreventa===va) {
                    return true;
                }
            }
    if (regla==='<') {
                if (precio_lista_gespreventa<va) {
                    return true;
                }
            }
} 
},

    resetVenta(){
        let me=this;
        me.name_moda="";
            //producto
            me.selected=null;
            me.selectProducto=null;           
            me.inputTextBuscarProductoIngreso="";
            //clientes
            me.buscarCliente="";
            me.cliente_id="";
            me.datos_cliete="";
            me.nom_a_facturar="";
            me.num_documento="";
            //casos especiales        
            me.id_tipo_doc="";
           me.razon_social_99001="";   
            me.razon_social_000=""; 
            //cliente_lote
            me.buscar=""; 
            me.tipoAccion=1;
            //add_cliente                     
            me.selectTipoDoc=0;
            me.selectTipo=0;           
            me.selectEX=0;  
            //datos de cliente
            me.pais="";
            me.ciudad="";
            me.direccion="";
            me.telefono="";
            me.num_documento2="";
            me.correo="";
            me.nombre_a_facturar="";
            me.nombres="";
            me.complemento_="";
            //tipo de codigo 
            me.correo_cliente="";
            me.extencion_tipodocumento="";
            me.nombre_documento="";
            me.TipoComprobate=0;
            //----venta----
            me.arrayVentas=[];
            me.descuento_1=0;
            me.numero="";
            me.descuento=0;   
            //----- numeral  
     
            me.arrayProducto_recibo_1=[];
            me.valorUnicoPersonalizado=0;
            me.caso5_id="";
            me.caso5_nom="";
            me.caso5_id_tabla="";
            me.sumatotal=0;
            me.efectivo=0;
            me.gift_value=0;
            me.cambio=0;
            me.id_tabla_y2=""; 
      
            me.existe_cliente_f=0;    
            me.contador_cliente=0;  
            me.monto_a_pagar=0;
            me.importe_fiscal=0;
            me.tota_del_total=0;
            me.descuento_final=0;      
            me.array_vetasQuery=[]; 
            me.key_1=0; 
            me.codigo_tienda_almacen='';
         me.array_ven__detalle_descuentos=[];
         me.caso5_id_tabla_p='';
         me.cliente_bandera=0;             
         me.producto_bandera_1=0;

        me.selectPago_='0';
        me.numeroTarjeta_siat='';
        me.complemento_siat='';
        me.cadenaOtros='';
        me.selectBanco_v='0';
        me.tipo_pago_Qr_con_tar=0;
            me.tipo_contado_s=0;
            me.tipo_qr_s=0;
            me.tipo_tarjeta=0;
            me.tipo_gift_=0;    

     //    me.estado_dosificacion_facctura=0;
    // me.arrayEstado_dosificacion_facctura:[],
     //me.id_dosificacaion_1='';
   //  me.nro_autorizacion_1='';
   //  me.dosificacion_1=''; 
   //  me.fecha_e_1=''; 
   //  me.n_ini_facturacion_1=''; 
   //  me.n_ini_facturacion_1='';
   //  me.n_fin_facturacion_1='';
   //  me.n_act_facturacion_1='';
       
    },

        
        listarPermisoFacturacion(sucursal,caja){
            let me = this; 
            var url ="/gestor_ventas/verificador_dosificacion_o_facturacion?id_sucursal="+sucursal+"&id_caja="+caja;  
            axios
                .get(url)
                .then(function (response){
                    let respuesta = response.data;                                               
                    me.estado_dosificacion_facctura=respuesta.estado;
                    switch (me.estado_dosificacion_facctura) {
                      case 0:
                        me.key_Activacion=2;
                         Swal.fire({title: "No existe configuracion de factura",text: "Debe activar alguna de estas opciones para continuar.",icon: "error",});  
                      break;
                      case 1:
                      me.key_Activacion=1;
                   
                        switch (me.estado_dosificacion_facctura) {
                          case 1:
                            //----------para facturacion  en linea
                            me.arrayEstado_dosificacion_facctura=respuesta.consulta;  
                            me.arrayQuery_siat_=respuesta.query; 
              
                          break;
                          case 10:  
                          Swal.fire({title: "Token caducado",text: "revicé: en configuración siat y actualice las fechas con su token de legado, si el error persite revise en la configuracion de impuestos nacionales y pida otro token.",icon: "error",});  
                          break;
                          case 11:  
                          Swal.fire({title: "CUIS caducado",text: "Pedir otro cuis en el sector de siat en la pestaña de cuis, revise si es sucursal o punto de venta.",icon: "error",});  
                          break;
                          case 12:  
                          Swal.fire({title: "CUFD caducado",text: "Pedir otro cufd en el sector de siat en la pestaña de cufd, revise si es sucursal o punto de venta",icon: "error",});  
                          break;
                          case 13:  
                          Swal.fire({title: "Error",text: "Debe esta correcto el cuis o cufd",icon: "error",});  
                          break;
                          default:
                          Swal.fire({title: "ERROR",text: "error de listar facturacion contacte al administrador.",icon: "error",});  
                          
                            break;
                        }
                       
                      break;
                      case 2:
                           //----------para facturacion  docificacion
                          
                           me.arrayEstado_dosificacion_facctura=respuesta.consulta;  
                           if (me.arrayEstado_dosificacion_facctura.diferencia_dias<=0) {
                            Swal.fire({title: "Dosificación o facturación caducada",text: "Paso el tiempo de vigencia establecido.",icon: "error",});  
                           me.estado_dosificacion_facctura=0; 
                           me.key_Activacion=0;
                        } else {
                          me.key_Activacion=1;

                          me.id_dosificacaion_1=me.arrayEstado_dosificacion_facctura.id;                       
                            me.nro_autorizacion_1=me.arrayEstado_dosificacion_facctura.nro_autorizacion;
                            me.dosificacion_1=me.arrayEstado_dosificacion_facctura.dosificacion; 
                            me.fecha_e_1=me.arrayEstado_dosificacion_facctura.fecha_e; 
                            me.n_ini_facturacion_1=me.arrayEstado_dosificacion_facctura.n_ini_facturacion; 
                            me.n_fin_facturacion_1=me.arrayEstado_dosificacion_facctura.n_fin_facturacion;     
                            me.n_act_facturacion_1=me.arrayEstado_dosificacion_facctura.n_act_facturacion;  
                            me.arrayEstado_dosificacion_facctura=respuesta.consulta;  
                        }
                      
                      break;
                      case 3:
                      me.key_Activacion=0;
                        //-------para                      
                      break;
                      case 10:  
                          Swal.fire({title: "Token caducado",text: "revicé: en configuración siat y actualice las fechas con su token de legado, si el error persite revise en la configuracion de impuestos nacionales y pida otro token.",icon: "error",});  
                      break;
                      case 11:  
                          Swal.fire({title: "CUIS caducado",text: "Pedir otro cuis en el sector de siat en la pestaña de cuis, revise si es sucursal o punto de venta.",icon: "error",});  
                      break;
                          case 12:  
                          Swal.fire({title: "CUFD caducado",text: "Pedir otro cufd en el sector de siat en la pestaña de cufd, revise si es sucursal o punto de venta",icon: "error",});  
                          break;
                          case 13:  
                          Swal.fire({title: "Error",text: "Debe esta correcto el cuis o cufd",icon: "error",});  
                          break;
                    
                      default:     
                      me.key_Activacion=2;                   
                      Swal.fire({title: "Error",text: "Manipulación no autorizada.",icon: "error",});                   
                      break;
                    }
                    
                               
                   
                }).catch(function (error) {
                    error401(error);
                });
        },

        listarDescuento_Tipo_tabla(){
            let me = this;       
            var url ="/gestor_ventas/listarDescuento_Tipo_tabla";            
            let contador_Normal=0;
            let contador_Dis_normal=0;
            
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data; 
                    me.arrayDescuentoOperacion=respuesta.descuento;   

                  
                    if (respuesta.validador==true && me.arrayDescuentoOperacion.length===1&&me.arrayDescuentoOperacion[0].id_nom_tabla===5) {
                        me.validadorPersonal=1;// caso personal
                        me.id_tabla_y2=me.arrayDescuentoOperacion[0].id_nom_tabla;                                       
                    } else {
                          if (me.arrayDescuentoOperacion.length===0) {
                            me.validadorPersonal=3;// caso cero no exite descuento
                            me.id_tabla_y2=0;
                          }else{
                            let contador_1_normal=0;
                                let contador_1_can_compra=0;
                                let contador_1_cliente=0;
                                let contador_1_producto=0;
                                let contador_1_final=0;
                               
                             
                            me.arrayDescuentoOperacion.forEach((descuento) => {   
                       
                            
                                if(descuento.id_nom_tabla===1){
                                    contador_1_normal++;
                                    me.id_tabla_y2=descuento.id_nom_tabla;
                                }
                                if(descuento.id_nom_tabla===2){
                                    contador_1_can_compra++;
                                }
                                if(descuento.id_nom_tabla===3){
                                    contador_1_cliente++;
                                }
                                if(descuento.id_nom_tabla===4){
                                    contador_1_producto++;
                                }
                                if(descuento.id_nom_tabla===6){
                                    contador_1_final++;
                                }
                                
                                

                            });
                            me.existe_cliente_f=contador_1_cliente;
                          
                                    if (contador_1_normal>0&&contador_1_can_compra===0&&contador_1_cliente===0&&contador_1_producto===0&&contador_1_final===0) {
                                        me.validadorPersonal=2;// caso normal sin otras tablas     
                                        return;                                  
                                    }
                                    if (contador_1_normal===0&&contador_1_can_compra>0&&contador_1_cliente===0&&contador_1_producto===0&&contador_1_final===0) {
                                        me.validadorPersonal=4;// caso normal sin otras tablas 
                                        return;                                      
                                    }
                                    if (contador_1_normal===0&&contador_1_can_compra===0&&contador_1_cliente>0&&contador_1_producto===0&&contador_1_final===0) {
                                        me.validadorPersonal=5;// caso normal cliente
                                        return;                                      
                                    }
                                    if (contador_1_normal===0&&contador_1_can_compra===0&&contador_1_cliente===0&&contador_1_producto>0&&contador_1_final===0) {
                                        me.validadorPersonal=6;// caso normal producto  
                                        return;                                    
                                    }
                                    if (contador_1_normal===0&&contador_1_can_compra===0&&contador_1_cliente===0&&contador_1_producto===0&&contador_1_final>0) {
                                        me.validadorPersonal=7;// caso normal final  
                                     
                                        return;                                    
                                    }

                                    //caso vario
                                    if (me.validadorPersonal===''||me.validadorPersonal===null||me.validadorPersonal>0) {
                                        me.validadorPersonal=99;
                                        me.existe_final=contador_1_final;
                                        return;
                                    }
                                
                          }


                    }   
             
                                            
                    
            }).catch(function (error) {
                    error401(error);
                });
        },
        

        listarDescuentos_listas() {
            let me = this;       
            var url ="/gestor_ventas/listarDescuentos_listas";            
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                    
                    me.id_descuento_x=response.data.id_descuento_x;
                 if (respuesta.lista) {
                    me.nom_lista=respuesta.lista.nombre_lista;
                    me.id_lista_v2=response.lista.id;
                 } else {
                    me.nom_lista="Sin lista";  
                    me.id_lista_v2=0;
                 }

                 if (respuesta.descuento.length>0) {

                    respuesta.descuento.forEach(des => {
                        if (des.id_tabla && des.id_tabla===3) {
                            me.existe_cliente=1;
                        }
                        if (des.id_tabla===4) {
                            me.existe_producto=1;
                        }
                   });
                            me.arrayDescuento=respuesta.descuento;
                            
                 } else {
                    me.descuentoNombre="Sin descuentos";
                 }


            }).catch(function (error) {
                    error401(error);
                });
        },


        limpiar_cantida_cantida(){
            let me = this;          
            me.numero='';
            me.descuento='';
        },

        quitarVEnta(id){            
            let me = this;
            let verificador=me.arrayVentas.find(venta => venta.id_contador === id);          
            let sumadoT= me.sumatotal;      
            me.sumatotal = parseFloat((Number(sumadoT) - Number(verificador.subtotal)).toFixed(2));  
          
me.tota_del_total = parseFloat((me.sumatotal - Number(me.descuento_final)).toFixed(2));  

me.monto_a_pagar=parseFloat((me.tota_del_total - Number(me.gift_value)).toFixed(2)); 
me.importe_fiscal=me.monto_a_pagar;

if(Number(me.efectivo) > 0){
    me.cambio = parseFloat((Number(me.efectivo) - me.tota_del_total).toFixed(2)); 
    me.cambio =  me.cambio.toFixed(2);
}

          me.arrayProducto_recibo_1 =me.arrayProducto_recibo_1.filter(ve => ve.id_contador !== id);
           me.arrayVentas = me.arrayVentas.filter(venta => venta.id_contador !== id);
           me.array_vetasQuery=me.array_vetasQuery.filter(ven => ven.id_contador !==id);
           const totalDescuento = me.array_vetasQuery.reduce((total, venta) => {
  return total + (parseFloat(venta.descuento) || 0);
}, 0);
 
            if (me.arrayVentas.length===0) {
               me.contador_cliente=0;
               me.arrayVentas=[];
               me.sumatotal=0;
               me.descuento_final=0; 
                me.tota_del_total=0;
                me.controlador_venta_id=0;
                me.efectivo=0;
                me.cambio=0;
                me.gift_value=0;
                me.monto_a_pagar=0;
                me.importe_fiscal=0;
            }           
          },


          getColorByPriority(prioridad) {
        switch (prioridad) {
            case 0: // NingunagetColorByPriority
                return '#AAB3AA'; // Negro
            case 1: // Bajo
                return '#00ff01'; // Verde
            case 2: // Medio
                return '#ccff00'; // Amarillo
            case 3: // Alta
                return '#FF0003'; // Rojo
            default:
                return '#AAB3AA'; // Color por defecto, negro
        }
    },

        agregarVenta(){
            try {
                            let me = this;      
                            me.controlador_venta_id++;     
                            if (me.validadorPersonal===99) {
                              
                if (me.num_documento===''||me.datos_cliete==='') {
                Swal.fire(
                    "Primero debe seleccionar un cliente",
                    "Haga click en Ok",
                    "error"
                );
            return ;
            }else{
                me.contador_cliente=1;
                let sumador_21_sub = 0;
                let sumador_21_des = 0;
           
                
                me.arrayDescuentoOperacion.forEach((descuento) => {
                    if (descuento.id_nom_tabla===1) {
                        let operacion_21 = this.operacion_Numeral_Procentaje(descuento.tipo_num_des, descuento.monto_descuento, this.selected.precio_lista_gespreventa);
                        let { valorNumeral, porcentaje } = operacion_21;
                        sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                        sumador_21_des += parseFloat(porcentaje);            
                        me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(porcentaje),tipo:1});

                    }
                    if (descuento.id_nom_tabla===2) {
                        let var_c = this.operacion_cantida_valor(descuento.tipo_can_valor,descuento.regla,descuento.cantidad_valor,me.selected.precio_lista_gespreventa);
                    if (var_c) {
                        let operacion_21 = this.operacion_Numeral_Procentaje(descuento.tipo_num_des, descuento.monto_descuento, me.selected.precio_lista_gespreventa);
                    let { valorNumeral, porcentaje } = operacion_21;
               
                    sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                    sumador_21_des += parseFloat(porcentaje);
                    me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(porcentaje),tipo:1}); 
                    }
                    else{
                    sumador_21_sub += parseFloat(0);  // Acumular los valores numerales como float
                    sumador_21_des += parseFloat(0); 
                    me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(0),tipo:1});  
                    }
                }
                    if (descuento.id_nom_tabla===3) {
                      
                        if (me.cliente_activo_descuento===1&&me.cliente_id_sucursal_desc===me.id_descuento_x) {
                    let operacion_21 = this.operacion_Numeral_Procentaje(me.cliente_num_por, me.cliente_des, this.selected.precio_lista_gespreventa);                   
                    let { valorNumeral, porcentaje } = operacion_21;
                    sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                    sumador_21_des += parseFloat(porcentaje); 
                    me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(porcentaje),tipo:1}); 
                    
                    }else{
                        sumador_21_sub += parseFloat(0);  // Acumular los valores numerales como float
                    sumador_21_des += parseFloat(0);  
                    me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(0),tipo:1});  
                    }
                    }
                    if (descuento.id_nom_tabla===4) {
                        if (me.selected.descuento_activo===1 && me.selected.id_11===me.id_descuento_x) {
                            let operacion_21 = this.operacion_Numeral_Procentaje(this.selected.tipo_num_des, this.selected.monto_descuento, this.selected.precio_lista_gespreventa);
                            let { valorNumeral, porcentaje } = operacion_21;
                            sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                            sumador_21_des += parseFloat(porcentaje); 
                            me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(porcentaje),tipo:1}); 
                   
                        }else{
                            sumador_21_sub += parseFloat(0);  // Acumular los valores numerales como float
                            sumador_21_des += parseFloat(0); 
                            me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(0),tipo:1});  
                      
                        }
                    }                      
            });
            me.descuento=parseFloat(sumador_21_des.toFixed(2));
                var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;

            if (sumador_21_des>me.selected.precio_lista_gespreventa) {
                sumador_21_des=0.00;
            } 
            descuento=sumador_21_des;
            me.descuento_1 += parseFloat(descuento);
            subtotal=precioXcantida-descuento;
            
            }   
               
             }   



            // caso sin descuento-----
          if (me.validadorPersonal===3) {
            var descuento=0;
            me.descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            me.descuento_1 +=parseFloat(descuento);      
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            subtotal=precioXcantida;
            //me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:0,id_descuento:0,cantidad_descuento:0.00,tipo:1});
            
          }
          //caso personalizado validoPersonal=1
          if (me.validadorPersonal===1) {
            
            var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
            descuento=me.descuento;
     
            me.descuento_1 +=parseFloat(descuento);
             
            subtotal=precioXcantida-descuento;
            me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:me.caso5_id_tabla_p,id_descuento:me.caso5_id ,cantidad_descuento:parseFloat(descuento),tipo:1});
            //subtotal=precioXcantida*descuento;
          }

          ///////////CASO NORMAL /////////////////////////////////////////////
          if (me.validadorPersonal===2) {
            let sumador_21_sub = 0;
            let sumador_21_des = 0;

                me.arrayDescuentoOperacion.forEach((descuento) => {
                let operacion_21 = this.operacion_Numeral_Procentaje(descuento.tipo_num_des, descuento.monto_descuento, this.selected.precio_lista_gespreventa);
                let { valorNumeral, porcentaje } = operacion_21;
                  
                    sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                    sumador_21_des += parseFloat(porcentaje);    
                    me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id ,cantidad_descuento:parseFloat(porcentaje),tipo:1});   
            });
            me.descuento=parseFloat(sumador_21_des.toFixed(2));
            var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
        
            descuento=sumador_21_des;
            me.descuento_1 +=parseFloat(descuento);
            subtotal=precioXcantida-descuento;
            //subtotal=sumador_21_sub;
            
            //subtotal=precioXcantida*descuento;      
        
          }
            /////////////////////////DESCUENTO CATIDAD O COMPRA//////////////////////////////////
         if (me.validadorPersonal===4) {
           let asigner=false;
           let sumador_21_sub = 0;
            let sumador_21_des = 0;

                me.arrayDescuentoOperacion.forEach((descuento) => {
                    let var_c = this.operacion_cantida_valor(descuento.tipo_can_valor,descuento.regla,descuento.cantidad_valor,me.selected.precio_lista_gespreventa);
                    if (var_c) {
                        let operacion_21 = this.operacion_Numeral_Procentaje(descuento.tipo_num_des, descuento.monto_descuento, me.selected.precio_lista_gespreventa);
                    let { valorNumeral, porcentaje } = operacion_21;
                    sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                    sumador_21_des += parseFloat(porcentaje); 
                    me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id ,cantidad_descuento:parseFloat(porcentaje),tipo:1});
            
                    }
                    else{
                    sumador_21_sub += parseFloat(0);  // Acumular los valores numerales como float
                    sumador_21_des += parseFloat(0);  
                    me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id ,cantidad_descuento:parseFloat(0),tipo:1});
            
                    }
                });
                me.descuento=parseFloat(sumador_21_des.toFixed(2));
                
                var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
        
            descuento=sumador_21_des;
            me.descuento_1 += parseFloat(descuento);
            subtotal=precioXcantida-descuento;
            //subtotal=sumador_21_sub;
            
            //subtotal=precioXcantida*descuento;  
         } 
         ////////////////////////////CLIENTE //////////////////////////
         if (me.validadorPersonal===5) {
            if (me.num_documento==='') {
                Swal.fire(
                    "Primero debe seleccionar un cliente",
                    "Haga click en Ok",
                    "error"
                );
            return ;
            }else{
        
                me.contador_cliente=1;     
                if (me.cliente_activo_descuento===1&&me.cliente_id_sucursal_desc===me.id_descuento_x) {

                    let operacion_21 = this.operacion_Numeral_Procentaje(me.cliente_num_por, me.cliente_des, this.selected.precio_lista_gespreventa);
                    
                    
                    let { valorNumeral, porcentaje } = operacion_21;
                    me.descuento = porcentaje;
   
                    var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
        
            descuento= me.descuento;
            me.descuento_1 += parseFloat(descuento);
            subtotal=precioXcantida-descuento;
            me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:me.cliente_id_tabla, id_descuento:me.cliente_id_descuento ,cantidad_descuento:parseFloat(descuento),tipo:1});
            me.cliente_bandera=0;          
                } else {
                    me.descuento=0;
                    var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
        
            descuento= me.descuento;
            me.descuento_1 +=descuento;
            subtotal=precioXcantida-descuento; 
            me.cliente_bandera=1;
               }         

            }
          
         }
         ////////////////////PRODUCTOS////////////////////////////
         if (me.validadorPersonal===6) {
               if (me.selected.descuento_activo===1 && me.selected.id_11===me.id_descuento_x) {
                let operacion_21 = this.operacion_Numeral_Procentaje(this.selected.tipo_num_des, this.selected.monto_descuento, this.selected.precio_lista_gespreventa);
                let { valorNumeral, porcentaje } = operacion_21;
                me.descuento = porcentaje;
                    var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
        
            descuento= me.descuento;
            subtotal=precioXcantida-descuento;
           me.array_ven__detalle_descuentos.push({id_contador:me.controlador_venta_id,id_tabla:this.selected.id_tabla,id_descuento:this.selected.id_descuento ,cantidad_descuento:parseFloat(porcentaje),tipo:1});            
               } else {
                me.descuento=0;
               var descuento=0;
       var precioXcantida=0;
       var subtotal=0;
       var final_d=0; 
       var total_d=0;
       precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
    
       descuento= me.descuento;
       me.descuento_1 +=parseFloat(descuento);
       subtotal=precioXcantida-descuento; 
      
            me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:0,id_descuento:0,cantidad_descuento:parseFloat(0),tipo:1});

   
               }
               
           
               
    }
         if (me.validadorPersonal===7) {
               
                    me.descuento=0;
                    var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            var final_d=0; 
            var total_d=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
         
            descuento= me.descuento;
       
            subtotal=precioXcantida-descuento; 
                
                    
         }
          me.sumatotal=me.sumatotal+subtotal;

          me.tota_del_total=me.sumatotal;
      
me.monto_a_pagar=me.tota_del_total-me.gift_value;
me.importe_fiscal=me.monto_a_pagar;
            // Convertir a un número con dos decimales
            subtotal = parseFloat(subtotal.toFixed(2));
            me.arrayVentas.push({id_contador:me.controlador_venta_id, id: me.selected.id,leyenda: me.selected.leyenda,cantidad:me.numero,precio:me.selected.precio_lista_gespreventa,
            descuento: descuento,subtotal:subtotal,id_ingreso:me.selected.id_ingreso,id_pro:me.selected.id_prod,nombre_linea:me.selected.nombre_linea
            });
            let es_lista=0;
            if (me.selected.id_lista==="x") {
                es_lista=0;
            } else {
                es_lista=me.selected.id_lista;
            }
            let may_leyenda=(me.selected.leyenda).toUpperCase();
            me.codigo_tienda_almacen=me.selected.codigo_tienda_almacen;
            me.array_vetasQuery.push({id_contador:me.controlador_venta_id,descuento: descuento,es_lista: es_lista,id_ges_pre:me.selected.id,id_ingreso:me.selected.id_ingreso,id_producto:me.selected.id_prod,id_linea:me.selected.id_linea,precio_venta:me.selected.precio_lista_gespreventa,cantidad_venta:me.numero,codigo_tienda_almacen:me.selected.codigo_tienda_almacen});
            me.arrayProducto_recibo_1.push({id_contador:me.controlador_venta_id,cant:me.numero,descrip:may_leyenda,p_u:me.selected.precio_lista_gespreventa,unidad_medida:me.selected.unidad_medida,descuento: descuento,cod_pro:me.selected.codigo_prod,codigoActividad:me.selected.codigoActividad,codigoProducto:me.selected.codigoProducto,id_unidad_me:me.selected.id_unidad_medida});
            if (me.validadorPersonal===7 || me.existe_final>0) {
            let sumador_21_sub = 0;
            let sumador_21_des = 0;

                me.arrayDescuentoOperacion.forEach((descuento) => {
                if (descuento.id_nom_tabla===6) {
                    let operacion_21 = this.operacion_Numeral_Procentaje(descuento.tipo_num_des, descuento.monto_descuento, me.sumatotal);
                let { valorNumeral, porcentaje } = operacion_21;
                sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                sumador_21_des += parseFloat(porcentaje); 
                }                    
               

            });
          
            me.descuento_final=parseFloat(sumador_21_des.toFixed(2));
            var descuento=me.descuento_final;
                
            descuento=sumador_21_des;
           
            me.tota_del_total=me.sumatotal-descuento;
            me.monto_a_pagar=me.tota_del_total-me.gift_value;
            me.importe_fiscal=me.monto_a_pagar;
          }
        
            } catch (error) {
                console.log(error);
            }          
        },

        filtrarNumeros(event) {
      // Obtener el código de la tecla presionada
      let charCode = event.keyCode;

      // Permitir las teclas de control (por ejemplo, borrar, retroceso, tabulación, flechas)
      if ((charCode >= 48 && charCode <= 57) ||  // Números del 0 al 9
          charCode === 8 ||  // Tecla de retroceso
          charCode === 46 || // Tecla de eliminar
          charCode === 37 || // Tecla de flecha izquierda
          charCode === 39 || // Tecla de flecha derecha
          (charCode >= 96 && charCode <= 105)) { // Teclado numérico
        // Permitir la entrada
      } else {
        // Si la tecla presionada no es permitida, prevenir su acción por defecto
        event.preventDefault();
      }
    },
  
  
  

        nameWithLang ({leyenda, nombre_linea,fecha_vencimiento}) {
            
      return `${leyenda} ${nombre_linea} FV:${fecha_vencimiento}`
    },

        toggle () {
      this.$refs.multiselect.$el.focus()

      setTimeout(() => {
        this.$refs.multiselect.$refs.search.blur()
      }, 1000)
    },
    open () {
      this.$refs.multiselect.activate()
    },
    close () {
      this.$refs.multiselect.deactivate()
    },
  
  
        validateInput() {
        this.num_documento2 = this.num_documento2.replace(/[^a-zA-Z0-9]/g, '');
        },
        cambiarEstadoSElectrorCliente(){
            let me=this;
            me.pais='';
            me.ciudad='';
            me.direccion='';
            me.telefono='';
            me.num_documento2='';
            me.correo='';
            me.nombre_a_facturar='';
            me.nombres='';
            me.selectEX=0; 
            me.apellidos='';
            me.selectTipoDoc=0;
        
        },
        listarEX(){
            let me=this;
            var url='/listarEx';           
            axios.get(url).then(function(response){
                var respuesta=response.data;
                    me.arrayEX=respuesta;
            }).catch(function(error){
                    error401(error);
                    
                })
        },
        
        
        listarTipoDoc(){
            let me=this;
            var url='/listarTipoDoc';            
            axios.get(url).then(function(response){
                var respuesta=response.data;
                me.arrayTipoDocumento=respuesta;
            }).catch(function(error){
                    error401(error);
           
                })
        },  

        caso_loteCliente(cliente){
     let me=this;
           //this.id_tipo_doc=cliente.id_tipo_doc;
       // this.cliente_id=cliente.id;
       // this.nom_a_facturar=cliente.nom_a_facturar;
       // this.num_documento=cliente.num_documento;
       // this.datos_cliete=cliente.nom_a_facturar+"/"+cliente.num_documento+"/"+cliente.tipo_doc_datos+"-"+cliente.tipo_doc_nombre;
       // this.key_1=1;
       // me.cliente_id=response.data.id;
                    
                    if (me.cliente_id==undefined) {
                        me.datos_cliete="No se encontro cliente..."
                        me.key_1=0;
                        me.TipoComprobate=0;
                        me.cliente_des=0;
                        me.cliente_num_por=0;
                    } else {
                        me.cliente_des=cliente.monto_descuento;
                    me.cliente_num_por=cliente.tipo_num_des;
                    me.cliente_activo_descuento=cliente.descuento_activo;
                    me.cliente_id_sucursal_desc=cliente.id_11;                    
                    me.cliente_id_descuento=cliente.id_descuento; 
                    me.cliente_id_tabla=cliente.id_tabla;
                        me.id_tipo_doc=cliente.id_tipo_doc;
                        me.nom_a_facturar=cliente.nom_a_facturar;
                        me.num_documento=cliente.num_documento;
                        me.cliente_id=cliente.id;
                        me.correo_cliente=cliente.correo;
                        me.datos_cliete=cliente.nom_a_facturar+"/"+cliente.num_documento+"/"+cliente.tipo_doc_datos+"-"+cliente.tipo_doc_nombre; 
                        me.key_1=1;
                    }
        this.cerrarModal('lote_cliete')
        },

        listarUsuarioRetorno() {
            let me = this;       
            var url ="/gestor_ventas/listarUsuarioRetorno?buscar="+this.buscar;            
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                  
                    me.arrayClienteLote = respuesta;
            }).catch(function (error) {
                    error401(error);
                });
        },

      caso_99001(){
        this.id_tipo_doc=4;
        this.cliente_id=0;
        this.num_documento=99001;
        this.correo_cliente="";
        this.nom_a_facturar=this.razon_social_99001;
        this.TipoComprobate=2;
        this.key_1=1;
        this.datos_cliete=this.nom_a_facturar+"/"+this.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
        
        this.cerrarModal('cliente_modal');       
      },
    

      restartotal(){
                let me=this;
                if(me.efectivo!=0)
                    me.cambio=Number(me.efectivo-me.monto_a_pagar);
                else
                    me.cambio=0;
            },
      reset_gift(){
        let me=this;
        if(me.gift_value!=0){
          me.monto_a_pagar=Number(me.tota_del_total-me.gift_value);
          me.importe_fiscal=me.monto_a_pagar;
          me.efectivo=0;
          me.cambio=0;
        }                
                else{
                  me.monto_a_pagar=me.tota_del_total;
                  me.importe_fiscal=me.monto_a_pagar;
                  me.efectivo=0;
                  me.cambio=0;
                }                    
      },      

      limpiar(){
        this.id_tipo_doc="";
        this.cliente_id="";
        this.num_documento="";
        this.correo_cliente="";
        this.nom_a_facturar="";
        //var documento      
        this.datos_cliete="";
        this.razon_social_000="";
        this.buscarCliente="";
        this.key_1=0;
      },

        listarUsuario() {
            let me = this;
            var url = "/gestor_ventas/listarUsuario?num_doc="+me.buscarCliente;         
            var opcion=me.buscarCliente;
            switch(opcion) {
    case '99001': 
      if (me.key_Activacion==1) {
        me.abrirModal('cliente_modal');
      }else{
        me.datos_cliete="No se encontro cliente...";
      }       
        break;
    case '99002':
    if (me.key_Activacion==1) {
      me.id_tipo_doc=4;
            me.cliente_id=0;
            me.num_documento=99002;
            me.nom_a_facturar="CONTROL TRIBUTARIO";
            me.correo_cliente="";
            me.datos_cliete=me.nom_a_facturar+"/"+ me.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
            me.TipoComprobate=2;
            me.key_1=1;
      }else{
        me.datos_cliete="No se encontro cliente...";
      }            
        break;
    case '99003':
    if (me.key_Activacion==1) {
      me.id_tipo_doc=4;
        me.cliente_id=0;
        me.num_documento=99003;
        me.correo_cliente="";
        me.nom_a_facturar="VENTAS MENORES DEL DIA";
        me.datos_cliete=me.nom_a_facturar+"/"+me.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
        me.TipoComprobate=2;
        me.key_1=1;
      }else{
        me.datos_cliete="No se encontro cliente...";
      }        
    break;
    case '0':
    if (me.key_Activacion==1) {
      me.id_tipo_doc=4;
        me.cliente_id=0;
        me.num_documento=0;
        me.nom_a_facturar="SIN NOMBRE";
        me.correo_cliente="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com";
        me.datos_cliete=me.nom_a_facturar+"/"+me.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
        me.TipoComprobate=2;
        me.key_1=1;
      }else{
        me.datos_cliete="No se encontro cliente...";
      }       
    break;
    case '000':    
    me.id_tipo_doc=4;
        me.cliente_id=0;
        me.num_documento="000";
        me.nom_a_facturar="S/N";
        me.correo_cliente="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com";
        me.datos_cliete=me.nom_a_facturar+"/"+me.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
        me.TipoComprobate=1;
        me.key_1=1;
    break;
    default:
    axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.cliente_id=response.data.id;
                    me.cliente_des=response.data.monto_descuento;
                    me.cliente_num_por=response.data.tipo_num_des;
                    me.cliente_activo_descuento=response.data.descuento_activo;
                    me.cliente_id_sucursal_desc=response.data.id_11;                    
                    me.cliente_id_descuento=response.data.id_descuento; 
                    me.cliente_id_tabla=response.data.id_tabla;
                    if (me.cliente_id==undefined) {
                        me.datos_cliete="No se encontro cliente...";
                        me.key_1=0;
                        me.TipoComprobate=0;
                    } else {
                        me.id_tipo_doc=response.data.id_tipo_doc;
                        if(me.id_tipo_doc===1){
                          me.complemento_siat=response.data.complemento_s;                        
                        }
                        me.nom_a_facturar=response.data.nom_a_facturar;
                        me.num_documento=response.data.num_documento;
                        me.cliente_id=response.data.id;
                        me.correo_cliente=response.data.correo;
                        me.datos_cliete=response.data.nom_a_facturar+"/"+response.data.num_documento+"/"+response.data.tipo_doc_nombre;  
                        me.key_1=1;
                    }
                  
                })
                .catch(function (error) {
                    error401(error);
                });
        break;
        }
          
          
        },

        validateNumber() {
      // Permite solo números y corta a 15 caracteres si es necesario
      this.num_documento = this.num_documento.replace(/[^0-9]/g, '').slice(0, 12);
    },

        estadoEX(){
            if (this.selectTipoDoc!=1) {
                this.complemento_='';  
            }
        },
       
        listarSucursalGet() {
            let me = this;
          
           // var url = "/listarSucursalGet";
           var url = "/gestor_ventas/get_sucusal";
           
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                   
                    if (respuesta==="000") {
                      Swal.fire("Error","El usuario debe tener rubro, contacte al administrador...","warning",);
                    }else{
                      me.arrayProducto = respuesta;
                    }                   
                
                })
                .catch(function (error) {
                    error401(error);
                });
        },


        volverLista(data){          
          let me =this;
          me.cadenaProducto_2=data;
          me.selected=data; 
          me.cerrarModal('vista_bloque');
        },

        listarProducto_seleccionado() {
            let me = this;
           // var url = "/listarSucursalGet";
           var url = "/gestor_ventas/get_producto_bloque?producto="+(me.selected).prod_name;
           
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProducto_2  = respuesta;
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
        //    me.listarAjusteNegativos(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "sin usar";
            
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;                 
                      
                    me.classModal.openModal("registrar");
                    break;
                }
                case "cliente_modal":{
                 
                    me.name_moda='OD-OTRO DOCUMENTO DE IDENTIDAD 99001.';
                    me.v99001='';
                      me.showModal = true;
                    me.id_tipo_doc='';
                    me.razon_social_99001="";
                    me.cliente_id="";
                   me.TipoComprobate=0;
                    me.classModal.openModal("cliente_modal");
                    break;
                }
                
                case "lote_cliete":{
                    me.buscar='';
                       me.showModal_2 = true;
                    me.id_tipo_doc=''
                    me.cliente_id='';
                    me.nom_a_facturar='';
                    me.num_documento='';
                    me.datos_cliete='';
                   me.TipoComprobate=0;
                    document.addEventListener('keydown', this.preventEnter);
                    me.classModal.openModal("lote_cliete");
                    break;
                }
                case "registrar_cliente": {
                   
                    me.tituloModal = "Registro de clientes";
                    me.pais='';
                    me.ciudad='';
                    me.direccion='';
                    me.telefono='';
                    
                    me.correo='';
                    me.nombre_a_facturar='';
                    me.nombres='';
                    me.selectEX=0; 
                    me.apellidos='';
                    me.num_documento='';
                    me.selectTipoDoc=0;
                    me.selectTipo=0;
                    me.complemento_='';
                    me.extencion_tipodocumento='';
                    me.nombre_documento='';
                    me.num_documento2='';
                    me.TipoComprobate=0;
                    me.complemento_siat='';
                        me.showModal_3 = true;
                    me.classModal.openModal("registrar_cliente");
                    break;
                } 
                case "vista_bloque":{
                  me.tituloModal = "Vista por producto precio";
                  me.cadenaProducto_2="";
                      me.showModal_4 = true;
                  me.classModal.openModal("vista_bloque");
                  break;
                }           
            }
        },


        preventEnter(event) {
      if (event.key === 'Enter') {
        event.preventDefault();
      }
    },


        cerrarModal(accion) {
            let me = this;  
              me.showModal = false; 
              me.showModal_2 = false; 
              me.showModal_3 = false; 
              me.showModal_4 = false; 

            if (accion == "vista_bloque") {
                me.arrayProducto_2=[];
                me.cadenaProducto_2="";
                me.classModal.closeModal(accion);           
            } else {
              me.classModal.closeModal(accion);
              me.tituloModal = "";
              me.buscarCliente ="";
              me.pais='';
                    me.ciudad='';
                    me.direccion='';
                    me.telefono='';
                    me.num_documento2='';
                    me.correo='';
                    //me.nombre_a_facturar='';
                    //me.nombres='';
                    //me.selectEX=0; 
                    //me.apellidos='';
                    //me.selectTipoDoc=0;
                    //me.selectTipo=0;
                    //me.complemento_='';
                   // me.extencion_tipodocumento="";
                   // me.nombre_documento="";    
            me.v99001="";
            }                           
        },

        realizarVenta() {
        let me = this;
        var validador=0;
          if (me.tipo_pago_Qr_con_tar<=0) {
            Swal.fire("Tipo de pago sin seleccionar","Debe seleccionar tipo de pago, que esta debajo de detalle de cliente","error");
          } else {
            let cadena=String(me.num_documento);
            let cadena_tipo=String(me.TipoComprobate);

     

            switch (cadena_tipo) {
                case "1":
                if ("0"===cadena||"99001"===cadena||"99002"===cadena||"99003"===cadena) {
                Swal.fire(
                    "No pudo realizar la venta",
                    "Un recibo no puede tener datos de factura como ser 0, 99001, 99002, 99003",
                    "error"
                );
                         
                }else{
                  if (me.selectPago_==="7") {
                    if (me.numeroTarjeta_siat===""||me.numeroTarjeta_siat===null||me.selectBanco_v==="0") {
                      validador=1;
              Swal.fire("Error","Tipo QR debe llenar los datos que en datos extra.","error");
                    } else {
              validador=0;
                    }
                  }
                  if (me.selectPago_==="2") {
                    if (me.numeroTarjeta_siat===""||me.numeroTarjeta_siat===null) {
                      validador=1;
              Swal.fire("Error","Debe llenar el numero de tarjeta con un numero 16 digitos ejemplo 4797123456727896.","error");
                    } else {
              validador=0;
                    }
                  }    

              if(validador===0){
                me.EnviarRecibo();
              }

                  
                }
                    break;
            
                case "2":
                    if ("000"===cadena) {
                        Swal.fire(
                    "No pudo realizar la venta",
                    "Un recibo no puede tener datos de factura como ser 000",
                    "error"
                ); 
                    } else {
                        //----facturacion
                        if(me.estado_dosificacion_facctura===1){
                          if (me.selectPago_==="7") {
                    if (me.numeroTarjeta_siat===""||me.numeroTarjeta_siat===null||me.selectBanco_v==="0") {
                      validador=1;
              Swal.fire("Error","Tipo QR debe llenar los datos que en datos extra.","error");
                    } else {
              validador=0;
                    }
                  }  

                  if (me.selectPago_==="2") {
                    if (me.numeroTarjeta_siat===""||me.numeroTarjeta_siat===null||(!/^\d{16}$/.test(me.numeroTarjeta_siat))) {
                      validador=1;
                      Swal.fire("Error","Debe llenar el numero de tarjeta con un numero 16 digitos ejemplo 4797123456727896.","error");
                    } else {
              validador=0;
                    }
                  }  

              if(validador===0){
                me.EnviarFactura();
              }
                        
                     
                        } else {
                            if (me.estado_dosificacion_facctura===2) {
                              if (me.selectPago_==="7") {
                    if (me.numeroTarjeta_siat===""||me.numeroTarjeta_siat===null||me.selectBanco_v==="0") {
                      validador=1;
              Swal.fire("Error","Tipo QR debe llenar los datos que en datos extra.","error");
                    } else {
              validador=0;
                    }
                  }  

              if(validador===0){
                me.EnviarRecibo();
              }
                            
                            } else {
                                Swal.fire("Acción no valida","Error inesperado","error"); 
                            }
                        }                   
                    }
                    break;    
                default:
                Swal.fire(
                    "Error no esperado",
                    "Contacte al administrador.",
                    "error"
                );
                    break;
            } 
          }
           
      },

    EnviarRecibo(){
        let me = this; 
    
              if (me.validadorPersonal===3) {           
            me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:0,id_descuento:0,cantidad_descuento:0.00,tipo:1});            
          }
          if (me.cliente_bandera === 1&&me.validadorPersonal===5) {
            me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:0,id_descuento:0,cantidad_descuento:0.00,tipo:1});
          }
          if (me.validadorPersonal===7 || me.existe_final>0) {
            let sumador_21_sub = 0;
            let sumador_21_des = 0;

                me.arrayDescuentoOperacion.forEach((descuento) => {
                if (descuento.id_nom_tabla===6) {
                    let operacion_21 = this.operacion_Numeral_Procentaje(descuento.tipo_num_des, descuento.monto_descuento, me.sumatotal);
                let { valorNumeral, porcentaje } = operacion_21;
                sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                sumador_21_des += parseFloat(porcentaje); 
                me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(porcentaje),tipo:2});
                }                
            });      
          }
          
        const totalDescuento = me.array_vetasQuery.reduce((total, venta) => {
        return total + (parseFloat(venta.descuento) || 0);
}, 0);
me.descuento_1=totalDescuento+me.descuento_final;
        const data = {
          TipoComprobate: me.TipoComprobate,
          num_documento: me.num_documento,
          id_tipo_doc: me.id_tipo_doc,
          cliente_id: me.cliente_id,
          nom_a_facturar: me.nom_a_facturar,
          correo_cliente: me.correo_cliente,
          tipoPago:me.selectPago_,        

          numeroTarjeta:me.numeroTarjeta_siat,
          cadenaOtros:me.cadenaOtros,
          tipoBanco:me.selectBanco_v,

          gift_value:me.gift_value,
          monto_a_pagar:parseFloat(me.monto_a_pagar).toFixed(2),
          importe_fiscal:parseFloat(me.importe_fiscal).toFixed(2),
          tipo_pago_Qr_con_tar:me.tipo_pago_Qr_con_tar,
          tipo_contado_s:me.tipo_contado_s,
          tipo_qr_s:me.tipo_qr_s,
          tipo_tarjeta:me.tipo_tarjeta, 
          tipo_gift_:me.tipo_gift_,
          tipo_pago_siat:me.selectPago_,

          total_venta:me.tota_del_total,
          efectivo_venta:me.efectivo,
          cambio_venta:me.cambio,
          descuento_venta:me.descuento_1,
          arrayProRecibo:me.arrayProducto_recibo_1,
          arrayDescuentoOperacion: me.array_ven__detalle_descuentos, // Incluir el array en el objeto data
          arrayDesatlleVenta: me.array_vetasQuery,
          codigo_tienda_almacen_0:me.codigo_tienda_almacen,
          id_lista_v2:me.id_lista_v2,
          id_apertura_cierre:me.id_apertura_cierre,

          estado_dosificacion_facctura:me.estado_dosificacion_facctura,
          id_dosificacaion_1:me.id_dosificacaion_1,
          nro_autorizacion_1:me.nro_autorizacion_1,  
          dosificacion_1:me.dosificacion_1,               
          fecha_e_1:me.fecha_e_1,            
          n_ini_facturacion_1:me.n_ini_facturacion_1,     
          n_fin_facturacion_1:me.n_fin_facturacion_1,  
          n_act_facturacion_1:me.n_act_facturacion_1,                        
        //  arrayEstado_dosificacion_facctura:me.arrayEstado_dosificacion_facctura,    

      };
       // Si ya está enviando, no permitas otra solicitud
       if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón

      // Realizar la solicitud POST con Axios
      axios.post("/gestor_ventas/venta", data)
          .then(response => {
              // Cerrar el modal
              me.resetVenta();
              me.listarSucursalGet();
              me.cerrarModal("registrar");
              if (response.data===12) {
                Swal.fire({
                  title: "Logitud de numero de factura paso el limite de 12 digitos",
                  text: "Haga click en Ok ",
                  icon: "error",
              });  
              } else {
                me.respuesta = response.data.data;
              if (me.respuesta==='1') {
                  Swal.fire({
                  title: "El usuario root no puede realizar ventas",
                  text: "Haga click en Ok ",
                  icon: "error",
              });  
              } else {
                  let respuesta= response.data;

let direccionMayusculas = respuesta.direccionMayusculas;
let nomsucursal = respuesta.nomsucursal;
let nuevoComprobante = respuesta.nuevoComprobante;
let fecha = respuesta.fecha;
let hora = respuesta.hora;
let num_documento = respuesta.num_documento;
let nom_a_facturar = respuesta.nom_a_facturar;
let array_recibo = respuesta.array_recibo;
let total_sin_des = respuesta.total_sin_des;
let descuento_venta = respuesta.descuento_venta;
let total_venta = respuesta.total_venta;
let efectivo_venta = respuesta.efectivo_venta;
let cambio_venta = respuesta.cambio_venta;
let fechaMas7Dias = respuesta.fechaMas7Dias;
let numero_referencia = respuesta.numero_referencia;
let nombreCompleto_1 = respuesta.nombreCompleto_1;
let tipocom = respuesta.tipocom;
let nombre_empresa = respuesta.nombre_empresa;
let ciudad_su_1 = respuesta.ciudad_su_1; 
let departamento_su_1 = respuesta.departamento_su_1;
let descuento_final_2 = respuesta.descuento_final_2;
let actividad_economica = respuesta.actividad_economica;
let num_auto = respuesta.num_auto;
let cod_autorizacion = respuesta.cod_autorizacion;
let fecha_e_2 = respuesta.fecha_e_2;
let numero_factura = respuesta.numero_factura;
let cliente_id = respuesta.cliente_id;
let total_literal=respuesta.total_literal;
let nit_2=respuesta.nit_2;
let tipo_venta_1="S/N";
switch (respuesta.tipo_venta) {
  case 1:
    tipo_venta_1="EFECTIVO";
    break;
    case 2:
    tipo_venta_1="QR";
    break;
    case 3:
    tipo_venta_1="TARJETA";
    break;
    case 4:
    tipo_venta_1="GIFTCARD";
    break;  
  default:
    tipo_venta_1="OTROS";
    break;
}
let monto_vale_1=respuesta.monto_vale; 
let monto_apagar_1=respuesta.monto_apagar;     


// Mostrar la alerta de éxito
                if (tipocom===1) {
                  me.isSubmitting=false;
                    Swal.fire({
  title: "Venta realizada",
  text: "Haga click en Ok ",
  icon: "success",
})
me.generarPDF(direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,
total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,nombre_empresa,tipo_venta_1,monto_vale_1,monto_apagar_1
); 
                } else{
                    if (tipocom===2) {
                      me.isSubmitting=false;
                        Swal.fire({
                        title: "Venta realizada",
                        text: "Haga click en Ok ",
                        icon: "success",
                        })
                        ///falta modelo de factura ------------
                        me.generarPDF_factura_dosificacion(direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,
total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,nombre_empresa,actividad_economica,num_auto,cod_autorizacion,fecha_e_2,ciudad_su_1,departamento_su_1,numero_factura,cliente_id,descuento_final_2,total_literal,nit_2,tipo_venta_1,monto_vale_1,monto_apagar_1
); 
                    } 
                }

                  
              }
              } 

          })
          .catch(error => {
              // Manejar el error
              if (error.response && error.response.status === 500) {
                  me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                  Swal.fire(
                      "Error",
                      "500 (Internal Server Error): " + me.errorMsg, // Muestra el mensaje de error en el alert
                      "error"
                  );
              } else {
                  Swal.fire(
                      "Error",
                      error.message || error, // Muestra el mensaje de error en el alert
                      "error"
                  );
              }
          });
            
           
    },
    
   

        verificadorAperturaCierre(){
            let me=this;
            var url = "/gestor_ventas/tieneApertura";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;  
               
                    if (respuesta===0||respuesta.tipo_caja_c_a===9||respuesta.id_apertura_cierre!=0) {
                      me.tieneApertura_0=0;
                        Swal.fire(
                    "Debe aperturar una caja",
                    "Haga click en Ok",
                    "warning",
                    );    
                    } else {
                      me.tieneApertura_0=1;
                         me.id_apertura_cierre=respuesta.id;
                         me.listarPermisoFacturacion(respuesta.id_sucursal,respuesta.id_caja);
                         
                         me.id_sucursal_siat=respuesta.id_sucursal;
                         me.id_caja_siat=respuesta.id_caja;
                         
                     } 
                })
                .catch(function (error) {
                    error401(error);
                });
        },


        registrar_cliente_modal() {
      
        let me = this;
        if(me.correo==''){
        me.correo="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com"
        }
// Expresión regular para verificar el formato del correo electrónico
const correoRegex = /^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}$/;
// Verificar si el correo cumple con el formato válido
if (!correoRegex.test(me.correo)) {
    this.correoInvalido = true;
    Swal.fire(
        "Formato de correo invalido.",
        "asegúrese si esta bien escrito el correo, no olvide la @ y la extencion ejemplo correo@correo.es",
        "warning",
    );
} else {
    if (
    me.selectTipoDoc === 0 ||              
    me.num_documento === "" ||
    me.nombre_a_facturar === "" ||
    me.correo === ""                
) {
    Swal.fire(
        "No puede ingresar valor nulos  o vacios",
        "Haga click en Ok",
        "warning",
    );
} else {
   
    if (me.num_documento!=99001&&me.num_documento!=99002&&me.num_documento!=99003&&me.num_documento!=0&&me.num_documento!="000") {

        axios
        .post("/directorio/registrar", {
            tipo_per_emp: me.selectTipo,
            id_tipo_doc: me.selectTipoDoc,
            nombre: me.nombres,
            apellido: me.apellidos,
            num_documento: me.num_documento,
            ex: me.complemento_,                       
            correo: me.correo,
            nom_a_facturar: me.nombre_a_facturar,
            telefono: me.telefono,                      
            direccion: me.direccion,
            pais: me.pais,
            ciudad: me.ciudad                
        })
        .then(function (response) {   

            me.id_tipo_doc=response.data.id_tipo_doc;
            me.cliente_id=response.data.id;
            me.num_documento=response.data.num_documento;
            me.correo_cliente=response.data.correo;
            me.nom_a_facturar=response.data.nom_a_facturar;
            me.complemento_siat=response.data.complemento;
        //var documento
      
        me.datos_cliete=me.nom_a_facturar+"/"+me.num_documento+"/"+me.extencion_tipodocumento+"-"+me.nombre_documento;
        me.key_1=1; 
            me.cerrarModal("registrar_cliente");
            Swal.fire(
                "Registro exitosamente",
                "Haga click en Ok",
                "success",
            );            
         
            //---                  
        })
      .catch(function (error) {           

    if (error.response.status === 500) {
        me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
    Swal.fire(
        "Error",
        "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
        "error"
    );
    }else{
        Swal.fire(
        "Error",
        ""+error.response.data.error, // Muestra el mensaje de error en el alert
        "error"
    );  
    }

   
});
    }   else {
        Swal.fire(
        "Los numeros 99001, 99002, 99003, 0, 000 . Esta ocupado para actividades especiales",
        "Haga click en Ok",
        "warning",
    ); 
    }
}
}
           
        },


        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
        ////////////////////////////////////SIAT/////////////////////////////////////////

        EnviarFactura(){
        let me = this;          
   
        let tamaño_array=me.arrayProducto_recibo_1.length;
        let ini=0;
        let contador=0;
        while (ini < tamaño_array) {
  let newTipo = me.arrayUnidadMedida_2.find(
    (element) => element.id_erp === me.arrayProducto_recibo_1[ini].id_unidad_me
  );

  if (newTipo) {
    me.arrayProducto_recibo_1[ini].id_unidad_me = newTipo.codigo;
    contador++;
  }
  ini++;
}
        if (contador===tamaño_array) {
          me.isSubmitting = true; // Deshabilita el botón   
        if (me.validadorPersonal===3) {           
            me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:0,id_descuento:0,cantidad_descuento:0.00,tipo:1});            
          }
          if (me.cliente_bandera === 1&&me.validadorPersonal===5) {
            me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:0,id_descuento:0,cantidad_descuento:0.00,tipo:1});
          }
          if (me.validadorPersonal===7 || me.existe_final>0) {
            let sumador_21_sub = 0;
            let sumador_21_des = 0;

                me.arrayDescuentoOperacion.forEach((descuento) => {
                if (descuento.id_nom_tabla===6) {
                    let operacion_21 = this.operacion_Numeral_Procentaje(descuento.tipo_num_des, descuento.monto_descuento, me.sumatotal);
                let { valorNumeral, porcentaje } = operacion_21;
                sumador_21_sub += parseFloat(valorNumeral);  // Acumular los valores numerales como float
                sumador_21_des += parseFloat(porcentaje); 
                me.array_ven__detalle_descuentos.push({id_contador:0,id_tabla:descuento.id_nom_tabla,id_descuento:descuento.id,cantidad_descuento:parseFloat(porcentaje),tipo:2});
                }                
            });      
          }
          
        const totalDescuento = me.array_vetasQuery.reduce((total, venta) => {
        return total + (parseFloat(venta.descuento) || 0);
}, 0);
me.descuento_1=totalDescuento+me.descuento_final;
        const data = {
          TipoComprobate: me.TipoComprobate, 
          num_documento: me.num_documento,
          id_tipo_doc: me.id_tipo_doc,
          cliente_id: me.cliente_id,
          nom_a_facturar: me.nom_a_facturar,
          correo_cliente: me.correo_cliente,
          complemento_siat: me.complemento_siat,
          tipoPago:me.selectPago_,
          numeroTarjeta:me.numeroTarjeta_siat,
          cadenaOtros:me.cadenaOtros,
          tipoBanco:me.selectBanco_v,
          gift_value:me.gift_value,
          monto_a_pagar:parseFloat(me.monto_a_pagar).toFixed(2),
          importe_fiscal:parseFloat(me.importe_fiscal).toFixed(2),
          tipo_pago_Qr_con_tar:me.tipo_pago_Qr_con_tar,
          tipo_contado_s:me.tipo_contado_s,
          tipo_qr_s:me.tipo_qr_s,
          tipo_tarjeta:me.tipo_tarjeta, 
          tipo_gift_:me.tipo_gift_,
          tipo_pago_siat:me.selectPago_,
          descuento_a_total:me.descuento_final,
          actividadEconomica:me.actividadEco_v2,
          leyenda:me.leyenda_v2,

          total_venta:me.tota_del_total,
          efectivo_venta:me.efectivo,
          cambio_venta:parseFloat(me.cambio).toFixed(2),
          descuento_venta:me.descuento_1,
          arrayProRecibo:me.arrayProducto_recibo_1,
          arrayDescuentoOperacion: me.array_ven__detalle_descuentos, // Incluir el array en el objeto data
          arrayDesatlleVenta: me.array_vetasQuery,
          codigo_tienda_almacen_0:me.codigo_tienda_almacen,
          id_lista_v2:me.id_lista_v2,
          id_apertura_cierre:me.id_apertura_cierre,

           arrayQuery_siat_:me.arrayQuery_siat_,
           arrayEstado_dosificacion_facctura:me.arrayEstado_dosificacion_facctura,          
        //  arrayEstado_dosificacion_facctura:me.arrayEstado_dosificacion_facctura,    

      };

  

      // Realizar la solicitud POST con Axios
      axios.post("/gestor_ventas/ventaFacturaSiat", data)
          .then(response => {
            var respuesta = response.data;  
     
            me.isSubmitting = false;
          })
          .catch(error => {
            me.isSubmitting = false; // Deshabilita el botón
            console.log(error);
          });
        } else {
          Swal.fire("Error","No existe el producto homologado","error"); 
        }        
    },

    verificarUnidadMedida(){
      let me = this;          
           var url = "/listarUnidadMedidaExcell";          
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayUnidadMedida_2 =respuesta;                                    
                })
                .catch(function (error) {
                    error401(error);
              
                });
    },

 listarPago_() {
            let me = this;          
           var url = "/gestor_ventas/pago_ex";          
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayPago_=respuesta;                                
                   me.selectPago_="0";                  
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarBanco() {
            let me = this;          
           var url = "/getBancos";          
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayBanco=respuesta;                    
                })
                .catch(function (error) {
                    error401(error);
                });
        },

  
        

        tipoPago_sis(data){
          let me=this;
          switch (data) {
            case 1:
            me.tipo_contado_s=1;
            me.tipo_qr_s=0;
            me.tipo_tarjeta=0;
            me.tipo_gift_=0;
            me.tipo_pago_Qr_con_tar=data;
            me.selectPago_="1";
            me.gift_value=0;
            me.monto_a_pagar=me.tota_del_total-me.gift_value;
            me.importe_fiscal=me.monto_a_pagar;
            me.efectivo=0;
          me.cambio=0;
            break;
          
            case 2:
            me.tipo_pago_Qr_con_tar=data;
            me.tipo_contado_s=0;
            me.tipo_qr_s=1;
            me.tipo_tarjeta=0;
            me.tipo_gift_=0;
            me.selectPago_="7";  
            me.gift_value=0;
            me.monto_a_pagar=me.tota_del_total-me.gift_value;
            me.importe_fiscal=me.monto_a_pagar;
            me.efectivo=0;
          me.cambio=0;
            break;

            case 3:
            me.tipo_pago_Qr_con_tar=data;
            me.tipo_contado_s=0;
            me.tipo_qr_s=0;
            me.tipo_tarjeta=1;
            me.tipo_gift_=0;
            me.selectPago_="2";
            me.gift_value=0;
            me.monto_a_pagar=me.tota_del_total-me.gift_value;
            me.importe_fiscal=me.monto_a_pagar;
            me.efectivo=0;
          me.cambio=0;
            break;
            case 4:
            me.tipo_pago_Qr_con_tar=data;
            me.tipo_contado_s=0;
            me.tipo_qr_s=0;
            me.tipo_tarjeta=0;
            me.tipo_gift_=1;
            me.selectPago_="102";
            me.gift_value=0;
            me.monto_a_pagar=me.tota_del_total-me.gift_value;
            me.importe_fiscal=me.monto_a_pagar;
            me.efectivo=0;
          me.cambio=0;
            break;

            default:
            me.tipo_pago_Qr_con_tar=0;
            me.tipo_contado_s=0;
            me.tipo_qr_s=0;
            me.tipo_tarjeta=0;
            me.selectPago_="0"; 
            me.gift_value=0;
            me.efectivo=0;
          me.cambio=0;
              break;
          }

        },


        resetDatosAdicionales_siat(){
          let me=this;
          me.selectPago_="0";
          me.numeroTarjeta_siat="";
          me.cadenaOtros="";
          me.selectBanco_v="0";
          me.tipo_pago_Qr_con_tar=0;
            me.tipo_contado_s=0;
            me.tipo_qr_s=0;
            me.tipo_tarjeta=0;
            me.selectPago_="0";
            me.gift_value=0;
        }, 
        resertPago_siat(){
          let me=this;      
          me.numeroTarjeta_siat="";
          me.cadenaOtros="";
          me.selectBanco_v="0";
        }, 

        actividadEconomica(){//------------eli9minar
          let me=this;
          var url = "/leyenda_siat";          
            axios.get(url)
                .then(function (response) {                
                    var respuesta_2 = (response.data); 
                      
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        
       
    },

    
    mounted() {
        this.listarDescuento_Tipo_tabla();
        this.classModal = new _pl.Modals();
        this.listarDescuentos_listas();
        this.listarSucursalGet();
        this.verificarUnidadMedida();       
        this.listarBanco();
        this.verificadorAperturaCierre();
        this.classModal.addModal("registrar");
        this.classModal.addModal("cliente_modal");
        this.classModal.addModal("lote_cliete"); 
        this.classModal.addModal("registrar_cliente");
        this.classModal.addModal("vista_bloque");
        this.listarPago_();   
        this.actividadEconomica();
    },
};
</script>

<style scoped>
.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}



.loading-screen img {
    width: 30%;
  height: 20%;
}
.error{
    
    color: red;
    font-size: 10px;
}
.red-day {
  color: rgb(230, 21, 132);
  /* Puedes ajustar otros estilos según tus preferencias */
}
.color-1 {
  color: rgb(17, 68, 16);
  /* Puedes ajustar otros estilos según tus preferencias */
}
.color-2 {
  color: rgb(238, 241, 20);
  /* Puedes ajustar otros estilos según tus preferencias */
}
.color-3 {
  color: rgb(75, 19, 19);
  /* Puedes ajustar otros estilos según tus preferencias */
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.modal {
  transition: opacity 0.5s ease;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active en versiones de Vue < 2.1.8 */ {
  opacity: 0;
}
</style>
<style scoped>
.modal {
  transition: opacity 0.5s ease;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active en versiones de Vue < 2.1.8 */ {
  opacity: 0;
}
</style>