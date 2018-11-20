<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Rotas do Tamplate
$route['default_controller'] = 'Util';
$route['indexar'] = 'Util/Indexar';

// 404 e erros
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
