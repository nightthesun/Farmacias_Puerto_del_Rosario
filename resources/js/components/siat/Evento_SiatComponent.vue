<template>
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
                <div class="card-header">
                    <i class="fa fa-search"></i> Filtro de busqueda 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-3">
                     <label for="">Sucursal SIAT:</label>
                     <div class="input-group">
                        <select class="form-control" v-model="selectEmisor">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option value="10000" >Todos</option>
                                    <option v-for="(i, index) in emisor_query_1" :key="index" :value="i.id_sucursal">{{'Emisor: '+i.nombre_emisor+' - Nombre suc.Siat: '+i.nombre_suc_siat+' - Sucursal: '+i.razon_social}}</option>                                   
                                </select>
                          <button type="submit" class="btn btn-primary" @click="listarInicio(1,1)" :disabled="selectEmisor==='0'"><i class="fa fa-search"></i></button>       
                     </div>
                                
                </div>
                <div class="col-md-3">
                    <label for="">Nro.Factura:</label>
                    <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar por numero de factura" v-model="buscar_factura"/>
                                <button type="submit" class="btn btn-primary" @click="listarInicio(1,2)" :disabled="buscar_factura===''"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                 
                <div class="col-md-6">
                    <label for="">CUF:</label>
                    <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar codigo de autorización" v-model="buscar_cuf"/>
                                <button type="submit" class="btn btn-primary" @click="listarInicio(1,3)" :disabled="buscar_cuf===''">
                                    <i class="fa fa-search"></i> 
                                </button>
                    </div>
                </div>
                
               
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                 <label for="">Sector SIAT:</label>
                 <div class="input-group">
                    <select class="form-control" v-model="selectSector">
                                    <option value="0" disabled selected>Seleccionar...</option>                               
                                    <option v-for="(i, index) in sector_query_2" :key="index" :value="i.codigo">{{'Descripción: '+i.descripcion+' - Codigo: '+i.codigo}}</option>                                   
                                </select>
                                 <button type="submit" class="btn btn-primary" @click="listarInicio(1,4)" :disabled="selectSector==='0'">
                                    <i class="fa fa-search"></i> 
                                </button>
                 </div>                                
                </div>
                <div class="col-md-3">
                     <label for="">Estado SIAT:</label>
                     <div class="input-group"> 
                        <select class="form-control" v-model="selectEstado">
                        <option value="0" disabled selected>Seleccionar...</option>    
                        <option value="901">Recepción Pendiente</option> 
                        <option value="902">Recepción Rechazada</option> 
                        <option value="903">Recepción Procesada</option> 
                        <option value="904">Recepción Observada</option>  
                        <option value="905">Anulación Confirmada</option> 
                        <option value="906">Anulación Rechazada</option> 
                        <option value="907">Reversión De Anulación Confirmada</option> 
                        <option value="908">Recepción Validada</option> 
                        <option value="909">Reversión De Anulación Rechazada</option> 
                    </select>
                       <button type="submit" class="btn btn-primary" @click="listarInicio(1,5)" :disabled="selectEstado==='0'">
                                    <i class="fa fa-search"></i> 
                                </button>
                                
                     </div>                    
                </div>
                 <div class="col-md-3">
                    <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" @change="listarInicio(1,6)">
                 </div>
                  <div class="col-md-3">
 <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" @change="listarInicio(1,6)">
                  </div>    
            </div> 
            <div class="form-group row">
                <div class="col-md-12">
                      <button type="submit" class="btn btn-primary" @click="abrirModal('contingencia_consullta_modal');" style="margin-top: 15px; margin-right: 10px;">
                        Ver estado evento.
                    </button>
                    <button type="submit" class="btn btn-primary" @click="abrirModal('contingencia_2');" style="margin-top: 15px; margin-right: 10px;">
                        Abrir evento.
                    </button>
                    <button type="submit" class="btn btn-primary" @click="abrirModal('envioPaquete');" style="margin-top: 15px; margin-right: 10px;">
                        Envio de paquetes.
                    </button>
                    
                </div>
            </div>       
         </div>


            </div>   
  
        <!-- fin de index -->
        </div>  
         <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    RESULTADO DE TABLA     
                </div>
                 <div style="overflow-x: auto; width: 100%;">
    
        <div class="card-body"> 
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Nro. Documento</th>
                        <th class="col-md-1">Sucursal</th>
                        <th class="col-md-1">Emisor</th>
                        <th class="col-md-2">F.Emision</th>
                        <th class="col-md-3">Sector</th>
                        <th class="col-md-3">CUF</th> 
                        <th>Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in inicioArray" :key="index">
                        <td>
                            <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>     
  <div class="dropdown-menu">    
      <a class="dropdown-item" href="#" @click="listarQueryModal_1(i);"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i>Ver datos</a>
    <a  class="dropdown-item" href="#" ><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i>Ver estado factura</a>     
    <a  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i>Ver en SIAT</a>
    <a v-show="i.codEstado != '908'" class="dropdown-item" href="#" @click="abrirModal('contingencia',i);" ><i style="color: black;"  class="fa fa-exclamation-triangle" aria-hidden="true"></i>Contingencia</a>
   
    <a v-show="i.estado==1"  @click="anulacionReversion(1,i.id)" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-refresh" aria-hidden="true"></i>Anular</a>   
    <a v-show="i.estado==0" @click="anulacionReversion(2,i.id)" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-refresh" aria-hidden="true"></i>Reversión</a>   
          
</div>  
                            
                        </td>
                        <td class="col-md-1">{{i.nro_doc}}</td>
                        <td class="col-md-1">{{i.nomre_sucursal}}</td>
                        <td class="col-md-1">{{i.punto_venta}}</td>
                        <td class="col-md-2">{{i.fechaEmision}}</td>
                        <td class="col-md-3">{{i.sector_descripcion}}</td>
                        <td class="col-md-3">{{i.cuf}}</td>
                        <td>
                            <div v-if="i.codEstado=='908'">
                                <span class="badge badge-pill badge-success">VALIDA</span>
                               
                            </div>
                            <div  v-else>
                                 <span class="badge badge-pill badge-warning">OBSERVADA</span>
                               
                            </div>
                        </td>                        
                    </tr>
                </tbody>
            </table>    
<nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,)">Ant</a>
                    </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                    </li>
                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,)">Sig</a>
                    </li>
                </ul>
            </nav>
            <!-----fin de tabla------->
        </div>
</div>

            </div>   
  
        <!-- fin de index -->
        </div>    
           <!--Inicio del modal agregar/actualizar-->
      <transition name="fade">
    <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">{{ tituloModal }}</h4>
                    <button type="button" class="close" @click="cerrarModal('registrar')">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <form class="form-horizontal">

                        <div class="container-fluid">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="text-center">
                                                <strong>Datos de cliente</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="15%"><strong>Documento:</strong></td>
                                            <td width="20%">{{ documento_modal }}</td>
                                            <td width="20%"><strong>Razón social:</strong></td>
                                            <td width="45%">{{ rasonSocial_modal }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="text-center">
                                                <strong>Datos de factura</strong>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td width="15%"><strong>Número Fac.:</strong></td>
                                            <td width="10%">{{ numeroFactura_modal }}</td>
                                            <td width="20%"><strong>Cód. Recepción:</strong></td>
                                            <td width="55%">
                                                <small>{{ codRecepcion_modal }}</small>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><strong>Fecha Emisión:</strong></td>
                                            <td>{{ fechaEmision_modal }}</td>
                                            <td><strong>Cód. Autorización:</strong></td>
                                            <td>
                                                <small>{{ codAutorizacion_modal }}</small>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><strong>Sucursal S.:</strong></td>
                                            <td>{{ nombreSucursal_modal }}</td>
                                            <td><strong>Punto de venta:</strong></td>
                                            <td>{{ razonSocialSucursal_modal }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>Sector:</strong></td>
                                            <td>{{ sectorDescripcion_modal }}</td>
                                            <td><strong>Estado:</strong></td>
                                            <td>{{ estado_modal }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>Contingencia:</strong></td>
                                            <td>{{ contingencia_modal }}</td>
                                            <td><strong>Estado contingencia:</strong></td>
                                            <td>{{ contigenciaDes_modal }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="cerrarModal('registrar')">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
</transition>
        <!--fin del modal-->

        <!--Inicio del modal agregar/actualizar-->
      <transition name="fade">
    <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">{{ tituloModal }}</h4>
                    <button type="button" class="close" @click="cerrarModal('contingencia')">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                   
                    <form class="form-horizontal">
                         <div class="row" style="margin-top: 10px;">
                        <div class="col-4">
                        <label for="">Tipo de contingencia:</label>
                        </div>
                        <div class="col-8">
                              <select class="form-control" v-model="selectListaContingencia_2" @change="cambioOpcion_modal(selectListaContingencia_2)">
                            <option value="0" disabled selected>Seleccionar...</option>    
                        <option  v-for="(i, index) in arrayListaContingencia_2" :key="index" :value="i.codigo" :hidden="i.codigo>=5">{{  "("+i.codigo+") "+i.descripcion }}</option> 
                              </select>
                            
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-4">
                        <label for="">Fecha y hora de inicio de contingencia:</label>
                        </div>
                        <div class="col-8">
                    <input id="start-date" type="datetime-local" class="form-control" v-model="startDate_modal_1">
                        </div>                       
                    </div>  
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-4">
                        <label for="">Fecha y hora de finalización de contingencia:</label>
                        </div>
                        <div class="col-8">
                    <input id="end-date" type="datetime-local" class="form-control" v-model="endDate_modal_1">
                        </div>                       
                    </div>  
                                    
                             
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('contingencia')">
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" @click="enviarContingencia()" :disabled="selectListaContingencia_2==='0' || startDate_modal_1==='' || endDate_modal_1===''">
                        Enviar  
                    </button>
                </div>

            </div>
        </div>
    </div>
</transition>
        <!--fin del modal-->

        <!--Inicio del modal estado de contingencia-->
    <transition name="fade">
    <div v-if="showModal_3" class="modal d-block" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">{{ tituloModal }}</h4>
                    <button type="button" class="close" @click="cerrarModal('contingencia_consullta_modal')">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                   
                    <form class="form-horizontal">
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-4">
                            <label for="">Fecha:</label>
                                <div class="input-group">
                                 <input type="date" class="form-control" v-model="fechaPP">                           
                                </div>                                
                            </div> 
                            <div class="col-md-4">
                            <label for="">Seleccionar sucursal siat:</label>
                                <div class="input-group">
                                    <select class="form-control" v-model="select_query_1" :disabled="siguienteP==1">
                                        <option value="0" disabled selected>Seleccionar...</option>                               
                                        <option  v-for="(i, index) in array_query_1" :key="index" :value="i.id" >{{  "("+i.codigo_siat+") "+i.nombre_suc_siat }}</option>                              
                                    </select>
                                 <button type="button" class="btn btn-primary" @click="listarModalSucuralSiatPunto(2,select_query_1); operacionT(1)" :disabled="siguienteP==1||select_query_1=='0'">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                </button>
                                </div>                                
                            </div>
                            <div class="col-md-4" v-show="siguienteP==1">
                            <label for="">Punto de venta:</label>
                                <div class="input-group">
                                    <select class="form-control" v-model="select_query_2" :disabled="siguienteP_1==1">
                                        <option value="0" disabled selected>Seleccionar...</option>                               
                                        <option  v-for="(i, index) in array_query_2" :key="index" :value="i.id" :disabled="siguienteP_1===1">{{  "("+i.nombre+") "+i.descripcion }}</option>                              
                                    </select>
                                     <button type="button" class="btn btn-primary" @click="operacionT(2)" style="margin-right: 5px;">
                                    <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                                </button>
                                    <button type="button" class="btn btn-primary" @click="consultarEventoSiat()" :disabled="siguienteP_1===1 || select_query_2=='0'||fechaPP==''">
                                    Listar
                                </button>
                                                            
                                </div>                                
                            </div>  
                                                        
                        </div>         
                    </form>
                    <div style="margin-top: 20px;" v-show="ver_1_==1">
<div v-show="mesanje_3_===0" class="alert alert-success" role="alert">
                        {{mesanje_1_}}
                        </div>
<div v-show="mesanje_3_===1" class="alert alert-warning" role="alert">
                        {{mesanje_1_}}
                        </div>
                        <div v-show="mesanje_3_===2" class="alert alert-danger" role="alert">
                        {{mesanje_1_}}
                        </div>
                         <label for="">
                                {{mesanje_2_}}
                         </label>
                    </div>
                        
                     

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('contingencia_consullta_modal')">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
</transition>
        <!--fin del modal-->

         <!--Inicio del modal estado de contingencia manual-->
    <transition name="fade">
    <div v-if="showModal_4" class="modal d-block" tabindex="-1" role="dialog">      
            <div class="modal-dialog modal-primary modal-dialog-scrollable" role="document">
           <div class="modal-body" style="max-height: 60vh; overflow-y: auto;"> 
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">{{ tituloModal }}</h4>
                    <button type="button" class="close" @click="cerrarModal('contingencia_2')">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                   
                    <form class="form-horizontal">
                        <div class="card">
  <div class="card-header">
    Envio de datos de contingencia
  </div>
  <div class="card-body">
      <div  style="margin-top: 10px;">                            
                           
                            <label for="">Seleccionar sucursal siat:</label>
                                <div class="input-group">
                                    <select class="form-control" v-model="select_query_1" :disabled="siguienteP==1">
                                        <option value="0" disabled selected>Seleccionar...</option>                               
                                        <option  v-for="(i, index) in array_query_1" :key="index" :value="i.id" >{{  "("+i.codigo_siat+") "+i.nombre_suc_siat }}</option>                              
                                    </select>
                                 <button type="button" class="btn btn-primary" @click="listarModalSucuralSiatPunto(2,select_query_1); operacionT(1)" :disabled="siguienteP==1||select_query_1=='0'">
                                   <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                </button>
                                </div> 
                                <div v-show="siguienteP==1" style="margin-top: 10px;">
                                    <label for="">Punto de venta:</label>
                                        <div class="input-group">
                                        <select class="form-control" v-model="select_query_2" :disabled="siguienteP_1==1">
                                            <option value="0" disabled selected>Seleccionar...</option>                               
                                            <option  v-for="(i, index) in array_query_2" :key="index" :value="i.id" :disabled="siguienteP_1===1">{{  "("+i.nombre+") "+i.descripcion }}</option>                              
                                        </select>
                                        <button type="button" class="btn btn-primary" @click="operacionT(2)" style="margin-right: 5px;">
                                        <i class="fa fa-angle-double-up" aria-hidden="true"></i>
                                        </button>                          
                                        </div>
                                     <label for="">Tipo evento:</label>    
                                         <select class="form-control" v-model="selectListaContingencia_2" @change="cambioOpcion_modal(selectListaContingencia_2)">
                                        <option value="0" disabled selected>Seleccionar...</option>    
                                        <option  v-for="(i, index) in arrayListaContingencia_2" :key="index" :value="i.codigo">{{  "("+i.codigo+") "+i.descripcion }}</option> 
                                        </select>

                                         <div class="row" style="margin-top: 10px;">
                        <div class="col-6">
                        <label for="">Fecha y hora de inicio de contingencia:</label>
                        
                    <input id="start-date" type="datetime-local" class="form-control" v-model="startDate_modal_1">
                        </div>
                        <div class="col-6">
                        <label for="">Fecha y hora de finalización de contingencia:</label>                      
                    <input id="end-date" type="datetime-local" class="form-control" v-model="endDate_modal_1">
                        </div>     

                    </div>  
                    <div style="margin-top: 10px;" v-show="selectListaContingencia_2>=5">
                    <label for="">CAFC:</label>  
                     <div class="alert alert-warning" role="alert">
                        Esta opcion es para tipo de contigencia 5, 6 y 7
                    </div>
                  <input type="text"  class="form-control" placeholder="Ingresa el codigo CAFC" v-model="codigo_cafc">
                    </div>
                   
                                </div> 
                        </div> 
  </div>
                        </div>

                        <div class="card"  v-show="siguienteP==1">
                            <div class="card-header">
                                Envio de datos adicionales                               
                            </div>
                             <button type="button" class="btn btn-primary" @click="listarAutmoSelect()">LLenar automatico</button>  
                            <div class="card-body">
                                <div style="margin-top: 10px;">
<label for="">Tipo de ambiente:</label>
                                        <div class="input-group">
                                        <select class="form-control" v-model="select_ambiente_2">
                                            <option value="0" disabled selected>Seleccionar...</option>  
                                            <option value="1">Producción</option>    
                                            <option value="2">Piloto</option>                                 
                                        </select>                                                             
                                        </div>
                                </div>
                                <div style="margin-top: 10px;">
                                    <label for="">Codigo sector:</label>
                                        <div class="input-group">
                                        <select class="form-control" v-model="select_codSector_2">
                                            <option value="0" disabled selected>Seleccionar...</option>  
                                            <option :value="i.codigo" v-for="(i, index) in array_codSector_2" :key="index">{{ i.descripcion }}</option>                                                                           
                                        </select>                                                             
                                        </div> 
                                </div>
                                 <div style="margin-top: 10px;">
                                    <label for="">Codigo contigencia:</label>
                                        <div class="input-group">
                                        <select class="form-control" v-model="select_contigencia_2">
                                            <option value="0" disabled selected>Seleccionar...</option>  
                                            <option :value="i.codigo" v-for="(i, index) in array_contigencia_2" :key="index">{{ i.descripcion }}</option>                                                                           
                                        </select>                                                             
                                        </div>
                                 </div> 
                                 <div style="margin-top: 10px;">
                                    <label for="">Codigo sector:</label>
                                        <div class="input-group">
                                        <select class="form-control" v-model="select_tipoFacturaDoc_2">
                                            <option value="0" disabled selected>Seleccionar...</option>  
                                            <option :value="i.codigo" v-for="(i, index) in array_tipoFacturaDoc_2" :key="index">{{ i.descripcion }}</option>                                                                           
                                        </select>                                                             
                                        </div> 
                                 </div>
                                 <div style="margin-top: 10px;">
                                    <label for="">Tipo de modalidad:</label>
                                        <div class="input-group">
                                        <select class="form-control" v-model="select_modalidad_2">
                                            <option value="0" disabled selected>Seleccionar...</option>  
                                            <option value="1">Electronica</option>    
                                            <option value="2">Computarizada</option>                                 
                                        </select>                                                             
                                        </div>  
                                 </div>
                                           
                            </div>
                          
                          
                        </div>
                      
                    </form>
                    
                </div>

               

            </div>
           </div>  
             <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('contingencia_2')">
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" @click="enviarEventoManual()" :disabled="siguienteP==0">
                        Enviar
                    </button>
                </div>
        </div>
    </div>
</transition>
        <!--fin del modal-->

        <!--Inicio del modal estado de contingencia-->
    <transition name="fade">
    <div v-if="showModal_5" class="modal d-block" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-ancho" role="document">
     
            <div class="modal-content">
      <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">{{ tituloModal }}</h4>
                    <button type="button" class="close" @click="cerrarModal('envioPaquete')">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                   
                    <form class="form-horizontal">
                       <button type="button" class="btn btn-primary" @click="listarEventoSignificativoPaquete(1)" style="margin-left: 10px;">
                        Ver activos
                        </button>
                        <button type="button" class="btn btn-primary" @click="listarEventoSignificativoPaquete(0)" style="margin-left: 10px;">
                        Ver desactivados
                        </button>

                        <div class="container-fluid">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm" style="margin-top: 20px;">
                                   <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Numero</th>
                                            <th>Cod. recep</th>
                                            <th>Descripción</th>
                                            <th>Fecha inicio</th>
                                            <th>Fecha final</th>
                                            <th>Ambiente</th>
                                            <th>Modalidad</th>
                                            <th>Estado</th>   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(i, index) in arrayEnvento_mm" :key="index">
                                            <td>
    <div class="d-flex align-items-center gap-2">
        <div>
            <button
                @click="activar_mm(i.created_at,i.id)"
                v-if="i.estado==1"
                type="button"
                class="btn btn-danger">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>

            <button
                v-else
                type="button"
                class="btn btn-secondary">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
        </div>

        <div>
            <button v-if="i.estado==1&&i.paso==0" type="button" class="btn btn-warning" @click="enviarPaquete_mm(i.id);" style="color: white;margin-left: 8px;">
                <i class="fa fa-podcast" aria-hidden="true"></i>
            </button>
            <button v-else type="button" class="btn btn-secondary" style="color: white;margin-left: 8px;">
                <i class="fa fa-podcast" aria-hidden="true"></i>
            </button>
        </div>
        <div>
            <button v-if="i.estado==1&&i.paso==1" type="button" class="btn btn-success" @click="enviarValidacion_mm(i.id)" style="color: white;margin-left: 8px;">
                <i class="fa fa-rocket" aria-hidden="true"></i>
            </button>
            <button v-else type="button" class="btn btn-secondary" style="color: white;margin-left: 8px;">
              <i class="fa fa-rocket" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</td>
                                            <td>{{i.id}}</td>
                                            <td>{{i.cod_recep_even}}</td>
                                            <td>{{i.descrip}}</td>
                                            <td>{{i.fechaI}}</td>
                                            <td>{{i.fechaF}}</td>
                                            <td>{{i.ambiente}}</td>
                                            <td>{{i.modalidad}}</td>
                                             <td>
                                                <span v-if="i.estado==1" class="badge badge-pill badge-success">Activo</span>
                                                <span v-else class="badge badge-pill badge-secondary">Desactivado</span>
                                             </td>
                                        </tr>                                        
                                    </tbody>     
                                </table>
                            </div>
                        </div>            
                    </form>
                  

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('envioPaquete')">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
</transition>
        <!--fin del modal-->

    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import { calendarFormat } from "moment";
//Vue.use(VeeValidate);
export default {
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
          

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',
showModal: false,
offset:3,
        emisor_query_1:[],
        sector_query_2:[],
        selectEmisor:'0',
        selectSector:'0',

        buscar_cuf:'',
        buscar_factura:'',
        selectEstado:'0',  
        inicioArray:[],

       documento_modal:'',
        rasonSocial_modal:'',
        numeroFactura_modal:'',
        codRecepcion_modal:'',
        codAutorizacion_modal:'',
        fechaEmision_modal:'',
        estado_modal:'',
        puntoVenta_modal:'',
      
       sectorDescripcion_modal:'', 
        nombreSucursal_modal:'',
        razonSocialSucursal_modal:'',
        contigenciaDes_modal:'',
        contingencia_modal:'',

        showModal_2:false,
      showModal_3:false,
      showModal_4:false,
      showModal_5:false,
        arrayListaContingencia_2:[],
        selectListaContingencia_2:'0',
        contingencia_descripcion_modal:'',
        contigenciaDes_codigo_modal:'',
        startDate_modal_1:'',
        endDate_modal_1:'',
        id_sucursal_modal:'',
         id_cufd_modal:'',
        punto_suc:'',
        punto_venta:'',
        id_sucursal_modal:'',
        id_suc_siat:'',

        
        pagina_uno:0,

        array_query_1:[],
        
        array_query_2:[],
        select_query_2:'0',
        select_query_1:'0',
        siguienteP:0,
        siguienteP_1:0,  
        fechaPP:'',

        mesanje_1_:'',
mesanje_2_:'',
mesanje_3_:'',
ver_1_:0,
codigo_cafc:'',
id_evento_:'',
select_ambiente_2:'0',
select_tipoFacturaDoc_2:'0',
array_tipoFacturaDoc_2:[],
select_contigencia_2:'0',
array_contigencia_2:[],
select_codSector_2:'0',
array_codSector_2:[],
select_modalidad_2:'0',

arrayEnvento_mm:[],


        };
    },

    

    computed: {
      //  sicompleto() {
      //      let me = this;
       //     if (
          
     //           me.glosa != "" &&
     //           me.cantidadS != "" &&
     //           me.ProductoLineaIngresoSeleccionado
     //       )
       //         return true;
      //      else return false;
      //  },
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

        anulacionReversion(dato,id){
            let me=this;     
         axios.put("/siat_eventos/anulacionReversion", { 
                    id: id,
                    dato:dato                                                                       
                })
                .then(function (response) {                   
                    let respuesta=response.data;
                     console.log(respuesta);
                     me.listarEventoSignificativoPaquete(1);
                     if(respuesta===0){                        
                       return Swal.fire('Acción realizada','con exito.','success');
                       }else{
                           return Swal.fire('Error',' '+respuesta,'error');
                       }                                                               
                })               
                .catch(function (error) {                
                 error401(error);              
            });
        },

        enviarValidacion_mm(id){
        let me=this;     
         axios.put("/siat_eventos/enviarValidacion_2", { 
                    id: id,                                                                       
                })
                .then(function (response) {                   
                    let respuesta=response.data;
                     console.log(respuesta);
                     me.listarEventoSignificativoPaquete(1);
                     if(respuesta===0){                        
                       return Swal.fire('Acción realizada','con exito.','success');
                       }else{
                           return Swal.fire('Error',' '+respuesta,'error');
                       }                                                               
                })               
                .catch(function (error) {                
                 error401(error);              
            });
      },

      enviarPaquete_mm(id){
        let me=this;     
         axios.put("/siat_eventos/enviarPaquetes", { 
                    id: id,                                                                       
                })
                .then(function (response) {                   
                    let respuesta=response.data;
                     console.log(respuesta);
                     me.listarEventoSignificativoPaquete(1);
                     if(respuesta===0){                        
                       return Swal.fire('Acción realizada','con exito.','success');
                       }else{
                           return Swal.fire('Error',' '+respuesta,'error');
                       }                                                               
                })               
                .catch(function (error) {                
                 error401(error);              
            });
      },

        listarEventoSignificativoPaquete(dato_1) {
            let me=this;
             let url="/siat_eventos/listarEventoSignificativoPaquete?a="+dato_1;
             axios.get(url)
             .then(function (response) {
                 let respuesta=response.data;              
                 me.arrayEnvento_mm=respuesta;              
                 
             })
             .catch(function (error) {
                 error401(error);
                 console.log(error);
             }); 
           
        },


        activar_mm(fecha,id){
             let me=this;
  // Convertir a objeto Date
    let nuevaFecha = new Date(fecha.replace(' ', 'T'));

    // Sumar 24 horas
    nuevaFecha.setHours(nuevaFecha.getHours() + 24);

    // Formatear
    const year = nuevaFecha.getFullYear();
    const month = String(nuevaFecha.getMonth() + 1).padStart(2, '0');
    const day = String(nuevaFecha.getDate()).padStart(2, '0');
    const hours = String(nuevaFecha.getHours()).padStart(2, '0');
    const minutes = String(nuevaFecha.getMinutes()).padStart(2, '0');
    const seconds = String(nuevaFecha.getSeconds()).padStart(2, '0');

    const fechaMas24 = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

      // Fecha enviada
    const fechaComparar = new Date(fechaMas24.replace(' ', 'T'));
  
    // Fecha actual
    const hoy = new Date();

    if (hoy > fechaComparar) {    
       Swal.fire({
  title: "Desea desactivar?",
  text: "Una vez que pase las 24 hora el registro ya no es funcional!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "SI, desactivar!"
}).then((result) => {
    
  if (result.isConfirmed) {
    axios.put("/siat_eventos/cambioEstadoEvento", { 
                    id: id,                                                                       
                })
                .then(function (response) {                   
                    let respuesta=response.data;
                      me.listarEventoSignificativoPaquete(1);
                      if(respuesta===0){
                       return Swal.fire('Acción realizada','con exito.','success');
                       }else{
                           return Swal.fire('Error',' '+respuesta,'error');
                       }              
                         console.log(respuesta);                                                          
                })               
                .catch(function (error) {                
                 error401(error);              
            });
  }

});
    
    } else {
     return Swal.fire('Error','No se puede desactivar, no paso 24 horas para desactivar.','error');
    
    }
       
          
        },

        enviarEventoManual(){
            let me=this;
            if (new Date(me.startDate_modal_1) > new Date(me.endDate_modal_1)) {
    
    return Swal.fire('Error','La fecha inicial no debe ser mayor a la fecha final.','error');
}
            if (me.select_query_1=='0'||me.select_query_2=='0'||me.selectListaContingencia_2=='0'||me.endDate_modal_1==''||me.startDate_modal_1==''
                || me.select_ambiente_2=='0'|| me.select_modalidad_2=='0'||me.select_tipoFacturaDoc_2=='0'||me.select_contigencia_2=='0'||me.select_codSector_2=='0'
            ) {
                return Swal.fire('Error','No debe haber espacion sin llenar','error');
            }
            axios.post("/siat_eventos/enviarEventoManual", { 
                    id_sucursal: me.select_query_1,
                    punto_venta: me.select_query_2,
                    id_contigencia:me.selectListaContingencia_2,
                    fecha_fin:me.endDate_modal_1,
                    fecha_ini:me.startDate_modal_1,
                    cafc:me.codigo_cafc,  
                    contingencia_descripcion_modal:me.contingencia_descripcion_modal,
                  contigenciaDes_codigo_modal:me.contigenciaDes_codigo_modal,
                  ambiente_m:me.select_ambiente_2,
                   modalidad_m:me.select_modalidad_2,
                   tipoFacturaDoc_m:me.select_tipoFacturaDoc_2,
                    contigencia_m:me.select_contigencia_2,
                    codSector_m:me.select_codSector_2                                                     
                })
                .then(function (response) {                   
                    let respuesta=response.data;
                       me.listarInicio(0,me.pagina_uno)              
                    me.cerrarModal('contingencia_2')
                      if(respuesta===0){
                       return Swal.fire('Acción realizada','con exito.','success');
                       }else{
                           return Swal.fire('Error',' '+respuesta,'error');
                       }              
                          console.log(respuesta);
                 ;        
                                             
                })               
                .catch(function (error) {                
                 error401(error);              
            });
        },

         consultarEventoSiat() {
            let me=this;
             let url="/siat_eventos/consultarEventoSiat?fechaEmision="+me.fechaPP+"&id_sucursal="+me.select_query_1+"&punto_ventar="+me.select_query_2;
             axios.get(url)
             .then(function (response) {
                 let respuesta=response.data;
                
                 me.mesanje_1_=respuesta.error;
                me.mesanje_2_=respuesta.message;
                me.mesanje_3_=respuesta.nivel;
                me.ver_1_=1;
                 console.log(respuesta);
                 
             })
             .catch(function (error) {
                 error401(error);
                 console.log(error);
             }); 
           
        },

        operacionT(dato){
            let me=this;
            if (dato==1) {
               me.siguienteP=1; 
            }
            if (dato==2) {
                me.select_query_2='0';
               me.siguienteP=0; 
            }
           
         
         
          //  if (dato==1) {
          //      let array=me.array_query_1.find(e=>e.id==select_query_1);
          //      me.punto_suc=array.codigo_siat;
          //  }
        },
        
listarModalSucuralSiatPunto(entrada,id) {
        let me = this;
        let url = "/siat_eventos/listarModalSucuralSiatPunto?entrada="+entrada+"&id="+id;
        axios.get(url)
                .then(function (response) {
                    let respuesta = response.data;   
                    if (entrada==1) {
                        me.array_query_1=respuesta;
                    }

                    if (entrada==2) {
                       
                        me.array_query_2=[];
                        me.array_query_2=respuesta;
                    }                    
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        enviarContingencia(){
             let me = this; 
             
       if (new Date(me.startDate_modal_1) > new Date(me.endDate_modal_1)) {
    
    return Swal.fire('Error','La fecha inicial no debe ser mayor a la fecha final.','error');
}
           
                axios.post("/siat_eventos/enviarContingencia", { 
                    contingencia: me.selectListaContingencia_2,
                    contingencia_descripcion_modal: me.contingencia_descripcion_modal,
                    contigenciaDes_codigo_modal: me.contigenciaDes_codigo_modal,
                    startDate_modal_1: me.startDate_modal_1,
                    endDate_modal_1: me.endDate_modal_1,
                    id_sucursal_modal: me.id_sucursal_modal,
         id_cufd_modal:me.id_cufd_modal,
        punto_suc:me.punto_suc,
        punto_venta:me.punto_venta,
        id_suc_siat:me.id_suc_siat,
        id:me.id_evento_,
       
                                  
                })
                .then(function (response) {                   
                    let respuesta=response.data;
                   console.log(respuesta);
                    me.listarInicio(0,me.pagina_uno);              
                    me.cerrarModal('contingencia');    
                        if(respuesta===0){
                            return Swal.fire('Acción realizada','con exito.','success');
                        }else{
                            return Swal.fire('Error',' '+respuesta,'error');
                        }                              
                })               
                .catch(function (error) {                
                 error401(error);              
            });
        },

        cambioOpcion_modal(data){
            let me=this;
                let array=me.arrayListaContingencia_2.find(e=>e.codigo==data);
                
              console.log(array);
                  if (array!=undefined) {
                    me.contingencia_descripcion_modal=array.descripcion;
                  me.contigenciaDes_codigo_modal=array.codigo;   
            }
            console.log(me.contingencia_descripcion_modal+"--"+me.contigenciaDes_codigo_modal);
        },  
        
         listarQueryModal_1(data2) {
            let me = this;
             
           let url = "/siat_eventos/listarQueryModal_1?id_sucursal="+data2.id_sucursal+"&suc="+data2.punto_venta+"&id_contigencia="+data2.tipo_contigencia;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;   
                        let respues_conti=respuesta.contigencia;           
                    let r=respuesta.sucursal;

                        if(data2.tipo_contigencia===0){
                        me.contingencia_modal="Sin contingencia";
                        me.contigenciaDes_modal="Catalogo normal";
                    }else{
                        
                       me.contingencia_modal=respues_conti.descripcion; 
                        me.contigenciaDes_modal="Caralogo "+respues_conti.codigo; 
                    }
                   

                    if (r!=null) {
                           me.nombreSucursal_modal=r.nombre_suc_siat;
                            me.razonSocialSucursal_modal=r.razon_social;
                    }else{
                           me.nombreSucursal_modal="error nombre";
                            me.razonSocialSucursal_modal="error razon social";
                        
                    }
                    me.tipoAccion = 1;
                
                    me.documento_modal=data2.nro_doc;
                    me.rasonSocial_modal=data2.nom_cliente;

                    me.numeroFactura_modal=data2.numFactura;
                    me.codRecepcion_modal=data2.codRecepcion;
                    me.codAutorizacion_modal=data2.cuf;
                    me.fechaEmision_modal=data2.fechaEmision;

                    me.sectorDescripcion_modal=data2.sector_descripcion;
                    if (data2.codEstado==='908') {
                    me.estado_modal="VALIDO";    
                    }else{
                    me.estado_modal="OBSERVADO";                        
                    }
                    me.puntoVenta_modal=data2.punto_suc;
                    me.tituloModal = "Datos de venta facturada.";
                
                    
                     me.showModal = true;
                    me.classModal.openModal("registrar");
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

 listarEmisorAndSector() {
            let me = this;
           var url = "/siat_eventos/listarEmisorAndSector";
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    let respuesta_1=respuesta.emisor_query_1;
                    let respuesta_2=respuesta.sector_query_2;
                    let error_1=respuesta.error_1;
                    let error_2=respuesta.error_2;
                    if (error_1==0 && error_2==0) {
                           Swal.fire("Error!", "No existe datos en la lista de sector ni en emisor",  "error",);                     
                    }
                    else{
                        if (error_1==0||error_2==0) {
                            Swal.fire("Error!", "No existe datos en la lista de emisor o sel sector",  "error",); 
                        }                        
                    }
                    me.emisor_query_1=respuesta_1;
                    me.sector_query_2=respuesta_2;

                    console.log(respuesta);
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

listarInicio(page,data)
            {
                let me=this;
                let valor=0;
                let url="";
                me.pagina_uno=data;
                switch (data) {
                    case 1:{
                     url='/siat_eventos/listarInicio?page='+page+'&id_sucursal='+me.selectEmisor+'&inicial='+data;
                    break;
                    }
                    case 2:{
                     url='/siat_eventos/listarInicio?page='+page+'&num_factura='+me.buscar_factura+'&inicial='+data;
                    break;                    
                    }
                    case 3:{
                     url='/siat_eventos/listarInicio?page='+page+'&cuf='+me.buscar_cuf+'&inicial='+data;
                    break;                    
                    }
                    case 4:{
                     url='/siat_eventos/listarInicio?page='+page+'&sector='+me.selectSector+'&inicial='+data    ;
                    break;                    
                    }
                    case 5:{
                    url='/siat_eventos/listarInicio?page='+page+'&estado='+me.selectEstado+'&inicial='+data;
                    break;                    
                    }
                    case 6:{
                    url='/siat_eventos/listarInicio?page='+page+'&fecha_inicio='+me.startDate+'&fecha_fin='+me.endDate+'&inicial='+data;
                    break;                    
                    }
                    default:{
                        valor=1;
                        break;
                    }
                }
                if (valor==1) {
                 return Swal.fire('Error!','error de lista','error');
                }
               
                me.inicioArray=[];
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                      me.inicioArray =respuesta.index.data;


                    console.log(me.inicioArray);
                  
                })
                .catch(function(error){
                    error401(error);
                });
            },

        sucursalFiltro() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

          listarSiatListaContingencia() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/listarSiatListaContingencia";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                  
                    me.arrayListaContingencia_2 = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        

         listarAutmoSelect() {
            let me = this;
           var url = "/siat_eventos/listarAutmo_select";
            axios
                .get(url)
                .then(function (response) {
                    const valores = [2, 4];
                    const random = valores[Math.floor(Math.random() * valores.length)];
                    let respuesta = response.data; 
                    let query_1=respuesta.query_1;  
                    let query_2=respuesta.query_2;
                    me.select_ambiente_2=query_2.tipo_ambiente; 
                    me.select_modalidad_2=query_2.tipo_modalidad;
                    me.select_tipoFacturaDoc_2=query_1.dos;
                    me.select_contigencia_2=2;
                    me.select_codSector_2=query_1.tres;
                   console.log(respuesta);
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

         listarModalDatosAdcionales() {
            let me = this;
           var url = "/siat_eventos/listarModal_datos_adcionales";
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;     
                    me.array_contigencia_2=respuesta.contigencia;
                    me.array_tipoFacturaDoc_2=respuesta.tipoFacturaDoc;
                    me.array_codSector_2=respuesta.codsedtor;           
                    console.log(me.array_codSector_2);
                 
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
            me.listarInicio(page,me.pagina_uno);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
            
                case "registrar": {
                    me.tipoAccion = 1;
                    console.log(data);
                    me.documento_modal=data.nro_doc;
                    me.rasonSocial_modal=data.nom_cliente;

                    me.numeroFactura_modal=data.numFactura;
                    me.codRecepcion_modal=data.codRecepcion;
                    me.codAutorizacion_modal=data.cuf;
                    me.fechaEmision_modal=data.fechaEmision;

                    me.sectorDescripcion_modal=data.sector_descripcion;
                    if (data.codEstado==='908') {
                    me.estado_modal="VALIDO";    
                    }else{
                    me.estado_modal="OBSERVADO";                        
                    }
                    me.puntoVenta_modal=data.punto_suc;
                    me.tituloModal = "Datos de venta facturada.";
                    me.listarQueryModal_1(data.id_sucursal,data.punto_suc,data.tipo_contigencia);
                
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
          
            
                    me.classModal.openModal("registrar");

                    break;
                }

                case "contingencia":{
                   me.tipoAccion = 1;
                   me.showModal_2=true;
                    me.tituloModal = "Contingencia de datos.";
                     if (data.tipo_contigencia==0||data.tipo_contigencia==null) {
                    me.selectListaContingencia_2="0";     
                    }else{
                    me.selectListaContingencia_2=data.tipo_contigencia; 
                    }
                    
                    me.contingencia_descripcion_modal='';
                    me.contigenciaDes_codigo_modal='';
                    me.startDate_modal_1='';
                     me.endDate_modal_1='';
                     me.id_cufd_modal=data.id_cufd;
                     me.punto_suc=data.punto_suc;
                    me.punto_venta=data.punto_venta;
                    me.id_sucursal_modal=data.id_sucursal;
                    me.id_suc_siat=data.id_suc_siat;
                    me.id_evento_=data.id;
                    console.log(data);
            
                    me.classModal.openModal("contingencia");

                    break;  
                }
                case "contingencia_consullta_modal":{
                   me.tipoAccion = 1;
                   me.showModal_3=true;
                    me.tituloModal = "Estado contingencia.";
            me.select_query_2='0';
            me.select_query_1='0';
            me.siguienteP=0;
            me.siguienteP_1=0;  
            me.fechaPP='';

        me.mesanje_1_='';
        me.mesanje_2_='';
        me.mesanje_3_='';
        me.ver_1_=0;
            
                    me.classModal.openModal("contingencia_consullta_modal");

                    break;  
                }

                 case "contingencia_2":{
                   me.tipoAccion = 1;
                   me.showModal_4=true;
                    me.tituloModal = "Registro de nuevo evento de contigencia.";
                    me.select_query_2='0';
                    me.selectListaContingencia_2='0';
                    
            me.select_query_1='0';
            me.siguienteP=0;
            me.siguienteP_1=0;  
            me.fechaPP='';

            me.codigo_cafc="";
            me.endDate_modal_1="";
            me.startDate_modal_1="";

             me.select_ambiente_2='0';
            me.select_tipoFacturaDoc_2='0';          
            me.select_contigencia_2='0';
            me.select_codSector_2='0';
            me.select_modalidad_2='0';

                
            
                    me.classModal.openModal("contingencia_2");

                    break;  
                }

                case "envioPaquete":{
                
                me.showModal_5= true;
                me.tituloModal="Envio de paquetes";
                me.classModal.openModal("envioPaquete");
                break;
                }
            
            }
        },

        fecha_inicial(){
            // Obtener la fecha actual
    const today = new Date();
    // Obtener el año, mes y día actual
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const day = String(today.getDate()).padStart(2, '0');

    // Asignar la fecha del primer día del mes al input de fecha de inicio
    this.startDate = `${year}-${month}-01`;
    // Asignar la fecha actual al input de fecha final
    this.endDate = `${year}-${month}-${day}`;
        },

        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = " ";
            }    
            if (accion == "contingencia") {
                me.classModal.closeModal(accion);
                me.showModal_2 = false;
          me.selectListaContingencia_2='0';
                    me.contingencia_descripcion_modal='';
                    me.contigenciaDes_codigo_modal='';
                    me.startDate_modal_1='';
                     me.endDate_modal_1='';
   
     me.id_cufd_modal='';
                     me.punto_suc='';
                    me.punto_venta='';
                    me.id_sucursal_modal='';
                    me.id_suc_siat='';
me.id_evento_="";
                me.tituloModal = " ";
            }    
            if (accion == "contingencia_consullta_modal") {
                me.classModal.closeModal(accion);
                me.showModal_3 = false;
                 me.tituloModal = " ";
               me.select_query_2='0';
            me.select_query_1='0';
            me.siguienteP=0;
            me.siguienteP_1=0;  
            me.fechaPP='';

        me.mesanje_1_='';
        me.mesanje_2_='';
        me.mesanje_3_='';
        me.ver_1_=0;
            }    

            if (accion == "contingencia_2") {
                me.classModal.closeModal(accion);
                me.showModal_4 = false;
                 me.tituloModal = " ";
                  me.select_query_2='0';
                    me.selectListaContingencia_2='0';
                    
            me.select_query_1='0';
            me.siguienteP=0;
            me.siguienteP_1=0;  
            me.fechaPP='';

            me.codigo_cafc="";
            me.endDate_modal_1="";
            me.startDate_modal_1="";

            me.select_ambiente_2='0';
            me.select_tipoFacturaDoc_2='0';          
            me.select_contigencia_2='0';
            me.select_codSector_2='0';
            me.select_modalidad_2='0';

             
            } 
            
            if (accion=="envioPaquete") {
                me.classModal.closeModal(accion);
                me.showModal_5= false;
                me.tituloModal="";
                
            }

            
        },

     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        this.sucursalFiltro();
        this.fecha_inicial();
        this.listarEmisorAndSector();
        this.listarSiatListaContingencia();
        this.classModal.addModal("registrar");
        this.classModal.addModal("contingencia");
        this.classModal.addModal("contingencia_consullta_modal");
        this.classModal.addModal("contingencia_2");
         this.classModal.addModal("envioPaquete");
        this.listarModalSucuralSiatPunto(1,0);
        this.listarModalDatosAdcionales();
         
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
.modal-ancho {
    width: 98%;
    max-width: 98%;
    margin: 10px auto;
}
</style>
