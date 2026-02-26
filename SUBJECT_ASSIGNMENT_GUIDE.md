# Student-Subject Assignment System

## Overview

A complete implementation of a many-to-many relationship between Students and Subjects in Laravel, with a fast, user-friendly UI for assigning core subjects in bulk and manually managing elective subjects.

## What's Been Implemented

### 1. Database Layer

**New Migration**: `database/migrations/2026_02_05_152432_create_subject_student_table.php`

- Creates a `subject_student` pivot table with:
  - `student_id` (foreign key to students)
  - `subject_id` (foreign key to subjects)
  - `unique(['student_id', 'subject_id'])` constraint (prevents duplicate assignments)
  - Timestamps for audit trail
  - `onDelete('cascade')` for data integrity

Run the migration:
```bash
php artisan migrate
```

### 2. Model Relationships

**Student Model** (`app/Models/Student.php`)
```php
public function subjects()
{
    return $this->belongsToMany(Subject::class, 'subject_student')
                ->withTimestamps();
}
```

**Subject Model** (`app/Models/Subject.php`)
```php
public function students()
{
    return $this->belongsToMany(Student::class, 'subject_student')
                ->withTimestamps();
}
```

### 3. Controller Logic

**New Controller**: `app/Http/Controllers/SubjectAssignmentController.php`

#### Methods:

**`syncCoreSubjects($request)`**
- Automatically assigns all 'core' subjects matching a student's level
- Uses `.sync()` to attach and detach in one transaction
- Example: Form 5 O-Level student → gets all O-Level core subjects
- Wrap in DB transaction for data consistency

**`attachElective($request)`**
- Manually adds a single elective subject to a student
- Validates:
  - Subject is marked as 'elective' category
  - Subject level matches student's school class level
- Uses `syncWithoutDetaching()` to preserve existing assignments

**`detachElective($request)`**
- Removes an elective subject from a student
- Called via AJAX when user clicks "Remove"

**`getAvailableElectives(Student $student)`**
- Returns JSON list of electives NOT yet assigned to the student
- Filtered by student's level
- Used to populate the modal dropdown

**`getAssignedElectives(Student $student)`**
- Returns JSON list of currently assigned elective subjects
- Used to display "remove" buttons in the modal

### 4. Routing

**New Routes** (`routes/web.php`):

```php
// Bulk assignment
Route::post('/students/assign-core-subjects', [SubjectAssignmentController::class, 'syncCoreSubjects'])
    ->name('subjects.syncCore');

// Individual elective management
Route::post('/students/attach-elective', [SubjectAssignmentController::class, 'attachElective'])
    ->name('subjects.attachElective');

Route::post('/students/detach-elective', [SubjectAssignmentController::class, 'detachElective'])
    ->name('subjects.detachElective');

// AJAX endpoints for modal data
Route::get('/students/{student}/available-electives', [SubjectAssignmentController::class, 'getAvailableElectives'])
    ->name('subjects.availableElectives');

Route::get('/students/{student}/assigned-electives', [SubjectAssignmentController::class, 'getAssignedElectives'])
    ->name('subjects.assignedElectives');
```

### 5. UI Components

**Updated Students Table** (`resources/views/students/students.blade.php`):

#### New Table Column: "Subjects"
- Displays assigned subjects as colored badges
- Shows "No subjects assigned" if empty
- Auto-updates when assignments change

#### New Action Buttons (per student row):

**"Core" Button** (Green)
```html
<form action="{{ route('subjects.syncCore') }}" method="POST">
    @csrf
    <input type="hidden" name="student_id" value="{{ $student->id }}">
    <button type="submit">Assign All Core Subjects</button>
</form>
```
- One-click bulk assignment
- Auto-fetches all core subjects for student's level

**"Electives" Button** (Orange)
```html
<button onclick="openElectiveModal({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}')">
    Manage Electives
</button>
```
- Opens modal dialog for selective elective management

#### Elective Assignment Modal

Features:
- **Two-panel design**:
  - Left: "Assigned Electives" with remove buttons
  - Right: "Available Electives" with add buttons
- **AJAX-powered**: 
  - Loads data on modal open via `fetch()`
  - Add/remove updates instantly without page reload
- **Real-time feedback**:
  - Toast notifications confirm actions
  - Modal content updates automatically

JavaScript Functions:
- `openElectiveModal(studentId, studentName)` - Opens modal
- `closeElectiveModal()` - Closes modal (Escape key also works)
- `loadElectives(studentId)` - Fetches and renders both lists
- `attachElective(studentId, subjectId)` - AJAX POST to add subject
- `detachElective(studentId, subjectId)` - AJAX POST to remove subject
- `showNotification(message, type)` - Toast notification

## Usage Workflow

### Assigning Core Subjects (Bulk)

1. View Students list (`/students`)
2. Click **"Core"** button on any student row
3. System automatically fetches all core subjects for that student's level
4. All are attached in one transaction
5. Success message appears
6. "Subjects" column updates instantly

### Managing Electives (Individual)

1. View Students list (`/students`)
2. Click **"Electives"** button on any student row
3. Modal opens showing:
   - **Currently Assigned**: Subjects student already has (with remove buttons)
   - **Available**: Electives not yet assigned (with add buttons)
4. Click **"Add"** or **"Remove"** as needed
5. Modal updates in real-time
6. Close modal or click outside to dismiss

## Technical Highlights

### Data Integrity
- Unique constraint prevents duplicate student-subject pairs
- Cascade delete removes assignments when student/subject deleted
- DB transactions ensure atomic operations

### Performance
- Eager loading: `Student::with('subjects')`
- Indexed foreign keys on pivot table
- JSON endpoints for efficient AJAX

### User Experience
- One-click core subject assignment saves time
- Modal modal for focused elective selection
- Toast notifications provide instant feedback
- Responsive design works on mobile

### Code Architecture
- Single responsibility: Controller has 5 focused methods
- RESTful routes (POST for state changes, GET for queries)
- No inline business logic in views
- Reusable JavaScript functions

## Testing the Implementation

### Manual Testing

```bash
# Run migrations
php artisan migrate

# Register a few students in different classes
# Create subjects with different categories (core/elective) and levels

# Visit /students
# Click "Core" button → should assign all core subjects for that level
# Click "Electives" button → should show available and assigned lists
# Add/remove electives → should update instantly
```

### Artisan Tinker (quick validation)

```php
php artisan tinker

$student = Student::first();
$student->load('subjects');
$student->subjects; // View assigned subjects

// Bulk assign core subjects
$coreSubjects = Subject::where('level', 'O-Level')->where('category', 'core')->pluck('id');
$student->subjects()->sync($coreSubjects);
```

## Common Scenarios

### Scenario 1: Student moves from O-Level to A-Level
```php
// Old subjects are kept (history preserved)
// To reset, first detach all:
$student->subjects()->detach();
// Then sync new level's core subjects:
$student->subjects()->sync($aLevelCores);
```

### Scenario 2: Elective subject is deactivated
```php
// Simply delete the subject
$subject->delete(); // Cascade detaches from all students
```

### Scenario 3: View all students taking a specific elective
```php
$subject = Subject::find($subjectId);
$students = $subject->students()->get();
```

## Future Enhancements

1. **Bulk Elective Assignment**: Select multiple students and assign same electives
2. **Export**: Download student-subject report as CSV/PDF
3. **Audit Log**: Track who assigned what and when
4. **Validation Rules**: Max electives per student (e.g., max 3)
5. **Subject Recommendations**: AI-powered suggestions based on performance
6. **Prerequisites**: Enforce subject dependencies (e.g., must take Physics before Advanced Physics)

## Files Modified/Created

### Created:
- `database/migrations/2026_02_05_152432_create_subject_student_table.php`
- `app/Http/Controllers/SubjectAssignmentController.php`

### Modified:
- `app/Models/Student.php` (added `subjects()` relationship)
- `app/Models/Subject.php` (added `students()` relationship)
- `routes/web.php` (added 5 new routes)
- `resources/views/students/students.blade.php` (added UI + modal + JS)

## Troubleshooting

**Issue**: "No subjects assigned" shows after clicking Core
- Check if subjects exist in database with matching level
- Verify subject category is 'core'

**Issue**: Modal doesn't open
- Check browser console for JavaScript errors
- Verify CSRF token meta tag exists in HTML head

**Issue**: Add/Remove buttons don't work
- Check Network tab in browser DevTools for AJAX errors
- Verify SubjectAssignmentController routes are imported

**Issue**: Cascade delete not working
- Ensure migration has `onDelete('cascade')`
- Run `php artisan migrate:fresh` if upgrading
