<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            Attendance Records
            <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary btn-sm">Add Attendance</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                <td>{{ $attendance->check_in ? date('H:i:s', strtotime($attendance->check_in)) : '-' }}</td>
                                <td>{{ $attendance->check_out ? date('H:i:s', strtotime($attendance->check_out)) : '-' }}</td>
                                <td>
                                    @php
                                        $badge = match($attendance->status) {
                                            'present' => 'bg-success',
                                            'late' => 'bg-warning text-dark',
                                            'absent' => 'bg-danger',
                                            'half-day' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($attendance->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.attendance.edit', $attendance) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.attendance.destroy', $attendance) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $attendances->links() }}
    </div>
</x-admin-layout>
