@extends('layouts.app')

@section('content')
<div class="bg-slate-50 border-b border-pulse-border py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-pulse-orange transition-colors">Home</a>
            <span>/</span>
            <span class="text-pulse-navy font-bold">Frequently Asked Questions</span>
        </nav>
    </div>
</div>

{{-- Header Banner --}}
<section class="bg-gradient-to-b from-white to-slate-50 border-b border-pulse-border py-10 sm:py-14 lg:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-pulse-teal/10 text-pulse-teal border border-pulse-teal/20 mb-3">
            <i class="fa-solid fa-circle-question mr-1.5 text-[11px]"></i> Help & Knowledge Base
        </span>
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-pulse-navy tracking-tight">
            Frequently Asked Questions
        </h1>
        <p class="mt-3 text-xs sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
            Instant answers to common customer questions regarding shopping, delivery timelines, Cash on Delivery, and return procedures in Pakistan.
        </p>
    </div>
</section>

{{-- Accordion Sections --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16 space-y-6 sm:space-y-10 w-full">

    @php $index = 0; @endphp
    @foreach($faqSections as $sectionTitle => $questions)
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-pulse-border p-4 sm:p-7 shadow-subtle">
            <div class="flex items-center space-x-3 border-b border-slate-100 pb-3 sm:pb-4 mb-4 sm:mb-6">
                <div class="w-8 h-8 rounded-xl bg-pulse-orange/10 text-pulse-orange flex items-center justify-center font-bold text-xs shrink-0">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <h2 class="text-sm sm:text-lg font-black text-pulse-navy">
                    {{ $sectionTitle }}
                </h2>
            </div>

            <div class="space-y-3">
                @foreach($questions as $faq)
                    @php
                        $index++;
                        $btnId = "faq-btn-{$index}";
                        $panelId = "faq-panel-{$index}";
                    @endphp
                    <div class="faq-item border border-slate-100 rounded-2xl overflow-hidden transition-colors hover:border-slate-200">
                        {{-- Accessible Button Toggle --}}
                        <button
                            type="button"
                            id="{{ $btnId }}"
                            aria-expanded="false"
                            aria-controls="{{ $panelId }}"
                            onclick="toggleFaqAccordion('{{ $btnId }}', '{{ $panelId }}')"
                            class="w-full flex items-center justify-between p-4 sm:p-5 text-left bg-slate-50/50 hover:bg-slate-50 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-pulse-orange"
                        >
                            <span class="text-xs sm:text-sm font-bold text-slate-800 pr-4">
                                {{ $faq['q'] }}
                            </span>
                            <span class="faq-chevron w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 shrink-0 transition-transform duration-200">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            </span>
                        </button>

                        {{-- Server-rendered Answer (Visible by default in HTML, toggled with hidden class) --}}
                        <div
                            id="{{ $panelId }}"
                            role="region"
                            aria-labelledby="{{ $btnId }}"
                            class="faq-panel hidden p-4 sm:p-5 pt-2 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-white"
                        >
                            <p>{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Still Have Questions Banner --}}
    <div class="bg-gradient-to-r from-pulse-navy to-pulse-navy-dark text-white rounded-3xl p-8 sm:p-10 shadow-card flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
        <div class="space-y-2">
            <h3 class="text-lg sm:text-xl font-black">Still have a question?</h3>
            <p class="text-xs text-slate-300 max-w-md leading-relaxed">
                Our customer support team is available on WhatsApp and phone to assist you with special requests, custom orders, or order status.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="https://wa.me/923328912706" target="_blank" rel="noopener" class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow-2xs flex items-center space-x-2">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>WhatsApp Helpline</span>
            </a>
            <a href="{{ route('contact') }}" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider transition-colors">
                Contact Form
            </a>
        </div>
    </div>

</div>

<script>
    function toggleFaqAccordion(btnId, panelId) {
        const btn = document.getElementById(btnId);
        const panel = document.getElementById(panelId);
        if (!btn || !panel) return;

        const isExpanded = btn.getAttribute('aria-expanded') === 'true';
        const chevron = btn.querySelector('.faq-chevron');

        if (isExpanded) {
            btn.setAttribute('aria-expanded', 'false');
            panel.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180', 'bg-pulse-orange', 'text-white', 'border-pulse-orange');
        } else {
            btn.setAttribute('aria-expanded', 'true');
            panel.classList.remove('hidden');
            if (chevron) {
                chevron.classList.add('rotate-180', 'bg-pulse-orange', 'text-white', 'border-pulse-orange');
                chevron.classList.remove('bg-white', 'text-slate-400');
            }
        }
    }
</script>
@endsection
