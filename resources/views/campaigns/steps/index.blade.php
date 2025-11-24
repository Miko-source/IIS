@extends('layouts.app')

@section('content')
<div class="container">
     <div class="mb-3">
     @include('components.back-link', [
    'target' => route('topics.campaigns.show', [$campaign->topic, $campaign]),
    'label' => '← Zpět na detail kampaně'
])


    </div>
    <h1>Kroky kampaně: {{ $campaign->name }}</h1>

    <a href="{{ route('campaign.steps.create', $campaign->id) }}" class="btn btn-primary mb-3">
        Přidat nový krok
    </a>

    <ul class="list-group">
        @foreach($steps as $step)
            <li class="list-group-item d-flex justify-content-between align-items-center">

                <div>
                    <strong>{{ $step->order }}.</strong> 
                    
                    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}">
                        {{ $step->name }}
                    </a>
                </div>

                <div class="d-flex gap-2 align-items-center">

                    {{-- Upravit --}}
                    @include('components.edit-button', [
                        'href' => route('campaign.steps.show', [
                            $campaign->id,
                            $step->id,
                            'edit' => 1
                        ]),
                        'label' => 'Upravit',
                        'small' => true
                    ])

                    {{-- Smazat krok --}}
                    @include('components.delete-button', [
                        'action' => route('campaign.steps.destroy', [$campaign->id, $step->id]),
                        'label'  => 'Smazat',
                        'confirm' => 'Opravdu chcete tento krok smazat?',
                        'small' => true
                    ])

                </div>

            </li>
        @endforeach
    </ul>
</div>
@endsection
