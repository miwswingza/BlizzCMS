<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {

    public function login()
    {
        if ($this->m_modules->getStatusLogin() != '1')
            redirect(base_url(),'refresh');

        if ($this->m_data->isLogged())
            redirect(base_url(),'refresh');

        if ($this->m_general->getExpansionAction() == 1)
        {
            $data = array(
                "username_form" => array(
                    'id' => 'login_username',
                    'name' => 'login_username',
                    'class' => 'uk-input',
                    'required' => 'required',
                    'placeholder' => $this->lang->line('username_re'),
                    'type' => 'text'),

                "password_form" => array(
                    'id' => 'login_password',
                    'name' => 'login_password',
                    'class' => 'uk-input',
                    'required' => 'required',
                    'placeholder' => $this->lang->line('password_re'),
                    'type' => 'password'),

                "submit_form" => array(
                    'id' => 'button_log',
                    'name' => 'button_log',
                    'value' => $this->lang->line('button_log'),
                    'class' => 'uk-button uk-button-primary uk-width-1-1')
            );

            $this->load->view('login1', $data);
        }
        else
        {
            $data = array(
                "email_form" => array(
                    'id' => 'login_email',
                    'name' => 'login_email',
                    'class' => 'uk-input',
                    'required' => 'required',
                    'placeholder' => $this->lang->line('email_re'),
                    'type' => 'email'),

                "password_form" => array(
                    'id' => 'login_password',
                    'name' => 'login_password',
                    'class' => 'uk-input',
                    'required' => 'required',
                    'placeholder' => $this->lang->line('password_re'),
                    'type' => 'password'),

                "submit_form" => array(
                    'id' => 'button_log',
                    'name' => 'button_log',
                    'value' => $this->lang->line('button_log'),
                    'class' => 'uk-button uk-button-primary uk-width-1-1')
            );

            $this->load->view('login2', $data);
        }

        $this->load->view('footer');
    }

    public function verify1()
    {
        $username = $this->input->post('login_username');
        $password = $this->input->post('login_password');

        $id = $this->m_data->getIDAccount($username);

        if ($id == "0")
            redirect(base_url('login?account'),'refresh');
        else
        {
            $password = $this->m_data->encryptAccount($username, $password);

            if (strtoupper($this->m_data->getPasswordAccountID($id)) == strtoupper($password))
            {
                $this->m_data->arraySession($id);
            }
            else
                redirect(base_url('login?password'),'refresh');
        }
    }

    public function verify2()
    {
        $email    = $this->input->post('login_email');
        $password = $this->input->post('login_password');

        $id = $this->m_data->getIDEmail($email);

        if ($id == "0")
            redirect(base_url('login?account'),'refresh');
        else
        {
            $password = $this->m_data->encryptBattlenet($email, $password);

            if (strtoupper($this->m_data->getPasswordBnetID($id)) == strtoupper($password))
                $this->m_data->arraySession($id);
            else
                redirect(base_url('login?password'),'refresh');
        }
    }

    public function register()
    {
        if ($this->m_modules->getStatusRegister() != '1')
            redirect(base_url(),'refresh');

        if ($this->m_data->isLogged())
            redirect(base_url(),'refresh');

        $this->load->library('recaptcha');

        $this->load->model('user_model');

        if ($this->config->item('maintenance_mode') == '1')
        {
            if ($this->m_data->isLogged() && $this->m_general->getPermissions($this->session->userdata('fx_sess_id')) == 1)
            {
                $this->load->view('register');
            }
            else
                $this->load->view('maintenance');
        }
        else
            $this->load->view('register');

        $this->load->view('footer');
    }

    public function logout()
    {
        $this->m_data->logout();
    }

    public function panel()
    {
        if ($this->m_modules->getStatusUCP() != '1')
            redirect(base_url(),'refresh');

        $this->load->model('user_model');

        if (!$this->m_data->isLogged())
            redirect(base_url(),'refresh');

        if ($this->config->item('maintenance_mode') == '1')
        {
            if ($this->m_data->isLogged() && $this->m_general->getPermissions($this->session->userdata('fx_sess_id')) == 1)
            {
                $this->load->view('panel');
            }
            else
                $this->load->view('maintenance');
        }
        else
            $this->load->view('panel');

        $this->load->view('footer');
    }

    public function profile($id)
    {
        if ($this->m_modules->getStatusUCP() != '1')
            redirect(base_url(),'refresh');

        if (empty($id) || is_null($id) || $id == '0')
            redirect(base_url(),'refresh');

        $this->load->model('user_model');

        $data['idlink'] = $id;

        if ($this->config->item('maintenance_mode') == '1')
        {
            if ($this->m_data->isLogged() && $this->m_general->getPermissions($this->session->userdata('fx_sess_id')) == 1)
            {
                $this->load->view('profile', $data);
            }
            else
                $this->load->view('maintenance');
        }
        else
            $this->load->view('profile', $data);

        $this->load->view('footer');
    }
}
