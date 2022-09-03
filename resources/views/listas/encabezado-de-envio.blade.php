
@extends('layouts.backend')

  @section('contenido')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo __('Campaña de Envio') ?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li class="active">Inicio</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">


      <?php if (isset($Listas_de_envios)) { ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Listas de Envio</h3>
                    <div class="box-tools">
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Enlace</th>
                    </tr>

                    <?php 
                    $i = 0;
                    foreach ($Listas_de_envios as $Lista_de_envio) { 
                      $i++;
                    ?>
                    <tr>
                      <td><?php echo $Lista_de_envio->id ?></td>
                      <td><?php echo $Lista_de_envio->nombre ?></td>
                      <td>
                          <?php $url_le = env('PATH_PUBLIC').'le/'.$Lista_de_envio->id.'/'.$Lista_de_envio->hash;?>
                        <a href="<?php echo $url_le ?>" target="_blank"><?php echo $url_le ?></a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody></table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
          </div>
      <?php } ?>
    </section>
    <!-- /.content -->


@endsection
