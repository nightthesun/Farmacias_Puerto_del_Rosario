import Swal from 'sweetalert2';

export function error401(err)
{
    if(err.response)
    {
      if(err.response.status == 401 || err.response.status == 419)
      {
         Swal.fire('Su session acaba de finalizar')
         .then(()=>{
            window.location.reload();
         });
      }
    }
}