<?php
date_default_timezone_set("Asia/Kolkata");
defined('BASEPATH') OR exit('No direct script access allowed');

class DC_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // DEFAULT TIME ZONE
        date_default_timezone_set('Asia/Jakarta');

        // TABLE
        $this->tbl_prefix = 'dc_';
        $this->tbl_static_content = $this->tbl_prefix . 'static_content';
        $this->tbl_user = $this->tbl_prefix . 'user';
        $this->tbl_menu = $this->tbl_prefix . 'menu';
        $this->tbl_icons = $this->tbl_prefix . 'icons';
        $this->tbl_user_groups = $this->tbl_prefix . 'user_groups';
        $this->tbl_groups = $this->tbl_prefix . 'groups';
        $this->tbl_user_accsess = $this->tbl_prefix . 'menu_accsess';
        $this->tbl_appearance = $this->tbl_prefix . 'appearance';
        $this->tbl_news = $this->tbl_prefix . 'news';
        $this->tbl_contact = $this->tbl_prefix . 'contact';
        $this->tbl_banner = $this->tbl_prefix . 'banner';
        $this->tbl_category = $this->tbl_prefix . 'category';
        $this->tbl_brand = $this->tbl_prefix . 'brand';
        $this->tbl_member = $this->tbl_prefix . 'member';
        $this->tbl_rekening = $this->tbl_prefix . 'rekening';
        $this->tbl_faq = $this->tbl_prefix . 'faq';
        $this->tbl_voucher = $this->tbl_prefix . 'voucher';
        $this->tbl_promo = $this->tbl_prefix . 'promo';
        $this->table_feedback = $this->tbl_prefix.'feedback';
        $this->table_product = $this->tbl_prefix.'product';
        $this->table_log_saldo = $this->tbl_prefix.'log_saldo';
        $this->table_bidnrun = $this->tbl_prefix.'bidnrun';
        $this->table_transaction = $this->tbl_prefix.'transaction';
        $this->table_log_activity = $this->tbl_prefix.'log_activity';
        $this->table_album_product = $this->tbl_prefix.'album_product';
        $this->table_coin = $this->tbl_prefix.'coin';

        // VIEW
        $this->view_prefix = 'view_';
        $this->view_dc_rekening = $this->view_prefix.$this->tbl_rekening;
        $this->view_dc_feedback = $this->view_prefix.$this->table_feedback;
        $this->view_dc_product = $this->view_prefix.$this->table_product;
        $this->view_dc_product_active = $this->view_prefix.$this->table_product.'_active';
        $this->view_dc_product_nonactive = $this->view_prefix.$this->table_product.'_nonactive';
        $this->view_dc_product_deleted = $this->view_prefix.$this->table_product.'_deleted';
        $this->view_dc_member = $this->view_prefix.$this->tbl_member;
        $this->view_dc_member_document_verified = $this->view_dc_member.'_document_verified';
        $this->view_dc_member_document_not_verified = $this->view_dc_member.'_document_not_verified';
        $this->view_dc_log_saldo = $this->view_prefix.$this->table_log_saldo;
        $this->view_dc_log_saldo_transfered = $this->view_prefix.$this->table_log_saldo.'_transfered';
        $this->view_dc_bidnrun = $this->view_prefix.$this->table_bidnrun;
        $this->view_dc_transaction = $this->view_prefix.$this->table_transaction;
        $this->view_dc_transaction_bidnrun = $this->view_dc_transaction.'_bidnrun';
        $this->view_omzet_now = $this->view_prefix.'omzet_now';
        $this->view_dc_transaction_buy = $this->view_dc_transaction.'_buy';
        $this->view_dc_log_activity = $this->view_prefix.$this->table_log_activity;
        $this->view_dc_product_all = $this->view_dc_product.'_all';
        $this->view_dc_product_soldout = $this->view_dc_product.'_soldout';
        $this->view_dc_product_dibayar = $this->view_dc_product.'_dibayar';
        $this->view_dc_transaction_berhasil = $this->view_dc_transaction.'_berhasil';
        $this->view_dc_member_verified = $this->view_dc_member.'_verified';
        $this->view_dc_member_unverified = $this->view_dc_member.'_unverified';
        $this->view_dc_rekening_verified = $this->view_dc_rekening.'_verified';
        $this->view_dc_rekening_unverified = $this->view_dc_rekening.'_unverified';
        $this->view_dc_voucher_member = $this->view_prefix.$this->tbl_voucher.'_member';
        $this->view_dc_coin = $this->view_prefix.$this->table_coin;

        //load model fo all page
        $this->load->model('Model_basic', 'model_basic');

        //apperance function for all
        $this->appearance = select_where($this->tbl_appearance, 'id', 1)->row();
        $this->news_side_bar = select_all_limit_random($this->tbl_news, 3);

        // TEMPLATE DIREKTORI
        $this->view_template = 'template/content';
    }

    function name_method($method)
    {
        if ($method != 'index') {
            echo str_replace('_', ' ', $method);
        }
    }

    function check_login()
    {
        if ($this->session->userdata('admin') == FALSE) {
            redirect('admin/login');
        } else {
            return true;
        }
    }

    function get_menu()
    {
        if ($this->session->userdata('admin')) {
            $user_groups = $this->session->userdata['admin']['user_group'];
        } else {
            $user_groups = 0;
        }
        $data = $this->model_basic->get_menu($user_groups);
        return $data;
    }


    function check_access()
    {
        if ($this->session->userdata('admin')) {
            $user_groups = $this->session->userdata['admin']['user_group'];
        } else {
            $user_groups = 0;
        }
        $data = $this->model_basic->get_menu_access($user_groups);
        foreach ($data as $key) {
            $form = $key->target . '_form';
            if ($key->target == $this->uri->segment(2) or $key->target == $this->uri->segment(1) or $form == $this->uri->segment(2)) {
                redirect('admin/404');
            }
        }
    }

    function pagination($base_url, $total_row, $total_item)
    {
        $config = array();
        $config["base_url"] = $base_url;
        $config["total_rows"] = $total_row;
        $config["per_page"] = 1;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $total_row;
        $config['first_url'] = '1';
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        $this->pagination->initialize($config);
        $data = $this->pagination->create_links();
        return $data;
    }

    function pagination_param($base_url, $total_row, $total_item)
    {
        $config = array();
        $config["base_url"] = $base_url;
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $total_row;
        $config['first_url'] = '1';
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        $this->pagination->initialize($config);
        $data = $this->pagination->create_links();
        return $data;
    }

}
