<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function __construct(protected ShippingService $shippingService) {}

    /**
     * List all courier shipments.
     */
    public function index(Request $request): View
    {
        $query = Shipment::with(['order.shippingAddress', 'courier', 'events']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                    ->orWhere('external_shipment_id', 'like', "%{$search}%")
                    ->orWhere('consignee_name', 'like', "%{$search}%")
                    ->orWhere('consignee_phone', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($oq) use ($search) {
                        $oq->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('shipment_status', $status);
        }

        if ($courierId = $request->get('courier_id')) {
            $query->where('courier_id', $courierId);
        }

        $shipments = $query->latest()->paginate(15)->withQueryString();
        $couriers = Courier::active()->get();

        $statusCounts = [
            'all' => Shipment::count(),
            'booked' => Shipment::where('shipment_status', 'booked')->count(),
            'picked_up' => Shipment::where('shipment_status', 'picked_up')->count(),
            'in_transit' => Shipment::where('shipment_status', 'in_transit')->count(),
            'out_for_delivery' => Shipment::where('shipment_status', 'out_for_delivery')->count(),
            'delivered' => Shipment::where('shipment_status', 'delivered')->count(),
            'failed' => Shipment::where('shipment_status', 'failed')->count(),
            'returned' => Shipment::where('shipment_status', 'returned')->count(),
        ];

        return view('admin.shipments.index', compact('shipments', 'couriers', 'statusCounts'));
    }

    /**
     * Create / Book a shipment for an order.
     */
    public function store(Request $request, int $orderId): RedirectResponse
    {
        $order = Order::findOrFail($orderId);

        $validated = $request->validate([
            'courier_id' => ['required', 'exists:couriers,id'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'weight' => ['required', 'numeric', 'min:0.1', 'max:100'],
            'pieces' => ['nullable', 'integer', 'min:1', 'max:20'],
            'cod_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'advance_order_status' => ['nullable', 'string', 'in:processing,packed,shipped'],
        ]);

        $shipment = $this->shippingService->createShipment($order, (int) $validated['courier_id'], $validated);

        return back()->with('success', "Shipment booked via {$shipment->courier?->name}! Tracking Number: {$shipment->tracking_number}");
    }

    /**
     * Update shipment status & add milestone log.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $shipment = Shipment::with('courier', 'order')->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', Shipment::STATUSES)],
            'location' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $this->shippingService->updateShipmentStatus(
            $shipment,
            $validated['status'],
            $validated['location'] ?? null,
            $validated['description'] ?? null
        );

        return back()->with('success', "Shipment #{$shipment->tracking_number} updated to ".ucfirst(str_replace('_', ' ', $validated['status'])).'.');
    }

    /**
     * Print standard courier shipping label.
     */
    public function label(int $id): View
    {
        $shipment = Shipment::with(['order.items', 'order.shippingAddress', 'courier'])->findOrFail($id);

        return view('admin.shipments.label', compact('shipment'));
    }
}
