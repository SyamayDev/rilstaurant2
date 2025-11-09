<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_website_settings'))
{
    function get_website_settings()
    {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        return $CI->Settings_model->get_settings();
    }
}