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
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-credencial" aria-selected="true">Configuración de credenciales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Datos de empresa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-fa-tab" data-toggle="pill" href="#pills-fa" role="tab" aria-controls="pills-fa" aria-selected="false">Tipo de venta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-moneda-tab" data-toggle="pill" href="#pills-moneda" role="tab" aria-controls="pills-moneda" @click="listarTipomoneda()" aria-selected="false">Tipo de moneda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-cuenta-tab" data-toggle="pill" href="#pills-cuenta" role="tab" aria-controls="pills-cuenta" @click="listarBanco();listarCuenta();listarUser()" aria-selected="false">Tipo de cuenta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-limite-tab" data-toggle="pill" href="#pills-limite" role="tab" aria-controls="pills-limite" aria-selected="false">Limite de transacción</a>
                </li>   
                <li class="nav-item">
                    <a class="nav-link" id="pills-superUser-tab" data-toggle="pill" href="#pills-superUser" role="tab" aria-controls="pills-superUser" @click="listarUser()" aria-selected="false">Super usuario</a>
                </li>    
                <li class="nav-item">
                    <a class="nav-link" id="pills-modal-tab" data-toggle="pill" href="#pills-modal" role="tab" aria-controls="pills-modal" aria-selected="false">Modal de apertura</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" id="pills-imp_tras-tab" data-toggle="pill" href="#pills-imp_tras" role="tab" aria-controls="pills-imp_tras" aria-selected="false">Imprecion transacción</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-add_user_rubro-tab" data-toggle="pill" href="#pills-add_user_rubro" role="tab" aria-controls="pills-add_user_rubro" aria-selected="false"  @click="listarUser();rubro_2();">Añadir usuario a rubro</a>
                </li>  
                       
            </ul>
        </div>
        <div class="card-body">
           
             <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-credencial-tab">
                        <div class="card">
                            <div class="card-header">
                                Credencial para servidor de correos
                            </div>
                            <div class="alert alert-info" role="alert">
  La configuración solo afectara a servidor de correos y <strong>correo default para crear clientes</strong>
</div>
                            <div class="card-body">    
                                <div class="form-group row">

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Host:</strong> 
                                    <span v-if="host == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="host" class="form-control" placeholder="mail.empresa.com" v-on:focus="selectAll"/>
                                        <span v-if="host == ''" class="error">Debe escribir el host</span>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Correo:</strong> 
                                    <span v-if="correo == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="correo" class="form-control" placeholder="correo@correo.es" v-on:focus="selectAll"/>
                                        <span v-if="correo == ''" class="error">Debe escribir un correo</span>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Puerto:</strong> 
                                    <span v-if="puerto == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="number" min="0" v-model="puerto" class="form-control" placeholder="" v-on:focus="selectAll"/>
                                        <span v-if="host == ''" class="error">Debe escribir un puerto</span>
                                    </div>
                                </div>
                                <div class="form-group row">

<label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Usuario:</strong> 
<span v-if="usuario == ''" class="error">(*)</span></label>
<div class="col-md-3">
    <input type="text" v-model="usuario" class="form-control" placeholder="puede ser un correo" v-on:focus="selectAll"/>
    <span v-if="host == ''" class="error">Debe escribir el usuario</span>
</div>

<label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Contraseña:</strong> 
<span v-if="contraseña == ''" class="error">(*)</span></label>
<div class="col-md-3">
    <input type="password" v-model="contraseña" class="form-control"  v-on:focus="selectAll"/>
    <span v-if="contraseña == ''" class="error">Debe escribir una contraseña</span>
</div>

<label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>SSL:</strong> 
</label>
<div class="col-md-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="autoSizingCheck2"  :true-value="1" 
    :false-value="0" v-model="ssl">      
      </div>
</div>
</div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 d-flex justify-content-center">       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;"  @click="update_credecial_correo();">Actualizar configuracion correro</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar configuracion correro</button>
   
    </div>
</div>

                          
                        </div>
                        
                    </div>


                    <!------------------------------------------------------------------------------------------------->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card">
                            <div class="card-header">
                              Datos de la empresa
                            </div>
                            <div class="alert alert-info" role="alert">
  La configuración solo afectara a datos del sistema tambien <strong>tomara los datos para la facturación</strong>
</div>
                            <div class="card-body">
                                <div class="form-group row">

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>NIT:</strong> 
                                    <span v-if="nit == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="nit" class="form-control" placeholder="nit de la empresa" v-on:focus="selectAll"/>
                                        <span v-if="nit == ''" class="error">Debe escribir el nit</span>
                                    </div>
                                      
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Empresa:</strong> 
                                    <span v-if="nombre_empresa == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="nombre_empresa" class="form-control" placeholder="nombre de negocio" v-on:focus="selectAll"/>
                                        <span v-if="nombre_empresa == ''" class="error">Debe escribir el un nombre de negocio</span>
                                    </div>                   
                               
                                </div>  
                                <div class="form-group row">
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Nro CEL/TEL:</strong> 
                                    <span v-if="celular == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="celular" class="form-control" placeholder="nit de la empresa" v-on:focus="selectAll"/>
                                        <span v-if="celular == ''" class="error">Debe escribir telefono o celular</span>
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Actividad economica:</strong> 
                                    <span v-if="celular == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="actividad_eco" class="form-control" placeholder="actividad economica" v-on:focus="selectAll"/>
                                        <span v-if="actividad_eco == ''" class="error">Debe escribir actividad economica</span>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group row justify-content-center">
    <div class="col-md-3 d-flex justify-content-center">
       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;"  @click="update_datos_empresa();">Actualizar datos de empresa</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar datos de empresa</button>
   
    </div>
</div>
                        </div>
                    </div>
                <!------------------------------------------------------------------------------------------>
                <div class="tab-pane fade" id="pills-fa" role="tabpanel" aria-labelledby="pills-fa-tab">
                    
                        <div class="card">
                            <div class="card-header">
                             Tipo de venta
                            </div>
                            <div class="alert alert-info" role="alert">
  La configuración afecta en el tipo facturación, <strong>tambien afecta a modulo de ventas</strong>
</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Tipo:</strong> 
                                    </label>
                                    <div class="col-md-3">
                                        <select v-if="puedeHacerOpciones_especiales===1" class="form-control"  v-model="selectTipoFac" @change="cambioEstado(selectTipoFac)" >
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option v-for="t in arrayTipofac" :key="t.id" :value="t.id">
                                                {{ t.tipo }}
                                            </option>
                                        </select>
                                        <select v-else class="form-control">
                                            <option value="0" disabled selected>Sin permiso...</option>
                                        
                                        </select>
                                     </div>
                                   
                                    <div class="col-md-3">
                                        <div v-if="validador_variables===0" class="alert alert-danger" role="alert">
                                           SIN CONFIGURACIÓN DE FACTURACIÓN
                                        </div>
                                        <div v-if="validador_variables===1" class="alert alert-success" role="alert">
                                            FACTURA
                                        </div>
                                        <div v-if="validador_variables===2" class="alert alert-primary" role="alert">
                                            DOSIFICACION
                                        </div>
                                        <div v-if="validador_variables===3" class="alert alert-warning" role="alert">
                                           USAR SOLO RECIBOS
                                        </div>
                                     </div>
              
                                </div>
                            </div>
                        </div>
                    </div>
                <!-------------------------------------------------------------------------------------------------------------------->
                <div class="tab-pane fade" id="pills-moneda" role="tabpanel" aria-labelledby="pills-moneda-tab">                    
                    <div class="card">
                        <div class="card-header">Tipo de moneda</div>
                        <div class="alert alert-warning" role="alert">
                           Para configuracion de facturación en linea. Ir a Siat/Configuración siat/en la pestaña de Conceptos/ en atividad escojer la opción Tipo moneda descargar, abrir el archivo que se descargo y buscar el pais que tiene en la configuracion de tipo de moneda, una vez que tenga el pais porner en la columna id_erp el valor de 1 solo debe haber un solo valor en 1 los demas deben estar vacios. guardar y subir el documento en mismo modulo que se descargo. 
</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Tipo:</strong> 
                                </label>
                                <div class="col-md-3">
                                    <select v-if="puedeHacerOpciones_especiales===1" class="form-control"  v-model="selectMoneda" @change="cambioDeMoneda(selectMoneda)">
                                        <option value="0" disabled selected>Seleccionar...</option>
                                        <option v-for="t in arrayMoneda" :key="t.id_nacionalidad_pais" :value="t.id_nacionalidad_pais">
                                            {{ t.pais+" "+t.simbolo }}
                                        </option>
                                    </select>
                                    <select v-else class="form-control">
                                        <option value="0" disabled selected>Sin permiso...</option>
                                    
                                    </select>
                                 </div>
                                 <div class="col-md-3">
                                        <div v-if="nombre_pais==='0'" class="alert alert-danger" role="alert">
                                           SIN TIPO DE MONEDA
                                        </div>
                                        <div v-else class="alert alert-primary" role="alert">
                                            {{"Pais: "+nombre_pais}}
                                        </div>
                                       
                                     </div>
                               
          
                            </div>
                        </div>
                    </div>
                </div>
                
            <!-------------------------------------------------------------------------------------------------------------------------->
            <div class="tab-pane fade" id="pills-cuenta" role="tabpanel" aria-labelledby="pills-cuenta-tab">
                <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Cuenta bancaria
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        <div class="form-group row">
            
                <div class="col-md-2">
                    <button v-if="puedeCrear===1" type="button" class="btn btn-primary" @click="abrirModal('regcuenta')">Añadir cuenta</button>
                    <button v-else type="button" class="btn btn-light">Añadir cuenta</button>
                    
                </div>                         
                               
          </div> 
        <div class="form-group row">
            <table class="table table-bordered table-striped table-sm table-responsive">
                <thead>
                    <tr>   
                        <th class="col-md-2" style="font-size: 13px; text-align: center">Opciones</th>
                        <th class="col-md-2" style="font-size: 13px; text-align: center">Banco</th>
                        <th class="col-md-2" style="font-size: 13px; text-align: center">Nombre</th>
                        <th class="col-md-2" style="font-size: 13px; text-align: center">Numero</th>
                        <th class="col-md-2" style="font-size: 13px; text-align: center">Titular</th>
                        <th class="col-md-2" style="font-size: 13px; text-align: center">Estado</th>                                    
                    </tr>
                </thead>
                <tbody>
                    <tr  v-for="c in arrayCuenta" :key="c.id">
                        <td class="col-md-2" style="font-size: 13px; text-align: center">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                    <button type="button" @click="abrirModal('regcuenta_edit',c);" class="btn btn-warning" style="margin-right: 5px;" >
                                        <i class="icon-pencil"></i></button>    
                                </div>
                                <div v-else>
                                    <button type="button"  class="btn btn-light" style="margin-right: 5px;" >
                                        <i class="icon-pencil"></i></button>    
                                </div>
                                <div v-if="puedeActivar==1">
                                <button v-if="c.estado == 1" type="button" @click="eliminar_cuenta(c.id)"  class="btn btn-danger" style="margin-right: 5px;">
                                    <i class="icon-trash"></i></button>
                                <button v-else type="button" class="btn btn-info" @click="activar_cuenta(c.id)" style="margin-right: 5px;">
                                    <i class="icon-check"></i></button>        
                                </div>
                                <div v-else>
                                <button v-if="c.estado == 1" type="button"  class="btn btn-light" style="margin-right: 5px;">
                                <i class="icon-trash"></i></button>
                                <button v-else type="button" class="btn btn-light"  style="margin-right: 5px;">
                                <i class="icon-check"></i></button> 
                                </div>

                            </div>                            
                        </td>
                        <td class="col-md-2" style="font-size: 13px; text-align: center">{{ c.nombre }}</td>
                        <td class="col-md-2" style="font-size: 13px; text-align: center">{{ c.nombre_cuenta }}</td>
                        <td class="col-md-2" style="font-size: 13px; text-align: center">{{ c.nro_cuenta }}</td>
                        <td class="col-md-2" style="font-size: 13px; text-align: center">{{ c.titular }}</td>
                        <td class="col-md-2" style="font-size: 13px; text-align: center">
                            <span v-if="c.estado===1" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
         Bancos
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
        <div v-if="puedeCrear===1" class="form-group row">
            <label class="col-md-3 form-control-label" for="text-input" style="font-size: 12px;"><strong>Nombre del banco:</strong></label>
               <div class="col-md-5">
                    <input type="text" v-model="banco_nombre" class="form-control" placeholder="Escriba el nombre del banco"/>                  
                </div>
                <div class="col-md-2">                   
                    <button type="button" v-if="isSubmitting_2==false" class="btn btn-outline-primary" @click="crearBanco()">Añadir</button>
                    <button type="button" v-else class="btn btn-outline-light">Añadir</button>
                </div>                    
          </div> 
          <div v-else class="form-group row">
            <label class="col-md-3 form-control-label" for="text-input" style="font-size: 12px;"><strong>Sin permiso</strong></label>
                                 
          </div> 
          <div class="form-group row">
            <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">Opciones</th>
                                    <th class="col-md-8" style="font-size: 13px; text-align: center">Nombre</th>
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">Estado</th>
                                                       
                                </tr>
                            </thead>
                            <tbody>                                  
                                <tr v-for="b in arrayBanco" :key="b.id">    
                                                
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">
                                        <div  class="d-flex justify-content-start">
                                            <div  v-if="puedeEditar==1">
                                                <button type="button" class="btn btn-warning" style="margin-right: 5px;" @click="abrirModal('regbanco',b)">
                                                    <i class="icon-pencil"></i></button>
                                            </div>
                                            <div v-else>
                                                <button type="button" class="btn btn-light" style="margin-right: 5px;">
                                                    <i class="icon-pencil"></i></button>
                                            </div>
                                            <div v-if="puedeActivar==1">
                                                <button v-if="b.activo == 1" @click="eliminar_banco(b.id)" type="button" class="btn btn-danger" style="margin-right: 5px;">
                                             <i class="icon-trash"></i> </button>
                                    <button v-else type="button" @click="activar_banco(b.id)" class="btn btn-info" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                    </button>   
                                            </div>
                                            <div v-else>
                                                <button v-if="b.activo == 1"  type="button" class="btn btn-light" style="margin-right: 5px;">
                                             <i class="icon-trash"></i> </button>
                                    <button v-else type="button"  class="btn btn-light" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                    </button>   
                                            </div>
                                        </div>        
                                       
                                
                                    </td>
                                    <td class="col-md-8" style="font-size: 13px; text-align: center">{{(b.nombre).toUpperCase()}}</td>
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">
                                        <span v-if="b.activo===1" class="badge badge-pill badge-success">Activo</span>
                                        <span v-else class="badge badge-pill badge-danger">Desactivado</span>
                                    </td>
                       
                                </tr>
                            </tbody>   
                        </table> 
          </div>

        </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTree" aria-expanded="false" aria-controls="collapseTree">
            Agente personal
        </button>
      </h5>
    </div>
    <div id="collapseTree" class="collapse" aria-labelledby="headingTree" data-parent="#accordion">
      <div class="card-body">
        <!-- insertar datos -->    
        <div class="container">
                                
                                <div class="form-group row">
                                    <div class="col-md-2 input-group mb-3">
                                        Usuario:
                                    </div>
                                <div class="col-md-7 input-group mb-3">
                                    
                    <VueMultiselect
                        v-model="selectedUser"
                        :options="arrayUser_2"
                        :max-height="120"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="nom_completo" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect>           
                     </div>
                        <div class="col-md-2 input-group mb-3">
                            <div v-if="puedeActivar===1">
                            <button   v-if="selectedUser==null" type="button" class="btn btn-light">Añadir</button>
                            <button   v-else-if="selectedUser.responsable===0" @click="añadirOquitar_Responsable(0)" type="button" class="btn btn-primary">Añadir</button>
                            <button   v-else="selectedUser.responsable===1" @click="añadirOquitar_Responsable(1)"  type="button" class="btn btn-danger">Quitar</button>                            
                            </div>
                            <div v-else>
                            <button   type="button" class="btn btn-light">Sin permiso</button>                       
                            </div>
                                          
                        </div>        
                    </div>
                    
                    <div class="form-group row">
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                 <tr>    
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">User</th>
                                    <th class="col-md-10" style="font-size: 13px; text-align: center">Nombre</th>                                                      
                                </tr>
                            </thead>
                            <tbody>                                  
                                <tr v-for="u in arrayUser_2" :key="u.id" :hidden="u.responsable===0"> 
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">{{ u.name }}</td>
                                    <td class="col-md-10" style="font-size: 13px; text-align: center">{{ u.nom_completo }}</td>
                                </tr>
                            </tbody>   
                        </table> 
                    </div>   
                  </div>
        </div>
    </div>
  </div>


</div>

                    </div>
            <!-------------------------------------------------------------------------------------------------------------------------->

                    <div class="tab-pane fade" id="pills-limite" role="tabpanel" aria-labelledby="pills-limite-tab">
                        <div class="card">
                            <div class="card-header">Datos de limite de salidas</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input" style="font-size: 12px;"><strong>Limite de monto:</strong></label>
                                    <div class="col-md-4">
                                        <input type="number" min="0" v-model="limite_monto"  class="form-control" placeholder="limite monto"/>                                      
                                    </div>
                                      
                                    <label class="col-md-2 form-control-label" for="text-input" style="font-size: 12px;"><strong>Limite en horas:</strong></label>
                                    <div class="col-md-4">
                                        <input type="number" min="0" v-model="limite_horas" class="form-control" placeholder="limite en horas "/>                                        
                                    </div>                              
                                </div>                               
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 d-flex justify-content-center">       
                                    <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;"  @click="añadirlimite();">Actualizar datos de limite</button>
                                    <button v-else type="button" class="btn btn-light">Actualizar datos de limite</button>   
                                </div>
                            </div>
                        </div>
                    </div>
<!------------------------------------------------------------------------------------------------------------------------->
<div class="tab-pane fade" id="pills-superUser" role="tabpanel" aria-labelledby="pills-superUser-tab">
    <div class="card-body">
        <!-- insertar datos -->    
        <div class="alert alert-info" role="alert">
  La configuración afecta el tipo de vista por usuario especifico. <strong>Puede ver todos los usuarios o usuario autencicado</strong>
</div>
        <div class="container">
                                
                                <div class="form-group row">
                                    <div class="col-md-2 input-group mb-3">
                                        Usuario:
                                    </div>
                                <div class="col-md-7 input-group mb-3">                                                   
                                    <VueMultiselect
                        v-model="selectedUser"
                        :options="arrayUser_2"
                        :max-height="120"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="nom_completo" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect>            
                     </div>
                        <div class="col-md-2 input-group mb-3">
                            <div v-if="puedeActivar===1">
                                <button   v-if="selectedUser==null" type="button" class="btn btn-light">Añadir</button>
                                <button   v-else-if="selectedUser.super_usuario===0" @click="añadirOquitar_superUsuario(0)" type="button" class="btn btn-primary">Añadir</button>
                                <button   v-else="selectedUser.super_usuario===1" @click="añadirOquitar_superUsuario(1)"  type="button" class="btn btn-danger">Quitar</button>                              
                            </div>
                            <div v-else>
                            <button   type="button" class="btn btn-light">Sin permiso</button>                       
                            </div>
                                          
                        </div>        
                    </div>
                    
                    <div class="form-group row">
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                 <tr>                                  
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">User</th>
                                    <th class="col-md-10" style="font-size: 13px; text-align: center">Nombre</th>                                                    
                                </tr>
                            </thead>
                            <tbody>                                  
                                <tr v-for="u in arrayUser_2" :key="u.id" :hidden="u.super_usuario===0">                                   
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">{{ u.name }}</td>
                                    <td class="col-md-10" style="font-size: 13px; text-align: center">{{ u.nom_completo }}</td>
                                </tr>
                            </tbody>   
                        </table> 
                    </div>   
                  </div>
        </div>
                    </div>
    <!---------------------------------------------------------------------------------------------------------------------------->
  <div class="tab-pane fade" id="pills-modal" role="tabpanel" aria-labelledby="pills-modal-tab">
                   <div class="card">
                            <div class="card-header">Datos de modal de apertura</div>
                            <div class="alert alert-info" role="alert">
  Solo afecta las ventanas modales donde se muestre conteo de monedas. <strong>Disponible en modulo de cajas</strong>
</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Venta modal:</strong> 
                                    </label>
                                  
          
                                    <div class="col-md-3">
                                        <select v-if="puedeHacerOpciones_especiales===1" class="form-control"  v-model="selectModalApertura" @change="cambioModalApertura(selectModalApertura)">
                                        <option value=0 disabled selected>Seleccionar...</option>
                                        <option value=1>Venta modal normal</option>
                                        <option value=2>Venta modal modificada</option>
                                    </select>    
                                        <select v-else class="form-control">
                                            <option value="0" disabled selected>Sin permiso...</option>
                                        
                                        </select>
                                     </div>
                                   
                                    <div class="col-md-3">
                                        <div v-if="selectModalApertura===0" class="alert alert-danger" role="alert">
                                             {{ modalApertura  }}
                                        </div>
                                        <div v-else class="alert alert-primary" role="alert">
                                             {{ modalApertura  }}
                                        </div>
                                     </div>
              
                                </div>
                            </div>
                           
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 d-flex justify-content-center">       
                                    <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;" @click="actualizarModalContable()" >Actualizar modal de apertura</button>
                                    <button v-else type="button" class="btn btn-light">Actualizar modal de apertura</button>   
                                </div>
                            </div>
                        </div>
                    </div>
            <!-------------------------------------------------------------------------------------------------------------------->
            <div class="tab-pane fade" id="pills-imp_tras" role="tabpanel" aria-labelledby="pills-imp_tras-tab">                    
                    <div class="card">
                        <div class="card-header">Impreción de transaccion</div>
                        <div class="alert alert-warning" role="alert">
                          Esta configuración solo afecta a acpertura y cierre de cajas donde, imprime despues del cierre automaticamente un ticket en la impresora termica con los datos de los traspasos electronicas. 
</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Tipo:</strong> 
                                </label>
                                <div class="col-md-3">
                                    <select v-if="puedeHacerOpciones_especiales===1" class="form-control"  v-model="transaccion_banco" >
                                        <option value="2" disabled selected>Seleccionar...</option>
                                        <option value="0" >Desactivar</option>
                                        <option value="1" >Activar</option>
                                    </select>
                                    <select v-else class="form-control">
                                        <option value="0" disabled selected>Sin permiso...</option>                                    
                                    </select>
                                 </div>
                                 <div class="col-md-1">
                                    <button v-if="transaccion_banco==='1'" type="button" class="btn btn-primary" @click="actulizar_transaccion(1)">Actulizar</button> 
                                    <button v-else-if="transaccion_banco==='0'" type="button" style="color: white;" class="btn btn-warning" @click="actulizar_transaccion(0)">Actulizar</button>  
                                    <button v-else="transaccion_banco==='2'" type="button" class="btn btn-light">Actulizar</button> 
                                 </div>
                                 
                                 <div class="col-md-6">
                                        <div v-if="transaccion_data===0" class="alert alert-warning" role="alert">
                                           SIN ACTIVACION ACCIÓN DE IMPRECIÓN DE TRANSACCION
                                        </div>
                                        <div v-else class="alert alert-primary" role="alert">
                                            ACTIVACION ACCIÓN DE IMPRECIÓN DE TRANSACCION
                                        </div>
                                       
                                     </div>
                               
          
                            </div>
                        </div>
                    </div>
                </div>
                
             <!--------------------------------------RUBRO---------------------------------------------------->
             <div class="tab-pane fade" id="pills-add_user_rubro" role="tabpanel" aria-labelledby="pills-add_user_rubro-tab">
    <div class="card-body">
        <!-- insertar datos -->    
        <div class="alert alert-warning" role="alert">
  La configuración afecta solo al modulo de ventas. <h4><strong>Es necesario añadir al usuario para que vea los productos en modulo de ventas</strong></h4>
</div>
        <div class="container">
                                
                                <div class="form-group row">
                                    
                                <div class="col-md-5 input-group mb-3">                                                   
                                    <VueMultiselect
                                
                        v-model="selectedUser"
                        :options="arrayUser_2"
                        :max-height="150"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="nom_completo" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect>            
                     </div>
                    
                    <div class="col-md-5 input-group mb-3" v-show="selectedUser!=null">   
                        <select class="form-control"  v-model="selectRubro" >
                                        <option value="0" disabled selected>Seleccionar...</option>
                                        <option v-for="o in arrayRubro" :key="o.id" :value="o.id">
                                            {{ o.nombre }}
                                        </option>
                                       
                        </select>


                    </div>  

                        <div class="col-md-2 input-group mb-3">
                            <div v-if="puedeActivar===1">
                                <button   v-if="selectedUser==null" type="button" class="btn btn-light">Añadir</button>
                                <button   v-else-if="selectedUser.rubro_x_usuario===null" @click="añadirOquitar_rubroUsuario(0)" type="button" class="btn btn-success">Añadir</button>
                                <button   v-else @click="añadirOquitar_rubroUsuario(1)"  type="button" class="btn btn-danger">Quitar</button>                              
                            </div>
                            <div v-else>
                            <button   type="button" class="btn btn-light">Sin permiso</button>                       
                            </div>
                                          
                        </div>        
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                 <tr>                                  
                                    <th class="col-md-3" style="font-size: 13px; text-align: center">User</th>
                                    <th class="col-md-5" style="font-size: 13px; text-align: center">Nombre</th>
                                    <th class="col-md-4" style="font-size: 13px; text-align: center">id rubro</th>                                                       
                                </tr>
                            </thead>
                            <tbody>                                  
                                <tr v-for="u in arrayUser_2" :key="u.id" :hidden="u.rubro_x_usuario===null">                                   
                                    <td class="col-md-3" style="font-size: 13px; text-align: center">{{ u.name }}</td>
                                    <td class="col-md-5" style="font-size: 13px; text-align: center">{{ u.nom_completo }}</td>
                                    <td class="col-md-4" style="font-size: 13px; text-align: center">{{ u.rubro_x_usuario }}</td>
                                </tr>
                            </tbody>   
                        </table>                
                    

                  </div>
        </div>
                    </div>
            <!-------------------------------------------------------------------------------------------------------------------->

                </div>
            
        </div>
    </div>
</div>
  <!-- modal registro bancos -->
  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="regbanco" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">BANCO</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('regbanco')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: whitesmoke">
                        <div class="form-group">
                            <label>Nombre Banco:</label>
                            <input type="text" id="nombrebanco" name="nombrebanco" class="form-control rounded" placeholder="Nombre Banco" v-model="nombrebanco_0">
                 
                        </div>
                    </div>    
                    

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('regbanco')">Cerrar</button>
                        <button type="button" class="btn btn-primary rounded" @click="editarBanco()" :disabled="nombrebanco_0==''">Guardar</button>
                        
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
         
        <!--fin del modal-->
          <!-- modal registro cuenta -->
  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="regcuenta" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">CUENTA</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('regcuenta')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: whitesmoke">
                        <div class="card-body">
    <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Banco:</label>
                                        <select class="form-control"  v-model="selectBanco_cuenta">
                                        <option value=0 disabled selected>Seleccionar...</option>
                                        <option v-for="b in arrayBanco" :key="b.id" :value="b.id" :hidden="b.estado===0">
                                 
                                            {{ b.nombre}}
                                        </option>
                                    </select>  
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Nombre cuenta:</label>
                                        <input type="text" class="form-control rounded" placeholder="Escriba el nombre del cuenta" v-model="nom_cuenta">
                                    </div>
                                  
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Numero de cuenta:</label>
                                        <input type="text" class="form-control rounded" placeholder="Escriba el numero de cuenta" v-model="num_cuenta">
                                   
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Titular:</label>
                                        <input type="text" class="form-control rounded" placeholder="Escriba el titular" v-model="titular_cuenta">
                                    </div>
                                </div>
  </div>
                    </div>    
                    
              
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('regcuenta')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button v-if="tipoAccion===1" @click="crearCuenta()" type="button" class="btn btn-primary rounded">Guardar</button>
                                <button v-else type="button" @click="editarCuenta()" class="btn btn-primary rounded">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                                <button type="button" v-else class="btn btn-light">Actualizar</button>
                            </div>
                        </div>
                                             
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import Multiselect from 'vue-multiselect'
//Vue.use(VeeValidate);

export default {
    components: { VueMultiselect,Multiselect},
     //---permisos_R_W_S
     props: ['codventana','idmodulo'],
        //-------------------
    data() {
        return {
            //---correo  
            tipoAccion:0,
            id_credencial:'',      
            host:'',
            correo:'',
            puerto:'',
            usuario:'',
            contraseña:'',
            isSubmitting_2: false, // Controla el estado del botón de envío
            ssl:0, 
            credenciales_correo:[],
            nit:'',
            nombre_empresa:'',
            celular:'',
            selectTipoFac:0,  
            actividad_eco:'',
            arrayTipofac: [{ id: 1, tipo: "Facturacion en linea" },{ id: 2, tipo: "Dosificacion" },{ id: 3, tipo: "Usa recibo" }],
//---permisos_R_W_S
puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
                validador_variables:0,

                arrayMoneda:[],
                selectMoneda:0,
                pais:'',
                estado_cambio_moneda:0,
                nombre_pais:'',

                arrayBanco:[],
                banco_nombre:'',
                nombrebanco_0:'',
                id_banco:'',

                titular_cuenta:'',
                num_cuenta:'',
                nom_cuenta:'',
                selectBanco_cuenta:0,
                id_cuenta:'',
                arrayCuenta:[],

                arrayUser_2:[],
                selectedUser:null,
                arrayUserResponsable:[],

                limite_monto:0,
                limite_horas:0,  

                isSubmitting: false, // Controla el estado del botón de envío
                isSubmitting_2:false,
                //------------------super usuario
              
                arryaSuper_user:[],
                selectUser_super:null,

                modalApertura:'No tiene ninguna configuración',
                selectModalApertura:0,
                transaccion_banco:'2',
                transaccion_data:'',

                //------------------rubro
                arrayRubro:[],
                selectRubro:'0',
                cadenaRubro:'',
                cadenaId_rubro:'',
              

              

        };
    },

   

    computed: {
      
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


    
    watch: {
        selectMoneda: function (newValue) {
            
           
                let p = this.arrayMoneda.find(
                    (element) => element.id_nacionalidad_pais === newValue,
                );

                if (p) {
                    this.pais =p.pais;
                    
                } 
            
        },
    },
    
    methods: {
          //-----------------------------------permisos_R_W_S        
    listarPerimsoxyz() {
                //console.log(this.codventana);
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
//-------------------------------------------------------------- 
  
        añadirlimite(){
            let me = this;            
            if (me.limite_monto===null || me.limite_monto==="" || me.limite_horas===null || me.limite_horas==="") {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "datos nulos",
                });
            } else {
      
                axios
                .post("/credenciales_correo/limite_2", {
                    id: me.id_credencial,                   
                    limite_monto:me.limite_monto,
                    limite_horas:me.limite_horas,                                   

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"actualziacion datos de limite",  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                .catch(function (error) {
                error401(error);
                });
            }
        },
       
        cambioModalApertura(data){
            let me = this;
          let numero = Number(data); // 123
          switch (numero) {
            case 0: {
                me.modalApertura="No tiene ninguna configuración";  
                    break;
                }
            case 1: {
                me.modalApertura="Modal normal. Esta selección anota en forma vertical tiene una forma clasica de llenado de datos.";  
                    break;
                }
            case 2: {
                me.modalApertura="Modal modificado, esta es un modal mas personalizado en formato de siguiente y siguiente";  
                    break;
                }        
          }       
        },


        añadirOquitar_Responsable(data){
            let me = this;      
            axios.put("/responsable/añadir_quitar", {
                    id:(me.selectedUser).id,
                    data:data                   
                }).then(function (response) {
                    me.listarUser();  
                    me.selectedUser=null;                
                    Swal.fire(
                        "Accion!",
                        "Realizada correctamente",
                        "success",
                    );
                })
                .catch(function (error) {                    
                    error401(error);
                });             
        },

        añadirOquitar_superUsuario(data){
            let me = this;      
            axios.put("/super_usuario/añadir_quitar", {
                    id:(me.selectedUser).id,
                    data:data                   
                }).then(function (response) {
                    me.listarUser();  
                    me.selectUser_super=null;                
                    Swal.fire(
                        "Accion!",
                        "Realizada correctamente",
                        "success",
                    );
                })
                .catch(function (error) {                    
                    error401(error);
                });             
        },

        añadirOquitar_rubroUsuario(data){
            let me = this; 
            let bandera=1;
            let idRubro = parseInt(me.selectRubro);
            if (data==0) {
                if (me.selectRubro=="0") {
                    bandera=0;
                }
            }
                
 
             if (bandera===0) {
                Swal.fire("Error!","Debe seleccionar al menos un rubro.","error",);
             } else {
           
            axios.put("/user_rubro/añadir_quitar", {
                    id:(me.selectedUser).id,
                    data:data,  
                    id_cadena:idRubro                 
                }).then(function (response) {
                    me.listarUser();  
                     me.selectRubro="0";
                    me.selectedUser=null;                
                    Swal.fire(
                        "Accion!",
                        "Realizada correctamente",
                        "success",
                    );
                })
                .catch(function (error) {                    
                    error401(error);
                });
             }            
        },

        listarUser(){
            let me = this;        
            var url = "/listarUser";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUser_2=respuesta;
                    // me.arrayUser_2 = respuesta.filter(user => user.responsable === 0);
                   // me.arrayUserResponsable = respuesta.filter(user => user.responsable === 1);
         
                })
                .catch(function (error) {
                    error401(error);       
                });
        },

        rubro_2(){
            let me = this;        
            var url = "/listarRubro_venta";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRubro=respuesta;  
                              
                })
                .catch(function (error) {
                    error401(error);
                });
        },
        

        nameWithLang ({name, nom_completo,}) {
            
            return `${name} (${nom_completo}) `
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


        update_tipo_venta(data){
            let me= this;         
            axios
                .put("/credenciales_correo/tipo_venta_update", {
                    id: me.id_credencial,                   
                    validador_variables:data,    
                    
                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"cambio de tipo de venta",  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                    me.selectTipoFac=0;
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                //.catch(function (error) {
                //    error401(error);
                //});
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
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
        },

    update_datos_empresa(){
            let me = this; 
            if (me.nit===""||me.nombre_empresa===""||me.celular===""||me.actividad_eco==="") {
                Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "datos nulos",

});
            }else{
                axios
                .post("/credenciales_correo/update_datos_empresa", {
                    id: me.id_credencial,                   
                    nit:me.nit,
                    nombre_empresa:me.nombre_empresa,
                    celular:me.celular,
                    actividad_eco:me.actividad_eco,                  

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"actualziacion datos de empresa",  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                //.catch(function (error) {
                //    error401(error);
                //});
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
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
            }

        },
        
        listarTipomoneda(){
            let me = this;        
            var url = "/credenciales_correo/tipo_moneda";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayMoneda = respuesta;
                    let p = me.arrayMoneda.find(
                    (element) => element.id_nacionalidad_pais === me.estado_cambio_moneda);
                if (p) {
                    me.nombre_pais =p.pais;                    
                } else {
                    me.nombre_pais ="0";  
                }          
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        update_credecial_correo() {
            let me = this;
            
            if (me.host==""||me.correo==""||me.puerto==""||me.usuario==""||me.contraseña=="") {
                
                    Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "datos nulos",

});
            }else{
               
            axios
                .post("/credenciales_correo/update", {
                    id: me.id_credencial,                   
                    host:me.host,
                    correo:me.correo,
                    puerto:me.puerto,
                    usuario:me.usuario,
                    contraseña:me.contraseña,
                    ssl:me.ssl,

                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"actualziacion de credencial correo",  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
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
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
            }
            

            
        
        },
   
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

        cambioDeMoneda(id){
            let me=this;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });         
                
            swalWithBootstrapButtons
                .fire({
                    title: "¿Esta Seguro de cambiar el tipo de moneda?",
                    text: "Tendra un cambio en tipo de moneda.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post("/credenciales_correo/tipomonedaUpdate", {
                    id: me.id_credencial,                   
                    id_moneda:id,    
                    
                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"cambio de tipo de venta id moneda"+id,  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                    me.nombre_pais=me.pais;
                    me.selectMoneda=0;
                  //  me.estado_cambio_moneda=0;
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                //.catch(function (error) {
                //    error401(error);
                //});
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
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
                      
                    } else if (
                        /* Read more about handling dismissals below */
                       
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        me.selectTipoFac=0;
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
            
        },

        cambioEstado(id){
            let me=this;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            if (id===1) {
                
            swalWithBootstrapButtons
                .fire({
                    title: "¿Esta Seguro de cambiar el tipo de facturación?",
                    text: "Se cambiara a facturacion en liena",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Activar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        me.validador_variables=1;
                        me.update_tipo_venta(1);
                    } else if (
                        /* Read more about handling dismissals below */
                       
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        me.selectTipoFac=0;
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
            } else {
                if (id===2) {
                    swalWithBootstrapButtons
                .fire({
                    title: "¿Esta Seguro de cambiar el tipo de facturación?",
                    text: "Se cambiara a Dofisificacion",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Activar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        me.validador_variables=2;
                        me.update_tipo_venta(2);
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        me.selectTipoFac=0;
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
                } else {
                    me.selectTipoFac=0,
                    swalWithBootstrapButtons.fire(
                                    "Error!",
                                    "Esta haciendo procesos o una mala manipulación del sistema",
                                    "error",
                                );
                }
            } if (id===3) {
                swalWithBootstrapButtons
                .fire({
                    title: "¿Esta Seguro no usar ninguna?",
                    text: "Esta opción no podra imprimir factura solo resivos podra hacer",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Activar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        me.validador_variables=3;
                        me.update_tipo_venta(3);
                    } else if (
                        /* Read more about handling dismissals below */
                       
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        me.selectTipoFac=0;
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
            } else {
                
            }
        },


        listarCuenta(){
            let me = this;
            var url = "/cuenta/listar_cuenta";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayCuenta=respuesta;
                })
                .catch(function (error) {
                    error401(error);
                });  
        },

        crearCuenta(){
            let me = this;
            if (me.titular_cuenta===""||me.num_cuenta===""||me.nom_cuenta===""||me.selectBanco_cuenta===0) {
                Swal.fire(
                    "Error",
                    "Campo nulo o vacío", 
                    "error"
                );
            } else {
                  // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                axios.post("/cuenta/crear_cuenta", {
                    id_banco: me.selectBanco_cuenta,   
                    nombre: me.nom_cuenta,   
                    nro_cuenta: me.num_cuenta,   
                    titular: me.titular_cuenta,                 
                })
                .then(function (response) {
                    me.listarCuenta(); 
                    me.selectBanco_cuenta=0; 
                    me.nom_cuenta=""; 
                    me.num_cuenta=""; 
                    me.titular_cuenta=""; 
                    me.cerrarModal('regcuenta');
                    Swal.fire(
                        "Registro creado!",
                        "Correctamente",
                        "success",
                    );              
                })               
                .catch(function (error) {                
                    Swal.fire(
                    "Error",
                    ""+error.response.data, // Muestra el mensaje de error en el alert
                    "error" );                
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
            }
        },

        editarCuenta(id){ 
            let me = this;
            if (me.titular_cuenta===""||me.num_cuenta===""||me.nom_cuenta===""||me.selectBanco_cuenta===0) {
                Swal.fire(
                    "Error",
                    "Campo nulo o vacío", 
                    "error"
                );
            } else {
                axios.put("/cuenta/editar_cuenta", {
                    id:me.id_cuenta,
                    id_banco: me.selectBanco_cuenta,   
                    nombre: me.nom_cuenta,   
                    nro_cuenta: me.num_cuenta,   
                    titular: me.titular_cuenta,                
                })
                .then(function (response) {
                    me.listarCuenta(); 
                    me.selectBanco_cuenta=0; 
                    me.nom_cuenta=""; 
                    me.num_cuenta=""; 
                    me.titular_cuenta=""; 
                    me.id_cuenta="";
                    me.cerrarModal('regcuenta');
                    Swal.fire(
                        "Registro editado!",
                        "Correctamente",
                        "success",
                    );                           
                })               
                .catch(function (error) {             
                    me.cerrarModal('regcuenta');                
            });
            }
        },
      
        eliminar_cuenta(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {                
                if (result.isConfirmed) {
                     axios.put('/cuenta/desactivar_cuenta',{
                        'id': id
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarCuenta(); 
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

            activar_cuenta(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Activar?',
                text: "Es una Activacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Activar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/cuenta/activar_cuenta',{
                        'id': id
                    }).then(function (response) {                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarCuenta(); 
                    }).catch(function (error) {
                        error401(error);
                    });       
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue Activado',
                    'error'
                    ) */
                }
                })
            },

         listarBanco(){
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

         crearBanco(){
            let me = this;
            if (me.banco_nombre===""||me.banco_nombre===null) {
                Swal.fire(
                    "Error",
                    "Campo nulo o vacío", 
                    "error"
                );
            } else {
                 // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting_2) return;
      me.isSubmitting_2 = true; // Deshabilita el botón
                axios.post("/credenciales_correo/crear_banco", {
                    nombre: me.banco_nombre,                 
                })
                .then(function (response) {
                    me.listarBanco(); 
                    me.banco_nombre=""; 
                    me.isSubmitting_2=false;
                    Swal.fire(
                        "Registro creado!",
                        "Correctamente",
                        "success",
                    );              
                })               
                .catch(function (error) {                
                    Swal.fire(
                    "Error",
                    ""+error.response.data, // Muestra el mensaje de error en el alert
                    "error" );                
            }).finally(() => {
          me.isSubmitting_2 = false; // Habilita el botón nuevamente al finalizar
        });
            }

         },

         editarBanco(){
            let me = this;
            if (me.nombrebanco_0===""||me.nombrebanco_0===null) {
                Swal.fire(
                    "Error",
                    "Campo nulo o vacío", 
                    "error"
                );
            } else {
                axios.put("/credenciales_correo/editar_banco", {
                    id:me.id_banco,
                    nombre: me.nombrebanco_0,                 
                })
                .then(function (response) {
                    me.listarBanco(); 
                    me.banco_nombre=""; 
                    Swal.fire(
                        "Registro editado!",
                        "Correctamente",
                        "success",
                    );
                    me.cerrarModal("regbanco");             
                })               
                .catch(function (error) {                
                    Swal.fire(
                    "Error",
                    ""+error.response.data, // Muestra el mensaje de error en el alert
                    "error" );                
            });
            }
         },
        
         eliminar_banco(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {                
                if (result.isConfirmed) {
                     axios.put('/credenciales_correo/banco_desactivar',{
                        'id': id
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarBanco(); 
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

            activar_banco(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Activar?',
                text: "Es una Activacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Activar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/credenciales_correo/banco_activar',{
                        'id': id
                    }).then(function (response) {                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarBanco();
                    }).catch(function (error) {
                        error401(error);
                    });       
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue Activado',
                    'error'
                    ) */
                }
                })
            },

            actualizarModalContable(){
                let me = this;
                axios.post("/credenciales_correo/modal_apertura", {
                    id: me.id_credencial,                   
                    modal_apertura:me.selectModalApertura,

                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"actualziacion modal de apertura",                
                }).then(function (response) {                    
                        Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);                                            
                    })                
                  .catch(function (error) { 
                    error401(error);                        
            }); 
            },

            actulizar_transaccion(data){
                let me = this;
                axios.post("/credenciales_correo/transaccion_data", {
                    id: me.id_credencial,                   
                    tras:data,

                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"actualziacion modal de transaccion con el dato "+data,                
                }).then(function (response) { 
                    me.transaccion_banco="2";   
                    me.listarCredencial();                
                        Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);                                            
                    })                
                  .catch(function (error) { 
                    error401(error);                        
            }); 
            },

 listarCredencial() {
            let me = this;
            var url = "/credenciales_correo";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                  
                    me.id_credencial=response.data[0].id;
                    me.host=response.data[0].host;                   
                   me.correo=response.data[0].correo;
                    me.puerto=response.data[0].puerto;
                    me.usuario=response.data[0].usuario;
                    me.contraseña=response.data[0].contraseña;
                    me.ssl=response.data[0].ssl;               
                    me.nit=response.data[0].nit;
                    me.nombre_empresa=response.data[0].nom_empresa;
                    me.celular=response.data[0].nro_celular;
                    me.validador_variables=response.data[0].factura_dosificacion === null ? 0:response.data[0].factura_dosificacion;
                    me.actividad_eco=response.data[0].actividad_economica;
                    me.estado_cambio_moneda=response.data[0].moneda;
                    
                    me.limite_monto=response.data[0].monto_limite;
                    me.limite_horas=response.data[0].tiempo_limite;  
                    me.selectModalApertura=response.data[0].modal_apertura;
                    me.transaccion_data=response.data[0].imprimir_trans;
                    me.cambioModalApertura(me.selectModalApertura);
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
         switch (accion) {
                case "regbanco": {
                
                    me.nombrebanco_0= (data.nombre).toUpperCase();
                    me.id_banco=data.id;
                    me.classModal.openModal("regbanco");
                    break;
                }  
                case "regcuenta": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
   
                    me.titular_cuenta="";
                    me.num_cuenta="";
                    me.nom_cuenta="";
                    me.selectBanco_cuenta=0;
                    me.classModal.openModal("regcuenta");
                    break;
                }
                case "regcuenta_edit":{
                    me.tipoAccion = 2;
                    me.isSubmitting=false;                  
                    me.id_cuenta=data.id;
                    me.selectBanco_cuenta=data.id_banco=== null ? 0 :data.id_banco;
                    me.nom_cuenta=data.nombre_cuenta;
                    me.num_cuenta=data.nro_cuenta;
                    me.titular_cuenta=data.titular;
                    me.classModal.openModal("regcuenta");
                    break;
                }                           
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "regbanco") {
              
                me.tituloModal = "";
                me.nombrebanco_0="";
                me.id_banco="";
                me.classModal.closeModal(accion);         
            }
            if (accion == "regcuenta") {
                me.tipoAccion=1;
                me.isSubmitting=false;
                me.tituloModal="";
                me.titular_cuenta="";
                me.num_cuenta="";
                me.nom_cuenta="";
                me.selectBanco_cuenta=0;
                me.id_cuenta="";
                me.classModal.closeModal(accion);         
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
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();
            //-----------------------
        this.listarCredencial();
        this.classModal.addModal("regbanco");
        this.classModal.addModal("regcuenta");
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
