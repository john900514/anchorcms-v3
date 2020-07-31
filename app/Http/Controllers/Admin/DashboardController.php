<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Clients;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;

class DashboardController extends Controller
{
    protected $clients, $request;

    public function __construct(Request $request, Clients $clients)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->request = $request;
    }

    public function index()
    {
        $args = [
            'page' => 'dashboard',
            //'sidebar_menu' => $this->menu_options()->getOptions('sidebar')
            'components' => [
                'dashboard' => [
                    'layout' => 'default',
                    'args' => []
                ]
            ]
        ];

        $client = $this->clients->find(backpack_user()->client_id);

        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $client = $this->clients->find(session()->get('active_client'));
            }
        }

        $args['client'] = $client;

        return view('backpack::dashboard', $args);
    }
}
