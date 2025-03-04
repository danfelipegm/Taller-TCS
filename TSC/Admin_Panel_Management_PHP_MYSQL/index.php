<?php
include 'inc/header.php';

Session::CheckSession();

$logMsg = Session::get('logMsg');
if (isset($logMsg)) {
  echo $logMsg;
}
$msg = Session::get('msg');
if (isset($msg)) {
  echo $msg;
}
Session::set("msg", NULL);
Session::set("logMsg", NULL);
?>
<?php

if (isset($_GET['remove'])) {
  $remove = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['remove']);
  $removeUser = $users->deleteUserById($remove);
}

if (isset($removeUser)) {
  echo $removeUser;
}
if (isset($_GET['deactive'])) {
  $deactive = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['deactive']);
  $deactiveId = $users->userDeactiveByAdmin($deactive);
}

if (isset($deactiveId)) {
  echo $deactiveId;
}
if (isset($_GET['active'])) {
  $active = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['active']);
  $activeId = $users->userActiveByAdmin($active);
}

if (isset($activeId)) {
  echo $activeId;
}


 ?>
<div class="card ">
  <div class="card-header">
    <h3 style="color: white; font-weight: bold;">
      <i class="fas fa-users mr-2"></i>lista de usuarios
      <span class="float-right">Bienvenido! 
        <strong>
          <span class="badge badge-lg badge-secondary text-white">
            <?php
            $username = Session::get('username');
            if (isset($username)) {
              echo $username;
            }
            ?>
          </span>
        </strong>
      </span>
    </h3>
  </div>
  <div class="card-body pr-2 pl-2">
    <?php if (Session::get("roleid") == '3') { ?>
      <script type="text/javascript">
        window.location.href = "userindex.php";  // Redirige a index.html
    </script>
    <?php } else { ?>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <style>
                .btn-ver {
                    background-color: #616161; /* Gris */
                    border-color: #616161;
                    color: white;
                }
                .btn-ver:hover {
                    background-color: #4b4b4b;
                    border-color: #4b4b4b;
                }
                .btn-editar {
                    background-color: #2196F3;
                    border-color: #2196F3;
                    color: white;
                }
                .btn-editar:hover {
                    background-color: #0056b3;
                    border-color: #004085;
                }
                .btn-eliminar {
                    background-color: #dc3545;
                    border-color: #dc3545;
                    color: white;
                }
                .btn-eliminar:hover {
                    background-color: #c82333;
                    border-color: #bd2130;
                }
                .btn-desactivar {
                    background-color: #ffc107;
                    border-color: #ffc107;
                    color: white;
                }
                .btn-desactivar:hover {
                    background-color: #e0a800;
                    border-color: #d39e00;
                }
            </style>

            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Correo Electronico</th>
                    <th class="text-center">Telefono</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Fecha de Creacion</th>
                    <th width='25%' class="text-center">Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $allUser = $users->selectAllUserData();

                if ($allUser) {
                    $i = 0;
                    foreach ($allUser as $value) {
                        $i++;
                ?>
                <tr class="text-center" <?php if (Session::get("id") == $value->id) echo "style='background:#2c3e50'"; ?>>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value->name; ?></td>
                    <td><?php echo $value->username; ?> <br>
                        <?php if ($value->roleid == '1') {
                            echo "<span class='badge badge-lg badge-info text-white'>Admin</span>";
                        } elseif ($value->roleid == '2') {
                            echo "<span class='badge badge-lg badge-dark text-white'>Editor</span>";
                        } elseif ($value->roleid == '3') {
                            echo "<span class='badge badge-lg badge-dark text-white'>User Only</span>";
                        } ?>
                    </td>
                    <td><?php echo $value->email; ?></td>
                    <td><span class="badge badge-lg badge-secondary text-white"><?php echo $value->mobile; ?></span></td>
                    <td>
                        <?php if ($value->isActive == '0') { ?>
                            <span class="badge badge-lg badge-info text-white">Activo</span>
                        <?php } else { ?>
                            <span class="badge badge-lg badge-danger text-white">Inactivo</span>
                        <?php } ?>
                    </td>
                    <td><span class="badge badge-lg badge-secondary text-white"><?php echo $users->formatDate($value->created_at); ?></span></td>
                    <td>
                        <?php if (Session::get("roleid") == '1') { ?>
                            <a class="btn btn-ver btn-sm" href="profile.php?id=<?php echo $value->id; ?>">Ver</a>
                            <a class="btn btn-editar btn-sm" href="profile.php?id=<?php echo $value->id; ?>">Editar</a>
                            <a onclick="return confirm('¿Estás seguro de eliminar?')" class="btn btn-eliminar btn-sm <?php if (Session::get("id") == $value->id) echo 'disabled'; ?>" href="?remove=<?php echo $value->id; ?>">Eliminar</a>
                            <?php if ($value->isActive == '0') { ?>
                                <a onclick="return confirm('¿Estás seguro de desactivarlo?')" class="btn btn-desactivar btn-sm <?php if (Session::get("id") == $value->id) echo 'disabled'; ?>" href="?deactive=<?php echo $value->id; ?>">Inactivar</a>
                            <?php } elseif ($value->isActive == '1') { ?>
                                <a onclick="return confirm('¿Estás seguro de activarlo?')" class="btn btn-desactivar btn-sm <?php if (Session::get("id") == $value->id) echo 'disabled'; ?>" href="?active=<?php echo $value->id; ?>">Activar</a>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php }} else { ?>
                <tr class="text-center">
                    <td colspan="8">Usuario no disponible</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>




  <?php
  include 'inc/footer.php';

  ?>
