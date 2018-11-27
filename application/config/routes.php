<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Rotas do Tamplate
$route['default_controller'] = 'Util';
$route['indexar'] = 'Util/Indexar';

$route['api/register/(:any)'] = 'Usuarios/RegisterUser';

$route['api/cd/(:num)/(:any)'] = 'Hinos/GetCdHinos';
$route['api/categorias/(:any)'] = 'Hinos/GetCategoriasHinos';

$route['api/hino/(:num)/(:any)'] = 'Hinos/GetHino';
$route['api/curtir/hino/(:num)/(:num)/(:any)'] = 'Hinos/LikeHino';

// 404 e erros
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;