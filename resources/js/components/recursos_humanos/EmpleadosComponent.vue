<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Empleados
                    <button  v-if="puedeCrear==1" type="button" class="btn btn-secondary rounded" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control rounded" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarEmpleados(1)">
                                <button type="submit" class="btn btn-primary rounded" @click="listarEmpleados(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-2">Nombre</th>
                                <th class="col-md-1">Cargo</th>
                                <th class="col-md-1">sexo</th>
                                <th class="col-md-1">CI</th>
                                <th class="col-md-1">Telefonos</th>
                                <th class="col-md-1">Fecha Nacimiento</th>
                                <th class="col-md-1">Estado Civil</th>
                                <th class="col-md-1">Domicilio</th>
                                <th class="col-md-2">Nº Cuenta</th>
                                <th >Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="empleado in arrayEmpleados" :key="empleado.id">
                                <td class="col-md-1">
                                   
                                    <div v-if="empleado.nombre!='ADMIN'" class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm rounded" @click="abrirModal('actualizar',empleado)" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div  v-else>
                                            <button type="button" class="btn btn-light btn-sm rounded" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="empleado.activo==1" type="button" class="btn btn-danger btn-sm rounded" @click="eliminarempleado(empleado.id)" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                        </button> 
                                        <button v-else type="button" class="btn btn-info btn-sm rounded" @click="activarempleado(empleado.id)" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                        </button>                                        
                                        </div>
                                        <div  v-else>
                                            <button v-if="empleado.activo==1" type="button" class="btn btn-light btn-sm rounded"  style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                        </button> 
                                        <button v-else type="button" class="btn btn-light btn-sm rounded"  style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                        </button>
                                        </div>
                                        <img v-if="empleado.foto" :src="'storage/'+ empleado.foto.substring(10)" class="rounded-circle fotosociomini" >
                                        <img v-else src="img/avatars/persona.png"  class="rounded-circle fotosociomini" >
                                    </div>
                                    
                                </td>
                                    <td v-text="empleado.nomempleado" class="col-md-2"></td>
                                    <td v-text="empleado.nomcargo" class="col-md-1"></td>
                                    <td v-text="empleado.sexo" class="col-md-1"></td>
                                    <td v-text="empleado.ci" class="col-md-1"></td>
                                    <td v-text="empleado.telefonos" class="col-md-1"></td>
                                    <td v-text="empleado.fechanacimiento" class="col-md-1"></td>
                                    <td v-text="empleado.estadocivil" class="col-md-1"></td>
                                    <td v-text="empleado.direccion" class="col-md-1"></td>
                                    <td v-text="empleado.nrcuenta + ' '+empleado.nombanco" class="col-md-2"></td>
                                <td >
                                    <div v-if="empleado.activo==1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning">Desactivado</span>
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
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
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
                        <div class="modal-body" style="background-color: whitesmoke">
                        <form @submit.prevent="registrarempleado || actualizarempleado" enctype="multipart/form-data" >

                       
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-personal-tab" data-toggle="pill" href="#pills-personal" role="tab" aria-controls="pills-home" aria-selected="true">Datos Personales</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-contacto-tab" data-toggle="pill" href="#pills-contacto" role="tab" aria-controls="pills-profile" aria-selected="false">Contacto</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-profesional-tab" data-toggle="pill" href="#pills-profesional" role="tab" aria-controls="pills-contact" aria-selected="false">Datos Profesionales</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-bancaria-tab" data-toggle="pill" href="#pills-bancaria" role="tab" aria-controls="pills-contact" aria-selected="false">Datos Bancarios</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-personal" role="tabpanel" aria-labelledby="pills-personal-tab">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Primer Apellido:</label>
                                        <input type="text" id="papellido" name="papellido" class="form-control rounded" placeholder="Primer Apellido" v-model="papellido" v-on:focus="selectAll" >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Segundo Apellido:</label>
                                        <input type="text" id="sapellido" name="sapellido" class="form-control rounded" placeholder="Segundo Apellido" v-model="sapellido" v-on:focus="selectAll" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Nombres:<span  v-if="nombre==''" class="error">(*)</span></label>
                                        <input type="text" id="nombre" name="nombre" class="form-control rounded" placeholder="Nombres" v-model="nombre" v-on:focus="selectAll" >
                                        <span  v-if="nombre==''" class="error">Debe Ingresar el Nombre del empleado</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>CI:<span  v-if="ci==''" class="error">(*)</span></label>
                                        <input type="number" min="0" id="ci" name="ci" class="form-control rounded" placeholder="CI" v-model="ci" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                        <span  v-if="ci==''" class="error">Debe Ingresar el CI del empleado</span>
                                        <small style="color:darkmagenta" v-if="mensajeError != ''" class="error">{{ mensajeError }}</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Complemento: <small class="text-muted">Opcional</small></label>
                                        <input type="text" id="complemento" name="complemento" class="form-control rounded" placeholder="Complemento" v-model="complemento" v-on:focus="selectAll" >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Expedido en: <small class="text-muted">Departamento</small> <span  v-if="deptoselected==0" class="error">(*)</span></label>
                                        <select v-model="deptoselected" class="form-control rounded">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option v-for="depto in arrayDepto" :key="depto.id" :value="depto.id" v-text="depto.nombre"></option>
                                        </select>
                                        <span  v-if="deptoselected==0" class="error">Debe Ingresar departamento </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Fecha Nacimiento: <span  v-if="fechanacimiento==''" class="error">(*)</span></label>
                                        <input type="date" id="fechanacimiento" name="fechanacimiento" class="form-control rounded" v-model="fechanacimiento">
                                        <span  v-if="fechanacimiento==''" class="error">Debe Ingresar la fecha de nacimiento</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Nacionalidad:</label>
                                        <select v-model="nacionselected" class="form-control rounded">
                                            <option v-for="nacion in arrayNacion" :key="nacion.id" :value="nacion.id" v-text="nacion.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Estado Civil: <span  v-if="estadocivil==0" class="error">(*)</span></label>
                                        <select v-model="estadocivil" class="form-control rounded">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option v-for="estado in arrayEstadoCivil" :key="estado" :value="estado" v-text="estado"></option>
                                        </select>
                                        <span class="error" v-if="estadocivil==0">Debe Seleccionar el Estado Civil</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Sexo: <span  v-if="sexo==0" class="error">(*)</span></label>
                                        <select v-model="sexo" class="form-control rounded">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option v-for="sex in arraySexo" :key="sex.id" :value="sex.id" v-text="sex.texto"></option>
                                        </select>
                                        <span class="error" v-if="sexo==0">Debe Seleccionar el Sexo</span>
                                    </div>
                                </div>
                                <div class="form-group" v-if="clearInputFile">
                                    <label for="">Fotografia:&nbsp; &nbsp;</label>
                                    <input class="form-control rounded" type="file" @change="subirfoto" :v-model="foto" accept="image/*" id="img-empleado">
                                    
                                </div>
                                <figure>
                                    <img width="100" height="100" :src="imagen" alt="">
                                </figure>


                            </div>
                            <div class="tab-pane fade" id="pills-contacto" role="tabpanel" aria-labelledby="pills-contacto-tab">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Direccion Domicilio:<span  v-if="domicilio==''" class="error">(*)</span></label>
                                        <input type="text" id="domicilio" name="domicilio" class="form-control rounded" placeholder="Domicilio" v-model="domicilio" v-on:focus="selectAll" >
                                        <span  v-if="domicilio==''" class="error">Debe Ingresar el Domicilio del empleado</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Ciudad:<span  v-if="ciudadselected==0" class="error">(*)</span></label>
                                        <div class="row">
                                            <div class="form-group col-sm-10" style="padding-right: 0px;">
                                                <select v-model="ciudadselected" class="form-control rounded">
                                                    <option value="0" disabled>Seleccionar...</option>
                                                    <option v-for="ciud in arrayCiudad" :key="ciud.id" :value="ciud.id" v-text="ciud.abrev+'-'+ciud.nombre"></option>
                                                </select>
                                                <span class="error" v-if="ciudadselected==0">Debe ingresar la Ciudad</span>
                                            </div>
                                             
                                             <div class="form-group">
                                                <button type="button" class="btn btn-success btn-sm rounded" @click="abrirModal('regciudad')" style="padding-bottom: 7px;padding-top: 7px;">
                                                    +
                                                </button>        
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Telefono Fijo:</label>
                                        <input type="text" id="telefono" name="telefono" class="form-control rounded" placeholder="Telefonos" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45 || event.charCode == 40 || event.charCode == 41 || event.charCode == 32)" v-model="telefono" v-on:focus="selectAll" >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Celular:<span  v-if="celular==''" class="error">(*)</span></label>
                                        <input type="text" id="celular" name="celular" class="form-control rounded" placeholder="Celular" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45 || event.charCode == 43 || event.charCode == 32)" v-model="celular" v-on:focus="selectAll" >
                                        <span  v-if="celular==''" class="error">Debe Ingresar Num. Cel.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profesional" role="tabpanel" aria-labelledby="pills-profesional-tab">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Grado Academico:</label>
                                        <select v-model="formacion" class="form-control rounded">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option v-for="forma in arrayFormacion" :key="forma.id" :value="forma.id" v-text="forma.nombre"></option>
                                        </select>
                                        <span class="error" v-if="formacion==0">Debe ingresar la Formacion</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Profesion:</label>
                                        <select v-model="profesion" class="form-control rounded">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option v-for="prof in arrayProfesion" :key="prof.id" :value="prof.id" v-text="prof.nombre"></option>
                                        </select>
                                        <span class="error" v-if="profesion==0">Debe ingresar la Profesion</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Nit:<small class="text-muted"> Si Corresponde</small></label>
                                        <input type="text" id="nit" name="nit" class="form-control rounded" placeholder="Nit" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" v-model="nit" v-on:focus="selectAll" >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Cargo:</label>
                                        <select v-model="cargo" class="form-control rounded">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option v-for="carg in arrayCargo" :key="carg.id" :value="carg.id" v-text="carg.nombre"></option>
                                        </select>
                                        <span class="error" v-if="cargo==0">Debe ingresar el Cargo</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Fecha Ingreso: <span  v-if="fechaingreso==''" class="error">(*)</span></label>
                                        <input type="date" id="fechaingreso" name="fechaingreso" class="form-control rounded" v-model="fechaingreso">
                                        <span  v-if="fechaingreso==''" class="error">Debe Ingresar la fecha de ingreso del empleado</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Fecha Retiro: </label>
                                        <input type="date" id="fecharetiro" name="fecharetiro" class="form-control rounded" v-model="fecharetiro">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-bancaria" role="tabpanel" aria-labelledby="pills-bancaria-tab">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Banco:</label>
                                        <div class="row">
                                            <div class="form-group col-sm-10" style="padding-right: 0px;">
                                                <select v-model="bancoselected" class="form-control rounded">
                                                    <option value="0" disabled>Seleccionar...</option>
                                                    <option v-for="bank in arrayBancos" :key="bank.id" :value="bank.id" v-text="bank.nombre"></option>
                                                </select>
                                                <span  v-if="bancoselected==0" class="error">Este campo es requerido</span>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-success btn-sm rounded" @click="abrirModal('regbanco')" style="padding-bottom: 7px;padding-top: 7px;">
                                                    +
                                                </button>        
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <strong>Nro de Cuenta: </strong>
                                        <input type="text" id="nrcuenta" name="nrcuenta" class="form-control rounded" placeholder="Numero de Cuenta" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45 )" v-model="nrcuenta" v-on:focus="selectAll" >
                                        <span  v-if="nrcuenta==''" class="error">El numero de cuenta es requerido</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 ">
                                        <strong>Observaciones: </strong>
                                        <input type="text" id="observaciones" name="observaciones" class="form-control rounded" placeholder="Observaciones" v-model="observaciones" v-on:focus="selectAll" >
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                      </form>
                    </div>    
                    <div class="modal-footer d-flex justify-content-between align-items-center">
    <div>
        <small style="color:darkmagenta" v-if="!sicompleto">Debe Registrar los Datos Obligatorios{{ mensajeError }}</small>
        <small style="color:darkmagenta" v-if="mensajeError != ''">{{ mensajeError }}</small>
    </div>
    <div class="d-flex">
        <button type="button" class="btn btn-secondary rounded" @click="cerrarModal('registrar')" style="margin-right: 20px;">Cerrar</button>
        
        <div class="d-flex justify-content-start">
            <div v-if="isSubmitting == false">
                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarempleado()" :disabled="!sicompleto && mensajeError == ''">Guardar</button>
                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarempleado()" :disabled="!sicompleto">Actualizar</button>
            </div>
            <div v-else>
                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                <button type="button" v-if="tipoAccion == 2" class="btn btn-light">Actualizar</button>
            </div>
        </div>
    </div>
</div>

                    </div>
                </div>
            </div>
        </transition>                
    
        <!--Fin del modal-->
        <!-- modal registro bancos -->
        <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('regbanco')">
                            <span>&times;</span>
                        </button>
                        </div>

                        <div class="modal-body" style="background-color: whitesmoke">
                        <div class="form-group">
                            <label>Nombre Banco:<span  v-if="nombrebanco==''" class="error">(*)</span></label>
                            <input type="text" id="nombrebanco" name="nombrebanco" class="form-control rounded" placeholder="Nombre Banco" v-model="nombrebanco" v-on:focus="selectAll" >
                            <span  v-if="nombrebanco==''" class="error">Debe Ingresar el nombre del Banco</span>
                        </div>
                    </div>    
                    

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('regbanco')">Cerrar</button>
                        <button type="button" class="btn btn-primary rounded" @click="registrarBanco()" :disabled="nombrebanco==''">Guardar</button>
                        
                    </div>
                    </div>
                </div>
            </div>
        </transition>                
        <transition name="fade">
            <div v-if="showModal_3" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('regciudad')">
                            <span>&times;</span>
                        </button>
                        </div>
                        <div class="modal-body" style="background-color: whitesmoke">
                        <div class="form-group col">
                            <div class="form-group">
                                <label>Departamento: <span  v-if="depselec==0" class="error">(*)</span></label>
                                <select v-model="depselec" class="form-control rounded">
                                    <option value="0" disabled>Seleccionar...</option>
                                    <option v-for="depto in arrayDepto" :key="depto.id" :value="depto.id" v-text="depto.nombre"></option>
                                </select>
                                <span  v-if="depselec==0" class="error">Debe Ingresar departamento </span>
                            </div>
                            <div class="form-group">
                                <label>Nombre Ciudad:<span  v-if="nomciudad==''" class="error">(*)</span></label>
                                <input type="text" id="nomciudad" name="nomciudad" class="form-control rounded" placeholder="Nombre Ciudad" v-model="nomciudad" v-on:focus="selectAll" >
                                <span  v-if="nomciudad==''" class="error">Debe Ingresar el nombre de la Ciudad</span>
                            </div>
                            
                        </div>
                    </div>    
                    

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('regciudad')">Cerrar</button>
                        <button type="button" class="btn btn-primary rounded" @click="registrarCiudad()" :disabled="nomciudad=='' && depselec==0">Guardar</button>
                        
                    </div>

                    </div>
                </div>
            </div>
        </transition>                
        <!-- modal registro ciudades -->
    
        
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import { error401 } from '../../errores';

//Vue.use(VeeValidate);
    export default {
        //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
        data(){
            return{
                pagination:{
                    'total' :0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0
                },
                offset:3,
                isSubmitting: false, // Controla el estado del botón de envío
                nombre:'',
                descripcion:'',
                arrayEmpleados:[],
                tituloModal:'',
                tipoAccion:1,
                idempleado:'',
                buscar:'',
                

                papellido:'',
                sapellido:'',
                ci:'',
                telefono:'',
                formacion:0,
                profesion:0,
                cargo:0,
                arrayFormacion:[],
                arrayProfesion:[],
                arrayCargo:[],
                fechanacimiento:'',
                domicilio:'',
                arrayCiudad:['El Alto',
                        'La Paz',
                        'Viacha'],
                
                fechaingreso:'',
                sexo:0,
                arraySexo:[{'id':'M','texto':'Masculino'},
                            {'id':'F','texto':'Femenino'}],
                estadocivil:0,
                arrayEstadoCivil:['Soltero(a)','Casado(a)','Divorciado(a)','Viudo(a)'],
                nrcuenta:'',
                fecharetiro:'',
                observaciones:'',
                arrayCiudad:[],
                arrayNacion:[],
                arrayDepto:[],
                deptoselected:0,
                nacionselected:0,
                ciudadselected:0,
                complemento:'',
                celular:'',
                nit:'',
                bancoselected:0,
                arrayBancos:[],
                nombrebanco:'',
                foto:'',
                depselec:0,
                nomciudad:'',

                imagenminiatura:'',
                clearInputFile:1,
                mensajeError:'',
                 //---permisos_R_W_S
                 puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
                showModal: false,
                showModal_2: false,
                showModal_3: false,
            }

        },
        computed:{
            imagen(){
                return this.imagenminiatura;
            },
            sicompleto(){
                let me=this;
                if (me.nombre!='' && me.ci!='' && me.deptoselected!=0 && me.fechanacimiento!='' && me.estadocivil!='' && me.sexo!='' && me.domicilio!='' && me.ciudadselected!=0 && me.celular!='' && me.formacion!=0 && me.profesion!=0 && me.cargo!=0 && me.fechaingreso!='' && me.bancoselected!=0 && me.nrcuenta!='')
                    return true;
                else
                    return false;
            },
            isActived:function(){
                return this.pagination.current_page;
            },
            pagesNumber:function(){
                if(!this.pagination.to){
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from<1){
                    from=1;
                }
                var to = from +(this.offset * 2);
                if(to>= this.pagination.last_page){
                    to=this.pagination.last_page;
                }
                var pagesArray =[];
                while(from<=to){
                    pagesArray.push(from);
                    from++
                }
                return pagesArray;
            },
        },
        methods :{

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
            console.log(error);
        });
},
//--------------------------------------------------------------
            caracteresPermitidosTelefono(ex){
                let me=this;
                console.log(ex.keyCode +'-->'+ex.key);
                if(ex.keyCode==32 || ex.keyCode==43 || ex.keyCode==8 || ex.keyCode == 45 || (ex.keyCode >= 48 && ex.keyCode <= 57) )
                {
                    me.telefono = me.telefono+ex.key;
                } 
            },

            caracteresPermitidosCelular(ex){
                let me=this;
                if(ex.keyCode==32 || ex.keyCode==43 || ex.keyCode==8 || ex.keyCode == 45 || (ex.keyCode >= 48 && ex.keyCode <= 57) )
                {
                    me.celular = me.celular+ex.key;
                } 
            },

            caracteresPermitidosNit(ex){
                let me=this;
                if(ex.keyCode==32 || ex.keyCode==8 || (ex.keyCode >= 48 && ex.keyCode <= 57) )
                {
                    me.nit = me.nit+ex.key;
                } 
            },

            subirfoto(event){
                let me=this;
                me.foto=event.target.files[0];
                me.cargarImagen();
            },
            cargarImagen(){
                let reader = new FileReader();
                reader.onload=(e)=>{
                    this.imagenminiatura=e.target.result;
                }
                reader.readAsDataURL(this.foto);
            },
            listarEmpleados(page){
                let me=this;
                var url='/empleado?page='+page+'&buscar='+me.buscar;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arrayEmpleados=respuesta.empleados.data;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarEmpleados(page);
            },
            registrarempleado(){
                let me = this;
                 // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                let formData = new FormData();
                
                formData.append('nombre',me.nombre);
                formData.append('papellido',me.papellido);
                formData.append('sapellido',me.sapellido);
                formData.append('sexo',me.sexo);
                formData.append('ci',me.ci);
                formData.append('complementoci',me.complemento);
                formData.append('iddepartamento',me.deptoselected);
                formData.append('fechanacimiento',me.fechanacimiento);
                formData.append('foto',me.foto); //<--------------------Foto
                formData.append('estadocivil',me.estadocivil);
                formData.append('idnacionalidad',me.nacionselected);
                
                formData.append('domicilio',me.domicilio);
                formData.append('idciudad',me.ciudadselected);
                formData.append('telefonos',me.telefono);
                formData.append('celular',me.celular);

                formData.append('idformacion',me.formacion);
                formData.append('idprofesion',me.profesion);
                formData.append('idcargo',me.cargo);
                formData.append('nit',me.nit);
                formData.append('fechaingreso',me.fechaingreso);
                formData.append('fecharetiro',me.fecharetiro);
                
                formData.append('idbanco',me.bancoselected);
                formData.append('nrcuenta',me.nrcuenta);
                
                formData.append('obs',me.observaciones);
                
                axios.post('/empleado/registrar', formData, {headers : {'content-type': 'multipart/form-data'}})
                .then(function(response){
                    me.cerrarModal('registrar');
                    me.listarEmpleados();
                }).catch(function(error){
                    error401(error);
                    if(error.response.status == 422)
                    {
                        me.mensajeError="El CI del Empleado ya registrardo";
                    }
                    console.log(error);
                }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });

            },
            eliminarempleado(idempleado){
                let me=this;
                //console.log("prueba");
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
                     axios.put('/empleado/desactivar',{
                        'id': idempleado
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarEmpleados();
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
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
            activarempleado(idempleado){
                let me=this;
                //console.log("prueba");
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
                     axios.put('/empleado/activar',{
                        'id': idempleado
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarEmpleados();
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
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
            actualizarempleado(){
               // const Swal = require('sweetalert2')
                let me =this;

                let formData = new FormData();
                
                formData.append( 'id',me.idempleado,);
                formData.append('nombre',me.nombre);
                formData.append('papellido',me.papellido);
                formData.append('sapellido',me.sapellido);
                formData.append('sexo',me.sexo);
                formData.append('ci',me.ci);
                formData.append('complementoci',me.complemento);
                formData.append('iddepartamento',me.deptoselected);
                formData.append('fechanacimiento',me.fechanacimiento);
                formData.append('foto',me.foto);
                formData.append('estadocivil',me.estadocivil);
                formData.append('idnacionalidad',me.nacionselected);
                
                formData.append('domicilio',me.domicilio);
                formData.append('idciudad',me.ciudadselected);
                formData.append('telefonos',me.telefono);
                formData.append('celular',me.celular);

                formData.append('idformacion',me.formacion);
                formData.append('idprofesion',me.profesion);
                formData.append('idcargo',me.cargo);
                formData.append('nit',me.nit);
                formData.append('fechaingreso',me.fechaingreso);
                formData.append('fecharetiro',me.fecharetiro);
                
                formData.append('idbanco',me.bancoselected);
                formData.append('nrcuenta',me.nrcuenta);
                
                formData.append('obs',me.observaciones);
                axios.post('/empleado/actualizar',formData
                   /*  'id':me.idempleado,
                    'nombre':me.nombre,
                    'papellido':me.papellido,
                    'sapellido':me.sapellido,
                    'sexo':me.sexo,
                    'ci':me.ci,
                    'complementoci':me.complemento,
                    'iddepartamento':me.deptoselected,
                    'fechanacimiento':me.fechanacimiento,
                    'foto':me.foto,
                    'estadocivil':me.estadocivil,
                    'idnacionalidad':me.nacionselected,
                    
                    'domicilio':me.domicilio,
                    'idciudad':me.ciudadselected,
                    'telefonos':me.telefono,
                    'celular':me.celular,

                    'idformacion':me.formacion,
                    'idprofesion':me.profesion,
                    'idcargo':me.cargo,
                    'nit':me.nit,
                    'fechaingreso':me.fechaingreso,
                    'fecharetiro':me.fecharetiro,
                    
                    'idbanco':me.bancoselected,
                    'nrcuenta':me.nrcuenta,
                    
                    'obs':me.observaciones, */
                    
                ).then(function (response) {
                    if(response.data.length){
                    }
                    // console.log(response)
                    else{
                            Swal.fire('Actualizado Correctamente')

                        me.listarEmpleados();
                    } 
                }).catch(function (error) {
                    error401(error);
                });
                me.cerrarModal('registrar');


            },
            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {me.isSubmitting=false;
                        me.showModal = true;
                        if(data[0]==1)
                            me.classModal.openModal('registrar');
                        if(data[0]==2)
                            me.classModal.openModal('registrar');
                        else{
                            
                            me.tituloModal='Registar empleado'
                            me.tipoAccion=1;
                            me.nombre='';
                            me.papellido='';
                            me.sapellido='';
                            me.ci='';
                            me.telefono='';
                            me.formacion=0;
                            me.profesion=0;
                            me.cargo=0;
                            me.fechanacimiento='';
                            me.domicilio='';
                            me.ciudad='';
                            me.fechaingreso='';
                            me.sexo=0;
                            me.estadocivil=0;
                            me.nrcuenta='';
                            me.fecharetiro='';
                            me.observaciones='';
                            me.deptoselected=0;
                            me.nacionselected=0;
                            me.ciudadselected=0;
                            me.complemento='';
                            me.celular='';
                            me.nit='';
                            me.bancoselected=0;
                            me.classModal.openModal('registrar');
                            me.clearInputFile=0;
                            setTimeout(me.tiempo, 200);   
                            
                        }
                        break;
                        
                    }
                    
                    case 'actualizar':
                    {
                        me.isSubmitting=false;
                        me.showModal = true;
                        me.idempleado=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar empleado';
                        me.nombre=data.nombre;
                        data.papellido=data.papellido===null ? '' : data.papellido;
                        me.papellido=data.papellido;
                        data.sapellido = data.sapellido === null ? '' : data.sapellido;
                        me.sapellido=data.sapellido;
                        me.ci=data.ci;
                        data.telefonos === null ? '' : data.telefonos;
                        me.telefono=data.telefonos;
                        me.formacion=data.idformacion;
                        me.profesion=data.idprofesion;
                        me.cargo=data.idcargo;
                        me.fechanacimiento=data.fechanacimiento;
                        data.domicilio = data.domicilio === null ? '' : data.domicilio;
                        me.domicilio=data.domicilio;
                        me.ciudad=data.ciudad;
                        me.fechaingreso=data.fechaingreso;
                        me.sexo=data.sexo;
                        me.estadocivil=data.estadocivil;
                        data.nrcuenta = data.nrcuenta === null ? '' : data.nrcuenta;
                        me.nrcuenta=data.nrcuenta;
                        me.fecharetiro=data.fecharetiro;
                        data.obs = data.obs === null ? '' : data.obs;
                        me.observaciones=data.obs;
                        me.deptoselected=data.iddepartamento;
                        me.nacionselected=data.idnacionalidad;
                        me.ciudadselected=data.idciudad;
                        data.complementoci= data.complementoci === null ? '' : data.complementoci;
                        me.complemento=data.complementoci;
                        me.celular=data.celular
                        data.nit = data.nit === null ? '' :data.nit;
                        me.nit=data.nit
                        data.idbanco = data.idbanco === null ? 0 : data.idbanco;
                        me.bancoselected=data.idbanco;
                        data.foto = data.foto === null ? 'persona.png': data.foto.substring(10);
                        me.imagenminiatura='storage/'+ data.foto;
                        me.classModal.openModal('registrar');
                        me.clearInputFile=0;
                        setTimeout(me.tiempo, 200);   

                        break;
                    }
                    case 'regbanco':
                    {
                        me.tituloModal='Registro de banco';
                        me.showModal_2 = true;
                        //me.classModal.closeModal('registrar');
                        me.classModal.openModal('regbanco');
                        me.nombrebanco='';
                        break;
                    }
                    case 'regciudad':
                    {
                        me.tituloModal='Registro de ciudad';
                        me.showModal_3 = true;
                        //me.classModal.closeModal('registrar');
                        me.classModal.openModal('regciudad');
                        me.nomciudad='';
                        break;
                    }

                }
                
            },
            registrarBanco(){
                let me = this;
                let arr=[1];
                axios.post('/banco/registrar',{
                    'nombre':me.nombrebanco,
                }).then(function(response){
                    me.selectBancos();
                    me.bancoselected=response.data.idbanco;
                 
                    me.abrirModal('registrar',arr);
                    
                }).catch(function(error){
                    error401(error);
                });

            },
            registrarCiudad(){
                let me = this;
                let arr=[2];
                axios.post('/ciudad/registrar',{
                    'iddepartamento':me.depselec,
                    'nombre':me.nomciudad,
                }).then(function(response){
                    me.selectCiudades();
                    me.ciudadselected=response.data.idciudad;
                    //console.log(response);
                    //me.classModal.closeModal('regbanco');
                    me.abrirModal('registrar',arr);
                    
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },
            
            tiempo(){
            this.clearInputFile=1;
            },

            cerrarModal(accion){
                let me = this;
                me.isSubmitting=false;
                if(accion=='regbanco'){
                    me.classModal.closeModal(accion);
                    //me.bancoselected=0;
                    me.showModal_2 = false; 
                    me.nombrebanco='';
                }
                else
                {
                    if(accion=='regciudad'){
                        me.classModal.closeModal(accion);
                        me.depselec=0;
                        me.showModal_3 = false; 
                        me.nomciudad='';
                    }
                    else    
                    {
                        
                        me.classModal.closeModal(accion);
                        me.showModal = false;                   
                        me.tipoAccion=1;
                        me.nombre='';
                        me.papellido='';
                        me.sapellido='';
                        me.ci='';
                        me.telefono='';
                        me.formacion=0;
                        me.profesion=0;
                        me.cargo=0;
                        me.fechanacimiento='';
                        me.domicilio='';
                        me.ciudad='';
                        me.fechaingreso='';
                        me.sexo=0;
                        me.estadocivil=0;
                        me.nrcuenta='';
                        me.fecharetiro='';
                        me.observaciones='';
                        me.tipoAccion=1;
                        me.deptoselected=0;
                        me.nacionselected=0;
                        me.ciudadselected=0;
                        me.complemento='';
                        me.celular='';
                        me.nit='';
                        me.bancoselected=0;
                        me.imagenminiatura='';
                        me.foto='';
                        
                    }
                }
              me.mensajeError='';
              me.listarEmpleados();
            },
            
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  
            selectProfesions(){
                let me=this;
                var url='/profesion/selectprofesion';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayProfesion=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            selectFormacions(){
                let me=this;
                var url='/formacion/selectformacion';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayFormacion=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            selectCargos(){
                let me=this;
                var url='/cargo/selectcargo';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayCargo=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            selectDepartamentos(){
                let me=this;
                var url='/depto/selectdepto';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayDepto=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            selectCiudades(){
                let me=this;
                var url='/ciudad/selectciudad';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayCiudad=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            selectNacionalidad(){
                let me=this;
                var url='/nacion/selectnacion';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    //console.log(respuesta);
                    me.arrayNacion=respuesta.nacions;
                    me.nacionselected=respuesta.idboliviano;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            selectBancos(){
                let me=this;
                var url='/banco/selectbanco';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayBancos=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },



        },
        mounted() {
            //-------permiso E_W_S-----
            this.listarPerimsoxyz();
            //-----------------------
            this.selectBancos();
            this.selectProfesions();
            this.selectFormacions();
            this.selectCargos();
            this.selectDepartamentos();
            this.selectCiudades();
            this.selectNacionalidad();
            this.listarEmpleados(1);
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            this.classModal.addModal('regbanco');
            this.classModal.addModal('regciudad');
            //console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;
    
}
label{
    margin-bottom: 0px;
}
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}
.fotosociomini{
	     display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:35px;
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