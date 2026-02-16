use App\Models\Registration;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function scan()
    {
        return view('officer.checkin.scan');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'qr_data' => 'required',
        ]);

        // Simple validation for now: qr_data stores the MD5 or unique ID
        $registration = Registration::with(['user', 'bus'])
            ->where('id', $request->qr_data) // In production: where('qr_code', MD5/Hash)
            ->first();

        if (!$registration) {
            return response()->json(['success' => false, 'message' => 'Tiket tidak valid.'], 404);
        }

        if ($registration->status !== 'accepted') {
            return response()->json(['success' => false, 'message' => 'Pendaftaran belum diverifikasi admin.'], 400);
        }

        if ($registration->checked_in_at) {
            return response()->json(['success' => false, 'message' => 'Penumpang sudah melakukan check-in sebelumnya.'], 400);
        }

        $registration->update(['checked_in_at' => now()]);

        return response()->json(['success' => true, 'registration' => $registration]);
    }
}
