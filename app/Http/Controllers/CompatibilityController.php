<?php
namespace App\Http\Controllers;
use App\Models\Cpu;
use App\Models\Motherboard;
use Illuminate\Http\Request;

class CompatibilityController extends Controller
{
   
    // In CompatibilityController.php
    public function check(Request $request)
    {
        $cpu = Cpu::findOrFail($request->cpu_id);
        $motherboard = Motherboard::findOrFail($request->motherboard_id);

        if ($cpu->socket_type !== $motherboard->socket_type) {
            return back()->with('error', 'CPU and motherboard sockets do not match!');
        }

        return back()->with([
            'success' => 'Components are compatible!',
            'selected_cpu' => $cpu,
            'selected_motherboard' => $motherboard,
        ]);
    }
}