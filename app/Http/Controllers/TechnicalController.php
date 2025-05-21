<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TechnicalController extends Controller
{
    public function index(Request $request)
    {
        $district = $request->input('district', null);
        $latitude = $request->input('latitude', null);
        $longitude = $request->input('longitude', null);

        $showDistance = !is_null($latitude) && !is_null($longitude);
        $maxDistance = 10; // Set maximum distance to 10 km

        // Fetch technicians within 10 km
        $technicians = Technician::when($district, function ($query, $district) {
                return $query->where('district', 'like', '%' . $district . '%');
            })
            ->when($showDistance, function ($query) use ($latitude, $longitude, $maxDistance) {
                return $query->selectRaw(
                    "*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                    [$latitude, $longitude, $latitude]
                )
                ->orderBy('distance')
                ->having('distance', '<', $maxDistance);
            })
            ->where('service_type', 'pc_building')
            ->get();

        $districts = Technician::select('district')->distinct()->pluck('district');

        // Initially empty pcShops, will be fetched via AJAX
        $pcShops = [];

        return view('technical.network', compact('technicians', 'districts', 'district', 'showDistance', 'latitude', 'longitude', 'pcShops', 'maxDistance'));
    }

    public function fetchNearbyShops(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $maxDistance = 10;

        Log::info('Fetching nearby shops', [
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        $pcShops = [];
        if ($latitude && $longitude) {
            try {
                $overpassUrl = "https://overpass-api.de/api/interpreter";
                $query = "[out:json];node(around:10000,{$latitude},{$longitude})[shop=computer];out body;>;out skel qt;";
                
                Log::info('Sending Overpass API request', [
                    'url' => $overpassUrl,
                    'query' => $query
                ]);
                
                $response = Http::get($overpassUrl, ['data' => $query]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Overpass API response received', [
                        'element_count' => count($data['elements'] ?? [])
                    ]);
                    
                    foreach ($data['elements'] as $element) {
                        if (isset($element['lat'], $element['lon'], $element['tags']['name'])) {
                            $distance = $this->calculateDistance($latitude, $longitude, $element['lat'], $element['lon']);
                            if ($distance <= $maxDistance) {
                                $address = $element['tags']['addr:street'] ?? '';
                                if (!empty($element['tags']['addr:housenumber'])) {
                                    $address = $element['tags']['addr:housenumber'] . ' ' . $address;
                                }
                                if (empty($address)) {
                                    $address = $element['tags']['addr:city'] ?? 'Address unavailable';
                                }
                                
                                $pcShops[] = [
                                    'name' => $element['tags']['name'] ?? 'Unnamed Shop',
                                    'latitude' => $element['lat'],
                                    'longitude' => $element['lon'],
                                    'distance' => number_format($distance, 2),
                                    'address' => $address
                                ];
                            }
                        }
                    }
                    
                    usort($pcShops, function($a, $b) {
                        return $a['distance'] <=> $b['distance'];
                    });
                    
                    Log::info('Found PC shops', [
                        'count' => count($pcShops)
                    ]);
                } else {
                    Log::error('Overpass API request failed', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return response()->json([
                        'error' => 'Failed to fetch shops. API request failed with status: ' . $response->status()
                    ], 500);
                }
            } catch (\Exception $e) {
                Log::error('Error fetching nearby shops', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'error' => 'Failed to fetch shops due to an internal error: ' . $e->getMessage()
                ], 500);
            }
        } else {
            Log::warning('Missing coordinates in fetch request');
            return response()->json(['error' => 'Latitude and longitude are required.'], 400);
        }

        return response()->json(['pcShops' => $pcShops]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function store(Request $request) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}