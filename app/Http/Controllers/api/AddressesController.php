<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PhilippineRegion;
use App\Models\PhilippineProvince;
use App\Models\PhilippineCity;
use App\Models\PhilippineBarangay;

use App\Traits\Messages;

class AddressesController extends Controller
{
    use Messages;

    /**
     * @group Addresses
     * 
     * Philippines Regions
     * 
     * @unauthenticated
     */
    public function regions()
    {
        $regions = PhilippineRegion::all(['region_code','region_description']);

        $regions = $regions->map(function($region) {
            return [
                'code' => $region->region_code,
                'name' => $region->region_description,
            ];
        });

        return $this->jsonSuccessResponse($regions, 200);        
    }

    /**
     * @group Addresses
     * 
     * Philippines Provinces
     * 
     * @urlParam code string required
     * 
     * @unauthenticated
     */
    public function provinces($code)
    {
        $provinces = PhilippineProvince::where('region_code',$code)->get();

        $provinces = $provinces->map(function($province) {
            return [
                'code' => $province->province_code,
                'name' => $province->province_description,
            ];            
        });

        return $this->jsonSuccessResponse($provinces, 200);           
    }

    /**
     * @group Addresses
     * 
     * Philippines Cities
     * 
     * @urlParam code string required
     * 
     * @unauthenticated
     */
    public function cities($code)
    {
        $cities = PhilippineCity::where('province_code',$code)->get();

        $cities = $cities->map(function($city) {
            return [
                'code' => $city->city_municipality_code,
                'name' => $city->city_municipality_description,
            ];            
        });

        return $this->jsonSuccessResponse($cities, 200);           
    }

    /**
     * @group Addresses
     * 
     * Philippines Barangays
     * 
     * @urlParam code string required
     * 
     * @unauthenticated
     */
    public function barangays($code)
    {
        $barangays = PhilippineBarangay::where('city_municipality_code',$code)->get();

        $barangays = $barangays->map(function($barangay) {
            return [
                'code' => $barangay->barangay_code,
                'name' => $barangay->barangay_description,
            ];            
        });

        return $this->jsonSuccessResponse($barangays, 200);         
    }      
}
