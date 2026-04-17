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
                <div class="card-header" v-if="puedeCrear==1" >
                    <i class="fa fa-align-justify"></i> Gestion inventario por periodo               
                    <button 
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar_0');"
                        :disabled="sucursalSeleccionada == 0 || peridoSelect=='0'"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="cambioSucursal(sucursalSeleccionada)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"
                                        v-text="sucursal.codigoS +' -> ' +sucursal.codigo+' ' +sucursal.razon_social"
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)"                                
                                    :hidden="sucursalSeleccionada == 0 || peridoSelect=='0'"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"                               
                                    :hidden="sucursalSeleccionada == 0 || peridoSelect=='0'"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

           <div class="form-group row" v-if="sucursalSeleccionada !== 0">
    <div class="col-md-2">
    </div>
    <div class="col-md-3">
         <label for="start-date">Periodo:</label>
        <div class="input-group">
            <select class="form-control" v-model="peridoSelect" @change="listarIndex(0)">
                <option value="0" disabled selected>Seleccionar...</option>
                <option value="D">Diario</option>
                <option value="G">Global</option>
           
            </select>
        </div>
    </div>
     <div class="col-md-3">
        <label for="">Estado</label>
         <div class="input-group">
            <select class="form-control" v-model="estadoSelect" @change="listarIndex(0)">
                <option value="0" disabled selected>Seleccionar...</option>
                <option value="A" v-if="puedeActivar==1">Ver anulado</option>
                <option value="N">Ver normal</option>
            </select>
        </div>
     </div>
        <div class="col-md-2">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" @change="listarIndex(0)">
        </div>
        <div class="col-md-2">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" @change="listarIndex(0)">
        </div>     
  </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-2">Opciones</th>
                        <th class="col-md-2">Nombre</th>                      
                        <th class="col-md-2">Motivo</th>
                        <th class="col-md-2">Fecha de creacion</th>
                        <th class="col-md-2">Usuario</th>
                        <th class="col-md-1">Proceso</th>
                        <th class="col-md-1">Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayInicio" :key="index">
                        <td class="col-md-2">
                            <div class="button-container">
                                <div  class="d-flex justify-content-start">
                                     <div>
                                        <button  type="button" class="btn btn-primary" style="margin-right: 5px;" @click="listarModalData(i.id,1,0);" v-if="i.enproceso==4">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-secondary" style="margin-right: 5px;" v-else>
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </button>                                        
                                     </div>
                                     <div>
<button  type="button" class="btn btn-warning" style="margin-right: 5px; color: white;" @click="abrirModal('ver',i);listarModalData(i.id,0,0);" v-if="i.enproceso==4">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-secondary" style="margin-right: 5px; color: white;" v-else>
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                                     </div>
                                      
                            
                                    <div v-if="puedeActivar==1">
                            <button v-if="i.estado == 1" type="button" class="btn btn-danger" style="margin-right: 5px;" @click="cambioEstado(i.id,0)">
                                <i class="icon-trash"></i>
                            </button>
                            <button v-else type="button" class="btn btn-success" style="margin-right: 5px;" @click="cambioEstado(i.id,1)">
                                <i class="icon-check"></i>
                            </button>
                                    </div>
                                      <div v-else>
                <button v-if="i.estado == 1" type="button" class="btn btn-light "
                 style="margin-right: 5px;">
                <i class="icon-trash"></i>
            </button>
            <button v-else type="button" class="btn btn-light"  style="margin-right: 5px;">
                <i class="icon-check"></i>
            </button>
            </div>
            <div>
 <button  type="button" class="btn btn-success" style="margin-right: 5px; color: white;" @click="abrirModal('registrar',i);" v-if="i.enproceso==0">
                               <i class="fa fa-star-o" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-secondary" style="margin-right: 5px;" v-else>
                             <i class="fa fa-star-o" aria-hidden="true"></i>
                            </button>
            </div>
            <div>
 <button  type="button" class="btn btn-success" style="margin-right: 5px; color: white;"  v-if="i.enproceso==1" @click="abrirModal('actualizar',i);">
                               <i class="fa fa-star-half-o" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-secondary" style="margin-right: 5px;" v-else>
                              <i class="fa fa-star-half-o" aria-hidden="true"></i>
                            </button>
            </div>
            <div>
 <button  type="button" class="btn btn-success" style="margin-right: 5px; color: white;"  v-if="i.enproceso==2"  @click="abrirModal('registrar_00',i);listarTabla_tres(i.id_dos);">
                            <i class="fa fa-star" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-secondary" style="margin-right: 5px;" v-else>
                           <i class="fa fa-star" aria-hidden="true"></i>
                            </button>
            </div>
              <div>
                        <button  type="button" class="btn btn-success" style="margin-right: 5px; color: white;"  v-if="i.enproceso==3" @click="reiniciarLote(i)">
                           <i class="fa fa-window-restore" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-secondary" style="margin-right: 5px;" v-else>
                          <i class="fa fa-window-restore" aria-hidden="true"></i>
                            </button>
            </div>
            <div v-show="puedeHacerOpciones_especiales==1">
                        <button  type="button" class="btn btn-success" style="margin-right: 5px; color: white;"  v-if="i.enproceso==4&&i.enviado===0"  @click="abrirModal('panelControl',i);listarModalData(i.id,0,1);">
                           <i class="fa fa-exclamation" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-secondary" style="margin-right: 5px;" v-else>
                          <i class="fa fa-exclamation" aria-hidden="true"></i>
                            </button>
            </div>
             
          

           
          
            
                                </div>
                            </div>    
                          
                            
                        </td>
                        <td class="col-md-2">
                            {{i.nombre}}
                        </td>
                        <td class="col-md-2">
                            {{i.motivo}}
                        </td>
                        
                         <td class="col-md-3">
                            {{i.fecha_creacion}}
                        </td>
                        <td class="col-md-2">
                            {{i.name}}
                        </td>
                        <td class="col-md-1">
                            <div v-if="i.enproceso === 0"><span class="badge badge-success">Sin uso</span></div>
<div v-else-if="i.enproceso === 1"><span class="badge badge-warning">Proceso iniciado</span></div>
<div v-else-if="i.enproceso === 2"><span class="badge badge-warning">Procesos en espera</span></div>
<div v-else><span class="badge badge-danger">Terminado</span></div>
                            
                    </td>
                         <td class="col-md-1">
                            <div v-if="i.estado == 1">
                                        <span class="badge badge-success"
                                            >Activo</span
                                        >
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning"
                                            >Desactivado</span
                                        >
                                    </div>
                    </td>
                    </tr>
                </tbody>
            </table>    
 <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active':'']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page< pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
           <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-super-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                    </div>
                      <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">  
                        <div class="alert alert-warning" role="alert" v-show="tipoAccion===1">
                            Todos los campos con (*) son requeridos
                        </div>
                      
                        
                            <!-- insertar datos -->
                                                         
                                <div class="form-group row" v-if="tipoAccion===1">
                                    <div class="col-md-2" v-show="peridoSelect=='D'">
                                        <label for="">Fecha:</label>
                                          <input id="start-date" type="date" class="form-control" v-model="fecha_0_0" :disabled="activador==1">
                                    </div>

                                    <div class="col-md-2"  v-show="peridoSelect=='D'">
                                        <label for="">Turno:</label>
                                        <select class="form-control" v-model="turnoSelect" :disabled="activador==1"> 
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Mañana</option>
                                            <option value="2">Tarde</option>
                                            <option value="3">Completo</option> 
                                        </select>
                                    </div> 
                                    <div class="col-md-4">
                                        <label for="">Linea:</label>
                                         <VueMultiselect
                        v-model="lineaSelect"
                                        :disabled="activador==1"
                        :options="arrayLinea"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="nombre" 
                                           
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado">
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect> 
                                    </div> 
                                    <div class="col-md-2">
                                        <div v-if="peridoSelect=='D'">
                                        <button type="button" style="margin-top: 27px;" class="btn btn-primary" :disabled="turnoSelect=='0'||lineaSelect==null || fecha_0_0==''" @click="listarProducto(lineaSelect.id);cambioActivar(1)" v-if="activador==0">
                                            Procesar
                                        </button>
                                        <button type="button" style="margin-top: 27px;" class="btn btn-danger" :disabled="turnoSelect=='0'||lineaSelect==null" @click="cambioActivar(0)" v-else>
                                            Quitar
                                        </button>
                                        </div>
                                        <div v-else>
                                              <button type="button" style="margin-top: 27px;" class="btn btn-primary" :disabled="lineaSelect==null" @click="listarProducto(lineaSelect.id);cambioActivar(1)" v-if="activador==0">
                                            Procesar
                                        </button>
                                        <button type="button" style="margin-top: 27px;" class="btn btn-danger" :disabled="turnoSelect=='0'||lineaSelect==null" @click="cambioActivar(0)" v-else>
                                            Quitar
                                        </button>
                                        </div>
                                       
                                    </div>  
                                       <div class="col-md-2">
                                        <button type="button" style="margin-top: 27px; color: white;" class="btn btn-warning" @click="empezarFuncion(1)" v-if="activador_00==1">
                                            Empezar
                                        </button>
                                         <button type="button" style="margin-top: 27px; color: white;" class="btn btn-secondary" v-else>
                                            Empezar
                                        </button>
                                       </div>    
                                </div>
                               
                                <div class="alert alert-primary" role="alert" v-else>
                                   
                                <h5> <strong>Fecha: {{nombre_fecha_accion_2}} Turno: {{nombre_turno_accion_2}} Linea: {{nombre_linea_accion_2}}</strong></h5>
                                </div>
                               
                                <div class="alert alert-success" role="alert" v-show="activador_00==1">
  <h5>Se encontraron {{ cantidadTamanio }} productos, debe apretar el boton de empezar para poder empezar el conteo.</h5> 
</div>

<div class="alert alert-primary" role="alert" v-if="peridoSelect=='D'&&((turnoSelect=='0'||lineaSelect==null)&&tipoAccion==1)">
  Debe seleccionar y completar todas las opciones 
</div>
<div v-else>
<div class="alert alert-warning" role="alert" v-show="activador==1&&tipoAccion==1">
  Debe apretar en boton rojo para quitar la seleccion, se hara una nueva busqueda de productos pero se borrar lo que busco anterior mente.
</div>
     
 <div class="form-group row" v-show="activador==1" >
        <div class="col-md-3">
            <button type="button"  class="btn btn-primary" @click="activarLecto(1)" v-if="verLector==0">Usar lector</button>      
            <button type="button"  class="btn btn-secondary" @click="activarLecto(0)" v-else>Desactivar lector</button>                            
        </div>
        <div class="col-md-5" v-show="verLector==1">
            <input type="text" class="form-control" v-model="inputBuscarCodigo" @keyup.enter="procesarCodigo" placeholder="Escannear el código">
        </div>
    
 </div>                                   
 <div v-if="verContenido==1&&verLector==1 && activarProceso==1">
    <div class="alert alert-primary" role="alert"><h5>{{alertMensajeLector}}</h5></div>

<table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-3">Producto</th>
                        <th class="col-md-2">Codigo</th>
                        <th class="col-md-2">Lote</th>
                        <th class="col-md-2">Fecha ingreso</th>
                        <th class="col-md-2">Fecha vencimiento</th>
                        <th class="col-md-1">Cantidad</th> 
                    
                       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in productosSeleccionados" :key="index">
                        <td class="col-md-3">{{i.leyenda}}</td>
                        <td class="col-md-2">{{i.codigo_imprecion}}</td>
                        <td class="col-md-2">{{i.lote}}</td>
                        <td class="col-md-2">{{i.fechaIngreso}}</td>
                        <td class="col-md-2">{{i.fecha_vencimiento}}</td>
                        <td class="col-md-1" style="text-align: center;">{{i.cantidad}}</td>                                        
                    </tr>
                </tbody>
            </table>
            <button type="button"  class="btn btn-primary btn-lg" @click="registrarConLencto()" v-show="activador==1" :disabled="isSubmitting==true||productosSeleccionados.length<=0">
                            Cargar tabla 
                        </button>
 </div>
 <div v-else-if="verContenido==1 && verLector == 0 && activarProceso==1">
<table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-3">Producto</th>
                        <th class="col-md-2">Codigo</th>
                        <th class="col-md-2">Lote</th>
                        <th class="col-md-2">Fecha ingreso</th>
                        <th class="col-md-2">Fecha vencimiento</th>
                        <th class="col-md-1">Cantidad</th>                        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayProductoLineaIngreso" :key="index">
                        <td class="col-md-3">{{i.nom_producto+" - "+i.nom_dis+" X "+i.cantidad_d+" "+i.nom_forma_faraceutica+" "+i.envase}}</td>
                        <td class="col-md-2">{{i.codigo_imprecion}}</td>
                        <td class="col-md-2">{{i.lote}}</td>
                        <td class="col-md-2">{{i.fecha_ingreso}}</td>
                        <td class="col-md-2">{{i.fecha_vencimiento}}</td>
                        <td class="col-md-1">
                            <input type="number" class="form-control"  @input="guardarProducto(i, $event.target.value)" min="0">                         
                        </td> 
                                       
                    </tr>
                </tbody>
</table>  
<button type="button"  class="btn btn-primary btn-lg" @click="registrar()" v-show="activador==1" :disabled="isSubmitting==true">
                            Cargar tabla 
                        </button>             
 </div>
<div class="alert alert-warning" role="alert" v-else>
  Debe apretar el boton empezar para ver las tablas.
</div>
  
</div>

          
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')" >
                            Cerrar
                        </button>
                      
                    </div>
                    </div>    
                </div>
            </div>  
    </transition>
        <!--fin del modal-->

        <!--Inicio del modal ver-->
           <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-super-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('ver')">
                            <span>&times;</span>
                        </button>
                    </div>
                      <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">  
                   
                        <form action="" class="form-horizontal">
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th>Nombre de inventario</th>
                                    <th>Nombre de motivo</th>
                                    <th>Fecha creación</th>
                                    <th>Tipo</th>
                                    <th>Inventario</th>
                                    <th>Usuario</th>
                                    <th>Bloqueado</th>
                                </tr>
                                <tr>
                                    <td>{{arrayCabeza.nombre}}</td>
                                    <td>{{arrayCabeza.motivo}}</td>
                                    <td>{{arrayCabeza.created_at}}</td>
                                    <td>{{arrayCabeza.enproceso}}</td>
                                    <td>{{arrayCabeza.tipo_inventario}}</td>
                                    <td>{{arrayCabeza.name}}</td>
                                    <td>
                                        <div v-if="arrayCabeza.id_bloqueo==null">
                                            <span>Sin bloqueo</span>
                                        </div>
                                        <div v-else>
                                            <span>Bloqueado</span>
                                        </div>
                                    </td>
                                </tr>
                            </thead> 
                        </table>          
                        
            
            <table class="table table-bordered table-striped table-sm table-responsive">
                <thead>
                    <tr>
                        <th class="col-md-3">Producto</th>
                        <th>Linea</th>
                        <th>Lote</th>
                        <th>Fecha vencimiento</th>
                        <th>Turno</th>
                        <th>Cantidad existentes</th>
                        <th>Cantidad fisica</th>
                        <th>Diferencia</th>
                        <th>Resultado</th>
                        <th>Observación</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayCuerpo" :key="index">
                        <td class="col-md-3">{{i.leyenda+" Envase: "+i.envase}}</td>
                        <td>{{i.nom_linea}}</td>
                        <td>{{i.lote}}</td>
                       <td>{{i.fecha_v}}</td>
                        <td>{{i.turno}}</td>
                       <td>{{i.cantidad_sis_detalle_inventario}}</td>
                       <td>{{i.cantidad_reg_detalle_inventario}}</td>
                       <td>{{i.diferencia_detalle_inventario}}</td>
                       <td>{{i.estado}}</td>   
                       <td>
    {{ i.observacion }}
    <i v-if="i.repetido == 'REPETIDO'"
       class="fa fa-bug fa-2x"
       aria-hidden="true"
       style="color: red; margin-left:5px;">
    </i>
</td>                   
                    </tr>
                </tbody>
                
            </table> 
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('ver')">
                            Cerrar
                        </button>                    
                    </div>
                    </div>    
                </div>
            </div>  
    </transition>
        <!--fin del modal-->

        <!--Inicio del modal crear computo-->
           <transition name="fade">
            <div v-if="showModal_3" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_0')">
                            <span>&times;</span>
                        </button>
                    </div>
                      <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">  
                   
                        <form action="" class="form-horizontal">
                          <div class="form-group row">                                    
                            <div class="col-md-6">
                            <label for="">Nombre:</label>
                             <input type="text" class="form-control" v-model="model_0_nombre">
                            </div>
                            <div class="col-md-6">
                            <label for="">Motivo:</label>
                             <input type="text" class="form-control" v-model="model_0_motivo">
                            </div>
                         </div>       
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar_0')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registro_0_0()">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">
                            Actualizar
                        </button>
                    </div>
                    </div>    
                </div>
            </div>  
    </transition>
        <!--fin del modal-->
        <!--Inicio del modal crear computo-->
           <transition name="fade">
            <div v-if="showModal_4" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-super-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_00')">
                            <span>&times;</span>
                        </button>
                    </div>
                      <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">  
                   
                        <form action="" class="form-horizontal">
                            <div v-if="arrayTabla3.length>0">
                                 <table class="table table-bordered table-striped table-sm table-responsive" >
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Linea</th>
                                        <th>Lote</th>
                                        <th>Fecha vencimiento</th>
                                        <th>Cantidad existentes</th>
                                        <th>Cantidad fisica</th>
                                        <th>Diferencia</th>
                                        <th>Resultado</th>
                                        <th>Observación</th>						
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(i, index) in arrayTabla3" :key="index">
                                        <td>{{ i.leyenda }}</td>
                                        <td>{{ i.nom_linea }}</td>
                                        <td>{{ i.lote }}</td>
                                        <td>{{ i.fecha_v }}</td>
                                        <td>{{ i.cantidad_sis_detalle_inventario }}</td>
                                        <td>{{ i.cantidad_reg_detalle_inventario }}</td>
                                        <td>{{ i.diferencia_detalle_inventario }}</td>
                                        <td>{{ i.estado }}</td>
                                        <td>
                                        <input type="text"  class="form-control"  v-model="arrayTabla3[index].observacion">
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                            </div>
                           
                            <div class="alert alert-success" role="alert"  v-else>
  No tiene ninguna observación.
</div>
                              
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar_00')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="guardarObservacion()">
                            Terminar conteo
                        </button>
                  
                    </div>
                    </div>    
                </div>
            </div>  
    </transition>
        <!--fin del modal-->

         <!--Inicio del modal crear panel de control-->
           <transition name="fade">
            <div v-if="showModal_5" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-super-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('panelControl')">
                            <span>&times;</span>
                        </button>
                    </div>
                      <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">  
                            <form action="" class="form-horizontal">
                                <div class="alert alert-danger" role="alert" v-if="panelControl_key===1">
                                <h5>Error el conteo contiene errores de duplicidad</h5>
                                </div>
                                <div v-else>
                                      <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th>Nombre de inventario</th>
                                    <th>Nombre de motivo</th>
                                    <th>Fecha creación</th>
                                    <th>Tipo</th>
                                    <th>Inventario</th>
                                    <th>Usuario</th>
                                    <th>Bloqueado</th>
                                </tr>
                                <tr>
                                    <td>{{arrayCabeza.nombre}}</td>
                                    <td>{{arrayCabeza.motivo}}</td>
                                    <td>{{arrayCabeza.created_at}}</td>
                                    <td>{{arrayCabeza.enproceso}}</td>
                                    <td>{{arrayCabeza.tipo_inventario}}</td>
                                    <td>{{arrayCabeza.name}}</td>
                                    <td>
                                        <div v-if="arrayCabeza.id_bloqueo==null">
                                            <span>Sin bloqueo</span>
                                        </div>
                                        <div v-else>
                                            <span>Bloqueado</span>
                                        </div>
                                    </td>
                                </tr>
                            </thead> 
                        </table>          
                        
            
            <table class="table table-bordered table-striped table-sm table-responsive">
                <thead>
                    <tr>
                        <th class="col-md-2">Producto</th>
                        <th>Linea</th>
                        <th>Lote</th>                  
                     
                        <th>Cantidad existentes</th>
                        <th>Cantidad fisica</th>
                        <th>Diferencia</th>
                        
                        <th>Observación</th> 
                        <th>Estado</th>  
                        <th>Resultado</th> 

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayCuerpo" :key="index">
                        <td class="col-md-2">{{i.leyenda+" Envase: "+i.envase}}</td>
                        <td>{{i.nom_linea}}</td>
                        <td>{{i.lote}}</td>
                  
                       
                       <td>{{i.cantidad_sis_detalle_inventario}}</td>
                       <td>{{i.cantidad_reg_detalle_inventario}}</td>
                       <td>{{i.diferencia_detalle_inventario}}</td>
                            <td>
    {{ i.observacion }}
   
</td>   
<td>{{i.estado}}</td>

                       <td>
                      
                            <select class="form-control" v-model="i.turnoSelect" @change="cambiarSelect(index, i.turnoSelect)"> 
                                
                                            <option v-for="(t, index2) in arrayTipoEntrada" :key="index2" :value="t.id"  v-show="i.estado === 'SOBRANTE' ? t.positivo === 1 : t.negativo === 1"
                                     
                                            >{{t.nombre}}</option>
                                           
                            </select>
                     
                       
                        
                    </td>   
                                  
                    </tr>
                </tbody>
                
            </table> 
                                </div>
                      
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('panelControl')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" :disabled="panelControl_key===1" @click="registro_ajuste_n_p()">
                            Terminar ajuste
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
import VueMultiselect from 'vue-multiselect';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import { forEach } from "lodash";
// Asigna los fonts a pdfmake
pdfMake.vfs = pdfFonts.pdfMake.vfs;
//Vue.use(VeeValidate);
export default {
     components: { VueMultiselect },
     //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
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
            showModal: false,
            showModal_2: false,
            showModal_3: false,
             showModal_4: false,
             showModal_5:false,
            offset:3,
            isSubmitting:false,

            tituloModal: '',
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:'',
            tipoAccion:1,
            startDate: '',
      endDate: '',

            id_sucursal:'',
            id_tienda:'',
            id_almacen:'',
            peridoSelect:'0',

arrayProductoLineaIngreso:[],
            arrayLinea:[],

            dateInput:'',
            turnoSelect:'0',
            lineaSelect:null,   
            productosSeleccionados: [],

            estadoSelect:'N',
            arrayInicio:[],

            usuario_ver:'',
            linea_ver:'',
            fecha_ver:'',
            estado_ver:'',
            arrayProductoDetalle:[],

             //---permisos_R_W_S
            puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------

                model_0_motivo:'',
                model_0_nombre:'',

                fecha_0_0:'',
                activador:0,

                verLector:0,
                inputBuscarCodigo:'',
                cadenaInput:'',     
                
                cantidadTamanio:0,
                verContenido:0,
                id_index:0,

                nombre_fecha_accion_2:'',
                nombre_turno_accion_2:'',
                nombre_linea_accion_2:'',
                id_dos:'',

                activador_00:0,

                arrayTabla3:[],
                activarProceso:0,

                id_bloqueo_1:'',

                id_tabla_dos_momentanio:'',

                arrayCabeza:[],
                arrayCuerpo:[],

                panelControl_key:0,
                arrayTipoEntrada:[],
                ajuste__:0,

          
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

        //-----------------------------------permisos_R_W_S        
listarPerimsoxyz() {
         
    let me = this;
        
    var url = '/gestion_permiso_editar_eliminar?win='+me.codventana;
  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;
     
            if(respuesta=="root"){
            me.puedeEditar=1;
            me.puedeActivar=1;
            me.puedeHacerOpciones_especiales=1;
            me.puedeCrear=1; 
            }else{
            me.puedeEditar=respuesta.edit;
            me.puedeActivar=respuesta.activar;
            me.puedeHacerOpciones_especiales=respuesta.especial;
            me.puedeCrear=respuesta.crear;        
            }
           
        })
        .catch(function(error) {
            error401(error);
        });
},

//-------------------------------------------------------------

    descargaPDF(array_c, array_b) {

    let me = this;

    const tableHeader = [];
    const tableBody = [];
    let valor_1;

    if (array_c.id_bloqueo == null) {
        valor_1 = 'Sin bloqueo';
    } else {
        valor_1 = 'Bloqueado';
    }

    // Encabezado información
    tableHeader.push([
        { text: 'Nombre de inventario', style: 'tableHeader' },
        { text: 'Nombre de motivo', style: 'tableHeader' },
        { text: 'Fecha creación', style: 'tableHeader' },
        { text: 'Tipo', style: 'tableHeader' },
        { text: 'Inventario', style: 'tableHeader' },
        { text: 'Usuario', style: 'tableHeader' },
        { text: 'Bloqueado', style: 'tableHeader' },
    ]);

    // Datos información
    tableHeader.push([
        { text: array_c.nombre, fontSize: 8 },
        { text: array_c.motivo, fontSize: 8 },
        { text: array_c.created_at, fontSize: 8 },
        { text: array_c.enproceso, fontSize: 8 },
        { text: array_c.tipo_inventario, fontSize: 8 },
        { text: array_c.name, fontSize: 8 },
        { text: valor_1, fontSize: 8 },
    ]);

    // Encabezados tabla detalle
    tableBody.push([
        { text: 'Producto', style: 'tableHeader' },
        { text: 'Linea', style: 'tableHeader' },
        { text: 'Lote', style: 'tableHeader' },
        { text: 'Fecha vencimiento', style: 'tableHeader' },
        { text: 'Turno', style: 'tableHeader' },
        { text: 'Cant. sistema', style: 'tableHeader' },
        { text: 'Cant. física', style: 'tableHeader' },
        { text: 'Diferencia', style: 'tableHeader' },
        { text: 'Observación', style: 'tableHeader' },
        { text: 'Resultado', style: 'tableHeader' },
    ]);

    // Datos detalle
    array_b.forEach(i => {

        let colorFila = (i.repetido == 'REPETIDO') ? 'red' : 'black';

        tableBody.push([
            { text: i.leyenda + " " + i.envase, fontSize: 7, color: colorFila },
            { text: i.nom_linea, fontSize: 7, color: colorFila },
            { text: i.lote, fontSize: 7, color: colorFila },
            { text: i.fecha_v, fontSize: 7, color: colorFila },
            { text: i.turno, fontSize: 7, color: colorFila },
            { text: i.cantidad_sis_detalle_inventario, fontSize: 7, color: colorFila },
            { text: i.cantidad_reg_detalle_inventario, fontSize: 7, color: colorFila },
            { text: i.diferencia_detalle_inventario, fontSize: 7, color: colorFila },
            { text: i.observacion, fontSize: 7, color: colorFila },
            { text: i.repetido, fontSize: 7, color: colorFila },
        ]);

    });

    const docDefinition = {
        pageSize: 'LETTER',
        pageMargins: [25, 40, 25, 40],

        content: [

            { text: 'INFORME DE CONTEO', style: 'header' },

            {
                margin: [0, 10, 0, 0],
                table: {
                    headerRows: 1,
                    widths: [80, 80, 60, 50, 60, 60, 50],
                    body: tableHeader
                }
            },
            {
                margin: [0, 10, 0, 0],
                table: {
                    headerRows: 1,
                    widths: ['*', 40, 40, 45, 40, 40, 40, 40, 70, 40],
                    body: tableBody
                }
            }

        ],

        styles: {

            header: {
                fontSize: 13,
                bold: true,
                alignment: 'center',
                margin: [0, 0, 0, 10]
            },

            tableHeader: {
                bold: true,
                fontSize: 8,
                color: 'black',
                fillColor: '#d3d3d3',
                alignment: 'center'
            }

        }
    };

    pdfMake.createPdf(docDefinition).open();
},
//------------------------lector codigo----------------------------------
reiniciarLote(data){
    let me = this;

    Swal.fire({
        title: "¿Desea terminar el proceso de conteo?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Quiero añadir más procesos",
        denyButtonText: "Terminar proceso y consolidar"
    }).then((result) => {

        let tipo = -1;

        if (result.isConfirmed) {
            tipo = 0;
        } else if (result.isDenied) {
            tipo = 4;
        } else {
            return; // cancelado → no hace nada
        }

      
        axios.put("/inventario-periodo/terminarProceso_continuar", { 
            id_index: data.id,
            tipo: tipo
        })
        .then(function (response) {               
            let respuesta = response.data;   

            if (respuesta === 0) {
                Swal.fire("Se guardó correctamente.", "Haga click en OK", "success");
            } else {
                Swal.fire(respuesta, "Haga click en OK", "error");
            }

            me.listarIndex();
        })
        .catch(function (error) {                
            error401(error);             
        });

    });
},

listar_entradasXe() {
            let me = this;
            var url = "/listar_entradasXe";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayTipoEntrada = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                
                });
        },

    guardarObservacion(){
       let me=this;
        axios.post("/inventario-periodo/terminarProceso", { 
                        'array': me.arrayTabla3,
                        'id_dos':me.id_dos,
                        'id_index':me.id_index,
                        'id_bloqueo':me.id_bloqueo_1,
                                                             
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar_00");
                        
                          var respuesta = response.data;   
                                        
                                if (respuesta===0) {
                                     Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                                }else{
                                    Swal.fire(respuesta,"Haga click en Ok","error",);
                                }                          
                         me.listarIndex();
                    })
                   .catch(function (error) {                
                      error401(error);             
            });
    },

procesarCodigo(){

    let me = this;
 
    const index1 = me.arrayProductoLineaIngreso.findIndex(

        p => p.codigo_imprecion === me.inputBuscarCodigo
    );
   
     if(index1 === -1){
   me.alertMensajeLector="No existe el código, ya que solo la busqueda es por liena";
   
}else{        
const producto = me.arrayProductoLineaIngreso[index1];
 
  me.alertMensajeLector="Producto encontrado";
   const index2 = me.productosSeleccionados.findIndex(
            p => p.codigo_imprecion === me.inputBuscarCodigo
        );

        if(index2 === -1){

            me.productosSeleccionados.push({
                id_ingreso: producto.id,
                id_prod_producto: producto.id_prod_producto,
                stock_ingreso: producto.stock_ingreso,                
                envase: producto.envase,
                cantidad: 1,  

                lote: producto.lote,

                fecha_vencimiento: producto.fecha_vencimiento, 

                codigo_imprecion: producto.codigo_imprecion,

                leyenda:`${producto.nom_producto} - ${producto.nom_dis} X ${producto.cantidad_d} ${producto.nom_forma_faraceutica} ${producto.envase}`,
                
                fechaIngreso:producto.fecha_ingreso,
            });
           
        }else{            
         me.productosSeleccionados[index2].cantidad += 1;
         me.alertMensajeLector="Producto encontrado";
        }

     }   
      me.inputBuscarCodigo = '';    
},
//-----------------------------------------------------------------------

activarLecto(data){
    let me=this;
    me.verLector=data;
    if (data==0) {      
        me.productosSeleccionados=[];
    } 
},


        verPDF(data){
            let me=this;
            me.usuario_ver=data.name;
                me.linea_ver=data.nom_linea;
                me.fecha_ver=data.fecha_creacion;
                me.estado_ver=data.estado;
        },
      

       listarIndex(page){ 
            let me=this;            
                var url='/inventario-periodo/listarInicio?page='+page+'&id_sucursal='+me.id_sucursal+'&buscar='+me.buscar+'&estadoSelect='+me.estadoSelect+'&tipo_inventario='+me.peridoSelect+'&id_tienda='+me.id_tienda+'&id_almacen='+me.id_almacen+'&ini='+me.startDate+'&fini='+me.endDate;
             
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;                   
                    me.pagination = respuesta.pagination;
                  me.arrayInicio = respuesta.resultados.data;
                           
                })
                .catch(function(error){
                    error401(error);
                });
        },

        listarModalData(id,data,bandera){ 
            let me=this; 
            me.arrayCuerpo=[];    
            me.arrayCabeza=[];       
                var url='/inventario-periodo/listarModalData?id='+id;             
                axios.get(url)
                .then(function(response){
                    let respuesta = response.data;
                    let respuesta_cabeza = respuesta.query_1;
                    let respuesta_cuerpo = respuesta.query_2;    
                    
                me.arrayCabeza=respuesta_cabeza;
                me.arrayCuerpo=respuesta_cuerpo; 
                  if (bandera===1) {
                    me.arrayCuerpo.forEach(e => {
                        if (e.estado==='SOBRANTE') {
                            e.estado_x = 12;
                        }else{
                           e.estado_x = 16; 
                        }
                          
                    if ( e.repetido== 'REPETIDO') {
                        me.panelControl_key=1;
                    }                   
                }); 
                  }  
                

                 if (data==1) {
                        me.descargaPDF(me.arrayCabeza,me.arrayCuerpo);
                    } 
                   
                })
                .catch(function(error){
                    error401(error);
                });
        },

        cambiarSelect(index, valor){
            let me=this;
            console.log("index:", index);
    console.log("valor seleccionado:", valor);
            
    // modificar directamente el array
    me.arrayCuerpo[index].estado_x = valor;
    console.log(me.arrayCuerpo);
        },

        registro_ajuste_n_p(){
            let me =this;           
           
                axios.post("/inventario-periodo/registro_ajuste_n_p", { 
                    'array_cuerpo':me.arrayCuerpo,
                    'array_cabeza':me.arrayCabeza, 
                    'sucursalSeleccionada':me.sucursalSeleccionada                       
                    })
                    .then(function (response) {
                        me.cerrarModal("panelControl");
                    
                          var respuesta = response.data;  
                          if (respuesta===0) {
                                     Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                                }else{
                                    if (respuesta===1) {
                                       Swal.fire("error de estados etapa uno","Haga click en Ok","error",); 
                                    } else {
                                        if (respuesta===2) {
                                            Swal.fire("error de id de tienda o almacen etapa dos","Haga click en Ok","error",); 
                                        } else {
                                           Swal.fire(respuesta,"Haga click en Ok","error",); 
                                        }
                                    }                                    
                                } 
                           console.log(respuesta);             
                                                      
                  
                      me.listarIndex();
                    })
               .catch(function (error) {                
                      error401(error);             
            });
            
        },

        listarTabla_tres(data){ 
            let me=this; 
                
                var url='/inventario-periodo/listarTabla_tres?id='+data;
                me.arrayTabla3=[];
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;                   
                    me.arrayTabla3=respuesta;
                              
                })
                .catch(function(error){
                    error401(error);
                });
        },

        cargarTabla_0(array,cantidad,index){
         let aa=0;
         let me=this;
          const index_2 = me.productosSeleccionados.findIndex(
            p => p.id_ingreso === array.id
        );
     

            if  (cantidad === null || cantidad === undefined || cantidad === '') {
                aa=0;
            }else{
                aa=cantidad;
            }          

            
        if (index !== -1) {
            // Si ya existe, solo actualiza cantidad
            this.productosSeleccionados[index].cantidad = aa;

        } else {
            // Si no existe, lo agrega completo
            this.productosSeleccionados.push({
                id_ingreso: array.id,
                id_prod_producto: array.id_prod_producto,
                stock_ingreso: array.stock_ingreso,                
                envase: array.envase,
                cantidad: aa,  
                lote: array.lote,
                fecha_vencimiento: array.fecha_vencimiento, 
            });

 

        }

        },

        guardarProducto(producto, cantidad) {
        const index = this.productosSeleccionados.findIndex(
            p => p.id_ingreso === producto.id
        );
        let aa=0;
         if  (cantidad === null || cantidad === undefined || cantidad === '') {
                aa=0;
            }else{
                aa=Number(cantidad);
            }

        if (index !== -1) {
            // Si ya existe, solo actualiza cantidad
            this.productosSeleccionados[index].cantidad = aa;

        } else {
            // Si no existe, lo agrega completo
            this.productosSeleccionados.push({
                id_ingreso: producto.id,
                id_prod_producto: producto.id_prod_producto,
                stock_ingreso: producto.stock_ingreso,                
                envase: producto.envase,
                cantidad: aa,  
                lote: producto.lote,
                fecha_vencimiento: producto.fecha_vencimiento,            
            });

       
        }
    },

    empezarFuncion(activador){
    let me = this;
    me.activarProceso=1;
 
    let url = "/inventario-periodo/listarBloqueo_1?id_sucursal=" + me.id_sucursal + "&id_linea=" + me.lineaSelect.id+"&id_bloqueo="+me.id_bloqueo_1;

    axios.get(url)
    .then(function (response) {

        if (response.data == 1) {
            Swal.fire("Ya un proceso con la linea y sucursal.", "Haga click en Ok", "error");
            return;
        }

        me.activador = activador;
        let inicio = (activador == 1) ? 1 : 0;

        // OBJETO BASE
        let datos_enviar = {
            tipo_inventario: me.peridoSelect,
            id_index: me.id_index,
            id_sucursal: me.id_sucursal,
            id_lineas: me.lineaSelect.id,
            id_tienda: me.id_tienda,
            id_almacen: me.id_almacen,
            fecha_0_0: me.fecha_0_0,
            turno: me.turnoSelect,
            id_bloqueo:me.id_bloqueo_1 
        };

        // SOLO SI ES DIARIO
        if (me.peridoSelect == "D") {
            datos_enviar.inicio = inicio;
        }

        axios.post("/inventario-periodo/bloquear", datos_enviar)
        .then(function (response) {

            let respuesta = response.data;
            let respuesta_1=respuesta.estado;
            let respuesta_2=respuesta.enviar;
            let respuesta_3=respuesta.error;
          console.log(respuesta);
if (respuesta_1==0) {
    me.id_dos=respuesta_2;
    me.id_tabla_dos_momentanio=respuesta_2;
    me.verContenido = 1;
            me.listarIndex();
    return;
}
if (respuesta_1==1) {
     Swal.fire("Ya tiene un proceso activo.", "Haga click en Ok", "error");
    me.id_dos=null;
    return;
}
if (respuesta_1==2) {
      Swal.fire(respuesta_3, "Haga click en Ok", "error");
    me.id_dos=null;
    return;
}     

        })
        .catch(function (error) {
            error401(error);
        });

    })
    .catch(function (error) {
        error401(error);
    });
},


        registrar(){
            let me =this;
          
          
            if (me.arrayProductoLineaIngreso.length==me.productosSeleccionados.length) {
          
                        
                        
                   me.isSubmitting=true;
                axios.post("/inventario-periodo/registrar", { 
                        'array': me.productosSeleccionados,
                        'id_dos':me.id_dos,
                        'id_index':me.id_index,
                        'id_tabla_dos_momentanio':me.id_tabla_dos_momentanio,
                                                             
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                          me.isSubmitting=false;
                          var respuesta = response.data;   
                                        
                                if (respuesta===0) {
                                     Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                                }else{
                                    Swal.fire(respuesta,"Haga click en Ok","error",);
                                }                          
                         me.listarIndex();
                    })
                   .catch(function (error) {                
                      error401(error);             
            });
            }else{
                  me.cerrarModal("registrar");
                 me.isSubmitting=false;
                me.productosSeleccionados=[];
                me.arrayProductoLineaIngreso=[];  
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            }           
        },

        registrarConLencto(){
            let me=this;            
 me.isSubmitting=true;
 Swal.fire({
  title: "Desea terminar.!",
  text: "Esta seguro de terminar el proceso de conteo.",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, Ya termine!"
}).then((result) => {
    if (result.isConfirmed){
        axios.post("/inventario-periodo/registrar", { 
                        'array': me.productosSeleccionados,
                        'id_dos':me.id_dos,
                        'id_index':me.id_index,
                        'id_tabla_dos_momentanio':me.id_tabla_dos_momentanio,
                                                             
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                          me.isSubmitting=false;
                          var respuesta = response.data;   
                                        
                                if (respuesta===0) {
                                     Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                                }else{
                                    Swal.fire(respuesta,"Haga click en Ok","error",);
                                }                          
                         me.listarIndex();
                    })
                   .catch(function (error) {                
                      error401(error);             
            });
    }  else{
        me.isSubmitting=false;
    }
});
 
        },

        registro_0_0(){
            let me =this;
            if (me.model_0_motivo==''||me.model_0_nombre==''||me.model_0_motivo==null||me.model_0_nombre==null) {
               Swal.fire("No puede ingresar valor nulos  o vacios","Haga click en Ok", "warning",); 
            } else {
                me.isSubmitting=true;
                axios.post("/inventario-periodo/registro_0_0", { 
                    'motivo_0':me.model_0_motivo,
                    'nombre_0':me.model_0_nombre,
                        'id_sucursal':me.id_sucursal,
                        'id_tienda':me.id_tienda,
                        'id_almacen':me.id_almacen,                       
                        'tipo_inventario':me.peridoSelect,                                                            
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar_0");
                          me.isSubmitting=false;
                          var respuesta = response.data;   
                                        
                                if (respuesta===0) {
                                     Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                                }else{
                                    Swal.fire(respuesta,"Haga click en Ok","error",);
                                }                          
                  
                      me.listarIndex();
                    })
               .catch(function (error) {                
                      error401(error);             
            });
            }
        },

        cambioEstado(id,dato) {
            let me = this;
            let title_2="";
             let text_2="";
              let estado_2="";
            if(dato==1){
                title_2="¿Esta Seguro de Activar?";
                text_2="Es una activacion logica";
                estado_2="Activar";
            }
            else{
                title_2="¿Esta Seguro de Desactivar?";
                text_2="Es una eliminacion logica";
                estado_2="Desactivar";
            }
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons
                .fire({
                    title: title_2,
                    text: text_2,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, "+estado_2,
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .put("/inventario-periodo/cambioEstado", {
                                id: id,
                                dato:dato,
                            })
                            .then(function (response) {  
                     
                                                        
                                swalWithBootstrapButtons.fire(
                                    estado_2+"!",
                                    "El registro a sido "+estado_2+" Correctamente",
                                    "success",
                                );
                                me.listarIndex();
                            })
                          
                           .catch(function (error) {           
                 error401(error);   
            });
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
        },

        listarProducto(id_linea){
            let me = this;
            let fechaDiario;  
            let bandera=0;
            if (me.peridoSelect=="D") {
                if ((me.fecha_0_0==""||me.fecha_0_0==null)&&me.tipoAccion==1) {
                        Swal.fire("fecha sin seleccionar","Haga click en Ok","error",);
                        bandera=1;
                } else {
                  fechaDiario=me.fecha_0_0;
                  bandera=0;  
                }
                
            } else {
                fechaDiario="0";
                bandera=0;  
            }
            if (bandera==0) {
                 me.arrayProductoLineaIngreso.length=0;  
            me.productosSeleccionados.length=0;  
            
            var url = "/inventario-periodo/listarProducto?id_linea="+id_linea+"&id_tienda="+me.id_tienda+"&id_almacen="+me.id_almacen+"&peridoSelect="+me.peridoSelect+"&fecha_0_0="+fechaDiario+"&turno="+me.turnoSelect+"&id_sucursal="+me.id_sucursal;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProductoLineaIngreso=respuesta;
                    me.cantidadTamanio=me.arrayProductoLineaIngreso.length;
                                 
                })
                .catch(function (error) {
                    error401(error);
                
                });
              
            }              
        },

        cambioActivar(data){
            let me=this;
            
            me.activador_00=data;
            me.cantidadTamanio=0;
            if(data==0){      
             me.lineaSelect=null;
             me.productosSeleccionados=[];
            me.arrayProductoLineaIngreso=[];
            }

        },

         listarProductoDetalle(id,dato){
            let me = this;    
            me.arrayProductoDetalle=[];   
             
            var url = "/inventario-periodo/listarProductoDetalle?id="+id;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProductoDetalle=respuesta;
              
                    if (dato==1) {
                        me.descargaPDF(me.arrayProductoDetalle);
                    }                 
                })
                .catch(function (error) {
                    error401(error);
           
                });
        },

        

        listarLinea() {
            let me = this;           
            var url = "/inventario-periodo/listarLinea";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayLinea = respuesta;
                              
                })
                .catch(function (error) {
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
              
                });
        },

        cambioSucursal(codigo){
            let me = this;
           const registro = me.arraySucursal.find(item => item.codigo === codigo);

if (registro) {
    me.id_sucursal=registro.id_sucursal;
    me.id_tienda=registro.id_tienda;
    me.id_almacen=registro.id_almacen;
} else {    
    me.id_sucursal="";
    me.id_tienda="";
    me.id_almacen="";
}
        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
           me.listarIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Crear inventario";
                    me.showModal = true;
                    me.dateInput="";
                    me.turnoSelect="0";
                    me.lineaSelect=null;   
                    me.productosSeleccionados= [];
                    me.fecha_0_0="";
                    me.activador=0;
                    me.verLector=0;
                    me.inputBuscarCodigo="";
                    me.cadenaInput=""; 
                    me.cantidadTamanio=0;
                    me.verContenido=0;
               
                    me.id_index=data.id;
                      me.nombre_fecha_accion_2="";
                me.nombre_turno_accion_2="";
                me.nombre_linea_accion_2="";
                me.activador_00=0;
                me.alertMensajeLector="";
               me.activarProceso=0;
               me.id_bloqueo_1=data.id_bloqueo;
               me.id_tabla_dos_momentanio=data.id_tabla_dos_momentanio;
                   
                   // me.isSubmitting=false;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   me.tituloModal = "Editar inventario";
                    me.showModal = true;
                    me.dateInput="";
                    me.turnoSelect="";
                    me.lineaSelect=null;   
                    me.productosSeleccionados= [];
                    me.fecha_0_0=data.fecha_ini;
                   me.activador=1;
                    me.verLector=0;
                  me.id_dos=data.id_dos;
                    me.inputBuscarCodigo="";
                    me.cadenaInput=""; 
                    me.cantidadTamanio=0;
                    me.verContenido=1;
               
                    me.id_index=data.id;
                       me.nombre_fecha_accion_2=data.fecha_ini;
                me.nombre_turno_accion_2=data.tuno_nombre;
                me.nombre_linea_accion_2=data.nombre_linea;
                   // me.isSubmitting=false;
                    me.listarProducto(data.id_linea);
                   me.cambioActivar(1);
                    me.alertMensajeLector="";
                    me.id_bloqueo_1=data.id_bloqueo;
                    me.activarProceso=1;
                    me.id_tabla_dos_momentanio=data.id_tabla_dos_momentanio;
                    me.classModal.openModal("registrar");

                    break;
                }
                case "ver":{
              
                me.showModal_2 = true;
                me.tituloModal = "Ver detalle";

                me.usuario_ver=data.name;
                me.linea_ver=data.nom_linea;
                me.fecha_ver=data.fecha_creacion;
                me.estado_ver=data.estado;
me.panelControl_key=0;
                me.classModal.openModal("ver");
                break;
                }

                case "registrar_0":{
                        me.showModal_3 = true;
                        me.tipoAccion = 1;
                        me.model_0_motivo="";
                        me.model_0_nombre="";
                      me.isSubmitting=false;
                me.tituloModal = "Registro de computo";
                me.panelControl_key=0;
                me.classModal.openModal("registrar_0");
                break;
                }
                case "registrar_00":{
             
                        me.showModal_4 = true;
                        me.tipoAccion = 1;
                      me.isSubmitting=false;
                      me.id_dos=data.id_dos;
                    me.id_index=data.id;
                    me.id_bloqueo_1=data.id_bloqueo;
                    me.panelControl_key=0;
                me.tituloModal = "Registro de actividad de computo";
                me.classModal.openModal("registrar_00");
                break;
                }
                  case "panelControl":{
             
                        me.showModal_5 = true;
                        me.tipoAccion = 1;
                      me.isSubmitting=false;
                   me.panelControl_key=0;
                me.tituloModal = "Registro de auto ajustado";
                me.classModal.openModal("panelControl");
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
              me.dateInput="";
                    me.turnoSelect="0";
                    me.lineaSelect=null;   
          me.fecha_0_0="";
             me.isSubmitting=false;
             me.lineaSelect=null;
             me.productosSeleccionados=[];
            me.arrayProductoLineaIngreso=[];
                me.activador=0;
                me.verLector=0;
                    me.inputBuscarCodigo="";
                    me.cadenaInput="";
                    me.cantidadTamanio=0;
                    me.verContenido=0;
                    me.id_index=0;

                    me.nombre_fecha_accion_2="";
                me.nombre_turno_accion_2="";
                me.nombre_linea_accion_2="";
                me.id_dos="";
                me.activador_00=0;
                me.alertMensajeLector="";
                me.activarProceso=0;
                me.id_bloqueo_1="";
                me.id_tabla_dos_momentanio="";

            }
            if (accion == "ver") {
                me.classModal.closeModal(accion);
                me.showModal_2 = false;
                me.tituloModal = " ";
                me.usuario_ver="";
                me.linea_ver="";
                me.fecha_ver="";
                me.estado_ver="";
                me.panelControl_key=0;
            }
            if (accion == "registrar_0") {
                me.classModal.closeModal(accion);
                me.showModal_3 = false;
                    me.model_0_motivo="";
                        me.model_0_nombre="";
                        me.isSubmitting=false;
                        me.panelControl_key=0;
                me.tituloModal = "Registrar registro para su computo";
            }
            if (accion == "registrar_00") {
                me.classModal.closeModal(accion);
                me.showModal_4 = false;
                  
                me.isSubmitting=false;
                me.tituloModal = "";
                me.registrar_00="";
                me.panelControl_key=0;
            }

            if (accion == "panelControl") {
                me.classModal.closeModal(accion);
                me.showModal_5 = false;                  
                me.isSubmitting=false;
                me.tituloModal = "";
                me.panelControl_key=0;
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
       this.listarLinea();
        this.listarPerimsoxyz();
        this.listar_entradasXe();
        this.classModal.addModal("registrar");
        this.classModal.addModal("ver");
        this.classModal.addModal("registrar_0");
        this.classModal.addModal("registrar_00");
         this.classModal.addModal("panelControl");
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
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

.modal-super-lg {
    max-width: 90% !important;
}
</style>
