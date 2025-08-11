@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Risk Assessments
                </h5>
                <a href="{{ route('risk-assessments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>New Assessment
                </a>
            </div>
            <div class="card-body">
                @if($assessments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Risk Rating</th>
                                    <th>Risk Points</th>
                                    <th>Client Acceptance</th>
                                    <th>Assessment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assessments as $assessment)
                                <tr>
                                    <td>
                                        <strong>{{ $assessment->client_name }}</strong>
                                    </td>
                                    <td>
                                        @switch($assessment->overall_risk_rating)
                                            @case('Very High-risk')
                                                <span class="badge bg-danger">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @case('High-risk')
                                                <span class="badge bg-warning">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @case('Medium-risk')
                                                <span class="badge bg-info">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @case('Low-risk')
                                                <span class="badge bg-success">{{ $assessment->overall_risk_rating }}</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $assessment->overall_risk_rating ?? 'N/A' }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <strong>{{ $assessment->overall_risk_points }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $assessment->client_acceptance == 'Do not accept client' ? 'danger' : 'success' }}">
                                            {{ $assessment->client_acceptance }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $assessment->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('risk-assessments.show', $assessment) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('risk-assessments.edit', $assessment) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('risk-assessments.destroy', $assessment) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this assessment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $assessments->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No risk assessments found</h5>
                        <p class="text-muted">Start by creating your first risk assessment.</p>
                        <a href="{{ route('risk-assessments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create Assessment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 