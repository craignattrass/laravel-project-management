# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Tab persistence feature: Forms now stay on the current tab after add/delete operations
- URL hash fragments to maintain tab state (`#tasks`, `#bugs`, `#flows`, etc.)
- JavaScript enhancement to read URL hash on page load and restore correct tab
- Detailed upgrade documentation in README.md

### Fixed
- Route method name mismatch between routes and controller
- Controller methods now follow Laravel RESTful conventions:
  - `createTask` → `storeTask`
  - `deleteTask` → `destroyTask`
  - `createModule` → `storeModule`
  - `deleteModule` → `destroyModule`
  - `createBug` → `storeBug`
  - `deleteBug` → `destroyBug`
  - `createFlow` → `storeFlow`
  - `deleteFlow` → `destroyFlow`
  - `scan` → `scanProject`
  - `assignModuleToEndpoint` → `updateEndpoint`

### Changed
- Redirects after CRUD operations now include tab fragments for better UX
- JavaScript `showTab()` function now updates URL hash

## Notes for AI Assistants

### Tab Persistence Implementation
When upgrading or maintaining this package:

1. **Controller Pattern**: All CRUD redirects should use:
   ```php
   return redirect()->route('project-management.index')
       ->with('success', 'Message')
       ->withFragment('tabname');
   ```

2. **Valid Tab Names**: `overview`, `modules`, `endpoints`, `commands`, `tasks`, `bugs`, `flows`

3. **JavaScript Pattern**: The view's JavaScript should:
   - Read `window.location.hash.substring(1)` on page load
   - Validate against valid tab names
   - Call `history.replaceState(null, null, '#' + tabName)` when switching tabs
   - Fall back to 'overview' if hash is invalid or missing

4. **Route Names**: Always use Laravel RESTful conventions:
   - `store*` for creating
   - `destroy*` for deleting
   - `update*` for updating
   - Never use `create*`, `delete*`, or custom names

5. **Hash Fragments Are Portable**: They work across all installations regardless of:
   - Domain/subdomain
   - Folder structure
   - Route prefix configuration
   
### Files Modified in This Update
- `src/Http/Controllers/ProjectManagementController.php` - Added `->withFragment()` to all redirects
- `resources/views/project-management/partials/content.blade.php` - Updated JavaScript for hash handling
- `routes/web.php` - Fixed method names to match controller
- `README.md` - Added upgrade instructions
- `CHANGELOG.md` - This file created

### Testing Checklist
- [ ] Create task → stays on Tasks tab
- [ ] Delete task → stays on Tasks tab
- [ ] Create bug → stays on Bugs tab
- [ ] Delete bug → stays on Bugs tab
- [ ] Create flow → stays on Flows tab
- [ ] Delete flow → stays on Flows tab
- [ ] Create module → stays on Modules tab
- [ ] Refresh page with hash in URL → correct tab loads
- [ ] Manual tab switching → hash updates in URL
- [ ] Works on different route prefixes
- [ ] Works on different domains/folders
