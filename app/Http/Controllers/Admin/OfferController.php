<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::query();

        // Search by title
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by discount type
        if ($request->has('discount_type') && $request->discount_type != '') {
            $query->where('discount_type', $request->discount_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by start date
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        // Filter by end date
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Sort by creation date
        $query->orderBy('created_at', 'desc');

        $offers = $query->paginate(15)->withQueryString();
        
        return view('admin.offers.index', compact('offers'));
    }

    public function show(Offer $offer){
        $products = $offer->products()->with(['section', 'category', 'brand', 'material', 'images'])->paginate(12);

        return view('admin.offers.show', compact('offer', 'products'));
    }

    public function create()
    {

        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'discount_value' => str_replace(',', '', $request->discount_value),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => [
                'required',
                'numeric',
                Rule::when($request->discount_type === 'percentage', ['min:1', 'max:100']),
                Rule::when($request->discount_type === 'fixed', ['min:1000']),
            ],
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',           
            'status' => 'required|in:active,inactive',
        ],[
            'title.required' => 'Tiêu đề ưu đãi không được để trống.',
            'description.required' => 'Mô tả ưu đãi không được để trống.',
            'discount_type.required' => 'Loại giảm giá không được để trống.',
            'discount_value.required' => 'Giá trị giảm giá không được để trống.',
            'discount_value.numeric' => 'Giá trị giảm giá phải là một số.',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_value.max' => 'Giá trị giảm giá không được vuien quá 100.',
            'start_date.required' => 'Ngày bắt đầu không được để trống.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải là ngày hôm nay hoặc sau đó.',
            'end_date.required' => 'Ngày kết thúc không được để trống.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image');
            // Nếu file gửi về là mảng do component => lấy file đầu tiên
            if (is_array($imagePath)) {
                $imagePath = $imagePath[0];
            }
            $imagePath = $imagePath->store('uploads/offers', 'public');
        }

        $offer = Offer::create([
            'title' => $request->title,
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,            
            'status' => $request->status,
            'image' => $imagePath,
        ]);


        return redirect()->route('admin.offers.index')->with('success', 'Đã tạo thành công!');
    }


    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $request->merge([
            'discount_value' => str_replace(',', '', $request->discount_value),
        ]);

        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|max:1000',
            'image'          => 'nullable|image|max:2048',
            'discount_type'               => ['required', 'in:percentage,fixed'],
            'discount_value' => [
                'required',
                'numeric',
                Rule::when($request->discount_type === 'percentage', ['min:1', 'max:100']),
                Rule::when($request->discount_type === 'fixed', ['min:1000']),
            ],
            'start_date'     => 'required|date|after_or_equal:today',
            'end_date'       => 'required|date|after:start_date',           
            'status'         => 'required|in:active,inactive',
        ],[
            'title.required'            => 'Tiêu đề ưu đãi không được để trống.',
            'description.required'      => 'Mô tả ưu đãi không được để trống.',
            'image.image'               => 'Hình ảnh không hợp lệ.',
            'image.max'                 => 'Hình ảnh không được lớn hơn 2MB.',
            'discount_type.required'    => 'Loại giảm giá không được để trống.',
            'discount_value.required'   => 'Giá trị giảm giá không được để trống.',
            'discount_value.numeric'    => 'Giá trị giảm giá phải là một số.',
            'discount_value.min'        => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_value.max'        => 'Giá trị giảm giá không được vuien quá 100.',
            'start_date.required'       => 'Ngày bắt đầu không được để trống.',
            'start_date.date'           => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải là ngày hôm nay hoặc sau đó.',
            'end_date.required'         => 'Ngày kết thúc không được để trống.',
            'end_date.date'             => 'Ngày kết thúc không hợp lệ.',
            'end_date.after'            => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'status.required'           => 'Trạng thái không được để trống.',
            'status.in'                 => 'Trạng thái không hợp lệ.',
        ]);

        // nếu không upload ảnh mới thì giữ nguyên ảnh cũ
        $imagePath = $offer->image;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            if (is_array($imageFile)) {
                $imageFile = $imageFile[0];
            }

            // Xóa ảnh cũ nếu có
            if ($offer->image && \Storage::disk('public')->exists($offer->image)) {
                \Storage::disk('public')->delete($offer->image);
            }

            $imagePath = $imageFile->store('uploads/offers', 'public');
        }

        $offer->update([
            'title' => $request->title,
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.offers.index')->with('success', 'Đã cập nhật thành công!');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.offers.index')->with('success', 'Đã xóa thành công!');
    }
}
