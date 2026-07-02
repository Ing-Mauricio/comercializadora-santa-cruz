<?php
class ErrorController extends Controller
{
    public function index()
    {
        http_response_code(404);
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
        <title>404 - Página no encontrada</title>
        <style>
            body{font-family:"Segoe UI",sans-serif;background:#0f1420;color:#fff;display:flex;
                 align-items:center;justify-content:center;height:100vh;margin:0;text-align:center}
            h1{font-size:6rem;margin:0;background:linear-gradient(90deg,#6366f1,#22d3ee);
               -webkit-background-clip:text;background-clip:text;color:transparent}
            a{color:#22d3ee;text-decoration:none}
        </style></head><body>
        <div><h1>404</h1><p>La página que buscas no existe.</p>
        <a href="' . (defined('BASE_URL') ? BASE_URL : '') . '/dashboard">Volver al Dashboard</a></div>
        </body></html>';
    }
}
