<?php

namespace App\Repositories\CampaignRepository;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

interface CampaignRepositoryInterface
{
  public function create($request);

  public function deleteById($campaign_id);

  public function update($data, $id);

  public function getAll();

  public function search($campaign_name, $start_date, $end_date);

  public function searchByName($campaign_name);
}