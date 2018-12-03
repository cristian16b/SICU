
//recibe una fecha en formato aaaa-mm-dd
//devuelve fecha en formato dd-mm-aaaa
function obtengoFechaFormato(fecha)
{
    var array = fecha.split("-");
    var salida = null;
    if(array.length > 0)
    {
        salida = array[2] + '-' + array[1] + '-' + array[0];
    }
    return salida;
}

