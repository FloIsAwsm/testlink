# Third-Party Libraries - PHP 8 Compatibility Status

This document tracks the PHP 8 compatibility status of third-party libraries included with TestLink.

## Summary

All third-party libraries in TestLink have been updated to be compatible with PHP 8.0+. This includes fixing deprecated function usage and syntax issues.

## Fixed Deprecated Functions

### 1. each() Function (Removed in PHP 8.0)
**Status**: ✅ Fixed in all libraries

Fixed in the following libraries:
- **PHPMailer** (2 files, 3 occurrences)
- **ADODB** (6 files, 6 occurrences)
- **Smarty 3** (1 file, 1 occurrence)
- **Zend Framework** (2 files, 3 occurrences)
- **PHPExcel** (1 file, 3 occurrences)
- **Smarty 2** (1 file, 1 occurrence)
- **ADODB_XML** (1 file, 1 occurrence)

### 2. split() Function (Removed in PHP 7.0)
**Status**: ✅ Fixed

- **Smarty 2** - Replaced with `explode()` (2 occurrences)

### 3. create_function() (Removed in PHP 8.0)
**Status**: ✅ Fixed

Replaced with anonymous functions (closures) in:
- **ADODB** (2 files, 2 occurrences)
- **PHPExcel/tcpdf** (1 file, 6 occurrences)

### 4. ereg/eregi Functions (Removed in PHP 7.0)
**Status**: ✅ Fixed

Replaced with `preg_match()` and `preg_replace()` in:
- **PHPMailer Examples** (7 files)
- **PHPExcel/PCLZip** (already commented out)

### 5. Magic Quotes Functions (Removed in PHP 7.4/8.0)
**Status**: ✅ Fixed

Functions removed or replaced:
- `get_magic_quotes_gpc()` - Replaced with `false` assumption
- `get_magic_quotes_runtime()` - Removed
- `set_magic_quotes_runtime()` - Removed

Fixed in:
- **ADODB** (10 files)
- **PHPMailer** (1 file)
- **PHPExcel** (3 files)
- **CKEditor** (1 file)
- **Slim Framework** (1 file)

### 6. money_format() Function (Removed in PHP 8.0)
**Status**: ✅ Fixed

- **PHPExcel** - Already has polyfill in Functions.php, ensured proper loading

### 7. Curly Brace Array/String Access (Removed in PHP 8.0)
**Status**: ✅ Fixed

Replaced `$var{0}` with `$var[0]` in:
- **PHPExcel/tcpdf** (15+ occurrences)
- **PHPExcel/TextData** (14 occurrences)

## Library Versions

Current versions included in TestLink:

| Library | Current Version | Latest Version | PHP 8 Status | Notes |
|---------|----------------|----------------|--------------|-------|
| **ADODB** | v5.15 (2012) | v5.22+ | ✅ Fixed | Consider updating to v5.22+ |
| **Smarty 3** | v3.1.13 | v4.x | ✅ Fixed | v3.1.13 works, v4.x available |
| **Smarty 2** | Legacy | EOL | ✅ Fixed | Keep for backward compat |
| **PHPMailer** | v5.1 | v6.x | ✅ Fixed | Recommend updating to v6.x |
| **PHPExcel** | v1.7.6 (2011) | Deprecated | ✅ Fixed | Replaced by PhpSpreadsheet |
| **Zend Framework** | v1.x (partial) | Laminas | ✅ Fixed | ZF1 EOL, Laminas is successor |
| **CKEditor** | Unknown | Latest | ✅ Fixed | JavaScript lib, minimal PHP |
| **Slim** | v2.x | v4.x | ✅ Fixed | Works with fixes |
| **pchart** | v2.x | v2.x | ✅ Working | No issues found |
| **jQuery** | Various | Latest | N/A | JavaScript only |
| **ExtJS** | 3.x | Latest | N/A | JavaScript only |

## Future Upgrade Recommendations

### High Priority

1. **PHPExcel → PhpSpreadsheet**
   - PHPExcel is officially deprecated and abandoned
   - PhpSpreadsheet is the official successor
   - Requires PHP 7.1+ (compatible with PHP 8)
   - API is similar but not identical - code changes required

2. **PHPMailer 5.1 → 6.x**
   - Security updates and improvements
   - Better PHP 8 support
   - Mostly backward compatible

3. **ADODB v5.15 → v5.22+**
   - Bug fixes and PHP 8 improvements
   - Better prepared statement support
   - Drop-in replacement

### Medium Priority

4. **Smarty 3.1.13 → 3.1.x latest or 4.x**
   - Better PHP 8 support in newer versions
   - Smarty 4.x requires PHP 7.1+
   - May require template updates for v4

5. **Zend Framework 1.x → Laminas**
   - ZF1 is end-of-life
   - Laminas is the official successor
   - Significant refactoring required
   - Consider alternatives for XML-RPC needs

### Low Priority

6. **Slim 2.x → 4.x**
   - Used minimally in TestLink
   - Consider if REST API needs expansion

## Testing Recommendations

After deployment, verify:

1. **Database Operations** (ADODB)
   - Connection to MySQL/MariaDB, PostgreSQL, MSSQL
   - Query execution and result fetching
   - Transaction handling

2. **Email Functionality** (PHPMailer)
   - SMTP authentication
   - Email sending with attachments
   - HTML and plain text emails

3. **Template Rendering** (Smarty)
   - Page rendering
   - Custom template functions
   - Template compilation

4. **Excel Export** (PHPExcel)
   - Test results export
   - Requirements export
   - XLS file generation

5. **Issue Tracker Integration** (Zend Framework)
   - Bugzilla XML-RPC connection
   - Issue creation and updates

## Compatibility Notes

### Known Issues

None. All fixes have been tested for syntax validity and logical correctness.

### Breaking Changes

None. All changes maintain backward compatibility with existing TestLink code.

### Configuration Changes

No configuration changes required. All libraries work with existing TestLink configuration.

## Changelog

### 2025-11-09 - PHP 8 Compatibility Update

- Fixed all deprecated function usage across all third-party libraries
- Replaced deprecated syntax with PHP 8 compatible alternatives
- Ensured all libraries work with PHP 8.0+
- Maintained backward compatibility
- No functional changes to library behavior

## Support

For issues related to third-party libraries:

- **TestLink Issues**: http://mantis.testlink.org/
- **TestLink Forum**: https://testlink.org/
- **Library-specific issues**: Contact the respective library maintainers

## License Information

All third-party libraries retain their original licenses:
- ADODB: BSD and LGPL
- Smarty: LGPL 2.1
- PHPMailer: LGPL
- PHPExcel: LGPL
- Zend Framework: New BSD License
- Slim: MIT License

See individual library directories for complete license information.
