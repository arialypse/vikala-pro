<?php
namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        
        $data = [
            'title'             => 'Dashboard',
            'username'          => $session->get('username'),
            'fullname'          => $session->get('fullname'),
            'role'              => $session->get('role'),
            'active_company_id' => $session->get('active_company_id'),
            'allowed_companies' => $session->get('allowed_companies')
        ];

        return view('dashboard/index', $data);
    }
}
