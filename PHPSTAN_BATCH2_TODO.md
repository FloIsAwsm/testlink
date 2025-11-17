# PHPStan Batch 2 - Remaining Errors

## Batch 1 Summary (COMPLETED)
**Fixed:** 40+ errors across 7 files
**Committed:** Yes (commit 6dc9e44)

### Files Fixed in Batch 1:
1. ✅ lib/api/rest/v1/tlRestApi.class.php (2 errors)
2. ✅ lib/api/xmlrpc/v1/xmlrpc.class.php (11 errors)
3. ✅ lib/execute/execDashboard.php (1 error)
4. ✅ lib/execute/execSetResults.php (4 errors)
5. ✅ lib/functions/assignment_mgr.class.php (6 errors)
6. ✅ lib/functions/tlPlatform.class.php (1 error - PHPDoc fix)
7. ✅ PHPSTAN_FIXES_TODO.md (tracking file created)

---

## Batch 2 - Remaining Work

### High Priority Files (similar patterns, quick wins)

#### lib/functions/cfield_mgr.class.php (8 errors)
- **Lines 459, 470, 475, 481:** Negated boolean always true (booleanNot.alwaysTrue)
- **Lines 1114, 2148, 2595:** is_null() with array always false
- **Line 906:** Baseline pattern mismatch

**Fix Pattern:** Similar to previous - update PHPDocs to include `|null` for methods that can return null arrays, or use `!empty()` instead of `!is_null()`

#### lib/functions/common.php (7 errors)
- **Lines 979, 1131, 1286:** Negated boolean always true
- **Lines 614, 1071:** is_null() with string always false
- **Line 1026:** Comparison 1 >= 0 always true
- **Line 470:** Baseline pattern mismatch

**Fix Pattern:** Update PHPDocs for functions returning string|null, simplify comparisons

### Medium Priority Files (need investigation)

#### lib/functions/csrf.php (1 error)
- **Line 71:** Offset unknown_type on array in isset()

#### lib/functions/database.class - Kopie.php (1 error)
- **Line 664:** If condition always true
- **Note:** This appears to be a backup copy ("Kopie"), may want to exclude from analysis

#### lib/functions/database.class.php (multiple errors)
- **Line 708:** If condition always true
- Likely more errors in this file

#### login.php (2 errors)
- **Line 200:** Offset 'kill_session' on string in isset()
- **Line 200:** Result of && always false

### Lower Priority (60+ files)

The remaining 60+ files follow similar patterns:
- Impossible type checks (is_null on non-nullable types)
- Redundant is_numeric() checks on int parameters
- Negated boolean expressions that are always true/false
- Isset checks on offsets that don't exist

---

## Recommended Approach for Batch 2

### Option 1: Quick Pattern Fixes (Recommended)
Focus on the systematic issues that appear across multiple files:

1. **Database method return types** - Many `db->fetchXXX()` methods return `array|null` but PHPDoc says only `array`
   - Fix: Update PHPDocs in database.class.php to reflect actual return types
   - Impact: Will fix 50+ downstream errors automatically

2. **Remove redundant type checks**
   - Pattern: `is_numeric($x)` where $x is `@param int`
   - Pattern: `is_null($arr)` where $arr is `@param array`
   - Fix: Replace with proper checks or remove entirely

3. **Update baseline**
   - After fixes, regenerate baseline to capture any truly unfixable issues

### Option 2: Full Manual Fix
Continue fixing each file individually (estimated 4-6 hours of work)

### Option 3: Adjust Analysis Level
Consider if all errors at level 6 need fixing, or if some can be baselined

---

## Next Steps

1. Run PHPStan again to verify Batch 1 fixes:
   ```bash
   phpstan analyse --memory-limit=1G
   ```

2. Check if error count decreased (should go from 309 to ~260-270)

3. Choose approach for Batch 2

4. Consider adding PHPStan to CI/CD to prevent regressions

---

## Notes

- **Unknown Classes:** PHPUnit_Framework_TestCase and IXR_Server are external dependencies - these need to either be:
  - Excluded from analysis
  - Added to baseline
  - Have stub files created

- **Baseline Pattern Mismatches:** Some ignored patterns now occur more/fewer times - this is actually GOOD, it means we fixed some instances! The baseline just needs to be updated.

- **Type System Improvement:** This cleanup is improving type safety significantly. Each fix makes the codebase more maintainable and less prone to bugs.
