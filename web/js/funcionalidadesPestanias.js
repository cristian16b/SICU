/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    $("#botonConfirmar").click(function(){
                                alert('llega');
                                muestraErrores();
                });        
            });


function muestraErrores()
            {
          alert('llega');
            var errorpersona = "";
            var errorlicencia = " ";
            var errorfamiliar = " ";
            var errorconfirmar = " ";
            
            
            var tipoLicencia = $("#tipoLicencia").val();
            //alert(tipoLicencia);
            
            ///  PREGUNTAR 
            if($("#codAreaI").val().length === 0  || 
            $("#codAreaII").val().length === 0 ||
            $("#telefonoI").val().length ===  0 ||
            $("#telefonoII").val().length ===  0 )
            {
                 errorpersona += 'Datos Personales - Debe especificar su telefono.';
                 
            }
            if($("#calle").val().length === 0 )
            {
                //alert('Datos Personales - Debe especificar la calle de su domicilio.');
                errorpersona += '<br/> Datos Personales - Debe especificar la calle de su domicilio.';
            }
            if($("#numero").val().length === 0 )
            {
                //alert('Datos Personales - Debe especificar el numero de su domicilio.');
                errorpersona += '<br/> Datos Personales - Debe especificar el numero la calle de su domicilio.';
            }
            
            //alert(tipoLicencia);
            if(tipoLicencia === "Atención Fliar. Enfermo")
            {
                //alert('pasa');
                if($("#tipoDniFamiliar").val().length === 0 )
                {
                    errorfamiliar += '<br/> Datos Familiar - Debe especificar el tipo de documento de su familiar.';
                }
                //alert(errorfamiliar);
                if($("#numDniFamiliar").val().length === 0 )
                {
                    errorfamiliar += '<br/> Datos Familiar - Debe especificar el numero de documento de su familiar.';
                }
                //alert(errorfamiliar);
                if($("#nombreFamiliar").val().length === 0 )
                {
                    errorfamiliar += '<br/> Datos Familiar - Debe especificar el nombre y apellido de su familiar.';
                }
                //alert(errorfamiliar);
                if($("#fechaNacimientoFamiliar").val().length === 0 )
                {
                    errorfamiliar += '<br/> Datos Familiar - Debe especificar la fecha de nacimiento de su familiar.';
                }
                //alert(errorfamiliar);
                if($("#parentescoFamiliar").val().length === 0 )
                {
                    errorfamiliar += '<br/> Datos Familiar - Debe especificar el parentesco con su familiar.';
                }
                //alert(errorfamiliar);
                if($("#conviveFamiliar").is(":checked") === false)
                {
                    errorfamiliar += '<br/> Datos Familiar - Debe especificar si convive con su familiar.';
                }
                //alert(errorfamiliar);
            }
                  //alert('entra');
                  
            if(tipoLicencia === "Preparto")
            {   
                
                if($("#fechaPosibleParto").val().length === 0 )
                {
                    errorlicencia += '<br/> Datos Licencia - Debe especificar la fecha de posible parto.';
                }
                
            }
            
            if(tipoLicencia === "Maternidad")
            {
                
                if($("#fechaNacimiento").val().length === 0 )
                {
                    //alert('Datos Licencia - Debe especificar fecha de nacimiento.');
                    errorlicencia += '<br/> Datos Licencia - Debe especificar fecha de nacimiento.';
                }
           
                if($("#fechaInicioLicencia").val().length === 0 )
                {
                    //alert('Datos Licencia - Debe especificar fecha de inicio licencia.');
                    errorlicencia += '<br/> Datos Licencia - Debe especificar fecha de inicio licencia.';
                }
                
                if($("#pesoKg").val().length === 0 )
                {
                    //alert('Datos Licencia - Debe el peso');
                    errorlicencia += '<br/> Datos Licencia - Debe especificar el peso.';
                }
                
                if($("#cantidadHijosVivos").val().length === 0 )
                {
                    //alert('Datos Licencia - Debe indicar la cantidad de hijos');
                    errorlicencia += '<br/> Datos Licencia - Debe especificar indicar la cantidad de hijos.';
                }
               
                
            }
            
            if(tipoLicencia === "OTRAS (Relacionadas a la MATERNIDAD)")
            {
                if($("#fechaFallecimiento").val().length === 0 )
                {
                    errorlicencia += '<br/> Datos Licencia - Debe especificar la fecha de fallecimiento.';
                }
                if($("#fechaNacidoFallecido").val().length === 0 )
                {
                    errorlicencia += '<br/> Datos Licencia - Debe especificar la fecha de nacimiento.';
                }
            }
            
           
            if($("#fechaFinLicencia").val().length === 0 )
            {
                //alert('Datos Licencia - Debe especificar fecha de fin licencia.');
                errorlicencia += '<br/> Datos Licencia - Debe especificar indicar la fecha de fin licencia.';
            }
            if($("#matricula").val().length === 0 )
            {
                //alert('Datos Licencia - Debe especificar la matricula del mèdico.');
                errorlicencia += '<br/> Datos Licencia - Debe especificar la matricula del mèdico.';
            }
            if($("#codCIE").val().length === 0 )
            {
                //alert('Datos Licencia - Debe especificar el còdigo CIE.');
                errorlicencia += '<br/> Datos Licencia - Debe especificar el cod. CIE.';
            }
            if($("#confirmaSolicitud").is(":checked") === false)
            {
                errorconfirmar += '<br/>No ha confirmado que acepta los terminos y condiciones.';
            }
            

            if(errorpersona.length !== 0 || errorlicencia.length !== 0 || 
               errorfamiliar.length !== 0 || errorconfirmar.length !== 0)
            {
                //alert("error persona " errorpersona.length + " error" + errorfamiliar +  errorconfirmar )
                var colortag = "<font color="+"red"+">";
                var icono = "<b><i class="+"material-icons"+">warning</i> Debe completar los siguientes campos:</b>";
                var titulo = colortag + icono + "</font>";
                $("#error").html(titulo);
                
                var salida = "<p>" + errorpersona + errorlicencia + errorfamiliar + errorconfirmar + "</p>";
                $("#mensajeError").html(salida);
                
            }
        }
  
  
   function preparoInputsSolicitar()
            {
               
                
                //recupero los datos de sessionStorage
                var tipoLicencia = sessionStorage.getItem("tipoLicencia");
                var decretoDocentes = sessionStorage.getItem("decretoDocentes");
                var decretoAsistentes = sessionStorage.getItem("decretoAsistentes");

               //alert(tipoLicencia + decretoDocentes + decretoAsistentes);

                //seteo el encabezado
                setEncabezado(tipoLicencia,decretoDocentes,decretoAsistentes);

                //seteo la pestaña datos familiar
                setPestañaFamiliar(tipoLicencia);

                //seteo la pestaña licencia
                setPestañaLicencia(tipoLicencia,decretoDocentes,decretoAsistentes);
            }

            //seteo los atributos de la ventana moda
            function setEncabezado(tipoLicencia,decretoDocentes,decretoAsistentes)
            {
                alert('llega');
                //seteo el valor de licencia
                $("#tipoLicencia").val(tipoLicencia);
                //alert(decretoDocentes + decretoAsistentes);

                //$(".decretoDocentes").hide();
                //oculto y check el decreto que se debe mostrar
                if(decretoAsistentes !== "true")
                {
                    $("#decretoAsistentes").hide();
                    $("#labelAsistentes").hide();
                    $("#colmAsistentes").hide();
                }
                else
                {
                    $("#decretoAsistentes").show();
                    $("#labelAsistentes").show();
                    $("#colmAsistentes").show();
                }
                if(decretoDocentes !== "true")
                {
                    $("#decretoDocentes").hide();
                    $("#labelDocentes").hide();
                }
                else
                {
                    $("#decretoDocentes").show();
                    $("#labelDocentes").show();

                }
            }

            function setPestañaFamiliar(tipoLicencia)
            {
                alert('ll');
                //habilito o deshabilito la pestaña de datos de familiares
                //tipoLicencia !== "Atención Fliar. Enfermo" ?  $("#datosFamiliar").hide()  :  $("#datosFamiliar").show();
            }

            function setPestañaLicencia(tipoLicencia,decretoDocentes,decretoAsistentes)
            {
                alert('lse');
                decretoDocentes !== true ? (
                                            $("#selectDocentes").hide() , $("#labelDocentesSelect").hide() 
                                            )
                                            :(
                                            $("#selectDocentes").show() , $("#labelDocentesSelect").show()
                                            );

                decretoAsistentes !== true ? (
                                             $("#selectAsistentes").hide() , $("#labelAsistentesSelect").hide() , $("#divSelectLicenciaAsistentes").hide(),
                                             $("#otrodivSelectLicenciaAsistentes").hide()
                                             )  
                                             :(  
                                             $("#selectAsistentes").show() , $("#labelAsistentesSelect").show()  , $("#divSelectLicenciaAsistentes").show(),
                                             $("#otrodivSelectLicenciaAsistentes").show()
                                             );
                //si la licencia es de preparto 
                //muestro el row con el input de fecha tentativa de parto
                tipoLicencia !== "Preparto" ? $("#divPosibleParto").hide() : $("#divPosibleParto").show();

                //si la licencia es de maternidad
                //
                tipoLicencia !== "Maternidad" ? ( $("#divMaternidad").hide() , $("#divInfoMedicaMaternidad").hide() , $("#divDiscapacidad").hide() ) 
                                               :( $("#divMaternidad").show() , $("#divInfoMedicaMaternidad").show() , $("#divDiscapacidad").show() ) ;

                //si la licencia es por otros (relacionados a maternidad)
                //
                tipoLicencia !== "OTRAS (Relacionadas a la MATERNIDAD)" ? (   $("#divFallecimiento").hide() )
                                                                        :
                                                                          (   $("#divFallecimiento").show()  ); 


            }