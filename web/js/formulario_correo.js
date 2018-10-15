/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//valida si los dos correos son iguales
function comparaCorreo()
{
    
    //window.alert('entro');
    //var correo1 = document.getElementById('correo').value;
    //window.alert(document.getElementById('correo').value);
    //window.alert(document.getElementById('correo2').value);
    if(document.getElementById('correo').value !== document.getElementById('correo2').value)
    {
        window.alert('Los correos ingresados no son iguales, ingrese nuevamente su correo');
    }
}

