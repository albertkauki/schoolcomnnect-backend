# Student-Subject Assignment System - Quick Reference

## What You Get

### 🎯 Features Implemented

1. **Bulk Core Subject Assignment**
   - One-click assignment of all core subjects matching student's level
   - Automatic subject filtering by level (O-Level vs A-Level)
   - Transaction-protected to ensure data integrity

2. **Manual Elective Management**
   - Modal dialog for managing elective subjects per student
   - Real-time add/remove without page reload
   - Prevents duplicate assignments
   - Level validation

3. **Data Visualization**
   - Subject list in student table showing all assigned subjects
   - Color-coded "Core" and "Electives" action buttons
   - Toast notifications for user feedback

---

## 📁 What Was Added/Modified

### NEW FILES

```
database/migrations/2026_02_05_152432_create_subject_student_table.php
```
- Many-to-many pivot table
- Unique constraint + cascade delete
- Ready to run: `php artisan migrate`

```
app/Http/Controllers/SubjectAssignmentController.php
```
- 5 methods handling all business logic
- Proper validation and error handling
- DB transactions for safety

---

### MODIFIED FILES

**app/Models/Student.php**
```php
// Added:
public function subjects()
{
    return $this->belongsToMany(Subject::class, 'subject_student')
                ->withTimestamps();
}
```

**app/Models/Subject.php**
```php
// Added:
public function students()
{
    return $this->belongsToMany(Student::class, 'subject_student')
                ->withTimestamps();
}
```

**routes/web.php**
```php
// Added 5 routes:
Route::post('/students/assign-core-subjects', [SubjectAssignmentController::class, 'syncCoreSubjects'])->name('subjects.syncCore');
Route::post('/students/attach-elective', [SubjectAssignmentController::class, 'attachElective'])->name('subjects.attachElective');
Route::post('/students/detach-elective', [SubjectAssignmentController::class, 'detachElective'])->name('subjects.detachElective');
Route::get('/students/{student}/available-electives', [SubjectAssignmentController::class, 'getAvailableElectives'])->name('subjects.availableElectives');
Route::get('/students/{student}/assigned-electives', [SubjectAssignmentController::class, 'getAssignedElectives'])->name('subjects.assignedElectives');
```

**resources/views/students/students.blade.php**
```
- Added "Subjects" column in table showing assigned subjects
- Added "Core" button for bulk assignment
- Added "Electives" button opening modal
- Added full modal component with two-panel UI
- Added 100+ lines of JavaScript for modal functionality
- Added CSRF token meta tag
```

---

## 🚀 How to Use

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Visit Students Page
```
http://localhost:8000/students
```

### Step 3: Assign Subjects

**Option A - Bulk Assign (Core Subjects)**
1. Find a student row
2. Click **"Core"** button (green)
3. All core subjects for that student's level are assigned instantly

**Option B - Manual Assign (Electives)**
1. Find a student row
2. Click **"Electives"** button (orange)
3. Modal opens with two sections:
   - **Currently Assigned**: Shows electives already added (remove option)
   - **Available**: Shows electives not yet assigned (add option)
4. Click **"Add"** or **"Remove"** as needed
5. Modal updates in real-time
6. Close modal when done

---

## 🎨 UI Overview

### Students Table (Updated)

```
┌─────────────────────────────────────────────────────────────────┐
│ Student Name | Reg No. | Class | Level | Subjects | Actions    │
├─────────────────────────────────────────────────────────────────┤
│ John Doe     │ ARC/... │ Form5 │ O-Lvl │ [Physics│ [Core] [Elec│
│              │         │   PCM │       │  Chemistry│ tics] [👁]│
│              │         │       │       │  Biology]│            │
└─────────────────────────────────────────────────────────────────┘
```

### Elective Modal

```
┌─────────────────────────────────┐
│ Manage Electives for John Doe   │ [✕]
├─────────────────────────────────┤
│                                 │
│ Currently Assigned Electives:   │
│ ┌─────────────────────────────┐ │
│ │ French         [Remove]     │ │
│ │ German         [Remove]     │ │
│ └─────────────────────────────┘ │
│                                 │
│ Available Electives:            │
│ ┌─────────────────────────────┐ │
│ │ Spanish        [Add]        │ │
│ │ Kiswahili      [Add]        │ │
│ └─────────────────────────────┘ │
│                                 │
├─────────────────────────────────┤
│                           [Close]│
└─────────────────────────────────┘
```

---

## 🔍 Behind the Scenes

### Database Structure
```sql
-- Pivot table (auto-created by migration)
CREATE TABLE subject_student (
    id BIGINT PRIMARY KEY,
    student_id BIGINT FOREIGN KEY,
    subject_id BIGINT FOREIGN KEY,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE (student_id, subject_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);
```

### API Endpoints
```
POST   /students/assign-core-subjects
       Body: { student_id: 1 }
       → Syncs all core subjects to student

POST   /students/attach-elective
       Body: { student_id: 1, subject_id: 5 }
       → Adds elective to student

POST   /students/detach-elective
       Body: { student_id: 1, subject_id: 5 }
       → Removes elective from student

GET    /students/{id}/available-electives
       → Returns JSON of unassigned electives

GET    /students/{id}/assigned-electives
       → Returns JSON of electives student has
```

---

## ✅ Validation & Safety

✓ **Duplicate Prevention**: Unique constraint on pivot table  
✓ **Level Matching**: Electives validated against student's level  
✓ **Category Verification**: Only electives can be manually added  
✓ **Transaction Protection**: Core assignment is atomic operation  
✓ **Cascade Delete**: Assignments removed when student/subject deleted  
✓ **CSRF Protection**: All POST endpoints protected  

---

## 🧪 Verify Installation

### Check Database
```bash
php artisan tinker

# Verify pivot table exists
Schema::hasTable('subject_student'); // true

# Check relationships
$student = Student::first();
$student->subjects; // Show subjects
```

### Check Routes
```bash
php artisan route:list | grep subject
```

### Check Frontend
1. Go to `/students`
2. Look for green "Core" button and orange "Electives" button
3. Click either to test functionality

---

## 📚 Documentation Files

- `SUBJECT_ASSIGNMENT_GUIDE.md` - Comprehensive technical guide
- `IMPLEMENTATION_CHECKLIST.md` - Detailed checklist with test scenarios

---

## 🆘 Troubleshooting

| Problem | Solution |
|---------|----------|
| "No subjects assigned" always | Check if subjects exist; verify category='core' |
| Modal won't open | Check browser console; verify JavaScript loaded |
| Add/Remove buttons inactive | Verify routes were imported; check CSRF token |
| "Level mismatch" error | Ensure subject level matches student's class level |
| No data in table | Run `php artisan migrate` first |

---

## 📊 Next Steps

1. ✅ Run migration: `php artisan migrate`
2. ✅ Visit `/students` page
3. ✅ Test "Core" button
4. ✅ Test "Electives" modal
5. ✅ Create more subjects and test
6. 📖 Read `SUBJECT_ASSIGNMENT_GUIDE.md` for advanced usage
