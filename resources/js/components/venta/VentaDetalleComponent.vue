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
                    <i class="fa fa-align-justify"></i> Detalle de ventas               
                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada"    @change="listarVentas(0)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"
                                        v-text="
                                            sucursal.codigoS +
                                            ' -> ' +
                                            sucursal.codigo+
                                            ' ' +
                                            sucursal.razon_social
                                        "
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar por usuario, nombre a facturar, numero de documento"
                                    v-model="buscar"
                                    @keyup.enter="listarVentas(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarVentas(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

            <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input  @change="listarVentas(1)" id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input  @change="listarVentas(1)" id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
      </div>
    </div>
  </div>
  <br>
  
    <div v-if="sucursalSeleccionada ===0" class="alert alert-secondary" role="alert">
  Debe seleccionar una sucursal!
</div>
<div v-else>
    <!---inserte tabla-->
    <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Cliente</th>
                        <th class="col-md-1">Nro Documento</th>
                        <th class="col-md-1">Nro Transaccion</th>
                        <th class="col-md-1">Tipo Comprobante</th>
                        
                        <th class="col-md-1">Total</th>
                        <th class="col-md-1">Efectivo</th>
                        <th class="col-md-1">Cambio</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Usuario</th>
                        <th>Anulado</th>
                        <th>Estado</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="v in arrayVentas" :key="v.id">
                        <td class="col-md-1">
            <!-- Example single danger button -->
<div class="btn-group" v-if="puedeHacerOpciones_especiales==1">
  <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu">
    <div v-if="puedeHacerOpciones_especiales==1">
      <a @click.prevent="abrirModal('recibo_r', v);" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i> Re imprimir factura ticket</a>
    <a @click.prevent="abrirModal('pdf_plana', v);" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i> Re imprimir factura plana</a>
    <a @click.prevent="abrirModal('ver_detalle_venta',v)"  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i> Ver estado</a>
    </div>
         <a v-if="puedeActivar==1&&v.anulado===0" @click.prevent="eliminar(v.id,v.fecha_formateada,v.fecha_mas_tres,v.dosificacion_o_electronica,v.tipo_venta_reci_fac);"  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-trash" aria-hidden="true"></i> Anular venta</a>
         <a v-else  class="dropdown-item" href="#"><i class="fa fa-trash-o" aria-hidden="true"></i> Venta ya anulada</a>
         
     </div>
</div>
<div v-else>
  <button type="button" style="color: white;"  class="btn btn-light">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>
</div>                       
                        </td>
                        <td class="col-md-1" v-text="v.nom_a_facturar"></td>
                        <td class="col-md-1" v-text="v.num_documento"></td>
                        <td class="col-md-1" v-text="v.nro_comprobante_venta"></td>
                        <td class="col-md-1" v-text="v.tipo_venta_reci_fac"></td>
                        <td class="col-md-1" v-text="v.total_venta"></td>
                        <td class="col-md-1" v-text="v.efectivo_venta"></td>
                        <td class="col-md-1" v-text="v.cambio_venta"></td>
                        <td class="col-md-2" v-text="v.created_at"></td>
                        <td class="col-md-1" v-text="v.name"></td>
                        <td>
                            <div v-if="v.anulado===0">
                                <span class="badge badge-pill badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span  class="badge badge-pill badge-danger">Anulado</span>
                            </div>
                        </td>
                        <td >
                            <div v-if="v.estado_venta==='ACEPTADO'">
                                <span class="badge badge-pill badge-success">Valida</span>
                            </div>
                            <div v-else>
                                <span  class="badge badge-pill badge-warning">Revisar</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>    
            <!-----fin de tabla------->
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
            id="ver_detalle"
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
                            @click="cerrarModal('ver_detalle')"
                        >
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      
                      <table class="table table-bordered table-striped table-sm table-responsive">
      <thead>
        <tr>
          <th  class="col-md-1" style="font-size: 11px; text-align: center">Cod. Cliente</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center">Nombre de cliente</th>
          <th  class="col-md-2" style="font-size: 11px; text-align: center">Nombre a facturar</th>
          <th  class="col-md-2" style="font-size: 11px; text-align: center">Numero de documento</th>
          <th  class="col-md-3" style="font-size: 11px; text-align: center">Correo</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="col-md-1" style="font-size: 11px; text-align: center;">{{cod_cliente}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{nom_cliente}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{nom_facturar}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{num_documento}}</td>
          <td class="col-md-3" style="font-size: 11px; text-align: center;">{{ correo }}</td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered table-striped table-sm table-responsive">
     
      <thead>
        <tr>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Tipo de venta</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Tipo de dato</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Nro. Factura</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Cod. Autorización</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Codigo de control</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">
            <span class="badge badge-pill badge-primary" style="font-size: 11px;">{{data_factura_tipo}}</span></td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{data_factura_tipo_docuemnto}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{data_factura_nro_factura}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{data_factura_cod_control}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{data_factura_nro_auto}}</td>
        </tr>
      </tbody>   

   
    </table>    
    
      
    <table class="table table-bordered table-striped table-sm table-responsive">
      <thead>
        <tr>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Numero de identificacion</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">
          <span>Numero de venta</span>
       
          </th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Fecha</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Hora</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Vendedor</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Estado</th>          
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{num_identificacion}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{num_reci_fac}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{fecha_x}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{hora_x}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{nom_user}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">
            <span class="badge badge-pill badge-success" v-if="anulado_x==='ACTIVO'"> {{anulado_x}}</span>
            <span class="badge badge-pill badge-danger" v-if="anulado_x==='ANULADO'"> {{anulado_x}}</span>
           
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered table-striped table-sm table-responsive">
      <thead>
        <tr>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Cod. Prod</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Liena</th>
          <th class="col-md-4" style="font-size: 11px; text-align: center;">Producto</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Cantidad</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">P/U</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Pre. Total</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Descuento</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Sub Total</th>
          
        </tr>
      </thead>
        <tbody>
          
            <tr v-for="d_v in array_detalle_venta" :key="d_v.id">
            <td class="col-md-1" style="font-size: 11px; text-align: center;">{{d_v.cod_prod}}</td>
            <td class="col-md-1" style="font-size: 11px; text-align: center;">{{d_v.linea_nombre}}</td>
            <td class="col-md-4" style="font-size: 11px; text-align: center;">{{d_v.leyenda}}</td>
            <td class="col-md-1" style="font-size: 11px; text-align: right;">{{d_v.cantidad_venta}}</td>
            <td class="col-md-1" style="font-size: 11px; text-align: right;">{{d_v.precio_unitario}}</td>
            <td class="col-md-1" style="font-size: 11px; text-align: right;">{{d_v.tot}}</td>
            <td class="col-md-1" style="font-size: 11px; text-align: right;">{{d_v.descuento}}</td>
            <td class="col-md-2" style="font-size: 11px; text-align: right;">{{(d_v.tot-d_v.descuento).toFixed(2)}}</td>
          </tr>
          <tr>
            <th colspan="7" style="font-size: 11px; text-align:right">Suma Total:</th>
            <th style="font-size: 11px; text-align:right">{{suma_total}} Bs</th>
        </tr>        
        <tr>
          <th colspan="7" style="font-size: 11px; text-align:right ">Descuento:</th>
          <th style="font-size: 11px; text-align:right">{{decuento_sin_venta}} Bs</th>
        </tr>
        <tr>
          <th colspan="7" style="font-size: 11px; text-align:right">Total:</th>
          <th style="font-size: 11px; text-align:right">{{total_1}} Bs</th>
        </tr>
        <tr>
          <th colspan="7" style="font-size: 11px; text-align:right">Efectivo:</th>
          <th style="font-size: 11px; text-align:right">{{efectivo}} Bs</th>
        </tr>
        <tr>
          <th colspan="7" style="font-size: 11px; text-align:right">Cambio:</th>
          <th style="font-size: 11px; text-align:right">{{cambio}} Bs</th>
        </tr>
   
        </tbody>       
    </table>
 
    <p>

  <button v-if="array_nombre_des.length === 0" class="btn btn-light" type="button">
   Descuentos
  </button>
  <button v-else class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
   Descuentos
  </button>

</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body">
    <div v-for="d_v in array_detalle_venta" :key="d_v.id">
  <span style="font-size: 11px;"><strong>{{ d_v.cod_prod + " " + d_v.leyenda }}</strong></span>
  <table class="table table-bordered table-striped table-sm table-responsive">
    <thead>
      <tr>
        <th style="font-size: 11px;">Tabla</th>
        <th style="font-size: 11px;">Descuento</th>
        <th style="font-size: 11px;">Cantidad descuento</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="des in array_nombre_des.filter(item => item.id_detalle_descuento === d_v.id_detalle_descuento)" :key="des.id">
        <td style="font-size: 11px;">{{ des.nom_tabla }}</td>
        <td style="font-size: 11px;">{{ des.nombre_descuento }}</td>
        <td style="font-size: 11px;text-align: right;">{{ des.cantidad_descuento }}</td>
      </tr>
    </tbody> 
  </table>   
 
 
</div>
<span style="font-size: 11px;"><strong>Descuento agregado final</strong></span>
<table class="table table-bordered table-striped table-sm table-responsive">
    <thead>
      <tr>
        <th style="font-size: 11px;">Tabla</th>
        <th style="font-size: 11px;">Descuento</th>
        <th style="font-size: 11px;">Cantidad descuento</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="des in array_nombre_des.filter(item => item.id_detalle_descuento === 0)" :key="des.id">
        <td style="font-size: 11px;">{{ des.nom_tabla }}</td>
        <td style="font-size: 11px;">{{ des.nombre_descuento }}</td>
        <td style="font-size: 11px;text-align: right;">{{ des.cantidad_descuento }}</td>
      </tr>
    </tbody> 
  </table>   

  </div>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    <i class="fa fa-arrow-circle-up" aria-hidden="true"></i> Subir
  </button>
</div>
               
                    </div>
                   <!---- acoordion ----> 
              

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="cerrarModal('ver_detalle')"
                        >
                            Cerrar
                        </button>
                       
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import moment from 'moment';
// Asigna los fonts a pdfmake
pdfMake.vfs = pdfFonts.pdfMake.vfs;
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
          

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',
      id_seleccionada_sucursal:0,
      cod_seleccionada_sucursal:'',
      arrayVentas:[],
      arrayDetalle_venta:[],
      recibo_ticket_plana:0,
       // datos de vista detalle venta
       cod_cliente:'',
       nom_cliente:'',
       nom_facturar:'',
       num_documento:'',
       correo:'',
       num_identificacion:'',
       num_reci_fac:'',
       fecha_x:'',
       hora_x:'',
       anulado_x:'',
       suma_total:'',
       descuento:'',
       total_1:'',
       efectivo:'',
       cambio:'',
       nom_user:'',
       tipo_per_emp:'',
       dosificacion_o_electronica:'',

       data_factura_tipo:'',
       data_factura_tipo_docuemnto:'',       
       data_factura_nro_factura:'',
       data_factura_cod_control:'',
       data_factura_nro_auto:'',
              
      
       //---
       array_nombre_des:[],
       array_detalle_venta:[],
       venta_con_descuento:'',
       decuento_sin_venta:'',
       nombre_completo_cliente:'',
       nombre_completo_empleado:'',
        todayDate: '',
           //---permisos_R_W_S
           puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
        };
    },

    watch: {
    sucursalSeleccionada(valor) {
      if (valor !== 0) {
        let sucursal = this.arraySucursal.find(element => element.codigo === valor);

        if (sucursal) {
          this.id_seleccionada_sucursal = sucursal.id_sucursal;
          this.cod_seleccionada_sucursal = sucursal.codigo;
        }

        console.log(this.id_seleccionada_sucursal);
        console.log(this.cod_seleccionada_sucursal);
        console.log(this.startDate);
        console.log(this.endDate);
      }
    }
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
/////////facturacion plana x dosificacion /////////////////////////////////
reimprecionFactura_dosificaicion_plana(cod_cliente,numero_identificacion,respuesta_total,nom_empresa,direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,venta_con_descuento,resultado_descuento_2,anulado,actividad_economica,nro_celular,ciudad_su_1,departamento_su_1,codigo_control_dosifi,estado_factura_dosifi,fecha_e_dosifi,nro_autorizacion_dosifi,numero_factura_dosifi,nit,descuento_final,total_literal,base64){

 const tableBody = [
    // Agrega los encabezados de la tabla
    [
      { text: 'Código Producto / Servicio.', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Descripción', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Vencimiento', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Lote', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Linea', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Cantidad', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Precio Unitario', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Venta Total', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Descuento', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'SubTotal' , style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
    ]
  ];
// Itera sobre los datos y agrega filas a la tabla
console.log("++-+-+-+-----");
console.log(array_recibo);
array_recibo.forEach(item => {
    tableBody.push([
      { text: item.cod_prod, fontSize: 8, alignment: 'left' },
      { text: item.leyenda, fontSize: 8, alignment: 'left' },
      { text: item.fecha_vencimiento, fontSize: 8, alignment: 'left' },
      { text: item.lote, fontSize: 8, alignment: 'left' },
      { text: item.linea_nombre, fontSize: 8, alignment: 'left' },
      { text: item.cantidad_venta, fontSize: 8, alignment: 'right' },
      { text: item.precio_unitario, fontSize: 8, alignment: 'right' },
      { text: item.tot, fontSize: 8, alignment: 'right' },
     { text: item.descuento, fontSize: 8, alignment: 'right' },
      
      { text: (item.tot - item.descuento).toFixed(2), fontSize: 7, alignment: 'right' } // Operación y formato
   ]);
  });
   // Agrega las filas con colspan al final del tableBody
tableBody.push(
  [
    { text: 'Suma Total', colSpan: 9, fontSize: 7, alignment: 'right', border: [false, true, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: (venta_con_descuento).toFixed(2), fontSize: 7, alignment: 'right' }
  ],
  [
    { text: 'Descuento', colSpan: 9, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: resultado_descuento_2, fontSize: 7, alignment: 'right' }
  ],
  [
    { text: 'Total Bs', colSpan: 9, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: total_venta , fontSize: 7, alignment: 'right' }
  ]
);

  const docDefinition = {
    pageSize: 'LETTER', // Tamaño carta
    pageMargins: [25, 30, 25, 30], // Márgenes: [left, top, right, bottom]
    content: [
    {
  		table: {
				widths: [65,150,'*',80, 90],
				body: [
      	[{rowSpan: 3,image: base64, fit: [65, 65]},{text: nom_empresa ,fontSize: 9,bold: true},{ },{text: 'NIT: ',fontSize: 8},{text: nit ,fontSize: 8}],
					[{ },{text: nomsucursal,fontSize: 8},{ },{text: 'FACTURA Nº:',fontSize: 8},{text: numero_factura_dosifi,fontSize: 8}],
          [{ },{text: direccionMayusculas+' \n'+ciudad_su_1+' - '+departamento_su_1+' \n'+'TELEFONO '+numero_referencia ,fontSize: 8},{ },{text: 'COD. AUTORIZACIÓN:',fontSize: 8},
            {text: nro_autorizacion_dosifi ,fontSize: 8}],
				]
			},
      layout: 'noBorders'
		},
    {text: 'FACTURA', style: 'header' },
    {			
			table: {
				widths: [90,120,'*',60,105],
				body: [
					[{text: 'Fecha:',style:'negritas'},
          {text: fecha+' '+ hora ,fontSize: 8},{ },
          {text: 'NIT/CI/CEX:',style:'negritas'},
          {text: num_documento,fontSize: 8},         
          ],
					[{text: 'Nombre/Razón Social:',style:'negritas'},
          {text: nom_a_facturar,fontSize: 8},{ },
          {text: 'Cod. Cliente:',style:'negritas'},
          {text: cod_cliente,fontSize: 8},         
          ],
				]
			},
      layout: 'noBorders'
		},
   {
        style: 'tableExample',
        margin:[0,10,0,0],
        table: {
          headerRows: 1,
          widths: [50,'*',55,34,32,37,40,40,43,40], // Ajusta los anchos de las columnas
          body: tableBody         
        },      
		},
    {
      text: 'Son: '+respuesta_total ,fontSize: 8,bold: true,
    },
    {
      text: 'Codigo de control: '+codigo_control_dosifi ,fontSize: 8,bold: true,
    },
    {
      text: 'Fecha de emisiòn: '+fecha_e_dosifi ,fontSize: 8,bold: true, margin:[0,0,0,15]
    },
    {
  		table: {
				widths: [75,'*'],
				body: [      
					[{rowSpan: 4,qr:  nit+'|'+numero_factura_dosifi+'|'+nro_autorizacion_dosifi+'|'+fecha+'|'+total_venta+'|'+codigo_control_dosifi+'|'+cod_cliente+'|0|0|0|0.00', fit: '85'},{text:'Valido desde '+ fecha +' hasta '+fechaMas7Dias+',valor de vigencia para el cambio valido hasta '+ fecha+' en la misma tienda, términos y codiciones según politica de cambios y devoluiones.',fontSize: 7}],
          [{ },{text:'"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY."',fontSize: 7}],
          [{ },{text:'Ley N° 453: Cuando lo solicite el paciente, se debe informar los resultados de exámenes, diagnósticos y estudios de laboratorio.',fontSize: 7}],
          [{ },{text:'Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea.',fontSize: 7}],
        
				]
			},
      layout: 'noBorders'
		},
  ],
  styles: {
      header: {
        fontSize: 16,
        bold: true,
        color: 'black',
        alignment: 'center',
        margin:[0,10,0,10]
      },
      negritas:{
        fontSize: 8,
        bold: true,
      },
      tableExample:{
        fontSize: 8,
        bold: true,
      },
    }
  };
   // Genera el PDF y abre una nueva ventana con el documento
   pdfMake.createPdf(docDefinition).open();
},
////////facturacion ticket x dosificacion/////////////////////////////////
  reimprecionFactura_dosificaicion(cod_cliente,numero_identificacion,respuesta_total,nom_empresa,direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,venta_con_descuento,resultado_descuento_2,anulado,actividad_economica,nro_celular,ciudad_su_1,departamento_su_1,codigo_control_dosifi,estado_factura_dosifi,fecha_e_dosifi,nro_autorizacion_dosifi,numero_factura_dosifi,nit,descuento_final,total_literal){

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
    sumador_subtotal=sumador_subtotal+((item.cantidad_venta * item.precio_unitario)-descuento_dosificacion_2);
    tableBody_2.push([
    { text: item.leyenda , fontSize: 7, alignment: 'left' }, // Salto de línea
      {},   
    ]);
    tableBody_2.push([
    { text: 'Unidad de medida: '+item.unidad_medida, fontSize: 7, margin: [5, 0, 0, 0],alignment: 'left' }, // Salto de línea
      {},   
    ]);
    tableBody_2.push([
    { text: item.cantidad_venta+'.00 x '+item.precio_unitario+' - '+(descuento_dosificacion_2), fontSize: 7, alignment: 'left' }, // Salto de línea
    { text: ((item.cantidad_venta * item.precio_unitario)-descuento_dosificacion_2).toFixed(2), fontSize: 7, alignment: 'right' },   
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
      { text: descuento_final, fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Total Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: total_venta, fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Monto gift card Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: '0.00', fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Monto a pagar Bs:   ',  fontSize: 7, alignment: 'right',bold: true }, 
      { text: total_venta, fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Importe base crédito fiscal Bs:   ',  fontSize: 7, alignment: 'right',bold: true }, 
      { text: total_venta, fontSize: 7, alignment: 'right' }     
    ]
  ];
  const table_pago_efe_cambio=[
  [
      { text: 'Pago en efectivo Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: efectivo_venta, fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Cambio Bs:   ',  fontSize: 7, alignment: 'right' }, 
      { text: cambio_venta, fontSize: 7, alignment: 'right' }     
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
        text: nom_empresa,
      
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
        text: nit,    
        style: 'header'
      },
      {
        text: 'FACTURA Nro',bold: true,    
        style: 'header'
      },
      {
        text: numero_factura_dosifi,    
        style: 'header'
      },
      {
        text: 'CÓD. AUTORIZACION', bold: true,    
        style: 'header'
      },
      {
        text: nro_autorizacion_dosifi,    
        style: 'header'
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
    
      {
        text: actividad_economica.toUpperCase(),
    
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
        text: 'COD.CLIENTE:    '+cod_cliente,
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
        text: 'Codigo de control: '+codigo_control_dosifi,    
        style: 'datos_f',margin: [6, 1, 6, 1]
      },
      {
        text: 'Fecha limite de emision: '+fecha_e_dosifi,    
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
      { qr:  nit+'|'+numero_factura_dosifi+'|'+nro_autorizacion_dosifi+'|'+fecha+'|'+total_venta+'|'+codigo_control_dosifi+'|'+cod_cliente+'|0|0|0|0.00',alignment: 'center',  fit: '85',margin: [0, 4, 0, 4] },
    
    ],
    styles: {
      header: {
            fontSize: 7,
           
            alignment: 'center',         
          },
          linea_2: {
                fontSize: 9,
                 margin: [1, 1, 1, 1],
                 alignment: 'center',  
            },
            normal: {
            fontSize: 7,                  
          },
          datos_f: {
            fontSize: 7,
         
            alignment: 'left',         
          },
    }
   
};
   // Genera el PDF y abre una nueva ventana con el documento
   pdfMake.createPdf(documentDefinition).open();
  },



            ///////////////////////////////funciones para la venta///////////////////////////////////////////////////////
    generarPDF(nom_empresa,direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,anulado) {
      // Define el contenido del PDF
      let watermark = {};      
      if (anulado===1) { // Aquí puedes poner tu condición
  watermark = { text: 'ANULADO', color: 'red', angle: -45, opacity: 0.3, bold: true, italics: false, fontSize: 40 };
}
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
      { text: item.cantidad_venta, fontSize: 7, alignment: 'center' },
      { text: item.leyenda, fontSize: 7, alignment: 'left' },
      { text: item.precio_unitario, fontSize: 7, alignment: 'center' },
      { text: (item.cantidad_venta * item.precio_unitario).toFixed(2), fontSize: 7, alignment: 'center' } // Operación y formato
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
        text: nom_empresa,
      
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
        text: 'IMPORTE TOTAL: Bs.   '+total_sin_des,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'DESCUENTO: Bs.   '+descuento_venta,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'IMPORTE A PAGAR: Bs.   '+total_venta,      
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
        text: 'PAGO EN EFECTIVO: Bs.   '+efectivo_venta,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'CAMBIO: Bs.   '+cambio_venta,      
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
        watermark: watermark, // Agrega la marca de agua condicionalmente
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
      },
      anulado: {
        fontSize: 7,
      }
        }
      };

      // Genera el PDF y abre una nueva ventana con el documento
      pdfMake.createPdf(documentDefinition).open();
    },
  
    generarFacPlana(cod_cliente,numero_identificacion,respuesta_total,nom_empresa,direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,venta_con_descuento,resultado_descuento_2,anulado) {
  
  // Crea el cuerpo de la tabla dinámicamente
  // Define el contenido del PDF
  let watermark = {};      
      if (anulado===1) { // Aquí puedes poner tu condición
  watermark = { text: 'ANULADO', color: 'red', angle: -45, opacity: 0.3, bold: true, italics: false, fontSize: 65 };
}
  const tableBody = [
    // Agrega los encabezados de la tabla
    [
      { text: 'Código Producto / Servicio.', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Descripción', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Vencimiento', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Lote', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Linea', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Cantidad', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Precio Unitario', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Venta Total', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Descuento', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'SubTotal' , style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
    ]
  ];
  
  // Itera sobre los datos y agrega filas a la tabla
  array_recibo.forEach(item => {
    tableBody.push([
      { text: item.cod_prod, fontSize: 8, alignment: 'left' },
      { text: item.leyenda, fontSize: 8, alignment: 'left' },
      { text: item.fecha_vencimiento, fontSize: 8, alignment: 'left' },
      { text: item.lote, fontSize: 8, alignment: 'left' },
      { text: item.linea_nombre, fontSize: 8, alignment: 'left' },
      { text: item.cantidad_venta, fontSize: 8, alignment: 'right' },
      { text: item.precio_unitario, fontSize: 8, alignment: 'right' },
      { text: item.tot, fontSize: 8, alignment: 'right' },
      { text: item.descuento, fontSize: 8, alignment: 'right' },
      
      { text: (item.tot - item.descuento).toFixed(2), fontSize: 7, alignment: 'right' } // Operación y formato
    ]);
  });
  // Agrega las filas con colspan al final del tableBody
tableBody.push(
  [
    { text: 'Suma Total', colSpan: 9, fontSize: 7, alignment: 'right', border: [false, true, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: (venta_con_descuento).toFixed(2), fontSize: 7, alignment: 'right' }
  ],
  [
    { text: 'Descuento', colSpan: 9, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: resultado_descuento_2, fontSize: 7, alignment: 'right' }
  ],
  [
    { text: 'Total', colSpan: 9, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: total_venta , fontSize: 7, alignment: 'right' }
  ]
);
      const docDefinition = {
    pageSize: 'LETTER', // Tamaño carta
    pageMargins: [25, 30, 25, 30], // Márgenes: [left, top, right, bottom]
    content: [

    {
			
			table: {
				widths: [120,'*',90, 100],
				body: [
					[{text: nom_empresa ,fontSize: 8},
          { },{text: 'IDENTIFICACIÓN ',fontSize: 8},{text: numero_identificacion ,fontSize: 8}
          ],
					[{text: nomsucursal,fontSize: 8},{ },
           {text: 'RECIBO Nº:',fontSize: 8},
            {text: nuevoComprobante,fontSize: 8}, 
            ],
            [{text: direccionMayusculas+' \n'+'TELEFONO '+numero_referencia ,fontSize: 8},{ },
           {text: 'COD DE CONTROL:',fontSize: 8},
            {text: nuevoComprobante ,fontSize: 8}, 
            ],
				]
			},
      layout: 'noBorders'
		},
   
    {text: 'RECIBO', style: 'header' },
    {			
			table: {
				widths: [90,120,'*',60,105],
				body: [
					[{text: 'Fecha:',fontSize: 8},
          {text: fecha+' '+ hora ,fontSize: 8},{ },
          {text: 'NIT/CI/CEX:',fontSize: 8},
          {text: num_documento,fontSize: 8},         
          ],
					[{text: 'Nombre/Razón Social:',fontSize: 8},
          {text: nom_a_facturar,fontSize: 8},{ },
          {text: 'Cod. Cliente:',fontSize: 8},
          {text: cod_cliente,fontSize: 8},         
          ],
				]
			},
      layout: 'noBorders'
		},
    {
        style: 'tableExample',
        margin:[0,10,0,0],
        table: {
          headerRows: 1,
          widths: [50,'*',55,34,32,37,40,40,43,40], // Ajusta los anchos de las columnas
          body: tableBody         
        },      
		},
    
    {
      text: respuesta_total ,fontSize: 7
    },
    {
      text:'Valido desde '+ fecha +' hasta '+fechaMas7Dias+',valor de vigencia para el recibo para el cambio valido hasta '+ fecha+' en la misma tienda, términos y codiciones según politica de cambios y devoluiones'
      ,fontSize: 7
    },
   
      {
        text: 'Usuario: '+ nombreCompleto_1,
        fontSize: 7
      },
    ],
    watermark: watermark, // Agrega la marca de agua condicionalmente
    styles: {
      header: {
        fontSize: 12,
        bold: true,
        color: 'black',
        alignment: 'center',
        margin:[0,10,0,10]
      },
      cabeza: {
        fontZise: 8,

      },
      tableExample:{
        fontSize: 8,
        bold: true,
      },
		tableHeader: {
			bold: true,
			fontSize: 9,
			color: 'black',
     // margin:[ 0, 0, 100,0]
		},
    tableHeader2 : {
			bold: true,
			fontSize: 7,
			color: 'black',
      margin:[ 180, 0, 20,0] 
		},
    tableHeader3 : {
			bold: true,
			fontSize: 7,
			color: 'black',
      margin:[ 50, 0, 10,0] 
		}
    }
  };
 // margin: [left, top, right, bottom]
  // Generar y abrir el PDF
  pdfMake.createPdf(docDefinition).open();
},

listarDetalle_producto_x(id,tipo_per_emp) {
            let me = this;           
            var url = "/detalle_venta_2/verCliente_x_venta?id_venta="+id;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta_1 = response.data;                                          
                    me.array_nombre_des=respuesta_1;         
                   
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },


    listarVentas(page){
        let me=this;       
        var url = "/detalle_venta_2/index?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_seleccionada_sucursal+"&codigo_tienda_almacen="+me.cod_seleccionada_sucursal+"&startDate="+me.startDate+"&endDate="+me.endDate;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayVentas = respuesta.ventas_show.data;
                    
                    console.log(me.arrayVentas);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
    },    

    factura_dosificacion(id) {
            let me = this;
           
           var url = "/detalle_venta_2/factura_dosificacion?id="+id;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                    
                   
                    if (respuesta.length===0) {
                      me.data_factura_tipo="Recibo";
                      me.data_factura_tipo_docuemnto="x";                             
                      me.data_factura_nro_factura="x";
                      me.data_factura_cod_control="x";
                      me.data_factura_nro_auto="x";
                    }else{
                      if (respuesta[0].key_0===1) {
                        me.data_factura_tipo="Factura";
                      me.data_factura_tipo_docuemnto="Dosificación";                             
                      me.data_factura_nro_factura=respuesta[0].numero_factura;
                      me.data_factura_cod_control=respuesta[0].codigo_control;
                      me.data_factura_nro_auto=respuesta[0].nro_autorizacion;
                      }
                    }
                    
                    
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
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
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarVentas(page);
        },

      
        
        detalleVenta(cod_cliente,validor_12,id,tipo,direccion,razon_social,nro_comprobante_venta,fecha_formateada,hora_formateada,
        num_documento,nom_a_facturar,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fecha_mas_siete,
        numero_referencia,nombre_completo,anulado,dosificacion_o_electronica,ciudad,departamento){
          let me=this;  
         console.log("------"+validor_12+"++++"+dosificacion_o_electronica);
        var url = "/detalle_venta_2/re_imprecion?id_venta="+id+"&tipofactura="+tipo+"&venta="+total_venta+"&total_sin_des="+total_sin_des+"&plana_ticket="+validor_12;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;   
                    let respuesta_empresa = respuesta.datos_empresa;
                    let respuesta_total = respuesta.datoTexto_v2;
                    let respuesta_descuento_1 = respuesta.venta_con_descuento;
                    let resultado_descuento_2 = respuesta.resultado_descuento_2;
                    let facturas_dosi_2 = respuesta.facturas_dosi;
                    console.log("------------8--------8-------------");
                    console.log(resultado_descuento_2.resultado);
                    if(resultado_descuento_2.resultado===null){
                      me.decuento_sin_venta="0.00";
                    }else{
                      me.decuento_sin_venta=resultado_descuento_2.resultado;  
                    }
                    
                    
                    let nit =respuesta_empresa[0].nit;  
                    let nom_empresa =respuesta_empresa[0].nom_empresa;   
                    let nro_celular =respuesta_empresa[0].nro_celular;  
                    let actividad_economica =respuesta_empresa[0].actividad_economica;  
                    me.arrayDetalle_venta = respuesta.detalle_venta;             
                    me.venta_con_descuento = respuesta_descuento_1.venta_con_descuento;
                    //factura dosificacion-------------------------
                   
                    
                    //let numero_factura_dosificacion= facturas_dosi_2[0].numero_factura;
                    
                    //---------------------------------------------
                    
                    //recibo normal tipo ticket
                     if (validor_12===1&&dosificacion_o_electronica===0) {
                     
                          me.generarPDF(nom_empresa,direccion,razon_social,nro_comprobante_venta,fecha_formateada,
                     hora_formateada,num_documento,nom_a_facturar,me.arrayDetalle_venta,total_sin_des,descuento_venta,total_venta,
                    efectivo_venta,cambio_venta,fecha_mas_siete,numero_referencia,nombre_completo,anulado);  
                       }
                       //factura tipo ticket dosificacion 
                       if (validor_12===1&&dosificacion_o_electronica===2) {
                      
                    if (facturas_dosi_2.length >0) { 
                      console.log("*******************");
                      let descuento_final = respuesta.descuento_final;
                      //console.log(facturas_dosi_2);
                      let codigo_control_dosifi=facturas_dosi_2[0].codigo_control;
                      let estado_factura_dosifi=facturas_dosi_2[0].estado_factura;
                      let fecha_e_dosifi=facturas_dosi_2[0].fecha_e;
                      let nro_autorizacion_dosifi=facturas_dosi_2[0].nro_autorizacion;
                      let numero_factura_dosifi=facturas_dosi_2[0].numero_factura;
                      let total_literal=respuesta.total_literal;
                      console.log(descuento_final);
                    
                    me.reimprecionFactura_dosificaicion(cod_cliente,id,respuesta_total,nom_empresa,direccion,razon_social,nro_comprobante_venta,fecha_formateada,
                     hora_formateada,num_documento,nom_a_facturar,me.arrayDetalle_venta,total_sin_des,descuento_venta,total_venta,
                    efectivo_venta,cambio_venta,fecha_mas_siete,numero_referencia,nombre_completo,respuesta_descuento_1,me.decuento_sin_venta,anulado,actividad_economica,nro_celular,ciudad,departamento,
                    codigo_control_dosifi,estado_factura_dosifi,fecha_e_dosifi,nro_autorizacion_dosifi,numero_factura_dosifi,nit,descuento_final,total_literal);
                    }
                    }
                    // plana tipo dosificacion
                    if (validor_12===2&&dosificacion_o_electronica===2) {
                      if (facturas_dosi_2.length >0) { 
                        console.log("*******************");
                      let descuento_final = respuesta.descuento_final;
                      //console.log(facturas_dosi_2);
                      let codigo_control_dosifi=facturas_dosi_2[0].codigo_control;
                      let estado_factura_dosifi=facturas_dosi_2[0].estado_factura;
                      let fecha_e_dosifi=facturas_dosi_2[0].fecha_e;
                      let nro_autorizacion_dosifi=facturas_dosi_2[0].nro_autorizacion;
                      let numero_factura_dosifi=facturas_dosi_2[0].numero_factura;
                      let total_literal=respuesta.total_literal;
                      let base64=respuesta.base64;
                
                      me.reimprecionFactura_dosificaicion_plana(cod_cliente,id,respuesta_total,nom_empresa,direccion,razon_social,nro_comprobante_venta,fecha_formateada,
                     hora_formateada,num_documento,nom_a_facturar,me.arrayDetalle_venta,total_sin_des,descuento_venta,total_venta,
                    efectivo_venta,cambio_venta,fecha_mas_siete,numero_referencia,nombre_completo,respuesta_descuento_1,me.decuento_sin_venta,anulado,actividad_economica,nro_celular,ciudad,departamento,
                    codigo_control_dosifi,estado_factura_dosifi,fecha_e_dosifi,nro_autorizacion_dosifi,numero_factura_dosifi,nit,descuento_final,total_literal,base64);
                  
                      }
                     }
                     //recibo normal tipo plana
                     if (validor_12===2&&dosificacion_o_electronica===0) {
                      me.generarFacPlana(cod_cliente,id,respuesta_total,nom_empresa,direccion,razon_social,nro_comprobante_venta,fecha_formateada,
                     hora_formateada,num_documento,nom_a_facturar,me.arrayDetalle_venta,total_sin_des,descuento_venta,total_venta,
                    efectivo_venta,cambio_venta,fecha_mas_siete,numero_referencia,nombre_completo,respuesta_descuento_1,me.decuento_sin_venta,anulado);
                     }
                     
                                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        detalle_venta_vista(id,tipo,total_venta,total_sin_des){
          let me=this;  
          var url = "/detalle_venta_2/re_imprecion?id_venta="+id+"&tipofactura="+tipo+"&venta="+total_venta+"&total_sin_des="+total_sin_des;
        axios
                .get(url)
                .then(function (response) {
                  var respuesta = response.data;  
                  let respuesta_descuento_1 = respuesta.venta_con_descuento;
                    let resultado_descuento_2 = respuesta.resultado_descuento_2;
                    me.suma_total=respuesta_descuento_1;                         
                  me.array_detalle_venta = respuesta.detalle_venta;
                  me.decuento_sin_venta=resultado_descuento_2.resultado;
                 
                  if(resultado_descuento_2.resultado===null){
                    me.decuento_sin_venta="0.00";
                  }else{
                    me.decuento_sin_venta=resultado_descuento_2.resultado;
                  }
                 
                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
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
                case "ver_detalle_venta":{
                  me.tituloModal = "Detalle de venta de la sucursal "+data.razon_social+" codigo "+data.cod;
                  me.factura_dosificacion(data.id);
             
                  me.cod_cliente=data.id_cliente;
                  me.nom_cliente=data.nombre_completo_cliente;
                  me.nom_facturar=data.nom_a_facturar;
                  me.num_documento=data.num_documento;
                  me.correo=data.correo;
                  me.num_identificacion=data.id;
                  me.num_reci_fac=data.nro_comprobante_venta;
                  me.fecha_x=data.fecha_formateada;
                  me.hora_x=data.hora_formateada;
                  me.recibo_ticket_plana=2;
               
                  if(data.anulado===0){
                  me.anulado_x="ACTIVO";    
                  }else{
                    me.anulado_x="ANULADO";  
                  }                  
            
                  me.descuento=data.descuento_venta;
                  me.total_1=data.total_venta;
                  me.efectivo=data.efectivo_venta;
                  me.cambio=data.cambio_venta;
                  me.nom_user=data.name;
                  me.tipo_per_emp=data.tipo_per_emp;
                  me.dosificacion_o_electronica=data.dosificacion_o_electronica;
                  me.detalle_venta_vista(data.id,data.tipo_venta_reci_fac,me.total_1,data.total_sin_des);
                  me.listarDetalle_producto_x(data.id)
                  me.classModal.openModal("ver_detalle");
                  break;
                }

                case "recibo_r": {
                  me.recibo_ticket_plana=1;
                  console.log("------------*------------");
                    console.log(data);
                    console.log("------------*------------");
                    if (data.tipo_venta_reci_fac==="RECIBO") {
                      me.detalleVenta(data.id_cliente,me.recibo_ticket_plana,data.id,data.tipo_venta_reci_fac,data.direccion,data.razon_social,data.nro_comprobante_venta,
                    data.fecha_formateada,data.hora_formateada,data.num_documento,data.nom_a_facturar,data.total_sin_des,
                    data.descuento_venta,data.total_venta,data.efectivo_venta,data.cambio_venta,data.fecha_mas_siete,
                    data.numero_referencia,data.nombre_completo_empleado,data.anulado,data.dosificacion_o_electronica,data.ciudad,data.departamento
                    );
                    } else {
                    if (data.tipo_venta_reci_fac==="FACTURA") {         
                          me.detalleVenta(data.id_cliente,me.recibo_ticket_plana,data.id,data.tipo_venta_reci_fac,data.direccion,data.razon_social,data.nro_comprobante_venta,
                    data.fecha_formateada,data.hora_formateada,data.num_documento,data.nom_a_facturar,data.total_sin_des,
                    data.descuento_venta,data.total_venta,data.efectivo_venta,data.cambio_venta,data.fecha_mas_siete,
                    data.numero_referencia,data.nombre_completo_empleado,data.anulado,data.dosificacion_o_electronica,data.ciudad,data.departamento
                    );
                        
                    } 
                  }
                    
                    break;
                }

                case "pdf_plana":{
               
               //   console.log(data.id);
              //    console.log(data);
                  if (data.tipo_venta_reci_fac==="RECIBO") {
                    me.detalleVenta(data.id_cliente,me.recibo_ticket_plana,data.id,data.tipo_venta_reci_fac,data.direccion,data.razon_social,data.nro_comprobante_venta,
                    data.fecha_formateada,data.hora_formateada,data.num_documento,data.nom_a_facturar,data.total_sin_des,
                    data.descuento_venta,data.total_venta,data.efectivo_venta,data.cambio_venta,data.fecha_mas_siete,
                    data.numero_referencia,data.nombre_completo_empleado,data.anulado,data.dosificacion_o_electronica,data.ciudad,data.departamento
                    );              
                  } 
                  if (data.tipo_venta_reci_fac==="FACTURA") {         
                          me.detalleVenta(data.id_cliente,me.recibo_ticket_plana,data.id,data.tipo_venta_reci_fac,data.direccion,data.razon_social,data.nro_comprobante_venta,
                    data.fecha_formateada,data.hora_formateada,data.num_documento,data.nom_a_facturar,data.total_sin_des,
                    data.descuento_venta,data.total_venta,data.efectivo_venta,data.cambio_venta,data.fecha_mas_siete,
                    data.numero_referencia,data.nombre_completo_empleado,data.anulado,data.dosificacion_o_electronica,data.ciudad,data.departamento
                    );
                        
                    } 

                 
                  break;
                }
                
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.classModal.openModal("registrar");

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
     
        eliminar(id,fecha_i,fecha_f,dosificacion_o_electronica,tipo_venta_reci_fac) {
            let me = this;  
            let validador_x_estado=0;
          
    // Función para convertir una fecha en formato dd/mm/yyyy a un objeto Date
function parseDate(dateString) {
    const [day, month, year] = dateString.split('/').map(Number);
    return new Date(year, month - 1, day);
}

// Fechas en formato dd/mm/yyyy
const startDateStr = fecha_i;
const endDateStr = fecha_f;

// Convertir las fechas a objetos Date
const startDate = parseDate(startDateStr);
const endDate = parseDate(endDateStr);

// Obtener la fecha actual
const today = new Date();

// Comprobar si la fecha actual está dentro del rango
const isInRange = today >= startDate && today <= endDate;

        if (isInRange===false) {
              Swal.fire(
                    "Error",
                    "La venta no puede ser anulada ya que paso con el tiempo de vigencia",
                    "error");
            } else {
              const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            swalWithBootstrapButtons
                .fire({
                    title: "Esta Seguro de Desactivar?",
                    text: "Es una eliminacion logica",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .post("/detalle_venta_2/desactivar", {
                              id_modulo: me.idmodulo,
                                id_sub_modulo:me.codventana, 
                                des:"eliminacion_de_venta",  
                                id: id,
                                
                            })
                            .then(function (response) {
                               // me.listarAjusteNegativos();
                               me.listarVentas();
                                swalWithBootstrapButtons.fire(
                                    "Desactivado!",
                                    "El registro eliminado",
                                    "success",
                                );
                             //   me.listarAjusteNegativos();
                            })
                    
                       .catch(function (error) {                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
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
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
          
            }
            
        },

        cerrarModal(accion) {
            let me = this;           
                me.classModal.closeModal(accion);               
                me.tituloModal = "";
                me.cod_cliente="";
                  me.nom_cliente="";
                  me.nom_facturar="";
                  me.num_documento="";
                  me.correo="";
                  me.num_identificacion="";
                  me.num_reci_fac=""
                  me.fecha_x="";
                  me.hora_x="";
                  me.anulado_x="";                                   
                  me.suma_total="";
                  me.descuento="";
                  me.total_1="";
                  me.efectivo="";
                  me.cambio="";
                  me.nom_user="";
                  me.tipo_per_emp="";
                  me.array_nombre_des=[];
                  me.array_detalle_venta=[];
                  me.venta_con_descuento="";
                  me.decuento_sin_venta="";
                  me.dosificacion_o_electronica="";
                  me.data_factura_tipo="";
                  me.data_factura_tipo_docuemnto="";       
                  me.data_factura_nro_factura="";
                  me.data_factura_cod_control="";
                  me.data_factura_nro_auto="",
                  me.classModal.openModal("ver_detalle");
         },

     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
       //-------permiso E_W_S-----
       this.listarPerimsoxyz();
             // this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
        this.classModal = new _pl.Modals();
        this.sucursalFiltro();
        this.fecha_inicial();
        this.classModal.addModal("registrar");

        this.classModal.addModal("ver_detalle");
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
