<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Calendar</title>

    <!--Scripts CSS-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="css/bootstrap-clockpicker.css">
    <link rel="stylesheet" href="fullcalendar/main.css">

    <!--Scripts JS-->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/bootstrap-clockpicker.js"></script>
    <script src="js/moment-with-locales.min.js"></script>
    <script src="fullcalendar/main.js"></script>
    
</head>
<body>

    <div class="container-fluid">
        <section class="content-header">
            <h2><small>Yefer's calendar</small></h2>    
        </section>
        <div class="row">
            <div class="col-10">
               <div id="calendar1" style="border: 1px solid #000; padding: 2px" ></div>   
            </div>
            <div class="col-2">
                <div id="external-events" style="margin-bottom: 1em; height: 350px; border: 1px solid #000; overflow: auto; padding:1em">
                  <h4 class="text-center">Eventos predefinidos</h4>
                  <div id="listaEventoPredefinido">

                    <?php
                    require('connection.php');
                    $conex = returnConnection();
                    $data = mysqli_query($conex, "SELECT id, name, start_date, end_date, text_color, background_color FROM eventos_predef");
                    $ep = mysqli_fetch_all($data, MYSQLI_ASSOC);

                    foreach ($ep as $row) {
                        echo '<div class="fc-event" 
                                  data-name="' . $row['name'] . '"
                                  data-start_date="' . $row['start_date'] . '"
                                  data-end_date="' . $row['end_date'] . '"
                                  data-text_color="' . $row['text_color'] . '"
                                  data-background_color="' . $row['background_color'] . '"
                                  style="border-color: ' . $row['background_color'] . '; color: ' . $row['text_color'] . '; background-color: ' . $row['background_color'] . '; margin: 10px">'
                                  . $row['name'] . '[' . substr($row['start_date'], 0, 5) . ' a ' . substr($row['end_date'], 0, 5) . ']</div>';
                    }
                    ?>
                      
                  </div>                      
                </div>
                <hr>
                <div class="" style="text-align: center;">
                    <button type="button" id="botonEventoPredefinido" class="btn btn-success">Admin Evento Predef</button>
                </div>
            </div>  
        </div>        
    </div>
 

    <!-- Formulario de Eventos -->
    <div class="modal fade" id="formularioEventos" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Botón para cierre de modal con function JS data-bs-dismiss="modal"-->
                    <button tupe="button" class="close" data-bs-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>  

                <div class="modal-body">
                    <input type="hidden" id="id">
                    
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Nombre del Evento:</label>
                            <input type="text" id="titulo" class="form-control" placeholder="">
                        </div>
                    </div>
                    <hr>

                    <div class="form-row d-flex">
                        <div class="form-group col-md-6">
                           <label>Fecha de inicio:</label>
                           <div class="input-group" data-autoclose="true">
                            <input type="date" id="fechaInicio" class="form-control" value=""> 
                           </div>
                        </div>
                        <div class="form-group col-md-6" id="tituloHoraInicio">
                              <label>Hora de inicio:</label>
                           <div class="input-group clockpicker" data-autoclose="true">
                            <input type="text" id="horaInicio" class="form-control" autocomplete="off" value="">
                           </div> 
                        </div>
                    </div>
                    <hr>

                      <div class="form-row d-flex">
                        <div class="form-group col-md-6">
                           <label>Fecha de fin:</label>
                           <div class="input-group" data-autoclose="true">
                            <input type="date" id="fechaFin" class="form-control" value=""> 
                           </div>
                        </div>
                        <div class="form-group col-md-6" id="tituloHoraInicio">
                              <label>Hora de fin:</label>
                           <div class="input-group clockpicker" data-autoclose="true">
                            <input type="text" id="horaFin" class="form-control" autocomplete="off" value="">
                           </div> 
                        </div>
                    </div>
                    <hr>

                      <div class="form-row d-flex">
                           <label>Descripción:</label>
                           <textarea class="form-control" type="color" value="#3788D8" id="descripcion" rows="3"></textarea> 
                      </div>
                      <hr>

                      <div class="form-row d-flex">
                           <label>Color de fondo:</label>
                           <input class="form-control" type="color" value="#3788D8" id="colorFondo" style="height: 36px;">
                      </div>

                      <div class="form-row d-flex">
                           <label>Color de texto:</label>
                           <input class="form-control" type="color" value="#FFFFFF" id="colorTexto" style="height: 36px;"> 
                      </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="button" id="botonAgregar" class="btn btn-success">Agregar</button>
                    <button type="button" id="botonModificar" class="btn btn-success">Modificar</button>
                    <button type="button" id="botonBorrar" class="btn btn-danger">Borrar</button>
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancelar</button>
                    

                    
                </div>
            </div>
        </div>
    </div>

    <script>

    document.addEventListener("DOMContentLoaded", function(){

        new FullCalendar.Draggable(document.getElementById('listaEventoPredefinido'),{
            itemSelector: '.fc-event',
            eventData: function(eventEl){
                return{
                    title: eventEl.innerText.trim()
                }
            }
        });

        
        $('.clockpicker').clockpicker();

        let calend = new FullCalendar.Calendar(document.getElementById('calendar1'),{
            droppable: true,
            height: 780,
            headerToolbar:{
               left: 'prev,next,today',
               center: 'title',
               right: 'dayGridMonth, timeGridWeek, timeGridDay'
            },
            editable: true,
            events: 'dataEvents.php?action=list',
            dateClick: function(info){
                console.log(info);
                //alert(info.dateStr);
                clearForm();
                $('#botonAgregar').show();
                $('#botonModificar').hide();
                $('#botonBorrar').hide();

                if (info.allDay) {
                    $('#fechaInicio').val(info.dateStr);
                    $('#fechaFin').val(info.dateStr);
                }else{
                    //let fechaHora = info.dateStr.split("T");
                    //let fechaHoraActual = new Date(); // Obtener la fecha y hora actuales
                    //let horaActual = `${fechaHoraActual.getHours()}:${fechaHoraActual.getMinutes()}`;
                    //$('#fechaInicio').val(fechaHora[0]);
                   // $('#fechaFin').val(fechaHora[0]);
                    //$('#horaInicio').val(horaActual);
                    //$('#horaInicio').val(fechaHora[1].substr(0,5));
                }
                $("#formularioEventos").modal('show');
            },
            eventClick: function(info){
                // Muestra los botones correspondientes en el formulario
                $('#botonAgregar').hide();
                $('#botonModificar').show();
                $('#botonBorrar').show();

                // Muestra el formulario
                $("#formularioEventos").modal('show');

                // Rellena los campos del formulario con la información del evento
                $('#id').val(info.event.id);
                $('#titulo').val(info.event.title);
                $('#descripcion').val(info.event.extendedProps.description); 
                //console.log('info.event:', info.event); 


                // Utiliza moment.js para formatear la fecha
                $('#fechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
                $('#fechaFin').val(moment(info.event.extendedProps.end_date).format("YYYY-MM-DD"));
                $('#horaInicio').val(moment(info.event.start).format("HH:mm"));
                $('#horaFin').val(moment(info.event.extendedProps.end_date).format("HH:mm"));
                $('#colorTexto').val(info.event.extendedProps.text_color);
                $('#colorFondo').val(info.event.extendedProps.background_color);
                
            },

            eventResize: function(info){
                $('#id').val(info.event.id);
                $('#titulo').val(info.event.title);
                $('#descripcion').val(info.event.extendedProps.description); 
                $('#fechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
                $('#fechaFin').val(moment(info.event.extendedProps.end_date).format("YYYY-MM-DD"));
                $('#horaInicio').val(moment(info.event.start).format("HH:mm"));
                $('#horaFin').val(moment(info.event.extendedProps.end_date).format("HH:mm"));
                $('#colorTexto').val(info.event.extendedProps.text_color);
                $('#colorFondo').val(info.event.extendedProps.background_color); 

                let registro = getFormData();
                editEvent(registro);
            },

            eventDrop: function(info){
                $('#id').val(info.event.id);
                $('#titulo').val(info.event.title);
                $('#descripcion').val(info.event.extendedProps.description); 
                $('#fechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
                $('#fechaFin').val(moment(info.event.extendedProps.end_date).format("YYYY-MM-DD"));
                $('#horaInicio').val(moment(info.event.start).format("HH:mm"));
                $('#horaFin').val(moment(info.event.extendedProps.end_date).format("HH:mm"));
                $('#colorTexto').val(info.event.extendedProps.text_color);
                $('#colorFondo').val(info.event.extendedProps.background_color); 

                let registro = getFormData();
                editEvent(registro);

            },
            drop: function(info){
            //alert ("Hello")
            clearForm();
            $('#colorTexto').val(info.draggedEl.dataset.text_color);
            $('#colorFondo').val(info.draggedEl.dataset.background_color);
            $('#titulo').val(info.draggedEl.dataset.name); 
            let fechaHora = info.dateStr.split("T");
            $('#fechaInicio').val(fechaHora[0]);
            $('#fechaFin').val(fechaHora[0]);

            if (info.allDay) {
                $('#horaInicio').val(info.draggedEl.dataset.start_date);
                $('#horaFin').val(info.draggedEl.dataset.end_date);
            } else {
                $('#horaInicio').val(fechaHora[1].substring(0,5));
                $('#horaFin').val(moment(fechaHora[1].substring(0,5)).add(1, 'hours'));
            }
            let registro = getFormData();
            addEventPredef(registro);

        }

        });

        calend.render();

        /* Eventos de botones */

        $('#botonAgregar').click(function(){
            //alert("add");
            let registro = getFormData();
            addEvent(registro);
            $('#formularioEventos').modal('hide');

        });

          $('#botonModificar').click(function(){
            //alert("edit");
            let registro = getFormData();
            editEvent(registro);
            $('#formularioEventos').modal('hide');

        });

          $('#botonBorrar').click(function(){
            //alert("delete);
            let registro = getFormData();
            deleteEvent(registro);
            $('#formularioEventos').modal('hide');
            
        });

        
        $('#botonEventoPredefinido').click(function(){
            //alert("predef");
            window.location = "eventPredef.html";       
            
        });


        /* Función para comunicarse con Ajax*/
        function addEvent(registro){
            $.ajax({
                type: 'POST',
                url: 'dataEvents.php?action=add',
                data: registro,
                success: function(msg){
                    calend.refetchEvents();
                },
                error: function(error){
                    alert("Error al agregar el evento " + error);
                }
            });
        }

         function editEvent(registro) {
        $.ajax({
            type: 'POST',
            url: 'dataEvents.php?action=edit',
            data: registro,
            success: function(msg) {
                calend.refetchEvents();
            },
            error: function(error){
                    alert("Error al modificar el evento " + error);
            /*error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error al modificar el evento:");
                console.log("Estado de la solicitud:", textStatus);
                console.log("Error lanzado:", errorThrown);
                console.log("Respuesta del servidor:", jqXHR.responseText);

                alert("Error al modificar el evento. Consulta la consola para más detalles.");*/
            }
        });
    }

         function deleteEvent(registro) {
            $.ajax({
                type: 'POST',
                url: 'dataEvents.php?action=delete',
                data: registro,
                success: function(msg) {
                    calend.refetchEvents();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error al modificar el evento:");
                console.log("Estado de la solicitud:", textStatus);
                console.log("Error lanzado:", errorThrown);
                console.log("Respuesta del servidor:", jqXHR.responseText);

                alert("Error al eliminar el evento" + error);
                }
            });
        }

        function addEventPredef(registro) {
            $.ajax({
                type: 'POST',
                url: 'dataEvents.php?action=add',
                data: registro,
                success: function(msg) {
                    calend.removeAllEvents();
                    calend.refetchEvents();
                },
                error: function(error) {
                alert("Error al agregar evento predefinido al calendario" + error);
                }
            });
        }


        /* Funciones para el formulario de eventos*/

        function clearForm(){

            $('#titulo').val("");
            $('#fechaInicio').val("");
            $('#horaInicio').val("");
            $('#fechaFin').val("");
            $('#horaFin').val("");
            $('#descripcion').val("");
            $('#colorFondo').val("#3788D8");
            $('#colorTexto').val("#FFFFFF");

        }

        function getFormData(){

            let registro = {
                id: $('#id').val(),
                name: $('#titulo').val(),
                description: $('#descripcion').val(),
                start_date: $('#fechaInicio').val() + ' ' + $('#horaInicio').val(),
                end_date: $('#fechaFin').val() + ' ' + $('#horaFin').val(),
                text_color: $('#colorTexto').val(),
                background_color: $('#colorFondo').val()
            } 
            return (registro);
        } 

    });
    </script>
</body>
</html>