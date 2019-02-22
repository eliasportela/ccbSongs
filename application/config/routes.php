<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Rotas do Tamplate
$route['default_controller'] = 'Util';
$route['indexar'] = 'Util/IndexarCd';
$route['indexar-json'] = 'Util/IndexarJson';

$route['api/register/(:any)'] = 'Usuarios/RegisterUser';
$route['api/login'] = 'Usuarios/LoginUser';

$route['api/cd/(:num)/(:any)'] = 'Hinos/GetCdHinos';
$route['api/categorias/(:any)'] = 'Hinos/GetCategoriasHinos';

$route['api/hino/(:num)/(:any)'] = 'Hinos/GetHino';
$route['api/salvar/hino/(:num)/(:any)'] = 'Hinos/SalvarUserHino';

// 404 e erros
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;