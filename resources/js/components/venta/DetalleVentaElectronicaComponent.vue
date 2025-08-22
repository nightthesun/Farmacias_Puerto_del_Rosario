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
                    <i class="fa fa-align-justify"></i> Transacciónes Electronica               
                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursales:</label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.id_almacen!=null"
                                        v-text="sucursal.codigoS +' -> ' +sucursal.codigo+' ' +sucursal.razon_social">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar por numero de comprobante o tipo electronico"
                                    v-model="buscar"
                                @keyup.enter="listarIndex(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              @click="listarIndex(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
            </div>             
       
            <div class="alert alert-primary" role="alert">
  Para re imprimir se debe ser super usaurio y debe ser cerrado la apertura.
</div>

            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Num. Apert.</th>
                        <th class="col-md-1">Comprobante</th>
                        <th class="col-md-1">Datos adicionales</th>
                        <th class="col-md-1">Banco</th>
                        <th class="col-md-1">Tipo elect.</th>
                        <th class="col-md-1">Tipo venta</th>
                        <th class="col-md-1">Monto</th>
                        <th class="col-md-2">Fecha venta</th>
                        <th class="col-md-2">Venta impreción</th>
                        <th class="col-md-1">Estado</th>                           
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td>
                            <div class="btn-group">
  <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu">
      <a  v-if="i.contador==0 && superUser==1" @click="subirCambio(1,i.id,i.id_apertura)" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i>Imprimir ticket</a>
      <a  v-else class="dropdown-item" href="#" style="color: white;"><i style="color: white;" class="fa fa-file-pdf-o" aria-hidden="true"></i>Imprimir ticket</a>
    <a  v-if="i.contador==0  && superUser==1"  @click="subirCambio(2,i.id,i.id_apertura)" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i>Imprimir apertura</a>
    <a  v-else class="dropdown-item" href="#" style="color: white;"><i style="color: white;" class="fa fa-file-pdf-o" aria-hidden="true"></i>Imprimir apertura</a>
     </div>
</div>
                        </td>
                        <td class="col-md-1">{{ i.id_apertura }}</td>
                        <td class="col-md-1">{{ i.num_tar_o_boleto }}</td>
                        <td class="col-md-1">{{ i.mas_datos }}</td>
                        <td class="col-md-1">{{ i.nombre_banco }}</td>                        
                        <td class="col-md-1">{{ i.tipo }}</td>
                        <td class="col-md-1">{{ i.tipo_venta }}</td>
                        <td class="col-md-1">{{ i.monto_apagar+" "+i.simbolo }}</td>
                        <td class="col-md-2">{{ i.venta_fecha }}</td>
                        <td class="col-md-2">{{ i.impre_fecha }}</td>
                        <td class="col-md-1">
                            <span v-if="i.contador==1" class="badge badge-pill badge-danger">Sin acción</span>
                            <span v-else class="badge badge-pill badge-success">Impreso</span>
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
                                   
                                   
        
                                </div>
                              
                            
                               
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary">
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
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

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
            showModal: false,
            offset:3,
            arrayIndex:[],
            selectTipo:"0",
            id_seleccionada_sucursal:0,
            tituloModal: "",
            sucursalSeleccionada:0,
         
            superUser:'',

            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',
      
      arraySis:[],
      simbolo_v2:'',
      nombreEmpresa:'',

      nombreSucursal:'', 

        };
    },
    watch: {
    sucursalSeleccionada(valor) {
      if (valor !== 0) {
        let sucursal = this.arraySucursal.find(element => element.codigo === valor);

        if (sucursal) {
          this.id_seleccionada_sucursal = sucursal.id_sucursal;    
          this.nombreSucursal = sucursal.razon_social;    
        }

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
     ////////--------------------- STAR PDF--------------------///////////////
     general_pdf(razon_social,sucursal,direccion,lugar,array_pdf,id_apertura,valor_total,simbolo,name){
   
   // Crea el cuerpo de la tabla dinámicamente
const tableBody = [
   // Agrega los encabezados de la tabla
   [
     { text: 'NUM.', style: 'tableHeader_2' }, 
     { text: 'MONTO', style: 'tableHeader_2' }, 
     { text: 'DOC.', style: 'tableHeader_2' }, 
     { text: 'TIPO.', style: 'tableHeader_2' },
     { text: 'FECHA.', style: 'tableHeader_2' }, 
   ]
 ];

 // Itera sobre los datos y agrega filas a la tabla
 array_pdf.forEach(item => {
   tableBody.push([
     { text: item.nro_comprobante_venta, fontSize: 6, alignment: 'center' },
     { text: item.monto_apagar+' '+simbolo, fontSize: 6, alignment: 'center' },
     { text: item.tipo_venta, fontSize: 6, alignment: 'center' },
     { text: item.tipo, fontSize: 6, alignment: 'center' },
     { text: item.created_at, fontSize: 6, alignment: 'center' },
   ]);
 });

   const documentDefinition = { 
       pageMargins: [6, 8, 6, 4], // Configura los márgenes en cero
       pageSize: {
   width: 80 * 2.83465, // Ancho en puntos (conversión a puntos desde mm)
   height: 'auto',
   columnGap: 2,
 },
 content: [
   { text: razon_social.toUpperCase(), style: 'header'},
   { text: sucursal.toUpperCase(), style: 'header_2'},
   { text: direccion.toUpperCase()+" "+lugar.toUpperCase(), style: 'header_2'},
   {
      text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
      style:'linea_2' 
   },
   { text: 'CAJA TRANSACCIÓN', style: 'header_2'},
   {
      text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
      style:'linea_2' 
   },
   {
       style: 'tableExample',
       table: {
         headerRows: 1,
         widths: ['13%', '25%', '20%', '10%','32%'], // Ajusta los anchos de las columnas
         body: tableBody
       },
       layout: 'noBorders'
       },
       
       {
      text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
      style:'linea_2' 
   },
   {style: 'datos_f',
       table: {
               body: [
                   ['NUM. APERTURA: ',id_apertura],
                   ['MONTO TOTAL: ', valor_total+' '+simbolo],  
                   ['USUARIO: ', name]               
               ]
           },
               layout: 'noBorders'
       },
     
 ],
 styles: {
         header: {
           fontSize: 7,
           bold: true,
           alignment: 'center',         
         },
         header_2: {
           fontSize: 7,            
           alignment: 'center',         
         },
         linea_2: {
           fontSize: 9,
           margin: [1, 1, 1, 1],
           alignment: 'center',  
           },
       datos_f: {
           fontSize: 7,         
           alignment: 'left',         
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
/////////////////////////////END PDF/////////////////////////////////////
      
        subirCambio(data,id,id_apertura) {
            let me = this;  
        
            var cadena_tipo_imprecion="";   
            const arrayResultado = [];  
          
            var ciudad_v2="";
            var lugar_v2="";
            var nombre_v2="";
            let totalMonto = 0;
        
                if (data==1) {
                    cadena_tipo_imprecion="TICKET";
                    const array = me.arrayIndex.find(element => element.id === id);
                  
                  if (array) {
                    ciudad_v2=array.ciudad;
                    lugar_v2=array.direccion;
                    nombre_v2=array.nom_empleado;
                    totalMonto=array.monto_apagar;
                    console.log(ciudad_v2);
                    arrayResultado.push({
                        nro_comprobante_venta: array.nro_comprobante_venta,
                        monto_apagar: array.monto_apagar,
                        tipo_venta: array.tipo_venta,
                        tipo:array.tipo,
                        created_at: array.venta_fecha,                       
                    });
                  
                    }
                  
                me.general_pdf(me.nombreEmpresa,me.nombreSucursal,lugar_v2,ciudad_v2,arrayResultado,id_apertura,totalMonto,me.simbolo_v2,nombre_v2);          
             //  me.general_pdf(dato_seelccionado.nombre_sucursal,dato_seelccionado.direccion,dato_seelccionado.ciudad,dato_seelccionado.tipo,arrayResultado,cadena_tipo_imprecion,dato_seelccionado.monto_apagar,dato_seelccionado.simbolo,dato_seelccionado.user_name,dato_seelccionado.nom_empleado,dato_seelccionado.tipo_venta);   
                } else {
                    cadena_tipo_imprecion="APERTURA";
                    const array = me.arrayIndex.filter(element => element.id_apertura === id_apertura);
                    if(array){
                        ciudad_v2 = array[0].ciudad;
                        lugar_v2 = array[0].direccion;
                        nombre_v2=array[0].nom_empleado;
                        
                        for (let index = 0; index < array.length; index++) {
                        const element = array[index];                     
                        arrayResultado.push({
                        nro_comprobante_venta: array[index].nro_comprobante_venta,
                        monto_apagar: array[index].monto_apagar,
                        tipo_venta: array[index].tipo_venta,
                        tipo:array[index].tipo,
                        created_at: array[index].venta_fecha, 
                    });
                    totalMonto += parseFloat(element.monto_apagar); // asegúrate que es número
                        }
                        console.log("----------------------------*");
                        console.log(arrayResultado);
                        console.log("---------------------------*");
                        
                        totalMonto = totalMonto.toFixed(2);
                    }  
                   
                    me.general_pdf(me.nombreEmpresa,me.nombreSucursal,lugar_v2,ciudad_v2,arrayResultado,id_apertura,totalMonto,me.simbolo_v2,nombre_v2);
                }                                                      
        },
       
        listarIndex(page){
        let me=this;       
        var url = "/ven_trasnferencia/listarInicio?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_seleccionada_sucursal;
        axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                 
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;  
                               
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
    },

        sucursalFiltro() {
            let me = this;
   
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
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


        usuarioEspecial() {
            let me = this;   
           var url = "/usuarioEspecial";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;               
                    me.superUser=respuesta;
                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        listarConfigsis() {
            let me = this;   
           var url = "/listarConfig_v2";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;   
                    me.simbolo_v2=respuesta.simbolo;
                    me.nombreEmpresa=respuesta.nom_empresa;
       
                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
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
                    me.tituloModal = "Ejemplo titulo";
                    me.showModal = true;
                    me.classModal.openModal("registrar");
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
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = " ";
             
             
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
        this.listarConfigsis();
        this.classModal.addModal("registrar");
        this.usuarioEspecial();
    
    
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
