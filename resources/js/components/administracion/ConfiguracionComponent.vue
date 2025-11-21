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
                <li class="nav-item">
                    <a class="nav-link" id="pills-stockMedio-tab" data-toggle="pill" href="#pills-stockMedio" role="tab" aria-controls="pills-stockMedio" aria-selected="false">Stock medio</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" id="pills-configStockMedio-tab" data-toggle="pill" href="#pills-configStockMedio" role="tab" aria-controls="pills-configStockMedio" aria-selected="false" @click="botonConfiguracionAutomatica()">Configuración gestion stock</a>
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
                          Esta configuración solo afecta a apectura y cierre de cajas donde, imprime despues del cierre automaticamente un ticket en la impresora termica con los datos de los traspasos electronicas. 
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
                
            <!----------------------------------------------RUBRO---------------------------------------------------------------->
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

            <!-------------------------------------------------STOCK MEDIO------------------------------------------------------------------->
            <div class="tab-pane fade" id="pills-stockMedio" role="tabpanel" aria-labelledby="pills-stockMedio-tab">                    
                    <div class="card">
                        <div class="card-header">Configuración de stock medio tipo tabla</div>
                        <div class="alert alert-warning" role="alert">
                         Esta configuración. Del uso de la tabla stock medio como preterminado ya que esta operacion afecta todo con lo dicho con gestion de stock.
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Tipo:</strong> 
                                </label>
                                <div class="col-md-3">
                                    <select v-if="puedeHacerOpciones_especiales===1" class="form-control"  v-model="selectStockMedio" >
                                        <option value="0" disabled selected>Seleccionar...</option>
                                        <option value="1">Stock medio 1</option>
                                        <option value="2">Stock medio 2</option>
                                    </select>
                                    <select v-else class="form-control">
                                        <option value="0" disabled selected>Sin permiso...</option>                                    
                                    </select>
                                 </div>
                                 <div class="col-md-1">
                                    <button v-if="parseInt(selectStockMedio) >= 1" type="button" class="btn btn-primary" @click="updateStockMedio()">Actulizar</button> 
                                    <button v-else type="button" class="btn btn-light">Actulizar</button> 
                                 </div>
                                 
                                <div class="col-md-6">
                                        <div v-show="stockMedio===0" class="alert alert-warning" role="alert">
                                           SIN ACTIVACION DE STOCK MEDIO, EL STOCK MEDIO 1 EN ACTIVACION POR DEFAULT
                                        </div>
                                        <div v-show="stockMedio===1" class="alert alert-primary" role="alert">
                                            STOCK MEDIO 1 ACTIVO
                                        </div>
                                        <div v-show="stockMedio===2" class="alert alert-primary" role="alert">
                                            STOCK MEDIO 2 ACTIVO
                                        </div>                                       
                                </div>       
                            </div>
                            <div class="form-group row">
                                <div v-show="selectStockMedio==='0'" class="alert alert-warning" role="alert">
                                           NO SE SELECCIONAR NINGUNA OPCION.
                                </div>
                                <div v-show="selectStockMedio==='1'" class="alert alert-primary" role="alert">
                                           EL STOCK MEDIO NUMERO 1, SU TABLA GUARDA DESDE EL INICIO HASTA EL FIN LAS VENTAS, A <strong>LARGO TIEMPO DE USO HACE LENTO EL SISTEMA POR LA CARGA DE GUARDADO DE DATOS. CARGA DE LOTES DE DATOS ENORMES Y REPETITIVOS PARA HACER EL PROMEDIO</strong>TIENE UNA MAYOR FIABILIDAD PERO HACE UNA ENORME CARDA DE LOTES DE DATOS A LARGO PLAZO SE TENDRA MILLONES DE DATOS QUE PONDRAN LENTO EL SISTEMA. 
                                </div>
                                <div v-show="selectStockMedio==='2'" class="alert alert-info" role="alert">
                                           EL STOCK MEDIO NUMERO 2, ES UNA TABLA MEJORADA DE LA TABLA 1 YA QUE ESTA GUARDA UN ITEM POR PRODUCTO Y NO LOTES DE DATOS COMO EL ANTERIOR STOCK MEDIO 1. <strong>LO MALO COMO GUARDA CON CONTADORES PARA HACER EL PROMEDIO, PUEDA QUE NO GUARDADE ALGUN DATO SI SE HACE UN USO MAL USO O FALLA HUMANA. </strong>ES OPTIMO PARA USO LARGO POR LA MENOR CARGA DE DATOS. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                
            <!-------------------------------------------------CONFIGURACION STOCK MEDIO------------------------------------------------------------------->
            <div class="tab-pane fade" id="pills-configStockMedio" role="tabpanel" aria-labelledby="pills-configStockMedio-tab"> 
                <div id="accordion" v-if="puedeHacerOpciones_especiales===1" >                  
                   
                    <div style="margin-top: 16px;">
                        <div class="card">
    <div class="card-header" id="headingUno1">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseUno1" aria-expanded="false" aria-controls="collapseUno1">
          Configuración de sucursal / Reloj
        </button>
      </h5>
    </div>

    <div id="collapseUno1" class="collapse" aria-labelledby="headingUno1" data-parent="#accordion">
      <div class="card-body">
        <div class="form-group row"> 
          
    <div class="col-md-2">
                    <label for="">Tipo surusal:</label>
                        <select v-model="selectSucursalGestionETC" class="form-control"  @change="cambioSelectETC()">
                            <option value="0" disabled selected>Seleccionar...</option>
                            <option value="1">Todo las sucursales</option>
                            <option value="2">Por sucursal en especifico</option>
                        </select>
                       
            </div> 
            <div class="col-md-4">                
                <div class="alert alert-primary" role="alert" v-if="selectSucursalGestionETC=='1'">
                    No necesita seleccionar mas sucursales.
                </div>
                <div v-else-if="selectSucursalGestionETC=='2'">
                    <label for="">Surusal:</label>
                    <div class="d-flex align-items-center gap-2">
  <select class="form-control" v-model="sucursalSeleccionada">
    <option value="0" disabled selected>Seleccionar...</option>
    <option
      v-for="(sucursal, index) in arraySucursal"
      :key="index"
      :value="sucursal.id_sucursal"
      :hidden="sucursal.id_almacen != null"
      v-text="'['+sucursal.id_sucursal+']' + ' -> ' + sucursal.codigo + ' ' + sucursal.razon_social"
    ></option>
  </select>

  <button type="button" class="btn btn-primary" :disabled="sucursalSeleccionada=='0'" @click="añadirETC()"><i class="fa fa-plus" aria-hidden="true"></i></button>
   <button type="button" class="btn btn-danger" :disabled="sucursalSeleccionada=='0'" @click="eliminarETC()"><i class="fa fa-minus" aria-hidden="true"></i></button>
</div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-primary" role="alert" v-if="selectSucursalGestionETC=='1'">
                    La configuración esta de tabla.
                </div>
                <div v-else-if="selectSucursalGestionETC=='2'">
                <table class="table table-bordered table-striped table-sm table-responsive">
                    <thead>
                        <tr>
                            <th class="col-md-4">Añadir (+)</th>
                            <th class="col-md-4">Eliminar (-)</th>
                            <th class="col-md-4">En Tabla</th>
                        </tr>
                        <tr>
                            <td class="col-md-4">{{arrayFAlasoETC}}</td>
                            <td class="col-md-4">{{arrayFAlasoEliETC}}</td>
                            <td class="col-md-4">
                                <span v-if="entabla_==null ||entabla_==''">Sin datos</span>
                                <span v-else>{{entabla_}}</span>
                            </td>
                        </tr>
                    </thead>
                </table>         
                </div>
            </div>                        
            

        </div>
         <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-2">Hora de sincronización</th>
                        <th class="col-md-2">Frecuencia para la sincronización</th>
                        <th class="col-md-2">Estado</th>                                              
                        <th class="col-md-2">Accion</th>                             
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="col-md-2"><input type="time" class="form-control" v-model="horaS"></td>
                            <td class="col-md-2">
                                <select  class="form-control"  v-model="selectFrecuencia"> 
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Ejecutar todos los días</option>
                                            <option value="2">Ejecutar el ultimo dia de la semana laboral</option>
                                            <option value="3">Ejecutar el último día del mes</option>
                                            <option value="4">Ejecutar cada trimestre el día 1</option>                                                                                                                          
                                </select>
                            </td>                      
                           
                            <td class="col-md-2">
                                <span class="badge badge-pill badge-success" v-if="estadoCogGesStock==1">Activo</span>
                                <span class="badge badge-pill badge-danger" v-else>Desactivado</span>
                            </td>
                            <td class="col-md-2">
                                <div>
                                <button type="button" class="btn btn-warning" style="color: white;"  v-if="(selectFrecuencia!='0'&&horaS!='')||selectSucursalGestionETC=='1'" @click="modificarConfiguracionGestionStock()">Actualziar</button>
                                <button type="button" class="btn btn-secondary" style="color: white;" v-else>Actualziar</button> 
                                </div>                                                
                            </td>
                        </tr>
                </tbody>
                </table> 
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingDos2">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseDos2" aria-expanded="false" aria-controls="collapseDos2">
          Distribuidor / Linea
        </button>
      </h5>
    </div>
    <div id="collapseDos2" class="collapse" aria-labelledby="headingDos2" data-parent="#accordion">
      <div class="card-body">                                   
            <div class="form-group row">
                <div class="col-md-3">
                      <label for="">Linea:</label>
                      <div class="d-flex align-items-center gap-2" >
                        <VueMultiselect
                        v-model="selecLinea"
                        :options="arrayLinea"
                        :disabled="disparador_2==1"
                        :max-height="120"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        
                        :custom-label="nameWithLang_2"                     
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
                    <button type="button" class="btn btn-primary" :disabled="selecLinea==null ||disparador_2==1" @click="listarDistribuidorETC(selecLinea.id)">
                        <i class="fa fa-share" aria-hidden="true"></i></button>
                  
                      </div>                 
                </div>
                
                <div class="col-md-6">
                    <div class="form-group" v-if="arrayDistriETC.length>0">
                        <label for=""> Lista distribuidor</label>    
                        <div class="d-flex align-items-center gap-2">
                        <select class="form-control"  v-model="selectDistriETC" :disabled="disparador_2==1">
                                        <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="(i, index) in arrayDistriETC" :key="index"  :value="i.id">
                                            {{"["+ i.id+"] Alias del distribuidor: "+i.alias+" Linea o lineas: "+i.nom_linea_array}}
                                    </option>                                       
                        </select>
                        <button type="button" class="btn btn-primary" :disabled="selectDistriETC=='0'" v-if="disparador_2==0" @click="disparadorETC_2(1)">Seleccionar</button>
                        <button type="button" class="btn btn-warning" :disabled="selectDistriETC=='0'" style="color: white;" v-else-if="disparador_2==1" @click="disparadorETC_2(2)">Deseleccionar</button>
                        </div>                      
                             
                    </div>
                    <div class="alert alert-warning" role="alert" v-else>
                        Debe seleccionar una linea.
                    </div>                 
                </div>               
            </div>
            <blockquote class="blockquote mb-0" v-show="disparador_2==1">
         <div class="row">
                                <div class="form-group col-sm-4">
                                    <span style="font-size: 12px;">Forma de pago:</span>
                                      <select v-model="selectFormaPago" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="a in array_forma_pago" :key="a.id" :value="a.id" v-text="a.tipo"></option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <span style="font-size: 12px;">Fecha de pago con intervalo:</span>                                
                                     <input type="number" class="form-control" placeholder="Con la fecha de creación se suma el inetrvalo"  v-model="fechaPago">
                               <span style="font-size: 12px;">Con la fecha de creacion se suma en dias el intervalo:</span>    
                                </div>
                                <div class="form-group col-sm-4" >
                                    <span style="font-size: 12px;">Plazo de pago:</span>
                                    <input type="text" class="form-control" placeholder=""  v-model="plazoPago">
                                
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="form-group col-sm-4">
                                    <span style="font-size: 12px;">Entrega de pedido:</span>
                                      <select v-model="selectEntregaPedido" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="a in array_entrega_pedido" :key="a.id" :value="a.id" v-text="a.tipo"></option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-4">
                                  <span style="font-size: 12px;">Observación:</span>
                                    <input type="text" class="form-control" placeholder=""  v-model="observacion">
                                </div>
                                <div class="form-group col-sm-4">
                                    <br>
                                    <button type="button" class="btn btn-primary" v-if="id_distribuidor_ETC==0" style="margin-right: 10px;" :disabled="observacion==''||selectFormaPago=='0'||fechaPago==''||plazoPago==''||selectEntregaPedido=='0'" @click="crearDistribuidorGestionAutomatica()">Crear</button>
                                    <button type="button" class="btn btn-warning" v-else style="margin-right: 10px;" :disabled="observacion==''||selectFormaPago=='0'||fechaPago==''||plazoPago==''||selectEntregaPedido=='0'" @click="editarDistribuidorGestionAutomatica()">Editar</button>
                                    <button type="button" class="btn btn-secondary" style="color: white;" @click="limpiarConfigGesStock_2()">Limpiar</button>
                                </div>                               
                            </div>
    </blockquote>
                         
     </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTres3">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTres3" aria-expanded="false" aria-controls="collapseTres3" @click="listarDistribuidorAutomatico_2()">
          Diferenciador por ventas 
        </button>
      </h5>
    </div>
    <div id="collapseTres3" class="collapse" aria-labelledby="headingTres3" data-parent="#accordion">
      <div class="card-body">
         <div class="form-group row" style="margin-top: 15px;">
                <div class="col-md-6">
                      <label for="">Distribuidor:</label>                      
                        <VueMultiselect
                        v-model="selecDistribui_3"
                        :options="arrayDistribui_3"                        
                        :max-height="180"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                       :custom-label="nameWithLang_3"    
                        :disabled="bloqueadorDistribui_3==1"                  
                        track-by="id"
                        class="w-260"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect>             
                </div>
                <div class="col-md-2" style="margin-top: 28px;">
                    <button type="button" v-if="bloqueadorDistribui_3==0" class="btn btn-primary" @click="mostrar_3_distri(selecDistribui_3.ciclo_2,selecDistribui_3.lim_inferior,selecDistribui_3.lim_superior,1)">Seleccionar</button>
                    <button type="button" v-else class="btn btn-warning" @click="mostrar_3_distri(0,0,0,0)">Deseleccionar</button>                       
                </div>
                
        </div>  
            <div class="row"  style="margin-top: 15px;" >
  <div class="col-sm-6" v-show="bloqueadorDistribui_3==1">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Por unidades o cantidad vendida</h5>
             <label for="">Esta función es opcinal no es necesario que este activo.<strong> La función se regula segun el consumo y la cantidad de venta del producto lo cual es cantidad_ingreso_tienda + Traspaso, ya que el traspaso es el indicador de ingreso de cantidad, para hacer el calculo segun:</strong></label> 
     <span class="badge badge-pill badge-danger" style="margin-right: 5px; color: white;">Consumo muy bajo entre el intervalo de  0% - 10%</span>
        <span class="badge badge-pill badge-danger" style="margin-right: 5px; color: white;">Consumo bajo entre el intervalo de  10% - 25%</span>
        <span class="badge badge-pill badge-warning" style="margin-right: 5px; color: white;">Consumo moderado entre el intervalo de  25% - 50%</span>
        <span class="badge badge-pill badge-warning" style="margin-right: 5px; color: white;">Consumo normal entre el intervalo de  50% - 59%</span>
        <span class="badge badge-pill badge-warning" style="margin-right: 5px; color: white;">Consumo alto entre el intervalo de  60% - 75%</span>
        <span class="badge badge-pill badge-success" style="margin-right: 5px; color: white;">Consumo muy alto entre el intervalo de  75% - 90%</span>
        <span class="badge badge-pill badge-success" style="margin-right: 5px; color: white;">Consumo óptimo entre el intervalo de  90% - 100%</span>
        <div class="row"  style="margin-top: 15px;">
<div class="col-md-6">
                    <label for="">Ciclo:</label>
                    <select v-model="selectCico_3" class="form-control">
                        <option value="0" disabled>Seleccionar...</option>
                        <option value="7">Una semana</option>
                        <option value="30">Un mes</option>
                        <option value="90">Tres meses</option>
                        <option value="180">Seis meses</option>
                        <option value="360">Doce meses</option>
                    </select>
                </div>
        </div>     
        
                
        <a href="#" class="btn btn-primary" style="margin-top: 15px;" @click="realizarOperacion_3(1,selecDistribui_3.id)">Realziar operación</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6" v-show="bloqueadorDistribui_3==1">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Diferencia por monto monetario</h5>
          <label for="">Esta función es opcinal no es necesario que este activo.<strong> La función se regula segun rango de valores enteros ejemplo 1 a 10 mes decir tomara el valor entre esos rangos donde el rago inferior siempre debe ser menor al superior, donde los valores ingresados paramétriza el valor del producto, añade segun el costo y añade a la lista</strong></label> 
           <div class="row">
                <div class="form-group col-sm-6">
                    <span>Limite inferiror:</span>
                    <input type="number" class="form-control" placeholder="" v-model="limiteInferior">
                </div>
                <div class="form-group col-sm-6">
                    <span>Limite Superiror:</span>
                    <input type="number" class="form-control" placeholder="" v-model="limiteSuperior">
                </div>
            </div>       
        <a href="#" class="btn btn-primary" @click="realizarOperacion_3(2,selecDistribui_3.id)">Realizar operación</a>
      </div>
    </div>
  </div>
</div>
           
            
    </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingCuatro2">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseCuatro2" aria-expanded="false" aria-controls="collapseCuatro2">
          Operaciones de activación
        </button>
      </h5>
    </div>
    <div id="collapseCuatro2" class="collapse" aria-labelledby="headingCuatro2" data-parent="#accordion">
      <div class="card-body">
         <table class="table table-bordered table-striped table-sm table-responsive">
    <thead>
        <tr>
            <th class="col-md-1 text-center">Activar</th>
            <th class="col-md-1 text-center">Desactivar</th>
            <th class="col-md-10">Modulo</th>
        </tr>
    </thead>
    <tbody>

        <tr>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno" :value="1" v-model="valueRadio_1" @click="modificarActivadoresConfiguracionGestionStock(1)">
            </td>
            <td class="text-center">
             <input class="form-check-input" type="radio" name="uno" :value="0" v-model="valueRadio_1" @click="modificarActivadoresConfiguracionGestionStock(1)">
            </td>
            <td>Modulo Configuración de sucursal / Reloj</td>
        </tr>

        <tr>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno2" :value="1" v-model="valueRadio_2" @click="modificarActivadoresConfiguracionGestionStock(2)">
            </td>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno2" :value="0" v-model="valueRadio_2" @click="modificarActivadoresConfiguracionGestionStock(2)">
            </td>
            <td>Modulo por unidades o cantidad vendida</td>
        </tr>

        <tr>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno3" :value="1" v-model="valueRadio_3" @click="modificarActivadoresConfiguracionGestionStock(3)">
            </td>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno3" :value="0" v-model="valueRadio_3" @click="modificarActivadoresConfiguracionGestionStock(3)">
            </td>
            <td>Diferencia por monto monetario</td>
        </tr>

        <tr>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno4" :value="1" v-model="valueRadio_4" @click="modificarActivadoresConfiguracionGestionStock(4)">
            </td>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno4" :value="0" v-model="valueRadio_4" @click="modificarActivadoresConfiguracionGestionStock(4)">
            </td>
            <td>Metodo ABC</td>
        </tr>
        <tr>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno5" :value="1" v-model="valueRadio_5" @click="modificarActivadoresConfiguracionGestionStock(5)">
            </td>
            <td class="text-center">
                <input class="form-check-input" type="radio" name="uno5" :value="0" v-model="valueRadio_5" @click="modificarActivadoresConfiguracionGestionStock(5)">
            </td>
            <td>Por canal</td>
        </tr>

    </tbody>
</table>
                         
               
        </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingCinco2">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseCinco2" aria-expanded="false" aria-controls="collapseCinco2" @click="listarDistribuidorAutomatico_2()">
        Listar / Eliminar
        </button>
      </h5>
    </div>
    <div id="collapseCinco2" class="collapse" aria-labelledby="headingCinco2" data-parent="#accordion">
      <div class="card-body">
        <div class="form-group row">              
                        
                        <div class="col-md-3">                          
                            <input type="text" id="texto" name="texto" class="form-control" v-model="inputPestaña_5" placeholder="Escriba segun lo que quiere buscar."/>                                                          
                        </div>
                        <div class="col-md-6">
                            <div v-if="inputPestaña_5==''">
                                <button type="submit" class="btn btn-secondary" style="margin-right: 5px;"><i class="fa fa-search"></i> Buscar x linea</button>
                                <button type="submit" class="btn btn-secondary" style="margin-right: 5px;"><i class="fa fa-search" aria-hidden="true"></i> Buscar x distribuidor</button>
                                <button type="submit" class="btn btn-secondary" ><i class="fa fa-search" aria-hidden="true"></i> Buscar x formato pago</button>                               
                            </div> 
                            <div v-else>
                                <button type="submit" class="btn btn-primary" style="margin-right: 5px;" @click="buscarInputPestaña_5(1,inputPestaña_5)"><i class="fa fa-search"></i> Buscar x linea</button>
                                <button type="submit" class="btn btn-primary" style="margin-right: 5px;" @click="buscarInputPestaña_5(2,inputPestaña_5)"><i class="fa fa-search" aria-hidden="true"></i> Buscar x distribuidor</button>
                                <button type="submit" class="btn btn-primary"  @click="buscarInputPestaña_5(3,inputPestaña_5)"><i class="fa fa-search" aria-hidden="true"></i> Buscar x formato pago</button>                                
                            </div>                                 
                        </div> 
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-warning" style="color: white;" @click="listarDistribuidorAutomatico_2()"><i class="fa fa-list-ul" aria-hidden="true"></i> Volvera mostrar todo</button>   
                        </div>                                             
            </div>
            <div style="max-height:300px; overflow-y:auto; overflow-x:hidden;">
    <table class="table table-bordered table-striped table-sm table-responsive">
        <thead>
            <tr>
                <th>Opción</th>
                <th>Linea</th>
                <th>Distribuidor</th>
                <th>Lineas a sociadas</th>
                <th>Forma de pago</th>
                <th>Intervalo de pago</th>
                <th>Plazo de pago</th>
                <th>Tiempo de entrega</th>
                <th>Observación</th>
                <th>Op. ciclo</th>
                <th>Op. limites</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(i, index) in arrayDistribui_3" :key="index">
                <td>
                    <button type="button" class="btn btn-danger" @click="eliminarPestañaGestionAutomatica_5(i.id)">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </td>
                <td>{{i.nombre_linea}}</td>
                <td>{{i.alias}}</td>
                <td>{{i.nom_linea_array}}</td>
                <td>{{i.forma_pago_nombre}}</td>
                <td>{{"Mas "+i.intervalo_pago+", a la fecha"}}</td>
                <td>{{i.Plazo_pago}}</td>
                <td>{{i.entrega_pedido_nombre}}</td>
                <td>{{i.observacion}}</td>
                <td>{{i.ciclo_2_nombre}}</td>
                <td>{{"Lim.Inf: "+i.lim_inferior+" Lim.Sup: "+i.lim_superior}}</td>
            </tr>
        </tbody>
    </table>
</div>
        
       </div>
    </div>
  </div>
                    </div>   
                </div>    
<div class="alert alert-warning" role="alert" v-else>
  No tiene permiso.
</div>             
            </div>
            <!-------------------------------------------------------------------------------------------------------------------------------->

                </div>
            
        </div>
    </div>
</div>
  <!-- modal registro bancos -->
  <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">BANCO</h4>
                        
                        <button type="button" class="close" @click="cerrarModal('regbanco')">
                            <span>&times;</span>
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
                </div>
            </div>
    </transition>                    

         
        <!--fin del modal-->
          <!-- modal registro cuenta -->
          <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">CUENTA</h4>
                        <button type="button" class="close" @click="cerrarModal('regcuenta')">
                            <span>&times;</span>
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
                </div>
            </div>
        </transition>                

    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import Multiselect from 'vue-multiselect'
import { toInteger } from "lodash";
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
                //-----------------
                showModal: false,              
                showModal_2: false,
                //stockmedio------
                selectStockMedio:'0',
                stockMedio:0,

                //LOGICA CONFIGURACION GESTION STOCK----  
                selectSucursalGestionETC:'0',
                arraySucursal:[],
                sucursalSeleccionada:'0',
                arrayFAlasoETC:[],
                arrayFAlasoEliETC:[],
                cambioActivarDesa:0,

                selecLinea:null,
                arrayLinea:[],

                arrayDistriETC:[],
                selectDistriETC:'0',
                arrayDisXLineasFalso:[],
            
                disparador_2:0,

                  selectFormaPago:'0', 
                     array_forma_pago: [{ id: 1, tipo: "CHEQUE" },{ id: 2, tipo: "CONTADO" },{ id: 3, tipo: "CREDITO" },{ id: 4, tipo: "TRASFERENCIA BANCARIA" }],
    fechaPago:'',
    plazoPago:'',
    selectEntregaPedido:'0',      
    array_entrega_pedido: [{ id: 1, tipo: "MAÑANA" },{ id: 2, tipo: "TARDE" }],
    observacion:'',

    selectFrecuencia:'0',
    horaS:'',    
         
            selecDistribui_3:null,
            arrayDistribui_3:[],
            selectCico_3:'0',
            entabla_:'',
            estadoCogGesStock:'',
            valueRadio_1:'',
            valueRadio_2:'',
            valueRadio_3:'',
            valueRadio_4:'',
            valueRadio_5:'',
            id_distribuidor_ETC:0,
            limiteInferior:0,
            limiteSuperior:0,
            bloqueadorDistribui_3:0,    
            inputPestaña_5:'',  


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
 //-------------------LOGICA CONFIGURACION GESTION STOCK------------------------------------    
        limpiarConfigGesStock_2(){
            let me=this;
            me.observacion="";
            me.selectEntregaPedido="0";
            me.plazoPago="";
            me.fechaPago="";
            me.selectFormaPago="0";
            me.disparador_2=0;
            me.selectDistriETC="0";
            me.selecLinea=null;
        },

        botonConfiguracionAutomatica(){            
            let me=this;
            me.arraySucursal=[];
            me.arrayFAlasoETC=[];
            me.arrayFAlasoEliETC=[],
            me.sucursalFiltro();
            me.listarLiena();
            me.listarConfigGestionStock();
            me.arrayLinea=[];
            me.selecLinea=null;
            me.arrayDisXLineasFalso=[];
            me.selectDistriETC="0";
            me.disparador_2=0;
            me.selectFormaPago="0";           
            me.fechaPago="";
            me.plazoPago="";
            me.selectEntregaPedido="0";     
          
            me.selectFormaPago="0"; 
            me.fechaPago="";
            me.plazoPago="";
            me.selectEntregaPedido="0";      
            me.observacion="Sin datos.";

            me.selectCico_3="0";
            me.limiteInferior=0;
            me.limiteSuperior=0; 
           
            
            me.selecDistribui_3=null;
            me.arrayDistribui_3=[];
            me.selectCico_3="0";
          me.inputPestaña_5="";
        },

        editarDistribuidorGestionAutomatica(){
            let me = this;
                axios.put("/credenciales_correo/editarDisGesAut", {
                    id:me.id_distribuidor_ETC,
                    formaPago:me.selectFormaPago,
                    fechaPago:me.fechaPago,
                    plazoPago:me.plazoPago,
                    pedidoEntre:me.selectEntregaPedido,
                    observacion:me.observacion             
                }).then(function (response) {          
                    let respuesta=response.data;  
                    if (respuesta==0) {
                        me.selecLinea=null;
                            me.selectDistriETC='0';
                            me.selectFormaPago='0';
                            me.fechaPago='';
                            me.plazoPago='';
                            me.pedidoEntre='';
                            me.observacion='';
                            me.disparador_2=0;
                            me.id_distribuidor_ETC=0;
                     Swal.fire("Edición exitosamente","Haga click en Ok", "success",);   
                    } else {
                      Swal.fire("Error: "+respuesta,"Haga click en Ok", "error",);   
                    }  
                    })                
                  .catch(function (error) { 
                    error401(error);                        
            });
        },

        buscarInputPestaña_5(data,buscar){
            let me=this;
            let arrayVerdadero=me.arrayDistribui_3;
            let resultado=[];
            switch (data) {
                case 1:
                resultado = arrayVerdadero.filter(i =>
    i.nombre_linea.toLowerCase().includes(buscar.toLowerCase()));
                break;
            
                case 2:
                     resultado = arrayVerdadero.filter(i =>
    i.alias.toLowerCase().includes(buscar.toLowerCase()));
                break;

                case 3:
                    resultado = arrayVerdadero.filter(i =>
    i.forma_pago_nombre.toLowerCase().includes(buscar.toLowerCase()));
                break;

                default:
                resultado=[];
                break;                
            }
            me.arrayDistribui_3=resultado;
            me.inputPestaña_5="";
        },

        realizarOperacion_3(data,id){
            let me=this;            
            axios.put("/credenciales_correo/editarDiferenciaVentas_3", {                   
                    data:data,
                    id:id,
                    ciclo:me.selectCico_3,
                    limiteInferior:me.limiteInferior,
                    limiteSuperior:me.limiteSuperior
                }).then(function (response) {
                    let respuesta=response.data;
                    if (respuesta==0) {
                         
                      Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);    
                    }else{
                     Swal.fire("Error:"+respuesta,"Haga click en Ok", "error",);    
                    }
                  
                   
                })
                .catch(function (error) {                    
                    error401(error);
                });  
        },


eliminarPestañaGestionAutomatica_5(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta Seguro de eliminar?',
                text: "Si elimina sera permanente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Eliminar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/credenciales_correo/eliminarDisGesAut_3',{
                        id: id
                    }).then(function (response) {
                    me.listarDistribuidorAutomatico_2();
                        swalWithBootstrapButtons.fire(
                            'Se elimino!',
                            'El registro exitosamente.',
                            'success'
                        ) 
                    }).catch(function (error) {
                        error401(error);
                    });
                } else if (
        
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                 
                }
                })
            },

         crearDistribuidorGestionAutomatica(){
                let me = this;
                axios.post("/credenciales_correo/crearDisGesAut", {
                    id_linea:me.selecLinea.id,
                    id_distribuidor:me.selectDistriETC,
                    formaPago:me.selectFormaPago,
                    fechaPago:me.fechaPago,
                    plazoPago:me.plazoPago,
                    pedidoEntre:me.selectEntregaPedido,
                    observacion:me.observacion             
                }).then(function (response) {          
                    let respuesta=response.data;
                    let valor=respuesta.valor;
                    console.log(valor);
                      if (valor==1) {
                       Swal.fire({
                        title: "El distribuidor seleccionado ya existe",
                        text: "¿Decea editarlo para cambiar la información?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#626970",
                        confirmButtonText: "Editar, SI"
                        }).then((result) => {                          
                            me.selectFormaPago=respuesta.forma_pago;
                            me.fechaPago=respuesta.intervalo_pago;
                            me.plazoPago=respuesta.Plazo_pago;
                            me.pedidoEntre=respuesta.entrega_pedid;
                            me.observacion=respuesta.observacion;
                            me.id_distribuidor_ETC=respuesta.id;                                            
                    });
                      } else {
                        if (valor==0) {
                            me.selecLinea=null;
                            me.selectDistriETC='0';
                            me.selectFormaPago='0';
                            me.fechaPago='';
                            me.plazoPago='';
                            me.pedidoEntre='';
                            me.observacion='';
                            me.disparador_2=0;
                            me.id_distribuidor_ETC=0;
                             Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);                                         
                        } else {
                            me.selecLinea=null;
                            me.selectDistriETC='0';
                            me.selectFormaPago='0';
                            me.fechaPago='';
                            me.plazoPago='';
                            me.pedidoEntre='';
                            me.observacion='';
                            me.disparador_2=0;
                            me.id_distribuidor_ETC=0;
                        Swal.fire("Error."+respuesta,"Haga click en Ok", "error",);                                                                            
                        }
                      }       
                       
                    })                
                  .catch(function (error) { 
                    error401(error);                        
            }); 
            },
       
        añadirETC(){
            let me=this;
            let entero= parseInt(me.sucursalSeleccionada,10);
            console.log(entero);
            if (entero!=0) {
            const index = me.arrayFAlasoETC.findIndex(f => f === entero);
              if(index !== -1) {
                console.log("---1");
                me.arrayFAlasoETC.splice(index, 1); // eliminar  
            } else {
                console.log("---2");
            me.arrayFAlasoETC.push(entero);
            }                
            }
        },

        modificarActivadoresConfiguracionGestionStock(data){
            let me = this;  
            let tipo="";
            switch (data) {
                case 1:                 
                 if (me.valueRadio_1==1) {
                    tipo=0;
                 }else{
                    tipo=1;
                 }   
                break;
                case 2:                 
                 if (me.valueRadio_2==1) {
                    tipo=0;
                 }else{
                    tipo=1;
                 }   
                break;
                case 3:                 
                 if (me.valueRadio_3==1) {
                    tipo=0;
                 }else{
                    tipo=1;
                 }   
                break;
                case 4:                 
                 if (me.valueRadio_4==1) {
                    tipo=0;
                 }else{
                    tipo=1;
                 }   
                break;
                case 5:                 
                 if (me.valueRadio_5==1) {
                    tipo=0;
                 }else{
                    tipo=1;
                 }   
                break;
                default:
                    break;
            }
             axios.put("/credenciales_correo/activar_panel_automatico", {
                    tipo:tipo,
                    data:data                   
                }).then(function (response) {
                   me.listarConfigGestionStock();
                })
                .catch(function (error) {                    
                    error401(error);
                });            
        },

          modificarConfiguracionGestionStock(){
            let me = this;
           
                if (me.selectSucursalGestionETC==1) {
                    me.entabla_="";
                }else{
                    if (me.arrayFAlasoETC.length>0) {
                  
                        let arrCadena =  me.entabla_.split(",").map(Number);
                        // unir ambos arrays
                        let combinado = [...arrCadena, ...me.arrayFAlasoETC];
                        // eliminar repetidos
                        let unicos = [...new Set(combinado)];
                         let resultado_1 = unicos.filter(elemento => elemento !== 0);
                        // convertir otra vez a string
                        let resultado_2 = resultado_1.join(",");
                        me.entabla_=resultado_2;                                      
                    }
                    if (me.arrayFAlasoEliETC.length > 0) {
              
    // convertir la cadena a array de números
    let arrCadena = me.entabla_.split(",").map(Number);
    let A = me.arrayFAlasoEliETC;
    let B = arrCadena;
    // convertir a Set para optimizar búsqueda
    let setA = new Set(A);
    let setB = new Set(B);
    // elementos únicos de A
    let soloA = A.filter(x => !setB.has(x));
    // elementos únicos de Bs
    let soloB = B.filter(x => !setA.has(x));
    // unir los que no están repetidos
    let combinado = [...soloA, ...soloB];
    let resultado = combinado.filter(elemento => elemento !== 0);
    // convertir a string
    me.entabla_ = resultado.join(",");
}                   
                }     
                
                axios
                .post("/credenciales_correo/configuracionGestionStoc", {
                    tipoSucursal:me.selectSucursalGestionETC,
                    entabla:me.entabla_,
                    hora:me.horaS,
                    frecuencia:me.selectFrecuencia,                              

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"modifica configuracionGestionStoc",  
                  
                })
                .then(function (response) {
                   let respuesta = response.data;
                   me.arrayFAlasoEliETC=[];
                   me.arrayFAlasoETC=[];
                   me.sucursalSeleccionada="0";

                    console.log(respuesta);
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                .catch(function (error) {
                error401(error);
                });
            
        },



         eliminarETC(){
            let me=this;
            let entero= parseInt(me.sucursalSeleccionada,10);
            console.log(entero);
            if (entero!=0) {
            const index = me.arrayFAlasoEliETC.findIndex(f => f === entero);
              if(index !== -1) {
                console.log("---1");
                me.arrayFAlasoEliETC.splice(index, 1); // eliminar  
            } else {
                console.log("---2");
            me.arrayFAlasoEliETC.push(entero);
            }                
            }
        },

        añadirETC_2(){
            let me=this;
            let entero= parseInt(me.selectDistriETC,10);           
            if (entero!=0) {
            const index = me.arrayDisXLineasFalso.findIndex(f => f === entero);
            if(index !== -1) {             
            me.arrayDisXLineasFalso.splice(index, 1); // eliminar  
            } else {              
            me.arrayDisXLineasFalso.push(entero);
            }                
            }
        },        

        disparadorETC_2(data){
            let me=this;
             let id_linea=0;
            let id_distribuidor=0;
            switch (data) {
                case 1:
                  me.disparador_2=1; 
             id_linea=me.selecLinea.id;
             id_distribuidor=me.selectDistriETC;  
                break;
                case 2:
                  me.disparador_2=0; 
             id_linea=0;
             id_distribuidor=0;  
                break;
            
                default:
                    me.disparador_2=0;
                id_linea=0;
             id_distribuidor=0;
                    break;
            }
            
            
        },

        cambioSelectETC(){
            this.sucursalSeleccionada="0";
            this.arrayFAlasoETC=[];
            this.arrayFAlasoEliETC=[];
        },

        mostrar_3_distri(ciclo,inf,sup,data){
          let me=this;
          if (data==1) {
            me.bloqueadorDistribui_3=1;
         
          } else {
            me.bloqueadorDistribui_3=0;
          
          }
          me.selectCico_3=ciclo;
            me.limiteInferior=inf;
            me.limiteSuperior=sup; 
             
        },

listarDistribuidorAutomatico_2() {
            let me = this;
           me.selecDistribui_3=null;
            me.arrayDistribui_3=[];
            me.inputPestaña_5="";
           var url = "/credenciales_correo/listarDistribuidorAutomatico";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;  
                    me.arrayDistribui_3=respuesta;   
                    console.log(respuesta);                 
                })
                .catch(function (error) {
                    error401(error);
                });
        },


        listarDistribuidorETC(data) {
            let me = this;
            me.arrayDistriETC=[];
            me.selectDistriETC="0";
           var url = "/listarDistribuidorXlinea?id_linea="+data;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;  
                    me.arrayDistriETC=respuesta;                 
                    console.log(respuesta);                 
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarLiena() {
            let me = this;
            var url = "/distribuidor/getLinea";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayLinea=respuesta;
                    console.log(respuesta);                 
                })
                .catch(function (error) {
                    error401(error);       
                });
        },

        listarConfigGestionStock() {
            let me = this;
            var url = "/listarConfigAdminGestionAutomatico";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;    
                    if (respuesta.length>0) {
                        me.selectSucursalGestionETC=respuesta[0].tipo_sucursal;
                        me.entabla_=respuesta[0].id_sucursales;
                        me.horaS=respuesta[0].hora;
                        me.selectFrecuencia=respuesta[0].frecuencia;
                        me.estadoCogGesStock=respuesta[0].activo_c_r_1;
                        me.valueRadio_1=respuesta[0].activo_c_r_1;
                        me.valueRadio_2=respuesta[0].activo_d_l_2;
                        me.valueRadio_3=respuesta[0].activo_d_m_m_3;
                        me.valueRadio_4=respuesta[0].activo_m_abc_4;
                        me.valueRadio_5=respuesta[0].activo_canal_5;
                        
                       
                    } else {
                       Swal.fire("No existe la tabla!","Tabla eliminada o duplicada con indicador id distinto a uno","error",); 
                    }            
                    console.log(respuesta);                 
                })
                .catch(function (error) {
                    error401(error);       
                });
        },

         sucursalFiltro() {
            let me = this;
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;
                    console.log(me.arraySucursal);                 
                })
                .catch(function (error) {
                    error401(error);       
                });
        },
        //------------------------------------------------------------------------

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
            
            return `${name} - (${nom_completo}) `
          },

          nameWithLang_2 ({codigo, nombre,}) {            
            return `${codigo} (${nombre}) `
          },

           nameWithLang_3 ({alias, nombre_linea,nom_linea_array}) {            
            return `Distribuidor: ${alias} (${nombre_linea}) Otras lineas: ${nom_linea_array}`
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


 updateStockMedio(){
            let me = this;           
                axios
                .post("/credenciales_correo/stock_medio", {
                    id: me.id_credencial,                   
                    stock_medio:me.selectStockMedio,                 

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"cambio de stock medio a selecto numero: "+me.selectStockMedio,  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                    let respuesta=response.data;
                    console.log(respuesta);
                    if (respuesta.length>0) {                     
                    Swal.fire(
                        "Oops...",
                        "Error en contrado: "+respuesta,
                        "error",
                    );
                    }else{
                          Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    ); 
                    }
                    
                })
                .catch(function (error) {
                    error401(error);
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
                    console.log(respuesta);
                    console.log("******");
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

                    me.stockMedio=response.data[0].stock_medio;
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
                    me.showModal = true;
                    me.nombrebanco_0= (data.nombre).toUpperCase();
                    me.id_banco=data.id;
                    me.classModal.openModal("regbanco");
                    break;
                }  
                case "regcuenta": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
                    me.showModal_2 = true;
                    me.titular_cuenta="";
                    me.num_cuenta="";
                    me.nom_cuenta="";
                    me.selectBanco_cuenta=0;
                    me.classModal.openModal("regcuenta");
                    break;
                }
                case "regcuenta_edit":{
                    me.tipoAccion = 2;
                    me.showModal_2 = true;
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
                me.showModal = false;
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
                me.showModal_2 = false;
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
