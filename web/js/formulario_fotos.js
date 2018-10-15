/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function mostrarVideo()
{
    //habilito los div
    $('#canvasdiv').show();
    $('#videodiv').show();
            
    
    //accedo al video
    var video = document.getElementById('video');
    //???
    video.style.display = "block";
    
    //solicita habilitacion al usuario
    navigator.getMedia
    (
        { 
          video: true, 
          audio: false 
        },
        function(stream) {
          if (navigator.mozGetUserMedia) { 
            video.mozSrcObject = stream;
          } else {
            var vendorURL = window.URL || window.webkitURL;
            video.src = vendorURL ? vendorURL.createObjectURL(stream) : stream;
          }
          video.play();
        },
        function(err) {
          console.log("An error occured! " + err);
        }
    );    
}

function ocultarVideo()
{
  //oculto los div
  $('#canvasdiv').hide();
  $('#videodiv').hide();
    
  //fuente
  //https://developer.mozilla.org/en-US/docs/Web/API/MediaStreamTrack/stop
  videoElem = document.getElementById('video');
  stream = videoElem.srcObject;
  tracks = stream.getTracks();

  tracks.forEach(function(track) {
    track.stop();
  });

  videoElem.srcObject = null;
  
  var video = document.getElementById('video');
  video.style.display = "none";
  //alert('close');
  var image = document.getElementById('canvas').toDataURL();
  document.getElementById('base64').innerHTML=image; 
    //document.getElementById('imagen').src = "img/perfil_defecto.png";
}

function setearImagen()
{
   // window.alert('paso');
    //accedo a la imagen
    var imagen = document.getElementById('imagen');
    //seteo a la imagen por defecto
    imagen.src = "img/perfi_defecto.png";
   // window.alert('salgo');
}

function previsualizar()
{
     //<!--codgio de carga de la imagen-->
                  if (window.FileReader)
                        {
                            
                          //creo una función para manejar el evento de subida
                          function seleccionArchivo(evt)
                          {
                            //reviso un objeto fileList leido del input de html
                            var files = evt.target.files;
                            //accedo al archivo leido en el index 0 del buffer (en este caso siempre es el 1ero)
                            var f = files[0];
                            //valido el tipo
                            //si es imagen se carga
                            //
//                            alert(f);
                            if (f.type.match('image.*')) 
                            {
  //                              alert('entr');
                                    var tamanio_bytes = f.size;
                                    var tamanio_kilobytes = parseInt(tamanio_bytes/1024);
                                    //A DEFINIR
                                    var umbral = 500;
                                    //valido el tamaño si es menor al umbral pasa
                                    if(tamanio_kilobytes < umbral)
                                    {
                                        //window.alert(tamanio_kilobytes);
                                        //creo un objeto filereader (se encargan de gestionar la lectura)
                                        var leerArchivo = new FileReader();
                                        //muestro el boton de rese<t y le asigno un estilo
                                        //docume nt.getElementById('resetear').style.display= 'block';
                                        //fileReader.onload es para gestionar el evento cuando se cargo el archivo
                                        leerArchivo.onload = (
                                        //funcion anidada media turbia que se encarga de crear el elemento html y mostrarlo
                                        function(elArchivo)
                                            {
                                             return function(e) {
                                                                 //ruta de la imagen leida
                                                                 var ruta = e.target.result;
                                                                 //accedo al elemento
                                                                 var imagen = document.getElementById('imagen');
                                                                 //asigno la ruta de archivo para que se muestre
                                                                 
                                                                 imagen.src = ruta;
                                                                 
                                                                 };
                                            })(f);
                                        //Comienza la lectura del contenido del objeto Blob,
                                        //una vez terminada, el atributo result contiene un data: URL que representa los datos del fichero.
                                        leerArchivo.readAsDataURL(f);
                                    }
                                    else
                                    {
                                         window.alert('Su fotografía supera el maximo tamaño en kilobytes permitido');
                                    }
                                   
                                        
                            }
                            //no es formato de imagen se informa por pantalla y no se actualiza
                            else
                            {
                                window.alert('FORMATO NO VALIDO: Cargue un archivo .jpg y .png');
                            }
                           //fin funcion seleccionar archivo
                            }
                            //fin primer bloque if
                          }   
                        else
                                {
                                        document.getElementById('vista_previa').innerHTML = "El navegador no soporta vista previa";
                                }
                //creo el evento
                document.getElementById('archivo').addEventListener('change', seleccionArchivo, false);
                //creo el evento
                document.getElementById('files').addEventListener('change', archivo, false);
    
//    alert('file');
}
                
                