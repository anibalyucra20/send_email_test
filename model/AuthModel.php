<?php
require_once("../library/conexion.php");

class AuthModel {
    private $conexion;
    private $tabla = 'usuarios'; // <-- CAMBIA AQUÍ si tu tabla se llama distinto

    function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrar($nombre, $correo, $passwordHash, $codigo6, $estado, $fechaVence) {
        $consulta = "INSERT INTO {$this->tabla} (nombre, correo, password, codigo, estado, fecha_vencimiento)
                     VALUES ('$nombre', '$correo', '$passwordHash', '$codigo6', $estado, '$fechaVence')";
        $sql = $this->conexion->query($consulta);
        return $sql ? $this->conexion->insert_id : 0;
    }

    public function existeCorreo($correo) {
        $consulta = "SELECT id FROM {$this->tabla} WHERE correo='$correo' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql ? $sql->num_rows : 0;
    }

    public function getPorCorreo($correo) {
        $consulta = "SELECT id, nombre, correo, password, codigo, estado, fecha_vencimiento
                     FROM {$this->tabla} WHERE correo='$correo' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql ? $sql->fetch_object() : null;
    }

    public function actualizarCodigo($id, $codigo6, $fechaVence) {
        $consulta = "UPDATE {$this->tabla}
                     SET codigo='$codigo6', fecha_vencimiento='$fechaVence', estado=0
                     WHERE id='$id'";
        return $this->conexion->query($consulta);
    }

    public function activar($id) {
        $consulta = "UPDATE {$this->tabla} SET estado=1 WHERE id='$id'";
        return $this->conexion->query($consulta);
    }
}
