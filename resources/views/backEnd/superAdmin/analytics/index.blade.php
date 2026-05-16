@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Analytics')

@section('content')
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title">School Growth (12 Months)</span>
            </div>
            <div style="padding: 20px 0;">
                @if(count($schoolGrowth) > 0)
                    <div style="display: flex; align-items: flex-end; gap: 4px; height: 150px;">
                        @php $maxVal = max($schoolGrowth) ?: 1; @endphp
                        @foreach($schoolGrowth as $month => $count)
                            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                                <div style="background: linear-gradient(to top, var(--usa-primary), var(--usa-secondary)); width: 100%; height: {{ ($count / $maxVal) * 120 }}px; border-radius: 4px 4px 0 0; min-height: 4px;"></div>
                                <span style="font-size: 9px; color: var(--usa-text-muted); transform: rotate(-45deg); white-space: nowrap;">{{ substr($month, 5) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; color: var(--usa-text-muted); padding: 40px;">No data available</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title">Student Enrollment (12 Months)</span>
            </div>
            <div style="padding: 20px 0;">
                @if(count($studentTrend) > 0)
                    <div style="display: flex; align-items: flex-end; gap: 4px; height: 150px;">
                        @php $maxStu = max($studentTrend) ?: 1; @endphp
                        @foreach($studentTrend as $month => $count)
                            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                                <div style="background: linear-gradient(to top, var(--usa-success), #34d399); width: 100%; height: {{ ($count / $maxStu) * 120 }}px; border-radius: 4px 4px 0 0; min-height: 4px;"></div>
                                <span style="font-size: 9px; color: var(--usa-text-muted); transform: rotate(-45deg); white-space: nowrap;">{{ substr($month, 5) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; color: var(--usa-text-muted); padding: 40px;">No data available</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">Top Schools by Student Count</span>
    </div>
    <table class="usa-table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>School</th>
                <th>Students</th>
                <th>Bar</th>
            </tr>
        </thead>
        <tbody>
            @php $maxTop = $topSchools->max('student_count') ?: 1; @endphp
            @foreach($topSchools as $index => $school)
                <tr>
                    <td style="font-weight: 700; color: var(--usa-primary);">#{{ $index + 1 }}</td>
                    <td style="color: var(--usa-text-primary); font-weight: 500;">{{ $school->school_name }}</td>
                    <td>{{ number_format($school->student_count) }}</td>
                    <td style="width: 40%;">
                        <div style="background: var(--usa-bg-dark); border-radius: 4px; height: 8px; overflow: hidden;">
                            <div style="background: linear-gradient(90deg, var(--usa-primary), var(--usa-secondary)); height: 100%; width: {{ ($school->student_count / $maxTop) * 100 }}%; border-radius: 4px;"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
