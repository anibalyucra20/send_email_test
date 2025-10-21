<div class="container">
    <h4 class="mt-3 mb-3">Lista de Usuarios</h4>
    <a href="<?= BASE_URL ?>new-user" class="btn btn-primary">Nuevo +</a>
    <br><br>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nro</th>
                <th>DNI</th>
                <th>Nombres y Apellidos</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="content_users">

        </tbody>
    </table>
</div>
<script src="<?= BASE_URL ?>view/function/user.js"></script>
