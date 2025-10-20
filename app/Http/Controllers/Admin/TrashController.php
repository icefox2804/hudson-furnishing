<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SoftDeleteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display the trash dashboard
     */
    public function index(): View
    {
        $trashedItems = SoftDeleteService::getAllTrashedItems();
        $statistics = SoftDeleteService::getTrashStatistics();
        
        return view('admin.trash.index', compact('trashedItems', 'statistics'));
    }

    /**
     * Show trashed items for a specific model
     */
    public function show(string $model): View
    {
        $models = SoftDeleteService::getSoftDeleteModels();
        
        if (!isset($models[$model])) {
            abort(404, 'Model not found');
        }
        
        $modelClass = $models[$model];
        $items = $modelClass::onlyTrashed()->paginate(15);
        
        return view('admin.trash.show', compact('items', 'model', 'modelClass'));
    }

    /**
     * Restore a specific item
     */
    public function restore(Request $request): RedirectResponse
    {
        $request->validate([
            'model' => 'required|string',
            'id'    => 'required|integer'
        ],[
            'model.required' => 'Vui lòng chọn Model',
            'id.required'    => 'Vui lòng chọn Dữ liệu',
            'id.integer'     => 'Dữ liệu khóng hợp lệ',
        ]);
        
        $models = SoftDeleteService::getSoftDeleteModels();
        $modelClass = $models[$request->model] ?? null;
        
        if (!$modelClass) {
            return back()->with('error', 'Invalid model');
        }
        
        if (SoftDeleteService::restoreItem($modelClass, $request->id)) {
            return back()->with('success', 'Đã khôi phục thành công');
        }
        
        return back()->with('error', 'Failed to restore item');
    }

    /**
     * Permanently delete an item
     */
    public function forceDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'model' => 'required|string',
            'id'    => 'required|integer'
        ],[
            'model.required' => 'Vui lòng chọn Model',
            'id.required'    => 'Vui lòng chọn Dữ liệu',
            'id.integer'     => 'Dữ liệu khóng hợp lệ',
        ]);
        
        $models = SoftDeleteService::getSoftDeleteModels();
        $modelClass = $models[$request->model] ?? null;
        
        if (!$modelClass) {
            return back()->with('error', 'Invalid model');
        }
        
        if (SoftDeleteService::forceDeleteItem($modelClass, $request->id)) {
            return back()->with('success', 'Đã xóa thành công');
        }
        
        return back()->with('error', 'Không thể xóa!');
    }

    /**
     * Bulk restore items
     */
    public function bulkRestore(Request $request): RedirectResponse
    {
        $request->validate([
            'model' => 'required|string',
            'ids'   => 'required|array',
            'ids.*' => 'integer'
        ],[
            'model.required' => 'Vui lòng chọn Model',
            'ids.required'   => 'Vui lòng chọn Dữ liệu',
            'ids.*.integer'  => 'Dữ liệu khóng hợp lệ',
        ]);
        
        $models = SoftDeleteService::getSoftDeleteModels();
        $modelClass = $models[$request->model] ?? null;
        
        if (!$modelClass) {
            return back()->with('error', 'Invalid model');
        }
        
        $count = SoftDeleteService::bulkRestore($modelClass, $request->ids);
        
        return back()->with('success', "{$count} Đã khôi phục!");
    }

    /**
     * Bulk force delete items
     */
    public function bulkForceDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'model' => 'required|string',
            'ids'   => 'required|array',
            'ids.*' => 'integer'
        ],[
            'model.required' => 'Vui lòng chọn Model',
            'ids.required'   => 'Vui lòng chọn dữ liệu',
            'ids.*.integer'  => 'Dữ liệu không hợp lệ',
        ]);
        
        $models = SoftDeleteService::getSoftDeleteModels();
        $modelClass = $models[$request->model] ?? null;
        
        if (!$modelClass) {
            return back()->with('error', 'Invalid model');
        }
        
        $count = SoftDeleteService::bulkForceDelete($modelClass, $request->ids);
        
        return back()->with('success', "{$count} Đã xóa!");
    }

    /**
     * Clean up old trash
     */
    public function cleanup(Request $request): RedirectResponse
    {
        $days = $request->input('days', 30);
        $results = SoftDeleteService::cleanupOldTrash($days);
        
        $message = 'Cleanup completed. ';
        foreach ($results as $model => $count) {
            $message .= ucfirst($model) . ": {$count} items. ";
        }
        
        return back()->with('success', $message);
    }
}
