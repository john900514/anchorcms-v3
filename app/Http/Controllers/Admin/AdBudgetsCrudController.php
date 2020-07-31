<?php

namespace AnchorCMS\Http\Controllers\Admin;

//use App\Services\AdBudgetMgntService;
use Bouncer;
use AnchorCMS\Clients;
//use App\Repositories\ClientServiceRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AnchorCMS\Http\Requests\StandardStoreRequest as StoreRequest;
use AnchorCMS\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AdBudgetsCrudController
 * @package AnchorCMS\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdBudgetsCrudController extends CrudController
{
    protected $ad_svc, $clients, $client_svc_repo;

    public function __construct(Clients $clients)//, ClientServiceRepository $c_repo, AdBudgetMgntService $ad_svc)
    {
        parent::__construct();
        $this->clients = $clients;
        //$this->client_svc_repo = $c_repo;
        //$this->ad_svc = $ad_svc;
    }

    public function setup()
    {
        $this->data['page'] = 'crud-ad-budgets';
        if(Bouncer::is(backpack_user())->a('god') || backpack_user()->can('view-ad-budgets'))
        {
            /*
            |--------------------------------------------------------------------------
            | CrudPanel Basic Information
            |--------------------------------------------------------------------------
            */
            $this->crud->setModel('AnchorCMS\AdBudgets');$this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-ad-budgets');
            $this->crud->setEntityNameStrings('Ad Budget', 'Ad Budgets');
            if(session()->has('active_client'))
            {
                $v_market_name = [
                    'name' => 'market.market_name', // the db column name (attribute name)
                    'label' => "Market Name", // the human-readable label for it
                    'type' => 'text', // the kind of column to show
                    'entity' => 'market',
                    'priority' => 2
                ];


                $v_club_name = [
                    'name' => 'club_id',
                    'label' => 'Club',
                    'type' => 'closure',
                    'function' => function($entry) /*use($repo, $client)*/ {
                        $results = false;

                        //$name = $repo->getClubName($client->id, $entry->club_id);

                        //if($name)
                        //{
                            //$results = $name;
                            $results = $entry->club_id;
                        //}

                        return $results;
                    },
                    'priority' => 1
                ];


                $v_facebook_budget = [
                    'name' => 'facebook_ig_budget', // the db column name (attribute name)
                    'label' => "Facebook/IG Budget", // the human-readable label for it
                    'type' => 'closure',
                    'function' => function($entry) {
                        $results = 'No Budget Set';

                        if(!is_null($entry->facebook_ig_budget))
                        {
                            $results = "$".$entry->facebook_ig_budget;
                        }

                        return $results;
                    },
                    'priority' => 3
                ];
                $v_google_budget = [
                    'name' => 'google_budget', // the db column name (attribute name)
                    'label' => "Google Budget", // the human-readable label for it
                    'type' => 'closure',
                    'function' => function($entry) {
                        $results = 'No Budget Set';

                        if(!is_null($entry->google_budget))
                        {
                            $results = "$".$entry->google_budget;
                        }

                        return $results;
                    },
                    'priority' => 3
                ];
                $v_fb_current_spend = [
                    'name' => 'created_at', // the db column name (attribute name)
                    'label' => "Current Spend (Facebook)", // the human-readable label for it
                    'type' => 'closure',
                    'function' => function($entry) {
                        $results = 'Requires Budget';

                        if(!is_null($entry->facebook_ig_budget))
                        {
                            // Get the day of the month (yesterday)
                            $day_of_mo = intval(date('d',strtotime('-1 day')));
                            // Get the amount of days in the current month
                            $days_in_mo = intval(date('t'));
                            // Do the math
                            $spend = number_format(($entry->facebook_ig_budget / $days_in_mo) * $day_of_mo, 2);
                            // Return with a $sign
                            $results = '$'.$spend;
                        }

                        return $results;
                    },
                    'priority' => 3
                ];

                $v_google_current_spend = [
                    'name' => 'updated_at', // the db column name (attribute name)
                    'label' => "Current Spend (Google)", // the human-readable label for it
                    'type' => 'closure',
                    'function' => function($entry) {
                        $results = 'Requires Budget';

                        if(!is_null($entry->google_budget))
                        {
                            // Get the day of the month (yesterday)
                            $day_of_mo = intval(date('d',strtotime('-1 day')));
                            // Get the amount of days in the current month
                            $days_in_mo = intval(date('t'));
                            // Do the math
                            $spend = number_format(($entry->google_budget / $days_in_mo) * $day_of_mo, 2);
                            // Return with a $sign
                            $results = '$'.$spend;
                        }

                        return $results;
                    },
                    'priority' => 3
                ];

                $column_defs = [$v_market_name, $v_club_name, $v_facebook_budget, $v_fb_current_spend, $v_google_budget, $v_google_current_spend];

                $this->crud->addColumns($column_defs);

                $cu_client_id = [   // Hidden
                    'name' => 'client_id',
                    'type' => 'hidden',
                    'value' => session()->get('active_client'),
                ];

                /*
                $cu_market_name = [
                    'name' => 'market_id',  // the db column name (attribute name)
                    'label' => "Select Market", // the human-readable label for it
                    'type' => 'select_from_array',
                    'options' => $this->ad_svc->getMarketCRUDDropdownOptions($client_id)
                ];

                $cu_club_name = [
                    'name' => 'club_id',  // the db column name (attribute name)
                    'label' => "Select Club", // the human-readable label for it
                    'type' => 'select_from_array',
                    'options' => $this->client_svc_repo->getClubDropdown($client_id)
                ];
                */
                $cu_fb_budget = [
                    'type' => 'text',
                    'name' => 'facebook_ig_budget',
                    'label' => 'Facebook/Instagram Budget',
                    'priority' => 1
                ];

                $cu_google_budget = [
                    'type' => 'text',
                    'name' => 'google_budget',
                    'label' => 'Google Budget',
                    'priority' => 1
                ];

                $both_defs = [$cu_client_id, /*$cu_market_name, $cu_club_name,*/ $cu_fb_budget, $cu_google_budget];
                $this->crud->addFields($both_defs,'both');

                // add asterisk for fields that are required in AdBudgetsRequest
                $this->crud->setRequiredFields(StoreRequest::class, 'create');
                $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
                $this->crud->enableExportButtons();
                $this->data['can_report'] = true;

                // get dependency data
                $this->data['client_id'] = session()->get('active_client');
            }
            else
            {
                $this->crud->hasAccessOrFail('big-derpz');
            }
        }
        else
        {
            $this->crud->hasAccessOrFail('big-derpz');
        }
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
