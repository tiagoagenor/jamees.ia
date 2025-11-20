@props(['currentStep' => 1, 'totalSteps' => 4])

@php
    $steps = [
        1 => ['label' => 'Selecionar Período', 'icon' => 'calendar'],
        2 => ['label' => 'Configurar', 'icon' => 'cog'],
        3 => ['label' => 'Aplicativos', 'icon' => 'apps'],
        4 => ['label' => 'Pagamento', 'icon' => 'credit-card'],
    ];
@endphp

<div class="mb-8">
    <div class="flex items-center justify-center">
        <div class="flex items-center w-full max-w-4xl">
            @foreach($steps as $stepNumber => $stepData)
                <div class="flex items-center {{ $stepNumber < $totalSteps ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        @if($stepNumber < $currentStep)
                            <!-- Step Completo -->
                            <div class="w-12 h-12 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold shadow-lg transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="mt-2 text-xs font-medium text-green-600">{{ $stepData['label'] }}</span>
                        @elseif($stepNumber === $currentStep)
                            <!-- Step Atual -->
                            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold shadow-lg ring-4 ring-blue-200 transition-all duration-300">
                                {{ $stepNumber }}
                            </div>
                            <span class="mt-2 text-xs font-medium text-blue-600">{{ $stepData['label'] }}</span>
                        @else
                            <!-- Step Futuro -->
                            <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-semibold transition-all duration-300">
                                {{ $stepNumber }}
                            </div>
                            <span class="mt-2 text-xs font-medium text-gray-400">{{ $stepData['label'] }}</span>
                        @endif
                    </div>
                    @if($stepNumber < $totalSteps)
                        <div class="flex-1 h-1 mx-3 transition-all duration-300 {{ $stepNumber < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>




