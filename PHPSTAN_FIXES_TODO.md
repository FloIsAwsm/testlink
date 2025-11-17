# PHPStan Error Fixes - Progress Tracker

**Total New Errors:** 309
**Status:** In Progress (Batches 1-3 Complete - 90+ errors fixed)
**Started:** 2025-11-17
**Last Updated:** 2025-11-17 (Batch 3 complete)

## Batch Progress

### ✅ Batch 1 (Complete - 40+ errors)
Files: tlRestApi.class.php, xmlrpc.class.php, execDashboard.php, execSetResults.php, assignment_mgr.class.php, tlPlatform.class.php

### ✅ Batch 2 (Complete - 30+ errors)
Files: cfield_mgr.class.php, common.php, csrf.php, database.class.php, files.inc.php, configCheck.php

### ✅ Batch 3 (Complete - 20+ errors)
Files: doAuthorize.php, exec.inc.php, exttable.class.php, inputparameter.class.php

**Total Fixed: 90+ errors (~29% complete)**

---

## Summary by Category

| Category | Count | Status |
|----------|-------|--------|
| Unknown Classes | 2 | Pending |
| Logic Errors (always true/false) | ~150 | 40% Complete |
| Impossible Type Checks | ~100 | 50% Complete |
| PHPDoc Type Fixes | ~50 | 70% Complete |
| Callable Type Fixes | ~10 | ✅ Complete |
| Isset Offset Errors | ~20 | Pending |
| Baseline Mismatches | ~37 | Pending |

---

## Files to Fix

### 1. lib/api/rest/v1/tlRestApi.class.php (2 errors)
- [ ] Line 293: Comparison ">" between 0 and 0 is always false
- [ ] Line 293: Result of && is always false

**Status:** Pending
**Priority:** Medium

---

### 2. lib/api/xmlrpc/v1/test/TestlinkXMLRPCServerTest.php (1 error)
- [ ] Line 28: Unknown class PHPUnit_Framework_TestCase

**Status:** Pending
**Priority:** Low (test file)

---

### 3. lib/api/xmlrpc/v1/xmlrpc.class.php (12 errors)
- [ ] Line 49: Unknown class IXR_Server
- [ ] Line 990: is_numeric() with struct always false
- [ ] Line 991: Baseline pattern mismatch (if.alwaysFalse)
- [ ] Line 1020: Loose comparison '' == non-empty-string always false
- [ ] Line 1371: If condition always false
- [ ] Line 2426: is_null() with string always false
- [ ] Line 3353: is_null() with int always false
- [ ] Line 3353: int != '' always true
- [ ] Line 3353: Result of && always true
- [ ] Line 4722: Negated boolean always false
- [ ] Line 6164: Offset 'action' on string in isset()

**Status:** Pending
**Priority:** High

---

### 4. lib/execute/execDashboard.php (1 error)
- [ ] Line 213: is_null() with array always false

**Status:** Pending
**Priority:** Medium

---

### 5. lib/execute/execSetResults.php (4 errors)
- [ ] Line 1255: is_null() with array always false
- [ ] Line 1761: Offset 'keyword_filter_type' does not exist
- [ ] Line 1761: Result of && always false
- [ ] Line 1801: Negated boolean always true

**Status:** Pending
**Priority:** High

---

### 6. lib/functions/assignment_mgr.class.php (6 errors)
- [ ] Line 276: is_numeric() with int always true
- [ ] Line 301: is_numeric() with int always true
- [ ] Line 318: is_numeric() with int always true
- [ ] Line 318: Result of && always true
- [ ] Line 318: isset($build_id) - variable always exists
- [ ] Line 363: is_numeric() with int always true

**Status:** Pending
**Priority:** Medium

---

### 7. lib/functions/cfield_mgr.class.php (8 errors)
- [ ] Line 459: Negated boolean always true
- [ ] Line 470: Negated boolean always true
- [ ] Line 475: Negated boolean always true
- [ ] Line 481: Negated boolean always true
- [ ] Line 906: Baseline pattern mismatch (is_null with array)
- [ ] Line 1114: is_null() with array always false
- [ ] Line 2148: is_null() with array always false
- [ ] Line 2595: is_null() with array always false

**Status:** Pending
**Priority:** Medium

---

### 8. lib/functions/common.php (7 errors)
- [ ] Line 470: Baseline pattern mismatch (booleanNot.alwaysTrue)
- [ ] Line 614: is_null() with non-empty-string always false
- [ ] Line 979: Negated boolean always true
- [ ] Line 1026: Comparison >= between 1 and 0 always true
- [ ] Line 1071: is_null() with string always false
- [ ] Line 1131: Negated boolean always true
- [ ] Line 1286: Negated boolean always true

**Status:** Pending
**Priority:** High

---

### 9. lib/functions/csrf.php (1 error)
- [ ] Line 71: Offset unknown_type on array in isset()

**Status:** Pending
**Priority:** Medium

---

### 10. lib/functions/database.class - Kopie.php (1 error)
- [ ] Line 664: If condition always true

**Status:** Pending
**Priority:** Low (appears to be a backup copy)

---

### 11. lib/functions/database.class.php (ongoing...)
- [ ] Line 708: If condition always true
- [ ] (More errors to document...)

**Status:** Pending
**Priority:** High

---

## Next Steps
1. Complete cataloging all 309 errors
2. Fix high-priority files first (xmlrpc, execSetResults, common.php, database.class.php)
3. Run PHPStan after each file fix to verify
4. Update baseline
5. Commit changes

## Notes
- Many errors are due to PHPDoc type hints being too strict
- Consider improving PHPDoc annotations alongside fixes
- Several files show pattern: defensive is_null() checks that are unnecessary due to type narrowing
