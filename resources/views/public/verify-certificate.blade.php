<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification | QuonixAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .quonix-gradient { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-indigo-100 antialiased min-h-screen flex flex-col">
    
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-24 w-64 h-64 bg-fuchsia-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-black tracking-tight mb-2">Certificate Verification</h1>
                <p class="text-slate-500 font-medium">Verify the authenticity of digital credentials issued by QuonixAI Partners.</p>
            </div>

            <div class="glass rounded-[3rem] p-10 shadow-2xl shadow-indigo-500/5">
                <form action="{{ route('certificates.public_verify') }}" method="GET" class="mb-10">
                    <div class="relative">
                        <input type="text" name="number" value="{{ $searchNumber }}" placeholder="Enter Certificate Number (e.g. CERT-2026-0001)"
                            class="w-full bg-slate-50 border-transparent rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all shadow-inner">
                        <button type="submit" class="absolute right-2 top-2 bottom-2 px-8 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg">
                            Verify
                        </button>
                    </div>
                </form>

                @if($certificate)
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="flex items-center gap-6 p-6 bg-emerald-50 rounded-3xl border border-emerald-100">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl">
                                ✅
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-emerald-800 tracking-tight">Verified Authentic</h3>
                                <p class="text-sm text-emerald-600 font-medium">This certificate was officially issued by {{ $certificate->institute->name }}.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-4">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Recipient Name</p>
                                <p class="text-lg font-black text-slate-800">{{ $certificate->student->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Issued By</p>
                                <p class="text-lg font-black text-slate-800">{{ $certificate->institute->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Issue Date</p>
                                <p class="text-lg font-black text-slate-800">{{ $certificate->issued_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Certificate Number</p>
                                <p class="text-lg font-black text-slate-800">{{ $certificate->certificate_number }}</p>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-slate-100 flex justify-center">
                            <a href="{{ route('student.portfolio', ['student' => $certificate->student->id]) }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">
                                View Student Portfolio
                            </a>
                        </div>
                    </div>
                @elseif($error)
                    <div class="p-8 bg-rose-50 rounded-3xl border border-rose-100 text-center animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="text-4xl mb-4">❌</div>
                        <h3 class="text-lg font-black text-rose-800 mb-2">Verification Failed</h3>
                        <p class="text-sm text-rose-600 font-medium">{{ $error }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <footer class="py-12 text-center text-slate-400">
        <p class="text-[10px] font-black uppercase tracking-[0.4em]">Powered by QuonixAI Ecosystem</p>
    </footer>

</body>
</html>
