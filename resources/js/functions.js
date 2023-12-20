

export class Modals{ 
  constructor() { this.modales=new Map();} 
   addModal(id) { 
    $("#"+id).children(".modal-dialog" ).addClass("modal-dialog-centered");
    $("#"+id).attr('data-backdrop', 'static');
    $("#"+id).attr('data-keyboard', 'false');

        this.modales.set(id, { 
        openPrimary: function() { $("#"+id).modal("show"); },
        close: function() { $("#"+id).modal("hide"); },
        onclose:function() { $("#"+id).on('hidden.bs.modal', function () { console.log('se cerro el modal con id='+id); });},
        openInfo: function() {$("#"+id).modal({backdrop: true, keyboard: true});}});  
    }
    
   openModal(id) { 
        if(this.modales.has(id)){ 
          this.closeOthers(id);
          this.modales.get(id).openPrimary(); 
          }
      }

   openModalInfo(id) {
        if(this.modales.has(id)){
          this.closeOthers(id);
          this.modales.get(id).openInfo();  
          }
      }

   closeModal(id) {
      if(this.modales.has(id)){
        this.modales.get(id).close();
        }
    }
    
    closeOthers(id){
      for(var [key, data] of this.modales){
        if(key!=id){
          data.close();
        }
      }
    }
}

export function datapicker(id,fechamin,fechamax,fechaini){
  $("#"+id).daterangepicker({
    singleDatePicker: true,
    forceUpdate: true,
    autoUpdateInput:true,
    autoApply:true,
    showDropdowns: true, 
    maxDate:fechamax,
    minDate: fechamin,
    startDate:fechaini,
    locale: {
      separator: "   |   ",
      format: "YYYY-MM-DD", 
      applyLabel: "Seleccionar",
      cancelLabel: "cancelar",
      fromLabel: "Desde",
      toLabel: "Hasta",
      customRangeLabel: "Seleccionar rango",
      daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre",
        "Diciembre"],
      firstDay: 1
    }
     
    });

}
export function datatime(id){
  $("#"+id).datetimepicker({  
     autoclose: 1,
     startView: 3,	
     minView: 3,  
     pickerPosition:'bottom-right',
     format: 'MM - yyyy'
  });
}


export function getdatapicker(id){
  $("#"+id).val(); 
}

export function viewPDF(t, l) {
  return swal({
    html: '<div id="framepdf" style="display:none;"><div style=" width: 100%; "><label style="display: inline;padding-left: 23px;font-weight: 500;float: left;">' 
          + l + '</label> <button id="close_id_swal" class="close" style="float: right; margin: 0 15px 2px 5px; ">x</button> </div><iframe id="printpdf" name="printpdf" src="' 
          + t + '" style="width:100%;height:800px;" allowfullscreen></iframe></div>',
    showConfirmButton: !1,
    showCancelButton: !0,
    allowOutsideClick: !1,
    allowEscapeKey: !1,
    confirmButtonText: "Ok",
    cancelButtonText: "Cerrar",
    confirmButtonColor: "#4dbd74",
    cancelButtonColor: "#f86c6b",
    buttonsStyling: !0,
    reverseButtons: !0,
    onBeforeOpen: function () {
      swal.showLoading(), swal.disableButtons(), 
      $("#close_id_swal").click(function () {
        swal.close()
      }), $("#printpdf").on("load", function () {
        $("#framepdf").css("display", "inline"), swal.hideLoading(), $(".swal2-popup").css("width", "90em"), $(".swal2-popup").css("padding", "0px 0px 20px 0px")
      })
    }
  })
}
 
