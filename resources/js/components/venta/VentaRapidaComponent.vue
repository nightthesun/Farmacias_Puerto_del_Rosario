<template>
    <div>
        <div v-if="loading" class="loading-screen">
            
      <img src="/img/logo.png" alt="Cargando..." class="loading-image" />
          <!-- También puedes agregar un spinner en lugar de una imagen -->
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
                               <div v-if="arrayDescuento.length > 0" class="d-flex flex-wrap align-items-center">
                                 <div v-for="d in arrayDescuento" :key="d.id" class="mr-2 mb-2">
                                    
                                     <span v-if="d.id_tabla == 1" class="badge badge-pill badge-warning">{{ "Descuento: "+d.nombre_descuento }}</span>
                                     <span v-if="d.id_tabla == 2" class="badge badge-pill badge-secondary">{{ "Descuento: "+d.nombre_descuento }}</span>
                                     <span v-if="d.id_tabla == 3" class="badge badge-pill badge-success">{{ "Descuento: "+d.nombre_descuento }}</span>
                                     <span v-if="d.id_tabla == 4" class="badge badge-pill badge-danger">{{ "Descuento: "+d.nombre_descuento }}</span>                             
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
         
                                 @select="lipiar_cantida_cantida();" 
                                >
                                <template #noResult>
                                 No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                               </template>
                               <template #option="{ option }">
                                 <div :class="{'red-day': option.dias <= 10}">
                                
                                {{option.leyenda}} {{option.nombre_linea}} {{ "FV: "+option.fecha_vencimiento}} {{ "Dias: "+option.dias}}
                                 </div>
                         </template>
                             </VueMultiselect>
                         </div>
                         
                         <span class="input-group-btn">
                             <a data-toggle="modal" href="#modal">
                                 <button class="btn btn-warning" type="button"><i class="fa fa-eye"></i></button>
                             </a>
                         </span>
                     </div>
                 </td>
                 
               <td class="col-md-1" >
                 <span  v-if="selected" v-text=" selected.precio_lista_gespreventa">
                 </span>
               </td>
               <td class="col-md-1" >
                 <span  v-if="selected" v-text=" selected.stock_ingreso">
                 </span>
               </td>
               <td class="col-md-1" >
                 <span  v-if="selected" v-text=" selected.fecha_vencimiento">
                 </span>
               </td>
             
                 <td class="col-md-1">
                     <input v-show="validadorPersonal === 1" :disabled="selected===null " type="text" class="form-control" id="inlineFormInputName" v-model="descuento">
                     
                     <span v-show="validadorPersonal === 2" style="text-align: center;">
                        Automatico
                     </span>
                     <span v-show="validadorPersonal === 3" style="color: black; display: flex; justify-content: center; align-items: center; font-size: 24px; width: 100%;">
                        <i class="fa fa-user-times" aria-hidden="true"></i>
                    </span>   
                    <button v-show="validadorPersonal === 0" type="button" class="btn btn-success">Calcular</button>
                 </td>
          
               <td class="col-md-1">
                 <input type="text" class="form-control" id="inlineFormInputName" v-model="numero" @keydown="filtrarNumeros">
               </td>
               <td class="col-md-1">
                    <div v-if="validadorPersonal === 1">
                        <button v-if="selected!==null && numero!=='' && descuento!=='' " type="button" class="btn btn-success btn-sm" style="margin-right: 5px;" @click="agregarVenta();">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            </button> 
                        <button v-else  type="button" class="btn btn-secondary  btn-sm" style="margin-right: 5px;">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>  
                    </div>
                    <div v-if="validadorPersonal === 3 || validadorPersonal === 2" >
                        <button v-if="selected!==null && numero!==''" type="button" class="btn btn-success btn-sm" style="margin-right: 5px;" @click="agregarVenta();">
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
                             <th >Opciones</th>
                             <th class="col-6">Producto</th>
                             <th class="col-1">cantidad</th>
                             <th class="col-1">Precio de venta</th>
                             <th class="col-1">Descuento</th>
                             <th class="col-2" >Sub Total</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr v-for="venta in arrayVentas" :key="venta.id">
                             <td style="text-align:center">
                                 <button type="button" class="btn btn-danger btn-sm" @click="quitarVEnta(venta.id)">
                                     <i class="fa fa-minus" aria-hidden="true"></i>
                                 </button>
                             </td>
                             <td class="col-6" v-text="venta.leyenda"></td>
                             <td class="col-1" v-text="venta.cantidad" style="text-align:right"></td>
                             <td class="col-1" v-text="venta.precio" style="text-align:right"></td>
                             <td class="col-1" v-text="venta.descuento" style="text-align:right"></td>
                             <td class="col-2" v-text="venta.subtotal" style="text-align:right"></td>
                         </tr>
                         <tr>
                             <th colspan="5" style="text-align:right">Suma Total:</th>
                             <th v-text="sumatotal + ' Bs.'" style="text-align:right"></th>
                         </tr>
                         <tr>
                             <th colspan="5" style="text-align:right">Efectivo:</th>
                             <th><input type="number" v-model="efectivo" v-on:focus="selectAll"  :disabled="sumatotal===0" @keyup="restartotal()" style="text-align:right"></th>
                         </tr>
                         <tr>
                             <th colspan="5" style="text-align:right">Cambio:</th>
                             <th v-text="cambio + ' Bs.'" style="text-align:right"></th>
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
             
                 <div class="form-group col-sm-4" style="display: flex; align-items: center;">
                     <input type="text" class="form-control"  placeholder="Número de Documento" v-model="buscarCliente">
                     <button class="btn btn-primary" type="button" 
                             @click="listarUsuario()" :disabled="buscarCliente==''">
                         Buscar
                     </button>
                   
                 </div>
                 <div class="form-group col-sm-6" style="display: flex; align-items: center;">
                     <input type="text" class="form-control"  v-model="datos_cliete" disabled placeholder="Número Documento/Número de Cliente">
                     <button class="btn btn-primary" type="button" @click="abrirModal('lote_cliete');listarUsuarioRetorno();">
                         <i class="fa fa-search-plus" aria-hidden="true"></i>
                     </button>                            
                     <button class="btn btn-info" type="button" style="color: white;"  @click="abrirModal('registrar_cliente');listarEX();listarTipoDoc();">
                         <i class="fa fa-user-plus" aria-hidden="true"></i>
                     </button>
                     <button class="btn btn-secondary" type="button" style="color: white;"  @click="limpiar();">
                         Limpiar
                     </button>
                 </div>           
                
               
                 
             </div> 
             </div>
           </div>
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
                   <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="cliente_modal"  data-backdrop="static" data-keyboard="false">
                     <div class="modal-dialog modal-primary">
                         <div class="modal-content animated fadeIn">
                             <div class="modal-header">
                                 <h4 class="modal-title">Datos de cliente</h4>
                                 <button class="close" @click="cerrarModal('cliente_modal')">x</button>
                             </div>
                             <div class="modal-body">
                                 <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                     <strong>NO ESCRITO EN EL PADRÓN</strong> <br>
                                     OD-OTRO DOCUMENTO DE IDENTIDAD 99001.
                                    
                                   </div>
                                   <div class="row justify-content-center">
                                     <div class="form-group col-sm-6">
                                         <strong>Razon social:</strong>
                                         <input type="text" class="form-control" v-model="razon_social_99001">
                                     </div>
                                 </div>
                                 
                             
                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" @click="cerrarModal('cliente_modal')">Cerrar</button>
                                 <button :disabled="razon_social_99001==''" class="btn btn-primary" @click="caso_99001();">Guardar</button>
                                 
                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- fin modal add cliente -->
                   <!-- Modal para la busqueda de clientes por lote -->
                   <div class="modal fade" id="lote_cliete" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
                   <div class="modal-dialog modal-dialog-scrollable modal-primary">
                       <div class="modal-content">
                           <div class="modal-header">
                               <h5 class="modal-title" id="exampleModalLabel">
                                   Busqueda de clientes
                               </h5>
                               <button type="button" @click="cerrarModal('lote_cliete')" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                   X
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
               <!-- Fun Modal para la busqueda de clientes por lote -->
               <!--Inicio del modal de registro de-->
               <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="registrar_cliente" aria-hidden="true" data-backdrop="static" data-key="false">
                 <div class="modal-dialog modal-primary modal-lg" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                         <h4 class="modal-title">{{ tituloModal }}</h4>
                         <button  type="button" class="close" aria-label="Close" @click="cerrarModal('registrar_cliente')">
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
                                                         <input type="text" @input="validateInput()" class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" v-on:focus="selectAll" >
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
                                                         <input type="text" @input="validateInput()" class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" v-on:focus="selectAll">
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
             <!--fin del modal-->
             </main>

        </div>
      </div>
  
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';

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
      options: ['list', 'of', 'options'],
            tituloModal: "",
            //selecto get sucursal
         
            arraySucursalGet:[],    
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
            //cliente_lote
            buscar:'',
            clientelote:'',
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
            complemento_:'',
            //tipo de codigo 
            correo_cliente:'',
            extencion_tipodocumento:'',
            nombre_documento:'',
        
    //----venta----
    arrayVentas:[],
    
    numero :'',
    descuento:0,
  
    //descuentos
    arrayDescuento:[],
    descuentoNombre:'',
    arrayDescuentoOperacion:[],
    //----- numeral
    arrayNumDescunto:[],
    arryPorDescuento:[],
    
    //lista
    nom_lista:'',

    validadorPersonal:'',

    valorUnicoPersonalizado:0,

    caso5_id:'',
    caso5_nom:'',
    caso5_id_tabla:'',
    sumatotal:0,
                efectivo:0,
                cambio:0,
     id_tabla_y2:'',           
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
            console.log(this.extencion_tipodocumento+"  "+this.nombre_documento);
        },

    descuento: function (valor) {
        if (this.arrayDescuentoOperacion.length===1) {
            if (this.arrayDescuentoOperacion[0].id_nom_tabla===5) {
                if (valor > this.valorUnicoPersonalizado) {               
                    this.descuento = 0;
                    Swal.fire(
                    "No puede ingresar un número mayor al descuento dado",
                    "Haga click en Ok",
                    "error",
                );
            }  
            }
        }
             
        },
        selected: function (valor) {
            if (valor !=null) {
                if (this.arrayDescuentoOperacion.length===1) {
                    if (this.arrayDescuentoOperacion[0].id_nom_tabla===5) {
                    let caso_5 = this.operacion_Numeral_Procentaje(this.arrayDescuentoOperacion[0].tipo_num_des, this.arrayDescuentoOperacion[0].monto_descuento,this.selected.precio_lista_gespreventa);
                    console.log("caso_5: "+caso_5);
                this.valorUnicoPersonalizado = caso_5;
                this.caso5_id=this.arrayDescuentoOperacion[0].id;
                this.caso5_nom=this.arrayDescuentoOperacion[0].nombre_descuento;
                this.caso5_id_tabla=this.arrayDescuentoOperacion[0].id_perso;//<====
                }  
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
  
    ///////////////////////////////funciones para la venta///////////////////////////////////////////////////////

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
            me.descuento=porcentaje;
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
            }
                me.descuento=var1;
                return { valorNumeral, porcentaje };
            }
        }

    },
        operacionDescuentos_tipo_tabla(tabla_id){
            let me = this; 

            me.arrayDescuentoOperacion.forEach(function(descuento) {  
                let var1=0;
            let porcentaje=0;
            let  valorNumeral=0;
   
    if (descuento.tipo_num_des === '#') {
        porcentaje=descuento.monto_descuento;
        valorNumeral=me.selected.precio_lista_gespreventa-porcentaje;
        if(valorNumeral<0){
            valorNumeral=me.selected.precio_lista_gespreventa;
        }
        let descuentoObjeto = {
                    id: descuento.id,
                    id_nombre_tabla: descuento.id_nom_tabla,
                    nombre: descuento.nombre_descuento,
                    monto: valorNumeral,
                    id_tabla_x:descuento.id_tablas_x
                };
    

    } 
    if (descuento.tipo_num_des === '%') {
                  
        porcentaje=descuento.monto_descuento/100;    
        valorNumeral=porcentaje*me.selected.precio_lista_gespreventa;
        if (descuento.id==="Normal") {
           // Crear un objeto literal con los valores
           let descuentoObjeto = {
            id: descuento.id,
                    id_nombre_tabla: descuento.id_nom_tabla,
                    nombre: descuento.nombre_descuento,
                    monto: valorNumeral,
                    id_tabla_x:descuento.id_tablas_x
                };    
            }   
    }

    
    if (descuento.id_nom_tabla===1) {
          // Agregar el objeto al array
          me.arryPorDescuento.push(descuentoObjeto);
    }




    if (descuento.id_nom_tabla===2) {
        if (descuento.tipo_can_valor==='#') {
            if (descuento.regla==='>') {
                if (me.numero>descuento.cantidad_valor) {
                    me.arryPorDescuento.push(descuentoObjeto);
                }
            }
            if (descuento.regla==='=') {
                if (me.numero=descuento.cantidad_valor) {
                    me.arryPorDescuento.push(descuentoObjeto);
                }
            }
            if (descuento.regla==='=') {
                if (me.numero>descuento.cantidad_valor) {
                    me.arryPorDescuento.push(descuentoObjeto);
                }
            }
        }
        if (descuento.tipo_can_valor==='BS') {
            let condicion1=me.selected.precio_lista_gespreventa*me.numero;
            if (descuento.regla==='>') {
                if (condicion1>descuento.cantidad_valor) {
                    me.arryPorDescuento.push(descuentoObjeto);
                }
            }
            if (descuento.regla==='=') {
                if (condicion1=descuento.cantidad_valor) {
                    me.arryPorDescuento.push(descuentoObjeto);
                }
            }
            if (descuento.regla==='<') {
                if (condicion1<descuento.cantidad_valor) {
                    me.arryPorDescuento.push(descuentoObjeto);
                }
            }

        } 
    }              
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
                    console.log("/-/-/-/-/-/-/-/-/-/-/-");
                    console.log(me.arrayDescuentoOperacion);       
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
                                    if (contador_1_normal>0&&contador_1_can_compra===0&&contador_1_cliente===0&&contador_1_producto===0&&contador_1_final===0) {
                                        me.validadorPersonal=2;// caso normal sin otras tablas  
                                      
                                    }
                          }


                    
                    }   
             
                                            
                    
            }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        

        listarDescuentos_listas() {
            let me = this;       
            var url ="/gestor_ventas/listarDescuentos_listas";            
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                    
            
                 if (respuesta.lista) {
                    me.nom_lista=respuesta.lista.nombre_lista;
                 } else {
                    me.nom_lista="Sin lista";  
                 }
          
// Verificar y mostrar la longitud del array 'descuento' en la respuesta (si es necesario)
                 if (respuesta.descuento.length>0) {
                    me.arrayDescuento=respuesta.descuento;
                 } else {
                    me.descuentoNombre="Sin descuentos";
                 }


            }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },


        lipiar_cantida_cantida(){
            let me = this;          
            me.numero='';
            me.descuento='';
        },

        quitarVEnta(id){            
            let me = this;            
            me.arrayVentas = me.arrayVentas.filter(venta => venta.id !== id);
        },

        agregarVenta(){
            try {
            let me = this;
            // caso sin descuento-----
          if (me.validadorPersonal===3) {
            me.descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            console.log("*****************************************");
            console.log(me.numero);
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            subtotal=precioXcantida;
          }
          //caso personalizado validoPersonal=1
          if (me.validadorPersonal===1) {
            
            var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
         
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
            descuento=me.descuento;
            subtotal=precioXcantida-descuento;
            //subtotal=precioXcantida*descuento;
          }
          if (me.validadorPersonal===2) {
            let sumador_21_sub=0;
            let sumador_21_des=0;
            let operacion_21_1='';
           
            me.arrayDescuentoOperacion.forEach((descuento) => {   
                    operacion_21=this.operacion_Numeral_Procentaje(descuento.tipo_num_des,descuento.monto_descuento,this.selected.precio_lista_gespreventa);
                    let { valorNumeral, porcentaje } = operacion_21;
                    sumador_21_sub=sumador_21+valorNumeral;
                    sumador_21_des=sumador_21+porcentaje;
            });
            me.descuento=sumador_21;
            var descuento=0;
            var precioXcantida=0;
            var subtotal=0;
            precioXcantida=me.selected.precio_lista_gespreventa*me.numero;
            //descuento=1-(me.descuento/100);
            descuento=sumador_21;
            subtotal=precioXcantida-descuento;
            console.log(operacion_21);
            //subtotal=precioXcantida*descuento;
            if(subtotal<=0){
                subtotal=precioXcantida;
                me.descuento=0;
                descuento=me.descuento;
                Swal.fire(
        "El descuento es mayo al precio original",
        "Haga click en Ok, el valor de descuento se sera de 0",
        "error",
        );
        }else{
            me.descuento=sumador_21;
            descuento=sumador_21;
        }
       
          }
            
          me.sumatotal=me.sumatotal+subtotal;
            // Convertir a un número con dos decimales
            subtotal = parseFloat(subtotal.toFixed(2));


            
            me.arrayVentas.push({id: me.selected.id,leyenda: me.selected.leyenda,cantidad:me.numero,precio:me.selected.precio_lista_gespreventa,
            descuento: descuento,subtotal:subtotal,id_ingreso:me.selected.id_ingreso,id_pro:me.selected.id_prod
            });

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
                    console.log(error);
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
                    console.log(error);
                })
        },  

        caso_loteCliente(cliente){
     
           this.id_tipo_doc=cliente.id_tipo_doc;
        this.cliente_id=cliente.id;
        this.nom_a_facturar=cliente.nom_a_facturar;
        this.num_documento=cliente.num_documento;
        this.datos_cliete=cliente.nom_a_facturar+"/"+cliente.num_documento+"/"+cliente.tipo_doc_datos+"-"+cliente.tipo_doc_nombre;
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
                    console.log(error);
                });
        },

      caso_99001(){
        this.id_tipo_doc=4;
        this.cliente_id=0;
        this.num_documento=99001;
        this.correo_cliente="";
        this.nom_a_facturar=this.razon_social_99001;
        
        this.datos_cliete=this.nom_a_facturar+"/"+this.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
        this.cerrarModal('cliente_modal')       
      },

      restartotal(){
                let me=this;
                if(me.efectivo!=0)
                    me.cambio=Number(me.efectivo-me.sumatotal);
                else
                    me.cambio=0;
            },

      limpiar(){
        this.id_tipo_doc="";
        this.cliente_id="";
        this.num_documento="";
        this.correo_cliente="";
        this.nom_a_facturar="";
        //var documento      
        this.datos_cliete="";
        this.buscarCliente="";
      },

        listarUsuario() {
            let me = this;
            var url = "/gestor_ventas/listarUsuario?num_doc="+me.buscarCliente;
         
            var opcion=me.buscarCliente;
            switch(opcion) {
    case '99001':
        me.abrirModal('cliente_modal');
        break;
    case '99002':
            me.id_tipo_doc=4;
            me.cliente_id=0;
            me.num_documento=99002;
            me.nom_a_facturar="CONTROL TRIBUTARIO";
            me.correo_cliente="";
            me.datos_cliete=me.nom_a_facturar+"/"+ me.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
        break;
    case '99003':
        me.id_tipo_doc=4;
        me.cliente_id=0;
        me.num_documento=99003;
        me.correo_cliente="";
        me.nom_a_facturar="VENTAS MENORES DEL DIA";
        me.datos_cliete=me.nom_a_facturar+"/"+me.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
    break;
    case '0':
        me.id_tipo_doc=4;
        me.cliente_id=0;
        me.num_documento=0;
        me.nom_a_facturar="SIN NOMBRE";
        me.correo_cliente="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com";
        me.datos_cliete=me.nom_a_facturar+"/"+me.num_documento+"/OD-OTRO DOCUMENTO DE IDENTIDAD";
    break;
    default:
    axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    console.log(response.data);
                    me.cliente_id=response.data.id;
                    if (me.cliente_id==undefined) {
                        me.datos_cliete="No se encontro cliente..."
                    } else {
                        me.id_tipo_doc=response.data.id_tipo_doc;
                        me.nom_a_facturar=response.data.nom_a_facturar;
                        me.num_documento=response.data.num_documento;
                        me.cliente_id=response.data.id;
                        me.correo_cliente=response.data.correo;
                        me.datos_cliete=response.data.nom_a_facturar+"/"+response.data.num_documento+"/"+response.data.tipo_doc_nombre;  
                    }
                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        break;
        }
          
          
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
                    me.arrayProducto = respuesta;
                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
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
                    me.tituloModal = "Registro de traspaso origen ";
            
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;                 
                      
                    me.classModal.openModal("registrar");
                    break;
                }
                case "cliente_modal":{
                    me.v99001='';
                    me.id_tipo_doc='';
                    me.razon_social_99001="";
                    me.cliente_id="";
                    me.classModal.openModal("cliente_modal");
                    break;
                }
                case "lote_cliete":{
                    me.buscar='';
                    me.id_tipo_doc=''
                    me.cliente_id='';
                    me.nom_a_facturar='';
                    me.num_documento='';
                    me.datos_cliete='';
       
                    me.classModal.openModal("lote_cliete");
                    break;
                }
                case "registrar_cliente": {
                   
                    me.tituloModal = "Registro de clientes";
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
                    me.selectTipo=0;
                    me.complemento_='';
                    me.extencion_tipodocumento='';
                    me.nombre_documento='';
                    me.num_documento2='';
            
                    me.classModal.openModal("registrar_cliente");
                    break;
                }            
            }
        },
        cerrarModal(accion) {
            let me = this;          
            me.classModal.closeModal(accion);
            me.tituloModal = "";
            me.buscarCliente ="";
                    //me.pais='';
                    //me.ciudad='';
                    //me.direccion='';
                    //me.telefono='';
                    //me.num_documento2='';
                    //me.correo='';
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
       console.log("tipo:"+me.selectTipo+" tipo: "+me.selectTipoDoc+" nombres: "+me.nombres
        +" apellidos: "+me.apellidos+" numero_d "+me.num_documento+" complemento: "+me.complemento_+" name: "+
        me.nombre_a_facturar+" telefono: "+me.telefono+" dir: "+me.direccion+" pais: "+me.pais+" ciudad: "+me.ciudad);
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
            console.log("Respuesta del servidor:", response.data); // Mostrar toda la respuesta en la consola
            me.id_tipo_doc=response.data.id_tipo_doc;
            me.cliente_id=response.data.id;
            me.num_documento=response.data.num_documento;
            me.correo_cliente=response.data.correo;
            me.nom_a_facturar=response.data.nom_a_facturar;
        //var documento
      
        me.datos_cliete=me.nom_a_facturar+"/"+me.num_documento+"/"+me.extencion_tipodocumento+"-"+me.nombre_documento;
           
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
    },

    
    mounted() {
        this.listarDescuento_Tipo_tabla();
        this.classModal = new _pl.Modals();
        this.listarDescuentos_listas();
        this.listarSucursalGet();
       
        this.classModal.addModal("registrar");
        this.classModal.addModal("cliente_modal");
        this.classModal.addModal("lote_cliete"); 
        this.classModal.addModal("registrar_cliente");   
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
  color: deeppink;
  /* Puedes ajustar otros estilos según tus preferencias */
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
