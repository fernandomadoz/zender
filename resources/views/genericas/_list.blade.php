@extends('layouts.backend')

@section('contenido')
<?php 

if(isset($mensaje)) {
  $mensaje_class = 'alert-success';
  $mensaje_icon = 'fa-check';

  if (isset($mensaje['class'])) {
    $mensaje_class = $mensaje['class'];
  }

  if (isset($mensaje['detalle'])) {
    $mensaje_detalle = $mensaje['detalle'];
  }
  else {
    $mensaje_detalle = $mensaje;  
  }

  if (isset($mensaje['error'])) {
    $mensaje_icon = 'fa fa-ban';
  }

}

?>
<div class="col-xs-12">
<?php if(isset($mensaje)) { ?>
  <br>
  <div class="alert <?php echo $mensaje_class; ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa <?php echo $mensaje_icon; ?>"></i> <?php echo $mensaje_detalle; ?></h4>  
  </div>
<?php } ?>
</div>

<div id="tabla"></div>

<?php 
$gen_seteo['gen_campos_a_ocultar'] = 'id';
?>

<script type="text/javascript">
$.ajax({
  url: '<?php echo env('PATH_PUBLIC')?>crearlista',
  type: 'POST',
  dataType: 'html',
  async: true,
  data:{
    _token: "{{ csrf_token() }}",
    gen_modelo: '<?php echo $gen_modelo ?>',
    gen_seteo: '<?php echo serialize($gen_seteo) ?>',
    gen_opcion: '<?php echo $gen_opcion ?>'
  },
  success: function success(data, status) {        
    $("#tabla").html(data);
  },
  error: function error(xhr, textStatus, errorThrown) {
      alert(errorThrown);
  }
});
</script>

@endsection
