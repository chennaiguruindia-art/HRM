<x-admin-layout>
    <x-slot name="header">Edit Attendance Record</x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.attendance.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $attendance->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" class="form-control @error('date') is-invalid @enderror">
                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="check_in" class="form-label">Check In</label>
                        <input type="datetime-local" name="check_in" id="check_in" value="{{ old('check_in', $attendance->check_in ? date('Y-m-d\TH:i', strtotime($attendance->check_in)) : '') }}" class="form-control @error('check_in') is-invalid @enderror">
                        @error('check_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="check_out" class="form-label">Check Out</label>
                        <input type="datetime-local" name="check_out" id="check_out" value="{{ old('check_out', $attendance->check_out ? date('Y-m-d\TH:i', strtotime($attendance->check_out)) : '') }}" class="form-control @error('check_out') is-invalid @enderror">
                        @error('check_out') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="present" {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>Late</option>
                        <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="half-day" {{ old('status', $attendance->status) === 'half-day' ? 'selected' : '' }}>Half Day</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $attendance->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
