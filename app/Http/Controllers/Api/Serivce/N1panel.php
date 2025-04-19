<?php

namespace App\Http\Controllers\Api\Serivce;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class N1panel extends Controller
{

    public function __construct()
    {
    }

    public function getAllService()
    {
       $response = Http::asMultipart()->get('https://n1panel.com/api/v2', [
            'key' => '9fbf16383d4d83a365fa65ea2468e934',
            'action' => 'services'
        ]);

        $categories = [];
        $index = 0;
        $succes =0;
        if ($response->successful()) {
            $data = $response->json();
            foreach ($data as $item) {
                if (
                    isset($item['category']) &&
                    (stripos($item['category'], 'Tiktok') !== false || stripos($item['category'], 'Facebook') !== false) &&
                    !in_array($item['category'], $categories)
                ) {
                    // Add the category to the unique categories array
                    $categories[] = $item['category'];
                }
            }
        } else {
            $status = $response->status();
            $errorMessage = $response->body();
        }

        foreach ($categories as $item) {
            $index++;
            $categoryParts = explode(" ", trim($item));

            $social = ServiceSocial::where('domain', getDomain())->where('slug',  strtolower($categoryParts[0]))->first();
            if ($social) {
                $slug_service = $categoryParts[0] . $index;
                $service = Service::where('slug', $slug_service)->where('service_social',  ($categoryParts[0]))->where('domain', getDomain())->first();
                if ($service) {
                    switch ($categoryParts[1]) {
                        case 'Views':
                        case 'Likes':
                        case 'Followers':
                            $file_view = storage_path('/app/public/code/default.lbd');
                            $action_type = 'default';
                            break;
                        case 'reaction':
                            $file_view = storage_path('/app/public/code/reaction.lbd');
                            $action_type = 'reaction';
                            break;
                        case 'reaction-speed':
                            $file_view = storage_path('/app/public/code/reaction-speed.lbd');
                            $action_type = 'reaction-speed';
                            break;
                        case 'Comments':
                            $file_view = storage_path('/app/public/code/comment2.lbd');
                            $action_type = 'comment';
                            break;
                        case 'comment-quantity':
                            $file_view = storage_path('/app/public/code/comment.lbd');
                            $action_type = 'comment-quantity';
                            break;
                        case 'minutes':
                            $file_view = storage_path('/app/public/code/minutes.lbd');
                            $action_type = 'minutes';
                            break;
                        case 'time':
                            $file_view = storage_path('/app/public/code/time.lbd');
                            $action_type = 'time';
                            break;
                        default:
                            $file_view = storage_path('/app/public/code/default.lbd');
                            $action_type = 'default';
                            break;
                    }
                    $social_folder = $social->folder;
                    $file_view = File::get($file_view);
                    $file_service = $slug_service . Str::random(4);
                    if (!File::exists(resource_path('views/service/' . $social_folder))) {
                        File::makeDirectory(resource_path('views/service/' . $social_folder), 0777, true, true);
                    }

                    // dd(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'));
                    if (!File::exists(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'))) {
                        File::put(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'), $file_view);
                    }
                    Service::create([
                        'name' =>  $item . 'n1panel',
                        'slug' => $slug_service. 'n1panel' . $index,
                        'service_social' => $social->slug,
                        'status' => 'show',
                        'file' => $file_service,
                        'category' => $item,
                        'domain' => getDomain(),
                    ]);

                    $succes++;
                } else {
                    $action_type = '';
                    switch ($categoryParts[1]) {
                        case 'Views':
                        case 'Likes':
                        case 'Followers':
                            $file_view = storage_path('/app/public/code/default.lbd');
                            $action_type = 'default';
                            break;
                        case 'reaction':
                            $file_view = storage_path('/app/public/code/reaction.lbd');
                            $action_type = 'reaction';
                            break;
                        case 'reaction-speed':
                            $file_view = storage_path('/app/public/code/reaction-speed.lbd');
                            $action_type = 'reaction-speed';
                            break;
                        case 'Comments':
                            $file_view = storage_path('/app/public/code/comment2.lbd');
                            $action_type = 'comment';
                            break;
                        case 'comment-quantity':
                            $file_view = storage_path('/app/public/code/comment.lbd');
                            $action_type = 'comment-quantity';
                            break;
                        case 'minutes':
                            $file_view = storage_path('/app/public/code/minutes.lbd');
                            $action_type = 'minutes';
                            break;
                        case 'time':
                            $file_view = storage_path('/app/public/code/time.lbd');
                            $action_type = 'time';
                            break;
                        default:
                            $file_view = storage_path('/app/public/code/default.lbd');
                            $action_type = 'default';
                            break;
                    }
                    $social_folder = $social->folder;
                    $file_view = File::get($file_view);
                    $file_service = $slug_service . Str::random(4);
                    if (!File::exists(resource_path('views/service/' . $social_folder))) {
                        File::makeDirectory(resource_path('views/service/' . $social_folder), 0777, true, true);
                    }

                    // dd(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'));
                    if (!File::exists(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'))) {
                        File::put(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'), $file_view);
                    }
                    Service::create([
                        'name' =>  $item,
                        'slug' => $slug_service,
                        'service_social' => $social->slug,
                        'status' => 'show',
                        'file' => $file_service,
                        'category' => $item,
                        'domain' => getDomain(),
                    ]);

                    $succes++;
                    // return redirect()->back()->with('success', 'Thêm thành công');
                }
            } else {
                return redirect()->back()->with('error', 'Dịch vụ MXH không tồn tại')->withInput();
            }
        }
        dd($succes);
    }
    
    public function getAllServiceByCategory(Request $request)
    {
        if($request->source == "n1panel"){
            $key = '9fbf16383d4d83a365fa65ea2468e934';
            $action = 'services';
    
            $response = Http::get('	https://n1panel.com/api/v2', [
                'key' => $key,
                'action' => $action,
            ]);
	  
            $categories = [];
            $success = 0;
           
            $service = Service::where('id', $request->id)->where('domain', getDomain())->first();
            if ($response->successful()) {
                $data = $response->json();
                foreach ($data as $item) {
                    if ($service->category == $item['category']) {
                        // Push the item to the $categories array
                        $categories[] = $item;
                        $success++;
                    }
                }
            } else {
                $status = $response->status();
                $errorMessage = $response->body();
            }
    
            // Return the $categories array
            return response()->json([
                'categories' => $categories,
                'success' => $success,
            ]);
        }
        else if($request->source == "secsers"){
           

            $response = Http::asMultipart()->get('https://secsers.com/api/v2', [
                'key' => 'be9e52d36015d1ee38726aa099e00304',
                'action' => 'services'
            ]);

            $categories = [];
            $success = 0;
	   
            $service = Service::where('id', $request->id)->where('domain', getDomain())->first();
            if ($response->successful()) {
                $data = $response->json();
    
                foreach ($data as $item) {
                    if ($service->category == $item['category']) {
                        // Push the item to the $categories array
                        $categories[] = $item;
                        $success++;
                    }
                }
            } else {
                $status = $response->status();
                $errorMessage = $response->body();
            }
    
            // Return the $categories array
            return response()->json([
                'categories' => $categories,
                'success' => $success,
            ]);
        }
        else if($request->source == "justanotherpanel"){
            // $key = 'f70b0c4dced54a6d0550fdf3774f6a8c';
            // $action = 'services';
    
            $response = Http::asMultipart()->get('https://justanotherpanel.com/api/v2', [
                'key' => 'fc2ed1a048fcff4ba306bac059c0c01f',
                'action' => 'services'
            ]);

            $categories = [];
            $success = 0;
            $service = Service::where('id', $request->id)->where('domain', getDomain())->first();

            if ($response->successful()) {
                $data = $response->json();
    
                foreach ($data as $item) {
                    if ($service->category == $item['category']) {
                        // Push the item to the $categories array
                        $categories[] = $item;
                        $success++;
                    }
                }
            } else {
                $status = $response->status();
                $errorMessage = $response->body();
            }
    
            return response()->json([
                'categories' => $categories,
                'success' => $success,
            ]);
        }
    }

    public function CreateOrder($link, $quantity, $service)
    {
        $key = '9fbf16383d4d83a365fa65ea2468e934';
        $action = 'add';
        $response = Http::post('https://n1panel.com/api/v2', [
            'key' => $key,
            'action' => $action,
            'link' => $link,
            'quantity' => $quantity,
            'service' =>  $service
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // Return a JSON response with the data
            if ($data['order']) {
                return $data = [
                    'status' => true,
                    'message' => 'Thanh cong',
                    'data' => $data
                ];
            }
            else {
                return $data = [
                    'status' => false,
                    'message' => 'That bai roi'
                ];
            }   
        } else {
            $status = $response->status();
            $errorMessage = $response->body();

            // Handle the error and return an error response
            return false ;
        }
    }
    public function status($order)
    {
        $response = Http::asMultipart()->post('https://n1panel.com/api/v2', [
            'key' => '9fbf16383d4d83a365fa65ea2468e934',
            'action' => 'status',
            'order' => $order,
        ]);
        return $response->json();
    }
}
