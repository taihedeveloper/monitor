<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (ENVIRONMENT == 'development') {
    $phpcas_path= BASEPATH . '/third_party/phpcas/libraries';
    $cas_host='192.168.1.50';
    $cas_port=8080;
    $cas_context='';
}
if (ENVIRONMENT == 'production') {
    $phpcas_path= BASEPATH . '/third_party/phpcas/libraries';
    $cas_host='192.168.1.50';
    $cas_port=8080;
    $cas_context='';
}
