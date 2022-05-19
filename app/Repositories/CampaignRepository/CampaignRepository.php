<?php

namespace App\Repositories\CampaignRepository;

use App\Http\Requests\UserRequest;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Validator;

use App\Repositories\CampaignRepository\CampaignRepositoryInterface;
use App\Models\Campaign;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class CampaignRepository implements CampaignRepositoryInterface
{

  // Create new campaign 
  public function create($data)
  {
    return Campaign::insert($data);
  }

  // Delete campaign by ID 
  public function deleteById($id)
  {
    $deleteCampaign = Campaign::where('id', $id)->update(['deleted_flag' => 'true']);
    return $deleteCampaign;
  }

  // Update campaign by ID
  public function update($data, $id)
  {
    return Campaign::where('id', $id)->update($data);
  }

  // Get all list campaign 
  public function getAll()
  {
    $campaigns = Campaign::where('deleted_flag', 'false')->get();
    return $campaigns;
  }

  public function search($campaign_name, $start_date, $end_date)
  {
    $campaigns = Campaign::whereDate('start_date', '<=', $start_date)->whereDate('end_date', '<=', $end_date)
      ->get();
    return $campaigns;
  }

  public function searchByName($campaign_name)
  {
    $campaigns = Campaign::where('name', 'like', "%{$campaign_name}%")->get();
    return $campaigns;
  }
}