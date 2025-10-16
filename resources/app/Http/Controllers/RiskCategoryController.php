<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskCategory;
use Illuminate\Support\Facades\Log;
use App\Services\RiskAssessmentService;

class RiskCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RiskCategory::with(['risks', 'predefinedRisks']);
        
        // Handle search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $categories = $query->paginate(15)->withQueryString();
        
        // Calculate real risk points using the Risk Assessment Matrix
        $riskService = new RiskAssessmentService();
        $riskStatistics = $riskService->getCategoriesRiskStatistics();
        
        return view('risk-categories.index', compact('categories', 'riskStatistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('risk-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:risk_categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);
        
        // Set default values
        $validated['is_active'] = $request->has('is_active');
        $validated['color'] = $validated['color'] ?? '#00072D';

        try {
            $category = RiskCategory::create($validated);
            
            Log::info('Risk category created', ['category_id' => $category->id, 'user_id' => auth()->id()]);
            
            return redirect()->route('risk-categories.index')
                ->with('success', 'Risk category created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create risk category', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create risk category. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RiskCategory $riskCategory)
    {
        $category = $riskCategory;
        
        // Calculate real risk points using the Risk Assessment Matrix
        $riskService = new RiskAssessmentService();
        $riskStats = $riskService->calculateCategoryRiskPoints($category);
        
        $risks = $category->risks()->paginate(15);
        
        return view('risk-categories.show', compact('category', 'risks', 'riskStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RiskCategory $riskCategory)
    {
        $category = $riskCategory;
        
        // Calculate real risk points using the Risk Assessment Matrix
        $riskService = new RiskAssessmentService();
        $riskStats = $riskService->calculateCategoryRiskPoints($category);
        
        return view('risk-categories.edit', compact('category', 'riskStats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RiskCategory $riskCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:risk_categories,name,' . $riskCategory->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);
        
        // Set default values
        $validated['is_active'] = $request->has('is_active');
        $validated['color'] = $validated['color'] ?? '#00072D';

        try {
            $riskCategory->update($validated);
            
            Log::info('Risk category updated', ['category_id' => $riskCategory->id, 'user_id' => auth()->id()]);
            
            return redirect()->route('risk-categories.index')
                ->with('success', 'Risk category updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update risk category', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update risk category. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RiskCategory $riskCategory)
    {
        try {
            if ($riskCategory->risks()->count() > 0) {
                return back()->with('error', 'Cannot delete category with associated risks.');
            }
            
            $riskCategory->delete();
            
            Log::info('Risk category deleted', ['category_id' => $riskCategory->id, 'user_id' => auth()->id()]);
            
            return redirect()->route('risk-categories.index')
                ->with('success', 'Risk category deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete risk category', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete risk category. Please try again.');
        }
    }

    /**
     * Handle bulk actions for risk categories
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'selected_categories' => 'required|array|min:1',
            'selected_categories.*' => 'exists:risk_categories,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        try {
            $categories = RiskCategory::whereIn('id', $request->selected_categories);
            
            switch ($request->action) {
                case 'activate':
                    $categories->update(['is_active' => true]);
                    $message = 'Selected categories activated successfully';
                    break;
                    
                case 'deactivate':
                    $categories->update(['is_active' => false]);
                    $message = 'Selected categories deactivated successfully';
                    break;
                    
                case 'delete':
                    // Check if any categories have associated risks
                    $categoriesWithRisks = $categories->get()->filter(function($category) {
                        return $category->risks()->count() > 0;
                    })->count();
                    if ($categoriesWithRisks > 0) {
                        return back()->with('error', 'Cannot delete categories with associated risks. Please remove risks first.');
                    }
                    
                    $categories->delete();
                    $message = 'Selected categories deleted successfully';
                    break;
            }
            
            Log::info('Bulk action performed', [
                'action' => $request->action,
                'categories_count' => count($request->selected_categories),
                'user_id' => auth()->id()
            ]);
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Bulk action failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Bulk action failed. Please try again.');
        }
    }

    /**
     * Export risk category data
     */
    public function export(RiskCategory $riskCategory)
    {
        try {
            $data = [
                'category' => $riskCategory->toArray(),
                'risks_count' => $riskCategory->risks()->count(),
                'predefined_risks_count' => $riskCategory->predefinedRisks()->count(),
                'exported_at' => now()->toISOString(),
                'exported_by' => auth()->user()->name,
            ];

            $filename = 'risk_category_' . $riskCategory->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
            
            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
                
        } catch (\Exception $e) {
            Log::error('Failed to export risk category', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export risk category data. Please try again.');
        }
    }

    /**
     * Export all risk categories as CSV
     */
    public function exportCsv()
    {
        try {
            $categories = RiskCategory::withCount(['risks', 'predefinedRisks'])->get();
            
            $filename = 'risk_categories_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($categories) {
                $file = fopen('php://output', 'w');
                
                // CSV headers
                fputcsv($file, [
                    'ID', 'Name', 'Description', 'Color', 'Is Active', 
                    'Risks Count', 'Predefined Risks Count', 'Created At', 'Updated At'
                ]);
                
                // CSV data
                foreach ($categories as $category) {
                    fputcsv($file, [
                        $category->id,
                        $category->name,
                        $category->description,
                        $category->color,
                        $category->is_active ? 'Yes' : 'No',
                        $category->risks_count,
                        $category->predefined_risks_count,
                        $category->created_at->format('Y-m-d H:i:s'),
                        $category->updated_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('CSV export failed for risk categories', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to export risk categories as CSV. Please try again.');
        }
    }
}
