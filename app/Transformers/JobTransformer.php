<?php

namespace App\Transformers;
use App\Location;
use App\Company;
use App\Asset;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LocationController;

class JobTransformer
{
    public function onJobPageLoad($assetId)
    {
        $assetController = new AssetController();
        $asset = $assetController->show($assetId);

        $assetController = new AssetController();
        $assets = $assetController->index();

        $companyController = new CompanyController();
        $companies = $companyController->index();

        $locationController = new LocationController();
        $locations = $locationController->index();
        
        return ['asset' => $asset, 'assets' => $assets, 'companies' => $companies, 'locations' => $locations];
    }
}