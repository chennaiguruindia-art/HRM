<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\DailyPlan;
use App\Models\Lead;
use App\Models\NonLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    private function branchScope(): ?int
    {
        $user = Auth::user();

        return $user && $user->branch_id ? (int) $user->branch_id : null;
    }

    private function denyBranchAdmin(): void
    {
        if ($this->branchScope()) {
            abort(403, 'Branch admins are not allowed to perform this action.');
        }
    }

    public function runMigrations()
    {
        Artisan::call('migrate', ['--force' => true]);

        return '<pre>' . Artisan::output() . '</pre>';
    }

    public function runMigrationsFresh()
    {
        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);

        return '<pre>' . Artisan::output() . '</pre>';
    }

    public function runSeeders()
    {
        Artisan::call('db:seed', ['--force' => true]);

        return '<pre>' . Artisan::output() . '</pre>';
    }

    public function dashboardStats(): JsonResponse
    {
        $branchId = $this->branchScope();

        $plans = DailyPlan::query()->when($branchId, fn ($q) => $q->where('branch_id', $branchId));
        $leads = Lead::query()->when($branchId, fn ($q) => $q->where('branch_id', $branchId));
        $nonLeads = NonLead::query()->when($branchId, fn ($q) => $q->where('branch_id', $branchId));

        return response()->json([
            'total' => (clone $plans)->count(),
            'today' => (clone $plans)->whereDate('date', now()->toDateString())->count(),
            'leads' => (clone $leads)->count(),
            'nonLeads' => (clone $nonLeads)->count(),
            'leadsToday' => (clone $leads)->whereDate('created_at', now()->toDateString())->count(),
            'rejectedToday' => (clone $nonLeads)->whereDate('created_at', now()->toDateString())->count(),
        ]);
    }

    public function branches(): JsonResponse
    {
        $branchId = $this->branchScope();
        $branches = Branch::query()
            ->when($branchId, fn ($q) => $q->where('id', $branchId))
            ->get()->map(function ($b) {
                return [
                    'id' => 'BR-' . str_pad($b->id, 2, '0', STR_PAD_LEFT),
                    'name' => $b->name,
                    'location' => $b->location ?? '',
                    'manager' => $b->manager ?? '',
                    'phone' => $b->phone ?? '',
                ];
            });

        return response()->json($branches);
    }

    public function storeBranch(Request $request): JsonResponse
    {
        $this->denyBranchAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $branch = Branch::create($data);

        return response()->json(['success' => true, 'id' => 'BR-' . str_pad($branch->id, 2, '0', STR_PAD_LEFT)]);
    }

    public function deleteBranch(Request $request): JsonResponse
    {
        $this->denyBranchAdmin();

        $id = (int) str_replace('BR-', '', $request->id);
        Branch::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function dailyPlans(): JsonResponse
    {
        $branchId = $this->branchScope();

        $plans = DailyPlan::with('branch')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest('date')
            ->latest('id')
            ->get()
            ->map(function ($p, $i) {
                return [
                    'sino' => $i + 1,
                    'id' => $p->id,
                    'branch' => $p->branch?->name ?? '-',
                    'date' => $p->date->toDateString(),
                    'salesperson' => $p->salesperson,
                    'company_address' => $p->company_address,
                    'company_details' => $p->company_details,
                    'purpose_of_visit' => $p->purpose_of_visit,
                    'type_of_service' => $p->type_of_service,
                    'inspection' => $p->inspection,
                    'quotation' => $p->quotation,
                    'followup1' => $p->followup1,
                    'followup2' => $p->followup2,
                    'followup3' => $p->followup3,
                    'remarks' => $p->remarks,
                    'updated_at' => $p->updated_at->format('Y-m-d H:i'),
                ];
            });

        return response()->json($plans);
    }

    public function storeDailyPlan(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $data = $request->validate([
            'date' => 'required|date',
            'salesperson' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_details' => 'nullable|string',
            'purpose_of_visit' => 'nullable|string',
            'type_of_service' => 'nullable|string',
            'inspection' => 'nullable|string',
            'quotation' => 'nullable|string',
            'followup1' => 'nullable|string',
            'followup2' => 'nullable|string',
            'followup3' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $data['branch_id'] = $branchId;

        DailyPlan::create($data);

        return response()->json(['success' => true]);
    }

    public function updateDailyPlan(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $plan = DailyPlan::findOrFail($request->id);

        if ($branchId && (int) $plan->branch_id !== $branchId) {
            abort(403, 'Access denied.');
        }

        $data = $request->validate([
            'date' => 'required|date',
            'salesperson' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_details' => 'nullable|string',
            'purpose_of_visit' => 'nullable|string',
            'type_of_service' => 'nullable|string',
            'inspection' => 'nullable|string',
            'quotation' => 'nullable|string',
            'followup1' => 'nullable|string',
            'followup2' => 'nullable|string',
            'followup3' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $plan->update($data);

        return response()->json(['success' => true]);
    }

    public function deleteDailyPlan(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $plan = DailyPlan::findOrFail($request->id);

        if ($branchId && (int) $plan->branch_id !== $branchId) {
            abort(403, 'Access denied.');
        }

        $plan->delete();

        return response()->json(['success' => true]);
    }

    private function planPayload($p, int $sino): array
    {
        return [
            'sino' => $sino,
            'id' => $p->id,
            'branch' => $p->branch?->name ?? '-',
            'date' => $p->date->toDateString(),
            'salesperson' => $p->salesperson,
            'company_address' => $p->company_address,
            'company_details' => $p->company_details,
            'purpose_of_visit' => $p->purpose_of_visit,
            'type_of_service' => $p->type_of_service,
            'inspection' => $p->inspection,
            'quotation' => $p->quotation,
            'followup1' => $p->followup1,
            'followup2' => $p->followup2,
            'followup3' => $p->followup3,
            'remarks' => $p->remarks,
            'updated_at' => $p->updated_at->format('Y-m-d H:i'),
        ];
    }

    public function leads(): JsonResponse
    {
        $branchId = $this->branchScope();

        $rows = Lead::with('branch')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest('date')
            ->latest('id')
            ->get()
            ->map(fn($p, $i) => $this->planPayload($p, $i + 1));

        return response()->json($rows);
    }

    public function nonLeads(): JsonResponse
    {
        $branchId = $this->branchScope();

        $rows = NonLead::with('branch')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest('date')
            ->latest('id')
            ->get()
            ->map(fn($p, $i) => $this->planPayload($p, $i + 1));

        return response()->json($rows);
    }

    private function moveDailyPlan(Request $request, string $target): JsonResponse
    {
        $branchId = $this->branchScope();

        $plan = DailyPlan::findOrFail($request->id);

        if ($branchId && (int) $plan->branch_id !== $branchId) {
            abort(403, 'Access denied.');
        }

        $data = Arr::except($plan->toArray(), ['created_at', 'updated_at']);
        $data['daily_plan_id'] = $plan->id;

        if ($target === 'lead') {
            Lead::create($data);
        } else {
            NonLead::create($data);
        }

        $plan->delete();

        return response()->json(['success' => true]);
    }

    public function convertDailyPlan(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|integer|exists:daily_plans,id']);

        return $this->moveDailyPlan($request, 'lead');
    }

    public function rejectDailyPlan(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|integer|exists:daily_plans,id']);

        return $this->moveDailyPlan($request, 'non_lead');
    }


}
