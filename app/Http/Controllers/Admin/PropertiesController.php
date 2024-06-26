<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Properties;
use App\Models\PropertyLineItem;
use App\Models\PropertyService;
use App\Models\PropertyNeighbour;
use App\Models\PropertyRoom;
use App\Models\PropertyPrice;
use Illuminate\Http\Request;
    use App\Models\Locations;
    use App\Models\MediaManager;

class PropertiesController extends Controller
{
    public function index()
    {
        return Properties::query()
        ->with('type')
        ->when(request('type'), function ($query) {
            return $query->where('property_type_id', request('type'));
        })
            ->latest()
            ->paginate();
    }

    public function getAllMedia($property_id)
    {
        $property = Properties::findOrFail($property_id);

        $featuredMediaFile = $property->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();

        if ($featuredMediaFile) {
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        $mediaFiles = $property->mediaFiles()->orderBy('display_order', 'ASC')->get();

        $mediaFiles->each(function ($mediaItem) {
            $mediaItem->url = $mediaItem->getUrl();
        });

        $attachmentIDs = $mediaFiles->map->id;

        return [
            'featuredMediaFile' => $featuredMediaFile,
            'mediaFiles' => $mediaFiles,
            'attachmentIDs' => $attachmentIDs,
        ];
    }

    public function featuredUpdate($property_id, $media_id)
    {
        $property = Properties::findOrFail($property_id);

        $mediaIds = $property->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $property->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $property->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($property_id, $media_id)
    {
        $property = Properties::findOrFail($property_id);

        $existingMedia = $property->mediaFiles()->find($media_id);

        if ($existingMedia) {
            $response = $property->mediaFiles()->detach($media_id, ['model_type' => get_class($property)]);
            return 'removed';
        } else {
            $response = $property->mediaFiles()->attach($media_id, ['model_type' => get_class($property)]);
            return 'attached';
        }
    }



    public function test($id)
    {
        $ids = [8,9,10];
        
        // $mediaFiles = MediaFile::find($ids);

        $location = Locations::findOrFail($id);
        $response = $location->mediaFiles()->detach($ids, ['model_type' => get_class($location)]);
        $response = $location->mediaFiles()->attach($ids, ['model_type' => get_class($location), 'is_featured' => true]);

        $mediaFiles = $location->mediaFile; //Trait Function

        $mediaFiles = $location->mediaFiles()
                            ->wherePivot('is_featured', true)
                            ->get();


        echo $mediaFilesFeaturedURL = $location->featuredMediaFileURL();
        echo 'featured<br/>';


        foreach ($mediaFiles as $mediaItem) {
            $url = $location->mediaFileURL($mediaItem->id);
            echo $url . '<br>';
        }


        dd($mediaFiles);
    }

    public function store(Request $request, Properties $properties)
    {
        // Define validation rules for specific fields
        $validationRules = [
            'name' => 'required',
            'item_code' => 'required',
            'slug' => 'required',
            'property_type_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules, [
            'property_type_id.required' => 'The property type field is required.',
        ]);
        // Update the model with all form fields
        $properties->create($request->except(['amenities','features','lineitems']));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));
        // Use the sync method to update the selected features
        $properties->features()->sync($request->input('features', []));


        // Handle lineitems if provided
        if (isset($request['lineitems'])) {
            // Create and associate line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new PropertyLineItem([
                    'name' => $lineitem['name'],
                    'value' => $lineitem['value'],
                    'value_type' => $lineitem['value_type'],
                    'apply_on' => $lineitem['apply_on'],
                    'is_required' => $lineitem['is_required'],
                    'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }

            $property->lineitems()->saveMany($lineItems);
        }

        return response()->json(['message' => 'success']);
    }

    public function edit(Properties $properties)
    {
        $properties->load('services')->load('lineitems')->load('neighbours')->load('rooms')->load('prices')->load('bookings');
        $properties['associated_amenities'] = $properties->amenities->pluck('id');
        $properties['associated_features'] = $properties->features->pluck('id');

        return $properties;
    }

    public function update(Request $request, Properties $properties)
    {
        // Define validation rules for specific fields
        $validationRules = [
            'name' => 'required',
            'item_code' => 'required',
            'slug' => 'required',
            'property_type_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules, [
            'property_type_id.required' => 'The property type field is required.',
        ]);
        // Update the model with all form fields
        $properties->update($request->except(['amenities','features','lineitems','services','neighbours','rooms','prices','bookings']));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));
        // Use the sync method to update the selected features
        $properties->features()->sync($request->input('features', []));

        if (isset($request['lineitems'])) {
            // Delete existing line items for the property
            $properties->lineitems()->delete();
        
            // Create and associate new line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new PropertyLineItem([
                    'name' => $lineitem['name'],
                    'value' => $lineitem['value'],
                    'value_type' => $lineitem['value_type'],
                    'apply_on' => $lineitem['apply_on'],
                    'is_required' => $lineitem['is_required'],
                    // 'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }
        
            $properties->lineitems()->saveMany($lineItems);
        }


        if (isset($request['services'])) {
            // Delete existing services for the property
            $properties->services()->delete();
        
            // Create and associate new services
            $services = [];
            foreach ($request['services'] as $service) {
                $services[] = new PropertyService([
                    'name' => $service['name'],
                    // 'icon' => $service['icon'],
                    // 'image' => $service['image'],
                    'price' => $service['price'],
                    'description' => $service['description'],
                    'display_order' => $service['display_order'],
                ]);
            }
        
            $properties->services()->saveMany($services);
        }
        

        if (isset($request['neighbours'])) {
            // Delete existing neighbours for the property
            $properties->neighbours()->delete();
        
            // Create and associate new neighbours
            $neighbours = [];
            foreach ($request['neighbours'] as $neighbour) {
                $neighbours[] = new PropertyNeighbour([
                    'name' => $neighbour['name'],
                    // 'icon' => $neighbour['icon'],
                    // 'image' => $neighbour['image'],
                    'distance' => $neighbour['distance'],
                    'description' => $neighbour['description'],
                    'display_order' => $neighbour['display_order'],
                ]);
            }
        
            $properties->neighbours()->saveMany($neighbours);
        }

        if (isset($request['rooms'])) {
            // Delete existing neighbours for the property
            $properties->rooms()->delete();
        
            // Create and associate new rooms
            $rooms = [];
            foreach ($request['rooms'] as $room) {
                $rooms[] = new PropertyRoom([
                    'name' => $room['name'],
                    'type' => $room['type'],
                    // 'image' => $room['image'],
                    'description' => $room['description'],
                    'display_order' => $room['display_order'],
                ]);
            }
        
            $properties->rooms()->saveMany($rooms);
        }


        if (isset($request['prices'])) {
            $properties->prices()->delete();
            
            $prices = [];
            
            foreach ($request['prices'] as $price) {
                $prices[] = new PropertyPrice([
                    'name' => $price['name'],
                    'date_start' => $price['date_start'],
                    'date_end' => $price['date_end'],
                    // 'icon' => $price['icon'],
                    'image' => $price['image'],
                    'price' => $price['price'],
                    'display_order' => $price['display_order'],
                ]);
            }
        
            $properties->prices()->saveMany($prices);
        }

        return response()->json(['success' => true]);
    }


    public function destroy(Properties $properties)
    {
        $properties->delete();

        return response()->json(['success' => true], 200);
    }
}
