<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\HomeProductSection;


class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {

        $products = Product::all();

        $settings = SiteSetting::pluck('value', 'key')->toArray();

        $homeSections = HomeProductSection::with('products')->get()->keyBy('type');

        $sections = [
            'featured' => 'section 1',
            'new' => 'section 2',
            'top_rated' => 'section 3',
            'custom' => 'section 4',
        ];

        return view('admin.settings.index', compact('products','settings', 'homeSections', 'sections'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {

        // Validate only some fields and allow others to be saved dynamically
        $validated = $request->validate([
            'site_name'        => 'required|string|max:255',
            'site_tagline'     => 'nullable|string|max:255',
            'contact_email'    => 'required|email|max:255',
            'contact_phone'    => 'nullable|string|max:50',
            'contact_address'  => 'nullable|string|max:1000',
            'business_hours'   => 'nullable|string|max:1000',
            'facebook_url'     => 'nullable|url|max:255',
            'instagram_url'    => 'nullable|url|max:255',
            'twitter_url'      => 'nullable|url|max:255',
            'linkedin_url'     => 'nullable|url|max:255',
            'items_per_page'   => 'nullable|integer|min:1|max:100',
            'maintenance_mode' => 'nullable|in:0,1',
            'logo'             => 'nullable|image|max:2048',
            'hero_image_1'     => 'nullable|image|max:2048',
            'hero_image_2'     => 'nullable|image|max:2048',
            'hero_image_3'     => 'nullable|image|max:2048',
            'google_map'       => 'nullable|string|max:1000',

        ]);

        // Fields bảo trì
        $maintenanceFields = [
            'maintenance_mode', 
            'maintenance_title', 
            'maintenance_text', 
            'maintenance_end', 
            'maintenance_1', 
            'maintenance_2', 
            'maintenance_3'
        ];

        // bật tắt bảo trì
        foreach ($maintenanceFields as $key) {
            if ($key === 'maintenance_mode') {
                $value = $request->has($key) ? '1' : '0';
                SiteSetting::updateOrCreate(['key'=>$key], ['value'=>$value]);
            } elseif(in_array($key, ['maintenance_1','maintenance_2','maintenance_3']) && $request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('uploads/maintenance', $filename, 'public');

                // Xóa file cu
                $old = SiteSetting::where('key',$key)->first();
                if($old) Storage::disk('public')->delete($old->value);

                SiteSetting::updateOrCreate(['key'=>$key], ['value'=>$path]);
            } else {
                // lưu giá trị cơ bản
                $value = $request->input($key,'');
                SiteSetting::updateOrCreate(['key'=>$key], ['value'=>$value]);
            }
        }

        // Logo
        // Upload and save the logo
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $file = $request->file('logo');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('uploads', $filename, 'public');

            // xóa file cu
            $oldLogo = SiteSetting::where('key', 'logo')->first();
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo->value);
            }

            // Save the logo filename as a setting
            SiteSetting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $path]
            );
        }

        // --- Xử lý Google Map riêng --- //
        if ($request->filled('google_map')) {
            $googleMap = $request->input('google_map');

            // Nếu admin dán cả iframe, tách lấy src
            if (preg_match('/src="([^"]+)"/', $googleMap, $matches)) {
                $googleMap = $matches[1];
            }

            // Cập nhật hoặc tạo mới Google Map
            SiteSetting::updateOrCreate(
                ['key' => 'google_map'],
                ['value' => trim($googleMap)]
            );
        } else {
            // Nếu trống, xóa Google Map
            SiteSetting::where('key', 'google_map')->delete();
        }

        // Handle hero carousel images
        foreach (['hero_image_1', 'hero_image_2', 'hero_image_3'] as $key) {
            if ($request->hasFile($key) && $request->file($key)->isValid()) {
                $file = $request->file($key);
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('uploads/hero', $filename, 'public');

                // Xóa ảnh cũ nếu có
                $oldImage = SiteSetting::where('key', $key)->first();
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage->value);
                }

                SiteSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $path]
                );
            }
        }

        // --- Xử lý home_section --- //
        if ($request->has('home_section')) {
            foreach ($request->home_section as $key => $section) {
                $homeSection = HomeProductSection::updateOrCreate(
                    ['type' => $key],
                    [
                        'title' => $section['title'] ?? ucfirst($key),
                        'limit' => $section['limit'] ?? 4,
                        'is_active' => isset($section['is_active']) ? 1 : 0,
                        'order' => array_search($key, array_keys($request->home_section)),
                    ]
                );

                // Cập nhật sản phẩm
                if (isset($section['products']) && is_array($section['products'])) {
                    $homeSection->products()->sync($section['products']);
                } else {
                    $homeSection->products()->detach();
                }
            }
        }

         // lưu các setting khác (loại trừ token/method/logo)
        $except = ['_token',
            '_method',
            'logo',
            'google_map',
            'hero_image_1',
            'hero_image_2',
            'hero_image_3'
        ];
        $data = $request->except($except);
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SiteSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }


        // Xóa cache settings nếu có
        if (class_exists(\Illuminate\Support\Facades\Cache::class)) {
        \Illuminate\Support\Facades\Cache::forget('site_settings');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cập nhật cài đặt thành công.');
    }
}
