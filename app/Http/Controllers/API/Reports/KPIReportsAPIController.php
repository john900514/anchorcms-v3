<?php

namespace AnchorCMS\Http\Controllers\API\Reports;

use AnchorCMS\AdBudgets;
use AnchorCMS\AdMarkets;
use AnchorCMS\Features;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use AnchorCMS\Http\Controllers\Controller;
use Silber\Bouncer\BouncerFacade as Bouncer;

class KPIReportsAPIController extends Controller
{
    protected $budgets, $features, $markets, $request;

    public function __construct(Request $request, Features $features, AdBudgets $budgets, AdMarkets $markets)
    {
        $this->budgets = $budgets;
        $this->features = $features;
        $this->markets = $markets;
        $this->request = $request;
    }

    public function get_report($client_id)
    {
        $results = ['success' => false, 'reason' => 'Feature not enabled for client'];

        // See if the client has the kpi feature enabled or fail
        $kpi_record = $this->features->getFeature('KPI Snapshot', $client_id);

        if($kpi_record)
        {
            $access_granted = false;
            $allowed_roles = str_getcsv($kpi_record->allowed_roles,',');
            $allowed_abilities = str_getcsv($kpi_record->allowed_abilities,',');

            // ACL - See if the user has permissions to view report or fail
            if(!Bouncer::is(backpack_user())->a('god'))
            {
                foreach ($allowed_roles as $role)
                {
                    if($access_granted = Bouncer::is(backpack_user())->a($role))
                    {
                        break;
                    }
                }

                if(!$access_granted)
                {
                    foreach ($allowed_abilities as $ability)
                    {
                        if($access_granted = backpack_user()->can($ability))
                        {
                            break;
                        }
                    }
                }
            }
            else
            {
                $access_granted = true;
            }

            if($access_granted)
            {
                $feature_attributes = $kpi_record->feature_attributes;

                // Get feature and attributes records or fail
                if(count($feature_attributes) > 0)
                {
                    // Locate the attribute record with the URL or fail
                    $api_url_record = $feature_attributes->where('attribute', '=', 'API URL')->first();

                    if(!is_null($api_url_record))
                    {
                        $api_url = $api_url_record->attribute_desc;

                        $headers = [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Client '.base64_encode($client_id)
                        ];

                        $payload = [
                            'date' => date('Y-m-d', strtotime('now -1DAY'))
                        ];

                        // Ping the client for the KPI data or fail
                        $response = Curl::to("{$api_url}/anchor-cms/feature/reports/kpi")
                            ->withHeaders($headers)
                            ->withData($payload)
                            ->asJson(true)
                            ->post();

                        if($response)
                        {
                            if(array_key_exists('message', $response))
                            {
                                $results['reason'] = "Error! KPI Snapshot Endpoint says {$response['message']}.";
                            }
                            else if(array_key_exists('success', $response))
                            {
                                if($response['success'])
                                {
                                    // Return the response.
                                    $results = $response;
                                }
                                else
                                {
                                    $results['reason'] = "Error! - KPI Snapshot Endpoint says {$response['reason']}";
                                }

                            }
                            else
                            {
                                $results['reason'] = "Error! Unknown response from Client Server.";
                            }
                        }
                        else
                        {
                            $results['reason'] = "Unable to initiate contact with KPI Snapshot server.";
                        }
                    }
                    else
                    {
                        $results['reason'] = 'Cannot Locate Client Access Point.';
                    }
                }
                else
                {
                    $results['reason'] = 'Client has not finished setting up feature.';
                }
            }
            else
            {
                $results['reason'] = 'Access Denied. You do not have permissions to view this report.';
            }
        }

        return response($results);
    }
}
