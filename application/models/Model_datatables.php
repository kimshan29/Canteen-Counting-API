<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Model_datatables extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($req, $dtColumn = array(), $dtOrder = array())
    {
        $i = 0;

        $like = "";
        foreach ($dtColumn as $item)
        {
            if(isset($req['search'])){
                if($req['search'])
                    ($i === 0) ? $like .= "where ".$item." like '%".$req['search']."%' " : $like .= "OR ".$item." like '%".$req['search']."%' ";
            }

            $column[$i] = $item;

            $i++;
        }

        if(isset($req['order']))
        {
            $urut   = "order by " . $column[$req['order']['column']] . " " . $req['order']['dir'] . " ";
        }
        else if(isset($dtOrder))
        {
            $order  = $dtOrder;
            $urut   = "order by " . key($order) . " " . $order[key($order)] . " ";
        }

        $data = $like . $urut;

        return $data;
    }

    function get_datatables($req, $query, $column = array(), $order = array())
    {
        $q      = $this->_get_datatables_query($req, $column, $order);
        $limit  = "";

        if(isset($req['length'])){
            if($req['length'] != -1)
                $limit = "LIMIT ".$req['start'].",".$req['length'];
        }

        $query  = $this->db->query($query . $q . $limit);

        return $query->result();
    }

    function count_filtered($req, $query, $column = array(), $order = array())
    {
        $q      = $this->_get_datatables_query($req, $column, $order);
        $query  = $this->db->query($query . $q);

        return $query->num_rows();
    }

    public function count_all($query)
    {
        $query = $this->db->query($query);

        return $query->num_rows();
    }

}
