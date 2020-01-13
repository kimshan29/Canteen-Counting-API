<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: rizki
 * Date: 29/07/2019
 * Time: 16:45
 */

/**
 * Get template notif
 * by id, data
 */
function get_template_notif($id, $data = array())
{
    $ci =& get_instance();
    $ci->load->library('parser');

    $text = $ci->super_model->get_type_name_by_id('dc_type_notif', 'id', $id, 'template_notif');
    $text = $ci->parser->parse_string($text, $data);

    return $text;
}