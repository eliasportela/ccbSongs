<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Rotas do Tamplate
$route['default_controller'] = 'Util';
$route['indexar'] = 'Util/IndexarCd';
$route['indexar-json'] = 'Util/IndexarJson';

$route['api/login'] = 'Usuarios/LoginUser';
$route['api/register'] = 'Usuarios/RegisterUser';
$route['api/usuario/(:any)'] = 'Usuarios/GetUser';

$route['api/favoritos/(:any)'] = 'Hinos/GetFavoritos';

$route['api/cd/(:num)/(:any)'] = 'Hinos/GetCdHinos';
$route['api/categorias/(:any)'] = 'Hinos/GetCategoriasHinos';
$route['api/categoria/(:any)'] = 'Hinos/GetCategoria';
$route['api/cd/busca/(:any)'] = 'Hinos/GetBusca';

$route['api/hino/(:num)/(:any)'] = 'Hinos/GetHino';
$route['api/salvar/hino/(:num)/(:any)'] = 'Hinos/SalvarUserHino';

// 404 e erros
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;