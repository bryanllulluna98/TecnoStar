<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\ProviderAccount;
use App\Models\Service;
use App\Models\Subscription;
use App\Services\WhatsAppMessageService;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(WhatsAppMessageService $waService): View
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth()->toDateString();
        $endOfMonth = $today->copy()->endOfMonth()->toDateString();

        // 1. Métricas financieras en tiempo real
        $monthlyIncome = (float) Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyExpense = (float) ProviderAccount::where('status', '!=', 'cancelled')
            ->whereBetween('billing_due_date', [$startOfMonth, $endOfMonth])
            ->sum('cost');
        $monthlyNetProfit = $monthlyIncome - $monthlyExpense;
        $profitMargin = $monthlyIncome > 0 ? round(($monthlyNetProfit / $monthlyIncome) * 100, 1) : 0;

        // 2. Inventario y Clientes
        $totalClients = Client::count();
        $activeAccounts = ProviderAccount::where('status', 'active')->count();
        $freeProfiles = Profile::where('status', 'free')->count();
        $occupiedProfiles = Profile::where('status', 'occupied')->count();

        // 3. Suscripciones que vencen hoy o en los próximos 3 días
        $expiringSubscriptions = Subscription::with(['client', 'profile.providerAccount.service'])
            ->whereIn('status', ['active', 'expiring_soon'])
            ->whereBetween('expires_at', [$today->copy()->subDays(5)->toDateString(), $today->copy()->addDays(3)->toDateString()])
            ->orderBy('expires_at', 'asc')
            ->take(8)
            ->get();

        // Generar URLs de WhatsApp para cada suscripción
        foreach ($expiringSubscriptions as $sub) {
            $sub->wa_charge_url = $waService->generateUrl('billing_reminder', $sub);
            $sub->wa_update_url = $waService->generateUrl('password_update', $sub);
            $sub->wa_profile_url = $waService->generateUrl('credential_delivery', $sub);
        }

        // 4. Cuentas madre próximas a vencer con el proveedor (próximos 7 días)
        $expiringProviderAccounts = ProviderAccount::with('service')
            ->where('status', 'active')
            ->whereBetween('billing_due_date', [$today->toDateString(), $today->copy()->addDays(7)->toDateString()])
            ->orderBy('billing_due_date', 'asc')
            ->take(5)
            ->get();

        // 5. Stock de perfiles por servicio
        $servicesStock = Service::where('is_active', true)
            ->withCount([
                'profiles as total_profiles',
                'profiles as free_profiles' => function ($query) {
                    $query->where('status', 'free');
                },
                'profiles as occupied_profiles' => function ($query) {
                    $query->where('status', 'occupied');
                },
            ])
            ->get();

        return view('dashboard', compact(
            'monthlyIncome',
            'monthlyExpense',
            'monthlyNetProfit',
            'profitMargin',
            'totalClients',
            'activeAccounts',
            'freeProfiles',
            'occupiedProfiles',
            'expiringSubscriptions',
            'expiringProviderAccounts',
            'servicesStock'
        ));
    }
}
