@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Client Risk Assessments
                </h5>
                <a href="{{ route('client-risk.create') }}" class="btn btn-primary">
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
                                    <th>Client Type</th>
                                    <th>Selected Risks</th>
                                    <th>Total Points</th>
                                    <th>Risk Rating</th>
                                    <th>Acceptance</th>
                                    <th>Monitoring</th>
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
                                        @if($assessment->client_type)
                                            <span class="badge bg-info">{{ $assessment->client_type }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assessment->selected_risk_ids)
                                            <div class="small">
                                                @foreach(array_slice($assessment->selected_risk_ids, 0, 3) as $riskId)
                                                    <span class="badge bg-secondary me-1">{{ $riskId }}</span>
                                                @endforeach
                                                @if(count($assessment->selected_risk_ids) > 3)
                                                    <span class="text-muted">+{{ count($assessment->selected_risk_ids) - 3 }} more</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $assessment->total_points }}</span>
                                    </td>
                                    <td>
                                        @if($assessment->overall_risk_rating)
                                            @php
                                                $colorClass = $assessment->getRiskColorClass();
                                            @endphp
                                            <span class="badge bg-{{ $colorClass }}">{{ $assessment->overall_risk_rating }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assessment->client_acceptance)
                                            @if($assessment->isClientAcceptable())
                                                <span class="badge bg-success">{{ $assessment->client_acceptance }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $assessment->client_acceptance }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assessment->monitoring_frequency)
                                            <span class="text-primary">{{ $assessment->monitoring_frequency }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $assessment->getFormattedAssessmentDate() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('client-risk.show', $assessment) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('client-risk.edit', $assessment) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('client-risk.destroy', $assessment) }}" 
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
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No client risk assessments found</h5>
                        <p class="text-muted">Start by creating your first client risk assessment.</p>
                        <a href="{{ route('client-risk.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create First Assessment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 