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
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link " id="pills-credencial-tab" data-toggle="pill" href="#pills-credencial" role="tab" aria-controls="pills-credencial" aria-selected="false" @click="cambioPestañaInf(1,0,0,0);limpiar(0);">Credenciales QR</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-lista-tab" data-toggle="pill" href="#pills-lista" role="tab" aria-controls="pills-lista" aria-selected="false" @click="cambioPestañaInf(0,1,0,0);listarQR();">Lista de QR</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-endPoint-tab" data-toggle="pill" href="#pills-endPoint" role="tab" aria-controls="pills-endPoint" aria-selected="false" @click="cambioPestañaInf(0,0,1,0); listarQR();">End Points</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-qrDatos-tab" data-toggle="pill" href="#pills-qrDatos" role="tab" aria-controls="pills-qrDatos" aria-selected="false" @click="cambioPestañaInf(0,0,0,1);listarGlosa()">QR datos a enviar</a>
  </li>
</ul>
                </div>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
  <!------------------------------credencial------------------------------------------------------->
  <div class="tab-pane fade" id="pills-credencial" role="tabpanel" aria-labelledby="pills-credencial-tab" v-show="show_1==1&&show_2==0&&show_3==0&&show_4==0">

    <div class="row">                              
        <div class="form-group col-sm-4" >
            <label for="">Nombre servicio:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el nombre del servicio ejemplo QR en banco patito"  v-model="nomServicio">                                  
            <span  v-if="nomServicio==''" class="error">Debe ingresar la nombre servicio</span>
        </div>
        <div class="form-group col-sm-4" >           
           <label for="">Tipo QR</label>
          <select class="form-control" v-model="selectBanco" @change="seleccionBanco_data(selectBanco)">
              <option value="0" disabled selected>Seleccionar...</option>
                <option v-for="(i, index) in arrayBanco" :key="index" :value="i.id"
               v-text="i.nombre + ' -> ' + i.sigla">                                        
                </option>                                   
            </select>
             <span  v-if="selectBanco=='0'" class="error">Debe ingresar la selección</span>
        </div>
        <div class="form-group col-sm-4" >
            <label for="">Url:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el url de la identidad que presta el servicio"  v-model="url_">                                  
            <span  v-if="url_==''" class="error">Debe ingresar la URL</span>
        </div>                            
    </div> 
    <div class="row">
       <div class="form-group col-sm-4" >
            <label for="">Usuario:</label>
            <input  type="text" class="form-control" placeholder="Ingresar usuario dada por la identidad"  v-model="usuario_">                                  
            <span  v-if="usuario_==''" class="error">Debe ingresar el usuario</span>
        </div>  
        <div class="form-group col-sm-4" >
            <label for="">Contraseña:</label>
            <input  type="text" class="form-control" placeholder="Ingresar contraseña dada por la identidad"  v-model="contraseña_">                                  
            <span  v-if="contraseña_==''" class="error">Debe ingresar la contraseña</span>
        </div>  
        <div class="form-group col-sm-4" style="margin-top: 15px;" >
            <button type="button" class="btn btn-warning btn-lg btn-block" @click="crearServicioQr()" style="color: white;" :disabled="isSubmitting==true">Añadir</button>         
        </div>        
    </div>

  </div>
  <!------------------------------lista------------------------------------------------------->
  <div class="tab-pane fade" id="pills-lista" role="tabpanel" aria-labelledby="pills-lista-tab" v-show="show_1==0&&show_2==1&&show_3==0&&show_4==0">
     <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>       
                        <th class="col-md-1">Opcion</th>                
                        <th>Nombre</th>
                        <th>Banco</th>                       
                        <th>Token</th>
                        <th>URL</th>                        
                        <th>Fecha creacion</th>                        
                        <th>Usuario</th> 
                        <th class="col-md-1">En uso</th>
                        <th class="col-md-1">Estado</th>                         
                    </tr>
                </thead>
          <tbody>
  <template v-for="(i, index) in arrayQR" :key="i.id">
                    <tr> 
                         <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px; color: white;" data-toggle="collapse" :data-target="'#collapseExample' + index" :aria-expanded="false" aria-controls="collapseExample" @click="actualizarPorId(i,$event)" v-if="valorador_1==0"> 
                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                </button> 
                                 <button type="button" class="btn btn-secondary btn-sm" style="margin-right: 5px; color: white;" v-else> 
                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                </button> 
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                </button> 
                                </div>
                                <div v-if="puedeActivar==1">
                                <button v-if="i.activo==1" type="button" class="btn btn-danger btn-sm"  @click="activar_desactivar(i.id,0)"  style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                            </button>
                            <button v-else type="button" class="btn btn-info btn-sm"  @click="activar_desactivar(i.id,1)"  style="margin-right: 5px;">
                                <i class="icon-check"></i>
                            </button>
                            </div>
                            <div v-else>
                                <button v-if="i.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                                </button>
                                <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                                </button>
                            </div>
                            <div v-if="puedeHacerOpciones_especiales==1">
                                <button v-if="i.prioridad==1" type="button" class="btn btn-success btn-sm"  @click="activar_desactivarPri(i.id,0)"  style="margin-right: 5px;">
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </button>
                            <button v-else type="button" class="btn btn-primary btn-sm"  @click="activar_desactivarPri(i.id,1)"  style="margin-right: 5px;">
                                <i class="fa fa-star-o" aria-hidden="true"></i>
                            </button>
                            </div>
                            <div v-else>
                                <button v-if="i.prioridad==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                                </button>
                                <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                                </button>
                            </div>
                            </div> 
                        </td>
                        <td >{{i.nom_servicio_qr}}</td>
                        <td >{{i.nombre}}</td>
                        <td>{{i.token_qr}}</td>
                        <td>{{i.url_banco_servicio}}</td>
                        <td>{{i.updated_at}}</td>
                        <td>{{i.name}}</td>
                        <td class="col-md-1">
                            <div v-if="i.prioridad==0">
                                <span class="badge badge-primary">NO</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-success">SI</span>
                            </div>
                        </td>                    
                        <td class="col-md-1">
                             <div v-if="i.activo==1">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-warning">Desactivado</span>
                            </div>
                        </td>
    </tr>   
                      <!-- Fila colapsable -->
    <tr>
      <td colspan="9">
        <div class="collapse" :id="'collapseExample' + index">
          <div class="card card-body">
            <div class="row">                              
        <div class="form-group col-sm-4" >
            <label for="">Nombre servicio:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el nombre del servicio ejemplo QR en banco patito"  v-model="nomServicio">                                  
            <span  v-if="nomServicio==''" class="error">Debe ingresar la nombre servicio</span>
        </div>
        <div class="form-group col-sm-4" >           
           <label for="">Tipo QR</label>
          <select class="form-control" v-model="selectBanco" @change="seleccionBanco_data(selectBanco)">
              <option value="0" disabled selected>Seleccionar...</option>
                <option v-for="(i, index) in arrayBanco" :key="index" :value="i.id"
               v-text="i.nombre + ' -> ' + i.sigla">                                        
                </option>                                   
            </select>
             <span  v-if="selectBanco=='0'" class="error">Debe ingresar la selección</span>
        </div>
        <div class="form-group col-sm-4" >
            <label for="">Url:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el url de la identidad que presta el servicio"  v-model="url_">                                  
            <span  v-if="url_==''" class="error">Debe ingresar la URL</span>
        </div>                            
    </div> 
    <div class="row">
       <div class="form-group col-sm-4" >
            <label for="">Usuario:</label>
            <input  type="text" class="form-control" placeholder="Ingresar usuario dada por la identidad"  v-model="usuario_">                       
          
        </div>  
        <div class="form-group col-sm-4" >
            <label for="">Contraseña:</label>
            <input  type="text" class="form-control" placeholder="Ingresar contraseña dada por la identidad"  v-model="contraseña_">                       
          
        </div>  
        <div class="form-group col-sm-1" style="margin-top: 15px;">             
            <button type="button" class="btn btn-warning btn-lg btn-block" style="margin-right: 5px; color: white;" data-toggle="collapse" :data-target="'#collapseExample' + index" :aria-expanded="false" aria-controls="collapseExample"  @click="subir();"> 
            <i class="fa fa-angle-double-up" aria-hidden="true"></i>
            </button>       
        </div>
        <div class="form-group col-sm-3" style="margin-top: 15px;" >
            <button type="button" class="btn btn-warning btn-lg btn-block" @click="editarServicioQr()" style="color: white;">Actualizar</button>         
        </div>        
    </div>
          </div>
        </div>
      </td>
    </tr>

  </template>
          
                </tbody>
    </table>    
  </div>
   <!------------------------------endpoints------------------------------------------------------->
  <div class="tab-pane fade" id="pills-endPoint" role="tabpanel" aria-labelledby="pills-endPoint-tab" v-show="show_1==0&&show_2==0&&show_3==1&&show_4==0">
     <div class="card">
        <div class="card-header">End points</div>
            <div class="alert alert-info" role="alert">
                Recuerde que no debe haber ningun espacion en los <strong>end points</strong>
            </div>
                <div class="card-body">    
                                <div class="row">                                                                
                                    <div class="form-group col-sm-7">
                                        <label>Servicio:</label>
                                     <select  class="form-control"  v-model="selectServicio" @change="cambioEstadoSelector()">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option v-for="(i, index) in arrayQR" :key="index" :value="i.id">{{i.nom_servicio_qr+" - "+i.nombre}}</option>
                                    </select>
                                    </div>                            
                                <div class="form-group col-sm-2" v-show="selectServicio!='0'">
                                    <label for="">Tipo:</label>
                                     <select  class="form-control"  v-model="selectModalidad" @change="cambioEstadoSelector()">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Producción</option>
                                            <option value="2">Piloto</option>
                                    </select>
                                </div>
                                  <div class="form-group col-sm-3" v-show="selectModalidad!='0'" style="margin-top: 15px;">
                                    <button type="button" class="btn btn-primary" @click="abrirModal('registrar')">Crear</button>
                                     <button type="button" class="btn btn-info" style="color: white;" @click="listar_endpoint_list()">Ver</button>
                                     <button type="button" class="btn btn-success" v-if="qr_estado_config==0" @click="activarFuncion(1)">Activar función</button>
                                     <button type="button" class="btn btn-danger" v-else  @click="activarFuncion(0)">Desactivar función</button>
                                  </div>                                                              
                        </div>
                          <table class="table table-bordered table-striped table-sm table-responsive" v-show="ver_v3==1">
                            <thead>
                                <tr>
                                    <th>Opc.</th>
                                    <th>ID</th>   
                                    <th class="col-md-5">Descripción</th>                       
                                    <th class="col-md-4">Url</th>
                                    <th>Versión</th>  
                                    <th>Estado</th>               
                                </tr>
                            </thead> 
                            <tbody>
                                <tr v-for="(i, index) in arrayEndPoint_list" :key="index">
                                    <td>
                                <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px; color: white;" @click="abrirModal('actualizar',i)"> 
                               <i class="icon-pencil"></i>
                                </button>                                  
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                                </button> 
                                </div>
                                <div v-if="puedeActivar==1">
                                <button type="button" class="btn btn-info btn-sm" style="margin-right: 5px; color: white;" @click="desactivar_1(i.id,0)" v-if="i.activar==1">
                                <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                </button> 
                                <button type="button" class="btn btn-danger btn-sm" style="margin-right: 5px;" @click="desactivar_1(i.id,1)" v-else>
                                <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                </button> 
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                </button> 
                                </div>
                                <div v-if="puedeHacerOpciones_especiales==1">
                                <button type="button" class="btn btn-success btn-sm" style="margin-right: 5px; color: white;" @click="json_operation_1(i)">
                                <i class="fa fa-connectdevelop" aria-hidden="true"></i>
                                </button>                              
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-connectdevelop" aria-hidden="true"></i>
                                </button> 
                                </div>
                                
                            </div>
                                    </td>
                                    <td>
                                        {{i.id}}
                                    </td>
                                    <td class="col-md-5">
                                        {{i.descripcion}}
                                    </td>
                                    <td class="col-md-4">
                                        {{i.url}}
                                    </td>
                                    <td>
                                        {{i.version}}
                                    </td>
                                    <td>
                                        <div v-if="i.activar==1">
                                            <span class="badge badge-pill badge-success">En uso</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-pill badge-secondary">Sin uso</span>
                                        </div>
                                    </td>
                                </tr>    
                            </tbody>
                        </table>
                    </div>
            </div>    
       
        </div>  
    <!------------------------------datos QR------------------------------------------------------->     
          <div class="tab-pane fade" id="pills-qrDatos" role="tabpanel" aria-labelledby="pills-qrDatos-tab" v-show="show_1==0&&show_2==0&&show_3==0&&show_4==1">
     <div class="card">
        <div class="card-header">Datos de glosa</div>
            <div class="alert alert-info" role="alert">
                Recuerde que no debe haber ningun espacion datos de ejemplo nacionalidad ejemplo BOB, glosa ejempplo Prueba QR, en datos adcionales ejemplo Datos Adicionales para identificar el QR
            </div>
                <div class="card-body">    
                                <div class="row">                                                                
                                    <div class="form-group col-sm-3">
                                        <label>Servicio:</label>
                                     <select  class="form-control"  v-model="selectNacionalidad">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option v-for="(i, index) in arrayNacionalidad" :key="index" :value="i.codigo">{{i.pais+" - "+i.codigo}}</option>
                                    </select> 
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>Glosa:</label>
                                    <input type="text" class="form-control" v-model="glosa_enviar">    
                                    </div>   
                                    <div class="form-group col-sm-3">
                                        <label>Rango:</label>
                                        <select class="form-control" v-model="selectTipo_enviar">
                                           <option value="0" disabled selected>Seleccionar...</option>
                                           <option value="1" >Cada dia</option>
                                           <option value="2" >Fecha específica</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3" style="margin-top: 20px;">
                                        <div v-if="selectTipo_enviar==='0'">
                                            <span>Sin datos...</span>
                                        </div>
                                        <div v-else-if="selectTipo_enviar==='1'">
                                             <span>automaticamente se formateara la fecha con la fecha de la operación</span>
                                        </div>
                                        <div v-else>
                                              <input type="date" class="form-control" v-model="fechaEnviar_2"> 
                                        </div>                                      
                                    </div>                                  
                        </div>
                         <div class="row">                                                                
                                        <div class="form-group col-sm-2">
                                            <label>Uso QR unico:</label>
                                        <select class="form-control" v-model="selectUnicoQR">
                                           <option value="0" disabled selected>Seleccionar...</option>
                                           <option value="true" >Si</option>
                                           <option value="false" >No</option>
                                        </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Datos adicionales:</label>
                                            <input type="text" class="form-control" v-model="datosAdicionalesEnviar">   
                                        </div>
                                         <div class="form-group col-sm-2">
                                            <label>Posición:</label>
                                            <select class="form-control" v-model="selectPosicion">
                                           <option value="0" disabled selected>Seleccionar...</option>
                                           <option value="1" >Nacional</option>
                                           <option value="2" >Extranjera</option>
                                        </select>
                                         </div>
                                        <div class="form-group col-sm-2">
                                            <button type="button" class="btn btn-warning btn-lg btn-block"  style="color: white;margin-top: 22px;" @click="enviarQrDatos()">Actualizar</button>
                                        </div>
                                    </div>  
                         
                    </div>
            </div>    
       
        </div> 
        <!--------------------------------------------------------------------------------------------------------------->  
        </div> 
        </div>
            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
           <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
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
                                    <div class="col-md-5">
                                        <label>Descripción: </label>
                                         <textarea class="form-control" id="exampleFormControlTextarea1" v-model="descripcion_endpoint" rows="3"></textarea>  
                                                                         
                                        <span  v-if="descripcion_endpoint==''" class="error">Debe llenar el dato</span> 
                                    </div>
                                    <div class="col-md-5">
                                    <label for="end-date">URL: </label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" v-model="url_endpoint" rows="3"></textarea>                                    
                                        <span  v-if="url_endpoint==''" class="error">Debe llenar el dato</span> 
                                    </div>   
                                    <div class="col-md-2">
                                    <label >Versión:</label>
                                            <input  type="text" class="form-control" v-model="version_endpoint">                                          
                                            <span  v-if="version_endpoint==''" class="error">Debe llenar el dato</span> 
                                    </div>  
                                </div>
                              <div class="form-group row">                                                                    
                                    <div class="col-md-3">
                                     <label for="">Servicio por defecto:</label>
                                    </div>
                                     <div class="col-md-5">
                                         <select  class="form-control"  v-model="selectServicio_v3" :disabled="tipoAccion == 2 &&selectServicio_v3!=8">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option  v-for="(i, index) in arraySelectServicio" :key="index" :value="i.id">{{i.nombre}}</option>                                            
                                    </select>                                     
                                     <span  v-if="selectServicio_v3=='0'" class="error">Debe seleccionar</span> 
                                     </div>
                                     <div class="col-md-2"  v-show="tipoAccion == 2">
                                        <button type="button" class="btn btn-danger btn-sm" style="margin-right: 5px;" @click="resetLista()">
                                Quitar lista
                                </button> 
                                     </div>                                   
                                </div>        
                            
                               
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="crearEndPoints()" :disabled="isSubmitting==true">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarEndPoints()">
                            Actualizar
                        </button>
                    </div>
                    </div>    
                </div>
            </div>      
                  
                   
             
      
      
    </transition>
     
          <!--Inicio del modal contraseña-->
           <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_2')">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
    <span>
        Todos los campos con (*) son requeridos.<br>
        <strong>Nueva autorización Id</strong>. Este campo debe cumplir con ciertos requisitos
        para considerarse una clave segura:
        <ul>
            <li>Mínimo debe tener un largo de 15 caracteres.</li>
            <li>Debe contener por lo menos una letra.</li>
            <li>Debe contener por lo menos un número.</li>
            <li>
                Debe contener por lo menos un caracter especial
                (+, -, /, *, $, &lt;, &gt;, !, ¡, #, |, =)
            </li>
        </ul>
    </span>
</div>

                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">                                                                    
                                    <div class="col-md-2">
                                        <label>Descripción: </label>                                     
                                    </div>
                                    <div class="col-md-10">
                                       <input type="text" class="form-control" v-model="input_2" placeholder="Contraseña">                                
                                        <span  v-if="input_2==''" class="error">Debe llenar el dato</span> 
                                    </div>
                                </div>
                                <div class="alert alert-danger" role="alert" v-show="mensajeError!=''||mensajeError==null">
                                    <label for="">
                                        {{mensajeError}}
                                    </label>                                  
                                </div>
                           
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar_2')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="detecto_entradas()">
                            Guardar
                        </button>
                      
                    </div>
                    </div>    
                </div>
            </div>      
                  
                   
             
      
      
    </transition>

     <!--Inicio del modal consulta de datos-->
           <transition name="fade">
            <div v-if="showModal_3" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_3')">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
    <span>
       Este servicio obtiene información de los QRs generados por la
empresa en una fecha determinada.
    </span>
</div>

                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">                                                                    
                                    <div class="col-md-2">
                                        <label>Descripción: </label>                                     
                                    </div>
                                    <div class="col-md-3">
                                       <input type="date" class="form-control" v-model="generationDate">                                
                                        <span  v-if="generationDate==''" class="error">Debe llenar el dato</span> 
                                    </div>
                                </div>                               
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar_3')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="consultaDAtos()" :disabled="generationDate==null">
                            Consultar
                        </button>
                      
                    </div>
                    </div>    
                </div>
            </div>     
    </transition>
 <!--Inicio del modal consulta de Estado QR-->
           <transition name="fade">
            <div v-if="showModal_4" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_4')">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
    <span>
       Este servicio obtiene el estado de un QR según su identificador.
    </span>
   
</div>

                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">                                                                    
                                    <div class="col-md-2">
                                        <label>id QR: </label>                                     
                                    </div>
                                    <div class="col-md-3">
                                       <input type="number" class="form-control" v-model="idQR_consultar">                                
                                        <span  v-if="idQR_consultar==''" class="error">Debe llenar el dato</span> 
                                    </div>
                                </div>                               
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar_4')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="consultaEstadoQR()" :disabled="idQR_consultar==null">
                            Consultar
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
//Vue.use(VeeValidate);
export default {
     props: ['codventana','idmodulo'],
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
            //offset:3,

            show_1:0,
            show_2:0,
            show_3:0,
            show_4:0,

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',


            nomServicio:'',
            url_:'',
            usuario_:'',
            contraseña_:'',          
            isSubmitting : false, 
            selectBanco:'0',

             arrayBanco:[],
            arrayQR:[],

            id_qr:'',
 //---permisos_R_W_S
            puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //----

                valorador_1:0,
                valorador_2:0,
           //-----endpoint
           selectModalidad:0,   
           descripcion_endpoint:'',
            url_endpoint:'',
             version_endpoint:'',  

            selectServicio:0, 
            arrayEndPoint_list:[],
            ver_v3:0,
            id_endPoints:'',

            arraySelectServicio: [{ id: 1, nombre: 'Generar Token' },
                                  { id: 2, nombre: 'Actualizar Credencial'},
                                  { id: 3, nombre: 'Generar QR'},
                                  { id: 4, nombre: 'Obtener lista de QRs generados'},
                                  { id: 5, nombre: 'Obtener estado QR'},
                                  { id: 6, nombre: 'Cancelar QR'},
                                  { id: 7, nombre: 'Otro operacion'},
                                  { id: 8, nombre: 'Sin lista'}
                                ],
            selectServicio_v3:'0',   
            showModal_2 :false, 
            isSubmitting_2 :false,
            input_2:'',
            mensajeError:'',
            id_entrada:'',
            id_servicio_entrada:'',
            id_qr_simple_entrada:'',
            tipo_entrada:'',
            id_servicio_entrada:'',

            arrayNacionalidad:[],
            selectNacionalidad:'0',

            selectTipo_enviar:'0', 
            glosa_enviar:'',
            fechaEnviar_2:'',
            selectUnicoQR:'0',
            datosAdicionalesEnviar:'',
            selectPosicion:'0',

            generationDate:'',
            showModal_3:false,

            idQR_consultar:'',
            showModal_4:false,

            qr_estado_config:0,
                        
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

 listarCredencial() {        
    let me = this;       
    var url = '/listar_config_siat_sis_v3';  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;  
            me.qr_estado_config=(respuesta.config_sistema[0]).qr_in_uso;  
           console.log(respuesta);
            console.log(me.qr_estado_config);         
        })
        .catch(function(error) {
            error401(error);       
        });
},

activarFuncion(data){
     let me=this;
            var url = "/qr_simple/credencial_3";
            axios  
                .put(url,{                 
                    data:data,
                })
                .then(function (response) {
                    var respuesta = response.data;                  
                     me.listarCredencial();
                     if (respuesta==1) {
                         Swal.fire("Funcion realizada.!","credencial activada","success");
                     } else {
                        Swal.fire("Funcion realizada.!","credencial desactivada","success");
                     }  
                                  
                })
                .catch(function (error) {
                    error401(error);                
                });
},

//-----------------solicitudes json--------------------------
json_operation_1(data) {
            let me = this;
            me.ver_v3=1;
            switch (data.id_servicio) {
              
                case 1:
               return  me.enviarDatosArray(data);
                         
                case 2:
                me.abrirModal('registrar_2',data);
                me.id_entrada=data.id;
                me.id_servicio_entrada=data.id_servicio;
                me.id_qr_simple_entrada=data.id_qr_simple;
                me.tipo_entrada=data.tipo;
                me.input_2=""; 
                break;
                case 3:
                return  me.enviarDatosArray(data);
                case 4:
                me.abrirModal('registrar_3',data);
                me.id_entrada=data.id;
                me.id_servicio_entrada=data.id_servicio;
                me.id_qr_simple_entrada=data.id_qr_simple;
                me.tipo_entrada=data.tipo;

                me.generationDate=""; 
                break;

                case 5:
                me.abrirModal('registrar_4',data);
                me.id_entrada=data.id;
                me.id_servicio_entrada=data.id_servicio;
                me.id_qr_simple_entrada=data.id_qr_simple;
                me.tipo_entrada=data.tipo;

                me.idQR_consultar=""; 
                break;               
                case 6:
                me.abrirModal('registrar_4',data);
                me.id_entrada=data.id;
                me.id_servicio_entrada=data.id_servicio;
                me.id_qr_simple_entrada=data.id_qr_simple;
                me.tipo_entrada=data.tipo;

                me.idQR_consultar=""; 
                break;  

            
                default:
                    break;
            }
            
        },

        enviarDatosArray(data){
            let me=this;
            var url = "/qr_simple/json_operation_1";
            axios  
                .put(url,{
                    id:data.id,
                    opc:data.id_servicio,
                    id_qr_simple:data.id_qr_simple,
                    tipo:data.tipo,
                    id_servicio:data.id_servicio
                })
                .then(function (response) {
                    var respuesta = response.data;                  
                     me.listar_endpoint_list();
                    if (respuesta.success==true) {                       
                        Swal.fire("Correcto.!",respuesta.message,"success");
                    }else{
                        Swal.fire("Error",respuesta.message,"error"); 
                    }    
                                  
                })
                .catch(function (error) {
                    error401(error);                
                });
        },

         listarGlosa() {
            let me = this;
            me.ver_v3=1;
           var url = "/qr_simple/listarGlosa";
            axios  
                .get(url)
                .then(function (response) {
                    var respuesta = response.data; 
                    if (respuesta.id==null) {
                        me.selectNacionalidad="0";
        me.selectTipo_enviar="0"; 
        me.glosa_enviar="";
        me.fechaEnviar_2="";
        me.selectUnicoQR="0";
        me.datosAdicionalesEnviar="";
        me.selectPosicion="0";
                    }else{
                        me.selectNacionalidad=respuesta.currency;
                        if (respuesta.tipoFecha==1) {
                             me.selectTipo_enviar="1";
                             me.fechaEnviar_2=""; 
                        } else {
                            me.selectTipo_enviar=respuesta.tipoFecha; 
                             me.fechaEnviar_2=respuesta.expirationDate; 
                        }
       
        me.glosa_enviar=respuesta.gloss;
                        if (respuesta.singleUse==1) {
                            me.selectUnicoQR="true";
                        } else {
                            me.selectUnicoQR="false";
                        }
        
        me.datosAdicionalesEnviar=respuesta.additionalData;
        me.selectPosicion=respuesta.destinationAccountId;
                    }           
                })
                .catch(function (error) {
                    error401(error);                
                });
        },

      actulizarQrDatos(){
        let me = this;

        me.selectNacionalidad="0";
        me.selectTipo_enviar="0"; 
        me.glosa_enviar="";
        me.fechaEnviar_2="";
        me.selectUnicoQR="0";
        me.datosAdicionalesEnviar="";
        me.selectPosicion="0";
      },  

      enviarQrDatos(){    
            let me = this;  
            
            if ( me.selectNacionalidad=="0" || me.selectTipo_enviar=="0" ||  me.glosa_enviar=="" || me.selectUnicoQR=== '0' ||
                me.datosAdicionalesEnviar=="" || me.selectPosicion=="0"
            ) {   

            Swal.fire("Error", "Registro nullo o vacio","error");
            } else {     
                    if (me.selectTipo_enviar=="2"&&me.fechaEnviar_2=="") {
                    Swal.fire("Error", "dato de fecha nullo","error");    
                } else {
                    axios.put("/qr_simple/editarQrGlosa", {
                    currency :me.selectNacionalidad,
                    tipoFecha:me.selectTipo_enviar, 
        gloss:me.glosa_enviar,
        expirationDate:me.fechaEnviar_2,// puede ser nullo
        singleUse:me.selectUnicoQR,
        additionalData:me.datosAdicionalesEnviar,
        destinationAccountId:me.selectPosicion                     
                })
                .then(function (response) {                  
                    let respuesta=response.data;
                    me.listarGlosa();
                    if (respuesta===0) {
                         Swal.fire("Registro actualizado!","Correctamente","success");

                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }                                                                   
                })                
                .catch(function (error) {
                    error401(error);
                }); 
                }    
            }                         
        },

//-----------------pestaña listar,editar,activar,desactivar---------------------

detecto_entradas(){
    let me=this;  
    let valor=me.input_2;  
    let error=0;
    // Longitud mínima
      if (valor.length < 15) {        
        me.mensajeError="Debe tener al menos 15 caracteres.";
        error=1;
    }

      // Al menos una letra
      if (!/[a-zA-Z]/.test(valor)) {
        me.mensajeError="Debe contener al menos una letra.";
    error=1;  
    }

      // Al menos un número
      if (!/[0-9]/.test(valor)) {
        me.mensajeError="Debe contener al menos un número.";
    error=1;  
    }

      // Al menos un carácter especial permitido
      if (!/[+\-\/\*\$<>!¡#\|=]/.test(valor)) {
         me.mensajeError="Debe contener al menos un carácter especial (+, -, /, *, $, <, >, !, ¡, #, |, =).";        
      error=1;
        }
        if (error==0) {
           me.mensajeError="Correcto...";  
           var url = "/qr_simple/json_operation_1";
            axios  
                .put(url,{
                    id:me.id_entrada,
                    opc:me.id_servicio_entrada,
                    id_qr_simple:me.id_qr_simple_entrada,
                    tipo:me.tipo_entrada,
                    id_servicio: me.id_servicio_entrada,
                    nuevo:valor

                })
                .then(function (response) {
                    var respuesta = response.data;
                   
                     me.listar_endpoint_list();
                     me.cerrarModal('registrar_2');
                    if (respuesta.success==true) {                       
                        Swal.fire("Correcto.!",respuesta.message,"success");
                    }else{
                        Swal.fire("Error",respuesta.message,"error"); 
                    }    
                                  
                })
                .catch(function (error) {
                    error401(error);                
                }); 
        }
},

consultaEstadoQR(){
    let me=this;    
        var url = "/qr_simple/json_operation_1";
            axios.put(url,{
                    id:me.id_entrada,
                    opc:me.id_servicio_entrada,
                    id_qr_simple:me.id_qr_simple_entrada,
                    tipo:me.tipo_entrada,
                    id_servicio: me.id_servicio_entrada,
                    qrId:me.idQR_consultar

                })
                .then(function (response) {
                    var respuesta = response.data;
                   
                     me.listar_endpoint_list();
                     me.cerrarModal('registrar_4');
                    if (respuesta.success==true) {                       
                        Swal.fire("Correcto.!",respuesta.message,"success");
                    }else{
                        Swal.fire("Error",respuesta.message,"error"); 
                    }    
                                  
                })
                .catch(function (error) {
                    error401(error);                
                });         
},

consultaDAtos(){
    let me=this;    
        var url = "/qr_simple/json_operation_1";
            axios.put(url,{
                    id:me.id_entrada,
                    opc:me.id_servicio_entrada,
                    id_qr_simple:me.id_qr_simple_entrada,
                    tipo:me.tipo_entrada,
                    id_servicio: me.id_servicio_entrada,
                    generationDate:me.generationDate

                })
                .then(function (response) {
                    var respuesta = response.data;
                
                     me.listar_endpoint_list();
                     me.cerrarModal('registrar_3');
                    if (respuesta.success==true) {                       
                        Swal.fire("Correcto.!",respuesta.message,"success");
                    }else{
                        Swal.fire("Error",respuesta.message,"error"); 
                    }    
                                  
                })
                .catch(function (error) {
                    error401(error);                
                });         
},

desactivar_1(id,data) {
            let me = this;
            let a;
            let b;
            let c;
            if (data==1) {
                a="Esta Seguro de Desactivar";
                b="Es una eliminacion logica";
                c="desactivado";

            } else {
                a="Esta Seguro de Activar";
                b="Es una activación logica";                
                c="activado";
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
                    title: a,
                    text: b,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .put("/qr_simple/desactivar_po", {
                                'id': id,  
                                'contador':data                              
                            })
                            .then(function (response) {
               
                               me.listar_endpoint_list();
                                swalWithBootstrapButtons.fire(
                                    c,
                                    "El registro a sido "+c+" Correctamente",
                                    "success",
                                );                            
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

resetLista(){
    this.selectServicio_v3=8;
},
subir(){    
this.valorador_1=0;
},

cambioEstadoSelector(){
    let me=this;
    me.arrayEndPoint_list=[];
    me.ver_v3=0;
},


 listar_endpoint_list() {
            let me = this;
            me.ver_v3=1;
           var url = "/qr_simple/listar_endpoint?id_qr_simple="+me.selectServicio+"&tipo="+me.selectModalidad;
            axios  
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayEndPoint_list = respuesta;                
                })
                .catch(function (error) {
                    error401(error);                
                });
        },


crearEndPoints(){    
            let me = this;       

            const data_1=me.descripcion_endpoint;
            const data_2=me.url_endpoint;
            const data_3=me.version_endpoint;
            const data_4=me.selectModalidad;
            const data_5=me.selectServicio_v3;
            
           
            if (data_1==null || data_1=== '' || data_2==null || data_2=== ''||
                data_3==null || data_3=== ''||data_4=='0'||data_4==0||data_5=='0'||data_5==0) {
            Swal.fire("Error", "Registro nullo o vacio","error");
            } else {
                me.isSubmitting=true;       
                 axios.post("/qr_simple/crearEndpoint", {
                    id_qr_simple:me.selectServicio,
                    descripcion:data_1,
                    url:data_2,                
                    version:data_3,    
                    tipo:data_4,
                    id_servicio:data_5,
                    
                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"registro de enpoints: "+me.descripcion,  
                })
                .then(function (response) {                  
                    let respuesta=response.data;
             
                    me.descripcion_endpoint="";
                    me.url_endpoint="";
                    me.version_endpoint="";
                    me.selectServicio_v3="0";
                    me.listar_endpoint_list();
                    me.cerrarModal('registrar');
                    if (respuesta===0) {
                         Swal.fire("Registro creado!","Correctamente","success");
                    } else {
                        if (respuesta===1) {
                            Swal.fire("Error!","ya esta en uso el servicio por default solo puede tener uno en activo, puedes quitar editando el reguistro","error");
                        } else {
                         Swal.fire("Error:",respuesta,"error");  
                        }                         
                    }                                                                   
                })                
                .catch(function (error) {
                    error401(error);
                    me.isSubmitting = false;
                });   
            }                         
        },

        actualizarEndPoints(){    
            let me = this;       

            const data_1=me.descripcion_endpoint;
            const data_2=me.url_endpoint;
            const data_3=me.version_endpoint;
            const data_4=me.selectModalidad;
            const data_5=me.selectServicio_v3;
            
           
            if (data_1==null || data_1=== '' || data_2==null || data_2=== ''||
                data_3==null || data_3=== ''||data_4=='0'||data_4==0||data_5=='0'||data_5==0) {
            Swal.fire("Error", "Registro nullo o vacio","error");
            } else {
                me.isSubmitting=true;       
                 axios.post("/qr_simple/actualizarEndpoint", {
                    id_qr_simple:me.selectServicio,
                    descripcion:data_1,
                    url:data_2,                
                    version:data_3,    
                    tipo:data_4,
                    id_servicio:data_5,
                    id:me.id_endPoints,
                    
                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"edicion de registro: "+me.descripcion,  
                })
                .then(function (response) {                  
                    let respuesta=response.data;
               
                    me.descripcion_endpoint="";
                    me.url_endpoint="";
                    me.version_endpoint="";
                    me.selectServicio_v3="0";
                    me.id_endPoints="";
                    me.cerrarModal('registrar');
                    me.listar_endpoint_list();
                    if (respuesta===0) {
                         Swal.fire("Actualizo!","Correctamente","success");
                    } else {
                        if (respuesta===1) {
                            Swal.fire("Error!","ya esta en uso el servicio por default solo puede tener uno en activo, puedes quitar editando el reguistro","error");
                        } else {
                         Swal.fire("Error:",respuesta,"error");  
                        }                         
                    }                                                                   
                })                
                .catch(function (error) {
                    error401(error);
                    me.isSubmitting = false;
                });   
            }                         
        },


editarServicioQr(){    
            let me = this;  
            const data_1=me.nomServicio;
            const data_2=me.url_;    
            const data_4=me.selectBanco;
            if (data_1==null || data_1=== '' || data_2==null || data_2=== '' ||
                data_4=='0' || data_4==0
            ) {
            Swal.fire("Error", "Registro nullo o vacio","error");
            } else {     
                 axios.put("/qr_simple/editarBanco", {
                    nombre:data_1,
                    usuario:me.usuario_,                
                    contraseña:me.contraseña_,
                    id:me.id_qr,
                    banco:data_4,   
                    url:data_2                      
                })
                .then(function (response) {                  
                    let respuesta=response.data;

                   me.listarQR(0);
                    if (respuesta===0) {
                         Swal.fire("Registro actualizado!","Correctamente","success");
                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }                                                                   
                })                
                .catch(function (error) {
                    error401(error);
                });   
            }                         
        },

limpiar(){
    let me=this;    
    me.nomServicio="";
    me.url_="";
    me.usuario_="";
    me.contraseña_="";         
    me.isSubmitting = false; 
    me.selectBanco="0";    
    me.id_qr=""; 
},

actualizarPorId(data,data2){
    let me=this;  
  const expanded = event.currentTarget.getAttribute('aria-expanded');
     
    // expanded es string: "true" o "false"
    if (expanded=='false') {
     me.valorador_1=1;
        me.nomServicio=data.nom_servicio_qr;
        me.url_=data.url_banco_servicio;
        me.usuario_="";
    me.contraseña_="";    
    me.id_qr=data.id;     
    me.isSubmitting = false; 
    me.selectBanco=data.tipo_qr;  
    }else{
        me.valorador_1=0;

    }  
},

activar_desactivarPri(id,puntero){
                let me=this;
                 let title_="";
                 let text_="";
                 let Desactivado_="";
                 let pieMSN="";
                if (puntero===0) {
                    title_="Esta Seguro de Desactivar ya que todo no seleccionados se desactivaran?";
                    text_="Es una eliminacion logica";   
                    Desactivado_="Desactivado!";
                    pieMSN="El registro a sido desactivado Correctamente";              
                } else {
                     title_= "Esta Seguro de Activar solo puede estar activo uno?";
                text_= "Es una Activacion logica";
                 Desactivado_="Activado!";
                    pieMSN="El registro a sido Activado Correctamente";   
                }

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: title_,
        text: text_,
        icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                
                if (result.isConfirmed) {
                     axios.put('/qr_simple/activar_desactivarPri',{
                        'id': id,
                        'puntero':puntero
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            Desactivado_,
                            pieMSN,
                            'success'
                        )
                        me.listarQR(1);
                    }).catch(function (error) {
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
                })
            },

activar_desactivar(id,puntero){
                let me=this;
                 let title_="";
                 let text_="";
                 let Desactivado_="";
                 let pieMSN="";
                if (puntero===0) {
                    title_="Esta Seguro de Desactivar?";
                    text_="Es una eliminacion logica";   
                    Desactivado_="Desactivado!";
                    pieMSN="El registro a sido desactivado Correctamente";              
                } else {
                     title_= "Esta Seguro de Activar?";
                text_= "Es una Activacion logica";
                 Desactivado_="Activado!";
                    pieMSN="El registro a sido Activado Correctamente";   
                }

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: title_,
        text: text_,
        icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                
                if (result.isConfirmed) {
                     axios.put('/qr_simple/activar_desactivar',{
                        'id': id,
                        'puntero':puntero
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            Desactivado_,
                            pieMSN,
                            'success'
                        )
                        me.listarQR(1);
                    }).catch(function (error) {
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
                })
            },

        
        listarQR() {
            let me = this;
           var url = "/qr_simple/listarQR";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayQR = respuesta;                 
                })
                .catch(function (error) {
                    error401(error);                
                });
        },

        crearServicioQr(){    
            let me = this;  
            const data_1=me.nomServicio;
            const data_2=me.usuario_;
            const data_3=me.contraseña_;
            const data_4=me.selectBanco;
            if (data_1==null || data_1=== '' || data_2==null || data_2=== ''||
                data_3==null || data_3=== '' || data_4=='0' || data_4==0
            ) {
            Swal.fire("Error", "Registro nullo o vacio","error");
            } else {
                me.isSubmitting=true;       
                 axios.post("/qr_simple/crearBanco", {
                    nombre:data_1,
                    usuario:data_2,                
                    contraseña:data_3,
                    banco:data_4,   
                    url:me.url_                          
                })
                .then(function (response) {                  
                    let respuesta=response.data;
                    me.nomServicio="";
                    me.usuario_="";
                    me.contraseña_="";
                    me.selectBanco="0";
                    me.isSubmitting = false;
                    me.url_="";
                    if (respuesta===0) {
                         Swal.fire("Registro creado!","Correctamente","success");
                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }                                                                   
                })                
                .catch(function (error) {
                    error401(error);
                    me.isSubmitting = false;
                });   
            }                         
        },

        seleccionBanco_data(id){
            let me=this;
            const banco = me.arrayBanco.find(e => e.id === id);            
         },

        cambioPestañaInf(data_1,data_2,data_3,data_4){
            let me=this;
            me.show_1=data_1;
            me.show_2=data_2;
            me.show_3=data_3;
            me.show_4=data_4;
            me.selectModalidad="0";
            me.selectServicio="0";
        },

        listarbanco() {
            let me = this;
           var url = "/qr_simple/listarbanco";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayBanco = respuesta;
                 
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


         listarNacionalidad() {
            let me = this;         
           var url = "/qr_simple/listarNacionalidad";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayNacionalidad = respuesta;                 
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
                    me.tituloModal = "Registrar endpoints";
                    me.showModal = true;
                   // me.selectModalidad="0";   
                    me.descripcion_endpoint="";
                    me.url_endpoint="";
                    me.version_endpoint="";
                    me.selectServicio_v3="0";
                 
                  //  me.selectServicio="0"; 
                  me.id_endPoints="";
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.tituloModal = "Actualizar endpoints";
             
                   me.descripcion_endpoint=data.descripcion;
                    me.url_endpoint=data.url;
                    me.version_endpoint=data.version;
                    me.selectServicio_v3=data.id_servicio;
                      me.id_endPoints=data.id;
          me.showModal = true;
            
                    me.classModal.openModal("registrar");

                    break;
                }

                case "registrar_2": {
                    me.tipoAccion = 1;                
                   me.showModal_2 = true;
me.tituloModal = "Cambio de contraseña";
me.isSubmitting_2 =false;
me.input_2=""; 
me.mensajeError="";            
                    me.classModal.openModal("registrar_2");
                    break;
                }

                case "registrar_3": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Consulta de datos";
               me.generationDate="";
                 me.showModal_3 = true;
                    me.classModal.openModal("registrar_3");

                    break;
                }
                case "registrar_4": {
                    me.tipoAccion = 1;
                    if (data.id_servicio==5) {
                       me.tituloModal = "Consulta de estado de QR"; 
                    } else {
                        me.tituloModal = "Eliminar QR"; 
                    }
                    
                    
               me.idQR_consultar=0;
                 me.showModal_4 = true;
                    me.classModal.openModal("registrar_4");

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
             me.isSubmitting=false;   
           
            }

            if (accion=="registrar_2") {
                me.classModal.closeModal(accion);
                me.showModal_2 = false;
                me.tituloModal = "Cambio de contraseña";
                me.isSubmitting_2 =false;
                me.input_2=""; 
                me.mensajeError="";

                me.id_entrada="";
                me.id_servicio_entrada="";
                me.id_qr_simple_entrada="";
                me.tipo_entrada="";
                me.id_servicio_entrada="";
            }

            if (accion=="registrar_3") {
                me.tituloModal = "Consulta de datos";
                me.generationDate="";
                 me.showModal_3 = false;                
            }

             if (accion=="registrar_4") {
                me.tituloModal = "Consulta de estado QR";
                me.generationDate="";
                 me.showModal_4 = false;                
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
        this.listarPerimsoxyz();
        this.listarbanco();
        this.listarNacionalidad();
        this.classModal.addModal("registrar");
        this.classModal.addModal("registrar_2");
        this.classModal.addModal("registrar_3");  
        this.classModal.addModal("registrar_4");  
        this.listarCredencial();      
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
</style>
