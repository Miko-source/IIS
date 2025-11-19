@extends('layouts.app')

@section('content')
<div class="container">
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
                <div>
                                
                    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}"
                       class="btn btn-info btn-sm">
                        Aktivity
                    </a>


                <a href="{{ route('campaign.steps.edit', [$campaign->id, $step->id]) }}"
                   class="btn btn-sm btn-warning">
                    Upravit
                </a>
            </div>

            </li>
        @endforeach
    </ul>
</div>
@endsection
