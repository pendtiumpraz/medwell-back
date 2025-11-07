# MEDWELL - COMPLETE TESTING GUIDE
**Testing Documentation for All Roles & Features**  
**Date:** November 7, 2025  
**Version:** 1.0  
**Tester:** QA Team / Senior Developer

---

## 🎯 OVERVIEW

Dokumen ini berisi **checklist lengkap** untuk testing seluruh fitur MEDWELL di setiap role yang ada.

### Test Environment
```
URL: http://localhost:8000
Database: SQLite (backend_2/database/database.sqlite)
Password: password123 (untuk semua user)
```

### Test Users (From Seeder)
```
1. Super Admin
   Email: superadmin@medwell.id
   Password: password123
   
2. Admin (Organization)
   Email: admin@biofarma.co.id
   Password: password123
   
3. Clinician
   Email: sarah.cardio@biofarma.co.id
   Password: password123
   
4. Patient
   Email: john.doe@email.com
   Password: password123
```

---

## 🧪 PRE-TESTING SETUP

### 1. Fresh Database Reset
```bash
cd D:\AI\medwell\backend_2
php artisan migrate:fresh --seed
```

### 2. Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3. Start Server
```bash
php artisan serve
```

### 4. Open Browser
```
Navigate to: http://localhost:8000
```

---

## 🔐 AUTHENTICATION TESTING

### Test Case: AUTH-001 - Login
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/login` | Login page displays | ☐ Pass ☐ Fail |
| 2 | Leave email & password empty, click Login | Validation errors shown | ☐ Pass ☐ Fail |
| 3 | Enter wrong credentials | "Invalid credentials" error | ☐ Pass ☐ Fail |
| 4 | Enter correct credentials | Redirect to dashboard | ☐ Pass ☐ Fail |

### Test Case: AUTH-002 - Logout
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Login successfully | Dashboard displays | ☐ Pass ☐ Fail |
| 2 | Click user menu → Logout | Redirect to landing page | ☐ Pass ☐ Fail |
| 3 | Try to access `/dashboard` | Redirect to login | ☐ Pass ☐ Fail |

### Test Case: AUTH-003 - Role-Based Access
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Login as Patient | Can't access `/admin/*` routes | ☐ Pass ☐ Fail |
| 2 | Login as Clinician | Can't access `/admin/users` | ☐ Pass ☐ Fail |
| 3 | Login as Admin | Can access admin routes | ☐ Pass ☐ Fail |
| 4 | Login as Super Admin | Can access all routes | ☐ Pass ☐ Fail |

**Notes:**
```
[Record any issues found]




```

---

## 👑 SUPER ADMIN TESTING

### Login
```
Email: superadmin@medwell.id
Password: password123
```

### Test Case: SA-001 - Dashboard
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Login as Super Admin | Redirect to super-admin dashboard | ☐ Pass ☐ Fail |
| 2 | Check dashboard widgets | Stats display correctly | ☐ Pass ☐ Fail |
| 3 | Check sidebar menu | All menu items visible | ☐ Pass ☐ Fail |

### Test Case: SA-002 - Organizations CRUD

#### Create Organization (BROKEN - NO INDEX VIEW)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/super-admin/organizations` | Organizations index displays | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Click "Add Organization" | Modal sidebar opens | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Fill in form, submit | Organization created, modal closes | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ❌ Index view doesn't exist!

#### Edit Organization (BROKEN)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Edit" on organization | Modal sidebar opens with data | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Update fields, submit | Organization updated | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ❌ Index view doesn't exist!

### Test Case: SA-003 - Facilities CRUD

#### Create Facility (BROKEN - NO INDEX VIEW)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/super-admin/facilities` | Facilities index displays | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Click "Add Facility" | Modal sidebar opens | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Select organization | Dropdown works | ☐ Pass ☐ Fail ☐ N/A |
| 4 | Fill form, submit | Facility created | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ❌ Index view doesn't exist!

### Test Case: SA-004 - Departments CRUD

#### Create Department (BROKEN - NO INDEX VIEW)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/super-admin/departments` | Departments index displays | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Click "Add Department" | Modal sidebar opens | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Select facility | Dropdown works | ☐ Pass ☐ Fail ☐ N/A |
| 4 | Fill form, submit | Department created | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ❌ Index view doesn't exist!

### Test Case: SA-005 - Medications Master CRUD

#### Create Medication (BROKEN - NO INDEX VIEW)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/super-admin/medications` | Medications index displays | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Click "Add Medication" | Modal sidebar opens | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Fill medication details | Form validates | ☐ Pass ☐ Fail ☐ N/A |
| 4 | Submit form | Medication created | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ❌ Index view doesn't exist!

### Test Case: SA-006 - Users Management

#### View Users List
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/super-admin/users` | Users list displays | ☐ Pass ☐ Fail |
| 2 | Check filters (role, status) | Filters work | ☐ Pass ☐ Fail |
| 3 | Search by username/email | Search works | ☐ Pass ☐ Fail |
| 4 | Check pagination | Pagination works | ☐ Pass ☐ Fail |

#### Create User (SPLIT-SCREEN, NOT MODAL)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Create User" | Navigate to create page | ☐ Pass ☐ Fail |
| 2 | Fill all required fields | Form validates | ☐ Pass ☐ Fail |
| 3 | Select role & organization | Dropdowns work | ☐ Pass ☐ Fail |
| 4 | Submit form | User created, redirect to show | ☐ Pass ☐ Fail |

**Note:** ⚠️ Uses split-screen, NOT modal sidebar!

#### Edit User
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Edit" on user | Navigate to edit page | ☐ Pass ☐ Fail |
| 2 | Form pre-filled with data | Data loads correctly | ☐ Pass ☐ Fail |
| 3 | Update fields | Changes saved | ☐ Pass ☐ Fail |
| 4 | Change password (optional) | Password updates | ☐ Pass ☐ Fail |

#### Delete User (Soft Delete)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Delete" on user | Confirmation prompt | ☐ Pass ☐ Fail |
| 2 | Confirm deletion | User soft deleted | ☐ Pass ☐ Fail |
| 3 | Check "Show Deleted" filter | Deleted user visible | ☐ Pass ☐ Fail |
| 4 | Click "Restore" | User restored | ☐ Pass ☐ Fail |
| 5 | Click "Delete Forever" | Permanent deletion | ☐ Pass ☐ Fail |

### Test Case: SA-007 - Patients Management

#### View Patients List
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/super-admin/patients` | Patients list displays | ☐ Pass ☐ Fail |
| 2 | Check filters (onboarding, wearable) | Filters work | ☐ Pass ☐ Fail |
| 3 | Search by name/email | Search works | ☐ Pass ☐ Fail |
| 4 | Check patient cards/table | Data displays correctly | ☐ Pass ☐ Fail |

#### Create Patient (MODAL - BROKEN)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Add Patient" button | Modal sidebar opens | ☐ Pass ☐ Fail |
| 2 | Fill username, email, password | Validation works | ☐ Pass ☐ Fail |
| 3 | Fill personal info (name, DOB, gender) | Required fields validated | ☐ Pass ☐ Fail |
| 4 | Fill optional fields (height, weight, blood type) | Optional fields work | ☐ Pass ☐ Fail |
| 5 | Submit form | Patient created, modal closes | ☐ Pass ☐ Fail |
| 6 | Check success message | Toast/flash message shown | ☐ Pass ☐ Fail |

**Known Issue:** ⚠️ Modal exists but may not work properly!

#### Edit Patient (MODAL - BROKEN)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Edit" on patient | Modal sidebar opens | ☐ Pass ☐ Fail |
| 2 | Modal pre-filled with data | AJAX fetch works | ☐ Pass ☐ Fail |
| 3 | Update fields | Changes validated | ☐ Pass ☐ Fail |
| 4 | Submit form | Patient updated | ☐ Pass ☐ Fail |
| 5 | Modal closes, table refreshes | UI updates | ☐ Pass ☐ Fail |

**Known Issue:** ❌ AJAX endpoint returns Blade view, not JSON!

#### View Patient Details
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "View" on patient | Patient detail page opens | ☐ Pass ☐ Fail |
| 2 | Check patient info cards | All data displays | ☐ Pass ☐ Fail |
| 3 | Check vital signs table | Latest vitals shown | ☐ Pass ☐ Fail |
| 4 | Check medications | Active medications listed | ☐ Pass ☐ Fail |
| 5 | Check wearable data | Today's wearable data shown | ☐ Pass ☐ Fail |

#### Assign Clinician to Patient
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | On patient detail page | "Assign Clinician" button visible | ☐ Pass ☐ Fail |
| 2 | Click "Assign Clinician" | Modal/form opens | ☐ Pass ☐ Fail |
| 3 | Select clinician from dropdown | Dropdown populated | ☐ Pass ☐ Fail |
| 4 | Select role (primary/secondary) | Role options available | ☐ Pass ☐ Fail |
| 5 | Submit assignment | Clinician assigned | ☐ Pass ☐ Fail |

#### Remove Clinician from Patient
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | On patient detail page | Assigned clinicians listed | ☐ Pass ☐ Fail |
| 2 | Click "Remove" on clinician | Confirmation prompt | ☐ Pass ☐ Fail |
| 3 | Confirm removal | Clinician removed | ☐ Pass ☐ Fail |

### Test Case: SA-008 - Roles & Permissions

#### View Roles
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/super-admin/roles` | Roles list displays | ☐ Pass ☐ Fail |
| 2 | Check system roles | Predefined roles visible | ☐ Pass ☐ Fail |

#### Create Custom Role (SPLIT-SCREEN, NOT MODAL)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Create Role" | Navigate to create page | ☐ Pass ☐ Fail |
| 2 | Enter role name (slug) | Validation works | ☐ Pass ☐ Fail |
| 3 | Enter display name | Field works | ☐ Pass ☐ Fail |
| 4 | Select permissions | Checkboxes work | ☐ Pass ☐ Fail |
| 5 | Use "Toggle All" for group | Group toggle works | ☐ Pass ☐ Fail |
| 6 | Set hierarchy level | Dropdown works | ☐ Pass ☐ Fail |
| 7 | Submit form | Role created | ☐ Pass ☐ Fail |

**Note:** ⚠️ Uses split-screen, NOT modal!

#### Edit Role
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Edit" on role | Navigate to edit page | ☐ Pass ☐ Fail |
| 2 | Form pre-filled | Data loads correctly | ☐ Pass ☐ Fail |
| 3 | Update permissions | Changes save | ☐ Pass ☐ Fail |

#### Delete Role
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Delete" on custom role | Confirmation prompt | ☐ Pass ☐ Fail |
| 2 | Confirm deletion | Role deleted | ☐ Pass ☐ Fail |
| 3 | Try delete system role | Prevented/warning shown | ☐ Pass ☐ Fail |

**Notes:**
```
[Record any issues for Super Admin]




```

---

## 👨‍💼 ADMIN TESTING

### Login
```
Email: admin@biofarma.co.id
Password: password123
```

### Test Case: ADM-001 - Dashboard
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Login as Admin | Redirect to admin dashboard | ☐ Pass ☐ Fail |
| 2 | Check dashboard widgets | Organization stats shown | ☐ Pass ☐ Fail |
| 3 | Check menu items | Limited menu (no orgs/facilities) | ☐ Pass ☐ Fail |

### Test Case: ADM-002 - Patients Management
(Same as Super Admin SA-007, but organization-scoped)

| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/admin/patients` | Only see org patients | ☐ Pass ☐ Fail |
| 2 | Create patient | Patient assigned to admin's org | ☐ Pass ☐ Fail |
| 3 | Edit patient | Can edit own org patients | ☐ Pass ☐ Fail |
| 4 | Try edit other org patient | Access denied (if seeded) | ☐ Pass ☐ Fail |

### Test Case: ADM-003 - Users Management
(Limited compared to Super Admin)

| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/admin/users` | See only org users | ☐ Pass ☐ Fail |
| 2 | Create user | Limited roles dropdown | ☐ Pass ☐ Fail |
| 3 | Cannot create super_admin | Role not in dropdown | ☐ Pass ☐ Fail |
| 4 | Cannot access restore/force delete | Buttons not visible | ☐ Pass ☐ Fail |

### Test Case: ADM-004 - Access Restrictions
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Try access `/super-admin/organizations` | 403 Forbidden | ☐ Pass ☐ Fail |
| 2 | Try access `/super-admin/facilities` | 403 Forbidden | ☐ Pass ☐ Fail |
| 3 | Try access `/super-admin/medications` | 403 Forbidden | ☐ Pass ☐ Fail |

**Notes:**
```
[Record any issues for Admin]




```

---

## 👨‍⚕️ CLINICIAN TESTING

### Login
```
Email: sarah.cardio@biofarma.co.id
Password: password123
```

### Test Case: CLN-001 - Dashboard
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Login as Clinician | Redirect to clinician dashboard | ☐ Pass ☐ Fail |
| 2 | Check assigned patients widget | Assigned patients count | ☐ Pass ☐ Fail |
| 3 | Check health alerts widget | Unresolved alerts count | ☐ Pass ☐ Fail |
| 4 | Check menu items | Limited menu (patients, vitals, alerts) | ☐ Pass ☐ Fail |

### Test Case: CLN-002 - View Assigned Patients
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/clinician/patients` | See only assigned patients | ☐ Pass ☐ Fail |
| 2 | Check patient list | Patient cards/table display | ☐ Pass ☐ Fail |
| 3 | Search patients | Search works | ☐ Pass ☐ Fail |

### Test Case: CLN-003 - View Patient Details
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click on patient | Patient detail page opens | ☐ Pass ☐ Fail |
| 2 | Check vital signs | Latest vitals displayed | ☐ Pass ☐ Fail |
| 3 | Check medications | Active medications listed | ☐ Pass ☐ Fail |
| 4 | Check health summary | BMI, wellness score shown | ☐ Pass ☐ Fail |

### Test Case: CLN-004 - View Vital Signs
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to patient vitals | Vitals history displayed | ☐ Pass ☐ Fail |
| 2 | Check vitals trends | Charts/graphs shown | ☐ Pass ☐ Fail |
| 3 | Filter by date range | Filter works | ☐ Pass ☐ Fail |

### Test Case: CLN-005 - Prescribe Medication (INCOMPLETE)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to patient medications | Medications page opens | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Click "Prescribe Medication" | Form opens | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Search medication from master | Autocomplete works | ☐ Pass ☐ Fail ☐ N/A |
| 4 | Set dosage, frequency, duration | Form validates | ☐ Pass ☐ Fail ☐ N/A |
| 5 | Submit prescription | Prescription created | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ⚠️ Feature may be incomplete!

### Test Case: CLN-006 - Health Alerts (INCOMPLETE)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/clinician/alerts` | Alerts list displays | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Filter by priority (high, medium, low) | Filter works | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Click "Acknowledge" on alert | Alert marked acknowledged | ☐ Pass ☐ Fail ☐ N/A |
| 4 | Click "Resolve" on alert | Alert marked resolved | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ⚠️ Feature may be placeholder!

### Test Case: CLN-007 - Messages (PLACEHOLDER)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/clinician/messages` | Messages page opens | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Check inbox | Messages listed | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Click on patient conversation | Conversation opens | ☐ Pass ☐ Fail ☐ N/A |
| 4 | Send message | Message sent | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ❌ Likely placeholder only!

### Test Case: CLN-008 - Access Restrictions
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Try access `/admin/users` | 403 Forbidden | ☐ Pass ☐ Fail |
| 2 | Try access `/admin/patients` (create) | 403 Forbidden | ☐ Pass ☐ Fail |
| 3 | Try view unassigned patient | 403 or not in list | ☐ Pass ☐ Fail |

**Notes:**
```
[Record any issues for Clinician]




```

---

## 👤 PATIENT TESTING

### Login
```
Email: john.doe@email.com
Password: password123
```

### Test Case: PAT-001 - Dashboard
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Login as Patient | Redirect to patient dashboard | ☐ Pass ☐ Fail |
| 2 | Check wellness score widget | Wellness score displayed | ☐ Pass ☐ Fail |
| 3 | Check today's summary | Steps, heart rate, calories shown | ☐ Pass ☐ Fail |
| 4 | Check latest vitals | Recent BP, glucose shown | ☐ Pass ☐ Fail |
| 5 | Check today's medications | Medication schedule shown | ☐ Pass ☐ Fail |
| 6 | Pull to refresh (if implemented) | Data refreshes | ☐ Pass ☐ Fail ☐ N/A |

### Test Case: PAT-002 - Record Vital Signs

#### Blood Pressure
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/patient/vitals` | Vitals page opens | ☐ Pass ☐ Fail |
| 2 | Click "Record Blood Pressure" | Form/modal opens | ☐ Pass ☐ Fail |
| 3 | Enter systolic (e.g., 120) | Validation works | ☐ Pass ☐ Fail |
| 4 | Enter diastolic (e.g., 80) | Validation works | ☐ Pass ☐ Fail |
| 5 | Submit | BP recorded, status shown (normal/high) | ☐ Pass ☐ Fail |
| 6 | Check history | New entry visible | ☐ Pass ☐ Fail |

#### Blood Glucose
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Record Blood Glucose" | Form opens | ☐ Pass ☐ Fail |
| 2 | Enter glucose value (e.g., 95) | Validation works | ☐ Pass ☐ Fail |
| 3 | Select timing (fasting/after meal) | Dropdown works | ☐ Pass ☐ Fail |
| 4 | Submit | Glucose recorded, status shown | ☐ Pass ☐ Fail |

#### Temperature
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Record Temperature" | Form opens | ☐ Pass ☐ Fail |
| 2 | Enter temperature (e.g., 36.5) | Validation works | ☐ Pass ☐ Fail |
| 3 | Submit | Temperature recorded | ☐ Pass ☐ Fail |

#### SpO2 (Oxygen Saturation)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Record SpO2" | Form opens | ☐ Pass ☐ Fail |
| 2 | Enter SpO2 (e.g., 98) | Validation (50-100) works | ☐ Pass ☐ Fail |
| 3 | Submit | SpO2 recorded, status shown | ☐ Pass ☐ Fail |

#### Weight & BMI
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Record Weight" | Form opens | ☐ Pass ☐ Fail |
| 2 | Enter weight (e.g., 70 kg) | Validation works | ☐ Pass ☐ Fail |
| 3 | Submit | Weight recorded | ☐ Pass ☐ Fail |
| 4 | Check BMI calculation | BMI auto-calculated from height/weight | ☐ Pass ☐ Fail |
| 5 | Check BMI category | Normal/Overweight/Underweight shown | ☐ Pass ☐ Fail |

### Test Case: PAT-003 - View Vital Signs History
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to vitals page | History table displays | ☐ Pass ☐ Fail |
| 2 | Check latest 10 entries | Recent vitals shown | ☐ Pass ☐ Fail |
| 3 | Filter by type (BP, glucose, etc.) | Filter works | ☐ Pass ☐ Fail |
| 4 | Filter by date range | Date filter works | ☐ Pass ☐ Fail |
| 5 | Check trends chart | Line chart displays | ☐ Pass ☐ Fail |

### Test Case: PAT-004 - Medications Management

#### View Medications
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/patient/medications` | Medications page opens | ☐ Pass ☐ Fail |
| 2 | Check active medications | Prescribed meds listed | ☐ Pass ☐ Fail |
| 3 | Check pending consent | Meds awaiting consent shown | ☐ Pass ☐ Fail |

#### Accept Medication Consent
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | New prescription visible | "Accept/Decline" buttons shown | ☐ Pass ☐ Fail |
| 2 | Click "Accept" | Confirmation modal | ☐ Pass ☐ Fail |
| 3 | Confirm acceptance | Medication moved to active | ☐ Pass ☐ Fail |
| 4 | Medication schedule created | Schedule generated | ☐ Pass ☐ Fail |

#### View Medication Schedule
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to today's schedule | Today's meds displayed | ☐ Pass ☐ Fail |
| 2 | Check time slots (Morning, Afternoon, Evening, Night) | Organized by time | ☐ Pass ☐ Fail |
| 3 | Check medication details | Name, dosage, instructions shown | ☐ Pass ☐ Fail |

#### Mark Medication as Taken
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Mark as Taken" on scheduled med | Button changes state | ☐ Pass ☐ Fail |
| 2 | Timestamp recorded | Taken time logged | ☐ Pass ☐ Fail |
| 3 | Check adherence rate | Adherence % updates | ☐ Pass ☐ Fail |

#### Weekly Adherence Tracking
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | View adherence chart | Weekly adherence displayed | ☐ Pass ☐ Fail |
| 2 | Check adherence percentage | Calculation correct | ☐ Pass ☐ Fail |
| 3 | Check missed medications | Missed meds highlighted | ☐ Pass ☐ Fail |

### Test Case: PAT-005 - Wearable Devices

#### Connect Fitbit (UI Only - Backend Incomplete)
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/patient/wearables` | Wearables page opens | ☐ Pass ☐ Fail |
| 2 | Click "Connect Fitbit" | OAuth page opens | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Login to Fitbit, authorize | Callback successful | ☐ Pass ☐ Fail ☐ N/A |
| 4 | Token saved | Status shows "Connected" | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ⚠️ Backend OAuth may be incomplete!

#### Connect Huawei Health
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Connect Huawei" | OAuth page opens | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Login, authorize | Callback successful | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Token saved | Status shows "Connected" | ☐ Pass ☐ Fail ☐ N/A |

#### Sync Wearable Data
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Sync Now" | Loading indicator | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Data retrieved from API | Steps, heart rate, sleep updated | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Data saved to database | Visible in dashboard | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ⚠️ Sync API may be incomplete!

#### View Wearable Data
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Check today's data | Steps, heart rate, calories shown | ☐ Pass ☐ Fail |
| 2 | Check sync status | Last sync time displayed | ☐ Pass ☐ Fail |
| 3 | View historical data | Weekly/monthly charts | ☐ Pass ☐ Fail ☐ N/A |

#### Disconnect Wearable
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Disconnect" | Confirmation prompt | ☐ Pass ☐ Fail |
| 2 | Confirm disconnection | Token deleted, status "Not Connected" | ☐ Pass ☐ Fail |

### Test Case: PAT-006 - Profile Management

#### View Profile
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Navigate to `/patient/profile` | Profile page displays | ☐ Pass ☐ Fail |
| 2 | Check personal info | Name, DOB, gender shown | ☐ Pass ☐ Fail |
| 3 | Check health info | Height, weight, blood type shown | ☐ Pass ☐ Fail |
| 4 | Check avatar | Avatar displayed (or placeholder) | ☐ Pass ☐ Fail |

#### Edit Profile
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Edit Profile" | Edit form displays | ☐ Pass ☐ Fail |
| 2 | Update phone number | Validation works | ☐ Pass ☐ Fail |
| 3 | Update address | Text saved | ☐ Pass ☐ Fail |
| 4 | Update height/weight | Numbers validated | ☐ Pass ☐ Fail |
| 5 | Submit changes | Profile updated | ☐ Pass ☐ Fail |

#### Upload Avatar
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Upload Avatar" | File picker opens | ☐ Pass ☐ Fail ☐ N/A |
| 2 | Select image file | Preview shown | ☐ Pass ☐ Fail ☐ N/A |
| 3 | Upload image | Avatar updates | ☐ Pass ☐ Fail ☐ N/A |

**Known Issue:** ⚠️ May not be implemented!

#### Change Password
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Click "Change Password" | Form displays | ☐ Pass ☐ Fail |
| 2 | Enter current password | Validation works | ☐ Pass ☐ Fail |
| 3 | Enter new password (min 8 chars) | Validation works | ☐ Pass ☐ Fail |
| 4 | Confirm new password | Matching validation | ☐ Pass ☐ Fail |
| 5 | Submit | Password updated | ☐ Pass ☐ Fail |
| 6 | Try login with old password | Login fails | ☐ Pass ☐ Fail |
| 7 | Login with new password | Login succeeds | ☐ Pass ☐ Fail |

### Test Case: PAT-007 - Access Restrictions
| Step | Action | Expected Result | Status |
|------|--------|----------------|--------|
| 1 | Try access `/admin/patients` | 403 Forbidden | ☐ Pass ☐ Fail |
| 2 | Try access `/clinician/patients` | 403 Forbidden | ☐ Pass ☐ Fail |
| 3 | Try access another patient's data | 403 or redirect | ☐ Pass ☐ Fail |

**Notes:**
```
[Record any issues for Patient]




```

---

## 📊 SUMMARY REPORT

### Overall Test Results

| Role | Total Tests | Passed | Failed | N/A | Pass Rate |
|------|-------------|--------|--------|-----|-----------|
| Authentication | ___ | ___ | ___ | ___ | ___% |
| Super Admin | ___ | ___ | ___ | ___ | ___% |
| Admin | ___ | ___ | ___ | ___ | ___% |
| Clinician | ___ | ___ | ___ | ___ | ___% |
| Patient | ___ | ___ | ___ | ___ | ___% |
| **TOTAL** | ___ | ___ | ___ | ___ | ___% |

### Critical Issues Found
```
1. [Issue title]
   Severity: High/Medium/Low
   Description: 
   Steps to reproduce:
   Expected:
   Actual:

2. [Issue title]
   ...

```

### Known Issues (From Analysis)
```
✅ CONFIRMED ISSUES:
1. ❌ Patient modal sidebar - AJAX endpoint returns Blade view, not JSON
2. ❌ Organizations - No index view
3. ❌ Facilities - No index view
4. ❌ Departments - No index view
5. ❌ Medications - No index view
6. ⚠️ Users/Roles - Use split-screen, not modal sidebar
7. ⚠️ Wearable OAuth - May be incomplete
8. ⚠️ Clinician prescribe medication - Incomplete
9. ⚠️ Clinician health alerts - Placeholder
10. ⚠️ Messaging - Placeholder only

```

### Recommendations
```
Priority 1 (URGENT):
- Fix patient modal sidebar
- Create missing index views
- Convert all CRUD to modal sidebar

Priority 2 (HIGH):
- Complete wearable sync backend
- Complete clinician features
- Add toast notifications

Priority 3 (MEDIUM):
- Complete messaging system
- Add avatar upload
- Add real-time notifications

```

---

## 📝 TESTING NOTES

### Browser Compatibility
Test on:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Edge (latest)
- [ ] Safari (if Mac available)

### Responsive Testing
Test on:
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

### Performance Notes
```
Page Load Times:
- Dashboard: ___ ms
- Patient List: ___ ms
- Vital Signs: ___ ms
- Medications: ___ ms

Issues:
[List any performance issues]

```

---

**Testing Completed By:** ___________________  
**Date:** ___________________  
**Signature:** ___________________

---

**🎯 END OF TESTING GUIDE**

Gunakan dokumen ini untuk systematic testing seluruh aplikasi.
Semua issue yang ditemukan akan dicatat dan diprioritaskan untuk fixing.
