<?php
class viewModel
{
    protected static function get_view($view)
    {
        $white_list = ["home", "products", "users", "new-user", "edit-user", "products", "new-product", "edit-product", "category", "new-category", "edit-category", "orders", "order-details", "profile", "settings", "clients", "new-client", "edit-client", "providers", "new-provider", "edit-provider"];
        if (in_array($view, $white_list)) {
            if (is_file("./view/" . $view . ".php")) {
                $content = "./view/" . $view . ".php";
            } else {
                $content = "404";
            }
        } elseif ($view == "login") {
            $content = "login";
        } elseif ($view == "singup") {
            $content = "singup";
        } else {
            $content = "404";
        }
        return $content;
    }
}
