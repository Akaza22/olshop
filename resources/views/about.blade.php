@extends('layout.main')

@section('content')
<div class="bg-stone-50/50">
    <section class="relative py-20 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <span class="text-maroon font-bold uppercase tracking-[0.3em] text-xs mb-4 block">Our Story</span>
                <h1 class="text-5xl md:text-7xl font-serif font-bold text-stone-900 mb-8 leading-tight">
                    Redefining <br> <span class="italic text-maroon">Luxury Thrifting.</span>
                </h1>
                <p class="text-lg text-stone-600 leading-relaxed mb-10">
                    Thrift VTO was born from a vision to combine the uniqueness of vintage clothing with future technology. We believe that personal style shouldn't harm the planet, and the convenience of shopping shouldn't lose its sense of precision.
                </p>
            </div>
        </div>
        <div class="absolute top-0 right-0 -mr-20 mt-20 w-96 h-96 bg-maroon/5 rounded-full blur-3xl"></div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="aspect-[3/4] rounded-[3rem] overflow-hidden shadow-2xl border-8 border-stone-50">
                        <img src="/images/vto.jpg" 
                             alt="Virtual Try On Experience" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-10 -right-10 bg-maroon text-white p-8 rounded-[2rem] shadow-2xl max-w-[200px] hidden md:block">
                        <i data-lucide="scan-eye" class="w-10 h-10 mb-4 text-red-200"></i>
                        <p class="text-sm font-bold leading-tight uppercase tracking-wider">The First VTO Thrift in Indonesia</p>
                    </div>
                </div>
                <div class="space-y-8">
                    <h2 class="text-4xl font-serif font-bold text-stone-900 leading-tight">Digital Fitting Room in Your Hands</h2>
                    <p class="text-stone-600 leading-relaxed">
                        We understand the biggest challenge of shopping for second-hand clothes online: <span class="italic font-medium">"Will this fit my body?"</span>. 
                    </p>
                    <p class="text-stone-600 leading-relaxed">
                        With our **Virtual Try-On (VTO)** technology, you can visualize our curated collection directly on digital models. No more doubt, just satisfaction when the package arrives at your door.
                    </p>
                    <div class="grid grid-cols-2 gap-6 pt-4">
                        <div class="space-y-2">
                            <h4 class="font-bold text-stone-900 flex items-center gap-2">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-maroon"></i> Accuracy
                            </h4>
                            <p class="text-xs text-stone-500">Visualization of size that is close to reality.</p>
                        </div>
                        <div class="space-y-2">
                            <h4 class="font-bold text-stone-900 flex items-center gap-2">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-maroon"></i> Efficiency
                            </h4>
                            <p class="text-xs text-stone-500">Save time without needing to return items.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-stone-900 text-white relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <h2 class="text-4xl font-serif font-bold mb-4">Why Choose Us?</h2>
                <div class="h-1 w-20 bg-maroon mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="award" class="text-maroon w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold font-serif">Premium Curation</h3>
                    <p class="text-stone-400 text-sm leading-relaxed">Every piece of clothing goes through a strict selection process to ensure material quality and design authenticity.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="leaf" class="text-maroon w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold font-serif">Sustainable Fashion</h3>
                    <p class="text-stone-400 text-sm leading-relaxed">Supporting the slow fashion movement to reduce textile waste's impact on the environment.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="sparkles" class="text-maroon w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold font-serif">Unique Character</h3>
                    <p class="text-stone-400 text-sm leading-relaxed">Find clothing cuts that you won't find anywhere else in retail stores. Be different.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-stone-50">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-serif font-bold text-stone-900 mb-4">Visit Our Workshop</h2>
            <p class="text-stone-500 mb-12">Butik Address: Jl. Fashion Vintage No. 12, Bekasi Regency, West Java.</p>
            <div class="inline-flex flex-wrap justify-center gap-4">
                <a href="mailto:hello@thriftvto.com" class="flex items-center gap-2 px-8 py-4 bg-white border border-stone-200 rounded-2xl font-bold text-stone-700 hover:bg-stone-100 transition shadow-sm">
                    <i data-lucide="mail" class="w-5 h-5"></i> Email Us
                </a>
                <a href="https://wa.me/628123456789" class="flex items-center gap-2 px-8 py-4 bg-maroon text-white rounded-2xl font-bold hover:bg-red-900 transition shadow-xl shadow-maroon/20">
                    <i data-lucide="message-circle" class="w-5 h-5"></i> WhatsApp Admin
                </a>
            </div>
        </div>
    </section>
</div>
@endsection