<template> 
   <v-select  v-if="mostrarselect==1" label="nombre" v-model="idsocio" ref='vueselect'  
     :placeholder="textoplace" 
     :options="options"
     :searchable="validador"
     :filterable="false"
     :clearable="clearSelection"
      
   @input="inputclear"   @search="buscarajax" @search:blur='blur'>
    <template slot="no-options">
      No se encontraron datos
    </template>
    <template slot="option" slot-scope="option">
      <div class="d-center">  
             <div v-html="contenidoview(option)"> 
             </div> 
      </div>
    </template>
    <template slot="selected-option" slot-scope="option3">
      <div class="selected d-center" :style="styleselected?styleselected:''" v-html="contenidoview(option3)">  
      </div>
    </template>
  </v-select>
</template>

<script>
import vSelect from "vue-select";

Vue.component("v-select", vSelect);
  export default {
      props: ["ruta","id","idtabla","placeholder","labels","resp_ruta","clearable","keyIn","selectedStyle"],
      data() {
    return {dataout:'',
      options: [],styleselected:this.selectedStyle, idsocio:this.id,idtablainto:null,keyI:this.keyIn, textoplace:this.placeholder, mostrarselect:0,  validador:true,clearSelection:false,
      valueslabels:this.labels.split(",")
      }; 
  },  
   methods: {
 contenidoview(va){ 
    var outs=""; 
    _.forEach(this.valueslabels, function(value) {
    if (value == "rutafoto") {
      if (va[value]) {
        outs = outs.trim().length > 0 ?
          "&nbsp;" + '<img  src="storage/socio/' + va[value] + '" style="margin: 3px 9px 3px 3px !important;" class="rounded-circle fotosociomini" alt="Cinque Terre">' :
          '<img  src="storage/socio/' + va[value] + '" style="margin: 3px 9px 3px 3px !important;" class="rounded-circle fotosociomini" alt="Cinque Terre">';
      } else {
        outs = outs.trim().length > 0 ?
          "&nbsp;" + '<img  src="storage/socio/avatar.png" style="margin: 3px 9px 3px 3px !important;" class="rounded-circle fotosociomini" alt="Cinque Terre">' :
          '<img  src="storage/socio/avatar.png" style="margin: 3px 9px 3px 3px !important;" class="rounded-circle fotosociomini" alt="Cinque Terre">';
      }
    } else {
      outs = outs + "&nbsp;" + va[value];
    }

    });
    return outs;
  },blur(){ 
   if (this.dataout != null) this.$emit('next',this.nextF); 
  },
  nextF(){
    $(this.$refs.vueselect.$el).parents(".filacontable").find('#input1').focus();
     this.$refs.vueselect.searchEl.blur(); 
  },ifocus(){
     this.$refs.vueselect.searchEl.focus(); 
  },
  inputclear(varrr) {
     this.dataout=varrr;
      this.valueslabels = this.labels.split(",");
      if (varrr == null) {
        this.options = [];
        this.mostrarselect = 1;
        this.validador = true;
        this.idsocio = 0;
        this.$emit('cleaning', this.keyI);
        this.ifocus(); 
      } else {
        this.validador = false;
        this.$emit('found', varrr, this.keyI);  
        this.options = [];
      }
    },
    buscarajax(search, loading) {
      loading(true);
      this.dataout=null;
      this.busqueda(loading, search, this);
    },
    busqueda: _.debounce((loading, wordsearch, bodythis) => {

      axios.get(bodythis.ruta + wordsearch).then(function (res) {
        bodythis.options = res.data[bodythis.resp_ruta];
        loading(false);
      }).catch(function (error) {
        console.log(error);
      });


    }, 350),
  },
  mounted() {
    this.dataout=null;
    this.mostrarselect = 0;
    this.valueslabels = this.labels.split(",");

    this.textoplace = this.placeholder;
    this.options = [];
    this.idsocio = 0;
    this.idtablainto = null;
    this.ruta = this.ruta;
    this.clearSelection = this.clearable ? true : false;
    if (this.idtabla) {
      this.idtablainto = this.idtabla;
    }

    if (this.id) {
      let me = this;
      axios.get(this.ruta + '&id=' + parseInt(this.id)).then(function (res) {
        me.options = (res.data[me.resp_ruta]);
        me.mostrarselect = 1;
        me.idsocio = _.find(me.options, [me.idtablainto, parseInt(me.id)]);
      }).catch(function (error) {
        console.log(error);
      });
    } else {
      this.options = [];
      this.mostrarselect = 1;
      this.idsocio = 0;
    }
  }
  }
</script>
<style>
 

.d-center {
  display: flex;
  align-items: center;
} 
</style>