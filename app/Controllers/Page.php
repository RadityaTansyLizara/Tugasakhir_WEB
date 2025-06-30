<?php
namespace App\Controllers;
class Page extends BaseController
{
    public function home()
    {
        return view("home", [
            "title" => "Home",
            "content" =>
                "Welcome to our website. We strive to provide valuable information and a seamless experience.",
        ]);
    }
    public function about()
    {
        return view("about", [
            "title" => "About Us",
            "content" =>
                "Learn more about our mission, values, and the people behind our work.",
        ]);
    }
    public function contact()
    {
        return view("contact", [
            "title" => "Contact Us",
            "content" =>
                "Have any questions? Feel free to reach out, and we'll be happy to assist you.",
        ]);
    }
    public function faqs()
    {
        return view("faqs", [
            "title" => "FAQs",
            "content" =>
                "Find answers to common questions and get the support you need.",
        ]);
    }
    public function tos()
    {
        return view("tos", [
            "title" => "Terms of Service",
            "content" =>
                "Please read our terms and conditions carefully to understand how we operate.",
        ]);
    }
}
