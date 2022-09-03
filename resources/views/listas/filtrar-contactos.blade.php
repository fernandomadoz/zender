
@extends('layouts.backend')

  @section('contenido')
    <script src="<?php echo env('PATH_PUBLIC')?>js/vue/vue.js"></script>
    <script src="<?php echo env('PATH_PUBLIC')?>js/vee-validate/dist/vee-validate.js"></script>
    <script src="<?php echo env('PATH_PUBLIC')?>js/vee-validate/dist/locale/es.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo env('PATH_PUBLIC')?>js/vue-form-generator/vfg.css">
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo __('GENERAR LISTAS') ?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li class="active">Inicio</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" id="app-filtrar">
      <div class="row">
        <div class="col-xs-12">


          <div class="box">
            <div class="box-header">

              {!! Form::open(array
                (
                'url' => env('PATH_PUBLIC').'generar-listas', 
                'role' => 'form',
                'method' => 'POST',
                'id' => "form_gen_modelo",
                'enctype' => 'multipart/form-data',
                'class' => 'form-inline',
                'ref' => 'form'
                )) 
              !!}

                <?php foreach ($campos as $campo) {

                  $busqueda = $campo[4];
                  
                  if ($busqueda == 's') {
                    $nombre = $campo[0];
                    $label = $campo[1];
                    $valores = $campo[2];
                    $tipo = $campo[3];
                ?>
                    <div class="form-group col-lg-3" style="padding: 10px">
                      <label for="nombre"><?php echo $label ?></label><br>
                      <?php if ($tipo == 'fk') { ?>
                        <?php echo Form::select($nombre, $valores, null, ['id' => $nombre, 'class' => 'form-control']); ?>
                      <?php }
                      else { ?>
                        <input class="form-control" id="<?php echo $nombre ?>" name="<?php echo $nombre ?>" placeholder="<?php echo $label ?>">
                      <?php } ?>
                    </div>
                  <?php } ?>
                <?php } ?>
                <hr class="col-lg-12">

                <button type="submit" class="btn btn-primary col-lg-3" style="padding: 10px">Obtener Contactos</button>

              {!! Form::close() !!}

            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


      <?php if (isset($Contactos)) { ?>
        {!! Form::open(array
          (
          'url' => env('PATH_PUBLIC').'generar-instancias', 
          'role' => 'form',
          'method' => 'POST',
          'id' => "form_gen_modelo",
          'enctype' => 'multipart/form-data',
          'class' => 'form-vertical',
          'ref' => 'form'
          )) 
        !!}
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Contactos</h3>
                  <hr>
                  <input type="hidden" name="cant_contactos" value="<?php echo $cant_contactos ?>">
                  <input type="button" data-toggle="modal" data-toggle="modal" data-target="#modal-enc" class="btn btn-primary col-lg-3" style="padding: 10px" value="Generar Lista">                      
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody><tr>
                      <th>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" v-model="checkAll" v-on:click="checkMasive()"> Todos
                          </label>
                        </div>
                      </th>
                      <th>ID</th>
                      <?php foreach ($campos as $campo) { ?>
                        <th><?php echo $campo[1] ?></th>
                      <?php } ?>
                    </tr>

                    <?php 
                    $i = 0;
                    foreach ($Contactos as $Contacto) { 
                      $i++;
                    ?>
                    <tr>
                      <td>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="contacto_<?php echo $i ?>" value="<?php echo $Contacto->id ?>" v-model="Contacto[<?php echo $i-1 ?>].check"> Seleccionar
                          </label>
                        </div>
                      </td>
                      <td><?php echo $Contacto->id ?></td>
                      <?php 
                      foreach ($campos as $campo) {
                        $nombre = $campo[0];
                        $label = $campo[1];
                        $valores = $campo[2];
                        $tipo = $campo[3];
                      ?>
                        <th>
                          <?php 
                            if ($tipo <> 'fk') {
                              echo $Contacto->$nombre;
                            }
                            else {
                              $nombre_rel = str_replace('_id', '', $nombre);
                              if ($Contacto->$nombre <> ''){
                                echo $Contacto->$nombre_rel->descrip_modelo();
                                //echo $Contacto->$nombre;
                              }
                            }
                          ?>                            
                        </th>
                      <?php } ?>
                    </tr>
                    <?php } ?>
                  </tbody></table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
          </div>


          <!-- MODAL ENCABEZADO -->
            <div class="modal modal fade" id="modal-enc">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><div id="modal-enc-titulo"><?php echo __('Desea Resetear esta campaña?') ?></div></h4>
                  </div>

                  <div class="modal-body" id="modal-enc-bodi">  

                <fieldset>
                  <div class="form-group required field-input">
                    <label for="nombre">Nombre de la Campaña</label>
                    <input id="nombre" type="text" max="45" name="nombre" placeholder="Nombre" required="required" class="form-control">
                  </div>
                  <div class="form-group required field-input">
                    <label for="nombre">Cantidad de Listas</label>
                    <input id="nombre" type="number" min="1" max="99" value="1" name="cant_listas" placeholder="cantidad" required="required" class="form-control">
                  </div>

                  <div class="form-group required field-input">
                    <label for="nombre">Prefijo Telefonico</label>
                    <input id="nombre" type="text" max="45" name="codigo_tel" placeholder="codigo_tel" class="form-control">
                  </div>

                  <div class="form-group required field-input">
                    <label for="titulo_mensaje_1">Titulo mensaje 1*</label>
                    <input id="titulo_mensaje_1" type="text" max="45" name="titulo_mensaje_1" placeholder="Titulo mensaje 1" required="required" class="form-control">
                  </div>
                  <div class="form-group required field-textArea">
                    <label for="mensaje_1">Mensaje 1*</label>
                    <textarea id="mensaje_1" maxlength="65535" placeholder="Mensaje 1" rows="5" name="mensaje_1" required="required" class="form-control"></textarea>
                  </div>

                  <div class="form-group required field-input">
                    <label for="titulo_mensaje_2">Titulo mensaje 2</label>
                    <input id="titulo_mensaje_2" type="text" max="45" name="titulo_mensaje_2" placeholder="Titulo mensaje 2" class="form-control">
                  </div>
                  <div class="form-group required field-textArea">
                    <label for="mensaje_2">Mensaje 2</label>
                    <textarea id="mensaje_2" maxlength="65535" placeholder="Mensaje 2" rows="5" name="mensaje_2" class="form-control"></textarea>
                  </div>
                  
                  <div class="form-group required field-input">
                    <label for="titulo_mensaje_3">Titulo mensaje 3</label>
                    <input id="titulo_mensaje_3" type="text" max="45" name="titulo_mensaje_3" placeholder="Titulo mensaje 3" class="form-control">
                  </div>
                  <div class="form-group required field-textArea">
                    <label for="mensaje_3">Mensaje 3</label>
                    <textarea id="mensaje_3" maxlength="65535" placeholder="Mensaje 3" rows="5" name="mensaje_3" class="form-control"></textarea>
                  </div>
                  
                  <div class="form-group required field-input">
                    <label for="titulo_mensaje_4">Titulo mensaje 4</label>
                    <input id="titulo_mensaje_4" type="text" max="45" name="titulo_mensaje_4" placeholder="Titulo mensaje 4" class="form-control">
                  </div>
                  <div class="form-group required field-textArea">
                    <label for="mensaje_4">Mensaje 4</label>
                    <textarea id="mensaje_4" maxlength="65535" placeholder="Mensaje 4" rows="5" name="mensaje_4" class="form-control"></textarea>
                  </div>
                  
                  <div class="form-group required field-input">
                    <label for="titulo_mensaje_5">Titulo mensaje 5</label>
                    <input id="titulo_mensaje_5" type="text" max="45" name="titulo_mensaje_5" placeholder="Titulo mensaje 5" class="form-control">
                  </div>
                  <div class="form-group required field-textArea">
                    <label for="mensaje_5">Mensaje 5</label>
                    <textarea id="mensaje_5" maxlength="65535" placeholder="Mensaje 5" rows="5" name="mensaje_5" class="form-control"></textarea>
                  </div>
                  
                </fieldset>

                
              </div>

                  <div class="modal-footer">
                    <center>
                      <button type="submit" class="btn btn-primary"><?php echo __('Aceptar') ?></button>
                      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancelar') ?></button>
                    </center>  
                    <input type="hidden" name="sino_aprobado_administracion" value="NO">
                  </div>

                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
          <!-- MODAL ENCABEZADO -->

        {!! Form::close() !!}

        <!-- FIN APP app-filtrar -->
          <script type="text/javascript">
            const config = {
              locale: 'es', 
            };
            //moment.locale('es');
            //console.log(moment());
            Vue.use(VeeValidate, config);

            var app = new Vue({
              el: '#app-filtrar',

              data: {
                checkAll: false,
                Contacto: [
                <?php 
                foreach ($Contactos as $Contacto) { 
                ?>
                      {
                        instancia_de_envio_id: <?php echo $Contacto->id ?>,
                        check: false
                      },
                <?php } ?>
                ]
              },

              methods: {                

                checkMasive: function () {
                  for (var i = 0, len = this.Contacto.length; i < len; i++) {
                    item = this.Contacto[i].check = !this.checkAll
                  }
                },

                  
              },



            })
          </script>
        <!-- FIN APP app-filtrar -->

      <?php } ?>
    </section>
    <!-- /.content -->




@endsection
