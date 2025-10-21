<?php
require_once "./model/views_model.php";

class viewsControl extends viewModel
{
    public function getPlantillaControl()
    {
        return require_once "./view/plantilla.php";
    }
    public function getViewControl()
    {
        session_start();
        if (isset($_GET['views'])) {
            $ruta = explode("/", $_GET['views']);
            $respuesta = viewModel::get_view($ruta[0]);
            if ($respuesta != "singup") {
                if (!isset($_SESSION['sis_email_id'])) {
                    $respuesta = "login";
                } else {
                    $respuesta = viewModel::get_view($ruta[0]);
                }
            } else {
                $respuesta = viewModel::get_view($ruta[0]);
            }
        } else {
            if (!isset($_SESSION['sis_email_id'])) {
                $respuesta = "login";
            } else {
                $respuesta = "inicio.php";
            }
        }
        return $respuesta;
    }
}
