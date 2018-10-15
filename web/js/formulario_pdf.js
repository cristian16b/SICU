/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



function generarPdf()
{
    //creo el objeto pdf
    var pdf = new jsPDF();
    //leo todas las variables del almacenamiento del navegador
    var apellido = sessionStorage.getItem('apellido');
    var nombre = sessionStorage.getItem('nombre');
    var dni = sessionStorage.getItem('dni');
    var tipo = sessionStorage.getItem('tipo');
    var facultad = sessionStorage.getItem('facultad');
    var carrera = sessionStorage.getItem('carrera');
    var correo = sessionStorage.getItem('correo');
    var codtelefono = sessionStorage.getItem('codtelefono');
    var telefono = sessionStorage.getItem('telefono');
    var celiaco = sessionStorage.getItem('celiaco');
    var vegetariano = sessionStorage.getItem('vegetariano');
    var sede = sessionStorage.getItem('sede');
    var fecha = sessionStorage.getItem('fecha');
    var horario = sessionStorage.getItem('horario');
    //escribo
    fila = 30;
    pdf.setFontType('bold');
    pdf.text(30,fila,'Ficha de solicitud Credencial Comedor UNL - ATE');
    pdf.setFontType('normal');
    fila = fila + 15;
    pdf.text(10,fila,'Apellido: ' + apellido);
    fila = fila + 10;
    pdf.text(10,fila,'Nombre: ' + nombre);
    fila = fila + 10;
    pdf.text(10,fila,'DNI: '+dni);
    fila = fila + 10;
    pdf.text(10,fila,'Tipo de comensal: '+tipo);
    fila = fila + 10;
    pdf.text(10,fila,'Facultad: '+facultad);
    fila = fila + 10;
    if(tipo !== 'Invitado' && tipo !== 'Docente - No docente')
    {
        pdf.text(10,fila,'Carrera: '+carrera);
        fila = fila + 10;
    }
    pdf.text(10,fila,'Correo: '+correo);
    fila = fila + 10;
    pdf.text(10,fila,'Telefono: '+codtelefono+' - '+telefono);
    fila = fila + 10;
    pdf.text(10,fila,'Celiaco: ' + celiaco);
    fila = fila + 10;
    pdf.text(10,fila,'Vegetariano: ' +vegetariano);
    //linea final
    if(tipo === 'Estudiante de carrera de grado')
    {
        fila = fila + 10;
        pdf.setFontType('bold');
        pdf.text(10,fila,'Presentese el día ' + fecha +' en la sede ' + sede + ' el horario ' + horario +' ');
        fila = fila + 7;
        pdf.text(10,fila,'con el certificado de alumno regular.');
        fila = fila + 7;
        pdf.text(10,fila,'Recuerde que el mismo debe tener fecha de emisión del presente año.');
        pdf.setFontType('italic');
        fila = fila + 10;
        pdf.text(10,fila,'Recomendamos llegar con un minimo de 10 minutos antes del turno solicitado');
        fila = fila + 7;
        pdf.text(10,fila,'para no demorar la entrega de credenciales.');
    }
    else if(tipo === 'Docente - No docente')
    {
        pdf.setFontType('bold');
        fila = fila + 10;
        pdf.text(10,fila,'Presentese el día ' + fecha +' en la sede ' + sede + ' el horario ' + horario +'.');
        fila = fila + 7;
        pdf.text(10,fila,'con su recibo de sueldo cuya fecha de emisión sea el presente año.');
        pdf.setFontType('italic');
        fila = fila + 10;
        pdf.text(10,fila,'Recomendamos llegar con un minimo de 10 minutos antes del turno solicitado');
        fila = fila + 7;
        pdf.text(10,fila,'para no demorar la entrega de credenciales.');
    }
    else if(tipo === 'Invitado')
    {
        pdf.setFontType('bold');
        fila = fila + 10;
        pdf.text(10,fila,'Presentese el día ' + fecha +' en la sede ' + sede + ' el horario ' + horario +'.');
        fila = fila + 10;
        pdf.setFontType('italic');
        pdf.text(10,fila,'Recomendamos llegar con un minimo de 10 minutos antes del turno solicitado');
        fila = fila + 7;
        pdf.text(10,fila,'para no demorar la entrega de credenciales.');
    }
    fila = fila + 7;
    pdf.text(10,fila,'Si desea cancelar el turno comunicarse al correo comedor@unl.edu.ar');
    //genero el archivo descargable
    pdf.save('Solicitud de credencial Comedor Universitario.pdf');
}