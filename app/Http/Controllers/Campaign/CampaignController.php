<?php

namespace App\Http\Controllers\Campaign;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CampaignRepository\CampaignRepositoryInterface;
use App\Models\Campaign;
use Illuminate\Support\Facades\Input;

class CampaignController extends Controller
{
    protected $CampaignRepositoryInterface;

    public function __construct(CampaignRepositoryInterface $CampaignRepositoryInterface)
    {
        $this->CampaignRepositoryInterface = $CampaignRepositoryInterface;

        $this->middleware('auth:api');
    }
    // create new campaign 
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:1,255',
            'url' => 'required|string|max:100|',
            'title' => 'required|string|between:1,255',
            'status' => 'required|boolean',
            'used_amount' => 'required|numeric',
            'budget' => 'required|numeric',
            'start_date' => 'required|date_format:d/m/Y H:i',
            'end_date' => 'required|date_format:d/m/Y H:i|after_or_equal:start_date',
            'description' => 'required|string|between:1,255 ',
            'bid_amount' => 'required|numeric',
            'deleted_flag' => 'required|boolean',
            'created_by' => 'required|integer',
            'updated_by' => 'required|integer ',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $campaign = $this->CampaignRepositoryInterface->create(array_merge($request->all()));
        $campaigns = $this->CampaignRepositoryInterface->getAll();
        return response()->json([
            'message' => 'Create successfully campaign',
            'data' => $campaign,
            'campaigns' => $campaigns
        ], 201);
    }

    public function findById($campaign_id)
    {
    }

    // delete soft campaign
    public function deleteById($id)
    {
        try {
            $deleteCampaign = $this->CampaignRepositoryInterface->deleteById($id);
            if ($deleteCampaign) {
                $campaigns = $this->CampaignRepositoryInterface->getAll();
                return response()->json([
                    'message' => 'Delete successfully campaign',
                    'data' => $deleteCampaign,
                    'campaigns' => $campaigns
                ], 204);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Delete fail',
            ], 422);
        }
    }

    public function update($id)
    {
        // $validator = Validator::make($request->all(), [
        //     'url' => 'required|string|max:100',
        //     'title' => 'required|between:1,255',
        //     'status' => 'required|boolean',
        //     'budget' => 'required|numeric',
        //     'start_date' => 'required|date_format:d/m/Y H:i',
        //     'end_date' => 'required|date_format:d/m/Y H:i|after_or_equal:start_date',
        //     'description' => 'required|string|between:1,255 ',
        //     'bid_amount' => 'required|numeric',
        // ]);

        // $data1 =  $request->input('url');

        // if ($validator->fails()) {
        //     return response()->json($validator->errors()->toJson(), 400);
        // }

        // $data = array_merge(
        //     $validator->validated()
        // );
        // $newCampaign =  $this->CampaignRepositoryInterface->update($data, $id);
        // // $campaigns =  $this->CampaignRepositoryInterface->getAll();

        // if ($newCampaign) {
        //     return response()->json([
        //         'message' => 'Update successfully campaign',
        //         'newCampaigs' =>  $data1,
        //     ], 204);
        // }
        return response()->json([
            'newCampaigs' =>  "updated",
        ], 204);
    }

    // Get list campaign with deleted_flag
    public function getAll()
    {
        $campaigns = $this->CampaignRepositoryInterface->getAll();
        return response()->json($campaigns, 200);
    }

    // Search campaigns with field name&& start_date && end_date
    public function search(Request $request)
    {
        $campaign_name = $request->get('name');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        try {
            if ($start_date == null && $end_date == null) {
                $campaigns = $this->CampaignRepositoryInterface->searchByName($campaign_name);
            } elseif ($start_date != '' && $end_date != '') {
                $campaigns = $this->CampaignRepositoryInterface->search($campaign_name, $start_date, $end_date);
            }
            if ($campaigns) {
                return response()->json([
                    'message' => 'get successfully',
                    'data' => $campaigns
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'get fail',
            ], 422);
        }
    }
}