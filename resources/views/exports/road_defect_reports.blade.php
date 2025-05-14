@php
    // Calculate the earliest and latest dates for the date range
    $minStartDate = $roadDefectReports->min('date');
    $maxEndDate = $roadDefectReports->max('date');

    $formattedDateRange = $minStartDate && $maxEndDate
        ? \Carbon\Carbon::parse($minStartDate)->format('F j, Y') . ' to ' . \Carbon\Carbon::parse($maxEndDate)->format('F j, Y')
        : 'No reports available';
@endphp

<table>
    <thead>
    <tr><th style="border: none; background-color: #FFFFFF; height: 15px" colspan="14"></th></tr>
    <tr><th style="border: none; background-color: #FFFFFF; height: 15px" colspan="14"></th></tr>
    <tr>
        <!-- Empty cell for column A -->
        <th style="border: none; background-color: #FFFFFF;" colspan="1"></th>
        <th style="border: none; background-color: #FFFFFF;" colspan="1"></th>
        <!-- Content starts in column B -->
        <th style="border: none; background-color: #FFFFFF; color: #252525; font-size: 8px; font-family: 'Arial', sans-serif; text-align: start; font-weight: normal;" colspan="12">
            REPUBLIC OF THE PHILIPPINES
        </th>
    </tr>
    <tr>
        <!-- Empty cell for column A -->
        <th style="border: none; background-color: #FFFFFF;" colspan="1"></th>
        <th style="border: none; background-color: #FFFFFF;" colspan="1"></th>
        <!-- Content starts in column B -->
        <th style="border: none; background-color: #FFFFFF; color: #252525; font-size: 8px; font-family: 'Arial', sans-serif; text-align: start; font-weight: normal;" colspan="12">
            PROVINCE OF DAVAO DEL NORTE
        </th>
    </tr>
    <tr>
        <!-- Empty cell for column A -->
        <th style="border: none; background-color: #FFFFFF;" colspan="1"></th>
        <th style="border: none; background-color: #FFFFFF;" colspan="1"></th>
        <!-- Content starts in column B -->
        <th style="border: none; background-color: #FFFFFF ; color: #16A34A; font-size: 16px; font-family: 'Arial', sans-serif; text-align: start; font-weight: bolder; " colspan="12">
            CITY OF TAGUM
        </th>
    </tr>
    <tr>
        <!-- Empty cell for column A -->
        <th style="border: none; background-color: #16A34A;" colspan="1"></th>
        <th style="border: none; background-color: #FFFFFF;" colspan="1"></th>
        <!-- Content starts in column B -->
        <th style="border: none; background-color: #16A34A ; color: #FFFFFF; font-size: 16px; font-family: 'Arial', sans-serif; text-align: start; font-weight: bolder; " colspan="12">
            CEO- MAINTENANCE DIVISION
        </th>
    </tr>
    <tr><th style="border: none; background-color: #FFFFFF; height: 15px" colspan="14"></th></tr>
    <tr><th style="border: none; background-color: #FFFFFF; height: 15px" colspan="14"></th></tr>
    <tr>
        <!-- Center the content -->
        <th style="border: none; background-color: #FFFFFF; color: #252525; font-size: 16px; font-family: 'Arial', sans-serif; text-align: center; font-weight: bold;" colspan="14">
            LIST OF ROAD DEFECT REPORTS IN TAGUM CITY
        </th>
    </tr>
    <tr>
        <!-- Center the content -->
        <th style="border: none; background-color: #FFFFFF; color: #454545; font-size: 11px; font-family: 'Arial', sans-serif; text-align: center; font-weight: bold;" colspan="14">
            As of {{ $formattedDateRange }}
        </th>
    </tr>
    <tr><th style="border: none; background-color: #FFFFFF; height: 15px" colspan="14"></th></tr>
    <tr>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 10px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: left; font-weight:bold ">No</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 90px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Report ID</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 120px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Type of Road Defect</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 160px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Location</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 120px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Barangay</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 120px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Date Reported</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 120px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Time Reported</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 100px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Road Status</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 100px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Report Count</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 80px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Level</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 160px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Updated Report By</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 120px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Date Updated</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 120px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Time Updated</th>
        <th style="border: 1px solid #FFFFFF; background-color: #000000; color: #FFFFFF; width: 120px; font-family: 'Arial', sans-serif; font-size: 10px; text-align: center; font-weight:bold ">Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($roadDefectReports as $report)
        <tr>
            <td style="border: 1px solid #101010; text-align: left;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid #101010; text-align: left;">{{ $report->id }}</td>
            <td style="border: 1px solid #101010; text-align: left;">{{ $report->defect }}</td>
            <td style="border: 1px solid #101010; text-align: left;">{{ $report->location }}</td>
            <td style="border: 1px solid #101010; text-align: left;">{{ $report->barangay }}</td>
            <td style="border: 1px solid #101010; text-align: left;">
                {{ $report->date ? \Carbon\Carbon::parse($report->date)->format('F j, Y') : 'N/A' }}
            </td>
            <td style="border: 1px solid #101010; text-align: left;">
                {{ $report->time_reported ? \Carbon\Carbon::parse($report->time_reported)->format('h:i A') : 'N/A' }}
            </td>
            <td style="border: 1px solid #101010; text-align: left;">{{ $report->status}}</td>
            <td style="border: 1px solid #101010; text-align: center;">{{ $report->report_count ?? 1 }}</td>
            <td style="border: 1px solid #101010; text-align: left;">{{ \App\Models\Severity::find($report->label)?->label ?? 'N/A' }}</td>
            <td style="border: 1px solid #101010; text-align: left;">{{ $report->updater?->first_name . ' ' . $report->updater?->last_name ?? 'N/A' }}</td>
            <td style="border: 1px solid #101010; text-align: left;">
                {{ $report->updated_at ? \Carbon\Carbon::parse($report->updated_at)->format('F j, Y') : 'N/A' }}
            </td>
            <td style="border: 1px solid #101010; text-align: left;">
                {{ $report->updated_at ? \Carbon\Carbon::parse($report->updated_at)->format('h:i A') : 'N/A' }}
            </td>
            <td style="border: 1px solid #101010; text-align: left;">
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
