<?php
namespace App\Controllers;
use App\Models\UserModel;
class User extends BaseController
{
    public function index()
    {
        $title = "Daftar User";
        $model = new UserModel();
        $users = $model->findAll();
        return view("user/index", compact("users", "title"));
    }
    public function login()
    {
        helper(["form"]);
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");
        if (!$username) {
            return view("login");
        }
        $session = session();
        $model = new UserModel();
        $login = $model->where("username", $username)->first();
        if ($login) {
            $pass = $login["password"];
            if ($password === $pass) {
                $login_data = [
                    "user_id" => $login["id"],
                    "user_name" => $login["username"],
                    "logged_in" => true,
                ];
                $session->set($login_data);
                return redirect("admin/article");
            } else {
                $session->setFlashdata("flash_msg", "Password salah.");
                return redirect()->to("/user/login");
            }
        } else {
            $session->setFlashdata("flash_msg", "Username tidak terdaftar.");
            return redirect()->to("/user/login");
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to("/user/login");
    }
}
