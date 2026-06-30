<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

Route::get('/', function () {
    return view('public.home');
})->name('public.home');

Route::get('/login', function () {
    return redirect()->route('public.home', ['login' => 1])->with('login_required', 'Silakan login terlebih dahulu.');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors(['email' => 'Email atau password tidak sesuai.']);
    }

    $request->session()->regenerate();
    $user = $request->user();

    if (! $user->isActive()) {
        $message = match (true) {
            $user->role === 'staff' && $user->status === 'pending' => 'Akun staf belum di-ACC oleh notaris. Silakan tunggu persetujuan notaris terlebih dahulu.',
            $user->role === 'staff' && in_array($user->status, ['blocked', 'resigned'], true) => 'Akun staf sudah diblokir atau berstatus resign. Hubungi notaris untuk akses kembali.',
            $user->role === 'notary' && $user->status === 'pending' => 'Akun notaris belum aktif.',
            default => 'Akun belum aktif atau sedang diblokir.',
        };

        Auth::logout();
        return back()->withErrors(['email' => $message]);
    }

    $user->forceFill(['last_login_at' => now()])->save();

    $defaultDashboard = match ($user->role) {
        'notary' => route('notaris.dashboard'),
        'admin', 'staff' => route('admin.dashboard'),
        default => route('user.dashboard'),
    };

    $intended = $request->session()->pull('url.intended');

    if ($intended && ($user->role === 'user' || ! str_contains($intended, '/dashboard/user'))) {
        return redirect()->to($intended);
    }

    return redirect()->to($defaultDashboard);
})->name('login.store');

Route::post('/login/check-staff-status', function (Request $request) {
    $validated = $request->validate([
        'email' => ['required', 'email'],
    ]);

    $user = User::query()
        ->where('email', $validated['email'])
        ->where('role', 'staff')
        ->first();

    if (! $user) {
        return response()->json([
            'is_staff' => false,
            'message' => null,
        ]);
    }

    return response()->json([
        'is_staff' => true,
        'status' => $user->status,
        'message' => match ($user->status) {
            'pending' => 'Email ini terdaftar sebagai staf, tetapi belum di-ACC oleh notaris.',
            'active' => 'Email staf sudah aktif. Silakan lanjut login.',
            'blocked', 'resigned' => 'Email staf ini sudah diblokir/resign. Hubungi notaris.',
            default => 'Status akun staf belum dapat digunakan.',
        },
    ]);
})->name('login.check-staff-status');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('public.home');
})->middleware('auth')->name('logout');

Route::post('/register/basic', function (Request $request) {
    $role = $request->input('role', 'user');
    $request->merge([
        'name' => match ($role) {
            'staff' => strtok((string) $request->input('staff_email'), '@') ?: 'Staff Kantor',
            'notary' => 'Notaris',
            default => $request->input('user_name'),
        },
        'email' => match ($role) {
            'staff' => $request->input('staff_email'),
            'notary' => $request->input('notary_email'),
            default => $request->input('user_email'),
        },
        'phone' => match ($role) {
            'notary' => $request->input('notary_phone'),
            default => $request->input('user_phone'),
        },
    ]);

    $validated = $request->validate([
        'name' => [$role === 'staff' ? 'nullable' : 'required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:160', 'unique:users,email'],
        'phone' => [$role === 'staff' ? 'nullable' : 'required', 'string', 'max:40'],
        'password' => ['required', 'confirmed', 'min:8'],
        'role' => ['required', 'in:user,staff,notary'],
    ]);

    if ($validated['role'] === 'notary' && User::where('role', 'notary')->exists()) {
        return back()->withErrors(['role' => 'Akun notaris hanya dapat dibuat satu kali.']);
    }

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'status' => $validated['role'] === 'staff' ? 'pending' : 'active',
    ]);

    return redirect()->route('public.home', ['login' => 1])->with('registered', 'Registrasi berhasil. Silakan login dengan email dan password yang dibuat.');
})->name('register.basic');

Route::get('/dashboard/user', function () {
    $user = request()->user();

    $applications = DB::table('file_applications')
        ->leftJoin('office_services', 'office_services.id', '=', 'file_applications.office_service_id')
        ->where('file_applications.user_id', $user->id)
        ->select('file_applications.*', 'office_services.name as service_name')
        ->latest('file_applications.created_at')
        ->get();

    $applicationIds = $applications->pluck('id');

    $documentRequests = DB::table('document_requests')
        ->join('file_applications', 'file_applications.id', '=', 'document_requests.file_application_id')
        ->where('file_applications.user_id', $user->id)
        ->select('document_requests.*', 'file_applications.file_number')
        ->latest('document_requests.created_at')
        ->get();

    $appointments = DB::table('appointments')
        ->where('user_id', $user->id)
        ->latest('appointment_date')
        ->orderBy('appointment_time')
        ->get();

    $officeServices = DB::table('office_services')
        ->where('is_active', true)
        ->orderBy('category')
        ->orderBy('name')
        ->get(['name', 'category'])
        ->groupBy('category')
        ->map(fn ($items) => $items->pluck('name')->values())
        ->toArray();

    $upcomingAppointment = $appointments
        ->whereIn('status', ['requested', 'approved', 'rescheduled'])
        ->where('appointment_date', '>=', now()->toDateString())
        ->sortBy([['appointment_date', 'asc'], ['appointment_time', 'asc']])
        ->first();

    $stats = [
        'total' => $applications->count(),
        'inProcess' => $applications->where('status', 'in_process')->count(),
        'completed' => $applications->where('status', 'completed')->count(),
        'waitingDocuments' => $documentRequests->where('status', 'requested')->count(),
        'upcomingAppointments' => $appointments
            ->whereIn('status', ['requested', 'approved', 'rescheduled'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->count(),
    ];

    $notifications = collect()
        ->merge($applications->take(3)->map(fn ($application) => [
            'section' => 'applications',
            'color' => match ($application->status) {
                'completed' => 'green',
                'waiting_document' => 'amber',
                'cancelled' => 'red',
                default => 'blue',
            },
            'title' => 'Status berkas diperbarui',
            'description' => ($application->file_number ?? '-') . ' · ' . ($application->service_name ?? 'Layanan belum dipilih'),
            'time' => optional($application->updated_at ? \Carbon\Carbon::parse($application->updated_at) : null)?->diffForHumans() ?? '-',
        ]))
        ->merge($documentRequests->where('status', 'requested')->take(3)->map(fn ($document) => [
            'section' => 'documents',
            'color' => 'amber',
            'title' => 'Dokumen tambahan diminta',
            'description' => ($document->document_name ?? '-') . ' · ' . ($document->file_number ?? '-'),
            'time' => optional($document->created_at ? \Carbon\Carbon::parse($document->created_at) : null)?->diffForHumans() ?? '-',
        ]))
        ->take(5)
        ->values();

    return view('user.dashboard', compact(
        'applications',
        'documentRequests',
        'appointments',
        'upcomingAppointment',
        'officeServices',
        'stats',
        'notifications'
    ));
})->middleware(['auth', 'role:user'])->name('user.dashboard');

Route::get('/dashboard/user/pendaftaran', function (Request $request) {
    return redirect()->route('user.dashboard', array_filter([
        'open' => 'new-application',
        'service' => $request->query('service'),
        'category' => $request->query('category'),
    ]));
})->middleware(['auth', 'role:user'])->name('user.dashboard.registration');

Route::post('/dashboard/user/applications', function (Request $request) {
    $validated = $request->validate([
        'client_service' => ['required', 'string', 'max:160'],
        'service_category' => ['required', 'in:notary,ppat'],
        'agreement' => ['accepted'],
    ]);

    $user = $request->user();

    $service = DB::table('office_services')
        ->where('name', $validated['client_service'])
        ->where('category', $validated['service_category'])
        ->where('is_active', true)
        ->first();

    if (! $service) {
        return back()
            ->withInput()
            ->withErrors(['client_service' => 'Layanan belum tersedia atau tidak aktif di database.']);
    }

    $year = now()->format('Y');
    $nextNumber = DB::table('file_applications')
        ->whereYear('created_at', $year)
        ->lockForUpdate()
        ->count() + 1;

    $fileNumber = 'BRK-' . $year . '-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    while (DB::table('file_applications')->where('file_number', $fileNumber)->exists()) {
        $nextNumber++;
        $fileNumber = 'BRK-' . $year . '-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    $trackingCode = 'TRK-' . $year . '-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    while (DB::table('file_applications')->where('tracking_code', $trackingCode)->exists()) {
        $nextNumber++;
        $trackingCode = 'TRK-' . $year . '-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    $applicationId = DB::table('file_applications')->insertGetId([
        'user_id' => $user->id,
        'office_service_id' => $service->id,
        'file_number' => $fileNumber,
        'tracking_code' => $trackingCode,
        'applicant_name' => $user->name,
        'applicant_phone' => $user->phone,
        'applicant_email' => $user->email,
        'status' => 'submitted',
        'submitted_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('file_progress_steps')->insert([
        [
            'file_application_id' => $applicationId,
            'position' => 1,
            'title' => 'Pengajuan diterima',
            'status' => 'done',
            'admin_note' => 'Pengajuan masuk dari dashboard klien dan menunggu verifikasi admin.',
            'started_at' => now(),
            'finished_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'file_application_id' => $applicationId,
            'position' => 2,
            'title' => 'Verifikasi berkas awal',
            'status' => 'running',
            'admin_note' => 'Admin akan memeriksa kelengkapan data dan dokumen awal.',
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    return redirect()
        ->route('user.dashboard')
        ->with('application_saved', "Pengajuan berhasil disimpan. Nomor berkas {$fileNumber}, kode tracking {$trackingCode}.");
})->middleware(['auth', 'role:user'])->name('user.applications.store');

Route::post('/dashboard/user/appointments', function (Request $request) {
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:160'],
        'appointment_date' => ['required', 'date', 'after_or_equal:today'],
        'appointment_time' => ['required', 'date_format:H:i'],
        'mode' => ['required', 'in:office,online,phone'],
        'note' => ['nullable', 'string', 'max:1000'],
        'file_application_id' => ['nullable', 'exists:file_applications,id'],
    ]);

    $user = $request->user();

    if (! empty($validated['file_application_id'])) {
        $ownsFile = DB::table('file_applications')
            ->where('id', $validated['file_application_id'])
            ->where('user_id', $user->id)
            ->exists();

        abort_unless($ownsFile, 403);
    }

    $isBooked = DB::table('appointments')
        ->where('appointment_date', $validated['appointment_date'])
        ->where('appointment_time', $validated['appointment_time'])
        ->where('mode', $validated['mode'])
        ->whereNotIn('status', ['rejected', 'cancelled'])
        ->exists();

    if ($isBooked) {
        return back()
            ->withInput()
            ->withErrors(['appointment_time' => 'Jadwal pada tanggal, jam, dan mode tersebut sudah terisi. Silakan pilih waktu lain.']);
    }

    DB::table('appointments')->insert([
        'file_application_id' => $validated['file_application_id'] ?? null,
        'user_id' => $user->id,
        'title' => $validated['title'],
        'appointment_date' => $validated['appointment_date'],
        'appointment_time' => $validated['appointment_time'],
        'mode' => $validated['mode'],
        'status' => 'requested',
        'feedback' => $validated['note'] ?? null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()
        ->route('user.dashboard', ['open' => 'appointments'])
        ->with('appointment_saved', 'Permintaan jadwal berhasil dikirim dan menunggu konfirmasi admin.');
})->middleware(['auth', 'role:user'])->name('user.appointments.store');

Route::get('/dashboard/admin', function () {
    $admin = request()->user();

    $applications = DB::table('file_applications')
        ->leftJoin('users', 'users.id', '=', 'file_applications.user_id')
        ->leftJoin('office_services', 'office_services.id', '=', 'file_applications.office_service_id')
        ->select('file_applications.*', 'users.name as client_name', 'office_services.name as service_name')
        ->latest('file_applications.created_at')
        ->get();

    $documentRequests = DB::table('document_requests')
        ->join('file_applications', 'file_applications.id', '=', 'document_requests.file_application_id')
        ->leftJoin('users', 'users.id', '=', 'file_applications.user_id')
        ->select('document_requests.*', 'file_applications.file_number', 'users.name as client_name')
        ->latest('document_requests.created_at')
        ->get();

    $appointments = DB::table('appointments')
        ->leftJoin('users', 'users.id', '=', 'appointments.user_id')
        ->leftJoin('file_applications', 'file_applications.id', '=', 'appointments.file_application_id')
        ->select('appointments.*', 'users.name as client_name', 'file_applications.file_number')
        ->orderBy('appointments.appointment_date')
        ->orderBy('appointments.appointment_time')
        ->get();

    $today = now()->toDateString();
    $attendance = DB::table('staff_attendances')
        ->where('user_id', $admin->id)
        ->whereDate('attendance_date', $today)
        ->first();

    $attendanceToday = DB::table('staff_attendances')
        ->whereDate('attendance_date', $today)
        ->get();

    $activeStaffCount = DB::table('users')
        ->whereIn('role', ['admin', 'staff'])
        ->where('status', 'active')
        ->count();

    $stats = [
        'totalTransactions' => $applications->count(),
        'inProcess' => $applications->where('status', 'in_process')->count(),
        'pendingSchedules' => $appointments->where('status', 'requested')->count(),
        'waitingDocuments' => $documentRequests->where('status', 'requested')->count(),
        'late' => $applications
            ->filter(fn ($application) => in_array($application->status, ['submitted', 'in_process', 'waiting_document'], true)
                && $application->updated_at
                && \Carbon\Carbon::parse($application->updated_at)->lt(now()->subDays(7)))
            ->count(),
        'staffPresentToday' => $attendanceToday->where('status', 'present')->count(),
        'activeStaff' => $activeStaffCount,
    ];

    $lateApplications = $applications
        ->filter(fn ($application) => in_array($application->status, ['submitted', 'in_process', 'waiting_document'], true)
            && $application->updated_at
            && \Carbon\Carbon::parse($application->updated_at)->lt(now()->subDays(7)))
        ->values();

    $weekStart = now()->startOfWeek();
    $weekEnd = now()->endOfWeek();
    $approvedThisWeek = $appointments->filter(fn ($appointment) => $appointment->status === 'approved'
        && \Carbon\Carbon::parse($appointment->appointment_date)->between($weekStart, $weekEnd));

    $deedNumberings = DB::table('deed_numberings')
        ->leftJoin('users', 'users.id', '=', 'deed_numberings.created_by')
        ->select('deed_numberings.*', 'users.name as staff_name')
        ->whereYear('deed_numberings.deed_date', now()->year)
        ->whereMonth('deed_numberings.deed_date', now()->month)
        ->latest('deed_numberings.deed_date')
        ->latest('deed_numberings.created_at')
        ->get();

    $deedStats = [
        'monthTotal' => $deedNumberings->count(),
        'notary' => $deedNumberings->where('category', 'notary')->count(),
        'protest' => $deedNumberings->where('category', 'protest')->count(),
        'legalization' => $deedNumberings->where('category', 'legalization')->count(),
        'waarmerking' => $deedNumberings->where('category', 'waarmerking')->count(),
        'ppat' => $deedNumberings->where('category', 'ppat')->count(),
    ];

    $alerts = collect()
        ->merge($lateApplications->map(fn ($application) => [
            'type' => 'late',
            'title' => $application->file_number,
            'description' => ($application->client_name ?? '-') . ' · ' . ($application->service_name ?? 'Layanan belum dipilih'),
        ]))
        ->merge($appointments->where('status', 'requested')->map(fn ($appointment) => [
            'type' => 'schedule',
            'title' => $appointment->title,
            'description' => ($appointment->client_name ?? '-') . ' · ' . \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('d F Y'),
        ]))
        ->merge($documentRequests->where('status', 'requested')->map(fn ($document) => [
            'type' => 'documents',
            'title' => $document->file_number,
            'description' => $document->document_name . ' · ' . ($document->client_name ?? '-'),
        ]))
        ->take(10)
        ->values();

    return view('admin.dashboard', compact(
        'admin',
        'applications',
        'documentRequests',
        'appointments',
        'attendance',
        'attendanceToday',
        'approvedThisWeek',
        'weekStart',
        'weekEnd',
        'deedNumberings',
        'deedStats',
        'stats',
        'lateApplications',
        'alerts'
    ));
})->middleware(['auth', 'role:admin,staff'])->name('admin.dashboard');

Route::post('/dashboard/admin/deed-numberings', function (Request $request) {
    $validated = $request->validate([
        'category' => ['required', 'in:notary,protest,legalization,waarmerking,ppat'],
        'serial_number' => ['nullable', 'integer', 'min:1'],
        'deed_number' => ['required', 'string', 'max:80'],
        'monthly_number' => ['nullable', 'string', 'max:80'],
        'deed_title' => ['required', 'string', 'max:180'],
        'deed_date' => ['required', 'date'],
        'time_start' => ['nullable', 'date_format:H:i'],
        'time_end' => ['nullable', 'date_format:H:i', 'after_or_equal:time_start'],
        'party_primary' => ['required', 'string', 'max:180'],
        'party_secondary' => ['nullable', 'string', 'max:180'],
        'letter_date' => ['nullable', 'date'],
        'registered_date' => ['nullable', 'date'],
        'instrument_date' => ['nullable', 'date'],
        'due_date' => ['nullable', 'date'],
        'extra' => ['nullable', 'array'],
        'extra.*' => ['nullable', 'string', 'max:255'],
        'note' => ['nullable', 'string', 'max:1000'],
    ]);

    $normalizePartyLines = function ($value) {
        return collect(preg_split('/\R+/', (string) $value))
            ->map(fn ($line) => preg_replace('/^\s*\d+[\.\)]\s*/', '', $line))
            ->map(fn ($line) => trim(preg_replace('/\s+/', ' ', mb_strtolower((string) $line))))
            ->filter();
    };
    $normalizedParties = $normalizePartyLines($validated['party_primary'])
        ->merge($normalizePartyLines($validated['party_secondary'] ?? ''))
        ->filter()
        ->sort()
        ->implode(' | ');

    $sameNumber = DB::table('deed_numberings')
        ->where('category', $validated['category'])
        ->where('deed_number', $validated['deed_number'])
        ->whereYear('deed_date', \Carbon\Carbon::parse($validated['deed_date'])->year)
        ->exists();

    if ($sameNumber) {
        return back()
            ->withInput()
            ->withErrors(['deed_number' => 'Nomor akta sudah pernah dipakai pada kategori dan tahun yang sama. Mohon cek ulang agar tidak terjadi penomoran ganda.']);
    }

    $similarParties = DB::table('deed_numberings')
        ->where('normalized_parties', $normalizedParties)
        ->whereYear('deed_date', \Carbon\Carbon::parse($validated['deed_date'])->year)
        ->latest('deed_date')
        ->first();

    DB::table('deed_numberings')->insert([
        'category' => $validated['category'],
        'serial_number' => $validated['serial_number'] ?? null,
        'deed_number' => $validated['deed_number'],
        'monthly_number' => $validated['monthly_number'] ?? null,
        'deed_title' => $validated['deed_title'],
        'deed_date' => $validated['deed_date'],
        'time_start' => $validated['time_start'] ?? null,
        'time_end' => $validated['time_end'] ?? null,
        'party_primary' => $validated['party_primary'],
        'party_secondary' => $validated['party_secondary'] ?? null,
        'letter_date' => $validated['letter_date'] ?? null,
        'registered_date' => $validated['registered_date'] ?? null,
        'instrument_date' => $validated['instrument_date'] ?? null,
        'due_date' => $validated['due_date'] ?? null,
        'normalized_parties' => $normalizedParties,
        'note' => $validated['note'] ?? null,
        'extra_data' => json_encode(collect($validated['extra'] ?? [])->filter(fn ($value) => filled($value))->all(), JSON_UNESCAPED_UNICODE),
        'created_by' => $request->user()->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $message = 'Penomoran akta berhasil disimpan.';
    if ($similarParties) {
        $message .= ' Perhatian: ditemukan nama pihak yang sama pada nomor ' . $similarParties->deed_number . ' tanggal ' . \Carbon\Carbon::parse($similarParties->deed_date)->translatedFormat('d F Y') . '.';
    }

    return redirect()
        ->route('admin.dashboard', ['open' => 'deed-numbering'])
        ->with('admin_message', $message);
})->middleware(['auth', 'role:admin,staff'])->name('admin.deed-numberings.store');

Route::post('/dashboard/admin/deed-numberings/check', function (Request $request) {
    $validated = $request->validate([
        'party_primary' => ['nullable', 'string', 'max:180'],
        'party_secondary' => ['nullable', 'string', 'max:180'],
        'deed_number' => ['nullable', 'string', 'max:80'],
        'category' => ['nullable', 'in:notary,protest,legalization,waarmerking,ppat'],
    ]);

    $normalizePartyLines = function ($value) {
        return collect(preg_split('/\R+/', (string) $value))
            ->map(fn ($line) => preg_replace('/^\s*\d+[\.\)]\s*/', '', $line))
            ->map(fn ($line) => trim(preg_replace('/\s+/', ' ', mb_strtolower((string) $line))))
            ->filter();
    };
    $parties = $normalizePartyLines($validated['party_primary'] ?? '')
        ->merge($normalizePartyLines($validated['party_secondary'] ?? ''))
        ->filter()
        ->sort()
        ->implode(' | ');

    $query = DB::table('deed_numberings')
        ->select('category', 'deed_number', 'deed_title', 'deed_date', 'party_primary', 'party_secondary')
        ->latest('deed_date')
        ->limit(6);

    if ($parties !== '') {
        $query->where('normalized_parties', $parties);
    } elseif (! empty($validated['deed_number'])) {
        $query->where('deed_number', $validated['deed_number']);
        if (! empty($validated['category'])) {
            $query->where('category', $validated['category']);
        }
    } else {
        return response()->json(['matches' => []]);
    }

    return response()->json(['matches' => $query->get()]);
})->middleware(['auth', 'role:admin,staff'])->name('admin.deed-numberings.check');

Route::post('/dashboard/admin/attendance', function (Request $request) {
    $user = $request->user();
    $today = now()->toDateString();
    $nowTime = now('Asia/Makassar')->format('H:i:s');

    DB::table('staff_attendances')->updateOrInsert(
        ['user_id' => $user->id, 'attendance_date' => $today],
        [
            'check_in_time' => $nowTime,
            'status' => now('Asia/Makassar')->format('H:i') > '09:00' ? 'late' : 'present',
            'note' => 'Absensi melalui dashboard admin/staf.',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()
        ->route('admin.dashboard')
        ->with('admin_message', 'Absensi hari ini berhasil disimpan.');
})->middleware(['auth', 'role:admin,staff'])->name('admin.attendance.store');

Route::post('/dashboard/admin/appointments/{appointment}/approve', function (Request $request, int $appointment) {
    DB::table('appointments')
        ->where('id', $appointment)
        ->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'feedback' => 'Jadwal disetujui oleh admin/staf.',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('admin.dashboard', ['open' => 'schedule'])
        ->with('admin_message', 'Jadwal berhasil di-ACC.');
})->middleware(['auth', 'role:admin,staff'])->name('admin.appointments.approve');

Route::post('/dashboard/admin/appointments/{appointment}/alternative', function (Request $request, int $appointment) {
    DB::table('appointments')
        ->where('id', $appointment)
        ->update([
            'status' => 'rescheduled',
            'approved_by' => $request->user()->id,
            'feedback' => 'Slot jadwal penuh. Mohon pilih waktu alternatif.',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('admin.dashboard', ['open' => 'schedule'])
        ->with('admin_message', 'Alternatif jadwal berhasil dikirim.');
})->middleware(['auth', 'role:admin,staff'])->name('admin.appointments.alternative');

Route::get('/dashboard/notaris', function () {
    $applications = DB::table('file_applications')
        ->leftJoin('users', 'users.id', '=', 'file_applications.user_id')
        ->leftJoin('office_services', 'office_services.id', '=', 'file_applications.office_service_id')
        ->select(
            'file_applications.*',
            'users.name as client_name',
            'office_services.name as service_name'
        )
        ->latest('file_applications.created_at')
        ->get();

    $documentRequests = DB::table('document_requests')
        ->join('file_applications', 'file_applications.id', '=', 'document_requests.file_application_id')
        ->leftJoin('users', 'users.id', '=', 'file_applications.user_id')
        ->select('document_requests.*', 'file_applications.file_number', 'users.name as client_name')
        ->latest('document_requests.created_at')
        ->get();

    $appointments = DB::table('appointments')
        ->leftJoin('users', 'users.id', '=', 'appointments.user_id')
        ->leftJoin('file_applications', 'file_applications.id', '=', 'appointments.file_application_id')
        ->select('appointments.*', 'users.name as client_name', 'file_applications.file_number')
        ->orderBy('appointments.appointment_date')
        ->orderBy('appointments.appointment_time')
        ->get();

    $staff = DB::table('users')
        ->whereIn('role', ['admin', 'staff'])
        ->orderBy('status')
        ->orderBy('name')
        ->get();

    $today = now()->toDateString();
    $attendanceToday = DB::table('staff_attendances')
        ->whereDate('attendance_date', $today)
        ->get();

    $stats = [
        'totalTransactions' => $applications->count(),
        'inProcess' => $applications->where('status', 'in_process')->count(),
        'pendingSchedules' => $appointments->where('status', 'requested')->count(),
        'waitingDocuments' => $documentRequests->where('status', 'requested')->count(),
        'staffPresentToday' => $attendanceToday->where('status', 'present')->count(),
        'pendingStaff' => $staff->where('status', 'pending')->count(),
        'activeStaff' => $staff->where('status', 'active')->count(),
    ];

    $lateApplications = $applications
        ->filter(fn ($application) => in_array($application->status, ['submitted', 'in_process', 'waiting_document'], true)
            && $application->updated_at
            && \Carbon\Carbon::parse($application->updated_at)->lt(now()->subDays(7)))
        ->values();

    $alerts = collect()
        ->merge($lateApplications->map(fn ($application) => [
            'type' => 'late',
            'title' => $application->file_number,
            'description' => ($application->client_name ?? '-') . ' · ' . ($application->service_name ?? 'Layanan belum dipilih'),
        ]))
        ->merge($appointments->where('status', 'requested')->map(fn ($appointment) => [
            'type' => 'schedule',
            'title' => $appointment->title,
            'description' => ($appointment->client_name ?? '-') . ' · ' . \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('d F Y'),
        ]))
        ->merge($documentRequests->where('status', 'requested')->map(fn ($document) => [
            'type' => 'documents',
            'title' => $document->file_number,
            'description' => $document->document_name . ' · ' . ($document->client_name ?? '-'),
        ]))
        ->take(10)
        ->values();

    return view('notaris.dashboard', compact(
        'applications',
        'documentRequests',
        'appointments',
        'staff',
        'attendanceToday',
        'stats',
        'lateApplications',
        'alerts'
    ));
})->middleware(['auth', 'role:notary'])->name('notaris.dashboard');

Route::post('/dashboard/notaris/appointments/{appointment}/approve', function (Request $request, int $appointment) {
    DB::table('appointments')
        ->where('id', $appointment)
        ->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'feedback' => 'Jadwal disetujui oleh notaris.',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('notaris.dashboard', ['open' => 'schedule'])
        ->with('schedule_message', 'Jadwal berhasil di-ACC.');
})->middleware(['auth', 'role:notary'])->name('notaris.appointments.approve');

Route::post('/dashboard/notaris/appointments/{appointment}/alternative', function (Request $request, int $appointment) {
    $validated = $request->validate([
        'feedback' => ['nullable', 'string', 'max:1000'],
    ]);

    DB::table('appointments')
        ->where('id', $appointment)
        ->update([
            'status' => 'rescheduled',
            'approved_by' => $request->user()->id,
            'feedback' => $validated['feedback'] ?: 'Slot jadwal penuh. Mohon pilih waktu alternatif.',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('notaris.dashboard', ['open' => 'schedule'])
        ->with('schedule_message', 'Alternatif jadwal berhasil dikirim.');
})->middleware(['auth', 'role:notary'])->name('notaris.appointments.alternative');

Route::post('/dashboard/notaris/staff/{staff}/approve', function (int $staff) {
    DB::table('users')
        ->where('id', $staff)
        ->where('role', 'staff')
        ->update([
            'status' => 'active',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('notaris.dashboard', ['open' => 'staff'])
        ->with('staff_message', 'Staf berhasil di-ACC dan sudah bisa login ke dashboard staf.');
})->middleware(['auth', 'role:notary'])->name('notaris.staff.approve');

Route::post('/dashboard/notaris/staff/{staff}/reject', function (int $staff) {
    DB::table('users')
        ->where('id', $staff)
        ->where('role', 'staff')
        ->where('status', 'pending')
        ->update([
            'status' => 'blocked',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('notaris.dashboard', ['open' => 'staff'])
        ->with('staff_message', 'Pendaftaran staf ditolak dan akun diblokir.');
})->middleware(['auth', 'role:notary'])->name('notaris.staff.reject');

Route::post('/dashboard/notaris/staff/{staff}/block', function (int $staff) {
    DB::table('users')
        ->where('id', $staff)
        ->whereIn('role', ['admin', 'staff'])
        ->update([
            'status' => 'resigned',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('notaris.dashboard', ['open' => 'staff'])
        ->with('staff_message', 'Akun staf sudah diblokir/resign.');
})->middleware(['auth', 'role:notary'])->name('notaris.staff.block');


Route::post('/contact-email', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:160'],
        'phone' => ['nullable', 'string', 'max:40'],
        'subject' => ['required', 'string', 'max:160'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    $recipient = env('OFFICE_EMAIL_TO', env('MAIL_FROM_ADDRESS', 'info@notarissuryamira.co.id'));

    Mail::raw(
        "Nama: {$validated['name']}\n" .
        "Email: {$validated['email']}\n" .
        "Telepon/WhatsApp: " . ($validated['phone'] ?? '-') . "\n" .
        "Subjek: {$validated['subject']}\n\n" .
        "Pesan:\n{$validated['message']}",
        function ($message) use ($validated, $recipient) {
            $message->to($recipient)
                ->replyTo($validated['email'], $validated['name'])
                ->subject('[Kontak Website] ' . $validated['subject']);
        }
    );

    return response()->json([
        'message' => 'Pesan berhasil dikirim. Kami akan menghubungi Anda kembali.',
    ]);
})->name('contact.email');

Route::get('/auth/{provider}/redirect', function (string $provider) {
    abort_unless(in_array($provider, ['google', 'apple', 'microsoft'], true), 404);

    return back()->with('auth_provider_notice', "Login {$provider} siap disambungkan dengan OAuth credential.");
})->name('auth.social.redirect');
Route::post('/register/check-notary', function () {
    return response()->json([
        'registered' => false,
        'message' => 'Akun notaris belum terdaftar. Registrasi dapat dilanjutkan.',
    ]);
})->name('register.check-notary');
