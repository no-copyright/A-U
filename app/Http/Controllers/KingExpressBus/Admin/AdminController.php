<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdminController extends Controller
{
    public function index()
    {
        $homePageData = DB::table('home_page')->first();

        if ($homePageData) {
            $homePageData->banners = json_decode($homePageData->banners, false) ?? (object)['title' => '', 'description' => '', 'images' => []];
            $homePageData->stats = json_decode($homePageData->stats, true) ?? [];
            $homePageData->fags = json_decode($homePageData->fags, true) ?? [];
            $homePageData->images = json_decode($homePageData->images, true) ?? [];
            $homePageData->link_youtubes = json_decode($homePageData->link_youtubes, true) ?? [];
        } else {
            $homePageData = (object) [
                'id' => null,
                'banners' => (object)['title' => '', 'description' => '', 'images' => []],
                'stats' => [],
                'fags' => [],
                'images' => [],
                'link_youtubes' => [],
            ];
        }

        return view('kingexpressbus.admin.modules.dashboard.edit', ['homePage' => $homePageData]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banners.title' => 'nullable|string|max:255',
            'banners.description' => 'nullable|string',
            'banners.images' => 'nullable|array',
            'banners.images.*' => 'string|max:255',

            'stats' => 'nullable|array',
            'stats.*.value' => 'required|integer',
            'stats.*.description' => 'required|string|max:255',
            'stats.*.images' => 'required|string|max:255',

            'fags' => 'nullable|array',
            'fags.*.question' => 'required|string|max:255',
            'fags.*.answer' => 'required|string',

            'images' => 'nullable|array',
            'images.*' => 'string|max:255',

            'link_youtubes' => 'nullable|array',
            'link_youtubes.*' => 'string|max:255',
        ]);

        $validator->setAttributeNames([
            'stats.*.value' => 'giá trị thống kê',
            'stats.*.description' => 'mô tả thống kê',
            'stats.*.images' => 'ảnh thống kê',
            'fags.*.question' => 'câu hỏi',
            'fags.*.answer' => 'câu trả lời',
            'link_youtubes.*' => 'link youtube',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $dataToUpdate = [
                'banners'       => json_encode($request->input('banners', ['title' => '', 'description' => '', 'images' => []])),
                'stats'         => json_encode($request->input('stats', [])),
                'fags'          => json_encode($request->input('fags', [])),
                'images'        => json_encode($request->input('images', [])),
                'link_youtubes' => json_encode($request->input('link_youtubes', [])),
                'updated_at'    => now()
            ];

            DB::table('home_page')->updateOrInsert(
                ['id' => 1],
                $dataToUpdate
            );
        } catch (Throwable $e) {
            Log::error("Error updating homepage: " . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật trang chủ.')->withInput();
        }

        return redirect()->route('admin.dashboard.index')->with('success', 'Cập nhật thông tin trang chủ thành công!');
    }




    public function getHomePageApi(): JsonResponse
    {
        $homePage = DB::table('home_page')->first();

        if (!$homePage) {
            return response()->json([
                'success' => false,
                'message' => 'Homepage data not found.'
            ], 404);
        }

        $banners = json_decode($homePage->banners, false) ?? (object)['title' => '', 'description' => '', 'images' => []];
        $stats = json_decode($homePage->stats, true) ?? [];
        $images = json_decode($homePage->images, true) ?? [];


        if (!empty($banners->images) && is_array($banners->images)) {
            $banners->images = array_map(function ($path) {
                return url($path);
            }, $banners->images);
        }

        if (!empty($stats) && is_array($stats)) {
            $stats = array_map(function ($stat) {
                if (!empty($stat['images'])) {
                    $stat['images'] = url($stat['images']);
                }
                return $stat;
            }, $stats);
        }

        if (!empty($images) && is_array($images)) {
            $images = array_map(function ($path) {
                return url($path);
            }, $images);
        }

        $data = [
            'banners' => $banners,
            'stats' => $stats,
            'fags' => json_decode($homePage->fags, true) ?? [],
            'images' => $images,
            'link_youtubes' => json_decode($homePage->link_youtubes, true) ?? [],
        ];


        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
